<?php
// register_v2.php - Self-contained setup and registration
$host = 'localhost';
$username = 'root';
$password = 'Password@123'; // Updated with user password
$dbname = 'authentication_system';

try {
    // 1. Connect and Setup Database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(255) NOT NULL,
        address TEXT NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 2. Handle Registration Logic
    $message = '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = trim($_POST['fullname'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($fullname) || empty($address) || empty($email) || empty($password)) {
            $error = "All fields are required.";
        } elseif (strlen($fullname) < 3) {
            $error = "Full Name must be at least 3 characters long.";
        } elseif (preg_match('/[0-9]/', $fullname)) {
            $error = "Full Name cannot contain numbers.";
        } elseif (strlen($address) < 5) {
            $error = "Address must be at least 5 characters long.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters long.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (fullname, address, email, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$fullname, $address, $email, $hashed_password]);
                $message = "Registration successful! You can now <a href='login.php'>login</a>.";
            }
        }
    }
} catch (PDOException $e) {
    die("CRITICAL ERROR: " . $e->getMessage() . "<br>Please ensure your MySQL password in this file is correct.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register V2 - Authentication System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><span class="highlight-text">Register</span> (System V2)</h2>
        <p style="color: #22c55e; font-size: 12px; text-align: center; font-weight: 600;">Database: authentication_system is ready.</p>
        <?php if ($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register_v2.php" method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" minlength="3" pattern="^[A-Za-z\s]+$" title="Only letters and spaces are allowed" placeholder="John Doe" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" minlength="5" placeholder="Enter your full address..." required></textarea>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="john@example.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" minlength="6" placeholder="Enter a secure password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
