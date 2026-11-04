<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    /**
     * Autosave code
     */
    public function autosave(Request $request, Question $question)
    {
        $user = $request->user();

        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        // Get or create progress
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'question_id' => $question->id,
            ],
            [
                'attempts' => 0,
            ]
        );

        // Save code
        $progress->saveCode($validated['code']);

        return response()->json([
            'success' => true,
            'message' => 'Kode tersimpan otomatis',
        ]);
    }

    /**
     * Check code (increment attempts)
     */
    public function check(Request $request, Question $question)
    {
        $user = $request->user();

        // Get or create progress
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'question_id' => $question->id,
            ],
            [
                'attempts' => 0,
            ]
        );

        // Increment attempts
        $progress->incrementAttempts();

        // Log activity
        $user->logActivity('check_code', $question->id, [
            'attempts' => $progress->attempts,
        ]);

        return response()->json([
            'success' => true,
            'attempts' => $progress->attempts,
        ]);
    }

    /**
     * Mark question as completed
     */
    public function complete(Request $request, Question $question)
    {
        $user = $request->user();

        // Check if question is unlocked
        if (!$question->isUnlockedFor($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Soal ini masih terkunci',
            ], 403);
        }

        // Get progress
        $progress = UserProgress::where('user_id', $user->id)
            ->where('question_id', $question->id)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Progress tidak ditemukan',
            ], 404);
        }

        // Check if already completed
        if ($progress->isCompleted()) {
            return response()->json([
                'success' => true,
                'message' => 'Soal sudah diselesaikan sebelumnya',
                'xp_awarded' => 0,
                'already_completed' => true,
            ]);
        }

        // Mark as completed (this will trigger badge/costume checks)
        $progress->markAsCompleted();

        // Log activity
        $user->logActivity('complete_question', $question->id, [
            'xp_awarded' => $question->xp_reward,
        ]);

        // Get newly earned badges
        $newBadges = $user->badges()
            ->wherePivot('earned_at', '>=', now()->subSeconds(5))
            ->get();

        // Get newly unlocked costumes
        $newCostumes = $user->costumes()
            ->wherePivot('unlocked_at', '>=', now()->subSeconds(5))
            ->get();

        // Get next question
        $nextQuestion = $question->nextQuestion();
        $nextQuestionUnlocked = $nextQuestion && $nextQuestion->isUnlockedFor($user);

        return response()->json([
            'success' => true,
            'message' => 'Selamat! Soal berhasil diselesaikan',
            'xp_awarded' => $question->xp_reward,
            'total_xp' => $user->getTotalXP(),
            'new_badges' => $newBadges->map(fn($badge) => [
                'name' => $badge->name,
                'icon' => $badge->icon,
            ]),
            'new_costumes' => $newCostumes->map(fn($costume) => [
                'name' => $costume->name,
                'icon' => $costume->icon,
            ]),
            'next_question' => $nextQuestion ? [
                'id' => $nextQuestion->id,
                'title' => $nextQuestion->title,
                'unlocked' => $nextQuestionUnlocked,
            ] : null,
        ]);
    }

    /**
     * Get user's overall progress
     */
    public function getProgress(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'total_xp' => $user->getTotalXP(),
            'completed_questions' => $user->getCompletedQuestionsCount(),
            'total_questions' => $user->getTotalQuestionsCount(),
            'completion_percentage' => $user->getCompletionPercentage(),
            'badges_count' => $user->badges()->count(),
            'rank' => $user->getRank(),
        ]);
    }
}
