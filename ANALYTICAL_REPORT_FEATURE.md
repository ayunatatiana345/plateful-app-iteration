# Analytical Report Feature

## Overview
Fitur untuk menghasilkan dan menampilkan laporan analitik mendalam tentang data platform Plateful.

## Components

### Models
- **AnalyticsLog** (`app/Models/AnalyticsLog.php`) - Model untuk menyimpan log aktivitas & analytics data
- **FoodItem** - Data makanan untuk analisis inventory
- **MealPlan** - Data meal plans untuk analisis perencanaan
- **Donation** - Data donasi untuk analisis amal/community impact
- **User** - Data pengguna untuk analisis demografis

### Controller
- **AnalyticsController** - Handle laporan analytics
  - `index()` - Dashboard dengan ringkasan analitik
  - `foodAnalytics()` - Laporan food inventory analytics
  - `donationAnalytics()` - Laporan donation analytics
  - `mealPlanAnalytics()` - Laporan meal plan analytics
  - `userAnalytics()` - Laporan user/community analytics
  - `exportReport()` - Export laporan ke PDF/Excel

### Database
- **AnalyticsLog Table** - Menyimpan:
  - User activity logs
  - System events
  - Analytics metrics
  - Timestamps untuk tracking
- **Factories**: `AnalyticsLogFactory` untuk test data

### Services
- **AnalyticsService** - Generate reports & aggregate data
  - Calculate metrics
  - Time-series analysis
  - Trend detection
  - Statistical calculations

### Views
- Analytics dashboard page
- Multiple report tabs/views:
  - Food inventory analytics
  - Donation impact report
  - Meal planning statistics
  - User engagement metrics
- Visualization charts & graphs
- Export functionality

### Routes
- `GET /analytics` - Dashboard
- `GET /analytics/food` - Food analytics report
- `GET /analytics/donations` - Donation analytics report
- `GET /analytics/meal-plans` - Meal plan analytics report
- `GET /analytics/users` - User analytics report
- `GET /analytics/export?type={type}&format={format}` - Export reports

### Features Included
✅ Real-time analytics dashboard
✅ Food inventory metrics (quantity, waste, usage trends)
✅ Donation tracking & impact metrics
✅ Meal plan analysis & statistics
✅ User engagement & activity tracking
✅ Time period filtering (daily, weekly, monthly, yearly)
✅ Date range selection for custom reports
✅ Export to PDF format
✅ Export to Excel/CSV format
✅ Data visualization with charts
✅ Comparative analysis (YoY, MoM)
✅ Performance indicators & KPIs

## Report Types

### 1. Food Inventory Analytics
- Total items in inventory
- Items by status (fresh, expiring, expired)
- Storage usage trends
- Waste metrics
- Most used food items
- Low stock alerts

### 2. Donation Analytics
- Total donations received
- Donation frequency
- Average donation value
- Donor demographics
- Impact metrics
- Trending donation items

### 3. Meal Plan Analytics
- Total meal plans created
- Meal plan usage frequency
- Most popular meals
- Nutritional summaries
- User preferences
- Planning trends

### 4. User Analytics
- Active user count
- User growth trends
- User engagement metrics
- User retention rates
- Activity distribution
- User segments

## Key Metrics & KPIs
- Food items managed
- Donations facilitated
- Community members engaged
- Meals planned/coordinated
- System uptime & performance
- Data accuracy metrics

## Data Visualization
- Line charts for trends over time
- Bar charts for comparisons
- Pie charts for distributions
- Heat maps for activity patterns
- Gauge charts for KPI progress

## Export Formats
- PDF with formatted reports & charts
- Excel/CSV for data analysis
- JSON for API consumption
- Print-friendly HTML

## Performance Considerations
- Aggregate data nightly for reports
- Cache analytics calculations
- Implement data retention policies
- Index analytics queries
- Optimize data aggregation queries

## Security & Permissions
- Admin-only access to analytics
- Log all analytics data access
- Anonymize user data where applicable
- Secure export with authentication

## Status: Implemented ✅
