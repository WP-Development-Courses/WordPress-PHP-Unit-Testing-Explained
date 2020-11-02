#!/bin/bash

# Fail on any error.
set -e

# Install Composer dependencies.
echo -e "Step 1: Install Composer dependencies"
composer install --no-suggest

# Create the test database.
echo -e "Step 2: Create the test database"
echo -e " * Creating database 'byline_tests' (if it's not already there)"
sudo mysql -u root --password=root -e "CREATE DATABASE IF NOT EXISTS \`byline_tests\`"
echo -e " * Granting the wp user priviledges to the 'byline_tests' database"
sudo mysql -u root --password=root -e "GRANT ALL PRIVILEGES ON \`byline_tests\`.* TO wp@localhost IDENTIFIED BY 'wp';"
echo -e " * DB operations done."

# Do a test run.
echo -e "Step 3: Do a test run"
composer test
