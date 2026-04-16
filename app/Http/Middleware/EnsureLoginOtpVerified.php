<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLoginOtpVerified
{
    /**
     * Ensure the user has completed the login OTP step for this session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow users to reach the login-otp screen and submit/resend without being bounced back.
        if ($request->routeIs('login.otp.*')) {
            return $next($request);
        }

        if (! $request->user()) {
            return $next($request);
        }

        if ($request->session()->get('login_otp_verified') === true) {
            return $next($request);
        }

        // Save where the user was trying to go, so they can be redirected back after OTP.
        if ($request->method() === 'GET') {
            $request->session()->put('url.intended', $request->fullUrl());
        }

        return redirect()->route('login.otp.notice');
    }
}
