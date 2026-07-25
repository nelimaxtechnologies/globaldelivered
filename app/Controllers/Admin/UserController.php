<?php
/**
 * Global Delivered Logistics - Admin User & Role Management Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class UserController extends Controller
{
    /**
     * List all users with search and pagination
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $role = (int) ($_GET['role'] ?? 0);
        $status = $_GET['status'] ?? '';
        
        $where = "WHERE u.deleted_at IS NULL";
        $params = [];
        
        if (!empty($search)) {
            $where .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        
        if ($role > 0) {
            $where .= " AND u.role_id = ?";
            $params[] = $role;
        }
        
        if ($status === 'active') {
            $where .= " AND u.is_active = 1";
        } elseif ($status === 'inactive') {
            $where .= " AND u.is_active = 0";
        }
        
        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM users u {$where}",
            "SELECT u.*, r.name as role_name, r.slug as role_slug, b.name as branch_name
             FROM users u
             JOIN roles r ON u.role_id = r.id
             LEFT JOIN branches b ON u.branch_id = b.id
             {$where} ORDER BY u.created_at DESC",
            $params, $page, 25
        );
        
        $roles = $this->db->fetchAll("SELECT * FROM roles ORDER BY name");
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN created_at >= DATE_FORMAT(NOW(), '%Y-%m-01') THEN 1 ELSE 0 END) as new_this_month
             FROM users WHERE deleted_at IS NULL"
        );
        
        $this->adminView('users/index', [
            'pageTitle' => 'User Management',
            'users' => $paginated->data,
            'pagination' => $paginated,
            'roles' => $roles,
            'stats' => $stats,
            'filters' => ['search' => $search, 'role' => $role, 'status' => $status],
        ]);
    }

    /**
     * Show user creation form
     */
    public function create(): void
    {
        $roles = $this->db->fetchAll("SELECT * FROM roles WHERE slug != 'customer' ORDER BY name");
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name");
        
        $this->adminView('users/create', [
            'pageTitle' => 'Create User',
            'roles' => $roles,
            'branches' => $branches,
        ]);
    }

    /**
     * Store new user
     */
    public function store(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min_length:8',
            'role_id' => 'required|numeric',
        ];
        
        $validated = $this->validate($data, $rules);
        
        try {
            // Check for duplicate email
            $exists = $this->db->fetchColumn("SELECT COUNT(*) FROM users WHERE email = ?", [$data['email']]);
            if ($exists) {
                flash('error', 'A user with this email already exists.');
                $this->back();
            }
            
            $this->db->query(
                "INSERT INTO users (role_id, branch_id, first_name, last_name, email, phone, password, is_active, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    (int) $data['role_id'],
                    !empty($data['branch_id']) ? (int) $data['branch_id'] : null,
                    $data['first_name'],
                    $data['last_name'],
                    $data['email'],
                    $data['phone'] ?? '',
                    password_hash($data['password'], PASSWORD_DEFAULT),
                    !empty($data['is_active']) ? 1 : 0,
                ]
            );
            
            $id = $this->db->lastInsertId();
            log_activity('user_created', 'user', $id);
            flash('success', 'User created successfully!');
            $this->redirect("/admin/users/{$id}");
            
        } catch (\Exception $e) {
            error_log("User creation error: " . $e->getMessage());
            flash('error', 'Failed to create user: ' . $e->getMessage());
            $this->back();
        }
    }

    /**
     * Show user details
     */
    public function show(int $id): void
    {
        $user = $this->db->fetch(
            "SELECT u.*, r.name as role_name, r.slug as role_slug, b.name as branch_name,
                    (SELECT COUNT(*) FROM audit_logs WHERE user_id = u.id) as total_actions
             FROM users u
             JOIN roles r ON u.role_id = r.id
             LEFT JOIN branches b ON u.branch_id = b.id
             WHERE u.id = ? AND u.deleted_at IS NULL",
            [$id]
        );
        
        if (!$user) {
            flash('error', 'User not found.');
            $this->redirect('/admin/users');
        }
        
        $recentActivity = $this->db->fetchAll(
            "SELECT * FROM audit_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 20",
            [$id]
        );
        
        $sessions = $this->db->fetchAll(
            "SELECT * FROM user_sessions WHERE user_id = ? AND is_active = 1 ORDER BY last_activity DESC",
            [$id]
        );
        
        $this->adminView('users/show', [
            'pageTitle' => "User: {$user->first_name} {$user->last_name}",
            'user' => $user,
            'recentActivity' => $recentActivity,
            'sessions' => $sessions,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$user) {
            flash('error', 'User not found.');
            $this->redirect('/admin/users');
        }
        
        $roles = $this->db->fetchAll("SELECT * FROM roles WHERE slug != 'customer' ORDER BY name");
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name");
        
        $this->adminView('users/edit', [
            'pageTitle' => "Edit User: {$user->first_name} {$user->last_name}",
            'user' => $user,
            'roles' => $roles,
            'branches' => $branches,
        ]);
    }

    /**
     * Update user
     */
    public function update(int $id): void
    {
        $data = $this->getPostData();
        
        $this->db->query(
            "UPDATE users SET role_id=?, branch_id=?, first_name=?, last_name=?, email=?, phone=?, is_active=?, updated_at=NOW() WHERE id=?",
            [
                (int) $data['role_id'],
                !empty($data['branch_id']) ? (int) $data['branch_id'] : null,
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['phone'] ?? '',
                !empty($data['is_active']) ? 1 : 0,
                $id
            ]
        );
        
        // Update password if provided
        if (!empty($data['password'])) {
            $this->db->query("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?", [
                password_hash($data['password'], PASSWORD_DEFAULT), $id
            ]);
        }
        
        log_activity('user_updated', 'user', $id);
        flash('success', 'User updated successfully!');
        $this->redirect("/admin/users/{$id}");
    }

    /**
     * Delete user (soft)
     */
    public function destroy(int $id): void
    {
        // Prevent self-deletion
        if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
            flash('error', 'You cannot delete your own account.');
            $this->redirect('/admin/users');
        }
        
        $this->db->query("UPDATE users SET deleted_at = NOW(), is_active = 0 WHERE id = ?", [$id]);
        log_activity('user_deleted', 'user', $id);
        flash('success', 'User deleted.');
        $this->redirect('/admin/users');
    }

    /**
     * Toggle user active status (AJAX)
     */
    public function toggleStatus(int $id): void
    {
        if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
            if ($this->isAjax()) {
                $this->error('You cannot deactivate your own account.');
            }
            flash('error', 'You cannot deactivate your own account.');
            $this->redirect('/admin/users');
        }
        
        $user = $this->db->fetch("SELECT is_active FROM users WHERE id = ? AND deleted_at IS NULL", [$id]);
        if (!$user) {
            if ($this->isAjax()) {
                $this->error('User not found.');
            }
            flash('error', 'User not found.');
            $this->redirect('/admin/users');
        }
        
        $newStatus = $user->is_active ? 0 : 1;
        $this->db->query("UPDATE users SET is_active = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $id]);
        
        log_activity('user_status_toggled', 'user', $id);
        
        if ($this->isAjax()) {
            $this->success(['is_active' => $newStatus], $newStatus ? 'User activated' : 'User deactivated');
        }
        
        flash('success', $newStatus ? 'User activated.' : 'User deactivated.');
        $this->redirect('/admin/users');
    }

    /**
     * Reset user password (AJAX)
     */
    public function resetPassword(int $id): void
    {
        $data = $this->getPostData();
        $newPassword = $data['password'] ?? '';
        
        if (strlen($newPassword) < 8) {
            if ($this->isAjax()) {
                $this->error('Password must be at least 8 characters.');
            }
            flash('error', 'Password must be at least 8 characters.');
            $this->back();
        }
        
        $user = $this->db->fetch("SELECT id FROM users WHERE id = ? AND deleted_at IS NULL", [$id]);
        if (!$user) {
            if ($this->isAjax()) {
                $this->error('User not found.');
            }
            flash('error', 'User not found.');
            $this->redirect('/admin/users');
        }
        
        $this->db->query("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?", [
            password_hash($newPassword, PASSWORD_DEFAULT), $id
        ]);
        
        log_activity('user_password_reset', 'user', $id);
        
        if ($this->isAjax()) {
            $this->success(null, 'Password reset successfully');
        }
        
        flash('success', 'Password reset successfully.');
        $this->redirect("/admin/users/{$id}");
    }

    /**
     * List roles
     */
    public function roles(): void
    {
        $roles = $this->db->fetchAll(
            "SELECT r.*, 
                    (SELECT COUNT(*) FROM users WHERE role_id = r.id AND deleted_at IS NULL) as user_count,
                    (SELECT COUNT(*) FROM role_permissions WHERE role_id = r.id) as permission_count
             FROM roles r ORDER BY r.is_system DESC, r.name ASC"
        );
        
        $permissions = $this->db->fetchAll("SELECT * FROM permissions ORDER BY `group`, name");
        $permissionsByGroup = [];
        foreach ($permissions as $p) {
            $permissionsByGroup[$p->group ?? 'General'][] = $p;
        }
        
        $this->adminView('users/roles', [
            'pageTitle' => 'Role Management',
            'roles' => $roles,
            'permissionsByGroup' => $permissionsByGroup,
        ]);
    }

    /**
     * Store new role
     */
    public function storeRole(): void
    {
        $data = $this->getPostData();
        
        $rules = ['name' => 'required'];
        $validated = $this->validate($data, $rules);
        
        try {
            $slug = generate_slug($data['name']);
            
            $exists = $this->db->fetchColumn("SELECT COUNT(*) FROM roles WHERE slug = ?", [$slug]);
            if ($exists) {
                flash('error', 'A role with this name already exists.');
                $this->back();
            }
            
            $this->db->beginTransaction();
            
            $this->db->query(
                "INSERT INTO roles (name, slug, description, is_system, created_at) VALUES (?, ?, ?, 0, NOW())",
                [$data['name'], $slug, $data['description'] ?? '']
            );
            
            $roleId = $this->db->lastInsertId();
            
            // Attach permissions
            $perms = $data['permissions'] ?? [];
            foreach ($perms as $permId) {
                $this->db->query(
                    "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)",
                    [$roleId, (int) $permId]
                );
            }
            
            $this->db->commit();
            
            log_activity('role_created', 'role', $roleId);
            flash('success', "Role '{$data['name']}' created successfully!");
            
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Role creation error: " . $e->getMessage());
            flash('error', 'Failed to create role.');
        }
        
        $this->redirect('/admin/roles');
    }

    /**
     * Get role permissions (AJAX)
     */
    public function getRolePermissions(int $id): void
    {
        $permIds = $this->db->fetchAll(
            "SELECT permission_id FROM role_permissions WHERE role_id = ?", [$id]
        );

        $ids = array_map(fn($p) => (int) $p->permission_id, $permIds);

        if ($this->isAjax()) {
            $this->success($ids);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $ids]);
        exit;
    }

    /**
     * Update role permissions (AJAX)
     */
    public function updateRolePermissions(int $id): void
    {
        $data = $this->getPostData();
        $permIds = $data['permissions'] ?? [];
        
        $this->db->beginTransaction();
        
        $this->db->query("DELETE FROM role_permissions WHERE role_id = ?", [$id]);
        
        foreach ($permIds as $permId) {
            $this->db->query(
                "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)",
                [$id, (int) $permId]
            );
        }
        
        $this->db->commit();
        
        log_activity('role_permissions_updated', 'role', $id);
        
        if ($this->isAjax()) {
            $this->success(null, 'Permissions updated!');
        }
        
        flash('success', 'Role permissions updated.');
        $this->redirect('/admin/roles');
    }

    /**
     * Impersonate user (super admin only)
     */
    public function impersonate(int $id): void
    {
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ? AND is_active = 1 AND deleted_at IS NULL", [$id]);
        
        if (!$user) {
            flash('error', 'User not found.');
            $this->redirect('/admin/users');
        }
        
        $_SESSION['impersonating'] = $_SESSION['user_id'];
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
        
        log_activity('user_impersonated', 'user', $id);
        flash('success', "Now impersonating {$user->first_name} {$user->last_name}");
        $this->redirect('/admin/dashboard');
    }

    /**
     * Stop impersonating
     */
    public function stopImpersonate(): void
    {
        if (isset($_SESSION['impersonating'])) {
            $_SESSION['user_id'] = $_SESSION['impersonating'];
            unset($_SESSION['impersonating']);
            flash('success', 'Returned to your account.');
        }
        $this->redirect('/admin/users');
    }
}
