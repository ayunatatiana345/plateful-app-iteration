<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto mb-3 inline-flex items-center justify-center">
            <div class="flex items-center justify-center gap-3">
                <span
                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white border border-[var(--neutral)] overflow-hidden ring-1 ring-black/5">
                    <img src="{{ asset('images/logo.png') }}" alt="Plateful" class="h-9 w-9 object-contain" />
                </span>
                <div class="text-left">
                    <div class="text-sm font-semibold leading-5 text-[var(--text)]">Plateful</div>
                    <div class="text-xs text-[var(--text-light)]">Mindful consumption</div>
                </div>
            </div>
        </div>

        <h1 class="mt-3 text-2xl font-semibold text-[var(--text)]">Welcome back</h1>
        <p class="mt-1 text-sm text-[var(--text-light)]">Log in to manage your inventory, recipes, donations, and meal
            plans.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-xl border-[var(--neutral)] bg-white focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-xl border-[var(--neutral)] bg-white focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-[var(--neutral)] text-[var(--primary-dark)] shadow-sm focus:ring-[var(--primary-dark)]"
                    name="remember">
                <span class="ms-2 text-sm text-[var(--text-light)]">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-[var(--text-light)] hover:text-[var(--text)]"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button
                class="w-full justify-center bg-[var(--primary)] text-[var(--text)] hover:bg-[var(--primary-dark)] focus:bg-[var(--primary-dark)] active:bg-[var(--primary-dark)]">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <p class="text-center text-sm text-[var(--text-light)]">
            Don't have an account?
            <a href="{{ route('register') }}"
                class="font-semibold text-[var(--primary-dark)] hover:text-[var(--text)]">Create one</a>
        </p>
    </form>
</x-guest-layout>
