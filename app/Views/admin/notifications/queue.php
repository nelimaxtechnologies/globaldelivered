<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-primary border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#4a6cf7,#6a8cff);">
                    <i class="bi bi-<?= $queueType === 'email' ? 'envelope' : 'phone' ?>"></i>
                </div>
                <div>
                    <div class="stat-label">Total</div>
                    <div class="stat-value text-primary"><?= number_format((int)($queueType === 'email' ? ($emailCounts->total ?? 0) : ($smsCounts->total ?? 0))) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-warning border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#ffc107,#ffcd38);">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="stat-label">Queued</div>
                    <div class="stat-value text-warning"><?= number_format((int)($queueType === 'email' ? ($emailCounts->queued ?? 0) : ($smsCounts->queued ?? 0))) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-success border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#28a745,#5cb85c);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Sent</div>
                    <div class="stat-value text-success"><?= number_format((int)($queueType === 'email' ? ($emailCounts->sent ?? 0) : ($smsCounts->sent ?? 0))) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-danger border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#dc3545,#e8636f);">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Failed</div>
                    <div class="stat-value text-danger"><?= number_format((int)($queueType === 'email' ? ($emailCounts->failed ?? 0) : ($smsCounts->failed ?? 0))) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Queue Distribution Bars -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-envelope me-2"></i>Email Queue Distribution</h6>
            <?php
            $eqTotal = max(1, (int)($emailCounts->total ?? 1));
            $eqQueued = (int)($emailCounts->queued ?? 0);
            $eqSending = (int)($emailCounts->sending ?? 0);
            $eqSent = (int)($emailCounts->sent ?? 0);
            $eqFailed = (int)($emailCounts->failed ?? 0);
            ?>
            <div class="d-flex gap-3 mb-2" style="font-size:0.78rem;">
                <span><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#4a6cf7;"></span> Queued: <?= $eqQueued ?></span>
                <span><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#17a2b8;"></span> Sending: <?= $eqSending ?></span>
                <span><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#28a745;"></span> Sent: <?= $eqSent ?></span>
                <span><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#dc3545;"></span> Failed: <?= $eqFailed ?></span>
            </div>
            <div class="progress" style="height:12px;border-radius:6px;">
                <?php if ($eqTotal > 0): ?>
                <div class="progress-bar" style="width:<?= ($eqQueued/$eqTotal)*100 ?>%;background:#4a6cf7;border-radius:6px 0 0 6px;" title="Queued: <?= $eqQueued ?>"></div>
                <div class="progress-bar" style="width:<?= ($eqSending/$eqTotal)*100 ?>%;background:#17a2b8;" title="Sending: <?= $eqSending ?>"></div>
                <div class="progress-bar" style="width:<?= ($eqSent/$eqTotal)*100 ?>%;background:#28a745;" title="Sent: <?= $eqSent ?>"></div>
                <div class="progress-bar" style="width:<?= ($eqFailed/$eqTotal)*100 ?>%;background:#dc3545;border-radius:0 6px 6px 0;" title="Failed: <?= $eqFailed ?>"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-phone me-2"></i>SMS Queue Distribution</h6>
            <?php
            $sqTotal = max(1, (int)($smsCounts->total ?? 1));
            $sqQueued = (int)($smsCounts->queued ?? 0);
            $sqSending = (int)($smsCounts->sending ?? 0);
            $sqSent = (int)($smsCounts->sent ?? 0);
            $sqFailed = (int)($smsCounts->failed ?? 0);
            ?>
            <div class="d-flex gap-3 mb-2" style="font-size:0.78rem;">
                <span><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#4a6cf7;"></span> Queued: <?= $sqQueued ?></span>
                <span><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#17a2b8;"></span> Sending: <?= $sqSending ?></span>
                <span><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#28a745;"></span> Sent: <?= $sqSent ?></span>
                <span><span style="display:inline-block;width:8px;height:8px;border-radius:2px;background:#dc3545;"></span> Failed: <?= $sqFailed ?></span>
            </div>
            <div class="progress" style="height:12px;border-radius:6px;">
                <?php if ($sqTotal > 0): ?>
                <div class="progress-bar" style="width:<?= ($sqQueued/$sqTotal)*100 ?>%;background:#4a6cf7;border-radius:6px 0 0 6px;" title="Queued: <?= $sqQueued ?>"></div>
                <div class="progress-bar" style="width:<?= ($sqSending/$sqTotal)*100 ?>%;background:#17a2b8;" title="Sending: <?= $sqSending ?>"></div>
                <div class="progress-bar" style="width:<?= ($sqSent/$sqTotal)*100 ?>%;background:#28a745;" title="Sent: <?= $sqSent ?>"></div>
                <div class="progress-bar" style="width:<?= ($sqFailed/$sqTotal)*100 ?>%;background:#dc3545;border-radius:0 6px 6px 0;" title="Failed: <?= $sqFailed ?>"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i><?= htmlspecialchars($tableTitle) ?></h5>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/notifications/queue?queue=email" class="btn btn-sm <?= $queueType === 'email' ? 'btn-primary' : 'btn-outline-primary' ?>"><i class="bi bi-envelope me-1"></i> Email</a>
            <a href="<?= BASE_URL ?>/admin/notifications/queue?queue=sms" class="btn btn-sm <?= $queueType === 'sms' ? 'btn-primary' : 'btn-outline-primary' ?>"><i class="bi bi-phone me-1"></i> SMS</a>
            <a href="<?= BASE_URL ?>/admin/notifications" class="btn btn-outline-secondary admin-btn admin-btn-sm"><i class="bi bi-bell me-1"></i> Notifications</a>
        </div>
    </div>

    <form method="GET" class="row g-2 mb-4">
        <input type="hidden" name="queue" value="<?= $queueType ?>">
        <div class="col-md-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search email, phone, subject..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="queued" <?= $filters['status'] === 'queued' ? 'selected' : '' ?>>Queued</option>
                <option value="sending" <?= $filters['status'] === 'sending' ? 'selected' : '' ?>>Sending</option>
                <option value="sent" <?= $filters['status'] === 'sent' ? 'selected' : '' ?>>Sent</option>
                <option value="failed" <?= $filters['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                <option value="cancelled" <?= $filters['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary admin-btn admin-btn-sm"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
        <div class="col-auto">
            <a href="<?= BASE_URL ?>/admin/notifications/queue?queue=<?= $queueType ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>

    <!-- Bulk Actions -->
    <div id="bulkRetryBar" class="d-none align-items-center justify-content-between mb-3 p-2 rounded-3" style="background:#fff3cd;border:1px solid #ffc107;">
        <span class="fw-semibold text-warning" style="font-size:0.88rem;"><span id="retrySelectedCount">0</span> failed item(s) selected</span>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-warning" onclick="bulkRetry()"><i class="bi bi-arrow-clockwise me-1"></i> Retry All Selected</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="clearQueueSelection()"><i class="bi bi-x-lg me-1"></i> Cancel</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-admin">
            <thead>
                <tr>
                    <th style="width:30px;"><input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll(this)"></th>
                    <th>#</th>
                    <?php if ($queueType === 'email'): ?>
                    <th>To</th><th>Subject</th>
                    <?php else: ?>
                    <th>To Phone</th><th>Message</th>
                    <?php endif; ?>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Retries</th>
                    <th>Error</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                    <tr class="<?= $item->status === 'failed' ? 'table-danger-subtle' : '' ?>">
                        <td>
                            <?php if ($item->status === 'failed'): ?>
                            <input type="checkbox" class="form-check-input queue-checkbox" value="<?= $item->id ?>" onchange="updateRetryActions()">
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted">#<?= $item->id ?></small></td>
                        <?php if ($queueType === 'email'): ?>
                        <td>
                            <div class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars($item->to_email) ?></div>
                            <?php if ($item->to_name): ?><small class="text-muted"><?= htmlspecialchars($item->to_name) ?></small><?php endif; ?>
                        </td>
                        <td><small><?= htmlspecialchars(mb_substr($item->subject, 0, 50)) ?></small></td>
                        <?php else: ?>
                        <td><span class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars($item->to_phone) ?></span></td>
                        <td><small class="text-muted"><?= htmlspecialchars(mb_substr($item->message, 0, 50)) ?></small></td>
                        <?php endif; ?>
                        <td>
                            <?php if ($item->priority > 0): ?>
                            <span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-arrow-up-short"></i><?= $item->priority ?></span>
                            <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">Normal</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $item->status === 'sent' ? 'success' : ($item->status === 'queued' ? 'primary' : ($item->status === 'sending' ? 'info' : ($item->status === 'failed' ? 'danger' : 'secondary'))) ?> rounded-pill">
                                <i class="bi bi-<?= $item->status === 'sent' ? 'check-circle' : ($item->status === 'queued' ? 'hourglass' : ($item->status === 'sending' ? 'arrow-repeat' : ($item->status === 'failed' ? 'x-circle' : 'dash-circle'))) ?>"></i>
                                <?= ucfirst($item->status) ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="progress flex-grow-1" style="height:4px;max-width:60px;">
                                    <div class="progress-bar bg-<?= $item->retry_count >= $item->max_retries ? 'danger' : 'warning' ?>" style="width:<?= $item->max_retries > 0 ? ($item->retry_count / $item->max_retries) * 100 : 0 ?>%"></div>
                                </div>
                                <small class="text-muted"><?= $item->retry_count ?>/<?= $item->max_retries ?></small>
                            </div>
                        </td>
                        <td>
                            <?php if ($item->error_message): ?>
                            <small class="text-danger text-truncate d-block" style="max-width:150px;" title="<?= htmlspecialchars($item->error_message) ?>"><?= htmlspecialchars(mb_substr($item->error_message, 0, 40)) ?></small>
                            <?php else: ?>
                            <small class="text-muted">—</small>
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?= time_ago($item->created_at) ?></small></td>
                        <td>
                            <?php if ($item->status === 'failed'): ?>
                            <a href="<?= BASE_URL ?>/admin/notifications/<?= $queueType ?>/<?= $item->id ?>/retry" class="btn btn-outline-warning btn-sm" title="Retry" onclick="event.preventDefault();retryItem(<?= $item->id ?>, '<?= $queueType ?>')">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <h6 class="fw-semibold">Queue is empty</h6>
                                <small>No <?= $queueType ?> items match your filters.</small>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination->totalPages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center mb-0">
            <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&queue=<?= $queueType ?>&status=<?= urlencode($filters['status']) ?>&search=<?= urlencode($filters['search'] ?? '') ?>"><i class="bi bi-chevron-left"></i></a>
            </li>
            <?php for ($p = max(1, $pagination->page - 2); $p <= min($pagination->totalPages, $pagination->page + 2); $p++): ?>
            <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>&queue=<?= $queueType ?>&status=<?= urlencode($filters['status']) ?>&search=<?= urlencode($filters['search'] ?? '') ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&queue=<?= $queueType ?>&status=<?= urlencode($filters['status']) ?>&search=<?= urlencode($filters['search'] ?? '') ?>"><i class="bi bi-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<script>
const BASE = '<?= BASE_URL ?>';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function toggleSelectAll(el) {
    document.querySelectorAll('.queue-checkbox').forEach(cb => cb.checked = el.checked);
    updateRetryActions();
}

function updateRetryActions() {
    const checked = document.querySelectorAll('.queue-checkbox:checked');
    document.getElementById('retrySelectedCount').textContent = checked.length;
    document.getElementById('bulkRetryBar').classList.toggle('d-none', checked.length === 0);
    document.getElementById('bulkRetryBar').classList.toggle('d-flex', checked.length > 0);
}

function clearQueueSelection() {
    document.querySelectorAll('.queue-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateRetryActions();
}

function retryItem(id, queueType) {
    fetch(`${BASE}/admin/notifications/${queueType}/${id}/retry`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) { showToast(r.message, 'success'); setTimeout(() => location.reload(), 800); }
        else { showToast(r.message || 'Error', 'danger'); }
    })
    .catch(() => showToast('Request failed', 'danger'));
}

function bulkRetry() {
    const ids = Array.from(document.querySelectorAll('.queue-checkbox:checked')).map(cb => parseInt(cb.value));
    if (ids.length === 0) return;
    if (!confirm(`Retry ${ids.length} failed item(s)?`)) return;
    
    fetch(`${BASE}/admin/notifications/bulk-retry`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF },
        body: `ids[]=${ids.join('&ids[]=')}&queue=<?= $queueType ?>`
    })
    .then(r => r.json())
    .then(r => { if (r.success) { showToast(r.message, 'success'); setTimeout(() => location.reload(), 800); } });
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top:20px;right:20px;z-index:9999;min-width:280px;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
    toast.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
