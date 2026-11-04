<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'event',
        'question_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Get the user that owns this log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question for this log (if applicable)
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Log an activity
     */
    public static function logActivity(User $user, string $event, ?int $questionId = null, ?array $meta = null): void
    {
        self::create([
            'user_id' => $user->id,
            'event' => $event,
            'question_id' => $questionId,
            'meta' => $meta,
        ]);
    }

    /**
     * Get total duration for user
     */
    public static function getTotalDuration(User $user): int
    {
        return self::where('user_id', $user->id)
            ->whereNotNull('meta')
            ->get()
            ->sum(function ($log) {
                return $log->meta['duration'] ?? 0;
            });
    }

    /**
     * Get activity summary for user
     */
    public static function getSummary(User $user): array
    {
        $logs = self::where('user_id', $user->id)->get();

        return [
            'total_logins' => $logs->where('event', 'login')->count(),
            'total_questions_opened' => $logs->where('event', 'open_question')->count(),
            'total_checks' => $logs->where('event', 'check_code')->count(),
            'total_completed' => $logs->where('event', 'complete_question')->count(),
            'total_duration' => self::getTotalDuration($user),
            'last_activity' => $logs->sortByDesc('created_at')->first()?->created_at,
        ];
    }
}
