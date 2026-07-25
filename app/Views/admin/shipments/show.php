<style>
    .premium-shipment-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .premium-shipment-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.10);
    }
    .premium-shipment-card .card-body {
        padding: 1.5rem;
    }
    .shipment-header-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        color: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(15,52,96,0.3);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .shipment-header-card .tracking-number {
        font-size: 1.8rem;
        font-weight: 800;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    .shipment-header-card .header-meta {
        opacity: 0.85;
        font-size: 0.9rem;
    }
    .shipment-header-card .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.5rem 1.2rem;
        transition: all 0.2s;
    }
    .shipment-header-card .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .status-badge-premium {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .icon-box-gradient {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #fff;
        flex-shrink: 0;
    }
    .icon-box-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    .icon-box-success {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }
    .icon-box-info {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }
    .icon-box-warning {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }
    .icon-box-dark {
        background: linear-gradient(135deg, #2d3436, #636e72);
    }
    .detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #8892b0;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .detail-value {
        font-weight: 700;
        color: #1a1a2e;
        font-size: 0.95rem;
    }
    .grand-total-display {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        border-radius: 14px;
        padding: 1.2rem;
        color: #fff;
        text-align: center;
    }
    .grand-total-display .total-amount {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }
    .premium-form-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .premium-form-card .form-header {
        padding: 1rem 1.5rem;
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .premium-form-card .form-body {
        padding: 1.5rem;
    }
    .premium-form-card .form-control,
    .premium-form-card .form-select {
        border-radius: 10px;
        border: 2px solid #e8ecf1;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .premium-form-card .form-control:focus,
    .premium-form-card .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
    }
    .premium-form-card .btn {
        border-radius: 10px;
        font-weight: 700;
        padding: 0.65rem;
        letter-spacing: 0.3px;
        transition: all 0.2s;
    }
    .premium-form-card .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .timeline-entry-premium {
        position: relative;
        padding-left: 28px;
        margin-bottom: 1.2rem;
    }
    .timeline-entry-premium::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 28px;
        bottom: -8px;
        width: 2px;
        background: #e8ecf1;
    }
    .timeline-entry-premium:last-child::before {
        display: none;
    }
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px currentColor;
    }
    .map-container {
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    .map-container .map-inner {
        height: 280px;
        border-radius: 14px;
    }
    .info-item {
        padding: 0.6rem 0;
        border-bottom: 1px solid #f0f2f7;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .doc-item {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        background: #f8f9fc;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.2s;
    }
    .doc-item:hover {
        background: #eef1f8;
    }
    .invoice-row {
        transition: background 0.2s;
    }
    .invoice-row:hover {
        background: #f8f9fc;
    }
    .modal-content {
        border-radius: 14px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .modal-header {
        border-radius: 14px 14px 0 0;
        padding: 1.2rem 1.5rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        border-radius: 0 0 14px 14px;
        padding: 1rem 1.5rem;
    }
</style>

<!-- Shipment Header -->
<div class="shipment-header-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <div class="tracking-number"><?= htmlspecialchars($shipment->tracking_number) ?></div>
            <div class="mb-2">
                <span class="status-badge-premium" style="background: <?= $shipment->status_color ?? '#6c757d' ?>;">
                    <?= htmlspecialchars($shipment->status_name ?? $shipment->status) ?>
                </span>
            </div>
            <div class="header-meta">
                <span class="me-3"><i class="bi bi-calendar3 me-1"></i> Created: <?= format_date($shipment->created_at) ?></span>
                <?php if ($shipment->expected_delivery_date): ?>
                <span><i class="bi bi-clock me-1"></i> ETA: <?= format_date($shipment->expected_delivery_date) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>/edit" class="btn btn-light">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>/label" class="btn btn-outline-light" target="_blank">
                <i class="bi bi-upc-scan me-1"></i> Label
            </a>
            <button class="btn btn-outline-light" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Print
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column - Details -->
    <div class="col-lg-7">
        <!-- Sender & Recipient -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="premium-shipment-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-box-gradient icon-box-primary">
                                <i class="bi bi-send-fill"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Sender</h6>
                        </div>
                        <p class="fw-bold mb-2" style="font-size:1.05rem; color:#1a1a2e;"><?= htmlspecialchars($shipment->sender_name) ?></p>
                        <div class="mb-1 small" style="color:#555;"><i class="bi bi-envelope me-2 text-primary"></i><?= htmlspecialchars($shipment->sender_email) ?></div>
                        <div class="mb-1 small" style="color:#555;"><i class="bi bi-telephone me-2 text-primary"></i><?= htmlspecialchars($shipment->sender_phone) ?></div>
                        <div class="small" style="color:#888;"><i class="bi bi-geo-alt me-2 text-primary"></i><?= htmlspecialchars($shipment->sender_address) ?>, <?= htmlspecialchars($shipment->sender_city) ?>, <?= htmlspecialchars($shipment->sender_country) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="premium-shipment-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-box-gradient icon-box-success">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Recipient</h6>
                        </div>
                        <p class="fw-bold mb-2" style="font-size:1.05rem; color:#1a1a2e;"><?= htmlspecialchars($shipment->recipient_name) ?></p>
                        <div class="mb-1 small" style="color:#555;"><i class="bi bi-envelope me-2 text-success"></i><?= htmlspecialchars($shipment->recipient_email ?? '-') ?></div>
                        <div class="mb-1 small" style="color:#555;"><i class="bi bi-telephone me-2 text-success"></i><?= htmlspecialchars($shipment->recipient_phone) ?></div>
                        <div class="small" style="color:#888;"><i class="bi bi-geo-alt me-2 text-success"></i><?= htmlspecialchars($shipment->recipient_address) ?>, <?= htmlspecialchars($shipment->recipient_city) ?>, <?= htmlspecialchars($shipment->recipient_country) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Details -->
        <div class="premium-shipment-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box-gradient icon-box-info">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Package Details</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="detail-label">Service Type</div>
                        <div class="detail-value text-capitalize"><?= str_replace('_', ' ', htmlspecialchars($shipment->service_type)) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Weight</div>
                        <div class="detail-value"><?= $shipment->weight ?> kg</div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Dimensions</div>
                        <div class="detail-value"><?= $shipment->length ?>×<?= $shipment->width ?>×<?= $shipment->height ?> cm</div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Package Type</div>
                        <div class="detail-value text-capitalize"><?= htmlspecialchars($shipment->package_type) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Declared Value</div>
                        <div class="detail-value"><?= format_currency($shipment->declared_value) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Insurance</div>
                        <div class="detail-value"><?= $shipment->is_insured ? '<span class="text-success">Yes</span> ($'.number_format($shipment->insurance_amount,2).')' : 'No' ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Signature Required</div>
                        <div class="detail-value"><?= $shipment->signature_required ? '<span class="text-success">Yes</span>' : 'No' ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">COD</div>
                        <div class="detail-value"><?= $shipment->is_cod ? format_currency($shipment->cod_amount) : 'No' ?></div>
                    </div>
                    <?php if ($shipment->description): ?>
                    <div class="col-12">
                        <div class="detail-label">Description</div>
                        <div class="detail-value fw-normal"><?= htmlspecialchars($shipment->description) ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if ($shipment->notes): ?>
                    <div class="col-12">
                        <div class="detail-label">Notes</div>
                        <div class="detail-value fw-normal" style="color:#555;"><?= htmlspecialchars($shipment->notes) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Financial Details -->
        <div class="premium-shipment-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box-gradient icon-box-warning">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Financial Details</h6>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="detail-label">Total Charges</div>
                        <div class="detail-value"><?= format_currency($shipment->total_charges) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Tax (<?= $shipment->grand_total && $shipment->total_charges ? round((($shipment->grand_total - $shipment->total_charges) / $shipment->total_charges) * 100, 1) : 0 ?>%)</div>
                        <div class="detail-value"><?= format_currency($shipment->tax_amount) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Payment Status</div>
                        <div class="detail-value text-capitalize"><?= htmlspecialchars($shipment->payment_status) ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Payment Method</div>
                        <div class="detail-value text-capitalize"><?= htmlspecialchars($shipment->payment_method ?? '-') ?></div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Currency</div>
                        <div class="detail-value"><?= htmlspecialchars($shipment->currency) ?></div>
                    </div>
                </div>
                <div class="grand-total-display">
                    <div style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; opacity:0.85; margin-bottom:0.3rem;">Grand Total</div>
                    <div class="total-amount"><?= format_currency($shipment->grand_total) ?></div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <?php if (!empty($documents)): ?>
        <div class="premium-shipment-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box-gradient icon-box-dark">
                        <i class="bi bi-files"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Documents</h6>
                </div>
                <?php foreach ($documents as $doc): ?>
                <div class="doc-item">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text text-primary"></i>
                        <span class="fw-semibold small"><?= htmlspecialchars($doc->name) ?></span>
                        <small class="text-muted">(<?= htmlspecialchars($doc->document_type) ?>)</small>
                    </div>
                    <a href="<?= BASE_URL ?>/admin/documents/<?= $doc->id ?>/download" class="btn btn-sm btn-outline-primary" style="border-radius:8px; font-size:0.8rem;" target="_blank">
                        <i class="bi bi-download me-1"></i>Download
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Invoices -->
        <?php if (!empty($invoices)): ?>
        <div class="premium-shipment-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box-gradient icon-box-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Invoices</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0" style="border-radius:10px; overflow:hidden;">
                        <thead>
                            <tr style="background:#f8f9fc;">
                                <th class="small fw-bold" style="border:none;">Invoice #</th>
                                <th class="small fw-bold" style="border:none;">Amount</th>
                                <th class="small fw-bold" style="border:none;">Status</th>
                                <th class="small fw-bold" style="border:none;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $inv): ?>
                            <tr class="invoice-row">
                                <td class="fw-semibold small" style="border:none;"><?= htmlspecialchars($inv->invoice_number) ?></td>
                                <td class="fw-semibold small" style="border:none;"><?= format_currency($inv->total) ?></td>
                                <td style="border:none;"><span class="badge rounded-pill bg-<?= $inv->status === 'paid' ? 'success' : ($inv->status === 'overdue' ? 'danger' : 'warning') ?>" style="font-size:0.75rem;"><?= $inv->status ?></span></td>
                                <td class="small text-muted" style="border:none;"><?= format_date($inv->created_at) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Shipment Live Location Map -->
        <?php if ($shipment->current_latitude && $shipment->current_longitude): ?>
        <div class="premium-shipment-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box-gradient icon-box-success">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Live Shipment Location</h6>
                </div>
                <div class="map-container">
                    <div id="shipmentLiveMap" class="map-inner"
                         data-lat="<?= $shipment->current_latitude ?>" data-lng="<?= $shipment->current_longitude ?>"
                         data-tracking="<?= htmlspecialchars($shipment->tracking_number) ?>"></div>
                </div>
                <?php if ($shipment->last_scan_at): ?>
                <div class="mt-2" style="color:#888; font-size:0.85rem;">
                    <i class="bi bi-clock me-1"></i> Last scan: <?= format_date($shipment->last_scan_at, 'M d, Y H:i') ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right Column - Management -->
    <div class="col-lg-5">
        <!-- Status Update -->
        <div class="premium-form-card mb-4">
            <div class="form-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: #fff;">
                <i class="bi bi-arrow-up-circle"></i> Update Status
            </div>
            <div class="form-body">
                <form id="statusForm" onsubmit="event.preventDefault(); updateStatus(<?= $shipment->id ?>);">
                    <div class="mb-3">
                        <label class="detail-label">Select Status</label>
                        <select name="status_id" class="form-select" required>
                            <?php foreach ($allStatuses as $st): ?>
                            <option value="<?= $st->id ?>" <?= $st->id == $shipment->current_status_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($st->name) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="detail-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Nairobi, Kenya">
                    </div>
                    <div class="mb-3">
                        <label class="detail-label">Description / Remarks</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Add any notes or remarks..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                        <i class="bi bi-arrow-up-circle me-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Assign Driver -->
        <div class="premium-form-card mb-4">
            <div class="form-header" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: #fff;">
                <i class="bi bi-person-badge"></i> Assign Driver
            </div>
            <div class="form-body">
                <form id="driverForm" onsubmit="event.preventDefault(); assignDriver(<?= $shipment->id ?>);">
                    <div class="mb-3">
                        <label class="detail-label">Select Driver</label>
                        <select name="driver_id" class="form-select">
                            <option value="">-- Select Driver --</option>
                            <?php foreach ($drivers as $d): ?>
                            <option value="<?= $d->id ?>" <?= $d->id == ($shipment->assigned_driver_id ?? '') ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d->first_name) ?> <?= htmlspecialchars($d->last_name) ?> (<?= htmlspecialchars($d->status) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: #fff; border: none; font-weight:700;">
                        <i class="bi bi-person-check me-1"></i> Assign Driver
                    </button>
                </form>
                <?php if ($shipment->driver_name): ?>
                <div class="mt-3" style="background:#f0fdf4; border-radius:10px; padding:0.85rem 1rem; border-left: 4px solid #38ef7d;">
                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px; color:#888; margin-bottom:0.2rem;">Current Driver</div>
                    <div class="fw-bold" style="color:#1a1a2e;"><?= htmlspecialchars($shipment->driver_name) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Assignment Info -->
        <div class="premium-shipment-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="icon-box-gradient icon-box-dark">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Assignment Info</h6>
                </div>
                <div class="info-item">
                    <div class="detail-label">Origin Branch</div>
                    <div class="detail-value"><?= htmlspecialchars($shipment->origin_branch_name ?? 'Not assigned') ?></div>
                </div>
                <div class="info-item">
                    <div class="detail-label">Destination Branch</div>
                    <div class="detail-value"><?= htmlspecialchars($shipment->destination_branch_name ?? 'Not assigned') ?></div>
                </div>
                <div class="info-item">
                    <div class="detail-label">Current Warehouse</div>
                    <div class="detail-value"><?= htmlspecialchars($shipment->warehouse_name ?? 'N/A') ?></div>
                </div>
                <div class="info-item">
                    <div class="detail-label">Vehicle</div>
                    <div class="detail-value"><?= htmlspecialchars($shipment->vehicle_reg ?? 'Not assigned') ?></div>
                </div>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="premium-shipment-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="icon-box-gradient icon-box-primary">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Tracking Timeline</h6>
                </div>
                <?php if (!empty($history)): ?>
                    <?php foreach ($history as $h): ?>
                    <div class="timeline-entry-premium" data-id="<?= $h->id ?>">
                        <div class="timeline-dot" style="color: <?= $h->status_color ?? '#6c757d' ?>; background: <?= $h->status_color ?? '#6c757d' ?>;"></div>
                        <div>
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="fw-bold small mb-1" style="color:#1a1a2e;"><?= htmlspecialchars($h->status_name) ?></div>
                                <button class="btn btn-sm py-0 px-2" style="border-radius:8px; font-size:0.75rem; color:#667eea; border:1px solid #667eea; background:transparent;" onclick="editHistory(<?= $h->id ?>, '<?= htmlspecialchars(addslashes($h->created_at), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($h->location ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($h->description ?? ''), ENT_QUOTES) ?>')" title="Edit entry">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                            <div style="font-size:0.82rem; color:#888; margin-bottom:0.15rem;">
                                <?= $h->description ? htmlspecialchars($h->description) . ' • ' : '' ?><?= format_date($h->created_at, 'M d, Y H:i') ?>
                            </div>
                            <?php if ($h->location): ?>
                            <div style="font-size:0.8rem; color:#aaa;"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($h->location) ?></div>
                            <?php endif; ?>
                            <?php if ($h->updated_by_name): ?>
                            <div style="font-size:0.78rem; color:#bbb;">by <?= htmlspecialchars($h->updated_by_name) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4" style="color:#aaa;">
                        <i class="bi bi-clock-history" style="font-size:2rem;"></i>
                        <p class="small mb-0 mt-2">No tracking history available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Load Leaflet for maps -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">

<script>
// Initialize live shipment map if element exists
$(document).ready(function() {
    var mapEl = document.getElementById('shipmentLiveMap');
    if (mapEl) {
        var lat = parseFloat(mapEl.dataset.lat);
        var lng = parseFloat(mapEl.dataset.lng);
        var tracking = mapEl.dataset.tracking;
        
        var map = L.map(mapEl, {
            center: [lat, lng],
            zoom: 14,
            zoomControl: true
        });
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        
        // Pulse marker
        var pulseIcon = L.divIcon({
            className: 'shipment-marker',
            html: '<div style="width:20px;height:20px;background:#e74c3c;border:3px solid #fff;border-radius:50%;box-shadow:0 0 0 4px rgba(231,76,60,0.3);"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
        });
        
        L.marker([lat, lng], { icon: pulseIcon })
            .addTo(map)
            .bindPopup('<b>Shipment: ' + tracking + '</b><br>Current Location');
        
        setTimeout(function() { map.invalidateSize(); }, 300);
    }
});

function updateStatus(id) {
    const form = document.getElementById('statusForm');
    const formData = new FormData(form);
    
    $.ajax({
        url: '<?= BASE_URL ?>/admin/shipments/' + id + '/status',
        method: 'POST',
        data: Object.fromEntries(formData),
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Failed to update status', 'error');
        }
    });
}

function assignDriver(id) {
    const form = document.getElementById('driverForm');
    const formData = new FormData(form);
    
    $.ajax({
        url: '<?= BASE_URL ?>/admin/shipments/' + id + '/assign-driver',
        method: 'POST',
        data: Object.fromEntries(formData),
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Failed to assign driver', 'error');
        }
    });
}
</script>

