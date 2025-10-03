# 🛠️ Setup ECS Service (One-time)

## Quick Setup
```bash
# Make script executable
chmod +x create-ecs-service.sh

# Run setup script
./create-ecs-service.sh
```

## Manual Setup (Alternative)

### 1. Create Security Group
```bash
# Get default VPC
VPC_ID=$(aws ec2 describe-vpcs --filters "Name=is-default,Values=true" --query 'Vpcs[0].VpcId' --output text --region ap-southeast-1)

# Create security group
SG_ID=$(aws ec2 create-security-group \
  --group-name cinema-sg \
  --description "Security group for cinema app" \
  --vpc-id $VPC_ID \
  --region ap-southeast-1 \
  --query 'GroupId' --output text)

# Allow HTTP traffic
aws ec2 authorize-security-group-ingress \
  --group-id $SG_ID \
  --protocol tcp \
  --port 80 \
  --cidr 0.0.0.0/0 \
  --region ap-southeast-1
```

### 2. Create CloudWatch Log Group
```bash
aws logs create-log-group --log-group-name /ecs/cinema-backend --region ap-southeast-1
```

### 3. Get Subnet IDs
```bash
aws ec2 describe-subnets --filters "Name=vpc-id,Values=$VPC_ID" --query 'Subnets[].SubnetId' --output text --region ap-southeast-1
```

### 4. Create ECS Service
```bash
aws ecs create-service \
  --cluster cinema-cluster \
  --service-name cinema-service \
  --task-definition cinema-backend \
  --desired-count 1 \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[YOUR_SUBNET_1,YOUR_SUBNET_2],securityGroups=[YOUR_SG_ID],assignPublicIp=ENABLED}" \
  --region ap-southeast-1
```

## ✅ After Setup
Your GitHub Actions will work automatically on next push!