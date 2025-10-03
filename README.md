# 🎬 Cinema Booking System - Laravel Backend

## Features
- **4 User Roles**: Pelanggan, Admin, Owner, Kasir
- **Complete Cinema Management**: Films, Schedules, Seats, Bookings
- **RESTful API**: Ready for frontend integration
- **Role-based Access Control**: Secure endpoints

## Installation

### 1. Install Dependencies
```bash
composer install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
# Configure database in .env
php artisan migrate
php artisan db:seed
```

### 4. Run Server
```bash
php artisan serve
```

## API Documentation
See `API_DOCUMENTATION.md` for complete endpoint list.

## Use Cases
See `USE_CASES.md` for system requirements and flows.

## Default Users (after seeding)
- **Admin**: admin@cinema.com / password
- **Owner**: owner@cinema.com / password  
- **Kasir**: kasir@cinema.com / password
- **Pelanggan**: john@example.com / password

## Tech Stack
- Laravel 11
- MySQL
- Laravel Sanctum (API Authentication)
- RESTful API Architecture