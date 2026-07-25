<!-- ============================================================
     AUDIT LOGS - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-list-check me-2"></i>Audit Logs</h4>
        <small class="text-muted">Track all system activity and user actions</small>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary admin-btn" onclick="exportAuditLogs()">
            <i class="bi bi-download me-1"></i> Export CSV
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-list-ul"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->total ?? 0) ?></div>
                    <div class="card-label">Total Logs</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->today ?? 0) ?></div>
                    <div class="card-label">Today</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->this_week ?? 0) ?></div>
                    <div class="card-label">This Week</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->unique_users ?? 0) ?></div>
                    <div class="card-label">Active Users</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-2"></i>Activity Trend (14 Days)</h6>
            <?php if (!empty($dailyTrend)): ?>
            <canvas id="activityChart" height="80"></canvas>
            <?php else: ?>
            <div class="text-center py-4 text-muted"><p class="mb-0">No activity data</p></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i>Top Actions</h6>
            <?php if (!empty($actionBreakdown)):
                $totalLogs = $stats->total ?? 1;
                $actionColors = ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#fd7e14','#20c997','#d63384','#0dcaf0','#6c757d'];
            ?>
                <?php foreach (array_slice($actionBreakdown, 0, 8) as $i => $ab):
                    $pct = $totalLogs > 0 ? round(($ab->count / $totalLogs) * 100, 1) : 0;
                    $color = $actionColors[$i % count($actionColors)];
                ?>
                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="fw-semibold"><i class="bi bi-circle-fill me-1" style="font-size:0.4rem;color:<?= $color ?>;"></i><?= ucwords(str_replace('_', ' ', $ab->action)) ?></small>
                        <small class="text-muted"><?= $pct ?>%</small>
                    </div>
                    <div class="progress" style="height:5px;border-radius:3px;">
                        <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $color ?>;border-radius:3px;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted"><p class="mb-0">No data</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="admin-card mb-4">
    <form method="GET" action="<?= BASE_URL ?>/admin/audit-logs" id="filterForm">
        <div class="row g-3 align-items-end">
            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Action, entity..." value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold small">Action</label>
                <select name="action" class="form-select" onchange="this.form.submit()">
                    <option value="">All Actions</option>
                    <?php foreach ($distinctActions as $a): ?>
                    <option value="<?= htmlspecialchars($a->action) ?>" <?= $filters['action'] === $a->action ? 'selected' : '' ?>><?= ucwords(str_replace('_', ' ', htmlspecialchars($a->action))) ?> (<?= $a->count ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold small">Entity Type</label>
                <select name="entity_type" class="form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <?php foreach ($entityTypes as $et): ?>
                    <option value="<?= htmlspecialchars($et->entity_type) ?>" <?= $filters['entity_type'] === $et->entity_type ? 'selected' : '' ?>><?= ucfirst($et->entity_type) ?> (<?= $et->count ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold small">User</label>
                <select name="user_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Users</option>
                    <?php foreach ($users as $u): ?>
                    <option value="<?= $u->id ?>" <?= $filters['user_id'] == $u->id ? 'selected' : '' ?>><?= htmlspecialchars($u->first_name . ' ' . $u->last_name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold small">From</label>
                <input type="date" name="start_date" class="form-control" value="<?= $filters['start_date'] ?>">
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="form-label fw-semibold small">To</label>
                <input type="date" name="end_date" class="form-control" value="<?= $filters['end_date'] ?>">
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary btn-sm admin-btn"><i class="bi bi-funnel me-1"></i> Apply Filters</button>
            <a href="<?= BASE_URL ?>/admin/audit-logs" class="btn btn-outline-secondary btn-sm admin-btn"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
            <div class="d-flex gap-1 ms-auto">
                <a href="?start_date=<?= date('Y-m-d') ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">Today</a>
                <a href="?start_date=<?= date('Y-m-d', strtotime('-7 days')) ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">7 Days</a>
                <a href="?start_date=<?= date('Y-m-01') ?>&end_date=<?= date('Y-m-d') ?>" class="btn btn-outline-secondary btn-sm">This Month</a>
            </div>
        </div>
        <?php
        $hasFilters = !empty($filters['action']) || $filters['user_id'] || !empty($filters['entity_type']) || !empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['search']);
        if ($hasFilters): ?>
        <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
            <small class="text-muted me-1">Active filters:</small>
            <?php if (!empty($filters['search'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">Search: <?= htmlspecialchars($filters['search']) ?> <a href="?<?= http_build_query(array_filter(['action'=>$filters['action'],'user_id'=>$filters['user_id'],'entity_type'=>$filters['entity_type'],'start_date'=>$filters['start_date'],'end_date'=>$filters['end_date']])) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
            <?php endif; ?>
            <?php if (!empty($filters['action'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">Action: <?= ucwords(str_replace('_', ' ', $filters['action'])) ?> <a href="?<?= http_build_query(array_filter(['search'=>$filters['search'],'user_id'=>$filters['user_id'],'entity_type'=>$filters['entity_type'],'start_date'=>$filters['start_date'],'end_date'=>$filters['end_date']])) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
            <?php endif; ?>
            <?php if (!empty($filters['entity_type'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">Entity: <?= ucfirst($filters['entity_type']) ?> <a href="?<?= http_build_query(array_filter(['search'=>$filters['search'],'action'=>$filters['action'],'user_id'=>$filters['user_id'],'start_date'=>$filters['start_date'],'end_date'=>$filters['end_date']])) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
            <?php endif; ?>
            <?php if ($filters['user_id']): ?>
                <?php $uName = ''; foreach($users as $u) { if($u->id == $filters['user_id']) { $uName = $u->first_name.' '.$u->last_name; break; } } ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">User: <?= $uName ?> <a href="?<?= http_build_query(array_filter(['search'=>$filters['search'],'action'=>$filters['action'],'entity_type'=>$filters['entity_type'],'start_date'=>$filters['start_date'],'end_date'=>$filters['end_date']])) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
            <?php endif; ?>
            <?php if (!empty($filters['start_date']) || !empty($filters['end_date'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">Date: <?= $filters['start_date'] ?: '...' ?> to <?= $filters['end_date'] ?: '...' ?> <a href="?<?= http_build_query(array_filter(['search'=>$filters['search'],'action'=>$filters['action'],'user_id'=>$filters['user_id'],'entity_type'=>$filters['entity_type']])) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Activity Timeline -->
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Activity Log</h6>
        <small class="text-muted">Showing <strong><?= $pagination->from ?></strong>–<strong><?= $pagination->to ?></strong> of <strong><?= number_format($pagination->total) ?></strong> entries</small>
    </div>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>Action</th>
                    <th>User</th>
                    <th>Entity</th>
                    <th>Details</th>
                    <th>IP Address</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $l):
                        $actionConfig = [
                            'created' => ['color' => '#198754', 'icon' => 'bi-plus-circle-fill'],
                            'updated' => ['color' => '#0d6efd', 'icon' => 'bi-pencil-square'],
                            'deleted' => ['color' => '#dc3545', 'icon' => 'bi-trash-fill'],
                            'login' => ['color' => '#6f42c1', 'icon' => 'bi-box-arrow-in-right'],
                            'logout' => ['color' => '#6c757d', 'icon' => 'bi-box-arrow-right'],
                        ];
                        $ac = $actionConfig[$l->action] ?? ['color' => '#6c757d', 'icon' => 'bi-circle-fill'];
                        if (str_contains($l->action, 'created')) { $ac = ['color' => '#198754', 'icon' => 'bi-plus-circle-fill']; }
                        elseif (str_contains($l->action, 'updated')) { $ac = ['color' => '#0d6efd', 'icon' => 'bi-pencil-square']; }
                        elseif (str_contains($l->action, 'deleted')) { $ac = ['color' => '#dc3545', 'icon' => 'bi-trash-fill']; }
                        elseif (str_contains($l->action, 'login')) { $ac = ['color' => '#6f42c1', 'icon' => 'bi-box-arrow-in-right']; }

                        $hasChanges = !empty($l->old_values) || !empty($l->new_values);
                        $oldVals = $l->old_values ? json_decode($l->old_values, true) : null;
                        $newVals = $l->new_values ? json_decode($l->new_values, true) : null;
                    ?>
                    <tr class="<?= $hasChanges ? 'log-expandable' : '' ?>" <?= $hasChanges ? 'data-log-id="'.$l->id.'" style="cursor:pointer;"' : '' ?>>
                        <td>
                            <div style="width:28px;height:28px;border-radius:7px;background:<?= $ac['color'] ?>15;display:flex;align-items:center;justify-content:center;">
                                <i class="bi <?= $ac['icon'] ?>" style="color:<?= $ac['color'] ?>;font-size:0.8rem;"></i>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background:<?= $ac['color'] ?>15;color:<?= $ac['color'] ?>;border:1px solid <?= $ac['color'] ?>30;font-weight:600;">
                                <?= ucwords(str_replace('_', ' ', $l->action)) ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($l->user_name): ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;background:linear-gradient(135deg,#6f42c1,#d63384);color:#fff;font-weight:600;font-size:0.6rem;">
                                    <?= strtoupper(substr($l->user_name,0,2)) ?>
                                </div>
                                <?php endif; ?>
                                <small class="fw-semibold"><?= htmlspecialchars($l->user_name ?? 'System') ?></small>
                            </div>
                        </td>
                        <td>
                            <?php if ($l->entity_type): ?>
                            <small>
                                <span class="text-muted"><?= ucfirst($l->entity_type) ?></span>
                                <?php if ($l->entity_id): ?>
                                <span class="fw-semibold">#<?= $l->entity_id ?></span>
                                <?php endif; ?>
                            </small>
                            <?php else: ?>
                            <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($hasChanges): ?>
                            <span class="badge bg-light text-dark" style="font-size:0.7rem;">
                                <i class="bi bi-code-slash me-1"></i><?= count((array)$newVals) ?> change<?= count((array)$newVals) != 1 ? 's' : '' ?>
                            </span>
                            <?php else: ?>
                            <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted" style="font-size:0.78rem;"><?= htmlspecialchars($l->ip_address ?? '-') ?></small>
                        </td>
                        <td>
                            <small class="text-muted"><?= format_date($l->created_at, 'M d, Y H:i') ?></small>
                        </td>
                    </tr>
                    <?php if ($hasChanges): ?>
                    <tr class="log-details-row" id="details-<?= $l->id ?>" style="display:none;">
                        <td colspan="7" class="p-0">
                            <div class="p-3" style="background:#f8f9fa;border-top:1px solid #e9ecef;">
                                <div class="row g-3">
                                    <?php if ($oldVals): ?>
                                    <div class="col-md-6">
                                        <small class="fw-bold text-muted d-block mb-2"><i class="bi bi-arrow-left me-1"></i>Old Values</small>
                                        <div class="bg-white p-2 rounded" style="border:1px solid #e9ecef;">
                                            <pre class="mb-0 small" style="white-space:pre-wrap;word-break:break-all;"><?= htmlspecialchars(json_encode($oldVals, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($newVals): ?>
                                    <div class="<?= $oldVals ? 'col-md-6' : 'col-md-12' ?>">
                                        <small class="fw-bold text-muted d-block mb-2"><i class="bi bi-arrow-right me-1"></i>New Values</small>
                                        <div class="bg-white p-2 rounded" style="border:1px solid #e9ecef;">
                                            <pre class="mb-0 small" style="white-space:pre-wrap;word-break:break-all;"><?= htmlspecialchars(json_encode($newVals, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($l->request_method || $l->request_url): ?>
                                <div class="mt-2 pt-2 border-top">
                                    <small class="text-muted">
                                        <span class="badge bg-secondary me-1"><?= htmlspecialchars($l->request_method) ?></span>
                                        <span style="font-size:0.78rem;"><?= htmlspecialchars($l->request_url) ?></span>
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-list-check" style="font-size:2.5rem;opacity:0.3;"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No audit logs found</h6>
                                <p class="mb-0">No activity matches your current filters.</p>
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
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">Page <?= $pagination->page ?> of <?= number_format($pagination->totalPages) ?></small>
            <select class="form-select form-select-sm" style="width:auto;" onchange="window.location=this.value">
                <?php foreach ([25, 50, 100] as $pp): ?>
                <option value="?<?= http_build_query(array_merge($filters, ['page'=>1,'per_page'=>$pp])) ?>" <?= ($pagination->perPage ?? 50) == $pp ? 'selected' : '' ?>><?= $pp ?> per page</option>
                <?php endforeach; ?>
            </select>
        </div>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=1&<?= http_build_query($filters) ?>"><i class="bi bi-chevron-double-left"></i></a></li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page - 1 ?>&<?= http_build_query($filters) ?>"><i class="bi bi-chevron-left"></i></a></li>
                <?php
                $start = max(1, $pagination->page - 2);
                $end = min($pagination->totalPages, $pagination->page + 2);
                if ($start > 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <?php for ($p = $start; $p <= $end; $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $p ?>&<?= http_build_query($filters) ?>"><?= $p ?></a></li>
                <?php endfor; ?>
                <?php if ($end < $pagination->totalPages): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page + 1 ?>&<?= http_build_query($filters) ?>"><i class="bi bi-chevron-right"></i></a></li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->totalPages ?>&<?= http_build_query($filters) ?>"><i class="bi bi-chevron-double-right"></i></a></li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<style>
    .table-admin thead th { font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6c757d;border-bottom:2px solid #e9ecef;padding:12px; }
    .table-admin tbody td { padding:12px;border-bottom:1px solid #f1f3f5; }
    .table-admin tbody tr:hover { background:#f8f9fa; }
    .table-admin tbody tr.log-expandable:hover { background:#f0f4ff; }
    .pagination .page-link { border-radius:6px;margin:0 2px;border:none;color:#6c757d;font-size:0.82rem; }
    .pagination .page-item.active .page-link { background:var(--admin-primary,#1a237e);color:#fff; }
    .pagination .page-link:hover { background:#e9ecef; }
</style>

<script>
// Toggle log details
document.querySelectorAll('.log-expandable').forEach(function(row) {
    row.addEventListener('click', function() {
        var logId = this.getAttribute('data-log-id');
        var details = document.getElementById('details-' + logId);
        if (details) {
            details.style.display = details.style.display === 'none' ? 'table-row' : 'none';
        }
    });
});

// Export CSV
function exportAuditLogs() {
    var params = new URLSearchParams(window.location.search);
    params.set('export', '1');
    params.set('format', 'csv');
    window.location = '<?= BASE_URL ?>/admin/audit-logs?' + params.toString();
}
</script>

<?php if (!empty($dailyTrend)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('activityChart').getContext('2d');
    var data = <?= json_encode($dailyTrend) ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(function(d) { return d.date; }),
            datasets: [{
                label: 'Activity',
                data: data.map(function(d) { return parseInt(d.count); }),
                borderColor: '#6f42c1',
                backgroundColor: 'rgba(111, 66, 193, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#6f42c1',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
<?php endif; ?>
