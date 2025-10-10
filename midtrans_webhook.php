<?php
require_once 'config/db.php'; // File koneksi database
require_once 'env/config.php'; // File untuk memuat .env
require_once 'midtrans/midtrans-php-master/Midtrans.php'; // Midtrans PHP SDK

// Konfigurasi Midtrans menggunakan .env
\Midtrans\Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
\Midtrans\Config::$isProduction = false; // Sandbox mode
\Midtrans\Config::$isSanitized = true;

// Ambil data notifikasi dari Midtrans
$input = file_get_contents("php://input");
$json = json_decode($input, true);

// Log data webhook untuk debugging
file_put_contents('logs/webhook_log.txt', date('Y-m-d H:i:s') . " - Input: " . $input . PHP_EOL, FILE_APPEND);

// Validasi data JSON
if (!$json || !isset($json['order_id'], $json['transaction_status'], $json['signature_key'])) {
    http_response_code(400);
    echo "Invalid data received.";
    exit;
}

// Mendapatkan informasi dari notifikasi
$orderId = $json['order_id'];
$transactionStatus = $json['transaction_status'];
$fraudStatus = $json['fraud_status'] ?? null;
$statusCode = $json['status_code'];
$grossAmount = $json['gross_amount'];
$signatureKey = $json['signature_key'];

// Validasi signature key
$calculatedSignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $_ENV['MIDTRANS_SERVER_KEY']);
if ($signatureKey !== $calculatedSignatureKey) {
    http_response_code(403);
    echo "Invalid signature key.";
    exit;
}

// Tentukan status transaksi berdasarkan notifikasi
try {
    switch ($transactionStatus) {
        case 'capture':
            $status = ($fraudStatus === 'challenge') ? 'pending' : 'success';
            break;
        case 'settlement':
            $status = 'success';
            break;
        case 'pending':
            $status = 'pending';
            break;
        case 'deny':
            $status = 'denied';
            break;
        case 'expire':
            $status = 'failed'; // Menggunakan "failed" untuk expired
            break;
        case 'cancel':
            $status = 'canceled';
            break;
        default:
            $status = 'unknown';
            break;
    }

    // Log status yang dideteksi
    file_put_contents('logs/webhook_status_log.txt', date('Y-m-d H:i:s') . " - Order ID: $orderId - Status: $status" . PHP_EOL, FILE_APPEND);

    // Perbarui status transaksi di database
    $sql = "UPDATE pembelian_tiket SET status = :status, updated_at = NOW() WHERE order_id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':order_id', $orderId);
    $stmt->execute();

    // Kirimkan respons sukses ke Midtrans
    http_response_code(200);
    echo "OK";
} catch (Exception $e) {
    // Log error jika terjadi kesalahan
    file_put_contents('logs/webhook_error_log.txt', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);

    // Kirim respons error ke Midtrans
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
}
