# 🚀 Complete Cinema Backend Deployment Guide

Panduan lengkap dari awal sampai akhir untuk deploy Cinema Backend ke AWS EC2 dengan GitHub Actions auto-deployment.

## 📋 Prerequisites

### Yang Dibutuhkan:
- ✅ AWS Account dengan EC2 instance Ubuntu 20.04+
- ✅ Domain name (contoh: be-ujikom.amayones.my.id)
- ✅ GitHub account
- ✅ SSH client (Terminal/CMD atau Termius)
- ✅ Text editor (VS Code, Notepad++, dll)

### AWS EC2 Setup:
- Instance Type: t2.micro atau lebih besar
- Storage: minimal 20GB
- Security Group: port 22, 80, 443 terbuka
- Key Pair: download file .pem

## 🏗️ Step 1: Setup AWS EC2 Instance

### 1.1 Launch EC2 Instance
1. Login ke AWS Console
2. EC2 → Launch Instance
3. Pilih **Ubuntu Server 20.04 LTS**
4. Instance type: **t2.micro** (free tier)
5. Key pair: **Create new** atau pilih existing
6. Storage: **20GB gp3**

### 1.2 Configure Security Group
```
Inbound Rules:
- SSH (22) - Source: My IP atau 0.0.0.0/0
- HTTP (80) - Source: 0.0.0.0/0  
- HTTPS (443) - Source: 0.0.0.0/0
```

### 1.3 Launch & Get IP
- Launch instance
- Catat **Public IPv4 address** (contoh: 54.255.65.241)
- Download file .pem key jika baru dibuat

## 🌐 Step 2: Setup Domain DNS

### 2.1 Configure DNS Records
Di DNS provider Anda (Cloudflare, Route53, dll):
```
Type: A
Name: be-ujikom.amayones.my.id
Value: 54.255.65.241 (IP EC2 Anda)
TTL: 300
```

### 2.2 Verify DNS
```bash
# Test DNS resolution
nslookup be-ujikom.amayones.my.id
# Harus return IP EC2 Anda
```

## 📁 Step 3: Prepare GitHub Repository

### 3.1 Fork/Clone Repository
```bash
# Clone repository
git clone https://github.com/amayones/ujikom-be-ac.git
cd ujikom-be-ac
```

### 3.2 Create GitHub Actions Workflow
Buat file `.github/workflows/deploy.yml`:

```yaml
name: Deploy to EC2 (Cinema Backend)

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Deploy to EC2 via SSH
        uses: appleboy/ssh-action@v1.0.0
        with:
          host: ${{ secrets.EC2_HOST }}
          username: ubuntu
          key: ${{ secrets.EC2_SSH_KEY }}
          script: |
            # Check disk space
            df -h
            
            # Clean up Docker to free space
            docker system prune -af --volumes
            docker image prune -af
            
            # Remove old containers and images
            docker container prune -f
            docker volume prune -f
            
            # Create directory if not exists
            if [ ! -d "~/cinema-backend" ]; then
              git clone https://github.com/amayones/ujikom-be-ac.git ~/cinema-backend
            fi
            
            # Navigate to project directory
            cd ~/cinema-backend
            
            # Update code
            git stash
            git pull origin main
            
            # Setup environment if not exists
            if [ ! -f ".env" ]; then
              cp .env.production .env
            fi
            
            # Create SSL directory if not exists
            mkdir -p nginx/ssl
            
            # Check if SSL certificates exist, if not use HTTP deployment
            if [ -f "/etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem" ]; then
              echo "SSL certificates found, deploying with HTTPS..."
              sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem ./nginx/ssl/cert.pem
              sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/privkey.pem ./nginx/ssl/key.pem
              sudo chown ubuntu:ubuntu ./nginx/ssl/*.pem
              docker compose -f docker-compose.ssl.yml down --remove-orphans
              docker compose -f docker-compose.ssl.yml up -d --build
            else
              echo "SSL certificates not found, deploying with HTTP..."
              docker compose down --remove-orphans
              docker compose up -d --build
            fi
```

### 3.3 Create Docker Configuration Files

