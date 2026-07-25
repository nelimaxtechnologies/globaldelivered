<!-- ============================================================
     RECORD PAYMENT - PREMIUM MODERN DESIGN v2
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-plus-lg me-2"></i>Record Payment</h4>
        <small class="text-muted">Record a new payment transaction against an invoice</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="<?= BASE_URL ?>/admin/payments" id="paymentForm">
            <?= csrf_field() ?>

            <!-- Section: Select Invoice -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">1</div>
                    <h6 class="fw-bold mb-0">Select Invoice</h6>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Invoice <span class="text-danger">*</span></label>
                    <select name="invoice_id" class="form-select select2" id="invoiceSelect" required>
                        <option value="">Choose an invoice to pay...</option>
                        <?php foreach ($invoices as $inv):
                            $remaining = (float)$inv->total - (float)$inv->amount_paid;
                        ?>
                        <option value="<?= $inv->id ?>"
                                data-total="<?= $inv->total ?>"
                                data-currency="<?= $inv->currency ?? 'USD' ?>"
                                data-customer="<?= $inv->customer_id ?>"
                                data-customer-name="<?= htmlspecialchars($inv->customer_name ?? '') ?>"
                                data-status="<?= $inv->status ?>"
                                data-paid="<?= $inv->amount_paid ?>"
                                data-remaining="<?= $remaining ?>"
                                <?= (isset($_GET['invoice_id']) && (int)$_GET['invoice_id'] == $inv->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($inv->invoice_number) ?> — <?= htmlspecialchars($inv->customer_name ?? '') ?> (<?= format_currency($remaining, $inv->currency) ?> remaining)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (empty($invoices)): ?>
                <div class="text-center py-3 text-muted">
                    <i class="bi bi-inbox fs-4 d-block mb-2" style="opacity:0.3;"></i>
                    <small>No invoices pending payment</small>
                    <div class="mt-2">
                        <a href="<?= BASE_URL ?>/admin/invoices/create" class="btn btn-sm btn-outline-primary">Create Invoice</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Section: Payment Method -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">2</div>
                    <h6 class="fw-bold mb-0">Payment Method</h6>
                </div>
                <input type="hidden" name="payment_method" id="paymentMethodHidden" required>
                <div class="row g-2" id="methodCards">
                    <?php
                    $methods = [
                        ['value' => 'cash', 'label' => 'Cash', 'icon' => 'bi-cash-stack', 'color' => '#198754'],
                        ['value' => 'bank', 'label' => 'Bank Transfer', 'icon' => 'bi-bank', 'color' => '#0d6efd'],
                        ['value' => 'stripe', 'label' => 'Stripe', 'icon' => 'bi-credit-card-2-front', 'color' => '#635bff'],
                        ['value' => 'paypal', 'label' => 'PayPal', 'icon' => 'bi-paypal', 'color' => '#003087'],
                        ['value' => 'flutterwave', 'label' => 'Flutterwave', 'icon' => 'bi-globe', 'color' => '#f5a623'],
                        ['value' => 'paystack', 'label' => 'Paystack', 'icon' => 'bi-shield-check', 'color' => '#2a8a6e'],
                        ['value' => 'mpesa', 'label' => 'M-Pesa', 'icon' => 'bi-phone', 'color' => '#4caf50'],
                    ];
                    foreach ($methods as $m): ?>
                    <div class="col-md-3 col-6">
                        <div class="method-card" data-method="<?= $m['value'] ?>" style="border:2px solid #e9ecef;border-radius:10px;padding:14px 10px;text-align:center;cursor:pointer;transition:all 0.2s;background:#fff;">
                            <i class="bi <?= $m['icon'] ?> fs-4 d-block mb-1" style="color:<?= $m['color'] ?>;"></i>
                            <small class="fw-semibold"><?= $m['label'] ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="invalid-feedback" id="methodError" style="display:none;">Please select a payment method</div>
            </div>

            <!-- Section: Payment Details -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#ffc107,#e0a800);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">3</div>
                    <h6 class="fw-bold mb-0">Payment Details</h6>
                </div>
                <div class="row g-3">
                    <!-- Amount -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="currencySymbol">$</span>
                            <input type="number" step="0.01" name="amount" class="form-control form-control-lg" required id="amount" placeholder="0.00" min="0.01" style="font-weight:700;font-size:1.1rem;">
                        </div>
                        <small class="text-muted" id="invoiceTotalHint"></small>
                    </div>

                    <!-- Currency -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Currency</label>
                        <select name="currency" class="form-select" id="currencySelect">
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                            <option value="GBP">GBP (£)</option>
                            <option value="NGN">NGN (₦)</option>
                            <option value="KES">KES (KSh)</option>
                        </select>
                    </div>

                    <!-- Customer -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Customer</label>
                        <select name="customer_id" class="form-select select2" id="customerSelect">
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $c): ?>
                            <option value="<?= $c->id ?>"><?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Transaction ID -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Transaction ID</label>
                        <div class="input-group">
                            <input type="text" name="transaction_id" class="form-control" id="transactionId" placeholder="Auto-generated if blank">
                            <button class="btn btn-outline-secondary" type="button" onclick="generateTransactionId()" title="Generate new ID">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                        <small class="text-muted">Leave blank for auto-generation</small>
                    </div>

                    <!-- Payment Reference -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Payment Reference</label>
                        <input type="text" name="payment_reference" class="form-control" placeholder="Receipt #, reference code...">
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes about this payment..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg admin-btn" id="submitBtn">
                    <i class="bi bi-check-circle me-1"></i> Record Payment
                </button>
                <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-outline-secondary btn-lg admin-btn">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Live Payment Summary -->
        <div class="admin-card mb-4" id="paymentSummary">
            <h6 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Payment Summary</h6>
            <div id="summaryContent">
                <div class="text-center py-3 text-muted">
                    <i class="bi bi-arrow-left fs-4 d-block mb-2" style="opacity:0.3;"></i>
                    <small>Select an invoice to see summary</small>
                </div>
            </div>
        </div>

        <!-- Remaining Balance Tracker -->
        <div class="admin-card mb-4" id="balanceTracker" style="display:none;">
            <h6 class="fw-bold mb-3"><i class="bi bi-speedometer2 me-2"></i>Balance Tracker</h6>
            <div id="balanceContent"></div>
        </div>

        <!-- Tips -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Tips</h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Select invoice to auto-fill amount & currency</small></li>
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Invoice status updates automatically</small></li>
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Shipment payment status syncs too</small></li>
                <li class="mb-0"><small><i class="bi bi-check2 text-success me-2"></i>Press <kbd class="bg-light px-1 rounded">Enter</kbd> to submit</small></li>
            </ul>
        </div>
    </div>
