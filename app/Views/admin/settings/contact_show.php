<!-- ============================================================
     CONTACT SUBMISSION DETAIL - PREMIUM MODERN DESIGN
     ============================================================ -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-envelope-paper me-2"></i>Contact Submission</h4>
        <small class="text-muted">From <?= htmlspecialchars($submission->name) ?> · <?= format_date($submission->created_at, 'M d, Y H:i') ?></small>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary admin-btn" onclick="updateStatus(<?= $submission->id ?>, 'replied')">
            <i class="bi bi-reply me-1"></i> Mark Replied
        </button>
        <button type="button" class="btn btn-outline-secondary admin-btn" onclick="updateStatus(<?= $submission->id ?>, 'archived')">
            <i class="bi bi-archive me-1"></i> Archive
        </button>
        <a href="<?= BASE_URL ?>/admin/contacts" class="btn btn-outline-secondary admin-btn">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Message Card -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($submission->subject) ?></h5>
                    <?php
                    $statusColors = ['new'=>'#0d6efd','read'=>'#ffc107','replied'=>'#198754','archived'=>'#6c757d'];
                    $color = $statusColors[$submission->status] ?? '#6c757d';
                    ?>
                    <span class="badge" style="background:<?=$color?>15;color:<?=$color?>;border:1px solid <?=$color?>30;border-radius:20px;padding:5px 14px;font-weight:600;font-size:0.78rem;">
                        <?= ucfirst($submission->status) ?>
                    </span>
                </div>
                <div class="p-3 mb-0" style="background:#f8f9fa;border-radius:12px;min-height:200px;white-space:pre-wrap;font-size:0.95rem;line-height:1.7;"><?= htmlspecialchars($submission->message) ?></div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Contact Info -->
    <div class="col-lg-4">
        <!-- Sender Info -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius:14px;border-left:4px solid #1a237e;">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3" style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.06em;color:#1a237e;">
                    <i class="bi bi-person me-1"></i> Sender
                </h6>
                <div class="mb-2"><strong>Name:</strong> <?= htmlspecialchars($submission->name) ?></div>
                <div class="mb-2"><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($submission->email) ?>"><?= htmlspecialchars($submission->email) ?></a></div>
                <?php if ($submission->phone): ?>
                <div class="mb-2"><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($submission->phone) ?>"><?= htmlspecialchars($submission->phone) ?></a></div>
                <?php endif; ?>
                <div class="mb-0"><strong>IP:</strong> <code class="small"><?= htmlspecialchars($submission->ip_address ?? 'N/A') ?></code></div>
            </div>
        </div>

        <!-- Status Actions -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius:14px;border-left:4px solid #3949ab;">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3" style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.06em;color:#3949ab;">
                    <i class="bi bi-gear me-1"></i> Actions
                </h6>
                <div class="d-grid gap-2">
                    <?php if ($submission->status !== 'new'): ?>
                    <button class="btn btn-sm w-100" style="border-radius:8px;border:1px solid #0d6efd30;color:#0d6efd;background:#0d6efd08;" onclick="updateStatus(<?= $submission->id ?>, 'new')">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Mark New
                    </button>
                    <?php endif; ?>
                    <?php if ($submission->status !== 'read'): ?>
                    <button class="btn btn-sm w-100" style="border-radius:8px;border:1px solid #ffc10730;color:#856404;background:#ffc10708;" onclick="updateStatus(<?= $submission->id ?>, 'read')">
                        <i class="bi bi-eye me-1"></i> Mark Read
                    </button>
                    <?php endif; ?>
                    <?php if ($submission->status !== 'replied'): ?>
                    <button class="btn btn-sm w-100" style="border-radius:8px;border:1px solid #19875430;color:#198754;background:#19875408;" onclick="updateStatus(<?= $submission->id ?>, 'replied')">
                        <i class="bi bi-reply me-1"></i> Mark Replied
                    </button>
                    <?php endif; ?>
                    <?php if ($submission->status !== 'archived'): ?>
                    <button class="btn btn-sm w-100" style="border-radius:8px;border:1px solid #6c757d30;color:#6c757d;background:#6c757d08;" onclick="updateStatus(<?= $submission->id ?>, 'archived')">
                        <i class="bi bi-archive me-1"></i> Archive
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="card border-0 shadow-sm" style="border-radius:14px;border-left:4px solid #6c757d;">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3" style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.06em;color:#6c757d;">
                    <i class="bi bi-clock me-1"></i> Timestamps
                </h6>
                <div class="mb-1"><strong>Submitted:</strong> <?= format_date($submission->created_at, 'M d, Y H:i:s') ?></div>
                <div class="mb-0"><strong>Updated:</strong> <?= format_date($submission->updated_at, 'M d, Y H:i:s') ?></div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(id, status) {
    const btn = event.currentTarget;
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    btn.disabled = true;

    fetch('<?= BASE_URL ?>/admin/contacts/' + id + '/status', {
        method: 'POST',
        headers: {'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'status=' + status
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            btn.innerHTML = original;
            btn.disabled = false;
        }
    })
    .catch(() => {
        btn.innerHTML = original;
        btn.disabled = false;
    });
}
</script>
