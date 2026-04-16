<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsLog;
use App\Models\Donation;
use App\Models\MealPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $mode = (string) $request->query('mode', 'weekly');
        if (! in_array($mode, ['weekly', 'monthly'], true)) {
            $mode = 'weekly';
        }

        $days = $mode === 'weekly' ? 7 : 30;
        $start = Carbon::today()->subDays($days - 1);
        $end = Carbon::today()->endOfDay();

        $category = trim((string) $request->query('category', ''));

        // Optional day drill-down (YYYY-MM-DD) triggered by chart clicks
        $day = trim((string) $request->query('day', ''));
        $dayDate = null;
        if ($day !== '') {
            try {
                $dayDate = Carbon::parse($day)->startOfDay();
            } catch (\Throwable $e) {
                $dayDate = null;
            }
        }

        $foodSavingActions = [
            // inventory items marked as used/consumed
            'inventory_marked_used',
            'consumed',

            // donating and claiming are also food-saving actions
            'donated',
            'donation_claimed',
        ];

        $wasteActions = [
            'wasted',
        ];

        // Base query for action logs in the selected range
        $logs = AnalyticsLog::query()
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end]);

        if ($category !== '') {
            $logs->whereHas('foodItem', fn($q) => $q->where('category', $category));
        }

        // Summary cards
        $savedCount = (clone $logs)
            ->whereIn('action_type', $foodSavingActions)
            ->count();

        $wastedCount = (clone $logs)
            ->whereIn('action_type', $wasteActions)
            ->count();

        $donationsMade = Donation::query()
            ->where('donor_id', $userId)
            ->when($category !== '', function ($q) use ($category) {
                $q->whereHas('foodItem', fn($qq) => $qq->where('category', $category));
            })
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $mealsCooked = MealPlan::query()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        // Goals (simple defaults; can be made configurable later)
        $savedGoal = $mode === 'weekly' ? 15 : 50;
        $donationsGoal = $mode === 'weekly' ? 5 : 20;
        $mealsGoal = $mode === 'weekly' ? 10 : 30;

        $savedPct = $savedGoal > 0 ? min(100, (int) round(($savedCount / $savedGoal) * 100)) : 0;
        $donationsPct = $donationsGoal > 0 ? min(100, (int) round(($donationsMade / $donationsGoal) * 100)) : 0;
        $mealsPct = $mealsGoal > 0 ? min(100, (int) round(($mealsCooked / $mealsGoal) * 100)) : 0;

        // Activity chart (Saved/Donated)
        $labels = [];
        $dayKeys = [];
        for ($d = 0; $d < $days; $d++) {
            $date = $start->copy()->addDays($d);
            $labels[] = $date->format($mode === 'weekly' ? 'D' : 'M d');
            $dayKeys[] = $date->toDateString();
        }

        $savedByDay = array_fill(0, $days, 0);
        $donatedByDay = array_fill(0, $days, 0);

        $savedRows = (clone $logs)
            ->whereIn('action_type', ['inventory_marked_used', 'consumed', 'donation_claimed'])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        foreach ($savedRows as $row) {
            $rowDay = Carbon::parse($row->day)->toDateString();
            $idx = (int) $start->diffInDays(Carbon::parse($rowDay));
            if ($idx >= 0 && $idx < $days) {
                $savedByDay[$idx] = (int) $row->count;
            }
        }

        // Donations made are better sourced from donations table
        $donatedRows = Donation::query()
            ->where('donor_id', $userId)
            ->when($category !== '', function ($q) use ($category) {
                $q->whereHas('foodItem', fn($qq) => $qq->where('category', $category));
            })
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        foreach ($donatedRows as $row) {
            $rowDay = Carbon::parse($row->day)->toDateString();
            $idx = (int) $start->diffInDays(Carbon::parse($rowDay));
            if ($idx >= 0 && $idx < $days) {
                $donatedByDay[$idx] = (int) $row->count;
            }
        }

        // Make total Saved consistent with the chart (Saved dataset)
        $savedCount = array_sum($savedByDay);

        // Recompute saved goal percentage after normalizing savedCount
        $savedPct = $savedGoal > 0 ? min(100, (int) round(($savedCount / $savedGoal) * 100)) : 0;

        // Category breakdown based on saved actions
        $topCategories = DB::table('analytics_logs')
            ->join('food_items', 'analytics_logs.food_item_id', '=', 'food_items.id')
            ->where('analytics_logs.user_id', $userId)
            ->whereBetween('analytics_logs.created_at', [$start, $end])
            ->whereIn('analytics_logs.action_type', ['inventory_marked_used', 'consumed', 'donation_claimed', 'donated'])
            ->when($category !== '', fn($q) => $q->where('food_items.category', $category))
            ->select('food_items.category', DB::raw('COUNT(*) as count'))
            ->groupBy('food_items.category')
            ->orderByDesc('count')
            ->limit(6)
            ->get()
            ->map(function ($row) {
                return [
                    'category' => (string) ($row->category ?? 'Other'),
                    'count' => (int) $row->count,
                ];
            })
            ->values()
            ->all();

        $totalCat = array_sum(array_map(fn($r) => $r['count'], $topCategories)) ?: 0;
        $topCategories = array_map(function ($row) use ($totalCat) {
            $row['pct'] = $totalCat > 0 ? (int) round(($row['count'] / $totalCat) * 100) : 0;
            return $row;
        }, $topCategories);

        // Category list for filter dropdown
        $categories = DB::table('food_items')
            ->where('user_id', $userId)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter(fn($c) => $c !== null && trim((string) $c) !== '')
            ->values();

        $hasAnyData = ($savedCount + $wastedCount + $donationsMade + $mealsCooked) > 0;

        // Drill-down detail list for a selected day (if provided)
        $selectedDayLabel = null;
        $dayEvents = collect();

        if ($dayDate) {
            $selectedDayLabel = $dayDate->toDateString();

            $dayStart = $dayDate->copy();
            $dayEnd = $dayDate->copy()->endOfDay();

            $dayEvents = AnalyticsLog::query()
                ->with('foodItem')
                ->where('user_id', $userId)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->when($category !== '', fn($q) => $q->whereHas('foodItem', fn($qq) => $qq->where('category', $category)))
                ->whereIn('action_type', [
                    'inventory_marked_used',
                    'consumed',
                    'donated',
                    'donation_claimed',
                    'meal_planned',
                    'meal_completed',
                ])
                ->latest()
                ->limit(30)
                ->get()
                ->map(function (AnalyticsLog $log) {
                    return [
                        'when' => $log->created_at?->format('H:i'),
                        'action' => match ($log->action_type) {
                            'inventory_marked_used', 'consumed' => 'Marked as used',
                            'donated' => 'Donated',
                            'donation_claimed' => 'Donation claimed',
                            'meal_planned' => 'Meal planned',
                            'meal_completed' => 'Meal completed',
                            default => $log->action_type,
                        },
                        'item' => $log->foodItem?->name,
                        'category' => $log->foodItem?->category,
                    ];
                });
        }

        return view('analytics.index', [
            'mode' => $mode,
            'days' => $days,

            'category' => $category,
            'categories' => $categories,

            'day' => $day,
            'selectedDayLabel' => $selectedDayLabel,
            'dayEvents' => $dayEvents,

            'savedCount' => $savedCount,
            'wastedCount' => $wastedCount,
            'donationsMade' => $donationsMade,
            'mealsCooked' => $mealsCooked,

            'savedPct' => $savedPct,
            'donationsPct' => $donationsPct,
            'mealsPct' => $mealsPct,

            'labels' => $labels,
            'dayKeys' => $dayKeys,
            'savedByDay' => $savedByDay,
            'donatedByDay' => $donatedByDay,

            'topCategories' => $topCategories,
            'hasAnyData' => $hasAnyData,
        ]);
    }
}
