<?php
/**
 * Global Delivered Logistics - Admin Payment Management Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class PaymentController extends Controller
{
    /**
     * List all payments with search/filter and pagination
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        $method = sanitize($_GET['method'] ?? '');
        $perPage = min(100, max(10, (int) ($_GET['per_page'] ?? 25)));

        $where = "WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (p.transaction_id LIKE ? OR p.payment_reference LIKE ? OR s.tracking_number LIKE ? OR c.first_name LIKE ? OR c.last_name LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s, $s]);
        }

        if (!empty($status)) {
            $where .= " AND p.status = ?";
            $params[] = $status;
        }

        if (!empty($method)) {
            $where .= " AND p.payment_method = ?";
            $params[] = $method;
        }

        // Payment stats
        $stats = $this->db->fetch(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                SUM(CASE WHEN status = 'refunded' THEN 1 ELSE 0 END) as refunded,
                COALESCE(SUM(amount), 0) as total_amount,
                COALESCE(SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END), 0) as completed_amount,
                COALESCE(SUM(CASE WHEN status = 'refunded' THEN amount ELSE 0 END), 0) as refunded_amount,
                COALESCE(SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END), 0) as pending_amount
             FROM payments"
        );

        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM payments p
             LEFT JOIN shipments s ON p.shipment_id = s.id
             LEFT JOIN customers c ON p.customer_id = c.id
             LEFT JOIN invoices i ON p.invoice_id = i.id
             {$where}",
            "SELECT p.*, s.tracking_number, i.invoice_number,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name,
                    CONCAT(u.first_name, ' ', u.last_name) as processed_by_name
             FROM payments p
             LEFT JOIN invoices i ON p.invoice_id = i.id
             LEFT JOIN shipments s ON p.shipment_id = s.id
             LEFT JOIN customers c ON p.customer_id = c.id
             LEFT JOIN users u ON p.processed_by = u.id
             {$where} ORDER BY p.created_at DESC",
            $params, $page, $perPage
        );

        $this->adminView('payments/index', [
            'pageTitle' => 'Payments Management',
            'payments' => $paginated->data,
            'pagination' => $paginated,
            'stats' => $stats,
            'filters' => ['search' => $search, 'status' => $status, 'method' => $method],
        ]);
    }

    /**
     * Show payment details
     */
    public function show(int $id): void
    {
        $payment = $this->db->fetch(
            "SELECT p.*, s.tracking_number, i.invoice_number, i.total as invoice_total,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name, c.email as customer_email, c.phone as customer_phone,
                    CONCAT(u.first_name, ' ', u.last_name) as processed_by_name
             FROM payments p
             LEFT JOIN invoices i ON p.invoice_id = i.id
             LEFT JOIN shipments s ON p.shipment_id = s.id
             LEFT JOIN customers c ON p.customer_id = c.id
             LEFT JOIN users u ON p.processed_by = u.id
             WHERE p.id = ?",
            [$id]
        );
        
        if (!$payment) {
            flash('error', 'Payment not found.');
            $this->redirect('/admin/payments');
        }
        
        $this->adminView('payments/show', [
            'pageTitle' => "Payment: {$payment->transaction_id}",
            'payment' => $payment,
        ]);
    }

    /**
     * Show payment creation form
     */
    public function create(): void
    {
        $invoices = $this->db->fetchAll(
            "SELECT i.id, i.invoice_number, i.total, i.status, i.customer_id, i.currency,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name,
                    COALESCE((SELECT SUM(amount) FROM payments WHERE invoice_id = i.id AND status = 'completed'), 0) as amount_paid
             FROM invoices i
             LEFT JOIN customers c ON i.customer_id = c.id
             WHERE i.status IN ('sent', 'partially_paid', 'overdue')
             ORDER BY i.created_at DESC"
        );

        $customers = $this->db->fetchAll(
            "SELECT id, first_name, last_name, email FROM customers WHERE is_active = 1 ORDER BY first_name"
        );

        $this->adminView('payments/create', [
            'pageTitle' => 'Record Payment',
            'invoices' => $invoices,
            'customers' => $customers,
        ]);
    }

    /**
     * Store new payment
     */
    public function store(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required',
        ];
        
        $validated = $this->validate($data, $rules);
        
        try {
            $invoiceId = !empty($data['invoice_id']) ? (int) $data['invoice_id'] : null;
            $customerId = !empty($data['customer_id']) ? (int) $data['customer_id'] : null;
            $shipmentId = null;
            
            // Get shipment from invoice if available
            if ($invoiceId) {
                $invoice = $this->db->fetch("SELECT shipment_id FROM invoices WHERE id = ?", [$invoiceId]);
                $shipmentId = $invoice ? $invoice->shipment_id : null;
            }
            
            $transactionId = $data['transaction_id'] ?? 'TXN-' . strtoupper(bin2hex(random_bytes(8)));
            
            $this->db->beginTransaction();
            
            $this->db->query(
                "INSERT INTO payments (invoice_id, shipment_id, customer_id, transaction_id, payment_method,
                 amount, currency, payment_reference, status, notes, processed_by, processed_at, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'completed', ?, ?, NOW(), NOW())",
                [
                    $invoiceId,
                    $shipmentId,
                    $customerId,
                    $transactionId,
                    $data['payment_method'],
                    (float) $data['amount'],
                    $data['currency'] ?? 'USD',
                    $data['payment_reference'] ?? '',
                    $data['notes'] ?? '',
                    $_SESSION['user_id'],
                ]
            );
            
            // Update invoice status
            if ($invoiceId) {
                // Check if fully paid
                $totalPaid = (float) $this->db->fetchColumn(
                    "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE invoice_id = ? AND status = 'completed'",
                    [$invoiceId]
                );
                $invoiceTotal = (float) $this->db->fetchColumn("SELECT total FROM invoices WHERE id = ?", [$invoiceId]);
                
                $newStatus = $totalPaid >= $invoiceTotal ? 'paid' : 'partially_paid';
                $this->db->query(
                    "UPDATE invoices SET status = ?, paid_at = IF(? = 'paid', NOW(), paid_at), updated_at = NOW() WHERE id = ?",
                    [$newStatus, $newStatus, $invoiceId]
                );
            }
            
            // Update shipment payment status if applicable
            if ($shipmentId) {
                $this->db->query(
                    "UPDATE shipments SET payment_status = 'paid', updated_at = NOW() WHERE id = ? AND payment_status = 'pending'",
                    [$shipmentId]
                );
            }
            
            $this->db->commit();
            
            $paymentId = $this->db->lastInsertId();
            log_activity('payment_created', 'payment', $paymentId);
            
            flash('success', 'Payment recorded successfully!');
            
            if ($invoiceId) {
                $this->redirect("/admin/invoices/{$invoiceId}");
            }
            $this->redirect('/admin/payments');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Payment creation error: " . $e->getMessage());
            flash('error', 'Failed to record payment: ' . $e->getMessage());
            $this->back();
        }
    }

    /**
     * Delete payment
     */
    public function destroy(int $id): void
    {
        $payment = $this->db->fetch("SELECT * FROM payments WHERE id = ?", [$id]);

        if (!$payment) {
            flash('error', 'Payment not found.');
            $this->redirect('/admin/payments');
        }

        $this->db->beginTransaction();

        $this->db->query("DELETE FROM payments WHERE id = ?", [$id]);

        // Recalculate invoice status
        if ($payment->invoice_id) {
            $totalPaid = (float) $this->db->fetchColumn(
                "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE invoice_id = ? AND status = 'completed'",
                [$payment->invoice_id]
            );
            $invoiceTotal = (float) $this->db->fetchColumn("SELECT total FROM invoices WHERE id = ?", [$payment->invoice_id]);

            $newStatus = $totalPaid <= 0 ? 'sent' : ($totalPaid < $invoiceTotal ? 'partially_paid' : 'paid');
            $this->db->query(
                "UPDATE invoices SET status = ?, paid_at = IF(? = 'paid', NOW(), NULL), updated_at = NOW() WHERE id = ?",
                [$newStatus, $newStatus, $payment->invoice_id]
            );

            // Update shipment payment status if applicable
            if ($payment->shipment_id) {
                $newPaymentStatus = $newStatus === 'paid' ? 'paid' : 'pending';
                $this->db->query(
                    "UPDATE shipments SET payment_status = ?, updated_at = NOW() WHERE id = ?",
                    [$newPaymentStatus, $payment->shipment_id]
                );
            }
        }

        $this->db->commit();

        log_activity('payment_deleted', 'payment', $id);
        flash('success', 'Payment deleted.');
        $this->redirect('/admin/payments');
    }
}
