<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-person-plus me-2"></i>Add New Driver</h4>
        <small class="text-muted">Register a new delivery driver</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/drivers" class="btn btn-outline-secondary admin-btn admin-btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/drivers">
    <div class="row g-4">
        <!-- Personal Info -->
        <div class="col-lg-7">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Personal Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">First Name *</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Last Name *</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Phone *</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-semibold">Email <span class="text-muted fw-normal">(optional - creates login account)</span></label>
                        <input type="email" name="email" class="form-control" placeholder="driver@example.com">
                    </div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="col-lg-5">
            <div class="admin-card h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-toggle-on me-2"></i>Status & Assignment</h6>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">Select Branch...</option>
                        <?php foreach ($branches as $b): ?>
                            <option value="<?= $b->id ?>"><?= htmlspecialchars($b->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label small fw-semibold">Assigned Vehicle</label>
                    <select name="assigned_vehicle_id" class="form-select">
                        <option value="">Select Vehicle...</option>
                        <?php foreach ($vehicles as $v): ?>
                            <option value="<?= $v->id ?>"><?= htmlspecialchars($v->name . ' (' . $v->registration_number . ')') ?></option>
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
                        <input type="text" name="license_number" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">License Class</label>
                        <input type="text" name="license_class" class="form-control" placeholder="e.g., Class B">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Expiry Date</label>
                        <input type="date" name="license_expiry" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>/admin/drivers" class="btn btn-secondary admin-btn">Cancel</a>
        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-check-circle me-1"></i> Create Driver</button>
    </div>
</form>
