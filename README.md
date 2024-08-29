
# Holiday Planner

**Holiday Planner** is a RESTful API system developed in Laravel to manage holiday plans for 2024. It features secure authentication with Laravel Passport, PDF generation using DOMPDF, and a well-structured architecture with Controllers, Services, Interfaces, and Repositories. The application is fully containerized using Docker, including both backend and frontend.

## Features

- **Authentication**: Utilizes Laravel Passport for secure API access.
- **PDF Generation**: Generate PDFs summarizing holiday plan details.
- **Architecture**: Follows API Controller, Service, Interface, and Repository patterns.
- **Access Control**: Validates different access levels for users.
- **Containerization**: Managed with Docker, including the frontend setup.

## Requirements

- Docker & Docker Compose
- Node.js (for frontend)
- Composer (for backend dependencies)

## Installation

### For Windows Users

1. **Install WSL2**: Follow [Microsoft's official guide](https://learn.microsoft.com/en-us/windows/wsl/install) to install WSL2.
   
2. **Set up Docker**: Install Docker Desktop for Windows and enable the WSL2 integration.

### Project Setup

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/brandaoalucas/holiday-planner.git
   cd holiday-planner
   ```

2. **Run the Setup Script**: 
   - For an automated setup, run the provided Bash script:
     ```bash
     ./setup.sh
     ```
   - This script will:
     - Copy the `.env.example` file to `.env`.
     - Start Docker containers.
     - Install backend dependencies with Composer.
     - Run migrations and set up Passport.
     - Configure the frontend environment, install dependencies, and start the development server.

### Manual Setup Instructions (if issues arise)

#### Backend Setup

1. **Copy .env File**:
   ```bash
   cp backend/.env.example backend/.env
   ```

2. **Start Docker Containers**:
   ```bash
   cd backend
   docker-compose up -d
   ```

3. **Install Backend Dependencies**:
   ```bash
   docker-compose exec laravel.test composer install
   ```

4. **Stop Containers and Remove Volumes**:
   ```bash
   docker-compose down -v
   ```

5. **Start Containers with Sail**:
   ```bash
   ./vendor/bin/sail up -d
   ```

6. **Run Migrations**:
   ```bash
   ./vendor/bin/sail artisan migrate:fresh
   ```

7. **Set Up Passport**:
   If you encounter encryption issues, run the following commands after migrations:
   ```bash
   ./vendor/bin/sail artisan passport:client --personal
   ./vendor/bin/sail artisan passport:keys
   ```

8. **Update .env File**:
   Add the following variables to your `.env` file, replacing with your actual credentials:
   ```env
   PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
   PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=doozx0ZuzpOcli87jbsxZCOR3Tqw0OEPAo7u6eJ0
   ```

#### Frontend Setup

1. **Navigate to the Frontend Directory**:
   ```bash
   cd frontend
   ```

2. **Install Node.js and NVM**:
   - If NVM is not installed, run:
     ```bash
     curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash
     source ~/.nvm/nvm.sh
     ```
   - Install Node.js:
     ```bash
     nvm install 20
     nvm use 20
     ```

3. **Install Frontend Dependencies**:
   ```bash
   npm install
   ```

4. **Build Frontend Assets**:
   ```bash
   npm run build
   ```

5. **Start the Vite Development Server**:
   ```bash
   npm run dev
   ```

## Usage

- Access the API endpoints using the base URL configured in your `.env` file.
- Use the authentication endpoints to obtain tokens via Passport for secure access.

## Testing

- **Unit Tests**: Run tests using PHPUnit:
  ```bash
  ./vendor/bin/sail artisan test
  ```

## API Documentation

- Detailed API documentation is available, including request methods, endpoints, parameters, and example responses.

## Contributions

Contributions are welcome! Please fork the repository, make your changes, and submit a pull request.

## License

This project is licensed under the MIT License.
