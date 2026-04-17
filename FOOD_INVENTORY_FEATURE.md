# Food Inventory Management Feature

## Overview
Manage food inventory system untuk tracking dan management makanan yang tersedia.

## Components

### Models
- **FoodItem** (`app/Models/FoodItem.php`) - Model untuk data item makanan

### Database
- Migrations untuk food items table
  - `2026_03_18_000001_create_food_items_table.php`
  - `2026_03_19_000005_add_image_path_to_food_items_table.php`
  - `2026_04_01_000006_add_used_at_to_food_items_table.php`
  - `2026_04_01_000007_add_fields_to_food_items_table.php`

### Features Included
- Create food items
- Track food inventory/stock
- Food status management (menggunakan FoodStatusService)
- Image storage untuk food items
- Usage tracking (used_at field)

### Services
- **FoodStatusService** (`app/Services/FoodStatusService.php`) - Service untuk manage status makanan

### Factories
- **FoodItemFactory** (`database/factories/FoodItemFactory.php`) - Factory untuk seeding test data

## Database Schema
Food items table includes:
- Basic info: name, description
- Quantity/inventory tracking
- Image path untuk foto makanan
- Used timestamp untuk tracking penggunaan
- Additional fields untuk requirements

## Status: Implemented ✅
