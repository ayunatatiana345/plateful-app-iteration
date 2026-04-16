<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\FoodItem;
use App\Services\FoodStatusService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $dismissed = $request->session()->get('dismissed_notifications', []);
        $explicitUnread = $request->session()->get('notifications_explicit_unread', []);
        $explicitRead = $request->session()->get('notifications_explicit_read', []);

        $notifications = [];

        // Inventory notifications
        $expiringItems = FoodItem::query()
            ->where('user_id', $userId)
            ->whereIn('status', [FoodStatusService::STATUS_EXPIRING_SOON, FoodStatusService::STATUS_EXPIRED])
            ->orderBy('expiration_date')
            ->limit(10)
            ->get();

        foreach ($expiringItems as $item) {
            $key = 'food:' . $item->id;
            if (in_array($key, $dismissed, true)) {
                continue;
            }

            $isExpired = $item->status === FoodStatusService::STATUS_EXPIRED;
            $expiresAt = $item->expiration_date ? Carbon::parse($item->expiration_date) : null;

            $title = $isExpired
                ? ($item->name . ' has expired')
                : ($item->name . ' expiring soon');

            $message = $isExpired
                ? 'This item is expired. Remove it or consider recording waste.'
                : 'This item will expire soon. Consider cooking it or donating.';

            $timeLabel = $expiresAt
                ? ($isExpired ? ('Expired ' . $expiresAt->diffForHumans()) : ('Expires ' . $expiresAt->diffForHumans()))
                : 'Recently';

            $notifications[] = [
                'key' => $key,
                'type' => $isExpired ? 'warning' : 'info',
                'title' => $title,
                'message' => $message,
                'time' => $timeLabel,
                'created_at' => $expiresAt?->toIso8601String(),
            ];
        }

        // Donation notifications
        if (Schema::hasColumn('donations', 'status')) {
            $claimed = Donation::query()
                ->where('donor_id', $userId)
                ->where('status', 'claimed')
                ->latest()
                ->limit(5)
                ->get();

            foreach ($claimed as $donation) {
                $key = 'donation:' . $donation->id;
                if (in_array($key, $dismissed, true)) {
                    continue;
                }

                $notifications[] = [
                    'key' => $key,
                    'type' => 'success',
                    'title' => 'Donation claimed',
                    'message' => 'Someone has claimed your donation. Please confirm availability and arrange pickup.',
                    'time' => optional($donation->created_at)->diffForHumans() ?? 'Recently',
                    'created_at' => optional($donation->created_at)?->toIso8601String(),
                ];
            }
        }

        // Mark-all-read: explicit unread overrides, explicit read also overrides
        $unreadCount = 0;
        foreach ($notifications as $n) {
            $key = (string) ($n['key'] ?? '');

            if ($key !== '' && in_array($key, $explicitUnread, true)) {
                $unreadCount++;
                continue;
            }

            // If explicitly read, it's read.
            if ($key !== '' && in_array($key, $explicitRead, true)) {
                continue;
            }

            // Default: treat as unread (newest list is always "new")
            $unreadCount++;
        }

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'explicitUnread' => $explicitUnread,
            'explicitRead' => $explicitRead,
        ]);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        // Mark current list as read (clear explicit unread + set a wildcard flag)
        $request->session()->put('notifications_mark_all_read', true);
        $request->session()->forget('notifications_explicit_unread');

        return Redirect::route('notifications.index');
    }

    public function markUnread(Request $request, string $key): RedirectResponse
    {
        $explicitUnread = $request->session()->get('notifications_explicit_unread', []);
        $explicitRead = $request->session()->get('notifications_explicit_read', []);

        // Toggle: if already explicit-unread, remove it (mark read). Otherwise add it (mark unread).
        if (in_array($key, $explicitUnread, true)) {
            $explicitUnread = array_values(array_filter($explicitUnread, fn($k) => $k !== $key));
            if (!in_array($key, $explicitRead, true)) {
                $explicitRead[] = $key;
            }
        } else {
            $explicitUnread[] = $key;
            $explicitRead = array_values(array_filter($explicitRead, fn($k) => $k !== $key));
        }

        $request->session()->put('notifications_explicit_unread', $explicitUnread);
        $request->session()->put('notifications_explicit_read', $explicitRead);

        return Redirect::back();
    }

    public function show(Request $request, string $key): View
    {
        $userId = $request->user()->id;

        // Rebuild the same list as index, then pick the one requested.
        // (Later, when DB-backed notifications are fully wired, this can become a simple lookup.)
        $notifications = [];

        $expiringItems = FoodItem::query()
            ->where('user_id', $userId)
            ->whereIn('status', [FoodStatusService::STATUS_EXPIRING_SOON, FoodStatusService::STATUS_EXPIRED])
            ->orderBy('expiration_date')
            ->limit(10)
            ->get();

        foreach ($expiringItems as $item) {
            $nKey = 'food:' . $item->id;

            $isExpired = $item->status === FoodStatusService::STATUS_EXPIRED;
            $expiresAt = $item->expiration_date ? Carbon::parse($item->expiration_date) : null;

            $title = $isExpired
                ? ($item->name . ' has expired')
                : ($item->name . ' expiring soon');

            $message = $isExpired
                ? 'This item is expired. Remove it or consider recording waste.'
                : 'This item will expire soon. Consider cooking it or donating.';

            $timeLabel = $expiresAt
                ? ($isExpired ? ('Expired ' . $expiresAt->diffForHumans()) : ('Expires ' . $expiresAt->diffForHumans()))
                : 'Recently';

            $notifications[$nKey] = [
                'key' => $nKey,
                'type' => $isExpired ? 'warning' : 'info',
                'title' => $title,
                'message' => $message,
                'time' => $timeLabel,
                'created_at' => $expiresAt?->toIso8601String(),
                'related_url' => route('browse.food-items.show', $item),
            ];
        }

        if (Schema::hasColumn('donations', 'status')) {
            $claimed = Donation::query()
                ->where('donor_id', $userId)
                ->where('status', 'claimed')
                ->latest()
                ->limit(5)
                ->get();

            foreach ($claimed as $donation) {
                $nKey = 'donation:' . $donation->id;

                $notifications[$nKey] = [
                    'key' => $nKey,
                    'type' => 'success',
                    'title' => 'Donation claimed',
                    'message' => 'Someone has claimed your donation. Please confirm availability and arrange pickup.',
                    'time' => optional($donation->created_at)->diffForHumans() ?? 'Recently',
                    'created_at' => optional($donation->created_at)?->toIso8601String(),
                    'related_url' => route('inventory.donations.index'),
                ];
            }
        }

        abort_unless(isset($notifications[$key]), 404);

        // Auto-mark as read when opening details.
        $explicitUnread = $request->session()->get('notifications_explicit_unread', []);
        $explicitRead = $request->session()->get('notifications_explicit_read', []);

        $explicitUnread = array_values(array_filter($explicitUnread, fn($k) => $k !== $key));
        if (!in_array($key, $explicitRead, true)) {
            $explicitRead[] = $key;
        }

        $request->session()->put('notifications_explicit_unread', $explicitUnread);
        $request->session()->put('notifications_explicit_read', $explicitRead);

        return view('notifications.show', [
            'notification' => $notifications[$key],
        ]);
    }
}
