# 🇸🇬 AWS Singapore Deployment Guide

## Region: ap-southeast-1 (Singapore)

### **1. AWS CLI Setup**
```bash
aws configure
# Region: ap-southeast-1
```

### **2. Create AWS Resources**
```bash
# ECR Repository
aws ecr create-repository --repository-name cinema-backend --region ap-southeast-1

# ECS Cluster  
aws ecs create-cluster --cluster-name cinema-cluster --region ap-southeast-1

# RDS MySQL (Free Tier)
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

### **3. Update Files**
**task-definition.json:**
- ECR URL: `YOUR_ACCOUNT_ID.dkr.ecr.ap-southeast-1.amazonaws.com/cinema-backend:latest`
- Log region: `ap-southeast-1`

**GitHub Secrets:**
- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`

### **4. Deploy**
```bash
git push origin main
```

### **5. Your API URL**
`http://YOUR_ECS_IP/api`

## ✅ Keuntungan Singapore Region:
- Latency rendah untuk Asia Tenggara
- Infrastruktur AWS terlengkap di region
- Performa stabil dan reliable