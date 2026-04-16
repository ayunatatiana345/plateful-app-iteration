<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationStoreRequest;
use App\Models\AnalyticsLog;
use App\Models\Donation;
use App\Models\FoodItem;
use App\Services\FoodStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $donations = Donation::query()
            ->with(['foodItem', 'donor'])
            ->where('status', 'available')
            ->where('donor_id', '!=', $userId)
            ->latest()
            ->paginate(10);

        return view('donations.index', [
            'donations' => $donations,
        ]);
    }

    public function my(Request $request): View
    {
        $userId = $request->user()->id;

        $donations = Donation::query()
            ->with(['foodItem', 'claimer'])
            ->where('donor_id', $userId)
            ->latest()
            ->paginate(10);

        return view('donations.my', [
            'donations' => $donations,
        ]);
    }

    public function create(Request $request): View
    {
        $userId = $request->user()->id;

        $foodItems = FoodItem::query()
            ->where('user_id', $userId)
            ->where('status', '!=', FoodStatusService::STATUS_EXPIRED)
            ->whereDoesntHave('donations', fn($q) => $q->where('status', 'available'))
            ->orderBy('expiration_date')
            ->get();

        $prefillFoodItemId = (string) $request->query('food_item_id', '');

        return view('donations.create', [
            'foodItems' => $foodItems,
            'prefillFoodItemId' => $prefillFoodItemId,
        ]);
    }

    public function store(DonationStoreRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $foodItem = FoodItem::query()->where('user_id', $user->id)->findOrFail($data['food_item_id']);

        // Avoid creating multiple active listings for the same food item
        $existing = Donation::query()
            ->where('food_item_id', $foodItem->id)
            ->where('status', 'available')
            ->first();

        if ($existing) {
            return redirect()
                ->route('donations.my')
                ->with('success', 'This item is already listed as a donation.');
        }

        $donation = Donation::query()->create([
            'donor_id' => $user->id,
            'claimer_id' => null,
            'food_item_id' => $foodItem->id,
            'description' => $data['description'] ?? null,
            'status' => 'available',
        ]);

        AnalyticsLog::query()->create([
            'user_id' => $user->id,
            'food_item_id' => $foodItem->id,
            'action_type' => 'donated',
        ]);

        return redirect()
            ->route('donations.my')
            ->with('success', 'Donation listed as available.');
    }

    public function claim(Request $request, Donation $donation): RedirectResponse
    {
        $userId = $request->user()->id;

        abort_unless($donation->status === 'available', 409);
        abort_unless($donation->donor_id !== $userId, 403);

        $donation->update([
            'claimer_id' => $userId,
            'status' => 'claimed',
        ]);

        // Mark the donated item as used in the donor's inventory (it has been given away)
        $donation->foodItem()?->update(['used_at' => now()]);

        AnalyticsLog::query()->create([
            'user_id' => $userId,
            'food_item_id' => $donation->food_item_id,
            'action_type' => 'donation_claimed',
        ]);

        return redirect()
            ->route('browse.food-items')
            ->with('success', 'Donation claimed. Please contact the donor.');
    }

    /**
     * Quick-create a donation listing from Inventory (no separate donation listing page).
     */
    public function quickStore(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'food_item_id' => ['required', 'integer', 'exists:food_items,id'],
        ]);

        $foodItem = FoodItem::query()->where('user_id', $user->id)->findOrFail($data['food_item_id']);

        // Prevent listing expired items
        abort_unless($foodItem->status !== FoodStatusService::STATUS_EXPIRED, 409);

        // Prevent listing used items
        abort_unless($foodItem->used_at === null, 409);

        // Avoid creating multiple active listings for the same food item
        $existing = Donation::query()
            ->where('food_item_id', $foodItem->id)
            ->where('status', 'available')
            ->first();

        if (!$existing) {
            Donation::query()->create([
                'donor_id' => $user->id,
                'claimer_id' => null,
                'food_item_id' => $foodItem->id,
                'description' => null,
                'status' => 'available',
            ]);

            AnalyticsLog::query()->create([
                'user_id' => $user->id,
                'food_item_id' => $foodItem->id,
                'action_type' => 'donated',
            ]);
        }

        return redirect()->route('inventory.donations.index');
    }
}
