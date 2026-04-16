<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationQuickStoreRequest;
use App\Models\AnalyticsLog;
use App\Models\Donation;
use App\Models\FoodItem;
use App\Services\FoodStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryDonationController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $donations = Donation::query()
            ->with('foodItem')
            ->where('donor_id', $userId)
            ->where('status', 'available')
            ->latest()
            ->paginate(10);

        return view('inventory.donations', [
            'donations' => $donations,
        ]);
    }

    public function edit(Request $request, Donation $donation): View
    {
        abort_unless($donation->donor_id === $request->user()->id, 403);

        $donation->load('foodItem');

        return view('inventory.donation-edit', [
            'donation' => $donation,
            'food' => $donation->foodItem,
        ]);
    }

    public function update(DonationQuickStoreRequest $request, Donation $donation): RedirectResponse
    {
        abort_unless($donation->donor_id === $request->user()->id, 403);
        abort_unless($donation->status === 'available', 409);

        $data = $request->validated();

        $donation->update([
            'description' => $data['description'] ?? null,
            'pickup_location' => $data['pickup_location'] ?? null,
            'availability' => $data['availability'] ?? null,
        ]);

        AnalyticsLog::query()->create([
            'user_id' => $request->user()->id,
            'food_item_id' => $donation->food_item_id,
            'action_type' => 'donation_updated',
        ]);

        return redirect()
            ->route('inventory.donations.index')
            ->with('success', 'Donation listing updated.');
    }

    public function destroy(Request $request, Donation $donation): RedirectResponse
    {
        abort_unless($donation->donor_id === $request->user()->id, 403);

        $foodItemId = $donation->food_item_id;

        $donation->delete();

        AnalyticsLog::query()->create([
            'user_id' => $request->user()->id,
            'food_item_id' => $foodItemId,
            'action_type' => 'donation_removed',
        ]);

        return redirect()
            ->route('inventory.donations.index')
            ->with('success', 'Donation listing removed.');
    }

    /**
     * Optional: convert an item and capture extra details in one step (use-case step 10).
     */
    public function convert(Request $request, FoodItem $inventory): View
    {
        abort_unless($inventory->user_id === $request->user()->id, 403);

        return view('inventory.convert-to-donation', [
            'item' => $inventory,
        ]);
    }

    public function store(DonationQuickStoreRequest $request, FoodStatusService $foodStatusService): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $foodItem = FoodItem::query()->where('user_id', $user->id)->findOrFail($data['food_item_id']);

        abort_unless($foodItem->status !== FoodStatusService::STATUS_EXPIRED, 409);
        abort_unless($foodItem->used_at === null, 409);

        // Avoid multiple active listings
        $existing = Donation::query()
            ->where('food_item_id', $foodItem->id)
            ->where('status', 'available')
            ->first();

        if ($existing) {
            return redirect()
                ->route('inventory.donations.index')
                ->with('success', 'This item is already listed as a donation.');
        }

        Donation::query()->create([
            'donor_id' => $user->id,
            'claimer_id' => null,
            'food_item_id' => $foodItem->id,
            'description' => $data['description'] ?? null,
            'pickup_location' => $data['pickup_location'] ?? null,
            'availability' => $data['availability'] ?? null,
            'status' => 'available',
        ]);

        AnalyticsLog::query()->create([
            'user_id' => $user->id,
            'food_item_id' => $foodItem->id,
            'action_type' => 'donated',
        ]);

        // Ensure status is up to date
        $foodStatusService->refreshFoodItemStatus($foodItem);

        return redirect()
            ->route('inventory.donations.index')
            ->with('success', 'Donation listed as available.');
    }

    /**
     * New flow: create donation listing immediately after user confirms conversion.
     * User fills pickup_location + availability later from Donation Listings page.
     */
    public function quickConvert(Request $request, FoodItem $inventory, FoodStatusService $foodStatusService): RedirectResponse
    {
        abort_unless($inventory->user_id === $request->user()->id, 403);

        if ($inventory->status === FoodStatusService::STATUS_EXPIRED) {
            return redirect()
                ->route('inventory.show', $inventory)
                ->with('error', 'Cannot convert an expired item to donation.');
        }

        if ($inventory->used_at !== null) {
            return redirect()
                ->route('inventory.show', $inventory)
                ->with('error', 'Cannot convert an item that is already marked as used.');
        }

        $existing = Donation::query()
            ->where('food_item_id', $inventory->id)
            ->where('status', 'available')
            ->first();

        if (! $existing) {
            Donation::query()->create([
                'donor_id' => $request->user()->id,
                'claimer_id' => null,
                'food_item_id' => $inventory->id,
                'description' => null,
                'pickup_location' => null,
                'availability' => null,
                'status' => 'available',
            ]);

            AnalyticsLog::query()->create([
                'user_id' => $request->user()->id,
                'food_item_id' => $inventory->id,
                'action_type' => 'donated',
            ]);
        }

        $foodStatusService->refreshFoodItemStatus($inventory);

        return redirect()
            ->route('inventory.donations.index')
            ->with('success', $existing ? 'This item is already listed as a donation.' : 'Item converted to donation. Please add pickup details.');
    }
}
