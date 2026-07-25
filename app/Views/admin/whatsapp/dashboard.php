<!-- WhatsApp Dashboard -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-whatsapp me-2" style="color:#25D366;"></i>WhatsApp Dashboard</h4>
        <small class="text-muted">Monitor your WhatsApp messaging activity</small>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary admin-btn" onclick="syncInstances()"><i class="bi bi-arrow-clockwise me-1"></i> Sync</button>
        <a href="<?= BASE_URL ?>/admin/whatsapp/chats" class="btn btn-success admin-btn"><i class="bi bi-chat-dots me-1"></i> Open Inbox</a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top:3px solid #25D366;">
            <div class="fw-bold fs-4" style="color:#25D366;"><?= $connectedCount ?></div>
            <small class="text-muted">Connected</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3">
            <div class="fw-bold fs-4 text-primary"><?= number_format($stats['today'] ?? 0) ?></div>
            <small class="text-muted">Today</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top:3px solid #198754;">
            <div class="fw-bold fs-4 text-success"><?= number_format($stats['sent'] ?? 0) ?></div>
            <small class="text-muted">Sent</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top:3px solid #0d6efd;">
            <div class="fw-bold fs-4 text-info"><?= number_format($stats['received'] ?? 0) ?></div>
            <small class="text-muted">Received</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top:3px solid #ffc107;">
            <div class="fw-bold fs-4 text-warning"><?= number_format($stats['pending'] ?? 0) ?></div>
            <small class="text-muted">Pending</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="border-top:3px solid #dc3545;">
            <div class="fw-bold fs-4 text-danger"><?= number_format($stats['failed'] ?? 0) ?></div>
            <small class="text-muted">Failed</small>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-2"></i>Messages Trend (14 Days)</h6>
            <canvas id="messageChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-chat-left-dots me-2"></i>Quick Actions</h6>
            <div class="d-grid gap-2">
                <a href="<?= BASE_URL ?>/admin/whatsapp/chats" class="btn btn-outline-success admin-btn"><i class="bi bi-chat-dots me-1"></i> Open Inbox</a>
                <a href="<?= BASE_URL ?>/admin/whatsapp/contacts" class="btn btn-outline-primary admin-btn"><i class="bi bi-people me-1"></i> Contacts</a>
                <a href="<?= BASE_URL ?>/admin/whatsapp/campaigns" class="btn btn-outline-warning admin-btn"><i class="bi bi-megaphone me-1"></i> Campaigns</a>
                <a href="<?= BASE_URL ?>/admin/whatsapp/templates" class="btn btn-outline-info admin-btn"><i class="bi bi-file-text me-1"></i> Templates</a>
                <a href="<?= BASE_URL ?>/admin/whatsapp/automation" class="btn btn-outline-secondary admin-btn"><i class="bi bi-gear me-1"></i> Automation</a>
                <a href="<?= BASE_URL ?>/admin/whatsapp/instances" class="btn btn-outline-dark admin-btn"><i class="bi bi-phone me-1"></i> Instances</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Conversations -->
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-chat-left-text me-2"></i>Recent Conversations</h6>
        <a href="<?= BASE_URL ?>/admin/whatsapp/chats" class="btn btn-sm btn-outline-success">View All</a>
    </div>
    <?php if (!empty($conversations)): ?>
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead><tr><th>Contact</th><th>Last Message</th><th>Time</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($conversations as $conv): ?>
                <tr style="cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/whatsapp/chats?phone=<?= $conv->phone ?>&instance=<?= $conv->instance ?>'">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;font-weight:600;font-size:0.7rem;">
                                <?= strtoupper(substr($conv->contact_name ?: $conv->phone, 0, 2)) ?>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars($conv->contact_name ?: $conv->phone) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($conv->phone) ?></small>
                            </div>
                        </div>
                    </td>
                    <td><small class="text-muted"><?= htmlspecialchars(mb_strimwidth($conv->last_message, 0, 50, '...')) ?></small></td>
                    <td><small class="text-muted"><?= time_ago($conv->last_message_at) ?></small></td>
                    <td>
                        <?php if ($conv->unread_count > 0): ?>
                            <span class="badge bg-success rounded-pill"><?= $conv->unread_count ?></span>
                        <?php else: ?>
                            <small class="text-muted"><?= $conv->direction === 'outbound' ? '<i class="bi bi-check-all text-primary"></i>' : '<i class="bi bi-arrow-down-left text-muted"></i>' ?></small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-chat-dots fs-1 d-block mb-2" style="opacity:0.3;"></i>
        <p>No conversations yet</p>
    </div>
    <?php endif; ?>
</div>

<style>.mini-stat{transition:all 0.2s;}.mini-stat:hover{transform:translateY(-3px);box-shadow:0 6px 16px rgba(0,0,0,0.1);}</style>

<script>
function syncInstances() {
    fetch('<?= BASE_URL ?>/admin/whatsapp/api/sync-instances')
    .then(r => r.json())
    .then(r => { if (r.success) { location.reload(); } else { alert(r.error || 'Sync failed'); } })
    .catch(() => alert('Request failed'));
}
</script>

<?php if (!empty($dailyStats)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('messageChart').getContext('2d');
    var data = <?= json_encode($dailyStats) ?>;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [
                { label: 'Sent', data: data.map(d => parseInt(d.sent)), borderColor: '#25D366', backgroundColor: 'rgba(37,211,102,0.1)', fill: true, tension: 0.4 },
                { label: 'Received', data: data.map(d => parseInt(d.received)), borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.1)', fill: true, tension: 0.4 }
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
    });
});
</script>
<?php endif; ?>
