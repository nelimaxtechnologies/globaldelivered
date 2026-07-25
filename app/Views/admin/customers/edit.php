<div class="admin-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-pencil me-2"></i>Edit Customer</h5>
    <form method="POST" action="<?= BASE_URL ?>/admin/customers/<?= $customer->id ?>">
        <input type="hidden" name="_method" value="PUT">
        <div class="row g-3 mb-4">
            <div class="col-md-4"><label class="form-label fw-semibold">Type</label><select name="customer_type" class="form-select"><option value="individual" <?= $customer->customer_type === 'individual' ? 'selected' : '' ?>>Individual</option><option value="company" <?= $customer->customer_type === 'company' ? 'selected' : '' ?>>Company</option></select></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Company</label><input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($customer->company_name) ?>"></div>
            <div class="col-12"><hr></div>
            <div class="col-md-4"><label class="form-label fw-semibold">First Name *</label><input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($customer->first_name) ?>" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Last Name *</label><input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($customer->last_name) ?>" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Email *</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer->email) ?>" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Phone *</label><input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($customer->phone) ?>" required></div>
            <div class="col-md-4"><label class="form-label">Alt Phone</label><input type="tel" name="alternative_phone" class="form-control" value="<?= htmlspecialchars($customer->alternative_phone) ?>"></div>
            <div class="col-12"><label class="form-label fw-semibold">Address</label><input type="text" name="address_line1" class="form-control" value="<?= htmlspecialchars($customer->address_line1) ?>"></div>
            <div class="col-md-4"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="<?= htmlspecialchars($customer->city) ?>"></div>
            <div class="col-md-4"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="<?= htmlspecialchars($customer->state) ?>"></div>
            <div class="col-md-4"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="<?= htmlspecialchars($customer->country) ?>"></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($customer->notes) ?></textarea></div>
        </div>
        <div class="d-flex gap-2 justify-content-end border-top pt-4">
            <a href="<?= BASE_URL ?>/admin/customers/<?= $customer->id ?>" class="btn btn-secondary admin-btn">Cancel</a>
            <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-save me-1"></i> Update Customer</button>
        </div>
    </form>
</div>
