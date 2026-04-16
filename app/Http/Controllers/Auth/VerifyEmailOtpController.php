<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailOtpController extends Controller
{
    public function store(Request $request, EmailOtpService $otp): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $status = $otp->verify($request->user(), (string) $request->input('otp'));

        if ($status === 'valid' || $status === 'already_verified') {
            return redirect()->intended(route('dashboard', absolute: false))->with('otp_status', 'activated');
        }

        return back()->with('otp_status', $status);
    }
}
