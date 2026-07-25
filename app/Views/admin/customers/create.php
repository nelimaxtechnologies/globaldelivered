<!-- ============================================================
     CREATE CUSTOMER - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;">
            <i class="bi bi-person-plus"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Create New Customer</h4>
            <small class="text-muted">Add a new customer to your database</small>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/admin/customers" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> Back to Customers
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/customers">
    <?= csrf_field() ?>

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
                        <input type="text" name="first_name" class="form-control" required placeholder="John">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" required placeholder="Doe">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Customer Type</label>
                        <select name="customer_type" class="form-select" id="customerType" onchange="toggleCompany()">
                            <option value="individual">Individual</option>
                            <option value="company">Company</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="companyField" style="display:none;">
                        <label class="form-label fw-semibold">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Company name">
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
                            <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" name="phone" class="form-control" required placeholder="+254700000000">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Alternative Phone</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" name="alternative_phone" class="form-control" placeholder="Optional">
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
                        <input type="text" name="address_line1" class="form-control" placeholder="123 Main Street">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" name="city" class="form-control" placeholder="City">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">State / Province</label>
                        <input type="text" name="state" class="form-control" placeholder="State">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Country</label>
                        <input type="text" name="country" class="form-control" placeholder="Country" list="countryList">
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
                <textarea name="notes" class="form-control" rows="3" placeholder="Internal notes about this customer..."></textarea>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Actions</h6>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success admin-btn">
                        <i class="bi bi-check-circle me-1"></i> Create Customer
                    </button>
                    <a href="<?= BASE_URL ?>/admin/customers" class="btn btn-outline-secondary admin-btn">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                </div>
            </div>

            <!-- Tips -->
            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Tips</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Fields marked with * are required</small></li>
                    <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Email must be unique per customer</small></li>
                    <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Phone should include country code</small></li>
                    <li class="mb-0"><small><i class="bi bi-check2 text-success me-2"></i>Address helps with delivery routing</small></li>
                </ul>
            </div>

            <!-- Quick Create -->
            <div class="admin-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Quick Create</h6>
                <p class="text-muted small mb-2">Need to create a shipment for this customer right away?</p>
                <p class="text-muted small mb-3">After creating the customer, you'll be redirected to their profile where you can create a shipment.</p>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:40px;height:40px;border-radius:10px;background:#f4f6f9;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-box-seam text-muted"></i>
                    </div>
                    <small class="text-muted">Shipments can be created from the customer profile page</small>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function toggleCompany() {
    var type = document.getElementById('customerType').value;
    document.getElementById('companyField').style.display = type === 'company' ? 'block' : 'none';
}
</script>
