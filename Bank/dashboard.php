<?php
include 'config.php';

// VULNERABLE DASHBOARD - No authentication check
// Anyone can access this if they know the URL

// Display user info without proper validation
$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Guest';
$account_number = $_SESSION['account_number'] ?? 'N/A';
$balance = $_SESSION['balance'] ?? 0;
$is_admin = $_SESSION['is_admin'] ?? 0;

// Get recent transactions - SQL INJECTION VULNERABILITY
$sql = "SELECT * FROM transactions WHERE from_account = '$account_number' OR to_account = '$account_number' ORDER BY transaction_date DESC LIMIT 10";
$transactions = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SecureBank</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏦</text></svg>">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🏦 SecureBank Dashboard</h1>
            <p class="subtitle">Welcome back, <?php echo htmlspecialchars($username); ?>!</p>
        </header>
        
        <main>
            <div class="user-info">
                <div class="account-summary">
                    <h2>Account Summary</h2>
                    <div class="balance-display">$<?php echo number_format($balance, 2); ?></div>
                    <div class="account-number">Account: <?php echo htmlspecialchars($account_number); ?></div>
                </div>
            </div>
            
            <div class="actions">
                <a href="transfer.php" class="action-card">
                    <h3>💸 Transfer Money</h3>
                    <p>Send money to other accounts</p>
                </a>
                
                <a href="view_transactions.php" class="action-card">
                    <h3>📊 Transaction History</h3>
                    <p>View all your transactions</p>
                </a>
                
                <a href="profile.php" class="action-card">
                    <h3>👤 Profile Settings</h3>
                    <p>Manage your account details</p>
                </a>
                
                <?php if ($is_admin): ?>
                <a href="admin.php" class="action-card admin-card">
                    <h3>⚙️ Admin Panel</h3>
                    <p>System administration</p>
                </a>
                <?php endif; ?>
                
                <a href="logout.php" class="action-card logout-card">
                    <h3>🚪 Sign Out</h3>
                    <p>Secure logout from your account</p>
                </a>
            </div>
            
            <div class="card recent-transactions">
                <h3>📈 Recent Transactions</h3>
                <?php if ($transactions->num_rows > 0): ?>
                <div class="transactions-list">
                    <?php while ($row = $transactions->fetch_assoc()): ?>
                    <div class="transaction-item">
                        <div class="transaction-details">
                            <div class="transaction-accounts">
                                <span class="from-account"><?php echo htmlspecialchars($row['from_account']); ?></span>
                                <span class="arrow">→</span>
                                <span class="to-account"><?php echo htmlspecialchars($row['to_account']); ?></span>
                            </div>
                            <div class="transaction-description"><?php echo htmlspecialchars($row['description']); ?></div>
                            <div class="transaction-date"><?php echo date('M j, Y g:i A', strtotime($row['transaction_date'])); ?></div>
                        </div>
                        <div class="transaction-amount <?php echo $row['from_account'] == $account_number ? 'negative' : ''; ?>">
                            <?php echo $row['from_account'] == $account_number ? '-' : '+'; ?>$<?php echo number_format($row['amount'], 2); ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <p class="no-transactions">No recent transactions found.</p>
                <?php endif; ?>
            </div>
        </main>
        
    </div>
</body>
</html>
