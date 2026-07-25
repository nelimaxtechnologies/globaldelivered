<!-- Branches Management -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Branches</h4>
        <small class="text-muted">Manage branch locations and resources</small>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary admin-btn" onclick="exportBranches()" title="Export">
            <i class="bi bi-download me-1"></i> Export
        </button>
        <a href="<?= BASE_URL ?>/admin/branches/create" class="btn btn-primary admin-btn">
            <i class="bi bi-plus-lg me-1"></i> Add Branch
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background:linear-gradient(135deg,#0d6efd,#0a58ca);">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="card-value"><?= number_format($stats->total ?? 0) ?></div>
                <div class="card-label">Total Branches</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background:linear-gradient(135deg,#198754,#146c43);">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="card-value text-success"><?= number_format($stats->active_count ?? 0) ?></div>
                <div class="card-label">Active</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background:linear-gradient(135deg,#6f42c1,#5a32a3);">
                <i class="bi bi-building-add"></i>
            </div>
            <div>
                <div class="card-value" style="color:#6f42c1;"><?= number_format($stats->head_offices ?? 0) ?></div>
                <div class="card-label">Head Offices</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card d-flex align-items-center gap-3">
            <div class="card-stat-icon" style="background:linear-gradient(135deg,#0dcaf0,#0aa2c0);">
                <i class="bi bi-geo-alt"></i>
            </div>
            <div>
                <div class="card-value" style="color:#0dcaf0;"><?= number_format(($stats->regional ?? 0) + ($stats->local_branches ?? 0)) ?></div>
                <div class="card-label">Regional + Local</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" id="filterForm">
        <div class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold small">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Name, code, city..." value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Country</label>
                <select name="country" class="form-select" onchange="this.form.submit()">
                    <option value="">All Countries</option>
                    <?php foreach ($countries as $c): ?>
                    <option value="<?= htmlspecialchars($c->country) ?>" <?= $filters['country'] === $c->country ? 'selected' : '' ?>><?= htmlspecialchars($c->country) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Type</label>
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="head_office" <?= $filters['type'] === 'head_office' ? 'selected' : '' ?>>Head Office</option>
                    <option value="regional" <?= $filters['type'] === 'regional' ? 'selected' : '' ?>>Regional</option>
                    <option value="local" <?= $filters['type'] === 'local' ? 'selected' : '' ?>>Local</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="1" <?= $filters['status'] === '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= $filters['status'] === '0' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary admin-btn flex-fill">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="<?= BASE_URL ?>/admin/branches" class="btn btn-outline-secondary admin-btn">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
        <?php if (!empty($filters['search']) || !empty($filters['country']) || !empty($filters['type']) || $filters['status'] !== ''): ?>
        <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
            <small class="text-muted me-1">Active filters:</small>
            <?php if (!empty($filters['search'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">
                Search: <?= htmlspecialchars($filters['search']) ?>
                <a href="?country=<?= urlencode($filters['country']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>" class="text-white ms-1">&times;</a>
            </span>
            <?php endif; ?>
            <?php if (!empty($filters['country'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">
                <?= htmlspecialchars($filters['country']) ?>
                <a href="?search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>" class="text-white ms-1">&times;</a>
            </span>
            <?php endif; ?>
            <?php if (!empty($filters['type'])): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">
                <?= str_replace('_', ' ', ucfirst($filters['type'])) ?>
                <a href="?search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&status=<?= urlencode($filters['status']) ?>" class="text-white ms-1">&times;</a>
            </span>
            <?php endif; ?>
            <?php if ($filters['status'] !== ''): ?>
            <span class="badge bg-primary d-flex align-items-center gap-1">
                <?= $filters['status'] === '1' ? 'Active' : 'Inactive' ?>
                <a href="?search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&type=<?= urlencode($filters['type']) ?>" class="text-white ms-1">&times;</a>
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Type Quick Filter Pills -->
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="<?= BASE_URL ?>/admin/branches" class="status-pill <?= empty($filters['type']) ? 'active' : '' ?>">
        <i class="bi bi-grid-3x3-gap me-1"></i> All <span class="badge bg-light text-dark ms-1"><?= number_format($stats->total ?? 0) ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/branches?type=head_office" class="status-pill <?= ($filters['type'] ?? '') === 'head_office' ? 'active' : '' ?>" style="--pill-color:#6f42c1;">
        <i class="bi bi-building-add me-1"></i> Head Office <span class="badge bg-light text-dark ms-1"><?= $stats->head_offices ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/branches?type=regional" class="status-pill <?= ($filters['type'] ?? '') === 'regional' ? 'active' : '' ?>" style="--pill-color:#0dcaf0;">
        <i class="bi bi-geo-alt me-1"></i> Regional <span class="badge bg-light text-dark ms-1"><?= $stats->regional ?? 0 ?></span>
    </a>
    <a href="<?= BASE_URL ?>/admin/branches?type=local" class="status-pill <?= ($filters['type'] ?? '') === 'local' ? 'active' : '' ?>" style="--pill-color:#198754;">
        <i class="bi bi-geo me-1"></i> Local <span class="badge bg-light text-dark ms-1"><?= $stats->local_branches ?? 0 ?></span>
    </a>
</div>

<!-- Branches Table -->
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <small class="text-muted">
            Showing <strong><?= $pagination->from ?></strong>–<strong><?= $pagination->to ?></strong> of <strong><?= number_format($pagination->total) ?></strong> branches
        </small>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small me-1">Per page:</label>
            <select class="form-select form-select-sm" style="width:auto;" onchange="window.location=this.value">
                <?php foreach ([10, 25, 50, 100] as $pp): ?>
                <option value="?page=1&per_page=<?= $pp ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>" <?= ($pagination->perPage ?? 25) == $pp ? 'selected' : '' ?>><?= $pp ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th style="width:220px;">Branch</th>
                    <th>Code</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Manager</th>
                    <th class="text-center">Resources</th>
                    <th>Contact</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($branches)): ?>
                    <?php foreach ($branches as $b): ?>
                    <?php
                    $typeColors = [
                        'head_office' => ['#6f42c1', '#6f42c120'],
                        'regional' => ['#0dcaf0', '#0dcaf020'],
                        'local' => ['#198754', '#19875420'],
                    ];
                    [$typeColor, $typeBg] = $typeColors[$b->branch_type] ?? ['#6c757d', '#6c757d20'];
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:38px;height:38px;border-radius:10px;background:<?= $typeBg ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-building" style="color:<?= $typeColor ?>;font-size:1rem;"></i>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/admin/branches/<?= $b->id ?>" class="fw-semibold text-decoration-none" style="font-size:0.88rem;">
                                        <?= htmlspecialchars($b->name) ?>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td><span class="fw-bold small" style="font-size:0.82rem;"><?= htmlspecialchars($b->code) ?></span></td>
                        <td>
                            <div><small><i class="bi bi-geo-alt me-1 text-muted"></i><?= htmlspecialchars($b->city) ?></small></div>
                            <div><small class="text-muted"><?= htmlspecialchars($b->country) ?></small></div>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background:<?= $typeBg ?>;color:<?= $typeColor ?>;border:1px solid <?= $typeColor ?>30;font-weight:600;font-size:0.72rem;">
                                <?= str_replace('_', ' ', ucfirst($b->branch_type)) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($b->manager_name): ?>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#0d6efd,#0a58ca);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;">
                                    <?= strtoupper(substr($b->manager_name, 0, 2)) ?>
                                </div>
                                <small><?= htmlspecialchars($b->manager_name) ?></small>
                            </div>
                            <?php else: ?>
                            <small class="text-muted">—</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <?php if ((int)$b->warehouse_count > 0): ?>
                                <span class="badge bg-primary" style="font-size:0.7rem;"><i class="bi bi-boxes me-1"></i><?= (int)$b->warehouse_count ?></span>
                                <?php endif; ?>
                                <?php if ((int)$b->driver_count > 0): ?>
                                <span class="badge bg-success" style="font-size:0.7rem;"><i class="bi bi-person-badge me-1"></i><?= (int)$b->driver_count ?></span>
                                <?php endif; ?>
                                <?php if ((int)$b->vehicle_count > 0): ?>
                                <span class="badge bg-info" style="font-size:0.7rem;"><i class="bi bi-truck me-1"></i><?= (int)$b->vehicle_count ?></span>
                                <?php endif; ?>
                                <?php if ((int)$b->warehouse_count === 0 && (int)$b->driver_count === 0 && (int)$b->vehicle_count === 0): ?>
                                <small class="text-muted">—</small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <small><i class="bi bi-phone me-1 text-muted"></i><?= htmlspecialchars($b->phone) ?></small>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input branch-toggle" type="checkbox" data-id="<?= $b->id ?>" <?= $b->is_active ? 'checked' : '' ?>
                                       style="cursor:pointer; <?= $b->is_active ? 'background-color:#198754;border-color:#198754;' : '' ?>">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= BASE_URL ?>/admin/branches/<?= $b->id ?>" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/branches/<?= $b->id ?>/edit" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-building" style="font-size:2.5rem;opacity:0.3;"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No branches found</h6>
                                <p class="mb-3" style="max-width:400px;margin:0 auto;">
                                    <?php if (!empty($filters['search']) || !empty($filters['country']) || !empty($filters['type']) || $filters['status'] !== ''): ?>
                                        No results match your current filters. Try adjusting your search criteria.
                                    <?php else: ?>
                                        Get started by adding your first branch location.
                                    <?php endif; ?>
                                </p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <?php if (!empty($filters['search']) || !empty($filters['country']) || !empty($filters['type']) || $filters['status'] !== ''): ?>
                                    <a href="<?= BASE_URL ?>/admin/branches" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Filters
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>/admin/branches/create" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-lg me-1"></i> Add Branch
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
                    <a class="page-link" href="?page=1&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-double-left"></i></a>
                </li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php
                $start = max(1, $pagination->page - 2);
                $end = min($pagination->totalPages, $pagination->page + 2);
                if ($start > 1): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <?php for ($p = $start; $p <= $end; $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <?php if ($end < $pagination->totalPages): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->totalPages ?>&search=<?= urlencode($filters['search']) ?>&country=<?= urlencode($filters['country']) ?>&type=<?= urlencode($filters['type']) ?>&status=<?= urlencode($filters['status']) ?>"><i class="bi bi-chevron-double-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<style>
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
</style>

<script>
function exportBranches() {
    var params = new URLSearchParams(window.location.search);
    window.location = '<?= BASE_URL ?>/admin/branches/export?' + params.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.branch-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var id = this.dataset.id;
            var btn = this;
            btn.disabled = true;
            $.ajax({
                url: '<?= BASE_URL ?>/admin/branches/' + id + '/toggle-active',
                method: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        btn.style.backgroundColor = res.is_active ? '#198754' : '';
                        btn.style.borderColor = res.is_active ? '#198754' : '';
                        showToast(res.message, 'success');
                    } else {
                        btn.checked = !btn.checked;
                        showToast(res.message || 'Failed', 'error');
                    }
                    btn.disabled = false;
                },
                error: function() {
                    btn.checked = !btn.checked;
                    btn.disabled = false;
                    showToast('Request failed', 'error');
                }
            });
        });
    });
});
</script>
