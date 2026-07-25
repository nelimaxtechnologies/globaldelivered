<!-- ============================================================
     VEHICLES MANAGEMENT - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Vehicles</h4>
        <small class="text-muted">Manage your fleet vehicles and maintenance</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/vehicles/create" class="btn btn-primary admin-btn">
        <i class="bi bi-plus-lg me-1"></i> Add Vehicle
    </a>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card text-center py-3 mini-stat">
            <div class="fw-bold fs-4 text-primary"><?= number_format($stats->total ?? 0) ?></div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card text-center py-3 mini-stat" style="border-top:3px solid #198754;">
            <div class="fw-bold fs-4 text-success"><?= number_format($stats->active ?? 0) ?></div>
            <small class="text-muted">Active</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card text-center py-3 mini-stat" style="border-top:3px solid #ffc107;">
            <div class="fw-bold fs-4 text-warning"><?= number_format($stats->maintenance ?? 0) ?></div>
            <small class="text-muted">Maintenance</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card text-center py-3 mini-stat" style="border-top:3px solid #dc3545;">
            <div class="fw-bold fs-4 text-danger"><?= number_format($stats->out_of_service ?? 0) ?></div>
            <small class="text-muted">Out of Service</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card text-center py-3 mini-stat" style="border-top:3px solid #6c757d;">
            <div class="fw-bold fs-4 text-secondary"><?= number_format($stats->retired ?? 0) ?></div>
            <small class="text-muted">Retired</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card text-center py-3 mini-stat" style="border-top:3px solid #0dcaf0;">
            <div class="fw-bold fs-4 text-info"><?= number_format($stats->total_weight_capacity ?? 0) ?></div>
            <small class="text-muted">Total kg</small>
        </div>
    </div>
</div>

