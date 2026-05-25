<?php
$host = 'localhost';
$username = 'root';
$password = 'Password@123'; // Updated with user password
$dbname = 'authentication_system';

try {
    // Connect to MySQL without specifying a database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbname' created or already exists.<br>";

    // Select the database
    $pdo->exec("USE `$dbname`");

    // Create Users Table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(255) NOT NULL,
        address TEXT NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";

    $pdo->exec($sql);
    echo "Table 'users' created or already exists.<br>";
    echo "<strong>Setup complete!</strong> You can now use the <a href='register.php'>Registration Page</a>.";

} catch (PDOException $e) {
    die("Database Initialization Failed: " . $e->getMessage());
}
?>
