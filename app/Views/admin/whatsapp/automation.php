<!-- WhatsApp Automation -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1"><i class="bi bi-gear me-2"></i>Automation Rules</h4><small class="text-muted">Auto-send WhatsApp messages based on events</small></div>
    <button class="btn btn-primary admin-btn" data-bs-toggle="modal" data-bs-target="#automationModal"><i class="bi bi-plus-lg me-1"></i> New Rule</button>
</div>

<!-- Automation Rules -->
<div class="row g-3">
    <?php if (!empty($automations)): ?>
    <?php foreach ($automations as $a):
        $eventIcons = ['order_created'=>'bi-bag-check','payment_received'=>'bi-credit-card','shipment_dispatched'=>'bi-truck','delivery_completed'=>'bi-check-circle','abandoned_cart'=>'bi-cart-x','password_reset'=>'bi-key','registration'=>'bi-person-plus'];
        $icon = $eventIcons[$a->trigger_event] ?? 'bi-gear';
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="admin-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:36px;height:36px;border-radius:8px;background:<?= $a->is_active ? '#19875415' : '#6c757d15' ?>;display:flex;align-items:center;justify-content:center;"><i class="bi <?= $icon ?>" style="color:<?= $a->is_active ? '#198754' : '#6c757d' ?>;"></i></div>
                    <div><div class="fw-bold" style="font-size:0.9rem;"><?= htmlspecialchars($a->name) ?></div><small class="text-muted"><?= ucwords(str_replace('_', ' ', $a->trigger_event)) ?></small></div>
                </div>
                <span class="badge <?= $a->is_active ? 'bg-success' : 'bg-secondary' ?>"><?= $a->is_active ? 'Active' : 'Inactive' ?></span>
            </div>
            <p class="text-muted small mb-2">Template: <?= htmlspecialchars($a->template_title ?? 'None') ?></p>
            <p class="text-muted small mb-2">Instance: <?= htmlspecialchars($a->instance ?: 'Default') ?></p>
            <div class="d-flex gap-1 mt-auto pt-2 border-top">
                <a href="<?= BASE_URL ?>/admin/whatsapp/automation/<?= $a->id ?>/delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this rule?')"><i class="bi bi-trash"></i> Delete</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="col-12"><div class="admin-card text-center py-5"><i class="bi bi-gear fs-1 d-block mb-2" style="opacity:0.3;"></i><p class="text-muted">No automation rules yet</p></div></div>
    <?php endif; ?>
</div>

<!-- New Automation Modal -->
<div class="modal fade" id="automationModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="<?= BASE_URL ?>/admin/whatsapp/automation">
        <div class="modal-header"><h6 class="fw-bold"><i class="bi bi-gear me-2"></i>New Automation Rule</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label fw-semibold">Rule Name *</label><input type="text" name="name" class="form-control" required placeholder="e.g., Send welcome message"></div>
            <div class="mb-3"><label class="form-label fw-semibold">Trigger Event *</label><select name="trigger_event" class="form-select" required>
                <option value="order_created">Order Created</option><option value="payment_received">Payment Received</option><option value="shipment_dispatched">Shipment Dispatched</option><option value="delivery_completed">Delivery Completed</option><option value="abandoned_cart">Abandoned Cart</option><option value="password_reset">Password Reset</option><option value="registration">Registration</option>
            </select></div>
            <div class="mb-3"><label class="form-label fw-semibold">Template *</label><select name="template_id" class="form-select" required><option value="">Select Template</option><?php foreach ($templates as $t): ?><option value="<?= $t->id ?>"><?= htmlspecialchars($t->title) ?></option><?php endforeach; ?></select></div>
            <div class="mb-3"><label class="form-label fw-semibold">Instance</label><select name="instance" class="form-select"><?php foreach ($instances as $i): ?><option value="<?= $i->instance_name ?>"><?= htmlspecialchars($i->instance_name) ?></option><?php endforeach; ?></select></div>
            <div class="form-check form-switch"><input type="checkbox" name="is_active" class="form-check-input" value="1" checked><label class="form-check-label fw-semibold">Active</label></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Save Rule</button></div>
    </form>
</div></div></div>
