<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\FoodItem;
use App\Models\MealPlan;
use App\Services\FoodStatusService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $totalItems = FoodItem::query()->where('user_id', $userId)->count();
        $expiringSoon = FoodItem::query()
            ->where('user_id', $userId)
            ->where('status', FoodStatusService::STATUS_EXPIRING_SOON)
            ->count();
        $expired = FoodItem::query()
            ->where('user_id', $userId)
            ->where('status', FoodStatusService::STATUS_EXPIRED)
            ->count();

        $itemsDonated = Donation::query()
            ->where('donor_id', $userId)
            ->count();

        $mealsPlanned = MealPlan::query()
            ->where('user_id', $userId)
            ->where('status', 'planned')
            ->count();

        $notificationCount = $expiringSoon + $expired;

        $recentExpiring = FoodItem::query()
            ->where('user_id', $userId)
            ->whereIn('status', [FoodStatusService::STATUS_EXPIRING_SOON, FoodStatusService::STATUS_EXPIRED])
            ->orderBy('expiration_date')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'totalItems' => $totalItems,
            'expiringSoon' => $expiringSoon,
            'expired' => $expired,
            'itemsDonated' => $itemsDonated,
            'mealsPlanned' => $mealsPlanned,
            'notificationCount' => $notificationCount,
            'recentExpiring' => $recentExpiring,
        ]);
    }
}
