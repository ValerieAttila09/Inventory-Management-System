#!/usr/bin/env bash
set -euo pipefail

# Simple bootstrap for the Storage Dashboard project
# Usage: ./scripts/setup.sh [--no-docker] [--dev]

ROOT_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)
cd "$ROOT_DIR"

NO_DOCKER=false
DEV=false

for arg in "$@"; do
  case "$arg" in
    --no-docker) NO_DOCKER=true ;; 
    --dev) DEV=true ;; 
    *) echo "Unknown arg: $arg"; exit 1 ;;
  esac
done

echo "-> Ensuring .env exists"
if [ ! -f .env ]; then
  cp .env.example .env
  echo "Created .env from .env.example; please review and edit if necessary."
else
  echo ".env exists; skipping creation"
fi

if [ "$NO_DOCKER" = false ]; then
  echo "-> Building Docker images"
  docker-compose build --no-cache

  echo "-> Starting DB container"
  docker-compose up -d db

  echo "-> Waiting for DB to become ready (timeout 60s)"
  SECONDS=0
  until docker-compose run --rm --no-deps app php -r "try{new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'ok'; } catch (Exception \$e) { /* ignore */ }" | grep -q ok; do
    sleep 1
    SECONDS=$((SECONDS+1))
    if [ "$SECONDS" -gt 60 ]; then
      echo "Timed out waiting for DB"
      exit 1
    fi
  done
fi

if [ "$NO_DOCKER" = false ]; then
  echo "-> Installing PHP dependencies inside container (composer update)"
  docker-compose run --rm app composer update --no-interaction || true
else
  if command -v composer >/dev/null 2>&1; then
    echo "-> Installing PHP dependencies locally"
    composer install --no-interaction
  else
    echo "Composer not found locally; run composer install or re-run without --no-docker"
    exit 1
  fi
fi

echo "-> Installing Node dependencies and building Tailwind CSS"
if [ -f package.json ]; then
  npm install
  if [ "$DEV" = true ]; then
    npm run dev &
  else
    npm run build
  fi
fi

if [ "$NO_DOCKER" = false ]; then
  echo "-> Running DB migrations"
  docker-compose run --rm app composer migrate

  echo "-> Running DB seeds (admin user)"
  docker-compose run --rm app composer seed
fi

echo "-> Set writable permissions for storage folders"
mkdir -p var/cache var/log
chown -R $USER:www-data var || true

cat <<EOF

Setup finished!
 - Admin user: admin@example.com / admin123 (change password after first login)
 - If you started Tailwind with --dev, the dev watcher runs in background
 - To run the app locally: php -S localhost:8000 -t public (or use Docker: docker-compose up -d app)

EOF
