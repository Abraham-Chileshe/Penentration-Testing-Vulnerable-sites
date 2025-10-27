<?php
include 'config.php';

// VULNERABLE REGISTRATION PAGE

if ($_POST) {
    $username = $_POST['username']; // No validation
    $password = $_POST['password']; // No validation
    $email = $_POST['email']; // No validation
    $full_name = $_POST['full_name']; // No validation
    
    // Generate account number (predictable)
    $account_number = rand(1000000000, 9999999999);
    
    // SQL INJECTION VULNERABILITY
    $sql = "INSERT INTO users (username, password, email, full_name, account_number) VALUES ('$username', '$password', '$email', '$full_name', '$account_number')";
    
    if ($conn->query($sql)) {
        $success = "Account created successfully! Account Number: $account_number";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - SecureBank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏦</text></svg>">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🏦 SecureBank</h1>
            <p class="subtitle">Join thousands of satisfied customers</p>
        </header>
        
        <main class="card">
            <h2>Create Your Account</h2>
            <p class="form-description">Fill in your details to get started with SecureBank</p>
            
            <?php if (isset($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="register-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Choose a username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="your@email.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" placeholder="Your full name" required>
                    </div>
                </div>
                
                <button type="submit" class="register-btn">🚀 Create Account</button>
            </form>
            
            <div class="auth-links">
                <p>Already have an account? <a href="login.php" class="link">Sign in here</a></p>
            </div>
        </main>
    </div>
</body>
</html>
