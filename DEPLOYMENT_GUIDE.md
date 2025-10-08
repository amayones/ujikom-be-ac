# 🚀 Panduan Lengkap Deploy Laravel Cinema ke AWS Lightsail

## 📋 Persiapan Awal

### 1. Setup AWS Lightsail Instance

1. **Login ke AWS Console** → Pilih **Lightsail**
2. **Create Instance**:
   - Platform: **Linux/Unix**
   - Blueprint: **OS Only** → **Ubuntu 22.04 LTS**
   - Instance plan: **$10/month** (2GB RAM, 1 vCPU) - recommended untuk Laravel
   - Instance name: `cinema-backend`
   - **Create Instance**

3. **Setup Networking**:
   - Masuk ke instance → **Networking** tab
   - **Create static IP** → Attach ke instance
   - **Firewall**: Pastikan port 22 (SSH), 80 (HTTP), 443 (HTTPS) terbuka

4. **Download SSH Key**:
   - **Account** → **SSH Keys** → Download default key
   - Simpan sebagai `lightsail-key.pem`

---

## 🔧 Koneksi ke Server

### 2. Setup SSH Connection

```bash
# Ubah permission key (di local)
chmod 400 lightsail-key.pem

# Connect ke server
ssh -i lightsail-key.pem ubuntu@YOUR_STATIC_IP
```

**Atau gunakan Termius:**
- Host: `YOUR_STATIC_IP`
- Username: `ubuntu`
- Key: Upload file `lightsail-key.pem`

---

## 🐳 Instalasi Docker & Dependencies

### 3. Update System & Install Docker

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install dependencies
sudo apt install -y curl wget git unzip software-properties-common

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add user to docker group
sudo usermod -aG docker ubuntu

# Enable Docker service
sudo systemctl enable docker
sudo systemctl start docker

# Logout dan login ulang
exit
```

**Login ulang dan test:**
```bash
ssh -i lightsail-key.pem ubuntu@YOUR_STATIC_IP
docker --version
docker-compose --version
docker ps
```

### 4. Install Nginx

```bash
# Install Nginx
sudo apt install nginx -y

# Enable dan start Nginx
sudo systemctl enable nginx
sudo systemctl start nginx

# Test Nginx
curl localhost
```

---

## 🔑 Setup SSH Keys untuk GitHub

### 5. Generate SSH Key untuk Deploy

```bash
# Generate SSH key untuk GitHub Actions
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/github_actions -N ""

# Set permissions
chmod 700 ~/.ssh
chmod 600 ~/.ssh/github_actions
chmod 644 ~/.ssh/github_actions.pub

# Add public key ke authorized_keys untuk GitHub Actions
cat ~/.ssh/github_actions.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

# Generate SSH key untuk GitHub repository
ssh-keygen -t ed25519 -C "deploy@cinema-backend" -f ~/.ssh/cinema_deploy -N ""
chmod 600 ~/.ssh/cinema_deploy
chmod 644 ~/.ssh/cinema_deploy.pub

# Copy public key untuk GitHub Deploy Key
cat ~/.ssh/cinema_deploy.pub

# Copy private key untuk GitHub Actions Secret
cat ~/.ssh/github_actions
```
**Setup di GitHub:**

**A. Deploy Key (untuk git clone/pull):**
1. Copy output dari `cat ~/.ssh/cinema_deploy.pub`
2. Masuk ke GitHub repo → **Settings** → **Deploy keys**
3. **Add deploy key**:
   - Title: `Cinema Backend Deploy Key`
   - Key: Paste public key
   - ✅ **Allow write access**
4. **Add deploy key**

**B. GitHub Actions Secret (untuk SSH connection):**
1. Copy output dari `cat ~/.ssh/github_actions` (SELURUH OUTPUT termasuk BEGIN/END)
2. Masuk ke GitHub repo → **Settings** → **Secrets and variables** → **Actions**
3. **Add secret**:
   - Name: `LIGHTSAIL_SSH_KEY`
   - Value: Paste private key
4. **Add secret**:
   - Name: `LIGHTSAIL_IP`
   - Value: Your static IP address

### 6. Setup SSH Config & Test Connections

```bash
# Create SSH config untuk GitHub
cat > ~/.ssh/config << 'EOF'
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/cinema_deploy
    IdentitiesOnly yes
EOF

# Set permission SSH config
chmod 600 ~/.ssh/config

# Test GitHub connection
ssh -T git@github.com

# Test GitHub Actions SSH (should work without password)
ssh -i ~/.ssh/github_actions ubuntu@localhost

# Clone repository (ganti USERNAME/REPO_NAME dengan repo kamu)
cd /home/ubuntu
git clone git@github.com:USERNAME/REPO_NAME.git cinema
cd cinema
```

**Jika masih error saat `ssh -T git@github.com`:**
```bash
# Debug SSH connection
ssh -vT git@github.com

# Atau test dengan explicit key
ssh -T git@github.com -i ~/.ssh/cinema_deploy

# Pastikan SSH agent running
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/cinema_deploy
ssh -T git@github.com
```

---

## 📁 Setup Project Files

### 7. Create Docker Files

**Dockerfile:**
```bash
cat > Dockerfile << 'EOF'
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
EOF
```

**docker-compose.yml:**
```bash
cat > docker-compose.yml << 'EOF'
version: '3.8'

services:
  app:
    build: .
    container_name: cinema-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./storage:/var/www/storage
    networks:
      - cinema-network
    depends_on:
      - db

  nginx:
    image: nginx:alpine
    container_name: cinema-nginx
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    networks:
      - cinema-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: cinema-db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword123
      MYSQL_DATABASE: cinema_db
      MYSQL_USER: cinema_user
      MYSQL_PASSWORD: cinema_pass123
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - cinema-network

