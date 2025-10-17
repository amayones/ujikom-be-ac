# Backend Authentication System

## Features
- Laravel Sanctum token-based authentication
- Role-based access control (customer, admin, owner, cashier)
- CORS configuration for frontend integration
- Comprehensive error handling

## Endpoints
- POST /api/register - User registration
- POST /api/login - User login
- POST /api/logout - User logout
- GET /api/me - Get current user

## Test Users
- test@example.com / password123 (customer)
- admin@example.com / password123 (admin)
- owner@example.com / password123 (owner)
- cashier@example.com / password123 (cashier)

## Configuration
- Domain: https://be-ujikom.amayones.my.id/
- Database: SQLite
- CORS: Configured for production domains