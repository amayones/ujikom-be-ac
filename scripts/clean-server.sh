#!/bin/bash

echo "🧹 Cleaning EC2 Server - Cinema Backend"
echo "======================================="

# Stop all running containers
echo "Stopping all Docker containers..."
docker stop $(docker ps -aq) 2>/dev/null || true

# Remove all containers
echo "Removing all Docker containers..."
docker rm $(docker ps -aq) 2>/dev/null || true

# Remove all images
echo "Removing all Docker images..."
docker rmi $(docker images -q) 2>/dev/null || true

# Remove all volumes
echo "Removing all Docker volumes..."
docker volume rm $(docker volume ls -q) 2>/dev/null || true

# Remove all networks (except default ones)
echo "Removing custom Docker networks..."
docker network rm $(docker network ls -q --filter type=custom) 2>/dev/null || true

# Clean Docker system
echo "Cleaning Docker system..."
docker system prune -af --volumes

# Remove project directory
echo "Removing project directory..."
rm -rf ~/cinema-backend

# Remove SSL certificates
echo "Removing SSL certificates..."
sudo rm -rf /etc/letsencrypt 2>/dev/null || true

# Remove nginx config if exists
sudo rm -rf /etc/nginx 2>/dev/null || true

# Clean up any remaining processes
echo "Cleaning up processes..."
sudo pkill -f nginx 2>/dev/null || true
sudo pkill -f docker 2>/dev/null || true

echo ""
echo "✅ Server cleanup completed!"
echo "Ready for fresh deployment."
echo ""
echo "Next steps:"
echo "1. Follow DEPLOYMENT.md guide"
echo "2. Run fresh installation commands"
echo "3. Setup SSL certificates"
echo "4. Deploy application"