# Cinema Booking API

Laravel REST API for cinema ticket booking system.

## Setup

```bash
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

## API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - Customer registration
- `POST /api/logout` - Logout

### Customer (`/api/customer`)
- `GET /films` - Get films
- `GET /films/{id}` - Film details
- `GET /schedules/{filmId}` - Film schedules
- `GET /seats/{scheduleId}` - Available seats
- `POST /book` - Book tickets
- `GET /orders` - Order history

### Admin (`/api/admin`)
- `GET|POST /films` - Film management
- `PUT|DELETE /films/{id}` - Update/delete film
- `GET|POST /schedules` - Schedule management
- `GET /customers` - Customer list

### Owner (`/api/owner`)
- `GET /financial-report` - Financial report

### Cashier (`/api/cashier`)
- `POST /book-offline` - Offline booking
- `GET /online-orders` - Pending orders
- `PUT /process-order/{id}` - Process order