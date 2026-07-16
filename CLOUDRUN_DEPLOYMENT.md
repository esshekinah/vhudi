# Google Cloud Run Deployment Guide

## Overview
This project has been configured for deployment to Google Cloud Run. Cloud Run is a serverless platform that automatically scales your containerized application based on demand.

## Key Changes for Cloud Run Compatibility

### Files Created
1. **Dockerfile.cloudrun** - Single container with both Nginx and PHP-FPM using Supervisor
2. **docker-compose/nginx/default.conf.cloudrun** - Nginx config listening on port 8080
3. **supervisor.conf** - Manages both Nginx and PHP-FPM processes
4. **cloudbuild.yaml** - Automated CI/CD pipeline for Cloud Run
5. **.gcloudignore** - Files to exclude from deployment

### Important Changes from docker-compose setup
- **Single container**: Combines Nginx and PHP-FPM (originally separate services)
- **Port 8080**: Cloud Run requires applications to listen on PORT environment variable (defaults to 8080)
- **Stdout/Stderr logging**: Logs are sent to stdout/stderr for Cloud Run's logging system
- **Stateless design**: No local file volumes; use Cloud Storage or managed databases

## Prerequisites

1. **Google Cloud Project**
   ```bash
   gcloud config set project YOUR_PROJECT_ID
   ```

2. **Enable required APIs**
   ```bash
   gcloud services enable run.googleapis.com cloudbuild.googleapis.com containerregistry.googleapis.com
   ```

3. **Set up environment variables** for your database and other services

4. **Authenticate with Google Cloud**
   ```bash
   gcloud auth login
   gcloud config set project PROJECT_ID
   ```

## Deployment Options

### Option 1: Using Cloud Build (Recommended - Automated)

1. **Configure substitution variables** in `cloudbuild.yaml`:
   ```bash
   # Update these in cloudbuild.yaml:
   _SERVICE_NAME: 'vhudi'
   _REGION: 'us-central1'  # Change if needed
   _DB_HOST: 'your-cloud-sql-ip'
   _DB_USERNAME: 'your-db-user'
   _DB_PASSWORD: 'your-db-password'
   ```

2. **Trigger build and deploy**:
   ```bash
   gcloud builds submit --config cloudbuild.yaml
   ```

3. **Monitor the deployment**:
   ```bash
   gcloud builds log --stream
   ```

### Option 2: Manual Build and Deploy

1. **Build the Docker image**:
   ```bash
   docker build -f Dockerfile.cloudrun -t gcr.io/YOUR_PROJECT_ID/vhudi:latest .
   ```

2. **Push to Container Registry**:
   ```bash
   docker push gcr.io/YOUR_PROJECT_ID/vhudi:latest
   ```

3. **Deploy to Cloud Run**:
   ```bash
   gcloud run deploy vhudi \
     --image gcr.io/YOUR_PROJECT_ID/vhudi:latest \
     --platform managed \
     --region us-central1 \
     --memory 1Gi \
     --cpu 1 \
     --allow-unauthenticated \
     --set-env-vars DB_HOST=your-db-host,DB_USERNAME=user,DB_PASSWORD=pass,DB_DATABASE=vhudi
   ```

### Option 3: Deploy from source (Build on Cloud Run)

```bash
gcloud run deploy vhudi \
  --source . \
  --platform managed \
  --region us-central1 \
  --allow-unauthenticated
```

## Database Configuration

### Using Cloud SQL
For production, use Google Cloud SQL:

1. **Create a Cloud SQL instance**:
   ```bash
   gcloud sql instances create vhudi-db \
     --database-version=MYSQL_5_7 \
     --tier=db-f1-micro \
     --region=us-central1
   ```

2. **Create a database**:
   ```bash
   gcloud sql databases create vhudi --instance=vhudi-db
   ```

3. **Create a user**:
   ```bash
   gcloud sql users create dbuser --instance=vhudi-db --password=YOUR_PASSWORD
   ```

4. **Connect Cloud Run to Cloud SQL** - Use Cloud SQL Auth proxy or setup public IP

### Using External Database
If using external database:
1. Ensure the database is accessible from Cloud Run
2. Update environment variables with correct host, port, username, password
3. Consider using Secret Manager for sensitive data

## Environment Variables

Set these via Cloud Run console or deployment command:

```bash
APP_NAME=VHUDI
ENVIRONMENT=production
DB_CONNECTION=mysql
DB_HOST=<your-database-host>
DB_PORT=3306
DB_DATABASE=vhudi
DB_USERNAME=<your-db-user>
DB_PASSWORD=<your-db-password>
USER_LOGO=<your-logo-url>
CLIENT_LOGO=<your-logo-url>
SYSTEM_LOGO=<your-logo-url>
```

**For sensitive data, use Google Secret Manager**:
```bash
# Create a secret
echo -n "your-password" | gcloud secrets create db-password --data-file=-

# Grant access to Cloud Run service account
gcloud secrets add-iam-policy-binding db-password \
  --member=serviceAccount:vhudi@PROJECT_ID.iam.gserviceaccount.com \
  --role=roles/secretmanager.secretAccessor
```

## File Storage

Cloud Run containers are ephemeral. For persistent file storage:

### Using Cloud Storage (Recommended)
1. **Create a Cloud Storage bucket**:
   ```bash
   gsutil mb gs://vhudi-files
   ```

2. **Install Google Cloud Storage library** (add to composer.json):
   ```json
   {
     "require": {
       "google/cloud-storage": "^1.26"
     }
   }
   ```

3. **Update your PHP application** to use Cloud Storage instead of local file system

### Using persistent disks
Not available on Cloud Run - use Cloud Storage instead.

## Scaling and Performance

### Memory and CPU
- Default: 512MB memory, 1 CPU
- Adjust in deployment command:
  ```bash
  --memory 2Gi --cpu 2
  ```

### Concurrency
```bash
--concurrency 80  # Max requests per container instance
```

### Startup timeout
```bash
--timeout 3600  # seconds
```

## Monitoring and Logging

### View logs
```bash
gcloud run services describe vhudi --region us-central1
gcloud logging read "resource.type=cloud_run_revision AND resource.labels.service_name=vhudi" --limit 50
```

### Set up monitoring alerts
- Use Google Cloud Console
- Create alerts for error rates, latency, etc.

## Troubleshooting

### Container fails to start
```bash
# Check logs
gcloud run services describe vhudi --region us-central1
gcloud logging read "resource.type=cloud_run_revision AND resource.labels.service_name=vhudi" --limit 100
```

### Database connection issues
- Verify Cloud SQL instance is running
- Check network connectivity and firewall rules
- Verify credentials are correct
- Use Cloud SQL Auth proxy for secure connections

### High memory usage
- Check PHP-FPM settings
- Reduce max pool size
- Optimize database queries

## Clean Up

To delete the Cloud Run service:
```bash
gcloud run services delete vhudi --region us-central1
```

## Additional Resources
- [Cloud Run Documentation](https://cloud.google.com/run/docs)
- [Cloud Run Best Practices](https://cloud.google.com/run/docs/quickstarts/build-and-deploy)
- [Cloud SQL Proxy](https://cloud.google.com/sql/docs/mysql/sql-proxy)
- [Cloud Storage Client Library (PHP)](https://cloud.google.com/php/docs/reference/cloud-storage/latest)
