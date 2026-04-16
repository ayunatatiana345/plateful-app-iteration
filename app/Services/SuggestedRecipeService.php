<?php

namespace App\Services;

use App\Models\FoodItem;
use Illuminate\Support\Collection;

class SuggestedRecipeService
{
    /**
     * Generate simple, local (non-API) recipe suggestions from a user's inventory.
     *
     * @return Collection<int, array{key:string,title:string,description:string,ingredients:array<int,string>}> 
     */
    public function suggestFromInventory(Collection $inventory, int $limit = 12): Collection
    {
        $names = $inventory
            ->pluck('name')
            ->map(fn($n) => trim((string) $n))
            ->filter()
            ->map(fn($n) => mb_strtolower($n))
            ->unique()
            ->values();

        $byCategory = $inventory
            ->groupBy(fn(FoodItem $i) => $i->category ?: 'Other')
            ->map(fn(Collection $items) => $items->pluck('name')->map(fn($n) => trim((string) $n))->filter()->unique()->values());

        $suggestions = collect();

        $add = function (string $title, string $description, array $ingredients) use (&$suggestions) {
            $ingredients = array_values(array_filter(array_map(static fn($i) => trim((string) $i), $ingredients), static fn($i) => $i !== ''));
            if ($ingredients === []) {
                return;
            }

            $key = mb_strtolower($title . '|' . implode(',', $ingredients));

            $suggestions->push([
                'key' => sha1($key),
                'title' => $title,
                'description' => $description,
                'ingredients' => $ingredients,
            ]);
        };

        // Heuristic-based recipe templates (no external API).
        $pickSome = function (array $candidates, int $max = 5) {
            $candidates = array_values(array_unique(array_filter($candidates)));
            shuffle($candidates);
            return array_slice($candidates, 0, $max);
        };

        // 1) Category-based: build a "Bowl" using items from the largest category.
        if ($byCategory->isNotEmpty()) {
            /** @var array{0:string,1:Collection} $top */
            $top = $byCategory->sortByDesc(fn(Collection $c) => $c->count())->first(fn() => null);

            if ($top) {
                [$cat, $items] = $top;
                $ings = $pickSome($items->all(), 5);
                $add(
                    $cat . ' Bowl',
                    'A simple bowl-style meal using your available ' . mb_strtolower($cat) . ' ingredients.',
                    $ings
                );
            }
        }

        // 2) Pantry mix: "Quick Stir Fry"
        $stir = $pickSome($names->all(), 6);
        if ($stir !== []) {
            $add('Quick Stir Fry', 'Stir-fry a mix of your current ingredients with basic seasoning.', $stir);
        }

        // 3) "Simple Soup"
        $soup = $pickSome($names->all(), 6);
        if ($soup !== []) {
            $add('Simple Soup', 'Simmer ingredients into a warm soup; adjust with salt, pepper, and herbs.', $soup);
        }

        // 4) "Leftover Salad"
        $salad = $pickSome($names->all(), 6);
        if ($salad !== []) {
            $add('Leftover Salad', 'Chop and combine ingredients; add a dressing of your choice.', $salad);
        }

        // 5) Pair suggestions: pick small pairs and name them
        $pairs = $names->shuffle()->chunk(2)->take(6);
        foreach ($pairs as $pair) {
            $p = $pair->values()->all();
            if (count($p) === 2) {
                $add(ucfirst($p[0]) . ' & ' . ucfirst($p[1]) . ' Plate', 'A quick plate using two items you have on hand.', $p);
            }
        }

        return $suggestions
            ->unique('key')
            ->take(max(1, $limit))
            ->values();
    }
}
