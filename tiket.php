<?php
session_start();
require_once 'env/config.php'; // Untuk Client Key Midtrans
require_once 'config/db.php'; // Include koneksi database

// Validasi jika user belum login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect ke halaman login jika belum login
    exit;
}

// Ambil data user dari session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'guest@example.com';

// Ambil daftar tipe tiket dari database, termasuk stok
$stmt = $pdo->prepare("SELECT id, name, price, description, stok FROM tipe_tiket ORDER BY id ASC");
$stmt->execute();
$ticket_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Tiket</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/tiket.css" />
    <link rel="icon" type="image/png" href="assets/logo.png" />
    <!-- Midtrans Snap.js -->
    <script
        type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="<?php echo $_ENV['MIDTRANS_CLIENT_KEY']; ?>">
    </script>
</head>
<body>
    <h1>Pembelian Tiket</h1>
    <form id="ticketForm">
        <label for="name">Nama:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user_name); ?>" readonly><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly><br>
        
        <label for="phone">Nomor Telepon:</label>
        <input type="tel" name="phone" required><br>
        
        <label for="ticketType">Tipe Tiket:</label>
        <select name="ticketType" id="ticketType" required>
            <option value="" disabled selected>Pilih tipe tiket</option>
            <?php foreach ($ticket_types as $ticket): ?>
                <option 
                    value="<?php echo $ticket['id']; ?>" 
                    data-description="<?php echo htmlspecialchars($ticket['description']); ?>" 
                    data-price="<?php echo $ticket['price']; ?>" 
                    data-stok="<?php echo $ticket['stok']; ?>">
                    <?php echo htmlspecialchars($ticket['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        
        <label for="price">Harga Tiket:</label>
        <input type="text" id="price" name="price" value="Rp 0" readonly><br>

        <label for="stock">Stok Tiket:</label>
        <input type="text" id="stock" name="stock" value="Tidak tersedia" readonly><br>

        <label for="description">Deskripsi Tiket:</label>
        <textarea id="description" name="description" readonly style="width: 100%; height: 50px;"></textarea><br>
        
        <button type="submit" disabled>Bayar</button>
    </form>

    <script>
        // Event listener untuk menampilkan harga, stok, dan deskripsi tiket
        document.getElementById('ticketType').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const description = selectedOption.getAttribute('data-description') || 'Deskripsi tidak tersedia.';
            const price = selectedOption.getAttribute('data-price') || '0';
            const stok = selectedOption.getAttribute('data-stok') || '0';

            // Update harga tiket
            document.getElementById('price').value = "Rp " + parseInt(price).toLocaleString('id-ID');

            // Update stok tiket
            document.getElementById('stock').value = stok > 0 ? stok + " tersedia" : "Habis";

            // Update deskripsi tiket
            document.getElementById('description').value = description;

            // Validasi stok
            const submitButton = document.getElementById('ticketForm').querySelector('button[type="submit"]');
            if (parseInt(stok) <= 0) {
                alert('Stok tiket habis! Silakan pilih tiket lain.');
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        });

        // Handle form submission
        document.getElementById('ticketForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Kirim data ke backend
            fetch('backend/tiket.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        window.snap.pay(data.token, {
                            onSuccess: function(result) {
                                console.log('Pembayaran berhasil:', result);
                                window.location.href = 'safera.php';
                            },
                            onPending: function(result) {
                                alert('Pembayaran Anda tertunda!');
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal! Silakan coba lagi.');
                            }
                        });
                    } else {
                        alert('Terjadi kesalahan: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada server: ' + error.message);
                });
        });
    </script>
</body>
</html>
