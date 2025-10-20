# Postman API Testing Guide

## Import Collection

1. Buka Postman
2. Klik **Import** di pojok kiri atas
3. Pilih file `Absolute_Cinema_API.postman_collection.json`
4. Collection akan muncul di sidebar

## Setup Environment Variables

### Base URL
- Variable: `base_url`
- Value: `https://be-ujikom.amayones.my.id/api`

### Token
- Variable: `token`
- Value: (akan diisi setelah login)

## Cara Testing API

### 1. Authentication Flow

#### Register (Optional)
```
POST {{base_url}}/auth/register
Body:
{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```
POST {{base_url}}/auth/login
Body:
{
  "email": "admin@cinema.com",
  "password": "password"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {...},
    "token": "1|xxxxxxxxxxxxx"
  }
}
```

**PENTING:** Copy token dari response dan paste ke variable `token` di Postman Environment

#### Get Profile
```
GET {{base_url}}/auth/me
Headers:
Authorization: Bearer {{token}}
```

#### Logout
```
POST {{base_url}}/auth/logout
Headers:
Authorization: Bearer {{token}}
```

---

### 2. Films Management

#### Get All Films
```
GET {{base_url}}/admin/films
Headers:
Authorization: Bearer {{token}}
```

#### Get Film by ID
```
GET {{base_url}}/admin/films/1
Headers:
Authorization: Bearer {{token}}
```

#### Create Film
```
POST {{base_url}}/admin/films
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "title": "Avengers: Endgame",
  "genre": "Action, Sci-Fi",
  "duration": 181,
  "director": "Russo Brothers",
  "cast": "Robert Downey Jr., Chris Evans",
  "synopsis": "Epic conclusion to the Infinity Saga",
  "poster": "https://example.com/poster.jpg",
  "trailer": "https://youtube.com/watch?v=test",
  "release_date": "2025-01-01",
  "status": "now_playing",
  "rating": "13+"
}
```

#### Update Film
```
PUT {{base_url}}/admin/films/1
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "title": "Updated Title",
  "duration": 120
}
```

#### Delete Film
```
DELETE {{base_url}}/admin/films/1
Headers:
Authorization: Bearer {{token}}
```

---

### 3. Users Management

#### Get All Users
```
GET {{base_url}}/admin/users
Headers:
Authorization: Bearer {{token}}
```

#### Create User
```
POST {{base_url}}/admin/users
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "phone": "081234567890",
  "address": "Jakarta",
  "role": "customer"
}
```

#### Update User
```
PUT {{base_url}}/admin/users/1
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "name": "Updated Name",
  "phone": "081234567891"
}
```

#### Delete User
```
DELETE {{base_url}}/admin/users/1
Headers:
Authorization: Bearer {{token}}
```

---

### 4. Schedules Management

#### Get All Schedules
```
GET {{base_url}}/admin/schedules
Headers:
Authorization: Bearer {{token}}
```

#### Create Schedule
```
POST {{base_url}}/admin/schedules
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "film_id": 1,
  "studio_id": 1,
  "price_id": 1,
  "date": "2025-01-15",
  "time": "14:00:00"
}
```

#### Update Schedule
```
PUT {{base_url}}/admin/schedules/1
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "date": "2025-01-16",
  "time": "16:00:00"
}
```

#### Delete Schedule
```
DELETE {{base_url}}/admin/schedules/1
Headers:
Authorization: Bearer {{token}}
```

---

### 5. Prices Management

#### Get All Prices
```
GET {{base_url}}/admin/prices
Headers:
Authorization: Bearer {{token}}
```

#### Update Price
```
PUT {{base_url}}/admin/prices/1
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "weekday": 35000,
  "weekend": 50000
}
```

---

### 6. Cashiers Management

#### Get All Cashiers
```
GET {{base_url}}/admin/cashiers
Headers:
Authorization: Bearer {{token}}
```

#### Create Cashier
```
POST {{base_url}}/admin/cashiers
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "name": "Cashier Name",
  "email": "cashier@example.com",
  "password": "password123",
  "phone": "081234567890",
  "shift": "Morning",
  "status": "Active"
}
```

#### Update Cashier
```
PUT {{base_url}}/admin/cashiers/1
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "shift": "Evening",
  "status": "Inactive"
}
```

#### Delete Cashier
```
DELETE {{base_url}}/admin/cashiers/1
Headers:
Authorization: Bearer {{token}}
```

---

### 7. Seats Management

#### Get Seats by Studio
```
GET {{base_url}}/seats/studio/1
Headers:
Authorization: Bearer {{token}}
```

#### Update Seat Status
```
PUT {{base_url}}/admin/seats/1
Headers:
Authorization: Bearer {{token}}
Content-Type: application/json

Body:
{
  "status": "maintenance"
}
```

**Status Options:**
- `available`
- `occupied`
- `maintenance`

---

## Response Format

Semua response menggunakan format standar:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {...}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {...}
}
```

---

## Testing Tips

1. **Selalu login dulu** untuk mendapatkan token
2. **Copy token** ke environment variable `token`
3. **Test endpoint** secara berurutan (GET → POST → PUT → DELETE)
4. **Perhatikan response** untuk memastikan data sesuai
5. **Gunakan ID yang valid** dari response GET sebelumnya

---

## Default Credentials

### Admin
- Email: `admin@cinema.com`
- Password: `password`

### Owner
- Email: `owner@cinema.com`
- Password: `password`

### Cashier
- Email: `cashier@cinema.com`
- Password: `password`

### Customer
- Email: `budi@example.com`
- Password: `password`
