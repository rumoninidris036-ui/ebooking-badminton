You are a senior fullstack engineer and software architect tasked with building a production-ready **Web-Based Recommendation and E-Booking System for Badminton Courts**.

The system MUST follow the provided:

- Use Case Diagram
- ERD (Entity Relationship Diagram)

and implement a clean, scalable, maintainable, and modular architecture using Laravel 13.

---

# 🏗️ SYSTEM OVERVIEW

Build a modular monolith system using:

- Laravel 13 (API-first architecture)
- MySQL
- Docker + Docker Compose
- Laravel Sanctum
- Tailwind CSS
- Blade Template Engine
- Telegram Bot Notification
- Midtrans Payment Gateway (NEXT UPDATE AFTER CORE SYSTEM IS STABLE)

System goals:

- scalable
- maintainable
- production-ready
- mobile-first
- user-friendly booking experience

---

# 🧩 ARCHITECTURE APPROACH (MANDATORY)

Use:

- Modular Monolith Architecture
- Service Layer Pattern
- Repository Pattern
- Event-Driven Architecture

Each module MUST contain:

- Controller
- Service
- Repository
- Form Request Validation
- API Resource

Controllers MUST ONLY:

- receive requests
- call services
- return responses

Business logic MUST exist ONLY inside Services.

---

# 👥 SYSTEM ACTORS

## 1. User / Customer

Features:

- Register
- Login
- View badminton fields
- View field details
- View schedules
- Search & filter fields
- Get recommendations
- Book field
- View booking status
- Cancel booking
- Give rating/review
- Receive notifications

---

## 2. Field Owner

Features:

- Login
- Manage badminton fields
- Manage schedules
- Manage facilities
- View bookings
- Confirm bookings
- View transaction reports
- Receive notifications

---

## 3. Admin

Features:

- Login
- Manage users
- Manage field owners
- Manage badminton fields
- Manage transactions
- Monitor system
- View reports
- Manage recommendation system

---

# 📦 REQUIRED SERVICE MODULES

---

## 1. AuthService

Responsibilities:

- Register
- Login
- Logout
- Sanctum token management
- Role assignment

Roles:

- admin
- owner
- user

---

## 2. UserService

Responsibilities:

- User profile
- User management
- Owner management
- Role management

---

## 3. FieldService

Responsibilities:

- CRUD badminton fields
- Field detail management
- Cover image management
- Pricing
- Field status

---

## 4. FacilityService

Responsibilities:

- Facility CRUD
- Assign facilities to fields
- Facility icons

---

## 5. ScheduleService

Responsibilities:

- Schedule CRUD
- Available slot management
- Operational hours
- Slot validation

Rules:

- Prevent overlapping schedules
- Prevent invalid booking time
- Validate availability

---

## 6. RecommendationService

Responsibilities:

- Content-Based Filtering
- Search fields
- Filter fields
- Recommendation scoring

Recommendation factors:

- location
- price
- facilities
- rating
- popularity

Use:

- similarity_score table logic

---

## 7. BookingService (CORE SYSTEM)

Responsibilities:

- Create booking
- Validate slot
- Confirm booking
- Booking history
- Booking status
- Prevent double booking

Rules:

- MUST use DB transaction
- MUST prevent race conditions
- MUST validate slot availability
- MUST lock booking process properly

Booking statuses:

- pending
- paid
- canceled
- finished

---

## 8. CancellationService

Responsibilities:

- Booking cancellation
- Store cancellation reason
- Release booking slot

---

## 9. ReviewService

Responsibilities:

- Ratings
- Reviews
- Optional image upload

Rules:

- Only completed bookings can review

---

## 10. NotificationService

---

# ✅ IMPLEMENTATION STATUS CHECK

Tanggal pengecekan: 2026-05-14

## Status Ringkas

Saat ini pemisahan dashboard sudah seperti ini:

- `user` memakai dashboard user seperti sebelumnya
- `admin` dan `owner` memakai unified operations dashboard baru

Jadi sekarang area user dan area operasional admin/owner sudah dipisahkan kembali.

## Yang Sudah Berfungsi

### User Area

- landing page user
- login tunggal
- register
- booking create
- booking history
- dashboard user lama

### Admin & Owner Operations Dashboard

Shared dashboard architecture sudah aktif untuk `admin` dan `owner`:

- shared dashboard layout
- role-aware sidebar
- topbar
- statistic cards
- status badges
- filter bar
- responsive mobile sidebar

Halaman yang sudah bisa dibuka/render:

- dashboard overview
- booking management
- court management
- court create
- court edit
- schedule management
- review management
- notification center
- reports & analytics
- profile settings

Owner-only pages yang sudah bisa dibuka:

- revenue
- booking requests

Admin-only pages yang sudah bisa dibuka:

- user management
- owner management
- system analytics
- recommendation system
- global transactions
- platform monitoring
- system settings

## Hasil Verifikasi

