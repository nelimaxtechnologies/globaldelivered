<!-- WhatsApp Templates -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1"><i class="bi bi-file-text me-2"></i>Message Templates</h4><small class="text-muted">Create reusable message templates</small></div>
    <button class="btn btn-primary admin-btn" data-bs-toggle="modal" data-bs-target="#templateModal" onclick="resetTemplateForm()"><i class="bi bi-plus-lg me-1"></i> New Template</button>
</div>

<!-- Templates Grid -->
<div class="row g-3">
    <?php if (!empty($templates)): ?>
    <?php foreach ($templates as $t): ?>
    <div class="col-lg-4 col-md-6">
        <div class="admin-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="fw-bold mb-0"><?= htmlspecialchars($t->title) ?></h6>
                <span class="badge bg-light text-dark"><?= htmlspecialchars($t->category) ?></span>
            </div>
            <p class="text-muted small mb-2" style="min-height:60px;"><?= htmlspecialchars(mb_strimwidth($t->message, 0, 120, '...')) ?></p>
            <?php if ($t->variables): ?>
            <div class="mb-2"><small class="text-muted"><i class="bi bi-code me-1"></i>Variables: <?= htmlspecialchars($t->variables) ?></small></div>
            <?php endif; ?>
            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                <small class="text-muted">Used <?= $t->use_count ?> times</small>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-primary" onclick="editTemplate(<?= htmlspecialchars(json_encode($t)) ?>)"><i class="bi bi-pencil"></i></button>
                    <a href="<?= BASE_URL ?>/admin/whatsapp/templates/<?= $t->id ?>/delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this template?')"><i class="bi bi-trash"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="col-12"><div class="admin-card text-center py-5"><i class="bi bi-file-text fs-1 d-block mb-2" style="opacity:0.3;"></i><p class="text-muted">No templates created yet</p></div></div>
    <?php endif; ?>
</div>

<!-- Template Modal -->
<div class="modal fade" id="templateModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="POST" action="<?= BASE_URL ?>/admin/whatsapp/templates">
        <div class="modal-header"><h6 class="fw-bold" id="templateModalTitle"><i class="bi bi-file-text me-2"></i>New Template</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="tplId" value="">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label fw-semibold">Title *</label><input type="text" name="title" class="form-control" id="tplTitle" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Category</label><select name="category" class="form-select" id="tplCategory"><option value="general">General</option><option value="welcome">Welcome</option><option value="order">Order</option><option value="shipping">Shipping</option><option value="delivery">Delivery</option><option value="otp">OTP</option><option value="invoice">Invoice</option><option value="payment">Payment</option><option value="support">Support</option><option value="promotion">Promotion</option></select></div>
                <div class="col-12"><label class="form-label fw-semibold">Message *</label><textarea name="message" class="form-control" rows="5" id="tplMessage" required placeholder="Hello {{customer}}, your order {{order}} has been shipped!"></textarea><small class="text-muted">Use {{variable}} for dynamic content</small></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Variables</label><input type="text" name="variables" class="form-control" id="tplVariables" placeholder="customer, order, tracking"><small class="text-muted">Comma-separated</small></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Media URL (optional)</label><input type="text" name="media_url" class="form-control" id="tplMedia" placeholder="https://..."></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Save Template</button></div>
    </form>
</div></div></div>

<script>
function resetTemplateForm() {
    document.getElementById('templateModalTitle').innerHTML = '<i class="bi bi-file-text me-2"></i>New Template';
    document.getElementById('tplId').value = '';
    document.getElementById('tplTitle').value = '';
    document.getElementById('tplMessage').value = '';
    document.getElementById('tplVariables').value = '';
    document.getElementById('tplCategory').value = 'general';
    document.getElementById('tplMedia').value = '';
}
function editTemplate(t) {
    document.getElementById('templateModalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Edit Template';
    document.getElementById('tplId').value = t.id;
    document.getElementById('tplTitle').value = t.title;
    document.getElementById('tplMessage').value = t.message;
    document.getElementById('tplVariables').value = t.variables || '';
    document.getElementById('tplCategory').value = t.category || 'general';
    document.getElementById('tplMedia').value = t.media_url || '';
    new bootstrap.Modal(document.getElementById('templateModal')).show();
}
</script>
