<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);

        // Check if email is configured (not 'log' driver)
        $mailDriver = config('mail.default');
        
        if ($mailDriver === 'log' || !config('mail.mailers.smtp.username')) {
            // Email not configured - direct user to admin tool
            return back()->with([
                'status' => 'Email belum dikonfigurasi. Silakan gunakan Admin Tool untuk generate reset link.',
                'show_admin_link' => true,
            ]);
        }

        // Try to send reset link via email
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                return back()->with('status', 'Link reset password telah dikirim ke email Anda. Cek inbox/spam folder.');
            }
            
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
                
        } catch (\Exception $e) {
            // If email sending fails, suggest admin tool
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            
            return back()->with([
                'status' => 'Gagal mengirim email. Silakan gunakan Admin Tool untuk generate reset link.',
                'show_admin_link' => true,
            ]);
        }
    }
}
