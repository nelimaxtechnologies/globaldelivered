<?php
    $statusColors = [
        'active' => '#198754',
        'maintenance' => '#ffc107',
        'out_of_service' => '#dc3545',
        'retired' => '#6c757d',
    ];
    $statusColor = $statusColors[$vehicle->status] ?? '#6c757d';
    $typeIcons = [
        'car' => 'bi-car-front',
        'motorbike' => 'bi-bicycle',
        'van' => 'bi-truck',
        'truck' => 'bi-truck',
        'container' => 'bi-box-seam',
    ];
    $typeIcon = $typeIcons[$vehicle->vehicle_type] ?? 'bi-truck';
?>

<!-- Vehicle Header -->
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:linear-gradient(135deg,<?= $statusColor ?>,<?= $statusColor ?>dd);color:#fff;font-weight:700;font-size:1.3rem;">
                <i class="bi <?= $typeIcon ?>"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($vehicle->name) ?></h5>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge" style="background:<?= $statusColor ?>;<?= $vehicle->status === 'maintenance' ? 'color:#000;' : '' ?>">
                        <?= str_replace('_', ' ', $vehicle->status) ?>
                    </span>
                    <span class="badge bg-secondary text-capitalize"><?= str_replace('_', ' ', $vehicle->vehicle_type) ?></span>
                    <span class="fw-semibold small"><?= htmlspecialchars($vehicle->registration_number) ?></span>
                    <?php if ($vehicle->branch_name): ?>
                        <span class="small text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($vehicle->branch_name) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/vehicles/<?= $vehicle->id ?>/edit" class="btn btn-outline-primary admin-btn admin-btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <button class="btn btn-outline-danger admin-btn admin-btn-sm" onclick="deleteVehicle(<?= $vehicle->id ?>, '<?= htmlspecialchars(addslashes($vehicle->name)) ?>')">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
            <a href="<?= BASE_URL ?>/admin/drivers?vehicle_id=<?= $vehicle->id ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm">
                <i class="bi bi-person-badge me-1"></i> View Drivers
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
                    <div class="card-value"><?= (int)($stats->total_trips ?? 0) ?></div>
                    <div class="card-label">Total Trips</div>
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
                    <div class="card-value"><?= (int)($stats->delivered ?? 0) ?></div>
                    <div class="card-label">Delivered</div>
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
                    <div class="card-value"><?= format_currency($stats->total_revenue ?? 0) ?></div>
                    <div class="card-label">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Vehicle Details -->
    <div class="col-lg-5">
        <div class="admin-card h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Vehicle Details</h6>
            <div class="row g-3">
                <div class="col-6">
                    <small class="text-muted d-block">Make</small>
                    <span class="fw-semibold"><?= htmlspecialchars($vehicle->make ?: '-') ?></span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Model</small>
                    <span class="fw-semibold"><?= htmlspecialchars($vehicle->model ?: '-') ?></span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Year</small>
                    <span class="fw-semibold"><?= htmlspecialchars($vehicle->year ?: '-') ?></span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Fuel Type</small>
                    <span class="text-capitalize"><?= htmlspecialchars($vehicle->fuel_type ?: '-') ?></span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Weight Capacity</small>
                    <span class="fw-semibold"><?= $vehicle->capacity_weight ? number_format($vehicle->capacity_weight) . ' kg' : '-' ?></span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Volume Capacity</small>
                    <span class="fw-semibold"><?= $vehicle->capacity_volume ? number_format($vehicle->capacity_volume, 1) . ' m&sup3;' : '-' ?></span>
                </div>
            </div>
            
            <hr class="my-3">
            
            <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2"></i>Insurance</h6>
            <div class="row g-3">
                <div class="col-12">
                    <small class="text-muted d-block">Policy Number</small>
                    <span class="fw-semibold"><?= htmlspecialchars($vehicle->insurance_policy ?: '-') ?></span>
                </div>
                <div class="col-12">
                    <small class="text-muted d-block">Expiry Date</small>
                    <?php if ($vehicle->insurance_expiry): ?>
                        <?php $isExpired = strtotime($vehicle->insurance_expiry) < time(); ?>
                        <span class="<?= $isExpired ? 'text-danger fw-bold' : 'fw-semibold' ?>">
                            <?= format_date($vehicle->insurance_expiry) ?>
                            <?php if ($isExpired): ?>
                                <span class="badge bg-danger ms-1" style="font-size:0.65rem;">Expired</span>
                            <?php endif; ?>
                        </span>
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <hr class="my-3">
            
            <h6 class="fw-bold mb-3"><i class="bi bi-wrench me-2"></i>Maintenance</h6>
            <div class="row g-3">
                <div class="col-6">
                    <small class="text-muted d-block">Last Service</small>
                    <span><?= $vehicle->maintenance_last ? format_date($vehicle->maintenance_last) : '-' ?></span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Next Service</small>
                    <?php if ($vehicle->maintenance_next): ?>
                        <?php $isOverdue = strtotime($vehicle->maintenance_next) < time(); ?>
                        <?php $isSoon = strtotime($vehicle->maintenance_next) < strtotime('+30 days'); ?>
                        <span class="<?= ($isOverdue || $isSoon) ? 'text-danger fw-bold' : '' ?>">
                            <?= format_date($vehicle->maintenance_next) ?>
                            <?php if ($isOverdue): ?>
                                <i class="bi bi-exclamation-triangle ms-1"></i>
                            <?php endif; ?>
                        </span>
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Assigned Drivers -->
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-badge me-2"></i>Assigned Drivers</h6>
                <?php if (!empty($assignedDrivers)): ?>
                    <span class="badge bg-primary"><?= count($assignedDrivers) ?> driver<?= count($assignedDrivers) !== 1 ? 's' : '' ?></span>
                <?php endif; ?>
            </div>
            <?php if (!empty($assignedDrivers)): ?>
            <div class="table-responsive">
                <table class="table table-sm table-admin">
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Contact</th>
                            <th>License</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignedDrivers as $d): ?>
                        <?php
                            $driverStatusColors = [
                                'available' => '#198754',
                                'on_delivery' => '#ffc107',
                                'off_duty' => '#6c757d',
                                'on_leave' => '#0dcaf0',
                            ];
                            $driverStatusColor = $driverStatusColors[$d->status] ?? '#6c757d';
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:linear-gradient(135deg,<?= $driverStatusColor ?>,<?= $driverStatusColor ?>dd);color:#fff;font-weight:600;font-size:0.7rem;">
                                        <?= strtoupper(substr($d->first_name,0,1) . substr($d->last_name,0,1)) ?>
                                    </div>
                                    <a href="<?= BASE_URL ?>/admin/drivers/<?= $d->id ?>" class="fw-semibold text-decoration-none">
                                        <?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <small><i class="bi bi-phone me-1 text-muted"></i><?= htmlspecialchars($d->phone) ?></small>
                            </td>
                            <td><small class="fw-semibold"><?= htmlspecialchars($d->license_number) ?></small></td>
                            <td>
                                <span class="badge" style="background:<?= $driverStatusColor ?>;<?= $d->status === 'on_delivery' ? 'color:#000;' : '' ?>">
                                    <?= str_replace('_', ' ', $d->status) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-person-x display-4 text-muted"></i>
                <p class="text-muted mt-2 mb-0">No drivers assigned to this vehicle</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function deleteVehicle(id, name) {
    Swal.fire({
        title: 'Delete Vehicle?',
        text: 'Delete "' + name + '"? This action cannot be undone.',
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
            form.action = '<?= BASE_URL ?>/admin/vehicles/' + id;
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
</script>
