<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-primary border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4a6cf7, #6a8cff);">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value text-primary"><?= (int)($stats->total ?? 0) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-success border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #5cb85c);">
                    <i class="bi bi-person-check"></i>
                </div>
                <div>
                    <div class="stat-label">Active</div>
                    <div class="stat-value text-success"><?= (int)($stats->active ?? 0) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-danger border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: linear-gradient(135deg, #dc3545, #e8636f);">
                    <i class="bi bi-person-x"></i>
                </div>
                <div>
                    <div class="stat-label">Inactive</div>
                    <div class="stat-value text-danger"><?= (int)($stats->inactive ?? 0) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-info border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8, #45b8d4);">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div>
                    <div class="stat-label">New This Month</div>
                    <div class="stat-value text-info"><?= (int)($stats->new_this_month ?? 0) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-shield me-2"></i>All Users</h5>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/roles" class="btn btn-outline-primary admin-btn admin-btn-sm"><i class="bi bi-lock me-1"></i> Roles</a>
            <a href="<?= BASE_URL ?>/admin/users/create" class="btn btn-primary admin-btn admin-btn-sm"><i class="bi bi-plus-lg me-1"></i> Add User</a>
        </div>
    </div>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search name, email, phone..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
        </div>
        <div class="col-md-2">
            <select name="role" class="form-select form-select-sm">
                <option value="">All Roles</option>
                <?php foreach ($roles as $r): ?>
                <option value="<?= $r->id ?>" <?= $filters['role'] == $r->id ? 'selected' : '' ?>><?= htmlspecialchars($r->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary admin-btn admin-btn-sm"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
        <div class="col-auto">
            <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary admin-btn admin-btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a>
        </div>
        <?php if (!empty($filters['search']) || !empty($filters['role']) || !empty($filters['status'])): ?>
        <div class="col-auto">
            <div class="d-flex gap-1 flex-wrap">
                <?php if (!empty($filters['search'])): ?>
                <span class="badge bg-primary-subtle text-primary d-flex align-items-center gap-1">
                    Search: <?= htmlspecialchars($filters['search']) ?>
                    <a href="<?= BASE_URL ?>/admin/users?role=<?= $filters['role'] ?>&status=<?= $filters['status'] ?>" class="text-primary text-decoration-none"><i class="bi bi-x"></i></a>
                </span>
                <?php endif; ?>
                <?php if (!empty($filters['role'])): ?>
                <?php $roleName = ''; foreach ($roles as $r) { if ($r->id == $filters['role']) { $roleName = $r->name; break; } } ?>
                <span class="badge bg-primary-subtle text-primary d-flex align-items-center gap-1">
                    Role: <?= htmlspecialchars($roleName) ?>
                    <a href="<?= BASE_URL ?>/admin/users?search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>" class="text-primary text-decoration-none"><i class="bi bi-x"></i></a>
                </span>
                <?php endif; ?>
                <?php if (!empty($filters['status'])): ?>
                <span class="badge bg-primary-subtle text-primary d-flex align-items-center gap-1">
                    Status: <?= ucfirst(htmlspecialchars($filters['status'])) ?>
                    <a href="<?= BASE_URL ?>/admin/users?search=<?= urlencode($filters['search']) ?>&role=<?= $filters['role'] ?>" class="text-primary text-decoration-none"><i class="bi bi-x"></i></a>
                </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table class="table table-admin">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Branch</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/users/<?= $u->id ?>" class="text-decoration-none d-flex align-items-center gap-2">
                                <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg, <?= $u->role_slug === 'super_admin' ? '#dc3545, #e8636f' : ($u->role_slug === 'admin' ? '#4a6cf7, #6a8cff' : ($u->role_slug === 'branch_manager' ? '#17a2b8, #45b8d4' : '#6c757d, #8a9199')) ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:700;flex-shrink:0;position:relative;">
                                    <?= strtoupper(substr($u->first_name, 0, 1) . substr($u->last_name, 0, 1)) ?>
                                    <span style="position:absolute;bottom:0;right:0;width:10px;height:10px;border-radius:50%;border:2px solid #fff;background:<?= $u->is_active ? '#28a745' : '#adb5bd' ?>;"></span>
                                </div>
                                <div>
                                    <span class="fw-semibold text-dark d-block lh-sm"><?= htmlspecialchars($u->first_name . ' ' . $u->last_name) ?></span>
                                    <small class="text-muted">ID: #<?= $u->id ?></small>
                                </div>
                            </a>
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($u->email) ?></small></td>
                        <td>
                            <span class="badge bg-<?= $u->role_slug === 'super_admin' ? 'danger' : ($u->role_slug === 'admin' ? 'primary' : ($u->role_slug === 'branch_manager' ? 'info' : ($u->role_slug === 'warehouse_manager' ? 'secondary' : 'success'))) ?> rounded-pill px-2 py-1">
                                <?= htmlspecialchars($u->role_name) ?>
                            </span>
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($u->branch_name ?? '—') ?></small></td>
                        <td>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                    <?= $u->is_active ? 'checked' : '' ?>
                                    <?= $u->id == (int)($_SESSION['user_id'] ?? 0) ? 'disabled' : '' ?>
                                    onchange="toggleUserStatus(<?= $u->id ?>, this)" 
                                    title="<?= $u->is_active ? 'Active' : 'Inactive' ?>">
                            </div>
                        </td>
                        <td>
                            <?php if ($u->last_login_at): ?>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i><?= time_ago($u->last_login_at) ?></small>
                            <?php else: ?>
                            <small class="text-muted fst-italic">Never</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= BASE_URL ?>/admin/users/<?= $u->id ?>" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/admin/users/<?= $u->id ?>/edit" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <?php if ($u->id !== (int)($_SESSION['user_id'] ?? 0)): ?>
                                <button class="btn btn-outline-danger" onclick="deleteUser(<?= $u->id ?>, '<?= htmlspecialchars(addslashes($u->first_name . ' ' . $u->last_name)) ?>')" title="Delete"><i class="bi bi-trash"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                <h6 class="fw-semibold">No users found</h6>
                                <small>Try adjusting your search filters or <a href="<?= BASE_URL ?>/admin/users/create">create a new user</a>.</small>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination->totalPages > 1): ?>
    <nav class="mt-3">
        <ul class="pagination justify-content-center mb-0">
            <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>&role=<?= $filters['role'] ?>&status=<?= $filters['status'] ?>"><i class="bi bi-chevron-left"></i></a>
            </li>
            <?php for ($p = max(1, $pagination->page - 2); $p <= min($pagination->totalPages, $pagination->page + 2); $p++): ?>
            <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($filters['search']) ?>&role=<?= $filters['role'] ?>&status=<?= $filters['status'] ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>&role=<?= $filters['role'] ?>&status=<?= $filters['status'] ?>"><i class="bi bi-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<script>
function deleteUser(id, name) {
    Swal.fire({
        title: 'Delete User?',
        text: 'Delete "' + name + '"? This cannot be undone.',
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
            form.action = '<?= BASE_URL ?>/admin/users/' + id;
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
}

function toggleUserStatus(id, el) {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    fetch('<?= BASE_URL ?>/admin/users/' + id + '/toggle-status', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
        body: '_csrf_token=' + encodeURIComponent(csrf)
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            showToast(r.message, 'success');
        } else {
            el.checked = !el.checked;
            showToast(r.message || 'Error', 'danger');
        }
    })
    .catch(() => { el.checked = !el.checked; showToast('Request failed', 'danger'); });
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:280px;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
    toast.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
