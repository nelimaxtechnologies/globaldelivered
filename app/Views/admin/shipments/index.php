<!-- ============================================================
     SHIPMENTS MANAGEMENT - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #1a237e;">Shipments</h4>
        <small class="text-muted">Manage and track all shipments</small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/shipments/create" class="btn btn-primary admin-btn" style="background: linear-gradient(135deg, #1a237e, #3949ab); border: none; border-radius: 10px; padding: 10px 20px; box-shadow: 0 4px 15px rgba(26,35,126,0.3);">
            <i class="bi bi-plus-lg me-1"></i> New Shipment
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <!-- Total -->
    <div class="col-lg-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-radius:14px;border-left:4px solid #1a237e;">
            <div class="fw-bold fs-5" style="color:#1a237e;"><?= number_format($pagination->total) ?></div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <!-- Pending -->
    <div class="col-lg-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-radius:14px;border-left:4px solid #ffa726;">
            <div class="fw-bold fs-5" style="color:#f57c00;"><?= $stats->pending ?? 0 ?></div>
            <small class="text-muted">Pending</small>
        </div>
    </div>
    <!-- In Transit -->
    <div class="col-lg-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-radius:14px;border-left:4px solid #1976d2;">
            <div class="fw-bold fs-5" style="color:#1976d2;"><?= $stats->in_transit ?? 0 ?></div>
            <small class="text-muted">In Transit</small>
        </div>
    </div>
    <!-- Delivered -->
    <div class="col-lg-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-radius:14px;border-left:4px solid #43a047;">
            <div class="fw-bold fs-5" style="color:#43a047;"><?= $stats->delivered ?? 0 ?></div>
            <small class="text-muted">Delivered</small>
        </div>
    </div>
    <!-- Returned -->
    <div class="col-lg-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-radius:14px;border-left:4px solid #e53935;">
            <div class="fw-bold fs-5" style="color:#e53935;"><?= $stats->returned ?? 0 ?></div>
            <small class="text-muted">Returned</small>
        </div>
    </div>
    <!-- Cancelled -->
    <div class="col-lg-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-radius:14px;border-left:4px solid #9e9e9e;">
            <div class="fw-bold fs-5" style="color:#757575;"><?= $stats->cancelled ?? 0 ?></div>
            <small class="text-muted">Cancelled</small>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="admin-card mb-4" style="border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid rgba(26,35,126,0.08);">
    <form method="GET" action="<?= BASE_URL ?>/admin/shipments" id="filterForm">
        <div class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="form-label fw-semibold small" style="color: #1a237e;">Search</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius: 10px 0 0 10px; background: linear-gradient(135deg, #e8eaf6, #c5cae9); border: 1px solid rgba(26,35,126,0.15); border-right: none;">
                        <i class="bi bi-search" style="color: #1a237e;"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Tracking #, name, email..." style="border-radius: 0 10px 10px 0; border: 1px solid rgba(26,35,126,0.15); padding: 10px 15px; box-shadow: 0 2px 8px rgba(26,35,126,0.05);" value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small" style="color: #1a237e;">Status</label>
                <select name="status" class="form-select" style="border-radius: 10px; border: 1px solid rgba(26,35,126,0.15); padding: 10px 15px; box-shadow: 0 2px 8px rgba(26,35,126,0.05);">
                    <option value="">All Statuses</option>
                    <?php foreach ($statuses as $st): ?>
                    <option value="<?= htmlspecialchars($st->slug) ?>" <?= $filters['status'] === $st->slug ? 'selected' : '' ?>>
                        <?= htmlspecialchars($st->name) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small" style="color: #1a237e;">Service</label>
                <select name="service" class="form-select" style="border-radius: 10px; border: 1px solid rgba(26,35,126,0.15); padding: 10px 15px; box-shadow: 0 2px 8px rgba(26,35,126,0.05);">
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
                <button type="submit" class="btn btn-primary admin-btn w-100" style="background: linear-gradient(135deg, #1a237e, #3949ab); border: none; border-radius: 10px; padding: 10px 15px; box-shadow: 0 4px 15px rgba(26,35,126,0.3);">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            <div class="col-lg-2 col-md-6">
                <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-outline-secondary admin-btn w-100" style="border-radius: 10px; border: 1px solid rgba(26,35,126,0.2); padding: 10px 15px;">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
        <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['service'])): ?>
        <div class="mt-3 d-flex gap-2 flex-wrap">
            <small class="text-muted me-1">Active filters:</small>
            <?php if (!empty($filters['search'])): ?>
                <span class="badge" style="background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; border-radius: 20px; padding: 6px 14px; font-weight: 500;">Search: <?= htmlspecialchars($filters['search']) ?></span>
            <?php endif; ?>
            <?php if (!empty($filters['status'])): ?>
                <span class="badge" style="background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; border-radius: 20px; padding: 6px 14px; font-weight: 500;">Status: <?= htmlspecialchars($filters['status']) ?></span>
            <?php endif; ?>
            <?php if (!empty($filters['service'])): ?>
                <span class="badge" style="background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; border-radius: 20px; padding: 6px 14px; font-weight: 500;">Service: <?= str_replace('_', ' ', htmlspecialchars($filters['service'])) ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Shipments Table -->
