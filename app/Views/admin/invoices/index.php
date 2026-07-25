<!-- ============================================================
     INVOICES MANAGEMENT - PREMIUM MODERN DESIGN v2
     ============================================================ -->

<!-- Overdue Alert Banner -->
<?php if (!empty($overdueInvoices)): ?>
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert" style="border-radius: 12px; border-left: 4px solid #dc3545;">
    <div class="d-flex align-items-center flex-grow-1">
        <div class="me-3">
            <div style="width:40px;height:40px;border-radius:10px;background:#dc354520;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <strong><?= count($overdueInvoices) ?> overdue invoice<?= count($overdueInvoices) > 1 ? 's' : '' ?></strong> need attention
            <div class="mt-1">
                <?php foreach (array_slice($overdueInvoices, 0, 3) as $oi): ?>
                <small class="me-3">
                    <a href="<?= BASE_URL ?>/admin/invoices/<?= $oi->id ?>" class="text-danger text-decoration-underline"><?= htmlspecialchars($oi->invoice_number) ?></a>
                    — <?= htmlspecialchars($oi->customer_name ?? 'N/A') ?>
                    (<?= format_currency($oi->total, $oi->currency) ?>, <?= $oi->days_overdue ?>d overdue)
                </small>
                <?php endforeach; ?>
                <?php if (count($overdueInvoices) > 3): ?>
                    <small class="text-danger">+<?= count($overdueInvoices) - 3 ?> more</small>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Invoices</h4>
        <small class="text-muted">Manage invoices, track payments and outstanding balances</small>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary admin-btn" onclick="exportInvoices()" title="Export">
            <i class="bi bi-download me-1"></i> Export
        </button>
        <a href="<?= BASE_URL ?>/admin/invoices/create" class="btn btn-primary admin-btn">
            <i class="bi bi-plus-lg me-1"></i> Create Invoice
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/invoices'">
            <div class="fw-bold fs-5"><?= number_format($stats->total ?? 0) ?></div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #6c757d; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/invoices?status=draft'">
            <div class="fw-bold fs-5 text-secondary"><?= $stats->draft ?? 0 ?></div>
            <small class="text-muted">Draft</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #42a5f5; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/invoices?status=sent'">
            <div class="fw-bold fs-5 text-primary"><?= $stats->sent ?? 0 ?></div>
            <small class="text-muted">Sent</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #4caf50; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/invoices?status=paid'">
            <div class="fw-bold fs-5 text-success"><?= $stats->paid ?? 0 ?></div>
            <small class="text-muted">Paid</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #f44336; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/invoices?status=overdue'">
            <div class="fw-bold fs-5 text-danger"><?= $stats->overdue ?? 0 ?></div>
            <small class="text-muted">Overdue</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #ffa726; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/invoices?status=partially_paid'">
            <div class="fw-bold fs-5 text-warning"><?= $stats->partially_paid ?? 0 ?></div>
            <small class="text-muted">Partial</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #0dcaf0; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/invoices?status=refunded'">
            <div class="fw-bold fs-5" style="color:#0dcaf0;"><?= $stats->refunded ?? 0 ?></div>
            <small class="text-muted">Refunded</small>
        </div>
    </div>
</div>

