<?php
/**
 * Global Delivered Logistics - Authentication Middleware
 * 
 * Verifies user authentication and session validity.
 */

namespace App\Middleware;

class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $isAuthenticated = !empty($_SESSION['user_id']);
        
        // Check for API token
        if (!$isAuthenticated) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? 
                         $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
            
            if (preg_match('/Bearer\s+(.+)$/i', $authHeader, $matches)) {
                $token = $matches[1];
                $isAuthenticated = $this->validateApiToken($token);
            }
        }
        
        if (!$isAuthenticated) {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($isAjax || str_starts_with($_SERVER['REQUEST_URI'], '/api/')) {
                json_response([
                    'success' => false,
                    'message' => 'Unauthenticated. Please login first.',
                ], 401);
            }
            
            // Store intended URL
            $requestUri = $_SERVER['REQUEST_URI'];
            $basePath = defined('BASE_URL') ? parse_url(BASE_URL, PHP_URL_PATH) : '';
            if ($basePath && str_starts_with($requestUri, $basePath)) {
                $requestUri = substr($requestUri, strlen($basePath));
            }
            $_SESSION['_intended_url'] = $requestUri;
            
            // Redirect to login
            $loginUrl = rtrim(defined('BASE_URL') ? BASE_URL : (getenv('APP_URL') ?: '/'), '/') . '/login';
            header('Location: ' . $loginUrl);
            exit;
        }
        
        // Check if user is active
        $db = \App\Core\Database::getInstance();
        $user = $db->fetch("SELECT id, is_active FROM users WHERE id = ?", [$_SESSION['user_id']]);
        
        if (!$user || !$user->is_active) {
            session_destroy();
            
            if (is_ajax()) {
                json_response(['success' => false, 'message' => 'Account deactivated.'], 403);
            }
            
            redirect('/login');
        }
    }

    /**
     * Validate API token
     */
    private function validateApiToken(string $token): bool
    {
        $db = \App\Core\Database::getInstance();
        $user = $db->fetch(
            "SELECT id FROM users WHERE remember_token = ? AND is_active = 1",
            [$token]
        );
        
        if ($user) {
            $_SESSION['user_id'] = $user->id;
            return true;
        }
        
        return false;
    }
}
