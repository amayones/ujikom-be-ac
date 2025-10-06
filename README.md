# Cinema Booking System - Backend API

Laravel-based REST API for cinema ticket booking system with role-based access control.

## Features

- **Multi-role Authentication** (Customer, Admin, Owner, Cashier)
- **Film Management** with status tracking
- **Schedule & Seat Management**
- **Online & Offline Booking**
- **Financial Reporting**
- **Rate Limiting & Security**

## Quick Start

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Docker (optional)

### Installation

```bash
# Clone repository
git clone https://github.com/amayones/ujikom-be-ac.git
cd ujikom-be-ac

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Start server
php artisan serve
```

### Docker Deployment

```bash
# Development
docker compose up -d

# Production with SSL
docker compose -f docker-compose.ssl.yml up -d
```

## 🚀 Complete EC2 Deployment Guide

### Prerequisites
- EC2 Ubuntu 20.04+ instance
- Security Group dengan port 22, 80, 443 terbuka
- Domain pointing ke EC2 IP (be-ujikom.amayones.my.id)
- SSH key (.pem file) untuk akses EC2

### Step 1: Setup GitHub Auto-Deploy (Sekali Saja)

1. **Buka GitHub Repository**
   - Go to: https://github.com/amayones/ujikom-be-ac
   - Settings → Secrets and variables → Actions

2. **Tambahkan Secrets**
   - Click "New repository secret"
   - Tambahkan 2 secrets:
     ```
     Name: EC2_HOST
     Value: [IP public EC2 Anda, contoh: 54.255.65.241]
     
     Name: EC2_SSH_KEY
     Value: [Copy paste seluruh isi file .pem key Anda]
     ```

3. **Test Auto-Deploy**
   - Push any code ke main branch
   - GitHub Actions akan otomatis deploy ke EC2
   - Cek di tab "Actions" untuk melihat progress

### Step 2: SSH ke EC2 Server

```bash
# SSH ke server (ganti dengan IP dan key file Anda)
ssh -i your-key.pem ubuntu@54.255.65.241
```

### Step 3: Setup SSL Certificate

```bash
# Masuk ke directory project
cd ~/cinema-backend

# Install Certbot
sudo apt update
sudo apt install -y certbot

# Generate SSL certificate (ganti email dengan email Anda)
sudo certbot certonly --standalone -d be-ujikom.amayones.my.id --email admin@amayones.my.id --agree-tos --non-interactive

# Copy SSL certificates ke project
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem ./nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/privkey.pem ./nginx/ssl/key.pem
sudo chown ubuntu:ubuntu ./nginx/ssl/*.pem

# Restart dengan SSL
docker compose -f docker-compose.ssl.yml down
docker compose -f docker-compose.ssl.yml up -d
```

### Step 4: Fix Laravel Cache Issue

```bash
# Masuk ke container Laravel
docker exec -it cinema-app bash

# Buat directory cache dan set permission
mkdir -p /var/www/bootstrap/cache
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs

# Set permission
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Keluar dari container
exit
```

### Step 5: Setup SSL Auto-Renewal

```bash
# Setup cron job untuk auto-renewal SSL
sudo crontab -e

# Pilih editor (pilih 1 untuk nano)
# Tambahkan baris ini di bagian bawah:
0 12 * * * /usr/bin/certbot renew --quiet

# Save: Ctrl+X, Y, Enter
```

### Step 6: Verifikasi Deployment

```bash
# Cek container status
docker ps

# Cek log aplikasi
docker logs cinema-app
docker logs cinema-nginx

# Test HTTP (harus redirect ke HTTPS)
curl -I http://be-ujikom.amayones.my.id

# Test HTTPS
curl -I https://be-ujikom.amayones.my.id

# Test API endpoint
curl https://be-ujikom.amayones.my.id/api/films
```

### 🎉 Selesai!

**Aplikasi sekarang berjalan di:**
- Frontend: https://ujikom.amayones.my.id
- Backend API: https://be-ujikom.amayones.my.id

**Auto-Deploy Active:**
- Setiap push code ke GitHub main branch akan otomatis deploy
- SSL certificate otomatis diperpanjang setiap hari
- Zero-downtime deployment

### Troubleshooting

**Jika container tidak running:**
```bash
docker compose -f docker-compose.ssl.yml restart
```

**Jika SSL error:**
```bash
# Regenerate SSL certificate
sudo certbot delete --cert-name be-ujikom.amayones.my.id
sudo certbot certonly --standalone -d be-ujikom.amayones.my.id --email admin@amayones.my.id --agree-tos --non-interactive
```

**Jika cache error:**
```bash
docker exec -it cinema-app php artisan cache:clear
docker exec -it cinema-app php artisan config:clear
```

## API Endpoints

### Authentication
```
POST /api/login          # User login
POST /api/register       # Customer registration
POST /api/logout         # Logout (auth required)
```

### Customer Routes (`/api/customer`)
```
GET  /films              # Get films by status
GET  /films/{id}         # Film details
GET  /schedules/{filmId} # Film schedules
GET  /seats/{scheduleId} # Available seats
POST /book               # Book tickets
GET  /orders             # Order history
```

### Admin Routes (`/api/admin`)
```
# Film Management
GET|POST /films
PUT|DELETE /films/{id}

# Schedule Management
GET|POST /schedules

# Customer Management
GET /customers
PUT /customers/{id}
```

### Owner Routes (`/api/owner`)
```
GET /financial-report    # Financial analytics
```

### Cashier Routes (`/api/cashier`)
```
POST /book-offline       # Offline booking
GET  /online-orders      # Pending orders
PUT  /process-order/{id} # Process order
```

## Database Schema

### Core Tables
- `users` - User accounts with roles
- `films` - Movie information
- `schedules` - Show times
- `seats` - Theater seating
- `orders` - Booking records
- `order_details` - Ticket details

### User Roles
- `customer` - End users
- `admin` - System management
- `owner` - Business analytics
- `cashier` - Ticket processing

## Security Features

- **JWT Authentication** via Laravel Sanctum
- **Rate Limiting** (5 req/min for auth, 60 req/min for API)
- **Input Validation** with custom rules
- **SQL Injection Protection** via Eloquent ORM
- **CORS Configuration** for frontend integration

## Environment Variables

```env
APP_URL=https://be-ujikom.amayones.my.id
DB_HOST=db
DB_DATABASE=cinema
DB_USERNAME=laravel
DB_PASSWORD=laravel
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure SSL certificates
- [ ] Set secure session cookies
- [ ] Configure rate limiting
- [ ] Set up database backups

### GitHub Actions
Automated deployment on push to main branch:
```yaml
- Pulls latest code
- Builds Docker images
- Deploys with SSL configuration
- Handles database migrations
```

## API Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success",
  "data": {...}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "data": null
}
```

## Testing

```bash
# Run tests
php artisan test

# API testing with sample data
php artisan db:seed --class=TestDataSeeder
```

## Contributing

1. Fork the repository
2. Create feature branch
3. Make changes with tests
4. Submit pull request

## License

MIT License - see LICENSE file for details.