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

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:linear-gradient(135deg,<?= $statusColor ?>,<?= $statusColor ?>dd);color:#fff;font-size:1.1rem;">
            <i class="bi <?= $typeIcon ?>"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Edit Vehicle</h4>
            <small class="text-muted"><?= htmlspecialchars($vehicle->name) ?> (<?= htmlspecialchars($vehicle->registration_number) ?>)</small>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/admin/vehicles/<?= $vehicle->id ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Profile
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/vehicles/<?= $vehicle->id ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <div class="row g-4">
        <!-- Basic Info -->
        <div class="col-lg-8">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-truck me-2"></i>Vehicle Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Vehicle Name *</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($vehicle->name) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Registration Number *</label>
                        <input type="text" name="registration_number" class="form-control" value="<?= htmlspecialchars($vehicle->registration_number) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Vehicle Type *</label>
                        <select name="vehicle_type" class="form-select" required>
                            <?php foreach (['car','motorbike','van','truck','container','air_cargo','ship'] as $t): ?>
                                <option value="<?= $t ?>" <?= $vehicle->vehicle_type === $t ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $t)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Make</label>
                        <input type="text" name="make" class="form-control" value="<?= htmlspecialchars($vehicle->make) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Model</label>
                        <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($vehicle->model) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Year</label>
                        <input type="number" name="year" class="form-control" value="<?= $vehicle->year ?>" min="2000" max="2030">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Fuel Type</label>
                        <select name="fuel_type" class="form-select">
                            <option value="">Select...</option>
                            <?php foreach (['diesel','petrol','electric','hybrid'] as $f): ?>
                                <option value="<?= $f ?>" <?= $vehicle->fuel_type === $f ? 'selected' : '' ?>><?= ucfirst($f) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">Select...</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b->id ?>" <?= $b->id == $vehicle->branch_id ? 'selected' : '' ?>><?= htmlspecialchars($b->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= $vehicle->status === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="maintenance" <?= $vehicle->status === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            <option value="out_of_service" <?= $vehicle->status === 'out_of_service' ? 'selected' : '' ?>>Out of Service</option>
                            <option value="retired" <?= $vehicle->status === 'retired' ? 'selected' : '' ?>>Retired</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacity -->
        <div class="col-lg-4">
            <div class="admin-card h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-speedometer me-2"></i>Capacity</h6>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Weight Capacity (kg)</label>
                    <input type="number" name="capacity_weight" class="form-control" step="0.1" min="0" value="<?= $vehicle->capacity_weight ?>">
                </div>
                <div class="mb-0">
                    <label class="form-label small fw-semibold">Volume Capacity (m&sup3;)</label>
                    <input type="number" name="capacity_volume" class="form-control" step="0.1" min="0" value="<?= $vehicle->capacity_volume ?>">
                </div>
            </div>
        </div>

        <!-- Insurance -->
        <div class="col-lg-6">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2"></i>Insurance Information</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Policy Number</label>
                        <input type="text" name="insurance_policy" class="form-control" value="<?= htmlspecialchars($vehicle->insurance_policy) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Expiry Date</label>
                        <input type="date" name="insurance_expiry" class="form-control" value="<?= $vehicle->insurance_expiry ?>">
                        <?php if ($vehicle->insurance_expiry && strtotime($vehicle->insurance_expiry) < time()): ?>
                            <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Insurance has expired</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="col-lg-6">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-wrench me-2"></i>Maintenance Schedule</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Last Service Date</label>
                        <input type="date" name="maintenance_last" class="form-control" value="<?= $vehicle->maintenance_last ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Next Service Date</label>
                        <input type="date" name="maintenance_next" class="form-control" value="<?= $vehicle->maintenance_next ?>">
                        <?php if ($vehicle->maintenance_next && strtotime($vehicle->maintenance_next) < strtotime('+30 days')): ?>
                            <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Service due soon</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>/admin/vehicles/<?= $vehicle->id ?>" class="btn btn-secondary admin-btn">Cancel</a>
        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-save me-1"></i> Update Vehicle</button>
    </div>
</form>
