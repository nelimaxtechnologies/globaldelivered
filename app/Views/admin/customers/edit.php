<!-- ============================================================
     EDIT CUSTOMER - PREMIUM MODERN DESIGN
     ============================================================ -->

<?php
$isCompany = $customer->customer_type === 'company';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,<?= $isCompany ? '#198754,#146c43' : '#0d6efd,#0a58ca' ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;">
            <?= strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) ?>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Edit Customer</h4>
            <small class="text-muted"><?= htmlspecialchars($customer->first_name . ' ' . $customer->last_name) ?> — <?= htmlspecialchars($customer->email) ?></small>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/admin/customers/<?= $customer->id ?>" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> Back to Profile
    </a>
</div>

<!-- Customer Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->total_shipments ?? 0) ?></div>
                    <div class="card-label">Shipments</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($stats->total_spent ?? 0) ?></div>
                    <div class="card-label">Total Spent</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
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
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="bi bi-clock"></i>
                </div>
                <div>
                    <div class="card-value"><?= $stats->last_shipment_date ? format_date($stats->last_shipment_date, 'M d') : 'Never' ?></div>
                    <div class="card-label">Last Activity</div>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/customers/<?= $customer->id ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">

    <div class="row g-4">
        <!-- Personal Information -->
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">1</div>
                    <h6 class="fw-bold mb-0">Personal Information</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($customer->first_name) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($customer->last_name) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Customer Type</label>
                        <select name="customer_type" class="form-select" id="customerType" onchange="toggleCompany()">
                            <option value="individual" <?= !$isCompany ? 'selected' : '' ?>>Individual</option>
                            <option value="company" <?= $isCompany ? 'selected' : '' ?>>Company</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="companyField" style="<?= !$isCompany ? 'display:none;' : '' ?>">
                        <label class="form-label fw-semibold">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($customer->company_name) ?>" placeholder="Company name">
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">2</div>
                    <h6 class="fw-bold mb-0">Contact Information</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer->email) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($customer->phone) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Alternative Phone</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" name="alternative_phone" class="form-control" value="<?= htmlspecialchars($customer->alternative_phone) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#ffc107,#e0a800);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">3</div>
                    <h6 class="fw-bold mb-0">Address</h6>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Street Address</label>
                        <input type="text" name="address_line1" class="form-control" value="<?= htmlspecialchars($customer->address_line1) ?>" placeholder="Street address">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($customer->city) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">State / Province</label>
                        <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($customer->state) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Country</label>
                        <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($customer->country) ?>" list="countryList">
                        <datalist id="countryList">
                            <?php foreach (['United States','United Kingdom','Canada','Australia','Germany','France','Italy','Spain','UAE','China','Japan','Nigeria','Kenya','South Africa','Brazil','India'] as $c): ?>
                            <option value="<?= $c ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="admin-card">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#6f42c1,#5a32a3);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">4</div>
                    <h6 class="fw-bold mb-0">Notes</h6>
                </div>
                <textarea name="notes" class="form-control" rows="3" placeholder="Internal notes about this customer..."><?= htmlspecialchars($customer->notes) ?></textarea>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success admin-btn">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                    <a href="<?= BASE_URL ?>/admin/customers/<?= $customer->id ?>" class="btn btn-outline-secondary admin-btn">
                        <i class="bi bi-eye me-1"></i> View Profile
                    </a>
                    <a href="<?= BASE_URL ?>/admin/shipments/create?customer_id=<?= $customer->id ?>" class="btn btn-outline-primary admin-btn">
                        <i class="bi bi-plus-lg me-1"></i> New Shipment
                    </a>
                </div>
            </div>

            <!-- Account Info -->
            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Account Info</h6>
                <div class="summary-row"><small class="text-muted">Customer ID</small><small class="fw-semibold">#<?= $customer->id ?></small></div>
                <div class="summary-row"><small class="text-muted">Type</small><span class="badge <?= $isCompany ? 'bg-success' : 'bg-primary' ?>"><?= ucfirst($customer->customer_type) ?></span></div>
                <div class="summary-row"><small class="text-muted">Status</small><span class="badge <?= $customer->is_active ? 'bg-success' : 'bg-secondary' ?>"><?= $customer->is_active ? 'Active' : 'Inactive' ?></span></div>
                <div class="summary-row"><small class="text-muted">Created</small><small class="fw-semibold"><?= format_date($customer->created_at) ?></small></div>
                <?php if ($customer->updated_at): ?>
                <div class="summary-row"><small class="text-muted">Updated</small><small class="fw-semibold"><?= format_date($customer->updated_at) ?></small></div>
                <?php endif; ?>
            </div>

            <!-- Recent Shipments -->
            <?php if (!empty($recentShipments)): ?>
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>Recent Shipments</h6>
                <?php foreach ($recentShipments as $s):
                    $statusColors = ['pending'=>'#ffc107','in_transit'=>'#0d6efd','delivered'=>'#198754','returned'=>'#dc3545','cancelled'=>'#6c757d'];
                    $sc = $statusColors[$s->status] ?? '#6c757d';
                ?>
                <div class="d-flex justify-content-between align-items-center py-2 <?= $s !== end($recentShipments) ? 'border-bottom' : '' ?>">
                    <div>
                        <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="fw-semibold text-decoration-none" style="font-size:0.85rem;"><?= htmlspecialchars($s->tracking_number) ?></a>
                        <div><small class="text-muted"><?= format_date($s->created_at, 'M d, Y') ?></small></div>
                    </div>
                    <span class="badge rounded-pill" style="background:<?= $sc ?>15;color:<?= $sc ?>;border:1px solid <?= $sc ?>30;font-size:0.7rem;"><?= ucfirst(str_replace('_', ' ', $s->status)) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<style>
    .summary-row { display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f3f5; }
    .summary-row:last-child { border-bottom:none; }
</style>

<script>
function toggleCompany() {
    var type = document.getElementById('customerType').value;
    document.getElementById('companyField').style.display = type === 'company' ? 'block' : 'none';
}
</script>
