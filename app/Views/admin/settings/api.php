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

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px; background:linear-gradient(135deg, rgba(13,110,253,0.06) 0%, rgba(13,110,253,0.01) 100%); border-left:3px solid #0d6efd;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#0d6efd,#0b5ed7);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,110,253,0.3);">
                        <i class="bi bi-key text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Keys</div>
                        <div class="fs-4 fw-bold mb-0"><?= count($apiKeys) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px; background:linear-gradient(135deg, rgba(25,135,84,0.06) 0%, rgba(25,135,84,0.01) 100%); border-left:3px solid #198754;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(25,135,84,0.3);">
                        <i class="bi bi-shield-check text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Active</div>
                        <div class="fs-4 fw-bold mb-0"><?= count(array_filter($apiKeys, fn($k) => !empty($k->value))) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px; background:linear-gradient(135deg, rgba(255,193,7,0.06) 0%, rgba(255,193,7,0.01) 100%); border-left:3px solid #ffc107;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#ffc107,#e0a800);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(255,193,7,0.3);">
                        <i class="bi bi-speedometer2 text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Rate Limit</div>
                        <div class="fs-4 fw-bold mb-0">1K<span class="small fw-normal text-muted">/hr</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px; background:linear-gradient(135deg, rgba(108,117,125,0.06) 0%, rgba(108,117,125,0.01) 100%); border-left:3px solid #6c757d;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#6c757d,#565e64);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(108,117,125,0.3);">
                        <i class="bi bi-globe2 text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Base URL</div>
                        <div class="fs-6 fw-bold mb-0" style="letter-spacing:-0.02em;">/api/v1</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Generate New Key -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(25,135,84,0.3);">
                        <i class="bi bi-plus-lg text-white fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Generate New API Key</h6>
                        <small class="text-muted">Create a key for external integrations</small>
                    </div>
                </div>
                <form method="POST" action="<?= BASE_URL ?>/admin/api-settings/generate-key">
                    <?= csrf_field() ?>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius:12px 0 0 12px;background:rgba(13,110,253,0.06);border-color:rgba(13,110,253,0.15);">
                            <i class="bi bi-tag text-primary"></i>
                        </span>
                        <input type="text" name="name" class="form-control py-2" placeholder="Key name (e.g., Mobile App, Web Integration)" required style="border-color:rgba(13,110,253,0.15);">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" style="border-radius:0 12px 12px 0;">
                            <i class="bi bi-plus-lg me-1"></i> Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- API Keys List -->
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#0d6efd,#0b5ed7);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,110,253,0.25);">
                            <i class="bi bi-list-check text-white"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Active API Keys</h6>
                    </div>
                    <span class="badge" style="background:rgba(13,110,253,0.1);color:#0d6efd;font-size:0.75rem;padding:6px 12px;border-radius:8px;"><?= count($apiKeys) ?> key<?= count($apiKeys) != 1 ? 's' : '' ?></span>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($apiKeys)): ?>
                    <?php foreach ($apiKeys as $k): ?>
                    <div class="d-flex justify-content-between align-items-center p-3 mb-2 rounded-3 api-key-item" style="border:1px solid rgba(0,0,0,0.06);transition:all 0.2s;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#0d6efd,#0b5ed7);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,110,253,0.2);">
                                <i class="bi bi-key text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($k->description) ?></div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <code class="small px-2 py-1 rounded" style="background:rgba(0,0,0,0.04);font-size:0.78rem;"><?= htmlspecialchars(substr($k->value, 0, 12)) ?>...<?= htmlspecialchars(substr($k->value, -6)) ?></code>
                                    <button class="btn btn-sm btn-outline-secondary copy-key-btn" onclick="copyKey('<?= htmlspecialchars($k->value) ?>', this)" title="Copy full key" style="border-radius:8px;width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-clipboard" style="font-size:0.8rem;"></i>
                                    </button>
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="bi bi-clock me-1"></i><?= format_date($k->created_at ?? $k->updated_at, 'M d, Y H:i') ?></small>
                            </div>
                        </div>
                        <form method="POST" action="<?= BASE_URL ?>/admin/api-settings/<?= $k->id ?>/delete" onsubmit="return confirm('Are you sure you want to delete this API key? This cannot be undone.')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete key" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,rgba(13,110,253,0.08),rgba(13,110,253,0.02));margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-key" style="font-size:2.5rem;color:rgba(13,110,253,0.3);"></i>
                        </div>
                        <h6 class="fw-bold mb-1">No API Keys Yet</h6>
                        <p class="text-muted small mb-0">Generate your first API key to get started with integrations.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- API Documentation -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#0d6efd,#0b5ed7);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(13,110,253,0.2);">
                        <i class="bi bi-book text-white" style="font-size:0.85rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-0">API Reference</h6>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block fw-semibold mb-1">Base URL</small>
                    <div class="p-2 rounded-2" style="background:rgba(0,0,0,0.04);border:1px solid rgba(0,0,0,0.06);">
                        <code class="small fw-semibold"><?= BASE_URL ?>/api/v1</code>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block fw-semibold mb-1">Authentication</small>
                    <div class="p-2 rounded-2" style="background:rgba(0,0,0,0.04);border:1px solid rgba(0,0,0,0.06);">
                        <code class="small fw-semibold">Authorization: Bearer {api_key}</code>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block fw-semibold mb-1">Rate Limiting</small>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="height:6px;border-radius:3px;">
                            <div class="progress-bar" role="progressbar" style="width:30%;background:linear-gradient(90deg,#198754,#ffc107);border-radius:3px;"></div>
                        </div>
                        <small class="text-muted fw-semibold">1,000/hr</small>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold mb-1">Response Format</small>
                    <div class="d-flex gap-2">
                        <span class="badge" style="background:rgba(13,110,253,0.1);color:#0d6efd;font-size:0.7rem;padding:4px 10px;border-radius:6px;">JSON</span>
                        <span class="badge" style="background:rgba(25,135,84,0.1);color:#198754;font-size:0.7rem;padding:4px 10px;border-radius:6px;">UTF-8</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Endpoints -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6f42c1,#5b32a3);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(111,66,193,0.25);">
                        <i class="bi bi-diagram-3 text-white" style="font-size:0.85rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Endpoints</h6>
                </div>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:rgba(0,0,0,0.02);">
                        <span class="badge" style="background:#198754;font-size:0.6rem;padding:3px 8px;border-radius:4px;min-width:38px;">GET</span>
                        <code class="small fw-semibold">/tracking/{number}</code>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:rgba(0,0,0,0.02);">
                        <span class="badge" style="background:#0d6efd;font-size:0.6rem;padding:3px 8px;border-radius:4px;min-width:38px;">POST</span>
                        <code class="small fw-semibold">/auth/login</code>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:rgba(0,0,0,0.02);">
                        <span class="badge" style="background:#198754;font-size:0.6rem;padding:3px 8px;border-radius:4px;min-width:38px;">GET</span>
                        <code class="small fw-semibold">/shipments</code>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:rgba(0,0,0,0.02);">
                        <span class="badge" style="background:#0d6efd;font-size:0.6rem;padding:3px 8px;border-radius:4px;min-width:38px;">POST</span>
                        <code class="small fw-semibold">/driver/location</code>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:rgba(0,0,0,0.02);">
                        <span class="badge" style="background:#198754;font-size:0.6rem;padding:3px 8px;border-radius:4px;min-width:38px;">GET</span>
                        <code class="small fw-semibold">/customers</code>
                    </div>
                    <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:rgba(0,0,0,0.02);">
                        <span class="badge" style="background:#0d6efd;font-size:0.6rem;padding:3px 8px;border-radius:4px;min-width:38px;">POST</span>
                        <code class="small fw-semibold">/webhooks</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Tips -->
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#ffc107,#e0a800);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(255,193,7,0.25);">
                        <i class="bi bi-shield-check text-white" style="font-size:0.85rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Security Best Practices</h6>
                </div>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2 p-2 rounded-2 d-flex align-items-center gap-2" style="background:rgba(0,0,0,0.02);">
                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(25,135,84,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-check-lg text-success" style="font-size:0.75rem;"></i>
                        </div>
                        <small class="fw-semibold">Keep API keys confidential</small>
                    </li>
                    <li class="mb-2 p-2 rounded-2 d-flex align-items-center gap-2" style="background:rgba(0,0,0,0.02);">
                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(25,135,84,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-check-lg text-success" style="font-size:0.75rem;"></i>
                        </div>
                        <small class="fw-semibold">Rotate keys periodically</small>
                    </li>
                    <li class="mb-2 p-2 rounded-2 d-flex align-items-center gap-2" style="background:rgba(0,0,0,0.02);">
                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(25,135,84,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-check-lg text-success" style="font-size:0.75rem;"></i>
                        </div>
                        <small class="fw-semibold">Use HTTPS only</small>
                    </li>
                    <li class="mb-0 p-2 rounded-2 d-flex align-items-center gap-2" style="background:rgba(0,0,0,0.02);">
                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(25,135,84,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-check-lg text-success" style="font-size:0.75rem;"></i>
                        </div>
                        <small class="fw-semibold">Monitor API usage regularly</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .api-key-item:hover { border-color: rgba(13,110,253,0.3) !important; background: rgba(13,110,253,0.02); }
    .copy-key-btn:hover { background: #0d6efd !important; color: #fff !important; border-color: #0d6efd !important; }
</style>

<script>
function copyKey(key, btn) {
    navigator.clipboard.writeText(key).then(function() {
        const icon = btn.querySelector('i');
        icon.className = 'bi bi-check-lg';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-secondary');
        setTimeout(function() {
            icon.className = 'bi bi-clipboard';
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
