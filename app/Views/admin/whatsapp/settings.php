<!-- WhatsApp Settings -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1"><i class="bi bi-gear me-2"></i>WhatsApp Settings</h4><small class="text-muted">Configure Evolution API connection</small></div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="<?= BASE_URL ?>/admin/whatsapp/settings">
            <!-- API Configuration -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#25D366,#128C7E);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-plug"></i></div>
                    <h6 class="fw-bold mb-0">API Configuration</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Evolution API URL *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-link"></i></span>
                            <input type="url" name="api_url" class="form-control" value="<?= htmlspecialchars($settings->api_url ?? '') ?>" required placeholder="https://your-evolution-api.com">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Timeout (seconds)</label>
                        <input type="number" name="timeout" class="form-control" value="<?= $settings->timeout ?? 30 ?>" min="5" max="120">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">API Key *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" name="api_key" class="form-control" value="<?= htmlspecialchars($settings->api_key ?? '') ?>" required placeholder="Your Evolution API key">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass(this)"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Default Instance</label>
                        <input type="text" name="default_instance" class="form-control" value="<?= htmlspecialchars($settings->default_instance ?? '') ?>" placeholder="e.g., business-main">
                    </div>
                </div>
            </div>

            <!-- Webhook Configuration -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-arrow-repeat"></i></div>
                    <h6 class="fw-bold mb-0">Webhook Configuration</h6>
                </div>

                <!-- Environment Detection -->
                <div class="alert <?= is_local() ? 'alert-warning' : 'alert-success' ?> d-flex align-items-center mb-3" style="border-radius:10px;">
                    <i class="bi <?= is_local() ? 'bi-laptop' : 'bi-cloud' ?> me-2 fs-5"></i>
                    <div>
                        <strong><?= $envLabel ?></strong> environment detected
                        <?php if (is_local()): ?>
                        — Using ngrok for webhook callbacks
                        <?php else: ?>
                        — Using production domain
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Webhook URL</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-globe"></i></span>
                            <input type="url" name="webhook_url" class="form-control" value="<?= htmlspecialchars($autoWebhookUrl ?: ($settings->webhook_url ?? '')) ?>" readonly style="background:#f8f9fa;">
                            <button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText(this.previousElementSibling.value);this.innerHTML='<i class=\'bi bi-check\'></i>';setTimeout(()=>this.innerHTML='<i class=\'bi bi-clipboard\'></i>',2000)" title="Copy"><i class="bi bi-clipboard"></i></button>
                        </div>
                        <small class="text-muted">
                            Auto-detected from environment:
                            <?php if (is_local()): ?>
                                <code>NGROK_URL</code> + <code>/api/webhooks/evolution</code>
                            <?php else: ?>
                                <code>PRODUCTION_URL</code> + <code>/api/webhooks/evolution</code>
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Webhook Secret</label>
                        <input type="text" name="webhook_secret" class="form-control" value="<?= htmlspecialchars($settings->webhook_secret ?? '') ?>" placeholder="Optional secret key">
                    </div>
                </div>

                <?php if (is_local() && empty(env('NGROK_URL', ''))): ?>
                <div class="alert alert-danger mt-3 mb-0 d-flex align-items-center" style="border-radius:10px;">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <div>
                        <strong>ngrok URL not configured!</strong> Set <code>NGROK_URL</code> in your <code>.env</code> file.
                        <br><small>Run <code>ngrok http 80</code> to get your ngrok URL, then add it to <code>.env</code> as <code>NGROK_URL=https://your-ngrok-url.ngrok-free.dev</code></small>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Options -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#ffc107,#e0a800);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-sliders"></i></div>
                    <h6 class="fw-bold mb-0">Options</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-check form-switch"><input type="checkbox" name="auto_retry" class="form-check-input" value="1" <?= ($settings->auto_retry ?? 1) ? 'checked' : '' ?>><label class="form-check-label fw-semibold">Auto Retry</label></div>
                        <small class="text-muted">Retry failed messages</small>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch"><input type="checkbox" name="enable_logs" class="form-check-input" value="1" <?= ($settings->enable_logs ?? 1) ? 'checked' : '' ?>><label class="form-check-label fw-semibold">Enable Logs</label></div>
                        <small class="text-muted">Log all API requests</small>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch"><input type="checkbox" name="enable_notifications" class="form-check-input" value="1" <?= ($settings->enable_notifications ?? 1) ? 'checked' : '' ?>><label class="form-check-label fw-semibold">Notifications</label></div>
                        <small class="text-muted">Auto-send on events</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success admin-btn"><i class="bi bi-save me-1"></i> Save Settings</button>
                <button type="button" class="btn btn-outline-primary admin-btn" onclick="testConnection()"><i class="bi bi-plug me-1"></i> Test Connection</button>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Quick Setup</h6>
            <ol class="small mb-0">
                <li class="mb-2">Deploy Evolution API server (Docker/VPS)</li>
                <li class="mb-2">Enter API URL and Key above</li>
                <li class="mb-2">Set Webhook URL to: <code><?= BASE_URL ?>/api/webhooks/evolution</code></li>
                <li class="mb-2">Test Connection</li>
                <li class="mb-0">Create an instance and scan QR</li>
            </ol>
        </div>
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-plug me-2"></i>Connection Test</h6>
            <div id="connectionResult" class="text-muted small">Click "Test Connection" to verify your API settings.</div>
        </div>
    </div>
</div>

<script>
function togglePass(btn) {
    var input = btn.parentElement.querySelector('input');
    if (input.type === 'password') { input.type = 'text'; btn.innerHTML = '<i class="bi bi-eye-slash"></i>'; }
    else { input.type = 'password'; btn.innerHTML = '<i class="bi bi-eye"></i>'; }
}
function testConnection() {
    var el = document.getElementById('connectionResult');
    el.innerHTML = '<div class="spinner-border spinner-border-sm"></div> Testing...';
    fetch('<?= BASE_URL ?>/admin/whatsapp/test-connection')
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            el.innerHTML = '<div class="alert alert-success py-2 mb-0"><i class="bi bi-check-circle me-1"></i>' + r.message + ' (' + (r.instances?.length || 0) + ' instances found)</div>';
        } else {
            el.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="bi bi-x-circle me-1"></i>' + r.message + '</div>';
        }
    })
    .catch(() => { el.innerHTML = '<div class="alert alert-danger py-2 mb-0">Connection failed</div>'; });
}
</script>
