#!/bin/bash

echo "Checking Docker..."
if [ -x "$(command -v docker)" ]; then
    echo "Docker already exists."
else
    echo "Please, install Docker."
    exit 1
fi

# Check if Composer executable exists in the directory
if [ ! -x "$(command -v composer)" ]; then
    echo "Composer is not installed in the specified directory. Installing Composer..."

    # Download and install Composer
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=. --filename=composer
    rm composer-setup.php

    echo "Composer installed successfully."
else
    echo "Composer is already installed in the specified directory."
fi

if [ ! -d "./vendor" ]; then
    # If vendor directory does not exist, install composer dependencies
    composer install --ignore-platform-reqs
fi

# Start Laravel Sail containers
./vendor/bin/sail up -d

# Run migrations and seed the database if needed
./vendor/bin/sail artisan migrate:fresh --force --seed

printf "Do you wish to run tests? \nEnter Y or N\n"
read -r answer

if [ "$answer" != "${answer#[Yy]}" ] ;then
    echo 'Running tests...'
    docker exec -it tasks-manager-laravel.test-1 ./vendor/bin/phpunit --coverage-html reports/
fi

printf "Do you want to generate API documentation? \nEnter Y or N\n"
read -r answer

if [ "$answer" != "${answer#[Yy]}" ] ;then
    echo 'Generating Open API documentation'
    ./vendor/bin/sail artisan l5-swagger:generate
fi

echo 'Documentation link: http://localhost/api/documentation'

# Display the URL where your Laravel application is running
echo "Your Laravel application is running at: http://localhost"
