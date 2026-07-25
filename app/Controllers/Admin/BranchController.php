<?php
/**
 * Global Delivered Logistics - Admin Branch Management Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class BranchController extends Controller
{
    /**
     * List all branches with search/filter and pagination
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $country = sanitize($_GET['country'] ?? '');
        $type = sanitize($_GET['type'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        $perPage = min(100, max(10, (int) ($_GET['per_page'] ?? 25)));

        $where = "WHERE b.deleted_at IS NULL";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (b.name LIKE ? OR b.code LIKE ? OR b.city LIKE ? OR b.phone LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        if (!empty($country)) {
            $where .= " AND b.country = ?";
            $params[] = $country;
        }
        if (!empty($type)) {
            $where .= " AND b.branch_type = ?";
            $params[] = $type;
        }
        if ($status !== '') {
            $where .= " AND b.is_active = ?";
            $params[] = (int) $status;
        }

        $stats = $this->db->fetch(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_count,
                SUM(CASE WHEN branch_type = 'head_office' THEN 1 ELSE 0 END) as head_offices,
                SUM(CASE WHEN branch_type = 'regional' THEN 1 ELSE 0 END) as regional,
                SUM(CASE WHEN branch_type = 'local' THEN 1 ELSE 0 END) as local_branches
             FROM branches WHERE deleted_at IS NULL"
        );

        $countries = $this->db->fetchAll(
            "SELECT DISTINCT country FROM branches WHERE deleted_at IS NULL ORDER BY country"
        );

        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM branches b {$where}",
            "SELECT b.*, CONCAT(u.first_name, ' ', u.last_name) as manager_name,
                    (SELECT COUNT(*) FROM warehouses WHERE branch_id = b.id AND deleted_at IS NULL) as warehouse_count,
                    (SELECT COUNT(*) FROM drivers WHERE branch_id = b.id AND deleted_at IS NULL) as driver_count,
                    (SELECT COUNT(*) FROM vehicles WHERE branch_id = b.id AND deleted_at IS NULL) as vehicle_count
             FROM branches b
             LEFT JOIN users u ON b.manager_id = u.id
             {$where} ORDER BY b.is_active DESC, b.created_at DESC",
            $params, $page, $perPage
        );

        $this->adminView('branches/index', [
            'pageTitle' => 'Branches Management',
            'branches' => $paginated->data,
            'pagination' => $paginated,
            'countries' => $countries,
            'filters' => ['search' => $search, 'country' => $country, 'type' => $type, 'status' => $status],
            'stats' => $stats,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $managers = $this->db->fetchAll(
            "SELECT id, first_name, last_name FROM users WHERE is_active = 1 ORDER BY first_name"
        );
        $allCountries = $this->db->fetchAll(
            "SELECT DISTINCT country FROM branches WHERE deleted_at IS NULL AND country IS NOT NULL ORDER BY country"
        );

        $this->adminView('branches/create', [
            'pageTitle' => 'Add Branch',
            'managers' => $managers,
            'allCountries' => $allCountries,
        ]);
    }

    /**
     * Store new branch
     */
    public function store(): void
    {
        $data = $this->getPostData();

        $rules = [
            'name' => 'required',
            'code' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'country' => 'required',
            'phone' => 'required',
        ];
        $this->validate($data, $rules);

        $code = strtoupper(trim($data['code']));
        $existing = $this->db->fetch(
            "SELECT id FROM branches WHERE code = ? AND deleted_at IS NULL", [$code]
        );
        if ($existing) {
            flash('error', "Branch code '{$code}' already exists. Please use a unique code.");
            $this->back();
            return;
        }

        try {
            $this->db->query(
                "INSERT INTO branches (name, slug, code, branch_type, manager_id, address_line1, address_line2,
                 city, state, country, postal_code, phone, email, whatsapp, latitude, longitude,
                 opening_time, closing_time, is_active, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())",
                [
                    $data['name'],
                    generate_slug($data['name']),
                    $code,
                    $data['branch_type'] ?? 'local',
                    !empty($data['manager_id']) ? (int) $data['manager_id'] : null,
                    $data['address_line1'],
                    $data['address_line2'] ?? '',
                    $data['city'],
                    $data['state'] ?? '',
                    $data['country'],
                    $data['postal_code'] ?? '',
                    $data['phone'],
                    $data['email'] ?? '',
                    $data['whatsapp'] ?? '',
                    !empty($data['latitude']) ? (float) $data['latitude'] : null,
                    !empty($data['longitude']) ? (float) $data['longitude'] : null,
                    $data['opening_time'] ?? null,
                    $data['closing_time'] ?? null,
                ]
            );

            log_activity('branch_created', 'branch', $this->db->lastInsertId());
            flash('success', 'Branch created successfully!');
            $this->redirect('/admin/branches');
        } catch (\Exception $e) {
            error_log("Branch creation error: " . $e->getMessage());
            flash('error', 'Failed to create branch: ' . $e->getMessage());
            $this->back();
        }
    }

    /**
     * Show branch details
     */
    public function show(int $id): void
    {
        $branch = $this->db->fetch(
            "SELECT b.*, CONCAT(u.first_name, ' ', u.last_name) as manager_name, u.email as manager_email
             FROM branches b LEFT JOIN users u ON b.manager_id = u.id
             WHERE b.id = ? AND b.deleted_at IS NULL", [$id]
        );

        if (!$branch) {
            flash('error', 'Branch not found.');
            $this->redirect('/admin/branches');
        }

        $warehouses = $this->db->fetchAll(
            "SELECT * FROM warehouses WHERE branch_id = ? AND deleted_at IS NULL ORDER BY name", [$id]
        );
        $drivers = $this->db->fetchAll(
            "SELECT * FROM drivers WHERE branch_id = ? AND deleted_at IS NULL ORDER BY first_name", [$id]
        );
        $vehicles = $this->db->fetchAll(
            "SELECT * FROM vehicles WHERE branch_id = ? AND deleted_at IS NULL ORDER BY name", [$id]
        );

        $stats = $this->db->fetch(
            "SELECT COUNT(*) as total_shipments,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                    SUM(CASE WHEN status NOT IN ('delivered', 'cancelled', 'returned') THEN 1 ELSE 0 END) as active,
                    COALESCE(SUM(grand_total), 0) as total_revenue
             FROM shipments
             WHERE (origin_branch_id = ? OR destination_branch_id = ?) AND deleted_at IS NULL",
            [$id, $id]
        );

        $this->adminView('branches/show', [
            'pageTitle' => "Branch: {$branch->name}",
            'branch' => $branch,
            'warehouses' => $warehouses,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'stats' => $stats,
        ]);
    }

    /**
     * Edit branch form
     */
    public function edit(int $id): void
    {
        $branch = $this->db->fetch(
            "SELECT * FROM branches WHERE id = ? AND deleted_at IS NULL", [$id]
        );
        if (!$branch) {
            flash('error', 'Branch not found.');
            $this->redirect('/admin/branches');
        }

        $managers = $this->db->fetchAll(
            "SELECT id, first_name, last_name FROM users WHERE is_active = 1 ORDER BY first_name"
        );
        $allCountries = $this->db->fetchAll(
            "SELECT DISTINCT country FROM branches WHERE deleted_at IS NULL AND country IS NOT NULL ORDER BY country"
        );

        $this->adminView('branches/edit', [
            'pageTitle' => "Edit Branch: {$branch->name}",
            'branch' => $branch,
            'managers' => $managers,
            'allCountries' => $allCountries,
        ]);
    }

    /**
     * Update branch
     */
    public function update(int $id): void
    {
        $data = $this->getPostData();

        $branch = $this->db->fetch("SELECT name FROM branches WHERE id = ?", [$id]);
        $slug = ($branch && $branch->name !== $data['name']) ? generate_slug($data['name']) : ($branch ? generate_slug($branch->name) : '');

        $this->db->query(
            "UPDATE branches SET name=?, slug=?, branch_type=?, manager_id=?, address_line1=?, address_line2=?,
             city=?, state=?, country=?, postal_code=?, phone=?, email=?, whatsapp=?,
             latitude=?, longitude=?, opening_time=?, closing_time=?, updated_at=NOW() WHERE id=?",
            [
                $data['name'],
                $slug,
                $data['branch_type'] ?? 'local',
                !empty($data['manager_id']) ? (int) $data['manager_id'] : null,
                $data['address_line1'] ?? '',
                $data['address_line2'] ?? '',
                $data['city'] ?? '',
                $data['state'] ?? '',
                $data['country'] ?? '',
                $data['postal_code'] ?? '',
                $data['phone'] ?? '',
                $data['email'] ?? '',
                $data['whatsapp'] ?? '',
                !empty($data['latitude']) ? (float) $data['latitude'] : null,
                !empty($data['longitude']) ? (float) $data['longitude'] : null,
                $data['opening_time'] ?? null,
                $data['closing_time'] ?? null,
                $id,
            ]
        );

        log_activity('branch_updated', 'branch', $id);
        flash('success', 'Branch updated successfully!');
        $this->redirect("/admin/branches/{$id}");
    }

    /**
     * Delete branch (soft)
     */
    public function destroy(int $id): void
    {
        $this->db->query("UPDATE branches SET deleted_at = NOW() WHERE id = ?", [$id]);
        log_activity('branch_deleted', 'branch', $id);
        flash('success', 'Branch deleted.');
        $this->redirect('/admin/branches');
    }

    /**
     * Toggle branch active status (AJAX)
     */
    public function toggleActive(int $id): void
    {
        if (!$this->isAjax()) {
            $this->redirect('/admin/branches');
            return;
        }

        $branch = $this->db->fetch("SELECT id, is_active FROM branches WHERE id = ? AND deleted_at IS NULL", [$id]);
        if (!$branch) {
            $this->json(['success' => false, 'message' => 'Branch not found'], 404);
            return;
        }

        $newStatus = $branch->is_active ? 0 : 1;
        $this->db->query("UPDATE branches SET is_active = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $id]);

        log_activity($newStatus ? 'branch_activated' : 'branch_deactivated', 'branch', $id);
        $this->json([
            'success' => true,
            'message' => $newStatus ? 'Branch activated.' : 'Branch deactivated.',
            'is_active' => $newStatus,
        ]);
    }

    /**
     * Export branches as CSV
     */
    public function export(): void
    {
        $search = sanitize($_GET['search'] ?? '');
        $country = sanitize($_GET['country'] ?? '');
        $type = sanitize($_GET['type'] ?? '');

        $where = "WHERE b.deleted_at IS NULL";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (b.name LIKE ? OR b.code LIKE ? OR b.city LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($country)) {
            $where .= " AND b.country = ?";
            $params[] = $country;
        }
        if (!empty($type)) {
            $where .= " AND b.branch_type = ?";
            $params[] = $type;
        }

        $branches = $this->db->fetchAll(
            "SELECT b.*, CONCAT(u.first_name, ' ', u.last_name) as manager_name
             FROM branches b
             LEFT JOIN users u ON b.manager_id = u.id
             {$where} ORDER BY b.created_at DESC",
            $params
        );

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=branches_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Name', 'Code', 'Type', 'Manager', 'City', 'State', 'Country', 'Phone', 'Email', 'WhatsApp', 'Active', 'Created']);

        foreach ($branches as $b) {
            fputcsv($output, [
                $b->name, $b->code, $b->branch_type,
                $b->manager_name ?? '',
                $b->city, $b->state, $b->country,
                $b->phone, $b->email ?? '', $b->whatsapp ?? '',
                $b->is_active ? 'Yes' : 'No',
                $b->created_at,
            ]);
        }

        fclose($output);
        exit;
    }
}
