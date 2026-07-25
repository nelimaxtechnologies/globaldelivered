<!-- ============================================================
     DRIVERS PERFORMANCE REPORT - PREMIUM MODERN DESIGN
     ============================================================ -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-person-badge me-2"></i>Drivers Performance</h4>
        <small class="text-muted">Driver efficiency and delivery statistics</small>
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
            <a href="?start_date=<?= date('Y-m-d', strtotime('-7 days')) ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">Last 7 Days</a>
            <a href="?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">This Month</a>
            <a href="?start_date=<?= date('Y-m-01', strtotime('-3 months')) ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">Last 3 Months</a>
        </div>
    </div>
</div>

<!-- Period Summary -->
<?php if ($periodTotals): ?>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #6f42c1;">
            <div class="fw-bold fs-4"><?= $periodTotals->active_drivers ?? 0 ?></div>
            <small class="text-muted">Active Drivers</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #0d6efd;">
            <div class="fw-bold fs-4"><?= number_format($periodTotals->total_shipments ?? 0) ?></div>
            <small class="text-muted">Total Deliveries</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #198754;">
            <div class="fw-bold fs-4 text-success"><?= format_currency($periodTotals->total_revenue ?? 0) ?></div>
            <small class="text-muted">Total Revenue</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #fd7e14;">
            <div class="fw-bold fs-4 text-warning"><?= $periodTotals->avg_hours ?? '-' ?>h</div>
            <small class="text-muted">Avg Delivery Time</small>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Drivers Chart + Table -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Deliveries by Driver</h6>
            <?php if (!empty($drivers)): ?>
            <canvas id="driversChart" height="100"></canvas>
            <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-person-badge fs-1 d-block mb-2" style="opacity:0.3;"></i>
                <p>No driver data for this period</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-trophy me-2"></i>Top Performers</h6>
            <?php
            $topDrivers = array_slice($drivers, 0, 5);
            $maxDeliveries = !empty($topDrivers) ? max(array_column($topDrivers, 'total_deliveries')) : 1;
            ?>
            <?php if (!empty($topDrivers)): ?>
                <?php foreach ($topDrivers as $i => $d): ?>
                <div class="d-flex align-items-center gap-3 mb-3 <?= $i < count($topDrivers) - 1 ? 'pb-3 border-bottom' : '' ?>">
                    <div class="text-center" style="width:24px;">
                        <?php if ($i < 3): ?>
                        <span class="badge" style="background:<?= ['#ffc107','#c0c0c0','#cd7f32'][$i] ?>;color:#fff;"><?= $i + 1 ?></span>
                        <?php else: ?>
                        <small class="text-muted"><?= $i + 1 ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?></div>
                        <div class="progress mt-1" style="height:4px;border-radius:2px;">
                            <div class="progress-bar" style="width:<?= $maxDeliveries > 0 ? round(($d->total_deliveries / $maxDeliveries) * 100) : 0 ?>%;background:#0d6efd;border-radius:2px;"></div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold"><?= $d->total_deliveries ?></div>
                        <small class="text-muted"><?= format_currency($d->total_revenue) ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted"><p class="mb-0">No data</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Drivers Table -->
<div class="admin-card">
    <h6 class="fw-bold mb-3"><i class="bi bi-table me-2"></i>All Drivers</h6>
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th class="text-end">Deliveries</th>
                    <th class="text-end">Completed</th>
                    <th class="text-end">Returned</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Avg Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($drivers)): ?>
                    <?php foreach ($drivers as $d): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:linear-gradient(135deg,#fd7e14,#ffc107);color:#fff;font-weight:600;font-size:0.72rem;">
                                    <?= strtoupper(substr($d->first_name,0,1) . substr($d->last_name,0,1)) ?>
                                </div>
                                <a href="<?= BASE_URL ?>/admin/drivers/<?= $d->id ?>" class="fw-semibold text-decoration-none"><?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?></a>
                            </div>
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($d->phone) ?></small></td>
                        <td>
                            <?php
                            $stColor = $d->status === 'available' ? '#198754' : ($d->status === 'on_delivery' ? '#ffc107' : '#6c757d');
                            ?>
                            <span class="badge rounded-pill" style="background:<?= $stColor ?>15;color:<?= $stColor ?>;border:1px solid <?= $stColor ?>30;font-weight:600;">
                                <?= str_replace('_', ' ', ucfirst($d->status)) ?>
                            </span>
                        </td>
                        <td class="text-end fw-bold"><?= (int)$d->total_deliveries ?></td>
                        <td class="text-end text-success"><?= (int)($d->completed ?? 0) ?></td>
                        <td class="text-end text-warning"><?= (int)($d->returned ?? 0) ?></td>
                        <td class="text-end fw-bold"><?= format_currency($d->total_revenue) ?></td>
                        <td class="text-end">
                            <?php if ($d->avg_delivery_hours): ?>
                            <span class="badge bg-light text-dark"><?= $d->avg_delivery_hours ?>h</span>
                            <?php else: ?>
                            <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-person-badge fs-1 d-block mb-2" style="opacity:0.3;"></i>
                        No driver data for selected period
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

<?php if (!empty($drivers)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('driversChart').getContext('2d');
    var data = <?= json_encode(array_slice($drivers, 0, 10)) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(function(d) { return d.first_name + ' ' + d.last_name; }),
            datasets: [{
                label: 'Deliveries',
                data: data.map(function(d) { return parseInt(d.total_deliveries); }),
                backgroundColor: '#0d6efd',
                borderRadius: 6
            }, {
                label: 'Revenue',
                data: data.map(function(d) { return parseFloat(d.total_revenue); }),
                backgroundColor: '#198754',
                borderRadius: 6,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, position: 'left', title: { display: true, text: 'Deliveries' } },
                y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { callback: function(v) { return '$' + v.toLocaleString(); } }, title: { display: true, text: 'Revenue' } }
            }
        }
    });
});
</script>
<?php endif; ?>
