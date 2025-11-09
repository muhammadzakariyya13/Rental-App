<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Payment Test</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f7fafc;
            color: #1a202c;
            padding: 0;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #2d3748;
            padding: 16px 0;
            color: white;
            margin-bottom: 40px;
        }
        h1, h2, h3 {
            font-weight: 600;
        }
        h1 {
            font-size: 1.5rem;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        button:hover {
            background-color: #2563eb;
        }
        .mt-4 {
            margin-top: 16px;
        }
        .mb-4 {
            margin-bottom: 16px;
        }
        .p-4 {
            padding: 16px;
        }
        .rounded {
            border-radius: 4px;
        }
        .hidden {
            display: none;
        }
        .bg-gray-100 {
            background-color: #f3f4f6;
        }
        .bg-green-100 {
            background-color: #d1fae5;
        }
        .bg-red-100 {
            background-color: #fee2e2;
        }
        pre {
            background-color: #1e293b;
            color: #e2e8f0;
            padding: 12px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .text-sm {
            font-size: 0.875rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Payment Gateway Test Page</h1>
        </div>
    </header>
    
    <div class="container">
        <div class="card">
            <h2>Payment Gateway Connection Test</h2>
            <p>Use this page to test the connection to the payment gateway and verify your configuration.</p>
            
            <div id="connection-result" class="p-4 mt-4 mb-4 bg-gray-100 rounded hidden"></div>
            
            <button id="test-connection" class="mt-4">
                Test Payment Gateway Connection
            </button>
        </div>
        
        <div class="grid">
            <div class="card">
                <h2>Gateway Configuration</h2>
                <p><strong>Gateway URL:</strong> {{ config('payment.gateway_url') }}</p>
                <p><strong>API Key:</strong> {{ !empty(config('payment.api_key')) ? '••••••••••••••' : 'Not set' }}</p>
                <p><strong>Webhook Secret:</strong> {{ !empty(config('payment.webhook_secret')) ? '••••••••••••••' : 'Not set' }}</p>
                <p><strong>Merchant Code:</strong> {{ config('payment.merchant_code') }}</p>
            </div>
            
            <div class="card">
                <h2>Webhook Configuration</h2>
                <p>Use these URLs for configuring the payment gateway:</p>
                
                <p><strong>Webhook URL:</strong></p>
                <pre>{{ url('/payment/callback') }}</pre>
                
                <p><strong>Success URL:</strong></p>
                <pre>{{ url('/payment/{id}/success') }}</pre>
                
                <p><strong>Failure URL:</strong></p>
                <pre>{{ url('/payment/{id}?status=failed') }}</pre>
            </div>
        </div>
        
        <div class="card">
            <h2>Simulate Webhook</h2>
            <p>Test the webhook handling by simulating a payment notification:</p>
            
            <div id="webhook-result" class="p-4 mt-4 mb-4 bg-gray-100 rounded hidden"></div>
            
            <form id="simulate-webhook-form" class="mt-4">
                <div class="mb-4">
                    <label for="transaction_id">Transaction ID:</label>
                    <input type="text" id="transaction_id" name="transaction_id" placeholder="Enter transaction ID" required
                        style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; margin-top: 4px;">
                </div>
                
                <div class="mb-4">
                    <label for="status">Payment Status:</label>
                    <select id="status" name="status" required
                        style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; margin-top: 4px;">
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="amount">Amount (optional):</label>
                    <input type="number" id="amount" name="amount" placeholder="Enter payment amount"
                        style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; margin-top: 4px;">
                </div>
                
                <button type="submit">Send Simulated Webhook</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Test Connection Button
            document.getElementById('test-connection').addEventListener('click', function() {
                const resultContainer = document.getElementById('connection-result');
                resultContainer.innerHTML = 'Testing connection...';
                resultContainer.classList.remove('hidden');
                
                fetch('/test-payment/connection')
                    .then(response => response.json())
                    .then(data => {
                        let html = '<h3 style="margin-top: 0">Connection Test Results</h3>';
                        html += '<ul style="margin-left: 20px">';
                        html += `<li>API Key: ${data.api_key}</li>`;
                        html += `<li>Webhook Secret: ${data.webhook_secret || 'Not set'}</li>`;
                        html += `<li>Merchant Code: ${data.merchant_code || 'Not set'}</li>`;
                        html += `<li>Gateway URL: ${data.gateway_url}</li>`;
                        html += `<li>Connection Test: ${data.connection_test}</li>`;
                        
                        if (data.api_test) {
                            html += `<li>API Test: ${data.api_test} (${data.api_status_code || 'N/A'})</li>`;
                        }
                        
                        if (data.merchant_test) {
                            html += `<li>Merchant API Test: ${data.merchant_test} (${data.merchant_status_code || 'N/A'})</li>`;
                        }
                        
                        if (data.connection_error) {
                            html += `<li>Error: ${data.connection_error}</li>`;
                        }
                        
                        if (data.api_error) {
                            html += `<li>API Error: ${data.api_error}</li>`;
                        }
                        
                        if (data.merchant_data) {
                            html += `<li>Merchant Status: ${data.merchant_data.status || 'Unknown'}</li>`;
                        }
                        
                        html += '</ul>';
                        
                        resultContainer.innerHTML = html;
                        
                        if (data.connection_test === 'Successful') {
                            resultContainer.classList.add('bg-green-100');
                            resultContainer.classList.remove('bg-gray-100');
                        } else {
                            resultContainer.classList.add('bg-red-100');
                            resultContainer.classList.remove('bg-gray-100');
                        }
                    })
                    .catch(error => {
                        resultContainer.innerHTML = `<p style="color: #ef4444">Error: ${error.message}</p>`;
                        resultContainer.classList.add('bg-red-100');
                        resultContainer.classList.remove('bg-gray-100');
                    });
            });
            
            // Webhook Simulation Form
            document.getElementById('simulate-webhook-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const resultContainer = document.getElementById('webhook-result');
                resultContainer.innerHTML = 'Sending simulated webhook...';
                resultContainer.classList.remove('hidden');
                
                const formData = {
                    transaction_id: document.getElementById('transaction_id').value,
                    status: document.getElementById('status').value,
                    amount: document.getElementById('amount').value || null
                };
                
                fetch('/test-payment/webhook', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                })
                    .then(response => response.json())
                    .then(data => {
                        let html = '<h3 style="margin-top: 0">Webhook Simulation Result</h3>';
                        html += `<p>${data.message}</p>`;
                        
                        resultContainer.innerHTML = html;
                        
                        if (data.success) {
                            resultContainer.classList.add('bg-green-100');
                            resultContainer.classList.remove('bg-gray-100');
                        } else {
                            resultContainer.classList.add('bg-red-100');
                            resultContainer.classList.remove('bg-gray-100');
                        }
                    })
                    .catch(error => {
                        resultContainer.innerHTML = `<p style="color: #ef4444">Error: ${error.message}</p>`;
                        resultContainer.classList.add('bg-red-100');
                        resultContainer.classList.remove('bg-gray-100');
                    });
            });
        });
    </script>
</body>
</html>
