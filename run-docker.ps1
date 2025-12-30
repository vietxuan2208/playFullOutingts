<#
run-docker.ps1
Auto build & run Docker containers and perform initial setup inside the app container.
Usage (PowerShell):
  ./run-docker.ps1
#>

function ExitWith($msg, $code = 1) {
    Write-Host $msg -ForegroundColor Red
    exit $code
}

Write-Host "Checking Docker availability..."
try {
    docker info > $null 2>&1
} catch {
    ExitWith "Docker does not appear to be running. Please start Docker Desktop and try again."
}

Write-Host "Ensuring .env exists and configured for docker MySQL..."
if (-not (Test-Path .env)) {
    if (Test-Path .env.example) {
        Copy-Item .env.example .env -Force
    } else {
        ExitWith ".env.example not found. Please create an .env based on your environment." 2
    }
}

$envContent = Get-Content .env
$envContent = $envContent -replace '^DB_HOST=.*','DB_HOST=db'
$envContent = $envContent -replace '^DB_DATABASE=.*','DB_DATABASE=doan_db'
$envContent = $envContent -replace '^DB_USERNAME=.*','DB_USERNAME=doan'
$envContent = $envContent -replace '^DB_PASSWORD=.*','DB_PASSWORD=secret'
$envContent | Set-Content .env

Write-Host ".env prepared. Building Docker images (this can take a while)..."
docker-compose build
if ($LASTEXITCODE -ne 0) { ExitWith "docker-compose build failed." }

Write-Host "Starting containers..."
docker-compose up -d
if ($LASTEXITCODE -ne 0) { ExitWith "docker-compose up failed." }

Write-Host "Waiting for containers to be healthy..."
Start-Sleep -Seconds 6

Write-Host "Running initial setup inside app container..."
docker-compose exec app bash -lc "composer install --no-interaction --prefer-dist || true"
docker-compose exec app bash -lc "if [ ! -f .env ]; then cp .env.example .env; fi"
docker-compose exec app bash -lc "php artisan key:generate || true"
docker-compose exec app bash -lc "php artisan migrate --seed || true"
docker-compose exec app bash -lc "php artisan storage:link || true"

Write-Host "Installing frontend dependencies and building assets inside container (may take a while)..."
docker-compose exec app bash -lc "npm install || true"
docker-compose exec app bash -lc "npm run dev || npm run prod || true"

Write-Host "All done. App should be available at http://localhost:8080" -ForegroundColor Green
