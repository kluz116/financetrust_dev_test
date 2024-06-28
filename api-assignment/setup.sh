#!/bin/bash

# Clone the repository (if not already cloned)//Can Uncomment these if you want be automatically done at setup
# echo "Cloning the repository..."
# git clone https://github.com/kidepo/financetrust_dev_test.git
# cd financetrust_dev_test/api-assignment
# git checkout kiyingi_denis_ftb_dev

# Navigate to the api-assignment directory
cd "$(dirname "$0")"

# Copy the example environment file
echo "Creating .env file..."
cp .env.example .env

# Build and start Docker containers
echo "Building and starting Docker containers..."
docker-compose up -d --build

# Install PHP dependencies
echo "Installing PHP dependencies..."
docker-compose exec app composer install

# Dump Composer autoload
echo "Dumping Composer autoload..."
docker-compose exec app composer dump-autoload

# Generate application key
echo "Generating application key..."
docker-compose exec app php artisan key:generate

# Generate Swagger documentation
echo "Generating Swagger API documentation..."
docker-compose exec app php artisan l5-swagger:generate

# Set correct permissions for the vendor directory
echo "Setting correct permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/vendor

# How to access!!
echo "Setup complete! You can now access the API documentation and also run tests & mockups at http://localhost:8080/api/documentation"