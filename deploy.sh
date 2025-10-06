#!/bin/bash

echo "🚀 Deploying Cinema Backend to EC2"
echo "=================================="

# Update system
sudo apt update

# Install Docker if not exists
if ! command -v docker &> /dev/null; then
    echo "Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    sudo usermod -aG docker ubuntu
    rm get-docker.sh
fi

# Install Git if not exists
if ! command -v git &> /dev/null; then
    echo "Installing Git..."
    sudo apt install -y git
fi

# Install Certbot if not exists
if ! command -v certbot &> /dev/null; then
    echo "Installing Certbot..."
    sudo apt install -y certbot
fi

# Clone or update repository
if [ -d "cinema-backend" ]; then
    echo "Updating existing repository..."
    cd cinema-backend
    git pull origin main
else
    echo "Cloning repository..."
    git clone https://github.com/amayones/ujikom-be-ac.git cinema-backend
    cd cinema-backend
fi

# Setup environment
cp .env.production .env

# Create SSL directory
mkdir -p nginx/ssl

# Generate SSL certificate
echo "Generating SSL certificate..."
sudo certbot certonly --standalone -d be-ujikom.amayones.my.id --email admin@amayones.my.id --agree-tos --non-interactive

# Copy SSL certificates
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem ./nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/privkey.pem ./nginx/ssl/key.pem
sudo chown ubuntu:ubuntu ./nginx/ssl/*.pem

# Deploy application
echo "Starting application..."
docker compose -f docker-compose.ssl.yml down --remove-orphans 2>/dev/null || true
docker compose -f docker-compose.ssl.yml up -d --build

echo ""
echo "✅ Deployment completed!"
echo "🌐 Application available at: https://be-ujikom.amayones.my.id"
echo ""
echo "Check status:"
echo "docker ps"
echo "docker logs cinema-app"
echo "docker logs cinema-nginx"