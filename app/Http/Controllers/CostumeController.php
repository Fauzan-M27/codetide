<?php

namespace App\Http\Controllers;

use App\Models\Costume;
use Illuminate\Http\Request;

class CostumeController extends Controller
{
    /**
     * Display costume customization page
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get all costumes with unlock status
        $costumes = Costume::all()->map(function ($costume) use ($user) {
            $isUnlocked = $costume->isUnlockedBy($user);
            $isActive = $costume->isActiveFor($user);

            return [
                'id' => $costume->id,
                'name' => $costume->name,
                'icon' => $costume->icon,
                'unlock_type' => $costume->unlock_type,
                'is_unlocked' => $isUnlocked,
                'is_active' => $isActive,
                'can_unlock' => !$isUnlocked && $costume->canBeUnlockedBy($user),
                'unlock_hint' => $this->getUnlockHint($costume),
            ];
        });

        $activeCostume = $user->getActiveCostume();

        return view('costumes.index', compact('costumes', 'activeCostume'));
    }

    /**
     * Set active costume
     */
    public function activate(Request $request, Costume $costume)
    {
        $user = $request->user();

        // Check if costume is unlocked
        if (!$costume->isUnlockedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Kostum ini belum ter-unlock',
            ], 403);
        }

        // Set as active
        $success = $user->setActiveCostume($costume);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Kostum berhasil diaktifkan',
                'costume' => [
                    'id' => $costume->id,
                    'name' => $costume->name,
                    'icon' => $costume->icon,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengaktifkan kostum',
        ], 500);
    }

    /**
     * Check for unlockable costumes
     */
    public function checkUnlocks(Request $request)
    {
        $user = $request->user();
        
        // Check and unlock costumes
        $user->checkAndUnlockCostumes();

        // Get newly unlocked costumes (last 10 seconds)
        $newCostumes = $user->costumes()
            ->wherePivot('unlocked_at', '>=', now()->subSeconds(10))
            ->get()
            ->map(function ($costume) {
                return [
                    'id' => $costume->id,
                    'name' => $costume->name,
                    'icon' => $costume->icon,
                ];
            });

        return response()->json([
            'new_costumes' => $newCostumes,
        ]);
    }

    /**
     * Show create custom character page
     */
    public function create(Request $request)
    {
        $user = $request->user();
        
        return view('costumes.create');
    }

    /**
     * Store uploaded character sprites
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'character_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sprite_idle' => 'required|image|mimes:png,gif|max:2048',
            'sprite_typing' => 'nullable|image|mimes:png,gif|max:2048',
            'sprite_thinking' => 'nullable|image|mimes:png,gif|max:2048',
            'sprite_success' => 'nullable|image|mimes:png,gif|max:2048',
            'sprite_error' => 'nullable|image|mimes:png,gif|max:2048',
        ]);

        // Upload sprites
        $sprites = [];
        $spriteFields = ['sprite_idle', 'sprite_typing', 'sprite_thinking', 'sprite_success', 'sprite_error'];
        
        foreach ($spriteFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('characters', 'public');
                $sprites[$field] = $path;
            }
        }

        // Create costume
        $costume = Costume::create([
            'name' => $request->character_name,
            'character_name' => $request->character_name,
            'description' => $request->description,
            'icon' => 'user', // Default icon for custom characters
            'avatar_icon' => 'user',
            'unlock_type' => 'default',
            'sprite_idle' => $sprites['sprite_idle'] ?? null,
            'sprite_typing' => $sprites['sprite_typing'] ?? $sprites['sprite_idle'],
            'sprite_thinking' => $sprites['sprite_thinking'] ?? $sprites['sprite_idle'],
            'sprite_success' => $sprites['sprite_success'] ?? $sprites['sprite_idle'],
            'sprite_error' => $sprites['sprite_error'] ?? $sprites['sprite_idle'],
            'sprite_format' => $request->file('sprite_idle')->getClientOriginalExtension(),
        ]);

        // Auto-unlock for the user
        $user->costumes()->attach($costume->id, [
            'unlocked_at' => now(),
            'is_active' => false,
        ]);

        return redirect()->route('costumes.index')
            ->with('success', 'Karakter kustom berhasil dibuat! Silakan aktifkan.');
    }

    /**
     * Get unlock hint text
     */
    private function getUnlockHint(Costume $costume): string
    {
        switch ($costume->unlock_type) {
            case 'default':
                return 'Tersedia dari awal';

            case 'badge':
                $badge = \App\Models\Badge::find($costume->unlock_requirement);
                return $badge ? "Dapatkan badge: {$badge->name}" : 'Dapatkan badge tertentu';

            case 'xp':
                $required = $costume->unlock_requirement;
                $totalPossible = 585; // Total XP from all 40 questions (5+10+15+20 variants)
                $percentage = round(($required / $totalPossible) * 100);
                return "Kumpulkan {$required} XP ({$percentage}% dari total)";

            case 'module':
                $module = \App\Models\Module::find($costume->unlock_requirement);
                return $module ? "Selesaikan modul: {$module->title}" : 'Selesaikan modul tertentu';

            default:
                return 'Syarat unlock tidak diketahui';
        }
    }
}
