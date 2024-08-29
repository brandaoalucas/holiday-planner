#!/bin/bash

# Set environment variables for backend
export WWWUSER=$(id -u)
export WWWGROUP=$(id -g)

# Backend setup
echo "Starting backend setup..."

cd backend

echo "Starting containers with docker-compose..."
docker-compose up -d

echo "Installing dependencies with Composer..."
docker-compose exec laravel.test composer install

echo "Shutting down containers..."
docker-compose down -v

echo "Starting containers with Sail..."
./vendor/bin/sail up -d

cd ..

# Frontend setup
echo "Starting frontend setup..."

cd frontend

# Install Node.js using NVM
if ! command -v nvm &> /dev/null; then
    echo "Installing NVM..."
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash
    source ~/.nvm/nvm.sh
else
    echo "NVM is already installed."
fi

echo "Installing Node.js version 20..."
nvm install 20

echo "Checking installed versions..."
node_version=$(node -v)
npm_version=$(npm -v)

echo "Installed Node.js version: $node_version"
echo "Installed npm version: $npm_version"

# Return to project root directory
cd ..

echo "Setup complete!"