volumes:
  db_data:

networks:
  cinema-network:
    driver: bridge
EOF
```

**nginx.conf:**
```bash
cat > nginx.conf << 'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF
```

### 8. Setup Environment File

```bash
# Copy .env.example to .env
cp .env.example .env

# Edit .env file
nano .env
```

**Update .env dengan:**
```env
APP_NAME="Cinema Booking API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://YOUR_STATIC_IP

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=cinema_db
DB_USERNAME=cinema_user
DB_PASSWORD=cinema_pass123

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

## 🌐 Setup Nginx Reverse Proxy

### 9. Configure Nginx

```bash
# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Create new site config
sudo tee /etc/nginx/sites-available/cinema << 'EOF'
server {
    listen 80;
    server_name _;

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_redirect off;
    }
}
EOF

# Enable site
sudo ln -s /etc/nginx/sites-available/cinema /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

---

## 🚀 Deploy Manual Pertama Kali

### 10. First Deployment

```bash
cd /home/ubuntu/cinema

# Build dan jalankan containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Wait for containers to start
sleep 30

# Installing composer
docker exec cinema-app composer install

# Generate app key
docker exec cinema-app php artisan key:generate --force

# Run migrations
docker exec cinema-app php artisan migrate --seed --force

# Set permissions
docker exec cinema-app chown -R www-data:www-data /var/www/storage
docker exec cinema-app chmod -R 755 /var/www/storage

# Check containers
docker ps
```

### 11. Test Deployment

```bash
# Test local
curl localhost

# Test dari browser
# http://YOUR_STATIC_IP
```

---

## 🔄 Setup GitHub Actions

### 12. Verify GitHub Secrets

**Pastikan secrets sudah benar di GitHub repo → Settings → Secrets and variables → Actions:**

1. **LIGHTSAIL_IP**: `YOUR_STATIC_IP`
2. **LIGHTSAIL_SSH_KEY**: Private key dari `~/.ssh/github_actions` (sudah di-setup di langkah 5)

**Format LIGHTSAIL_SSH_KEY harus seperti ini:**
```
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA...
...
-----END RSA PRIVATE KEY-----
```

**Test GitHub Actions SSH:**
```bash
# Di server, test apakah GitHub Actions bisa connect
ssh -i ~/.ssh/github_actions ubuntu@localhost
# Jika berhasil tanpa password, berarti setup benar
```

### 13. GitHub Actions Workflow (Sudah Dibuat)

File `.github/workflows/deploy.yml` sudah dibuat dengan konfigurasi yang benar menggunakan `appleboy/ssh-action` yang lebih reliable untuk SSH connection.

**Workflow akan:**
1. Checkout code dari repository
2. Connect ke server menggunakan SSH key
3. Pull latest changes dari GitHub
4. Rebuild dan restart Docker containers
5. Run Laravel migrations dan optimizations
6. Set proper permissions

**Trigger deployment:**
- Otomatis saat push ke branch `main`
- Manual via GitHub Actions tab → "Run workflow"

---

## 🔍 Monitoring & Troubleshooting

### 14. Useful Commands

```bash
# Check containers
docker ps -a

# View logs
docker-compose logs -f
docker logs cinema-app
docker logs cinema-nginx
docker logs cinema-db

# Enter container
docker exec -it cinema-app bash

# Restart services
docker-compose restart
sudo systemctl restart nginx

# Check disk space
df -h

# Check memory
free -h

# Check processes
htop
```

### 15. Common Issues & Solutions

**GitHub Actions SSH Error (Permission denied):**
```bash
# Re-generate GitHub Actions SSH key
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/github_actions -N ""
cat ~/.ssh/github_actions.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

# Copy private key dan update GitHub Secret LIGHTSAIL_SSH_KEY
cat ~/.ssh/github_actions
```

**Container tidak start:**
```bash
docker-compose down
docker system prune -f
docker-compose up -d --build
```

**Permission issues:**
```bash
sudo chown -R ubuntu:ubuntu /home/ubuntu/cinema
docker exec cinema-app chown -R www-data:www-data /var/www/storage
```

**Database connection error:**
```bash
docker exec cinema-db mysql -u root -p -e "SHOW DATABASES;"
```

**Nginx error:**
```bash
sudo nginx -t
sudo systemctl status nginx
sudo tail -f /var/log/nginx/error.log
```

**Git pull error:**
```bash
# Setup git config jika belum
git config --global user.email "deploy@cinema.com"
git config --global user.name "Cinema Deploy"

# Reset jika ada conflict
git reset --hard origin/main
git pull origin main
```

---

## ✅ Final Checklist

- [ ] AWS Lightsail instance running
- [ ] Static IP attached
- [ ] SSH connection working
- [ ] Docker & Docker Compose installed
- [ ] Nginx installed and configured
- [ ] GitHub SSH key setup
- [ ] Project files created
- [ ] Environment configured
- [ ] First deployment successful
- [ ] GitHub Actions secrets configured
- [ ] Auto-deployment working

**Test URLs:**
- API Base: `http://YOUR_STATIC_IP/api`
- Health Check: `http://YOUR_STATIC_IP/api/health`

**Default Admin Login:**
- Email: `admin@cinema.com`
- Password: `password`

---

## 🔒 Security Recommendations

1. **Setup SSL Certificate** (Let's Encrypt)
2. **Configure Firewall** (UFW)
3. **Regular Backups**
4. **Monitor Logs**
5. **Update Dependencies**

Deployment selesai! 🎉