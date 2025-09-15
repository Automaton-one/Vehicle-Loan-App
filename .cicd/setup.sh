#!/bin/bash
set -e

echo "Vehicle Loan App Setup"

# Copy .env.example to .env if not already present
if [ ! -f ".env" ]; then
  echo "Copying .env.example to .env"
  cp .env.example .env
else
  echo ".env already exists, skipping copy."
fi

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate --seed

echo "Setup complete. Run 'docker-compose up --build' to start the app at localhost:8080"
