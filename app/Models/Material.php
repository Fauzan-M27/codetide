<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'content',
        'order',
    ];

    /**
     * Get the module that owns this material
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get all questions for this material
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Check if user has completed this material
     */
    public function isCompletedBy(User $user): bool
    {
        $totalQuestions = $this->questions()->count();
        $completedQuestions = UserProgress::where('user_id', $user->id)
            ->whereIn('question_id', $this->questions()->pluck('id'))
            ->whereNotNull('completed_at')
            ->count();

        return $totalQuestions > 0 && $completedQuestions === $totalQuestions;
    }

    /**
     * Get completion percentage for user
     */
    public function getCompletionPercentage(User $user): float
    {
        $totalQuestions = $this->questions()->count();
        if ($totalQuestions === 0) {
            return 0;
        }

        $completedQuestions = UserProgress::where('user_id', $user->id)
            ->whereIn('question_id', $this->questions()->pluck('id'))
            ->whereNotNull('completed_at')
            ->count();

        return round(($completedQuestions / $totalQuestions) * 100, 2);
    }

    /**
     * Check if this material is unlocked for user
     */
    public function isUnlockedFor(User $user): bool
    {
        // Check if parent module is unlocked
        if (!$this->module->isUnlockedFor($user)) {
            return false;
        }

        // First material in module is always unlocked (if module unlocked)
        if ($this->order === 1) {
            return true;
        }

        // Check if previous material in same module is completed
        $previousMaterial = self::where('module_id', $this->module_id)
            ->where('order', $this->order - 1)
            ->first();

        return $previousMaterial ? $previousMaterial->isCompletedBy($user) : false;
    }
}
