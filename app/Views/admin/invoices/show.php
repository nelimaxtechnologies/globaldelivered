<!-- Invoice Detail -->
<?php
$remaining = (float) $invoice->total - $totalPaid;
$collectionPct = $invoice->total > 0 ? round(($totalPaid / $invoice->total) * 100, 1) : 0;

$statusTimeline = ['draft', 'sent', 'partially_paid', 'paid'];
if ($invoice->status === 'overdue') $statusTimeline = ['draft', 'sent', 'overdue'];
if ($invoice->status === 'cancelled') $statusTimeline = ['draft', 'cancelled'];
if ($invoice->status === 'refunded') $statusTimeline = ['draft', 'sent', 'paid', 'refunded'];

$statusMeta = [
    'draft' => ['color' => '#6c757d', 'icon' => 'bi-pencil-square', 'label' => 'Draft'],
    'sent' => ['color' => '#0d6efd', 'icon' => 'bi-send-fill', 'label' => 'Sent'],
    'partially_paid' => ['color' => '#ffc107', 'icon' => 'bi-clock-fill', 'label' => 'Partial'],
    'paid' => ['color' => '#198754', 'icon' => 'bi-check-circle-fill', 'label' => 'Paid'],
    'overdue' => ['color' => '#dc3545', 'icon' => 'bi-exclamation-circle-fill', 'label' => 'Overdue'],
    'cancelled' => ['color' => '#6c757d', 'icon' => 'bi-x-circle-fill', 'label' => 'Cancelled'],
    'refunded' => ['color' => '#0dcaf0', 'icon' => 'bi-arrow-return-left', 'label' => 'Refunded'],
];
$currMeta = $statusMeta[$invoice->status] ?? $statusMeta['draft'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>/admin/invoices" class="btn btn-outline-secondary btn-sm admin-btn">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Invoice <?= htmlspecialchars($invoice->invoice_number) ?></h4>
            <small class="text-muted">Created <?= format_date($invoice->created_at) ?> by <?= htmlspecialchars($invoice->created_by_name ?? 'System') ?></small>
        </div>
    </div>
    <div class="d-flex gap-2">
        <?php if ($invoice->status === 'draft'): ?>
        <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>/mark-sent" class="btn btn-primary admin-btn mark-sent-btn">
            <i class="bi bi-send me-1"></i> Mark Sent
        </a>
        <?php endif; ?>
        <?php if (in_array($invoice->status, ['sent', 'overdue', 'partially_paid'])): ?>
        <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>/mark-paid" class="btn btn-success admin-btn mark-paid-btn">
            <i class="bi bi-check-circle me-1"></i> Mark Paid
        </a>
        <?php endif; ?>
        <?php if (in_array($invoice->status, ['paid'])): ?>
        <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>/mark-refunded" class="btn btn-info admin-btn mark-refunded-btn">
            <i class="bi bi-arrow-return-left me-1"></i> Refund
        </a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>/edit" class="btn btn-outline-primary admin-btn">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <button class="btn btn-outline-danger admin-btn delete-invoice-btn" data-id="<?= $invoice->id ?>" data-number="<?= htmlspecialchars($invoice->invoice_number) ?>">
            <i class="bi bi-trash me-1"></i> Delete
        </button>
    </div>
</div>

<!-- Status + Financial Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card text-center py-4" style="border-top: 4px solid <?= $currMeta['color'] ?>;">
            <div style="width:56px;height:56px;border-radius:14px;background:<?= $currMeta['color'] ?>20;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <i class="bi <?= $currMeta['icon'] ?>" style="font-size:1.5rem;color:<?= $currMeta['color'] ?>;"></i>
            </div>
            <div class="fw-bold fs-5" style="color:<?= $currMeta['color'] ?>;"><?= $currMeta['label'] ?></div>
            <small class="text-muted">Status</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-4" style="border-top: 4px solid #0d6efd;">
            <div style="width:56px;height:56px;border-radius:14px;background:#0d6efd20;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <i class="bi bi-currency-dollar" style="font-size:1.5rem;color:#0d6efd;"></i>
            </div>
            <div class="fw-bold fs-5"><?= format_currency($invoice->total, $invoice->currency) ?></div>
            <small class="text-muted">Total</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-4" style="border-top: 4px solid #198754;">
            <div style="width:56px;height:56px;border-radius:14px;background:#19875420;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <i class="bi bi-check-circle" style="font-size:1.5rem;color:#198754;"></i>
            </div>
            <div class="fw-bold fs-5 text-success"><?= format_currency($totalPaid, $invoice->currency) ?></div>
            <small class="text-muted">Paid (<?= $collectionPct ?>%)</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-4" style="border-top: 4px solid <?= $remaining > 0 ? '#dc3545' : '#198754' ?>;">
            <div style="width:56px;height:56px;border-radius:14px;background:<?= $remaining > 0 ? '#dc3545' : '#198754' ?>20;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <i class="bi <?= $remaining > 0 ? 'bi-exclamation-triangle' : 'bi-check-all' ?>" style="font-size:1.5rem;color:<?= $remaining > 0 ? '#dc3545' : '#198754' ?>;"></i>
            </div>
            <div class="fw-bold fs-5" style="color:<?= $remaining > 0 ? '#dc3545' : '#198754' ?>;"><?= format_currency(max(0, $remaining), $invoice->currency) ?></div>
            <small class="text-muted"><?= $remaining > 0 ? 'Remaining' : 'Settled' ?></small>
        </div>
    </div>
</div>

<!-- Collection Progress -->
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2"></i>Payment Progress</h6>
        <span class="fw-bold" style="color: <?= $collectionPct >= 100 ? '#198754' : ($collectionPct >= 50 ? '#ffc107' : '#dc3545') ?>"><?= $collectionPct ?>%</span>
    </div>
    <div class="progress" style="height:10px;border-radius:5px;">
        <div class="progress-bar" style="width:<?= min(100, $collectionPct) ?>%;background:linear-gradient(90deg, <?= $collectionPct >= 100 ? '#198754,#20c997' : ($collectionPct >= 50 ? '#ffc107,#ffcd38' : '#dc3545,#ff6b6b') ?>);border-radius:5px;"></div>
    </div>
    <div class="d-flex justify-content-between mt-2">
        <small class="text-muted">Paid: <?= format_currency($totalPaid, $invoice->currency) ?></small>
        <small class="text-muted">Due: <?= format_currency($invoice->total, $invoice->currency) ?></small>
    </div>
</div>

<div class="row g-4">
    <!-- Left: Details -->
    <div class="col-lg-8">
        <!-- Status Timeline -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 me-2"></i>Status Timeline</h6>
            <div class="d-flex align-items-center justify-content-between position-relative" style="padding: 10px 0;">
                <div style="position:absolute;top:50%;left:40px;right:40px;height:3px;background:#e9ecef;z-index:0;"></div>
                <?php foreach ($statusTimeline as $idx => $step): ?>
                <?php $sm = $statusMeta[$step] ?? $statusMeta['draft']; ?>
                <div class="text-center position-relative" style="z-index:1;flex:1;">
                    <div style="width:36px;height:36px;border-radius:50%;background:<?= $sm['color'] ?>;color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 6px;font-size:0.85rem;box-shadow:0 2px 8px <?= $sm['color'] ?>40;">
                        <i class="bi <?= $sm['icon'] ?>"></i>
                    </div>
                    <small class="fw-semibold" style="color:<?= $sm['color'] ?>;font-size:0.72rem;"><?= $sm['label'] ?></small>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Customer & Shipment Info -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="admin-card h-100">
                    <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Customer</h6>
                    <?php if ($invoice->customer_name): ?>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#0d6efd,#0a58ca);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;">
                            <?= strtoupper(substr($invoice->customer_name, 0, 2)) ?>
                        </div>
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($invoice->customer_name) ?></div>
                            <small class="text-muted">Customer ID: #<?= $invoice->customer_id ?? 'N/A' ?></small>
                        </div>
                    </div>
                    <?php if ($invoice->customer_email): ?>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-envelope text-muted" style="width:20px;"></i>
                        <small><?= htmlspecialchars($invoice->customer_email) ?></small>
                    </div>
                    <?php endif; ?>
                    <?php if ($invoice->customer_phone): ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-phone text-muted" style="width:20px;"></i>
                        <small><?= htmlspecialchars($invoice->customer_phone) ?></small>
                    </div>
                    <?php endif; ?>
                    <?php else: ?>
                    <p class="text-muted mb-0"><i class="bi bi-person-x me-1"></i> No customer assigned</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="admin-card h-100">
                    <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>Shipment</h6>
                    <?php if ($invoice->tracking_number): ?>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#ffc107,#ff9800);color:#000;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <a href="<?= BASE_URL ?>/admin/shipments/<?= $invoice->shipment_id ?>" class="fw-semibold text-decoration-none">
                                <?= htmlspecialchars($invoice->tracking_number) ?>
                            </a>
                            <div><small class="text-muted"><?= htmlspecialchars($invoice->service_type ?? '') ?></small></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-person text-muted" style="width:20px;"></i>
                        <small>From: <?= htmlspecialchars($invoice->sender_name ?? 'N/A') ?></small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt text-muted" style="width:20px;"></i>
                        <small>To: <?= htmlspecialchars($invoice->recipient_name ?? 'N/A') ?></small>
                    </div>
                    <?php else: ?>
                    <p class="text-muted mb-0"><i class="bi bi-box me-1"></i> No shipment linked</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Invoice Breakdown -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-table me-2"></i>Invoice Breakdown</h6>
            <table class="table table-admin mb-0">
                <tbody>
                    <tr>
                        <td class="text-muted">Subtotal</td>
                        <td class="text-end fw-semibold"><?= format_currency($invoice->subtotal, $invoice->currency) ?></td>
                    </tr>
                    <?php if ($invoice->discount_amount > 0): ?>
                    <tr>
                        <td class="text-muted">Discount</td>
                        <td class="text-end fw-semibold text-danger">-<?= format_currency($invoice->discount_amount, $invoice->currency) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($invoice->tax_amount > 0): ?>
                    <tr>
                        <td class="text-muted">Tax (<?= $invoice->tax_percentage ?>%)</td>
                        <td class="text-end fw-semibold">+<?= format_currency($invoice->tax_amount, $invoice->currency) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr style="background: <?= $currMeta['color'] ?>08;">
                        <td class="fw-bold fs-6">Total</td>
                        <td class="text-end fw-bold fs-5"><?= format_currency($invoice->total, $invoice->currency) ?></td>
                    </tr>
                </tbody>
            </table>
            <?php if (!empty($invoice->notes)): ?>
            <div class="mt-3 p-3" style="background:#f8f9fa;border-radius:8px;">
                <small class="fw-semibold text-muted"><i class="bi bi-sticky me-1"></i>Notes</small>
                <p class="mb-0 mt-1" style="font-size:0.88rem;"><?= nl2br(htmlspecialchars($invoice->notes)) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Payment History -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2"></i>Payment History</h6>
            <?php if (!empty($payments)): ?>
            <div class="table-responsive">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p): ?>
                        <tr>
                            <td><small class="text-muted"><?= format_date($p->created_at, 'M d, Y H:i') ?></small></td>
                            <td><span class="badge bg-light text-dark border"><i class="bi bi-credit-card me-1"></i><?= ucfirst(htmlspecialchars($p->payment_method)) ?></span></td>
                            <td><small class="text-muted"><?= htmlspecialchars($p->transaction_id ?? $p->payment_reference ?? '-') ?></small></td>
                            <td class="text-end fw-bold text-success"><?= format_currency($p->amount, $p->currency ?? $invoice->currency) ?></td>
                            <td><span class="badge rounded-pill bg-<?= $p->status === 'completed' ? 'success' : ($p->status === 'pending' ? 'warning text-dark' : 'danger') ?>"><?= ucfirst($p->status) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <div style="width:60px;height:60px;border-radius:16px;background:#f4f6f9;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-credit-card" style="font-size:1.8rem;opacity:0.3;"></i>
                </div>
                <p class="text-muted mb-2">No payments recorded yet</p>
                <?php if (in_array($invoice->status, ['sent', 'overdue', 'partially_paid'])): ?>
                <a href="<?= BASE_URL ?>/admin/payments/create?invoice_id=<?= $invoice->id ?>" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-plus me-1"></i>Record First Payment
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Actions Sidebar -->
    <div class="col-lg-4">
        <!-- Due Date -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-calendar-event me-2"></i>Due Date</h6>
            <?php
            $dueClass = 'text-muted';
            $dueMsg = '';
            if ($invoice->due_date && !in_array($invoice->status, ['paid', 'cancelled', 'refunded'])) {
                $due = new DateTime($invoice->due_date);
                $now = new DateTime();
                $diff = $now->diff($due);
                if ($due < $now) {
                    $dueClass = 'text-danger';
                    $dueMsg = $diff->days . ' days overdue';
                } elseif ($diff->days <= 3) {
                    $dueClass = 'text-warning';
                    $dueMsg = 'Due in ' . $diff->days . ' day' . ($diff->days > 1 ? 's' : '');
                } else {
                    $dueMsg = 'Due in ' . $diff->days . ' days';
                }
            }
            ?>
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:<?= $invoice->due_date ? ($dueClass === 'text-danger' ? '#dc354520' : ($dueClass === 'text-warning' ? '#ffc10720' : '#f4f6f9')) : '#f4f6f9' ?>;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-calendar3 <?= $dueClass ?>" style="font-size:1.3rem;"></i>
                </div>
                <div>
                    <div class="fw-bold <?= $dueClass ?>" style="font-size:1.1rem;"><?= $invoice->due_date ? format_date($invoice->due_date) : 'Not set' ?></div>
                    <?php if ($dueMsg): ?>
                    <small class="<?= $dueClass ?>"><?= $dueMsg ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
            <div class="d-grid gap-2">
                <?php if ($invoice->status === 'draft'): ?>
                <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>/mark-sent" class="btn btn-primary mark-sent-btn text-start">
                    <i class="bi bi-send me-2"></i>Send Invoice to Customer
                </a>
                <?php endif; ?>
                <?php if (in_array($invoice->status, ['sent', 'overdue', 'partially_paid'])): ?>
                <a href="<?= BASE_URL ?>/admin/payments/create?invoice_id=<?= $invoice->id ?>" class="btn btn-success text-start">
                    <i class="bi bi-credit-card me-2"></i>Record Payment
                </a>
                <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>/mark-paid" class="btn btn-outline-success mark-paid-btn text-start">
                    <i class="bi bi-check-circle me-2"></i>Mark Fully Paid
                </a>
                <?php endif; ?>
                <?php if ($invoice->status === 'paid'): ?>
                <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>/mark-refunded" class="btn btn-outline-info mark-refunded-btn text-start">
                    <i class="bi bi-arrow-return-left me-2"></i>Mark as Refunded
                </a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>/edit" class="btn btn-outline-primary text-start">
                    <i class="bi bi-pencil me-2"></i>Edit Invoice
                </a>
            </div>
        </div>

        <!-- Invoice Metadata -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Details</h6>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Invoice #</small>
                <small class="fw-semibold"><?= htmlspecialchars($invoice->invoice_number) ?></small>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Created</small>
                <small class="fw-semibold"><?= format_date($invoice->created_at, 'M d, Y H:i') ?></small>
            </div>
            <?php if ($invoice->updated_at && $invoice->updated_at !== $invoice->created_at): ?>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Updated</small>
                <small class="fw-semibold"><?= format_date($invoice->updated_at, 'M d, Y H:i') ?></small>
            </div>
            <?php endif; ?>
            <?php if ($invoice->paid_at): ?>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Paid On</small>
                <small class="fw-semibold text-success"><?= format_date($invoice->paid_at, 'M d, Y H:i') ?></small>
            </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Currency</small>
                <small class="fw-semibold"><?= htmlspecialchars($invoice->currency) ?></small>
            </div>
            <div class="d-flex justify-content-between py-2">
                <small class="text-muted">Created By</small>
                <small class="fw-semibold"><?= htmlspecialchars($invoice->created_by_name ?? 'System') ?></small>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mark-sent-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('href');
            Swal.fire({
                title: 'Mark as Sent?',
                text: 'This invoice will be marked as sent to the customer.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'Cancel'
            }).then(function(result) {
                if (result.isConfirmed) window.location = url;
            });
        });
    });

    document.querySelectorAll('.mark-paid-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('href');
            Swal.fire({
                title: 'Mark as Paid?',
                text: 'This invoice will be marked as fully paid.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark paid!',
                cancelButtonText: 'Cancel'
            }).then(function(result) {
                if (result.isConfirmed) window.location = url;
            });
        });
    });

    document.querySelectorAll('.mark-refunded-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('href');
            Swal.fire({
                title: 'Mark as Refunded?',
                text: 'This invoice will be marked as refunded.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0dcaf0',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, refund!',
                cancelButtonText: 'Cancel'
            }).then(function(result) {
                if (result.isConfirmed) window.location = url;
            });
        });
    });

    document.querySelectorAll('.delete-invoice-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var number = this.dataset.number;
            Swal.fire({
                title: 'Delete Invoice?',
                html: 'Are you sure you want to delete <strong>' + number + '</strong>?<br><small class="text-danger">This will also delete all associated payments. This action cannot be undone.</small>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(function(result) {
                if (result.isConfirmed) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= BASE_URL ?>/admin/invoices/' + id;
                    form.innerHTML = '<input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="<?= csrf_token() ?>">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
