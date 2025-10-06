#!/bin/bash

echo "🚀 Setting up Cinema Backend Server"
echo "==================================="

# Update system
echo "Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install Docker
echo "Installing Docker..."
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker ubuntu
rm get-docker.sh

# Install Git
echo "Installing Git..."
sudo apt install -y git

# Install Certbot
echo "Installing Certbot..."
sudo apt install -y certbot

echo ""
echo "✅ Server setup completed!"
echo ""
echo "⚠️  IMPORTANT: You need to logout and login again for Docker group changes to take effect."
echo ""
echo "After re-login, run these commands:"
echo "1. git clone https://github.com/amayones/ujikom-be-ac.git cinema-backend"
echo "2. cd cinema-backend"
echo "3. cp .env.production .env"
echo "4. mkdir -p nginx/ssl"
echo "5. Follow SSL setup in DEPLOYMENT.md"