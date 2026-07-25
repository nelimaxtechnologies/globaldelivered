<?php
/**
 * Global Delivered Logistics - Admin Invoice Management Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class InvoiceController extends Controller
{
    /**
     * Auto-mark overdue invoices (called on index load)
     */
    private function checkOverdueInvoices(): void
    {
        $this->db->query(
            "UPDATE invoices SET status = 'overdue', updated_at = NOW()
             WHERE status = 'sent' AND due_date IS NOT NULL AND due_date < CURDATE()"
        );
    }

    /**
     * List all invoices with search/filter and pagination
     */
    public function index(): void
    {
        $this->checkOverdueInvoices();

        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        $perPage = min(100, max(10, (int) ($_GET['per_page'] ?? 25)));

        $where = "WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (i.invoice_number LIKE ? OR s.tracking_number LIKE ? OR c.first_name LIKE ? OR c.last_name LIKE ? OR i.customer_id IN (SELECT id FROM customers WHERE CONCAT(first_name, ' ', last_name) LIKE ?))";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s, $s]);
        }

        if (!empty($status)) {
            $where .= " AND i.status = ?";
            $params[] = $status;
        }

        $stats = $this->db->fetch(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft,
                SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue,
                SUM(CASE WHEN status = 'partially_paid' THEN 1 ELSE 0 END) as partially_paid,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = 'refunded' THEN 1 ELSE 0 END) as refunded,
                COALESCE(SUM(total), 0) as total_amount,
                COALESCE(SUM(CASE WHEN status = 'paid' THEN total ELSE 0 END), 0) as paid_amount,
                COALESCE(SUM(CASE WHEN status IN ('sent','overdue') THEN total ELSE 0 END), 0) as outstanding_amount,
                COALESCE(SUM(CASE WHEN status = 'partially_paid' THEN total ELSE 0 END), 0) as partially_collected
             FROM invoices"
        );

        $overdueInvoices = $this->db->fetchAll(
            "SELECT i.id, i.invoice_number, i.total, i.due_date, i.currency,
                    DATEDIFF(CURDATE(), i.due_date) as days_overdue,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name
             FROM invoices i
             LEFT JOIN customers c ON i.customer_id = c.id
             WHERE i.status = 'overdue'
             ORDER BY i.due_date ASC
             LIMIT 5"
        );

        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM invoices i
             LEFT JOIN shipments s ON i.shipment_id = s.id
             LEFT JOIN customers c ON i.customer_id = c.id
             {$where}",
            "SELECT i.*, s.tracking_number,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name
             FROM invoices i
             LEFT JOIN shipments s ON i.shipment_id = s.id
             LEFT JOIN customers c ON i.customer_id = c.id
             {$where} ORDER BY i.created_at DESC",
            $params, $page, $perPage
        );

        $this->adminView('invoices/index', [
            'pageTitle' => 'Invoices Management',
            'invoices' => $paginated->data,
            'pagination' => $paginated,
            'stats' => $stats,
            'overdueInvoices' => $overdueInvoices,
            'filters' => ['search' => $search, 'status' => $status],
        ]);
    }

    /**
     * Show invoice creation form
     */
    public function create(): void
    {
        $customers = $this->db->fetchAll(
            "SELECT id, first_name, last_name, email FROM customers WHERE is_active = 1 ORDER BY first_name ASC"
        );
        $shipments = $this->db->fetchAll(
            "SELECT id, tracking_number, grand_total, currency, CONCAT(recipient_name, ' (', tracking_number, ')') as label
             FROM shipments WHERE deleted_at IS NULL AND payment_status IN ('pending', 'partially_paid')
             ORDER BY created_at DESC LIMIT 100"
        );

        $this->adminView('invoices/create', [
            'pageTitle' => 'Create Invoice',
            'customers' => $customers,
            'shipments' => $shipments,
            'nextNumber' => 'INV-' . date('Ymd') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT),
        ]);
    }

    /**
     * Store new invoice
     */
    public function store(): void
    {
        $data = $this->getPostData();

        $rules = [
            'invoice_number' => 'required',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ];

        $validated = $this->validate($data, $rules);

        try {
            $shipmentId = !empty($data['shipment_id']) ? (int) $data['shipment_id'] : null;
            $customerId = !empty($data['customer_id']) ? (int) $data['customer_id'] : null;

            $status = in_array($data['status'] ?? '', ['draft', 'sent']) ? $data['status'] : 'draft';

            $this->db->query(
                "INSERT INTO invoices (invoice_number, shipment_id, customer_id, subtotal, tax_percentage,
                 tax_amount, discount_amount, total, currency, status, due_date, notes, created_by, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    $data['invoice_number'],
                    $shipmentId,
                    $customerId,
                    (float) $data['subtotal'],
                    (float) ($data['tax_percentage'] ?? 0),
                    (float) ($data['tax_amount'] ?? 0),
                    (float) ($data['discount_amount'] ?? 0),
                    (float) $data['total'],
                    $data['currency'] ?? 'USD',
                    $status,
                    !empty($data['due_date']) ? $data['due_date'] : null,
                    $data['notes'] ?? '',
                    $_SESSION['user_id'],
                ]
            );

            $id = $this->db->lastInsertId();
            log_activity('invoice_created', 'invoice', $id);
            flash('success', "Invoice {$data['invoice_number']} created successfully!");
            $this->redirect("/admin/invoices/{$id}");

        } catch (\Exception $e) {
            error_log("Invoice creation error: " . $e->getMessage());
            flash('error', 'Failed to create invoice: ' . $e->getMessage());
            $this->back();
        }
    }

    /**
     * Show invoice details
     */
    public function show(int $id): void
    {
        $invoice = $this->db->fetch(
            "SELECT i.*, s.tracking_number, s.sender_name, s.recipient_name, s.service_type,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name, c.email as customer_email, c.phone as customer_phone,
                    CONCAT(u.first_name, ' ', u.last_name) as created_by_name
             FROM invoices i
             LEFT JOIN shipments s ON i.shipment_id = s.id
             LEFT JOIN customers c ON i.customer_id = c.id
             LEFT JOIN users u ON i.created_by = u.id
             WHERE i.id = ?",
            [$id]
        );

        if (!$invoice) {
            flash('error', 'Invoice not found.');
            $this->redirect('/admin/invoices');
        }

        $payments = $this->db->fetchAll(
            "SELECT * FROM payments WHERE invoice_id = ? ORDER BY created_at DESC",
            [$id]
        );

        $totalPaid = 0;
        foreach ($payments as $p) {
            if ($p->status === 'completed') {
                $totalPaid += (float) $p->amount;
            }
        }

        $this->adminView('invoices/show', [
            'pageTitle' => "Invoice: {$invoice->invoice_number}",
            'invoice' => $invoice,
            'payments' => $payments,
            'totalPaid' => $totalPaid,
        ]);
    }

    /**
     * Edit invoice form
     */
    public function edit(int $id): void
    {
        $invoice = $this->db->fetch("SELECT * FROM invoices WHERE id = ?", [$id]);

        if (!$invoice) {
            flash('error', 'Invoice not found.');
            $this->redirect('/admin/invoices');
        }

        $customers = $this->db->fetchAll(
            "SELECT id, first_name, last_name, email FROM customers WHERE is_active = 1 ORDER BY first_name ASC"
        );
        $shipments = $this->db->fetchAll(
            "SELECT id, tracking_number, grand_total, currency, CONCAT(recipient_name, ' (', tracking_number, ')') as label
             FROM shipments WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 100"
        );

        $this->adminView('invoices/edit', [
            'pageTitle' => "Edit Invoice: {$invoice->invoice_number}",
            'invoice' => $invoice,
            'customers' => $customers,
            'shipments' => $shipments,
        ]);
    }

    /**
     * Update invoice
     */
    public function update(int $id): void
    {
        $data = $this->getPostData();

        $allowedStatuses = ['draft', 'sent', 'paid', 'partially_paid', 'overdue', 'cancelled', 'refunded'];
        $status = in_array($data['status'] ?? '', $allowedStatuses) ? $data['status'] : 'draft';

        $paidAt = null;
        if ($status === 'paid') {
            $paidAt = date('Y-m-d H:i:s');
        }

        $this->db->query(
            "UPDATE invoices SET shipment_id=?, customer_id=?, subtotal=?, tax_percentage=?, tax_amount=?,
             discount_amount=?, total=?, currency=?, status=?, paid_at=?, due_date=?, notes=?, updated_at=NOW()
             WHERE id=?",
            [
                !empty($data['shipment_id']) ? (int) $data['shipment_id'] : null,
                !empty($data['customer_id']) ? (int) $data['customer_id'] : null,
                (float) $data['subtotal'],
                (float) ($data['tax_percentage'] ?? 0),
                (float) ($data['tax_amount'] ?? 0),
                (float) ($data['discount_amount'] ?? 0),
                (float) $data['total'],
                $data['currency'] ?? 'USD',
                $status,
                $paidAt,
                !empty($data['due_date']) ? $data['due_date'] : null,
                $data['notes'] ?? '',
                $id
            ]
        );

        log_activity('invoice_updated', 'invoice', $id);
        flash('success', 'Invoice updated successfully!');
        $this->redirect("/admin/invoices/{$id}");
    }

    /**
     * Delete invoice
     */
    public function destroy(int $id): void
    {
        $this->db->query("DELETE FROM payments WHERE invoice_id = ?", [$id]);
        $this->db->query("DELETE FROM invoices WHERE id = ?", [$id]);
        log_activity('invoice_deleted', 'invoice', $id);
        flash('success', 'Invoice deleted.');
        $this->redirect('/admin/invoices');
    }

    /**
     * Mark invoice as sent
     */
    public function markSent(int $id): void
    {
        $this->db->query("UPDATE invoices SET status = 'sent', updated_at = NOW() WHERE id = ?", [$id]);
        log_activity('invoice_marked_sent', 'invoice', $id);
        flash('success', 'Invoice marked as sent.');
        $this->back();
    }

    /**
     * Mark invoice as paid
     */
    public function markPaid(int $id): void
    {
        $this->db->query(
            "UPDATE invoices SET status = 'paid', paid_at = NOW(), updated_at = NOW() WHERE id = ?",
            [$id]
        );
        log_activity('invoice_marked_paid', 'invoice', $id);
        flash('success', 'Invoice marked as paid.');
        $this->back();
    }

    /**
     * Mark invoice as refunded
     */
    public function markRefunded(int $id): void
    {
        $this->db->query("UPDATE invoices SET status = 'refunded', updated_at = NOW() WHERE id = ?", [$id]);
        log_activity('invoice_marked_refunded', 'invoice', $id);
        flash('success', 'Invoice marked as refunded.');
        $this->back();
    }

    /**
     * Export invoices as CSV
     */
    public function export(): void
    {
        $search = sanitize($_GET['search'] ?? '');
        $status = sanitize($_GET['status'] ?? '');

        $where = "WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (i.invoice_number LIKE ? OR s.tracking_number LIKE ? OR c.first_name LIKE ? OR c.last_name LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }

        if (!empty($status)) {
            $where .= " AND i.status = ?";
            $params[] = $status;
        }

        $invoices = $this->db->fetchAll(
            "SELECT i.*, s.tracking_number,
                    CONCAT(c.first_name, ' ', c.last_name) as customer_name
             FROM invoices i
             LEFT JOIN shipments s ON i.shipment_id = s.id
             LEFT JOIN customers c ON i.customer_id = c.id
             {$where} ORDER BY i.created_at DESC",
            $params
        );

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=invoices_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Invoice #', 'Customer', 'Tracking #', 'Subtotal', 'Tax', 'Discount', 'Total', 'Currency', 'Status', 'Due Date', 'Paid At', 'Created']);

        foreach ($invoices as $inv) {
            fputcsv($output, [
                $inv->invoice_number,
                $inv->customer_name ?? '',
                $inv->tracking_number ?? '',
                $inv->subtotal,
                $inv->tax_amount,
                $inv->discount_amount,
                $inv->total,
                $inv->currency,
                $inv->status,
                $inv->due_date ?? '',
                $inv->paid_at ?? '',
                $inv->created_at,
            ]);
        }

        fclose($output);
        exit;
    }
}
