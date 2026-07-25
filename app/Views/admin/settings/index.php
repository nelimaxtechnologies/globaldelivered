<!-- ============================================================
     SETTINGS DASHBOARD - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">System Settings</h4>
        <small class="text-muted">Configure your system preferences and integrations</small>
    </div>
</div>

<!-- System Overview Stats -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                    <i class="bi bi-gear"></i>
                </div>
                <div>
                    <div class="card-value"><?= $totalSettings ?></div>
                    <div class="card-label">Total Settings</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #198754, #146c43);">
                    <i class="bi bi-key"></i>
                </div>
                <div>
                    <div class="card-value"><?= $totalApiKeys ?></div>
                    <div class="card-label">API Keys</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
                    <i class="bi bi-list-check"></i>
                </div>
                <div>
                    <div class="card-value"><?= number_format($totalAuditLogs) ?></div>
                    <div class="card-label">Audit Logs</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3">
                <div class="card-stat-icon" style="background: linear-gradient(135deg, #fd7e14, #e0a800);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <div class="card-value" style="font-size:1rem;"><?= $lastUpdated ? format_date($lastUpdated, 'M d, H:i') : 'Never' ?></div>
                    <div class="card-label">Last Updated</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Settings Groups -->
    <div class="col-lg-8">
        <h5 class="fw-bold mb-3">Settings Groups</h5>
        <div class="row g-3 mb-4">
            <?php
            $groupConfig = [
                'general' => ['icon' => 'bi-building', 'color' => '#0d6efd', 'desc' => 'Company name, address, timezone'],
                'email' => ['icon' => 'bi-envelope', 'color' => '#198754', 'desc' => 'SMTP, email templates, notifications'],
                'tracking' => ['icon' => 'bi-box-seam', 'color' => '#6f42c1', 'desc' => 'Tracking page, public portal settings'],
                'payment' => ['icon' => 'bi-credit-card', 'color' => '#fd7e14', 'desc' => 'Payment gateways, currency, invoicing'],
                'sms' => ['icon' => 'bi-chat-dots', 'color' => '#20c997', 'desc' => 'SMS provider, message templates'],
                'api' => ['icon' => 'bi-key', 'color' => '#dc3545', 'desc' => 'API keys, rate limiting, webhooks'],
                'shipping' => ['icon' => 'bi-truck', 'color' => '#0dcaf0', 'desc' => 'Shipping zones, rates, carriers'],
                'notification' => ['icon' => 'bi-bell', 'color' => '#ffc107', 'desc' => 'Push notifications, alerts, preferences'],
            ];
            ?>
            <?php foreach ($groups as $g):
                $cfg = $groupConfig[$g->group] ?? ['icon' => 'bi-gear', 'color' => '#6c757d', 'desc' => 'System settings'];
            ?>
            <div class="col-md-6">
                <a href="<?= BASE_URL ?>/admin/settings/<?= htmlspecialchars($g->group) ?>" class="text-decoration-none">
                    <div class="admin-card settings-group-card h-100" style="border-left:4px solid <?= $cfg['color'] ?>;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:48px;border-radius:12px;background:<?= $cfg['color'] ?>15;display:flex;align-items:center;justify-content:center;">
                                <i class="bi <?= $cfg['icon'] ?>" style="color:<?= $cfg['color'] ?>;font-size:1.3rem;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= ucwords(str_replace('_', ' ', $g->group)) ?></div>
                                <small class="text-muted"><?= $cfg['desc'] ?></small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light text-dark"><?= (int)$g->count ?></span>
                                <div><i class="bi bi-chevron-right text-muted"></i></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Links -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Quick Access</h6>
            <div class="d-grid gap-2">
                <a href="<?= BASE_URL ?>/admin/api-settings" class="btn btn-outline-primary admin-btn d-flex align-items-center gap-2">
                    <i class="bi bi-key"></i> API Keys <span class="badge bg-primary ms-auto"><?= $totalApiKeys ?></span>
                </a>
                <a href="<?= BASE_URL ?>/admin/audit-logs" class="btn btn-outline-secondary admin-btn d-flex align-items-center gap-2">
                    <i class="bi bi-list-check"></i> Audit Logs <span class="badge bg-secondary ms-auto"><?= number_format($totalAuditLogs) ?></span>
                </a>
            </div>
        </div>

        <!-- My Profile -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>My Profile</h6>
            <?php if ($currentUser): ?>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;">
                    <?= strtoupper(substr($currentUser->first_name,0,1) . substr($currentUser->last_name,0,1)) ?>
                </div>
                <div>
                    <div class="fw-bold"><?= htmlspecialchars($currentUser->first_name . ' ' . $currentUser->last_name) ?></div>
                    <small class="text-muted"><?= htmlspecialchars($currentUser->email) ?></small>
                    <div><span class="badge bg-primary" style="font-size:0.65rem;"><?= htmlspecialchars($currentUser->role_name) ?></span></div>
                </div>
            </div>
            <?php endif; ?>
            <form method="POST" action="<?= BASE_URL ?>/admin/settings/profile">
                <?= csrf_field() ?>
                <div class="mb-2">
                    <input type="text" name="first_name" class="form-control form-control-sm" placeholder="First Name" value="<?= htmlspecialchars($currentUser->first_name ?? $_SESSION['user_name'] ?? '') ?>" required>
                </div>
                <div class="mb-2">
                    <input type="text" name="last_name" class="form-control form-control-sm" placeholder="Last Name" value="<?= htmlspecialchars($currentUser->last_name ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <input type="tel" name="phone" class="form-control form-control-sm" placeholder="Phone" value="<?= htmlspecialchars($currentUser->phone ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-primary admin-btn w-100">
                    <i class="bi bi-check-lg me-1"></i> Update Profile
                </button>
            </form>
        </div>

        <!-- System Info -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>System Info</h6>
            <div class="summary-row"><small class="text-muted">Version</small><small class="fw-semibold">v2.0 Enterprise</small></div>
            <div class="summary-row"><small class="text-muted">PHP Version</small><small class="fw-semibold"><?= phpversion() ?></small></div>
            <div class="summary-row"><small class="text-muted">Server</small><small class="fw-semibold"><?= php_uname('s') ?></small></div>
            <div class="summary-row"><small class="text-muted">Settings</small><small class="fw-semibold"><?= $totalSettings ?> configured</small></div>
        </div>
    </div>
</div>

<style>
    .settings-group-card { transition: all 0.3s; cursor: pointer; }
    .settings-group-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
    .summary-row { display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f3f5; }
    .summary-row:last-child { border-bottom:none; }
</style>
