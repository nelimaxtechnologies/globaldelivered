<!-- Edit Invoice -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-pencil me-2"></i>Edit Invoice</h4>
        <small class="text-muted"><?= htmlspecialchars($invoice->invoice_number) ?></small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>" class="btn btn-outline-primary admin-btn">
            <i class="bi bi-eye me-1"></i> View
        </a>
        <a href="<?= BASE_URL ?>/admin/invoices" class="btn btn-outline-secondary admin-btn">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-text me-2"></i>Invoice Details</h6>
            <form method="POST" action="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>" id="invoiceForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Customer</label>
                        <select name="customer_id" class="form-select select2">
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $c): ?>
                            <option value="<?= $c->id ?>" <?= $invoice->customer_id == $c->id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?> (<?= htmlspecialchars($c->email) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Shipment</label>
                        <select name="shipment_id" class="form-select select2" id="shipmentSelect">
                            <option value="">No Shipment</option>
                            <?php foreach ($shipments as $s): ?>
                            <option value="<?= $s->id ?>" <?= $invoice->shipment_id == $s->id ? 'selected' : '' ?>
                                    data-total="<?= $s->grand_total ?>" data-currency="<?= $s->currency ?>">
                                <?= htmlspecialchars($s->label) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <?php
                            $statuses = [
                                'draft' => ['Draft', '#6c757d'],
                                'sent' => ['Sent', '#0d6efd'],
                                'paid' => ['Paid', '#198754'],
                                'partially_paid' => ['Partially Paid', '#ffc107'],
                                'overdue' => ['Overdue', '#dc3545'],
                                'cancelled' => ['Cancelled', '#6c757d'],
                                'refunded' => ['Refunded', '#0dcaf0'],
                            ];
                            foreach ($statuses as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $invoice->status === $val ? 'selected' : '' ?>>
                                <?= $label[0] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Currency</label>
                        <select name="currency" class="form-select">
                            <?php foreach (['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'NGN' => '₦', 'KES' => 'KSh'] as $code => $sym): ?>
                            <option value="<?= $code ?>" <?= $invoice->currency === $code ? 'selected' : '' ?>><?= $code ?> (<?= $sym ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="<?= $invoice->due_date ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Subtotal</label>
                        <input type="number" step="0.01" name="subtotal" class="form-control" id="subtotal" value="<?= $invoice->subtotal ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tax %</label>
                        <input type="number" step="0.01" name="tax_percentage" class="form-control" id="taxPercent" value="<?= $invoice->tax_percentage ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tax Amount</label>
                        <input type="number" step="0.01" name="tax_amount" class="form-control" id="taxAmount" value="<?= $invoice->tax_amount ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Discount</label>
                        <input type="number" step="0.01" name="discount_amount" class="form-control" id="discount" value="<?= $invoice->discount_amount ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Total</label>
                        <input type="number" step="0.01" name="total" class="form-control" id="total" value="<?= $invoice->total ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Payment terms, notes..."><?= htmlspecialchars($invoice->notes ?? '') ?></textarea>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-save me-1"></i> Update Invoice</button>
                        <a href="<?= BASE_URL ?>/admin/invoices/<?= $invoice->id ?>" class="btn btn-outline-secondary admin-btn ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Quick Info</h6>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Invoice Number</small>
                <small class="fw-semibold"><?= htmlspecialchars($invoice->invoice_number) ?></small>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Created</small>
                <small class="fw-semibold"><?= format_date($invoice->created_at) ?></small>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Current Status</small>
                <?php
                $statusColors = [
                    'paid' => '#198754', 'sent' => '#0d6efd', 'overdue' => '#dc3545',
                    'partially_paid' => '#ffc107', 'cancelled' => '#6c757d', 'refunded' => '#0dcaf0', 'draft' => '#6c757d',
                ];
                $sc = $statusColors[$invoice->status] ?? '#6c757d';
                ?>
                <span class="badge rounded-pill" style="background: <?= $sc ?>20; color: <?= $sc ?>; font-weight: 600;">
                    <?= str_replace('_', ' ', ucfirst($invoice->status)) ?>
                </span>
            </div>
            <div class="d-flex justify-content-between py-2">
                <small class="text-muted">Total</small>
                <small class="fw-bold fs-6"><?= format_currency($invoice->total, $invoice->currency) ?></small>
            </div>
        </div>

        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-calculator me-2"></i>Calculation Preview</h6>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Subtotal</small>
                <small class="fw-semibold" id="previewSubtotal"><?= format_currency($invoice->subtotal, $invoice->currency) ?></small>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Tax (<span id="previewTaxPct"><?= $invoice->tax_percentage ?></span>%)</small>
                <small class="fw-semibold" id="previewTax">+<?= format_currency($invoice->tax_amount, $invoice->currency) ?></small>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Discount</small>
                <small class="fw-semibold text-danger" id="previewDiscount">-<?= format_currency($invoice->discount_amount, $invoice->currency) ?></small>
            </div>
            <div class="d-flex justify-content-between py-2">
                <small class="fw-bold">Total</small>
                <small class="fw-bold fs-5" id="previewTotal"><?= format_currency($invoice->total, $invoice->currency) ?></small>
            </div>
        </div>
    </div>
</div>

<script>
$('#taxPercent, #subtotal, #discount').on('input', function() {
    var subtotal = parseFloat($('#subtotal').val()) || 0;
    var taxPct = parseFloat($('#taxPercent').val()) || 0;
    var discount = parseFloat($('#discount').val()) || 0;
    var taxAmt = subtotal * (taxPct / 100);
    $('#taxAmount').val(taxAmt.toFixed(2));
    var total = subtotal + taxAmt - discount;
    $('#total').val(total.toFixed(2));

    $('#previewSubtotal').text('$' + subtotal.toFixed(2));
    $('#previewTaxPct').text(taxPct);
    $('#previewTax').text('+$' + taxAmt.toFixed(2));
    $('#previewDiscount').text('-$' + discount.toFixed(2));
    $('#previewTotal').text('$' + total.toFixed(2));
});
</script>
