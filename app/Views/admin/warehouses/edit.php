<?php
    $statusColor = $warehouse->is_active ? '#198754' : '#6c757d';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:<?= $warehouse->temperature_controlled ? 'linear-gradient(135deg,#0dcaf0,#0aa2c0)' : 'linear-gradient(135deg,#6c757d,#5a6268)' ?>;color:#fff;font-size:1.1rem;">
            <i class="bi bi-boxes"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Edit Warehouse</h4>
            <small class="text-muted"><?= htmlspecialchars($warehouse->name) ?> (<?= htmlspecialchars($warehouse->code) ?>)</small>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/admin/warehouses/<?= $warehouse->id ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Profile
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/warehouses/<?= $warehouse->id ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <div class="row g-4">
        <!-- Basic Info -->
        <div class="col-lg-7">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-boxes me-2"></i>Warehouse Information</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Warehouse Name *</label>
                        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($warehouse->name) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Code</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($warehouse->code) ?>" disabled>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Capacity (m&sup3;)</label>
                        <input type="number" step="0.01" name="capacity" class="form-control" value="<?= $warehouse->capacity ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Branch *</label>
                        <select name="branch_id" class="form-select select2" required>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b->id ?>" <?= $warehouse->branch_id == $b->id ? 'selected' : '' ?>><?= htmlspecialchars($b->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Manager</label>
                        <select name="manager_id" class="form-select select2">
                            <option value="">None</option>
                            <?php foreach ($managers as $m): ?>
                                <option value="<?= $m->id ?>" <?= $warehouse->manager_id == $m->id ? 'selected' : '' ?>><?= htmlspecialchars($m->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Features -->
        <div class="col-lg-5">
            <div class="admin-card h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-gear me-2"></i>Features</h6>
                <div class="form-check form-switch mb-3">
                    <input type="checkbox" name="temperature_controlled" class="form-check-input" id="tempCtrl" value="1" <?= $warehouse->temperature_controlled ? 'checked' : '' ?>>
                    <label class="form-check-label fw-semibold" for="tempCtrl">Temperature Controlled</label>
                    <div><small class="text-muted">Enable if this warehouse has climate control</small></div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="col-12">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Address</h6>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Address Line 1 *</label>
                        <input type="text" name="address_line1" class="form-control" required value="<?= htmlspecialchars($warehouse->address_line1) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-control" value="<?= htmlspecialchars($warehouse->address_line2 ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">City *</label>
                        <input type="text" name="city" class="form-control" required value="<?= htmlspecialchars($warehouse->city) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">State *</label>
                        <input type="text" name="state" class="form-control" required value="<?= htmlspecialchars($warehouse->state) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Country *</label>
                        <input type="text" name="country" class="form-control" required value="<?= htmlspecialchars($warehouse->country) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($warehouse->postal_code ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Latitude</label>
                        <input type="number" step="any" name="latitude" class="form-control" value="<?= $warehouse->latitude ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Longitude</label>
                        <input type="number" step="any" name="longitude" class="form-control" value="<?= $warehouse->longitude ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>/admin/warehouses/<?= $warehouse->id ?>" class="btn btn-secondary admin-btn">Cancel</a>
        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-save me-1"></i> Update Warehouse</button>
    </div>
</form>
