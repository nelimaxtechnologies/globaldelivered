<!-- ============================================================
     GENERAL SETTINGS - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-building me-2"></i><?= ucfirst(str_replace('_', ' ', $group)) ?> Settings</h4>
        <small class="text-muted">Configure your company and system preferences</small>
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

<!-- Settings Form -->
<div class="admin-card">
    <form method="POST" action="<?= BASE_URL ?>/admin/settings" id="settingsForm">
        <?= csrf_field() ?>
        <input type="hidden" name="group" value="<?= htmlspecialchars($group) ?>">

        <div class="row g-4">
            <?php
            $currentGroup = '';
            foreach ($settings as $i => $s):
                if ($s->group !== $currentGroup):
                    if ($currentGroup !== '') echo '</div></div>';
                    $currentGroup = $s->group;
            ?>
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 mb-3 mt-2">
                    <div style="width:6px;height:24px;border-radius:3px;background:var(--admin-primary,#1a237e);"></div>
                    <h6 class="fw-bold mb-0 text-uppercase" style="font-size:0.8rem;letter-spacing:0.05em;"><?= ucwords(str_replace('_', ' ', $group)) ?> Configuration</h6>
                </div>
                <div class="row g-4">
                <?php endif; ?>

                <div class="col-md-6">
                    <div class="settings-field p-3 rounded-3" style="background:#f8f9fa;">
                        <label class="form-label fw-semibold small">
                            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $s->key))) ?>
                            <?php if ($s->is_system): ?>
                            <span class="badge bg-secondary ms-1" style="font-size:0.6rem;">System</span>
                            <?php endif; ?>
                        </label>

                        <?php if ($s->type === 'boolean'): ?>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-check-input" value="true" <?= ($s->value ?? '') === 'true' ? 'checked' : '' ?> <?= $s->is_system ? 'disabled' : '' ?>>
                            <label class="form-check-label small text-muted"><?= ($s->value ?? '') === 'true' ? 'Enabled' : 'Disabled' ?></label>
                        </div>

                        <?php elseif ($s->type === 'textarea' || ($s->type === 'text' && strlen($s->value ?? '') > 100)): ?>
                        <textarea name="setting_<?= htmlspecialchars($s->key) ?>" class="form-control form-control-sm" rows="3" <?= $s->is_system ? 'disabled' : '' ?>><?= htmlspecialchars($s->value) ?></textarea>

                        <?php elseif ($s->type === 'email'): ?>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-control" value="<?= htmlspecialchars($s->value) ?>" <?= $s->is_system ? 'disabled' : '' ?>>
                        </div>

                        <?php elseif ($s->type === 'number'): ?>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-123"></i></span>
                            <input type="number" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-control" value="<?= htmlspecialchars($s->value) ?>" <?= $s->is_system ? 'disabled' : '' ?>>
                        </div>

                        <?php elseif ($s->key === 'currency'): ?>
                        <select name="setting_<?= htmlspecialchars($s->key) ?>" class="form-select form-select-sm" <?= $s->is_system ? 'disabled' : '' ?>>
                            <?php foreach (['USD','EUR','GBP','NGN','KES'] as $cur): ?>
                            <option value="<?= $cur ?>" <?= $s->value === $cur ? 'selected' : '' ?>><?= $cur ?></option>
                            <?php endforeach; ?>
                        </select>

                        <?php elseif (str_contains($s->key, 'password') || str_contains($s->key, 'secret') || str_contains($s->key, 'token')): ?>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-control" value="<?= htmlspecialchars($s->value) ?>" <?= $s->is_system ? 'disabled' : '' ?>>
                        </div>

                        <?php else: ?>
                        <input type="text" name="setting_<?= htmlspecialchars($s->key) ?>" class="form-control form-control-sm" value="<?= htmlspecialchars($s->value) ?>" <?= $s->is_system ? 'disabled' : '' ?>>
                        <?php endif; ?>

                        <?php if ($s->description): ?>
                        <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1" style="font-size:0.7rem;"></i><?= htmlspecialchars($s->description) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

            <?php if ($i === count($settings) - 1): ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Submit -->
        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <a href="<?= BASE_URL ?>/admin/settings" class="btn btn-outline-secondary admin-btn">Cancel</a>
            <button type="submit" class="btn btn-primary admin-btn" id="saveBtn">
                <i class="bi bi-save me-1"></i> Save Settings
            </button>
        </div>
    </form>
</div>

<style>
    .settings-field { transition: all 0.2s; border: 1px solid transparent; }
    .settings-field:hover { border-color: #dee2e6; background: #fff !important; }
    .status-pill { display:inline-flex;align-items:center;padding:6px 14px;border-radius:20px;border:1px solid #e9ecef;background:#fff;color:#495057;text-decoration:none;font-size:0.82rem;font-weight:600;transition:all 0.2s;white-space:nowrap; }
    .status-pill:hover { background:#f8f9fa;border-color:#dee2e6;transform:translateY(-1px); }
    .status-pill.active { background:var(--admin-primary,#1a237e);color:#fff;border-color:var(--admin-primary,#1a237e); }
</style>

<script>
$('#settingsForm').on('submit', function() {
    $('#saveBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
});
</script>
