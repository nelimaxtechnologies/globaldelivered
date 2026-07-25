<?php
/**
 * Global Delivered Logistics - Shipment Model
 */

namespace App\Models;

use App\Core\Model;

class Shipment extends Model
{
    protected static string $table = 'shipments';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'tracking_number', 'customer_id', 'sender_name', 'sender_email', 'sender_phone',
        'sender_address', 'sender_city', 'sender_state', 'sender_country', 'sender_postal_code',
        'recipient_name', 'recipient_email', 'recipient_phone', 'recipient_address',
        'recipient_city', 'recipient_state', 'recipient_country', 'recipient_postal_code',
        'origin_branch_id', 'destination_branch_id', 'current_warehouse_id',
        'assigned_driver_id', 'assigned_vehicle_id',
        'service_type', 'package_type', 'weight', 'length', 'width', 'height',
        'description', 'declared_value', 'is_fragile', 'is_insured', 'insurance_amount',
        'is_cod', 'cod_amount', 'signature_required', 'reference_number', 'notes',
        'current_status_id', 'current_latitude', 'current_longitude', 'last_scan_at',
        'pickup_date', 'expected_delivery_date', 'actual_delivery_date',
        'delivered_to', 'delivery_signature', 'delivery_photo',
        'total_charges', 'tax_amount', 'grand_total', 'currency',
        'payment_status', 'payment_method', 'status', 'is_active', 'created_by'
    ];
    protected static array $searchable = [
        'tracking_number', 'sender_name', 'sender_email', 'sender_phone',
        'recipient_name', 'recipient_email', 'recipient_phone', 'reference_number'
    ];

    /**
     * Get tracking history
     */
    public function trackingHistory(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT th.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon,
                    w.name as warehouse_name,
                    CONCAT(u.first_name, ' ', u.last_name) as updated_by_name
             FROM tracking_history th
             JOIN shipment_statuses ss ON th.status_id = ss.id
             LEFT JOIN warehouses w ON th.warehouse_id = w.id
             LEFT JOIN users u ON th.updated_by = u.id
             WHERE th.shipment_id = ?
             ORDER BY th.created_at DESC",
            [$this->id]
        );
    }

    /**
     * Get current status
     */
    public function currentStatus(): ?object
    {
        if (!$this->current_status_id) return null;
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM shipment_statuses WHERE id = ?", [$this->current_status_id]);
    }

    /**
     * Get assigned driver
     */
    public function driver(): ?object
    {
        if (!$this->assigned_driver_id) return null;
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM drivers WHERE id = ?", [$this->assigned_driver_id]);
    }

    /**
     * Get customer
     */
    public function customer(): ?object
    {
        if (!$this->customer_id) return null;
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM customers WHERE id = ?", [$this->customer_id]);
    }

    /**
     * Get invoices
     */
    public function invoices(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM invoices WHERE shipment_id = ? ORDER BY created_at DESC",
            [$this->id]
        );
    }

    /**
     * Update shipment status and create tracking history entry
     */
    public function updateStatus(int $statusId, string $description = '', string $location = '', 
                                  ?float $latitude = null, ?float $longitude = null,
                                  ?int $warehouseId = null, string $source = 'admin'): bool
    {
        $db = \App\Core\Database::getInstance();
        
        try {
            $db->beginTransaction();
            
            // Create tracking history entry
            $db->query(
                "INSERT INTO tracking_history (shipment_id, status_id, location, latitude, longitude, 
                 warehouse_id, description, updated_by, source, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [$this->id, $statusId, $location, $latitude, $longitude, 
                 $warehouseId, $description, $_SESSION['user_id'] ?? null, $source]
            );
            
            // Update shipment
            $db->query(
                "UPDATE shipments SET current_status_id = ?, current_latitude = ?, current_longitude = ?,
                 last_scan_at = NOW() WHERE id = ?",
                [$statusId, $latitude, $longitude, $this->id]
            );
            
            $db->commit();
            
            // Log activity
            log_activity('shipment_status_updated', 'shipment', $this->id, null, [
                'tracking_number' => $this->tracking_number,
                'new_status_id' => $statusId,
            ]);
            
            return true;
        } catch (\Exception $e) {
            $db->rollback();
            error_log("Status update failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get status timeline for progress display
     */
    public static function getStatusTimeline(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM shipment_statuses WHERE is_active = 1 ORDER BY sort_order ASC"
        );
    }

    /**
     * Dashboard statistics
     */
    public static function getDashboardStats(): object
    {
        $db = \App\Core\Database::getInstance();
        
        $stats = $db->fetch(
            "SELECT 
                COUNT(*) as total_shipments,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_shipments,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'in_transit' THEN 1 ELSE 0 END) as in_transit,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                COALESCE(SUM(grand_total), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN DATE(created_at) = CURDATE() THEN grand_total ELSE 0 END), 0) as today_revenue
             FROM shipments 
             WHERE deleted_at IS NULL"
        );
        
        $stats->total_customers = $db->fetchColumn("SELECT COUNT(*) FROM customers WHERE deleted_at IS NULL");
        $stats->total_drivers = $db->fetchColumn("SELECT COUNT(*) FROM drivers WHERE deleted_at IS NULL");
        $stats->total_vehicles = $db->fetchColumn("SELECT COUNT(*) FROM vehicles WHERE deleted_at IS NULL");
        $stats->total_branches = $db->fetchColumn("SELECT COUNT(*) FROM branches WHERE deleted_at IS NULL");
        
        return $stats;
    }

    /**
     * Get monthly revenue for charts
     */
    public static function getMonthlyRevenue(int $months = 12): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as shipments,
                    COALESCE(SUM(grand_total), 0) as revenue
             FROM shipments 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
               AND deleted_at IS NULL
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY month ASC",
            [$months]
        );
    }

    /**
     * Get shipments by country
     */
    public static function getCountryStats(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT recipient_country as country, COUNT(*) as total
             FROM shipments 
             WHERE deleted_at IS NULL
             GROUP BY recipient_country
             ORDER BY total DESC
             LIMIT 20"
        );
    }
}
