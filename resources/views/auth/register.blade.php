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

        <h1 class="mt-3 text-2xl font-semibold text-[var(--text)]">Create your account</h1>
        <p class="mt-1 text-sm text-[var(--text-light)]">Start tracking food and reduce waste with mindful planning.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name"
                class="block mt-1 w-full rounded-xl border-[var(--neutral)] bg-white focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-xl border-[var(--neutral)] bg-white focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-xl border-[var(--neutral)] bg-white focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation"
                class="block mt-1 w-full rounded-xl border-[var(--neutral)] bg-white focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="household_size" :value="__('Household size (optional)')" />
            <x-text-input id="household_size"
                class="block mt-1 w-full rounded-xl border-[var(--neutral)] bg-white focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]"
                type="number" name="household_size" :value="old('household_size')" min="1" max="50"
                inputmode="numeric" autocomplete="off" placeholder="e.g. 4" />
            <x-input-error :messages="$errors->get('household_size')" class="mt-2" />
            <p class="mt-1 text-xs text-[var(--text-light)]">Used for personalization (optional).</p>
        </div>

        <div class="pt-2">
            <x-primary-button
                class="w-full justify-center bg-[var(--primary)] text-[var(--text)] hover:bg-[var(--primary-dark)] focus:bg-[var(--primary-dark)] active:bg-[var(--primary-dark)]">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <p class="text-center text-sm text-[var(--text-light)]">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-[var(--primary-dark)] hover:text-[var(--text)]">Log
                in</a>
        </p>
    </form>
</x-guest-layout>
