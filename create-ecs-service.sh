#!/bin/bash

# Get default VPC and subnets
VPC_ID=$(aws ec2 describe-vpcs --filters "Name=is-default,Values=true" --query 'Vpcs[0].VpcId' --output text --region ap-southeast-1)
SUBNET_IDS=$(aws ec2 describe-subnets --filters "Name=vpc-id,Values=$VPC_ID" --query 'Subnets[0:2].SubnetId' --output text --region ap-southeast-1)
SUBNET_1=$(echo $SUBNET_IDS | cut -d' ' -f1)
SUBNET_2=$(echo $SUBNET_IDS | cut -d' ' -f2)

# Create security group
SG_ID=$(aws ec2 create-security-group \
  --group-name cinema-sg \
  --description "Security group for cinema app" \
  --vpc-id $VPC_ID \
  --region ap-southeast-1 \
  --query 'GroupId' --output text)

# Add inbound rule for HTTP
aws ec2 authorize-security-group-ingress \
  --group-id $SG_ID \
  --protocol tcp \
  --port 80 \
  --cidr 0.0.0.0/0 \
  --region ap-southeast-1

# Create CloudWatch log group
aws logs create-log-group --log-group-name /ecs/cinema-backend --region ap-southeast-1

# Register task definition
aws ecs register-task-definition --cli-input-json file://task-definition.json --region ap-southeast-1

# Create ECS service
aws ecs create-service \
  --cluster cinema-cluster \
  --service-name cinema-service \
  --task-definition cinema-backend \
  --desired-count 1 \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[$SUBNET_1,$SUBNET_2],securityGroups=[$SG_ID],assignPublicIp=ENABLED}" \
  --region ap-southeast-1

echo "ECS Service created successfully!"
echo "Security Group ID: $SG_ID"
echo "Subnets: $SUBNET_1, $SUBNET_2"