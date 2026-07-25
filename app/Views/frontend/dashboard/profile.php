<div class="container py-4">
    <h4 class="fw-bold mb-4">My Profile</h4>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($customer->first_name) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($customer->last_name) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($customer->email) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($customer->phone) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="address_line1" class="form-control" value="<?= htmlspecialchars($customer->address_line1 ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($customer->city ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($customer->state ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Country</label>
                        <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($customer->country ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($customer->postal_code ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4"><i class="bi bi-check-circle me-1"></i> Update Profile</button>
            </form>
        </div>
    </div>
</div>
