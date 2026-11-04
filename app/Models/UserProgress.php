<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'question_id',
        'completed_at',
        'code_submitted',
        'attempts',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'attempts' => 'integer',
    ];

    /**
     * Get the user that owns this progress
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question for this progress
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Check if this question is completed
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Mark as completed and award XP
     */
    public function markAsCompleted(): void
    {
        if ($this->isCompleted()) {
            return; // Already completed, no XP awarded twice
        }

        $this->completed_at = now();
        $this->save();

        // Check for badges
        $this->user->checkAndAwardBadges();
        
        // Check for costume unlocks
        $this->user->checkAndUnlockCostumes();
    }

    /**
     * Increment attempts
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Save code (autosave)
     */
    public function saveCode(string $code): void
    {
        $this->code_submitted = $code;
        $this->save();
    }
}
