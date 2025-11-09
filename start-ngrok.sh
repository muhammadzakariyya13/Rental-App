#!/bin/bash

# Script untuk menjalankan ngrok dari VS Code terminal
# File: start-ngrok.sh

echo "🚀 Starting Ngrok for Laravel Rental App..."
echo ""

# Check if Laravel server is running
if ! curl -s http://localhost:8000 > /dev/null; then
    echo "⚠️  Laravel server tidak berjalan!"
    echo "Jalankan dulu: php artisan serve"
    echo ""
fi

echo "Starting ngrok..."

# Try different ngrok locations
if [ -f "/c/ngrok/ngrok.exe" ]; then
    echo "Found ngrok at /c/ngrok/"
    /c/ngrok/ngrok.exe http 8000
elif [ -f "/c/Users/$USER/ngrok/ngrok.exe" ]; then
    echo "Found ngrok at /c/Users/$USER/ngrok/"
    /c/Users/$USER/ngrok/ngrok.exe http 8000
elif command -v ngrok &> /dev/null; then
    echo "Found ngrok in PATH"
    ngrok http 8000
else
    echo "❌ Ngrok tidak ditemukan!"
    echo ""
    echo "Download ngrok dari: https://ngrok.com/download"
    echo "Extract ke: C:\\ngrok\\"
    echo "Atau tambahkan ngrok ke PATH"
fi