#!/bin/bash
# Laravel Rental App - Server Start Script
# Solusi untuk error "php: command not found"

echo "🚀 Starting Laravel Rental App Server..."
echo "📍 Using Laragon PHP: /c/laragon/bin/php/php-8.2.28-Win32-vs16-x64/"

# Set PHP path temporarily
export PATH="/c/laragon/bin/php/php-8.2.28-Win32-vs16-x64:$PATH"

# Start Laravel server
echo "⚡ Starting server on http://127.0.0.1:8000"
php artisan serve

# Alternative: Use full path if PATH doesn't work
# /c/laragon/bin/php/php-8.2.28-Win32-vs16-x64/php.exe artisan serve