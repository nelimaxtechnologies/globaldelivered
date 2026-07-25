<!-- Customer Header -->
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:<?= $customer->customer_type === 'company' ? 'linear-gradient(135deg,#198754,#146c43)' : 'linear-gradient(135deg,#0d6efd,#0a58ca)' ?>;color:#fff;font-weight:700;font-size:1.2rem;">
                <?= strtoupper(substr($customer->first_name,0,1) . substr($customer->last_name,0,1)) ?>
            </div>
            <div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($customer->first_name . ' ' . $customer->last_name) ?></h5>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge" style="background:<?= $customer->customer_type === 'company' ? '#198754' : '#6c757d' ?>;"><?= $customer->customer_type ?></span>
                    <?php if ($customer->company_name): ?>
                        <span class="fw-semibold small"><?= htmlspecialchars($customer->company_name) ?></span>
                    <?php endif; ?>
                    <span class="text-muted small"><i class="bi bi-calendar"></i> Customer since <?= format_date($customer->created_at, 'M d, Y') ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/customers/<?= $customer->id ?>/edit" class="btn btn-outline-primary admin-btn admin-btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="<?= BASE_URL ?>/admin/shipments?customer_id=<?= $customer->id ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm">
                <i class="bi bi-box-seam me-1"></i> View Shipments
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->total ?? 0) ?></div>
                    <div class="card-label">Total Shipments</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
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
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <div class="card-value"><?= (int)($stats->in_transit ?? 0) ?></div>
                    <div class="card-label">In Transit</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($stats->total_spent ?? 0) ?></div>
                    <div class="card-label">Total Spent</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Contact Info -->
    <div class="col-lg-5">
        <div class="admin-card h-100">
            <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Contact Information</h6>
            <div class="mb-3">
                <small class="text-muted d-block">Email Address</small>
                <span><i class="bi bi-envelope me-2 text-muted"></i><?= htmlspecialchars($customer->email) ?></span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">Phone Number</small>
                <span><i class="bi bi-phone me-2 text-muted"></i><?= htmlspecialchars($customer->phone) ?></span>
            </div>
            <?php if ($customer->alternative_phone): ?>
            <div class="mb-3">
                <small class="text-muted d-block">Alternative Phone</small>
                <span><i class="bi bi-phone me-2 text-muted"></i><?= htmlspecialchars($customer->alternative_phone) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($customer->whatsapp): ?>
            <div class="mb-3">
                <small class="text-muted d-block">WhatsApp</small>
                <span><i class="bi bi-whatsapp me-2 text-muted"></i><?= htmlspecialchars($customer->whatsapp) ?></span>
            </div>
            <?php endif; ?>
            
            <hr>
            
            <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Address</h6>
            <?php if ($customer->address_line1): ?>
                <p class="mb-1"><?= htmlspecialchars($customer->address_line1) ?></p>
                <?php if ($customer->address_line2): ?>
                    <p class="mb-1"><?= htmlspecialchars($customer->address_line2) ?></p>
                <?php endif; ?>
                <p class="mb-0 text-muted">
                    <?= htmlspecialchars($customer->city) ?><?= $customer->city && $customer->state ? ', ' : '' ?><?= htmlspecialchars($customer->state) ?>
                    <?= $customer->postal_code ? htmlspecialchars($customer->postal_code) . ' ' : '' ?><?= htmlspecialchars($customer->country) ?>
                </p>
            <?php else: ?>
                <p class="text-muted mb-0">No address on file</p>
            <?php endif; ?>
            
            <?php if ($customer->notes): ?>
            <hr>
            <h6 class="fw-bold mb-2"><i class="bi bi-journal-text me-2"></i>Notes</h6>
            <p class="mb-0 small"><?= nl2br(htmlspecialchars($customer->notes)) ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Shipments -->
    <div class="col-lg-7">
        <div class="admin-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-2"></i>Recent Shipments</h6>
                <?php if (!empty($shipments)): ?>
                    <a href="<?= BASE_URL ?>/admin/shipments?customer_id=<?= $customer->id ?>" class="small">View All <i class="bi bi-arrow-right"></i></a>
                <?php endif; ?>
            </div>
            <?php if (!empty($shipments)): ?>
            <div class="table-responsive">
                <table class="table table-sm table-admin">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Destination</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shipments as $s): ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="fw-semibold text-decoration-none">
                                    <?= htmlspecialchars($s->tracking_number) ?>
                                </a>
                            </td>
                            <td>
                                <small><?= htmlspecialchars($s->recipient_city) ?>, <?= htmlspecialchars($s->recipient_country) ?></small>
                            </td>
                            <td>
                                <span class="badge" style="background:<?= $s->status_color ?? '#6c757d' ?>;"><?= htmlspecialchars($s->status_name ?? $s->status) ?></span>
                            </td>
                            <td class="text-end fw-semibold"><?= format_currency($s->grand_total) ?></td>
                            <td class="text-end"><small class="text-muted"><?= format_date($s->created_at, 'M d, Y') ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-2 mb-0">No shipments yet</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
