<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Get user stats
        $stats = [
            'total_xp' => $user->getTotalXP(),
            'completed_questions' => $user->progress()->whereNotNull('completed_at')->count(),
            'total_questions' => 40,
            'badges_earned' => $user->badges()->count(),
            'costumes_unlocked' => $user->costumes()->count(),
            'join_date' => $user->created_at->format('d M Y'),
        ];
        
        // Get active costume
        $activeCostume = $user->costumes()->wherePivot('is_active', true)->first();
        
        // Get earned badges
        $earnedBadges = $user->badges()->get();
        
        // Get all badges for display (earned + locked)
        $allBadges = \App\Models\Badge::all()->map(function ($badge) use ($user) {
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'is_earned' => $user->badges()->where('badge_id', $badge->id)->exists(),
                'earned_at' => $user->badges()->where('badge_id', $badge->id)->first()?->pivot->earned_at ?? null,
            ];
        });
        
        return view('profile.edit', [
            'user' => $user,
            'stats' => $stats,
            'activeCostume' => $activeCostume,
            'earnedBadges' => $earnedBadges,
            'allBadges' => $allBadges,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