**docker-compose.yml** (HTTP version):
```yaml
services:
  app:
    build: .
    container_name: cinema-app
    restart: unless-stopped
    ports:
      - "80:8000"
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      APP_URL: http://be-ujikom.amayones.my.id
      DB_HOST: db
      DB_PORT: 3306
      DB_DATABASE: cinema
      DB_USERNAME: laravel
      DB_PASSWORD: laravel
    depends_on:
      - db
    volumes:
      - ./.env:/var/www/.env

  db:
    image: mysql:8.0
    container_name: cinema-db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: cinema
      MYSQL_USER: laravel
      MYSQL_PASSWORD: laravel
    volumes:
      - cinema-db-data:/var/lib/mysql

volumes:
  cinema-db-data:
```

**docker-compose.ssl.yml** (HTTPS version):
```yaml
services:
  app:
    build: .
    container_name: cinema-app
    restart: unless-stopped
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      APP_URL: https://be-ujikom.amayones.my.id
      DB_HOST: db
      DB_PORT: 3306
      DB_DATABASE: cinema
      DB_USERNAME: laravel
      DB_PASSWORD: laravel
    depends_on:
      - db
    volumes:
      - ./.env:/var/www/.env

  nginx:
    image: nginx:alpine
    container_name: cinema-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./nginx/ssl:/etc/nginx/ssl
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: cinema-db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: cinema
      MYSQL_USER: laravel
      MYSQL_PASSWORD: laravel
    volumes:
      - cinema-db-data:/var/lib/mysql

volumes:
  cinema-db-data:
```

### 3.4 Create Nginx Configuration
Buat directory `nginx/` dan file `nginx/nginx.conf`:

```nginx
events {
    worker_connections 1024;
}

http {
    upstream app {
        server app:8000;
    }

    # HTTP to HTTPS redirect
    server {
        listen 80;
        server_name be-ujikom.amayones.my.id;
        return 301 https://$server_name$request_uri;
    }

    # HTTPS server
    server {
        listen 443 ssl;
        server_name be-ujikom.amayones.my.id;

        ssl_certificate /etc/nginx/ssl/cert.pem;
        ssl_certificate_key /etc/nginx/ssl/key.pem;

        location / {
            proxy_pass http://app;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
        }
    }
}
```

### 3.5 Create Dockerfile
```dockerfile
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chmod -R 755 storage bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
```

### 3.6 Create Environment Files
**.env.production**:
```env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:Zg1mO4hX4W2S+ulG+niNSPip9ZzlaH3ASQuMv8HFwv8=
APP_DEBUG=false
APP_URL=https://be-ujikom.amayones.my.id
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=cinema
DB_USERNAME=laravel
DB_PASSWORD=laravel

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
SESSION_LIFETIME=120

LOG_LEVEL=error
```

## 🔐 Step 4: Setup GitHub Secrets

### 4.1 Get SSH Key Content
```bash
# Windows
type your-key.pem

# Mac/Linux  
cat your-key.pem

# Copy seluruh output termasuk header dan footer
```

### 4.2 Add GitHub Secrets
1. Buka GitHub repository
2. Settings → Secrets and variables → Actions
3. Click "New repository secret"
4. Tambahkan 2 secrets:

**Secret 1:**
```
Name: EC2_HOST
Value: 54.255.65.241
```

**Secret 2:**
```
Name: EC2_SSH_KEY  
Value: -----BEGIN RSA PRIVATE KEY-----
[paste seluruh isi file .pem]
-----END RSA PRIVATE KEY-----
```

### 4.3 Test Auto-Deploy
```bash
# Commit dan push untuk trigger deployment
git add .
git commit -m "Initial deployment setup"
git push origin main

# Cek GitHub Actions tab untuk melihat progress
```

## 🖥️ Step 5: SSH ke EC2 Server

### Opsi 1: Command Line
```bash
# SSH ke server
ssh -i your-key.pem ubuntu@54.255.65.241
```

### Opsi 2: Termius (Recommended)
1. Download Termius dari https://termius.com
2. Install dan buka aplikasi
3. Click "+ New Host"
4. Konfigurasi:
   ```
   Alias: Cinema Backend Server
   Hostname: 54.255.65.241
   Username: ubuntu
   Port: 22
   ```
5. Import SSH key (.pem file)
6. Save dan Connect

## 🔒 Step 6: Setup SSL Certificate

