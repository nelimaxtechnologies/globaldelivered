<!-- ============================================================
     PAYMENTS MANAGEMENT - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Payments</h4>
        <small class="text-muted">Track all payment transactions and processing history</small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/payments/create" class="btn btn-primary admin-btn">
            <i class="bi bi-plus-lg me-1"></i> Record Payment
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/payments'">
            <div class="fw-bold fs-5"><?= number_format($stats->total ?? 0) ?></div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #198754; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/payments?status=completed'">
            <div class="fw-bold fs-5 text-success"><?= $stats->completed ?? 0 ?></div>
            <small class="text-muted">Completed</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #ffc107; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/payments?status=pending'">
            <div class="fw-bold fs-5 text-warning"><?= $stats->pending ?? 0 ?></div>
            <small class="text-muted">Pending</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #dc3545; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/payments?status=failed'">
            <div class="fw-bold fs-5 text-danger"><?= $stats->failed ?? 0 ?></div>
            <small class="text-muted">Failed</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top: 3px solid #0dcaf0; cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/payments?status=refunded'">
            <div class="fw-bold fs-5 text-info"><?= $stats->refunded ?? 0 ?></div>
            <small class="text-muted">Refunded</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3">
            <div class="fw-bold fs-5"><?= format_currency($stats->total_amount ?? 0) ?></div>
            <small class="text-muted">Total Value</small>
        </div>
    </div>
</div>

<!-- Financial Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="card-value text-success"><?= format_currency($stats->completed_amount ?? 0) ?></div>
                <div class="card-label">Completed</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                <i class="bi bi-clock"></i>
            </div>
            <div>
                <div class="card-value text-warning"><?= format_currency($stats->pending_amount ?? 0) ?></div>
                <div class="card-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
                <i class="bi bi-arrow-return-left"></i>
            </div>
            <div>
                <div class="card-value text-info"><?= format_currency($stats->refunded_amount ?? 0) ?></div>
                <div class="card-label">Refunded</div>
            </div>
        </div>
    </div>
</div>

<!-- Success Rate Progress -->
<?php
$totalPayments = (int) ($stats->total ?? 0);
$completedPayments = (int) ($stats->completed ?? 0);
$successRate = $totalPayments > 0 ? round(($completedPayments / $totalPayments) * 100, 1) : 0;
?>
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2"></i>Payment Success Rate</h6>
        <span class="fw-bold fs-5" style="color: <?= $successRate >= 90 ? '#198754' : ($successRate >= 70 ? '#ffc107' : '#dc3545') ?>"><?= $successRate ?>%</span>
    </div>
    <div class="progress" style="height: 12px; border-radius: 6px;">
        <div class="progress-bar" role="progressbar" style="width: <?= $successRate ?>%; background: linear-gradient(90deg, <?= $successRate >= 90 ? '#198754' : ($successRate >= 70 ? '#ffc107' : '#dc3545') ?>, <?= $successRate >= 90 ? '#20c997' : ($successRate >= 70 ? '#ffcd38' : '#ff6b6b') ?>); border-radius: 6px;" aria-valuenow="<?= $successRate ?>" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="d-flex justify-content-between mt-2">
        <small class="text-muted">Completed: <?= number_format($completedPayments) ?> of <?= number_format($totalPayments) ?></small>
        <small class="text-muted">Failed/Refunded: <?= number_format(($stats->failed ?? 0) + ($stats->refunded ?? 0)) ?></small>
    </div>
</div>

