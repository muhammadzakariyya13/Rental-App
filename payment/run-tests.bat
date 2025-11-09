@echo off
REM This script runs all payment tests from the payment directory

REM Define colors using ANSI escape codes for Windows 10+
set GREEN=[92m
set RED=[91m
set YELLOW=[93m
set BLUE=[94m
set NC=[0m

echo %BLUE%============================================%NC%
echo %BLUE%PAYMENT GATEWAY TEST SUITE%NC%
echo %BLUE%============================================%NC%

REM Check if we're in the payment directory
if not exist "direct-gateway-test.php" (
    echo %RED%Error: Run this script from the payment directory%NC%
    exit /b 1
)

REM Run direct gateway test
echo %YELLOW%Running Direct Gateway Test...%NC%
php .\direct-gateway-test.php
echo %GREEN%Direct Gateway Test completed%NC%
echo.

REM Run PG Dummy test
echo %YELLOW%Running PG Dummy Integration Test...%NC%
php .\pg-dummy-test-updated.php
echo %GREEN%PG Dummy Integration Test completed%NC%
echo.

REM Ask if the user wants to run the full payment flow test
echo %YELLOW%Do you want to run the complete payment flow test?%NC%
echo This will create a transaction and simulate a webhook callback.
set /p RUN_PAYMENT_FLOW="Enter 'Y' to continue or any other key to skip: "

if /I "%RUN_PAYMENT_FLOW%"=="Y" (
    echo.
    echo %YELLOW%Running Complete Payment Flow Test...%NC%
    call .\test-payment-flow.bat
    echo %GREEN%Complete Payment Flow Test finished%NC%
)

echo.
echo %BLUE%============================================%NC%
echo %BLUE%ALL TESTS COMPLETED%NC%
echo %BLUE%============================================%NC%

pause
