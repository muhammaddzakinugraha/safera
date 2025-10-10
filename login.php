<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- Font Awesome Icons -->
<link rel="stylesheet" href="css/login1.css"> <!-- External CSS -->
<link rel="icon" type="image/png" href="assets/logo.png" />
</head>
<body>
    <div class="login-container">
		<img src="assets/logosafera.png" alt="Logo" class="login-logo">
        <form action="backend/login.php" method="POST">
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="show-password">
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="signup-link">
            Don't have an account yet? <a href="signup.php">Signup now</a>
        </div>
    </div>

    <script>
        const showPasswordCheckbox = document.getElementById('showPassword');
        const passwordInput = document.getElementById('password');

        showPasswordCheckbox.addEventListener('change', function() {
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>
</body>
</html>
