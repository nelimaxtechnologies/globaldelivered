<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-person-badge me-2"></i>Drivers</h4>
        <small class="text-muted">Manage your delivery drivers</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/drivers/create" class="btn btn-primary admin-btn">
        <i class="bi bi-plus-lg me-1"></i> Add Driver
    </a>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->total ?? 0) ?></div>
                    <div class="card-label">Total Drivers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->available ?? 0) ?></div>
                    <div class="card-label">Available</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->on_delivery ?? 0) ?></div>
                    <div class="card-label">On Delivery</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6c757d, #5a6268);">
                    <i class="bi bi-person-x"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->inactive ?? 0) ?></div>
                    <div class="card-label">Off Duty / Leave</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label small fw-semibold">Search</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Name, phone, license number..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="available" <?= $filters['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="on_delivery" <?= $filters['status'] === 'on_delivery' ? 'selected' : '' ?>>On Delivery</option>
                <option value="off_duty" <?= $filters['status'] === 'off_duty' ? 'selected' : '' ?>>Off Duty</option>
                <option value="on_leave" <?= $filters['status'] === 'on_leave' ? 'selected' : '' ?>>On Leave</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary admin-btn w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </div>
        <div class="col-md-2">
            <a href="<?= BASE_URL ?>/admin/drivers" class="btn btn-outline-secondary admin-btn w-100">Reset</a>
        </div>
    </form>
    <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
    <div class="mt-3">
        <small class="text-muted">Active filters:</small>
        <?php if (!empty($filters['search'])): ?>
            <span class="badge bg-primary ms-1"><?= htmlspecialchars($filters['search']) ?> <a href="?status=<?= $filters['status'] ?>" class="text-white ms-1">&times;</a></span>
        <?php endif; ?>
        <?php if (!empty($filters['status'])): ?>
            <span class="badge bg-primary ms-1"><?= str_replace('_', ' ', ucfirst($filters['status'])) ?> <a href="?search=<?= urlencode($filters['search']) ?>" class="text-white ms-1">&times;</a></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Drivers Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-admin">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Contact</th>
                    <th>License</th>
                    <th>Branch</th>
                    <th>Vehicle</th>
                    <th>Status</th>
                    <th class="text-center">Active</th>
                    <th class="text-center">Completed</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($drivers)): ?>
                    <?php foreach ($drivers as $d): ?>
                    <?php
                        $statusColors = [
                            'available' => '#198754',
                            'on_delivery' => '#ffc107',
                            'off_duty' => '#6c757d',
                            'on_leave' => '#0dcaf0',
                        ];
                        $statusColor = $statusColors[$d->status] ?? '#6c757d';
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg,<?= $statusColor ?>,<?= $statusColor ?>dd);color:#fff;font-weight:600;font-size:0.8rem;">
                                    <?= strtoupper(substr($d->first_name,0,1) . substr($d->last_name,0,1)) ?>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/admin/drivers/<?= $d->id ?>" class="fw-semibold text-decoration-none">
                                        <?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><small><i class="bi bi-phone me-1 text-muted"></i><?= htmlspecialchars($d->phone) ?></small></div>
                            <?php if ($d->email): ?>
                            <div><small><i class="bi bi-envelope me-1 text-muted"></i><?= htmlspecialchars($d->email) ?></small></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="fw-semibold"><?= htmlspecialchars($d->license_number) ?></small>
                            <?php if ($d->license_expiry && strtotime($d->license_expiry) < time()): ?>
                                <div><span class="badge bg-danger" style="font-size:0.65rem;">Expired</span></div>
                            <?php endif; ?>
                        </td>
                        <td><small><?= htmlspecialchars($d->branch_name ?? '-') ?></small></td>
                        <td><small class="fw-semibold"><?= htmlspecialchars($d->vehicle_reg ?? '-') ?></small></td>
                        <td>
                            <span class="badge" style="background:<?= $statusColor ?>;<?= $d->status === 'on_delivery' ? 'color:#000;' : '' ?>">
                                <?= str_replace('_', ' ', $d->status) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if ((int)$d->active_deliveries > 0): ?>
                                <span class="badge bg-warning text-dark"><?= (int)$d->active_deliveries ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="fw-semibold"><?= (int)$d->completed_deliveries ?></span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= BASE_URL ?>/admin/drivers/<?= $d->id ?>" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/admin/drivers/<?= $d->id ?>/edit" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">
                            <div class="text-center py-5">
                                <i class="bi bi-person-badge display-3 text-muted"></i>
                                <h5 class="mt-3 text-muted">No drivers found</h5>
                                <p class="text-muted">Try adjusting your filters or add a new driver.</p>
                                <a href="<?= BASE_URL ?>/admin/drivers/create" class="btn btn-primary admin-btn mt-2">
                                    <i class="bi bi-plus-lg me-1"></i> Add Driver
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination->totalPages > 1): ?>
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <small class="text-muted">
            Showing <?= (($pagination->page - 1) * $pagination->perPage) + 1 ?> to <?= min($pagination->page * $pagination->perPage, $pagination->total) ?> of <?= number_format($pagination->total) ?> drivers
        </small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php for ($p = max(1, $pagination->page - 2); $p <= min($pagination->totalPages, $pagination->page + 2); $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