<!-- Status Filter Pills -->
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="<?= BASE_URL ?>/admin/vehicles" class="status-pill <?= empty($filters['status']) && empty($filters['type']) ? 'active' : '' ?>">
        <i class="bi bi-grid-3x3-gap me-1"></i> All <span class="badge bg-light text-dark ms-1"><?= number_format($stats->total ?? 0) ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/vehicles?status=active" class="status-pill <?= ($filters['status'] ?? '') === 'active' ? 'active' : '' ?>">
        <i class="bi bi-check-circle me-1"></i> Active <span class="badge bg-light text-dark ms-1"><?= $stats->active ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/vehicles?status=maintenance" class="status-pill <?= ($filters['status'] ?? '') === 'maintenance' ? 'active' : '' ?>">
        <i class="bi bi-wrench me-1"></i> Maintenance <span class="badge bg-light text-dark ms-1"><?= $stats->maintenance ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/vehicles?status=out_of_service" class="status-pill <?= ($filters['status'] ?? '') === 'out_of_service' ? 'active' : '' ?>">
        <i class="bi bi-x-circle me-1"></i> Out of Service <span class="badge bg-light text-dark ms-1"><?= $stats->out_of_service ?? 0 ?></span>
    </a>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label class="form-label small fw-semibold">Search</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Name, reg, make, model..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label small fw-semibold">Vehicle Type</label>
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">All Types</option>
                <?php foreach ($vehicleTypes as $t): ?>
                <option value="<?= htmlspecialchars($t->vehicle_type) ?>" <?= $filters['type'] === $t->vehicle_type ? 'selected' : '' ?>><?= ucwords(str_replace('_', ' ', $t->vehicle_type)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label small fw-semibold">Status</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="maintenance" <?= $filters['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                <option value="out_of_service" <?= $filters['status'] === 'out_of_service' ? 'selected' : '' ?>>Out of Service</option>
                <option value="retired" <?= $filters['status'] === 'retired' ? 'selected' : '' ?>>Retired</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <button type="submit" class="btn btn-primary admin-btn w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
        <div class="col-lg-2 col-md-6">
            <a href="<?= BASE_URL ?>/admin/vehicles" class="btn btn-outline-secondary admin-btn w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
        </div>
    </form>
    <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['type'])): ?>
    <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
        <small class="text-muted me-1">Active filters:</small>
        <?php if (!empty($filters['search'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">Search: <?= htmlspecialchars($filters['search']) ?> <a href="?type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
        <?php endif; ?>
        <?php if (!empty($filters['type'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">Type: <?= ucwords(str_replace('_', ' ', $filters['type'])) ?> <a href="?search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
        <?php endif; ?>
        <?php if (!empty($filters['status'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">Status: <?= str_replace('_', ' ', ucfirst($filters['status'])) ?> <a href="?search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Vehicles Table -->
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <small class="text-muted">Showing <strong><?= $pagination->from ?></strong>–<strong><?= $pagination->to ?></strong> of <strong><?= number_format($pagination->total) ?></strong> vehicles</small>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small me-1">Per page:</label>
            <select class="form-select form-select-sm" style="width:auto;" onchange="window.location=this.value">
                <?php foreach ([10, 25, 50, 100] as $pp): ?>
                <option value="?page=1&per_page=<?= $pp ?>&search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>" <?= ($pagination->perPage ?? 25) == $pp ? 'selected' : '' ?>><?= $pp ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Registration</th>
                    <th>Type</th>
                    <th>Branch</th>
                    <th class="text-end">Capacity</th>
                    <th>Status</th>
                    <th>Next Service</th>
                    <th class="text-center" style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($vehicles)): ?>
                    <?php foreach ($vehicles as $v):
                        $typeIcons = ['car' => 'bi-car-front', 'motorbike' => 'bi-bicycle', 'van' => 'bi-truck', 'truck' => 'bi-truck', 'container' => 'bi-box-seam', 'air_cargo' => 'bi-airplane', 'ship' => 'bi-ship'];
                        $typeIcon = $typeIcons[$v->vehicle_type] ?? 'bi-truck';
                        $statusColors = ['active' => '#198754', 'maintenance' => '#ffc107', 'out_of_service' => '#dc3545', 'retired' => '#6c757d'];
                        $statusColor = $statusColors[$v->status] ?? '#6c757d';
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,<?= $statusColor ?>,<?= $statusColor ?>dd);color:#fff;font-size:0.9rem;">
                                    <i class="bi <?= $typeIcon ?>"></i>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/admin/vehicles/<?= $v->id ?>" class="fw-semibold text-decoration-none" style="font-size:0.88rem;">
                                        <?= htmlspecialchars($v->name) ?>
                                    </a>
                                    <?php if ($v->make || $v->model): ?>
                                    <div><small class="text-muted"><?= htmlspecialchars($v->make) ?> <?= htmlspecialchars($v->model) ?><?= $v->year ? ' (' . $v->year . ')' : '' ?></small></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><span class="fw-semibold small" style="font-family:monospace;"><?= htmlspecialchars($v->registration_number) ?></span></td>
                        <td>
                            <span class="badge bg-light text-dark border text-capitalize"><?= str_replace('_', ' ', $v->vehicle_type) ?></span>
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($v->branch_name ?? '-') ?></small></td>
                        <td class="text-end">
                            <?php if ($v->capacity_weight || $v->capacity_volume): ?>
                                <small>
                                    <?php if ($v->capacity_weight): ?><span class="fw-semibold"><?= number_format($v->capacity_weight) ?></span> kg<?php endif; ?>
                                    <?php if ($v->capacity_weight && $v->capacity_volume): ?><br><?php endif; ?>
                                    <?php if ($v->capacity_volume): ?><span class="fw-semibold"><?= number_format($v->capacity_volume, 1) ?></span> m³<?php endif; ?>
                                </small>
                            <?php else: ?>
                                <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background:<?= $statusColor ?>15;color:<?= $statusColor ?>;border:1px solid <?= $statusColor ?>30;font-weight:600;">
                                <?= str_replace('_', ' ', ucfirst($v->status)) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($v->maintenance_next): ?>
                                <?php $isOverdue = strtotime($v->maintenance_next) < time(); ?>
                                <?php $isSoon = strtotime($v->maintenance_next) < strtotime('+30 days'); ?>
                                <small class="<?= ($isOverdue || $isSoon) ? 'text-danger fw-semibold' : '' ?>">
                                    <?= format_date($v->maintenance_next, 'M d, Y') ?>
                                    <?php if ($isOverdue): ?><i class="bi bi-exclamation-triangle ms-1"></i><?php endif; ?>
                                </small>
                            <?php else: ?>
                                <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= BASE_URL ?>/admin/vehicles/<?= $v->id ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/admin/vehicles/<?= $v->id ?>/edit" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-sm btn-outline-danger delete-vehicle-btn" title="Delete" data-id="<?= $v->id ?>" data-name="<?= htmlspecialchars($v->name) ?>"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-truck" style="font-size:2.5rem;opacity:0.3;"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No vehicles found</h6>
                                <p class="mb-3">Try adjusting your filters or add a new vehicle.</p>
                                <a href="<?= BASE_URL ?>/admin/vehicles/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> Add Vehicle</a>
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
        <small class="text-muted">Page <?= $pagination->page ?> of <?= number_format($pagination->totalPages) ?></small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=1&search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-double-left"></i></a></li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-left"></i></a></li>
                <?php
                $start = max(1, $pagination->page - 2);
                $end = min($pagination->totalPages, $pagination->page + 2);
                if ($start > 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <?php for ($p = $start; $p <= $end; $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><?= $p ?></a></li>
                <?php endfor; ?>
                <?php if ($end < $pagination->totalPages): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-right"></i></a></li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->totalPages ?>&search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-double-right"></i></a></li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<style>
    .mini-stat { transition: all 0.2s; }
    .mini-stat:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .status-pill { display:inline-flex;align-items:center;padding:6px 14px;border-radius:20px;border:1px solid #e9ecef;background:#fff;color:#495057;text-decoration:none;font-size:0.82rem;font-weight:600;transition:all 0.2s;white-space:nowrap; }
    .status-pill:hover { background:#f8f9fa;border-color:#dee2e6;transform:translateY(-1px); }
    .status-pill.active { background:var(--admin-primary,#1a237e);color:#fff;border-color:var(--admin-primary,#1a237e); }
    .status-pill.active .badge { background:rgba(255,255,255,0.2) !important;color:#fff !important; }
    .status-pill .badge { font-size:0.7rem; }
    .table-admin thead th { font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6c757d;border-bottom:2px solid #e9ecef;padding:12px; }
    .table-admin tbody td { padding:12px;border-bottom:1px solid #f1f3f5; }
    .table-admin tbody tr:hover { background:#f8f9fa; }
    .pagination .page-link { border-radius:6px;margin:0 2px;border:none;color:#6c757d;font-size:0.82rem; }
    .pagination .page-item.active .page-link { background:var(--admin-primary,#1a237e);color:#fff; }
    .pagination .page-link:hover { background:#e9ecef; }
</style>

<script>
document.querySelectorAll('.delete-vehicle-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var name = this.getAttribute('data-name');
        Swal.fire({
            title: 'Delete Vehicle?',
            text: 'Delete "' + name + '"? This action cannot be undone.',
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
                form.action = '<?= BASE_URL ?>/admin/vehicles/' + id;
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
