<?php
include 'config.php';

// VULNERABLE LOGIN PAGE - Multiple security issues

if ($_POST) {
    $username = $_POST['username']; // No input validation
    $password = $_POST['password']; // No input validation
    
    // SQL INJECTION VULNERABILITY - Direct concatenation
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Store sensitive data in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['account_number'] = $user['account_number'];
        $_SESSION['balance'] = $user['balance'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        // No session regeneration after login
        // No proper session timeout
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureBank - Login Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏦</text></svg>">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🏦 SecureBank</h1>
            <p class="subtitle">Your Trusted Financial Partner</p>
        </header>
        
        <main class="card">
            <h2>Sign In to Your Account</h2>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="login-btn">Sign In</button>
            </form>
            
            <div class="auth-links">
                <p>Don't have an account? <a href="register.php" class="link">Create one here</a></p>
            </div>
        </main>
        
    </div>
</body>
</html>
