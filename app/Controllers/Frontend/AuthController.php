<?php
/**
 * Global Delivered Logistics - Frontend Auth Controller
 */

namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function loginForm(): void
    {
        if (isset($_SESSION['user_id'])) {
            if (in_array($_SESSION['user_role'] ?? '', ['super_admin', 'admin', 'branch_manager'])) {
                $this->redirect('/admin/dashboard');
            } else {
                $this->redirect('/dashboard');
            }
        }
        
        $this->view('frontend/login', [
            'pageTitle' => 'Login - Global Delivered Logistics',
        ]);
    }

    /**
     * Process login
     */
    public function login(): void
    {
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = !empty($_POST['remember']);
        
        if (empty($email) || empty($password)) {
            flash('error', 'Please enter email and password.');
            $this->back();
        }
        
        $user = User::verifyPassword($email, $password);
        
        if (!$user) {
            flash('error', 'Invalid email or password.');
            $this->back();
        }
        
        // Update login info
        $this->db->query(
            "UPDATE users SET last_login_at = NOW(), last_login_ip = ? WHERE id = ?",
            [$_SERVER['REMOTE_ADDR'] ?? '', $user->id]
        );
        
        // Set session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = $user->role_slug;
        $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
        
        // Remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->db->query("UPDATE users SET remember_token = ? WHERE id = ?", [$token, $user->id]);
            setcookie('remember_token', $token, time() + 86400 * 30, '/', '', true, true);
        }
        
        log_activity('user_login', 'user', $user->id);
        
        // Redirect to intended URL or dashboard
        $intended = $_SESSION['_intended_url'] ?? '/dashboard';
        unset($_SESSION['_intended_url']);
        
        // If admin user, go to admin
        if (in_array($user->role_slug, ['super_admin', 'admin', 'branch_manager'])) {
            $intended = '/admin/dashboard';
        }
        
        flash('success', 'Welcome back, ' . $user->first_name . '!');
        $this->redirect($intended);
    }

    /**
     * Show registration form
     */
    public function registerForm(): void
    {
        if (isset($_SESSION['user_id'])) {
            if (in_array($_SESSION['user_role'] ?? '', ['super_admin', 'admin', 'branch_manager'])) {
                $this->redirect('/admin/dashboard');
            } else {
                $this->redirect('/dashboard');
            }
        }
        
        $this->view('frontend/register', [
            'pageTitle' => 'Register - Global Delivered Logistics',
        ]);
    }

    /**
     * Process registration
     */
    public function register(): void
    {
        $data = [
            'first_name' => sanitize($_POST['first_name'] ?? ''),
            'last_name' => sanitize($_POST['last_name'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
        ];
        
        // Validation
        $errors = [];
        if (empty($data['first_name'])) $errors[] = 'First name is required.';
        if (empty($data['last_name'])) $errors[] = 'Last name is required.';
        if (!is_valid_email($data['email'])) $errors[] = 'Valid email is required.';
        if (strlen($data['password']) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($data['password'] !== $data['password_confirm']) $errors[] = 'Passwords do not match.';
        
        // Check email uniqueness
        $existing = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM users WHERE email = ?",
            [$data['email']]
        );
        if ($existing > 0) {
            $errors[] = 'Email address is already registered.';
        }
        
        if (!empty($errors)) {
            flash('error', implode(' ', $errors));
            $_SESSION['_old_input'] = $data;
            $this->back();
        }
        
        try {
            // Get customer role
            $customerRole = $this->db->fetchColumn("SELECT id FROM roles WHERE slug = 'customer'");
            
            // Create user
            $userId = User::createUser([
                'role_id' => $customerRole,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'is_active' => 1,
            ]);
            
            // Create customer record
            $this->db->query(
                "INSERT INTO customers (user_id, first_name, last_name, email, phone, is_active, created_at) 
                 VALUES (?, ?, ?, ?, ?, 1, NOW())",
                [$userId, $data['first_name'], $data['last_name'], $data['email'], $data['phone']]
            );
            
            log_activity('user_registered', 'user', $userId);
            
            flash('success', 'Account created successfully! You can now login.');
            $this->redirect('/login');
            
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            flash('error', 'Registration failed. Please try again.');
            $this->back();
        }
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            log_activity('user_logout', 'user', $_SESSION['user_id']);
        }
        
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        
        $this->redirect('/');
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword(): void
    {
        $this->view('frontend/forgot_password', [
            'pageTitle' => 'Forgot Password - Global Delivered Logistics',
        ]);
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(): void
    {
        $email = sanitize($_POST['email'] ?? '');
        
        if (!is_valid_email($email)) {
            flash('error', 'Please enter a valid email address.');
            $this->back();
            return;
        }
        
        $user = $this->db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
        
        if (!$user) {
            flash('success', 'If that email exists, a reset link has been sent.');
            $this->redirect('/login');
            return;
        }
        
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $this->db->query(
            "UPDATE users SET password_reset_token = ?, password_reset_at = NOW() WHERE id = ?",
            [$token, $user->id]
        );
        
        // Send reset email via PHPMailer SMTP
        $emailService = \App\Services\EmailService::getInstance();
        $emailService->sendPasswordReset(
            $email,
            $user->first_name . ' ' . $user->last_name,
            $token
        );
        
        flash('success', 'If that email exists, a password reset link has been sent.');
        $this->redirect('/login');
    }
}
