<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Display leaderboard
     */
    public function index(Request $request)
    {
        $currentUser = $request->user();

        // Get all users sorted by XP with pagination
        $topPlayers = User::withCount('badges')
            ->with(['costumes' => function ($query) {
                $query->wherePivot('is_active', true);
            }])
            ->get()
            ->sortByDesc(function ($user) {
                return $user->getTotalXP();
            })
            ->values();

        // Calculate user rank
        $userRank = $topPlayers->search(function ($user) use ($currentUser) {
            return $user->id === $currentUser->id;
        }) + 1;

        // Paginate manually
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedPlayers = new \Illuminate\Pagination\LengthAwarePaginator(
            $topPlayers->slice($offset, $perPage)->values(),
            $topPlayers->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get current user stats
        $userStats = [
            'total_xp' => $currentUser->getTotalXP(),
            'completed_questions' => $currentUser->progress()->whereNotNull('completed_at')->count(),
            'badges_earned' => $currentUser->badges()->count(),
            'average_score' => $currentUser->progress()->whereNotNull('completed_at')->count() > 0 
                ? round($currentUser->getTotalXP() / $currentUser->progress()->whereNotNull('completed_at')->count(), 1)
                : 0,
        ];

        // Get current user costume
        $currentUserCostume = $currentUser->costumes()->wherePivot('is_active', true)->first();

        // Total users
        $totalUsers = User::count();

        return view('leaderboard.index', [
            'topPlayers' => $paginatedPlayers,
            'userRank' => $userRank,
            'userStats' => $userStats,
            'currentUserCostume' => $currentUserCostume,
            'totalUsers' => $totalUsers,
        ]);
    }

    /**
     * Get leaderboard data as JSON
     */
    public function getData(Request $request)
    {
        $currentUser = $request->user();

        $users = User::all()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nickname' => $user->nickname,
                    'total_xp' => $user->getTotalXP(),
                    'completed_questions' => $user->getCompletedQuestionsCount(),
                    'badges_count' => $user->badges()->count(),
                    'active_costume' => $user->getActiveCostume() ? [
                        'name' => $user->getActiveCostume()->name,
                        'icon' => $user->getActiveCostume()->icon,
                    ] : null,
                ];
            })
            ->sortByDesc('total_xp')
            ->values()
            ->map(function ($userData, $index) {
                $userData['rank'] = $index + 1;
                return $userData;
            });

        return response()->json([
            'top_users' => $users->take(5),
            'current_user_rank' => $users->firstWhere('id', $currentUser->id),
        ]);
    }
}
