<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="css/signup1.css"> <!-- External CSS -->
    <link rel="icon" type="image/png" href="assets/logo.png" />
</head>
<body>
    <div class="signup-container">
        <h2>Sign up</h2>
        <form action="./backend/register.php" method="POST">
            <div class="input-icon">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" id="confirmPassword" name="confirm" placeholder="Confirm Password" required>
            </div>
            <div class="show-password">
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Lihat Password</label>
            </div>
            <button type="submit" class="signup-btn">Signup</button>
        </form>
        <div class="login-link">
            Have an Account? <a href="login.php">Login now</a>
        </div>
    </div>

    <script>
        const showPasswordCheckbox = document.getElementById('showPassword');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');

        showPasswordCheckbox.addEventListener('change', function () {
            if (this.checked) {
                passwordInput.type = 'text';
                confirmPasswordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
                confirmPasswordInput.type = 'password';
            }
        });
    </script>
</body>
</html>
