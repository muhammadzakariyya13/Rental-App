#!/bin/bash

# Comprehensive payment integration test script
# This script runs all payment integration tests sequentially

echo "==========================================="
echo "PAYMENT INTEGRATION TEST SUITE"
echo "==========================================="
echo "Starting tests at: $(date)"
echo ""

# Function to print section header
section() {
  echo ""
  echo "==========================================="
  echo "$1"
  echo "==========================================="
}

# Function to check if the previous command succeeded
check_result() {
  if [ $? -eq 0 ]; then
    echo "✓ SUCCESS: $1"
  else
    echo "✗ FAILED: $1"
    if [ "$2" == "critical" ]; then
      echo "Critical test failed, stopping further tests."
      exit 1
    fi
  fi
  echo ""
}

# Check environment configuration
section "CHECKING ENVIRONMENT"
if [ ! -f .env ]; then
  echo "Error: .env file not found!"
  exit 1
fi

# Check if payment config exists
if grep -q "PAYMENT_API_KEY" .env && grep -q "PAYMENT_WEBHOOK_SECRET" .env; then
  echo "✓ Payment configuration found in .env"
else
  echo "✗ Payment configuration not found in .env"
  echo "Please add PAYMENT_API_KEY, PAYMENT_WEBHOOK_SECRET, PAYMENT_GATEWAY_URL, and PAYMENT_MERCHANT_CODE to your .env file."
  exit 1
fi

# Test direct gateway connection
section "TESTING DIRECT GATEWAY CONNECTION"
php ./direct-gateway-test.php
check_result "Direct gateway test" "critical"

# Test PHP configuration
section "CHECKING PHP CONFIGURATION"
php -r "echo 'PHP version: ' . PHP_VERSION . PHP_EOL;"
php -r "echo 'Extensions: curl=' . (extension_loaded('curl') ? 'YES' : 'NO') . ', json=' . (extension_loaded('json') ? 'YES' : 'NO') . PHP_EOL;"
check_result "PHP configuration check"

# Check if Laravel is running or start it
section "CHECKING LARAVEL SERVER"
if curl -s http://localhost:8000 > /dev/null; then
  echo "Laravel server is already running"
else
  echo "Laravel server is not running, starting it..."
  php artisan serve --quiet > /dev/null 2>&1 &
  LARAVEL_PID=$!
  echo "Started Laravel server with PID: $LARAVEL_PID"
  sleep 3
fi

# Test full payment flow
section "TESTING FULL PAYMENT FLOW"
php ./full-payment-test.php
check_result "Full payment flow test"

# Check if ngrok is running
section "CHECKING NGROK"
if command -v ngrok &> /dev/null; then
  echo "ngrok is installed"
  
  # Try to get public URL from ngrok
  NGROK_URL=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o '"public_url":"[^"]*' | grep -o 'http[^"]*' | head -1)
  
  if [ -n "$NGROK_URL" ]; then
    echo "ngrok is running with URL: $NGROK_URL"
    echo ""
    echo "To test webhooks through ngrok, update the URL in enhanced-ngrok-test.php and run:"
    echo "php ./enhanced-ngrok-test.php"
  else
    echo "ngrok is not running. To test webhooks, start ngrok with:"
    echo "ngrok http 8000"
  fi
else
  echo "ngrok is not installed. Install it from https://ngrok.com/download"
fi

# Test webhook simulation
section "TESTING WEBHOOK SIMULATION"
TEST_TRANSACTION_ID="TEST-$(date +%s)"
echo "Using test transaction ID: $TEST_TRANSACTION_ID"
php webhook-test.php $TEST_TRANSACTION_ID completed
check_result "Webhook simulation test"

# Summary
section "TEST SUMMARY"
echo "All tests completed!"
echo "For manual testing, visit:"
echo "- http://localhost:8000/test-payment"
echo ""
echo "For detailed information, check:"
echo "- PAYMENT-GATEWAY-DOCS.md"
echo "- PAYMENT-TESTING.md"

# Clean up if we started Laravel
if [ -n "$LARAVEL_PID" ]; then
  echo ""
  echo "Stopping Laravel server (PID: $LARAVEL_PID)..."
  kill $LARAVEL_PID
fi

echo ""
echo "Tests completed at: $(date)"
echo "==========================================="
