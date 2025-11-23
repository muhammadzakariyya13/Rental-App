<x-mail::message>
# Pembayaran Berhasil!

Halo {{ $payment->customer_name }},

Pembayaran Anda telah berhasil diproses dengan detail sebagai berikut:

**Detail Pembayaran:**
- ID Pembayaran: #{{ $payment->id }}
- ID Pesanan: {{ $payment->order_id }}
- Jumlah: {{ $payment->formatted_amount }}
- Metode: {{ $payment->method ? ucwords(str_replace('_', ' ', $payment->method)) : '-' }}
- Tanggal: {{ $payment->paid_at->format('d/m/Y H:i') }} WIB

<x-mail::button :url="route('payment.receipt', $payment->id)">
Download Kwitansi
</x-mail::button>

<x-mail::panel>
**Informasi Penting:**
- Simpan kwitansi ini sebagai bukti pembayaran
- Hubungi customer service jika ada pertanyaan
</x-mail::panel>

Terima kasih telah menggunakan layanan kami!

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
