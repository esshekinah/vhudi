#!/bin/bash

# Google Cloud Run Deployment Script
# Usage: ./deploy-to-cloudrun.sh [PROJECT_ID] [SERVICE_NAME] [REGION]

set -e

# Configuration
PROJECT_ID="${1:-}"
SERVICE_NAME="${2:-vhudi}"
REGION="${3:-us-central1}"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Validation
if [ -z "$PROJECT_ID" ]; then
    echo -e "${RED}Error: PROJECT_ID is required${NC}"
    echo "Usage: $0 <PROJECT_ID> [SERVICE_NAME] [REGION]"
    echo "Example: $0 my-project vhudi us-central1"
    exit 1
fi

echo -e "${YELLOW}=== Google Cloud Run Deployment ===${NC}"
echo "Project ID: $PROJECT_ID"
echo "Service Name: $SERVICE_NAME"
echo "Region: $REGION"

# Set project
echo -e "${YELLOW}Setting GCP project...${NC}"
gcloud config set project $PROJECT_ID

# Enable required APIs
echo -e "${YELLOW}Enabling required APIs...${NC}"
gcloud services enable run.googleapis.com cloudbuild.googleapis.com containerregistry.googleapis.com

# Build the image
IMAGE_URL="gcr.io/${PROJECT_ID}/${SERVICE_NAME}:latest"
echo -e "${YELLOW}Building Docker image: ${IMAGE_URL}${NC}"
docker build -f Dockerfile.cloudrun -t $IMAGE_URL .

# Push to Container Registry
echo -e "${YELLOW}Pushing image to Container Registry...${NC}"
docker push $IMAGE_URL

# Deploy to Cloud Run
echo -e "${YELLOW}Deploying to Cloud Run...${NC}"
gcloud run deploy $SERVICE_NAME \
    --image $IMAGE_URL \
    --platform managed \
    --region $REGION \
    --memory 1Gi \
    --cpu 1 \
    --timeout 3600 \
    --allow-unauthenticated

echo -e "${GREEN}Deployment complete!${NC}"

# Get the service URL
SERVICE_URL=$(gcloud run services describe $SERVICE_NAME --region $REGION --format 'value(status.url)')
echo -e "${GREEN}Service URL: ${SERVICE_URL}${NC}"

# Display next steps
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "1. Set environment variables:"
echo "   gcloud run services update $SERVICE_NAME --region $REGION --update-env-vars DB_HOST=your-db,DB_USERNAME=user,DB_PASSWORD=pass"
echo ""
echo "2. View logs:"
echo "   gcloud run services describe $SERVICE_NAME --region $REGION"
echo "   gcloud logging read \"resource.type=cloud_run_revision AND resource.labels.service_name=$SERVICE_NAME\" --limit 50"
echo ""
echo "3. For more configuration, see CLOUDRUN_DEPLOYMENT.md"