</div>

<style>
    .method-card:hover { border-color: #0d6efd !important; background: #f8f9ff !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .method-card.selected { border-color: #0d6efd !important; background: #0d6efd0d !important; box-shadow: 0 0 0 3px #0d6efd20; }
    .method-card.selected i { transform: scale(1.1); }
    .summary-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f1f3f5; }
    .summary-row:last-child { border-bottom:none; }
    .balance-bar { height:8px; border-radius:4px; background:#e9ecef; overflow:hidden; margin-top:8px; }
    .balance-bar-fill { height:100%; border-radius:4px; transition:width 0.3s ease; }
    kbd { font-size: 0.7rem; }
</style>

<script>
var currencySymbols = { 'USD': '$', 'EUR': '€', 'GBP': '£', 'NGN': '₦', 'KES': 'KSh' };

// Payment Method Cards
$('.method-card').on('click', function() {
    $('.method-card').removeClass('selected');
    $(this).addClass('selected');
    $('#paymentMethodHidden').val($(this).data('method')).trigger('change');
    $('#methodError').hide();
});

// Generate Transaction ID
function generateTransactionId() {
    var id = 'TXN-' + new Date().getFullYear() + String(new Date().getMonth()+1).padStart(2,'0') + String(new Date().getDate()).padStart(2,'0') + '-' + Math.random().toString(36).substring(2,8).toUpperCase();
    $('#transactionId').val(id);
}

// Currency Symbol Update
$('#currencySelect').on('change', function() {
    $('#currencySymbol').text(currencySymbols[$(this).val()] || '$');
    updateSummary();
});

// Invoice Selection
$('#invoiceSelect').on('change', function() {
    var opt = $(this).find('option:selected');
    if (opt.val()) {
        var total = parseFloat(opt.data('total')) || 0;
        var paid = parseFloat(opt.data('paid')) || 0;
        var remaining = parseFloat(opt.data('remaining')) || 0;
        var currency = opt.data('currency') || 'USD';
        var symbol = currencySymbols[currency] || '$';
        var status = opt.data('status') || '';
        var custName = opt.data('customer-name') || '';
        var invoiceNum = opt.text().split('—')[0].trim();

        // Auto-fill
        $('#amount').val(remaining.toFixed(2));
        $('#currencySelect').val(currency).trigger('change');
        $('#currencySymbol').text(symbol);

        // Auto-fill customer
        var customerId = opt.data('customer');
        if (customerId) {
            $('#customerSelect').val(customerId).trigger('change');
        }

        // Show invoice summary
        var paidPct = total > 0 ? Math.min(100, (paid / total) * 100) : 0;
        var statusBadge = getStatusBadge(status);

        $('#summaryContent').html(
            '<div class="summary-row"><small class="text-muted">Invoice</small><small class="fw-semibold">' + invoiceNum + '</small></div>' +
            '<div class="summary-row"><small class="text-muted">Customer</small><small class="fw-semibold">' + custName + '</small></div>' +
            '<div class="summary-row"><small class="text-muted">Total</small><small class="fw-bold">' + symbol + total.toFixed(2) + '</small></div>' +
            '<div class="summary-row"><small class="text-muted">Already Paid</small><small class="text-success fw-semibold">' + symbol + paid.toFixed(2) + '</small></div>' +
            '<div class="summary-row"><small class="text-muted">Balance Due</small><small class="fw-bold" style="color:#dc3545;">' + symbol + remaining.toFixed(2) + '</small></div>' +
            '<div class="summary-row"><small class="text-muted">Status</small>' + statusBadge + '</div>'
        );

        // Balance tracker
        $('#balanceTracker').show();
        var barColor = paidPct >= 100 ? '#198754' : (paidPct > 0 ? '#ffc107' : '#dc3545');
        $('#balanceContent').html(
            '<div class="d-flex justify-content-between mb-1"><small class="text-muted">Paid</small><small class="fw-semibold">' + paidPct.toFixed(0) + '%</small></div>' +
            '<div class="balance-bar"><div class="balance-bar-fill" style="width:' + paidPct + '%;background:' + barColor + ';"></div></div>' +
            '<div class="d-flex justify-content-between mt-2"><small class="text-muted">Paid: ' + symbol + paid.toFixed(2) + '</small><small class="text-muted">Due: ' + symbol + remaining.toFixed(2) + '</small></div>'
        );

        // Update hint
        $('#invoiceTotalHint').html('Balance due: <strong>' + symbol + remaining.toFixed(2) + '</strong>');

    } else {
        $('#summaryContent').html('<div class="text-center py-3 text-muted"><i class="bi bi-arrow-left fs-4 d-block mb-2" style="opacity:0.3;"></i><small>Select an invoice to see summary</small></div>');
        $('#balanceTracker').hide();
        $('#invoiceTotalHint').text('');
    }
});

function getStatusBadge(status) {
    var colors = {
        'sent': '#0d6efd',
        'partially_paid': '#ffc107',
        'overdue': '#dc3545'
    };
    var color = colors[status] || '#6c757d';
    return '<span class="badge rounded-pill" style="background:' + color + '15;color:' + color + ';border:1px solid ' + color + '30;font-weight:600;">' + status.replace('_', ' ') + '</span>';
}

// Live amount validation
$('#amount').on('input', function() {
    updateSummary();
});

function updateSummary() {
    var opt = $('#invoiceSelect').find('option:selected');
    if (!opt.val()) return;

    var remaining = parseFloat(opt.data('remaining')) || 0;
    var amount = parseFloat($('#amount').val()) || 0;
    var currency = $('#currencySelect').val();
    var symbol = currencySymbols[currency] || '$';

    var afterPayment = remaining - amount;
    var hint = '';
    if (amount > 0 && afterPayment > 0) {
        hint = 'Partial payment — ' + symbol + afterPayment.toFixed(2) + ' will remain';
    } else if (amount > 0 && afterPayment <= 0) {
        hint = '<span class="text-success fw-semibold">Full payment — invoice will be marked as paid</span>';
    }
    $('#invoiceTotalHint').html(hint || 'Balance due: <strong>' + symbol + remaining.toFixed(2) + '</strong>');
}

// Form validation
$('#paymentForm').on('submit', function(e) {
    var method = $('#paymentMethodHidden').val();
    if (!method) {
        e.preventDefault();
        $('#methodError').show();
        $('#methodCards').addClass('border border-danger');
        return false;
    }
    return true;
});

// Trigger change if invoice pre-selected
if ($('#invoiceSelect').val()) {
    $('#invoiceSelect').trigger('change');
}
</script>
