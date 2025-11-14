@echo off
REM Laravel Rental App - Server Start Script (Windows)
REM Solusi untuk error "php: command not found"

echo 🚀 Starting Laravel Rental App Server...
echo 📍 Using Laragon PHP: C:\laragon\bin\php\php-8.2.28-Win32-vs16-x64\

REM Set PHP path temporarily  
set PATH=C:\laragon\bin\php\php-8.2.28-Win32-vs16-x64;%PATH%

REM Start Laravel server
echo ⚡ Starting server on http://127.0.0.1:8000
php artisan serve

REM Alternative: Use full path if PATH doesn't work
REM C:\laragon\bin\php\php-8.2.28-Win32-vs16-x64\php.exe artisan serve