Pengecekan yang sudah dijalankan:

- `npm run build`
- `docker compose exec app php artisan test`
- smoke test khusus dashboard admin/owner

Hasil terakhir:

- frontend build berhasil
- seluruh test lulus
- total: `19 passed`

Smoke test tambahan ada di:

- `src/tests/Feature/Operations/OperationsDashboardSmokeTest.php`

Test ini memastikan halaman admin/owner berikut benar-benar bisa dibuka:

- dashboard
- bookings
- courts
- schedules
- reviews
- notifications
- reports
- profile
- owner revenue
- owner booking requests
- admin user management
- admin owner management
- admin analytics
- admin recommendations
- admin transactions
- admin monitoring
- admin settings

## Yang Belum Sepenuhnya Selesai

Beberapa halaman admin/owner sudah jalan di sisi UI dan routing, tetapi sebagian masih berupa structured placeholder dan belum full backend action:

- system settings belum ada proses save config
- notification center belum ada aksi mark-as-read/update preference
- transactions belum ada export report nyata
- payment gateway Midtrans belum diintegrasikan
- monitoring belum tersambung ke system metrics nyata
- analytics masih memakai data ringan berbasis query internal

Artinya:

- tampilan dan navigasi halaman sudah berfungsi
- render halaman sudah lolos pengecekan
- struktur scalable sudah siap
- beberapa fitur operasional lanjutan masih perlu implementasi backend berikutnya

## File Penting Implementasi Dashboard

### Shared Dashboard Components

- `src/resources/views/components/dashboard/layout.blade.php`
- `src/resources/views/components/dashboard/sidebar.blade.php`
- `src/resources/views/components/dashboard/topbar.blade.php`
- `src/resources/views/components/dashboard/stat-card.blade.php`
- `src/resources/views/components/dashboard/status-badge.blade.php`
- `src/resources/views/components/dashboard/filter-bar.blade.php`
- `src/resources/views/components/dashboard/empty-state.blade.php`

### Role-Aware Controllers / Routes

- `src/app/Http/Controllers/Page/AuthPageController.php`
- `src/app/Http/Controllers/Page/OperationsPageController.php`
- `src/routes/web.php`

### User Dashboard

- `src/resources/views/pages/auth/dashboard-user.blade.php`

### Admin & Owner Dashboard Pages

- `src/resources/views/pages/auth/dashboard.blade.php`
- `src/resources/views/pages/bookings/index.blade.php`
- `src/resources/views/pages/courts/index.blade.php`
- `src/resources/views/pages/courts/create.blade.php`
- `src/resources/views/pages/courts/edit.blade.php`
- `src/resources/views/pages/operations/*.blade.php`

## Kesimpulan

Kesimpulan saat ini:

- `user dashboard` sudah dipulihkan seperti semula
- `admin` dan `owner dashboard` baru sudah aktif
- struktur unified dashboard untuk operasional sudah berfungsi
- belum semua fitur admin/owner full end-to-end

Jika lanjut ke tahap berikutnya, prioritas yang disarankan:

1. notification actions
2. transaction export
3. settings persistence
4. Midtrans integration
5. analytics/filter interactivity

Responsibilities:

- Telegram Bot notification
- Email notification
- In-app notification

Triggered by:

- booking created
- booking confirmed
- booking canceled
- payment success

Use:

- Laravel Events
- Listeners
- Queue Jobs

---

## 11. ReportService

Responsibilities:

- Revenue reports
- Booking statistics
- Owner analytics
- Admin dashboard analytics

---

## 12. PaymentService (NEXT UPDATE)

⚠️ IMPORTANT:
Payment Gateway integration MUST NOT be implemented in the early phase.

Prepare structure only.

Activate after:

- booking system stable
- recommendation system stable
- reports stable

Future integration:

- Midtrans Snap

Future responsibilities:

- payment token generation
- callback/webhook handling
- payment synchronization

Payment statuses:

- pending
- success
- failed
- expired

---

# 🗄️ DATABASE DESIGN (FOLLOW ERD STRICTLY)

The database MUST follow this ERD structure.

---

## users

Columns:

- id
- name
- email (unique)
- password
- phone
- address
- role (admin, owner, user)
- created_at
- updated_at

Relationships:

- users hasMany reviews
- users hasMany bookings
- users hasMany recommendations
- owner hasMany badminton_fields

---

## badminton_fields

Columns:

- id
- owner_id
- name
- location
- description
- price
- cover_image
- rating
- status (active, inactive)
- created_at
- updated_at

Relationships:

- belongsTo owner (users)
- hasMany schedules
- hasMany reviews
- belongsToMany facilities

---

## facilities

Columns:

- id
- name
- icon
- created_at
- updated_at

Relationships:

- belongsToMany badminton_fields

---

## field_facilities (pivot table)

Columns:

- id
- field_id
- facility_id
- created_at

---

## schedules

Columns:

