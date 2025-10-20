# Absolute Cinema - Backend API

Laravel REST API untuk sistem pemesanan tiket bioskop dengan 4 role pengguna.

## 🚀 Quick Start

```bash
# Install & Setup
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## 📱 API Endpoints

### Authentication
- `POST /api/auth/login` - Login (email, password)
- `POST /api/auth/register` - Register customer
- `POST /api/auth/logout` - Logout

### Customer
- `GET /api/films` - Daftar film (filter: ?status=now_playing/coming_soon)
- `GET /api/films/{id}` - Detail film
- `GET /api/schedules` - Jadwal tayang
- `GET /api/seats/studio/{id}` - Kursi tersedia
- `POST /api/orders` - Buat pesanan
- `GET /api/orders` - Riwayat pesanan

### Admin
- `GET|POST /api/admin/films` - Kelola film
- `PUT|DELETE /api/admin/films/{id}` - Update/hapus film
- `GET|POST /api/admin/schedules` - Kelola jadwal
- `GET /api/admin/users` - Kelola customer
- `PUT /api/admin/prices/{id}` - Update harga

### Owner
- `GET /api/owner/income` - Laporan pemasukan
- `GET /api/owner/expense` - Laporan pengeluaran

### Cashier
- `POST /api/cashier/offline-booking` - Booking offline
- `POST /api/cashier/process-ticket` - Validasi tiket

## 🔑 Default Accounts

```
Admin: admin@cinema.com / password
Owner: owner@cinema.com / password  
Cashier: cashier@cinema.com / password
Customer: budi@example.com / password
```

## 🛠 Tech Stack

- Laravel 11 + Sanctum Auth
- MySQL Database
- Docker Ready
- AWS Lightsail Deployed

## 🌐 Production

**Live API:** https://be-ujikom.amayones.my.id/api

**Postman Collection:** `Absolute_Cinema_API_Updated.postman_collection.json`