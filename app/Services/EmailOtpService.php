<?php

namespace App\Services;

use App\Mail\EmailOtpVerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailOtpService
{
    public const TTL_MINUTES = 10;

    public function issue(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_otp_code' => $code,
            'email_otp_expires_at' => Carbon::now()->addMinutes(self::TTL_MINUTES),
            'email_otp_sent_at' => Carbon::now(),
        ])->save();

        Mail::to($user->email)->send(new EmailOtpVerificationMail($code, self::TTL_MINUTES));
    }

    public function verify(User $user, string $code): string
    {
        if (!$user->email_otp_code || !$user->email_otp_expires_at) {
            return 'expired';
        }

        $expiresAt = Carbon::parse($user->email_otp_expires_at);
        if (Carbon::now()->greaterThan($expiresAt)) {
            return 'expired';
        }

        if (!hash_equals((string) $user->email_otp_code, $code)) {
            return 'invalid';
        }

        $user->forceFill([
            'email_verified_at' => Carbon::now(),
            'email_otp_code' => null,
            'email_otp_expires_at' => null,
            'email_otp_sent_at' => null,
        ])->save();

        return 'valid';
    }
}
