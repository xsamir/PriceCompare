# includes/footer.php
        </div> <!-- Close main content container -->
        <footer class="site-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>About Us</h3>
                        <p>We help you compare prices between Amazon.sa and AliExpress to find the best deals.</p>
                    </div>
                    
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul>
                            <li><a href="/">Home</a></li>
                            <?php 
                            $categories = $price_comparison->getCategories();
                            foreach ($categories as $category): 
                            ?>
                            <li>
                                <a href="/?category=<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h3>Contact</h3>
                        <p>Email: <?php echo ADMIN_EMAIL; ?></p>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['website_name']); ?>. All rights reserved.</p>
                </div>
            </div>
        </footer>
        
        <script src="/assets/js/main.js"></script>
    </body>
</html>