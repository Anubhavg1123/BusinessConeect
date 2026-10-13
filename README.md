# Business Connect – A Business Management and Networking Platform

**Business Connect** is a complete, full-stack web application built using **Laravel 12 (PHP 8.3)**, **MySQL**, **Bootstrap 5**, **HTML5**, **CSS3**, **JavaScript**, and **Blade Templates**.

The platform enables businesses to register, showcase their products & services, manage customer inquiries, receive verified reviews, and connect online. It features robust role-based authentication and authorization for **Admin**, **Business Owner**, and **Customer** roles.

---

## 📁 Academic / Lecture Project Structure

As requested for project submission and academic presentation, the repository is explicitly partitioned into separate `frontend/` and `backend/` directories to clearly demonstrate the separation of concerns:

```
business-connect/
├── backend/                             # Complete Laravel 12 PHP Backend Codebase
│   ├── app/
│   │   ├── Http/Controllers/           # Auth, Admin, Business, Product, Service, Inquiry, Review Controllers
│   │   ├── Models/                     # 15 Eloquent Models (User, Business, Product, Service, etc.)
│   │   ├── Http/Middleware/            # Role-Based Middleware
│   │   └── Policies/                   # Authorization Policies
│   ├── database/
│   │   ├── migrations/                 # 16 Normalized MySQL Database Migration Files
│   │   ├── seeders/                    # DatabaseSeeder with realistic demo data
│   │   └── schema.sql                  # Standalone raw MySQL import file for phpMyAdmin / MySQL CLI
│   ├── routes/
│   │   ├── web.php                     # Full Web Route Registry
│   │   └── api.php                     # API Autosuggest & Search Endpoints
│   └── composer.json                   # Backend Dependencies
│
├── frontend/                            # Frontend UI & Blade Design System
│   ├── views/                          # Blade Views organized by Layout, Auth, Admin, Business, Customer
│   │   ├── layouts/                    # app.blade.php, admin, business, customer layouts
│   │   ├── auth/                       # login, register-business, register-customer
│   │   ├── public/                     # landing page, directory, business detail with tabs, products, services
│   │   ├── admin/                      # dashboard, businesses, users, categories, reports
│   │   ├── business/                   # dashboard, profile editor, products CRUD, services CRUD, inquiries
│   │   └── customer/                   # dashboard, favorites, inquiries history
│   ├── public/
│   │   ├── css/                        # custom.css (Glassmorphism, Animations, Dark Mode variables)
│   │   ├── js/                         # theme.js (Dark Mode), charts.js (Chart.js), app.js (Live Search & SweetAlert)
│   │   └── server.js                   # Node-based interactive browser presentation server
│   └── package.json
│
├── README.md                            # Complete Project Overview & Presentation Guide
└── INSTALLATION.md                      # Step-by-Step Setup Guide (XAMPP / Laragon / SQLite / MySQL)
```

---

## 🔑 Key Features & User Roles

### 1. Admin Module
- **Analytics Dashboard**: Real-time counter metrics (Total Businesses, Customers, Products, Services, Pending Approvals, Revenue).
- **Interactive Charts**: Powered by Chart.js for Business Growth, Monthly Registrations, Category Distribution, and Customer Inquiries.
- **Approval Workflows**: Approve, Reject, or Delete business listing requests.
- **Data & Category Management**: Manage business sectors, product categories, reviews, user accounts, and contact messages.
- **Report Exports**: One-click CSV / Excel report generation.

### 2. Business Owner Module
- **Business Profile Editor**: Manage business logo, cover banner, description, GST number, contact channels (WhatsApp, Website, Social Links), operational hours, state, city, PIN code, and Google Maps embed.
- **Product Management**: Complete CRUD operations for products (Price, Discount %, Stock, SKU, Availability status, Tags, Specifications).
- **Service Management**: Complete CRUD for professional services (Price, Duration, Availability).
- **Inquiry Inbox**: Receive customer inquiries, review preferred contact times, and send direct replies.

### 3. Customer Module
- **Multi-Parameter Search**: Search by Business Name, Category, Product, Service, City, State, Price, and Rating.
- **Business Profiles**: View verified business details with interactive tabs (Overview, Products, Services, Reviews).
- **Review & Rating System**: Rate businesses (1-5 stars), write reviews, and like existing reviews.
- **Favorites & Inquiries**: Bookmark favorite businesses and track inquiry responses in real time.

---

## 🔑 Pre-Configured Demo Credentials

| Role | Email | Password | Access Level |
|---|---|---|---|
| **Admin** | `admin@businessconnect.com` | `password123` | Full System Administration |
| **Business Owner (IT)** | `alex@nexustech.com` | `password123` | NexusTech Solutions Ltd Profile & Products |
| **Business Owner (Wellness)**| `sophia@greenleaf.com` | `password123` | Green Leaf Organic Profile & Services |
| **Customer** | `david.customer@gmail.com` | `password123` | Directory Browsing, Favorites, Inquiries |

---

## ⚡ Quick Start & Interactive UI Demo

To preview all frontend views interactively right now without setting up MySQL/PHP:

```bash
cd frontend
npm install
npm start
```
Open your browser at `http://localhost:3000`.

---

## 🎓 Lecture Presentation Talking Points

1. **Architecture & Separation of Concerns**: Explain how the MVC pattern is organized with a clear distinction between the `backend/` logic (Controllers, Eloquent ORM, Database Normalization) and the `frontend/` layer (Blade Layouts, CSS Design System, Chart.js).
2. **Database Normalization**: Highlight the 16 normalized MySQL tables (`users`, `roles`, `businesses`, `business_categories`, `products`, `services`, `inquiries`, `reviews`, `favorites`, `notifications`, `galleries`, `contact_messages`, `settings`, `activity_logs`, `password_resets`).
3. **Role-Based Access Control (RBAC)**: Demonstrate how middleware enforces role boundaries so Customers cannot access Admin endpoints or modify other business profiles.
4. **UX/UI Polish**: Point out dark mode switching, micro-animations, glassmorphism cards, live search suggestions, and dynamic Chart.js analytics.
