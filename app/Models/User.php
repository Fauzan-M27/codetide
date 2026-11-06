<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nickname',
        'email',
        'participant_code',
        'password',
        'consent_at',
        'tutorial_completed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'consent_at' => 'datetime',
            'password' => 'hashed',
            'tutorial_completed' => 'boolean',
        ];
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get all progress records for this user
     */
    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get all badges earned by this user
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get all costumes unlocked by this user
     */
    public function costumes()
    {
        return $this->belongsToMany(Costume::class, 'user_costumes')
            ->withPivot('unlocked_at', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get all activity logs for this user
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ==========================================
    // XP & PROGRESS METHODS
    // ==========================================

    /**
     * Get total XP earned by user
     */
    public function getTotalXP(): int
    {
        return $this->progress()
            ->whereNotNull('completed_at')
            ->with('question')
            ->get()
            ->sum(function ($progress) {
                return $progress->question->xp_reward;
            });
    }

    /**
     * Get completed questions count
     */
    public function getCompletedQuestionsCount(): int
    {
        return $this->progress()
            ->whereNotNull('completed_at')
            ->count();
    }

    /**
     * Get total questions count
     */
    public function getTotalQuestionsCount(): int
    {
        return Question::count();
    }

    /**
     * Get overall completion percentage
     */
    public function getCompletionPercentage(): float
    {
        $total = $this->getTotalQuestionsCount();
        if ($total === 0) {
            return 0;
        }

        $completed = $this->getCompletedQuestionsCount();
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Get user's rank on leaderboard
     */
    public function getRank(): int
    {
        $userXP = $this->getTotalXP();
        
        return self::all()
            ->filter(function ($user) use ($userXP) {
                return $user->getTotalXP() > $userXP;
            })
            ->count() + 1;
    }

    // ==========================================
    // BADGE & COSTUME METHODS
    // ==========================================

    /**
     * Check and award badges that user qualifies for
     */
    public function checkAndAwardBadges(): void
    {
        $allBadges = Badge::all();

        foreach ($allBadges as $badge) {
            if ($badge->canBeEarnedBy($this)) {
                $badge->awardTo($this);
            }
        }
    }

    /**
     * Check and unlock costumes that user qualifies for
     */
    public function checkAndUnlockCostumes(): void
    {
        $allCostumes = Costume::all();

        foreach ($allCostumes as $costume) {
            if ($costume->canBeUnlockedBy($this)) {
                $costume->unlockFor($this);
            }
        }
    }

    /**
     * Get active costume for this user
     */
    public function getActiveCostume(): ?Costume
    {
        return $this->costumes()->wherePivot('is_active', true)->first();
    }

    /**
     * Set active costume
     */
    public function setActiveCostume(Costume $costume): bool
    {
        // Check if user has unlocked this costume
        if (!$costume->isUnlockedBy($this)) {
            return false;
        }

        // Deactivate all costumes
        $this->costumes()->updateExistingPivot(
            $this->costumes()->pluck('costumes.id')->toArray(),
            ['is_active' => false]
        );

        // Activate the selected costume
        $this->costumes()->updateExistingPivot($costume->id, ['is_active' => true]);

        return true;
    }

    // ==========================================
    // UNLOCK LOGIC
    // ==========================================

    /**
     * Get next unlocked question for user
     */
    public function getNextUnlockedQuestion(): ?Question
    {
        return Question::all()->first(function ($question) {
            return $question->isUnlockedFor($this) && !$question->isCompletedBy($this);
        });
    }

    /**
     * Initialize user progress (unlock default costumes)
     */
    public function initializeProgress(): void
    {
        // Unlock default costumes
        $defaultCostumes = Costume::where('unlock_type', 'default')->get();
        
        foreach ($defaultCostumes as $costume) {
            if (!$costume->isUnlockedBy($this)) {
                $this->costumes()->attach($costume->id, [
                    'unlocked_at' => now(),
                    'is_active' => $defaultCostumes->first()->id === $costume->id, // First one is active
                ]);
            }
        }
    }

    // ==========================================
    // ACTIVITY LOGGING
    // ==========================================

    /**
     * Log an activity
     */
    public function logActivity(string $event, ?int $questionId = null, ?array $meta = null): void
    {
        ActivityLog::logActivity($this, $event, $questionId, $meta);
    }

    /**
     * Get activity summary
     */
    public function getActivitySummary(): array
    {
        return ActivityLog::getSummary($this);
    }
}
