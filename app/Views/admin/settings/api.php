<!-- ============================================================
     API SETTINGS - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-key me-2"></i>API Settings</h4>
        <small class="text-muted">Manage API keys and integration endpoints</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/settings" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> All Settings
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Generate New Key -->
        <div class="admin-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;color:#fff;">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <h6 class="fw-bold mb-0">Generate New API Key</h6>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/api-settings/generate-key" class="d-flex gap-2">
                <?= csrf_field() ?>
                <div class="input-group flex-grow-1">
                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="Key name (e.g., Mobile App, Web Integration)" required>
                </div>
                <button type="submit" class="btn btn-primary admin-btn">
                    <i class="bi bi-plus-lg me-1"></i> Generate Key
                </button>
            </form>
        </div>

        <!-- API Keys List -->
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i>Active API Keys</h6>
                <span class="badge bg-primary"><?= count($apiKeys) ?> key<?= count($apiKeys) != 1 ? 's' : '' ?></span>
            </div>

            <?php if (!empty($apiKeys)): ?>
                <?php foreach ($apiKeys as $k): ?>
                <div class="api-key-card p-3 mb-3 rounded-3" style="background:#f8f9fa;border:1px solid #e9ecef;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;color:#fff;">
                                <i class="bi bi-key"></i>
                            </div>
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($k->description) ?></div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <code class="small" style="background:#fff;padding:2px 8px;border-radius:4px;border:1px solid #e9ecef;">
                                        <?= htmlspecialchars(substr($k->value, 0, 12)) ?>...<?= htmlspecialchars(substr($k->value, -6)) ?>
                                    </code>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyKey('<?= htmlspecialchars($k->value) ?>', this)" title="Copy full key">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <small class="text-muted mt-1 d-block">Created: <?= format_date($k->created_at ?? $k->updated_at, 'M d, Y H:i') ?></small>
                            </div>
                        </div>
                        <form method="POST" action="<?= BASE_URL ?>/admin/api-settings/<?= $k->id ?>/delete" onsubmit="return confirm('Are you sure you want to delete this API key? This cannot be undone.')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete key">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-key" style="font-size:2.5rem;opacity:0.3;"></i>
                    </div>
                    <h6 class="fw-bold mb-1">No API Keys</h6>
                    <p class="text-muted small mb-0">Generate your first API key to get started with integrations.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- API Documentation -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-book me-2"></i>API Documentation</h6>
            <div class="mb-3">
                <small class="text-muted d-block fw-semibold mb-1">Base URL</small>
                <div class="bg-light p-2 rounded">
                    <code class="small"><?= BASE_URL ?>/api/v1</code>
                </div>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block fw-semibold mb-1">Authentication</small>
                <div class="bg-light p-2 rounded">
                    <code class="small">Authorization: Bearer {api_key}</code>
                </div>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block fw-semibold mb-1">Rate Limiting</small>
                <p class="small mb-0">1000 requests per hour per API key.</p>
            </div>
        </div>

        <!-- Available Endpoints -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-2"></i>Available Endpoints</h6>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success" style="font-size:0.65rem;width:36px;">GET</span>
                    <code class="small">/tracking/{number}</code>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary" style="font-size:0.65rem;width:36px;">POST</span>
                    <code class="small">/auth/login</code>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success" style="font-size:0.65rem;width:36px;">GET</span>
                    <code class="small">/shipments</code>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary" style="font-size:0.65rem;width:36px;">POST</span>
                    <code class="small">/driver/location</code>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success" style="font-size:0.65rem;width:36px;">GET</span>
                    <code class="small">/customers</code>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary" style="font-size:0.65rem;width:36px;">POST</span>
                    <code class="small">/webhooks</code>
                </div>
            </div>
        </div>

        <!-- Security Tips -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2"></i>Security Tips</h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Keep API keys confidential</small></li>
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Rotate keys periodically</small></li>
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Use HTTPS only</small></li>
                <li class="mb-0"><small><i class="bi bi-check2 text-success me-2"></i>Monitor API usage regularly</small></li>
            </ul>
        </div>
    </div>
</div>

<style>
    .api-key-card { transition: all 0.2s; }
    .api-key-card:hover { border-color: #198754 !important; background: #fff !important; }
</style>

<script>
function copyKey(key, btn) {
    navigator.clipboard.writeText(key).then(function() {
        btn.innerHTML = '<i class="bi bi-check-lg"></i>';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        setTimeout(function() {
            btn.innerHTML = '<i class="bi bi-clipboard"></i>';
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
