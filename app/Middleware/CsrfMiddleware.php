<?php
/**
 * Global Delivered Logistics - CSRF Protection Middleware
 * 
 * Protects against Cross-Site Request Forgery attacks.
 */

namespace App\Middleware;

class CsrfMiddleware
{
    public function handle(): void
    {
        // Skip CSRF check for certain HTTP methods
        if (in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD', 'OPTIONS'])) {
            return;
        }
        
        // Skip for API routes with token auth
        if (str_starts_with($_SERVER['REQUEST_URI'], '/api/')) {
            return;
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = $_POST['_csrf_token'] ?? 
                 $_SERVER['HTTP_X_CSRF_TOKEN'] ?? 
                 $_SERVER['HTTP_X_XSRF_TOKEN'] ?? '';
        
        $expectedToken = $_SESSION['_csrf_token'] ?? '';
        
        if (empty($expectedToken) || empty($token) || !hash_equals($expectedToken, $token)) {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($isAjax) {
                json_response([
                    'success' => false,
                    'message' => 'Invalid or expired CSRF token.',
                ], 419);
            }
            
            http_response_code(419);
            require VIEWS_PATH . '/errors/419.php';
            exit;
        }
        
        // Regenerate token for extra security
        if (!empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}