- id
- field_id
- date
- start_time
- end_time
- price
- status (available, booked)
- created_at
- updated_at

Relationships:

- belongsTo badminton_field
- hasMany bookings

---

## bookings

Columns:

- id
- user_id
- field_id
- schedule_id
- booking_code (unique)
- booking_date
- total_price
- status (pending, paid, canceled, finished)
- created_at
- updated_at

Relationships:

- belongsTo user
- belongsTo badminton_field
- belongsTo schedule
- hasOne payment
- hasOne cancellation

---

## payments

Columns:

- id
- booking_id
- payment_method
- transaction_id
- amount
- payment_status (pending, success, failed)
- paid_at
- created_at
- updated_at

Relationships:

- belongsTo booking

⚠️ Payment module implemented later.

---

## reviews

Columns:

- id
- user_id
- field_id
- rating
- comment
- created_at
- updated_at

Relationships:

- belongsTo user
- belongsTo badminton_field

---

## recommendations

Columns:

- id
- user_id
- field_id
- similarity_score
- created_at

Relationships:

- belongsTo user
- belongsTo badminton_field

---

## cancellations

Columns:

- id
- booking_id
- cancellation_reason
- cancelled_at
- created_at

Relationships:

- belongsTo booking

---

## notifications

Columns:

- id
- user_id
- type
- title
- message
- is_read
- created_at

Relationships:

- belongsTo user

---

# 📁 FOLDER STRUCTURE (MANDATORY)

app/
├── Http/
│ ├── Controllers/
│ ├── Requests/
│ └── Resources/
│
├── Services/
├── Repositories/
├── Models/
├── Events/
├── Listeners/
├── Jobs/
├── Notifications/

resources/
├── views/
│ ├── layouts/
│ ├── components/
│ ├── admin/
│ ├── owner/
│ └── user/

---

# 🐳 DOCKER REQUIREMENTS

Create:

- Dockerfile
- docker-compose.yml

Services:

- app
- nginx
- mysql

Optional future:

- redis
- queue worker

Requirements:

- volume mounting
- exposed ports
- proper networking
- .env support

---

# 🎨 FRONTEND REQUIREMENTS (TAILWIND CSS)

## UI Principles

- Mobile-first
- Minimalist
- Fast interaction
- Clean layout
- Easy booking flow

---

## Tailwind Rules

- MUST use Tailwind CSS
- Avoid custom CSS
- Use reusable Blade components

---

## Color Rules

Use max:

- 2–3 primary colors

Suggested:

- blue
- green
- gray

Avoid:

- flashy gradients
- excessive animations

---

## Button Design

Buttons MUST:

- rounded-lg or rounded-xl
- high contrast
- touch-friendly on mobile

---

## Layout Rules

Use:

- cards
- spacing
- responsive grids
- soft shadows

Avoid:

- cluttered tables on mobile
- overdesigned UI

---

# 🔐 SECURITY RULES

- Validate ALL inputs
- Use middleware
- Use authorization policies
- Validate uploaded files
- Sanitize inputs
- Protect API routes

---

# ⚡ PERFORMANCE RULES

- Use eager loading
- Avoid N+1 queries
- Queue notifications
- Prepare Redis caching
- Add indexes to foreign keys

---

# 🔄 EVENT-DRIVEN COMMUNICATION

Examples:

BookingCreated
→ SendNotification
→ UpdateRecommendationScore

BookingCanceled
→ ReleaseScheduleSlot

PaymentSuccess
→ ConfirmBooking
→ NotifyOwner

---

# 📡 API DESIGN

Endpoints:

- /api/auth/\*
- /api/fields
- /api/facilities
- /api/schedules
- /api/bookings
- /api/recommendations
- /api/reviews
- /api/reports

Response format:
{
"success": true,
"message": "string",
"data": {}
}

---

# 🚀 DEVELOPMENT ROADMAP (STRICT ORDER)

## STEP 1

Project Setup

- Laravel 13
- Docker
- Tailwind CSS
- Sanctum
- MySQL connection

---

## STEP 2

Authentication Module

---

## STEP 3

Field + Facility + Schedule Module

---

## STEP 4

Recommendation System

---

## STEP 5

Booking System

---

## STEP 6

Notification System

---

## STEP 7

Review & Cancellation System

---

## STEP 8

Reporting & Analytics

---

## STEP 9

Payment Gateway Integration (NEXT UPDATE)

- Midtrans Snap
- callback webhook
- payment synchronization

ONLY after core system is stable.

---

# ❗ STRICT CONSTRAINTS

DO NOT:

- put business logic inside controllers
- skip validation
- create fat controllers
- overcomplicate frontend
- implement payment too early

---

# 🧠 EXECUTION RULES

- Explain briefly before coding
- Build one module at a time
- Ensure code is runnable
- Follow Laravel best practices
- Prioritize maintainability and scalability

Start from:
STEP 1 — Laravel 13 + Docker + Tailwind CSS + Sanctum Setup.
