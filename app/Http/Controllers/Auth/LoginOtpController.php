<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginOtpController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.login-otp');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $result = app(EmailOtpService::class)->verify($user, $request->string('otp')->toString());

        if ($result !== 'valid') {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP.',
            ])->withInput();
        }

        $request->session()->put('login_otp_verified', true);

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('status', 'otp-verified');
    }

    public function resend(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        app(EmailOtpService::class)->issue($user);

        return back()->with('status', 'otp-sent');
    }
}
