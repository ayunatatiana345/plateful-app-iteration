# Plan Weekly Meals - Implementation Details

## Architecture Overview

### Data Flow
```
User Weekly View → Controller → Service → Models → Database
                       ↓
                  Suggestion Engine
                       ↓
                  Nutrition Calculator
                       ↓
                  View/API Response
```

## Component Responsibilities

### MealPlanController
```php
class MealPlanController extends Controller
{
    // Display weekly meal plan grid
    public function weekly(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfWeek());
        $user = auth()->user();
        
        // Fetch meal plans for this week
        // Group by date and meal_slot
        // Include ingredient details
        // Return weekly view
    }

    // Get meal suggestions for planning
    public function getSuggestions(Request $request)
    {
        $mealSlot = $request->input('slot'); // breakfast, lunch, dinner, snacks
        $date = $request->input('date');
        
        // Get suggested recipes/meals
        // Filter by available ingredients
        // Rank by relevance
        // Return top suggestions
    }

    // Create new meal plan
    public function store(MealPlanRequest $request)
    {
        $validated = $request->validated();
        
        // Validate date & slot
        // Check ingredient availability
        // Create meal plan record
        // Save ingredients
        // Return created meal plan
    }

    // Update meal plan
    public function update($id, MealPlanRequest $request)
    {
        $mealPlan = MealPlan::findOrFail($id);
        // Authorize user ownership
        // Update meal details
        // Update ingredients
        // Return updated plan
    }

    // Mark meal plan as completed
    public function markComplete($id)
    {
        $mealPlan = MealPlan::findOrFail($id);
        $mealPlan->update(['status' => 'completed']);
        // Trigger notification
        // Update inventory if needed
        return $mealPlan;
    }
}
```

### MealPlanningService
```php
class MealPlanningService
{
    private SuggestedRecipeService $recipeService;
    private FoodStatusService $foodStatusService;

    // Generate suggestions untuk full week
    public function generateWeeklyPlan(User $user, \DateTime $startDate): array
    {
        $suggestions = [];
        $mealSlots = ['breakfast', 'lunch', 'dinner', 'snacks'];
        
        for ($day = 0; $day < 7; $day++) {
            $date = (clone $startDate)->addDays($day);
            foreach ($mealSlots as $slot) {
                $suggestions[$date->format('Y-m-d')][$slot] = 
                    $this->suggestMealsFromInventory($user, $slot, $date);
            }
        }
        
        return $suggestions;
    }

    // Get meal suggestions based on available ingredients
    public function suggestMealsFromInventory(
        User $user, 
        string $mealSlot, 
        \DateTime $date
    ): array
    {
        // Get available food items
        $available = FoodItem::where('user_id', $user->id)
                            ->where('status', '!=', 'expired')
                            ->pluck('name')
                            ->toArray();

        // Get recipes for this meal slot
        $recipes = $this->recipeService->getMealIdeas($mealSlot);

        // Score recipes based on ingredient match
        $scored = array_map(function($recipe) use ($available) {
            return [
                ...recipe,
                'match_score' => $this->calculateMatchScore($recipe, $available)
            ];
        }, $recipes);

        // Sort by match score & return top 5
        return collect($scored)
            ->sortByDesc('match_score')
            ->take(5)
            ->values()
            ->all();
    }

    // Validate ingredient availability
    public function validateIngredientAvailability(
        User $user, 
        array $ingredients
    ): array
    {
        $available = FoodItem::where('user_id', $user->id)
                            ->whereIn('name', $ingredients)
                            ->pluck('name')
                            ->toArray();

        $missing = array_diff($ingredients, $available);

        return [
            'all_available' => empty($missing),
            'available_count' => count($available),
            'missing_ingredients' => $missing,
            'missing_count' => count($missing)
        ];
    }

    // Calculate nutrition summary for week
    public function calculateNutritionSummary(User $user, \DateTime $startDate): array
    {
        $mealPlans = MealPlan::where('user_id', $user->id)
                            ->whereBetween('planned_date', [
                                $startDate,
                                (clone $startDate)->addDays(6)
                            ])
                            ->get();

        $totals = [
            'calories' => 0,
            'protein' => 0,
            'fat' => 0,
            'carbs' => 0
        ];

        foreach ($mealPlans as $plan) {
            $nutrition = $this->getMealNutrition($plan);
            foreach ($totals as $key => $_) {
                $totals[$key] += $nutrition[$key] ?? 0;
            }
        }

        return [
            'totals' => $totals,
            'daily_average' => [
                'calories' => $totals['calories'] / 7,
                'protein' => $totals['protein'] / 7,
                'fat' => $totals['fat'] / 7,
                'carbs' => $totals['carbs'] / 7
            ]
        ];
    }

    // Get nutrition info untuk meal
    private function getMealNutrition(MealPlan $plan): array
    {
        // Lookup nutrition data dari ingredients
        // Can use external API atau local database
        // Return aggregated nutrition values
        return [
            'calories' => 450,
            'protein' => 20,
            'fat' => 15,
            'carbs' => 60
        ];
    }

    // Calculate match score between recipe & available ingredients
    private function calculateMatchScore(array $recipe, array $available): float
    {
        $ingredients = $recipe['ingredients'] ?? [];
        if (empty($ingredients)) {
            return 0;
        }

        $matches = count(array_intersect($ingredients, $available));
        return ($matches / count($ingredients)) * 100;
    }
}
```

