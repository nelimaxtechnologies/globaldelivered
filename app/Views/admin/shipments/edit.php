<style>
    .shipment-edit-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        padding: 30px 0;
    }
    .shipment-edit-wrapper .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
    }
    .shipment-edit-wrapper .page-header .page-title {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .shipment-edit-wrapper .page-header .page-title .title-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
    }
    .shipment-edit-wrapper .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 10px;
        border: 1.5px solid #d1d5db;
        background: #fff;
        color: #4b5563;
        font-weight: 600;
        font-size: 0.88rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .shipment-edit-wrapper .btn-back:hover {
        border-color: #667eea;
        color: #667eea;
        background: #f0f0ff;
    }
    .shipment-edit-wrapper .form-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .shipment-edit-wrapper .form-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    }
    .shipment-edit-wrapper .card-header-bar {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 24px;
        border-bottom: 1px solid #f0f0f0;
    }
    .shipment-edit-wrapper .card-header-bar .section-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .shipment-edit-wrapper .card-header-bar .section-icon.gradient-blue { background: linear-gradient(135deg, #667eea, #764ba2); }
    .shipment-edit-wrapper .card-header-bar .section-icon.gradient-green { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .shipment-edit-wrapper .card-header-bar .section-icon.gradient-orange { background: linear-gradient(135deg, #f7971e, #ffd200); }
    .shipment-edit-wrapper .card-header-bar .section-icon.gradient-purple { background: linear-gradient(135deg, #a18cd1, #fbc2eb); }
    .shipment-edit-wrapper .card-header-bar .section-icon.gradient-teal { background: linear-gradient(135deg, #00b09b, #96c93d); }
    .shipment-edit-wrapper .card-header-bar .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .shipment-edit-wrapper .card-body-inner {
        padding: 24px;
    }
    .shipment-edit-wrapper .form-group {
        margin-bottom: 20px;
    }
    .shipment-edit-wrapper .form-group:last-child {
        margin-bottom: 0;
    }
    .shipment-edit-wrapper .form-group label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        letter-spacing: 0.01em;
    }
    .shipment-edit-wrapper .form-group input[type="text"],
    .shipment-edit-wrapper .form-group input[type="tel"],
    .shipment-edit-wrapper .form-group input[type="number"],
    .shipment-edit-wrapper .form-group input[type="date"],
    .shipment-edit-wrapper .form-group select,
    .shipment-edit-wrapper .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.9rem;
        color: #1f2937;
        background: #fafbfc;
        transition: all 0.2s;
        outline: none;
        font-family: inherit;
    }
    .shipment-edit-wrapper .form-group input:focus,
    .shipment-edit-wrapper .form-group select:focus,
    .shipment-edit-wrapper .form-group textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.12);
        background: #fff;
    }
    .shipment-edit-wrapper .form-group textarea {
        resize: vertical;
        min-height: 60px;
    }
    .shipment-edit-wrapper .form-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    .shipment-edit-wrapper .form-row.cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }
    .shipment-edit-wrapper .form-row.cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
    .shipment-edit-wrapper .form-row.cols-1 {
        grid-template-columns: 1fr;
    }
    .shipment-edit-wrapper .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding-top: 4px;
    }
    .shipment-edit-wrapper .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .shipment-edit-wrapper .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        accent-color: #667eea;
        cursor: pointer;
    }
    .shipment-edit-wrapper .checkbox-item label {
        font-size: 0.88rem;
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 0;
        cursor: pointer;
    }
    .shipment-edit-wrapper .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 24px;
        border-top: 1px solid #f0f0f0;
        background: #fafbfc;
        border-radius: 0 0 16px 16px;
    }
    .shipment-edit-wrapper .btn-cancel {
        padding: 10px 22px;
        border-radius: 10px;
        border: 1.5px solid #d1d5db;
        background: #fff;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .shipment-edit-wrapper .btn-cancel:hover {
        border-color: #9ca3af;
        background: #f9fafb;
    }
    .shipment-edit-wrapper .btn-submit {
        padding: 10px 28px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s;
        box-shadow: 0 2px 10px rgba(102,126,234,0.3);
    }
    .shipment-edit-wrapper .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 18px rgba(102,126,234,0.45);
    }
    .shipment-edit-wrapper .btn-submit:active {
        transform: translateY(0);
    }
    @media (max-width: 768px) {
        .shipment-edit-wrapper .form-row,
        .shipment-edit-wrapper .form-row.cols-3,
        .shipment-edit-wrapper .form-row.cols-2 {
            grid-template-columns: 1fr;
        }
        .shipment-edit-wrapper .page-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
    }
</style>

