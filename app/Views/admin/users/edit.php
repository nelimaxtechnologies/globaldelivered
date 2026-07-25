<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2"></i>Edit User: <?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></h5>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/users/<?= $user->id ?>" class="btn btn-outline-primary admin-btn admin-btn-sm"><i class="bi bi-eye me-1"></i> View Profile</a>
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary admin-btn admin-btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card card border-start border-primary border-4 shadow-sm">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-plus text-primary"></i>
                    <div>
                        <small class="text-muted d-block" style="font-size:0.72rem;">Created</small>
                        <span class="fw-semibold" style="font-size:0.85rem;"><?= $user->created_at ? date('M j, Y', strtotime($user->created_at)) : '—' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card border-start border-warning border-4 shadow-sm">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-warning"></i>
                    <div>
                        <small class="text-muted d-block" style="font-size:0.72rem;">Last Login</small>
                        <span class="fw-semibold" style="font-size:0.85rem;"><?= $user->last_login_at ? time_ago($user->last_login_at) : 'Never' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card border-start border-info border-4 shadow-sm">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-activity text-info"></i>
                    <div>
                        <small class="text-muted d-block" style="font-size:0.72rem;">Total Actions</small>
                        <span class="fw-semibold" style="font-size:0.85rem;"><?= (int)($user->total_actions ?? 0) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card border-start border-<?= $user->is_active ? 'success' : 'danger' ?> border-4 shadow-sm">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-<?= $user->is_active ? 'check-circle text-success' : 'x-circle text-danger' ?>"></i>
                    <div>
                        <small class="text-muted d-block" style="font-size:0.72rem;">Status</small>
                        <span class="fw-semibold" style="font-size:0.85rem;"><?= $user->is_active ? 'Active' : 'Inactive' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $user->id ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Personal Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" required value="<?= htmlspecialchars($user->first_name) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" required value="<?= htmlspecialchars($user->last_name) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user->phone ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user->email) ?>">
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-lock me-2"></i>Password Change</h6>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="passwordInput" class="form-control" minlength="8" placeholder="Leave blank to keep current">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordInput', this)"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="generateSecurePassword()"><i class="bi bi-magic me-1"></i> Generate</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-person-gear me-2"></i>Assignment</h6>
                <div class="vstack gap-3">
                    <div>
                        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select select2" required>
                            <?php foreach ($roles as $r): ?>
                            <option value="<?= $r->id ?>" <?= $user->role_id == $r->id ? 'selected' : '' ?>><?= htmlspecialchars($r->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" class="form-select select2">
                            <option value="">No Branch</option>
                            <?php foreach ($branches as $b): ?>
                            <option value="<?= $b->id ?>" <?= $user->branch_id == $b->id ? 'selected' : '' ?>><?= htmlspecialchars($b->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" <?= $user->is_active ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="isActive">Active Account</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-save me-1"></i> Update User</button>
                    <a href="<?= BASE_URL ?>/admin/users/<?= $user->id ?>" class="btn btn-outline-secondary admin-btn">Cancel</a>
                </div>
            </div>

            <?php if ($user->id !== (int)($_SESSION['user_id'] ?? 0)): ?>
            <div class="admin-card border-danger">
                <h6 class="fw-bold mb-2 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h6>
                <p class="text-muted small mb-3">Permanently deactivate this user account. They will no longer be able to log in.</p>
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="deleteUser(<?= $user->id ?>, '<?= htmlspecialchars(addslashes($user->first_name . ' ' . $user->last_name)) ?>')">
                    <i class="bi bi-trash me-1"></i> Delete Account
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

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

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

function generateSecurePassword() {
    const chars = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%&*';
    let pass = '';
    for (let i = 0; i < 16; i++) pass += chars[Math.floor(Math.random() * chars.length)];
    const input = document.getElementById('passwordInput');
    input.value = pass;
    input.type = 'text';
    input.parentElement.querySelector('i').className = 'bi bi-eye-slash';
}
</script>
