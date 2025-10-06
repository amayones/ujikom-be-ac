# Deployment Guide

## Clean Server Setup (Fresh Start)

### Step 1: Clean Existing Installation
```bash
# Stop all containers
docker stop $(docker ps -aq) 2>/dev/null || true
docker rm $(docker ps -aq) 2>/dev/null || true

# Remove all images
docker rmi $(docker images -q) 2>/dev/null || true

# Remove all volumes
docker volume rm $(docker volume ls -q) 2>/dev/null || true

# Remove project directory
rm -rf ~/cinema-backend

# Clean up SSL certificates (if exists)
sudo rm -rf /etc/letsencrypt
```

### Step 2: Fresh Server Setup

#### Prerequisites
- Ubuntu 20.04+ EC2 instance
- Security Group with ports 22, 80, 443 open
- Domain pointing to EC2 IP

#### Install Dependencies
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker ubuntu

# Install Git
sudo apt install -y git

# Logout and login again for docker group
exit
```

### Step 3: Clone and Setup Project
```bash
# Clone repository
git clone https://github.com/amayones/ujikom-be-ac.git cinema-backend
cd cinema-backend

# Copy production environment
cp .env.production .env

# Create SSL directory
mkdir -p nginx/ssl
```

### Step 4: SSL Certificate Setup
```bash
# Install Certbot
sudo apt install -y certbot

# Generate SSL certificate
sudo certbot certonly --standalone -d be-ujikom.amayones.my.id --email your-email@domain.com --agree-tos --non-interactive

# Copy certificates
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem ./nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/privkey.pem ./nginx/ssl/key.pem
sudo chown ubuntu:ubuntu ./nginx/ssl/*.pem
```

### Step 5: Deploy Application
```bash
# Start services with SSL
docker compose -f docker-compose.ssl.yml up -d

# Check status
docker ps
docker logs cinema-app
docker logs cinema-nginx
```

### Step 6: Test Deployment
```bash
# Test HTTP (should redirect to HTTPS)
curl -I http://be-ujikom.amayones.my.id

# Test HTTPS
curl -I https://be-ujikom.amayones.my.id

# Test API endpoint
curl https://be-ujikom.amayones.my.id/api/films
```

## GitHub Actions Auto-Deployment

### Setup GitHub Secrets
1. Go to GitHub repository → Settings → Secrets and variables → Actions
2. Add these secrets:
   - `EC2_HOST`: Your EC2 public IP address
   - `EC2_SSH_KEY`: Your private SSH key content (the .pem file content)

### Workflow Configuration
The workflow is already configured in `.github/workflows/deploy.yml`:

```yaml
name: Deploy to EC2 (Cinema Backend)
on:
  push:
    branches: [main]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to EC2 via SSH
        uses: appleboy/ssh-action@v1.0.0
        with:
          host: ${{ secrets.EC2_HOST }}
          username: ubuntu
          key: ${{ secrets.EC2_SSH_KEY }}
          script: |
            cd ~/cinema-backend
            git stash
            git pull origin main
            docker compose -f docker-compose.ssl.yml down --remove-orphans
            docker compose -f docker-compose.ssl.yml up -d --build
```

### How Auto-Deployment Works
1. Push code to main branch
2. GitHub Actions triggers automatically
3. Connects to EC2 via SSH
4. Pulls latest code
5. Rebuilds and restarts containers
6. Zero-downtime deployment

### First Time Setup Only
After initial server setup, every code push will automatically deploy!

### Health Checks

```bash
# Check container status
docker ps

# Check logs
docker logs cinema-app
docker logs cinema-nginx

# Test endpoints
curl -I https://be-ujikom.amayones.my.id/api/films
```

### SSL Certificate Renewal

```bash
# Auto-renewal setup
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Backup Strategy

```bash
# Database backup
docker exec cinema-db mysqldump -u laravel -p cinema > backup.sql

# Restore database
docker exec -i cinema-db mysql -u laravel -p cinema < backup.sql
```

### Monitoring

- **Application Logs**: `docker logs cinema-app`
- **Nginx Logs**: `docker logs cinema-nginx`
- **Database Logs**: `docker logs cinema-db`
- **System Resources**: `htop`, `df -h`

### Troubleshooting

**Container won't start:**
```bash
docker compose -f docker-compose.ssl.yml logs
```

**SSL certificate issues:**
```bash
openssl s_client -connect be-ujikom.amayones.my.id:443
```

**Database connection issues:**
```bash
docker exec -it cinema-db mysql -u laravel -p
```

**Port conflicts:**
```bash
sudo netstat -tlnp | grep :80
sudo netstat -tlnp | grep :443
```