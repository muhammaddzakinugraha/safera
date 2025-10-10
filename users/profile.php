<?php
session_start();
include '../config/db.php'; // Koneksi database

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    header('Location: login.php'); // Redirect jika bukan user biasa
    exit();
}

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Ambil data user dari database
    $query = "SELECT name, email, role FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" type="image/png" href="../assets/logo.png" />
    <link rel="stylesheet" href="../css/profile.css" />
</head>
<body>
    <a href="javascript:history.back()" class="back-button">&larr; Back</a>
    <div class="content-container">
        <!-- Notification Section -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="notification success">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="notification error">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="profile-container">
            <h1>Profile</h1>

            <!-- Update Profile Form -->
            <form action="update_profile.php" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <input type="text" id="role" value="<?php echo htmlspecialchars($user['role']); ?>" readonly>
                </div>
                <button type="submit" name="update_profile">Update Profile</button>
            </form>

            <h2>Change Password</h2>
            <form action="change_password.php" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password">Change Password</button>
            </form>
            <a href="../logout.php">Login kembali</a>
        </div>
    </div>
</body>
</html>
