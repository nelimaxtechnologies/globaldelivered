<?php
/**
 * Global Delivered Logistics - Application Configuration
 */

return [
    'name' => env('APP_NAME', 'Global Delivered Logistics'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'https://globaldelivered.com'),
    'timezone' => 'UTC',
    'locale' => 'en',
    
    // Encryption
    'key' => env('APP_KEY', 'base64:'.base64_encode(random_bytes(32))),
    'cipher' => 'AES-256-CBC',
    
    // Pagination
    'pagination' => [
        'per_page' => 25,
        'max_per_page' => 100,
    ],
    
    // Upload
    'upload' => [
        'max_size' => 10 * 1024 * 1024, // 10MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv'],
        'path' => storage_path('uploads'),
    ],
    
    // Tracking
    'tracking' => [
        'prefix' => 'GDL',
        'length' => 12,
        'polling_interval' => 5000, // ms
    ],
    
    // Currency
    'currency' => 'USD',
    'currency_symbol' => '$',
    
    // Tax
    'tax_percentage' => 0,
    
    // Default pagination
    'pagination_per_page' => 25,
];
