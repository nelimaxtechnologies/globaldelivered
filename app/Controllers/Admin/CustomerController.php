<?php
/**
 * Global Delivered Logistics - Admin Customer Management Controller
 * Full CRUD with search, pagination, and shipment history viewing.
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class CustomerController extends Controller
{
    /**
     * List all customers with search and pagination
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $type = sanitize($_GET['type'] ?? '');
        
        $where = "WHERE c.deleted_at IS NULL";
        $params = [];
        
        if (!empty($search)) {
            $where .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ? OR c.company_name LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s, $s]);
        }
        
        if (!empty($type)) {
            $where .= " AND c.customer_type = ?";
            $params[] = $type;
        }
        
        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM customers c {$where}",
            "SELECT c.*, 
                    (SELECT COUNT(*) FROM shipments WHERE customer_id = c.id AND deleted_at IS NULL) as total_shipments,
                    (SELECT COALESCE(SUM(grand_total), 0) FROM shipments WHERE customer_id = c.id AND deleted_at IS NULL) as total_spent
             FROM customers c {$where} ORDER BY c.created_at DESC",
            $params, $page, 25
        );
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN customer_type = 'individual' THEN 1 ELSE 0 END) as individual_count,
                    SUM(CASE WHEN customer_type = 'company' THEN 1 ELSE 0 END) as company_count,
                    (SELECT COALESCE(SUM(grand_total), 0) FROM shipments WHERE customer_id IN (SELECT id FROM customers WHERE deleted_at IS NULL) AND deleted_at IS NULL) as total_revenue
             FROM customers WHERE deleted_at IS NULL"
        );
        
        $this->adminView('customers/index', [
            'pageTitle' => 'Customers Management',
            'customers' => $paginated->data,
            'pagination' => $paginated,
            'filters' => ['search' => $search, 'type' => $type],
            'stats' => $stats,
        ]);
    }

    /**
     * Show customer creation form
     */
    public function create(): void
    {
        $this->adminView('customers/create', [
            'pageTitle' => 'Create Customer',
        ]);
    }

    /**
     * Store new customer
     */
    public function store(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ];
        
        $validated = $this->validate($data, $rules);
        
        try {
            $this->db->query(
                "INSERT INTO customers (customer_type, company_name, first_name, last_name, email, phone, alternative_phone, 
                 address_line1, city, state, country, postal_code, notes, is_active, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())",
                [
                    $data['customer_type'] ?? 'individual',
                    $data['company_name'] ?? '',
                    $data['first_name'],
                    $data['last_name'],
                    $data['email'],
                    $data['phone'],
                    $data['alternative_phone'] ?? '',
                    $data['address_line1'] ?? '',
                    $data['city'] ?? '',
                    $data['state'] ?? '',
                    $data['country'] ?? '',
                    $data['postal_code'] ?? '',
                    $data['notes'] ?? '',
                ]
            );
            
            log_activity('customer_created', 'customer', $this->db->lastInsertId());
            flash('success', 'Customer created successfully!');
            $this->redirect('/admin/customers');
            
        } catch (\Exception $e) {
            error_log("Customer creation error: " . $e->getMessage());
            flash('error', 'Failed to create customer.');
            $this->back();
        }
    }

    /**
     * Show customer details with shipment history
     */
    public function show(int $id): void
    {
        $customer = $this->db->fetch("SELECT * FROM customers WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$customer) {
            flash('error', 'Customer not found.');
            $this->redirect('/admin/customers');
        }
        
        $shipments = $this->db->fetchAll(
            "SELECT s.*, ss.name as status_name, ss.color as status_color
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.customer_id = ? AND s.deleted_at IS NULL
             ORDER BY s.created_at DESC LIMIT 20",
            [$id]
        );
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total, COALESCE(SUM(grand_total), 0) as total_spent,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                    SUM(CASE WHEN status = 'in_transit' THEN 1 ELSE 0 END) as in_transit
             FROM shipments WHERE customer_id = ? AND deleted_at IS NULL",
            [$id]
        );
        
        $this->adminView('customers/show', [
            'pageTitle' => "Customer: {$customer->first_name} {$customer->last_name}",
            'customer' => $customer,
            'shipments' => $shipments,
            'stats' => $stats,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $customer = $this->db->fetch("SELECT * FROM customers WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$customer) {
            flash('error', 'Customer not found.');
            $this->redirect('/admin/customers');
        }
        
        $this->adminView('customers/edit', [
            'pageTitle' => "Edit Customer",
            'customer' => $customer,
        ]);
    }

    /**
     * Update customer
     */
    public function update(int $id): void
    {
        $data = $this->getPostData();
        
        $this->db->query(
            "UPDATE customers SET customer_type=?, company_name=?, first_name=?, last_name=?, email=?, phone=?,
             alternative_phone=?, address_line1=?, city=?, state=?, country=?, postal_code=?, notes=?, updated_at=NOW()
             WHERE id=?",
            [
                $data['customer_type'] ?? 'individual', $data['company_name'] ?? '',
                $data['first_name'], $data['last_name'], $data['email'], $data['phone'],
                $data['alternative_phone'] ?? '', $data['address_line1'] ?? '',
                $data['city'] ?? '', $data['state'] ?? '', $data['country'] ?? '',
                $data['postal_code'] ?? '', $data['notes'] ?? '', $id
            ]
        );
        
        log_activity('customer_updated', 'customer', $id);
        flash('success', 'Customer updated successfully!');
        $this->redirect("/admin/customers/{$id}");
    }

    /**
     * Delete customer (soft)
     */
    public function destroy(int $id): void
    {
        $this->db->query("UPDATE customers SET deleted_at = NOW() WHERE id = ?", [$id]);
        log_activity('customer_deleted', 'customer', $id);
        flash('success', 'Customer deleted.');
        $this->redirect('/admin/customers');
    }
}
