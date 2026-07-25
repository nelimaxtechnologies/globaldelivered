<?php
    $statusColors = [
        'available' => '#198754',
        'on_delivery' => '#ffc107',
        'off_duty' => '#6c757d',
        'on_leave' => '#0dcaf0',
    ];
    $statusColor = $statusColors[$driver->status] ?? '#6c757d';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:linear-gradient(135deg,<?= $statusColor ?>,<?= $statusColor ?>dd);color:#fff;font-weight:700;">
            <?= strtoupper(substr($driver->first_name,0,1) . substr($driver->last_name,0,1)) ?>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Edit Driver</h4>
            <small class="text-muted"><?= htmlspecialchars($driver->first_name . ' ' . $driver->last_name) ?></small>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/admin/drivers/<?= $driver->id ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Profile
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/drivers/<?= $driver->id ?>">
    <input type="hidden" name="_method" value="PUT">
    <div class="row g-4">
        <!-- Personal Info -->
        <div class="col-lg-7">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Personal Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">First Name *</label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($driver->first_name) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Last Name *</label>
                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($driver->last_name) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Phone *</label>
                        <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($driver->phone) ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($driver->email) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Assignment -->
        <div class="col-lg-5">
            <div class="admin-card h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-toggle-on me-2"></i>Status & Assignment</h6>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="available" <?= $driver->status === 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="on_delivery" <?= $driver->status === 'on_delivery' ? 'selected' : '' ?>>On Delivery</option>
                        <option value="off_duty" <?= $driver->status === 'off_duty' ? 'selected' : '' ?>>Off Duty</option>
                        <option value="on_leave" <?= $driver->status === 'on_leave' ? 'selected' : '' ?>>On Leave</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">Select...</option>
                        <?php foreach ($branches as $b): ?>
                            <option value="<?= $b->id ?>" <?= $b->id == $driver->branch_id ? 'selected' : '' ?>><?= htmlspecialchars($b->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label small fw-semibold">Assigned Vehicle</label>
                    <select name="assigned_vehicle_id" class="form-select">
                        <option value="">Select...</option>
                        <?php foreach ($vehicles as $v): ?>
                            <option value="<?= $v->id ?>" <?= $v->id == $driver->assigned_vehicle_id ? 'selected' : '' ?>><?= htmlspecialchars($v->name . ' (' . $v->registration_number . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- License Info -->
        <div class="col-12">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-card-list me-2"></i>License Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">License Number *</label>
                        <input type="text" name="license_number" class="form-control" value="<?= htmlspecialchars($driver->license_number) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">License Class</label>
                        <input type="text" name="license_class" class="form-control" value="<?= htmlspecialchars($driver->license_class) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Expiry Date</label>
                        <input type="date" name="license_expiry" class="form-control" value="<?= $driver->license_expiry ?>">
                        <?php if ($driver->license_expiry && strtotime($driver->license_expiry) < time()): ?>
                            <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>License has expired</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>/admin/drivers/<?= $driver->id ?>" class="btn btn-secondary admin-btn">Cancel</a>
        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-save me-1"></i> Update Driver</button>
    </div>
</form>