<div class="admin-card" style="border-radius:14px;overflow:hidden;">
    <div class="table-responsive" style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
        <table class="table table-admin align-middle mb-0" style="min-width:700px;">
            <thead>
                <tr style="background:linear-gradient(135deg,#1a237e,#283593);color:#fff;">
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;white-space:nowrap;">Tracking</th>
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;white-space:nowrap;">Route</th>
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;white-space:nowrap;">Service</th>
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;white-space:nowrap;">Status</th>
                    <th class="text-end" style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;white-space:nowrap;">Amount</th>
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;white-space:nowrap;">Date</th>
                    <th class="text-center" style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;white-space:nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($shipments)): ?>
                    <?php foreach ($shipments as $s): ?>
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 14px;max-width:180px;">
                            <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="text-decoration-none">
                                <div class="fw-bold" style="color:#1a237e;font-size:0.85rem;"><?= htmlspecialchars($s->tracking_number) ?></div>
                            </a>
                            <small class="text-muted d-block text-truncate" style="max-width:150px;"><?= htmlspecialchars($s->sender_name) ?></small>
                        </td>
                        <td style="padding:10px 14px;">
                            <div class="d-flex align-items-center gap-1">
                                <small class="text-muted text-truncate" style="max-width:80px;"><?= htmlspecialchars($s->sender_city ?? '-') ?></small>
                                <i class="bi bi-arrow-right" style="font-size:0.65rem;color:#1a237e;flex-shrink:0;"></i>
                                <small class="fw-semibold text-truncate" style="max-width:80px;"><?= htmlspecialchars($s->recipient_city ?? '-') ?></small>
                            </div>
                        </td>
                        <td style="padding:10px 14px;">
                            <span class="badge" style="background:rgba(26,35,126,0.08);color:#1a237e;border-radius:8px;padding:4px 10px;font-weight:500;font-size:0.72rem;white-space:nowrap;">
                                <?= ucwords(str_replace('_', ' ', htmlspecialchars($s->service_type))) ?>
                            </span>
                        </td>
                        <td style="padding:10px 14px;">
                            <span class="badge" style="background:<?= $s->status_color ?? '#6c757d' ?>15;color:<?= $s->status_color ?? '#6c757d' ?>;border:1px solid <?= $s->status_color ?? '#6c757d' ?>30;border-radius:20px;padding:4px 12px;font-weight:600;font-size:0.72rem;white-space:nowrap;">
                                <?= htmlspecialchars($s->status_name ?? ucfirst(str_replace('_', ' ', $s->status))) ?>
                            </span>
                        </td>
                        <td class="text-end fw-semibold" style="padding:10px 14px;color:#1a237e;white-space:nowrap;"><?= format_currency($s->grand_total) ?></td>
                        <td style="padding:10px 14px;white-space:nowrap;">
                            <small class="text-muted"><?= format_date($s->created_at, 'M d, Y') ?></small>
                        </td>
                        <td style="padding:10px 14px;">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="btn btn-sm" title="View" style="border-radius:8px;border:1px solid rgba(26,35,126,0.15);color:#1a237e;padding:4px 8px;font-size:0.75rem;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>/edit" class="btn btn-sm" title="Edit" style="border-radius:8px;border:1px solid rgba(26,35,126,0.15);color:#1a237e;padding:4px 8px;font-size:0.75rem;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>/label" class="btn btn-sm" title="Label" target="_blank" style="border-radius:8px;border:1px solid rgba(26,35,126,0.15);color:#1a237e;padding:4px 8px;font-size:0.75rem;">
                                    <i class="bi bi-upc-scan"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/tracking/<?= htmlspecialchars($s->tracking_number) ?>" class="btn btn-sm" title="Track" target="_blank" style="border-radius:8px;border:1px solid rgba(26,35,126,0.15);color:#1a237e;padding:4px 8px;font-size:0.75rem;">
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
                                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #e8eaf6, #c5cae9); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 4px 20px rgba(26,35,126,0.15);">
                                    <i class="bi bi-inbox fs-1" style="color: #1a237e; opacity: 0.6;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: #1a237e;">No shipments found</h6>
                                <p class="mb-3" style="color: #757575;">Try adjusting your search or filter criteria</p>
                                <a href="<?= BASE_URL ?>/admin/shipments/create" class="btn" style="background: linear-gradient(135deg, #1a237e, #3949ab); border: none; border-radius: 10px; color: #fff; padding: 10px 20px; box-shadow: 0 4px 15px rgba(26,35,126,0.3);">
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
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 pb-3 px-4" style="border-top: 1px solid #f1f3f5;">
        <small class="text-muted">
            Showing <?= (($pagination->page - 1) * $pagination->perPage) + 1 ?>–<?= min($pagination->page * $pagination->perPage, $pagination->totalItems) ?>
            of <?= number_format($pagination->total) ?> shipments
        </small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=1&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>" style="border-radius: 20px; margin: 0 3px; border: none; color: #1a237e; font-size: 0.82rem; padding: 6px 12px;">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>" style="border-radius: 20px; margin: 0 3px; border: none; color: #1a237e; font-size: 0.82rem; padding: 6px 12px;">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                <?php for ($p = max(1, $pagination->page - 2); $p <= min($pagination->totalPages, $pagination->page + 2); $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>" style="border-radius: 20px; margin: 0 3px; border: none; font-size: 0.82rem; padding: 6px 12px; <?= $p === $pagination->page ? 'background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; box-shadow: 0 4px 15px rgba(26,35,126,0.3);' : 'color: #1a237e;' ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>" style="border-radius: 20px; margin: 0 3px; border: none; color: #1a237e; font-size: 0.82rem; padding: 6px 12px;">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->totalPages ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&service=<?= urlencode($filters['service']) ?>" style="border-radius: 20px; margin: 0 3px; border: none; color: #1a237e; font-size: 0.82rem; padding: 6px 12px;">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<style>
    .table-admin tbody td { padding:10px 14px; border-bottom:1px solid #f1f3f5; }
    .table-admin tbody tr:hover { background:rgba(26,35,126,0.03); }
    .mini-stat { transition:all 0.2s; }
    .mini-stat:hover { transform:translateY(-2px); box-shadow:0 4px 16px rgba(0,0,0,0.1) !important; }
    .pagination .page-link { border-radius:20px; margin:0 2px; border:none; color:#1a237e; font-size:0.8rem; padding:5px 10px; }
    .pagination .page-item.active .page-link { background:linear-gradient(135deg,#1a237e,#3949ab); color:#fff; }
    .pagination .page-link:hover { background:#e8eaf6; }
    .pagination .page-item.disabled .page-link { color:#ccc; }
</style>