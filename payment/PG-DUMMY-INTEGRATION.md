# PG Dummy Payment Gateway Integration

This document provides information about the PG Dummy payment gateway integration in Rental-App.

## Overview

The Rental-App application integrates with the PG Dummy payment gateway to process property rental payments. This integration allows tenants to pay for their bookings using various payment methods including bank transfers, credit cards, and e-wallets.

## Configuration

The payment gateway configuration is stored in the `.env` file:

```
PAYMENT_API_KEY=your_api_key
PAYMENT_WEBHOOK_SECRET=your_webhook_secret
PAYMENT_GATEWAY_URL=https://payment-dummy.doovera.com
PAYMENT_MERCHANT_CODE=your_merchant_code
```

These values are used by the `PaymentService` class to authenticate with the payment gateway API.

## Payment Flow

1. **Create Booking**: User selects a property and creates a booking
2. **Choose Payment Method**: User selects a payment method (bank transfer, credit card, etc.)
3. **Process Payment**:
   - For regular payments, the application creates a virtual account with PG Dummy
   - For simulated payments, the application marks the payment as completed automatically
   - For offline payments, the application marks the booking as confirmed but payment as pending
4. **Payment Completion**:
   - For regular payments, the user is redirected to the payment gateway to complete payment
   - Upon completion, the payment gateway sends a webhook to notify the application
   - The application updates the booking status accordingly

## Payment Options

The following payment options are available:

1. **Regular Payment**: Process payment through the PG Dummy gateway
2. **Simulated Payment**: Simulate a successful payment (for testing only)
3. **Offline Payment**: Mark payment as pending and booking as confirmed (pay later in person)

## Testing Tools

Several test scripts are available to help with testing the payment integration:

### API Testing

- `pg-dummy-test-updated.php`: Tests direct API communication with the payment gateway
- `simulate-webhook.php`: Simulates a webhook from the payment gateway to test webhook handling

### Full Flow Testing

- `test-payment-flow.sh` (Linux/Mac) / `test-payment-flow.bat` (Windows): 
  Tests the complete payment flow from creating a transaction to receiving a webhook

### Usage

```bash
# Test direct API communication
php pg-dummy-test-updated.php

# Simulate a webhook
php simulate-webhook.php [transaction_id] [status] [callback_url]
# Example: php simulate-webhook.php 8800000123456789 completed http://localhost:8000/api/webhook/payment

# Test complete payment flow
./test-payment-flow.sh [callback_url]
# Example: ./test-payment-flow.sh https://your-ngrok-url.ngrok-free.dev/api/webhook/payment

# Or on Windows:
test-payment-flow.bat [callback_url]
```

## Troubleshooting

If transactions are not appearing in the PG Dummy dashboard:

1. **Check API Key**: Ensure your API key is correct and active
2. **Verify API Endpoints**: Make sure the application is using the correct API endpoints
3. **Test Direct Communication**: Run the `pg-dummy-test-updated.php` script to verify direct API communication
4. **Check Logs**: Review the application logs for any errors
5. **Test Webhooks**: Use the `simulate-webhook.php` script to test webhook handling

## API Reference

### Create Virtual Account

**Endpoint:** `POST /api/v1/virtual-account/create`

**Headers:**
- `X-API-Key`: Your API key
- `Content-Type`: application/json

**Request:**
```json
{
  "external_id": "BOOK-123",
  "amount": 100000,
  "merchant_code": "MCH-XXXXX",
  "customer_name": "John Doe",
  "customer_email": "john@example.com",
  "payment_method": "bank_transfer",
  "description": "Payment for booking",
  "callback_url": "https://your-app.com/api/webhook/payment",
  "success_redirect_url": "https://your-app.com/payment/success",
  "failure_redirect_url": "https://your-app.com/payment/failure"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Virtual account created successfully",
  "data": {
    "va_number": "8800000671781298",
    "external_id": "BOOK-123",
    "amount": 100000,
    "status": "pending",
    "payment_url": "http://payment-dummy.doovera.com/pay/8800000671781298",
    "expired_at": "2025-10-13T12:28:28+00:00",
    "created_at": "2025-10-12T12:28:28+00:00"
  }
}
```

### Check Transaction Status

**Endpoint:** `GET /api/v1/virtual-account/status/{va_number}`

**Headers:**
- `X-API-Key`: Your API key
- `Accept`: application/json

**Response:**
```json
{
  "status": "success",
  "data": {
    "va_number": "8800000671781298",
    "external_id": "BOOK-123",
    "amount": 100000,
    "status": "completed",
    "payment_url": "http://payment-dummy.doovera.com/pay/8800000671781298",
    "expired_at": "2025-10-13T12:28:28+00:00",
    "created_at": "2025-10-12T12:28:28+00:00",
    "updated_at": "2025-10-12T12:30:00+00:00",
    "paid_at": "2025-10-12T12:30:00+00:00"
  }
}
```

### Webhook Notification

**Headers:**
- `X-Webhook-Signature`: HMAC signature using SHA-256
- `Content-Type`: application/json

**Payload:**
```json
{
  "data": {
    "id": "8800000671781298",
    "status": "completed",
    "amount": 100000,
    "payment_method": "bank_transfer",
    "merchant_code": "MCH-XXXXX",
    "external_id": "BOOK-123",
    "created_at": "2025-10-12T12:28:28+00:00",
    "updated_at": "2025-10-12T12:30:00+00:00",
    "paid_at": "2025-10-12T12:30:00+00:00"
  }
}
```
