<!-- ============================================================
     PAYMENT DETAILS - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Payment Details</h4>
        <small class="text-muted">Transaction: <?= htmlspecialchars($payment->transaction_id ?? 'N/A') ?></small>
    </div>
    <div class="d-flex gap-2">
        <?php
        $statusConfig = [
            'completed' => ['color' => '#198754', 'bg' => '#19875415', 'border' => '#19875430', 'icon' => 'bi-check-circle-fill'],
            'pending' => ['color' => '#ffc107', 'bg' => '#ffc10715', 'border' => '#ffc10730', 'icon' => 'bi-clock-fill'],
            'failed' => ['color' => '#dc3545', 'bg' => '#dc354515', 'border' => '#dc354530', 'icon' => 'bi-x-circle-fill'],
            'refunded' => ['color' => '#0dcaf0', 'bg' => '#0dcaf015', 'border' => '#0dcaf030', 'icon' => 'bi-arrow-return-left'],
        ];
        $cfg = $statusConfig[$payment->status] ?? ['color' => '#6c757d', 'bg' => '#6c757d15', 'border' => '#6c757d30', 'icon' => 'bi-circle'];
        ?>
        <span class="badge rounded-pill fs-6" style="background: <?= $cfg['bg'] ?>; color: <?= $cfg['color'] ?>; border: 1px solid <?= $cfg['border'] ?>; font-weight: 600;">
            <i class="bi <?= $cfg['icon'] ?> me-1"></i>
            <?= ucfirst($payment->status) ?>
        </span>
        <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-outline-secondary admin-btn">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Main Details -->
    <div class="col-lg-8">
        <div class="admin-card">
            <!-- Amount Card -->
            <div class="text-center py-4 mb-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 12px;">
                <small class="text-muted text-uppercase fw-semibold">Payment Amount</small>
                <div class="fw-bold mt-1" style="font-size: 2.5rem; color: <?= $payment->status === 'completed' ? '#198754' : ($payment->status === 'refunded' ? '#0dcaf0' : '#212529') ?>;">
                    <?= format_currency($payment->amount, $payment->currency) ?>
                </div>
                <small class="text-muted"><?= ucwords(str_replace('_', ' ', $payment->payment_method)) ?></small>
            </div>

            <div class="row g-4">
                <!-- Customer Info -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:linear-gradient(135deg,#0d6efd,#0a58ca);color:#fff;font-weight:600;font-size:0.9rem;">
                            <?php
                            $nameParts = explode(' ', trim($payment->customer_name ?? 'N'));
                            $initials = '';
                            foreach (array_slice($nameParts, 0, 2) as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                            echo $initials ?: 'N';
                            ?>
                        </div>
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($payment->customer_name ?? 'N/A') ?></div>
                            <small class="text-muted">Customer</small>
                        </div>
                    </div>
                    <?php if ($payment->customer_email): ?>
                    <div class="mb-2"><small><i class="bi bi-envelope me-2 text-muted"></i><?= htmlspecialchars($payment->customer_email) ?></small></div>
                    <?php endif; ?>
                    <?php if ($payment->customer_phone): ?>
                    <div class="mb-2"><small><i class="bi bi-phone me-2 text-muted"></i><?= htmlspecialchars($payment->customer_phone) ?></small></div>
                    <?php endif; ?>
                </div>

                <!-- Transaction Info -->
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Transaction Details</h6>
                    <div class="mb-2">
                        <small class="text-muted">Transaction ID</small>
                        <div class="fw-semibold"><?= htmlspecialchars($payment->transaction_id ?? 'N/A') ?></div>
                    </div>
                    <?php if ($payment->payment_reference): ?>
                    <div class="mb-2">
                        <small class="text-muted">Reference</small>
                        <div class="fw-semibold"><?= htmlspecialchars($payment->payment_reference) ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="mb-2">
                        <small class="text-muted">Processed By</small>
                        <div class="fw-semibold"><?= htmlspecialchars($payment->processed_by_name ?? 'N/A') ?></div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Processed At</small>
                        <div class="fw-semibold"><?= $payment->processed_at ? format_date($payment->processed_at) : format_date($payment->created_at) ?></div>
                    </div>
                </div>
            </div>

            <!-- Linked Records -->
            <hr class="my-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted text-uppercase small mb-2">Linked Invoice</h6>
                    <?php if ($payment->invoice_number): ?>
                    <a href="<?= BASE_URL ?>/admin/invoices/<?= $payment->invoice_id ?>" class="text-decoration-none">
                        <span class="badge bg-light text-dark border fs-6 p-2">
                            <i class="bi bi-receipt me-2"></i><?= htmlspecialchars($payment->invoice_number) ?>
                        </span>
                    </a>
                    <?php else: ?>
                    <small class="text-muted">No linked invoice</small>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted text-uppercase small mb-2">Linked Shipment</h6>
                    <?php if ($payment->tracking_number): ?>
                    <a href="<?= BASE_URL ?>/admin/shipments/<?= $payment->shipment_id ?? '' ?>" class="text-decoration-none">
                        <span class="badge bg-light text-dark border fs-6 p-2">
                            <i class="bi bi-box-seam me-2"></i><?= htmlspecialchars($payment->tracking_number) ?>
                        </span>
                    </a>
                    <?php else: ?>
                    <small class="text-muted">No linked shipment</small>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($payment->notes)): ?>
            <hr class="my-4">
            <h6 class="fw-bold text-muted text-uppercase small mb-2">Notes</h6>
            <p class="mb-0"><?= nl2br(htmlspecialchars($payment->notes)) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Timeline -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Timeline</h6>
            <div class="timeline">
                <div class="d-flex gap-3 mb-3">
                    <div class="text-center" style="width:40px;">
                        <div style="width:12px;height:12px;border-radius:50%;background:<?= $payment->status === 'completed' ? '#198754' : ($payment->status === 'pending' ? '#ffc107' : '#dc3545') ?>;margin:4px auto;"></div>
                        <div style="width:2px;height:30px;background:#e9ecef;margin:0 auto;"></div>
                    </div>
                    <div>
                        <small class="fw-semibold text-success">Payment <?= ucfirst($payment->status) ?></small>
                        <div><small class="text-muted"><?= format_date($payment->created_at, 'M d, Y H:i') ?></small></div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="text-center" style="width:40px;">
                        <div style="width:12px;height:12px;border-radius:50%;background:#3498db;margin:4px auto;"></div>
                    </div>
                    <div>
                        <small class="fw-semibold">Payment Created</small>
                        <div><small class="text-muted"><?= format_date($payment->created_at, 'M d, Y H:i') ?></small></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
            <div class="d-grid gap-2">
                <?php if ($payment->invoice_id): ?>
                <a href="<?= BASE_URL ?>/admin/invoices/<?= $payment->invoice_id ?>" class="btn btn-outline-primary admin-btn">
                    <i class="bi bi-receipt me-1"></i> View Invoice
                </a>
                <?php endif; ?>
                <?php if ($payment->shipment_id): ?>
                <a href="<?= BASE_URL ?>/admin/shipments/<?= $payment->shipment_id ?>" class="btn btn-outline-primary admin-btn">
                    <i class="bi bi-box-seam me-1"></i> View Shipment
                </a>
                <?php endif; ?>
                <button class="btn btn-outline-danger admin-btn" onclick="deletePayment(<?= $payment->id ?>, '<?= htmlspecialchars(addslashes($payment->transaction_id ?? '')) ?>')">
                    <i class="bi bi-trash me-1"></i> Delete Payment
                </button>
                <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-outline-secondary admin-btn">
                    <i class="bi bi-list-ul me-1"></i> All Payments
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline { position: relative; }
</style>

<script>
function deletePayment(id, txn) {
    Swal.fire({
        title: 'Delete Payment?',
        text: 'Delete transaction "' + txn + '"? This will recalculate the linked invoice status.',
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
            form.action = '<?= BASE_URL ?>/admin/payments/' + id;
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_csrf_token';
                csrfInput.value = csrfMeta.getAttribute('content');
                form.appendChild(csrfInput);
            }
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
