<?php
include 'config.php';

// VULNERABLE ADMIN PANEL - Multiple critical vulnerabilities

// No proper authentication check - just checking session
$is_admin = $_SESSION['is_admin'] ?? 0;

if (!$is_admin) {
    die("Access denied!");
}

// SQL INJECTION VULNERABILITY - User input directly in query
$action = $_GET['action'] ?? 'list';
$user_id = $_GET['user_id'] ?? '';

if ($action == 'delete' && $user_id) {
    // SQL INJECTION VULNERABILITY
    $sql = "DELETE FROM users WHERE id = $user_id";
    $conn->query($sql);
    $message = "User deleted successfully!";
}

if ($action == 'reset_password' && $user_id) {
    $new_password = $_POST['new_password'] ?? 'password123';
    // SQL INJECTION VULNERABILITY
    $sql = "UPDATE users SET password = '$new_password' WHERE id = $user_id";
    $conn->query($sql);
    $message = "Password reset successfully!";
}

// Get all users - SQL INJECTION VULNERABILITY
$sql = "SELECT * FROM users";
$users = $conn->query($sql);

// Get all transactions - SQL INJECTION VULNERABILITY
$sql2 = "SELECT * FROM transactions ORDER BY transaction_date DESC LIMIT 100";
$transactions = $conn->query($sql2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Vulnerable Banking App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        
        <?php if (isset($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="admin-actions">
            <a href="?action=list" class="btn">View Users</a>
            <a href="?action=transactions" class="btn">View All Transactions</a>
            <a href="?action=logs" class="btn">View Admin Logs</a>
        </div>
        
        <?php if ($action == 'list'): ?>
        <h2>All Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th> <!-- Should never display passwords -->
                <th>Email</th>
                <th>Full Name</th>
                <th>Account Number</th>
                <th>Balance</th>
                <th>Is Admin</th>
                <th>Actions</th>
            </tr>
            <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['password']; ?></td> <!-- XSS VULNERABILITY -->
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['full_name']; ?></td>
                <td><?php echo $user['account_number']; ?></td>
                <td>$<?php echo number_format($user['balance'], 2); ?></td>
                <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <a href="?action=delete&user_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    <a href="?action=reset_password&user_id=<?php echo $user['id']; ?>">Reset Password</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php endif; ?>
        
        <?php if ($action == 'transactions'): ?>
        <h2>All Transactions</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>From Account</th>
                <th>To Account</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
                <th>IP Address</th>
            </tr>
            <?php while ($transaction = $transactions->fetch_assoc()): ?>
            <tr>
                <td><?php echo $transaction['id']; ?></td>
                <td><?php echo $transaction['from_account']; ?></td>
                <td><?php echo $transaction['to_account']; ?></td>
                <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                <td><?php echo $transaction['description']; ?></td> <!-- XSS VULNERABILITY -->
                <td><?php echo $transaction['transaction_date']; ?></td>
                <td><?php echo $transaction['ip_address']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php endif; ?>
        
        <?php if ($action == 'reset_password' && $user_id): ?>
        <h2>Reset Password for User ID: <?php echo $user_id; ?></h2>
        <form method="POST">
            <div class="form-group">
                <label>New Password:</label>
                <input type="text" name="new_password" value="password123" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        <?php endif; ?>
        
        <p><a href="dashboard.php">Back to Dashboard</a></p>
        
    </div>
</body>
</html>
