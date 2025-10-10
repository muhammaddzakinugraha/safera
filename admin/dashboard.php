<?php
require_once '../config/db.php'; // Pastikan file ini berisi koneksi database
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php'); // Redirect jika bukan admin
    exit();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$admin_name = $_SESSION['user_name'] ?? 'Admin';

// Ambil data user dari session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'guest@example.com';

// Ambil data tipe tiket
try {
    $stmt = $pdo->prepare("SELECT * FROM tipe_tiket ORDER BY created_at DESC");
    $stmt->execute();
    $ticket_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$stmt = $pdo->prepare("SELECT id, name, price, stok, description FROM tipe_tiket ORDER BY id ASC");
$stmt->execute();
$ticket_types = $stmt->fetchAll(PDO::FETCH_ASSOC);


// transaksi user
// Ambil filter status dan search
$filter_status = $_GET['status'] ?? '';
$search_name = $_GET['search'] ?? '';

// Ambil data transaksi dengan filter
try {
    $sql = "SELECT 
                pt.id, 
                pt.order_id, 
                pt.total_amount, 
                pt.status, 
                pt.created_at, 
                pt.updated_at, 
                u.name AS user_name, 
                tt.name AS ticket_name
            FROM pembelian_tiket pt
            JOIN users u ON pt.user_id = u.id
            JOIN tipe_tiket tt ON pt.ticket_type_id = tt.id
            WHERE 1=1";

    // Tambahkan filter berdasarkan status jika dipilih
    if (!empty($filter_status)) {
        $sql .= " AND pt.status = :status";
    }

    // Tambahkan filter pencarian nama pengguna
    if (!empty($search_name)) {
        $sql .= " AND u.name LIKE :search_name";
    }

    // Urutkan berdasarkan tanggal transaksi terbaru
    $sql .= " ORDER BY pt.created_at DESC";

    // Persiapkan query
    $stmt = $pdo->prepare($sql);

    // Bind parameter untuk status jika ada
    if (!empty($filter_status)) {
        $stmt->bindParam(':status', $filter_status, PDO::PARAM_STR);
    }

    // Bind parameter untuk pencarian nama jika ada
    if (!empty($search_name)) {
        $search_name = htmlspecialchars($search_name); // Pastikan aman dari XSS
        $stmt->bindParam(':search_name', $search_name, PDO::PARAM_STR);
    }

    // Eksekusi query
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// transaksi user

// daftar users
// Hapus User
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Casting ID ke integer untuk keamanan
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$id])) {
        // Redirect setelah berhasil hapus
        header("Location: dashboard.php?status=success&message=User berhasil dihapus!");
        exit();
    } else {
        echo "Gagal menghapus user!";
    }
}

