# admin/dashboard.php
<?php
require_once('../config/config.php');
require_once('../includes/functions.php');
require_once('includes/auth.php');

$price_comparison = new PriceComparison();

// Get dashboard statistics
$total_products = $price_comparison->getProductCount();
$total_categories = $price_comparison->getCategoryCount();
$recent_updates = $price_comparison->getRecentPriceUpdates();
?>

<?php include('includes/header.php'); ?>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Total Products</h3>
        <p class="stat-number"><?php echo $total_products; ?></p>
    </div>
    <div class="stat-card">
        <h3>Categories</h3>
        <p class="stat-number"><?php echo $total_categories; ?></p>
    </div>
</div>

<div class="recent-updates">
    <h2>Recent Price Updates</h2>
    <table class="admin-table">
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

<?php include('includes/footer.php'); ?>