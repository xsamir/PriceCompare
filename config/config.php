<?php
session_start();

define('SITE_URL', 'https://your-domain.com');
define('ADMIN_EMAIL', 'admin@your-domain.com');

// API Keys
define('AMAZON_API_KEY', 'your_amazon_api_key');
define('ALIEXPRESS_API_KEY', 'your_aliexpress_api_key');
define('OPENAI_API_KEY', 'your_openai_api_key');

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/');
