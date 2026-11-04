<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Module extends Model
{
    protected $fillable = [
        'title',
        'description',
        'order',
        'badge_name',
        'badge_icon',
    ];

    /**
     * Get all materials for this module
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->orderBy('order');
    }

    /**
     * Get the badge for this module
     */
    public function badge(): HasOne
    {
        return $this->hasOne(Badge::class, 'requirement_value')
            ->where('requirement_type', 'complete_module');
    }

    /**
     * Get all questions through materials
     */
    public function questions()
    {
        return $this->hasManyThrough(Question::class, Material::class)
            ->select('questions.*');
    }

    /**
     * Check if user has completed this module
     */
    public function isCompletedBy(User $user): bool
    {
        $totalQuestions = $this->questions()->count();
        $completedQuestions = UserProgress::where('user_id', $user->id)
            ->whereIn('question_id', $this->questions()->pluck('questions.id'))
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
            ->whereIn('question_id', $this->questions()->pluck('questions.id'))
            ->whereNotNull('completed_at')
            ->count();

        return round(($completedQuestions / $totalQuestions) * 100, 2);
    }

    /**
     * Check if this module is unlocked for user
     */
    public function isUnlockedFor(User $user): bool
    {
        // First module is always unlocked
        if ($this->order === 1) {
            return true;
        }

        // Check if previous module is completed
        $previousModule = self::where('order', $this->order - 1)->first();
        return $previousModule ? $previousModule->isCompletedBy($user) : false;
    }
}
