<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'requirement_type',
        'requirement_value',
    ];

    protected $casts = [
        'requirement_value' => 'integer',
    ];

    /**
     * Get the module for module-based badges
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'requirement_value');
    }

    /**
     * Get all users who have this badge
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Check if user meets requirements for this badge
     */
    public function isEarnedBy(User $user): bool
    {
        return $user->badges()->where('badges.id', $this->id)->exists();
    }

    /**
     * Check if user meets requirements to earn this badge
     */
    public function canBeEarnedBy(User $user): bool
    {
        if ($this->isEarnedBy($user)) {
            return false; // Already earned
        }

        switch ($this->requirement_type) {
            case 'complete_module':
                $module = Module::find($this->requirement_value);
                return $module ? $module->isCompletedBy($user) : false;

            case 'complete_all':
                // Check if all modules are completed
                $totalModules = Module::count();
                $completedModules = Module::all()->filter(function ($module) use ($user) {
                    return $module->isCompletedBy($user);
                })->count();
                
                return $totalModules > 0 && $completedModules === $totalModules;

            default:
                return false;
        }
    }

    /**
     * Award this badge to user
     */
    public function awardTo(User $user): void
    {
        if (!$this->isEarnedBy($user) && $this->canBeEarnedBy($user)) {
            $user->badges()->attach($this->id, ['earned_at' => now()]);
        }
    }
}
