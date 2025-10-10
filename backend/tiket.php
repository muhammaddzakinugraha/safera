<?php
session_start();
require_once '../env/config.php'; // Include file config.php untuk membaca .env
require_once '../config/db.php'; // Include file koneksi database
require_once '../midtrans/midtrans-php-master/Midtrans.php'; // Include Midtrans PHP SDK

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY']; // Ambil dari .env
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User belum login.']);
    exit;
}

// Ambil data user dari session
$user_id = $_SESSION['user_id'];

// Validasi input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'] ?? '';
    $ticketTypeId = $_POST['ticketType'] ?? '';

    if (empty($phone) || empty($ticketTypeId)) {
        echo json_encode(['error' => 'Nomor telepon dan tipe tiket harus diisi.']);
        exit;
    }

    try {
        // Ambil data tiket termasuk stok dari database
        $stmt = $pdo->prepare("SELECT id, name, price, stok FROM tipe_tiket WHERE id = :id");
        $stmt->execute([':id' => $ticketTypeId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            echo json_encode(['error' => 'Tipe tiket tidak valid.']);
            exit;
        }

        // Validasi stok
        if ($ticket['stok'] <= 0) {
            echo json_encode(['error' => 'Stok tiket habis.']);
            exit;
        }

        // Jika stok tersedia, kurangi stok
        $updateStok = $pdo->prepare("UPDATE tipe_tiket SET stok = stok - 1 WHERE id = :id");
        $updateStok->execute([':id' => $ticketTypeId]);

        // Total harga tiket
        $total = $ticket['price'];

        // Generate order_id unik
        $orderId = "ORD-" . uniqid();

        // Simpan data ke database
        $sql = "INSERT INTO pembelian_tiket (order_id, user_id, phone, ticket_type_id, total_amount, status, created_at, updated_at) 
                VALUES (:order_id, :user_id, :phone, :ticket_type_id, :total_amount, :status, NOW(), NOW())";
        $stmt = $pdo->prepare($sql);
        $status = 'pending'; // Status awal
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':ticket_type_id', $ticketTypeId);
        $stmt->bindParam(':total_amount', $total);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        // Parameter Midtrans dengan Expiry Time
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'phone' => $phone,
            ],
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s T"), // Waktu saat transaksi dibuat
                'unit' => 'minute',                   // Satuan waktu
                'duration' => 15,                     // Durasi batas waktu pembayaran (15 menit)
            ],
        ];

        // Snap Token dari Midtrans
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        echo json_encode(['token' => $snapToken]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}
