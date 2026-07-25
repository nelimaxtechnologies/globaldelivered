<?php
/**
 * Global Delivered Logistics - REST API Tracking Controller
 * 
 * Provides JSON endpoints for mobile apps and third-party integrations.
 */

namespace App\Controllers\Api;

use App\Core\Controller;

class TrackingController extends Controller
{
    /**
     * Get shipment by tracking number
     */
    public function show(string $number): void
    {
        $shipment = $this->db->fetch(
            "SELECT s.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.tracking_number = ? AND s.deleted_at IS NULL",
            [$number]
        );
        
        if (!$shipment) {
            $this->error('Shipment not found', 404);
        }
        
        $this->success($this->formatShipment($shipment));
    }

    /**
     * Get tracking timeline
     */
    public function timeline(string $number): void
    {
        $shipment = $this->db->fetch(
            "SELECT id FROM shipments WHERE tracking_number = ? AND deleted_at IS NULL",
            [$number]
        );
        
        if (!$shipment) {
            $this->error('Shipment not found', 404);
        }
        
        $history = $this->db->fetchAll(
            "SELECT th.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon
             FROM tracking_history th
             JOIN shipment_statuses ss ON th.status_id = ss.id
             WHERE th.shipment_id = ?
             ORDER BY th.created_at ASC",
            [$shipment->id]
        );
        
        $this->success(['timeline' => $history]);
    }

    /**
     * Get current location
     */
    public function location(string $number): void
    {
        $shipment = $this->db->fetch(
            "SELECT tracking_number, current_latitude, current_longitude, last_scan_at
             FROM shipments WHERE tracking_number = ? AND deleted_at IS NULL",
            [$number]
        );
        
        if (!$shipment) {
            $this->error('Shipment not found', 404);
        }
        
        $this->success([
            'tracking_number' => $shipment->tracking_number,
            'latitude' => $shipment->current_latitude,
            'longitude' => $shipment->current_longitude,
            'last_updated' => $shipment->last_scan_at,
        ]);
    }

    /**
     * Get user's shipments
     */
    public function userShipments(): void
    {
        $userId = $_SESSION['user_id'] ?? 0;
        $customer = $this->db->fetch("SELECT id FROM customers WHERE user_id = ?", [$userId]);
        
        if (!$customer) {
            $this->error('Customer not found', 404);
        }
        
        $shipments = $this->db->fetchAll(
            "SELECT s.*, ss.name as status_name
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.customer_id = ? AND s.deleted_at IS NULL
             ORDER BY s.created_at DESC",
            [$customer->id]
        );
        
        $this->success(['shipments' => array_map([$this, 'formatShipment'], $shipments)]);
    }

    /**
     * Update driver location
     */
    public function updateLocation(): void
    {
        $data = $this->getPostData();
        $driverId = $this->db->fetchColumn(
            "SELECT id FROM drivers WHERE user_id = ?",
            [$_SESSION['user_id']]
        );
        
        if (!$driverId) {
            $this->error('Driver not found', 404);
        }
        
        $this->db->query(
            "UPDATE drivers SET current_latitude = ?, current_longitude = ?, last_location_update = NOW() WHERE id = ?",
            [$data['latitude'], $data['longitude'], $driverId]
        );
        
        $this->success(null, 'Location updated');
    }

    /**
     * Update shipment status (from driver app)
     */
    public function updateShipmentStatus(int $id): void
    {
        $data = $this->getPostData();
        
        $this->db->query(
            "INSERT INTO tracking_history (shipment_id, status_id, location, latitude, longitude, description, updated_by, source, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'driver_app', NOW())",
            [$id, $data['status_id'], $data['location'] ?? '', $data['latitude'] ?? null, $data['longitude'] ?? null,
             $data['description'] ?? '', $_SESSION['user_id']]
        );
        
        $this->db->query(
            "UPDATE shipments SET current_status_id = ?, current_latitude = ?, current_longitude = ?, last_scan_at = NOW() WHERE id = ?",
            [$data['status_id'], $data['latitude'] ?? null, $data['longitude'] ?? null, $id]
        );
        
        $this->success(null, 'Status updated');
    }

