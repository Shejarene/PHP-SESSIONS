<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Authentication System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page-body">
    <nav class="navbar">
        <div class="nav-brand"><span class="highlight-text">Auth</span>System</div>
        <div class="nav-links">
            <span>Welcome, <span class="highlight-text"><?php echo htmlspecialchars($_SESSION['fullname']); ?></span></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>User <span class="highlight-text">Dashboard</span></h1>
            <p>You are successfully logged in!</p>
        </header>

        <section class="blue-themed-section">
            <h2>Account Information</h2>
            <div class="info-card">
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
            
            <div class="content-box">
                <h3>System Status</h3>
                <p>The authentication system is currently operational. You have secure access to your session data.</p>
            </div>
        </section>
    </div>
</body>
</html>
