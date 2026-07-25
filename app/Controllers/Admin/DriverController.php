<?php
/**
 * Global Delivered Logistics - Admin Driver Management Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class DriverController extends Controller
{
    /**
     * List all drivers
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        
        $where = "WHERE d.deleted_at IS NULL";
        $params = [];
        
        if (!empty($search)) {
            $where .= " AND (d.first_name LIKE ? OR d.last_name LIKE ? OR d.phone LIKE ? OR d.license_number LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        
        if (!empty($status)) {
            $where .= " AND d.status = ?";
            $params[] = $status;
        }
        
        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM drivers d {$where}",
            "SELECT d.*, b.name as branch_name, v.registration_number as vehicle_reg,
                    (SELECT COUNT(*) FROM shipments WHERE assigned_driver_id = d.id AND deleted_at IS NULL AND status != 'delivered') as active_deliveries,
                    (SELECT COUNT(*) FROM shipments WHERE assigned_driver_id = d.id AND deleted_at IS NULL AND status = 'delivered') as completed_deliveries
             FROM drivers d
             LEFT JOIN branches b ON d.branch_id = b.id
             LEFT JOIN vehicles v ON d.assigned_vehicle_id = v.id
             {$where} ORDER BY d.created_at DESC",
            $params, $page, 25
        );
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN status = 'on_delivery' THEN 1 ELSE 0 END) as on_delivery,
                    SUM(CASE WHEN status = 'off_duty' OR status = 'on_leave' THEN 1 ELSE 0 END) as inactive
             FROM drivers WHERE deleted_at IS NULL"
        );
        
        $this->adminView('drivers/index', [
            'pageTitle' => 'Drivers Management',
            'drivers' => $paginated->data,
            'pagination' => $paginated,
            'filters' => ['search' => $search, 'status' => $status],
            'stats' => $stats,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name ASC");
        $vehicles = $this->db->fetchAll("SELECT * FROM vehicles WHERE is_active = 1 AND status = 'active'");
        
        $this->adminView('drivers/create', [
            'pageTitle' => 'Create Driver',
            'branches' => $branches,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Store new driver
     */
    public function store(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'license_number' => 'required',
        ];
        
        $validated = $this->validate($data, $rules);
        
        try {
            $userId = null;
            if (!empty($data['email'])) {
                $roleId = $this->db->fetchColumn("SELECT id FROM roles WHERE slug = 'driver'");
                $userId = \App\Models\User::createUser([
                    'role_id' => $roleId,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'password' => bin2hex(random_bytes(8)), // random temp password
                    'is_active' => 1,
                ]);
            }
            
            $this->db->query(
                "INSERT INTO drivers (branch_id, user_id, first_name, last_name, email, phone, license_number, 
                 license_expiry, license_class, assigned_vehicle_id, status, is_active, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'available', 1, NOW())",
                [
                    !empty($data['branch_id']) ? (int) $data['branch_id'] : null,
                    $userId,
                    $data['first_name'],
                    $data['last_name'],
                    $data['email'] ?? '',
                    $data['phone'],
                    $data['license_number'],
                    $data['license_expiry'] ?? null,
                    $data['license_class'] ?? '',
                    !empty($data['assigned_vehicle_id']) ? (int) $data['assigned_vehicle_id'] : null,
                ]
            );
            
            log_activity('driver_created', 'driver', $this->db->lastInsertId());
            flash('success', 'Driver created successfully!');
            $this->redirect('/admin/drivers');
            
        } catch (\Exception $e) {
            error_log("Driver creation error: " . $e->getMessage());
            flash('error', 'Failed to create driver: ' . $e->getMessage());
            $this->back();
        }
    }

    /**
     * Show driver details
     */
    public function show(int $id): void
    {
        $driver = $this->db->fetch(
            "SELECT d.*, b.name as branch_name, v.registration_number as vehicle_reg, v.name as vehicle_name
             FROM drivers d
             LEFT JOIN branches b ON d.branch_id = b.id
             LEFT JOIN vehicles v ON d.assigned_vehicle_id = v.id
             WHERE d.id = ? AND d.deleted_at IS NULL",
            [$id]
        );
        
        if (!$driver) {
            flash('error', 'Driver not found.');
            $this->redirect('/admin/drivers');
        }
        
        $activeShipments = $this->db->fetchAll(
            "SELECT s.*, ss.name as status_name FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.assigned_driver_id = ? AND s.status NOT IN ('delivered', 'cancelled', 'returned') AND s.deleted_at IS NULL
             ORDER BY s.created_at DESC",
            [$id]
        );
        
        $completedShipments = $this->db->fetchAll(
            "SELECT s.*, ss.name as status_name FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.assigned_driver_id = ? AND s.status = 'delivered' AND s.deleted_at IS NULL
             ORDER BY s.created_at DESC LIMIT 10",
            [$id]
        );
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status NOT IN ('delivered', 'cancelled', 'returned') THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN grand_total IS NOT NULL THEN grand_total ELSE 0 END) as total_earned
             FROM shipments WHERE assigned_driver_id = ? AND deleted_at IS NULL",
            [$id]
        );
        
        $this->adminView('drivers/show', [
            'pageTitle' => "Driver: {$driver->first_name} {$driver->last_name}",
            'driver' => $driver,
            'activeShipments' => $activeShipments,
            'completedShipments' => $completedShipments,
            'stats' => $stats,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $driver = $this->db->fetch("SELECT * FROM drivers WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$driver) {
            flash('error', 'Driver not found.');
            $this->redirect('/admin/drivers');
        }
        
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name ASC");
        $vehicles = $this->db->fetchAll("SELECT * FROM vehicles WHERE is_active = 1");
        
        $this->adminView('drivers/edit', [
            'pageTitle' => 'Edit Driver',
            'driver' => $driver,
            'branches' => $branches,
            'vehicles' => $vehicles,
        ]);
    }

    public function update(int $id): void
    {
        $data = $this->getPostData();
        
        $this->db->query(
            "UPDATE drivers SET branch_id=?, first_name=?, last_name=?, email=?, phone=?, license_number=?,
             license_expiry=?, license_class=?, assigned_vehicle_id=?, status=?, updated_at=NOW()
             WHERE id=?",
            [
                !empty($data['branch_id']) ? (int) $data['branch_id'] : null,
                $data['first_name'], $data['last_name'], $data['email'] ?? '',
                $data['phone'], $data['license_number'], $data['license_expiry'] ?? null,
                $data['license_class'] ?? '',
                !empty($data['assigned_vehicle_id']) ? (int) $data['assigned_vehicle_id'] : null,
                $data['status'] ?? 'available',
                $id
            ]
        );
        
        log_activity('driver_updated', 'driver', $id);
        flash('success', 'Driver updated successfully!');
        $this->redirect("/admin/drivers/{$id}");
    }

    public function destroy(int $id): void
    {
        $this->db->query("UPDATE drivers SET deleted_at = NOW() WHERE id = ?", [$id]);
        log_activity('driver_deleted', 'driver', $id);
        flash('success', 'Driver deleted.');
        $this->redirect('/admin/drivers');
    }
}
