<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Costume extends Model
{
    protected $fillable = [
        'name',
        'character_name',
        'description',
        'icon',
        'avatar_icon',
        'unlock_type',
        'unlock_requirement',
        'sprite_idle',
        'sprite_typing',
        'sprite_thinking',
        'sprite_success',
        'sprite_error',
        'sprite_width',
        'sprite_height',
        'sprite_format',
    ];

    protected $casts = [
        'unlock_requirement' => 'integer',
    ];

    /**
     * Get all users who have unlocked this costume
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_costumes')
            ->withPivot('unlocked_at', 'is_active')
            ->withTimestamps();
    }

    /**
     * Check if user has unlocked this costume
     */
    public function isUnlockedBy(User $user): bool
    {
        return $user->costumes()->where('costumes.id', $this->id)->exists();
    }

    /**
     * Check if user can unlock this costume
     */
    public function canBeUnlockedBy(User $user): bool
    {
        if ($this->isUnlockedBy($user)) {
            return false; // Already unlocked
        }

        switch ($this->unlock_type) {
            case 'default':
                return true; // Default costumes should be unlocked for all users

            case 'badge':
                $badge = Badge::find($this->unlock_requirement);
                return $badge ? $badge->isEarnedBy($user) : false;

            case 'xp':
                return $user->getTotalXP() >= $this->unlock_requirement;

            case 'module':
                $module = Module::find($this->unlock_requirement);
                return $module ? $module->isCompletedBy($user) : false;

            default:
                return false;
        }
    }

    /**
     * Unlock this costume for user
     */
    public function unlockFor(User $user): void
    {
        if (!$this->isUnlockedBy($user) && $this->canBeUnlockedBy($user)) {
            $user->costumes()->attach($this->id, [
                'unlocked_at' => now(),
                'is_active' => false,
            ]);
        }
    }

    /**
     * Check if this is the active costume for user
     */
    public function isActiveFor(User $user): bool
    {
        $pivot = $user->costumes()->where('costumes.id', $this->id)->first();
        return $pivot ? $pivot->pivot->is_active : false;
    }

    /**
     * Get sprite path for specific animation state
     */
    public function getSprite(string $state = 'idle'): ?string
    {
        $field = "sprite_{$state}";
        
        if ($this->$field) {
            return asset('storage/' . $this->$field);
        }
        
        // Fallback to idle if specific state not available
        if ($state !== 'idle' && $this->sprite_idle) {
            return asset('storage/' . $this->sprite_idle);
        }
        
        return null;
    }

    /**
     * Get all available animation states
     */
    public function getAvailableAnimations(): array
    {
        $animations = [];
        $states = ['idle', 'typing', 'thinking', 'success', 'error'];
        
        foreach ($states as $state) {
            if ($this->{"sprite_{$state}"}) {
                $animations[$state] = $this->getSprite($state);
            }
        }
        
        return $animations;
    }
}
