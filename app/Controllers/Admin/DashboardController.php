<?php
/**
 * Global Delivered Logistics - Admin Dashboard Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Shipment;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index(): void
    {
        // Get today's date
        $today = date('Y-m-d');
        
        // Dashboard statistics
        $stats = $this->db->fetch(
            "SELECT 
                COUNT(*) as total_shipments,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_shipments,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_shipments,
                SUM(CASE WHEN status = 'in_transit' THEN 1 ELSE 0 END) as in_transit_shipments,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_shipments,
                SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned_shipments,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_shipments,
                COALESCE(SUM(grand_total), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN DATE(created_at) = CURDATE() THEN grand_total ELSE 0 END), 0) as today_revenue
             FROM shipments WHERE deleted_at IS NULL"
        );
        
        // Additional counts
        $stats->total_customers = $this->db->fetchColumn("SELECT COUNT(*) FROM customers WHERE deleted_at IS NULL");
        $stats->total_drivers = $this->db->fetchColumn("SELECT COUNT(*) FROM drivers WHERE deleted_at IS NULL AND is_active = 1");
        $stats->total_vehicles = $this->db->fetchColumn("SELECT COUNT(*) FROM vehicles WHERE deleted_at IS NULL AND is_active = 1");
        $stats->total_branches = $this->db->fetchColumn("SELECT COUNT(*) FROM branches WHERE deleted_at IS NULL AND is_active = 1");
        
        // Monthly revenue for last 12 months
        $monthlyRevenue = $this->db->fetchAll(
            "SELECT DATE_FORMAT(created_at, '%b') as month_name,
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as shipments,
                    COALESCE(SUM(grand_total), 0) as revenue
             FROM shipments 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
               AND deleted_at IS NULL
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY month ASC"
        );
        
        // Recent shipments
        $recentShipments = $this->db->fetchAll(
            "SELECT s.*, ss.name as status_name, ss.color as status_color
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.deleted_at IS NULL
             ORDER BY s.created_at DESC
             LIMIT 10"
        );
        
        // Shipments by country
        $countryStats = $this->db->fetchAll(
            "SELECT recipient_country as country, COUNT(*) as total
             FROM shipments WHERE deleted_at IS NULL
             GROUP BY recipient_country ORDER BY total DESC LIMIT 10"
        );
        
        // Recent activities
        $recentActivities = $this->db->fetchAll(
            "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as user_name
             FROM audit_logs a
             LEFT JOIN users u ON a.user_id = u.id
             ORDER BY a.created_at DESC LIMIT 10"
        );
        
        $this->adminView('dashboard', [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'monthlyRevenue' => $monthlyRevenue,
            'recentShipments' => $recentShipments,
            'countryStats' => $countryStats,
            'recentActivities' => $recentActivities,
        ]);
    }

    /**
     * Get dashboard stats (AJAX)
     */
    public function stats(): void
    {
        $stats = $this->db->fetch(
            "SELECT 
                COUNT(*) as total_shipments,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_shipments,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                COALESCE(SUM(grand_total), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN DATE(created_at) = CURDATE() THEN grand_total ELSE 0 END), 0) as today_revenue
             FROM shipments WHERE deleted_at IS NULL"
        );
        
        $this->success($stats);
    }

    /**
     * Get chart data (AJAX)
     */
    public function charts(): void
    {
        $monthlyRevenue = $this->db->fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as shipments,
                    COALESCE(SUM(grand_total), 0) as revenue
             FROM shipments 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
               AND deleted_at IS NULL
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY month ASC"
        );
        
        $this->success(['monthly_revenue' => $monthlyRevenue]);
    }
}
