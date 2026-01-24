#!/bin/bash

echo "========================================"
echo "Saloon Management System - Setup Script"
echo "========================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "Copying .env.example to .env..."
    cp .env.example .env
    echo ".env file created successfully!"
    echo ""
fi

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo "Failed to install Composer dependencies!"
    exit 1
fi
echo ""

# Install NPM dependencies
echo "Installing NPM dependencies..."
npm install
if [ $? -ne 0 ]; then
    echo "Failed to install NPM dependencies!"
    exit 1
fi
echo ""

# Generate application key
echo "Generating application key..."
php artisan key:generate
echo ""

# Ask for database type
echo "Which database would you like to use?"
echo "1. MySQL"
echo "2. SQLite"
read -p "Enter your choice (1 or 2): " dbchoice

if [ "$dbchoice" = "2" ]; then
    echo "Setting up SQLite..."
    touch database/database.sqlite
    
    # Update .env for SQLite
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
    sed -i '/DB_HOST=/d' .env
    sed -i '/DB_PORT=/d' .env
    sed -i '/DB_DATABASE=/d' .env
    sed -i '/DB_USERNAME=/d' .env
    sed -i '/DB_PASSWORD=/d' .env
    
    echo "SQLite database file created!"
else
    echo "Using MySQL configuration..."
    echo "Please ensure you have created the database 'saloon_management' in MySQL"
    echo ""
    read -p "Press enter to continue..."
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate:fresh --seed
if [ $? -ne 0 ]; then
    echo ""
    echo "========================================"
    echo "Migration failed!"
    echo "========================================"
    echo ""
    echo "Please check:"
    echo "1. Database connection in .env file"
    echo "2. Database server is running"
    echo "3. Database 'saloon_management' exists (for MySQL)"
    echo ""
    exit 1
fi
echo ""

# Build assets
echo "Building frontend assets..."
npm run build
echo ""

echo "========================================"
echo "Setup completed successfully!"
echo "========================================"
echo ""
echo "Default Login Credentials:"
echo ""
echo "Super Admin:"
echo "  Email: admin@saloon.com"
echo "  Password: password"
echo ""
echo "Saloon Admin:"
echo "  Email: saloon@saloon.com"
echo "  Password: password"
echo ""
echo "Customer/User:"
echo "  Email: user@saloon.com"
echo "  Password: password"
echo ""
echo "To start the application, run:"
echo "  php artisan serve"
echo ""
echo "Then visit: http://localhost:8000"
echo ""
