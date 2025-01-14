<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Comparison - Compare Prices from Amazon.sa and AliExpress</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="logo">
                <a href="/">
                    <img src="/assets/uploads/<?php echo $settings['logo_path']; ?>" 
                         alt="<?php echo htmlspecialchars($settings['website_name']); ?>">
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="/">Home</a></li>
                    <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="/?category=<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="site-main">