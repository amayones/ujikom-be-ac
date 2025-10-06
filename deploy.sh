#!/bin/bash

# Deployment script with error handling and rollback
set -e

PROJECT_DIR="~/cinema-backend"
BACKUP_DIR="~/cinema-backend-backup"
LOG_FILE="~/deployment.log"

# Function to log messages
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

# Function to rollback on failure
rollback() {
    log "ERROR: Deployment failed. Starting rollback..."
    
    if [ -d "$BACKUP_DIR" ]; then
        log "Restoring from backup..."
        cd "$PROJECT_DIR"
        docker compose down --timeout 30 || true
        
        # Restore backup
        cp -r "$BACKUP_DIR"/* "$PROJECT_DIR"/ || true
        
        # Start old version
        docker compose up -d || log "Rollback failed - manual intervention required"
        log "Rollback completed"
    else
        log "No backup found - manual intervention required"
    fi
    
    exit 1
}

# Trap errors and rollback
trap rollback ERR

log "Starting deployment process..."

# Navigate to project directory
cd "$PROJECT_DIR" || { log "Project directory not found"; exit 1; }

# Create backup
log "Creating backup..."
rm -rf "$BACKUP_DIR"
cp -r "$PROJECT_DIR" "$BACKUP_DIR"

# Pull latest changes
log "Pulling latest changes..."
git pull origin main

# Stop containers gracefully
log "Stopping containers..."
docker compose down --timeout 30 || log "Some containers were already stopped"

# Clean up resources
log "Cleaning up resources..."
docker system prune -f --volumes || log "Cleanup completed with warnings"

# Build and start containers
log "Building and starting containers..."
docker compose up -d --build --force-recreate

# Wait for services to be healthy
log "Waiting for services to be healthy..."
timeout 300 bash -c 'until docker compose ps | grep -q "healthy"; do sleep 5; done' || {
    log "Health check timeout - checking container status"
    docker compose ps
    docker compose logs app --tail 50
}

# Verify application
log "Verifying application..."
sleep 10
if curl -f http://localhost:8000/api/films > /dev/null 2>&1; then
    log "Application is responding correctly"
else
    log "Application health check failed"
    docker compose logs app --tail 20
    rollback
fi

# Cleanup backup on success
log "Deployment successful - cleaning up backup..."
rm -rf "$BACKUP_DIR"

log "Deployment completed successfully!"