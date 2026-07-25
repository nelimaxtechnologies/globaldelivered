<?php
/**
 * Global Delivered Logistics - Admin Settings Controller
 * Manages all system settings including company profile, email, tracking, and audit logs.
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class SettingsController extends Controller
{
    /**
     * Display settings dashboard with all groups
     */
    public function index(): void
    {
        $groups = $this->db->fetchAll(
            "SELECT `group`, COUNT(*) as count FROM settings GROUP BY `group` ORDER BY `group`"
        );

        $allSettings = $this->db->fetchAll(
            "SELECT * FROM settings ORDER BY `group`, `key`"
        );

        $groupedSettings = [];
        foreach ($allSettings as $s) {
            $groupedSettings[$s->group][] = $s;
        }

        // System stats
        $totalSettings = count($allSettings);
        $lastUpdated = $this->db->fetchColumn("SELECT MAX(updated_at) FROM settings");
        $totalApiKeys = $this->db->fetchColumn("SELECT COUNT(*) FROM settings WHERE `group` = 'api'");
        $totalAuditLogs = $this->db->fetchColumn("SELECT COUNT(*) FROM audit_logs");

        // Current user profile
        $currentUser = $this->db->fetch(
            "SELECT u.id, u.first_name, u.last_name, u.email, u.phone, r.name as role_name
             FROM users u
             JOIN roles r ON u.role_id = r.id
             WHERE u.id = ?",
            [$_SESSION['user_id'] ?? 0]
        );

        $this->adminView('settings/index', [
            'pageTitle' => 'System Settings',
            'groups' => $groups,
            'groupedSettings' => $groupedSettings,
            'totalSettings' => $totalSettings,
            'lastUpdated' => $lastUpdated,
            'totalApiKeys' => $totalApiKeys,
            'totalAuditLogs' => $totalAuditLogs,
            'currentUser' => $currentUser,
        ]);
    }

    public function update(): void
    {
        $data = $this->getPostData();
        $groupId = sanitize($data['group'] ?? 'general');

        $this->db->beginTransaction();
        $updated = 0;

        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'setting_')) {
                $settingKey = substr($key, 8);
                $this->db->query(
                    "UPDATE settings SET `value` = ?, updated_at = NOW() WHERE `key` = ? AND `group` = ?",
                    [$value, $settingKey, $groupId]
                );
                $updated++;
            }
        }

        $this->db->commit();
        log_activity('settings_updated', 'settings', null, null, ['group' => $groupId, 'count' => $updated]);
        flash('success', "{$updated} setting(s) updated successfully!");

        $this->redirect("/admin/settings/{$groupId}");
    }

    public function group(string $group): void
    {
        $settings = $this->db->fetchAll(
            "SELECT * FROM settings WHERE `group` = ? ORDER BY `key`", [$group]
        );

        if (empty($settings)) {
            flash('error', 'Settings group not found.');
            $this->redirect('/admin/settings');
        }

        $this->adminView("settings/{$group}", [
            'pageTitle' => ucfirst(str_replace('_', ' ', $group)) . ' Settings',
            'settings' => $settings,
            'group' => $group,
        ]);
    }

    /**
     * View audit logs with filtering
     */
    public function auditLogs(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $action = sanitize($_GET['action'] ?? '');
        $userId = (int) ($_GET['user_id'] ?? 0);
        $entityType = sanitize($_GET['entity_type'] ?? '');
        $startDate = sanitize($_GET['start_date'] ?? '');
        $endDate = sanitize($_GET['end_date'] ?? '');
        $search = sanitize($_GET['search'] ?? '');
        $perPage = min(100, max(10, (int) ($_GET['per_page'] ?? 50)));

        $where = "WHERE 1=1";
        $params = [];

        if (!empty($action)) { $where .= " AND a.action = ?"; $params[] = $action; }
        if ($userId > 0) { $where .= " AND a.user_id = ?"; $params[] = $userId; }
        if (!empty($entityType)) { $where .= " AND a.entity_type = ?"; $params[] = $entityType; }
        if (!empty($startDate)) { $where .= " AND DATE(a.created_at) >= ?"; $params[] = $startDate; }
        if (!empty($endDate)) { $where .= " AND DATE(a.created_at) <= ?"; $params[] = $endDate; }
        if (!empty($search)) {
            $where .= " AND (a.action LIKE ? OR a.entity_type LIKE ? OR a.entity_id LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s]);
        }

        // Stats
        $stats = $this->db->fetch(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today,
                SUM(CASE WHEN DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as this_week,
                COUNT(DISTINCT user_id) as unique_users
             FROM audit_logs"
        );

        // Action breakdown for chart
        $actionBreakdown = $this->db->fetchAll(
            "SELECT action, COUNT(*) as count FROM audit_logs GROUP BY action ORDER BY count DESC LIMIT 10"
        );

        // Daily activity trend (last 14 days)
        $dailyTrend = $this->db->fetchAll(
            "SELECT DATE(created_at) as date, COUNT(*) as count
             FROM audit_logs WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
             GROUP BY DATE(created_at) ORDER BY date ASC"
        );

        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM audit_logs a {$where}",
            "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email
             FROM audit_logs a
             LEFT JOIN users u ON a.user_id = u.id
             {$where}
             ORDER BY a.created_at DESC",
            $params, $page, $perPage
        );

        $distinctActions = $this->db->fetchAll(
            "SELECT DISTINCT action, COUNT(*) as count FROM audit_logs GROUP BY action ORDER BY count DESC"
        );

        $entityTypes = $this->db->fetchAll(
            "SELECT DISTINCT entity_type, COUNT(*) as count FROM audit_logs WHERE entity_type IS NOT NULL GROUP BY entity_type ORDER BY count DESC"
        );

        $users = $this->db->fetchAll(
            "SELECT id, first_name, last_name FROM users ORDER BY first_name"
        );

        $this->adminView('settings/audit_logs', [
            'pageTitle' => 'Audit Logs',
            'logs' => $paginated->data,
            'pagination' => $paginated,
            'stats' => $stats,
            'actionBreakdown' => $actionBreakdown,
            'dailyTrend' => $dailyTrend,
            'distinctActions' => $distinctActions,
            'entityTypes' => $entityTypes,
            'users' => $users,
            'filters' => [
                'action' => $action, 'user_id' => $userId, 'entity_type' => $entityType,
                'start_date' => $startDate, 'end_date' => $endDate, 'search' => $search,
            ],
        ]);
    }

    /**
     * API settings page
     */
    public function apiSettings(): void
    {
        $apiKeys = $this->db->fetchAll(
            "SELECT * FROM settings WHERE `group` = 'api' ORDER BY created_at DESC"
        );

        $this->adminView('settings/api', [
            'pageTitle' => 'API Settings',
            'apiKeys' => $apiKeys,
        ]);
    }

    public function generateApiKey(): void
    {
        $name = sanitize($_POST['name'] ?? '');
        if (empty($name)) {
            flash('error', 'API key name is required.');
            $this->back();
        }

        $key = 'gdl_' . bin2hex(random_bytes(24));
        $slug = generate_slug($name);

        $exists = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM settings WHERE `group` = 'api' AND `key` = ?", [$slug]
        );

        if ($exists) {
            flash('error', 'An API key with this name already exists.');
            $this->back();
        }

        $this->db->query(
            "INSERT INTO settings (`group`, `key`, `value`, `type`, `description`, `is_system`, `is_encrypted`, created_at)
             VALUES ('api', ?, ?, 'text', ?, 0, 1, NOW())",
            [$slug, $key, $name]
        );

        log_activity('api_key_generated', 'settings', null, null, ['name' => $name]);
        flash('success', "API Key \"{$name}\" generated successfully!");
        $this->redirect('/admin/api-settings');
    }

    public function deleteApiKey(int $id): void
    {
        $key = $this->db->fetch("SELECT * FROM settings WHERE id = ? AND `group` = 'api'", [$id]);
        if (!$key) {
            flash('error', 'API key not found.');
            $this->redirect('/admin/api-settings');
        }

        $this->db->query("DELETE FROM settings WHERE id = ?", [$id]);
        log_activity('api_key_deleted', 'settings', $id, ['name' => $key->description]);
        flash('success', 'API key deleted.');
        $this->redirect('/admin/api-settings');
    }

    public function profile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings');
        }

        $data = $this->getPostData();

        $firstName = sanitize($data['first_name'] ?? '');
        $lastName = sanitize($data['last_name'] ?? '');
        $phone = sanitize($data['phone'] ?? '');

        if (empty($firstName) || empty($lastName)) {
            flash('error', 'First and last name are required.');
            $this->back();
        }

        $this->db->query(
            "UPDATE users SET first_name=?, last_name=?, phone=?, updated_at=NOW() WHERE id=?",
            [$firstName, $lastName, $phone, $_SESSION['user_id']]
        );

        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        log_activity('profile_updated', 'user', $_SESSION['user_id']);
        flash('success', 'Profile updated successfully!');
        $this->redirect('/admin/settings');
    }

    public function testEmail(): void
    {
        $email = sanitize($_POST['email'] ?? '');
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter a valid email address.');
            $this->back();
            return;
        }

        $emailService = \App\Services\EmailService::getInstance();

        $subject = "GDL Test Email - " . date('M d, Y H:i');
        $body = "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>
            <div style='background:#1a237e;padding:30px;text-align:center;'>
                <h1 style='color:#fff;margin:0;'>Global Delivered Logistics</h1>
            </div>
            <div style='padding:30px;background:#f8f9fa;'>
                <h2 style='color:#1a237e;'>Test Email Successful!</h2>
                <p>This is a test email from your GDL admin panel.</p>
                <p>If you received this, your SMTP email configuration is working correctly.</p>
                <div style='background:#fff;padding:20px;border-radius:8px;margin:20px 0;'>
                    <p><strong>Server:</strong> mail.globaldelivered.biz</p>
                    <p><strong>Port:</strong> 465 (SSL)</p>
                    <p><strong>From:</strong> track@globaldelivered.biz</p>
                    <p><strong>Sent at:</strong> " . date('M d, Y H:i:s') . "</p>
                </div>
            </div>
            <div style='background:#0d1452;color:#fff;padding:20px;text-align:center;font-size:12px;'>
                <p>&copy; " . date('Y') . " Global Delivered Logistics</p>
            </div>
        </div>";

        $sent = $emailService->sendDirect($email, 'Admin Test', $subject, $body);

        log_activity('test_email_sent', 'settings', null, null, [
            'email' => $email,
            'success' => $sent,
        ]);

        if ($sent) {
            flash('success', "Test email sent successfully to {$email}. Check your inbox.");
        } else {
            flash('error', "Failed to send test email to {$email}. Check SMTP settings and error logs.");
        }
        $this->back();
    }

    /**
     * Process email queue (manual trigger or AJAX)
     */
    public function processEmailQueue(): void
    {
        $limit = (int) ($_POST['limit'] ?? 20);

        $emailService = \App\Services\EmailService::getInstance();
        $results = $emailService->processQueue($limit);

        log_activity('email_queue_processed', 'settings', null, null, $results);

        if ($this->isAjax()) {
            $this->json(['success' => true, 'data' => $results]);
            return;
        }

        $msg = "Email queue processed: {$results['sent']} sent, {$results['failed']} failed.";
        if (!empty($results['errors'])) {
            $msg .= " Errors: " . implode('; ', array_slice($results['errors'], 0, 3));
        }
        flash($results['failed'] > 0 ? 'error' : 'success', $msg);
        $this->back();
    }
}
