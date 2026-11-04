<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    protected $fillable = [
        'material_id',
        'order',
        'type',
        'question_type',
        'title',
        'instruction',
        'starter_code',
        'xp_reward',
        'checker_file',
    ];

    protected $casts = [
        'xp_reward' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the material that owns this question
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the module through material
     */
    public function module()
    {
        return $this->material->module();
    }

    /**
     * Get all progress records for this question
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get user's progress for this question
     */
    public function userProgress(User $user): ?UserProgress
    {
        return UserProgress::where('user_id', $user->id)
            ->where('question_id', $this->id)
            ->first();
    }

    /**
     * Check if user has completed this question
     */
    public function isCompletedBy(User $user): bool
    {
        $progress = $this->userProgress($user);
        return $progress && $progress->completed_at !== null;
    }

    /**
     * Check if this question is unlocked for user
     */
    public function isUnlockedFor(User $user): bool
    {
        // Check if parent material is unlocked
        if (!$this->material->isUnlockedFor($user)) {
            return false;
        }

        // First question in material is always unlocked (if material unlocked)
        if ($this->order === 1) {
            return true;
        }

        // Check if previous question in same material is completed
        $previousQuestion = self::where('material_id', $this->material_id)
            ->where('order', $this->order - 1)
            ->first();

        return $previousQuestion ? $previousQuestion->isCompletedBy($user) : false;
    }

    /**
     * Get next question
     */
    public function nextQuestion(): ?Question
    {
        // Try next question in same material
        $nextInMaterial = self::where('material_id', $this->material_id)
            ->where('order', $this->order + 1)
            ->first();

        if ($nextInMaterial) {
            return $nextInMaterial;
        }

        // Try first question in next material
        $nextMaterial = Material::where('module_id', $this->material->module_id)
            ->where('order', $this->material->order + 1)
            ->first();

        if ($nextMaterial) {
            return $nextMaterial->questions()->where('order', 1)->first();
        }

        // Try first question in next module
        $nextModule = Module::where('order', $this->material->module->order + 1)->first();
        
        if ($nextModule) {
            $firstMaterial = $nextModule->materials()->where('order', 1)->first();
            return $firstMaterial ? $firstMaterial->questions()->where('order', 1)->first() : null;
        }

        return null;
    }

    /**
     * Check if this is a special type question
     */
    public function isBugHunt(): bool
    {
        return $this->type === 'bug_hunt';
    }

    public function isClone(): bool
    {
        return $this->type === 'clone';
    }

    public function isTimeMode(): bool
    {
        return $this->type === 'time_mode';
    }

    public function isReguler(): bool
    {
        return $this->type === 'reguler';
    }
}
