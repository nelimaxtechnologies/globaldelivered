<!-- ============================================================
     REVENUE REPORT - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow me-2"></i>Revenue Report</h4>
        <small class="text-muted">Financial overview for the selected period</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> All Reports
    </a>
</div>

<!-- Date Filter + Export -->
<div class="admin-card mb-4">
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap" id="dateFilterForm">
            <div>
                <label class="form-label small fw-semibold mb-0">From</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="<?= $startDate ?>">
            </div>
            <div>
                <label class="form-label small fw-semibold mb-0">To</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="<?= $endDate ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm admin-btn mt-1">
                <i class="bi bi-search me-1"></i> Update
            </button>
        </form>
        <div class="d-flex gap-1 mt-1">
            <a href="?start_date=<?= date('Y-m-d') ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">Today</a>
            <a href="?start_date=<?= date('Y-m-d', strtotime('-7 days')) ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">7 Days</a>
            <a href="?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">This Month</a>
            <a href="?start_date=<?= date('Y-m-01', strtotime('-1 month')) ?>&end_date=<?= date('Y-m-t', strtotime('-1 month')) ?>" class="btn btn-outline-secondary btn-sm">Last Month</a>
        </div>
        <div class="ms-auto d-flex gap-1">
            <form method="POST" action="<?= BASE_URL ?>/admin/reports/export" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="type" value="revenue">
                <input type="hidden" name="start_date" value="<?= $startDate ?>">
                <input type="hidden" name="end_date" value="<?= $endDate ?>">
                <button type="submit" name="format" value="csv" class="btn btn-outline-success btn-sm"><i class="bi bi-filetype-csv me-1"></i> CSV</button>
                <button type="submit" name="format" value="xls" class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel me-1"></i> Excel</button>
            </form>
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($totals->total) ?></div>
                    <div class="card-label">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($totals->count) ?></div>
                    <div class="card-label">Transactions</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($totals->cash_total) ?></div>
                    <div class="card-label">Cash Payments</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #fd7e14, #e0a800);">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($totals->avg_payment) ?></div>
                    <div class="card-label">Avg Payment</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart + Method Breakdown -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Daily Revenue</h6>
            <?php if (!empty($revenueData)): ?>
            <canvas id="revenueChart" height="120"></canvas>
            <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bar-chart fs-1 d-block mb-2" style="opacity:0.3;"></i>
                <p>No revenue data for this period</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i>By Payment Method</h6>
            <?php if (!empty($byMethod)): ?>
                <?php
                $methodColors = [
                    'cash' => '#198754', 'bank' => '#0d6efd', 'stripe' => '#635bff',
                    'paypal' => '#003087', 'flutterwave' => '#f5a623', 'paystack' => '#2a8a6e', 'mpesa' => '#4caf50'
                ];
                ?>
                <?php foreach ($byMethod as $m):
                    $pct = $totals->total > 0 ? round(($m->total / $totals->total) * 100, 1) : 0;
                    $color = $methodColors[$m->payment_method] ?? '#6c757d';
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="fw-semibold"><?= ucwords(str_replace('_', ' ', $m->payment_method)) ?></small>
                        <small class="fw-bold"><?= format_currency($m->total) ?> <span class="text-muted">(<?= $pct ?>%)</span></small>
                    </div>
                    <div class="progress" style="height:6px;border-radius:3px;">
                        <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $color ?>;border-radius:3px;"></div>
                    </div>
                    <small class="text-muted"><?= $m->count ?> transaction<?= $m->count != 1 ? 's' : '' ?></small>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <p class="mb-0">No data</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Revenue Table -->
<div class="admin-card">
    <h6 class="fw-bold mb-3"><i class="bi bi-table me-2"></i>Daily Breakdown</h6>
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Cash</th>
                    <th class="text-end">Bank</th>
                    <th class="text-end">Online</th>
                    <th class="text-end">Transactions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($revenueData)): ?>
                    <?php foreach ($revenueData as $r): ?>
                    <tr>
                        <td class="fw-semibold"><?= format_date($r->date, 'D, M d, Y') ?></td>
                        <td class="text-end fw-bold" style="color:#198754;"><?= format_currency($r->total) ?></td>
                        <td class="text-end"><?= format_currency($r->cash) ?></td>
                        <td class="text-end"><?= format_currency($r->bank) ?></td>
                        <td class="text-end"><?= format_currency($r->online) ?></td>
                        <td class="text-end"><?= $r->count ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="table-active">
                        <td class="fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= format_currency($totals->total) ?></td>
                        <td class="text-end fw-bold"><?= format_currency($totals->cash_total) ?></td>
                        <td class="text-end fw-bold"><?= format_currency($totals->bank_total) ?></td>
                        <td class="text-end fw-bold"><?= format_currency($totals->stripe_total + $totals->paypal_total + $totals->flutterwave_total + $totals->paystack_total + $totals->mpesa_total) ?></td>
                        <td class="text-end fw-bold"><?= $totals->count ?></td>
                    </tr>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2" style="opacity:0.3;"></i>
                        No revenue data for the selected period
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

<?php if (!empty($revenueData)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var data = <?= json_encode($revenueData) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(function(d) { return d.date; }),
            datasets: [
                {
                    label: 'Cash',
                    data: data.map(function(d) { return parseFloat(d.cash); }),
                    backgroundColor: '#198754',
                    borderRadius: 4
                },
                {
                    label: 'Bank',
                    data: data.map(function(d) { return parseFloat(d.bank); }),
                    backgroundColor: '#0d6efd',
                    borderRadius: 4
                },
                {
                    label: 'Online',
                    data: data.map(function(d) { return parseFloat(d.online); }),
                    backgroundColor: '#6f42c1',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(ctx) { return ctx.dataset.label + ': $' + ctx.parsed.y.toLocaleString(); }
                    }
                }
            },
            scales: {
                x: { stacked: true },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: { callback: function(val) { return '$' + val.toLocaleString(); } }
                }
            }
        }
    });
});
</script>
<?php endif; ?>
