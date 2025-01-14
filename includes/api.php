<?php
class AmazonAPI {
    private $api_key;
    private $base_url = 'https://webservices.amazon.sa/paapi5/';
    
    public function __construct($api_key) {
        $this->api_key = $api_key;
    }
    
    public function getPrice($product_name) {
        // Implement Amazon Product Advertising API
        $endpoint = $this->base_url . 'searchitems';
        
        $params = [
            'Keywords' => $product_name,
            'SearchIndex' => 'All',
            'Resources' => ['Offers.Listings.Price']
        ];
        
        // Make API request and return price data
        return $this->makeRequest($endpoint, $params);
    }
    
    private function makeRequest($endpoint, $params) {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->api_key,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($params)
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($response, true);
    }
}

class AliExpressAPI {
    private $api_key;
    private $base_url = 'https://api.aliexpress.com/v2/';
    
    public function __construct($api_key) {
        $this->api_key = $api_key;
    }
    
    public function getPrice($product_name) {
        // Implement AliExpress API
        $endpoint = $this->base_url . 'search';
        
        $params = [
            'keywords' => $product_name,
            'page_size' => 1
        ];
        
        return $this->makeRequest($endpoint, $params);
    }
    
    private function makeRequest($endpoint, $params) {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint . '?' . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->api_key
            ]
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($response, true);
    }
}