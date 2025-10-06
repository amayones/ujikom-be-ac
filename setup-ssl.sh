#!/bin/bash

# Install certbot
sudo apt update
sudo apt install -y certbot

# Stop current containers
docker compose down

# Generate SSL certificate
sudo certbot certonly --standalone -d be-ujikom.amayones.my.id --email your-email@example.com --agree-tos --non-interactive

# Copy certificates to nginx folder
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/fullchain.pem ./nginx/ssl/cert.pem
sudo cp /etc/letsencrypt/live/be-ujikom.amayones.my.id/privkey.pem ./nginx/ssl/key.pem
sudo chown $USER:$USER ./nginx/ssl/*.pem

# Start with SSL configuration
docker compose -f docker-compose.ssl.yml up -d

echo "SSL setup complete! Access: https://be-ujikom.amayones.my.id"