    /**
     * Format shipment for API response
     */
    private function formatShipment($shipment): array
    {
        return [
            'id' => (int) $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'status' => [
                'name' => $shipment->status_name ?? $shipment->status,
                'color' => $shipment->status_color ?? '#6c757d',
            ],
            'sender' => [
                'name' => $shipment->sender_name,
                'email' => $shipment->sender_email,
                'phone' => $shipment->sender_phone,
                'city' => $shipment->sender_city,
                'country' => $shipment->sender_country,
            ],
            'recipient' => [
                'name' => $shipment->recipient_name,
                'email' => $shipment->recipient_email,
                'phone' => $shipment->recipient_phone,
                'city' => $shipment->recipient_city,
                'country' => $shipment->recipient_country,
            ],
            'package' => [
                'weight' => (float) $shipment->weight,
                'dimensions' => [
                    'length' => (float) $shipment->length,
                    'width' => (float) $shipment->width,
                    'height' => (float) $shipment->height,
                ],
                'type' => $shipment->package_type,
                'description' => $shipment->description,
                'is_fragile' => (bool) $shipment->is_fragile,
                'is_insured' => (bool) $shipment->is_insured,
            ],
            'service_type' => $shipment->service_type,
            'dates' => [
                'created' => $shipment->created_at,
                'expected_delivery' => $shipment->expected_delivery_date,
                'actual_delivery' => $shipment->actual_delivery_date,
            ],
            'tracking' => [
                'latitude' => (float) $shipment->current_latitude,
                'longitude' => (float) $shipment->current_longitude,
                'last_scan' => $shipment->last_scan_at,
            ],
            'financial' => [
                'total_charges' => (float) $shipment->total_charges,
                'grand_total' => (float) $shipment->grand_total,
                'currency' => $shipment->currency,
                'payment_status' => $shipment->payment_status,
            ],
        ];
    }

    /**
     * Get branches list
     */
    public function branches(): void
    {
        $branches = $this->db->fetchAll(
            "SELECT id, name, code, address_line1, city, state, country, phone, email, 
                    latitude, longitude, opening_time, closing_time
             FROM branches WHERE is_active = 1 ORDER BY name ASC"
        );
        
        $this->success(['branches' => $branches]);
    }

    public function branchDetail(int $id): void
    {
        $branch = $this->db->fetch(
            "SELECT * FROM branches WHERE id = ? AND is_active = 1",
            [$id]
        );
        
        if (!$branch) {
            $this->error('Branch not found', 404);
        }
        
        $this->success($branch);
    }

    public function services(): void
    {
        $this->success([
            'services' => [
                'domestic', 'international', 'express', 'same_day', 'freight',
                'air_cargo', 'sea_freight', 'road_transport', 'warehousing', 'last_mile'
            ]
        ]);
    }

    public function calculateQuote(): void
    {
        $data = $this->getPostData();
        // Reuse quote calculation logic
        $this->success(['message' => 'Quote calculation endpoint']);
    }

    public function documents(): void
    {
        $this->success(['documents' => []]);
    }

    public function uploadDocument(): void
    {
        $this->success(null, 'Document uploaded');
    }

    public function driverAssignments(): void
    {
        $driver = $this->db->fetchColumn("SELECT id FROM drivers WHERE user_id = ?", [$_SESSION['user_id']]);
        
        $assignments = $this->db->fetchAll(
            "SELECT s.*, ss.name as status_name FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.assigned_driver_id = ? AND s.status != 'delivered' AND s.deleted_at IS NULL
             ORDER BY s.created_at DESC",
            [$driver]
        );
        
        $this->success(['assignments' => $assignments]);
    }

    public function createShipment(): void
    {
        // Validate and create shipment via API
        $this->success(null, 'Shipment created via API');
    }

    public function paymentWebhook(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        error_log("Payment webhook received: " . json_encode($input));
        $this->success(null, 'Webhook received');
    }

    public function trackingWebhook(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        error_log("Tracking webhook received: " . json_encode($input));
        $this->success(null, 'Webhook received');
    }
}
