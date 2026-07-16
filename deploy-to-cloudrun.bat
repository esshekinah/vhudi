@echo off
REM Google Cloud Run Deployment Script for Windows
REM Usage: deploy-to-cloudrun.bat <PROJECT_ID> [SERVICE_NAME] [REGION]

setlocal enabledelayedexpansion

REM Configuration
set "PROJECT_ID=%1"
set "SERVICE_NAME=%2"
set "REGION=%3"

if not defined SERVICE_NAME set "SERVICE_NAME=vhudi"
if not defined REGION set "REGION=us-central1"

REM Validation
if not defined PROJECT_ID (
    echo Error: PROJECT_ID is required
    echo Usage: %0 ^<PROJECT_ID^> [SERVICE_NAME] [REGION]
    echo Example: %0 my-project vhudi us-central1
    exit /b 1
)

echo.
echo === Google Cloud Run Deployment ===
echo Project ID: %PROJECT_ID%
echo Service Name: %SERVICE_NAME%
echo Region: %REGION%
echo.

REM Set project
echo Setting GCP project...
call gcloud config set project %PROJECT_ID%
if errorlevel 1 goto :error

REM Enable required APIs
echo Enabling required APIs...
call gcloud services enable run.googleapis.com cloudbuild.googleapis.com containerregistry.googleapis.com
if errorlevel 1 goto :error

REM Build the image
set "IMAGE_URL=gcr.io/%PROJECT_ID%/%SERVICE_NAME%:latest"
echo Building Docker image: %IMAGE_URL%
call docker build -f Dockerfile.cloudrun -t %IMAGE_URL% .
if errorlevel 1 goto :error

REM Push to Container Registry
echo Pushing image to Container Registry...
call docker push %IMAGE_URL%
if errorlevel 1 goto :error

REM Deploy to Cloud Run
echo Deploying to Cloud Run...
call gcloud run deploy %SERVICE_NAME% ^
    --image %IMAGE_URL% ^
    --platform managed ^
    --region %REGION% ^
    --memory 1Gi ^
    --cpu 1 ^
    --timeout 3600 ^
    --allow-unauthenticated
if errorlevel 1 goto :error

echo.
echo Deployment complete!

REM Get service URL
for /f "delims=" %%i in ('gcloud run services describe %SERVICE_NAME% --region %REGION% --format "value(status.url)"') do set "SERVICE_URL=%%i"
echo Service URL: %SERVICE_URL%

echo.
echo Next steps:
echo 1. Set environment variables:
echo    gcloud run services update %SERVICE_NAME% --region %REGION% --update-env-vars DB_HOST=your-db,DB_USERNAME=user,DB_PASSWORD=pass
echo.
echo 2. View logs:
echo    gcloud run services describe %SERVICE_NAME% --region %REGION%
echo.
echo 3. For more configuration, see CLOUDRUN_DEPLOYMENT.md
echo.
exit /b 0

:error
echo.
echo Error occurred during deployment!
exit /b 1