<div class="shipment-edit-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <span class="title-icon"><i class="bi bi-pencil-square"></i></span>
            Edit Shipment: <?= htmlspecialchars($shipment->tracking_number) ?>
        </h1>
        <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>">
        <input type="hidden" name="_method" value="PUT">
        <?= csrf_field() ?>

        <!-- Status & Service -->
        <div class="form-card">
            <div class="card-header-bar">
                <span class="section-icon gradient-blue"><i class="bi bi-info-circle"></i></span>
                <h3 class="section-title">Status & Service</h3>
            </div>
            <div class="card-body-inner">
                <div class="form-row cols-3">
                    <div class="form-group">
                        <label for="current_status_id">Current Status</label>
                        <select name="current_status_id" id="current_status_id">
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?= $s->id ?>" <?= $shipment->current_status_id == $s->id ? 'selected' : '' ?>><?= htmlspecialchars($s->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_type">Service Type</label>
                        <select name="service_type" id="service_type">
                            <?php foreach (['domestic','international','express','freight','air_cargo','sea_freight','road_transport','same_day','last_mile'] as $type): ?>
                                <option value="<?= $type ?>" <?= $shipment->service_type === $type ? 'selected' : '' ?>><?= ucwords(str_replace('_', ' ', $type)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="package_type">Package Type</label>
                        <select name="package_type" id="package_type">
                            <?php foreach (['parcel','document','box','pallet','container','envelope'] as $pt): ?>
                                <option value="<?= $pt ?>" <?= $shipment->package_type === $pt ? 'selected' : '' ?>><?= ucfirst($pt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sender Information -->
        <div class="form-card">
            <div class="card-header-bar">
                <span class="section-icon gradient-green"><i class="bi bi-box-arrow-up-right"></i></span>
                <h3 class="section-title">Sender Information</h3>
            </div>
            <div class="card-body-inner">
                <div class="form-row cols-3">
                    <div class="form-group">
                        <label for="sender_name">Sender Name</label>
                        <input type="text" name="sender_name" id="sender_name" value="<?= htmlspecialchars($shipment->sender_name ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="sender_phone">Sender Phone</label>
                        <input type="tel" name="sender_phone" id="sender_phone" value="<?= htmlspecialchars($shipment->sender_phone ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="sender_address">Sender Address</label>
                        <input type="text" name="sender_address" id="sender_address" value="<?= htmlspecialchars($shipment->sender_address ?? '') ?>">
                    </div>
                </div>
                <div class="form-row cols-4">
                    <div class="form-group">
                        <label for="sender_city">Sender City</label>
                        <input type="text" name="sender_city" id="sender_city" value="<?= htmlspecialchars($shipment->sender_city ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="sender_state">Sender State</label>
                        <input type="text" name="sender_state" id="sender_state" value="<?= htmlspecialchars($shipment->sender_state ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="sender_country">Sender Country</label>
                        <input type="text" name="sender_country" id="sender_country" value="<?= htmlspecialchars($shipment->sender_country ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="sender_postal_code">Sender Postal Code</label>
                        <input type="text" name="sender_postal_code" id="sender_postal_code" value="<?= htmlspecialchars($shipment->sender_postal_code ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipient Information -->
        <div class="form-card">
            <div class="card-header-bar">
                <span class="section-icon gradient-orange"><i class="bi bi-box-arrow-in-down-right"></i></span>
                <h3 class="section-title">Recipient Information</h3>
            </div>
            <div class="card-body-inner">
                <div class="form-row cols-3">
                    <div class="form-group">
                        <label for="recipient_name">Recipient Name</label>
                        <input type="text" name="recipient_name" id="recipient_name" value="<?= htmlspecialchars($shipment->recipient_name) ?>">
                    </div>
                    <div class="form-group">
                        <label for="recipient_phone">Recipient Phone</label>
                        <input type="tel" name="recipient_phone" id="recipient_phone" value="<?= htmlspecialchars($shipment->recipient_phone) ?>">
                    </div>
                    <div class="form-group">
                        <label for="recipient_address">Recipient Address</label>
                        <input type="text" name="recipient_address" id="recipient_address" value="<?= htmlspecialchars($shipment->recipient_address) ?>">
                    </div>
                </div>
                <div class="form-row cols-4">
                    <div class="form-group">
                        <label for="recipient_city">City</label>
                        <input type="text" name="recipient_city" id="recipient_city" value="<?= htmlspecialchars($shipment->recipient_city) ?>">
                    </div>
                    <div class="form-group">
                        <label for="recipient_state">State</label>
                        <input type="text" name="recipient_state" id="recipient_state" value="<?= htmlspecialchars($shipment->recipient_state) ?>">
                    </div>
                    <div class="form-group">
                        <label for="recipient_country">Country</label>
                        <input type="text" name="recipient_country" id="recipient_country" value="<?= htmlspecialchars($shipment->recipient_country) ?>">
                    </div>
                    <div class="form-group">
                        <label for="recipient_postal_code">Postal Code</label>
                        <input type="text" name="recipient_postal_code" id="recipient_postal_code" value="<?= htmlspecialchars($shipment->recipient_postal_code) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Details -->
        <div class="form-card">
            <div class="card-header-bar">
                <span class="section-icon gradient-purple"><i class="bi bi-box-seam"></i></span>
                <h3 class="section-title">Package Details</h3>
            </div>
            <div class="card-body-inner">
                <div class="form-row cols-4">
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" name="weight" id="weight" step="0.1" min="0.1" value="<?= htmlspecialchars($shipment->weight) ?>">
                    </div>
                    <div class="form-group">
                        <label for="length">Length (cm)</label>
                        <input type="number" name="length" id="length" step="0.1" min="0" value="<?= htmlspecialchars($shipment->length) ?>">
                    </div>
                    <div class="form-group">
                        <label for="width">Width (cm)</label>
                        <input type="number" name="width" id="width" step="0.1" min="0" value="<?= htmlspecialchars($shipment->width) ?>">
                    </div>
                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" name="height" id="height" step="0.1" min="0" value="<?= htmlspecialchars($shipment->height) ?>">
                    </div>
                </div>
                <div class="form-row cols-1">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="2"><?= htmlspecialchars($shipment->description) ?></textarea>
                    </div>
                </div>
                <div class="form-row cols-1">
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" rows="2"><?= htmlspecialchars($shipment->notes) ?></textarea>
                    </div>
                </div>
                <div class="form-row cols-1">
                    <div class="form-group">
                        <label>Options</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" name="is_fragile" id="is_fragile" value="1" <?= $shipment->is_fragile ? 'checked' : '' ?>>
                                <label for="is_fragile">Fragile</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="is_insured" id="is_insured" value="1" <?= $shipment->is_insured ? 'checked' : '' ?>>
                                <label for="is_insured">Insured</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="signature_required" id="signature_required" value="1" <?= $shipment->signature_required ? 'checked' : '' ?>>
                                <label for="signature_required">Signature Required</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row cols-4">
                    <div class="form-group">
                        <label for="declared_value">Declared Value ($)</label>
                        <input type="number" name="declared_value" id="declared_value" step="0.01" min="0" value="<?= htmlspecialchars($shipment->declared_value) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment & Schedule -->
        <div class="form-card">
            <div class="card-header-bar">
                <span class="section-icon gradient-teal"><i class="bi bi-calendar-check"></i></span>
                <h3 class="section-title">Assignment & Schedule</h3>
            </div>
            <div class="card-body-inner">
                <div class="form-row cols-3">
                    <div class="form-group">
                        <label for="origin_branch_id">Origin Branch</label>
                        <select name="origin_branch_id" id="origin_branch_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b->id ?>" <?= $shipment->origin_branch_id == $b->id ? 'selected' : '' ?>><?= htmlspecialchars($b->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="destination_branch_id">Destination Branch</label>
                        <select name="destination_branch_id" id="destination_branch_id">
                            <option value="">-- Select --</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b->id ?>" <?= $shipment->destination_branch_id == $b->id ? 'selected' : '' ?>><?= htmlspecialchars($b->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assigned_driver_id">Assigned Driver</label>
                        <select name="assigned_driver_id" id="assigned_driver_id">
                            <option value="">-- None --</option>
                            <?php foreach ($drivers as $d): ?>
                                <option value="<?= $d->id ?>" <?= $shipment->assigned_driver_id == $d->id ? 'selected' : '' ?>><?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row cols-3">
                    <div class="form-group">
                        <label for="assigned_vehicle_id">Assigned Vehicle</label>
                        <select name="assigned_vehicle_id" id="assigned_vehicle_id">
                            <option value="">-- None --</option>
                            <?php foreach ($vehicles as $v): ?>
                                <option value="<?= $v->id ?>" <?= $shipment->assigned_vehicle_id == $v->id ? 'selected' : '' ?>><?= htmlspecialchars($v->registration_number) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pickup_date">Pickup Date</label>
                        <input type="date" name="pickup_date" id="pickup_date" value="<?= htmlspecialchars($shipment->pickup_date) ?>">
                    </div>
                    <div class="form-group">
                        <label for="expected_delivery_date">Expected Delivery</label>
                        <input type="date" name="expected_delivery_date" id="expected_delivery_date" value="<?= htmlspecialchars($shipment->expected_delivery_date) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-card" style="margin-bottom:0;">
            <div class="form-actions">
                <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>" class="btn-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save2"></i> Update Shipment
                </button>
            </div>
        </div>
    </form>
</div>
