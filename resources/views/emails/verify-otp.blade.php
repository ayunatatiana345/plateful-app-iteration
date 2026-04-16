@php
    /** @var string $code */
    /** @var int $ttlMinutes */
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} OTP</title>
</head>

<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:560px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;">
            <h2 style="margin:0 0 12px 0;color:#111827;">Login verification code</h2>
            <p style="margin:0 0 16px 0;color:#374151;line-height:1.5;">
                Use this 6-digit code to continue signing in:
            </p>

            <div style="text-align:center;margin:20px 0;">
                <div
                    style="display:inline-block;padding:14px 18px;border-radius:10px;border:1px solid #e5e7eb;background:#f9fafb;font-size:22px;letter-spacing:4px;font-weight:700;color:#111827;">
                    {{ $code }}
                </div>
            </div>

            <p style="margin:0;color:#6b7280;line-height:1.5;">
                This code expires in <strong>{{ $ttlMinutes }} minutes</strong>.
            </p>
        </div>

        <p style="margin:14px 0 0 0;text-align:center;color:#9ca3af;font-size:12px;">
            © {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</body>

</html>
