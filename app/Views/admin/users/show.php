<div class="card border-0 shadow-sm mb-4 overflow-hidden">
    <div class="card-body p-0">
        <div style="background: linear-gradient(135deg, #1a237e, #283593); padding: 30px 35px; position: relative;">
            <div class="d-flex align-items-center gap-4">
                <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.15);border:3px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.8rem;font-weight:700;flex-shrink:0;">
                    <?= strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) ?>
                </div>
                <div class="text-white">
                    <h4 class="fw-bold mb-1"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></h4>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($user->email) ?></span>
                        <?php if ($user->phone): ?>
                        <span><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($user->phone) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="ms-auto text-end">
                    <span class="badge bg-<?= $user->role_slug === 'super_admin' ? 'danger' : ($user->role_slug === 'admin' ? 'primary' : ($user->role_slug === 'branch_manager' ? 'info' : 'success')) ?> fs-6 px-3 py-2">
                        <?= htmlspecialchars($user->role_name) ?>
                    </span>
                    <div class="mt-2">
                        <span class="badge bg-<?= $user->is_active ? 'success' : 'secondary' ?> rounded-pill">
                            <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i><?= $user->is_active ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 bg-light">
            <div class="d-flex gap-4 flex-wrap">
                <a href="<?= BASE_URL ?>/admin/users/<?= $user->id ?>/edit" class="btn btn-primary btn-sm admin-btn"><i class="bi bi-pencil me-1"></i> Edit</a>
                <?php if ($user->id !== (int)($_SESSION['user_id'] ?? 0)): ?>
                <button class="btn btn-outline-warning btn-sm admin-btn" onclick="showResetPasswordModal(<?= $user->id ?>)"><i class="bi bi-key me-1"></i> Reset Password</button>
                <button class="btn btn-outline-info btn-sm admin-btn" onclick="confirmAction('Impersonate','Log in as <?= htmlspecialchars(addslashes($user->first_name)) ?>?',function(){ window.location='<?= BASE_URL ?>/admin/users/<?= $user->id ?>/impersonate'; })"><i class="bi bi-person-check me-1"></i> Impersonate</button>
                <button class="btn btn-outline-danger btn-sm admin-btn" onclick="deleteUser(<?= $user->id ?>, '<?= htmlspecialchars(addslashes($user->first_name . ' ' . $user->last_name)) ?>')"><i class="bi bi-trash me-1"></i> Delete</button>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary btn-sm admin-btn ms-auto"><i class="bi bi-arrow-left me-1"></i> Back to Users</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card card border-start border-primary border-4 shadow-sm">
            <div class="card-body text-center py-3">
                <i class="bi bi-calendar-check text-primary fs-4"></i>
                <div class="fw-bold fs-5 mt-1"><?= $user->created_at ? floor((time() - strtotime($user->created_at)) / 86400) : 0 ?>d</div>
                <small class="text-muted">Account Age</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card border-start border-info border-4 shadow-sm">
            <div class="card-body text-center py-3">
                <i class="bi bi-activity text-info fs-4"></i>
                <div class="fw-bold fs-5 mt-1"><?= (int)$user->total_actions ?></div>
                <small class="text-muted">Total Actions</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card border-start border-warning border-4 shadow-sm">
            <div class="card-body text-center py-3">
                <i class="bi bi-clock-history text-warning fs-4"></i>
                <div class="fw-bold fs-5 mt-1"><?= $user->last_login_at ? time_ago($user->last_login_at) : 'Never' ?></div>
                <small class="text-muted">Last Login</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card border-start border-<?= $user->is_active ? 'success' : 'danger' ?> border-4 shadow-sm">
            <div class="card-body text-center py-3">
                <i class="bi bi-person-<?= $user->is_active ? 'check' : 'x' ?> text-<?= $user->is_active ? 'success' : 'danger' ?> fs-4"></i>
                <div class="fw-bold fs-5 mt-1"><?= $user->is_active ? 'Active' : 'Inactive' ?></div>
                <small class="text-muted">Account Status</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lines-fill me-2"></i>Account Details</h6>
            <div class="vstack gap-3">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted"><i class="bi bi-shield me-2"></i>Role</span>
                    <span class="fw-semibold">
                        <span class="badge bg-<?= $user->role_slug === 'super_admin' ? 'danger' : ($user->role_slug === 'admin' ? 'primary' : 'info') ?> rounded-pill"><?= htmlspecialchars($user->role_name) ?></span>
                    </span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted"><i class="bi bi-building me-2"></i>Branch</span>
                    <span class="fw-semibold"><?= htmlspecialchars($user->branch_name ?? 'None') ?></span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted"><i class="bi bi-envelope me-2"></i>Email</span>
                    <span class="fw-semibold"><?= htmlspecialchars($user->email) ?></span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted"><i class="bi bi-telephone me-2"></i>Phone</span>
                    <span class="fw-semibold"><?= htmlspecialchars($user->phone ?? '—') ?></span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted"><i class="bi bi-calendar-plus me-2"></i>Created</span>
                    <span class="fw-semibold"><?= $user->created_at ? date('M j, Y', strtotime($user->created_at)) : '—' ?></span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted"><i class="bi bi-calendar-check me-2"></i>Last Updated</span>
                    <span class="fw-semibold"><?= $user->updated_at ? time_ago($user->updated_at) : '—' ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Recent Activity</h6>
            <?php if (!empty($recentActivity)): ?>
            <div style="max-height:320px;overflow-y:auto;">
                <?php foreach ($recentActivity as $i => $log): ?>
                <div class="d-flex gap-3 <?= $i < count($recentActivity) - 1 ? 'pb-3 mb-3 border-bottom' : '' ?>">
                    <div style="width:8px;height:8px;border-radius:50%;background:<?= strpos($log->action, 'deleted') !== false ? '#dc3545' : (strpos($log->action, 'created') !== false ? '#28a745' : (strpos($log->action, 'updated') !== false ? '#ffc107' : '#4a6cf7')) ?>;margin-top:7px;flex-shrink:0;"></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars(str_replace('_', ' ', ucfirst($log->action))) ?></div>
                        <small class="text-muted"><?= htmlspecialchars($log->entity_type ?? '') ?> #<?= $log->entity_id ?></small>
                    </div>
                    <small class="text-muted text-nowrap"><i class="bi bi-clock me-1"></i><?= time_ago($log->created_at) ?></small>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-4 text-muted">
                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                <small>No activity recorded yet</small>
            </div>
            <?php endif; ?>
        </div>

        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-laptop me-2"></i>Active Sessions</h6>
            <?php if (!empty($sessions)): ?>
                <?php foreach ($sessions as $sess): ?>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:40px;height:40px;border-radius:10px;background:#f0f2f5;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-<?= strpos(strtolower($sess->device_type ?? ''), 'mobile') !== false ? 'phone' : 'laptop' ?> text-muted"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars($sess->device_type ?? 'Unknown Device') ?></div>
                            <small class="text-muted">IP: <?= htmlspecialchars($sess->ip_address) ?></small>
                        </div>
                    </div>
                    <small class="text-muted text-nowrap"><i class="bi bi-clock me-1"></i><?= time_ago($sess->last_activity) ?></small>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="text-center py-4 text-muted">
                <i class="bi bi-laptop fs-3 d-block mb-2"></i>
                <small>No active sessions</small>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><i class="bi bi-key me-2"></i>Reset Password</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="resetPasswordForm">
                    <input type="hidden" id="resetUserId" value="">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" minlength="8" required placeholder="Min. 8 characters">
                            <button class="btn btn-outline-secondary" type="button" onclick="generatePassword()"><i class="bi bi-magic"></i></button>
                        </div>
                        <div class="form-text">Password must be at least 8 characters long.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirmPassword" minlength="8" required placeholder="Re-enter password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning btn-sm" onclick="submitResetPassword()"><i class="bi bi-check-lg me-1"></i> Reset Password</button>
            </div>
        </div>
    </div>
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

function showResetPasswordModal(userId) {
    document.getElementById('resetUserId').value = userId;
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
    new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
}

function generatePassword() {
    const chars = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%';
    let pass = '';
    for (let i = 0; i < 16; i++) pass += chars[Math.floor(Math.random() * chars.length)];
    document.getElementById('newPassword').value = pass;
    document.getElementById('confirmPassword').value = pass;
}

function submitResetPassword() {
    const userId = document.getElementById('resetUserId').value;
    const password = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmPassword').value;
    
    if (password.length < 8) { alert('Password must be at least 8 characters.'); return; }
    if (password !== confirm) { alert('Passwords do not match.'); return; }
    
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    fetch('<?= BASE_URL ?>/admin/users/' + userId + '/reset-password', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
        body: '_csrf_token=' + encodeURIComponent(csrf) + '&password=' + encodeURIComponent(password)
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
            alert('Password reset successfully!');
        } else {
            alert(r.message || 'Error resetting password');
        }
    })
    .catch(() => alert('Request failed'));
}
</script>
