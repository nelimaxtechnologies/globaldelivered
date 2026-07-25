<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-truck me-2"></i>Add New Vehicle</h4>
        <small class="text-muted">Register a new fleet vehicle</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/vehicles" class="btn btn-outline-secondary admin-btn admin-btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/vehicles">
    <?= csrf_field() ?>
    <div class="row g-4">
        <!-- Basic Info -->
        <div class="col-lg-8">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-truck me-2"></i>Vehicle Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Vehicle Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g., Ford Transit #1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Registration Number *</label>
                        <input type="text" name="registration_number" class="form-control" required placeholder="e.g., KAX 123Z">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Vehicle Type *</label>
                        <select name="vehicle_type" class="form-select" required>
                            <option value="van">Van</option>
                            <option value="truck">Truck</option>
                            <option value="car">Car</option>
                            <option value="motorbike">Motorbike</option>
                            <option value="container">Container</option>
                            <option value="air_cargo">Air Cargo</option>
                            <option value="ship">Ship</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Make</label>
                        <input type="text" name="make" class="form-control" placeholder="e.g., Ford">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Model</label>
                        <input type="text" name="model" class="form-control" placeholder="e.g., Transit">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Year</label>
                        <input type="number" name="year" class="form-control" min="2000" max="2030" placeholder="e.g., 2024">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Fuel Type</label>
                        <select name="fuel_type" class="form-select">
                            <option value="">Select...</option>
                            <option value="diesel">Diesel</option>
                            <option value="petrol">Petrol</option>
                            <option value="electric">Electric</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">Select Branch...</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b->id ?>"><?= htmlspecialchars($b->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="col-lg-4">
            <div class="admin-card h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-toggle-on me-2"></i>Capacity</h6>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Weight Capacity (kg)</label>
                    <input type="number" name="capacity_weight" class="form-control" step="0.1" min="0" placeholder="e.g., 1500">
                </div>
                <div class="mb-0">
                    <label class="form-label small fw-semibold">Volume Capacity (m&sup3;)</label>
                    <input type="number" name="capacity_volume" class="form-control" step="0.1" min="0" placeholder="e.g., 12.5">
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
                        <input type="text" name="insurance_policy" class="form-control" placeholder="e.g., INS-12345">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Expiry Date</label>
                        <input type="date" name="insurance_expiry" class="form-control">
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
                        <input type="date" name="maintenance_last" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Next Service Date</label>
                        <input type="date" name="maintenance_next" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>/admin/vehicles" class="btn btn-secondary admin-btn">Cancel</a>
        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-check-circle me-1"></i> Add Vehicle</button>
    </div>
</form>
