# 🔧 Fix 500 Server Error

## Problem
HTTP 500 - Laravel application error

## Quick Fixes

### 1. Check Container Logs
```bash
# Get task ARN
aws ecs list-tasks --cluster cinema-cluster --service-name cinema-service --region ap-southeast-1

# Check logs (replace TASK_ID)
aws logs get-log-events \
  --log-group-name /ecs/cinema-backend \
  --log-stream-name ecs/cinema-backend/TASK_ID \
  --region ap-southeast-1
```

### 2. Missing APP_KEY
Add to task-definition.json:
```json
{
  "name": "APP_KEY",
  "value": "base64:YOUR_32_CHAR_KEY"
}
```

Generate key:
```bash
php artisan key:generate --show
```

### 3. Database Connection
Test RDS connection:
```bash
mysql -h cinema-database.cl6me2ouqtxn.ap-southeast-1.rds.amazonaws.com -u admin -p
```

### 4. Run Migrations
```bash
# Connect to running container
aws ecs execute-command \
  --cluster cinema-cluster \
  --task TASK_ARN \
  --container cinema-backend \
  --interactive \
  --command "/bin/bash"

# Inside container
php artisan migrate --force
php artisan db:seed --force
```

### 5. Fix Permissions
Add to Dockerfile:
```dockerfile
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```

## ✅ After Fix
Redeploy: `git push origin main`