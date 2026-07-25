<?php
/**
 * Global Delivered Logistics - Role-Based Access Control Middleware
 * 
 * Restricts access to routes based on user roles and permissions.
 */

namespace App\Middleware;

class RoleMiddleware
{
    private array $roles;

    public function __construct(...$roles)
    {
        $this->roles = $roles;
    }

    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['user_id'])) {
            redirect('/login');
            return;
        }
        
        $db = \App\Core\Database::getInstance();
        $user = $db->fetch(
            "SELECT u.*, r.slug as role_slug 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ? AND u.is_active = 1",
            [$_SESSION['user_id']]
        );
        
        if (!$user) {
            session_destroy();
            redirect('/login');
            return;
        }
        
        if (!in_array($user->role_slug, $this->roles) && !in_array('*', $this->roles)) {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($isAjax) {
                json_response([
                    'success' => false,
                    'message' => 'Forbidden. Insufficient permissions.',
                ], 403);
            }
            
            http_response_code(403);
            require VIEWS_PATH . '/errors/403.php';
            exit;
        }
    }

    /**
     * Check if user has a specific permission
     */
    public static function hasPermission(string $permissionSlug): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['user_id'])) return false;
        
        $db = \App\Core\Database::getInstance();
        
        // Super admin has all permissions
        $role = $db->fetchColumn(
            "SELECT r.slug FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?",
            [$_SESSION['user_id']]
        );
        
        if ($role === 'super_admin') return true;
        
        // Check permission
        $count = $db->fetchColumn(
            "SELECT COUNT(*) 
             FROM role_permissions rp
             JOIN permissions p ON rp.permission_id = p.id
             JOIN users u ON u.role_id = rp.role_id
             WHERE u.id = ? AND p.slug = ?",
            [$_SESSION['user_id'], $permissionSlug]
        );
        
        return $count > 0;
    }
}
