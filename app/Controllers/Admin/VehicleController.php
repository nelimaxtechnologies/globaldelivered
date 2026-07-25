<?php
/**
 * Global Delivered Logistics - Admin Vehicle Management Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class VehicleController extends Controller
{
    /**
     * List all vehicles
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        $type = sanitize($_GET['type'] ?? '');
        $perPage = min(100, max(10, (int) ($_GET['per_page'] ?? 25)));

        $where = "WHERE v.deleted_at IS NULL";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (v.name LIKE ? OR v.registration_number LIKE ? OR v.make LIKE ? OR v.model LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        if (!empty($status)) { $where .= " AND v.status = ?"; $params[] = $status; }
        if (!empty($type)) { $where .= " AND v.vehicle_type = ?"; $params[] = $type; }

        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM vehicles v {$where}",
            "SELECT v.*, b.name as branch_name,
                    (SELECT COUNT(*) FROM drivers WHERE assigned_vehicle_id = v.id AND deleted_at IS NULL) as assigned_drivers
             FROM vehicles v
             LEFT JOIN branches b ON v.branch_id = b.id
             {$where} ORDER BY v.created_at DESC",
            $params, $page, $perPage
        );

        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance,
                    SUM(CASE WHEN status = 'out_of_service' THEN 1 ELSE 0 END) as out_of_service,
                    SUM(CASE WHEN status = 'retired' THEN 1 ELSE 0 END) as retired,
                    COALESCE(SUM(capacity_weight), 0) as total_weight_capacity,
                    COALESCE(SUM(capacity_volume), 0) as total_volume_capacity
             FROM vehicles WHERE deleted_at IS NULL"
        );

        $vehicleTypes = $this->db->fetchAll("SELECT DISTINCT vehicle_type FROM vehicles WHERE deleted_at IS NULL ORDER BY vehicle_type");

        $this->adminView('vehicles/index', [
            'pageTitle' => 'Vehicles Management',
            'vehicles' => $paginated->data,
            'pagination' => $paginated,
            'filters' => ['search' => $search, 'status' => $status, 'type' => $type],
            'stats' => $stats,
            'vehicleTypes' => $vehicleTypes,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name ASC");
        $this->adminView('vehicles/create', ['pageTitle' => 'Add Vehicle', 'branches' => $branches]);
    }

    /**
     * Store new vehicle
     */
    public function store(): void
    {
        $data = $this->getPostData();
        
        $rules = ['name' => 'required', 'registration_number' => 'required', 'vehicle_type' => 'required'];
        $validated = $this->validate($data, $rules);
        
        try {
            $this->db->query(
                "INSERT INTO vehicles (branch_id, vehicle_type, name, registration_number, make, model, year,
                 capacity_weight, capacity_volume, fuel_type, insurance_policy, insurance_expiry,
                 maintenance_last, maintenance_next, status, is_active, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', 1, NOW())",
                [
                    !empty($data['branch_id']) ? (int) $data['branch_id'] : null,
                    $data['vehicle_type'], $data['name'], $data['registration_number'],
                    $data['make'] ?? '', $data['model'] ?? '', !empty($data['year']) ? (int) $data['year'] : null,
                    !empty($data['capacity_weight']) ? (float) $data['capacity_weight'] : null,
                    !empty($data['capacity_volume']) ? (float) $data['capacity_volume'] : null,
                    $data['fuel_type'] ?? '', $data['insurance_policy'] ?? '', $data['insurance_expiry'] ?? null,
                    $data['maintenance_last'] ?? null, $data['maintenance_next'] ?? null,
                ]
            );
            
            log_activity('vehicle_created', 'vehicle', $this->db->lastInsertId());
            flash('success', 'Vehicle added successfully!');
            $this->redirect('/admin/vehicles');
        } catch (\Exception $e) {
            flash('error', 'Failed to add vehicle.');
            $this->back();
        }
    }

    /**
     * Show vehicle details
     */
    public function show(int $id): void
    {
        $vehicle = $this->db->fetch(
            "SELECT v.*, b.name as branch_name FROM vehicles v
             LEFT JOIN branches b ON v.branch_id = b.id
             WHERE v.id = ? AND v.deleted_at IS NULL", [$id]
        );
        
        if (!$vehicle) { flash('error', 'Vehicle not found.'); $this->redirect('/admin/vehicles'); }
        
        $assignedDrivers = $this->db->fetchAll(
            "SELECT * FROM drivers WHERE assigned_vehicle_id = ? AND deleted_at IS NULL", [$id]
        );
        
        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total_trips,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                    SUM(CASE WHEN status NOT IN ('delivered', 'cancelled', 'returned') THEN 1 ELSE 0 END) as active,
                    COALESCE(SUM(grand_total), 0) as total_revenue
             FROM shipments WHERE assigned_driver_id IN (SELECT id FROM drivers WHERE assigned_vehicle_id = ? AND deleted_at IS NULL) AND deleted_at IS NULL",
            [$id]
        );
        
        $this->adminView('vehicles/show', [
            'pageTitle' => "Vehicle: {$vehicle->name}",
            'vehicle' => $vehicle,
            'assignedDrivers' => $assignedDrivers,
            'stats' => $stats,
        ]);
    }

    public function edit(int $id): void
    {
        $vehicle = $this->db->fetch("SELECT * FROM vehicles WHERE id = ? AND deleted_at IS NULL", [$id]);
        if (!$vehicle) { flash('error', 'Vehicle not found.'); $this->redirect('/admin/vehicles'); }
        
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name ASC");
        $this->adminView('vehicles/edit', ['pageTitle' => 'Edit Vehicle', 'vehicle' => $vehicle, 'branches' => $branches]);
    }

    public function update(int $id): void
    {
        $data = $this->getPostData();
        
        $this->db->query(
            "UPDATE vehicles SET branch_id=?, vehicle_type=?, name=?, registration_number=?, make=?, model=?, year=?,
             capacity_weight=?, capacity_volume=?, fuel_type=?, insurance_policy=?, insurance_expiry=?,
             maintenance_last=?, maintenance_next=?, status=?, updated_at=NOW() WHERE id=?",
            [
                !empty($data['branch_id']) ? (int) $data['branch_id'] : null,
                $data['vehicle_type'], $data['name'], $data['registration_number'],
                $data['make'] ?? '', $data['model'] ?? '', !empty($data['year']) ? (int) $data['year'] : null,
                !empty($data['capacity_weight']) ? (float) $data['capacity_weight'] : null,
                !empty($data['capacity_volume']) ? (float) $data['capacity_volume'] : null,
                $data['fuel_type'] ?? '', $data['insurance_policy'] ?? '', $data['insurance_expiry'] ?? null,
                $data['maintenance_last'] ?? null, $data['maintenance_next'] ?? null,
                $data['status'] ?? 'active', $id
            ]
        );
        
        log_activity('vehicle_updated', 'vehicle', $id);
        flash('success', 'Vehicle updated successfully!');
        $this->redirect("/admin/vehicles/{$id}");
    }

    public function destroy(int $id): void
    {
        $this->db->query("UPDATE vehicles SET deleted_at = NOW(), is_active = 0 WHERE id = ?", [$id]);
        log_activity('vehicle_deleted', 'vehicle', $id);
        flash('success', 'Vehicle deleted.');
        $this->redirect('/admin/vehicles');
    }

    /**
     * Toggle vehicle status (AJAX)
     */
    public function toggleStatus(int $id): void
    {
        $vehicle = $this->db->fetch("SELECT status FROM vehicles WHERE id = ? AND deleted_at IS NULL", [$id]);
        if (!$vehicle) {
            if ($this->isAjax()) { $this->error('Vehicle not found.'); }
            flash('error', 'Vehicle not found.');
            $this->redirect('/admin/vehicles');
        }

        $newStatus = $vehicle->status === 'active' ? 'maintenance' : 'active';
        $this->db->query("UPDATE vehicles SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $id]);

        log_activity('vehicle_status_changed', 'vehicle', $id, ['status' => $vehicle->status], ['status' => $newStatus]);

        if ($this->isAjax()) {
            $this->success(['status' => $newStatus], 'Vehicle status updated to ' . str_replace('_', ' ', $newStatus));
        }

        flash('success', 'Vehicle status updated.');
        $this->redirect('/admin/vehicles');
    }
}
