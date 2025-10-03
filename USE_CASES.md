# Cinema Booking System Use Cases

## Actors and Their Use Cases

### 1. Pelanggan/User (Customer)
**Primary Use Case: Book Movie Tickets**

**Use Cases:**
- Login to system
- View list of films (play now, coming soon, history)
- View film details
- Select film schedule
- Choose seats
- Make payment
- View invoice
- View booking history
- Edit profile

**Flow Example:**
1. Customer logs in
2. Browses available films
3. Selects a film and views details
4. Chooses preferred schedule
5. Selects available seats
6. Proceeds to payment
7. Receives invoice
8. Can view booking in history

### 2. Admin
**Primary Use Case: Manage Cinema Operations**

**Use Cases:**
- Manage films (CRUD operations)
- Manage customers (view, update)
- Manage schedules (create, view)
- Manage prices (create, view)
- Manage cashiers (CRUD operations)
- Manage seats (create, view)

**Flow Example:**
1. Admin logs in
2. Adds new film with details
3. Creates schedules for the film
4. Sets pricing for different time slots
5. Manages seat arrangements
6. Monitors customer activities

### 3. Owner
**Primary Use Case: Monitor Financial Performance**

**Use Cases:**
- View financial reports (income/expenses)
- View monthly performance reports
- Add expense records
- Monitor overall business performance

**Flow Example:**
1. Owner logs in
2. Views financial dashboard
3. Analyzes income vs expenses
4. Reviews monthly trends
5. Adds operational expenses
6. Makes business decisions based on reports

### 4. Kasir (Cashier)
**Primary Use Case: Process Ticket Transactions**

**Use Cases:**
- Book tickets offline (walk-in customers)
- Print physical tickets
- Process online ticket confirmations
- Handle ticket cancellations

**Flow Example:**
1. Cashier logs in
2. For walk-in customers: creates offline booking
3. Prints tickets for customers
4. For online orders: confirms or cancels pending orders
5. Handles customer service issues

## System Relationships

```
[Pelanggan] ---- (Login)
[Pelanggan] ---- (View Films)
[Pelanggan] ---- (Book Tickets)
[Pelanggan] ---- (View History)
[Pelanggan] ---- (Edit Profile)

[Admin] ---- (Manage Films)
[Admin] ---- (Manage Customers)
[Admin] ---- (Manage Schedules)
[Admin] ---- (Manage Prices)
[Admin] ---- (Manage Cashiers)
[Admin] ---- (Manage Seats)

[Owner] ---- (View Financial Reports)
[Owner] ---- (Monitor Performance)

[Kasir] ---- (Process Offline Bookings)
[Kasir] ---- (Print Tickets)
[Kasir] ---- (Process Online Orders)
```

## Business Rules
1. Only authenticated users can access system features
2. Each role has specific permissions and access levels
3. Seats can only be booked if available
4. Payments must be confirmed before ticket generation
5. Only cashiers can process offline bookings
6. Only owners can view financial reports
7. Only admins can manage system data