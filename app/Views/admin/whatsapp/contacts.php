<!-- WhatsApp Contacts -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1"><i class="bi bi-people me-2"></i>WhatsApp Contacts</h4><small class="text-muted">Manage your WhatsApp contact database</small></div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success admin-btn" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bi bi-upload me-1"></i> Import CSV</button>
        <a href="<?= BASE_URL ?>/admin/whatsapp/contacts/export" class="btn btn-outline-primary admin-btn"><i class="bi bi-download me-1"></i> Export</a>
    </div>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-6"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" name="search" class="form-control" placeholder="Search name, phone, email..." value="<?= htmlspecialchars($filters['search']) ?>"></div></div>
        <div class="col-md-3"><button type="submit" class="btn btn-primary admin-btn w-100"><i class="bi bi-funnel me-1"></i> Search</button></div>
        <div class="col-md-3"><a href="<?= BASE_URL ?>/admin/whatsapp/contacts" class="btn btn-outline-secondary admin-btn w-100">Reset</a></div>
    </form>
</div>

<!-- Contacts Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead><tr><th>Contact</th><th>Phone</th><th>Email</th><th>Country</th><th>Last Seen</th></tr></thead>
            <tbody>
                <?php if (!empty($contacts)): ?>
                    <?php foreach ($contacts as $c): ?>
                    <tr>
                        <td><div class="d-flex align-items-center gap-2"><div class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;font-size:0.7rem;font-weight:600;"><?= strtoupper(substr($c->name ?: $c->phone, 0, 2)) ?></div><span class="fw-semibold"><?= htmlspecialchars($c->name ?: 'Unknown') ?></span></div></td>
                        <td><small><?= htmlspecialchars($c->phone) ?></small></td>
                        <td><small class="text-muted"><?= htmlspecialchars($c->email ?: '-') ?></small></td>
                        <td><small class="text-muted"><?= htmlspecialchars($c->country ?: '-') ?></small></td>
                        <td><small class="text-muted"><?= $c->last_seen ? time_ago($c->last_seen) : 'Never' ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-people fs-1 d-block mb-2" style="opacity:0.3;"></i>No contacts found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pagination->totalPages > 1): ?>
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
        <small class="text-muted">Page <?= $pagination->page ?> of <?= $pagination->totalPages ?></small>
        <nav><ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($filters['search']) ?>"><i class="bi bi-chevron-left"></i></a></li>
            <li class="page-item active"><span class="page-link"><?= $pagination->page ?></span></li>
            <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($filters['search']) ?>"><i class="bi bi-chevron-right"></i></a></li>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST" action="<?= BASE_URL ?>/admin/whatsapp/contacts/import" enctype="multipart/form-data">
        <div class="modal-header"><h6 class="fw-bold"><i class="bi bi-upload me-2"></i>Import Contacts</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <p class="text-muted small">CSV must have headers: <code>name, phone, email, country</code></p>
            <div class="mb-3"><label class="form-label fw-semibold">Instance</label><select name="instance" class="form-select"><?php foreach ($instances as $i): ?><option value="<?= $i->instance_name ?>"><?= htmlspecialchars($i->instance_name) ?></option><?php endforeach; ?></select></div>
            <div class="mb-3"><label class="form-label fw-semibold">CSV File</label><input type="file" name="csv_file" class="form-control" accept=".csv" required></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success btn-sm"><i class="bi bi-upload me-1"></i> Import</button></div>
    </form>
</div></div></div>
