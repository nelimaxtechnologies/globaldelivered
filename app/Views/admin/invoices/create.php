<!-- Create Invoice -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-plus-lg me-2"></i>Create Invoice</h4>
        <small class="text-muted">Generate a new invoice for a customer or shipment</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/invoices" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> Back to Invoices
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-plus me-2"></i>Invoice Information</h6>
            <form method="POST" action="<?= BASE_URL ?>/admin/invoices" id="invoiceForm">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Invoice Number <span class="text-danger">*</span></label>
                        <input type="text" name="invoice_number" class="form-control" required value="<?= htmlspecialchars($nextNumber) ?>" placeholder="INV-XXXXXXXX-XXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Customer</label>
                        <select name="customer_id" class="form-select select2">
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $c): ?>
                            <option value="<?= $c->id ?>"><?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?> (<?= htmlspecialchars($c->email) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Shipment</label>
                        <select name="shipment_id" class="form-select select2" id="shipmentSelect">
                            <option value="">No Shipment</option>
                            <?php foreach ($shipments as $s): ?>
                            <option value="<?= $s->id ?>" data-total="<?= $s->grand_total ?>" data-currency="<?= $s->currency ?>"><?= htmlspecialchars($s->label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12"><hr></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Currency</label>
                        <select name="currency" class="form-select">
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                            <option value="GBP">GBP (£)</option>
                            <option value="NGN">NGN (₦)</option>
                            <option value="KES">KES (KSh)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Subtotal <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="subtotal" class="form-control" required id="subtotal" value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tax %</label>
                        <input type="number" step="0.01" name="tax_percentage" class="form-control" id="taxPercent" value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tax Amount</label>
                        <input type="number" step="0.01" name="tax_amount" class="form-control" id="taxAmount" value="0" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Discount</label>
                        <input type="number" step="0.01" name="discount_amount" class="form-control" id="discount" value="0">
                    </div>
                    <div class="col-12"><hr></div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Total <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="total" class="form-control fs-5 fw-bold" required id="total" value="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Payment terms, special instructions..."></textarea>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-check-lg me-1"></i> Create Invoice</button>
                        <a href="<?= BASE_URL ?>/admin/invoices" class="btn btn-outline-secondary admin-btn ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-calculator me-2"></i>Calculation Preview</h6>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Subtotal</small>
                <small class="fw-semibold" id="previewSubtotal">$0.00</small>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Tax (<span id="previewTaxPct">0</span>%)</small>
                <small class="fw-semibold" id="previewTax">+$0.00</small>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <small class="text-muted">Discount</small>
                <small class="fw-semibold text-danger" id="previewDiscount">-$0.00</small>
            </div>
            <div class="d-flex justify-content-between py-2 mt-2" style="background:#f8f9fa;border-radius:8px;padding:10px;">
                <span class="fw-bold">Total</span>
                <span class="fw-bold fs-5" id="previewTotal">$0.00</span>
            </div>
        </div>

        <div class="admin-card mt-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i>Tips</h6>
            <ul class="mb-0" style="font-size:0.82rem;">
                <li class="mb-2">Select a shipment to auto-fill the subtotal from its grand total.</li>
                <li class="mb-2">Set status to <strong>Sent</strong> to immediately mark the invoice as delivered.</li>
                <li class="mb-2">Due date defaults to 30 days from today.</li>
                <li class="mb-0">Add notes for payment terms or special instructions.</li>
            </ul>
        </div>
    </div>
</div>

<script>
$('#taxPercent, #subtotal, #discount').on('input', function() {
    var subtotal = parseFloat($('#subtotal').val()) || 0;
    var taxPct = parseFloat($('#taxPercent').val()) || 0;
    var discount = parseFloat($('#discount').val()) || 0;
    var taxAmt = subtotal * (taxPct / 100);
    var total = subtotal + taxAmt - discount;
    $('#taxAmount').val(taxAmt.toFixed(2));
    $('#total').val(total.toFixed(2));

    $('#previewSubtotal').text('$' + subtotal.toFixed(2));
    $('#previewTaxPct').text(taxPct);
    $('#previewTax').text('+$' + taxAmt.toFixed(2));
    $('#previewDiscount').text('-$' + discount.toFixed(2));
    $('#previewTotal').text('$' + total.toFixed(2));
});

$('#shipmentSelect').on('change', function() {
    var opt = $(this).find('option:selected');
    if (opt.val()) {
        $('#subtotal').val(opt.data('total')).trigger('input');
    }
});

// Trigger initial calculation
$('#subtotal').trigger('input');
</script>
