# Browse Food Item - Implementation Details

## Feature Flow

### User Journey
1. User navigates to browse page
2. System displays grid/list of all available food items
3. User can:
   - View food details (tap/click item)
   - Search by name/keyword
   - Filter by status (fresh, expiring, expired)
   - Sort by various criteria
   - View food images

### Data Flow
```
Request → Route → Controller → Service → Model → View
```

1. **Request**: User requests `/food-items` or `/food-items?search=...`
2. **Route**: Handled by `routes/web.php` or `routes/api.php`
3. **Controller**: `FoodItemController@index()` or `@search()`
4. **Service**: `FoodStatusService` determines item status
5. **Model**: `FoodItem` fetches data with filters/sorting
6. **View**: Returns HTML (Blade template) or JSON (API response)

## Integration with Existing Features

### FoodItem Model Integration
- Uses existing `FoodItem` model with all attributes
- Leverages timestamps (created_at, updated_at, used_at)
- Uses image_path field for food photos

### Status Determination
- `FoodStatusService` evaluates:
  - Item age
  - Expiration date
  - Current quantity
  - Returns: fresh, expiring-soon, expired

### Search & Filter Logic
- Search: Full-text across name, description
- Filter by status: Uses `FoodStatusService` evaluation
- Sort: by name, date added, quantity, status

## UI Components

### Browse Page Layout
- Header with search bar
- Filter sidebar or dropdown
- Grid/List view toggle
- Food items display
- Pagination controls
- Load more button (mobile-friendly)

### Item Card Components
- Food image (thumbnail)
- Food name
- Status badge (fresh/expiring/expired)
- Quantity available
- Quick action buttons (View Details)

### Detail Page
- Large food image
- Full details:
  - Name, description
  - Quantity available
  - Status with timestamp info
  - Added date
  - Related meals/donations (if any)
- Related items section

## Performance Considerations
- Eager load relationships
- Implement pagination (20-50 items per page)
- Cache food status calculations
- Optimize image display (lazy loading, thumbnails)
- Index database queries on frequently filtered columns

## Testing
- Test search with various keywords
- Test filtering by all status types
- Test pagination edge cases
- Test image loading
- Test responsive design

## Status: Design Documented ✅
