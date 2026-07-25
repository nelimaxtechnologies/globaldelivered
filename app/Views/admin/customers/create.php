<div class="admin-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-person-plus me-2"></i>Create New Customer</h5>
    <form method="POST" action="<?= BASE_URL ?>/admin/customers">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Customer Type</label>
                <select name="customer_type" class="form-select">
                    <option value="individual">Individual</option>
                    <option value="company">Company</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Company Name</label>
                <input type="text" name="company_name" class="form-control" placeholder="If company">
            </div>
            <div class="col-12"><hr></div>
            <div class="col-md-4"><label class="form-label fw-semibold">First Name *</label><input type="text" name="first_name" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Last Name *</label><input type="text" name="last_name" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Email *</label><input type="email" name="email" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Phone *</label><input type="tel" name="phone" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Alternative Phone</label><input type="tel" name="alternative_phone" class="form-control"></div>
            <div class="col-12"><label class="form-label fw-semibold">Address</label><input type="text" name="address_line1" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">City</label><input type="text" name="city" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">State</label><input type="text" name="state" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Country</label><input type="text" name="country" class="form-control"></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
        </div>
        <div class="d-flex gap-2 justify-content-end border-top pt-4">
            <a href="<?= BASE_URL ?>/admin/customers" class="btn btn-secondary admin-btn">Cancel</a>
            <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-check-circle me-1"></i> Create Customer</button>
        </div>
    </form>
</div>
