<section>
    <header>
        <h2 class="text-base font-semibold text-[var(--text)]">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-[var(--muted)]">
            Update your account's profile information and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-[var(--text)]">
                        Your email address is unverified.
                        <button form="send-verification"
                            class="underline text-sm text-[var(--muted)] hover:text-[var(--text)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--accent)]">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-700">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="household_size" :value="__('Household Size')" />
            <x-text-input id="household_size" name="household_size" type="number" min="1" max="50"
                class="mt-1 block w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                :value="old('household_size', $user->household_size)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('household_size')" />
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button type="submit"
                class="inline-flex items-center rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-[var(--muted)]">Saved.</p>
            @endif
        </div>
    </form>
</section>
