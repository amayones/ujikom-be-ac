# 🛠️ Manual Create ECS Service

## 1. Get Your Network Info
```bash
# Get VPC ID
aws ec2 describe-vpcs --filters "Name=is-default,Values=true" --query 'Vpcs[0].VpcId' --output text --region ap-southeast-1

# Get Subnet IDs  
aws ec2 describe-subnets --query 'Subnets[0:2].SubnetId' --output text --region ap-southeast-1
```

## 2. Create Security Group
```bash
# Replace VPC_ID with your VPC
aws ec2 create-security-group \
  --group-name cinema-sg \
  --description "Cinema app" \
  --vpc-id vpc-xxxxxxxxx \
  --region ap-southeast-1

# Allow HTTP (replace SG_ID)
aws ec2 authorize-security-group-ingress \
  --group-id sg-xxxxxxxxx \
  --protocol tcp \
  --port 80 \
  --cidr 0.0.0.0/0 \
  --region ap-southeast-1
```

## 3. Create Log Group
```bash
aws logs create-log-group --log-group-name /ecs/cinema-backend --region ap-southeast-1
```

## 4. Create ECS Service
```bash
# Replace SUBNET_1, SUBNET_2, SG_ID with your values
aws ecs create-service \
  --cluster cinema-cluster \
  --service-name cinema-service \
  --task-definition cinema-backend \
  --desired-count 1 \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[subnet-xxxxxxxxx,subnet-yyyyyyyyy],securityGroups=[sg-xxxxxxxxx],assignPublicIp=ENABLED}" \
  --region ap-southeast-1
```

## 5. Update Workflow
After service created, update deploy.yml:
```yaml
- name: Update ECS Service
  run: |
    aws ecs update-service --cluster cinema-cluster --service cinema-service --force-new-deployment
```

## ✅ Done!
Next push will work automatically.