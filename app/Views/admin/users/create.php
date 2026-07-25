<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="bi bi-person-plus me-2"></i>Create New User</h5>
    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary admin-btn admin-btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Users</a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/users">
    <?= csrf_field() ?>
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Personal Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" required placeholder="John">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" required placeholder="Doe">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+254729373801">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-lock me-2"></i>Security</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="passwordInput" class="form-control" required minlength="8" placeholder="Min. 8 characters">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordInput', this)"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="generateSecurePassword()"><i class="bi bi-magic me-1"></i> Generate Secure Password</button>
                    </div>
                </div>
                <div class="mt-2" id="passwordStrength" style="display:none;">
                    <div class="progress" style="height:4px;">
                        <div class="progress-bar" id="strengthBar" style="width:0%"></div>
                    </div>
                    <small id="strengthText" class="text-muted"></small>
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
                            <option value="<?= $r->id ?>"><?= htmlspecialchars($r->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" class="form-select select2">
                            <option value="">No Branch</option>
                            <?php foreach ($branches as $b): ?>
                            <option value="<?= $b->id ?>"><?= htmlspecialchars($b->name) ?> (<?= htmlspecialchars($b->code) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" checked>
                            <label class="form-check-label fw-semibold" for="isActive">Active Account</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-check-lg me-1"></i> Create User</button>
                    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary admin-btn">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
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
    checkPasswordStrength(pass);
}

document.getElementById('passwordInput')?.addEventListener('input', function() {
    checkPasswordStrength(this.value);
});

function checkPasswordStrength(password) {
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    const container = document.getElementById('passwordStrength');
    if (!bar || !text || !container) return;
    
    container.style.display = password.length > 0 ? 'block' : 'none';
    let strength = 0;
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 25;
    if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) strength += 25;
    
    bar.style.width = strength + '%';
    bar.className = 'progress-bar ' + (strength <= 25 ? 'bg-danger' : (strength <= 50 ? 'bg-warning' : (strength <= 75 ? 'bg-info' : 'bg-success')));
    text.textContent = strength <= 25 ? 'Weak' : (strength <= 50 ? 'Fair' : (strength <= 75 ? 'Strong' : 'Very Strong'));
    text.className = strength <= 25 ? 'text-danger' : (strength <= 50 ? 'text-warning' : (strength <= 75 ? 'text-info' : 'text-success'));
}
</script>
