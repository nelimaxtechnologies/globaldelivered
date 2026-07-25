<!-- ============================================================
     REPORTS & ANALYTICS DASHBOARD - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Reports & Analytics</h4>
        <small class="text-muted">Business intelligence and performance insights</small>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary admin-btn" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print
        </button>
    </div>
</div>

<!-- Key Metrics -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($totalRevenue) ?></div>
                    <div class="card-label">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($totalShipments) ?></div>
                    <div class="card-label">Total Shipments</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($totalCustomers) ?></div>
                    <div class="card-label">Active Customers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($totalDrivers) ?></div>
                    <div class="card-label">Total Drivers</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- This Month + Performance -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="fw-bold fs-4 text-success"><?= format_currency($monthRevenue) ?></div>
            <small class="text-muted">This Month Revenue</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="fw-bold fs-4 text-primary"><?= number_format($monthShipments) ?></div>
            <small class="text-muted">This Month Shipments</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="fw-bold fs-4 text-success"><?= $deliveredToday ?></div>
            <small class="text-muted">Delivered Today</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="fw-bold fs-4 text-info"><?= $avgDeliveryTime ?>h</div>
            <small class="text-muted">Avg Delivery Time</small>
        </div>
    </div>
</div>

<!-- Quick Alerts -->
<div class="row g-3 mb-4">
    <?php if ($pendingInvoices > 0): ?>
    <div class="col-md-6">
        <a href="<?= BASE_URL ?>/admin/invoices?status=sent" class="text-decoration-none">
            <div class="admin-card d-flex align-items-center gap-3" style="border-left: 4px solid #ffc107;">
                <div style="width:44px;height:44px;border-radius:10px;background:#ffc10720;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-receipt text-warning fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold"><?= $pendingInvoices ?> Pending Invoice<?= $pendingInvoices > 1 ? 's' : '' ?></div>
                    <small class="text-muted">Require attention</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        </a>
    </div>
    <?php endif; ?>
    <?php if ($delayedCount > 0): ?>
    <div class="col-md-6">
        <a href="<?= BASE_URL ?>/admin/reports/delayed" class="text-decoration-none">
            <div class="admin-card d-flex align-items-center gap-3" style="border-left: 4px solid #dc3545;">
                <div style="width:44px;height:44px;border-radius:10px;background:#dc354520;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-exclamation-triangle text-danger fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold"><?= $delayedCount ?> Delayed Shipment<?= $delayedCount > 1 ? 's' : '' ?></div>
                    <small class="text-muted">Past expected delivery</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Revenue Trend Chart -->
<?php if (!empty($revenueTrend)): ?>
<div class="admin-card mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-2"></i>Revenue Trend (Last 7 Days)</h6>
    <canvas id="revenueTrendChart" height="80"></canvas>
</div>
<?php endif; ?>

<!-- Report Cards -->
<h5 class="fw-bold mb-3">Available Reports</h5>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <a href="<?= BASE_URL ?>/admin/reports/revenue?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="text-decoration-none">
            <div class="admin-card report-card h-100">
                <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#198754,#20c997);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h6 class="fw-bold text-center">Revenue Report</h6>
                <p class="text-muted small text-center mb-0">Daily revenue, payment methods, totals, and averages</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="<?= BASE_URL ?>/admin/reports/shipments?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="text-decoration-none">
            <div class="admin-card report-card h-100">
                <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#0d6efd,#6610f2);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h6 class="fw-bold text-center">Shipments Report</h6>
                <p class="text-muted small text-center mb-0">Status breakdown, service types, delivery performance</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="<?= BASE_URL ?>/admin/reports/customers?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="text-decoration-none">
            <div class="admin-card report-card h-100">
                <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#6f42c1,#d63384);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;">
                    <i class="bi bi-people"></i>
                </div>
                <h6 class="fw-bold text-center">Customers Report</h6>
                <p class="text-muted small text-center mb-0">Top customers, spending, activity, and retention</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="<?= BASE_URL ?>/admin/reports/drivers?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="text-decoration-none">
            <div class="admin-card report-card h-100">
                <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#fd7e14,#ffc107);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h6 class="fw-bold text-center">Drivers Performance</h6>
                <p class="text-muted small text-center mb-0">Delivery stats, revenue per driver, efficiency</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="<?= BASE_URL ?>/admin/reports/delayed" class="text-decoration-none">
            <div class="admin-card report-card h-100">
                <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#dc3545,#e74c3c);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h6 class="fw-bold text-center">Delayed Packages</h6>
                <p class="text-muted small text-center mb-0">Overdue shipments requiring immediate attention</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <div class="admin-card report-card h-100">
            <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#212529,#495057);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;">
                <i class="bi bi-download"></i>
            </div>
            <h6 class="fw-bold text-center">Export Data</h6>
            <p class="text-muted small text-center mb-3">Download reports as CSV or Excel</p>
            <form method="POST" action="<?= BASE_URL ?>/admin/reports/export" class="d-flex gap-2 justify-content-center">
                <?= csrf_field() ?>
                <select name="type" class="form-select form-select-sm" style="width:auto;">
                    <option value="revenue">Revenue</option>
                    <option value="shipments">Shipments</option>
                    <option value="customers">Customers</option>
                    <option value="drivers">Drivers</option>
                    <option value="delayed">Delayed</option>
                </select>
                <select name="format" class="form-select form-select-sm" style="width:auto;">
                    <option value="csv">CSV</option>
                    <option value="xls">Excel</option>
                </select>
                <input type="hidden" name="start_date" value="<?= date('Y-m-01') ?>">
                <input type="hidden" name="end_date" value="<?= date('Y-m-d') ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></button>
            </form>
        </div>
    </div>
</div>

<style>
    .report-card { transition: all 0.3s; cursor: pointer; }
    .report-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
</style>

<?php if (!empty($revenueTrend)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('revenueTrendChart').getContext('2d');
    var data = <?= json_encode($revenueTrend) ?>;
    var labels = data.map(function(d) { return d.date; });
    var values = data.map(function(d) { return parseFloat(d.total); });

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: values,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#198754',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) { return '$' + ctx.parsed.y.toLocaleString(); }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) { return '$' + val.toLocaleString(); }
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>
