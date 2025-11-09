# Payment Gateway Integration Documentation

## Overview

This document provides information on the payment gateway integration for the Rental-App application. The integration allows users to make payments for property rentals using a virtual account system provided by the payment gateway.

## Configuration

The payment gateway configuration is stored in the `.env` file:

```
PAYMENT_API_KEY=0tuLmFTDqe1K9lMGkrGRvFReffrvmkhi
PAYMENT_WEBHOOK_SECRET=TKrEI2ytDtJrluJc8wwGUn5zMV3u1HWC
PAYMENT_GATEWAY_URL=https://payment-dummy.doovera.com
PAYMENT_MERCHANT_CODE=MCH-GXLUDZ
```

## Testing Tools

Several test scripts are available to help with testing the payment gateway integration:

### 1. Direct Gateway Test

This script tests the payment gateway API directly without going through Laravel:

```bash
php direct-gateway-test.php
```

### 2. Full Payment Flow Test

This script tests the entire payment flow from creating a transaction to webhook processing:

```bash
# For local testing
php full-payment-test.php

# For ngrok testing
php full-payment-test.php https://your-ngrok-url.ngrok-free.dev
```

### 3. Webhook Test

This script simulates a webhook from the payment gateway:

```bash
php webhook-test.php [transaction_id] [status]
```

Example:
```bash
php webhook-test.php 8800000671781298 completed
```

### 4. Ngrok Testing

For testing webhooks with ngrok, use the enhanced ngrok test script:

```bash
# First, update the ngrok URL in the script
# Then run:
php enhanced-ngrok-test.php
```

## API Endpoints

### 1. Creating a Virtual Account

**Endpoint:** `POST /api/v1/virtual-account/create`

**Headers:**
- X-API-Key: Your API key
- Content-Type: application/json

**Request Body:**
```json
{
  "external_id": "ORDER-123",
  "amount": 100000,
  "merchant_code": "MCH-GXLUDZ",
  "customer_name": "John Doe",
  "customer_email": "john@example.com",
  "payment_method": "bank_transfer",
  "description": "Payment for Order #123",
  "callback_url": "https://your-domain.com/api/webhook/payment",
  "success_redirect_url": "https://your-domain.com/payment/success",
  "failure_redirect_url": "https://your-domain.com/payment/failure"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Virtual account created successfully",
  "data": {
    "va_number": "8800000671781298",
    "external_id": "ORDER-123",
    "amount": 100000,
    "status": "pending",
    "payment_url": "http://payment-dummy.doovera.com/pay/8800000671781298",
    "expired_at": "2025-10-13T12:28:28+00:00",
    "created_at": "2025-10-12T12:28:28+00:00"
  },
  "meta": {
    "request_id": "req_68eb9eec157d7",
    "timestamp": "2025-10-12T12:28:28+00:00"
  }
}
```

### 2. Receiving Webhooks

The payment gateway will send webhook notifications to the callback URL provided when creating a virtual account.

**Webhook URL:** `https://your-domain.com/api/webhook/payment`

**Headers:**
- X-Webhook-Signature: HMAC signature using SHA-256
- Content-Type: application/json

**Webhook Payload:**
```json
{
  "data": {
    "id": "8800000671781298",
    "status": "completed",
    "amount": 100000,
    "payment_method": "bank_transfer",
    "merchant_code": "MCH-GXLUDZ",
    "external_id": "ORDER-123",
    "created_at": "2025-10-12T12:28:28+00:00",
    "updated_at": "2025-10-12T12:30:00+00:00",
    "paid_at": "2025-10-12T12:30:00+00:00"
  }
}
```

## Testing

### 1. Testing Connection

You can test the connection to the payment gateway using the following tools:

```bash
# Test the payment gateway connection
php payment-gateway-test.php

# Test ngrok connections for webhooks
php ngrok-test.php
```

### 2. Web Interface Testing

Access the payment testing interface at:
- Local: http://localhost:8000/test-payment
- Ngrok: https://your-ngrok-domain.ngrok-free.dev/test-payment

### 3. Webhook Testing

You can simulate webhooks using:
```bash
# Using the webhook test script
php webhook-test.php TEST-TRANSACTION-ID completed

# Or through the web interface
# Visit http://localhost:8000/test-payment and use the Simulate Webhook section
```

## Troubleshooting

### 1. CSRF Issues with Webhooks

If webhooks are returning 419 (Page Expired) errors, ensure the webhook URL is properly excluded in the `VerifyCsrfToken` middleware.

### 2. Ngrok URL Access

When using ngrok, ensure your ngrok URL is properly configured and accessible. Test the connection using the `ngrok-test.php` script.

### 3. Payment Gateway Errors

If you receive errors from the payment gateway API, check:
- API Key validity
- Merchant Code correctness
- Request format according to documentation

## Implemented Changes

1. Updated `PaymentService.php` to use the correct API endpoint `/api/v1/virtual-account/create`
2. Updated response handling to match the payment gateway's response format
3. Improved webhook signature verification
4. Added multiple webhook URLs to ensure reliable reception
5. Created test scripts for API and ngrok testing
6. Added comprehensive test interface at `/test-payment`
