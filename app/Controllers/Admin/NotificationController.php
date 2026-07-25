<?php
/**
 * Global Delivered Logistics - Admin Notification Controller
 * Manages system notifications, email queue, SMS queue, and templates.
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class NotificationController extends Controller
{
    /**
     * List all notifications with search/filter
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $type = sanitize($_GET['type'] ?? '');
        $read = $_GET['read'] ?? '';
        $search = sanitize($_GET['search'] ?? '');
        $startDate = sanitize($_GET['start_date'] ?? '');
        $endDate = sanitize($_GET['end_date'] ?? '');
        $tab = sanitize($_GET['tab'] ?? 'all');
        
        $where = "WHERE 1=1";
        $params = [];
        
        if (!empty($type)) {
            $where .= " AND n.type = ?";
            $params[] = $type;
        }
        
        if ($read === 'unread') {
            $where .= " AND n.is_read = 0";
        } elseif ($read === 'read') {
            $where .= " AND n.is_read = 1";
        }
        
        if (!empty($search)) {
            $where .= " AND (n.subject LIKE ? OR n.message LIKE ? OR CONCAT(u.first_name, ' ', u.last_name) LIKE ? OR CONCAT(c.first_name, ' ', c.last_name) LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        
        if (!empty($startDate)) {
            $where .= " AND DATE(n.created_at) >= ?";
            $params[] = $startDate;
        }
        if (!empty($endDate)) {
            $where .= " AND DATE(n.created_at) <= ?";
            $params[] = $endDate;
        }
        
        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM notifications n
             LEFT JOIN users u ON n.user_id = u.id
             LEFT JOIN customers c ON n.customer_id = c.id
             {$where}",
            "SELECT n.*, CONCAT(u.first_name, ' ', u.last_name) as user_name,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name
             FROM notifications n
             LEFT JOIN users u ON n.user_id = u.id
             LEFT JOIN customers c ON n.customer_id = c.id
             {$where} ORDER BY n.created_at DESC",
            $params, $page, 25
        );
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN is_sent = 1 THEN 1 ELSE 0 END) as sent,
                    SUM(CASE WHEN is_sent = 0 AND is_read = 0 THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as `read`,
                    SUM(CASE WHEN type = 'email' THEN 1 ELSE 0 END) as emails,
                    SUM(CASE WHEN type = 'sms' THEN 1 ELSE 0 END) as sms,
                    SUM(CASE WHEN type = 'system' THEN 1 ELSE 0 END) as system_count,
                    SUM(CASE WHEN type = 'push' THEN 1 ELSE 0 END) as push_count,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today
             FROM notifications"
        );
        
        $unreadCount = (int)($stats->pending ?? 0);
        
        $this->adminView('notifications/index', [
            'pageTitle' => 'Notifications',
            'notifications' => $paginated->data,
            'pagination' => $paginated,
            'stats' => $stats,
            'unreadCount' => $unreadCount,
            'filters' => [
                'type' => $type, 'read' => $read, 'search' => $search,
                'start_date' => $startDate, 'end_date' => $endDate, 'tab' => $tab,
            ],
        ]);
    }

    /**
     * Show a single notification detail (AJAX)
     */
    public function show(int $id): void
    {
        $notification = $this->db->fetch(
            "SELECT n.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name
             FROM notifications n
             LEFT JOIN users u ON n.user_id = u.id
             LEFT JOIN customers c ON n.customer_id = c.id
             WHERE n.id = ?",
            [$id]
        );
        
        if (!$notification) {
            $this->error('Notification not found.');
        }
        
        $this->success($notification, 'Notification loaded');
    }

    /**
     * Send a notification (AJAX)
     */
    public function send(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'subject' => 'required',
            'message' => 'required',
            'type' => 'required|in:email,sms,push,system',
        ];
        
        $validated = $this->validate($data, $rules);
        
        try {
            $userId = !empty($data['user_id']) ? (int) $data['user_id'] : null;
            $customerId = !empty($data['customer_id']) ? (int) $data['customer_id'] : null;
            
            $this->db->query(
                "INSERT INTO notifications (user_id, customer_id, type, subject, message, is_sent, is_read, created_at)
                 VALUES (?, ?, ?, ?, ?, 0, 0, NOW())",
                [$userId, $customerId, $data['type'], $data['subject'], $data['message']]
            );
            
            $id = $this->db->lastInsertId();
            
            // If email type, also queue in email_queue
            if ($data['type'] === 'email' && !empty($data['email'])) {
                $this->db->query(
                    "INSERT INTO email_queue (to_email, to_name, subject, body, template, status, created_at)
                     VALUES (?, ?, ?, ?, 'admin_notification', 'queued', NOW())",
                    [$data['email'], $data['to_name'] ?? '', $data['subject'], $data['message']]
                );
            }
            
            // If sms type, also queue in sms_queue
            if ($data['type'] === 'sms' && !empty($data['phone'])) {
                $this->db->query(
                    "INSERT INTO sms_queue (to_phone, message, provider, status, created_at)
                     VALUES (?, ?, 'twilio', 'queued', NOW())",
                    [$data['phone'], $data['message']]
                );
            }
            
            log_activity('notification_sent', 'notification', $id);
            
            if ($this->isAjax()) {
                $this->success(['id' => $id], 'Notification sent successfully');
            }
            
            flash('success', 'Notification sent!');
            
        } catch (\Exception $e) {
            error_log("Notification send error: " . $e->getMessage());
            if ($this->isAjax()) {
                $this->error('Failed to send notification.');
            }
            flash('error', 'Failed to send notification.');
        }
        
        $this->redirect('/admin/notifications');
    }

    /**
     * Mark notification as read (AJAX)
     */
    public function markRead(int $id): void
    {
        $this->db->query("UPDATE notifications SET is_read = 1, read_at = NOW() WHERE id = ?", [$id]);
        
        if ($this->isAjax()) {
            $this->success(null, 'Marked as read');
        }
        
        $this->back();
    }

    /**
     * Toggle read/unread status (AJAX)
     */
    public function toggleRead(int $id): void
    {
        $notification = $this->db->fetch("SELECT is_read FROM notifications WHERE id = ?", [$id]);
        if (!$notification) {
            if ($this->isAjax()) { $this->error('Not found'); }
            $this->back();
        }
        
        $newStatus = $notification->is_read ? 0 : 1;
        $readAt = $newStatus ? 'NOW()' : 'NULL';
        $this->db->query("UPDATE notifications SET is_read = ?, read_at = {$readAt} WHERE id = ?", [$newStatus, $id]);
        
        if ($this->isAjax()) {
            $this->success(['is_read' => $newStatus], $newStatus ? 'Marked as read' : 'Marked as unread');
        }
        
        $this->back();
    }

    /**
     * Mark all as read (AJAX)
     */
    public function markAllRead(): void
    {
        $this->db->query(
            "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE is_read = 0"
        );
        
        if ($this->isAjax()) {
            $this->success(null, 'All notifications marked as read');
        }
        
        flash('success', 'All notifications marked as read.');
        $this->redirect('/admin/notifications');
    }

    /**
     * Delete notification (AJAX supported)
     */
    public function destroy(int $id): void
    {
        $this->db->query("DELETE FROM notifications WHERE id = ?", [$id]);
        log_activity('notification_deleted', 'notification', $id);
        
        if ($this->isAjax()) {
            $this->success(null, 'Notification deleted');
        }
        
        flash('success', 'Notification deleted.');
        $this->redirect('/admin/notifications');
    }

    /**
     * Bulk delete notifications (AJAX)
     */
    public function bulkDelete(): void
    {
        $data = $this->getPostData();
        $ids = $data['ids'] ?? [];
        
        if (empty($ids)) {
            if ($this->isAjax()) { $this->error('No notifications selected'); }
            $this->back();
        }
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $this->db->query("DELETE FROM notifications WHERE id IN ({$placeholders})", $ids);
        
        log_activity('notifications_bulk_deleted', 'notification', 0);
        
        if ($this->isAjax()) {
            $this->success(null, count($ids) . ' notification(s) deleted');
        }
        
        flash('success', count($ids) . ' notification(s) deleted.');
        $this->redirect('/admin/notifications');
    }

    /**
     * Bulk mark read (AJAX)
     */
    public function bulkMarkRead(): void
    {
        $data = $this->getPostData();
        $ids = $data['ids'] ?? [];
        
        if (empty($ids)) {
            if ($this->isAjax()) { $this->error('No notifications selected'); }
            $this->back();
        }
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $this->db->query("UPDATE notifications SET is_read = 1, read_at = NOW() WHERE id IN ({$placeholders})", $ids);
        
        if ($this->isAjax()) {
            $this->success(null, count($ids) . ' notification(s) marked as read');
        }
        
        flash('success', count($ids) . ' notification(s) marked as read.');
        $this->redirect('/admin/notifications');
    }

    /**
     * Show notification queue (email and SMS)
     */
    public function queue(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $queueType = sanitize($_GET['queue'] ?? 'email');
        $status = sanitize($_GET['status'] ?? '');
        $search = sanitize($_GET['search'] ?? '');
        
        $table = $queueType === 'email' ? 'email_queue' : 'sms_queue';
        $where = "WHERE 1=1";
        $params = [];
        
        if (!empty($status)) {
            $where .= " AND status = ?";
            $params[] = $status;
        }
        
        if (!empty($search)) {
            if ($queueType === 'email') {
                $where .= " AND (to_email LIKE ? OR subject LIKE ? OR body LIKE ?)";
            } else {
                $where .= " AND (to_phone LIKE ? OR message LIKE ?)";
            }
            $s = "%{$search}%";
            $params[] = $s;
            $params[] = $s;
            if ($queueType === 'email') { $params[] = $s; }
        }
        
        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM {$table} {$where}",
            "SELECT * FROM {$table} {$where} ORDER BY priority DESC, created_at ASC",
            $params, $page, 25
        );
        
        $tableTitle = $queueType === 'email' ? 'Email Queue' : 'SMS Queue';
        
        $emailCounts = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN status = 'queued' THEN 1 ELSE 0 END) as queued,
                    SUM(CASE WHEN status = 'sending' THEN 1 ELSE 0 END) as sending,
                    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
             FROM email_queue"
        );
        
        $smsCounts = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN status = 'queued' THEN 1 ELSE 0 END) as queued,
                    SUM(CASE WHEN status = 'sending' THEN 1 ELSE 0 END) as sending,
                    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
             FROM sms_queue"
        );
        
        $this->adminView('notifications/queue', [
            'pageTitle' => 'Notification Queue',
            'items' => $paginated->data,
            'pagination' => $paginated,
            'queueType' => $queueType,
            'tableTitle' => $tableTitle,
            'emailCounts' => $emailCounts,
            'smsCounts' => $smsCounts,
            'filters' => ['queue' => $queueType, 'status' => $status, 'search' => $search],
        ]);
    }

    /**
     * Retry failed email (AJAX)
     */
    public function retryEmail(int $id): void
    {
        $this->db->query("UPDATE email_queue SET status = 'queued', retry_count = 0, error_message = NULL WHERE id = ?", [$id]);
        
        if ($this->isAjax()) {
            $this->success(null, 'Email queued for retry');
        }
        
        flash('success', 'Email queued for retry.');
        $this->redirect('/admin/notifications/queue?queue=email');
    }

    /**
     * Retry failed SMS (AJAX)
     */
    public function retrySms(int $id): void
    {
        $this->db->query("UPDATE sms_queue SET status = 'queued', retry_count = 0, error_message = NULL WHERE id = ?", [$id]);
        
        if ($this->isAjax()) {
            $this->success(null, 'SMS queued for retry');
        }
        
        flash('success', 'SMS queued for retry.');
        $this->redirect('/admin/notifications/queue?queue=sms');
    }

    /**
     * Bulk retry failed items (AJAX)
     */
    public function bulkRetry(): void
    {
        $data = $this->getPostData();
        $ids = $data['ids'] ?? [];
        $queueType = $data['queue'] ?? 'email';
        $table = $queueType === 'email' ? 'email_queue' : 'sms_queue';
        
        if (empty($ids)) {
            if ($this->isAjax()) { $this->error('No items selected'); }
            $this->back();
        }
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $this->db->query("UPDATE {$table} SET status = 'queued', retry_count = 0, error_message = NULL WHERE id IN ({$placeholders}) AND status = 'failed'", $ids);
        
        if ($this->isAjax()) {
            $this->success(null, count($ids) . ' item(s) queued for retry');
        }
        
        flash('success', count($ids) . ' item(s) queued for retry.');
        $this->redirect("/admin/notifications/queue?queue={$queueType}");
    }

    /**
     * View notification templates
     */
    public function templates(): void
    {
        $templates = $this->db->fetchAll(
            "SELECT * FROM notification_templates ORDER BY name ASC"
        );
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN type = 'email' THEN 1 ELSE 0 END) as emails,
                    SUM(CASE WHEN type = 'sms' THEN 1 ELSE 0 END) as sms
             FROM notification_templates"
        );
        
        $this->adminView('notifications/templates', [
            'pageTitle' => 'Notification Templates',
            'templates' => $templates,
            'stats' => $stats,
        ]);
    }

    /**
     * Get recipients for compose modal (AJAX)
     */
    public function recipients(): void
    {
        $type = sanitize($_GET['type'] ?? 'user');
        $search = sanitize($_GET['search'] ?? '');
        
        if ($type === 'user') {
            $query = "SELECT id, CONCAT(first_name, ' ', last_name) as name, email FROM users WHERE deleted_at IS NULL";
            $params = [];
            if (!empty($search)) {
                $query .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
                $s = "%{$search}%";
                $params = [$s, $s, $s];
            }
            $query .= " ORDER BY first_name LIMIT 20";
            $results = $this->db->fetchAll($query, $params);
            $results = array_map(fn($r) => ['id' => $r->id, 'label' => $r->name . ' (' . $r->email . ')', 'email' => $r->email], $results);
        } else {
            $query = "SELECT id, CONCAT(first_name, ' ', last_name) as name, email, phone FROM customers WHERE deleted_at IS NULL";
            $params = [];
            if (!empty($search)) {
                $query .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
                $s = "%{$search}%";
                $params = [$s, $s, $s, $s];
            }
            $query .= " ORDER BY first_name LIMIT 20";
            $results = $this->db->fetchAll($query, $params);
            $results = array_map(fn($r) => ['id' => $r->id, 'label' => $r->name . ' (' . $r->email . ')', 'email' => $r->email, 'phone' => $r->phone], $results);
        }
        
        $this->success($results, 'Recipients loaded');
    }
}