// Edit User
// Bagian untuk memproses update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$name, $email, $role, $id])) {
            header("Location: dashboard.php?status=success&message=User berhasil diupdate!");
            exit();
        } else {
            throw new Exception("Gagal mengupdate user!");
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
// Tampilkan Daftar User
$sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// daftar users

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="../assets/logo.png" />
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Tombol Toggle Sidebar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Dashboard</a>
        </li>
    </ul>

    <!-- Logout Button -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="../logout.php" class="btn btn-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Hai, <strong><?php echo htmlspecialchars($user_name); ?></span></span>
        </a>
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav>
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" onclick="showContent('dashboard')">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="showContent('tickets')">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            <p>Tipe Tiket</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="showContent('transactions')">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>Transaksi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                    <a href="#" class="nav-link" onclick="showContent('users')">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Daftar User</p>
                    </a>
                </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Konten Utama -->
        <section class="content-header">
            <h1 id="content-title">Dashboard</h1>
        </section>

        <section class="content">
            <!-- Dashboard -->
            <div id="dashboard" class="content-section">
                <div class="card">
                    <div class="card-body">
                        <p>Selamat datang di Dashboard Admin!</p>
                    </div>
                </div>
            </div>

           <!-- Tipe Tiket -->
<div id="tickets" class="content-section" style="display:none;">
    <button class="btn btn-primary" onclick="showAddModal()">Tambah Tiket</button>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th> <!-- Kolom baru untuk stok -->
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ticket_types as $ticket): ?>
                        <tr>
                            <td><?php echo $ticket['id']; ?></td>
                            <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                            <td>Rp <?php echo number_format($ticket['price'], 0, ',', '.'); ?></td>
                            <td><?php echo $ticket['stok']; ?></td> <!-- Menampilkan stok -->
                            <td><?php echo htmlspecialchars($ticket['description']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="showEditModal(<?php echo $ticket['id']; ?>, '<?php echo htmlspecialchars($ticket['name']); ?>', <?php echo $ticket['price']; ?>, '<?php echo htmlspecialchars($ticket['description']); ?>', <?php echo $ticket['stok']; ?>)">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteTicket(<?php echo $ticket['id']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- js tambah tiket -->
<script>
    // Tambah Tiket
    function showAddModal() {
        Swal.fire({
            title: 'Tambah Tiket',
            html: `
                <input id="name" class="swal2-input" placeholder="Nama">
                <input id="price" class="swal2-input" type="number" placeholder="Harga">
                <input id="stok" class="swal2-input" type="number" placeholder="Stok">
                <textarea id="description" class="swal2-textarea" placeholder="Deskripsi"></textarea>
            `,
            confirmButtonText: 'Tambah',
            showCancelButton: true
        }).then(result => {
            if (result.isConfirmed) {
                // Ambil data input
                const name = document.getElementById('name').value;
                const price = document.getElementById('price').value;
                const stok = document.getElementById('stok').value;
                const description = document.getElementById('description').value;

                // Kirim data ke backend
                fetch('ticket-action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=add&name=${name}&price=${price}&stok=${stok}&description=${description}`
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Berhasil', data.message, 'success').then(() => location.reload());
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                });
            }
        });
    }

    // Edit Tiket
    function showEditModal(id, name, price, description, stok) {
        Swal.fire({
            title: 'Edit Tiket',
            html: `
                <input id="name" class="swal2-input" value="${name}">
                <input id="price" class="swal2-input" type="number" value="${price}">
                <input id="stok" class="swal2-input" type="number" value="${stok}">
                <textarea id="description" class="swal2-textarea">${description}</textarea>
            `,
            confirmButtonText: 'Simpan',
            showCancelButton: true
        }).then(result => {
            if (result.isConfirmed) {
                // Ambil data input yang diperbarui
                const updatedName = document.getElementById('name').value;
                const updatedPrice = document.getElementById('price').value;
                const updatedStok = document.getElementById('stok').value;
                const updatedDescription = document.getElementById('description').value;

                // Kirim data ke backend
                fetch('ticket-action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=edit&id=${id}&name=${updatedName}&price=${updatedPrice}&stok=${updatedStok}&description=${updatedDescription}`
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Berhasil', data.message, 'success').then(() => location.reload());
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                });
            }
        });
    }

    // Hapus Tiket
    function deleteTicket(id) {
        Swal.fire({
            title: 'Hapus Tiket?',
            text: "Data akan dihapus permanen dan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                // Kirim permintaan ke backend untuk menghapus tiket
                fetch('ticket-action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete&id=${id}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Berhasil', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                });
            }
        });
    }
</script>

<!-- js tambah tiket -->

