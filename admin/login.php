<?php
require_once('../config/config.php');
require_once('../includes/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Add proper authentication logic here
    if ($username === 'admin' && password_verify($password, 'your_hashed_password')) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h2>Admin Login</h2>
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

# admin/dashboard.php
<?php
require_once('../includes/auth.php');
require_once('../config/config.php');
require_once('../includes/functions.php');

$price_comparison = new PriceComparison();
$total_products = $price_comparison->getTotalProducts();
$total_categories = $price_comparison->getTotalCategories();
$recent_updates = $price_comparison->getRecentUpdates();
?>

<?php include('includes/header.php'); ?>

<div class="dashboard-container">
    <div class="dashboard-stats">
        <div class="stat-box">
            <h3>Total Products</h3>
            <p><?php echo $total_products; ?></p>
        </div>
        <div class="stat-box">
            <h3>Categories</h3>
            <p><?php echo $total_categories; ?></p>
        </div>
    </div>
    
    <div class="recent-updates">
        <h3>Recent Price Updates</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Platform</th>
                    <th>Old Price</th>
                    <th>New Price</th>
                    <th>Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_updates as $update): ?>
                <tr>
                    <td><?php echo htmlspecialchars($update['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($update['platform']); ?></td>
                    <td><?php echo number_format($update['old_price'], 2); ?></td>
                    <td><?php echo number_format($update['new_price'], 2); ?></td>
                    <td><?php echo date('Y-m-d H:i', strtotime($update['updated_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
