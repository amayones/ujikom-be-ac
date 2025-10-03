# 🚀 Quick Deploy to AWS Singapore

## 1. Setup AWS CLI
```bash
aws configure
# Region: ap-southeast-1
```

## 2. Create Resources (One-time)
```bash
# ECR
aws ecr create-repository --repository-name cinema-backend --region ap-southeast-1

# ECS Cluster
aws ecs create-cluster --cluster-name cinema-cluster --region ap-southeast-1

# RDS
aws rds create-db-instance \
  --db-instance-identifier cinema-db \
  --db-instance-class db.t3.micro \
  --engine mysql \
  --master-username admin \
  --master-user-password YourPassword123 \
  --allocated-storage 20 \
  --db-name cinema_db \
  --region ap-southeast-1
```

## 3. Update task-definition.json
Replace `YOUR_ACCOUNT_ID` with your AWS Account ID

## 4. GitHub Secrets
Add to repository secrets:
- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`

## 5. Deploy
```bash
git add .
git commit -m "Deploy to Singapore"
git push origin main
```

## ✅ Done!
Your API will be available at: `http://YOUR_ECS_IP/api`