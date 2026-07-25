<!-- Add New Branch -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-building me-2"></i>Add New Branch</h4>
        <small class="text-muted">Register a new branch location</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/branches" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/branches">
    <?= csrf_field() ?>
    <div class="row g-4">
        <!-- Branch Information -->
        <div class="col-lg-8">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-building me-2"></i>Branch Information</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g., Nairobi Central">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" required placeholder="e.g., NBO001" style="text-transform:uppercase;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Branch Type</label>
                        <select name="branch_type" class="form-select">
                            <option value="local">Local</option>
                            <option value="regional">Regional</option>
                            <option value="head_office">Head Office</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Manager</label>
                        <select name="manager_id" class="form-select select2">
                            <option value="">Select Manager...</option>
                            <?php foreach ($managers as $m): ?>
                            <option value="<?= $m->id ?>"><?= htmlspecialchars($m->first_name . ' ' . $m->last_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" required placeholder="+1 234 567 890">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="branch@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">WhatsApp</label>
                        <input type="tel" name="whatsapp" class="form-control" placeholder="+1 234 567 890">
                    </div>
                </div>
            </div>
        </div>

        <!-- Operating Hours -->
        <div class="col-lg-4">
            <div class="admin-card h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-clock me-2"></i>Operating Hours</h6>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Opening</label>
                        <input type="time" name="opening_time" class="form-control" value="08:00">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Closing</label>
                        <input type="time" name="closing_time" class="form-control" value="17:00">
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="col-12">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Address</h6>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" name="address_line1" class="form-control" required placeholder="Street address">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-control" placeholder="Suite, unit, etc.">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" name="state" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                        <select name="country" class="form-select select2" required>
                            <option value="">Select Country</option>
                            <?php
                            $commonCountries = ['United States','United Kingdom','Canada','Germany','France','Nigeria','Kenya','South Africa','India','China','Japan','Australia','Brazil','UAE','Saudi Arabia','Egypt','Ghana','Tanzania','Uganda','Rwanda','Ethiopia','Morocco','Netherlands','Belgium','Spain','Italy','Sweden','Norway','Switzerland','Singapore','Thailand','Vietnam','Philippines','Indonesia','Malaysia','Mexico','Argentina','Colombia','Chile','Peru'];
                            $existingCountries = array_column($allCountries, 'country');
                            $allC = array_unique(array_merge($commonCountries, $existingCountries));
                            sort($allC);
                            foreach ($allC as $c): ?>
                            <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="number" name="latitude" class="form-control" step="0.0000001" placeholder="-1.2921">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="number" name="longitude" class="form-control" step="0.0000001" placeholder="36.8219">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>/admin/branches" class="btn btn-outline-secondary admin-btn">Cancel</a>
        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-check-circle me-1"></i> Create Branch</button>
    </div>
</form>
