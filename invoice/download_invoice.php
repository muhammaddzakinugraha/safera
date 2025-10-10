<?php
session_start();
require_once '../config/db.php'; // Koneksi database
require_once '../vendor/autoload.php'; // Library Dompdf

use Dompdf\Dompdf;
use Dompdf\Options;

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Validasi order_id dari query string
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die("Order ID tidak ditemukan atau tidak valid!");
}
$order_id = $_GET['order_id'];

// Validasi session user_name dan user_email
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    die("Informasi pengguna tidak ditemukan. Silakan login ulang.");
}

// Ambil data order dan tipe tiket dari database
$stmt = $pdo->prepare("
    SELECT 
        pembelian_tiket.order_id,
        pembelian_tiket.phone,
        pembelian_tiket.total_amount,
        pembelian_tiket.status,
        pembelian_tiket.created_at,
        pembelian_tiket.updated_at,
        tipe_tiket.name AS ticket_name,
        tipe_tiket.description AS ticket_description,
        tipe_tiket.price AS ticket_price
    FROM 
        pembelian_tiket
    JOIN 
        tipe_tiket ON pembelian_tiket.ticket_type_id = tipe_tiket.id
    WHERE 
        pembelian_tiket.order_id = :order_id AND pembelian_tiket.user_id = :user_id
");
$stmt->execute(['order_id' => $order_id, 'user_id' => $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order tidak ditemukan atau Anda tidak memiliki akses.");
}

// Inisialisasi Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// HTML yang akan dikonversi menjadi PDF
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - ' . htmlspecialchars($order['order_id']) . '</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            max-width: 700px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007BFF;
        }
        .invoice-header h1 {
            font-size: 2.8em;
            font-weight: bold;
            color: #333;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-details th, .invoice-details td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .invoice-details th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Order ID: <strong>' . htmlspecialchars($order['order_id']) . '</strong></p>
        </div>
        <div class="invoice-details">
            <table>
                <tr><th>Nama</th><td>' . htmlspecialchars($_SESSION['user_name']) . '</td></tr>
                <tr><th>Email</th><td>' . htmlspecialchars($_SESSION['user_email']) . '</td></tr>
                <tr><th>Nomor Telepon</th><td>' . htmlspecialchars($order['phone']) . '</td></tr>
                <tr><th>Tipe Tiket</th><td>' . htmlspecialchars($order['ticket_name']) . '</td></tr>
                <tr><th>Deskripsi Tiket</th><td>' . htmlspecialchars($order['ticket_description']) . '</td></tr>
                <tr><th>Harga Tiket</th><td>Rp ' . number_format($order['ticket_price'], 0, ',', '.') . '</td></tr>
                <tr><th>Total</th><td class="total-row">Rp ' . number_format($order['total_amount'], 0, ',', '.') . '</td></tr>
                <tr><th>Status</th><td>' . htmlspecialchars($order['status']) . '</td></tr>
                <tr><th>Tanggal</th><td>' . date("F j, Y H:i", strtotime($order['created_at'])) . '</td></tr>
                <tr><th>Terakhir Diperbarui</th><td>' . date("F j, Y H:i", strtotime($order['updated_at'])) . '</td></tr>
            </table>
        </div>
    </div>
</body>
</html>
';

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// Render PDF
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Kirim file PDF ke browser
$dompdf->stream("Invoice_" . $order_id . ".pdf", ["Attachment" => 1]);
exit;
?>
