<?php
    $statusColor = $warehouse->is_active ? '#198754' : '#6c757d';
?>

<!-- Warehouse Header -->
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:<?= $warehouse->temperature_controlled ? 'linear-gradient(135deg,#0dcaf0,#0aa2c0)' : 'linear-gradient(135deg,#6c757d,#5a6268)' ?>;color:#fff;font-size:1.3rem;">
                <i class="bi bi-boxes"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1">
                    <?= htmlspecialchars($warehouse->name) ?>
                    <span class="badge bg-secondary ms-2" style="font-size:0.75rem;"><?= htmlspecialchars($warehouse->code) ?></span>
                </h5>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge" style="background:<?= $statusColor ?>;">
                        <?= $warehouse->is_active ? 'Active' : 'Inactive' ?>
                    </span>
                    <?php if ($warehouse->branch_name): ?>
                        <span class="small text-muted"><i class="bi bi-building"></i> <?= htmlspecialchars($warehouse->branch_name) ?></span>
                    <?php endif; ?>
                    <?php if ($warehouse->temperature_controlled): ?>
                        <span class="badge bg-info"><i class="bi bi-thermometer-snow me-1"></i>Temp Controlled</span>
                    <?php endif; ?>
                    <span class="small text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($warehouse->city) ?>, <?= htmlspecialchars($warehouse->country) ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/warehouses/<?= $warehouse->id ?>/edit" class="btn btn-outline-primary admin-btn admin-btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <button class="btn btn-outline-danger admin-btn admin-btn-sm" onclick="deleteWarehouse(<?= $warehouse->id ?>, '<?= htmlspecialchars(addslashes($warehouse->name)) ?>')">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
            <a href="<?= BASE_URL ?>/admin/branches/<?= $warehouse->branch_id ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm">
                <i class="bi bi-building me-1"></i> View Branch
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-box"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->total ?? 0) ?></div>
                    <div class="card-label">Total Items</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-inbox"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->received ?? 0) ?></div>
                    <div class="card-label">Received</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="bi bi-archive"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->stored ?? 0) ?></div>
                    <div class="card-label">Stored</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
                    <i class="bi bi-send"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->shipped ?? 0) ?></div>
                    <div class="card-label">Shipped</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Warehouse Details -->
    <div class="col-lg-5">
        <div class="admin-card h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Warehouse Information</h6>
            <div class="mb-3">
                <small class="text-muted d-block">Address</small>
                <span><i class="bi bi-geo-alt me-2 text-muted"></i><?= htmlspecialchars($warehouse->address_line1) ?><?= $warehouse->address_line2 ? ', ' . htmlspecialchars($warehouse->address_line2) : '' ?></span>
                <div class="ms-4"><small class="text-muted"><?= htmlspecialchars($warehouse->city) ?>, <?= htmlspecialchars($warehouse->state) ?></small></div>
                <div class="ms-4"><small class="text-muted"><?= htmlspecialchars($warehouse->country) ?></small></div>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">Manager</small>
                <span><i class="bi bi-person me-2 text-muted"></i><?= htmlspecialchars($warehouse->manager_name ?? 'Not Assigned') ?></span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">Capacity</small>
                <span class="fw-semibold"><?= $warehouse->capacity ? number_format($warehouse->capacity) . ' m&sup3;' : '-' ?></span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">Temperature Controlled</small>
                <span><?= $warehouse->temperature_controlled ? '<span class="badge bg-info"><i class="bi bi-thermometer-snow me-1"></i>Yes</span>' : '<span class="text-muted">No</span>' ?></span>
            </div>
            <?php if ($warehouse->latitude && $warehouse->longitude): ?>
            <hr>
            <h6 class="fw-bold mb-2"><i class="bi bi-geo-alt me-2"></i>Location</h6>
            <div id="warehouseMap" style="height: 200px; border-radius: 8px;" data-lat="<?= $warehouse->latitude ?>" data-lng="<?= $warehouse->longitude ?>"></div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Inventory & Shipments -->
    <div class="col-lg-7">
        <!-- Recent Shipments -->
        <div class="admin-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-truck me-2"></i>Recent Shipments</h6>
                <?php if (!empty($recentShipments)): ?>
                    <span class="badge bg-primary"><?= count($recentShipments) ?> shipment<?= count($recentShipments) !== 1 ? 's' : '' ?></span>
                <?php endif; ?>
            </div>
            <?php if (!empty($recentShipments)): ?>
            <div class="table-responsive">
                <table class="table table-sm table-admin">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentShipments as $s): ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="fw-semibold text-decoration-none">
                                    <?= htmlspecialchars($s->tracking_number) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge" style="background:<?= $s->status_color ?? '#6c757d' ?>;">
                                    <?= htmlspecialchars($s->status_name ?? $s->status) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-2 mb-0">No recent shipments at this warehouse</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Current Inventory -->
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-archive me-2"></i>Current Inventory</h6>
                <?php if (!empty($inventory)): ?>
                    <span class="badge bg-success"><?= count($inventory) ?> item<?= count($inventory) !== 1 ? 's' : '' ?></span>
                <?php endif; ?>
            </div>
            <?php if (!empty($inventory)): ?>
            <div class="table-responsive" style="max-height:350px;overflow-y:auto;">
                <table class="table table-sm table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Shelf</th>
                            <th>Status</th>
                            <th class="text-end">Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory as $inv): ?>
                        <?php
                            $invStatusColors = [
                                'received' => '#198754',
                                'stored' => '#6f42c1',
                                'shipped' => '#0dcaf0',
                                'damaged' => '#dc3545',
                            ];
                            $invStatusColor = $invStatusColors[$inv->status] ?? '#6c757d';
                        ?>
                        <tr>
                            <td>
                                <small class="fw-semibold"><?= htmlspecialchars($inv->tracking_number ?? '-') ?></small>
                            </td>
                            <td>
                                <small><i class="bi bi-geo me-1 text-muted"></i><?= htmlspecialchars($inv->shelf_location ?? '-') ?></small>
                            </td>
                            <td>
                                <span class="badge" style="background:<?= $invStatusColor ?>;font-size:0.65rem;">
                                    <?= $inv->status ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <small class="text-muted"><?= format_date($inv->received_at ?? $inv->created_at, 'M d, Y') ?></small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-2 mb-0">No inventory records</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($warehouse->latitude && $warehouse->longitude): ?>
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<?php endif; ?>

<script>
function deleteWarehouse(id, name) {
    Swal.fire({
        title: 'Delete Warehouse?',
        text: 'Delete "' + name + '"? This will also soft-delete all related inventory.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>/admin/warehouses/' + id;
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_csrf_token';
                csrfInput.value = csrfMeta.getAttribute('content');
                form.appendChild(csrfInput);
            }
            document.body.appendChild(form);
            form.submit();
        }
    });
}

<?php if ($warehouse->latitude && $warehouse->longitude): ?>
document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('warehouseMap');
    var lat = parseFloat(el.dataset.lat);
    var lng = parseFloat(el.dataset.lng);
    var map = L.map(el).setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    L.marker([lat, lng], { icon: L.divIcon({ className: 'warehouse-marker', html: '<i class="bi bi-boxes fs-3 text-primary"></i>' }) }).addTo(map)
        .bindPopup('<strong><?= htmlspecialchars(addslashes($warehouse->name)) ?></strong><br><?= htmlspecialchars(addslashes($warehouse->address_line1)) ?>');
});
<?php endif; ?>
</script>
