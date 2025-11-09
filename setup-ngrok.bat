@echo off
echo ===================================================
echo         SETUP NGROK UNTUK PAYMENT GATEWAY
echo ===================================================
echo.

echo 1. DOWNLOAD NGROK:
echo    - Buka: https://ngrok.com/download
echo    - Download versi Windows (ZIP file)
echo.

echo 2. EXTRACT NGROK:
echo    - Extract ngrok.exe ke folder: C:\ngrok\
echo    - Pastikan file ada di: C:\ngrok\ngrok.exe
echo.

echo 3. DAFTAR ACCOUNT GRATIS:
echo    - Buka: https://ngrok.com
echo    - Sign up gratis
echo    - Login dan copy authtoken dari dashboard
echo.

echo 4. SETUP AUTHTOKEN:
echo    Jalankan command ini di Command Prompt:
echo    cd C:\ngrok
echo    ngrok config add-authtoken YOUR_AUTHTOKEN_HERE
echo.

echo 5. JALANKAN LARAVEL SERVER:
echo    Buka terminal VS Code dan jalankan:
cd "%~dp0"
echo    %CD%
echo    /c/laragon/bin/php/php-8.2.28-Win32-vs16-x64/php.exe artisan serve
echo.

echo 6. JALANKAN NGROK (Terminal kedua):
echo    cd C:\ngrok
echo    ngrok http 8000
echo.

echo 7. COPY WEBHOOK URL:
echo    Dari output ngrok, copy URL HTTPS seperti:
echo    https://abc123.ngrok-free.app
echo    Lalu tambahkan: /payment/callback
echo    Jadi: https://abc123.ngrok-free.app/payment/callback
echo.

echo 8. UPDATE PG DUMMY DASHBOARD:
echo    Paste webhook URL ke field "Webhook URL" di dashboard PG Dummy
echo.

echo ===================================================
echo Tekan sembarang tombol untuk lanjut...
pause >nul