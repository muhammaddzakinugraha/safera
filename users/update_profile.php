<?php
session_start();
include '../config/db.php'; // Koneksi database

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    try {
        $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Profil kamu berhasil di update,silahkan login kembali.";
        } else {
            $_SESSION['error'] = "Gagal Mengupdate Profil.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error Mengupdate: " . $e->getMessage();
    }
    header('Location: profile.php');
    exit;
}

?>