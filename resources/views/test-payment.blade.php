<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Test Payment Gateway') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-4">Payment Gateway Test</h3>
                        
                        <div id="connection-result" class="p-4 mb-6 bg-gray-100 dark:bg-gray-700 rounded-md hidden"></div>
                        
                        <button id="test-connection" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Test Payment Gateway Connection
                        </button>
                    </div>
                    
                    <hr class="my-6 border-gray-200 dark:border-gray-600">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-4">Simulate Payment Webhook</h3>
                        <p class="text-sm mb-4">Use this to simulate webhook callbacks from the payment gateway</p>
                        
                        <div id="webhook-result" class="p-4 mb-6 bg-gray-100 dark:bg-gray-700 rounded-md hidden"></div>
                        
                        <form id="simulate-webhook-form" class="space-y-4">
                            <div>
                                <label for="transaction_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transaction ID</label>
                                <input type="text" id="transaction_id" name="transaction_id" placeholder="Enter transaction ID" required
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Status</label>
                                <select id="status" name="status" required
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="completed">Completed</option>
                                    <option value="failed">Failed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                              <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (optional)</label>
                                <input type="number" id="amount" name="amount" placeholder="Enter payment amount" 
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            
                            <div>
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    Send Simulated Webhook
                                </button>
                            </div>
                            
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-4">
                                <p>Note: This simulates a webhook coming from the payment gateway.</p>
                                <p>The webhook will include a valid signature using the configured webhook secret.</p>
                            </div>
                        </form>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-600">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-4">Payment Gateway Configuration</h3>
                        
                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                            <p class="mb-2 font-medium">Webhook URL:</p>
                            <code class="block p-2 bg-gray-800 text-white rounded mb-4">{{ route('penyewa.payment.callback') }}</code>
                            
                            <p class="mb-2 font-medium">Redirect URLs:</p>
                            <p class="mb-1">Success URL:</p>
                            <code class="block p-2 bg-gray-800 text-white rounded mb-2">https://{{ request()->getHost() }}/penyewa/payment/{id}/success</code>
                            
                            <p class="mb-1">Failure URL:</p>
                            <code class="block p-2 bg-gray-800 text-white rounded">https://{{ request()->getHost() }}/penyewa/payment/{id}?status=failed</code>
                        </div>
                    </div>
                </div>
            </div>
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
                        let html = '<h4 class="font-medium mb-2">Connection Test Results</h4>';                        html += '<ul class="list-disc pl-5 space-y-1">';
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
                            resultContainer.classList.add('bg-green-100', 'dark:bg-green-900');
                            resultContainer.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                        } else {
                            resultContainer.classList.add('bg-yellow-100', 'dark:bg-yellow-900');
                            resultContainer.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                        }
                    })
                    .catch(error => {
                        resultContainer.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
                        resultContainer.classList.add('bg-red-100', 'dark:bg-red-900');
                        resultContainer.classList.remove('bg-gray-100', 'dark:bg-gray-700');
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
                        let html = '<h4 class="font-medium mb-2">Webhook Simulation Result</h4>';
                        html += `<p>${data.message}</p>`;
                        
                        resultContainer.innerHTML = html;
                        
                        if (data.success) {
                            resultContainer.classList.add('bg-green-100', 'dark:bg-green-900');
                            resultContainer.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                        } else {
                            resultContainer.classList.add('bg-red-100', 'dark:bg-red-900');
                            resultContainer.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                        }
                    })
                    .catch(error => {
                        resultContainer.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
                        resultContainer.classList.add('bg-red-100', 'dark:bg-red-900');
                        resultContainer.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                    });
            });
        });
    </script>
</x-app-layout>
