<div class="container py-4">
    <h4 class="fw-bold mb-4">My Addresses</h4>
    
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="bi bi-plus-circle fs-1 text-primary mb-2 d-block"></i>
                    <h6 class="fw-bold">Add New Address</h6>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addAddressForm">Add Address</button>
                </div>
            </div>
            
            <div class="collapse mt-3" id="addAddressForm">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <form method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-2">
                                <select name="label" class="form-select form-select-sm">
                                    <option value="Home">Home</option>
                                    <option value="Office">Office</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-2"><input type="text" name="address_line1" class="form-control form-control-sm" placeholder="Address" required></div>
                            <div class="mb-2"><input type="text" name="address_line2" class="form-control form-control-sm" placeholder="Address Line 2"></div>
                            <div class="row g-1 mb-2">
                                <div class="col-4"><input type="text" name="city" class="form-control form-control-sm" placeholder="City" required></div>
                                <div class="col-4"><input type="text" name="state" class="form-control form-control-sm" placeholder="State" required></div>
                                <div class="col-4"><input type="text" name="country" class="form-control form-control-sm" placeholder="Country" required></div>
                            </div>
                            <div class="mb-2"><div class="form-check"><input type="checkbox" name="is_default" class="form-check-input" id="isDefault"><label class="form-check-label small" for="isDefault">Set as default</label></div></div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">Save Address</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($addresses)): ?>
            <?php foreach ($addresses as $addr): ?>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="fw-bold mb-2">
                                <?= htmlspecialchars($addr->label) ?>
                                <?php if ($addr->is_default): ?><span class="badge bg-primary ms-1">Default</span><?php endif; ?>
                            </h6>
                            <form method="POST" action="<?= BASE_URL ?>/dashboard/addresses/<?= $addr->id ?>/delete" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this address?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                        <p class="mb-1 small"><?= htmlspecialchars($addr->address_line1) ?></p>
                        <?php if ($addr->address_line2): ?><p class="mb-1 small"><?= htmlspecialchars($addr->address_line2) ?></p><?php endif; ?>
                        <p class="mb-0 small text-muted"><?= htmlspecialchars($addr->city) ?>, <?= htmlspecialchars($addr->state) ?>, <?= htmlspecialchars($addr->country) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-lg-8">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                    <p>No saved addresses. Add your first address!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
