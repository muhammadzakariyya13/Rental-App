<?php
/**
 * Script untuk mengetes koneksi ke API public
 */

// Set URL untuk diuji (ganti URL sesuai dengan URL ngrok yang aktif)
$baseUrl = 'https://cristopher-hastiest-unviolably.ngrok-free.dev'; // Ganti dengan URL ngrok Anda
$apiEndpoint = '/api/public/payment-test/connection';
$url = $baseUrl . $apiEndpoint;

echo "Testing API connection to: $url\n";

// Inisialisasi cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Untuk testing saja, pada produksi harus true

// Kirim request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Tampilkan hasil
echo "HTTP Status Code: $httpCode\n";

if ($error) {
    echo "Error: $error\n";
} else {
    echo "Response:\n";
    $json = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo $response . "\n";
    }
}

// Cobalah mengakses halaman HTML publik
$htmlEndpoint = '/test-payment';
$htmlUrl = $baseUrl . $htmlEndpoint;

echo "\nTesting HTML page: $htmlUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $htmlUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: $httpCode\n";

if ($error) {
    echo "Error: $error\n";
} else {
    echo "Headers:\n$headers\n";
    echo "Response body truncated to 200 chars:\n";
    echo substr($response, $headerSize, 200) . "...\n";
}
