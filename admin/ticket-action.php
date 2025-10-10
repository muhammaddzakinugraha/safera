<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        // Tambah tiket baru
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stok = $_POST['stok']; // Tangkap stok dari input
        $description = $_POST['description'];

        $stmt = $pdo->prepare("INSERT INTO tipe_tiket (name, price, stok, description, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $price, $stok, $description]);
        echo json_encode(['status' => 'success', 'message' => 'Tiket berhasil ditambahkan.']);
    } elseif ($action === 'edit') {
        // Edit tiket yang ada
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stok = $_POST['stok']; // Tangkap stok dari input
        $description = $_POST['description'];

        $stmt = $pdo->prepare("UPDATE tipe_tiket SET name = ?, price = ?, stok = ?, description = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$name, $price, $stok, $description, $id]);
        echo json_encode(['status' => 'success', 'message' => 'Tiket berhasil diperbarui.']);
    } elseif ($action === 'delete') {
        // Hapus tiket
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM tipe_tiket WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['status' => 'success', 'message' => 'Tiket berhasil dihapus.']);
    }
    exit();
}
?>
