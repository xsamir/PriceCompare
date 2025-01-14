<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Price Comparison</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <nav class="admin-nav">
        <div class="nav-brand">
            Price Comparison Admin
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="login.php?logout=1">Logout</a></li>
        </ul>
    </nav>
    <div class="admin-container">