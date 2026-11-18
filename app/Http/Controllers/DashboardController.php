<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Top leaderboard — hitung XP semua user dalam 1 query agregat (bukan N+1)
        $topUsers = \App\Models\User::select('users.id', 'users.nickname')
            ->leftJoin('user_progress', function ($join) {
                $join->on('user_progress.user_id', '=', 'users.id')
                     ->whereNotNull('user_progress.completed_at');
            })
            ->leftJoin('questions', 'user_progress.question_id', '=', 'questions.id')
            ->groupBy('users.id', 'users.nickname')
            ->selectRaw('users.id, users.nickname, COALESCE(SUM(questions.xp_reward), 0) as total_xp')
            ->orderByDesc('total_xp')
            ->take(5)
            ->get()
            ->map(fn($u) => [
                'id'       => $u->id,
                'nickname' => $u->nickname,
                'xp'       => (int) $u->total_xp,
            ]);

        // Tampilkan tutorial hanya jika user baru saja login/register (session flag).
        // session()->pull() membaca sekaligus menghapus flag, sehingga
        // navigasi balik ke dashboard tidak memunculkan tutorial lagi.
        $showTutorial = session()->pull('show_tutorial', false)
                        && !$user->tutorial_completed;

        return view('dashboard', compact(
            'modules',
            'stats',
            'recentBadges',
            'activeCostume',
            'nextQuestion',
            'topUsers',
            'showTutorial'
        ));
    }
}
