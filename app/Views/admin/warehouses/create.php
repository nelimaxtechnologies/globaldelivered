<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-boxes me-2"></i>Add New Warehouse</h4>
        <small class="text-muted">Register a new warehouse facility</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/warehouses" class="btn btn-outline-secondary admin-btn admin-btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/warehouses">
    <?= csrf_field() ?>
    <div class="row g-4">
        <!-- Basic Info -->
        <div class="col-lg-7">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-boxes me-2"></i>Warehouse Information</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Warehouse Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g., Main Sorting Center">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Code *</label>
                        <input type="text" name="code" class="form-control" required placeholder="e.g., WH-001">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Capacity (m&sup3;)</label>
                        <input type="number" step="0.01" name="capacity" class="form-control" placeholder="5000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Branch *</label>
                        <select name="branch_id" class="form-select select2" required>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b->id ?>"><?= htmlspecialchars($b->name) ?> (<?= htmlspecialchars($b->code) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Manager</label>
                        <select name="manager_id" class="form-select select2">
                            <option value="">None</option>
                            <?php foreach ($managers as $m): ?>
                                <option value="<?= $m->id ?>"><?= htmlspecialchars($m->name) ?></option>
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
                    <input type="checkbox" name="temperature_controlled" class="form-check-input" id="tempCtrl" value="1">
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
                        <input type="text" name="address_line1" class="form-control" required placeholder="Street address">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-control" placeholder="Suite, unit, etc.">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">City *</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">State *</label>
                        <input type="text" name="state" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Country *</label>
                        <input type="text" name="country" class="form-control" required list="countryList">
                        <datalist id="countryList">
                            <?php foreach (['United States','United Kingdom','Canada','Australia','Germany','France','Italy','Spain','UAE','China','Japan','Nigeria','Kenya','South Africa','Brazil','India'] as $c): ?>
                                <option value="<?= $c ?>"><?= $c ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Latitude</label>
                        <input type="number" step="any" name="latitude" class="form-control" placeholder="40.7128">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Longitude</label>
                        <input type="number" step="any" name="longitude" class="form-control" placeholder="-74.0060">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>/admin/warehouses" class="btn btn-secondary admin-btn">Cancel</a>
        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-check-circle me-1"></i> Create Warehouse</button>
    </div>
</form>
