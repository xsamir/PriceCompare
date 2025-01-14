<?php
require_once('../includes/auth.php');
require_once('../config/config.php');
require_once('../includes/functions.php');

$price_comparison = new PriceComparison();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'website_name' => $_POST['website_name'],
        'amazon_api_key' => $_POST['amazon_api_key'],
        'aliexpress_api_key' => $_POST['aliexpress_api_key'],
        'openai_api_key' => $_POST['openai_api_key']
    ];
    
    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $logo_path = handleImageUpload($_FILES['logo'], 'logos');
        if ($logo_path) {
            $settings['logo_path'] = $logo_path;
        }
    }
    
    $price_comparison->updateSettings($settings);
    header('Location: settings.php?updated=1');
    exit;
}

$current_settings = $price_comparison->getSettings();
?>

<?php include('includes/header.php'); ?>

<div class="admin-container">
    <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
        Settings updated successfully!
    </div>
    <?php endif; ?>
    
    <div class="admin-card">
        <h2>Website Settings</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="website_name">Website Name</label>
                <input type="text" id="website_name" name="website_name" 
                       value="<?php echo htmlspecialchars($current_settings['website_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="logo">Website Logo</label>
                <?php if ($current_settings['logo_path']): ?>
                <div class="current-logo">
                    <img src="<?php echo htmlspecialchars($current_settings['logo_path']); ?>" 
                         alt="Current Logo" style="max-width: 200px;">
                </div>
                <?php endif; ?>
                <input type="file" id="logo" name="logo" accept="image/*">
            </div>
            
            <div class="form-group">
                <label for="amazon_api_key">Amazon API Key</label>
                <input type="password" id="amazon_api_key" name="amazon_api_key" 
                       value="<?php echo htmlspecialchars($current_settings['amazon_api_key']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="aliexpress_api_key">AliExpress API Key</label>
                <input type="password" id="aliexpress_api_key" name="aliexpress_api_key" 
                       value="<?php echo htmlspecialchars($current_settings['aliexpress_api_key']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="openai_api_key">OpenAI API Key</label>
                <input type="password" id="openai_api_key" name="openai_api_key" 
                       value="<?php echo htmlspecialchars($current_settings['openai_api_key']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>
