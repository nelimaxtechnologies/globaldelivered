<!-- ============================================================
     SHIPMENTS REPORT - PREMIUM MODERN DESIGN
     ============================================================ -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-box-seam me-2"></i>Shipments Report</h4>
        <small class="text-muted">Shipment performance and service breakdown</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> All Reports
    </a>
</div>

<!-- Date Filter + Export -->
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
            <a href="?start_date=<?= date('Y-m-d') ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">Today</a>
            <a href="?start_date=<?= date('Y-m-d', strtotime('-7 days')) ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">7 Days</a>
            <a href="?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">This Month</a>
            <a href="?start_date=<?= date('Y-m-01', strtotime('-1 month')) ?>&end_date=<?= date('Y-m-t', strtotime('-1 month')) ?>" class="btn btn-outline-secondary btn-sm">Last Month</a>
        </div>
        <div class="ms-auto">
            <form method="POST" action="<?= BASE_URL ?>/admin/reports/export" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="type" value="shipments">
                <input type="hidden" name="start_date" value="<?= $startDate ?>">
                <input type="hidden" name="end_date" value="<?= $endDate ?>">
                <button type="submit" name="format" value="csv" class="btn btn-outline-success btn-sm"><i class="bi bi-filetype-csv me-1"></i> CSV</button>
            </form>
        </div>
    </div>
</div>

<!-- Period Summary -->
<?php if ($periodTotals): ?>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="admin-card text-center py-3">
            <div class="fw-bold fs-4"><?= number_format($periodTotals->total) ?></div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center py-3" style="border-top:3px solid #ffc107;">
            <div class="fw-bold fs-4 text-warning"><?= $periodTotals->pending ?? 0 ?></div>
            <small class="text-muted">Pending</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center py-3" style="border-top:3px solid #0d6efd;">
            <div class="fw-bold fs-4 text-primary"><?= $periodTotals->in_transit ?? 0 ?></div>
            <small class="text-muted">In Transit</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center py-3" style="border-top:3px solid #198754;">
            <div class="fw-bold fs-4 text-success"><?= $periodTotals->delivered ?? 0 ?></div>
            <small class="text-muted">Delivered</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center py-3" style="border-top:3px solid #dc3545;">
            <div class="fw-bold fs-4 text-danger"><?= $periodTotals->cancelled ?? 0 ?></div>
            <small class="text-muted">Cancelled</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center py-3" style="border-top:3px solid #198754;">
            <div class="fw-bold fs-4 text-success"><?= format_currency($periodTotals->revenue) ?></div>
            <small class="text-muted">Revenue</small>
        </div>
    </div>
</div>

<!-- Delivery Rate -->
<?php
$totalP = (int)($periodTotals->total ?? 0);
$deliveredP = (int)($periodTotals->delivered ?? 0);
$deliveryRate = $totalP > 0 ? round(($deliveredP / $totalP) * 100, 1) : 0;
?>
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2"></i>Delivery Rate</h6>
        <span class="fw-bold fs-5" style="color:<?= $deliveryRate >= 90 ? '#198754' : ($deliveryRate >= 70 ? '#ffc107' : '#dc3545') ?>"><?= $deliveryRate ?>%</span>
    </div>
    <div class="progress" style="height:10px;border-radius:5px;">
        <div class="progress-bar" style="width:<?= $deliveryRate ?>%;background:linear-gradient(90deg,<?= $deliveryRate >= 90 ? '#198754' : ($deliveryRate >= 70 ? '#ffc107' : '#dc3545') ?>,<?= $deliveryRate >= 90 ? '#20c997' : ($deliveryRate >= 70 ? '#ffcd38' : '#ff6b6b') ?>);border-radius:5px;"></div>
    </div>
    <div class="d-flex justify-content-between mt-2">
        <small class="text-muted">Delivered: <?= $deliveredP ?> of <?= $totalP ?></small>
        <small class="text-muted">Avg time: <?= $periodTotals->avg_hours ?? '-' ?>h</small>
    </div>
</div>
<?php endif; ?>

<!-- Chart + Service Breakdown -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Daily Shipments</h6>
            <?php if (!empty($stats)): ?>
            <canvas id="shipmentsChart" height="120"></canvas>
            <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2" style="opacity:0.3;"></i>
                <p>No shipment data for this period</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i>By Service Type</h6>
            <?php if (!empty($byService)): ?>
                <?php
                $svcColors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997'];
                $totalCount = array_sum(array_column($byService, 'count'));
                ?>
                <?php foreach ($byService as $i => $svc):
                    $color = $svcColors[$i % count($svcColors)];
                    $pct = $totalCount > 0 ? round(($svc->count / $totalCount) * 100, 1) : 0;
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="fw-semibold"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;color:<?= $color ?>;"></i><?= ucwords(str_replace('_', ' ', $svc->service_type)) ?></small>
                        <small class="fw-bold"><?= $svc->count ?> <span class="text-muted">(<?= $pct ?>%)</span></small>
                    </div>
                    <div class="progress" style="height:6px;border-radius:3px;">
                        <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $color ?>;border-radius:3px;"></div>
                    </div>
                    <small class="text-muted"><?= format_currency($svc->revenue) ?></small>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted"><p class="mb-0">No data</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Daily Table -->
<div class="admin-card">
    <h6 class="fw-bold mb-3"><i class="bi bi-table me-2"></i>Daily Breakdown</h6>
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr><th>Date</th><th class="text-end">Total</th><th class="text-end">Pending</th><th class="text-end">Active</th><th class="text-end">Delivered</th><th class="text-end">Returned</th><th class="text-end">Cancelled</th></tr>
            </thead>
            <tbody>
                <?php if (!empty($stats)): ?>
                    <?php foreach ($stats as $s): ?>
                    <tr>
                        <td class="fw-semibold"><?= format_date($s->date, 'D, M d, Y') ?></td>
                        <td class="text-end fw-bold"><?= $s->total ?></td>
                        <td class="text-end"><?= $s->pending ?></td>
                        <td class="text-end text-primary"><?= $s->active ?></td>
                        <td class="text-end text-success"><?= $s->delivered ?></td>
                        <td class="text-end text-warning"><?= $s->returned ?></td>
                        <td class="text-end text-danger"><?= $s->cancelled ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2" style="opacity:0.3;"></i>No data for selected period
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

<?php if (!empty($stats)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('shipmentsChart').getContext('2d');
    var data = <?= json_encode($stats) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(function(d) { return d.date; }),
            datasets: [
                { label: 'Pending', data: data.map(function(d) { return parseInt(d.pending); }), backgroundColor: '#ffc107', borderRadius: 4 },
                { label: 'Active', data: data.map(function(d) { return parseInt(d.active); }), backgroundColor: '#0d6efd', borderRadius: 4 },
                { label: 'Delivered', data: data.map(function(d) { return parseInt(d.delivered); }), backgroundColor: '#198754', borderRadius: 4 },
                { label: 'Cancelled', data: data.map(function(d) { return parseInt(d.cancelled); }), backgroundColor: '#dc3545', borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
        }
    });
});
</script>
<?php endif; ?>
