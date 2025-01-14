<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/functions.php');

// Set maximum execution time for long-running updates
set_time_limit(3600); // 1 hour

try {
    $price_comparison = new PriceComparison();
    $price_comparison->updatePrices();
    
    // Log successful update
    file_put_contents(
        __DIR__ . '/cron.log',
        date('Y-m-d H:i:s') . " - Price update completed successfully\n",
        FILE_APPEND
    );
} catch (Exception $e) {
    // Log error
    file_put_contents(
        __DIR__ . '/cron.log',
        date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n",
        FILE_APPEND
    );
}
