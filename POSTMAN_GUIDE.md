# Postman Collection Guide - Absolute Cinema API

## Setup

### 1. Import Collection
- Import `Absolute_Cinema_API.postman_collection.json` ke Postman
- Collection sudah include Bearer Token authentication otomatis

### 2. Environment Variables
Collection menggunakan 2 variables:
- `base_url`: `https://be-ujikom.amayones.my.id/api` (production) atau `http://localhost:8000/api` (local)
- `access_token`: Otomatis tersimpan setelah login

### 3. Authentication Flow
1. **Login** → Token otomatis tersimpan di `{{access_token}}`
2. Semua request authenticated menggunakan Bearer Token dari variable
3. **Logout** → Token dihapus dari server (manual clear variable jika perlu)

## Testing Workflow

### Step 1: Authentication
```
1. Buka folder "Authentication"
2. Run "Login" dengan credentials:
   - Admin: admin@cinema.com / password
   - Owner: owner@cinema.com / password
   - Cashier: cashier@cinema.com / password
   - Customer: budi@example.com / password
3. Token otomatis tersimpan, cek di Console: "Token saved: ..."
```

### Step 2: Test Admin Endpoints
```
1. Pastikan sudah login sebagai admin
2. Test CRUD Films:
   - Get All Films
   - Create Film (isi body sesuai kebutuhan)
   - Update Film (ubah ID di URL)
   - Delete Film
3. Test CRUD lainnya: Schedules, Users, Prices, Seats, Cashiers
```

### Step 3: Test Owner Endpoints
```
1. Login sebagai owner
2. Test:
   - Get Dashboard
   - Get Financial Reports
   - Get Performance Reports
```

### Step 4: Test Cashier Endpoints
```
1. Login sebagai cashier
2. Test:
   - Get Dashboard
   - Create Offline Booking
   - Get Transactions
   - Print Ticket
```

### Step 5: Test Public Endpoints
```
1. Tidak perlu login (auth: noauth)
2. Test:
   - Get All Films (Public)
   - Get Schedules (Public)
   - Get Seats by Studio
```

### Step 6: Test Customer Endpoints
```
1. Login sebagai customer
2. Test:
   - Create Order
   - Get My Orders
   - Get Profile
   - Update Profile
   - Process Payment
```

## Bearer Token Authentication

### Automatic Token Management
Collection menggunakan **Collection-level Bearer Token**:
```
Authorization: Bearer {{access_token}}
```

### Manual Token Management
Jika perlu set token manual:
1. Klik collection → Variables tab
2. Set `access_token` value dengan token dari login response
3. Save

### Override Authentication
Beberapa endpoint (Public) menggunakan `"auth": "noauth"` untuk override collection-level auth.

## Response Format

Semua endpoint menggunakan format standar:
```json
{
  "success": true,
  "message": "Success message",
  "data": {...}
}
```

Error response:
```json
{
  "success": false,
  "message": "Error message"
}
```

## Test Accounts

| Role     | Email                | Password |
|----------|---------------------|----------|
| Admin    | admin@cinema.com    | password |
| Owner    | owner@cinema.com    | password |
| Cashier  | cashier@cinema.com  | password |
| Cashier  | kasir2@cinema.com   | password |
| Customer | budi@example.com    | password |
| Customer | john@example.com    | password |
| Customer | jane@example.com    | password |

## Tips

1. **Auto-save Token**: Script di request "Login" otomatis menyimpan token
2. **Check Console**: Lihat Postman Console untuk debug token
3. **Token Expiry**: Jika dapat 401 Unauthorized, login ulang
4. **Environment**: Ganti `base_url` untuk testing local/production
5. **Organized Folders**: Endpoints dikelompokkan berdasarkan role

## Common Issues

### 401 Unauthorized
- Token expired atau invalid
- Solution: Login ulang

### 403 Forbidden
- User tidak punya akses ke endpoint
- Solution: Login dengan role yang sesuai

### 422 Validation Error
- Request body tidak sesuai validasi
- Solution: Cek required fields di body

### CORS Error
- Hanya terjadi di browser, tidak di Postman
- Postman tidak terpengaruh CORS policy
