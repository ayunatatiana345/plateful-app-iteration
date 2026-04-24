# Plateful App

A web application for managing **food inventory**, **donations**, and **meal plans**, complete with a **dashboard** and **analytics**.

## Main Fiture

- **Inventory**: Manage `FoodItem` data (stock and status)
- **Meal Plans**: create meal plans
- **Donations**: record and track donations
- **Analytics**: log activity via `AnalyticsLog`
- **Auth**: Laravel's built-in authentication page (sign up + optional `household size` input)

## Tech Stack

- **Laravel** (Backend)
- **MySql** (default dev database: `plateful-app`)
- **Vite + Tailwind CSS** (Frontend tooling)

## Prerequisites

- PHP (as specified by Laravel in `composer.json`)
- Composer
- Node.js & npm

## Installation (Windows / PowerShell)

1. Install backend dependencies
    - `composer install`
2. Install frontend dependencies
    - `npm install`
3. Set up the environment
    - Copy `.env.example` to `.env`
    - Generate app key: `php artisan key:generate`
4. Database
    - Ensure the MySQL database exists: `plateful-app`
    - Run migrations & seeder: `php artisan migrate --seed`
5. Run the application
    - Terminal 1: `php artisan serve`
    - Terminal 2: `npm run dev`

Access the application at `http://127.0.0.1:8000`.

## Module Structure (Summary)

- Model: `app/Models` (`FoodItem`, `MealPlan`, `Donation`, `AnalyticsLog`, `User`)
- Request/Controller: `app/Http/Requests`, `app/Http/Controllers`
- View: `resources/views`
- Route: `routes/web.php`
- Migration/Seeder: `database/migrations`, `database/seeders`

## Scripts

- `npm run dev` — build assets for development
- `npm run build` — build assets for production

## Testing

- Run the tests: `php artisan test`

## License

Internal project. If you plan to publish this, add license information here.
