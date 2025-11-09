# Payment Gateway Testing Instructions

## Prerequisites

1. Laravel development server running
2. ngrok installed and running (for webhook testing)
3. Correct environment variables in `.env` file:
   ```
   PAYMENT_API_KEY=your_api_key
   PAYMENT_WEBHOOK_SECRET=your_webhook_secret
   PAYMENT_GATEWAY_URL=https://payment-dummy.doovera.com
   PAYMENT_MERCHANT_CODE=your_merchant_code
   ```

## Step 1: Testing the Direct API Connection

First, let's verify that our application can connect directly to the payment gateway:

```bash
php direct-gateway-test.php
```

This script will:
- Test connection to the gateway base URL
- Test the API status endpoint with your API key
- Test the merchant status endpoint with your merchant code
- Create a test virtual account and display the transaction details

If any of these steps fail, check your API credentials and ensure the payment gateway is accessible.

## Step 2: Testing the Full Payment Flow

Now, let's test the full payment flow through our Laravel application:

```bash
# Start Laravel development server
php artisan serve

# In a separate terminal, run the test script
php full-payment-test.php
```

This script will:
- Create a test booking in the database
- Use our PaymentService to create a transaction with the payment gateway
- Send a test webhook to simulate payment completion
- Verify that the booking status was updated correctly

## Step 3: Testing with ngrok for Webhooks

For testing webhooks from the payment gateway to your local environment:

```bash
# Start Laravel development server
php artisan serve

# Start ngrok (in a separate terminal)
ngrok http 8000

# Update the ngrok URL in the enhanced-ngrok-test.php script
# Then run the test (in a separate terminal)
php enhanced-ngrok-test.php
```

This will test:
- Connection to your app through ngrok
- Access to the payment test page
- Testing the API endpoints
- Sending webhooks to multiple endpoints to ensure at least one works

## Step 4: Manual Testing

For manual testing, visit these URLs:

1. Local payment test page: http://localhost:8000/test-payment
2. ngrok payment test page: https://your-ngrok-url.ngrok-free.dev/test-payment

From these pages you can:
- Test payment gateway connection
- Create test transactions
- Simulate webhooks

## Troubleshooting

### CSRF Issues with Webhooks (419 errors)

If webhooks return 419 status codes:
- Check `app/Http/Middleware/VerifyCsrfToken.php` to ensure webhook URLs are excluded
- Verify that webhook requests include webhook-related headers
- Try alternative webhook URLs (/webhook/test, /api/webhook/payment, etc.)

### No Response from Webhook Endpoints

If webhook endpoints don't respond:
- Check Laravel logs (`storage/logs/laravel.log`)
- Ensure Laravel server is running
- Verify ngrok is connected properly

### Payment Status Not Updated

If payment status isn't updated after webhook:
- Check webhook response (should be 2xx)
- Check Laravel logs for webhook processing errors
- Verify the transaction ID in the webhook matches the one in the database

## Additional Commands

### Simulate a Specific Webhook

```bash
php webhook-test.php TRANSACTION_ID STATUS
```

Where:
- `TRANSACTION_ID` is the transaction ID from a previous test
- `STATUS` is one of: completed, failed, expired, canceled

### Testing Connection to Payment Gateway

```bash
# Through Laravel
php artisan test-payment:connection

# Direct API test
php direct-gateway-test.php
```

### Clearing Cache

If you've updated configurations and need to clear the cache:

```bash
php artisan cache:clear
php artisan config:clear
```
