<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran #{{ $payment->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .receipt {
            max-width: 600px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }
        .receipt-number {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .details {
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .detail-row.highlight {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            font-weight: bold;
            font-size: 18px;
            margin: 15px 0;
            padding: 10px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ config('app.name', 'Laravel') }}</div>
            <div>Jl. Contoh No. 123, Jakarta</div>
            <div>Telp: (021) 123-4567 | Email: info@example.com</div>
            
            <div class="receipt-title">KWITANSI PEMBAYARAN</div>
            <div class="receipt-number">No: {{ $payment->id }}/{{ date('Y/m', strtotime($payment->created_at)) }}</div>
        </div>

        <!-- Payment Details -->
        <div class="details">
            <div class="detail-row">
                <span>Sudah terima dari:</span>
                <span>{{ $payment->customer_name }}</span>
            </div>
            
            <div class="detail-row">
                <span>Email:</span>
                <span>{{ $payment->customer_email }}</span>
            </div>
            
            <div class="detail-row">
                <span>Telepon:</span>
                <span>{{ $payment->customer_phone }}</span>
            </div>
            
            <div class="detail-row">
                <span>ID Pesanan:</span>
                <span>{{ $payment->order_id }}</span>
            </div>
            
            <div class="detail-row">
                <span>Metode Pembayaran:</span>
                <span>{{ $payment->method ? ucwords(str_replace('_', ' ', $payment->method)) : '-' }}</span>
            </div>
            
            <div class="detail-row">
                <span>Tanggal Pembayaran:</span>
                <span>{{ $payment->paid_at->format('d F Y, H:i') }} WIB</span>
            </div>
            
            <div class="detail-row highlight">
                <span>TOTAL PEMBAYARAN:</span>
                <span>{{ $payment->formatted_amount }}</span>
            </div>
            
            <div style="margin: 20px 0; font-style: italic;">
                <strong>Terbilang:</strong> 
                {{ ucwords($payment->amount < 1000000 ? 
                    number_format($payment->amount, 0, ',', ' ') . ' rupiah' :
                    'Sejumlah ' . $payment->formatted_amount
                ) }}
            </div>
            
            @if($payment->notes)
            <div class="detail-row">
                <span>Keterangan:</span>
                <span>{{ $payment->notes }}</span>
            </div>
            @endif
        </div>

        <!-- Signature Area -->
        <div class="signature-area">
            <div class="signature-box">
                <div>Yang Menerima</div>
                <div class="signature-line"></div>
                <div>{{ $payment->customer_name }}</div>
            </div>
            
            <div class="signature-box">
                <div>Hormat Kami</div>
                <div class="signature-line"></div>
                <div>{{ config('app.name') }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Kwitansi ini dicetak secara otomatis pada {{ now()->format('d F Y, H:i') }} WIB</p>
            <p>Untuk verifikasi, silakan hubungi customer service kami</p>
            
            @if($payment->payment_id)
            <p><small>Payment Gateway ID: {{ $payment->payment_id }}</small></p>
            @endif
        </div>
    </div>

    <!-- Print Button (hidden when printing) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="
            background: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
            font-size: 16px;
        ">
            Cetak Kwitansi
        </button>
        
        <a href="{{ route('payment.history') }}" style="
            display: inline-block;
            margin-left: 10px;
            background: #6c757d; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 4px;
            font-size: 16px;
        ">
            Kembali ke Riwayat
        </a>
    </div>

    <script>
        // Auto-print when opening in new window/tab
        if (window.location.search.includes('auto-print=1')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>
</html>