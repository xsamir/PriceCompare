<?php
class Cache {
    private $redis;
    private $prefix = 'price_compare:';
    private $default_ttl = 3600; // 1 hour
    
    public function __construct() {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }
    
    public function get($key) {
        $value = $this->redis->get($this->prefix . $key);
        return $value ? json_decode($value, true) : null;
    }
    
    public function set($key, $value, $ttl = null) {
        $ttl = $ttl ?: $this->default_ttl;
        return $this->redis->setex(
            $this->prefix . $key,
            $ttl,
            json_encode($value)
        );
    }
    
    public function delete($key) {
        return $this->redis->del($this->prefix . $key);
    }
    
    public function flush() {
        $keys = $this->redis->keys($this->prefix . '*');
        if (!empty($keys)) {
            return $this->redis->del($keys);
        }
        return true;
    }
}