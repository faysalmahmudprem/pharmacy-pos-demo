# Pharmacy POS (Point of Sale) System

A robust and efficient Point of Sale system built with Laravel 10, designed specifically for pharmacy management.

## 🚀 Features

- **Intuitive POS Interface:** Quickly process sales with real-time calculations.
- **Inventory Management:** Comprehensive medicine database tracking generic names, brands, categories, and stock levels.
- **Customer Dues & Payments:** Manage customer accounts, track outstanding balances, and record payments.
- **Invoice Generation:** Professional invoices viewable in-browser or exportable as PDF.
- **Real-time Stock Tracking:** Automatically deducts stock upon sale completion.
- **Pre-seeded Database:** Includes over 100+ common medicines across categories like Pain, Antibiotics, Gastric, Diabetes, etc.

## 🛠️ Tech Stack

- **Framework:** [Laravel 10](https://laravel.com/)
- **Frontend Bundler:** [Vite](https://vitejs.dev/)
- **PDF Engine:** [Laravel-DomPDF](https://github.com/barryvdh/laravel-dompdf)
- **Styling:** Vanilla CSS / Blade Templates
- **HTTP Client:** Axios

## 📋 Prerequisites

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL or any supported SQL database

## ⚙️ Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd pharmacy-pos
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   ```bash
   cp .env.example .env
   ```
   *Configure your database settings in the `.env` file.*

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

7. **Compile Assets:**
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

8. **Start the Server:**
   ```bash
   php artisan serve
   ```

## 📂 Project Structure

- `app/Http/Controllers`: Business logic for POS, Sales, Medicines, and Customers.
- `app/Models`: Eloquent models representing the database schema.
- `database/migrations`: Database schema definitions.
- `database/seeders`: Initial data including a large medicine database.
- `resources/views`: UI templates (Blade).
- `routes/web.php`: Application routing.
- `tests/Feature`: Automated tests for sale flows and payments.

## 🧪 Running Tests

```bash
php artisan test
```

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
