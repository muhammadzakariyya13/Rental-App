<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrak Sewa #{{ $pemesanan->id_pemesanan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0284c7;
        }
        .header h1 {
            font-size: 20px;
            margin: 0 0 10px 0;
            color: #0284c7;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #0284c7;
            text-transform: uppercase;
        }
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            color: #64748b;
        }
        .info-value {
            display: table-cell;
            font-weight: bold;
            color: #1e293b;
        }
        .highlight-box {
            background: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 15px;
            margin: 15px 0;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        ul li {
            margin-bottom: 5px;
        }
        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-row {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        .signature-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .signature-space {
            height: 80px;
            margin: 20px 0;
        }
        .signature-name {
            font-weight: bold;
            border-top: 2px solid #000;
            display: inline-block;
            padding-top: 10px;
            min-width: 200px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center {
            text-align: center;
        }
        .text-justify {
            text-align: justify;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-green {
            color: #16a34a;
        }
        .text-blue {
            color: #0284c7;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>SURAT PERJANJIAN SEWA MENYEWA PROPERTI</h1>
            <p style="font-size: 16px; font-weight: bold;">{{ $pemesanan->properti->nama }}</p>
            <p style="font-size: 11px; color: #64748b;">Nomor Kontrak: {{ $pemesanan->transaction_id }}</p>
        </div>

        {{-- Pembukaan --}}
        <div class="section text-justify">
            <p>Pada hari ini, <strong>{{ $pemesanan->paid_at->isoFormat('dddd, D MMMM Y') }}</strong>, yang bertanda tangan di bawah ini:</p>
        </div>

        {{-- Pihak Pertama --}}
        <div class="section">
            <div class="section-title">PIHAK PERTAMA (PEMILIK)</div>
            <div class="info-box">
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">: {{ $pemilik_nama }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">: {{ $pemesanan->properti->pemilik->email ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Telepon</div>
                    <div class="info-value">: {{ $pemilik_telepon }}</div>
                </div>
            </div>
            <p style="font-size: 11px;">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong></p>
        </div>

        {{-- Pihak Kedua --}}
        <div class="section">
            <div class="section-title">PIHAK KEDUA (PENYEWA)</div>
            <div class="info-box">
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">: {{ $penyewa_nama }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">: {{ $pemesanan->akun->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Telepon</div>
                    <div class="info-value">: {{ $penyewa_telepon }}</div>
                </div>
            </div>
            <p style="font-size: 11px;">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong></p>
        </div>

        {{-- Pasal 1: Objek Sewa --}}
        <div class="section">
            <div class="section-title">PASAL 1 - OBJEK SEWA</div>
            <p>PIHAK PERTAMA sepakat untuk menyewakan properti kepada PIHAK KEDUA dengan detail sebagai berikut:</p>
            <div class="highlight-box">
                <div class="info-row">
                    <div class="info-label">Nama Properti</div>
                    <div class="info-value">: {{ $pemesanan->properti->nama }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tipe Properti</div>
                    <div class="info-value">: {{ ucfirst($pemesanan->properti->tipe) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat Lengkap</div>
                    <div class="info-value">: {{ $pemesanan->properti->alamat }}</div>
                </div>
                @if($pemesanan->properti->kamar_tidur)
                <div class="info-row">
                    <div class="info-label">Kamar Tidur</div>
                    <div class="info-value">: {{ $pemesanan->properti->kamar_tidur }} kamar</div>
                </div>
                @endif
                @if($pemesanan->properti->kamar_mandi)
                <div class="info-row">
                    <div class="info-label">Kamar Mandi</div>
                    <div class="info-value">: {{ $pemesanan->properti->kamar_mandi }} kamar</div>
                </div>
                @endif
                @if($pemesanan->properti->luas_bangunan)
                <div class="info-row">
                    <div class="info-label">Luas Bangunan</div>
                    <div class="info-value">: {{ $pemesanan->properti->luas_bangunan }} m²</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Pasal 2: Jangka Waktu --}}
        <div class="section">
            <div class="section-title">PASAL 2 - JANGKA WAKTU SEWA</div>
            <p>Jangka waktu sewa yang disepakati adalah sebagai berikut:</p>
            <div class="highlight-box">
                <div class="info-row">
                    <div class="info-label">Tanggal Mulai Sewa</div>
                    <div class="info-value">: {{ $tanggal_mulai->format('d F Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Berakhir Sewa</div>
                    <div class="info-value">: {{ $tanggal_selesai->format('d F Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Durasi Sewa</div>
                    <div class="info-value">: {{ $pemesanan->lama_sewa }} Bulan</div>
                </div>
            </div>
            <p style="font-size: 11px; margin-top: 10px;">
                Kontrak akan berakhir otomatis pada tanggal yang telah ditentukan kecuali diperpanjang oleh kedua belah pihak.
            </p>
        </div>

        {{-- Pasal 3: Harga dan Pembayaran --}}
        <div class="section">
            <div class="section-title">PASAL 3 - HARGA SEWA DAN PEMBAYARAN</div>
            <div class="highlight-box">
                <div class="info-row">
                    <div class="info-label">Harga Sewa per Bulan</div>
                    <div class="info-value">: Rp {{ number_format($pemesanan->properti->harga, 0, ',', '.') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Durasi</div>
                    <div class="info-value">: {{ $pemesanan->lama_sewa }} Bulan</div>
                </div>
                <div class="info-row" style="margin-top: 10px; border-top: 2px solid #0284c7; padding-top: 10px;">
                    <div class="info-label" style="font-size: 14px;">Total Pembayaran</div>
                    <div class="info-value text-blue" style="font-size: 16px;">: Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</div>
                </div>
                <div class="info-row" style="margin-top: 10px;">
                    <div class="info-label">Status Pembayaran</div>
                    <div class="info-value text-green">: LUNAS</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Pembayaran</div>
                    <div class="info-value">: {{ $pemesanan->paid_at->format('d F Y, H:i') }} WIB</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Metode Pembayaran</div>
                    <div class="info-value">: {{ $pemesanan->formatted_payment_type }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">ID Transaksi</div>
                    <div class="info-value">: {{ $pemesanan->transaction_id }}</div>
                </div>
            </div>
        </div>

        {{-- Page Break --}}
        <div class="page-break"></div>

        {{-- Pasal 4: Hak dan Kewajiban --}}
        <div class="section">
            <div class="section-title">PASAL 4 - HAK DAN KEWAJIBAN PARA PIHAK</div>
            
            <div style="margin-bottom: 15px;">
                <p class="font-bold">A. Hak PIHAK KEDUA (Penyewa):</p>
                <ul>
                    <li>Menempati dan menggunakan properti sesuai dengan fungsinya selama masa sewa</li>
                    <li>Mendapatkan properti dalam kondisi layak huni</li>
                    <li>Menerima perbaikan dari PIHAK PERTAMA apabila terjadi kerusakan yang bukan disebabkan oleh kesalahan PIHAK KEDUA</li>
                    <li>Mendapatkan pemberitahuan minimal 30 hari sebelum kontrak berakhir</li>
                </ul>
            </div>

            <div style="margin-bottom: 15px;">
                <p class="font-bold">B. Kewajiban PIHAK KEDUA (Penyewa):</p>
                <ul>
                    <li>Menjaga dan merawat properti dengan sebaik-baiknya</li>
                    <li>Tidak mengubah struktur bangunan tanpa izin tertulis dari PIHAK PERTAMA</li>
                    <li>Mengembalikan properti dalam kondisi baik seperti semula saat kontrak berakhir</li>
                    <li>Membayar tagihan listrik, air, gas, internet, dan utilitas lainnya tepat waktu</li>
                    <li>Tidak menyewakan atau memindahkan hak sewa kepada pihak lain tanpa persetujuan PIHAK PERTAMA</li>
                    <li>Memberitahukan kepada PIHAK PERTAMA jika terjadi kerusakan atau masalah pada properti</li>
                </ul>
            </div>

            <div>
                <p class="font-bold">C. Hak PIHAK PERTAMA (Pemilik):</p>
                <ul>
                    <li>Menerima pembayaran penuh sesuai kesepakatan</li>
                    <li>Menerima kembali properti dalam kondisi baik saat kontrak berakhir</li>
                    <li>Melakukan inspeksi properti dengan pemberitahuan terlebih dahulu</li>
                </ul>
            </div>
        </div>

        {{-- Pasal 5: Larangan --}}
        <div class="section">
            <div class="section-title">PASAL 5 - LARANGAN</div>
            <p>PIHAK KEDUA dilarang untuk:</p>
            <ul>
                <li>Menggunakan properti untuk kegiatan ilegal atau yang melanggar hukum</li>
                <li>Merusak atau mengubah struktur bangunan tanpa izin tertulis</li>
                <li>Mengganggu kenyamanan tetangga sekitar</li>
                <li>Memelihara hewan peliharaan tanpa persetujuan PIHAK PERTAMA</li>
                <li>Menunggak pembayaran utilitas lebih dari 2 bulan</li>
            </ul>
        </div>

        {{-- Pasal 6: Pemutusan Kontrak --}}
        <div class="section">
            <div class="section-title">PASAL 6 - PEMUTUSAN KONTRAK</div>
            <p>Kontrak ini dapat diputuskan sebelum jangka waktu berakhir apabila:</p>
            <ul>
                <li>PIHAK KEDUA melanggar ketentuan dalam perjanjian ini</li>
                <li>PIHAK KEDUA menggunakan properti untuk kegiatan ilegal</li>
                <li>Terjadi force majeure (bencana alam, perang, dll) yang menyebabkan properti tidak dapat digunakan</li>
                <li>Kesepakatan bersama antara PIHAK PERTAMA dan PIHAK KEDUA dengan pemberitahuan minimal 30 hari sebelumnya</li>
            </ul>
        </div>

        {{-- Pasal 7: Penyelesaian Sengketa --}}
        <div class="section">
            <div class="section-title">PASAL 7 - PENYELESAIAN SENGKETA</div>
            <p class="text-justify">
                Apabila terjadi perselisihan atau perbedaan pendapat dalam pelaksanaan perjanjian ini, 
                kedua belah pihak sepakat untuk menyelesaikannya secara musyawarah. Apabila tidak tercapai kesepakatan, 
                maka penyelesaian akan dilakukan melalui jalur hukum yang berlaku di Indonesia.
            </p>
        </div>

        {{-- Penutup --}}
        <div class="section">
            <div class="section-title">PASAL 8 - PENUTUP</div>
            <p class="text-justify">
                Demikian surat perjanjian ini dibuat dengan sebenarnya dalam keadaan sehat jasmani dan rohani, 
                tanpa ada paksaan dari pihak manapun, untuk dapat dipergunakan sebagaimana mestinya.
            </p>
        </div>

        {{-- Tanda Tangan --}}
        <div class="signature-section">
            <p class="text-center" style="margin-bottom: 5px;">
                Dibuat di: <strong>{{ $pemesanan->properti->alamat }}</strong>
            </p>
            <p class="text-center" style="margin-bottom: 30px;">
                Tanggal: <strong>{{ $pemesanan->paid_at->format('d F Y') }}</strong>
            </p>

            <div class="signature-row">
                <div class="signature-col">
                    <p class="font-bold">PIHAK PERTAMA</p>
                    <p style="font-size: 11px; color: #64748b;">(Pemilik Properti)</p>
                    <div class="signature-space"></div>
                    <div class="signature-name">
                        {{ $pemilik_nama }}
                    </div>
                </div>
                <div class="signature-col">
                    <p class="font-bold">PIHAK KEDUA</p>
                    <p style="font-size: 11px; color: #64748b;">(Penyewa)</p>
                    <div class="signature-space"></div>
                    <div class="signature-name">
                        {{ $penyewa_nama }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p><strong>Rental App</strong> - Platform Sewa Properti Terpercaya</p>
            <p>Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan basah</p>
            <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
            <p style="margin-top: 10px; font-size: 9px;">
                ID Dokumen: {{ $pemesanan->transaction_id }} | Halaman 1 dari 2
            </p>
        </div>
    </div>
</body>
</html>
