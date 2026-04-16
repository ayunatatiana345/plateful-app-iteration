<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodItemStoreRequest;
use App\Http\Requests\FoodItemUpdateRequest;
use App\Models\AnalyticsLog;
use App\Models\FoodItem;
use App\Services\FoodStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FoodItemController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $search = (string) $request->query('q', '');
        $category = (string) $request->query('category', '');
        $status = (string) $request->query('status', '');

        $query = FoodItem::query()
            ->where('user_id', $userId)
            ->when($search !== '', fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($category !== '', fn($q) => $q->where('category', $category))
            ->when($status !== '', fn($q) => $q->where('status', $status))
            ->orderBy('expiration_date');

        $items = $query->paginate(10)->withQueryString();

        $base = FoodItem::query()->where('user_id', $userId);

        $statuses = [
            FoodStatusService::STATUS_FRESH,
            FoodStatusService::STATUS_EXPIRING_SOON,
            FoodStatusService::STATUS_EXPIRED,
        ];

        $statusCounts = [];
        foreach ($statuses as $s) {
            $statusCounts[$s] = (clone $base)->where('status', $s)->count();
        }

        $counts = [
            'all' => (clone $base)->count(),
            'status' => $statusCounts,
        ];

        $categories = (clone $base)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('inventory.index', [
            'items' => $items,
            'counts' => $counts,
            'categories' => $categories,
            'q' => $search,
            'selectedCategory' => $category,
            'selectedStatus' => $status,
            'statuses' => $statuses,
        ]);
    }

    public function create(): View
    {
        return view('inventory.create');
    }

    public function store(FoodItemStoreRequest $request, FoodStatusService $foodStatusService): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['status'] = $foodStatusService->computeStatus($data['expiration_date']);

        $item = FoodItem::query()->create($data);

        AnalyticsLog::query()->create([
            'user_id' => $user->id,
            'food_item_id' => $item->id,
            'action_type' => 'inventory_added',
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Food item added.');
    }

    public function edit(Request $request, FoodItem $inventory): View
    {
        $this->authorizeOwner($request, $inventory);

        return view('inventory.edit', ['item' => $inventory]);
    }

    public function update(
        FoodItemUpdateRequest $request,
        FoodItem $inventory,
        FoodStatusService $foodStatusService
    ): RedirectResponse {
        $this->authorizeOwner($request, $inventory);

        $data = $request->validated();
        $data['status'] = $foodStatusService->computeStatus($data['expiration_date']);

        $inventory->update($data);

        AnalyticsLog::query()->create([
            'user_id' => $request->user()->id,
            'food_item_id' => $inventory->id,
            'action_type' => 'inventory_updated',
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Food item updated.');
    }

    public function destroy(Request $request, FoodItem $inventory): RedirectResponse
    {
        $this->authorizeOwner($request, $inventory);

        $itemId = $inventory->id;

        $inventory->delete();

        AnalyticsLog::query()->create([
            'user_id' => $request->user()->id,
            'food_item_id' => $itemId,
            'action_type' => 'inventory_deleted',
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('toast', true)
            ->with('success', 'Food item deleted.');
    }

    public function show(Request $request, FoodItem $inventory): View
    {
        $this->authorizeOwner($request, $inventory);

        return view('inventory.show', ['item' => $inventory]);
    }

    public function markUsed(Request $request, FoodItem $inventory, FoodStatusService $foodStatusService): RedirectResponse
    {
        $this->authorizeOwner($request, $inventory);

        if ($inventory->used_at) {
            return redirect()
                ->route('inventory.show', $inventory)
                ->with('success', 'Item already marked as used.');
        }

        $inventory->forceFill([
            'used_at' => now(),
        ])->save();

        // Recompute status in case expiration date changed since it was added
        $foodStatusService->refreshFoodItemStatus($inventory);

        AnalyticsLog::query()->create([
            'user_id' => $request->user()->id,
            'food_item_id' => $inventory->id,
            'action_type' => 'inventory_marked_used',
        ]);

        return redirect()
            ->route('inventory.show', $inventory)
            ->with('success', 'Marked as used.');
    }

    private function authorizeOwner(Request $request, FoodItem $item): void
    {
        abort_unless($item->user_id === $request->user()->id, 403);
    }
}
