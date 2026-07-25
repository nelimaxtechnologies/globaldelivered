<?php
/**
 * Global Delivered Logistics - Tracking Controller
 * 
 * Handles shipment tracking with real-time AJAX updates.
 */

namespace App\Controllers\Frontend;

use App\Core\Controller;

class TrackingController extends Controller
{
    /**
     * Display tracking page
     */
    public function index(): void
    {
        $this->view('frontend/tracking', [
            'pageTitle' => 'Track Your Shipment - Global Delivered Logistics',
        ]);
    }

    /**
     * Look up shipment by tracking number (AJAX)
     */
    public function lookup(): void
    {
        $trackingNumber = sanitize($_POST['tracking_number'] ?? '');
        
        if (empty($trackingNumber)) {
            $this->error('Please enter a tracking number.');
        }
        
        $shipment = $this->db->fetch(
            "SELECT s.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon,
                    ss.slug as status_slug
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.tracking_number = ? AND s.deleted_at IS NULL",
            [$trackingNumber]
        );
        
        if (!$shipment) {
            $this->error('Shipment not found. Please check your tracking number and try again.', 404);
        }
        
        // Get tracking history
        $history = $this->db->fetchAll(
            "SELECT th.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon,
                    ss.sort_order as status_order,
                    w.name as warehouse_name
             FROM tracking_history th
             JOIN shipment_statuses ss ON th.status_id = ss.id
             LEFT JOIN warehouses w ON th.warehouse_id = w.id
             WHERE th.shipment_id = ?
             ORDER BY th.created_at ASC",
            [$shipment->id]
        );
        
        // Get all statuses for timeline
        $timeline = $this->db->fetchAll(
            "SELECT * FROM shipment_statuses WHERE is_active = 1 ORDER BY sort_order ASC"
        );
        
        $this->success([
            'shipment' => $shipment,
            'history' => $history,
            'timeline' => $timeline,
        ]);
    }

    /**
     * Subscribe to tracking notifications (AJAX)
     */
    public function subscribe(): void
    {
        $trackingNumber = sanitize($_POST['tracking_number'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $name = sanitize($_POST['name'] ?? '');

        if (empty($trackingNumber) || empty($email)) {
            $this->error('Tracking number and email are required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please enter a valid email address.');
        }

        $shipment = $this->db->fetch(
            "SELECT id, tracking_number FROM shipments WHERE tracking_number = ? AND deleted_at IS NULL",
            [$trackingNumber]
        );

        if (!$shipment) {
            $this->error('Shipment not found.');
        }

        $existing = $this->db->fetch(
            "SELECT id, is_active FROM shipment_notification_subscriptions WHERE tracking_number = ? AND email = ?",
            [$trackingNumber, $email]
        );

        if ($existing) {
            if ($existing->is_active) {
                $this->error('You are already subscribed to notifications for this shipment.');
            }
            $this->db->query(
                "UPDATE shipment_notification_subscriptions SET is_active = 1, unsubscribed_at = NULL, updated_at = NOW() WHERE id = ?",
                [$existing->id]
            );
        } else {
            $this->db->query(
                "INSERT INTO shipment_notification_subscriptions (tracking_number, email, name, notify_email, is_active, created_at)
                 VALUES (?, ?, ?, 1, 1, NOW())",
                [$trackingNumber, $email, $name ?: null]
            );
        }

        $this->success([], 'You will receive email notifications for future tracking updates.');
    }

    /**
     * Unsubscribe from tracking notifications (GET link or AJAX POST)
     */
    public function unsubscribe(): void
    {
        $trackingNumber = sanitize($_GET['tracking_number'] ?? $_POST['tracking_number'] ?? '');
        $email = sanitize($_GET['email'] ?? $_POST['email'] ?? '');

        if (empty($trackingNumber) || empty($email)) {
            $this->view('frontend/tracking', [
                'pageTitle' => 'Unsubscribe',
                'unsubscribeMessage' => 'Invalid unsubscribe link.',
            ]);
            return;
        }

        $this->db->query(
            "UPDATE shipment_notification_subscriptions SET is_active = 0, unsubscribed_at = NOW(), updated_at = NOW()
             WHERE tracking_number = ? AND email = ?",
            [$trackingNumber, $email]
        );

        if ($this->isAjax()) {
            $this->success([], 'You have been unsubscribed from notifications.');
            return;
        }

        $this->view('frontend/tracking', [
            'pageTitle' => 'Unsubscribed',
            'unsubscribeMessage' => 'You have been unsubscribed from tracking notifications for ' . htmlspecialchars($trackingNumber) . '.',
        ]);
    }

    /**
     * Check if email is subscribed (AJAX)
     */
    public function checkSubscription(): void
    {
        $trackingNumber = sanitize($_POST['tracking_number'] ?? '');
        $email = sanitize($_POST['email'] ?? '');

        if (empty($trackingNumber) || empty($email)) {
            $this->success(['subscribed' => false]);
            return;
        }

        $sub = $this->db->fetch(
            "SELECT id FROM shipment_notification_subscriptions WHERE tracking_number = ? AND email = ? AND is_active = 1",
            [$trackingNumber, $email]
        );

        $this->success(['subscribed' => (bool) $sub]);
    }

    /**
     * Show tracking result page (from direct URL)
     */
    public function show(string $number): void
    {
        $shipment = $this->db->fetch(
            "SELECT s.*, ss.name as status_name, ss.color as status_color
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.tracking_number = ? AND s.deleted_at IS NULL",
            [$number]
        );
        
        if (!$shipment) {
            $this->view('frontend/tracking', [
                'pageTitle' => 'Track Your Shipment',
                'error' => 'Shipment not found.',
            ]);
            return;
        }
        
        $history = $this->db->fetchAll(
            "SELECT th.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon
             FROM tracking_history th
             JOIN shipment_statuses ss ON th.status_id = ss.id
             WHERE th.shipment_id = ?
             ORDER BY th.created_at ASC",
            [$shipment->id]
        );
        
        $timeline = $this->db->fetchAll(
            "SELECT * FROM shipment_statuses WHERE is_active = 1 ORDER BY sort_order ASC"
        );
        
        $this->view('frontend/tracking', [
            'pageTitle' => "Tracking: {$shipment->tracking_number}",
            'shipment' => $shipment,
            'history' => $history,
            'timeline' => $timeline,
        ]);
    }
}
