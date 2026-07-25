<?php
/**
 * Global Delivered Logistics - User Model
 */

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected static string $table = 'users';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'role_id', 'branch_id', 'first_name', 'last_name', 'email', 'phone',
        'password', 'avatar', 'email_verified_at', 'two_factor_secret',
        'two_factor_enabled', 'is_active', 'last_login_at', 'last_login_ip',
        'remember_token'
    ];
    protected static array $searchable = ['first_name', 'last_name', 'email', 'phone'];
    protected static array $sortable = ['id', 'first_name', 'last_name', 'email', 'created_at'];

    /**
     * Get full name
     */
    public function fullName(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Verify password
     */
    public static function verifyPassword(string $email, string $password): ?object
    {
        $db = \App\Core\Database::getInstance();
        $user = $db->fetch(
            "SELECT u.*, r.name as role_name, r.slug as role_slug 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.email = ? AND u.is_active = 1",
            [$email]
        );
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return null;
    }

    /**
     * Create a new user with hashed password
     */
    public static function createUser(array $data): ?int
    {
        $db = \App\Core\Database::getInstance();
        
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $db->query(
            "INSERT INTO users ({$fields}) VALUES ({$placeholders})",
            array_values($data)
        );
        
        return (int) $db->lastInsertId();
    }

    /**
     * Get role
     */
    public function role(): ?object
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM roles WHERE id = ?", [$this->role_id]);
    }
}
