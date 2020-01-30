#!/bin/bash

# Fail on any error.
set -e

# Install Composer dependencies.
composer install --no-suggest

# Create the test database.
mysqladmin create byline_tests -u root -proot

# Do a test run.
composer test