<!-- Transaksi -->
<div id="transactions" class="content-section" style="display:none;">
        <!-- Header Transaksi -->
        <section class="content-header">
            <form method="GET" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <label for="status" class="mr-2">Status:</label>
                    <select name="status" class="form-control">
                        <option value="">Semua</option>
                        <option value="pending" <?php echo $filter_status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="success" <?php echo $filter_status == 'success' ? 'selected' : ''; ?>>Sukses</option>
                        <option value="canceled" <?php echo $filter_status == 'canceled' ? 'selected' : ''; ?>>Dibatalkan</option>
                        <option value="failed" <?php echo $filter_status == 'failed' ? 'selected' : ''; ?>>Gagal</option>
                    </select>
                </div>

                <div class="form-group mr-2">
                    <label for="search" class="mr-2">Cari Nama:</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama User" 
                           value="<?php echo htmlspecialchars($search_name); ?>">
                </div>

                <button type="submit" class="btn btn-primary">Filter</button>
                
            </form>
            <a href="download_transaksi.php" class="btn btn-success mb-3" target="_blank">Download PDF</a>

        </section>

        <!-- Content Transaksi -->
        <section class="content">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>Nama Pengguna</th>
                                <th>Nama Tiket</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $trans): ?>
                                    <tr>
                                        <td><?php echo $trans['id']; ?></td>
                                        <td><?php echo htmlspecialchars($trans['order_id']); ?></td>
                                        <td><?php echo htmlspecialchars($trans['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($trans['ticket_name']); ?></td>
                                        <td>Rp <?php echo number_format($trans['total_amount'], 0, ',', '.'); ?></td>
                                        <td>
                                            <?php
                                            switch ($trans['status']) {
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
                                        <td><?php echo date("d-m-Y H:i", strtotime($trans['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data transaksi ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
 </div> <!-- End of transactions -->
    <!-- daftar user  -->
    <div id="users" class="content-section" style="display: none; padding: 15px;">
    <h2 style="font-size: 20px; margin-bottom: 15px; color: #333;">Daftar User</h2>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px;">
        <thead style="background-color: #f5f5f5; color: #333;">
            <tr>
                <th style="padding: 10px; border: 1px solid #ddd;">ID</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Nama</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Role</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php $loop = 0; ?>
                <?php foreach ($users as $user): ?>
                <tr style="background-color: <?= ($loop % 2 === 0) ? '#f9f9f9' : '#fff'; ?>;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($user['id']) ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($user['name']) ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($user['role']) ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <a href="dashboard.php?action=edit&id=<?= htmlspecialchars($user['id']) ?>" style="text-decoration: none; color: #007bff;">Edit</a>
                        |
                        <a href="dashboard.php?action=delete&id=<?= htmlspecialchars($user['id']) ?>" style="text-decoration: none; color: red;" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                    </td>
                </tr>
                <?php $loop++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="padding: 10px; border: 1px solid #ddd; text-align: center; color: #777;">
                        Tidak ada data user.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])): 
    // Ambil data user yang akan diedit
    $userId = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userToEdit):
?>
    <div id="edit-form" class="content-section" style="display: block; padding: 15px;">
        <h2>Edit User</h2>
        <form method="POST" action="dashboard.php?action=edit&id=<?= htmlspecialchars($userToEdit['id']) ?>">
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($userToEdit['name']) ?>" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($userToEdit['email']) ?>" required>
            <br>
            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="user" <?= $userToEdit['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $userToEdit['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
            <br>
            <button type="submit">Update</button>
        </form>
    </div>
<?php 
    else:
        echo "<div class='alert alert-danger'>User tidak ditemukan!</div>";
    endif;
endif; 
?>

    <!-- daftar user  -->

</div>

<!-- Footer -->
<footer class="main-footer text-center">
    <strong>Copyright &copy; 2024 <a href="#">Safera</a></strong>
    All rights reserved.
</footer>

<!-- Scripts -->


<script>
    function showContent(section) {
        // Hide all sections
        document.querySelectorAll('.content-section').forEach(function(el) {
            el.style.display = 'none';
        });

        // Show the selected section
        document.getElementById(section).style.display = 'block';

        // Update the page title
        const titles = {
            dashboard: 'Dashboard',
            tickets: 'Tipe Tiket',
            transactions: 'Daftar Transaksi',
            users: 'Daftar User'
        };
        document.getElementById('content-title').innerText = titles[section];

        // Remove 'active' class from all links
        document.querySelectorAll('.nav-link').forEach(function(link) {
            link.classList.remove('active');
        });

        // Add 'active' class to the clicked link
        event.currentTarget.classList.add('active');
    }
</script>
<!-- AdminLTE JS -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- AdminLTE JS -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

<script>
    // Inisialisasi fungsi pushmenu AdminLTE
    $(document).ready(function() {
        $('[data-widget="pushmenu"]').PushMenu();
    });
</script>
<script src="../js/admin.js"></script>
</body>
</html>
