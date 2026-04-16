<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Notifications</h1>
                <p class="text-sm text-gray-600">Stay updated on your food items, donations, and account activities.
                </p>
            </div>

            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit"
                    class="rounded-full border px-4 py-2 text-sm font-semibold transition bg-[var(--primary)] border-[var(--primary)] text-[var(--text)] hover:brightness-95">
                    Mark all as read
                </button>
            </form>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 p-4">
            <div class="space-y-4">
                @forelse ($notifications as $n)
                    @php
                        $isUnread =
                            in_array($n['key'], $explicitUnread ?? [], true) ||
                            !in_array($n['key'], $explicitRead ?? [], true);

                        // Strip = unread indicator only (primary)
                        $bar = $isUnread ? 'bg-[var(--primary)]' : 'bg-gray-200';

                        $titleColor = 'text-gray-900';
                        $messageColor = 'text-gray-600';
                        $timeColor = 'text-gray-500';

                        $toggleLabel = $isUnread ? 'Mark read' : 'Mark unread';

                        $toggleBtn = $isUnread
                            ? 'rounded-full border px-3 py-1 text-xs font-semibold transition ' .
                                'bg-[var(--primary)] border-[var(--primary)] text-[var(--text)] hover:brightness-95'
                            : 'rounded-full border px-3 py-1 text-xs font-semibold transition ' .
                                'bg-[var(--secondary)] border-[var(--secondary)] text-gray-900 hover:brightness-95';

                        $dismissBtn =
                            'rounded-full border px-3 py-1 text-xs font-semibold transition ' .
                            'bg-[var(--accent)] border-[var(--accent)] text-[var(--text)] hover:brightness-95';
                    @endphp

                    <a href="{{ route('notifications.show', ['key' => $n['key']]) }}"
                        class="block rounded-2xl border border-gray-200 bg-white overflow-hidden transition hover:shadow-sm hover:bg-gray-50">
                        <div class="flex">
                            <div class="w-1.5 {{ $bar }}"></div>
                            <div class="p-5 flex items-start justify-between gap-4 w-full">
                                <div class="min-w-0">
                                    <div class="font-semibold truncate {{ $titleColor }}">{{ $n['title'] }}</div>
                                    <div class="mt-1 text-sm {{ $messageColor }}">{{ $n['message'] }}</div>
                                    <div class="mt-2 text-xs {{ $timeColor }}">{{ $n['time'] ?? '' }}</div>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <form method="POST"
                                        action="{{ route('notifications.markUnread', ['key' => $n['key']]) }}"
                                        onclick="event.preventDefault(); event.stopPropagation(); this.submit();">
                                        @csrf
                                        <button type="submit" class="{{ $toggleBtn }}">
                                            {{ $toggleLabel }}
                                        </button>
                                    </form>

                                    <form method="POST"
                                        action="{{ route('notifications.dismiss', ['key' => $n['key']]) }}"
                                        onclick="event.preventDefault(); event.stopPropagation(); this.submit();">
                                        @csrf
                                        <button type="submit" class="{{ $dismissBtn }}">
                                            Dismiss
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-2xl border border-gray-200 bg-white px-6 py-8">
                        <div class="text-center">
                            <div class="text-sm font-semibold text-gray-800">New no Notification</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
