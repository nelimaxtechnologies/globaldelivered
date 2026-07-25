<!-- ============================================================
     CUSTOMERS REPORT - PREMIUM MODERN DESIGN
     ============================================================ -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-people me-2"></i>Customers Report</h4>
        <small class="text-muted">Customer activity and spending analysis</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> All Reports
    </a>
</div>

<!-- Date Filter -->
<div class="admin-card mb-4">
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
            <div>
                <label class="form-label small fw-semibold mb-0">From</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="<?= $startDate ?>">
            </div>
            <div>
                <label class="form-label small fw-semibold mb-0">To</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="<?= $endDate ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm admin-btn mt-1"><i class="bi bi-search me-1"></i> Update</button>
        </form>
        <div class="d-flex gap-1 mt-1">
            <a href="?start_date=<?= date('Y-m-d', strtotime('-30 days')) ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">Last 30 Days</a>
            <a href="?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">This Month</a>
            <a href="?start_date=<?= date('Y-m-01', strtotime('-3 months')) ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">Last 3 Months</a>
        </div>
        <div class="ms-auto">
            <form method="POST" action="<?= BASE_URL ?>/admin/reports/export" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="type" value="customers">
                <input type="hidden" name="start_date" value="<?= $startDate ?>">
                <input type="hidden" name="end_date" value="<?= $endDate ?>">
                <button type="submit" name="format" value="csv" class="btn btn-outline-success btn-sm"><i class="bi bi-filetype-csv me-1"></i> CSV</button>
            </form>
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6f42c1, #d63384);">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($totals->total_customers ?? 0) ?></div>
                    <div class="card-label">Active Customers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($totals->total_shipments ?? 0) ?></div>
                    <div class="card-label">Total Shipments</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($totals->total_revenue ?? 0) ?></div>
                    <div class="card-label">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Customer Highlight -->
<?php if ($topCustomer): ?>
<div class="admin-card mb-4" style="border-left: 4px solid #ffc107;">
    <div class="d-flex align-items-center gap-3">
        <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#ffc107,#e0a800);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;">
            <i class="bi bi-trophy"></i>
        </div>
        <div class="flex-grow-1">
            <small class="text-muted">Top Customer</small>
            <div class="fw-bold fs-5"><?= htmlspecialchars($topCustomer->first_name . ' ' . $topCustomer->last_name) ?></div>
        </div>
        <div class="text-end">
            <div class="fw-bold fs-5 text-success"><?= format_currency($topCustomer->total_spent) ?></div>
            <small class="text-muted"><?= $topCustomer->total_shipments ?> shipments</small>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Customer Table -->
<div class="admin-card">
    <h6 class="fw-bold mb-3"><i class="bi bi-table me-2"></i>Customer Rankings</h6>
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th class="text-end">Shipments</th>
                    <th class="text-end">Total Spent</th>
                    <th>Last Activity</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $i => $c): ?>
                    <tr>
                        <td>
                            <?php if ($i < 3): ?>
                            <span class="badge" style="background:<?= ['#ffc107','#c0c0c0','#cd7f32'][$i] ?>;color:#fff;font-weight:700;"><?= $i + 1 ?></span>
                            <?php else: ?>
                            <small class="text-muted"><?= $i + 1 ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:linear-gradient(135deg,#6f42c1,#d63384);color:#fff;font-weight:600;font-size:0.72rem;">
                                    <?= strtoupper(substr($c->first_name,0,1) . substr($c->last_name,0,1)) ?>
                                </div>
                                <a href="<?= BASE_URL ?>/admin/customers/<?= $c->id ?>" class="fw-semibold text-decoration-none"><?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?></a>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted"><?= htmlspecialchars($c->email) ?></small>
                        </td>
                        <td class="text-end fw-semibold"><?= $c->total_shipments ?></td>
                        <td class="text-end fw-bold" style="color:#198754;"><?= format_currency($c->total_spent) ?></td>
                        <td><small class="text-muted"><?= $c->last_shipment_date ? format_date($c->last_shipment_date, 'M d, Y') : '-' ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2" style="opacity:0.3;"></i>
                        No customer data for selected period
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-admin thead th { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d; border-bottom: 2px solid #e9ecef; padding: 12px; }
    .table-admin tbody td { padding: 12px; border-bottom: 1px solid #f1f3f5; }
    .table-admin tbody tr:hover { background: #f8f9fa; }
</style>
