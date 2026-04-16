<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\FoodItem;
use App\Models\User;
use App\Models\Notification;
use App\Services\FoodStatusService;
use Carbon\Carbon;

class NotificationSyncService
{
    /**
     * Generate/update notifications based on current state.
     * This keeps the feature deterministic and avoids needing queues for now.
     */
    public function syncForUser(User $user): void
    {
        $this->syncFoodExpiry($user);
        $this->syncDonationUpdates($user);
        // Meal reminders can be added later when a reminder schedule exists.
    }

    private function syncFoodExpiry(User $user): void
    {
        // Respect privacy setting
        if (!((bool) ($user->privacy_expiry_notifications ?? true))) {
            return;
        }

        $items = FoodItem::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [FoodStatusService::STATUS_EXPIRING_SOON, FoodStatusService::STATUS_EXPIRED])
            ->orderBy('expiration_date')
            ->limit(20)
            ->get();

        foreach ($items as $item) {
            $isExpired = $item->status === FoodStatusService::STATUS_EXPIRED;
            $expiresAt = $item->expiration_date ? Carbon::parse($item->expiration_date)->startOfDay() : null;

            $title = $isExpired
                ? ($item->name . ' has expired')
                : ($item->name . ' expiring soon');

            $message = $isExpired
                ? 'This item is expired. Remove it or use it to record waste.'
                : 'This item will expire soon. Consider cooking it or donating.';

            $actionLabel = $isExpired ? 'Review' : 'Donate';
            $actionUrl = $isExpired
                ? route('inventory.edit', $item)
                : route('inventory.donations.convert', $item);

            $dedupe = $isExpired
                ? ('food:' . $item->id . ':expired')
                : ('food:' . $item->id . ':expiring');

            Notification::query()->updateOrCreate(
                ['user_id' => $user->id, 'dedupe_key' => $dedupe],
                [
                    'type' => $isExpired ? 'food_expired' : 'food_expiring',
                    'title' => $title,
                    'message' => $message,
                    'action_label' => $actionLabel,
                    'action_url' => $actionUrl,
                    // keep original created_at if exists
                ]
            );
        }
    }

    private function syncDonationUpdates(User $user): void
    {
        if (!((bool) ($user->privacy_donation_updates ?? true))) {
            return;
        }

        // Donation posted (available listings)
        $posted = Donation::query()
            ->where('donor_id', $user->id)
            ->where('status', 'available')
            ->latest()
            ->limit(10)
            ->get();

        foreach ($posted as $donation) {
            $dedupe = 'donation:' . $donation->id . ':posted';
            Notification::query()->updateOrCreate(
                ['user_id' => $user->id, 'dedupe_key' => $dedupe],
                [
                    'type' => 'donation_posted',
                    'title' => 'Donation listed',
                    'message' => 'Your donation is now visible to the community.',
                    'action_label' => 'View Listings',
                    'action_url' => route('inventory.donations.index'),
                ]
            );
        }

        // Donation claimed
        $claimed = Donation::query()
            ->where('donor_id', $user->id)
            ->where('status', 'claimed')
            ->latest()
            ->limit(10)
            ->get();

        foreach ($claimed as $donation) {
            $dedupe = 'donation:' . $donation->id . ':claimed';
            Notification::query()->updateOrCreate(
                ['user_id' => $user->id, 'dedupe_key' => $dedupe],
                [
                    'type' => 'donation_claimed',
                    'title' => 'Donation claimed',
                    'message' => 'Someone claimed your donation. Please confirm availability and arrange pickup.',
                    'action_label' => 'Manage',
                    'action_url' => route('inventory.donations.index'),
                ]
            );
        }
    }
}
