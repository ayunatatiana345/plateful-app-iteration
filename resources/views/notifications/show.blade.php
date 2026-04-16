<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Notification Detail</h1>
                <p class="text-sm text-gray-600">Detail lengkap notifikasi.</p>
            </div>

            <a href="{{ route('notifications.index') }}"
                class="rounded-full border px-4 py-2 text-sm font-semibold transition bg-[var(--primary)] border-[var(--primary)] text-[var(--text)] hover:brightness-95">
                Back
            </a>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="text-lg font-semibold text-gray-900">{{ $notification['title'] }}</div>
                        <div class="mt-2 text-sm text-gray-600">{{ $notification['message'] }}</div>
                        @if (!empty($notification['time']))
                            <div class="mt-3 text-xs text-gray-500">{{ $notification['time'] }}</div>
                        @endif

                        @if (!empty($notification['related_url']))
                            <a href="{{ $notification['related_url'] }}"
                                class="mt-4 inline-flex items-center justify-center rounded-full border px-4 py-2 text-sm font-semibold transition bg-[var(--secondary)] border-[var(--secondary)] text-gray-900 hover:brightness-95">
                                Go to Related Page
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <form method="POST"
                            action="{{ route('notifications.dismiss', ['key' => $notification['key']]) }}">
                            @csrf
                            <button type="submit"
                                class="rounded-full border px-3 py-1 text-xs font-semibold transition bg-[var(--accent)] border-[var(--accent)] text-[var(--text)] hover:brightness-95">
                                Dismiss
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
