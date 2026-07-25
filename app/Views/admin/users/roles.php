<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="bi bi-lock me-2"></i>Roles & Permissions</h5>
    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary admin-btn admin-btn-sm"><i class="bi bi-arrow-left me-1"></i> Users</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card card border-start border-primary border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#4a6cf7,#6a8cff);width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
                    <i class="bi bi-shield"></i>
                </div>
                <div>
                    <div class="stat-label">Total Roles</div>
                    <div class="stat-value text-primary" style="font-size:1.5rem;"><?= count($roles) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card border-start border-warning border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#ffc107,#ffcd38);width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
                    <i class="bi bi-lock-fill"></i>
                </div>
                <div>
                    <div class="stat-label">System Roles</div>
                    <div class="stat-value text-warning" style="font-size:1.5rem;"><?= count(array_filter($roles, fn($r) => $r->is_system)) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card border-start border-info border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#17a2b8,#45b8d4);width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
                    <i class="bi bi-key"></i>
                </div>
                <div>
                    <div class="stat-label">Permissions</div>
                    <div class="stat-value text-info" style="font-size:1.5rem;"><?= count($permissionsByGroup) ?> groups</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-shield me-2"></i>Roles</h6>
            <div class="vstack gap-2">
                <?php foreach ($roles as $r): ?>
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border <?= $r->is_system ? 'border-warning' : 'border-light' ?>" style="background:<?= $r->is_system ? '#fff9e6' : '#fafbfc' ?>;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:40px;height:40px;border-radius:10px;background:<?= $r->is_system ? 'linear-gradient(135deg,#ffc107,#ffcd38)' : 'linear-gradient(135deg,#6c757d,#8a9199)' ?>;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.85rem;font-weight:700;">
                            <?= strtoupper(substr($r->name, 0, 2)) ?>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:0.92rem;">
                                <?= htmlspecialchars($r->name) ?>
                                <?php if ($r->is_system): ?>
                                <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem;"><i class="bi bi-lock-fill me-1"></i>System</span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted"><?= $r->user_count ?> users &middot; <?= $r->permission_count ?> permissions</small>
                        </div>
                    </div>
                    <?php if (!$r->is_system): ?>
                    <button class="btn btn-sm btn-outline-primary" onclick="editRolePermissions(<?= $r->id ?>, '<?= htmlspecialchars(addslashes($r->name)) ?>')" title="Edit Permissions"><i class="bi bi-key"></i></button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2"></i>Create New Role</h6>
            <form method="POST" action="<?= BASE_URL ?>/admin/roles">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Logistics Manager" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Optional description">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Permissions</label>
                    <div style="max-height:250px;overflow-y:auto;border:1px solid #e9ecef;border-radius:8px;padding:12px;">
                        <?php foreach ($permissionsByGroup as $group => $perms): ?>
                        <div class="mb-2">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <input type="checkbox" class="form-check-input group-check" data-group="<?= htmlspecialchars($group) ?>" id="grp_<?= md5($group) ?>">
                                <label class="fw-semibold text-muted" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;" for="grp_<?= md5($group) ?>"><?= htmlspecialchars($group) ?></label>
                            </div>
                            <div class="ms-3">
                                <?php foreach ($perms as $p): ?>
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" class="form-check-input perm-check" data-group="<?= htmlspecialchars($group) ?>" value="<?= $p->id ?>" id="perm_<?= $p->id ?>">
                                    <label class="form-check-label small" for="perm_<?= $p->id ?>"><?= htmlspecialchars($p->name) ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary admin-btn admin-btn-sm w-100"><i class="bi bi-plus-lg me-1"></i> Create Role</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2"></i>Available Permissions</h6>
            <?php foreach ($permissionsByGroup as $group => $perms): ?>
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:8px;height:8px;border-radius:2px;background:var(--admin-primary, #1a237e);"></div>
                    <h6 class="text-uppercase fw-bold mb-0" style="font-size:0.78rem;letter-spacing:0.5px;color:#495057;"><?= htmlspecialchars($group) ?></h6>
                    <span class="badge bg-secondary rounded-pill" style="font-size:0.65rem;"><?= count($perms) ?></span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($perms as $p): ?>
                    <span class="badge bg-light text-dark border px-3 py-2" style="font-size:0.8rem;border-color:#dee2e6 !important;"><?= htmlspecialchars($p->name) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.group-check').forEach(function(cb) {
    cb.addEventListener('change', function() {
        const group = this.dataset.group;
        document.querySelectorAll('.perm-check[data-group="' + group + '"]').forEach(function(p) {
            p.checked = cb.checked;
        });
    });
});

