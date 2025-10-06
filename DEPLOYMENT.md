# Deployment Guide

## Production Deployment

### Prerequisites
- Ubuntu 20.04+ server
- Docker & Docker Compose
- Domain with SSL certificate
- GitHub repository access

### Server Setup

1. **Install Docker**
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
```

2. **Clone Repository**
```bash
git clone https://github.com/amayones/ujikom-be-ac.git
cd ujikom-be-ac
```

3. **Environment Configuration**
```bash
cp .env.production .env
# Edit .env with production values
```

4. **SSL Certificate Setup**
```bash
sudo apt install certbot
sudo certbot certonly --standalone -d be-ujikom.amayones.my.id
```

5. **Deploy with SSL**
```bash
# Copy SSL certificates
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem ./nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/privkey.pem ./nginx/ssl/key.pem
sudo chown $USER:$USER ./nginx/ssl/*.pem

# Start services
docker compose -f docker-compose.ssl.yml up -d
```

### GitHub Actions Deployment

Automatic deployment configured in `.github/workflows/deploy.yml`:

```yaml
name: Deploy to EC2
on:
  push:
    branches: [main]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy via SSH
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

### Required Secrets
- `EC2_HOST` - Server IP address
- `EC2_SSH_KEY` - Private SSH key

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