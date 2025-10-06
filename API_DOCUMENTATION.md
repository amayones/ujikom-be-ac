# API Documentation

## Authentication
All protected routes require Bearer token in header: `Authorization: Bearer {token}`

## Endpoints

### Auth
```
POST /api/login
POST /api/register
POST /api/logout (auth required)
```

### Customer (`/api/customer`)
```
GET  /films?status=play_now|coming_soon
GET  /films/{id}
GET  /schedules/{filmId}
GET  /seats/{scheduleId}
POST /book
GET  /orders
```

### Admin (`/api/admin`)
```
GET|POST /films
PUT|DELETE /films/{id}
GET|POST /schedules
GET /customers
```

### Owner (`/api/owner`)
```
GET /financial-report?start_date=&end_date=
```

### Cashier (`/api/cashier`)
```
POST /book-offline
GET  /online-orders
PUT  /process-order/{id}
```

## Request/Response Format

### Login Request
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

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

## User Roles
- `customer` - End users
- `admin` - System management
- `owner` - Business analytics
- `cashier` - Ticket processing

## Rate Limiting
- Auth endpoints: 5 requests/minute
- API endpoints: 60 requests/minute