<x-guest-layout>
    <div class="mb-4 text-sm text-[var(--text-muted)]">
        We emailed a 6-digit code to <span class="font-medium text-[var(--text)]">{{ Auth::user()->email }}</span>.
    </div>

    @if (session('status') === 'otp-sent')
        <div class="mb-4 text-sm text-[var(--accent)]">
            A new OTP has been sent.
        </div>
    @endif

    <form method="POST" action="{{ route('login.otp.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="otp" id="otp" value="{{ old('otp') }}" />

        <div>
            <x-input-label for="otp" value="OTP" />

            <div class="mt-2 flex gap-2" data-otp-container>
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1"
                        class="w-11 h-12 text-center rounded-lg border border-[var(--border)] bg-white/5 text-[var(--text)] focus:ring-[var(--primary)] focus:border-[var(--primary)]" />
                @endfor
            </div>

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                class="inline-flex items-center px-6 py-2.5 rounded-lg bg-[var(--primary)] text-black text-base font-semibold hover:opacity-90">
                Verify
            </button>

            <a href="#" onclick="event.preventDefault(); document.getElementById('resend-form').submit();"
                class="text-base font-semibold text-black hover:underline">
                Resend OTP
            </a>
        </div>
    </form>

    <form id="resend-form" method="POST" action="{{ route('login.otp.resend') }}" class="hidden">
        @csrf
    </form>

    <script>
        (function() {
            const container = document.querySelector('[data-otp-container]');
            if (!container) return;

            const hidden = document.getElementById('otp');
            const inputs = Array.from(container.querySelectorAll('input'));

            function syncHidden() {
                hidden.value = inputs.map(i => i.value).join('');
            }

            inputs.forEach((input, idx) => {
                input.addEventListener('input', (e) => {
                    input.value = (input.value || '').replace(/\D/g, '').slice(0, 1);
                    if (input.value && inputs[idx + 1]) inputs[idx + 1].focus();
                    syncHidden();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && inputs[idx - 1]) {
                        inputs[idx - 1].focus();
                    }
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const text = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = (text || '').replace(/\D/g, '').slice(0, 6).split('');

                    inputs.forEach((inp, i) => {
                        inp.value = digits[i] || '';
                    });

                    const next = inputs[Math.min(digits.length, inputs.length) - 1];
                    if (next) next.focus();
                    syncHidden();
                });
            });

            // initialize from old value
            if (hidden.value) {
                const digits = String(hidden.value).replace(/\D/g, '').slice(0, 6).split('');
                inputs.forEach((inp, i) => (inp.value = digits[i] || ''));
                syncHidden();
            }
        })();
    </script>
</x-guest-layout>
