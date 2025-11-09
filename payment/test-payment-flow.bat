@echo off
setlocal EnableDelayedExpansion

echo Starting complete payment flow test...

REM Step 1: Create a transaction
echo Step 1: Creating a transaction...
php .\pg-dummy-test-updated.php > transaction_output.txt

REM Extract the VA number from the output using findstr
for /f "tokens=2 delims=: " %%a in ('findstr /C:"VA Number:" transaction_output.txt') do (
    set VA_NUMBER=%%a
    goto :found_va
)

:found_va
if "%VA_NUMBER%"=="" (
    echo Failed to extract VA number from test output
    type transaction_output.txt
    exit /b 1
)

echo Successfully created transaction with VA Number: %VA_NUMBER%

REM Step 2: Simulate a webhook for the transaction with a completed status
echo Step 2: Simulating webhook for transaction %VA_NUMBER% with status 'completed'...

REM Set the callback URL - use argument if provided, otherwise use localhost
if "%~1"=="" (
    set CALLBACK_URL=http://localhost:8000/api/webhook/payment
) else (
    set CALLBACK_URL=%~1
)

echo Using callback URL: %CALLBACK_URL%

REM Run the webhook simulation
php .\simulate-webhook.php %VA_NUMBER% completed %CALLBACK_URL%

if %ERRORLEVEL% EQU 0 (
    echo Webhook sent successfully! Transaction should now be marked as paid.
) else (
    echo Webhook sending failed.
    exit /b 1
)

echo Payment flow test completed successfully!

REM Clean up
del transaction_output.txt

endlocal
