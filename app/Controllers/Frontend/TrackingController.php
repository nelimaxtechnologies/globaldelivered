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
