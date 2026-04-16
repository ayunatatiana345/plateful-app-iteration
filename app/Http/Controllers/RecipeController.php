<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $meals = [];
        $error = null;

        if ($q !== '') {
            try {
                $response = Http::timeout(8)->get('https://www.themealdb.com/api/json/v1/1/search.php', [
                    's' => $q,
                ]);

                if ($response->successful()) {
                    /** @var array{meals?: array<int, array<string, mixed>>|null} $data */
                    $data = $response->json();
                    $meals = $data['meals'] ?? [];
                    $meals = $meals ?: [];
                } else {
                    $error = 'Failed to fetch recipes. Please try again.';
                }
            } catch (ConnectionException $e) {
                $error = 'Cannot reach recipe service. Check your connection and try again.';
            }
        }

        return view('recipes.index', [
            'q' => $q,
            'meals' => $meals,
            'error' => $error,
        ]);
    }

    public function show(string $idMeal): View
    {
        $meal = null;
        $error = null;

        try {
            $response = Http::timeout(8)->get('https://www.themealdb.com/api/json/v1/1/lookup.php', [
                'i' => $idMeal,
            ]);

            if ($response->successful()) {
                /** @var array{meals?: array<int, array<string, mixed>>|null} $data */
                $data = $response->json();
                $meal = $data['meals'][0] ?? null;
            } else {
                $error = 'Failed to fetch recipe details.';
            }
        } catch (ConnectionException $e) {
            $error = 'Cannot reach recipe service. Check your connection and try again.';
        }

        return view('recipes.show', [
            'meal' => $meal,
            'error' => $error,
        ]);
    }
}
