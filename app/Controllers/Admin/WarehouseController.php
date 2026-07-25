<?php
/**
 * Global Delivered Logistics - Admin Warehouse Management Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class WarehouseController extends Controller
{
    /**
     * List all warehouses with search and pagination
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $country = sanitize($_GET['country'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        $perPage = min(100, max(10, (int) ($_GET['per_page'] ?? 25)));

        $where = "WHERE w.deleted_at IS NULL";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (w.name LIKE ? OR w.code LIKE ? OR w.city LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s]);
        }

        if (!empty($country)) {
            $where .= " AND w.country = ?";
            $params[] = $country;
        }

        if ($status === 'active') {
            $where .= " AND w.is_active = 1";
        } elseif ($status === 'inactive') {
            $where .= " AND w.is_active = 0";
        } elseif ($status === 'temp') {
            $where .= " AND w.temperature_controlled = 1";
        }

        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM warehouses w {$where}",
            "SELECT w.*, b.name as branch_name,
                    (SELECT COUNT(*) FROM warehouse_inventory WHERE warehouse_id = w.id) as inventory_count,
                    CONCAT(u.first_name, ' ', u.last_name) as manager_name
             FROM warehouses w
             LEFT JOIN branches b ON w.branch_id = b.id
             LEFT JOIN users u ON w.manager_id = u.id
             {$where} ORDER BY w.created_at DESC",
            $params, $page, $perPage
        );

        $countries = $this->db->fetchAll("SELECT DISTINCT country FROM warehouses WHERE deleted_at IS NULL ORDER BY country");

        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN temperature_controlled = 1 THEN 1 ELSE 0 END) as temp_controlled,
                    COALESCE(SUM(capacity), 0) as total_capacity
             FROM warehouses WHERE deleted_at IS NULL"
        );

        $this->adminView('warehouses/index', [
            'pageTitle' => 'Warehouses Management',
            'warehouses' => $paginated->data,
            'pagination' => $paginated,
            'countries' => $countries,
            'filters' => ['search' => $search, 'country' => $country, 'status' => $status],
            'stats' => $stats,
        ]);
    }

    /**
     * Show warehouse creation form
     */
    public function create(): void
    {
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name ASC");
        $managers = $this->db->fetchAll(
            "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) as name FROM users u
             JOIN roles r ON u.role_id = r.id
             WHERE r.slug IN ('warehouse_manager', 'admin', 'super_admin') AND u.is_active = 1
             ORDER BY u.first_name"
        );
        
        $this->adminView('warehouses/create', [
            'pageTitle' => 'Create Warehouse',
            'branches' => $branches,
            'managers' => $managers,
        ]);
    }

    /**
     * Store new warehouse
     */
    public function store(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'branch_id' => 'required|numeric',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'address_line1' => 'required',
        ];
        
        $validated = $this->validate($data, $rules);
        
        try {
            $this->db->query(
                "INSERT INTO warehouses (branch_id, name, code, manager_id, address_line1, address_line2,
                 city, state, country, latitude, longitude, capacity, temperature_controlled, is_active, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())",
                [
                    (int) $data['branch_id'],
                    $data['name'],
                    strtoupper($data['code']),
                    !empty($data['manager_id']) ? (int) $data['manager_id'] : null,
                    $data['address_line1'],
                    $data['address_line2'] ?? '',
                    $data['city'],
                    $data['state'],
                    $data['country'],
                    !empty($data['latitude']) ? (float) $data['latitude'] : null,
                    !empty($data['longitude']) ? (float) $data['longitude'] : null,
                    !empty($data['capacity']) ? (float) $data['capacity'] : null,
                    !empty($data['temperature_controlled']) ? 1 : 0,
                ]
            );
            
            $id = $this->db->lastInsertId();
            log_activity('warehouse_created', 'warehouse', $id);
            flash('success', 'Warehouse created successfully!');
            $this->redirect("/admin/warehouses/{$id}");
            
        } catch (\Exception $e) {
            error_log("Warehouse creation error: " . $e->getMessage());
            flash('error', 'Failed to create warehouse: ' . $e->getMessage());
            $this->back();
        }
    }

    /**
     * Show warehouse details
     */
    public function show(int $id): void
    {
        $warehouse = $this->db->fetch(
            "SELECT w.*, b.name as branch_name, b.code as branch_code,
                    CONCAT(u.first_name, ' ', u.last_name) as manager_name
             FROM warehouses w
             LEFT JOIN branches b ON w.branch_id = b.id
             LEFT JOIN users u ON w.manager_id = u.id
             WHERE w.id = ? AND w.deleted_at IS NULL",
            [$id]
        );
        
        if (!$warehouse) {
            flash('error', 'Warehouse not found.');
            $this->redirect('/admin/warehouses');
        }
        
        $inventory = $this->db->fetchAll(
            "SELECT wi.*, s.tracking_number
             FROM warehouse_inventory wi
             LEFT JOIN shipments s ON wi.shipment_id = s.id
             WHERE wi.warehouse_id = ?
             ORDER BY wi.created_at DESC LIMIT 50",
            [$id]
        );
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN status = 'received' THEN 1 ELSE 0 END) as received,
                    SUM(CASE WHEN status = 'stored' THEN 1 ELSE 0 END) as stored,
                    SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END) as shipped,
                    SUM(CASE WHEN status = 'damaged' THEN 1 ELSE 0 END) as damaged
             FROM warehouse_inventory WHERE warehouse_id = ?",
            [$id]
        );
        
        $recentShipments = $this->db->fetchAll(
            "SELECT s.id, s.tracking_number, ss.name as status_name
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.current_warehouse_id = ? AND s.deleted_at IS NULL
             ORDER BY s.updated_at DESC LIMIT 20",
            [$id]
        );
        
        $this->adminView('warehouses/show', [
            'pageTitle' => "Warehouse: {$warehouse->name}",
            'warehouse' => $warehouse,
            'inventory' => $inventory,
            'stats' => $stats,
            'recentShipments' => $recentShipments,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $warehouse = $this->db->fetch("SELECT * FROM warehouses WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$warehouse) {
            flash('error', 'Warehouse not found.');
            $this->redirect('/admin/warehouses');
        }
        
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name ASC");
        $managers = $this->db->fetchAll(
            "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) as name FROM users u
             JOIN roles r ON u.role_id = r.id
             WHERE r.slug IN ('warehouse_manager', 'admin', 'super_admin') AND u.is_active = 1
             ORDER BY u.first_name"
        );
        
        $this->adminView('warehouses/edit', [
            'pageTitle' => "Edit Warehouse: {$warehouse->name}",
            'warehouse' => $warehouse,
            'branches' => $branches,
            'managers' => $managers,
        ]);
    }

    /**
     * Update warehouse
     */
    public function update(int $id): void
    {
        $data = $this->getPostData();

        // Get existing warehouse to preserve code if not provided
        $existing = $this->db->fetch("SELECT code FROM warehouses WHERE id = ?", [$id]);
        $code = !empty($data['code']) ? strtoupper($data['code']) : ($existing ? $existing->code : '');

        $this->db->query(
            "UPDATE warehouses SET branch_id=?, name=?, code=?, manager_id=?, address_line1=?, address_line2=?,
             city=?, state=?, country=?, latitude=?, longitude=?, capacity=?, temperature_controlled=?, updated_at=NOW()
             WHERE id=?",
            [
                (int) $data['branch_id'],
                $data['name'],
                $code,
                !empty($data['manager_id']) ? (int) $data['manager_id'] : null,
                $data['address_line1'],
                $data['address_line2'] ?? '',
                $data['city'],
                $data['state'],
                $data['country'],
                !empty($data['latitude']) ? (float) $data['latitude'] : null,
                !empty($data['longitude']) ? (float) $data['longitude'] : null,
                !empty($data['capacity']) ? (float) $data['capacity'] : null,
                !empty($data['temperature_controlled']) ? 1 : 0,
                $id
            ]
        );

        log_activity('warehouse_updated', 'warehouse', $id);
        flash('success', 'Warehouse updated successfully!');
        $this->redirect("/admin/warehouses/{$id}");
    }

    /**
     * Delete warehouse (soft)
     */
    public function destroy(int $id): void
    {
        $this->db->query("UPDATE warehouses SET deleted_at = NOW(), is_active = 0 WHERE id = ?", [$id]);
        log_activity('warehouse_deleted', 'warehouse', $id);
        flash('success', 'Warehouse deleted.');
        $this->redirect('/admin/warehouses');
    }

    /**
     * Toggle warehouse active status (AJAX)
     */
    public function toggleStatus(int $id): void
    {
        $warehouse = $this->db->fetch("SELECT is_active FROM warehouses WHERE id = ? AND deleted_at IS NULL", [$id]);
        if (!$warehouse) {
            if ($this->isAjax()) {
                $this->error('Warehouse not found.');
            }
            flash('error', 'Warehouse not found.');
            $this->redirect('/admin/warehouses');
        }

        $newStatus = $warehouse->is_active ? 0 : 1;
        $this->db->query("UPDATE warehouses SET is_active = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $id]);

        log_activity('warehouse_status_toggled', 'warehouse', $id);

        if ($this->isAjax()) {
            $this->success(['is_active' => $newStatus], $newStatus ? 'Warehouse activated' : 'Warehouse deactivated');
        }

        flash('success', $newStatus ? 'Warehouse activated.' : 'Warehouse deactivated.');
        $this->redirect('/admin/warehouses');
    }
}
