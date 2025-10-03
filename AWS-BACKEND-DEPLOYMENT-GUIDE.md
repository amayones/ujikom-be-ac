# 🚀 AWS Backend Deployment Guide - Step by Step

## Region: ap-southeast-1 (Singapore)

---

## **STEP 1: AWS Account Setup**

### 1.1 Create AWS Account
1. Go to https://aws.amazon.com
2. Click "Create an AWS Account"
3. Fill in email, password, account name
4. Add payment method (won't be charged for Free Tier)
5. Verify phone number
6. Choose "Basic Support - Free"

### 1.2 Login to AWS Console
1. Go to https://console.aws.amazon.com
2. Login with your credentials
3. **Important:** Select region **ap-southeast-1 (Singapore)** at top right

---

## **STEP 2: Create IAM User (Security)**

### 2.1 Go to IAM Service
1. AWS Console → Search "IAM" → Click IAM
2. Left sidebar → Users → Create user

### 2.2 Create User
1. **User name:** `cinema-deployer`
2. **Access type:** Programmatic access
3. Click "Next"

### 2.3 Set Permissions
1. **Attach policies directly**
2. Search and select these policies:
   - `AmazonECS_FullAccess`
   - `AmazonEC2ContainerRegistryFullAccess`
   - `AmazonRDSFullAccess`
   - `CloudWatchLogsFullAccess`
   - `IAMReadOnlyAccess`
3. Click "Next" → "Create user"

### 2.4 Download Credentials
1. **IMPORTANT:** Download the CSV file
2. Save `Access Key ID` and `Secret Access Key`
3. You'll need these for GitHub

---

## **STEP 3: Create ECR Repository (Docker Registry)**

### 3.1 Go to ECR Service
1. AWS Console → Search "ECR" → Click Elastic Container Registry
2. Make sure region is **ap-southeast-1**

### 3.2 Create Repository
1. Click "Create repository"
2. **Repository name:** `cinema-backend`
3. **Visibility:** Private
4. Click "Create repository"

### 3.3 Copy Repository URI
1. Click on `cinema-backend` repository
2. Copy the URI: `123456789012.dkr.ecr.ap-southeast-1.amazonaws.com/cinema-backend`
3. **Save this URI** - you'll need it later

---

## **STEP 4: Create RDS Database**

### 4.1 Go to RDS Service
1. AWS Console → Search "RDS" → Click RDS
2. Make sure region is **ap-southeast-1**

### 4.2 Create Database
1. Click "Create database"
2. **Engine type:** MySQL
3. **Templates:** Free tier
4. **DB instance identifier:** `cinema-database`

### 4.3 Database Settings
1. **Master username:** `admin`
2. **Master password:** `CinemaPass123!`
3. **Confirm password:** `CinemaPass123!`

### 4.4 Instance Configuration
1. **DB instance class:** db.t3.micro (Free tier)
2. **Storage type:** General Purpose SSD
3. **Allocated storage:** 20 GB

### 4.5 Connectivity
1. **Public access:** Yes
2. **VPC security group:** Create new
3. **Security group name:** `cinema-db-sg`
4. **Database port:** 3306

### 4.6 Additional Configuration
1. **Initial database name:** `cinema_db`
2. **Backup retention:** 7 days
3. **Monitoring:** Enable Enhanced monitoring
4. Click "Create database"

### 4.7 Wait and Get Endpoint
1. **Wait 5-10 minutes** for database creation
2. Click on `cinema-database`
3. **Copy Endpoint:** `cinema-database.xxxxx.ap-southeast-1.rds.amazonaws.com`
4. **Save this endpoint** - you'll need it

---

## **STEP 5: Create ECS Cluster**

### 5.1 Go to ECS Service
1. AWS Console → Search "ECS" → Click Elastic Container Service
2. Make sure region is **ap-southeast-1**

### 5.2 Create Cluster
1. Click "Create cluster"
2. **Cluster name:** `cinema-cluster`
3. **Infrastructure:** AWS Fargate (serverless)
4. Click "Create"

---

## **STEP 6: Create CloudWatch Log Group**

### 6.1 Go to CloudWatch
1. AWS Console → Search "CloudWatch" → Click CloudWatch
2. Left sidebar → Logs → Log groups

### 6.2 Create Log Group
1. Click "Create log group"
2. **Log group name:** `/ecs/cinema-task`
3. **Retention:** 7 days
4. Click "Create"

---

## **STEP 7: Update Your Project Files**

### 7.1 Update task-definition.json
Replace these values in your `task-definition.json`:

```json
{
  "family": "cinema-task",
  "networkMode": "awsvpc",
  "requiresCompatibilities": ["FARGATE"],
  "cpu": "256",
  "memory": "512",
  "executionRoleArn": "arn:aws:iam::YOUR_ACCOUNT_ID:role/ecsTaskExecutionRole",
  "containerDefinitions": [
    {
      "name": "cinema-backend",
      "image": "YOUR_ACCOUNT_ID.dkr.ecr.ap-southeast-1.amazonaws.com/cinema-backend:latest",
      "portMappings": [{"containerPort": 80}],
      "environment": [
        {"name": "APP_ENV", "value": "production"},
        {"name": "APP_DEBUG", "value": "false"},
        {"name": "DB_CONNECTION", "value": "mysql"},
        {"name": "DB_HOST", "value": "YOUR_RDS_ENDPOINT"},
        {"name": "DB_PORT", "value": "3306"},
        {"name": "DB_DATABASE", "value": "cinema_db"},
        {"name": "DB_USERNAME", "value": "admin"},
        {"name": "DB_PASSWORD", "value": "CinemaPass123!"}
      ],
      "logConfiguration": {
        "logDriver": "awslogs",
        "options": {
          "awslogs-group": "/ecs/cinema-task",
          "awslogs-region": "ap-southeast-1",
          "awslogs-stream-prefix": "ecs"
        }
      }
    }
  ]
}
```

**Replace:**
- `YOUR_ACCOUNT_ID` → Your 12-digit AWS Account ID (top right corner)
- `YOUR_RDS_ENDPOINT` → RDS endpoint from Step 4.7

---

## **STEP 8: Setup GitHub Secrets**

### 8.1 Go to GitHub Repository
1. Go to your GitHub repository
2. Click "Settings" tab
3. Left sidebar → "Secrets and variables" → "Actions"

### 8.2 Add Secrets
Click "New repository secret" and add:

1. **Name:** `AWS_ACCESS_KEY_ID`
   **Value:** (from IAM CSV file)

2. **Name:** `AWS_SECRET_ACCESS_KEY`
   **Value:** (from IAM CSV file)

---

## **STEP 9: First Deployment**

### 9.1 Push to GitHub
```bash
git add .
git commit -m "AWS deployment setup"
git push origin main
```

### 9.2 Check GitHub Actions
1. Go to GitHub → Actions tab
2. Watch the deployment process
3. Wait for green checkmark ✅

---

## **STEP 10: Create ECS Task Definition**

### 10.1 Go to ECS Console
1. AWS Console → ECS → Task definitions
2. Click "Create new task definition"

### 10.2 Upload Task Definition
1. Click "Create new task definition" → "Create from JSON"
2. Copy-paste your updated `task-definition.json`
3. Click "Create"

---

## **STEP 11: Create ECS Service**

### 11.1 Go to Cluster
1. ECS → Clusters → cinema-cluster
2. Click "Services" tab → "Create"

### 11.2 Service Configuration
1. **Launch type:** Fargate
2. **Task definition:** cinema-task
3. **Service name:** `cinema-service`
4. **Number of tasks:** 1

### 11.3 Network Configuration
1. **VPC:** Default VPC
2. **Subnets:** Select all available
3. **Security groups:** Create new
   - **Name:** `cinema-service-sg`
   - **Inbound rules:** HTTP (80) from Anywhere
4. **Auto-assign public IP:** ENABLED

### 11.4 Create Service
1. Click "Create"
2. Wait 3-5 minutes for service to start

---

## **STEP 12: Get Your API URL**

### 12.1 Find Public IP
1. ECS → Clusters → cinema-cluster
2. Services → cinema-service
3. Tasks tab → Click on running task
4. **Copy Public IP:** e.g., `54.251.123.45`

### 12.2 Test Your API
```bash
# Test health check
curl http://54.251.123.45/

# Test API endpoint
curl http://54.251.123.45/api/login
```

---

## **STEP 13: Run Database Migration**

### 13.1 Run Migration Task
```bash
aws ecs run-task \
  --cluster cinema-cluster \
  --task-definition cinema-task \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[subnet-xxx],securityGroups=[sg-xxx],assignPublicIp=ENABLED}" \
  --overrides '{"containerOverrides":[{"name":"cinema-backend","command":["php","artisan","migrate","--force"]}]}'
```

### 13.2 Alternative: Manual Migration
1. Connect to RDS using MySQL client
2. Run Laravel migrations manually

---

## **🎉 DEPLOYMENT COMPLETE!**

### Your API is now live at:
`http://YOUR_PUBLIC_IP/api`

### Auto-deployment is active:
- Push to `main` branch → Automatic deployment
- GitHub Actions handles everything

### Next Steps:
1. Update frontend API URL to your backend
2. Test all endpoints
3. Setup custom domain (optional)
4. Setup SSL certificate (optional)

---

## **💰 Cost Estimate (Free Tier)**
- **ECS Fargate:** Free for 1 year
- **RDS MySQL:** Free for 1 year  
- **ECR:** 500MB free storage
- **CloudWatch:** Basic monitoring free
- **Total:** $0/month for first year

---

## **🔧 Troubleshooting**

### Service won't start:
1. Check CloudWatch logs: `/ecs/cinema-task`
2. Verify RDS security group allows port 3306
3. Check task definition environment variables

### Can't connect to database:
1. RDS → Security groups → Edit inbound rules
2. Add rule: MySQL/Aurora (3306) from ECS security group

### GitHub Actions failing:
1. Check AWS credentials in GitHub secrets
2. Verify IAM permissions
3. Check ECR repository exists

---

**Follow these steps exactly and your Laravel backend will be running on AWS!**