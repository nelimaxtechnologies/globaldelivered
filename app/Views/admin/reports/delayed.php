<!-- ============================================================
     DELAYED PACKAGES - PREMIUM MODERN DESIGN
     ============================================================ -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Delayed Packages</h4>
        <small class="text-muted">Shipments past their expected delivery date</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> All Reports
    </a>
</div>

<!-- Alert Banner -->
<?php if ($delayedCount > 0): ?>
<div class="alert alert-danger d-flex align-items-center mb-4" role="alert" style="border-radius:12px;border-left:4px solid #dc3545;">
    <div class="me-3">
        <div style="width:40px;height:40px;border-radius:10px;background:#dc354520;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        </div>
    </div>
    <div>
        <strong><?= $delayedCount ?> shipment<?= $delayedCount > 1 ? 's' : '' ?> delayed</strong> — total value at risk: <?= format_currency($totalValue) ?>
        <div class="mt-1">
            <?php if ($criticalCount > 0): ?>
            <span class="badge bg-danger me-1"><?= $criticalCount ?> critical (7+ days)</span>
            <?php endif; ?>
            <?php if ($warningCount > 0): ?>
            <span class="badge bg-warning text-dark"><?= $warningCount ?> warning (3-7 days)</span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #dc3545;">
            <div class="fw-bold fs-4 text-danger"><?= $delayedCount ?></div>
            <small class="text-muted">Total Delayed</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #dc3545;">
            <div class="fw-bold fs-4 text-danger"><?= $criticalCount ?></div>
            <small class="text-muted">Critical (7+ days)</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #ffc107;">
            <div class="fw-bold fs-4 text-warning"><?= $warningCount ?></div>
            <small class="text-muted">Warning (3-7 days)</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #0d6efd;">
            <div class="fw-bold fs-4"><?= format_currency($totalValue) ?></div>
            <small class="text-muted">Value at Risk</small>
        </div>
    </div>
</div>

<!-- Delayed Packages Table -->
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-table me-2"></i>Delayed Shipments</h6>
        <?php if ($delayedCount > 0): ?>
        <button class="btn btn-outline-danger btn-sm" onclick="exportDelayed()">
            <i class="bi bi-download me-1"></i> Export
        </button>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-admin align-middle" id="delayedTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Tracking</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th>Expected Delivery</th>
                    <th class="text-center">Days Overdue</th>
                    <th class="text-center">Severity</th>
                    <th class="text-center" style="width:80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($delayed)): ?>
                    <?php foreach ($delayed as $i => $s):
                        $daysOverdue = floor((time() - strtotime($s->expected_delivery_date)) / 86400);
                        $severity = $daysOverdue > 7 ? 'critical' : ($daysOverdue > 3 ? 'warning' : 'normal');
                    ?>
                    <tr class="<?= $severity === 'critical' ? 'row-critical' : ($severity === 'warning' ? 'row-warning' : '') ?>">
                        <td><?= $i + 1 ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="text-decoration-none">
                                <div class="fw-bold" style="color:var(--admin-primary,#1a237e);font-size:0.88rem;">
                                    <?= htmlspecialchars($s->tracking_number) ?>
                                </div>
                            </a>
                        </td>
                        <td>
                            <?php if ($s->driver_name): ?>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;background:linear-gradient(135deg,#fd7e14,#ffc107);color:#fff;font-weight:600;font-size:0.6rem;">
                                    <?= strtoupper(substr($s->driver_name,0,2)) ?>
                                </div>
                                <small class="fw-semibold"><?= htmlspecialchars($s->driver_name) ?></small>
                            </div>
                            <?php else: ?>
                            <span class="badge bg-secondary">Unassigned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background:<?= $s->status_color ?? '#6c757d' ?>15;color:<?= $s->status_color ?? '#6c757d' ?>;border:1px solid <?= $s->status_color ?? '#6c757d' ?>30;font-weight:600;">
                                <?= htmlspecialchars($s->status_name ?? ucfirst(str_replace('_', ' ', $s->status))) ?>
                            </span>
                        </td>
                        <td><small class="text-muted"><?= format_date($s->expected_delivery_date) ?></small></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $severity === 'critical' ? 'danger' : ($severity === 'warning' ? 'warning text-dark' : 'secondary') ?> fs-6">
                                <?= $daysOverdue ?> day<?= $daysOverdue != 1 ? 's' : '' ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if ($severity === 'critical'): ?>
                            <span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i>Critical</span>
                            <?php elseif ($severity === 'warning'): ?>
                            <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i>Warning</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Minor</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <div style="width:80px;height:80px;border-radius:20px;background:#19875415;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-check-circle" style="font-size:2.5rem;color:#198754;"></i>
                                </div>
                                <h6 class="fw-bold mb-1 text-success">No delayed packages!</h6>
                                <p>All shipments are on track.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-admin thead th { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d; border-bottom: 2px solid #e9ecef; padding: 12px; }
    .table-admin tbody td { padding: 12px; border-bottom: 1px solid #f1f3f5; }
    .table-admin tbody tr { transition: all 0.15s; }
    .table-admin tbody tr:hover { background: #f8f9fa; }
    .table-admin tbody tr.row-critical { background: #dc354506; }
    .table-admin tbody tr.row-critical:hover { background: #dc35450d; }
    .table-admin tbody tr.row-warning { background: #ffc10706; }
    .table-admin tbody tr.row-warning:hover { background: #ffc1070d; }
</style>

<script>
function exportDelayed() {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= BASE_URL ?>/admin/reports/export';

    var fields = { type: 'delayed', format: 'csv', start_date: '', end_date: '' };
    for (var key in fields) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = fields[key];
        form.appendChild(input);
    }

    // Add CSRF token
    var csrf = document.querySelector('meta[name="csrf-token"]');
    if (csrf) {
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrf.getAttribute('content');
        form.appendChild(csrfInput);
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
