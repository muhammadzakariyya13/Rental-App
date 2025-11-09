@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Payment Webhook Testing') }}</div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Webhook URLs Available:</h5>
                        <ul>
                            <li><code>{{ url('/payment/callback') }}</code></li>
                            <li><code>{{ url('/api/webhook/payment') }}</code></li>
                        </ul>
                    </div>                <div class="mb-4">
                    <a href="{{ asset('webhook-test.html') }}" class="btn btn-primary" target="_blank">
                        Open Interactive Webhook Test Tool
                    </a>
                    <a href="{{ asset('update-booking-status.html') }}" class="btn btn-success" target="_blank">
                        Update Booking Status
                    </a>
                </div>

                    <hr>

                    <div class="mb-4">
                        <h5>Manual Webhook Testing:</h5>
                        
                        <form method="POST" action="{{ url('/test-payment/webhook') }}">
                            @csrf
                            <div class="form-group row mb-3">
                                <label for="transaction_id" class="col-md-4 col-form-label text-md-right">{{ __('Transaction ID') }}</label>

                                <div class="col-md-6">
                                    <input id="transaction_id" type="text" class="form-control" name="transaction_id" value="{{ '88' . rand(1000000000, 9999999999) }}" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Payment Status') }}</label>

                                <div class="col-md-6">
                                    <select id="status" class="form-control" name="status" required>
                                        <option value="completed">completed</option>
                                        <option value="pending">pending</option>
                                        <option value="failed">failed</option>
                                        <option value="cancelled">cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Test Webhook') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <hr>
                    
                    <div class="mt-3">
                        <h5>How to Use:</h5>
                        <ol>
                            <li>Use the form above to send test webhooks with different statuses</li>
                            <li>Check your application logs to verify webhook processing</li>
                            <li>For more advanced testing, use the interactive tool</li>
                        </ol>
                        
                        <div class="alert alert-info">
                            <strong>Note:</strong> For real webhook testing with external payment providers, you may need to use a service like Ngrok to expose your local server to the internet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
