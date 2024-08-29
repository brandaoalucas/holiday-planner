#!/bin/bash

echo "Copying .env.example to .env..."
cp backend/.env.example backend/.env

export WWWUSER=$(id -u)
export WWWGROUP=$(id -g)

echo "Starting backend setup..."

cd backend

echo "Starting containers with docker-compose..."
docker-compose up -d
sleep 1

echo "Installing dependencies with Composer..."
docker-compose exec laravel.test composer install

echo "Shutting down containers..."
docker-compose down -v

echo "Starting containers with Sail..."
./vendor/bin/sail up -d

echo "Waiting for MySQL service to be ready..."
until ./vendor/bin/sail exec mysql mysqladmin ping -h "mysql" --silent; do
    sleep 5
    echo "Waiting for MySQL..."
done

echo "Running migrations..."
./vendor/bin/sail artisan migrate:fresh
sleep 1

echo "Creating Passport personal access client..."
PASSPORT_OUTPUT=$(./vendor/bin/sail artisan passport:client --personal --name="frontend")
sleep 1

PASSPORT_CLIENT_ID=$(echo "$PASSPORT_OUTPUT" | awk '/Client ID/ {print $NF}')
PASSPORT_CLIENT_SECRET=$(echo "$PASSPORT_OUTPUT" | awk '/Client secret/ {print $NF}')

echo "Client ID: $PASSPORT_CLIENT_ID"
echo "Client Secret: $PASSPORT_CLIENT_SECRET"

ENV_FILE=".env"

if grep -q "PASSPORT_PERSONAL_ACCESS_CLIENT_ID" "$ENV_FILE"; then
  sed -i "s/^PASSPORT_PERSONAL_ACCESS_CLIENT_ID=.*/PASSPORT_PERSONAL_ACCESS_CLIENT_ID=$PASSPORT_CLIENT_ID/" "$ENV_FILE"
else
  echo "PASSPORT_PERSONAL_ACCESS_CLIENT_ID=$PASSPORT_CLIENT_ID" >> "$ENV_FILE"
fi

if grep -q "PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET" "$ENV_FILE"; then
  sed -i "s/^PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=.*/PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=$PASSPORT_CLIENT_SECRET/" "$ENV_FILE"
else
  echo "PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=$PASSPORT_CLIENT_SECRET" >> "$ENV_FILE"
fi
echo "Generating Passport keys..."
./vendor/bin/sail artisan passport:keys

echo "Changing permissions and ownership of the vendor directory..."
sudo chown -R $WWWUSER:$WWWGROUP vendor
sudo chmod -R 755 vendor

echo "Generating encryption keys..."
./vendor/bin/sail artisan key:generate

ALIAS_COMMAND="alias sail='sh \$([ -f sail ] && echo sail || echo vendor/bin/sail)'"

add_alias() {
    local file=$1
    if ! grep -Fxq "$ALIAS_COMMAND" "$file"; then
        echo "Adding 'sail' alias to $file..."
        echo "$ALIAS_COMMAND" >> "$file"
        source "$file"
    else
        echo "'sail' alias is already configured in $file."
    fi
}

if [ -f ~/.bashrc ]; then
    add_alias ~/.bashrc
elif [ -f ~/.bash_profile ]; then
    add_alias ~/.bash_profile
elif [ -f ~/.zshrc ]; then
    add_alias ~/.zshrc
else
    echo "No suitable profile file found for adding the alias."
fi

cd ..

echo "Starting frontend setup..."

cd frontend

if ! command -v nvm &> /dev/null; then
    echo "Installing NVM..."
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash
    source ~/.nvm/nvm.sh
else
    echo "NVM is already installed."
fi

sleep 5
echo "Installing Node.js version 20..."
nvm install 20
nvm use 20

echo "Installing frontend dependencies..."
npm install
sleep 5

echo "Building frontend assets..."
npm run build

export PATH=$PATH:./node_modules/.bin

if ! command -v vite &> /dev/null; then
    echo "Vite is not installed or not found. Installing Vite..."
    npm install vite --save-dev
fi

if command -v vite &> /dev/null; then
    echo "Starting Vite development server..."
    npm run dev
else
    echo "Failed to install Vite. Please check for errors and try again."
fi

# Return to project root directory
cd ..

echo "Setup complete!"
