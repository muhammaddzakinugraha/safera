<?php
session_start();
require_once '../config/db.php'; // Koneksi database

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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo htmlspecialchars($order['order_id']); ?></title>
    <link rel="icon" type="image/png" href="../assets/logo.png" />
    <style>
        /* General Styling */
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f8f9fa;
        }
        .invoice-container {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 20px 30px;
            border-radius: 8px;
        }
        /* Header */
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007BFF;
        }
        .invoice-header h1 {
            font-size: 3em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .invoice-header p {
            margin: 0;
            font-size: 1em;
            color: #666;
        }
        /* Table */
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-details table, th, td {
            border: 1px solid #ddd;
        }
        .invoice-details th, .invoice-details td {
            padding: 12px;
            text-align: left;
        }
        .invoice-details th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-row {
            font-size: 1.2em;
            font-weight: bold;
        }
        /* Footer */
        .invoice-footer {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            background-color: #007BFF;
            color: #fff;
            font-size: 1em;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        @media print {
            body {
                background-color: #fff;
                color: #000;
            }
            .invoice-container {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
            .btn, .no-print {
                display: none;
            }
        }

        /* untuk invoice */
        @page {
    margin: 0;
    size: auto;
    -webkit-print-color-adjust: exact;
}

@media print {
    body {
        background-color: #fff;
        color: #000;
        margin: 0;
        padding: 0;
        -webkit-print-color-adjust: exact !important;
    }
    
    .invoice-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        box-shadow: none;
        border: none;
        margin: 0;
        padding: 0;
        width: 100%;
        max-width: 700px;
    }
    
    .btn, .no-print {
        display: none;
    }
    
    /* Menghilangkan header dan footer browser saat print */
    @page {
        size: auto;
        margin: 0mm;
    }
    
    /* Menghilangkan URL, tanggal, dan nomor halaman */
    @page :first {
        margin-top: 0;
    }
    
    @page :left {
        margin-left: 0;
    }
    
    @page :right {
        margin-right: 0;
    }
}
        /* untuk invoice */
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Order ID: <strong><?php echo htmlspecialchars($order['order_id']); ?></strong></p>
        </div>

        <!-- Invoice Details Table -->
        <div class="invoice-details">
            <table>
                <tr>
                    <th>Nama</th>
                    <td><?php echo htmlspecialchars($_SESSION['user_name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($_SESSION['user_email']); ?></td>
                </tr>
                <tr>
                    <th>Nomor Telepon</th>
                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                </tr>
                <tr>
                    <th>Tipe Tiket</th>
                    <td><?php echo htmlspecialchars($order['ticket_name']); ?></td>
                </tr>
                <tr>
                    <th>Deskripsi Tiket</th>
                    <td><?php echo htmlspecialchars($order['ticket_description']); ?></td>
                </tr>
                <tr>
                    <th>Harga Tiket</th>
                    <td>Rp <?php echo number_format($order['ticket_price'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td class="total-row">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php
                        switch ($order['status']) {
                            case 'success':
                                echo '<span style="color: green;">Berhasil</span>';
                                break;
                            case 'pending':
                                echo '<span style="color: orange;">Menunggu Pembayaran</span>';
                                break;
                            case 'canceled':
                                echo '<span style="color: red;">Dibatalkan</span>';
                                break;
                            case 'failed':
                                echo '<span style="color: gray;">Gagal</span>';
                                break;
                            default:
                                echo '<span style="color: gray;">Tidak Diketahui</span>';
                                break;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td><?php echo date("F j, Y H:i", strtotime($order['created_at'])); ?></td>
                </tr>
                <tr>
                    <th>Terakhir Diperbarui</th>
                    <td><?php echo date("F j, Y H:i", strtotime($order['updated_at'])); ?></td>
                </tr>
            </table>
        </div>

        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <a href="#" class="btn no-print" onclick="window.print()">Cetak Invoice</a>
            <a href="download_invoice.php?order_id=<?php echo $order['order_id']; ?>" class="btn no-print" target="_blank">Download PDF</a>
            <a href="../safera.php" class="btn no-print">Kembali Ke Website</a>
        </div>
    </div>
</body>
</html>
