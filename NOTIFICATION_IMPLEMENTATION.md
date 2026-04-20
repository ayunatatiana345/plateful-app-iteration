# Notification - Implementation Details

## Architecture Overview

### Data Flow
```
Event Trigger → NotificationSyncService → Notification Model → Database
     ↓
   Display in View / API Response
```

### Notification Lifecycle
1. **Generation**: Service generates notification based on condition
2. **Deduplication**: Check if notification already exists using dedupe_key
3. **Storage**: Save to database with user association
4. **Display**: Show in notification center or bell
5. **Interaction**: User can read, dismiss, or delete
6. **Archival**: Old notifications can be archived (optional)

## Component Details

### NotificationController Implementation
```php
class NotificationController extends Controller
{
    // List all notifications for authenticated user
    public function index(Request $request)
    {
        // Fetch with pagination
        // Order by created_at DESC
        // Include unread count in response
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        // Update read_at timestamp
        // Return updated notification
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        // Update read_at for all user notifications
        // Return count of updated records
    }

    // Dismiss notification (hide but don't delete)
    public function dismiss($id)
    {
        // Update dismissed_at timestamp
        // Still queryable but marked as dismissed
    }

    // Delete notification permanently
    public function delete($id)
    {
        // Permanently delete from database
    }

    // Get unread count
    public function getUnreadCount()
    {
        // Count where read_at is null and dismissed_at is null
        // Can be cached for performance
    }
}
```

### NotificationSyncService Implementation
```php
class NotificationSyncService
{
    public function syncForUser(User $user): void
    {
        // Call sync methods for each notification type
        $this->syncFoodExpiry($user);
        $this->syncDonationUpdates($user);
        $this->syncMealReminders($user);
    }

    private function syncFoodExpiry(User $user): void
    {
        // Check privacy settings
        if (!$user->privacy_expiry_notifications) {
            return;
        }

        // Find expiring/expired food items
        $items = FoodItem::where('user_id', $user->id)
                        ->whereIn('status', [STATUS_EXPIRING, STATUS_EXPIRED])
                        ->get();

        foreach ($items as $item) {
            // Create notification with dedupe_key
            // Prevents duplicates for same item
        }
    }

    private function syncDonationUpdates(User $user): void
    {
        // Find recent donation changes
        // Create notifications for relevant users
    }

    private function syncMealReminders(User $user): void
    {
        // Find upcoming meal plans
        // Create reminder notifications
    }
}
```

## Notification Generation Process

### Step 1: Trigger Detection
- Food expiry check (scheduled or on-demand)
- Donation updates (on donation create/update)
- Meal plan reminders (scheduled)
- System announcements (manual)

### Step 2: User Identification
- Find affected users (owner, donors, participants)
- Apply privacy filters
- Check notification preferences

### Step 3: Deduplication
- Generate dedupe_key based on event + resource
- Check if notification already exists
- Update existing or create new

### Step 4: Notification Storage
```php
Notification::updateOrCreate(
    [
        'user_id' => $userId,
        'dedupe_key' => 'food:123:expiring'
    ],
    [
        'type' => 'food_expiring',
        'title' => 'Apple expiring soon',
        'message' => 'Your apple will expire in 2 days',
        'action_url' => '/food-items/123'
    ]
);
```

## Frontend Integration

### Notification Bell Component
```javascript
// Real-time unread count
// Click to open notification dropdown
// Show latest notifications
// Mark as read on interaction
```

### Notification Center Page
```
- Header with filters
- List of notifications
- Mark as read / Mark all as read
- Delete / Dismiss buttons
- Pagination
- Empty state
```

### Notification Card Component
```
- Badge (unread indicator)
- Icon based on type
- Title and preview message
- Timestamp
- Action button
```

## Query Optimization

### Key Queries

#### Get Unread Notifications
```sql
SELECT * FROM notifications
WHERE user_id = ? 
AND read_at IS NULL 
AND dismissed_at IS NULL
ORDER BY created_at DESC
LIMIT 20
```

#### Count Unread
```sql
SELECT COUNT(*) FROM notifications
WHERE user_id = ?
AND read_at IS NULL
AND dismissed_at IS NULL
```

#### Find Duplicates
```sql
SELECT * FROM notifications
WHERE user_id = ?
AND dedupe_key = ?
AND dismissed_at IS NULL
```

### Indexes Used
- `(user_id, created_at)` - For listing
- `(user_id, read_at, dismissed_at)` - For unread count
- `(user_id, dedupe_key)` - For deduplication

## Caching Strategy

### What to Cache
- Unread count per user
- Recent notifications (last 10)
- Notification preferences

### Cache Keys
- `notifications:unread-count:{user_id}`
- `notifications:recent:{user_id}`
- `notifications:preferences:{user_id}`

### Invalidation
- Clear on new notification
- Clear on read/dismiss/delete
- TTL: 5 minutes for counts, 1 hour for list

## Scheduling & Background Jobs

### Notification Sync Job (if using queues)
```php
// Run every 10 minutes or on-demand
- Check all food items for expiry
- Check all donations for updates
- Generate notifications
- Handle failures gracefully
```

### Current Implementation
- Sync on-demand when triggered
- Called from controllers/services
- Respects privacy settings
- Deduplicates automatically

## Security Considerations

### Authorization
- Users can only see their own notifications
- Only admins can see all notifications
- Privacy settings are enforced

### Data Validation
- Validate notification type
- Sanitize message content
- Validate URLs in action_url

### Rate Limiting
- Limit notification generation frequency
- Prevent notification spam
- Apply per-user rate limits

## Testing Strategy

### Unit Tests
```php
// Test notification creation
// Test deduplication
// Test privacy setting enforcement
// Test status updates
```

### Feature Tests
```php
// Test notification generation workflow
// Test controller actions
// Test authorization
// Test API responses
```

### Integration Tests
```php
// Test with FoodStatusService
// Test with DonationModel
// Test with MealPlanModel
```

## Monitoring & Metrics

### Key Metrics
- Total notifications generated
- Unread notification rate
- Read rate by type
- Delete rate
- Average time to read

### Alerts
- Failed notification generation
- Database query slowness
- Cache miss rate too high

## Scalability Considerations

### Current Limitations
- Single-user generation
- No real-time push notifications
- Database-backed storage only

### Future Scalability
- Batch notification generation
- Queue-based processing
- Real-time WebSocket updates
- Redis caching layer
- Archive old notifications to cold storage

## Database Maintenance

### Cleanup Tasks
- Archive notifications older than 90 days
- Delete dismissed notifications after 30 days
- Compact notification tables
- Rebuild indexes periodically

### Commands (if implemented)
```bash
php artisan notifications:archive
php artisan notifications:cleanup
php artisan notifications:rebuild-indexes
```

## Status: Architecture Documented ✅
