# Notification Feature

## Overview
Sistem notifikasi real-time untuk menginformasikan pengguna tentang event penting seperti food expiry, donations, dan meal reminders.

## Components

### Models
- **Notification** (`app/Models/UserNotification.php`) - Model untuk menyimpan user notifications
  - Relationship dengan User (many-to-one)
  - Track read/dismissed status
  - Deduplication support

### Database
- **Notifications Table** - Table untuk menyimpan notifikasi
  - `2026_04_01_000007_create_user_notifications_table.php`
  - Fields: user_id, type, title, message, action_url, read_at, dismissed_at
  - Deduplication dengan dedupe_key
  - Indexed untuk fast queries

### Controller
- **NotificationController** - Handle notification operations
  - `index()` - Tampilkan notifikasi pengguna
  - `markAsRead($id)` - Mark notifikasi sudah dibaca
  - `markAllAsRead()` - Mark semua notifikasi sudah dibaca
  - `dismiss($id)` - Dismiss/sembunyikan notifikasi
  - `delete($id)` - Hapus notifikasi
  - `getUnreadCount()` - Hitung notifikasi yang belum dibaca

### Services
- **NotificationSyncService** (`app/Services/NotificationSyncService.php`) - Generate & sync notifikasi
  - `syncForUser(User)` - Sync notifikasi untuk user tertentu
  - `syncFoodExpiry(User)` - Sync food expiry notifications
  - `syncDonationUpdates(User)` - Sync donation-related notifications
  - `syncMealReminders(User)` - Sync meal plan reminders
  - Respects privacy settings
  - Prevents duplicate notifications with dedupe_key

### Routes
- `GET /notifications` - List user notifications
- `POST /notifications/{id}/read` - Mark as read
- `POST /notifications/read-all` - Mark all as read
- `POST /notifications/{id}/dismiss` - Dismiss notification
- `DELETE /notifications/{id}` - Delete notification
- `GET /api/notifications/unread-count` - Get unread count

### Features Included
✅ User-based notifications
✅ Multiple notification types:
  - Food expiry alerts
  - Donation updates
  - Meal plan reminders
  - System announcements
✅ Read/unread status tracking
✅ Dismiss functionality
✅ Deduplication to prevent duplicates
✅ Privacy setting respect
✅ Action links to relevant pages
✅ Pagination for notification list
✅ Real-time unread count
✅ Bulk operations (mark all as read)
✅ Notification deletion

## Notification Types

### 1. Food Expiry Notifications
- **Trigger**: Food item expiring soon or expired
- **Type**: `food_expiring` / `food_expired`
- **Message**: Shows food name and days until expiry
- **Action**: Link to food item detail page
- **Dedupe Key**: `food:{item_id}:expiring`

### 2. Donation Notifications
- **Trigger**: Donation posted/updated or claimed
- **Type**: `donation_posted` / `donation_claimed`
- **Message**: Shows donation details and donor info
- **Action**: Link to donation detail page
- **Dedupe Key**: `donation:{donation_id}:posted`

### 3. Meal Plan Reminders
- **Trigger**: Upcoming meal plan preparation time
- **Type**: `meal_reminder`
- **Message**: Shows meal plan details
- **Action**: Link to meal plan page
- **Dedupe Key**: `meal:{plan_id}:reminder`

### 4. System Notifications
- **Type**: `system_announcement`
- **Message**: General platform announcements
- **Action**: Optional link to relevant page

## Privacy Settings
- Privacy setting: `privacy_expiry_notifications`
- Users can disable notifications per type
- Privacy preferences are respected before generating notifications
- No notifications sent for disabled types

## Database Schema

### Notifications Table
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| type | string(50) | Notification type (indexed) |
| title | string | Notification title |
| message | text | Notification message body |
| action_label | string | Button/action label |
| action_url | string | URL for action |
| dedupe_key | string | Deduplication key (indexed, unique per user) |
| read_at | timestamp | When marked as read |
| dismissed_at | timestamp | When dismissed |
| created_at | timestamp | Created time |
| updated_at | timestamp | Updated time |

### Indexes
- `user_id, created_at` - For listing user notifications
- `type` - For filtering by type
- `read_at` - For finding unread notifications
- `dismissed_at` - For filtering active notifications
- `dedupe_key` - For deduplication
- `user_id, dedupe_key` (unique) - Unique constraint

## API Responses

### List Notifications
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "type": "food_expiring",
      "title": "Tomato expiring soon",
      "message": "This item will expire in 2 days...",
      "action_label": "View Item",
      "action_url": "/food-items/5",
      "read_at": null,
      "dismissed_at": null,
      "created_at": "2026-04-21T10:30:00Z"
    }
  ],
  "pagination": {
    "total": 15,
    "per_page": 10,
    "current_page": 1
  }
}
```

### Unread Count
```json
{
  "unread_count": 5,
  "total_count": 25
}
```

## Performance Optimization
- Efficient queries with proper indexing
- Pagination for large notification lists
- Cache unread count per user
- Lazy load notification details
- Archive old notifications (optional)

## Testing Strategy
- Test notification generation for each type
- Test deduplication mechanism
- Test privacy setting enforcement
- Test read/unread status updates
- Test dismissal functionality
- Test bulk operations

## Future Enhancements
- Push notifications (mobile)
- Email notifications
- In-app notification bell with real-time updates
- Notification preferences UI
- Scheduled notifications
- Batch notification processing
- Notification templates
- Multi-language support

## Status: Implemented ✅
