<!-- ============================================================
     WAREHOUSES MANAGEMENT - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Warehouses</h4>
        <small class="text-muted">Manage your warehouse facilities and inventory</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/warehouses/create" class="btn btn-primary admin-btn">
        <i class="bi bi-plus-lg me-1"></i> Add Warehouse
    </a>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-boxes"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->total ?? 0) ?></div>
                    <div class="card-label">Total Warehouses</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->active ?? 0) ?></div>
                    <div class="card-label">Active</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
                    <i class="bi bi-thermometer-snow"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->temp_controlled ?? 0) ?></div>
                    <div class="card-label">Temp Controlled</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                    <i class="bi bi-speedometer"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->total_capacity ?? 0) ?></div>
                    <div class="card-label">Total Capacity (m³)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label class="form-label small fw-semibold">Search</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Name, code, city..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label small fw-semibold">Country</label>
            <select name="country" class="form-select">
                <option value="">All Countries</option>
                <?php foreach ($countries as $c): ?>
                    <option value="<?= htmlspecialchars($c->country) ?>" <?= $filters['country'] === $c->country ? 'selected' : '' ?>><?= htmlspecialchars($c->country) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label small fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                <option value="temp" <?= ($filters['status'] ?? '') === 'temp' ? 'selected' : '' ?>>Temp Controlled</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <button type="submit" class="btn btn-primary admin-btn w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
        <div class="col-lg-2 col-md-6">
            <a href="<?= BASE_URL ?>/admin/warehouses" class="btn btn-outline-secondary admin-btn w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
        </div>
    </form>
    <?php if (!empty($filters['search']) || !empty($filters['country']) || !empty($filters['status'])): ?>
    <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
        <small class="text-muted me-1">Active filters:</small>
        <?php if (!empty($filters['search'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">Search: <?= htmlspecialchars($filters['search']) ?> <a href="?country=<?= urlencode($filters['country']) ?>&status=<?= urlencode($filters['status']) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
        <?php endif; ?>
        <?php if (!empty($filters['country'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">Country: <?= htmlspecialchars($filters['country']) ?> <a href="?search=<?= urlencode($filters['search']) ?>&status=<?= urlencode($filters['status']) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
        <?php endif; ?>
        <?php if (!empty($filters['status'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">Status: <?= ucfirst($filters['status']) ?> <a href="?search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Warehouses Table -->
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <small class="text-muted">Showing <strong><?= $pagination->from ?></strong>–<strong><?= $pagination->to ?></strong> of <strong><?= number_format($pagination->total) ?></strong> warehouses</small>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small me-1">Per page:</label>
            <select class="form-select form-select-sm" style="width:auto;" onchange="window.location=this.value">
                <?php foreach ([10, 25, 50, 100] as $pp): ?>
                <option value="?page=1&per_page=<?= $pp ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&status=<?= urlencode($filters['status']) ?>" <?= ($pagination->perPage ?? 25) == $pp ? 'selected' : '' ?>><?= $pp ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Warehouse</th>
                    <th>Code</th>
                    <th>Branch</th>
                    <th>Location</th>
                    <th class="text-end">Capacity</th>
                    <th class="text-center">Temp</th>
                    <th class="text-center">Inventory</th>
                    <th>Status</th>
                    <th class="text-center" style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($warehouses)): ?>
                    <?php foreach ($warehouses as $w): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:<?= $w->temperature_controlled ? 'linear-gradient(135deg,#0dcaf0,#0aa2c0)' : 'linear-gradient(135deg,#6c757d,#5a6268)' ?>;color:#fff;font-size:0.85rem;">
                                    <i class="bi bi-boxes"></i>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/admin/warehouses/<?= $w->id ?>" class="fw-semibold text-decoration-none" style="font-size:0.88rem;">
                                        <?= htmlspecialchars($w->name) ?>
                                    </a>
                                    <?php if ($w->manager_name): ?>
                                    <div><small class="text-muted"><i class="bi bi-person me-1"></i><?= htmlspecialchars($w->manager_name) ?></small></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><span class="fw-bold small" style="font-family:monospace;"><?= htmlspecialchars($w->code) ?></span></td>
                        <td>
                            <?php if ($w->branch_name): ?>
                                <span class="badge bg-light text-dark border"><i class="bi bi-building me-1"></i><?= htmlspecialchars($w->branch_name) ?></span>
                            <?php else: ?>
                                <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><small><i class="bi bi-geo-alt me-1 text-muted"></i><?= htmlspecialchars($w->city) ?></small></div>
                            <div><small class="text-muted"><?= htmlspecialchars($w->country) ?></small></div>
                        </td>
                        <td class="text-end">
                            <?php if ($w->capacity): ?>
                                <span class="fw-semibold"><?= number_format($w->capacity) ?></span> <small class="text-muted">m³</small>
                            <?php else: ?>
                                <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($w->temperature_controlled): ?>
                                <span class="badge bg-info rounded-pill"><i class="bi bi-thermometer-snow me-1"></i>Yes</span>
                            <?php else: ?>
                                <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ((int)$w->inventory_count > 0): ?>
                                <span class="badge bg-primary rounded-pill"><?= (int)$w->inventory_count ?></span>
                            <?php else: ?>
                                <small class="text-muted">0</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    <?= $w->is_active ? 'checked' : '' ?>
                                    onchange="toggleWarehouseStatus(<?= $w->id ?>, this)"
                                    title="<?= $w->is_active ? 'Active' : 'Inactive' ?>">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= BASE_URL ?>/admin/warehouses/<?= $w->id ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/admin/warehouses/<?= $w->id ?>/edit" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-sm btn-outline-danger delete-wh-btn" title="Delete" data-id="<?= $w->id ?>" data-name="<?= htmlspecialchars($w->name) ?>"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-boxes" style="font-size:2.5rem;opacity:0.3;"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No warehouses found</h6>
                                <p class="mb-3">Try adjusting your filters or add a new warehouse.</p>
                                <a href="<?= BASE_URL ?>/admin/warehouses/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> Add Warehouse</a>
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
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=1&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-double-left"></i></a></li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-left"></i></a></li>
                <?php
                $start = max(1, $pagination->page - 2);
                $end = min($pagination->totalPages, $pagination->page + 2);
                if ($start > 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <?php for ($p = $start; $p <= $end; $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&status=<?= urlencode($filters['status']) ?>"><?= $p ?></a></li>
                <?php endfor; ?>
                <?php if ($end < $pagination->totalPages): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-right"></i></a></li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->totalPages ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-double-right"></i></a></li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<style>
    .table-admin thead th { font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6c757d;border-bottom:2px solid #e9ecef;padding:12px; }
    .table-admin tbody td { padding:12px;border-bottom:1px solid #f1f3f5; }
    .table-admin tbody tr:hover { background:#f8f9fa; }
    .pagination .page-link { border-radius:6px;margin:0 2px;border:none;color:#6c757d;font-size:0.82rem; }
    .pagination .page-item.active .page-link { background:var(--admin-primary,#1a237e);color:#fff; }
    .pagination .page-link:hover { background:#e9ecef; }
</style>

<script>
function toggleWarehouseStatus(id, el) {
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    fetch('<?= BASE_URL ?>/admin/warehouses/' + id + '/toggle-status', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
        body: '_csrf_token=' + encodeURIComponent(csrf)
    })
    .then(function(r) { return r.json(); })
    .then(function(r) {
        if (r.success) {
            showToast(r.message, 'success');
        } else {
            el.checked = !el.checked;
            showToast(r.message || 'Error', 'danger');
        }
    })
    .catch(function() { el.checked = !el.checked; showToast('Request failed', 'danger'); });
}

document.querySelectorAll('.delete-wh-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var name = this.getAttribute('data-name');
        Swal.fire({
            title: 'Delete Warehouse?',
            text: 'Delete "' + name + '"? This will also soft-delete all related inventory.',
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
                form.action = '<?= BASE_URL ?>/admin/warehouses/' + id;
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

function showToast(message, type) {
    var toast = document.createElement('div');
    toast.className = 'alert alert-' + type + ' alert-dismissible fade show position-fixed';
    toast.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:280px;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
    toast.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    document.body.appendChild(toast);
    setTimeout(function() { toast.remove(); }, 3000);
}
</script>
