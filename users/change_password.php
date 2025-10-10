<?php
session_start();
include '../config/db.php'; // Koneksi database

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        // Query untuk mendapatkan password lama dari tabel `users`
        $query = "SELECT password FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                // Validasi panjang password baru (minimal 6 karakter)
                if (strlen($new_password) < 6) {
                    $_SESSION['error'] = "Password baru harus memiliki minimal 6 karakter.";
                } else {
                    // Hash password baru
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Query untuk update password baru ke tabel `users`
                    $update_query = "UPDATE users SET password = :password WHERE id = :id";
                    $update_stmt = $pdo->prepare($update_query);
                    $update_stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                    $update_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

                    if ($update_stmt->execute()) {
                        $_SESSION['message'] = "Password kamu berhasil di ganti, silahkan login kembali.";
                    } else {
                        $_SESSION['error'] = "Gagal mengupdate password.";
                    }
                }
            } else {
                $_SESSION['error'] = "Password baru dan konfirmasi password tidak cocok.";
            }
        } else {
            $_SESSION['error'] = "Password saat ini salah.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Terjadi kesalahan saat mengubah password: " . $e->getMessage();
    }
    header('Location: profile.php');
    exit;
}
?>
