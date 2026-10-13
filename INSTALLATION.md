# Installation Guide - Business Connect Platform

This guide outlines step-by-step instructions to set up and run **Business Connect** on your local machine using XAMPP, Laragon, WAMP, or SQLite.

---

## 📋 System Requirements

- **PHP**: 8.2 or 8.3+
- **Database**: MySQL 8.0+ / MariaDB 10.4+ (or SQLite for quick testing)
- **Composer**: 2.x
- **Node.js & NPM**: Node 18+ and NPM 9+

---

## 🛠️ Step-by-Step Installation (Laravel Backend)

### 1. Open Terminal in Backend Directory
```bash
cd backend
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Configuration
Copy `.env.example` to `.env` (or create a new `.env` file):
```bash
cp .env.example .env
```

Configure your MySQL database settings in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=business_connect
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Database Migrations & Seeders
Create a new MySQL database named `business_connect` in phpMyAdmin / MySQL Workbench, then execute:
```bash
php artisan migrate --seed
```

> **Alternative Option (Direct SQL Import):**
> You can also import the provided `backend/database/schema.sql` directly into phpMyAdmin or MySQL CLI:
> ```bash
> mysql -u root -p business_connect < database/schema.sql
> ```

### 6. Create Storage Link (For Logo & Image Uploads)
```bash
php artisan storage:link
```

### 7. Start Laravel Development Server
```bash
php artisan serve
```
The application will be accessible at `http://127.0.0.1:8000`.

---

## 🎨 Interactive Frontend Browser Preview Mode

If you want to demonstrate or view all frontend Blade UI pages (Admin Dashboard, Business Owner Dashboard, Customer Dashboard, Search Filters, Charts, Dark Mode) instantly without PHP/MySQL configured:

```bash
cd frontend
npm install
npm start
```
Access the interactive preview server at `http://localhost:3000`.