### SuggestedRecipeService Integration
```php
class SuggestedRecipeService
{
    // Get meal ideas untuk specific meal slot
    public function getMealIdeas(string $mealSlot): array
    {
        return match($mealSlot) {
            'breakfast' => [
                ['name' => 'Eggs & Toast', 'ingredients' => ['Eggs', 'Bread']],
                ['name' => 'Oatmeal', 'ingredients' => ['Oats', 'Milk']],
                ['name' => 'Pancakes', 'ingredients' => ['Flour', 'Eggs', 'Milk']]
            ],
            'lunch' => [
                ['name' => 'Chicken Salad', 'ingredients' => ['Chicken', 'Lettuce', 'Tomato']],
                ['name' => 'Pasta', 'ingredients' => ['Pasta', 'Tomato Sauce', 'Cheese']]
            ],
            'dinner' => [
                ['name' => 'Grilled Fish', 'ingredients' => ['Fish', 'Lemon', 'Olive Oil']],
                ['name' => 'Curry', 'ingredients' => ['Rice', 'Curry Spice', 'Chicken']]
            ],
            'snacks' => [
                ['name' => 'Apple', 'ingredients' => ['Apple']],
                ['name' => 'Nuts & Fruits', 'ingredients' => ['Nuts', 'Dried Fruits']]
            ],
            default => []
        };
    }

    // Filter recipes by available ingredients
    public function filterByAvailableIngredients(array $recipes, array $available): array
    {
        return array_filter($recipes, function($recipe) use ($available) {
            $ingredients = $recipe['ingredients'] ?? [];
            $match_count = count(array_intersect($ingredients, $available));
            return $match_count > 0; // At least some ingredients available
        });
    }
}
```

## Weekly Meal Planning Flow

### User Workflow
1. **View Weekly Planner**
   - Navigate to `/meal-plans/weekly`
   - Display 7-day grid with meal slots

2. **Select a Meal Slot**
   - Click breakfast/lunch/dinner/snacks for specific day
   - Open meal selection modal

3. **Get Suggestions**
   - System suggests meals based on available food
   - Show match percentage
   - Show nutrition info

4. **Add Meal**
   - Select suggested meal OR search database
   - Add custom meal
   - System validates ingredients

5. **Ingredient Check**
   - Show available ingredients (green)
   - Show missing ingredients (orange/red)
   - Allow proceeding anyway or choose alternative

6. **Save Plan**
   - Save meal for that slot & date
   - Update weekly view
   - Calculate nutrition summary

7. **View Summary**
   - Show nutrition totals for week
   - Generate shopping list
   - View meal history

### Database Queries

#### Get Weekly Meal Plans
```sql
SELECT * FROM meal_plans
WHERE user_id = ?
AND planned_date BETWEEN ? AND ?
ORDER BY planned_date, 
  FIELD(meal_slot, 'breakfast', 'lunch', 'dinner', 'snacks')
```

#### Get Available Food Items
```sql
SELECT * FROM food_items
WHERE user_id = ?
AND status IN ('fresh', 'ready_to_use')
ORDER BY expiration_date
```

#### Find Duplicate Meal Plans
```sql
SELECT * FROM meal_plans
WHERE user_id = ?
AND planned_date = ?
AND meal_slot = ?
```

### Optimization Strategy

#### Caching
- Cache meal suggestions (30 min TTL)
- Cache user's inventory (5 min TTL)
- Cache nutrition calculations (1 hour TTL)

#### Query Optimization
- Index on (user_id, planned_date, meal_slot)
- Index on food_items(user_id, status)
- Eager load relationships

#### Lazy Loading
- Load ingredients on meal detail view
- Load nutrition on demand
- Load reservation data separately

## Frontend Components

### WeeklyMealPlanner Component
```jsx
// Weekly grid with 7 days
// Each day shows 4 meal slots
// Click to add meal
// Drag-drop to rearrange
// Shows ingredient highlights
```

### MealSlotCard Component
```jsx
// Display meal name
// Show ingredients count
// Show nutrition badge
// Quick action buttons (edit, delete)
// Drag handle for reordering
```

### MealSuggestionModal Component
```jsx
// Search suggestions
// Filter by meal slot
// Show match score
// Nutrition preview
// Select button
```

### NutritionSummary Component
```jsx
// Display total calories
// Show macros breakdown
// Weekly average
// Compare with recommendations
```

## Testing Strategy

### Unit Tests
```php
// Test meal plan creation
// Test ingredient validation
// Test nutrition calculation
// Test suggestion ranking
```

### Integration Tests
```php
// Test weekly meal plan flow
// Test ingredient availability check
// Test nutrition summary calculation
// Test suggestions with real inventory
```

### Feature Tests
```php
// Test complete weekly planning workflow
// Test API responses
// Test authorization
// Test pagination
```

## Performance Metrics

### Queries to Monitor
- Weekly meal plan fetch
- Ingredient availability query
- Suggestion generation
- Nutrition calculation

### Cache Hit Rate Targets
- Suggestions: > 80%
- Inventory: > 85%
- Nutrition: > 70%

### Load Time Targets
- Weekly view: < 1s
- Suggestions load: < 500ms
- Save meal plan: < 500ms

## Database Maintenance

### Maintenance Jobs
- Archive meal plans older than 1 year
- Remove orphaned meal plan items
- Rebuild indexes monthly
- Analyze query performance

## Status: Architecture Defined ✅
