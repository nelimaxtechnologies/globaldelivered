<?php
    $statusColors = [
        'available' => '#198754',
        'on_delivery' => '#ffc107',
        'off_duty' => '#6c757d',
        'on_leave' => '#0dcaf0',
    ];
    $statusColor = $statusColors[$driver->status] ?? '#6c757d';
?>

<!-- Driver Header -->
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:linear-gradient(135deg,<?= $statusColor ?>,<?= $statusColor ?>dd);color:#fff;font-weight:700;font-size:1.2rem;">
                <?= strtoupper(substr($driver->first_name,0,1) . substr($driver->last_name,0,1)) ?>
            </div>
            <div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($driver->first_name . ' ' . $driver->last_name) ?></h5>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge" style="background:<?= $statusColor ?>;<?= $driver->status === 'on_delivery' ? 'color:#000;' : '' ?>">
                        <?= str_replace('_', ' ', $driver->status) ?>
                    </span>
                    <?php if ($driver->branch_name): ?>
                        <span class="small text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($driver->branch_name) ?></span>
                    <?php endif; ?>
                    <?php if ($driver->vehicle_reg): ?>
                        <span class="small text-muted"><i class="bi bi-truck"></i> <?= htmlspecialchars($driver->vehicle_reg) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/drivers/<?= $driver->id ?>/edit" class="btn btn-outline-primary admin-btn admin-btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="<?= BASE_URL ?>/admin/shipments?driver_id=<?= $driver->id ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm">
                <i class="bi bi-box-seam me-1"></i> View Shipments
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
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->total ?? 0) ?></div>
                    <div class="card-label">Total Deliveries</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->completed ?? 0) ?></div>
                    <div class="card-label">Completed</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->active ?? 0) ?></div>
                    <div class="card-label">Active Now</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($stats->total_earned ?? 0) ?></div>
                    <div class="card-label">Total Earned</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Driver Info -->
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Driver Information</h6>
            <div class="mb-3">
                <small class="text-muted d-block">Full Name</small>
                <span class="fw-semibold"><?= htmlspecialchars($driver->first_name . ' ' . $driver->last_name) ?></span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">Email Address</small>
                <span><i class="bi bi-envelope me-2 text-muted"></i><?= htmlspecialchars($driver->email ?: '-') ?></span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">Phone Number</small>
                <span><i class="bi bi-phone me-2 text-muted"></i><?= htmlspecialchars($driver->phone) ?></span>
            </div>
            
            <hr>
            
            <h6 class="fw-bold mb-3"><i class="bi bi-card-list me-2"></i>License Details</h6>
            <div class="mb-3">
                <small class="text-muted d-block">License Number</small>
                <span class="fw-semibold"><?= htmlspecialchars($driver->license_number) ?></span>
            </div>
            <?php if ($driver->license_expiry): ?>
            <div class="mb-3">
                <small class="text-muted d-block">Expiry Date</small>
                <span class="<?= strtotime($driver->license_expiry) < time() ? 'text-danger fw-bold' : '' ?>">
                    <?= format_date($driver->license_expiry) ?>
                    <?php if (strtotime($driver->license_expiry) < time()): ?>
                        <span class="badge bg-danger ms-1" style="font-size:0.65rem;">Expired</span>
                    <?php endif; ?>
                </span>
            </div>
            <?php endif; ?>
            <?php if ($driver->license_class): ?>
            <div class="mb-3">
                <small class="text-muted d-block">License Class</small>
                <span><?= htmlspecialchars($driver->license_class) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($driver->vehicle_reg): ?>
            <hr>
            <h6 class="fw-bold mb-3"><i class="bi bi-truck me-2"></i>Assigned Vehicle</h6>
            <div class="mb-3">
                <small class="text-muted d-block">Vehicle</small>
                <span class="fw-semibold"><?= htmlspecialchars($driver->vehicle_name ?? $driver->vehicle_reg) ?></span>
            </div>
            <div class="mb-0">
                <small class="text-muted d-block">Registration</small>
                <span class="fw-semibold"><?= htmlspecialchars($driver->vehicle_reg) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($driver->current_latitude && $driver->current_longitude): ?>
            <hr>
            <h6 class="fw-bold mb-2"><i class="bi bi-geo-alt me-2"></i>Last Known Location</h6>
            <div id="driverLocationMap" style="height: 200px; border-radius: 8px;" 
                 data-lat="<?= $driver->current_latitude ?>" data-lng="<?= $driver->current_longitude ?>"></div>
            <small class="text-muted">Updated: <?= $driver->last_location_update ? time_ago($driver->last_location_update) : 'N/A' ?></small>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Shipments -->
    <div class="col-lg-8">
        <!-- Active Shipments -->
        <div class="admin-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-truck me-2"></i>Active Shipments</h6>
                <?php if (!empty($activeShipments)): ?>
                    <span class="badge bg-warning text-dark"><?= count($activeShipments) ?> active</span>
                <?php endif; ?>
            </div>
            <?php if (!empty($activeShipments)): ?>
            <div class="table-responsive">
                <table class="table table-sm table-admin">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Recipient</th>
                            <th>Destination</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activeShipments as $s): ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="fw-semibold text-decoration-none small">
                                    <?= htmlspecialchars($s->tracking_number) ?>
                                </a>
                            </td>
                            <td><small><?= htmlspecialchars($s->recipient_name) ?></small></td>
                            <td><small><?= htmlspecialchars($s->recipient_city) ?>, <?= htmlspecialchars($s->recipient_country) ?></small></td>
                            <td>
                                <span class="badge" style="background:<?= $s->status_color ?? '#6c757d' ?>;">
                                    <?= htmlspecialchars($s->status_name ?? $s->status) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-2 mb-0">No active shipments</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Completed Deliveries -->
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-check-circle me-2"></i>Completed Deliveries</h6>
                <?php if (!empty($completedShipments)): ?>
                    <a href="<?= BASE_URL ?>/admin/shipments?driver_id=<?= $driver->id ?>&status=delivered" class="small">View All <i class="bi bi-arrow-right"></i></a>
                <?php endif; ?>
            </div>
            <?php if (!empty($completedShipments)): ?>
            <div class="table-responsive">
                <table class="table table-sm table-admin">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Recipient</th>
                            <th>Destination</th>
                            <th class="text-end">Delivered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completedShipments as $s): ?>
                        <tr>
                            <td><small class="fw-semibold"><?= htmlspecialchars($s->tracking_number) ?></small></td>
                            <td><small><?= htmlspecialchars($s->recipient_name) ?></small></td>
                            <td><small><?= htmlspecialchars($s->recipient_city) ?>, <?= htmlspecialchars($s->recipient_country) ?></small></td>
                            <td class="text-end"><small class="text-muted"><?= $s->actual_delivery_date ? format_date($s->actual_delivery_date) : format_date($s->updated_at) ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-2 mb-0">No completed deliveries yet</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($driver->current_latitude && $driver->current_longitude): ?>
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var mapEl = document.getElementById('driverLocationMap');
    var lat = parseFloat(mapEl.dataset.lat);
    var lng = parseFloat(mapEl.dataset.lng);
    var map = L.map(mapEl).setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    L.marker([lat, lng], { icon: L.divIcon({ className: 'driver-marker', html: '<i class="bi bi-person-badge fs-3 text-primary"></i>' }) }).addTo(map)
        .bindPopup('<strong><?= htmlspecialchars($driver->first_name . ' ' . $driver->last_name) ?></strong><br><?= htmlspecialchars($driver->vehicle_reg ?? '') ?>');
});
</script>
<?php endif; ?>
