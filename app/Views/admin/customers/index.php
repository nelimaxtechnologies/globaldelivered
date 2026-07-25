<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-people me-2"></i>Customers</h4>
        <small class="text-muted">Manage your customer database</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/customers/create" class="btn btn-primary admin-btn">
        <i class="bi bi-plus-lg me-1"></i> Add Customer
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
                    <div class="card-label">Total Customers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6c757d, #5a6268);">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->individual_count ?? 0) ?></div>
                    <div class="card-label">Individual</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($stats->company_count ?? 0) ?></div>
                    <div class="card-label">Companies</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="card-value"><?= format_currency($stats->total_revenue ?? 0) ?></div>
                    <div class="card-label">Total Revenue</div>
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
                <input type="text" name="search" class="form-control" placeholder="Name, email, phone, company..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Customer Type</label>
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="individual" <?= $filters['type'] === 'individual' ? 'selected' : '' ?>>Individual</option>
                <option value="company" <?= $filters['type'] === 'company' ? 'selected' : '' ?>>Company</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary admin-btn w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </div>
        <div class="col-md-2">
            <a href="<?= BASE_URL ?>/admin/customers" class="btn btn-outline-secondary admin-btn w-100">Reset</a>
        </div>
    </form>
    <?php if (!empty($filters['search']) || !empty($filters['type'])): ?>
    <div class="mt-3">
        <small class="text-muted">Active filters:</small>
        <?php if (!empty($filters['search'])): ?>
            <span class="badge bg-primary ms-1"><?= htmlspecialchars($filters['search']) ?> <a href="?type=<?= $filters['type'] ?>" class="text-white ms-1">&times;</a></span>
        <?php endif; ?>
        <?php if (!empty($filters['type'])): ?>
            <span class="badge bg-primary ms-1"><?= ucfirst($filters['type']) ?> <a href="?search=<?= urlencode($filters['search']) ?>" class="text-white ms-1">&times;</a></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Customers Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-admin">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Shipments</th>
                    <th>Total Spent</th>
                    <th>Joined</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $c): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:<?= $c->customer_type === 'company' ? 'linear-gradient(135deg,#198754,#146c43)' : 'linear-gradient(135deg,#0d6efd,#0a58ca)' ?>;color:#fff;font-weight:600;font-size:0.8rem;">
                                    <?= strtoupper(substr($c->first_name,0,1) . substr($c->last_name,0,1)) ?>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/admin/customers/<?= $c->id ?>" class="fw-semibold text-decoration-none"><?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?></a>
                                    <?php if ($c->company_name): ?>
                                        <div><small class="text-muted"><?= htmlspecialchars($c->company_name) ?></small></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><small><i class="bi bi-envelope me-1 text-muted"></i><?= htmlspecialchars($c->email) ?></small></div>
                            <div><small><i class="bi bi-phone me-1 text-muted"></i><?= htmlspecialchars($c->phone) ?></small></div>
                        </td>
                        <td><span class="badge" style="background:<?= $c->customer_type === 'company' ? '#198754' : '#6c757d' ?>;"><?= $c->customer_type ?></span></td>
                        <td>
                            <span class="fw-semibold"><?= (int)$c->total_shipments ?></span>
                        </td>
                        <td><span class="fw-semibold"><?= format_currency($c->total_spent) ?></span></td>
                        <td><small class="text-muted"><?= format_date($c->created_at, 'M d, Y') ?></small></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= BASE_URL ?>/admin/customers/<?= $c->id ?>" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/admin/customers/<?= $c->id ?>/edit" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="text-center py-5">
                                <i class="bi bi-people display-3 text-muted"></i>
                                <h5 class="mt-3 text-muted">No customers found</h5>
                                <p class="text-muted">Try adjusting your filters or add a new customer.</p>
                                <a href="<?= BASE_URL ?>/admin/customers/create" class="btn btn-primary admin-btn mt-2">
                                    <i class="bi bi-plus-lg me-1"></i> Add Customer
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
            Showing <?= (($pagination->page - 1) * $pagination->perPage) + 1 ?> to <?= min($pagination->page * $pagination->perPage, $pagination->total) ?> of <?= number_format($pagination->total) ?> customers
        </small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&type=<?= $filters['type'] ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php for ($p = max(1, $pagination->page - 2); $p <= min($pagination->totalPages, $pagination->page + 2); $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&type=<?= $filters['type'] ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&type=<?= $filters['type'] ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
