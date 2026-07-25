<?php
/**
 * Global Delivered Logistics - Admin Shipment Management Controller
 */

namespace App\Controllers\Admin;

use App\Core\Controller;

class ShipmentController extends Controller
{
    /**
     * List all shipments
     */
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        $serviceType = sanitize($_GET['service'] ?? '');
        
        // Build query
        $where = "WHERE s.deleted_at IS NULL";
        $params = [];
        
        if (!empty($search)) {
            $where .= " AND (s.tracking_number LIKE ? OR s.sender_name LIKE ? OR s.recipient_name LIKE ? OR s.sender_email LIKE ? OR s.recipient_email LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($status)) {
            $where .= " AND ss.slug = ?";
            $params[] = $status;
        }
        
        if (!empty($serviceType)) {
            $where .= " AND s.service_type = ?";
            $params[] = $serviceType;
        }
        
        $countSql = "SELECT COUNT(*) FROM shipments s LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id {$where}";
        $dataSql = "SELECT s.*, ss.name as status_name, ss.color as status_color
                    FROM shipments s
                    LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
                    {$where} ORDER BY s.created_at DESC";
        
        $paginated = $this->db->paginate($countSql, $dataSql, $params, $page, 25);
        
        // Status stats for summary cards
        $stats = $this->db->fetch(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'in_transit' THEN 1 ELSE 0 END) as in_transit,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
             FROM shipments WHERE deleted_at IS NULL"
        );
        
        $statuses = $this->db->fetchAll("SELECT * FROM shipment_statuses WHERE is_active = 1 ORDER BY sort_order");
        $drivers = $this->db->fetchAll("SELECT * FROM drivers WHERE is_active = 1 AND status = 'available'");
        
        $this->adminView('shipments/index', [
            'pageTitle' => 'Shipments Management',
            'shipments' => $paginated->data,
            'pagination' => $paginated,
            'statuses' => $statuses,
            'drivers' => $drivers,
            'stats' => $stats,
            'filters' => ['search' => $search, 'status' => $status, 'service' => $serviceType],
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $customers = $this->db->fetchAll("SELECT * FROM customers WHERE is_active = 1 ORDER BY first_name ASC");
        $statuses = $this->db->fetchAll("SELECT * FROM shipment_statuses WHERE is_active = 1 ORDER BY sort_order");
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name ASC");
        $drivers = $this->db->fetchAll("SELECT * FROM drivers WHERE is_active = 1 AND status = 'available'");
        $vehicles = $this->db->fetchAll("SELECT * FROM vehicles WHERE is_active = 1 AND status = 'active'");
        
        $this->adminView('shipments/create', [
            'pageTitle' => 'Create Shipment',
            'customers' => $customers,
            'statuses' => $statuses,
            'branches' => $branches,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'trackingNumber' => generate_tracking_number(),
        ]);
    }

    /**
     * Store new shipment
     */
    public function store(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'sender_name' => 'required',
            'sender_email' => 'required|email',
            'sender_phone' => 'required',
            'sender_address' => 'required',
            'sender_city' => 'required',
            'sender_country' => 'required',
            'recipient_name' => 'required',
            'recipient_phone' => 'required',
            'recipient_address' => 'required',
            'recipient_city' => 'required',
            'recipient_country' => 'required',
            'service_type' => 'required',
            'weight' => 'required|numeric|min:0.1',
        ];
        
        $validated = $this->validate($data, $rules);
        
        try {
            // Calculate charges
            $weight = (float) $data['weight'];
            $length = (float) ($data['length'] ?? 0);
            $width = (float) ($data['width'] ?? 0);
            $height = (float) ($data['height'] ?? 0);
            $isInsured = !empty($data['is_insured']);
            $declaredValue = (float) ($data['declared_value'] ?? 0);
            
            $dimWeight = ($length * $width * $height) / 5000;
            $chargeableWeight = max($weight, $dimWeight);
            
            $baseRate = 10;
            $perKgRate = 3;
            $subtotal = $baseRate + ($perKgRate * $chargeableWeight);
            
            if ($isInsured && $declaredValue > 0) {
                $subtotal += $declaredValue * 0.01;
            }
            
            $taxRate = 8;
            $taxAmount = $subtotal * ($taxRate / 100);
            $total = $subtotal + $taxAmount;
            
            $trackingNumber = !empty($data['tracking_number']) ? $data['tracking_number'] : generate_tracking_number();
            $customerId = !empty($data['customer_id']) ? (int) $data['customer_id'] : null;
            
            // Insert shipment
            $this->db->query(
                "INSERT INTO shipments (
                    tracking_number, customer_id,
                    sender_name, sender_email, sender_phone, sender_address, sender_city, sender_state, sender_country, sender_postal_code,
                    recipient_name, recipient_email, recipient_phone, recipient_address, recipient_city, recipient_state, recipient_country, recipient_postal_code,
                    origin_branch_id, destination_branch_id, assigned_driver_id, assigned_vehicle_id,
                    service_type, package_type, weight, length, width, height,
                    description, declared_value, is_fragile, is_insured, insurance_amount,
                    is_cod, cod_amount, signature_required, reference_number, notes,
                    current_status_id, pickup_date, expected_delivery_date,
                    total_charges, tax_amount, grand_total, currency,
                    payment_status, status, is_active, created_by, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    $trackingNumber, $customerId,
                    $data['sender_name'], $data['sender_email'], $data['sender_phone'],
                    $data['sender_address'], $data['sender_city'], $data['sender_state'] ?? '', $data['sender_country'], $data['sender_postal_code'] ?? '',
                    $data['recipient_name'], $data['recipient_email'] ?? '', $data['recipient_phone'],
                    $data['recipient_address'], $data['recipient_city'], $data['recipient_state'] ?? '', $data['recipient_country'], $data['recipient_postal_code'] ?? '',
                    !empty($data['origin_branch_id']) ? (int) $data['origin_branch_id'] : null,
                    !empty($data['destination_branch_id']) ? (int) $data['destination_branch_id'] : null,
                    !empty($data['assigned_driver_id']) ? (int) $data['assigned_driver_id'] : null,
                    !empty($data['assigned_vehicle_id']) ? (int) $data['assigned_vehicle_id'] : null,
                    $data['service_type'], $data['package_type'] ?? 'parcel', $weight, $length, $width, $height,
                    $data['description'] ?? '', $declaredValue, !empty($data['is_fragile']), $isInsured, $isInsured ? $declaredValue * 0.01 : 0,
                    !empty($data['is_cod']), (float) ($data['cod_amount'] ?? 0), !empty($data['signature_required']),
                    $data['reference_number'] ?? '', $data['notes'] ?? '',
                    !empty($data['current_status_id']) ? (int) $data['current_status_id'] : null,
                    $data['pickup_date'] ?? null, $data['expected_delivery_date'] ?? null,
                    $subtotal, $taxAmount, $total, $data['currency'] ?? 'USD',
                    'pending', 'pending', 1, $_SESSION['user_id'],
                ]
            );
            
            $shipmentId = $this->db->lastInsertId();
            
            // Create initial tracking history
            $statusId = !empty($data['current_status_id']) ? (int) $data['current_status_id'] : 1;
            $this->db->query(
                "INSERT INTO tracking_history (shipment_id, status_id, description, updated_by, source, created_at) 
                 VALUES (?, ?, 'Shipment created', ?, 'admin', NOW())",
                [$shipmentId, $statusId, $_SESSION['user_id']]
            );
            
            log_activity('shipment_created', 'shipment', $shipmentId, null, ['tracking_number' => $trackingNumber]);
            
            flash('success', "Shipment {$trackingNumber} created successfully!");
            $this->redirect('/admin/shipments');
            
        } catch (\Exception $e) {
            error_log("Shipment creation error: " . $e->getMessage());
            flash('error', 'Failed to create shipment: ' . $e->getMessage());
            $this->back();
        }
    }

    /**
     * Show shipment details
     */
    public function show(int $id): void
    {
        $shipment = $this->db->fetch(
            "SELECT s.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon,
                    ob.name as origin_branch_name, db.name as destination_branch_name,
                    w.name as warehouse_name,
                    CONCAT(d.first_name, ' ', d.last_name) as driver_name,
                    v.registration_number as vehicle_reg
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             LEFT JOIN branches ob ON s.origin_branch_id = ob.id
             LEFT JOIN branches db ON s.destination_branch_id = db.id
             LEFT JOIN warehouses w ON s.current_warehouse_id = w.id
             LEFT JOIN drivers d ON s.assigned_driver_id = d.id
             LEFT JOIN vehicles v ON s.assigned_vehicle_id = v.id
             WHERE s.id = ? AND s.deleted_at IS NULL",
            [$id]
        );
        
        if (!$shipment) {
            flash('error', 'Shipment not found.');
            $this->redirect('/admin/shipments');
        }
        
        $history = $this->db->fetchAll(
            "SELECT th.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon,
                    w.name as warehouse_name,
                    CONCAT(u.first_name, ' ', u.last_name) as updated_by_name
             FROM tracking_history th
             JOIN shipment_statuses ss ON th.status_id = ss.id
             LEFT JOIN warehouses w ON th.warehouse_id = w.id
             LEFT JOIN users u ON th.updated_by = u.id
             WHERE th.shipment_id = ?
             ORDER BY th.created_at ASC",
            [$id]
        );
        
        $allStatuses = $this->db->fetchAll("SELECT * FROM shipment_statuses WHERE is_active = 1 ORDER BY sort_order");
        $drivers = $this->db->fetchAll("SELECT * FROM drivers WHERE is_active = 1");
        $documents = $this->db->fetchAll("SELECT * FROM documents WHERE shipment_id = ?", [$id]);
        $invoices = $this->db->fetchAll("SELECT * FROM invoices WHERE shipment_id = ? ORDER BY created_at DESC", [$id]);
        
        $this->adminView('shipments/show', [
            'pageTitle' => "Shipment: {$shipment->tracking_number}",
            'shipment' => $shipment,
            'history' => $history,
            'allStatuses' => $allStatuses,
            'drivers' => $drivers,
            'documents' => $documents,
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $shipment = $this->db->fetch("SELECT * FROM shipments WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$shipment) {
            flash('error', 'Shipment not found.');
            $this->redirect('/admin/shipments');
        }
        
        $statuses = $this->db->fetchAll("SELECT * FROM shipment_statuses WHERE is_active = 1 ORDER BY sort_order");
        $branches = $this->db->fetchAll("SELECT * FROM branches WHERE is_active = 1 ORDER BY name ASC");
        $drivers = $this->db->fetchAll("SELECT * FROM drivers WHERE is_active = 1");
        $vehicles = $this->db->fetchAll("SELECT * FROM vehicles WHERE is_active = 1");
        
        $this->adminView('shipments/edit', [
            'pageTitle' => "Edit Shipment: {$shipment->tracking_number}",
            'shipment' => $shipment,
            'statuses' => $statuses,
            'branches' => $branches,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Update shipment
     */
    public function update(int $id): void
    {
        $data = $this->getPostData();
        
        $this->db->query(
            "UPDATE shipments SET 
                current_status_id = ?,
                service_type = ?, package_type = ?,
                sender_name = ?, sender_phone = ?, sender_address = ?,
                recipient_name = ?, recipient_phone = ?, recipient_address = ?,
                recipient_city = ?, recipient_state = ?, recipient_country = ?, recipient_postal_code = ?,
                weight = ?, length = ?, width = ?, height = ?,
                description = ?, declared_value = ?, is_fragile = ?, is_insured = ?,
                signature_required = ?, notes = ?,
                origin_branch_id = ?, destination_branch_id = ?,
                assigned_driver_id = ?, assigned_vehicle_id = ?,
                expected_delivery_date = ?, pickup_date = ?,
                updated_at = NOW()
             WHERE id = ?",
            [
                $data['current_status_id'],
                $data['service_type'] ?? 'express', $data['package_type'] ?? 'parcel',
                $data['sender_name'] ?? '', $data['sender_phone'] ?? '', $data['sender_address'] ?? '',
                $data['recipient_name'], $data['recipient_phone'], $data['recipient_address'],
                $data['recipient_city'], $data['recipient_state'] ?? '', $data['recipient_country'], $data['recipient_postal_code'] ?? '',
                $data['weight'], $data['length'] ?? 0, $data['width'] ?? 0, $data['height'] ?? 0,
                $data['description'] ?? '', $data['declared_value'] ?? 0, !empty($data['is_fragile']), !empty($data['is_insured']),
                !empty($data['signature_required']), $data['notes'] ?? '',
                !empty($data['origin_branch_id']) ? $data['origin_branch_id'] : null,
                !empty($data['destination_branch_id']) ? $data['destination_branch_id'] : null,
                !empty($data['assigned_driver_id']) ? $data['assigned_driver_id'] : null,
                !empty($data['assigned_vehicle_id']) ? $data['assigned_vehicle_id'] : null,
                $data['expected_delivery_date'] ?: null, $data['pickup_date'] ?: null,
                $id
            ]
        );
        
        log_activity('shipment_updated', 'shipment', $id);
        
        flash('success', 'Shipment updated successfully!');
        $this->redirect("/admin/shipments/{$id}");
    }

    /**
     * Update shipment status (AJAX)
     */
    public function updateStatus(int $id): void
    {
        $statusId = (int) ($_POST['status_id'] ?? 0);
        $description = sanitize($_POST['description'] ?? '');
        $location = sanitize($_POST['location'] ?? '');
        
        if (!$statusId) {
            $this->error('Status ID is required.');
        }
        
        $shipment = $this->db->fetch("SELECT * FROM shipments WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$shipment) {
            $this->error('Shipment not found.', 404);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Create tracking history
            $this->db->query(
                "INSERT INTO tracking_history (shipment_id, status_id, location, description, updated_by, source, created_at) 
                 VALUES (?, ?, ?, ?, ?, 'admin', NOW())",
                [$id, $statusId, $location, $description, $_SESSION['user_id']]
            );
            
            // Update shipment
            $status = $this->db->fetchColumn("SELECT slug FROM shipment_statuses WHERE id = ?", [$statusId]);
            $dbStatus = $status ? $this->mapStatusToDbStatus($status) : 'active';
            
            $this->db->query(
                "UPDATE shipments SET current_status_id = ?, status = ?, last_scan_at = NOW(), 
                 updated_at = NOW() WHERE id = ?",
                [$statusId, $dbStatus, $id]
            );
            
            // If delivered, set delivery date
            if ($dbStatus === 'delivered') {
                $this->db->query(
                    "UPDATE shipments SET actual_delivery_date = NOW() WHERE id = ?",
                    [$id]
                );
            }
            
            $this->db->commit();
            
            log_activity('shipment_status_updated', 'shipment', $id, null, [
                'tracking_number' => $shipment->tracking_number,
                'new_status' => $status
            ]);
            
            $this->success(['status' => $status], 'Status updated successfully!');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->error('Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Assign driver to shipment (AJAX)
     */
    public function assignDriver(int $id): void
    {
        $driverId = (int) ($_POST['driver_id'] ?? 0);
        
        if (!$driverId) {
            $this->error('Please select a driver.');
        }
        
        $this->db->query(
            "UPDATE shipments SET assigned_driver_id = ?, updated_at = NOW() WHERE id = ?",
            [$driverId, $id]
        );
        
        // Update driver status
        $this->db->query(
            "UPDATE drivers SET status = 'on_delivery' WHERE id = ?",
            [$driverId]
        );
        
        log_activity('driver_assigned', 'shipment', $id, null, ['driver_id' => $driverId]);
        
        $driver = $this->db->fetch("SELECT CONCAT(first_name, ' ', last_name) as name FROM drivers WHERE id = ?", [$driverId]);
        
        $this->success(['driver_name' => $driver->name ?? 'Unknown'], 'Driver assigned successfully!');
    }

    /**
     * Print shipping label
     */
    public function printLabel(int $id): void
    {
        $shipment = $this->db->fetch("SELECT * FROM shipments WHERE id = ?", [$id]);
        
        if (!$shipment) {
            flash('error', 'Shipment not found.');
            $this->redirect('/admin/shipments');
        }

        $serviceLabel = ucwords(str_replace('_', ' ', $shipment->service_type));
        $date = date('M d, Y');
        $weight = $shipment->weight . ' kg';
        $dims = $shipment->length && $shipment->width && $shipment->height 
            ? "{$shipment->length}×{$shipment->width}×{$shipment->height} cm" : '—';
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <title>Shipping Label — <?= htmlspecialchars($shipment->tracking_number) ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            *{margin:0;padding:0;box-sizing:border-box}
            body{font-family:'Inter',sans-serif;background:#fff;color:#1a1a2e;-webkit-print-color-adjust:exact;print-color-adjust:exact}
            .label{width:4in;padding:0;margin:0 auto;border:1.5px solid #e0e0e0;overflow:hidden}
            .label-header{background:linear-gradient(135deg,#0d1452 0%,#1a237e 60%,#283593 100%);color:#fff;padding:14px 16px;display:flex;align-items:center;justify-content:space-between}
            .brand{display:flex;flex-direction:row;align-items:center;gap:8px}
            .brand-name{font-size:13px;font-weight:800;letter-spacing:0.04em;text-transform:uppercase}
            .brand-tag{font-size:7.5px;font-weight:500;opacity:0.7;letter-spacing:0.1em;text-transform:uppercase}
            .service-pill{background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.25);color:#fff;font-size:7.5px;font-weight:700;padding:4px 10px;border-radius:20px;letter-spacing:0.06em;text-transform:uppercase}

            .tracking-section{padding:14px 16px 10px;text-align:center;border-bottom:1px solid #eee}
            .tracking-label{font-size:7px;font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:#888;margin-bottom:4px}
            .tracking-number{font-size:20px;font-weight:800;letter-spacing:0.08em;color:#0d1452;font-family:'Courier New',monospace}

            .barcode-area{padding:8px 16px 12px;text-align:center}
            .barcode-text{font-size:7.5px;font-weight:600;letter-spacing:0.18em;color:#555;font-family:'Courier New',monospace}

            .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:0}
            .info-box{padding:12px 16px;border-top:1px solid #eee}
            .info-box.from{border-right:1px solid #eee}
            .info-tag{display:inline-flex;align-items:center;gap:4px;font-size:7px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;margin-bottom:6px;padding:2px 8px;border-radius:3px}
            .info-tag.from-tag{background:#e8eaf6;color:#1a237e}
            .info-tag.to-tag{background:#e8f5e9;color:#2e7d32}
            .info-name{font-size:11px;font-weight:700;margin-bottom:2px;line-height:1.3}
            .info-line{font-size:8.5px;color:#555;line-height:1.5}
            .info-phone{font-size:8.5px;color:#1a237e;font-weight:600;margin-top:2px}

            .details-bar{display:flex;border-top:1px solid #eee}
            .detail-item{flex:1;padding:10px 16px;text-align:center;border-right:1px solid #eee}
            .detail-item:last-child{border-right:none}
            .detail-label{font-size:6.5px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:#888;margin-bottom:2px}
            .detail-value{font-size:10px;font-weight:700;color:#1a1a2e}

            .label-footer{background:#f8f9fc;border-top:1px solid #eee;padding:10px 16px;display:flex;justify-content:space-between;align-items:center}
            .footer-left{font-size:7px;color:#888;line-height:1.6}
            .footer-right{text-align:right}
            .footer-qr{width:32px;height:32px;background:#e8eaf6;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:5px;font-weight:700;color:#1a237e;line-height:1.1;text-align:center}

            @media print{
                body{margin:0;padding:0;background:#fff}
                .label{border:none;box-shadow:none}
                @page{size:4in auto;margin:0}
            }
        </style>
        </head>
        <body>
        <div class="label">
            <div class="label-header">
                <div class="brand d-flex align-items-center gap-2">
                    <svg width="28" height="28" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0">
                        <defs><linearGradient id="gdlLbl" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#FFD54F"/><stop offset="100%" style="stop-color:#FFA726"/></linearGradient></defs>
                        <circle cx="60" cy="60" r="56" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="3"/>
                        <rect x="36" y="40" width="48" height="36" rx="4" fill="url(#gdlLbl)" opacity="0.95"/>
                        <line x1="36" y1="52" x2="84" y2="52" stroke="rgba(26,35,126,0.3)" stroke-width="2"/>
                        <line x1="60" y1="40" x2="60" y2="76" stroke="rgba(26,35,126,0.3)" stroke-width="2"/>
                        <rect x="52" y="36" width="16" height="8" rx="2" fill="#fff" opacity="0.3"/>
                        <path d="M78 32 L92 48 L82 48 L82 64 L74 64 L74 48 L64 48 Z" fill="#fff" opacity="0.9"/>
                        <text x="60" y="100" text-anchor="middle" font-family="Inter,sans-serif" font-weight="800" font-size="16" fill="url(#gdlLbl)" letter-spacing="3">GDL</text>
                    </svg>
                    <div>
                        <div class="brand-name">Global Delivered</div>
                        <div class="brand-tag">Worldwide Logistics</div>
                    </div>
                </div>
                <div class="service-pill"><?= $serviceLabel ?></div>
            </div>

            <div class="tracking-section">
                <div class="tracking-label">Tracking Number</div>
                <div class="tracking-number"><?= htmlspecialchars($shipment->tracking_number) ?></div>
            </div>

            <div class="barcode-area">
                <a href="<?= BASE_URL ?>/tracking/<?= htmlspecialchars($shipment->tracking_number) ?>" target="_blank" style="text-decoration:none;display:block">
                    <svg id="barcode"></svg>
                </a>
            </div>

            <div class="info-grid">
                <div class="info-box from">
                    <div class="info-tag from-tag">
                        <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="10" r="3"/><path d="M12 2C7.58 2 4 5.58 4 10c0 5.25 8 12 8 12s8-6.75 8-12c0-4.42-3.58-8-8-8z"/></svg>
                        Ship From
                    </div>
                    <div class="info-name"><?= htmlspecialchars($shipment->sender_name) ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->sender_address) ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->sender_city) ?>, <?= htmlspecialchars($shipment->sender_state) ?> <?= htmlspecialchars($shipment->sender_postal_code ?? '') ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->sender_country) ?></div>
                    <div class="info-phone"><?= htmlspecialchars($shipment->sender_phone) ?></div>
                </div>
                <div class="info-box to">
                    <div class="info-tag to-tag">
                        <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Ship To
                    </div>
                    <div class="info-name"><?= htmlspecialchars($shipment->recipient_name) ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->recipient_address) ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->recipient_city) ?>, <?= htmlspecialchars($shipment->recipient_state) ?> <?= htmlspecialchars($shipment->recipient_postal_code ?? '') ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->recipient_country) ?></div>
                    <div class="info-phone"><?= htmlspecialchars($shipment->recipient_phone) ?></div>
                </div>
            </div>

            <div class="details-bar">
                <div class="detail-item">
                    <div class="detail-label">Weight</div>
                    <div class="detail-value"><?= $weight ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Dimensions</div>
                    <div class="detail-value"><?= $dims ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Pickup Date</div>
                    <div class="detail-value"><?= $shipment->pickup_date ? format_date($shipment->pickup_date, 'M d, Y') : '—' ?></div>
                </div>
                <?php if ($shipment->is_fragile): ?>
                <div class="detail-item">
                    <div class="detail-label">Handle</div>
                    <div class="detail-value" style="color:#d32f2f">⚠ Fragile</div>
                </div>
                <?php endif; ?>
                <?php if ($shipment->is_insured): ?>
                <div class="detail-item">
                    <div class="detail-label">Insured</div>
                    <div class="detail-value" style="color:#2e7d32">✓ Yes</div>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($shipment->description): ?>
            <div style="padding:10px 16px;border-top:1px solid #eee;">
                <div class="detail-label" style="font-size:6.5px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:#888;margin-bottom:2px;">Contents</div>
                <div style="font-size:8.5px;color:#1a1a2e;line-height:1.5;"><?= htmlspecialchars($shipment->description) ?></div>
            </div>
            <?php endif; ?>

            <div class="label-footer">
                <div class="footer-left">
                    track@globaldelivered.biz<br>
                    +254 729 373 801 · 24/7 Support
                </div>
                <div class="footer-right">
                    <div class="footer-qr">GDL<br>COURIER</div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
        <script>
            JsBarcode("#barcode", "<?= htmlspecialchars($shipment->tracking_number) ?>", {
                format: "CODE128",
                width: 1.5,
                height: 40,
                displayValue: true,
                font: "Courier",
                fontSize: 12,
                textMargin: 4,
                margin: 0
            });
            window.onload = function(){ setTimeout(function(){ window.print(); }, 500); };
        </script>
        </body>
        </html>
        <?php
        exit;
    }

    /**
     * Get shipment timeline (AJAX)
     */
    public function timeline(int $id): void
    {
        $history = $this->db->fetchAll(
            "SELECT th.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon
             FROM tracking_history th
             JOIN shipment_statuses ss ON th.status_id = ss.id
             WHERE th.shipment_id = ?
             ORDER BY th.created_at ASC",
            [$id]
        );
        
        $this->success(['history' => $history]);
    }

    /**
     * Update a tracking history entry (AJAX)
     */
    public function updateHistory(int $id, int $historyId): void
    {
        $shipment = $this->db->fetch("SELECT id FROM shipments WHERE id = ?", [$id]);
        if (!$shipment) {
            $this->error('Shipment not found.', 404);
            return;
        }

        $history = $this->db->fetch("SELECT id FROM tracking_history WHERE id = ? AND shipment_id = ?", [$historyId, $id]);
        if (!$history) {
            $this->error('History entry not found.', 404);
            return;
        }

        $data = $this->getPostData();
        $createdAt = $data['created_at'] ?? '';
        $location = $data['location'] ?? '';
        $description = $data['description'] ?? '';

        if (empty($createdAt)) {
            $this->error('Date is required.', 422);
            return;
        }

        // Convert from "2026-07-23T02:35" (datetime-local) to MySQL format
        $dateStr = str_replace('T', ' ', $createdAt) . ':00';

        $this->db->query(
            "UPDATE tracking_history SET created_at = ?, location = ?, description = ? WHERE id = ?",
            [$dateStr, $location, $description, $historyId]
        );

        log_activity('tracking_history_updated', 'tracking_history', $historyId, [
            'shipment_id' => $id,
            'new_date' => $dateStr,
            'new_location' => $location,
        ]);

        $this->success(['message' => 'History entry updated successfully.']);
    }

    /**
     * Delete shipment
     */
    public function destroy(int $id): void
    {
        $this->db->query("UPDATE shipments SET deleted_at = NOW() WHERE id = ?", [$id]);
        log_activity('shipment_deleted', 'shipment', $id);
        
        flash('success', 'Shipment deleted successfully.');
        $this->redirect('/admin/shipments');
    }

    /**
     * Map status slug to database status
     */
    private function mapStatusToDbStatus(string $slug): string
    {
        $map = [
            'order_received' => 'pending',
            'picked_up' => 'active',
            'at_warehouse' => 'active',
            'in_transit' => 'in_transit',
            'customs_clearance' => 'in_transit',
            'fees_payment_required' => 'active',
            'awaiting_forwarding' => 'in_transit',
            'out_for_delivery' => 'in_transit',
            'delivered' => 'delivered',
            'delayed' => 'active',
            'returned' => 'returned',
            'cancelled' => 'cancelled',
            'on_hold' => 'pending',
        ];
        return $map[$slug] ?? 'active';
    }
}
