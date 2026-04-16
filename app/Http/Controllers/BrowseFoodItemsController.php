<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\FoodItem;
use App\Services\FoodStatusService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrowseFoodItemsController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $q = trim((string) $request->query('q', ''));
        $category = (string) $request->query('category', '');
        $sort = (string) $request->query('sort', 'expiry_soonest'); // expiry_soonest | newest
        $source = (string) $request->query('source', 'donations'); // inventory | donations
        $storage = trim((string) $request->query('storage', ''));
        $expiryMax = trim((string) $request->query('expiry_max', '')); // YYYY-MM-DD

        if (!in_array($sort, ['expiry_soonest', 'newest'], true)) {
            $sort = 'expiry_soonest';
        }

        if (!in_array($source, ['inventory', 'donations'], true)) {
            $source = 'donations';
        }

        // Categories from all food items
        $categories = FoodItem::query()
            ->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Storage types from user's inventory (so user can filter by their labels)
        $storageOptions = FoodItem::query()
            ->where('user_id', $userId)
            ->whereNotNull('storage_location')
            ->where('storage_location', '!=', '')
            ->select('storage_location')
            ->distinct()
            ->orderBy('storage_location')
            ->pluck('storage_location');

        $donations = null;
        $inventoryItems = null;

        if ($source === 'inventory') {
            $inventoryQuery = FoodItem::query()
                ->where('user_id', $userId)
                ->whereNull('used_at')
                ->when($q !== '', fn($fi) => $fi->where('name', 'like', "%{$q}%"))
                ->when($category !== '', fn($fi) => $fi->where('category', $category))
                ->when($storage !== '', fn($fi) => $fi->where('storage_location', $storage))
                ->when($expiryMax !== '', fn($fi) => $fi->whereDate('expiration_date', '<=', $expiryMax));

            $inventoryQuery = $sort === 'newest'
                ? $inventoryQuery->latest()
                : $inventoryQuery->orderBy('expiration_date');

            $inventoryItems = $inventoryQuery->paginate(12)->withQueryString();
        } else {
            $donationsQuery = Donation::query()
                ->with(['foodItem', 'donor'])
                ->where('status', 'available')
                ->where('donor_id', '!=', $userId)
                ->whereHas('foodItem', function ($fi) use ($storage, $expiryMax) {
                    $fi->where('status', '!=', FoodStatusService::STATUS_EXPIRED)
                        ->whereNull('used_at')
                        ->when($storage !== '', fn($q) => $q->where('storage_location', $storage))
                        ->when($expiryMax !== '', fn($q) => $q->whereDate('expiration_date', '<=', $expiryMax));
                })
                ->when($q !== '', function ($dq) use ($q) {
                    $dq->whereHas('foodItem', fn($fi) => $fi->where('name', 'like', "%{$q}%"));
                })
                ->when($category !== '', function ($dq) use ($category) {
                    $dq->whereHas('foodItem', fn($fi) => $fi->where('category', $category));
                })
                ->when($sort === 'expiry_soonest', function ($dq) {
                    $dq->orderBy(
                        FoodItem::select('expiration_date')
                            ->whereColumn('food_items.id', 'donations.food_item_id')
                            ->limit(1)
                    );
                }, function ($dq) {
                    $dq->latest();
                });

            $donations = $donationsQuery->paginate(12)->withQueryString();
        }

        return view('browse-food-items.index', [
            'q' => $q,
            'category' => $category,
            'sort' => $sort,
            'source' => $source,
            'storage' => $storage,
            'expiryMax' => $expiryMax,
            'categories' => $categories,
            'storageOptions' => $storageOptions,
            'donations' => $donations,
            'inventoryItems' => $inventoryItems,
        ]);
    }

    public function show(Request $request, FoodItem $foodItem): View
    {
        $userId = $request->user()->id;

        // Only allow viewing details for items that belong to the user (Inventory-only browse).
        abort_unless($foodItem->user_id === $userId, 404);

        return view('browse-food-items.show', [
            'item' => $foodItem,
        ]);
    }
}
