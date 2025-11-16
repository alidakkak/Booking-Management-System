# Booking Management System

A small yet production-ready **Booking Management System** built with **Laravel 12** and **Filament v4**.

The project provides:

- **Admin & Staff roles** with clear access control
- **Admin dashboard** with booking statistics
- **Filament CRUD** for managing bookings (and users for Admin)
- **Public booking page** where end users can create bookings
- **REST API** for creating bookings (`POST /api/bookings`)
- Validation that **prevents duplicate bookings on the same date & time**

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Main Features](#main-features)
3. [Technology Stack](#technology-stack)
4. [Architecture & Structure](#architecture--structure)
5. [Getting Started (Local Setup)](#getting-started-local-setup)
6. [Usage Guide](#usage-guide)
   - [Admin Panel](#admin-panel)
   - [Staff Access](#staff-access)
   - [Public Booking Page](#public-booking-page)
7. [Validation & Business Rules](#validation--business-rules)
8. [Database Seeders](#database-seeders)
9. [Extending the Project](#extending-the-project)

---

## Project Overview

This project implements a simple **booking system** where:

- **Customers** can create bookings through a public page.
- **Staff members** can manage bookings from an internal admin panel.
- **Administrators** have full access:
  - Manage bookings
  - Manage system users
  - View dashboard & statistics

The system is intentionally kept small, but built in a way that is:

- Clean and maintainable
- Easy to extend

---

## Main Features

### 1. Role-based Access Control

Two main roles:

- **Admin**

  - Full access to the Filament admin panel
  - Can manage **Users** (CRUD)
  - Can manage **Bookings** (CRUD, including deleting any booking)
  - Can see the dashboard and booking statistics

- **Staff**
  - Logs in through the **same panel** (`/admin`)
  - Only sees **Bookings** in the sidebar (Users are hidden)
  - Can manage bookings (controlled via resource policies)
  - Cannot access user management at all (even via URL – they get 403)

Role logic is defined at:

- `app/Models/User.php`
  - e.g. `isAdmin()` / `isStaff()` helper methods
- `app/Filament/Resources/UserResource.php`
  - `shouldRegisterNavigation()` and `canAccess()` restrict Users to Admin only

---

### 2. Booking Management (Filament Resource)

Bookings are managed through a dedicated Filament resource:

- Namespace: `App\Filament\Resources\Bookings\BookingResource`
- Includes:
  - **Form** schema (create/edit) – `BookingForm`
  - **Table** schema (list) – `BookingsTable`
  - **Pages**:
    - List bookings
    - Create booking
    - Edit booking

**Booking model fields:**

```php
$table->id();
$table->string('customer_name');
$table->string('phone');
$table->dateTime('booking_date');
$table->string('service_type');
$table->text('notes')->nullable();
$table->enum('status', ['Pending', 'Confirmed', 'Cancelled'])->default('Pending');
$table->timestamps();
```

- Status options:
  - `Pending`
  - `Confirmed`
  - `Cancelled`

---

### 3. Admin Dashboard & Stats Widget

The project includes a simple Filament dashboard that shows:

- **Total bookings**
- **Pending bookings**
- **Confirmed bookings**
- **Cancelled bookings**

Widget class:

- `App\Filament\Widgets\BookingStatsWidget`

Panel configuration:

- `App\Providers\Filament\AdminPanelProvider.php`
  - Registers the default panel `admin`
  - Sets brand name (e.g. `Booking Management`)
  - Registers `BookingStatsWidget` in the dashboard

---

### 4. Public Booking Page (Tailwind UI)

There is a **public page** where any user can create a booking without logging in:

- Route: `GET /booking`
- View: `resources/views/booking/create.blade.php`
- Link: `http://127.0.0.1:8000/booking`

Characteristics:

- Built using **Tailwind CSS (CDN)** for quick styling.
- Dark UI to match the Filament theme.
- Uses **fetch + JSON** to call the API endpoint `POST /api/bookings`.
- Shows:
  - Field-level validation errors
  - Global error messages
  - A success message when the booking is created

---

### 5. REST API – `POST /api/bookings`

An API endpoint is exposed for creating bookings programmatically.

- **Route file**: `routes/api.php`
- **Endpoint**: `POST /api/bookings`
- **Controller**: `App\Http\Controllers\BookingController@store`
- **Request class**: `App\Http\Requests\StoreBookingRequest`
- **Custom rule**: `App\Rules\BookingDateAvailable`

**Example request (JSON):**

```json
{
  "customer_name": "Ali Dakkak",
  "phone": "0937356470",
  "booking_date": "2025-11-15 10:00:00",
  "service_type": "Haircut",
  "notes": "First time booking"
}
```

**Example success response:**

```json
{
  "message": "Booking created successfully.",
  "data": {
    "id": 14,
    "customer_name": "Ali Dakkak",
    "phone": "0937356470",
    "booking_date": "2025-12-12 10:00:00",
    "service_type": "Haircut",
    "notes": "First time booking",
    "status": null,
    "created_at": "2025-11-16T00:11:58.000000Z",
    "updated_at": "2025-11-16T00:11:58.000000Z"
  }
}
```

This makes it very easy to integrate with:

- Mobile apps
- External websites
- Any frontend framework (React, Vue, Next.js, etc.)

---

## Technology Stack

- **Backend Framework**: Laravel 12 (PHP 8.2)
- **Admin Panel**: Filament v4.2.x
- **Database**: MySQL
- **Frontend for admin**: Filament (Blade components)
- **Public booking page**: Laravel Blade + Tailwind CSS (CDN)
- **Authentication**: Laravel’s standard session-based auth
- **Validation**: Laravel Form Requests + custom validation rule

---

## Architecture & Structure

### High-level design

- **Domain logic** is in:
  - Models (`Booking`, `User`)
  - Form Requests (`StoreBookingRequest`)
  - Custom Rule (`BookingDateAvailable`)
- **Admin UI**:
  - Filament resources, pages, widgets
- **Public UI**:
  - Simple Blade page that talks to the API
- **API layer**:
  - `BookingController@store` exposes the booking creation as REST

---

## Getting Started (Local Setup)

### 1. Prerequisites

- PHP 8.2+
- Composer
- MySQL (or MariaDB)

### 2. Clone & install dependencies

```bash
git clone <your-repo-url>
cd booking-management

composer install
```

### 3. Environment configuration

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update at least:

```env
APP_NAME="Booking Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booking_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

Generate app key:

```bash
php artisan key:generate
```

### 4. Run migrations & seeders

Make sure the database exists, then:

```bash
php artisan migrate --seed
```

This will:

- Create all tables
- Seed demo data (see [Database Seeders](#database-seeders))

### 5. Run the application

```bash
php artisan serve
```

Open:

- Admin Panel → `http://127.0.0.1:8000/admin`
- Public Booking Page → `http://127.0.0.1:8000/booking`

---

## Usage Guide

### Admin Panel

- URL: `/admin`
- Provides:
  - **Dashboard** with stats
  - **Bookings** resource
  - **Users** resource (Admin only)

**Typical flows for Admin:**

- View overall booking statistics (total, pending, confirmed, cancelled)
- Create / edit / cancel bookings manually
- Manage users and assign them roles (Admin / Staff)

---

### Staff Access

- Staff users log in from the same URL: `/admin`
- Once logged in:
  - See **Dashboard** and **Bookings** only
  - Users menu item is **hidden** from navigation
  - Direct access to `/admin/users` is blocked (403)

This keeps the staff focused only on day-to-day booking operations.

---

### Public Booking Page

- URL: `/booking`
- Simple Tailwind-styled form with the following inputs:
  - Customer name
  - Phone
  - Booking date & time (`datetime-local`)
  - Service type (select box)
  - Optional notes

---

## Validation & Business Rules

Booking creation is validated using `StoreBookingRequest` and a custom rule.

### StoreBookingRequest

Typical rules:

- `customer_name` → required, string
- `phone` → required, string
- `booking_date` → required, `date`, `BookingDateAvailable` rule
- `service_type` → required, string
- `notes` → nullable, string
- `status` → optional, must be one of `Pending`, `Confirmed`, `Cancelled`

### BookingDateAvailable Rule

Location: `app/Rules/BookingDateAvailable.php`

Logic:

- Parses the incoming `booking_date` using Carbon
- Normalizes it to `Y-m-d H:i:s`
- Checks if any existing booking already has that exact `booking_date`:
  ```php
  $exists = Booking::where('booking_date', $bookingDate)->exists();
  ```
- If yes → validation fails with:
  - `"This booking time is already taken."`

This ensures **no two bookings can be created at exactly the same date & time**, regardless of whether they were created via:

- Filament admin panel
- Public booking form
- External API consumer

---

## Database Seeders

The project includes seeders to help you start quickly.

### BookingSeeder

File: `database/seeders/BookingSeeder.php`

- Uses `BookingFactory` to create **10 demo bookings** with:
  - Random customer names
  - Random phone numbers
  - Booking dates within the next 30 days
  - Random service types
  - Random status (Pending / Confirmed / Cancelled)

Run:

```bash
php artisan db:seed --class=BookingSeeder
```

or just:

```bash
php artisan migrate:fresh --seed
```

### User seeders

- One **Admin** user
- One **Staff** user

Example :

```php
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('Admin@123'),
    'role' => 'admin',
]);

User::create([
    'name' => 'Staff User',
    'email' => 'staff@example.com',
    'password' => bcrypt('Staff@123'),
    'role' => 'staff',
]);
```

---

## Extending the Project

Some ideas for future improvements:

- **Time slots / duration**  
  Add booking duration and prevent overlap within a time window (not just exact same timestamp).

- **Branch / location support**  
  Attach bookings to branches and restrict staff to their own branch bookings.

- **Customer accounts**  
  Allow customers to register and view/update their own bookings.

- **Notifications**  
  Send email/SMS on booking creation, confirmation, or cancellation.

- **API authentication**  
  Protect `/api/bookings` using Laravel Sanctum or Passport for authenticated integrations.

- **Multi-language UI**  
  Add language switch (Arabic/English) for the public booking page and admin panel labels.
