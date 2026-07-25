<!-- WhatsApp Logs -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1"><i class="bi bi-list-check me-2"></i>API Logs</h4><small class="text-muted">Monitor all API requests and responses</small></div>
    <a href="<?= BASE_URL ?>/admin/whatsapp/logs/clear" class="btn btn-outline-danger admin-btn" onclick="return confirm('Clear all logs?')"><i class="bi bi-trash me-1"></i> Clear Logs</a>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-4"><label class="form-label small fw-semibold">Instance</label><select name="instance" class="form-select" onchange="this.form.submit()"><option value="">All Instances</option><?php foreach ($instances as $i): ?><option value="<?= $i->instance_name ?>" <?= ($filters['instance'] ?? '') === $i->instance_name ? 'selected' : '' ?>><?= htmlspecialchars($i->instance_name) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-2"><button type="submit" class="btn btn-primary admin-btn w-100"><i class="bi bi-funnel me-1"></i> Filter</button></div>
        <div class="col-md-2"><a href="<?= BASE_URL ?>/admin/whatsapp/logs" class="btn btn-outline-secondary admin-btn w-100">Reset</a></div>
    </form>
</div>

<!-- Logs Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead><tr><th>Time</th><th>Method</th><th>Endpoint</th><th>Status</th><th>Duration</th><th>Response</th></tr></thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $l): ?>
                    <tr>
                        <td><small class="text-muted"><?= format_date($l->created_at, 'M d, H:i:s') ?></small></td>
                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($l->method) ?></span></td>
                        <td><small class="text-muted" style="font-family:monospace;font-size:0.78rem;"><?= htmlspecialchars(mb_strimwidth($l->endpoint, 0, 50, '...')) ?></small></td>
                        <td><span class="badge <?= $l->status === 'success' ? 'bg-success' : 'bg-danger' ?>"><?= $l->response_code ?></span></td>
                        <td><small><?= $l->duration_ms ?>ms</small></td>
                        <td><small class="text-muted" style="font-family:monospace;font-size:0.75rem;"><?= htmlspecialchars(mb_strimwidth($l->error_message ?? $l->response_body ?? '', 0, 60, '...')) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-list-check fs-1 d-block mb-2" style="opacity:0.3;"></i>No logs found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pagination->totalPages > 1): ?>
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
        <small class="text-muted">Page <?= $pagination->page ?> of <?= $pagination->totalPages ?></small>
        <nav><ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page - 1 ?>&instance=<?= urlencode($filters['instance'] ?? '') ?>"><i class="bi bi-chevron-left"></i></a></li>
            <li class="page-item active"><span class="page-link"><?= $pagination->page ?></span></li>
            <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page + 1 ?>&instance=<?= urlencode($filters['instance'] ?? '') ?>"><i class="bi bi-chevron-right"></i></a></li>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>
