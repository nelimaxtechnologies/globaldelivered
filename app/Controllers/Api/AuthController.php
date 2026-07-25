<?php
/**
 * Global Delivered Logistics - API Auth Controller
 */

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * API Login - returns JWT-like token
     */
    public function login(): void
    {
        $data = $this->getPostData();
        
        $email = sanitize($data['email'] ?? '');
        $password = $data['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $this->error('Email and password are required', 422);
        }
        
        $user = User::verifyPassword($email, $password);
        
        if (!$user) {
            $this->error('Invalid credentials', 401);
        }
        
        // Generate API token
        $token = bin2hex(random_bytes(32));
        $this->db->query("UPDATE users SET remember_token = ?, last_login_at = NOW(), last_login_ip = ? WHERE id = ?",
            [$token, $_SERVER['REMOTE_ADDR'] ?? '', $user->id]);
        
        $this->success([
            'token' => $token,
            'user' => [
                'id' => (int) $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'role' => $user->role_slug,
            ]
        ], 'Login successful');
    }

    /**
     * API Register
     */
    public function register(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min_length:8',
        ];
        
        $validated = $this->validate($data, $rules);
        
        // Check existing
        $exists = $this->db->fetchColumn("SELECT COUNT(*) FROM users WHERE email = ?", [$data['email']]);
        if ($exists > 0) {
            $this->error('Email already registered', 409);
        }
        
        $customerRole = $this->db->fetchColumn("SELECT id FROM roles WHERE slug = 'customer'");
        
        $userId = User::createUser([
            'role_id' => $customerRole,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_active' => 1,
        ]);
        
        $this->success(['user_id' => $userId], 'Registration successful', 201);
    }

    /**
     * Get current user
     */
    public function user(): void
    {
        $user = $this->db->fetch(
            "SELECT u.*, r.name as role_name, r.slug as role_slug
             FROM users u JOIN roles r ON u.role_id = r.id
             WHERE u.id = ?",
            [$_SESSION['user_id']]
        );
        
        if (!$user) {
            $this->error('User not found', 404);
        }
        
        $this->success([
            'id' => (int) $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role_slug,
        ]);
    }

    /**
     * Update profile
     */
    public function updateProfile(): void
    {
        $data = $this->getPostData();
        
        $this->db->query(
            "UPDATE users SET first_name = ?, last_name = ?, phone = ?, updated_at = NOW() WHERE id = ?",
            [$data['first_name'] ?? '', $data['last_name'] ?? '', $data['phone'] ?? '', $_SESSION['user_id']]
        );
        
        $this->success(null, 'Profile updated');
    }

    /**
     * Update password
     */
    public function updatePassword(): void
    {
        $data = $this->getPostData();
        
        $user = $this->db->fetch("SELECT password FROM users WHERE id = ?", [$_SESSION['user_id']]);
        
        if (!password_verify($data['current_password'] ?? '', $user->password)) {
            $this->error('Current password is incorrect', 422);
        }
        
        if (strlen($data['new_password'] ?? '') < 8) {
            $this->error('New password must be at least 8 characters', 422);
        }
        
        $this->db->query(
            "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?",
            [password_hash($data['new_password'], PASSWORD_BCRYPT), $_SESSION['user_id']]
        );
        
        $this->success(null, 'Password updated');
    }

    /**
     * Forgot password
     */
    public function forgotPassword(): void
    {
        $data = $this->getPostData();
        $email = sanitize($data['email'] ?? '');
        
        $user = $this->db->fetch("SELECT id, email FROM users WHERE email = ?", [$email]);
        
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $this->db->query("UPDATE users SET password_reset_token = ?, password_reset_at = NOW() WHERE id = ?", [$token, $user->id]);
        }
        
        // Always return success (don't reveal if email exists)
        $this->success(null, 'If your email exists, a reset link has been sent.');
    }
}
