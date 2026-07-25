<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Welcome, <?= htmlspecialchars($customer->first_name) ?>!</h4>
            <p class="text-muted mb-0">Manage your shipments and account settings.</p>
        </div>
        <a href="<?= BASE_URL ?>/quote" class="btn btn-warning fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> New Shipment
        </a>
    </div>
    
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-primary fs-1 mb-2"><i class="bi bi-box-seam"></i></div>
                    <h5 class="fw-bold mb-0"><?= (int)($stats->total_shipments ?? 0) ?></h5>
                    <small class="text-muted">Total Shipments</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-truck"></i></div>
                    <h5 class="fw-bold mb-0"><?= (int)($stats->in_transit ?? 0) ?></h5>
                    <small class="text-muted">In Transit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-success fs-1 mb-2"><i class="bi bi-check-circle"></i></div>
                    <h5 class="fw-bold mb-0"><?= (int)($stats->delivered ?? 0) ?></h5>
                    <small class="text-muted">Delivered</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-secondary fs-1 mb-2"><i class="bi bi-clock"></i></div>
                    <h5 class="fw-bold mb-0"><?= (int)($stats->pending ?? 0) ?></h5>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Recent Shipments</h6>
            <a href="<?= BASE_URL ?>/dashboard/shipments" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($shipments)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr>
                        <th>Tracking #</th><th>Recipient</th><th>Status</th><th>Date</th><th></th>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($shipments as $s): ?>
                        <tr>
                            <td><a href="<?= BASE_URL ?>/dashboard/shipments/<?= $s->id ?>" class="fw-semibold"><?= htmlspecialchars($s->tracking_number) ?></a></td>
                            <td><?= htmlspecialchars($s->recipient_name) ?></td>
                            <td><span class="badge" style="background:<?= $s->status_color ?? '#6c757d' ?>"><?= htmlspecialchars($s->status_name ?? $s->status) ?></span></td>
                            <td><small><?= format_date($s->created_at, 'M d, Y') ?></small></td>
                            <td><a href="<?= BASE_URL ?>/dashboard/shipments/<?= $s->id ?>/label" class="btn btn-sm btn-outline-info" target="_blank"><i class="bi bi-upc-scan"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p>No shipments yet. <a href="<?= BASE_URL ?>/quote">Create your first shipment!</a></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>My Profile</h6>
                    <p class="small mb-1"><?= htmlspecialchars($customer->first_name . ' ' . $customer->last_name) ?></p>
                    <p class="small mb-1"><?= htmlspecialchars($customer->email) ?></p>
                    <p class="small mb-0"><?= htmlspecialchars($customer->phone) ?></p>
                    <a href="<?= BASE_URL ?>/dashboard/profile" class="btn btn-sm btn-outline-primary mt-2">Edit Profile</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Quick Actions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= BASE_URL ?>/tracking" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> Track</a>
                        <a href="<?= BASE_URL ?>/quote" class="btn btn-sm btn-outline-primary"><i class="bi bi-calculator"></i> Quote</a>
                        <a href="<?= BASE_URL ?>/dashboard/addresses" class="btn btn-sm btn-outline-primary"><i class="bi bi-geo-alt"></i> Addresses</a>
                        <a href="<?= BASE_URL ?>/dashboard/invoices" class="btn btn-sm btn-outline-primary"><i class="bi bi-receipt"></i> Invoices</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
