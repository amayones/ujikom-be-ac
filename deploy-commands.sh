#!/bin/bash

# AWS CLI Commands for Quick Setup

echo "=== AWS Cinema Backend Deployment ==="

# 1. Create ECR Repository
echo "Creating ECR repository..."
aws ecr create-repository --repository-name cinema-backend --region ap-southeast-3

# 2. Get ECR login token
echo "Getting ECR login..."
aws ecr get-login-password --region ap-southeast-3 | docker login --username AWS --password-stdin YOUR_ACCOUNT_ID.dkr.ecr.ap-southeast-3.amazonaws.com

# 3. Build and push initial image
echo "Building Docker image..."
docker build -t cinema-backend .
docker tag cinema-backend:latest YOUR_ACCOUNT_ID.dkr.ecr.ap-southeast-3.amazonaws.com/cinema-backend:latest
docker push YOUR_ACCOUNT_ID.dkr.ecr.ap-southeast-3.amazonaws.com/cinema-backend:latest

# 4. Create ECS cluster
echo "Creating ECS cluster..."
aws ecs create-cluster --cluster-name cinema-cluster

# 5. Create CloudWatch log group
echo "Creating log group..."
aws logs create-log-group --log-group-name /ecs/cinema-task

# 6. Register task definition (you need to create task-definition.json first)
echo "Registering task definition..."
aws ecs register-task-definition --cli-input-json file://task-definition.json

# 7. Create ECS service
echo "Creating ECS service..."
aws ecs create-service \
  --cluster cinema-cluster \
  --service-name cinema-service \
  --task-definition cinema-task:1 \
  --desired-count 1 \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[subnet-12345],securityGroups=[sg-12345],assignPublicIp=ENABLED}"

echo "Deployment complete! Check AWS Console for service status."