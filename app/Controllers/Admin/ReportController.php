<?php
/**
 * Global Delivered Logistics - Admin Reports Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class ReportController extends Controller
{
    public function index(): void
    {
        $totalRevenue = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'completed'"
        );

        $totalShipments = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM shipments WHERE deleted_at IS NULL"
        );

        $deliveredToday = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM shipments WHERE status = 'delivered' AND DATE(actual_delivery_date) = CURDATE()"
        );

        $avgDeliveryTime = $this->db->fetchColumn(
            "SELECT ROUND(AVG(TIMESTAMPDIFF(HOUR, created_at, actual_delivery_date)), 1)
             FROM shipments WHERE status = 'delivered' AND actual_delivery_date IS NOT NULL"
        );

        // This month stats
        $monthRevenue = $this->db->fetchColumn(
            "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'completed' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"
        );

        $monthShipments = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM shipments WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"
        );

        $totalCustomers = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM customers WHERE is_active = 1"
        );

        $totalDrivers = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM drivers"
        );

        $pendingInvoices = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM invoices WHERE status IN ('sent', 'overdue', 'partially_paid')"
        );

        $delayedCount = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM shipments WHERE deleted_at IS NULL
             AND status NOT IN ('delivered', 'cancelled', 'returned')
             AND expected_delivery_date IS NOT NULL AND expected_delivery_date < NOW()"
        );

        // Revenue trend (last 7 days)
        $revenueTrend = $this->db->fetchAll(
            "SELECT DATE(created_at) as date, COALESCE(SUM(amount), 0) as total
             FROM payments WHERE status = 'completed'
             AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
             GROUP BY DATE(created_at) ORDER BY date ASC"
        );

        $this->adminView('reports/index', [
            'pageTitle' => 'Reports & Analytics',
            'totalRevenue' => $totalRevenue,
            'totalShipments' => $totalShipments,
            'deliveredToday' => $deliveredToday,
            'avgDeliveryTime' => $avgDeliveryTime ?? 0,
            'monthRevenue' => $monthRevenue,
            'monthShipments' => $monthShipments,
            'totalCustomers' => $totalCustomers,
            'totalDrivers' => $totalDrivers,
            'pendingInvoices' => $pendingInvoices,
            'delayedCount' => $delayedCount,
            'revenueTrend' => $revenueTrend,
        ]);
    }

    public function show(string $type): void
    {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        switch ($type) {
            case 'revenue':
                $data = $this->revenueReport($startDate, $endDate);
                $view = 'reports/revenue';
                break;
            case 'shipments':
                $data = $this->shipmentsReport($startDate, $endDate);
                $view = 'reports/shipments';
                break;
            case 'customers':
                $data = $this->customersReport($startDate, $endDate);
                $view = 'reports/customers';
                break;
            case 'drivers':
                $data = $this->driversReport($startDate, $endDate);
                $view = 'reports/drivers';
                break;
            case 'delayed':
                $data = $this->delayedPackagesReport();
                $view = 'reports/delayed';
                break;
            default:
                flash('error', 'Report type not found.');
                $this->redirect('/admin/reports');
        }

        $this->adminView($view, array_merge($data, [
            'pageTitle' => ucfirst($type) . ' Report',
            'reportType' => $type,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]));
    }

    public function export(): void
    {
        $type = sanitize($_POST['type'] ?? '');
        $format = sanitize($_POST['format'] ?? 'csv');
        $startDate = sanitize($_POST['start_date'] ?? date('Y-m-01'));
        $endDate = sanitize($_POST['end_date'] ?? date('Y-m-d'));

        $data = [];
        $filename = "{$type}_report_{$startDate}_to_{$endDate}";

        switch ($type) {
            case 'revenue':
                $data = $this->getRevenueData($startDate, $endDate);
                $headers = ['Date', 'Total Revenue', 'Cash', 'Bank', 'Online', 'Transactions'];
                break;
            case 'shipments':
                $data = $this->getShipmentStatsData($startDate, $endDate);
                $headers = ['Date', 'Total', 'Pending', 'Active', 'Delivered', 'Returned', 'Cancelled'];
                break;
            case 'customers':
                $data = $this->getCustomerReportData($startDate, $endDate);
                $headers = ['Customer Name', 'Email', 'Phone', 'Total Shipments', 'Total Spent', 'Last Shipment'];
                break;
            case 'drivers':
                $data = $this->getDriverReportData($startDate, $endDate);
                $headers = ['Driver', 'Phone', 'Status', 'Deliveries', 'Revenue', 'Avg Delivery Hours'];
                break;
            case 'delayed':
                $data = $this->getDelayedData();
                $headers = ['Tracking', 'Driver', 'Status', 'Expected Delivery', 'Days Overdue'];
                break;
            default:
                flash('error', 'Invalid report type.');
                $this->back();
        }

        if ($format === 'csv') {
            $this->exportCsv($filename, $headers, $data);
        } else {
            $this->exportExcel($filename, $headers, $data);
        }
    }

    // ------------------------------------------------
    // Report Data Methods
    // ------------------------------------------------

    private function revenueReport(string $startDate, string $endDate): array
    {
        $revenueData = $this->getRevenueData($startDate, $endDate);

        $totals = $this->db->fetch(
            "SELECT
                COALESCE(SUM(amount), 0) as total,
                COUNT(*) as count,
                COALESCE(SUM(CASE WHEN payment_method = 'cash' THEN amount ELSE 0 END), 0) as cash_total,
                COALESCE(SUM(CASE WHEN payment_method = 'bank' THEN amount ELSE 0 END), 0) as bank_total,
                COALESCE(SUM(CASE WHEN payment_method = 'stripe' THEN amount ELSE 0 END), 0) as stripe_total,
                COALESCE(SUM(CASE WHEN payment_method = 'paypal' THEN amount ELSE 0 END), 0) as paypal_total,
                COALESCE(SUM(CASE WHEN payment_method = 'flutterwave' THEN amount ELSE 0 END), 0) as flutterwave_total,
                COALESCE(SUM(CASE WHEN payment_method = 'paystack' THEN amount ELSE 0 END), 0) as paystack_total,
                COALESCE(SUM(CASE WHEN payment_method = 'mpesa' THEN amount ELSE 0 END), 0) as mpesa_total,
                ROUND(AVG(amount), 2) as avg_payment
             FROM payments
             WHERE status = 'completed' AND DATE(created_at) BETWEEN ? AND ?",
            [$startDate, $endDate]
        );

        // Payment method breakdown
        $byMethod = $this->db->fetchAll(
            "SELECT payment_method, COUNT(*) as count, COALESCE(SUM(amount), 0) as total
             FROM payments WHERE status = 'completed' AND DATE(created_at) BETWEEN ? AND ?
             GROUP BY payment_method ORDER BY total DESC",
            [$startDate, $endDate]
        );

        return [
            'revenueData' => $revenueData,
            'totals' => $totals,
            'byMethod' => $byMethod,
        ];
    }

    private function shipmentsReport(string $startDate, string $endDate): array
    {
        $stats = $this->getShipmentStatsData($startDate, $endDate);

        $byService = $this->db->fetchAll(
            "SELECT service_type, COUNT(*) as count, COALESCE(SUM(grand_total), 0) as revenue
             FROM shipments WHERE deleted_at IS NULL AND DATE(created_at) BETWEEN ? AND ?
             GROUP BY service_type ORDER BY count DESC",
            [$startDate, $endDate]
        );

        // Overall totals for the period
        $periodTotals = $this->db->fetch(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status IN ('active','in_transit') THEN 1 ELSE 0 END) as in_transit,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                COALESCE(SUM(grand_total), 0) as revenue,
                ROUND(AVG(TIMESTAMPDIFF(HOUR, created_at, actual_delivery_date)), 1) as avg_hours
             FROM shipments WHERE deleted_at IS NULL AND DATE(created_at) BETWEEN ? AND ?",
            [$startDate, $endDate]
        );

        return [
            'stats' => $stats,
            'byService' => $byService,
            'periodTotals' => $periodTotals,
        ];
    }

    private function customersReport(string $startDate, string $endDate): array
    {
        $customers = $this->getCustomerReportData($startDate, $endDate);

        $totals = $this->db->fetch(
            "SELECT COUNT(DISTINCT customer_id) as total_customers,
                    COUNT(*) as total_shipments,
                    COALESCE(SUM(grand_total), 0) as total_revenue
             FROM shipments
             WHERE deleted_at IS NULL AND customer_id IS NOT NULL
             AND DATE(created_at) BETWEEN ? AND ?",
            [$startDate, $endDate]
        );

        // Top customer
        $topCustomer = !empty($customers) ? $customers[0] : null;

        return [
            'customers' => $customers,
            'totals' => $totals,
            'topCustomer' => $topCustomer,
        ];
    }

    private function driversReport(string $startDate, string $endDate): array
    {
        $drivers = $this->db->fetchAll(
            "SELECT d.id, d.first_name, d.last_name, d.phone, d.status,
                    COUNT(s.id) as total_deliveries,
                    COALESCE(SUM(s.grand_total), 0) as total_revenue,
                    SUM(CASE WHEN s.status = 'delivered' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN s.status = 'returned' THEN 1 ELSE 0 END) as returned,
                    ROUND(AVG(CASE WHEN s.actual_delivery_date IS NOT NULL
                        THEN TIMESTAMPDIFF(HOUR, s.created_at, s.actual_delivery_date)
                        ELSE NULL END), 1) as avg_delivery_hours
             FROM drivers d
             LEFT JOIN shipments s ON d.id = s.assigned_driver_id AND s.deleted_at IS NULL
                AND DATE(s.created_at) BETWEEN ? AND ?
             GROUP BY d.id
             ORDER BY total_deliveries DESC",
            [$startDate, $endDate]
        );

        // Period summary
        $periodTotals = $this->db->fetch(
            "SELECT COUNT(DISTINCT assigned_driver_id) as active_drivers,
                    COUNT(*) as total_shipments,
                    COALESCE(SUM(grand_total), 0) as total_revenue,
                    ROUND(AVG(TIMESTAMPDIFF(HOUR, created_at, actual_delivery_date)), 1) as avg_hours
             FROM shipments WHERE deleted_at IS NULL
             AND DATE(created_at) BETWEEN ? AND ? AND assigned_driver_id IS NOT NULL",
            [$startDate, $endDate]
        );

        return [
            'drivers' => $drivers,
            'periodTotals' => $periodTotals,
        ];
    }

    private function delayedPackagesReport(): array
    {
        $delayed = $this->db->fetchAll(
            "SELECT s.*, ss.name as status_name, ss.color as status_color,
                    CONCAT(d.first_name, ' ', d.last_name) as driver_name
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             LEFT JOIN drivers d ON s.assigned_driver_id = d.id
             WHERE s.deleted_at IS NULL
             AND s.status NOT IN ('delivered', 'cancelled', 'returned')
             AND s.expected_delivery_date IS NOT NULL
             AND s.expected_delivery_date < NOW()
             ORDER BY s.expected_delivery_date ASC"
        );

        $totalValue = 0;
        $critical = 0;
        $warning = 0;
        foreach ($delayed as $d) {
            $totalValue += (float)($d->grand_total ?? 0);
            $days = floor((time() - strtotime($d->expected_delivery_date)) / 86400);
            if ($days > 7) $critical++;
            elseif ($days > 3) $warning++;
        }

        return [
            'delayed' => $delayed,
            'delayedCount' => count($delayed),
            'totalValue' => $totalValue,
            'criticalCount' => $critical,
            'warningCount' => $warning,
        ];
    }

    private function getRevenueData(string $startDate, string $endDate): array
    {
        return $this->db->fetchAll(
            "SELECT DATE(created_at) as date,
                    COALESCE(SUM(amount), 0) as total,
                    COUNT(*) as count,
                    COALESCE(SUM(CASE WHEN payment_method = 'cash' THEN amount ELSE 0 END), 0) as cash,
                    COALESCE(SUM(CASE WHEN payment_method = 'bank' THEN amount ELSE 0 END), 0) as bank,
                    COALESCE(SUM(CASE WHEN payment_method IN ('stripe', 'paypal', 'flutterwave', 'paystack', 'mpesa') THEN amount ELSE 0 END), 0) as online
             FROM payments
             WHERE status = 'completed' AND DATE(created_at) BETWEEN ? AND ?
             GROUP BY DATE(created_at)
             ORDER BY date ASC",
            [$startDate, $endDate]
        );
    }

    private function getShipmentStatsData(string $startDate, string $endDate): array
    {
        return $this->db->fetchAll(
            "SELECT DATE(created_at) as date,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status IN ('active', 'in_transit') THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                    SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
             FROM shipments
             WHERE deleted_at IS NULL AND DATE(created_at) BETWEEN ? AND ?
             GROUP BY DATE(created_at)
             ORDER BY date ASC",
            [$startDate, $endDate]
        );
    }

    private function getCustomerReportData(string $startDate, string $endDate): array
    {
        return $this->db->fetchAll(
            "SELECT c.id, c.first_name, c.last_name, c.email, c.phone,
                    COUNT(s.id) as total_shipments,
                    COALESCE(SUM(s.grand_total), 0) as total_spent,
                    MAX(s.created_at) as last_shipment_date
             FROM customers c
             LEFT JOIN shipments s ON c.id = s.customer_id AND s.deleted_at IS NULL
                AND DATE(s.created_at) BETWEEN ? AND ?
             GROUP BY c.id
             HAVING total_shipments > 0
             ORDER BY total_spent DESC
             LIMIT 100",
            [$startDate, $endDate]
        );
    }

    private function getDriverReportData(string $startDate, string $endDate): array
    {
        return $this->db->fetchAll(
            "SELECT d.first_name, d.last_name, d.phone, d.status,
                    COUNT(s.id) as total_deliveries,
                    COALESCE(SUM(s.grand_total), 0) as total_revenue,
                    ROUND(AVG(CASE WHEN s.actual_delivery_date IS NOT NULL
                        THEN TIMESTAMPDIFF(HOUR, s.created_at, s.actual_delivery_date)
                        ELSE NULL END), 1) as avg_delivery_hours
             FROM drivers d
             LEFT JOIN shipments s ON d.id = s.assigned_driver_id AND s.deleted_at IS NULL
                AND DATE(s.created_at) BETWEEN ? AND ?
             GROUP BY d.id ORDER BY total_deliveries DESC",
            [$startDate, $endDate]
        );
    }

    private function getDelayedData(): array
    {
        return $this->db->fetchAll(
            "SELECT s.tracking_number, CONCAT(d.first_name, ' ', d.last_name) as driver_name,
                    s.status, s.expected_delivery_date,
                    DATEDIFF(NOW(), s.expected_delivery_date) as days_overdue
             FROM shipments s
             LEFT JOIN drivers d ON s.assigned_driver_id = d.id
             WHERE s.deleted_at IS NULL
             AND s.status NOT IN ('delivered', 'cancelled', 'returned')
             AND s.expected_delivery_date IS NOT NULL AND s.expected_delivery_date < NOW()
             ORDER BY s.expected_delivery_date ASC"
        );
    }

    // ------------------------------------------------
    // Export Methods
    // ------------------------------------------------

    private function exportCsv(string $filename, array $headers, array $data): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);

        foreach ($data as $row) {
            fputcsv($output, (array) $row);
        }

        fclose($output);
        exit;
    }

    private function exportExcel(string $filename, array $headers, array $data): void
    {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');

        echo '<html><body><table border="1">';
        echo '<tr>';
        foreach ($headers as $h) {
            echo '<th style="background:#1a237e;color:#fff;padding:8px;">' . htmlspecialchars($h) . '</th>';
        }
        echo '</tr>';

        foreach ($data as $row) {
            echo '<tr>';
            foreach ((array) $row as $cell) {
                echo '<td style="padding:6px;">' . htmlspecialchars((string) $cell) . '</td>';
            }
            echo '</tr>';
        }

        echo '</table></body></html>';
        exit;
    }
}
