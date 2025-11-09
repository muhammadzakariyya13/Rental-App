@echo off
echo Starting Ngrok for Laravel Rental App...
echo.
echo Make sure Laravel server is running on http://localhost:8000
echo Run: php artisan serve
echo.
echo Starting ngrok...

REM Check if ngrok exists in common locations
if exist "C:\ngrok\ngrok.exe" (
    echo Found ngrok at C:\ngrok\
    C:\ngrok\ngrok.exe http 8000 --log=stdout
) else if exist "%USERPROFILE%\ngrok\ngrok.exe" (
    echo Found ngrok at %USERPROFILE%\ngrok\
    %USERPROFILE%\ngrok\ngrok.exe http 8000 --log=stdout
) else (
    echo Ngrok not found! Please install ngrok and update the path in this script.
    echo Download from: https://ngrok.com/download
    pause
)