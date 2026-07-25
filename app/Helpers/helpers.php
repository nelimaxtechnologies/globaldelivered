<?php
/**
 * Global Delivered Logistics - Environment Helper
 * Loads environment variables from .env file
 */

if (!function_exists('env')) {
    function env($key, $default = null) {
        static $env = null;
        
        if ($env === null) {
            $env = [];
            $envFile = dirname(__DIR__) . '/.env';
            
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '#')) continue;
                    
                    $parts = explode('=', $line, 2);
                    if (count($parts) === 2) {
                        $env[trim($parts[0])] = trim($parts[1]);
                    }
                }
            }
        }
        
        return $env[$key] ?? $default;
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return rtrim(env('APP_URL', 'http://localhost/globaldelivered'), '/') . '/public/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        return rtrim(defined('BASE_URL') ? BASE_URL : '/', '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('admin_asset')) {
    function admin_asset($path) {
        return rtrim(env('APP_URL', 'http://localhost/globaldelivered'), '/') . '/public/admin/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return dirname(__DIR__) . '/storage/' . ltrim($path, '/');
    }
}

if (!function_exists('view_path')) {
    function view_path($path = '') {
        return dirname(__DIR__) . '/app/Views/' . ltrim($path, '/');
    }
}

if (!function_exists('config')) {
    function config($key, $default = null) {
        $keys = explode('.', $key);
        $group = array_shift($keys);
        $configFile = dirname(__DIR__) . '/config/' . $group . '.php';
        
        if (!file_exists($configFile)) return $default;
        
        $config = require $configFile;
        
        foreach ($keys as $k) {
            if (!isset($config[$k])) return $default;
            $config = $config[$k];
        }
        
        return $config;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('csrf_meta')) {
    function csrf_meta() {
        return '<meta name="csrf-token" content="' . csrf_token() . '">';
    }
}

if (!function_exists('old')) {
    function old($field, $default = '') {
        return $_SESSION['_old_input'][$field] ?? $default;
    }
}

if (!function_exists('flash')) {
    function flash($key, $value = null) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return;
        }
        
        $val = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $val;
    }
}

if (!function_exists('has_flash')) {
    function has_flash($key) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['_flash'][$key]);
    }
}

if (!function_exists('is_ajax')) {
    function is_ajax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}

if (!function_exists('json_response')) {
    function json_response($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if (!function_exists('redirect')) {
    function redirect($url, $statusCode = 302) {
        if (strpos($url, 'http') !== 0 && strpos($url, '//') !== 0) {
            $basePath = defined('BASE_URL') ? parse_url(BASE_URL, PHP_URL_PATH) : '';
            if ($basePath && str_starts_with($url, $basePath)) {
                $url = substr($url, strlen($basePath));
            }
            $url = rtrim(defined('BASE_URL') ? BASE_URL : '', '/') . '/' . ltrim($url, '/');
        }
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
}

if (!function_exists('back')) {
    function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
        redirect($referer);
    }
}

if (!function_exists('abort')) {
    function abort($code = 404, $message = 'Not Found') {
        http_response_code($code);
        $viewFile = VIEWS_PATH . "/errors/{$code}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<h1>{$code} - {$message}</h1>";
        }
        exit;
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amount, $currency = 'USD') {
        $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'NGN' => '₦', 'KES' => 'KSh', 'ZAR' => 'R'];
        $symbol = $symbols[$currency] ?? '$';
        return $symbol . number_format((float)$amount, 2);
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'M d, Y') {
        if (empty($date)) return '-';
        return date($format, strtotime($date));
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime) {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        $intervals = [
            31536000 => 'year',
            2592000  => 'month',
            604800   => 'week',
            86400    => 'day',
            3600     => 'hour',
            60       => 'minute',
            1        => 'second'
        ];
        
        foreach ($intervals as $seconds => $label) {
            $count = floor($diff / $seconds);
            if ($count >= 1) {
                return $count . ' ' . $label . ($count > 1 ? 's' : '') . ' ago';
            }
        }
        return 'just now';
    }
}

if (!function_exists('generate_tracking_number')) {
    function generate_tracking_number() {
        $prefix = TRACKING_PREFIX;
        $code = '';
        for ($i = 0; $i < TRACKING_LENGTH; $i++) {
            $code .= random_int(0, 9);
        }
        return $prefix . $code;
    }
}

if (!function_exists('sanitize')) {
    function sanitize($input) {
        if (is_array($input)) {
            return array_map('sanitize', $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('generate_slug')) {
    function generate_slug($string) {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}

if (!function_exists('is_valid_email')) {
    function is_valid_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('canonical_url')) {
    function canonical_url(): string {
        if (!defined('BASE_URL')) return $_SERVER['REQUEST_URI'] ?? '/';
        $basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
        $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '/';
        return rtrim(BASE_URL, '/') . '/' . ltrim(str_replace($basePath, '', $requestPath), '/');
    }
}

if (!function_exists('currentPage')) {
    function currentPage($path): bool {
        if (!isset($_SERVER['REQUEST_URI'])) return false;
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');
        $path = '/' . trim($path, '/');
        // Exact match or starts with (for nested routes)
        return $uri === $path || str_starts_with($uri, $path . '/');
    }
}

if (!function_exists('log_activity')) {
    function log_activity($action, $entityType = null, $entityId = null, $oldValues = null, $newValues = null) {
        try {
            $db = \App\Core\Database::getInstance();
            $stmt = $db->prepare(
                "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent, request_method, request_url) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $_SESSION['user_id'] ?? null,
                $action,
                $entityType,
                $entityId,
                $oldValues ? json_encode($oldValues) : null,
                $newValues ? json_encode($newValues) : null,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                $_SERVER['REQUEST_METHOD'] ?? null,
                $_SERVER['REQUEST_URI'] ?? null,
            ]);
        } catch (\Exception $e) {
            // Silently fail for audit logging
            error_log("Audit log failed: " . $e->getMessage());
        }
    }
}
