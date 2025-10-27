<?php
include 'config.php';

// VULNERABLE PROFILE PAGE - SQL Injection and XSS vulnerabilities

$user_id = $_SESSION['user_id'] ?? null;

if ($_POST) {
    $full_name = $_POST['full_name']; // No validation
    $email = $_POST['email']; // No validation
    
    // SQL INJECTION VULNERABILITY
    $sql = "UPDATE users SET full_name = '$full_name', email = '$email' WHERE id = $user_id";
    $conn->query($sql);
    
    $success = "Profile updated successfully!";
}

// Get user data - SQL INJECTION VULNERABILITY
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile - Vulnerable Banking App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" value="<?php echo $user['username']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Account Number:</label>
                <input type="text" value="<?php echo $user['account_number']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Balance:</label>
                <input type="text" value="$<?php echo number_format($user['balance'], 2); ?>" readonly>
            </div>
            
            <button type="submit">Update Profile</button>
        </form>
        
        <p><a href="dashboard.php">Back to Dashboard</a></p>
        
    </div>
</body>
</html>
