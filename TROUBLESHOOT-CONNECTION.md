# 🔧 Troubleshoot Connection Issue

## Problem
`curl: (28) Failed to connect` - ECS task tidak bisa diakses

## Quick Fixes

### 1. Check Security Group
```bash
# Get security group dari ECS task
aws ecs describe-services --cluster cinema-cluster --services cinema-service --region ap-southeast-1

# Add HTTP rule ke security group
aws ec2 authorize-security-group-ingress \
  --group-id sg-YOUR_SG_ID \
  --protocol tcp \
  --port 80 \
  --cidr 0.0.0.0/0 \
  --region ap-southeast-1
```

### 2. Check Task Status
```bash
# Cek apakah task running
aws ecs list-tasks --cluster cinema-cluster --service-name cinema-service --region ap-southeast-1

# Cek task health
aws ecs describe-tasks --cluster cinema-cluster --tasks TASK_ARN --region ap-southeast-1
```

### 3. Check Logs
```bash
# Lihat container logs
aws logs get-log-events \
  --log-group-name /ecs/cinema-backend \
  --log-stream-name ecs/cinema-backend/TASK_ID \
  --region ap-southeast-1
```

### 4. Manual Fix via Console
1. Buka ECS Console
2. Pilih cluster `cinema-cluster`
3. Pilih service `cinema-service`
4. Tab "Tasks" → klik task ID
5. Tab "Networking" → edit security group
6. Add inbound rule: HTTP (80) from 0.0.0.0/0

## ✅ After Fix
Test lagi: `curl http://18.142.48.173`