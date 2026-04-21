# Plan Weekly Meals Feature

## Overview
Fitur untuk merencanakan meal harian dalam satu minggu dengan dukungan meal slots, ingredient tracking, dan smart suggestions berdasarkan food inventory yang tersedia.

## Components

### Models
- **MealPlan** (`app/Models/MealPlan.php`) - Model untuk meal plan harian
  - user_id, meal_name, planned_date, meal_slot, status, ingredients_used
  - Relationship dengan User (many-to-one)
  - Relationship dengan MealPlanItem (one-to-many)
  - Relationship dengan MealPlanReservation (one-to-many)

- **MealPlanItem** (`app/Models/MealPlanItem.php`) - Individual item/ingredient dalam meal plan
  - meal_plan_id, food_item_id, quantity, unit
  - Relationship dengan MealPlan
  - Relationship dengan FoodItem

- **MealPlanReservation** (`app/Models/MealPlanReservation.php`) - Reservasi untuk meal items
  - user_id, meal_plan_item_id, quantity
  - Track siapa yang reserve berapa banyak

### Database
- **Meal Plans Table** - `2026_03_18_000002_create_meal_plans_table.php`
- **Meal Plan Items Table** (if exists) - Track ingredients per meal
- **Meal Plan Reservations Table** (if exists) - Track reservations

### Controllers
- **MealPlanController** - Handle meal planning CRUD
  - `index()` - Tampilkan meal plans untuk user
  - `weekly()` - Tampilkan meal plan untuk 1 minggu
  - `store()` - Create meal plan baru
  - `update($id)` - Update meal plan
  - `destroy($id)` - Delete meal plan
  - `getSuggestions()` - Get meal suggestions dari available food
  - `markComplete($id)` - Mark meal plan sebagai completed

### Services
- **MealPlanningService** - Business logic untuk meal planning
  - `generateWeeklyPlan()` - Generate suggestion untuk seminggu
  - `suggestMealsFromInventory()` - Suggest meals berdasarkan available food
  - `validateIngredientAvailability()` - Check ketersediaan ingredients
  - `calculateNutritionSummary()` - Hitung summary nutrisi untuk minggu
  - `optimizeMealPlan()` - Optimize berdasarkan budget/preferensi

- **SuggestedRecipeService** (`app/Services/SuggestedRecipeService.php`) - Provide recipe suggestions
  - `getSuggestedRecipes()` - Get recipe suggestions
  - `getMealIdeas()` - Get meal ideas for meal slots
  - `filterByAvailableIngredients()` - Filter berdasarkan available food

