<?php
require './../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form login
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Validasi input
    if (!empty($email) && !empty($password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>
                    alert('Format email tidak valid.');
                    window.history.back();
                  </script>";
            exit();
        }

        // Query untuk mengambil data user berdasarkan email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                session_start();

                // Set data ke dalam session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email']; // Tambahkan user_email ke session
                $_SESSION['user_role'] = $user['role'];

                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../safera.php");
                }
                exit();
            } else {
                // Password salah
                echo "<script>
                        alert('Password salah.');
                        window.history.back();
                      </script>";
                exit();
            }
        } else {
            // Email tidak ditemukan
            echo "<script>
                    alert('Email tidak terdaftar. Silakan daftar terlebih dahulu.');
                    window.location.href = '../signup.php';
                  </script>";
            exit();
        }
    } else {
        // Form kosong
        echo "<script>
                alert('Harap isi semua kolom.');
                window.history.back();
              </script>";
        exit();
    }
} else {
    // Metode pengiriman tidak valid
    echo "<script>
            alert('Metode pengiriman tidak valid.');
            window.history.back();
          </script>";
    exit();
}
?>
