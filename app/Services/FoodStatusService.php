<?php

namespace App\Services;

use App\Models\FoodItem;
use Carbon\Carbon;

class FoodStatusService
{
    public const STATUS_FRESH = 'Fresh';
    public const STATUS_EXPIRING_SOON = 'Expiring Soon';
    public const STATUS_EXPIRED = 'Expired';

    public function computeStatus(?string $expirationDate): string
    {
        if (! $expirationDate) {
            return self::STATUS_FRESH;
        }

        $today = Carbon::today();
        $exp = Carbon::parse($expirationDate)->startOfDay();

        if ($exp->lt($today)) {
            return self::STATUS_EXPIRED;
        }

        if ($exp->diffInDays($today) <= 3) {
            return self::STATUS_EXPIRING_SOON;
        }

        return self::STATUS_FRESH;
    }

    public function refreshFoodItemStatus(FoodItem $foodItem): FoodItem
    {
        if ($foodItem->used_at) {
            $foodItem->status = self::STATUS_FRESH;
            $foodItem->save();
            return $foodItem;
        }

        $foodItem->status = $this->computeStatus(optional($foodItem->expiration_date)->toDateString());
        $foodItem->save();

        return $foodItem;
    }
}
