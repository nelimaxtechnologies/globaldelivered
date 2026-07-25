<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Create New Shipment</h5>
        <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-outline-secondary admin-btn admin-btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Shipments
        </a>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/admin/shipments">
        <?= csrf_field() ?>
        
        <!-- Tracking Number -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tracking Number</label>
                <div class="input-group">
                    <input type="text" name="tracking_number" class="form-control" value="<?= htmlspecialchars($trackingNumber) ?>" readonly>
                    <button type="button" class="btn btn-outline-secondary" onclick="this.previousElementSibling.value='GDL' + Math.floor(10000000 + Math.random() * 90000000)">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Customer (optional)</label>
                <select name="customer_id" class="form-select">
                    <option value="">Walk-in Customer</option>
                    <?php foreach ($customers as $c): ?>
                    <option value="<?= $c->id ?>"><?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?> (<?= htmlspecialchars($c->email) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Reference Number</label>
                <input type="text" name="reference_number" class="form-control" placeholder="Customer reference">
            </div>
        </div>
        
        <!-- Sender Information -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-box-arrow-up-right me-2 text-primary"></i>Sender Information</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="sender_name" class="form-control" required placeholder="John Doe">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="sender_email" class="form-control" required placeholder="john@example.com">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                <input type="tel" name="sender_phone" class="form-control" required placeholder="+254729373801">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                <input type="text" name="sender_address" class="form-control" required placeholder="Street address">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                <input type="text" name="sender_city" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">State</label>
                <input type="text" name="sender_state" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                <input type="text" name="sender_country" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Postal Code</label>
                <input type="text" name="sender_postal_code" class="form-control">
            </div>
        </div>
        
        <!-- Recipient Information -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-box-arrow-in-down-right me-2 text-success"></i>Recipient Information</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="recipient_name" class="form-control" required placeholder="Jane Doe">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="recipient_email" class="form-control" placeholder="jane@example.com">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                <input type="tel" name="recipient_phone" class="form-control" required placeholder="+1-555-0124">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                <input type="text" name="recipient_address" class="form-control" required placeholder="Street address">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                <input type="text" name="recipient_city" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">State</label>
                <input type="text" name="recipient_state" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                <input type="text" name="recipient_country" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Postal Code</label>
                <input type="text" name="recipient_postal_code" class="form-control">
            </div>
        </div>
        
        <!-- Package Details -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-box me-2 text-warning"></i>Package Details</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                <select name="service_type" class="form-select" required>
                    <option value="domestic">Domestic</option>
                    <option value="international">International</option>
                    <option value="express" selected>Express</option>
                    <option value="same_day">Same Day</option>
                    <option value="freight">Freight</option>
                    <option value="air_cargo">Air Cargo</option>
                    <option value="sea_freight">Sea Freight</option>
                    <option value="road_transport">Road Transport</option>
                    <option value="warehousing">Warehousing</option>
                    <option value="last_mile">Last Mile</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Package Type</label>
                <select name="package_type" class="form-select">
                    <option value="parcel">Parcel</option>
                    <option value="document">Document</option>
                    <option value="box">Box</option>
                    <option value="pallet">Pallet</option>
                    <option value="container">Container</option>
                    <option value="envelope">Envelope</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Currency</label>
                <select name="currency" class="form-select">
                    <option value="USD">USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="GBP">GBP (£)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Weight (kg) <span class="text-danger">*</span></label>
                <input type="number" name="weight" class="form-control" required step="0.1" min="0.1">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Length (cm)</label>
                <input type="number" name="length" class="form-control" step="0.1" min="0">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Width (cm)</label>
                <input type="number" name="width" class="form-control" step="0.1" min="0">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Height (cm)</label>
                <input type="number" name="height" class="form-control" step="0.1" min="0">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Package contents description"></textarea>
            </div>
        </div>
        
        <!-- Options -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-gear me-2"></i>Options & Schedule</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="form-check"><input type="checkbox" name="is_fragile" class="form-check-input" id="isFragile"><label class="form-check-label" for="isFragile">Fragile</label></div>
            </div>
            <div class="col-md-3">
                <div class="form-check"><input type="checkbox" name="is_insured" class="form-check-input" id="isInsured"><label class="form-check-label" for="isInsured">Insured</label></div>
            </div>
            <div class="col-md-3">
                <div class="form-check"><input type="checkbox" name="signature_required" class="form-check-input" id="sigRequired"><label class="form-check-label" for="sigRequired">Signature Required</label></div>
            </div>
            <div class="col-md-3">
                <div class="form-check"><input type="checkbox" name="is_cod" class="form-check-input" id="isCod"><label class="form-check-label" for="isCod">Cash on Delivery</label></div>
            </div>
            <div class="col-md-4" id="codAmountField" style="display:none;">
                <label class="form-label">COD Amount</label>
                <input type="number" name="cod_amount" class="form-control" step="0.01" min="0">
            </div>
            <div class="col-md-4" id="declaredValueField" style="display:none;">
                <label class="form-label">Declared Value ($)</label>
                <input type="number" name="declared_value" class="form-control" step="0.01" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="current_status_id" class="form-select">
                    <?php foreach ($statuses as $st): ?>
                    <option value="<?= $st->id ?>" <?= $st->sort_order == 1 ? 'selected' : '' ?>><?= htmlspecialchars($st->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Pickup Date</label>
                <input type="date" name="pickup_date" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Expected Delivery</label>
                <input type="date" name="expected_delivery_date" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes"></textarea>
            </div>
        </div>
        
        <!-- Assignment -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-person-badge me-2 text-info"></i>Assignment</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Origin Branch</label>
                <select name="origin_branch_id" class="form-select">
                    <option value="">Select branch...</option>
                    <?php foreach ($branches as $b): ?>
                    <option value="<?= $b->id ?>"><?= htmlspecialchars($b->name) ?> - <?= htmlspecialchars($b->city) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Destination Branch</label>
                <select name="destination_branch_id" class="form-select">
                    <option value="">Select branch...</option>
                    <?php foreach ($branches as $b): ?>
                    <option value="<?= $b->id ?>"><?= htmlspecialchars($b->name) ?> - <?= htmlspecialchars($b->city) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Assign Driver</label>
                <select name="assigned_driver_id" class="form-select">
                    <option value="">Select driver...</option>
                    <?php foreach ($drivers as $d): ?>
                    <option value="<?= $d->id ?>"><?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Assign Vehicle</label>
                <select name="assigned_vehicle_id" class="form-select">
                    <option value="">Select vehicle...</option>
                    <?php foreach ($vehicles as $v): ?>
                    <option value="<?= $v->id ?>"><?= htmlspecialchars($v->name . ' (' . $v->registration_number . ')') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="d-flex gap-2 justify-content-end border-top pt-4">
            <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-secondary admin-btn">Cancel</a>
            <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-check-circle me-1"></i> Create Shipment</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#isInsured').on('change', function() { $('#declaredValueField').toggle(this.checked); });
    $('#isCod').on('change', function() { $('#codAmountField').toggle(this.checked); });
});
</script>
