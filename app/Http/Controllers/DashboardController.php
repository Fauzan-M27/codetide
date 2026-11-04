<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Log activity
        $user->logActivity('login');

        // Get modules with progress
        $modules = Module::with(['materials.questions'])
            ->orderBy('order')
            ->get()
            ->map(function ($module) use ($user) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'badge_name' => $module->badge_name,
                    'badge_icon' => $module->badge_icon,
                    'is_unlocked' => $module->isUnlockedFor($user),
                    'is_completed' => $module->isCompletedBy($user),
                    'completion_percentage' => $module->getCompletionPercentage($user),
                    'total_questions' => $module->questions()->count(),
                ];
            });

        // Get user stats
        $stats = [
            'total_xp' => $user->getTotalXP(),
            'completed_questions' => $user->getCompletedQuestionsCount(),
            'total_questions' => $user->getTotalQuestionsCount(),
            'completion_percentage' => $user->getCompletionPercentage(),
            'badges_count' => $user->badges()->count(),
            'rank' => $user->getRank(),
        ];

        // Get recent badges
        $recentBadges = $user->badges()
            ->orderByPivot('earned_at', 'desc')
            ->take(3)
            ->get();

        // Get active costume
        $activeCostume = $user->getActiveCostume();

        // Get next question
        $nextQuestion = $user->getNextUnlockedQuestion();

        // Top leaderboard — sorted by XP (computed in controller to avoid N+1 in view)
        $topUsers = \App\Models\User::all()
            ->map(fn ($u) => [
                'id'       => $u->id,
                'nickname' => $u->nickname,
                'xp'       => $u->getTotalXP(),
            ])
            ->sortByDesc('xp')
            ->take(5)
            ->values();

        return view('dashboard', compact(
            'modules',
            'stats',
            'recentBadges',
            'activeCostume',
            'nextQuestion',
            'topUsers'
        ));
    }
}