### 6.1 Install Certbot
```bash
# Update system
sudo apt update

# Install Certbot
sudo apt install -y certbot
```

### 6.2 Generate SSL Certificate
```bash
# Generate certificate (ganti email dengan email Anda)
sudo certbot certonly --standalone -d be-ujikom.amayones.my.id --email admin@amayones.my.id --agree-tos --non-interactive
```

### 6.3 Copy SSL ke Project
```bash
# Masuk ke project directory
cd ~/cinema-backend

# Create SSL directory
mkdir -p nginx/ssl

# Copy certificates
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem ./nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/privkey.pem ./nginx/ssl/key.pem
sudo chown ubuntu:ubuntu ./nginx/ssl/*.pem
```

### 6.4 Deploy dengan SSL
```bash
# Stop HTTP version
docker compose down --remove-orphans

# Start HTTPS version
docker compose -f docker-compose.ssl.yml up -d
```

## 🛠️ Step 7: Fix Laravel Issues

### 7.1 Fix Cache Permissions
```bash
# Masuk ke Laravel container
docker exec -it cinema-app bash

# Create cache directories
mkdir -p /var/www/bootstrap/cache
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs

# Set permissions
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Exit container
exit
```

### 7.2 Setup Database (Optional)
```bash
# Run migrations
docker exec -it cinema-app php artisan migrate --force

# Seed database
docker exec -it cinema-app php artisan db:seed --force
```

## ⚙️ Step 8: Setup SSL Auto-Renewal

### 8.1 Create Cron Job
```bash
# Edit crontab
sudo crontab -e

# Pilih editor (1 untuk nano)
# Tambahkan baris ini:
0 12 * * * /usr/bin/certbot renew --quiet

# Save: Ctrl+X, Y, Enter
```

### 8.2 Test Renewal
```bash
# Test renewal (dry run)
sudo certbot renew --dry-run
```

## ✅ Step 9: Verification & Testing

### 9.1 Check Container Status
```bash
# List running containers
docker ps

# Check logs
docker logs cinema-app
docker logs cinema-nginx
```

### 9.2 Test HTTP/HTTPS
```bash
# Test HTTP (should redirect to HTTPS)
curl -I http://be-ujikom.amayones.my.id

# Test HTTPS
curl -I https://be-ujikom.amayones.my.id

# Test API endpoint
curl https://be-ujikom.amayones.my.id/api/films
```

### 9.3 Test Auto-Deploy
```bash
# Make a small change and push
echo "# Test" >> README.md
git add .
git commit -m "Test auto-deploy"
git push origin main

# Check GitHub Actions tab
```

## 🎉 Deployment Complete!

### ✅ What's Working:
- **Backend API**: https://be-ujikom.amayones.my.id
- **Auto-Deploy**: Every push to main branch
- **SSL Certificate**: Valid HTTPS with auto-renewal
- **Database**: MySQL running in container
- **Monitoring**: Docker logs available

### 🔄 Maintenance Commands:

**Restart Application:**
```bash
docker compose -f docker-compose.ssl.yml restart
```

**Update SSL Certificate:**
```bash
sudo certbot renew
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem ~/cinema-backend/nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/privkey.pem ~/cinema-backend/nginx/ssl/key.pem
docker compose -f docker-compose.ssl.yml restart nginx
```

**Clean Docker:**
```bash
docker system prune -af --volumes
```

**Check Disk Space:**
```bash
df -h
```

## 🚨 Troubleshooting

### Container Won't Start:
```bash
docker compose -f docker-compose.ssl.yml logs
docker compose -f docker-compose.ssl.yml restart
```

### SSL Certificate Issues:
```bash
sudo certbot delete --cert-name be-ujikom.amayones.my.id
sudo certbot certonly --standalone -d be-ujikom.amayones.my.id --email admin@amayones.my.id --agree-tos --non-interactive
```

### Laravel Cache Issues:
```bash
docker exec -it cinema-app php artisan cache:clear
docker exec -it cinema-app php artisan config:clear
```

### GitHub Actions Failed:
- Check secrets are correctly set
- Verify EC2 is running
- Check disk space: `df -h`
- Clean Docker: `docker system prune -af`

---

**🎯 Selamat! Cinema Backend sudah berhasil di-deploy dengan auto-deployment yang lengkap!**