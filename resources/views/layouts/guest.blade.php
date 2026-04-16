<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Plateful') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-[var(--text)]">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-[var(--bg)] via-white to-[var(--bg)]"></div>

        <div class="pointer-events-none absolute -top-24 -left-24 h-80 w-80 rounded-full opacity-70"
            style="background: radial-gradient(circle at 30% 30%, var(--primary) 0%, rgba(158, 216, 181, 0) 70%);">
        </div>
        <div class="pointer-events-none absolute -bottom-28 -right-28 h-96 w-96 rounded-full opacity-70"
            style="background: radial-gradient(circle at 70% 70%, var(--secondary) 0%, rgba(183, 215, 242, 0) 70%);">
        </div>
        <div class="pointer-events-none absolute top-1/3 -right-24 h-72 w-72 rounded-full opacity-60"
            style="background: radial-gradient(circle at 70% 40%, var(--accent) 0%, rgba(244, 227, 163, 0) 70%);"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <div class="rounded-2xl border border-[var(--neutral)] bg-white/85 p-6 shadow-sm backdrop-blur">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-[var(--text-light)]">
                    © {{ date('Y') }} Plateful
                </p>
            </div>
        </div>
    </div>
</body>

</html>
