<?php
$typeColors = [
    'head_office' => ['#6f42c1', '#6f42c120'],
    'regional' => ['#0dcaf0', '#0dcaf020'],
    'local' => ['#198754', '#19875420'],
];
[$typeColor, $typeBg] = $typeColors[$branch->branch_type] ?? ['#6c757d', '#6c757d20'];
?>

<!-- Edit Branch -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width:44px;height:44px;border-radius:12px;background:<?= $typeBg ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-building" style="font-size:1.1rem;color:<?= $typeColor ?>;"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Edit Branch</h4>
            <small class="text-muted"><?= htmlspecialchars($branch->name) ?> (<?= htmlspecialchars($branch->code) ?>)</small>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/branches/<?= $branch->id ?>" class="btn btn-outline-primary admin-btn">
            <i class="bi bi-eye me-1"></i> View
        </a>
        <a href="<?= BASE_URL ?>/admin/branches" class="btn btn-outline-secondary admin-btn">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/branches/<?= $branch->id ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <div class="row g-4">
        <!-- Branch Information -->
        <div class="col-lg-8">
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-building me-2"></i>Branch Information</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($branch->name) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Code</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($branch->code) ?>" disabled>
                        <small class="text-muted">Cannot be changed</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Branch Type</label>
                        <select name="branch_type" class="form-select">
                            <option value="local" <?= $branch->branch_type === 'local' ? 'selected' : '' ?>>Local</option>
                            <option value="regional" <?= $branch->branch_type === 'regional' ? 'selected' : '' ?>>Regional</option>
                            <option value="head_office" <?= $branch->branch_type === 'head_office' ? 'selected' : '' ?>>Head Office</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Manager</label>
                        <select name="manager_id" class="form-select select2">
                            <option value="">Select Manager...</option>
                            <?php foreach ($managers as $m): ?>
                            <option value="<?= $m->id ?>" <?= $m->id == $branch->manager_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m->first_name . ' ' . $m->last_name) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($branch->phone) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($branch->email) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">WhatsApp</label>
                        <input type="tel" name="whatsapp" class="form-control" value="<?= htmlspecialchars($branch->whatsapp) ?>">
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
                        <input type="time" name="opening_time" class="form-control" value="<?= $branch->opening_time ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Closing</label>
                        <input type="time" name="closing_time" class="form-control" value="<?= $branch->closing_time ?>">
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
                        <input type="text" name="address_line1" class="form-control" value="<?= htmlspecialchars($branch->address_line1) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-control" value="<?= htmlspecialchars($branch->address_line2) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($branch->city) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($branch->state) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($branch->postal_code) ?>">
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
                            <option value="<?= htmlspecialchars($c) ?>" <?= $branch->country === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="number" name="latitude" class="form-control" step="0.0000001" value="<?= $branch->latitude ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="number" name="longitude" class="form-control" step="0.0000001" value="<?= $branch->longitude ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>/admin/branches/<?= $branch->id ?>" class="btn btn-outline-secondary admin-btn">Cancel</a>
        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-save me-1"></i> Update Branch</button>
    </div>
</form>
