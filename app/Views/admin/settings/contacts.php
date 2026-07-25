<!-- ============================================================
     CONTACT SUBMISSIONS - PREMIUM MODERN DESIGN
     ============================================================ -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-envelope-paper me-2"></i>Contact Submissions</h4>
        <small class="text-muted">View and manage contact form messages</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/settings" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> All Settings
    </a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;border-left:4px solid #6c757d;">
            <div class="card-body p-3 text-center">
                <div class="fw-bold fs-4 mb-0"><?= number_format($stats->total ?? 0) ?></div>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;border-left:4px solid #0d6efd;">
            <div class="card-body p-3 text-center">
                <div class="fw-bold fs-4 mb-0" style="color:#0d6efd;"><?= $stats->new_count ?? 0 ?></div>
                <small class="text-muted">New</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;border-left:4px solid #ffc107;">
            <div class="card-body p-3 text-center">
                <div class="fw-bold fs-4 mb-0" style="color:#ffc107;"><?= $stats->read ?? 0 ?></div>
                <small class="text-muted">Read</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;border-left:4px solid #198754;">
            <div class="card-body p-3 text-center">
                <div class="fw-bold fs-4 mb-0" style="color:#198754;"><?= $stats->replied ?? 0 ?></div>
                <small class="text-muted">Replied</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
    <div class="card-body p-3">
        <form method="GET" action="<?= BASE_URL ?>/admin/contacts" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Search</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Name, email, subject..." value="<?= htmlspecialchars($filters['search']) ?>" style="border-radius:0 10px 10px 0;">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select" style="border-radius:10px;">
                    <option value="">All Statuses</option>
                    <option value="new" <?= $filters['status'] === 'new' ? 'selected' : '' ?>>New</option>
                    <option value="read" <?= $filters['status'] === 'read' ? 'selected' : '' ?>>Read</option>
                    <option value="replied" <?= $filters['status'] === 'replied' ? 'selected' : '' ?>>Replied</option>
                    <option value="archived" <?= $filters['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary" style="border-radius:10px;"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
            <div class="col-md-2 d-grid">
                <a href="<?= BASE_URL ?>/admin/contacts" class="btn btn-outline-secondary" style="border-radius:10px;">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr style="background:linear-gradient(135deg,#1a237e,#283593);color:#fff;">
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;">Name</th>
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;">Email</th>
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;">Subject</th>
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;">Status</th>
                    <th style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;">Date</th>
                    <th class="text-center" style="padding:12px 14px;border:none;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($submissions)): ?>
                    <?php foreach ($submissions as $sub): ?>
                    <tr style="border-bottom:1px solid #f1f3f5;<?= $sub->status === 'new' ? 'background:rgba(13,110,253,0.03);' : '' ?>">
                        <td style="padding:10px 14px;">
                            <span class="fw-semibold"><?= htmlspecialchars($sub->name) ?></span>
                            <?php if ($sub->phone): ?>
                            <br><small class="text-muted"><?= htmlspecialchars($sub->phone) ?></small>
                            <?php endif; ?>
                        </td>
                        <td style="padding:10px 14px;">
                            <small><?= htmlspecialchars($sub->email) ?></small>
                        </td>
                        <td style="padding:10px 14px;">
                            <span class="text-truncate d-block" style="max-width:200px;"><?= htmlspecialchars($sub->subject) ?></span>
                        </td>
                        <td style="padding:10px 14px;">
                            <?php
                            $statusColors = ['new'=>'#0d6efd','read'=>'#ffc107','replied'=>'#198754','archived'=>'#6c757d'];
                            $color = $statusColors[$sub->status] ?? '#6c757d';
                            ?>
                            <span class="badge" style="background:<?=$color?>15;color:<?=$color?>;border:1px solid <?=$color?>30;border-radius:20px;padding:4px 12px;font-weight:600;font-size:0.72rem;">
                                <?= ucfirst($sub->status) ?>
                            </span>
                        </td>
                        <td style="padding:10px 14px;white-space:nowrap;">
                            <small class="text-muted"><?= format_date($sub->created_at, 'M d, Y H:i') ?></small>
                        </td>
                        <td style="padding:10px 14px;" class="text-center">
                            <a href="<?= BASE_URL ?>/admin/contacts/<?= $sub->id ?>" class="btn btn-sm" style="border-radius:8px;border:1px solid rgba(26,35,126,0.15);color:#1a237e;padding:4px 10px;">
                                <i class="bi bi-eye me-1"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-envelope-open fs-1 d-block mb-3" style="opacity:0.3;"></i>
                                <h6 class="fw-bold mb-1">No submissions found</h6>
                                <p class="mb-0 small">Contact form messages will appear here.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination->totalPages > 1): ?>
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 pb-3 px-4" style="border-top:1px solid #f1f3f5;">
        <small class="text-muted">
            Showing <?= (($pagination->page - 1) * $pagination->perPage) + 1 ?>–<?= min($pagination->page * $pagination->perPage, $pagination->totalItems) ?>
            of <?= number_format($pagination->total) ?>
        </small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=1&status=<?= urlencode($filters['status']) ?>&search=<?= urlencode($filters['search']) ?>" style="border-radius:20px;margin:0 2px;border:none;color:#1a237e;"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php for ($p = max(1, $pagination->page - 2); $p <= min($pagination->totalPages, $pagination->page + 2); $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>&status=<?= urlencode($filters['status']) ?>&search=<?= urlencode($filters['search']) ?>" style="border-radius:20px;margin:0 2px;border:none;<?= $p === $pagination->page ? 'background:linear-gradient(135deg,#1a237e,#3949ab);color:#fff;' : 'color:#1a237e;' ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination->totalPages ?>&status=<?= urlencode($filters['status']) ?>&search=<?= urlencode($filters['search']) ?>" style="border-radius:20px;margin:0 2px;border:none;color:#1a237e;"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
