<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-primary border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#4a6cf7,#6a8cff);">
                    <i class="bi bi-bell"></i>
                </div>
                <div>
                    <div class="stat-label">Total</div>
                    <div class="stat-value text-primary"><?= number_format((int)($stats->total ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-danger border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#dc3545,#e8636f);">
                    <i class="bi bi-envelope-exclamation"></i>
                </div>
                <div>
                    <div class="stat-label">Unread</div>
                    <div class="stat-value text-danger"><?= number_format((int)($stats->pending ?? 0)) ?></div>
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
                    <div class="stat-label">Sent Today</div>
                    <div class="stat-value text-success"><?= number_format((int)($stats->today ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-info border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#17a2b8,#45b8d4);">
                    <i class="bi bi-envelope-check"></i>
                </div>
                <div>
                    <div class="stat-label">Emails / SMS</div>
                    <div class="stat-value text-info" style="font-size:1.4rem;"><?= number_format((int)($stats->emails ?? 0)) ?> <span class="text-muted">/</span> <?= number_format((int)($stats->sms ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-bell me-2"></i>Notifications</h5>
            <?php if ($unreadCount > 0): ?>
            <span class="badge bg-danger rounded-pill"><?= $unreadCount ?> unread</span>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/notifications/templates" class="btn btn-outline-info admin-btn admin-btn-sm"><i class="bi bi-file-text me-1"></i> Templates</a>
            <a href="<?= BASE_URL ?>/admin/notifications/queue" class="btn btn-outline-primary admin-btn admin-btn-sm"><i class="bi bi-list-ul me-1"></i> Queue</a>
            <?php if ($unreadCount > 0): ?>
            <button class="btn btn-outline-success admin-btn admin-btn-sm" onclick="markAllRead()"><i class="bi bi-check-all me-1"></i> Mark All Read</button>
            <?php endif; ?>
            <button class="btn btn-primary admin-btn admin-btn-sm" onclick="openComposeModal()"><i class="bi bi-send me-1"></i> Compose</button>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-4 gap-1">
        <?php
        $tabs = [
            'all' => ['label' => 'All', 'icon' => 'bi-bell', 'count' => $stats->total ?? 0],
            'unread' => ['label' => 'Unread', 'icon' => 'bi-envelope', 'count' => $stats->pending ?? 0],
            'email' => ['label' => 'Email', 'icon' => 'bi-envelope-at', 'count' => $stats->emails ?? 0],
            'sms' => ['label' => 'SMS', 'icon' => 'bi-phone', 'count' => $stats->sms ?? 0],
            'system' => ['label' => 'System', 'icon' => 'bi-gear', 'count' => $stats->system_count ?? 0],
        ];
        foreach ($tabs as $key => $tab):
            $active = ($filters['tab'] ?? 'all') === $key || ($key === 'all' && empty($filters['tab']));
            $href = BASE_URL . '/admin/notifications?tab=' . $key;
            if (!empty($filters['search'])) $href .= '&search=' . urlencode($filters['search']);
            if (!empty($filters['type']) && $key === 'all') $href .= '&type=' . urlencode($filters['type']);
            if (!empty($filters['start_date'])) $href .= '&start_date=' . urlencode($filters['start_date']);
            if (!empty($filters['end_date'])) $href .= '&end_date=' . urlencode($filters['end_date']);
            if ($key === 'unread') $href .= '&read=unread';
        ?>
        <li class="nav-item">
            <a class="nav-link <?= $active ? 'active' : '' ?> d-flex align-items-center gap-1 <?= $active ? '' : 'text-muted' ?>" href="<?= $href ?>" style="border-radius:10px;font-size:0.85rem;">
                <i class="bi <?= $tab['icon'] ?>"></i> <?= $tab['label'] ?>
                <?php if ((int)$tab['count'] > 0): ?>
                <span class="badge <?= $active ? 'bg-white text-primary' : 'bg-light text-dark' ?> rounded-pill ms-1" style="font-size:0.68rem;"><?= number_format((int)$tab['count']) ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <!-- Filters -->
    <form method="GET" class="row g-2 mb-4">
        <input type="hidden" name="tab" value="<?= htmlspecialchars($filters['tab'] ?? 'all') ?>">
        <div class="col-md-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search subject, message, recipient..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="email" <?= $filters['type'] === 'email' ? 'selected' : '' ?>>Email</option>
                <option value="sms" <?= $filters['type'] === 'sms' ? 'selected' : '' ?>>SMS</option>
                <option value="system" <?= $filters['type'] === 'system' ? 'selected' : '' ?>>System</option>
                <option value="push" <?= $filters['type'] === 'push' ? 'selected' : '' ?>>Push</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="read" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="unread" <?= $filters['read'] === 'unread' ? 'selected' : '' ?>>Unread</option>
                <option value="read" <?= $filters['read'] === 'read' ? 'selected' : '' ?>>Read</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="date" name="start_date" class="form-control form-control-sm" value="<?= htmlspecialchars($filters['start_date']) ?>" title="From date">
        </div>
        <div class="col-md-1">
            <input type="date" name="end_date" class="form-control form-control-sm" value="<?= htmlspecialchars($filters['end_date']) ?>" title="To date">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary admin-btn admin-btn-sm"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
        <div class="col-auto">
            <a href="<?= BASE_URL ?>/admin/notifications" class="btn btn-outline-secondary admin-btn admin-btn-sm"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="d-none align-items-center justify-content-between mb-3 p-2 rounded-3" style="background:#e8f4fd;border:1px solid #b6d9f7;">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-primary" style="font-size:0.88rem;"><span id="selectedCount">0</span> selected</span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-success" onclick="bulkMarkRead()"><i class="bi bi-check-all me-1"></i> Mark Read</button>
            <button class="btn btn-sm btn-outline-danger" onclick="bulkDelete()"><i class="bi bi-trash me-1"></i> Delete</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection()"><i class="bi bi-x-lg me-1"></i> Cancel</button>
        </div>
    </div>

    <!-- Notification Feed -->
    <?php if (!empty($notifications)): ?>
    <div class="vstack gap-0">
        <?php
        $typeIcons = [
            'email' => ['icon' => 'bi-envelope-fill', 'color' => '#4a6cf7'],
            'sms' => ['icon' => 'bi-phone-fill', 'color' => '#28a745'],
            'system' => ['icon' => 'bi-gear-fill', 'color' => '#6c757d'],
            'push' => ['icon' => 'bi-bell-fill', 'color' => '#ffc107'],
        ];
        $currentDate = '';
        ?>
        <?php foreach ($notifications as $n): ?>
        <?php
            $logDate = date('Y-m-d', strtotime($n->created_at));
            $isNewDay = $logDate !== $currentDate;
            $currentDate = $logDate;
            $typeInfo = $typeIcons[$n->type] ?? ['icon' => 'bi-dot', 'color' => '#6c757d'];
            $recipient = $n->user_name ?? $n->customer_name ?? 'System';
            $isUnread = !$n->is_read;
        ?>
        <?php if ($isNewDay): ?>
        <div class="d-flex align-items-center gap-3 py-2 mt-2" style="border-bottom:2px solid #e9ecef;">
            <div style="width:32px;height:32px;border-radius:8px;background:#f0f2f5;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-calendar3 text-muted" style="font-size:0.85rem;"></i>
            </div>
            <span class="fw-bold text-muted" style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.5px;"><?= date('l, F j, Y', strtotime($logDate)) ?></span>
        </div>
        <?php endif; ?>
        <div class="d-flex align-items-start gap-3 py-3 border-bottom notif-row <?= $isUnread ? 'bg-light' : '' ?>" 
             data-id="<?= $n->id ?>" style="transition:background 0.2s;cursor:pointer;"
             onmouseover="this.style.background='#f0f4f8'" onmouseout="this.style.background='<?= $isUnread ? '#f8f9fa' : '' ?>'">
            <div>
                <input type="checkbox" class="form-check-input notif-checkbox" value="<?= $n->id ?>" onchange="updateBulkActions()" style="margin-top:8px;">
            </div>
            <div style="width:40px;height:40px;border-radius:10px;background:<?= $typeInfo['color'] ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi <?= $typeInfo['icon'] ?>" style="color:<?= $typeInfo['color'] ?>;"></i>
            </div>
            <div class="flex-grow-1 min-width-0" onclick="viewNotification(<?= $n->id ?>)">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <?php if ($isUnread): ?>
                    <span style="width:8px;height:8px;border-radius:50%;background:#dc3545;flex-shrink:0;"></span>
                    <?php endif; ?>
                    <span class="fw-semibold <?= $isUnread ? '' : 'text-muted' ?>" style="font-size:0.92rem;"><?= htmlspecialchars(mb_substr($n->subject, 0, 60)) ?></span>
                    <span class="badge bg-<?= $n->type === 'email' ? 'primary' : ($n->type === 'sms' ? 'success' : ($n->type === 'system' ? 'secondary' : 'warning')) ?> rounded-pill" style="font-size:0.65rem;"><?= $n->type ?></span>
                    <?php if ($n->is_sent): ?>
                    <span class="badge bg-success-subtle text-success rounded-pill" style="font-size:0.65rem;"><i class="bi bi-check-lg"></i> Sent</span>
                    <?php endif; ?>
                </div>
                <div class="text-truncate" style="font-size:0.85rem;color:#6c757d;max-width:600px;"><?= htmlspecialchars(mb_substr($n->message, 0, 120)) ?></div>
                <div class="d-flex align-items-center gap-3 mt-1">
                    <small class="text-muted"><i class="bi bi-person me-1"></i><?= htmlspecialchars($recipient) ?></small>
                    <small class="text-muted"><i class="bi bi-clock me-1"></i><?= time_ago($n->created_at) ?></small>
                </div>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <button class="btn btn-sm btn-outline-<?= $isUnread ? 'success' : 'secondary' ?>" onclick="event.stopPropagation();toggleRead(<?= $n->id ?>)" title="<?= $isUnread ? 'Mark as read' : 'Mark as unread' ?>">
                    <i class="bi bi-<?= $isUnread ? 'check' : 'envelope-open' ?>"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();confirmDelete(<?= $n->id ?>)" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:#f0f2f5;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="bi bi-bell-slash fs-2 text-muted"></i>
        </div>
        <h6 class="fw-semibold text-muted">No notifications found</h6>
        <small class="text-muted">Try adjusting your filters or <a href="javascript:void(0)" onclick="openComposeModal()">compose a new notification</a>.</small>
    </div>
    <?php endif; ?>

    <?php if ($pagination->totalPages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center mb-0">
            <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&tab=<?= urlencode($filters['tab'] ?? 'all') ?>&type=<?= urlencode($filters['type']) ?>&read=<?= urlencode($filters['read']) ?>&search=<?= urlencode($filters['search']) ?>&start_date=<?= urlencode($filters['start_date']) ?>&end_date=<?= urlencode($filters['end_date']) ?>"><i class="bi bi-chevron-left"></i></a>
            </li>
            <?php for ($p = max(1, $pagination->page - 2); $p <= min($pagination->totalPages, $pagination->page + 2); $p++): ?>
            <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>&tab=<?= urlencode($filters['tab'] ?? 'all') ?>&type=<?= urlencode($filters['type']) ?>&read=<?= urlencode($filters['read']) ?>&search=<?= urlencode($filters['search']) ?>&start_date=<?= urlencode($filters['start_date']) ?>&end_date=<?= urlencode($filters['end_date']) ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&tab=<?= urlencode($filters['tab'] ?? 'all') ?>&type=<?= urlencode($filters['type']) ?>&read=<?= urlencode($filters['read']) ?>&search=<?= urlencode($filters['search']) ?>&start_date=<?= urlencode($filters['start_date']) ?>&end_date=<?= urlencode($filters['end_date']) ?>"><i class="bi bi-chevron-right"></i></a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<!-- Compose Modal -->
<div class="modal fade" id="composeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="bi bi-send me-2"></i>Compose Notification</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="composeForm" onsubmit="return sendNotification(event)">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" id="notifType" required onchange="toggleRecipientFields()">
                                <option value="system">System Notification</option>
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                                <option value="push">Push Notification</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Recipient Type</label>
                            <select class="form-select" id="recipientType" onchange="loadRecipients()">
                                <option value="user">System User</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Recipient</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" class="form-control" id="recipientSearch" placeholder="Type to search users/customers..." oninput="loadRecipients()">
                            </div>
                            <div id="recipientResults" class="list-group mt-1" style="max-height:200px;overflow-y:auto;display:none;"></div>
                            <input type="hidden" name="user_id" id="selectedUserId">
                            <input type="hidden" name="customer_id" id="selectedCustomerId">
                            <input type="hidden" name="email" id="selectedEmail">
                            <input type="hidden" name="phone" id="selectedPhone">
                            <input type="hidden" name="to_name" id="selectedToName">
                            <small id="selectedRecipientLabel" class="text-success d-none"><i class="bi bi-check-circle me-1"></i> <span></span></small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" required placeholder="Notification subject...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="4" required placeholder="Write your notification message..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" form="composeForm"><i class="bi bi-send me-1"></i> Send Notification</button>
            </div>
        </div>
    </div>
</div>

<!-- View Notification Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="bi bi-envelope-open me-2"></i>Notification Detail</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
const BASE = '<?= BASE_URL ?>';
let recipientSearchTimeout = null;

function openComposeModal() {
    document.getElementById('composeForm').reset();
    document.getElementById('selectedUserId').value = '';
    document.getElementById('selectedCustomerId').value = '';
    document.getElementById('selectedEmail').value = '';
    document.getElementById('selectedPhone').value = '';
    document.getElementById('selectedToName').value = '';
    document.getElementById('recipientResults').style.display = 'none';
    document.getElementById('selectedRecipientLabel').classList.add('d-none');
    toggleRecipientFields();
    new bootstrap.Modal(document.getElementById('composeModal')).show();
}

function toggleRecipientFields() {
    const type = document.getElementById('notifType').value;
    const fields = document.querySelectorAll('#recipientFields');
    // no-op for now, can show/hide email/phone fields based on type
}

let loadRecipientTimeout;
function loadRecipients() {
    clearTimeout(loadRecipientTimeout);
    const search = document.getElementById('recipientSearch').value.trim();
    const type = document.getElementById('recipientType').value;
    const resultsDiv = document.getElementById('recipientResults');
    
    if (search.length < 2) { resultsDiv.style.display = 'none'; return; }
    
    loadRecipientTimeout = setTimeout(() => {
        fetch(`${BASE}/admin/notifications/recipients?type=${type}&search=${encodeURIComponent(search)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(r => {
            if (r.success && r.data.length > 0) {
                resultsDiv.innerHTML = r.data.map(item => 
                    `<button type="button" class="list-group-item list-group-item-action" onclick="selectRecipient(${item.id}, '${type}', '${escapeHtml(item.label)}', '${escapeHtml(item.email || '')}', '${escapeHtml(item.phone || '')}')">${escapeHtml(item.label)}</button>`
                ).join('');
                resultsDiv.style.display = 'block';
            } else {
                resultsDiv.innerHTML = '<div class="list-group-item text-muted text-center py-2">No results found</div>';
                resultsDiv.style.display = 'block';
            }
        })
        .catch(() => { resultsDiv.style.display = 'none'; });
    }, 300);
}

function selectRecipient(id, type, label, email, phone) {
    if (type === 'user') {
        document.getElementById('selectedUserId').value = id;
        document.getElementById('selectedCustomerId').value = '';
    } else {
        document.getElementById('selectedCustomerId').value = id;
        document.getElementById('selectedUserId').value = '';
    }
    document.getElementById('selectedEmail').value = email;
    document.getElementById('selectedPhone').value = phone;
    document.getElementById('selectedToName').value = label.split(' (')[0];
    document.getElementById('recipientResults').style.display = 'none';
    document.getElementById('recipientSearch').value = label;
    const lbl = document.getElementById('selectedRecipientLabel');
    lbl.querySelector('span').textContent = label;
    lbl.classList.remove('d-none');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/'/g, "\\'");
}

function sendNotification(e) {
    e.preventDefault();
    const form = document.getElementById('composeForm');
    const data = new FormData(form);
    
    fetch(`${BASE}/admin/notifications/send`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: data
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            bootstrap.Modal.getInstance(document.getElementById('composeModal')).hide();
            showToast(r.message, 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(r.message || 'Error sending notification', 'danger');
        }
    })
    .catch(() => showToast('Request failed', 'danger'));
    
    return false;
}

function viewNotification(id) {
    const body = document.getElementById('viewModalBody');
    body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
    new bootstrap.Modal(document.getElementById('viewModal')).show();
    
    fetch(`${BASE}/admin/notifications/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            const n = r.data;
            const typeColors = { email: 'primary', sms: 'success', system: 'secondary', push: 'warning' };
            body.innerHTML = `
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-${typeColors[n.type] || 'secondary'} rounded-pill">${n.type}</span>
                    ${n.is_sent ? '<span class="badge bg-success-subtle text-success rounded-pill"><i class="bi bi-check-lg"></i> Sent</span>' : '<span class="badge bg-warning-subtle text-warning rounded-pill">Pending</span>'}
                    ${n.is_read ? '<span class="badge bg-info-subtle text-info rounded-pill">Read</span>' : '<span class="badge bg-danger-subtle text-danger rounded-pill">Unread</span>'}
                </div>
                <h6 class="fw-bold mb-2">${escapeHtmlHtml(n.subject)}</h6>
                <div class="text-muted mb-3" style="font-size:0.85rem;">
                    <i class="bi bi-person me-1"></i> ${escapeHtmlHtml(n.user_name || n.customer_name || 'System')}
                    <span class="mx-2">·</span>
                    <i class="bi bi-clock me-1"></i> ${escapeHtmlHtml(n.created_at)}
                </div>
                <hr>
                <div style="white-space:pre-wrap;line-height:1.7;">${escapeHtmlHtml(n.message)}</div>
            `;
            // Mark as read
            if (!n.is_read) {
                fetch(`${BASE}/admin/notifications/${id}/read`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            }
        } else {
            body.innerHTML = '<div class="text-center py-4 text-danger">Failed to load notification</div>';
        }
    })
    .catch(() => { body.innerHTML = '<div class="text-center py-4 text-danger">Request failed</div>'; });
}

function escapeHtmlHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function toggleRead(id) {
    fetch(`${BASE}/admin/notifications/${id}/toggle-read`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(r => { if (r.success) location.reload(); });
}

function markAllRead() {
    fetch(`${BASE}/admin/notifications/mark-all-read`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(r => { if (r.success) { showToast(r.message, 'success'); setTimeout(() => location.reload(), 800); } });
}

function confirmDelete(id) {
    if (confirm('Delete this notification?')) {
        fetch(`${BASE}/admin/notifications/${id}/delete`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(r => { if (r.success) { showToast(r.message, 'success'); setTimeout(() => location.reload(), 800); } });
    }
}

// Bulk Actions
function updateBulkActions() {
    const checked = document.querySelectorAll('.notif-checkbox:checked');
    const count = checked.length;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('bulkActionsBar').classList.toggle('d-none', count === 0);
    document.getElementById('bulkActionsBar').classList.toggle('d-flex', count > 0);
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.notif-checkbox:checked')).map(cb => parseInt(cb.value));
}

function clearSelection() {
    document.querySelectorAll('.notif-checkbox').forEach(cb => cb.checked = false);
    updateBulkActions();
}

function bulkMarkRead() {
    const ids = getSelectedIds();
    if (ids.length === 0) return;
    if (!confirm(`Mark ${ids.length} notification(s) as read?`)) return;
    
    fetch(`${BASE}/admin/notifications/bulk-mark-read`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: 'ids[]=' + ids.join('&ids[]=')
    })
    .then(r => r.json())
    .then(r => { if (r.success) { showToast(r.message, 'success'); setTimeout(() => location.reload(), 800); } });
}

function bulkDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) return;
    if (!confirm(`Delete ${ids.length} notification(s)? This cannot be undone.`)) return;
    
    fetch(`${BASE}/admin/notifications/bulk-delete`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: 'ids[]=' + ids.join('&ids[]=')
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
