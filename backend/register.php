<?php

require './../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $confirm = isset($_POST['confirm']) ? trim($_POST['confirm']) : null;

    // Validasi input kosong
    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        echo "<script>
                alert('Harap isi semua kolom.');
                window.history.back();
              </script>";
        exit();
    }

    // Validasi password minimal 6 karakter
    if (strlen($password) < 6) {
        echo "<script>
                alert('Password harus memiliki minimal 6 karakter.');
                window.history.back();
              </script>";
        exit();
    }

    // Validasi password konfirmasi
    if ($password !== $confirm) {
        echo "<script>
                alert('Password tidak sesuai dengan konfirmasi password.');
                window.history.back();
              </script>";
        exit();
    }

    // Cek apakah email sudah digunakan
    $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        echo "<script>
                alert('Email sudah digunakan.');
                window.history.back();
              </script>";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $created_at = date('Y-m-d H:i:s');

    // Simpan data pengguna
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, :created_at)");
    $result = $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'created_at' => $created_at
    ]);

    if ($result) {
        echo "<script>
                alert('Registrasi berhasil. Silakan login.');
                window.location.href = '../login.php';
              </script>";
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat registrasi. Silakan coba lagi.');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('Metode pengiriman tidak valid.');
            window.history.back();
          </script>";
    exit();
}
?>
