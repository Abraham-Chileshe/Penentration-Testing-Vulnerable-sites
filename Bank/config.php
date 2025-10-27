<?php
// Vulnerable configuration file - NO SECURITY MEASURES
error_reporting(0); // Hide errors from users (bad practice)

// Database configuration - credentials in plain text
$host = 'localhost';
$dbname = 'bank_database';
$username = 'root';
$password = '';

// Create connection without proper error handling
$conn = new mysqli($host, $username, $password, $dbname);

// No connection error checking
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// No prepared statements will be used - direct SQL injection vulnerability
// No input validation or sanitization
// No CSRF protection
// No rate limiting
// No proper session management

// Start session without proper security settings
session_start();

// No session regeneration
// No secure session cookies
// No session timeout
?>
