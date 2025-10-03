# AWS Deployment Setup Guide

## 1. AWS Services Setup (Free Tier)

### A. Create ECR Repository
```bash
aws ecr create-repository --repository-name cinema-backend --region ap-southeast-3
```

### B. Create ECS Cluster
```bash
aws ecs create-cluster --cluster-name cinema-cluster
```

### C. Create RDS MySQL Database (Free Tier)
```bash
aws rds create-db-instance \
  --db-instance-identifier cinema-db \
  --db-instance-class db.t3.micro \
  --engine mysql \
  --master-username admin \
  --master-user-password YourPassword123 \
  --allocated-storage 20 \
  --db-name cinema_db
```

### D. Create Task Definition
```json
{
  "family": "cinema-task",
  "networkMode": "awsvpc",
  "requiresCompatibilities": ["FARGATE"],
  "cpu": "256",
  "memory": "512",
  "executionRoleArn": "arn:aws:iam::YOUR_ACCOUNT:role/ecsTaskExecutionRole",
  "containerDefinitions": [
    {
      "name": "cinema-backend",
      "image": "YOUR_ECR_URI:latest",
      "portMappings": [
        {
          "containerPort": 80,
          "protocol": "tcp"
        }
      ],
      "environment": [
        {"name": "APP_ENV", "value": "production"},
        {"name": "DB_HOST", "value": "YOUR_RDS_ENDPOINT"},
        {"name": "DB_DATABASE", "value": "cinema_db"},
        {"name": "DB_USERNAME", "value": "admin"},
        {"name": "DB_PASSWORD", "value": "YourPassword123"}
      ],
      "logConfiguration": {
        "logDriver": "awslogs",
        "options": {
          "awslogs-group": "/ecs/cinema-task",
          "awslogs-region": "ap-southeast-3",
          "awslogs-stream-prefix": "ecs"
        }
      }
    }
  ]
}
```

### E. Create ECS Service
```bash
aws ecs create-service \
  --cluster cinema-cluster \
  --service-name cinema-service \
  --task-definition cinema-task \
  --desired-count 1 \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[subnet-xxx],securityGroups=[sg-xxx],assignPublicIp=ENABLED}"
```

## 2. GitHub Secrets Setup

Add these secrets to your GitHub repository:
- `AWS_ACCESS_KEY_ID`: Your AWS access key
- `AWS_SECRET_ACCESS_KEY`: Your AWS secret key

## 3. Local Testing

```bash
# Build and test locally
docker-compose up --build

# Test API
curl http://localhost/api/login
```

## 4. Deploy Process

1. Push to main branch
2. GitHub Actions automatically:
   - Builds Docker image
   - Pushes to ECR
   - Updates ECS service
   - Database migrations run automatically

## 5. Database Setup

After first deployment, run migrations:
```bash
aws ecs run-task \
  --cluster cinema-cluster \
  --task-definition cinema-task \
  --overrides '{"containerOverrides":[{"name":"cinema-backend","command":["php","artisan","migrate","--force"]}]}'
```

## 6. Access Your API

Your API will be available at:
`http://YOUR_ECS_PUBLIC_IP/api`

## Cost Estimate (Free Tier)
- ECS Fargate: 1 vCPU, 0.5GB RAM = Free for 1 year
- RDS MySQL: db.t3.micro = Free for 1 year
- ECR: 500MB storage = Free
- Total: $0/month for first year