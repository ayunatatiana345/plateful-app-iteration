# Plateful App

Aplikasi web untuk mengelola **inventori makanan**, **donasi**, dan **meal plan**, lengkap dengan **dashboard** dan **analytics**.

## Fitur Utama

- **Inventory**: kelola data `FoodItem` (stok dan status)
- **Meal Plans**: susun rencana menu/meal plan
- **Donations**: pencatatan dan pelacakan donasi
- **Analytics**: pencatatan aktivitas melalui `AnalyticsLog`
- **Auth**: halaman autentikasi bawaan Laravel (register + input _household size_ opsional)

## Tech Stack

- **Laravel** (Backend)
- **MySql** (default dev database: `plateful-app`)
- **Vite + Tailwind CSS** (Frontend tooling)

## Prasyarat

- PHP (sesuai kebutuhan Laravel di `composer.json`)
- Composer
- Node.js & npm

## Instalasi (Windows / PowerShell)

1. Install dependency backend
    - `composer install`
2. Install dependency frontend
    - `npm install`
3. Siapkan environment
    - Copy `.env.example` menjadi `.env`
    - Generate app key: `php artisan key:generate`
4. Database
    - Pastikan file mysql ada: `plateful-app`
    - Jalankan migrasi & seeder: `php artisan migrate --seed`
5. Jalankan aplikasi
    - Terminal 1: `php artisan serve`
    - Terminal 2: `npm run dev`

Akses aplikasi di `http://127.0.0.1:8000`.

## Struktur Modul (ringkas)

- Model: `app/Models` (`FoodItem`, `MealPlan`, `Donation`, `AnalyticsLog`, `User`)
- Request/Controller: `app/Http/Requests`, `app/Http/Controllers`
- View: `resources/views`
- Route: `routes/web.php`
- Migration/Seeder: `database/migrations`, `database/seeders`

## Scripts

- `npm run dev` — build assets untuk development
- `npm run build` — build assets untuk production

## Testing

- Jalankan test: `php artisan test`

## Lisensi

Internal project. Jika ingin dipublikasikan, tambahkan informasi lisensi di bagian ini.
