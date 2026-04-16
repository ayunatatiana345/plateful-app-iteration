<x-guest-layout>
    <div class="space-y-5">
        <div>
            <h1 class="text-2xl font-semibold text-[var(--text)]">Verify your email</h1>
            <p class="mt-1 text-sm text-[var(--text)]/70">
                We sent a 6-digit verification code to <span class="font-semibold">{{ auth()->user()->email }}</span>.
                Enter it below to activate your account.
            </p>
        </div>

        {{-- Registration success message (after sign up) --}}
        @if (session('status') === 'registered')
            <div class="rounded-xl border border-[var(--neutral)] bg-white px-4 py-3 text-sm text-[var(--text)]">
                Registration successful. Please verify your email first to activate your account.
            </div>
        @endif

        @if (session('otp_status') === 'sent')
            <div class="rounded-xl border border-[var(--neutral)] bg-white px-4 py-3 text-sm text-[var(--text)]">
                A new verification code has been sent.
            </div>
        @endif

        {{-- OTP status messages --}}
        @if (session('otp_status') === 'valid' || session('otp_status') === 'activated')
            <div
                class="rounded-xl border border-[var(--neutral)] bg-[var(--primary)]/25 px-4 py-3 text-sm text-[var(--text)]">
                Your account has been activated successfully.
            </div>
        @elseif (session('otp_status') === 'invalid')
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                Invalid code. Please try again.
            </div>
        @elseif (session('otp_status') === 'expired')
            <div class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                Code expired. Please request a new code.
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                Please enter a valid 6-digit code.
            </div>
        @endif

        <div class="rounded-2xl border border-[var(--neutral)] bg-white p-5">
            <div class="text-sm font-semibold text-[var(--text)]">6-digit code</div>
            <p class="mt-1 text-sm text-[var(--text)]/70">Enter the code from your email.</p>

            <form method="POST" action="{{ route('verification.otp') }}" class="mt-4" id="otp-form">
                @csrf

                <input type="hidden" name="otp" id="otp" value="" />

                <div class="flex items-center gap-2" id="otp-boxes">
                    @for ($i = 1; $i <= 6; $i++)
                        <input inputmode="numeric" pattern="[0-9]*" maxlength="1"
                            aria-label="Digit {{ $i }}"
                            class="otp-box h-12 w-12 rounded-xl border border-[var(--neutral)] bg-white text-center text-lg font-semibold text-[var(--text)] focus:border-[var(--primary)] focus:ring-[var(--primary)]" />
                    @endfor
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <button type="submit"
                        class="rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-black ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                        Verify
                    </button>

                    <span class="text-xs text-[var(--text)]/60">
                        Code expired? Request a new one.
                    </span>
                </div>
            </form>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-xl bg-[var(--accent)] px-4 py-2 text-sm font-semibold text-black ring-1 ring-black/5 hover:brightness-95">
                        Resend / Request New Code
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-sm font-semibold text-[var(--text)]/70 hover:text-[var(--text)] underline decoration-transparent hover:decoration-[var(--text)]">
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        <div class="rounded-2xl bg-[var(--bg)] p-4 text-sm text-[var(--text)]/70 ring-1 ring-[var(--neutral)]">
            If you didn't receive the code, please check your spam folder.
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const form = document.getElementById('otp-form');
                const hidden = document.getElementById('otp');
                const boxes = Array.from(document.querySelectorAll('.otp-box'));

                const sync = () => {
                    hidden.value = boxes.map(b => (b.value || '').replace(/\D/g, '')).join('').slice(0, 6);
                };

                boxes.forEach((box, idx) => {
                    box.addEventListener('input', (e) => {
                        box.value = (box.value || '').replace(/\D/g, '').slice(0, 1);
                        sync();
                        if (box.value && idx < boxes.length - 1) {
                            boxes[idx + 1].focus();
                        }
                    });

                    box.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace' && !box.value && idx > 0) {
                            boxes[idx - 1].focus();
                        }
                    });

                    box.addEventListener('paste', (e) => {
                        const text = (e.clipboardData || window.clipboardData).getData('text') || '';
                        const digits = text.replace(/\D/g, '').slice(0, 6);
                        if (!digits) return;

                        e.preventDefault();
                        digits.split('').forEach((d, i) => {
                            if (boxes[i]) boxes[i].value = d;
                        });
                        sync();
                        const next = boxes[Math.min(digits.length, boxes.length) - 1];
                        if (next) next.focus();
                    });
                });

                form.addEventListener('submit', () => {
                    sync();
                });

                // focus first box
                if (boxes[0]) boxes[0].focus();
            })();
        </script>
    @endpush
</x-guest-layout>