### Routes
- `GET /meal-plans` - List all meal plans (user's)
- `GET /meal-plans/weekly` - Get weekly view
- `GET /meal-plans/suggestions` - Get meal suggestions
- `POST /meal-plans` - Create new meal plan
- `PUT /meal-plans/{id}` - Update meal plan
- `DELETE /meal-plans/{id}` - Delete meal plan
- `POST /meal-plans/{id}/complete` - Mark as completed
- `GET /meal-plans/nutrition-summary` - Get nutrition summary

### Views & UI
- **Weekly meal planner page** - Grid view untuk 7 hari
- **Each day card** dengan meal slots:
  - Breakfast
  - Lunch
  - Dinner
  - Snacks
- **Meal slot editor** - Add/edit meal untuk slot
- **Ingredient list** - Show required ingredients
- **Nutrition calculator** - Display nutrition info
- **Suggestion panel** - Show meal ideas
- **Inventory checker** - Check available ingredients
- **Meal history** - View past meal plans

## Features Included
✅ Weekly meal planning grid (7 days)
✅ Multiple meal slots per day (breakfast, lunch, dinner, snacks)
✅ Organize meals by day & slot
✅ Add multiple items per meal
✅ Ingredient tracking per meal
✅ Smart meal suggestions from available inventory
✅ Nutrition calculation & summary
✅ Inventory compatibility check
✅ Meal history tracking
✅ Mark meal as completed
✅ Copy previous week's plan
✅ Export meal plan
✅ Shared meal planning (family/household)
✅ Meal shopping list generation
✅ Budget tracking per meal/week
✅ Allergen filtering
✅ Dietary preference support

## Meal Slots
1. **Breakfast** - Pagi hari
2. **Lunch** - Siang hari
3. **Dinner** - Malam hari
4. **Snacks** - Cemilan sepanjang hari

## Planning Flow

### Step 1: View Weekly Calendar
- Display 7 hari dengan meal slots kosong
- Show current week atau selected week

### Step 2: Add Meals
- Click on meal slot untuk add meal
- Search/browse meal suggestions
- Select meal atau tambah custom meal

### Step 3: Check Ingredients
- System check available ingredients
- Show warning jika ada ingredient yang tidak tersedia
- Suggest alternative meals

### Step 4: Save Plan
- Save meal plan untuk minggu
- Can save as draft atau finalized

### Step 5: Execute Plan
- View shopping list
- Track completed meals
- Update inventory saat meal dikerjakan

## Meal Suggestion Engine

### Data Sources
- Recipe database
- Available food items dari inventory
- User's favorite meals
- Dietary preferences
- Budget constraints

### Suggestion Logic
1. Analyze available food items
2. Match dengan popular recipes
3. Filter berdasarkan:
   - Dietary preferences
   - Allergies
   - Budget
   - Cooking time
4. Rank by:
   - Ingredient match
   - User preference
   - Nutritional value
   - Time to prepare

## Nutrition Tracking
- Calories tracking
- Macro nutrients (protein, fat, carbs)
- Micro nutrients (vitamins, minerals)
- Daily summary
- Weekly average
- Compare dengan daily recommendations

## Database Schema

### Meal Plans Table
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| meal_name | string | Nama meal |
| planned_date | date | Tanggal meal direncanakan |
| meal_slot | string | Breakfast/Lunch/Dinner/Snacks |
| status | string | planned/completed/cancelled |
| ingredients_used | json | Array of ingredients |
| created_at | timestamp | Created |
| updated_at | timestamp | Updated |

### Meal Plan Items Table (if exists)
| Field | Type | Description |
| id | bigint | Primary key |
| meal_plan_id | bigint | Foreign key to meal_plans |
| food_item_id | bigint | Foreign key to food_items |
| quantity | decimal | Jumlah ingredient |
| unit | string | Satuan (gram, ml, pieces) |

## API Response Format

### Weekly Meal Plan
```json
{
  "week": "2026-04-21 to 2026-04-27",
  "days": [
    {
      "date": "2026-04-21",
      "day_name": "Monday",
      "meals": {
        "breakfast": {
          "name": "Eggs & Toast",
          "ingredients": ["Eggs", "Bread", "Butter"],
          "status": "planned"
        },
        "lunch": {
          "name": "Chicken Salad",
          "ingredients": ["Chicken", "Lettuce", "Tomato"],
          "status": "planned"
        },
        "dinner": null
      }
    }
  ],
  "nutrition_summary": {
    "total_calories": 12500,
    "avg_daily_calories": 1786,
    "macros": { "protein": 350, "fat": 420, "carbs": 1200 }
  },
  "shopping_list": ["Eggs", "Bread", "Chicken", "Lettuce", "Tomato"]
}
```

### Meal Suggestions
```json
{
  "suggestions": [
    {
      "id": 1,
      "name": "Pasta Carbonara",
      "ingredients": ["Pasta", "Eggs", "Bacon", "Parmesan"],
      "prep_time": 20,
      "calories": 450,
      "match_score": 95,
      "available_ingredients": 4/4
    }
  ]
}
```

## Performance Considerations
- Cache meal suggestions
- Index meal_plans by user_id & planned_date
- Lazy load nutrition calculations
- Batch nutrition calculations weekly
- Cache inventory availability

## Security & Permissions
- Users can only plan their own meals
- Share meal plans with family members
- Household-based planning
- Privacy settings for shared meals

## Testing Strategy
- Test meal plan creation & updates
- Test ingredient validation
- Test suggestion engine accuracy
- Test nutrition calculations
- Test sharing & permissions

## Future Enhancements
- AI-powered meal planning
- Dietary restrictions & allergies
- Meal prep reminders
- Integration dengan grocery services
- Cost analysis & budgeting
- Recipe scaling based on household size
- Seasonal ingredient suggestions
- Health goal-based planning

## Status: Ready for Implementation ✅
