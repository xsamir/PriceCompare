<?php
require_once(__DIR__ . '/../config/database.php');

class PriceComparison {
    private $db;
    private $amazon_api;
    private $aliexpress_api;
    
    public function __construct() {
        $this->db = new Database();
        $this->initializeAPIs();
    }
    
    private function initializeAPIs() {
        // Initialize API clients
        $this->amazon_api = new AmazonAPI(AMAZON_API_KEY);
        $this->aliexpress_api = new AliExpressAPI(ALIEXPRESS_API_KEY);
    }
    
    public function getCategories() {
        $sql = "SELECT * FROM categories WHERE status = 1 ORDER BY name";
        $result = $this->db->query($sql);
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }
    
    public function getProducts($category_id = null, $limit = 10, $offset = 0) {
        $where = $category_id ? "WHERE p.category_id = " . (int)$category_id : "";
        
        $sql = "SELECT p.*, c.name as category_name, 
                MIN(pr.price) as min_price, 
                MAX(pr.price) as max_price 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN prices pr ON p.id = pr.product_id 
                $where 
                GROUP BY p.id 
                ORDER BY p.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $result = $this->db->query($sql);
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $row['prices'] = $this->getProductPrices($row['id']);
            $products[] = $row;
        }
        
        return $products;
    }
    
    private function getProductPrices($product_id) {
        $sql = "SELECT * FROM prices WHERE product_id = $product_id ORDER BY price ASC";
        $result = $this->db->query($sql);
        
        $prices = [];
        while ($row = $result->fetch_assoc()) {
            $prices[] = $row;
        }
        
        return $prices;
    }
    
    public function updatePrices() {
        $products = $this->getProductsForUpdate();
        
        foreach ($products as $product) {
            // Get prices from APIs
            $amazon_price = $this->amazon_api->getPrice($product['name']);
            $aliexpress_price = $this->aliexpress_api->getPrice($product['name']);
            
            // Verify with ChatGPT
            $gpt_verified = $this->verifyWithGPT([
                'product' => $product,
                'amazon_price' => $amazon_price,
                'aliexpress_price' => $aliexpress_price
            ]);
            
            if ($gpt_verified) {
                $this->updateProductPrices($product['id'], $amazon_price, $aliexpress_price);
            }
        }
    }
    
    private function verifyWithGPT($data) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . OPENAI_API_KEY,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Verify product prices and ensure they are reasonable.'
                    ],
                    [
                        'role' => 'user',
                        'content' => json_encode($data)
                    ]
                ]
            ])
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        $result = json_decode($response, true);
        return isset($result['choices'][0]['message']['content']);
    }
}