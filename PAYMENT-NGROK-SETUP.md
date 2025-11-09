# Payment Gateway Setup dengan Ngrok

## Credentials Payment Gateway
- **Merchant Code:** MCH-L9VRWU
- **API Key:** 0bkGS0zEJ5k8Go8FF1zxLdVQY2aWLV1f  
- **Webhook Secret:** pLPTCPHCQbLcwScSev8yPIttwXVtw0J
- **Gateway URL:** https://payment-dummy.doovera.com

## Setup Ngrok

### 1. Install Ngrok
1. Download dari https://ngrok.com/download
2. Extract ke `C:\ngrok\`
3. Daftar account gratis di ngrok.com
4. Login di dashboard ngrok untuk mendapat authtoken

### 2. Setup Authtoken
Buka Command Prompt sebagai Administrator:
```cmd
cd C:\ngrok
ngrok config add-authtoken YOUR_AUTHTOKEN_FROM_DASHBOARD
```

### 3. Start Services (2 Terminal Terpisah)

**Terminal 1: Start Laravel Server**
```bash
cd "D:\PROJEK LARAVEL\Rental-App"
/c/laragon/bin/php/php-8.2.28-Win32-vs16-x64/php.exe artisan serve
```

**Terminal 2: Start Ngrok**
```cmd
cd C:\ngrok
ngrok http 8000
```

### 4. Copy Ngrok URL
Setelah ngrok berjalan, akan muncul output seperti ini:
```
ngrok                                                          
Session Status                online                           
Account                       your-account (Plan: Free)        
Version                       3.x.x                           
Region                        United States (us)               
Web Interface                 http://127.0.0.1:4040          
Forwarding                    https://abc123def.ngrok-free.app -> http://localhost:8000
Forwarding                    http://abc123def.ngrok-free.app -> http://localhost:8000
```

**COPY URL HTTPS:** `https://abc123def.ngrok-free.app`

### 5. Update Webhook di Payment Gateway Dashboard
Masukkan URL webhook di field "Webhook URL":
```
https://abc123def.ngrok-free.app/payment/callback
```

**JANGAN GUNAKAN:**
- ❌ `https://your-domain.com/webhook`  
- ❌ `http://localhost:8000/payment/callback`

**GUNAKAN:**
- ✅ `https://YOUR-NGROK-URL.ngrok-free.app/payment/callback`

### 5. Update .env (jika diperlukan)
```env
APP_URL=https://your-ngrok-url.ngrok-free.app
PAYMENT_API_KEY=0bkGS0zEJ5k8Go8FF1zxLdVQY2aWLV1f
PAYMENT_WEBHOOK_SECRET=pLPTCPHCQbLcwScSev8yPIttwXVtw0J
PAYMENT_GATEWAY_URL=https://payment-dummy.doovera.com
PAYMENT_MERCHANT_CODE=MCH-L9VRWU
```

## Testing Payment

### Manual Testing Tools:
1. **Manual Update:** http://localhost:8000/manual-update
2. **Webhook Test:** http://localhost:8000/webhook-test  
3. **Payment Test:** http://localhost:8000/test-payment

### Test Flow:
1. Login sebagai penyewa: `penyewa@rental.com` / `password`
2. Browse properti dan buat booking
3. Proses pembayaran
4. Webhook akan dikirim otomatis ke ngrok URL
5. Cek status pemesanan berubah menjadi "confirmed"

## Troubleshooting

### Jika webhook tidak diterima:
1. Cek ngrok masih berjalan
2. Cek Laravel server masih berjalan  
3. Cek URL webhook di payment gateway dashboard
4. Gunakan manual testing tools

### Cek logs:
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Ngrok logs
# Buka http://localhost:4040 (ngrok web interface)
```