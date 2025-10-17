# Branch Structure - Cinema Booking API

This repository is organized into modular branches based on functionality to maintain clean separation of concerns and enable parallel development.

## Main Branch
- **`main`** - Production-ready code with all features integrated

## Feature Branches

### 🔐 Authentication System
- **Branch:** `feature/authentication`
- **Files:** 
  - `app/Http/Controllers/AuthController.php`
  - `routes/auth.php`
  - `app/Models/User.php`
- **Functionality:** User login, registration, logout, JWT authentication

### 🎬 Film Management
- **Branch:** `feature/film-management`
- **Files:**
  - `app/Http/Controllers/FilmController.php`
  - `app/Models/Film.php`
  - `database/migrations/*_create_films_table.php`
  - `database/seeders/FilmSeeder.php`
- **Functionality:** CRUD operations for films, film listings, film details

### 📅 Schedule Management
- **Branch:** `feature/schedule-management`
- **Files:**
  - `app/Http/Controllers/ScheduleController.php`
  - `app/Models/Schedule.php`
  - `app/Models/ScheduleSeat.php`
  - `database/migrations/*_create_schedules_table.php`
  - `database/seeders/ScheduleSeeder.php`
- **Functionality:** Film scheduling, time slots, seat availability

### 🎫 Booking System
- **Branch:** `feature/booking-system`
- **Files:**
  - `app/Http/Controllers/OrderController.php`
  - `app/Http/Controllers/SeatController.php`
  - `app/Models/Order.php`
  - `app/Models/OrderDetail.php`
  - `database/migrations/*_create_orders_table.php`
  - `database/seeders/OrderSeeder.php`
- **Functionality:** Ticket booking, seat selection, order management

### 💳 Payment System
- **Branch:** `feature/payment-system`
- **Files:**
  - `app/Http/Controllers/PaymentController.php`
  - `app/Models/Payment.php`
  - `app/Models/Invoice.php`
  - `database/migrations/*_create_payments_table.php`
- **Functionality:** Payment processing, invoice generation, payment methods

### 👨‍💼 Admin Panel
- **Branch:** `feature/admin-panel`
- **Files:**
  - `routes/admin.php`
  - `app/Http/Controllers/UserController.php`
  - `app/Http/Controllers/PriceController.php`
  - `app/Models/Price.php`
- **Functionality:** Admin dashboard, user management, system configuration

### 💰 Cashier System
- **Branch:** `feature/cashier-system`
- **Files:**
  - `routes/cashier.php`
  - `app/Http/Controllers/CashierController.php`
- **Functionality:** Offline booking, ticket processing, transaction management

### 📊 Owner Dashboard
- **Branch:** `feature/owner-dashboard`
- **Files:**
  - `routes/owner.php`
  - `app/Models/Report.php`
  - `database/seeders/ReportSeeder.php`
- **Functionality:** Financial reports, analytics, business insights

### 🚀 Deployment
- **Branch:** `feature/deployment`
- **Files:**
  - `Dockerfile`
  - `docker-compose.yml`
  - `nginx.conf`
  - `.github/workflows/deploy.yml`
  - `DEPLOYMENT_GUIDE.md`
- **Functionality:** Docker configuration, CI/CD pipeline, AWS deployment

## Development Workflow

1. **Feature Development:** Work on individual feature branches
2. **Testing:** Test features independently on their respective branches
3. **Integration:** Merge tested features into `main` branch
4. **Deployment:** Deploy from `main` branch to production

## Branch Commands

```bash
# Switch to a feature branch
git checkout feature/authentication

# Create new feature branch
git checkout -b feature/new-feature

# Merge feature to main
git checkout main
git merge feature/authentication

# Push all branches
git push --all origin
```

## API Endpoints by Branch

### Authentication (`/api/auth`)
- POST `/login` - User login
- POST `/register` - User registration  
- POST `/logout` - User logout

### Films (`/api/films`)
- GET `/` - List all films
- GET `/{id}` - Get film details
- POST `/` - Create film (admin)
- PUT `/{id}` - Update film (admin)
- DELETE `/{id}` - Delete film (admin)

### Schedules (`/api/schedules`)
- GET `/` - List schedules
- GET `/{id}` - Get schedule details
- POST `/` - Create schedule (admin)

### Booking (`/api/orders`)
- GET `/` - List user orders
- POST `/` - Create new booking
- GET `/{id}` - Get order details

### Admin (`/api/admin`)
- All administrative functions
- User management
- System configuration

### Cashier (`/api/cashier`)
- Offline booking management
- Transaction processing

### Owner (`/api/owner`)
- Financial reports
- Business analytics

## Database Structure

The application uses the following main entities:
- **Users** - Customer and staff accounts
- **Films** - Movie information
- **Studios** - Cinema halls
- **Schedules** - Film showtimes
- **Orders** - Booking records
- **Payments** - Transaction records
- **Reports** - Business analytics

All migrations and seeders are organized by functionality and can be run independently for testing specific features.