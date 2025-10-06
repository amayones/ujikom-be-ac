# System Architecture

## Overview

Cinema Booking System with microservices architecture deployed on AWS.

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend API   │    │   Database      │
│   (React SPA)   │◄──►│   (Laravel)     │◄──►│   (MySQL)       │
│   AWS Amplify   │    │   AWS EC2       │    │   Docker        │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## Frontend Architecture

### Technology Stack
- **Framework**: React 18 with Vite
- **Styling**: Tailwind CSS
- **Routing**: React Router v6
- **State**: Context API + Local State
- **HTTP Client**: Axios
- **Deployment**: AWS Amplify

### Component Structure
```
src/
├── components/
│   ├── Navbar/           # Role-based navigation
│   ├── ErrorBoundary     # Error handling
│   └── TrailingSlashRedirect
├── pages/
│   ├── Auth/             # Authentication
│   ├── User/             # Customer interface
│   ├── Admin/            # Management interface
│   ├── Owner/            # Analytics interface
│   └── Cashier/          # POS interface
├── services/
│   ├── api.js            # HTTP configuration
│   └── index.js          # API services
├── context/
│   └── AuthContext.jsx   # Authentication state
└── utils/
    └── pathUtils.js      # URL utilities
```

### Data Flow
1. User interaction triggers component
2. Component calls service function
3. Service makes HTTP request to API
4. Response updates component state
5. UI re-renders with new data

## Backend Architecture

### Technology Stack
- **Framework**: Laravel 11
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum (JWT)
- **Containerization**: Docker
- **Web Server**: Nginx (production)
- **Deployment**: AWS EC2

### Application Structure
```
app/
├── Http/
│   ├── Controllers/      # API endpoints
│   │   ├── AuthController
│   │   ├── CustomerController
│   │   ├── AdminController
│   │   ├── OwnerController
│   │   └── CashierController
│   └── Middleware/       # Request processing
├── Models/               # Database models
│   ├── User
│   ├── Film
│   ├── Schedule
│   ├── Order
│   └── Seat
└── Services/             # Business logic
```

### Database Schema
```sql
users (id, nama, email, role, ...)
films (id, judul, genre, status, ...)
schedules (id, film_id, tanggal, jam, ...)
seats (id, schedule_id, nomor_kursi, status)
orders (id, user_id, schedule_id, total, ...)
order_details (id, order_id, seat_id, harga)
```

### API Design
- **RESTful endpoints** with resource-based URLs
- **Role-based access control** via middleware
- **Rate limiting** to prevent abuse
- **Input validation** for security
- **Consistent JSON responses**

## Security Architecture

### Authentication Flow
```
1. User submits credentials
2. Server validates against database
3. JWT token generated and returned
4. Token stored in localStorage
5. Token sent in Authorization header
6. Server validates token on each request
```

### Security Measures
- **HTTPS encryption** for all communications
- **JWT tokens** with expiration
- **Rate limiting** on sensitive endpoints
- **Input validation** and sanitization
- **SQL injection protection** via ORM
- **CORS configuration** for cross-origin requests

## Deployment Architecture

### Production Environment
```
Internet
    │
    ▼
┌─────────────────┐
│   CloudFlare    │  # CDN + DDoS protection
│   (Optional)     │
└─────────────────┘
    │
    ▼
┌─────────────────┐    ┌─────────────────┐
│   AWS Amplify   │    │   AWS EC2       │
│   Frontend      │    │   Backend       │
│   - React SPA   │    │   - Docker      │
│   - CDN         │    │   - Nginx       │
│   - SSL         │    │   - Laravel     │
└─────────────────┘    │   - MySQL       │
                       │   - SSL         │
                       └─────────────────┘
```

### CI/CD Pipeline
```
GitHub Push
    │
    ▼
┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │
│   GitHub Actions│    │   GitHub Actions│
│   │               │    │   │               │
│   ├─ Build       │    │   ├─ Pull Code   │
│   ├─ Test        │    │   ├─ Build Image │
│   └─ Deploy      │    │   └─ Deploy      │
│     to Amplify   │    │     to EC2      │
└─────────────────┘    └─────────────────┘
```

## Scalability Considerations

### Horizontal Scaling
- **Load balancer** for multiple backend instances
- **Database replication** for read scaling
- **CDN** for static asset delivery
- **Caching layer** (Redis) for session data

### Performance Optimization
- **Code splitting** in frontend
- **Database indexing** for queries
- **API response caching**
- **Image optimization**
- **Gzip compression**

## Monitoring & Logging

### Application Monitoring
- **Error tracking** via logs
- **Performance metrics** collection
- **Uptime monitoring**
- **Database query analysis**

### Infrastructure Monitoring
- **Server resource usage**
- **Docker container health**
- **SSL certificate expiration**
- **Backup verification**

## Disaster Recovery

### Backup Strategy
- **Database backups** (daily automated)
- **Code repository** (GitHub)
- **SSL certificates** backup
- **Environment configuration** backup

### Recovery Procedures
1. **Database restore** from backup
2. **Application redeployment** from repository
3. **SSL certificate** restoration
4. **DNS configuration** verification