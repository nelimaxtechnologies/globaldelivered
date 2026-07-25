<!-- ============================================================
     SHIPMENTS MANAGEMENT - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Shipments</h4>
        <small class="text-muted">Manage and track all shipments</small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/shipments/create" class="btn btn-primary admin-btn">
            <i class="bi bi-plus-lg me-1"></i> New Shipment
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3">
            <div class="fw-bold fs-5"><?= number_format($pagination->total) ?></div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #ffa726;">
            <div class="fw-bold fs-5 text-warning"><?= $stats->pending ?? 0 ?></div>
            <small class="text-muted">Pending</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #42a5f5;">
            <div class="fw-bold fs-5 text-primary"><?= $stats->in_transit ?? 0 ?></div>
            <small class="text-muted">In Transit</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #4caf50;">
            <div class="fw-bold fs-5 text-success"><?= $stats->delivered ?? 0 ?></div>
            <small class="text-muted">Delivered</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #f44336;">
            <div class="fw-bold fs-5 text-danger"><?= $stats->returned ?? 0 ?></div>
            <small class="text-muted">Returned</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #9e9e9e;">
            <div class="fw-bold fs-5 text-muted"><?= $stats->cancelled ?? 0 ?></div>
            <small class="text-muted">Cancelled</small>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="admin-card mb-4">
    <form method="GET" action="<?= BASE_URL ?>/admin/shipments" id="filterForm">
        <div class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="form-label fw-semibold small">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Tracking #, name, email..."
                           value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <?php foreach ($statuses as $st): ?>
                    <option value="<?= htmlspecialchars($st->slug) ?>" <?= $filters['status'] === $st->slug ? 'selected' : '' ?>>
                        <?= htmlspecialchars($st->name) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Service</label>
                <select name="service" class="form-select">
                    <option value="">All Services</option>
                    <option value="domestic" <?= $filters['service'] === 'domestic' ? 'selected' : '' ?>>Domestic</option>
                    <option value="international" <?= $filters['service'] === 'international' ? 'selected' : '' ?>>International</option>
                    <option value="express" <?= $filters['service'] === 'express' ? 'selected' : '' ?>>Express</option>
                    <option value="same_day" <?= $filters['service'] === 'same_day' ? 'selected' : '' ?>>Same Day</option>
                    <option value="freight" <?= $filters['service'] === 'freight' ? 'selected' : '' ?>>Freight</option>
                    <option value="air_cargo" <?= $filters['service'] === 'air_cargo' ? 'selected' : '' ?>>Air Cargo</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <button type="submit" class="btn btn-primary admin-btn w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            <div class="col-lg-2 col-md-6">
                <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-outline-secondary admin-btn w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
        <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['service'])): ?>
        <div class="mt-3 d-flex gap-2 flex-wrap">
            <small class="text-muted me-1">Active filters:</small>
            <?php if (!empty($filters['search'])): ?>
                <span class="badge bg-primary">Search: <?= htmlspecialchars($filters['search']) ?></span>
            <?php endif; ?>
            <?php if (!empty($filters['status'])): ?>
                <span class="badge bg-primary">Status: <?= htmlspecialchars($filters['status']) ?></span>
            <?php endif; ?>
            <?php if (!empty($filters['service'])): ?>
                <span class="badge bg-primary">Service: <?= str_replace('_', ' ', htmlspecialchars($filters['service'])) ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Shipments Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th style="width: 180px;">Tracking</th>
                    <th>Route</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th class="text-end">Amount</th>
                    <th>Date</th>
                    <th class="text-center" style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($shipments)): ?>
                    <?php foreach ($shipments as $s): ?>
                    <tr>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="text-decoration-none">
                                <div class="fw-bold" style="color: var(--admin-primary, #1a237e); font-size: 0.88rem;">
                                    <?= htmlspecialchars($s->tracking_number) ?>
                                </div>
                            </a>
                            <small class="text-muted"><?= htmlspecialchars($s->sender_name) ?></small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <small class="text-muted"><?= htmlspecialchars($s->sender_city ?? '-') ?></small>
                                <i class="bi bi-arrow-right text-muted" style="font-size: 0.7rem;"></i>
                                <small class="fw-semibold"><?= htmlspecialchars($s->recipient_city ?? '-') ?></small>
                            </div>
                            <small class="text-muted"><?= htmlspecialchars($s->recipient_country ?? '') ?></small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-box-seam me-1"></i>
                                <?= ucwords(str_replace('_', ' ', htmlspecialchars($s->service_type))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background: <?= $s->status_color ?? '#6c757d' ?>15; color: <?= $s->status_color ?? '#6c757d' ?>; border: 1px solid <?= $s->status_color ?? '#6c757d' ?>30; font-weight: 600;">
                                <?= htmlspecialchars($s->status_name ?? ucfirst(str_replace('_', ' ', $s->status))) ?>
                            </span>
                        </td>
                        <td class="text-end fw-semibold"><?= format_currency($s->grand_total) ?></td>
                        <td>
                            <small class="text-muted"><?= format_date($s->created_at, 'M d, Y') ?></small>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>/edit" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>/label" class="btn btn-sm btn-outline-info" title="Print Label" target="_blank">
                                    <i class="bi bi-upc-scan"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/tracking/<?= htmlspecialchars($s->tracking_number) ?>" class="btn btn-sm btn-outline-dark" title="Track" target="_blank">
                                    <i class="bi bi-geo-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3" style="opacity: 0.3;"></i>
                                <h6 class="fw-bold mb-1">No shipments found</h6>
                                <p class="mb-3">Try adjusting your search or filter criteria</p>
                                <a href="<?= BASE_URL ?>/admin/shipments/create" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i> Create First Shipment
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination->totalPages > 1): ?>
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
        <small class="text-muted">
            Showing <?= (($pagination->page - 1) * $pagination->perPage) + 1 ?>–<?= min($pagination->page * $pagination->perPage, $pagination->totalItems) ?>
            of <?= number_format($pagination->total) ?> shipments
        </small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=1&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                <?php for ($p = max(1, $pagination->page - 2); $p <= min($pagination->totalPages, $pagination->page + 2); $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->totalPages ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<style>
    .table-admin thead th { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d; border-bottom: 2px solid #e9ecef; padding: 12px; white-space: nowrap; }
    .table-admin tbody td { padding: 12px; border-bottom: 1px solid #f1f3f5; }
    .table-admin tbody tr { transition: background 0.15s; }
    .table-admin tbody tr:hover { background: #f8f9fa; }
    .mini-stat { transition: all 0.2s; }
    .mini-stat:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .pagination .page-link { border-radius: 6px; margin: 0 2px; border: none; color: #6c757d; font-size: 0.82rem; }
    .pagination .page-item.active .page-link { background: var(--admin-primary, #1a237e); color: #fff; }
    .pagination .page-link:hover { background: #e9ecef; }
</style>
