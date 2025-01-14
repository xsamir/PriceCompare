# admin/categories.php
<?php
require_once('../config/config.php');
require_once('../includes/functions.php');
require_once('includes/auth.php');

$price_comparison = new PriceComparison();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $price_comparison->addCategory([
                    'name' => $_POST['name'],
                    'slug' => strtolower(str_replace(' ', '-', $_POST['name']))
                ]);
                break;
            
            case 'edit':
                $price_comparison->updateCategory([
                    'id' => $_POST['category_id'],
                    'name' => $_POST['name'],
                    'slug' => strtolower(str_replace(' ', '-', $_POST['name']))
                ]);
                break;
            
            case 'delete':
                $price_comparison->deleteCategory($_POST['category_id']);
                break;
        }
        
        header('Location: categories.php');
        exit;
    }
}

$categories = $price_comparison->getCategories();
?>

<?php include('includes/header.php'); ?>

<div class="admin-card">
    <h2>Add New Category</h2>
    <form method="POST" class="admin-form">
        <input type="hidden" name="action" value="add">
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
</div>

<div class="admin-card">
    <h2>Manage Categories</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['id']; ?></td>
                <td><?php echo htmlspecialchars($category['name']); ?></td>
                <td><?php echo htmlspecialchars($category['slug']); ?></td>
                <td><?php echo $price_comparison->getProductCountByCategory($category['id']); ?></td>
                <td>
                    <button onclick="editCategory(<?php echo $category['id']; ?>)" class="btn btn-sm btn-primary">Edit</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>