function editRolePermissions(roleId, roleName) {
    document.getElementById('editRoleId').value = roleId;
    document.getElementById('editRoleName').textContent = roleName;

    // Reset all checkboxes
    document.querySelectorAll('#editPermissionsForm .perm-check').forEach(function(cb) {
        cb.checked = false;
    });

    // Fetch current permissions for this role
    fetch('<?= BASE_URL ?>/admin/roles/' + roleId + '/permissions', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data) {
            data.data.forEach(function(permId) {
                var cb = document.querySelector('#editPermissionsForm .perm-check[value="' + permId + '"]');
                if (cb) cb.checked = true;
            });
        }
        new bootstrap.Modal(document.getElementById('editPermissionsModal')).show();
    })
    .catch(() => {
        // If GET fails, just open modal
        new bootstrap.Modal(document.getElementById('editPermissionsModal')).show();
    });
}

function submitRolePermissions() {
    var roleId = document.getElementById('editRoleId').value;
    var perms = [];
    document.querySelectorAll('#editPermissionsForm .perm-check:checked').forEach(function(cb) {
        perms.push(cb.value);
    });

    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

    fetch('<?= BASE_URL ?>/admin/roles/' + roleId + '/permissions', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
        body: '_csrf_token=' + encodeURIComponent(csrf) + '&' + perms.map(function(p) { return 'permissions[]=' + p; }).join('&')
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            bootstrap.Modal.getInstance(document.getElementById('editPermissionsModal')).hide();
            showToast('Permissions updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(r.message || 'Error updating permissions', 'danger');
        }
    })
    .catch(() => showToast('Request failed', 'danger'));
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-' + type + ' alert-dismissible fade show position-fixed';
    toast.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:280px;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
    toast.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>

<!-- Edit Permissions Modal -->
<div class="modal fade" id="editPermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><i class="bi bi-key me-2"></i>Edit Permissions: <span id="editRoleName"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editPermissionsForm">
                    <input type="hidden" id="editRoleId" value="">
                    <div style="max-height:400px;overflow-y:auto;">
                        <?php foreach ($permissionsByGroup as $group => $perms): ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input type="checkbox" class="form-check-input edit-group-check" data-group="<?= htmlspecialchars($group) ?>" id="edit_grp_<?= md5($group) ?>">
                                <label class="fw-semibold" style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.5px;" for="edit_grp_<?= md5($group) ?>"><?= htmlspecialchars($group) ?></label>
                            </div>
                            <div class="ms-3 d-flex flex-wrap gap-2">
                                <?php foreach ($perms as $p): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input perm-check" data-group="<?= htmlspecialchars($group) ?>" value="<?= $p->id ?>" id="edit_perm_<?= $p->id ?>">
                                    <label class="form-check-label small" for="edit_perm_<?= $p->id ?>"><?= htmlspecialchars($p->name) ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="submitRolePermissions()"><i class="bi bi-save me-1"></i> Save Permissions</button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.edit-group-check').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var group = this.dataset.group;
        document.querySelectorAll('.perm-check[data-group="' + group + '"]').forEach(function(p) {
            p.checked = cb.checked;
        });
    });
});
</script>
