# Cinema Booking System API Documentation

## Authentication
All protected routes require Bearer token authentication.

### Auth Endpoints
- `POST /api/login` - User login
- `POST /api/register` - User registration (creates pelanggan role)
- `POST /api/logout` - User logout (requires auth)

## Pelanggan (Customer) Endpoints
Base URL: `/api/pelanggan`

- `GET /films?status=play_now|coming_soon|history` - Get films by status
- `GET /films/{id}` - Get film details
- `GET /schedules/{filmId}` - Get schedules for a film
- `GET /seats/{scheduleId}` - Get available seats for a schedule
- `POST /book` - Book tickets
- `GET /orders` - Get order history
- `PUT /profile` - Update profile

## Admin Endpoints
Base URL: `/api/admin`

### Film Management
- `GET /films` - Get all films
- `POST /films` - Create new film
- `PUT /films/{id}` - Update film
- `DELETE /films/{id}` - Delete film

### Customer Management
- `GET /customers` - Get all customers
- `PUT /customers/{id}` - Update customer

### Schedule Management
- `GET /schedules` - Get all schedules
- `POST /schedules` - Create new schedule

### Price Management
- `GET /prices` - Get all prices
- `POST /prices` - Create new price

### Cashier Management
- `GET /cashiers` - Get all cashiers
- `POST /cashiers` - Create new cashier

### Seat Management
- `GET /seats` - Get all seats
- `POST /seats` - Create new seat

## Owner Endpoints
Base URL: `/api/owner`

- `GET /financial-report?start_date=&end_date=` - Get financial report
- `GET /monthly-report` - Get monthly report
- `POST /expenses` - Add expense record

## Kasir (Cashier) Endpoints
Base URL: `/api/kasir`

- `POST /book-offline` - Book offline ticket
- `GET /print-ticket/{orderId}` - Get ticket data for printing
- `GET /online-orders` - Get pending online orders
- `PUT /process-order/{orderId}` - Confirm or cancel online order

## User Roles
- `pelanggan` - Customer
- `admin` - Administrator
- `owner` - Owner
- `kasir` - Cashier

## Sample Login Credentials (after seeding)
- Admin: admin@cinema.com / password
- Owner: owner@cinema.com / password
- Kasir: kasir@cinema.com / password
- Pelanggan: john@example.com / password