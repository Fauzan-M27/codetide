<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
     * Dihitung langsung di SQL (1 query, bukan N query)
     */
    public function getTotalXP(): int
    {
        return (int) $this->progress()
            ->whereNotNull('completed_at')
            ->join('questions', 'user_progress.question_id', '=', 'questions.id')
            ->sum('questions.xp_reward');
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
     * Dihitung dengan 1 query agregat, bukan N+1 per user
     */
    public function getRank(): int
    {
        $myXP = $this->getTotalXP();

        // Hitung XP setiap user dalam 1 query GROUP BY
        $allUserXps = DB::table('users')
            ->where('users.id', '!=', $this->id)
            ->leftJoin('user_progress', function ($join) {
                $join->on('user_progress.user_id', '=', 'users.id')
                     ->whereNotNull('user_progress.completed_at');
            })
            ->leftJoin('questions', 'user_progress.question_id', '=', 'questions.id')
            ->groupBy('users.id')
            ->selectRaw('COALESCE(SUM(questions.xp_reward), 0) as total_xp')
            ->pluck('total_xp');

        return $allUserXps->filter(fn($xp) => $xp > $myXP)->count() + 1;
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
     * Pakai query DB langsung, bukan load semua soal ke PHP
     */
    public function getNextUnlockedQuestion(): ?Question
    {
        // Ambil ID soal yang sudah selesai
        $completedIds = $this->progress()
            ->whereNotNull('completed_at')
            ->pluck('question_id');

        // Cari soal pertama yang belum selesai, urut berdasarkan module/material/question order
        // Gunakan 'questions.id' (qualified) agar tidak ambiguous saat JOIN
        return Question::whereNotIn('questions.id', $completedIds)
            ->join('materials', 'questions.material_id', '=', 'materials.id')
            ->join('modules', 'materials.module_id', '=', 'modules.id')
            ->orderBy('modules.order')
            ->orderBy('materials.order')
            ->orderBy('questions.order')
            ->select('questions.*')
            ->get()
            ->first(fn($question) => $question->isUnlockedFor($this));
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
