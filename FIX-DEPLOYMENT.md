# 🔧 Fix Deployment Error

## Problem
`ServiceNotFoundException` - ECS Service belum dibuat

## Solution

### 1. Run Setup Script (Recommended)
```bash
# Clone repo locally
git clone YOUR_REPO_URL
cd be-ac

# Make executable
chmod +x create-ecs-service.sh

# Run setup
./create-ecs-service.sh
```

### 2. Manual Fix
```bash
# Get your VPC and Subnet info
aws ec2 describe-vpcs --filters "Name=is-default,Values=true" --region ap-southeast-1
aws ec2 describe-subnets --region ap-southeast-1

# Create security group
aws ec2 create-security-group \
  --group-name cinema-sg \
  --description "Cinema app security group" \
  --vpc-id vpc-YOUR_VPC_ID \
  --region ap-southeast-1

# Create log group
aws logs create-log-group --log-group-name /ecs/cinema-backend --region ap-southeast-1

# Register task definition
aws ecs register-task-definition --cli-input-json file://task-definition.json --region ap-southeast-1

# Create service
aws ecs create-service \
  --cluster cinema-cluster \
  --service-name cinema-service \
  --task-definition cinema-backend \
  --desired-count 1 \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[YOUR_SUBNET_IDS],securityGroups=[YOUR_SG_ID],assignPublicIp=ENABLED}" \
  --region ap-southeast-1
```

### 3. Push Again
```bash
git add .
git commit -m "Fix deployment"
git push origin main
```

## ✅ Next Deploy Will Work!