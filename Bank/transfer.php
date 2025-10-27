<?php
include 'config.php';

// VULNERABLE TRANSFER PAGE - Multiple security issues

if ($_POST) {
    $to_account = $_POST['to_account']; // No validation
    $amount = $_POST['amount']; // No validation
    $description = $_POST['description']; // No validation
    $from_account = $_SESSION['account_number'];
    
    // No CSRF protection
    // No rate limiting
    // No amount validation
    
    // SQL INJECTION VULNERABILITY - Check if recipient exists
    $check_sql = "SELECT * FROM users WHERE account_number = '$to_account'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        // SQL INJECTION VULNERABILITY - Update sender balance
        $update_sender = "UPDATE users SET balance = balance - $amount WHERE account_number = '$from_account'";
        $conn->query($update_sender);
        
        // SQL INJECTION VULNERABILITY - Update recipient balance
        $update_recipient = "UPDATE users SET balance = balance + $amount WHERE account_number = '$to_account'";
        $conn->query($update_recipient);
        
        // SQL INJECTION VULNERABILITY - Insert transaction record
        $ip = $_SERVER['REMOTE_ADDR'];
        $insert_sql = "INSERT INTO transactions (from_account, to_account, amount, description, ip_address) VALUES ('$from_account', '$to_account', '$amount', '$description', '$ip')";
        $conn->query($insert_sql);
        
        $success = "Transfer successful!";
        
        // Update session balance
        $_SESSION['balance'] -= $amount;
    } else {
        $error = "Recipient account not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Money - SecureBank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏦</text></svg>">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>💸 Transfer Money</h1>
            <p class="subtitle">Send money securely to other accounts</p>
        </header>
        
        <main>
            <div class="card">
                <h2>New Transfer</h2>
                
                <?php if (isset($success)): ?>
                    <div class="success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="transfer-form">
                    <div class="form-group">
                        <label for="to_account">Recipient Account Number</label>
                        <input type="text" id="to_account" name="to_account" placeholder="Enter recipient's account number" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="amount">Transfer Amount</label>
                        <input type="number" id="amount" name="amount" step="0.01" min="0.01" placeholder="0.00" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description" placeholder="What is this transfer for?" required>
                    </div>
                    
                    <button type="submit" class="transfer-btn">💸 Send Transfer</button>
                </form>
            </div>
            
            <div class="card account-info">
                <h3>📊 Account Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">From Account:</span>
                        <span class="info-value"><?php echo $_SESSION['account_number']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Available Balance:</span>
                        <span class="info-value balance">$<?php echo number_format($_SESSION['balance'], 2); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="navigation">
                <a href="dashboard.php" class="btn">← Back to Dashboard</a>
            </div>
        </main>
        
    </div>
</body>
</html>
