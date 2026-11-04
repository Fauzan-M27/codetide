<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nickname' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Auto-generate unique participant code
        $participantCode = $this->generateParticipantCode();

        $user = User::create([
            'nickname' => $request->nickname,
            'email' => $request->email,
            'participant_code' => $participantCode,
            'password' => Hash::make($request->password),
            'consent_at' => now(), // Auto-set consent timestamp
        ]);

        // Initialize default costumes
        $user->initializeProgress();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Generate unique participant code
     */
    private function generateParticipantCode(): string
    {
        do {
            // Format: CP-YYMMDD-XXXX (e.g., CP-261017-A3F2)
            $code = 'CP-' . date('ymd') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        } while (User::where('participant_code', $code)->exists());

        return $code;
    }
}
