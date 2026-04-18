# Browse Food Item Feature

## Overview
Fitur untuk browse/melihat daftar food items yang tersedia dengan berbagai filter dan search functionality.

## Components

### Controllers
- **FoodItemController** - Handle browse food items logic
  - `index()` - Tampilkan daftar semua food items
  - `show($id)` - Tampilkan detail food item tertentu
  - `search()` - Search food items berdasarkan keyword
  - `filter()` - Filter food items berdasarkan status/kategori

### Models
- **FoodItem** (`app/Models/FoodItem.php`) - Model untuk data item makanan
  - Relationship dengan meals/donations jika ada
  - Status tracking
  - Image associations

### Views & UI
- Browse page - Tampilkan list/grid food items
- Detail page - Tampilkan detail food item
- Search & Filter components
- Image gallery untuk food items

### Routes
- `GET /food-items` - List semua food items
- `GET /food-items/{id}` - Detail food item
- `GET /food-items/search?q={keyword}` - Search functionality
- `GET /food-items/filter?status={status}` - Filter by status

### Services
- **FoodStatusService** - Determine food status (fresh, expiring, expired, etc.)
- Food browsing logic & business rules

### Features Included
✅ List all available food items
✅ View detailed information per item
✅ Search food by name/description
✅ Filter by status (fresh, expiring, expired)
✅ View food images
✅ Pagination support
✅ Sort by various criteria (date, name, quantity)
✅ Responsive UI for mobile & desktop

## Database Tables Used
- `food_items` - Store food inventory
- Related timestamp fields for tracking

## API Endpoints
- `/api/food-items` - JSON list of food items
- `/api/food-items/{id}` - JSON detail food item

## Status: Ready for Implementation ✅
