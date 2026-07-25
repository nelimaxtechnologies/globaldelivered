<!-- WhatsApp Campaigns -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1"><i class="bi bi-megaphone me-2"></i>Campaign Manager</h4><small class="text-muted">Send bulk WhatsApp messages</small></div>
    <button class="btn btn-primary admin-btn" data-bs-toggle="modal" data-bs-target="#campaignModal"><i class="bi bi-plus-lg me-1"></i> New Campaign</button>
</div>

<!-- Campaigns Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead><tr><th>Campaign</th><th>Template</th><th>Instance</th><th>Status</th><th>Progress</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (!empty($campaigns)): ?>
                    <?php foreach ($campaigns as $c):
                        $statusColors = ['draft'=>'#6c757d','scheduled'=>'#0d6efd','running'=>'#ffc107','paused'=>'#fd7e14','completed'=>'#198754','failed'=>'#dc3545'];
                        $sc = $statusColors[$c->status] ?? '#6c757d';
                        $progress = $c->total_contacts > 0 ? round(($c->sent / $c->total_contacts) * 100) : 0;
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($c->name) ?></td>
                        <td><small><?= htmlspecialchars($c->template_title ?? '-') ?></small></td>
                        <td><small class="text-muted"><?= htmlspecialchars($c->instance) ?></small></td>
                        <td><span class="badge rounded-pill" style="background:<?= $sc ?>15;color:<?= $sc ?>;border:1px solid <?= $sc ?>30;font-weight:600;"><?= ucfirst($c->status) ?></span></td>
                        <td style="width:150px;">
                            <div class="progress" style="height:6px;"><div class="progress-bar" style="width:<?= $progress ?>%;background:<?= $sc ?>;"></div></div>
                            <small class="text-muted"><?= $c->sent ?>/<?= $c->total_contacts ?></small>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <?php if ($c->status === 'draft'): ?>
                                <a href="<?= BASE_URL ?>/admin/whatsapp/campaigns/<?= $c->id ?>/start" class="btn btn-sm btn-success" title="Start"><i class="bi bi-play-fill"></i></a>
                                <?php endif; ?>
                                <?php if ($c->status === 'running'): ?>
                                <a href="<?= BASE_URL ?>/admin/whatsapp/campaigns/<?= $c->id ?>/pause" class="btn btn-sm btn-warning" title="Pause"><i class="bi bi-pause-fill"></i></a>
                                <?php endif; ?>
                                <?php if ($c->status === 'paused'): ?>
                                <a href="<?= BASE_URL ?>/admin/whatsapp/campaigns/<?= $c->id ?>/resume" class="btn btn-sm btn-success" title="Resume"><i class="bi bi-play-fill"></i></a>
                                <?php endif; ?>
                                <?php if (in_array($c->status, ['running','paused'])): ?>
                                <a href="<?= BASE_URL ?>/admin/whatsapp/campaigns/<?= $c->id ?>/stop" class="btn btn-sm btn-danger" title="Stop"><i class="bi bi-stop-fill"></i></a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/admin/whatsapp/campaigns/<?= $c->id ?>/delete" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this campaign?')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-megaphone fs-1 d-block mb-2" style="opacity:0.3;"></i>No campaigns yet</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- New Campaign Modal -->
<div class="modal fade" id="campaignModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="<?= BASE_URL ?>/admin/whatsapp/campaigns">
        <div class="modal-header"><h6 class="fw-bold"><i class="bi bi-megaphone me-2"></i>New Campaign</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label fw-semibold">Campaign Name *</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Template *</label><select name="template_id" class="form-select" required><option value="">Select Template</option><?php foreach ($templates as $t): ?><option value="<?= $t->id ?>"><?= htmlspecialchars($t->title) ?></option><?php endforeach; ?></select></div>
            <div class="mb-3"><label class="form-label fw-semibold">Instance</label><select name="instance" class="form-select"><?php foreach ($instances as $i): ?><option value="<?= $i->instance_name ?>"><?= htmlspecialchars($i->instance_name) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i> Create Campaign</button></div>
    </form>
</div></div></div>
