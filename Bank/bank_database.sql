-- Vulnerable Banking Application Database
-- Import this file into phpMyAdmin

CREATE DATABASE IF NOT EXISTS vulnerable_bank;
USE vulnerable_bank;

-- Users table with weak security
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Will store plaintext passwords
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(20) NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin TINYINT(1) DEFAULT 0
);

-- Transactions table
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_account VARCHAR(20) NOT NULL,
    to_account VARCHAR(20) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45)
);

-- Admin logs table (for testing privilege escalation)
CREATE TABLE admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_user VARCHAR(50) NOT NULL,
    action TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert vulnerable test data
INSERT INTO users (username, password, email, full_name, account_number, balance, is_admin) VALUES
('admin', 'admin123', 'admin@bank.com', 'Bank Administrator', '0000000001', 1000000.00, 1),
('john_doe', 'password123', 'john@email.com', 'John Doe', '1234567890', 5000.00, 0),
('jane_smith', 'qwerty', 'jane@email.com', 'Jane Smith', '0987654321', 2500.00, 0),
('bob_wilson', '123456', 'bob@email.com', 'Bob Wilson', '1122334455', 10000.00, 0),
('alice_brown', 'password', 'alice@email.com', 'Alice Brown', '5566778899', 7500.00, 0);

-- Insert some sample transactions
INSERT INTO transactions (from_account, to_account, amount, description, ip_address) VALUES
('1234567890', '0987654321', 500.00, 'Payment for services', '192.168.1.100'),
('0987654321', '1122334455', 250.00, 'Gift money', '192.168.1.101'),
('1122334455', '5566778899', 1000.00, 'Loan repayment', '192.168.1.102');

-- Insert admin logs
INSERT INTO admin_logs (admin_user, action) VALUES
('admin', 'System initialized'),
('admin', 'User john_doe created'),
('admin', 'User jane_smith created');
