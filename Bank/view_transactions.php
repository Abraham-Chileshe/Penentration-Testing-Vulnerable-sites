<?php
include 'config.php';

// VULNERABLE TRANSACTION VIEWER - SQL Injection and XSS vulnerabilities

$account_number = $_SESSION['account_number'] ?? '';

// SQL INJECTION VULNERABILITY - Direct user input in query
$search = $_GET['search'] ?? '';
$limit = $_GET['limit'] ?? 50; // No validation

$sql = "SELECT * FROM transactions WHERE from_account = '$account_number' OR to_account = '$account_number'";

if ($search) {
    $sql .= " AND description LIKE '%$search%'";
}

$sql .= " ORDER BY transaction_date DESC LIMIT $limit";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Transactions - Vulnerable Banking App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Transaction History</h1>
        
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search transactions..." value="<?php echo htmlspecialchars($search); ?>">
                <input type="number" name="limit" placeholder="Limit" value="<?php echo htmlspecialchars($limit); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        
        <table>
            <tr>
                <th>From Account</th>
                <th>To Account</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
                <th>IP Address</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['from_account']; ?></td>
                <td><?php echo $row['to_account']; ?></td>
                <td>$<?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo $row['description']; ?></td> <!-- XSS VULNERABILITY - No htmlspecialchars -->
                <td><?php echo $row['transaction_date']; ?></td>
                <td><?php echo $row['ip_address']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <p><a href="dashboard.php">Back to Dashboard</a></p>
        
    </div>
</body>
</html>
