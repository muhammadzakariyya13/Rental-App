@echo off
REM Comprehensive payment integration test script for Windows
REM This script runs all payment integration tests sequentially

echo ===========================================
echo PAYMENT INTEGRATION TEST SUITE
echo ===========================================
echo Starting tests at: %date% %time%
echo.

REM Function to print section header
:section
echo.
echo ===========================================
echo %~1
echo ===========================================
goto :eof

REM Check environment configuration
call :section "CHECKING ENVIRONMENT"
if not exist .env (
  echo Error: .env file not found!
  exit /b 1
)

REM Check if payment config exists
findstr /C:"PAYMENT_API_KEY" .env >nul
if %errorlevel% neq 0 (
  echo Payment configuration not found in .env
  echo Please add PAYMENT_API_KEY, PAYMENT_WEBHOOK_SECRET, PAYMENT_GATEWAY_URL, and PAYMENT_MERCHANT_CODE to your .env file.
  exit /b 1
) else (
  echo Payment configuration found in .env
)

REM Test direct gateway connection
call :section "TESTING DIRECT GATEWAY CONNECTION"
php .\direct-gateway-test.php
if %errorlevel% neq 0 (
  echo FAILED: Direct gateway test
  echo Critical test failed, stopping further tests.
  exit /b 1
) else (
  echo SUCCESS: Direct gateway test
)

REM Test PHP configuration
call :section "CHECKING PHP CONFIGURATION"
php -r "echo 'PHP version: ' . PHP_VERSION . PHP_EOL;"
php -r "echo 'Extensions: curl=' . (extension_loaded('curl') ? 'YES' : 'NO') . ', json=' . (extension_loaded('json') ? 'YES' : 'NO') . PHP_EOL;"

REM Check if Laravel is running or start it
call :section "CHECKING LARAVEL SERVER"
curl -s http://localhost:8000 >nul
if %errorlevel% equ 0 (
  echo Laravel server is already running
) else (
  echo Laravel server is not running, starting it...
  start /b php artisan serve
  echo Started Laravel server
  timeout /t 3 >nul
)

REM Test full payment flow
call :section "TESTING FULL PAYMENT FLOW"
php .\full-payment-test.php

REM Test webhook simulation
call :section "TESTING WEBHOOK SIMULATION"
set TEST_TRANSACTION_ID=TEST-%RANDOM%
echo Using test transaction ID: %TEST_TRANSACTION_ID%
php .\webhook-test.php %TEST_TRANSACTION_ID% completed

REM Summary
call :section "TEST SUMMARY"
echo All tests completed!
echo For manual testing, visit:
echo - http://localhost:8000/test-payment
echo.
echo For detailed information, check:
echo - PAYMENT-GATEWAY-DOCS.md
echo - PAYMENT-TESTING.md

echo.
echo Tests completed at: %date% %time%
echo ===========================================

pause
