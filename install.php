<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Step 1: Configuration form
if (!isset($_POST['step'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Install Price Comparison System</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; }
            input[type="text"], input[type="password"] { width: 100%; padding: 8px; }
            .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
            .alert-info { color: #31708f; background-color: #d9edf7; border-color: #bce8f1; }
            button { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        </style>
    </head>
    <body>
        <h1>Price Comparison System Installation</h1>
        <div class="alert alert-info">
            Please enter your database and website configuration details below.
        </div>
        
        <form method="POST">
            <input type="hidden" name="step" value="1">
            
            <h2>Database Configuration</h2>
            <div class="form-group">
                <label>Database Host:</label>
                <input type="text" name="db_host" value="localhost" required>
            </div>
            <div class="form-group">
                <label>Database Name:</label>
                <input type="text" name="db_name" value="price_comparison" required>
            </div>
            <div class="form-group">
                <label>Database Username:</label>
                <input type="text" name="db_user" required>
            </div>
            <div class="form-group">
                <label>Database Password:</label>
                <input type="password" name="db_pass">
            </div>
            
            <h2>Website Configuration</h2>
            <div class="form-group">
                <label>Website URL (with https://):</label>
                <input type="text" name="site_url" value="https://" required>
            </div>
            <div class="form-group">
                <label>Admin Email:</label>
                <input type="email" name="admin_email" required>
            </div>
            
            <h2>API Configuration</h2>
            <div class="form-group">
                <label>Amazon API Key:</label>
                <input type="text" name="amazon_api" placeholder="Enter your Amazon API key">
            </div>
            <div class="form-group">
                <label>AliExpress API Key:</label>
                <input type="text" name="aliexpress_api" placeholder="Enter your AliExpress API key">
            </div>
            <div class="form-group">
                <label>OpenAI API Key:</label>
                <input type="text" name="openai_api" placeholder="Enter your OpenAI API key">
            </div>
            
            <h2>Admin Account Setup</h2>
            <div class="form-group">
                <label>Admin Username:</label>
                <input type="text" name="admin_user" required>
            </div>
            <div class="form-group">
                <label>Admin Password:</label>
                <input type="password" name="admin_pass" required>
            </div>
            
            <button type="submit">Install System</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Step 2: Installation process
if ($_POST['step'] == '1') {
    try {
        // Collect form data
        $db_host = $_POST['db_host'];
        $db_name = $_POST['db_name'];
        $db_user = $_POST['db_user'];
        $db_pass = $_POST['db_pass'];
        $site_url = rtrim($_POST['site_url'], '/');
        $admin_email = $_POST['admin_email'];
        $amazon_api = $_POST['amazon_api'];
        $aliexpress_api = $_POST['aliexpress_api'];
        $openai_api = $_POST['openai_api'];
        $admin_user = $_POST['admin_user'];
        $admin_pass = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);

        // Create database connection
        $conn = new mysqli($db_host, $db_user, $db_pass);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        if (!$conn->query($sql)) {
            throw new Exception("Error creating database: " . $conn->error);
        }

        // Select the database
        $conn->select_db($db_name);

        // [Create tables code remains the same as in previous version]
        
        // Update database.php
        $database_config = "<?php
define('DB_HOST', '" . addslashes($db_host) . "');
define('DB_USER', '" . addslashes($db_user) . "');
define('DB_PASS', '" . addslashes($db_pass) . "');
define('DB_NAME', '" . addslashes($db_name) . "');

class Database {
    private \$connection;
    
    public function __construct() {
        \$this->connect();
    }
    
    private function connect() {
        \$this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if (\$this->connection->connect_error) {
            die(\"Connection failed: \" . \$this->connection->connect_error);
        }
        
        \$this->connection->set_charset(\"utf8mb4\");
    }
    
    public function getConnection() {
        return \$this->connection;
    }
    
    public function query(\$sql) {
        return \$this->connection->query(\$sql);
    }
    
    public function escape(\$value) {
        return \$this->connection->real_escape_string(\$value);
    }
}";

        // Update config.php
        $main_config = "<?php
session_start();

define('SITE_URL', '" . addslashes($site_url) . "');
define('ADMIN_EMAIL', '" . addslashes($admin_email) . "');

// API Keys
define('AMAZON_API_KEY', '" . addslashes($amazon_api) . "');
define('ALIEXPRESS_API_KEY', '" . addslashes($aliexpress_api) . "');
define('OPENAI_API_KEY', '" . addslashes($openai_api) . "');

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('UPLOAD_PATH', \$_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/');";

        // Write configuration files
        if (file_put_contents('config/database.php', $database_config) === false) {
            throw new Exception("Error writing to database.php");
        }

        if (file_put_contents('config/config.php', $main_config) === false) {
            throw new Exception("Error writing to config.php");
        }

        // Create necessary directories
        $directories = [
            'assets/uploads',
            'assets/uploads/products',
            'assets/uploads/logos'
        ];
        
        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        // Success message
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Installation Complete</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
                .success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 15px; margin-bottom: 20px; }
                pre { background: #f8f9fa; padding: 15px; overflow: auto; }
            </style>
        </head>
        <body>
            <div class='success'>
                <h2>Installation Completed Successfully!</h2>
                <p>Configuration files have been updated:</p>
                <ul>
                    <li>config/database.php</li>
                    <li>config/config.php</li>
                </ul>
                <p>You can now:</p>
                <ul>
                    <li>Access the admin panel at: <a href='admin/login.php'>Admin Login</a></li>
                    <li>View your website at: <a href='index.php'>Homepage</a></li>
                </ul>
                <p><strong>Important:</strong> Please delete this install.php file for security reasons.</p>
            </div>
        </body>
        </html>";
        
    } catch (Exception $e) {
        die("Installation failed: " . $e->getMessage());
    }
}
?>
