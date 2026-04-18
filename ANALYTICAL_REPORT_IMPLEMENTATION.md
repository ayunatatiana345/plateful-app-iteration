# Analytical Report - Implementation Details

## Architecture

### Data Flow for Report Generation
```
User Request → Route → Controller → Service → Models → Database
                         ↓
                   Analytics Service
                   (Aggregation & Calculations)
                         ↓
                   Data Formatting
                         ↓
                   View Rendering / PDF Export
```

## Component Responsibilities

### AnalyticsController
```php
// Handles user requests for reports
- Validates request parameters
- Calls appropriate analytics service methods
- Returns formatted response (HTML/PDF/JSON)
- Enforces authorization checks
```

### AnalyticsService
```php
// Core business logic for analytics
Methods:
- getDashboardMetrics() - Get dashboard summary data
- getFoodAnalytics(filters) - Food inventory analytics
- getDonationAnalytics(filters) - Donation analytics
- getMealPlanAnalytics(filters) - Meal plan analytics
- getUserAnalytics(filters) - User engagement analytics
- calculateTrends(data) - Trend analysis
- compareWithPreviousPeriod() - YoY/MoM comparisons
- generateExportData() - Prepare data for export
```

### Models Used
- **AnalyticsLog**: Source of truth for logging events
- **FoodItem**: Query for inventory data
- **Donation**: Query for donation data
- **MealPlan**: Query for meal planning data
- **User**: Query for user data

## Database Queries

### Key Queries
1. Count active food items by status
2. Sum donation amounts by period
3. Count meal plans created by period
4. Aggregate user activity metrics
5. Calculate growth rates
6. Identify trends over time

### Optimization Strategy
- Use database aggregation functions (COUNT, SUM, AVG)
- Index columns used in WHERE/GROUP BY clauses
- Cache computed metrics
- Archive old AnalyticsLog entries

## Report Generation Process

### Step 1: Data Collection
```
Aggregate data from relevant models
Apply filters (date range, status, category)
Group data as needed
```

### Step 2: Calculations
```
Calculate metrics (totals, averages, percentages)
Determine trends and changes
Identify anomalies/alerts
```

### Step 3: Formatting
```
Format numbers with proper currency/units
Create readable labels
Prepare for visualization
```

### Step 4: Presentation
```
Generate HTML view with charts
Create PDF with formatted layout
Export to Excel with multiple sheets
```

## API Response Format

### Dashboard Response
```json
{
  "metrics": {
    "total_food_items": 150,
    "items_expiring_soon": 12,
    "total_donations": 25000,
    "active_users": 350,
    "meal_plans_this_month": 45
  },
  "trends": {
    "food_usage": "trending_up",
    "donations": "stable",
    "user_growth": "high_growth"
  },
  "period": "2026-04-01 to 2026-04-18"
}
```

### Detailed Report Response
```json
{
  "report_type": "food_analytics",
  "data": [
    {
      "date": "2026-04-10",
      "total_items": 150,
      "fresh": 120,
      "expiring": 20,
      "expired": 10
    }
  ],
  "summary": {
    "average_daily": 142,
    "trend": "increasing",
    "alerts": []
  }
}
```

## Frontend Integration

### Dashboard Components
- **MetricCard** - Display KPI values with trends
- **ChartComponent** - Render analytics charts
- **FilterPanel** - Select date range & filters
- **ExportButton** - Trigger report download
- **ReportTable** - Display detailed data

### Chart Libraries
- Chart.js for interactive charts
- ApexCharts for advanced visualizations
- D3.js for custom visualizations

## Export Implementation

### PDF Export
1. Collect report data
2. Format into PDF template
3. Include charts/graphics
4. Generate PDF file
5. Stream to user download

### Excel Export
1. Collect report data
2. Format into sheet structure
3. Create multiple sheets for different sections
4. Apply formatting & styles
5. Generate Excel file
6. Stream to user download

### Tools
- `barryvdh/laravel-dompdf` for PDF generation
- `phpoffice/phpspreadsheet` for Excel generation

## Caching Strategy

### Cache Keys
- `analytics:dashboard-{period}` - Dashboard metrics
- `analytics:food-{filter}` - Food analytics
- `analytics:donations-{month}` - Donation metrics

### Cache Duration
- Real-time metrics: 5 minutes
- Daily reports: 24 hours
- Monthly reports: 30 days

### Invalidation
- Clear cache on new data entry
- Scheduled cache refresh
- Manual clear button for admins

## Performance Metrics to Track

### System Performance
- Report generation time
- Query execution time
- Page load time
- Export generation time

### Data Metrics
- Data freshness
- Query result sizes
- Cache hit rate
- Database load

## Testing Strategy

### Unit Tests
- Service method calculations
- Data aggregation logic
- Formula accuracy

### Integration Tests
- Database queries return expected data
- Service integration with models
- Controller authorization checks

### Feature Tests
- Report generation
- Export functionality
- Date filtering
- Data accuracy in reports

## Future Enhancements
- Real-time dashboard updates with WebSockets
- AI-powered insights & predictions
- Custom report builder
- Scheduled report emails
- Data visualization customization
- Drill-down capabilities
- Anomaly detection
- Comparative analytics between periods

## Status: Architecture Designed ✅