<!-- Filters Card -->
<div class="admin-card mb-4">
    <form method="GET" action="<?= BASE_URL ?>/admin/payments" id="filterForm">
        <div class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold small">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Transaction ID, reference, tracking..."
                           value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="completed" <?= $filters['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="failed" <?= $filters['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                    <option value="refunded" <?= $filters['status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Method</label>
                <select name="method" class="form-select" onchange="this.form.submit()">
                    <option value="">All Methods</option>
                    <option value="cash" <?= $filters['method'] === 'cash' ? 'selected' : '' ?>>Cash</option>
                    <option value="bank" <?= $filters['method'] === 'bank' ? 'selected' : '' ?>>Bank Transfer</option>
                    <option value="stripe" <?= $filters['method'] === 'stripe' ? 'selected' : '' ?>>Stripe</option>
                    <option value="paypal" <?= $filters['method'] === 'paypal' ? 'selected' : '' ?>>PayPal</option>
                    <option value="flutterwave" <?= $filters['method'] === 'flutterwave' ? 'selected' : '' ?>>Flutterwave</option>
                    <option value="paystack" <?= $filters['method'] === 'paystack' ? 'selected' : '' ?>>Paystack</option>
                    <option value="mpesa" <?= $filters['method'] === 'mpesa' ? 'selected' : '' ?>>M-Pesa</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <button type="submit" class="btn btn-primary admin-btn w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            <div class="col-lg-2 col-md-6">
                <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-outline-secondary admin-btn w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
        <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['method'])): ?>
        <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
            <small class="text-muted me-1">Active filters:</small>
            <?php if (!empty($filters['search'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">
                    Search: <?= htmlspecialchars($filters['search']) ?>
                    <a href="?status=<?= urlencode($filters['status']) ?>&method=<?= urlencode($filters['method']) ?>" class="text-white ms-1" style="text-decoration:none;">&times;</a>
                </span>
            <?php endif; ?>
            <?php if (!empty($filters['status'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">
                    Status: <?= ucfirst($filters['status']) ?>
                    <a href="?search=<?= urlencode($filters['search']) ?>&method=<?= urlencode($filters['method']) ?>" class="text-white ms-1" style="text-decoration:none;">&times;</a>
                </span>
            <?php endif; ?>
            <?php if (!empty($filters['method'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">
                    Method: <?= ucfirst($filters['method']) ?>
                    <a href="?search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>" class="text-white ms-1" style="text-decoration:none;">&times;</a>
                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Status Quick Filter Pills -->
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="<?= BASE_URL ?>/admin/payments" class="status-pill <?= empty($filters['status']) && empty($filters['method']) ? 'active' : '' ?>">
        <i class="bi bi-grid-3x3-gap me-1"></i> All <span class="badge bg-light text-dark ms-1"><?= number_format($stats->total ?? 0) ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/payments?status=completed" class="status-pill <?= ($filters['status'] ?? '') === 'completed' ? 'active' : '' ?>" style="--pill-color: #198754;">
        <i class="bi bi-check-circle me-1"></i> Completed <span class="badge bg-light text-dark ms-1"><?= $stats->completed ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/payments?status=pending" class="status-pill <?= ($filters['status'] ?? '') === 'pending' ? 'active' : '' ?>" style="--pill-color: #ffc107;">
        <i class="bi bi-clock me-1"></i> Pending <span class="badge bg-light text-dark ms-1"><?= $stats->pending ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/payments?status=failed" class="status-pill <?= ($filters['status'] ?? '') === 'failed' ? 'active' : '' ?>" style="--pill-color: #dc3545;">
        <i class="bi bi-x-circle me-1"></i> Failed <span class="badge bg-light text-dark ms-1"><?= $stats->failed ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/payments?status=refunded" class="status-pill <?= ($filters['status'] ?? '') === 'refunded' ? 'active' : '' ?>" style="--pill-color: #0dcaf0;">
        <i class="bi bi-arrow-return-left me-1"></i> Refunded <span class="badge bg-light text-dark ms-1"><?= $stats->refunded ?? 0 ?></span>
    </a>
</div>

<!-- Payments Table -->
<div class="admin-card">
    <!-- Table Header Bar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">
                Showing <strong><?= $pagination->from ?></strong>–<strong><?= $pagination->to ?></strong> of <strong><?= number_format($pagination->total) ?></strong> payments
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small me-1">Per page:</label>
            <select class="form-select form-select-sm" style="width: auto;" onchange="window.location=this.value">
                <?php foreach ([10, 25, 50, 100] as $pp): ?>
                <option value="?page=1&per_page=<?= $pp ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&method=<?= urlencode($filters['method']) ?>" <?= ($pagination->perPage ?? 25) == $pp ? 'selected' : '' ?>><?= $pp ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-admin align-middle" id="paymentsTable">
            <thead>
                <tr>
                    <th style="width: 170px;">Transaction ID</th>
                    <th>Customer</th>
                    <th>Invoice</th>
                    <th>Method</th>
                    <th class="text-end">Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Processed By</th>
                    <th class="text-center" style="width: 80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payments)): ?>
                    <?php foreach ($payments as $p): ?>
                    <?php
                    $rowClass = '';
                    if ($p->status === 'completed') $rowClass = 'row-completed';
                    elseif ($p->status === 'failed') $rowClass = 'row-failed';
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td>
                            <a href="<?= BASE_URL ?>/admin/payments/<?= $p->id ?>" class="text-decoration-none">
                                <div class="fw-bold" style="color: var(--admin-primary, #1a237e); font-size: 0.85rem;">
                                    <?= htmlspecialchars($p->transaction_id ?? '-') ?>
                                </div>
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:34px;height:34px;background:linear-gradient(135deg,<?= $p->status === 'completed' ? '#198754,#146c43' : '#0d6efd,#0a58ca' ?>);color:#fff;font-weight:600;font-size:0.72rem;">
                                    <?php
                                    $nameParts = explode(' ', trim($p->customer_name ?? '-'));
                                    $initials = '';
                                    foreach (array_slice($nameParts, 0, 2) as $part) {
                                        $initials .= strtoupper(substr($part, 0, 1));
                                    }
                                    echo $initials ?: '-';
                                    ?>
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size: 0.88rem;"><?= htmlspecialchars($p->customer_name ?? '-') ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($p->invoice_number): ?>
                                <a href="<?= BASE_URL ?>/admin/invoices/<?= $p->invoice_id ?? '' ?>" class="text-decoration-none">
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-receipt me-1"></i>
                                        <?= htmlspecialchars($p->invoice_number) ?>
                                    </span>
                                </a>
                            <?php else: ?>
                                <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $methodIcons = [
                                'cash' => 'bi-cash-stack',
                                'bank' => 'bi-bank',
                                'stripe' => 'bi-credit-card-2-front',
                                'paypal' => 'bi-paypal',
                                'flutterwave' => 'bi-globe',
                                'paystack' => 'bi-shield-check',
                                'mpesa' => 'bi-phone',
                            ];
                            $methodIcon = $methodIcons[$p->payment_method] ?? 'bi-credit-card';
                            ?>
                            <span class="badge bg-light text-dark border">
                                <i class="bi <?= $methodIcon ?> me-1"></i>
                                <?= ucwords(str_replace('_', ' ', htmlspecialchars($p->payment_method))) ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold" style="font-size: 0.95rem;"><?= format_currency($p->amount, $p->currency) ?></span>
                        </td>
                        <td>
                            <?php
                            $statusConfig = [
                                'completed' => ['color' => '#198754', 'bg' => '#19875415', 'border' => '#19875430', 'icon' => 'bi-check-circle-fill'],
                                'pending' => ['color' => '#ffc107', 'bg' => '#ffc10715', 'border' => '#ffc10730', 'icon' => 'bi-clock-fill'],
                                'failed' => ['color' => '#dc3545', 'bg' => '#dc354515', 'border' => '#dc354530', 'icon' => 'bi-x-circle-fill'],
                                'refunded' => ['color' => '#0dcaf0', 'bg' => '#0dcaf015', 'border' => '#0dcaf030', 'icon' => 'bi-arrow-return-left'],
                            ];
                            $cfg = $statusConfig[$p->status] ?? ['color' => '#6c757d', 'bg' => '#6c757d15', 'border' => '#6c757d30', 'icon' => 'bi-circle'];
                            ?>
                            <span class="badge rounded-pill" style="background: <?= $cfg['bg'] ?>; color: <?= $cfg['color'] ?>; border: 1px solid <?= $cfg['border'] ?>; font-weight: 600;">
                                <i class="bi <?= $cfg['icon'] ?> me-1"></i>
                                <?= ucfirst($p->status) ?>
                            </span>
                        </td>
                        <td>
                            <div>
                                <small class="fw-semibold"><?= format_date($p->created_at, 'M d, Y') ?></small>
                                <div><small class="text-muted"><?= format_date($p->created_at, 'H:i') ?></small></div>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted"><?= htmlspecialchars($p->processed_by_name ?? '-') ?></small>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= BASE_URL ?>/admin/payments/<?= $p->id ?>" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-payment-btn" title="Delete" data-id="<?= $p->id ?>" data-txn="<?= htmlspecialchars($p->transaction_id ?? '') ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-credit-card" style="font-size:2.5rem;opacity:0.3;"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No payments found</h6>
                                <p class="mb-3" style="max-width:400px;margin:0 auto;">
                                    <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['method'])): ?>
                                        No results match your current filters. Try adjusting your search criteria.
                                    <?php else: ?>
                                        No payments have been recorded yet.
                                    <?php endif; ?>
                                </p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['method'])): ?>
                                    <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Filters
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>/admin/payments/create" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-lg me-1"></i> Record Payment
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
                    <a class="page-link" href="?page=1&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&method=<?= urlencode($filters['method']) ?>">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&method=<?= urlencode($filters['method']) ?>">
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
                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&method=<?= urlencode($filters['method']) ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <?php if ($end < $pagination->totalPages): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&method=<?= urlencode($filters['method']) ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->totalPages ?>&search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>&method=<?= urlencode($filters['method']) ?>">
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
    .table-admin tbody tr.row-completed { background: #19875406; }
    .table-admin tbody tr.row-completed:hover { background: #1987540d; }
    .table-admin tbody tr.row-failed { background: #dc354506; }
    .table-admin tbody tr.row-failed:hover { background: #dc35450d; }

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
document.querySelectorAll('.delete-payment-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var txn = this.getAttribute('data-txn');
        Swal.fire({
            title: 'Delete Payment?',
            text: 'Delete transaction "' + txn + '"? This will recalculate the linked invoice status.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.isConfirmed) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= BASE_URL ?>/admin/payments/' + id;
                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta) {
                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_csrf_token';
                    csrfInput.value = csrfMeta.getAttribute('content');
                    form.appendChild(csrfInput);
                }
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
</script>
