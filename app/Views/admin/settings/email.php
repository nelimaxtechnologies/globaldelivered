<!-- ============================================================
     EMAIL SETTINGS - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-envelope me-2"></i>Email Settings</h4>
        <small class="text-muted">Configure SMTP and email notification settings</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/settings" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> All Settings
    </a>
</div>

<!-- Settings Groups Nav -->
<div class="d-flex gap-2 mb-4 flex-wrap">
    <?php
    $allGroups = ['general', 'email', 'tracking', 'payment', 'sms', 'shipping', 'notification'];
    $groupIcons = ['general' => 'bi-building', 'email' => 'bi-envelope', 'tracking' => 'bi-box-seam', 'payment' => 'bi-credit-card', 'sms' => 'bi-chat-dots', 'shipping' => 'bi-truck', 'notification' => 'bi-bell'];
    foreach ($allGroups as $g): ?>
    <a href="<?= BASE_URL ?>/admin/settings/<?= $g ?>" class="status-pill <?= $group === $g ? 'active' : '' ?>">
        <i class="bi <?= $groupIcons[$g] ?? 'bi-gear' ?> me-1"></i> <?= ucfirst($g) ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- SMTP Configuration -->
        <div class="admin-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;color:#fff;">
                    <i class="bi bi-server"></i>
                </div>
                <h6 class="fw-bold mb-0">SMTP Configuration</h6>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/settings" id="emailForm">
                <?= csrf_field() ?>
                <input type="hidden" name="group" value="email">
                <div class="row g-3">
                    <?php foreach ($settings as $s): ?>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">
                            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $s->key))) ?>
                        </label>
                        <?php if (str_contains($s->key, 'password') || str_contains($s->key, 'secret')): ?>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-control" value="<?= htmlspecialchars($s->value) ?>" id="pass_<?= $s->key ?>">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('pass_<?= $s->key ?>', this)"><i class="bi bi-eye"></i></button>
                        </div>
                        <?php elseif ($s->type === 'number'): ?>
                        <input type="number" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-control" value="<?= htmlspecialchars($s->value) ?>">
                        <?php elseif ($s->type === 'boolean'): ?>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-check-input" value="true" <?= ($s->value ?? '') === 'true' ? 'checked' : '' ?>>
                        </div>
                        <?php else: ?>
                        <input type="text" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-control" value="<?= htmlspecialchars($s->value) ?>">
                        <?php endif; ?>
                        <?php if ($s->description): ?>
                        <small class="text-muted"><?= htmlspecialchars($s->description) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <a href="<?= BASE_URL ?>/admin/settings" class="btn btn-outline-secondary admin-btn">Cancel</a>
                    <button type="submit" class="btn btn-primary admin-btn">
                        <i class="bi bi-save me-1"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Test Email -->
        <div class="admin-card">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:#fff;">
                    <i class="bi bi-send"></i>
                </div>
                <h6 class="fw-bold mb-0">Test Email Configuration</h6>
            </div>
            <p class="text-muted small mb-3">Send a test email to verify your SMTP settings are working correctly.</p>
            <form method="POST" action="<?= BASE_URL ?>/admin/settings/test-email" class="d-flex gap-2">
                <?= csrf_field() ?>
                <div class="input-group flex-grow-1">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Enter email address..." required>
                </div>
                <button type="submit" class="btn btn-success admin-btn">
                    <i class="bi bi-send me-1"></i> Send Test
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>SMTP Help</h6>
            <div class="mb-3">
                <small class="text-muted d-block fw-semibold mb-1">Common SMTP Servers</small>
                <div class="bg-light p-2 rounded small">
                    <div class="mb-1"><strong>Gmail:</strong> smtp.gmail.com:587</div>
                    <div class="mb-1"><strong>Outlook:</strong> smtp.office365.com:587</div>
                    <div class="mb-1"><strong>Yahoo:</strong> smtp.mail.yahoo.com:587</div>
                    <div><strong>SendGrid:</strong> smtp.sendgrid.net:587</div>
                </div>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block fw-semibold mb-1">Port Numbers</small>
                <div class="bg-light p-2 rounded small">
                    <div class="mb-1"><strong>587</strong> — TLS (recommended)</div>
                    <div class="mb-1"><strong>465</strong> — SSL</div>
                    <div><strong>25</strong> — Unencrypted (not recommended)</div>
                </div>
            </div>
            <div>
                <small class="text-muted d-block fw-semibold mb-1">Security</small>
                <p class="small mb-0">Always use TLS encryption for production email. Never use port 25 for sending.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .status-pill { display:inline-flex;align-items:center;padding:6px 14px;border-radius:20px;border:1px solid #e9ecef;background:#fff;color:#495057;text-decoration:none;font-size:0.82rem;font-weight:600;transition:all 0.2s;white-space:nowrap; }
    .status-pill:hover { background:#f8f9fa;border-color:#dee2e6;transform:translateY(-1px); }
    .status-pill.active { background:var(--admin-primary,#1a237e);color:#fff;border-color:var(--admin-primary,#1a237e); }
</style>

<script>
function togglePass(id, btn) {
    var input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="bi bi-eye"></i>';
    }
}
</script>
