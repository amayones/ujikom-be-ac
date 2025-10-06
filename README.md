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

## 🚀 EC2 Deployment

### Langkah 1: Bersihkan Server (Jika Perlu)
```bash
# SSH ke EC2
ssh -i your-key.pem ubuntu@your-ec2-ip

# Download script pembersihan
wget https://raw.githubusercontent.com/amayones/ujikom-be-ac/main/scripts/clean-server.sh
chmod +x clean-server.sh
./clean-server.sh
```

### Langkah 2: Deploy Aplikasi
```bash
# Download script deploy
wget https://raw.githubusercontent.com/amayones/ujikom-be-ac/main/deploy.sh
chmod +x deploy.sh
./deploy.sh
```

### Langkah 3: Setup Auto-Deploy GitHub
1. Buka GitHub repo → Settings → Secrets and variables → Actions
2. Tambahkan secrets:
   - `EC2_HOST`: IP public EC2 Anda
   - `EC2_SSH_KEY`: Isi file .pem key Anda
3. Selesai! Setiap push ke main branch akan auto deploy

### Cek Status Aplikasi
```bash
docker ps                    # Lihat container yang running
docker logs cinema-app       # Lihat log aplikasi
docker logs cinema-nginx     # Lihat log nginx
curl https://be-ujikom.amayones.my.id/api/films  # Test API
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