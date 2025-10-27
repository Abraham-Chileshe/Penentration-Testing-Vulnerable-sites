# Vulnerable Banking Application

## ⚠️ WARNING: This application is intentionally vulnerable for penetration testing purposes only!

This banking application has been deliberately designed with multiple security vulnerabilities for educational and penetration testing purposes. **DO NOT use this code in production environments.**

## Setup Instructions

### 1. Database Setup
1. Import the `bank_database.sql` file into phpMyAdmin
2. The database will be created with sample data including:
   - Admin user: `admin` / `admin123`
   - Regular users with various account balances
   - Sample transaction history

### 2. Web Server Setup
1. Place all files in your web server directory (e.g., `C:\wamp64\www\PenTest\Bank\`)
2. Ensure PHP and MySQL are running
3. Access the application via `http://localhost/PenTest/Bank/`

## Default Credentials

| Username | Password | Role | Account Number |
|----------|----------|------|----------------|
| admin | admin123 | Admin | 0000000001 |
| john_doe | password123 | User | 1234567890 |
| jane_smith | qwerty | User | 0987654321 |
| bob_wilson | 123456 | User | 1122334455 |
| alice_brown | password | User | 5566778899 |

## Application Features

- User registration and login
- Account dashboard with balance display
- Money transfer between accounts
- Transaction history viewing
- User profile management
- Admin panel for user management
- Debug information display

## 🚨 Security Vulnerabilities

### 1. SQL Injection Vulnerabilities
**Severity: Critical**

- **Location**: All database queries throughout the application
- **Description**: User input is directly concatenated into SQL queries without sanitization
- **Examples**:
  - Login: `SELECT * FROM users WHERE username = '$username' AND password = '$password'`
  - Transfer: `UPDATE users SET balance = balance - $amount WHERE account_number = '$from_account'`
  - Admin: `DELETE FROM users WHERE id = $user_id`

**Test Payloads**:
```sql
-- Login bypass
Username: admin' OR '1'='1' --
Password: anything

-- Extract all users
Username: ' UNION SELECT username, password, email, full_name, account_number, balance, is_admin FROM users --
Password: anything

-- Delete all users
Username: '; DROP TABLE users; --
Password: anything
```

### 2. Cross-Site Scripting (XSS)
**Severity: High**

- **Location**: Multiple pages where user input is displayed without encoding
- **Description**: User input is echoed directly to HTML without proper escaping
- **Examples**:
  - Transaction descriptions in `view_transactions.php`
  - User data in admin panel
  - Search results

**Test Payloads**:
```html
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
<svg onload=alert('XSS')>
```

### 3. Cross-Site Request Forgery (CSRF)
**Severity: High**

- **Location**: All forms (transfer, profile update, admin actions)
- **Description**: No CSRF tokens or validation
- **Impact**: Attackers can perform actions on behalf of authenticated users

**Test Payload**:
```html
<form action="http://localhost/PenTest/Bank/transfer.php" method="POST">
    <input name="to_account" value="attacker_account">
    <input name="amount" value="1000">
    <input name="description" value="CSRF Attack">
</form>
<script>document.forms[0].submit()</script>
```

### 4. Weak Authentication
**Severity: Critical**

- **Description**: Multiple authentication flaws
- **Issues**:
  - Passwords stored in plaintext
  - No password complexity requirements
  - No account lockout after failed attempts
  - No session timeout
  - No proper session management

### 5. Insecure Direct Object References
**Severity: High**

- **Location**: Admin panel, profile pages
- **Description**: User IDs and account numbers can be manipulated
- **Examples**:
  - Access other users' profiles by changing user ID
  - Admin actions without proper authorization checks

### 6. Information Disclosure
**Severity: Medium**

- **Description**: Sensitive information exposed
- **Issues**:
  - Debug information displayed in production
  - SQL queries visible to users
  - Session data exposed
  - Passwords visible in admin panel
  - Error messages reveal system information

### 7. No Input Validation
**Severity: High**

- **Description**: No validation or sanitization of user input
- **Issues**:
  - No length limits on input fields
  - No data type validation
  - No business logic validation (negative amounts, etc.)

### 8. Insecure File Upload (Potential)
**Severity: Medium**

- **Description**: While not implemented, the structure allows for file upload vulnerabilities
- **Risk**: If file upload is added, it would likely lack proper validation

### 9. Session Management Issues
**Severity: High**

- **Description**: Poor session handling
- **Issues**:
  - No session regeneration after login
  - No secure session cookies
  - No session timeout
  - Session data stored in plaintext

### 10. Privilege Escalation
**Severity: Critical**

- **Location**: Admin panel access
- **Description**: Weak admin privilege checking
- **Issues**:
  - Admin status stored in session without proper validation
  - No additional authentication for admin functions
  - Admin actions can be performed by manipulating session data

## Penetration Testing Scenarios

### 1. SQL Injection Testing
1. Try login bypass using SQL injection
2. Extract database schema and data
3. Perform privilege escalation through database manipulation
4. Test blind SQL injection techniques

### 2. XSS Testing
1. Inject malicious scripts in transaction descriptions
2. Test stored XSS in user profiles
3. Test reflected XSS in search functionality
4. Attempt session hijacking through XSS

### 3. Authentication Bypass
1. Try common default credentials
2. Test for SQL injection in login
3. Manipulate session data
4. Test for direct access to protected pages

### 4. Authorization Testing
1. Access admin panel without admin privileges
2. View other users' data by manipulating parameters
3. Perform unauthorized transactions
4. Test for horizontal privilege escalation

### 5. Business Logic Testing
1. Transfer negative amounts
2. Transfer more money than available
3. Test for race conditions in transfers
4. Manipulate account numbers

## Tools for Testing

- **SQLMap**: For automated SQL injection testing
- **Burp Suite**: For web application security testing
- **OWASP ZAP**: For vulnerability scanning
- **Manual Testing**: Browser developer tools for XSS testing

## Remediation Recommendations

1. **Use Prepared Statements**: Replace all dynamic SQL with prepared statements
2. **Input Validation**: Implement comprehensive input validation and sanitization
3. **Output Encoding**: Use `htmlspecialchars()` for all user output
4. **CSRF Protection**: Implement CSRF tokens for all forms
5. **Secure Authentication**: Use password hashing, implement proper session management
6. **Authorization**: Implement proper access controls and privilege checks
7. **Error Handling**: Remove debug information and implement proper error handling
8. **Security Headers**: Implement security headers (CSP, HSTS, etc.)

## Legal Notice

This application is provided for educational and authorized penetration testing purposes only. Users are responsible for ensuring they have proper authorization before testing any systems. The authors are not responsible for any misuse of this application.

## File Structure

```
Bank/
├── index.php              # Redirects to login
├── login.php              # Login page with SQL injection
├── register.php           # Registration page
├── dashboard.php          # User dashboard
├── transfer.php           # Money transfer functionality
├── view_transactions.php  # Transaction history
├── profile.php            # User profile management
├── admin.php              # Admin panel
├── logout.php             # Logout functionality
├── config.php             # Database configuration
├── style.css              # CSS styling
├── bank_database.sql      # Database schema
└── README.md              # This file
```

## Contact

For questions about this vulnerable application or penetration testing techniques, please refer to OWASP guidelines and ethical hacking resources.
