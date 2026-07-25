<!-- WhatsApp Instances -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-phone me-2"></i>WhatsApp Instances</h4>
        <small class="text-muted">Manage WhatsApp connections</small>
    </div>
    <button class="btn btn-primary admin-btn" data-bs-toggle="modal" data-bs-target="#createInstanceModal"><i class="bi bi-plus-lg me-1"></i> New Instance</button>
</div>

<!-- QR Code Modal -->
<?php if (!empty($showQR)): ?>
<div class="modal fade" id="qrModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="fw-bold"><i class="bi bi-qr-code me-2"></i>Scan QR Code</h6>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted small">Open WhatsApp on your phone → Settings → Linked Devices → Link a Device</p>
                <?php if ($showQR->qrcode): ?>
                <img src="<?= $showQR->qrcode ?>" alt="QR Code" style="max-width:300px;" class="img-fluid border rounded">
                <?php else: ?>
                <div class="py-5">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-2 text-muted">Waiting for QR code...</p>
                </div>
                <?php endif; ?>
                <p class="text-muted small mt-2">Instance: <strong><?= htmlspecialchars($showQR->instance_name) ?></strong></p>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Modal(document.getElementById('qrModal')).show();
    // Auto-refresh QR every 5 seconds
    var qrInterval = setInterval(function() {
        fetch('<?= BASE_URL ?>/admin/whatsapp/instances/<?= urlencode($showQR->instance_name) ?>/qr-data')
        .then(r => r.json())
        .then(r => {
            if (r.success && r.data && r.data.base64) {
                var img = document.querySelector('#qrModal img');
                if (img) img.src = r.data.base64;
            }
            if (r.data && (r.data.state === 'open' || r.data.state === 'connected')) {
                clearInterval(qrInterval);
                location.reload();
            }
        }).catch(() => {});
    }, 5000);
});
</script>
<?php endif; ?>

<!-- Instances Grid -->
<div class="row g-3">
    <?php if (!empty($instances)): ?>
    <?php foreach ($instances as $inst):
        $statusColors = ['open' => '#198754', 'connecting' => '#ffc107', 'disconnected' => '#dc3545', 'restarting' => '#0dcaf0'];
        $statusColor = $statusColors[$inst->status] ?? '#6c757d';
        $isConnected = $inst->status === 'open';
    ?>
    <div class="col-lg-4 col-md-6">
        <div class="admin-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:40px;height:40px;border-radius:10px;background:<?= $statusColor ?>15;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-phone" style="color:<?= $statusColor ?>;"></i>
                    </div>
                    <div>
                        <div class="fw-bold"><?= htmlspecialchars($inst->display_name ?: $inst->instance_name) ?></div>
                        <small class="text-muted"><?= htmlspecialchars($inst->phone ?: 'No phone') ?></small>
                    </div>
                </div>
                <span class="badge rounded-pill" style="background:<?= $statusColor ?>15;color:<?= $statusColor ?>;border:1px solid <?= $statusColor ?>30;">
                    <?= ucfirst($inst->status) ?>
                </span>
            </div>

            <?php if ($inst->profile_name): ?>
            <div class="mb-2"><small class="text-muted"><i class="bi bi-person me-1"></i><?= htmlspecialchars($inst->profile_name) ?></small></div>
            <?php endif; ?>
            <div class="mb-3"><small class="text-muted"><i class="bi bi-clock me-1"></i>Last seen: <?= $inst->last_seen ? time_ago($inst->last_seen) : 'Never' ?></small></div>

            <div class="d-flex gap-1 flex-wrap">
                <?php if (!$isConnected): ?>
                <a href="<?= BASE_URL ?>/admin/whatsapp/instances/<?= urlencode($inst->instance_name) ?>/connect" class="btn btn-sm btn-success"><i class="bi bi-link-45deg me-1"></i> Connect</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/admin/whatsapp/instances/<?= urlencode($inst->instance_name) ?>/restart" class="btn btn-sm btn-outline-warning" title="Restart"><i class="bi bi-arrow-clockwise"></i></a>
                <a href="<?= BASE_URL ?>/admin/whatsapp/instances/<?= urlencode($inst->instance_name) ?>/logout" class="btn btn-sm btn-outline-secondary" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
                <a href="<?= BASE_URL ?>/admin/whatsapp/instances/<?= urlencode($inst->instance_name) ?>/delete" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this instance?')"><i class="bi bi-trash"></i></a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="col-12">
        <div class="admin-card text-center py-5">
            <i class="bi bi-phone fs-1 d-block mb-2" style="opacity:0.3;"></i>
            <h6 class="fw-bold">No instances yet</h6>
            <p class="text-muted small">Create your first WhatsApp instance to get started.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Create Instance Modal -->
<div class="modal fade" id="createInstanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>/admin/whatsapp/instances/create">
                <div class="modal-header">
                    <h6 class="fw-bold"><i class="bi bi-plus-circle me-2"></i>Create Instance</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Instance Name <span class="text-danger">*</span></label>
                        <input type="text" name="instance_name" class="form-control" required placeholder="e.g., business-main">
                        <small class="text-muted">Unique name for this instance (lowercase, no spaces)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Display Name</label>
                        <input type="text" name="display_name" class="form-control" placeholder="e.g., Business WhatsApp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g., 254700000000">
                        <small class="text-muted">Include country code, no + sign</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-plus-lg me-1"></i> Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
