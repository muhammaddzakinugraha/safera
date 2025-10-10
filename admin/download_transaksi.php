<?php
require_once '../config/db.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Query Data Transaksi
$stmt = $pdo->prepare("
    SELECT pt.order_id, u.name AS user_name, tt.name AS ticket_name, pt.total_amount, pt.status, pt.created_at
    FROM pembelian_tiket pt
    JOIN users u ON pt.user_id = u.id
    JOIN tipe_tiket tt ON pt.ticket_type_id = tt.id
    ORDER BY pt.created_at DESC
");
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML untuk PDF
$html = '<h1>Daftar Transaksi</h1>';
$html .= '<table border="1" cellpadding="10" cellspacing="0" style="width:100%;">';
$html .= '<thead><tr>
            <th>Order ID</th>
            <th>Nama Pengguna</th>
            <th>Nama Tiket</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr></thead><tbody>';

foreach ($transactions as $trans) {
    $html .= '<tr>
                <td>' . htmlspecialchars($trans['order_id']) . '</td>
                <td>' . htmlspecialchars($trans['user_name']) . '</td>
                <td>' . htmlspecialchars($trans['ticket_name']) . '</td>
                <td>Rp ' . number_format($trans['total_amount'], 0, ',', '.') . '</td>
                <td>' . htmlspecialchars(ucfirst($trans['status'])) . '</td>
                <td>' . date('d-m-Y H:i', strtotime($trans['created_at'])) . '</td>
              </tr>';
}
$html .= '</tbody></table>';

// Generate PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Daftar_Transaksi.pdf', ['Attachment' => true]);
exit;
?>
