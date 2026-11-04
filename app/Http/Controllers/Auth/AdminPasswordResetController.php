<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminPasswordResetController extends Controller
{
    /**
     * Show the admin password reset form
     * This is a temporary solution for environments without email
     */
    public function showGenerateForm()
    {
        return view('auth.admin-reset-link');
    }

    /**
     * Generate a password reset link for a user
     */
    public function generateLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Delete any existing tokens for this user
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        // Generate new token
        $token = Str::random(64);

        // Store hashed token in database
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Generate reset URL
        $resetUrl = route('password.reset', ['token' => $token]) . '?email=' . urlencode($user->email);

        return back()->with([
            'status' => 'Link reset password berhasil dibuat!',
            'reset_url' => $resetUrl,
            'email' => $user->email,
            'nickname' => $user->nickname,
        ]);
    }
}