<!-- Edit History Modal -->
<div class="modal fade" id="editHistoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: #fff;">
                <h6 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Tracking Entry</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editHistoryForm">
                    <input type="hidden" id="editHistoryId">
                    <div class="mb-3">
                        <label class="detail-label">Date & Time</label>
                        <input type="datetime-local" class="form-control" style="border-radius:10px; border:2px solid #e8ecf1; padding:0.65rem 1rem;" id="editHistoryDate" required>
                    </div>
                    <div class="mb-3">
                        <label class="detail-label">Location</label>
                        <input type="text" class="form-control" style="border-radius:10px; border:2px solid #e8ecf1; padding:0.65rem 1rem;" id="editHistoryLocation" placeholder="e.g. Nairobi, Kenya">
                    </div>
                    <div class="mb-3">
                        <label class="detail-label">Description</label>
                        <textarea class="form-control" style="border-radius:10px; border:2px solid #e8ecf1; padding:0.65rem 1rem;" id="editHistoryDescription" rows="3" placeholder="Optional description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="border-radius:10px;" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn" style="border-radius:10px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; font-weight:700;" onclick="saveHistory()"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
function editHistory(id, createdAt, location, description) {
    document.getElementById('editHistoryId').value = id;
    // Convert "Jul 23, 2026 02:35" or MySQL "2026-07-23 02:35:09" to datetime-local format
    var dt = createdAt;
    // Try parsing as MySQL format
    if (dt.match(/^\d{4}-\d{2}-\d{2}/)) {
        dt = dt.replace(' ', 'T').substring(0, 16);
    } else {
        // Try parsing "Jul 23, 2026 02:35"
        var d = new Date(dt);
        if (!isNaN(d.getTime())) {
            var pad = function(n) { return n < 10 ? '0' + n : n; };
            dt = d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate()) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
        }
    }
    document.getElementById('editHistoryDate').value = dt;
    document.getElementById('editHistoryLocation').value = location;
    document.getElementById('editHistoryDescription').value = description;
    new bootstrap.Modal(document.getElementById('editHistoryModal')).show();
}

function saveHistory() {
    var id = document.getElementById('editHistoryId').value;
    var shipmentId = <?= $shipment->id ?>;
    var data = {
        created_at: document.getElementById('editHistoryDate').value,
        location: document.getElementById('editHistoryLocation').value,
        description: document.getElementById('editHistoryDescription').value
    };

    $.ajax({
        url: '<?= BASE_URL ?>/admin/shipments/' + shipmentId + '/history/' + id + '/update',
        method: 'POST',
        data: data,
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                setTimeout(function() { location.reload(); }, 800);
            } else {
                showToast(response.message || 'Update failed', 'error');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to update entry';
            showToast(msg, 'error');
        }
    });
}
</script>