<!-- Financial Summary + Collection Rate -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div>
                <div class="card-value"><?= format_currency($stats->total_amount ?? 0) ?></div>
                <div class="card-label">Total Invoiced</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="card-value text-success"><?= format_currency($stats->paid_amount ?? 0) ?></div>
                <div class="card-label">Collected</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background: linear-gradient(135deg, #f44336, #d32f2f);">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <div class="card-value text-danger"><?= format_currency($stats->outstanding_amount ?? 0) ?></div>
                <div class="card-label">Outstanding</div>
            </div>
        </div>
    </div>
</div>

<!-- Collection Rate Progress -->
<?php
$totalAmt = (float) ($stats->total_amount ?? 0);
$paidAmt = (float) ($stats->paid_amount ?? 0);
$collectionRate = $totalAmt > 0 ? round(($paidAmt / $totalAmt) * 100, 1) : 0;
?>
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2"></i>Collection Rate</h6>
        <span class="fw-bold fs-5" style="color: <?= $collectionRate >= 80 ? '#198754' : ($collectionRate >= 50 ? '#ffc107' : '#dc3545') ?>"><?= $collectionRate ?>%</span>
    </div>
    <div class="progress" style="height: 12px; border-radius: 6px;">
        <div class="progress-bar" role="progressbar" style="width: <?= $collectionRate ?>%; background: linear-gradient(90deg, <?= $collectionRate >= 80 ? '#198754' : ($collectionRate >= 50 ? '#ffc107' : '#dc3545') ?>, <?= $collectionRate >= 80 ? '#20c997' : ($collectionRate >= 50 ? '#ffcd38' : '#ff6b6b') ?>); border-radius: 6px;" aria-valuenow="<?= $collectionRate ?>" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="d-flex justify-content-between mt-2">
        <small class="text-muted">Collected: <?= format_currency($paidAmt) ?></small>
        <small class="text-muted">Total: <?= format_currency($totalAmt) ?></small>
    </div>
</div>

<!-- Filters Card -->
<div class="admin-card mb-4">
    <form method="GET" action="<?= BASE_URL ?>/admin/invoices" id="filterForm">
        <div class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="form-label fw-semibold small">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Invoice #, tracking, customer..."
                           value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="draft" <?= $filters['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="sent" <?= $filters['status'] === 'sent' ? 'selected' : '' ?>>Sent</option>
                    <option value="paid" <?= $filters['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="partially_paid" <?= $filters['status'] === 'partially_paid' ? 'selected' : '' ?>>Partially Paid</option>
                    <option value="overdue" <?= $filters['status'] === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                    <option value="cancelled" <?= $filters['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    <option value="refunded" <?= $filters['status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <button type="submit" class="btn btn-primary admin-btn w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            <div class="col-lg-2 col-md-6">
                <a href="<?= BASE_URL ?>/admin/invoices" class="btn btn-outline-secondary admin-btn w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
        <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
        <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
            <small class="text-muted me-1">Active filters:</small>
            <?php if (!empty($filters['search'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">
                    Search: <?= htmlspecialchars($filters['search']) ?>
                    <a href="?status=<?= urlencode($filters['status']) ?>" class="text-white ms-1" style="text-decoration:none;">&times;</a>
                </span>
            <?php endif; ?>
            <?php if (!empty($filters['status'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">
                    <?= str_replace('_', ' ', ucfirst($filters['status'])) ?>
                    <a href="?search=<?= urlencode($filters['search']) ?>" class="text-white ms-1" style="text-decoration:none;">&times;</a>
                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Status Quick Filter Pills -->
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="<?= BASE_URL ?>/admin/invoices" class="status-pill <?= empty($filters['status']) ? 'active' : '' ?>">
        <i class="bi bi-grid-3x3-gap me-1"></i> All <span class="badge bg-light text-dark ms-1"><?= number_format($stats->total ?? 0) ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/invoices?status=draft" class="status-pill <?= ($filters['status'] ?? '') === 'draft' ? 'active' : '' ?>" style="--pill-color: #6c757d;">
        <i class="bi bi-pencil-square me-1"></i> Draft <span class="badge bg-light text-dark ms-1"><?= $stats->draft ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/invoices?status=sent" class="status-pill <?= ($filters['status'] ?? '') === 'sent' ? 'active' : '' ?>" style="--pill-color: #0d6efd;">
        <i class="bi bi-send me-1"></i> Sent <span class="badge bg-light text-dark ms-1"><?= $stats->sent ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/invoices?status=paid" class="status-pill <?= ($filters['status'] ?? '') === 'paid' ? 'active' : '' ?>" style="--pill-color: #198754;">
        <i class="bi bi-check-circle me-1"></i> Paid <span class="badge bg-light text-dark ms-1"><?= $stats->paid ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/invoices?status=overdue" class="status-pill <?= ($filters['status'] ?? '') === 'overdue' ? 'active' : '' ?>" style="--pill-color: #dc3545;">
        <i class="bi bi-exclamation-triangle me-1"></i> Overdue <span class="badge bg-light text-dark ms-1"><?= $stats->overdue ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/invoices?status=partially_paid" class="status-pill <?= ($filters['status'] ?? '') === 'partially_paid' ? 'active' : '' ?>" style="--pill-color: #ffc107;">
        <i class="bi bi-clock me-1"></i> Partial <span class="badge bg-light text-dark ms-1"><?= $stats->partially_paid ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/invoices?status=cancelled" class="status-pill <?= ($filters['status'] ?? '') === 'cancelled' ? 'active' : '' ?>" style="--pill-color: #6c757d;">
        <i class="bi bi-x-circle me-1"></i> Cancelled <span class="badge bg-light text-dark ms-1"><?= $stats->cancelled ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/invoices?status=refunded" class="status-pill <?= ($filters['status'] ?? '') === 'refunded' ? 'active' : '' ?>" style="--pill-color: #0dcaf0;">
        <i class="bi bi-arrow-return-left me-1"></i> Refunded <span class="badge bg-light text-dark ms-1"><?= $stats->refunded ?? 0 ?></span>
    </a>
</div>

<!-- Invoices Table -->
<div class="admin-card">
    <!-- Table Header Bar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">
                Showing <strong><?= $pagination->from ?></strong>–<strong><?= $pagination->to ?></strong> of <strong><?= number_format($pagination->total) ?></strong> invoices
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small me-1">Per page:</label>
            <select class="form-select form-select-sm" style="width: auto;" onchange="window.location=this.value">
                <?php foreach ([10, 25, 50, 100] as $pp): ?>
                <option value="?page=1&per_page=<?= $pp ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>" <?= ($pagination->perPage ?? 25) == $pp ? 'selected' : '' ?>><?= $pp ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-admin align-middle" id="invoicesTable">
            <thead>
                <tr>
                    <th style="width: 180px;">Invoice #</th>
                    <th>Customer</th>
                    <th>Tracking</th>
                    <th class="text-end">Amount</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Created</th>
                    <th class="text-center" style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($invoices)): ?>
                    <?php foreach ($invoices as $inv): ?>
                    <?php
                    $rowClass = '';
                    if ($inv->status === 'paid') $rowClass = 'row-paid';
                    elseif ($inv->status === 'overdue') $rowClass = 'row-overdue';
                    elseif ($inv->status === 'refunded') $rowClass = 'row-refunded';
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td>
                            <a href="<?= BASE_URL ?>/admin/invoices/<?= $inv->id ?>" class="text-decoration-none">
                                <div class="fw-bold" style="color: var(--admin-primary, #1a237e); font-size: 0.88rem;">
                                    <?= htmlspecialchars($inv->invoice_number) ?>
                                </div>
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:34px;height:34px;background:linear-gradient(135deg,<?= $inv->status === 'paid' ? '#198754,#146c43' : '#0d6efd,#0a58ca' ?>);color:#fff;font-weight:600;font-size:0.72rem;">
                                    <?php
                                    $nameParts = explode(' ', trim($inv->customer_name ?? '-'));
                                    $initials = '';
                                    foreach (array_slice($nameParts, 0, 2) as $part) {
                                        $initials .= strtoupper(substr($part, 0, 1));
                                    }
                                    echo $initials ?: '-';
                                    ?>
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.88rem;"><?= htmlspecialchars($inv->customer_name ?? '-') ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($inv->tracking_number): ?>
                                <a href="<?= BASE_URL ?>/admin/shipments/<?= $inv->shipment_id ?? '' ?>" class="text-decoration-none">
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-box-seam me-1"></i>
                                        <?= htmlspecialchars($inv->tracking_number) ?>
                                    </span>
                                </a>
                            <?php else: ?>
                                <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold" style="font-size: 0.95rem;"><?= format_currency($inv->total, $inv->currency) ?></span>
                        </td>
                        <td>
                            <?php
                            $statusConfig = [
                                'paid' => ['color' => '#198754', 'bg' => '#19875415', 'border' => '#19875430', 'icon' => 'bi-check-circle-fill'],
                                'sent' => ['color' => '#0d6efd', 'bg' => '#0d6efd15', 'border' => '#0d6efd30', 'icon' => 'bi-send-fill'],
                                'draft' => ['color' => '#6c757d', 'bg' => '#6c757d15', 'border' => '#6c757d30', 'icon' => 'bi-pencil-square'],
                                'overdue' => ['color' => '#dc3545', 'bg' => '#dc354515', 'border' => '#dc354530', 'icon' => 'bi-exclamation-circle-fill'],
                                'partially_paid' => ['color' => '#ffc107', 'bg' => '#ffc10715', 'border' => '#ffc10730', 'icon' => 'bi-clock-fill'],
                                'cancelled' => ['color' => '#6c757d', 'bg' => '#6c757d15', 'border' => '#6c757d30', 'icon' => 'bi-x-circle-fill'],
                                'refunded' => ['color' => '#0dcaf0', 'bg' => '#0dcaf015', 'border' => '#0dcaf030', 'icon' => 'bi-arrow-return-left'],
                            ];
                            $cfg = $statusConfig[$inv->status] ?? ['color' => '#212529', 'bg' => '#21252915', 'border' => '#21252930', 'icon' => 'bi-circle'];
                            ?>
                            <span class="badge rounded-pill" style="background: <?= $cfg['bg'] ?>; color: <?= $cfg['color'] ?>; border: 1px solid <?= $cfg['border'] ?>; font-weight: 600;">
                                <i class="bi <?= $cfg['icon'] ?> me-1"></i>
                                <?= str_replace('_', ' ', ucfirst($inv->status)) ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $dueClass = '';
                            $dueIcon = '';
                            if ($inv->due_date && !in_array($inv->status, ['paid', 'cancelled'])) {
                                $due = new DateTime($inv->due_date);
                                $now = new DateTime();
                                if ($due < $now) {
                                    $dueClass = 'text-danger fw-semibold';
                                    $dueIcon = '<i class="bi bi-exclamation-circle-fill me-1" style="font-size:0.7rem;"></i>';
                                } elseif ($due->diff($now)->days <= 3) {
                                    $dueClass = 'text-warning fw-semibold';
                                    $dueIcon = '<i class="bi bi-clock-fill me-1" style="font-size:0.7rem;"></i>';
                                }
                            }
                            ?>
                            <small class="<?= $dueClass ?>"><?= $dueIcon ?><?= $inv->due_date ? format_date($inv->due_date) : '-' ?></small>
                        </td>
                        <td><small class="text-muted"><?= format_date($inv->created_at, 'M d, Y') ?></small></td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= BASE_URL ?>/admin/invoices/<?= $inv->id ?>" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/invoices/<?= $inv->id ?>/edit" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($inv->status === 'draft'): ?>
                                <a href="<?= BASE_URL ?>/admin/invoices/<?= $inv->id ?>/mark-sent" class="btn btn-sm btn-outline-info mark-sent-btn" title="Mark Sent">
                                    <i class="bi bi-send"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (in_array($inv->status, ['sent', 'overdue', 'partially_paid'])): ?>
                                <a href="<?= BASE_URL ?>/admin/payments/create?invoice_id=<?= $inv->id ?>" class="btn btn-sm btn-outline-success" title="Record Payment">
                                    <i class="bi bi-credit-card"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-receipt" style="font-size:2.5rem;opacity:0.3;"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No invoices found</h6>
                                <p class="mb-3" style="max-width:400px;margin:0 auto;">
                                    <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                                        No results match your current filters. Try adjusting your search criteria.
                                    <?php else: ?>
                                        Get started by creating your first invoice.
                                    <?php endif; ?>
                                </p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                                    <a href="<?= BASE_URL ?>/admin/invoices" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Filters
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>/admin/invoices/create" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-lg me-1"></i> Create Invoice
                                    </a>
                                </div>
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
            Page <?= $pagination->page ?> of <?= number_format($pagination->totalPages) ?>
        </small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=1&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                <?php
                $start = max(1, $pagination->page - 2);
                $end = min($pagination->totalPages, $pagination->page + 2);
                if ($start > 1): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <?php for ($p = $start; $p <= $end; $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <?php if ($end < $pagination->totalPages): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->totalPages ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<style>
    /* Table */
    .table-admin thead th { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d; border-bottom: 2px solid #e9ecef; padding: 12px; white-space: nowrap; }
    .table-admin tbody td { padding: 12px; border-bottom: 1px solid #f1f3f5; }
    .table-admin tbody tr { transition: all 0.15s; }
    .table-admin tbody tr:hover { background: #f8f9fa; }
    .table-admin tbody tr.row-paid { background: #19875406; }
    .table-admin tbody tr.row-paid:hover { background: #1987540d; }
    .table-admin tbody tr.row-overdue { background: #dc354506; }
    .table-admin tbody tr.row-overdue:hover { background: #dc35450d; }
    .table-admin tbody tr.row-refunded { background: #0dcaf006; }
    .table-admin tbody tr.row-refunded:hover { background: #0dcaf00d; }

    /* Mini Stat Cards */
    .mini-stat { transition: all 0.2s; }
    .mini-stat:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }

    /* Status Quick Filter Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid #e9ecef;
        background: #fff;
        color: #495057;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .status-pill:hover { background: #f8f9fa; border-color: #dee2e6; color: #212529; transform: translateY(-1px); }
    .status-pill.active { background: var(--admin-primary, #1a237e); color: #fff; border-color: var(--admin-primary, #1a237e); }
    .status-pill.active .badge { background: rgba(255,255,255,0.2) !important; color: #fff !important; }
    .status-pill .badge { font-size: 0.7rem; }

    /* Pagination */
    .pagination .page-link { border-radius: 6px; margin: 0 2px; border: none; color: #6c757d; font-size: 0.82rem; }
    .pagination .page-item.active .page-link { background: var(--admin-primary, #1a237e); color: #fff; }
    .pagination .page-link:hover { background: #e9ecef; }
</style>

<script>
function exportInvoices() {
    var params = new URLSearchParams(window.location.search);
    window.location = '<?= BASE_URL ?>/admin/invoices/export?' + params.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    // Mark Sent confirmation
    document.querySelectorAll('.mark-sent-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('href');
            Swal.fire({
                title: 'Mark as Sent?',
                text: 'This invoice will be marked as sent.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark it!',
                cancelButtonText: 'Cancel'
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location = url;
                }
            });
        });
    });
});
</script>
