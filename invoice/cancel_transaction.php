<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    
    try {
        // Mulai transaction
        $pdo->beginTransaction();
        
        // Ambil ticket_type_id dari pembelian yang akan dibatalkan
        $stmt = $pdo->prepare("SELECT ticket_type_id FROM pembelian_tiket WHERE order_id = :order_id AND status = 'pending'");
        $stmt->execute([':order_id' => $orderId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($ticket) {
            // Update status pembelian menjadi canceled
            $updateOrder = $pdo->prepare("UPDATE pembelian_tiket SET status = 'canceled' WHERE order_id = :order_id AND status = 'pending'");
            $updateOrder->execute([':order_id' => $orderId]);
            
            // Kembalikan stok
            $restoreStock = $pdo->prepare("UPDATE tipe_tiket SET stok = stok + 1 WHERE id = :ticket_type_id");
            $restoreStock->execute([':ticket_type_id' => $ticket['ticket_type_id']]);
            
            // Commit transaction
            $pdo->commit();
            
            $_SESSION['message'] = "Transaksi berhasil dibatalkan.";
        } else {
            throw new Exception("Transaksi tidak ditemukan atau sudah diproses.");
        }
        
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $pdo->rollBack();
        $_SESSION['error'] = "Gagal membatalkan transaksi: " . $e->getMessage();
    }
    
    header('Location: history_order.php');
    exit;
}
?>