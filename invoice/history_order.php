<?php
session_start();
require_once '../config/db.php'; // Koneksi database
require_once '../OOP/PengelolaPembelian.php'; // Include kelas PengelolaPembelian

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    header('Location: login.php'); // Redirect jika bukan user biasa
    exit();
}
// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$idPengguna = $_SESSION['user_id'];

// Buat instance PengelolaPembelian
$pengelolaPembelian = new PengelolaPembelian($pdo);

// Ambil data pembelian pengguna
$riwayatPembelian = $pengelolaPembelian->ambilPembelianPengguna($idPengguna);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian</title>
    <link rel="stylesheet" href="../css/history.css">
    <link rel="icon" type="image/png" href="../assets/logo.png" />
</head>
<body>
    <div class="navigation-buttons">
        <a href="javascript:history.back()">&larr; Back</a>
    </div>

    <h1>Riwayat Pembelian</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Tipe Tiket</th>
                <th>Total</th>
                <th>Status</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($riwayatPembelian) > 0): ?>
                <?php foreach ($riwayatPembelian as $pembelian): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pembelian->getIdOrder()); ?></td>
                    <td><?php echo htmlspecialchars($pembelian->getTipeTiket()); ?></td>
                    <td>Rp <?php echo number_format($pembelian->getTotalHarga(), 0, ',', '.'); ?></td>
                    <td>
                        <?php
                        // Menampilkan status dengan warna
                        switch ($pembelian->getStatus()) {
                            case 'success':
                                echo '<span style="color: green;">Berhasil</span>';
                                break;
                            case 'pending':
                                echo '<span style="color: orange;">Menunggu Pembayaran</span>';
                                break;
                            case 'expired':
                                echo '<span style="color: red;">Kedaluwarsa</span>';
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
                    <td><?php echo date("d-m-Y H:i", strtotime($pembelian->getWaktuDibuat())); ?></td>
                    <td>
                        <a href="invoice_preview.php?order_id=<?php echo $pembelian->getIdOrder(); ?>">Lihat Invoice</a>
                        <a href="download_invoice.php?order_id=<?php echo $pembelian->getIdOrder(); ?>" target="_blank">Download PDF</a>
                        <?php if ($pembelian->getStatus() === 'pending'): ?>
                            <form method="POST" action="cancel_transaction.php" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $pembelian->getIdOrder(); ?>">
                                <button type="submit" style="color: red; background: none; border: none; cursor: pointer;">Batalkan Transaksi</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada riwayat pembelian.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
