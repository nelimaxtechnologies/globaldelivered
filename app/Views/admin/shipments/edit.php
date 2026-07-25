<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2"></i>Edit Shipment: <?= htmlspecialchars($shipment->tracking_number) ?></h5>
        <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>" class="btn btn-outline-secondary admin-btn admin-btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>">
        <input type="hidden" name="_method" value="PUT">
        <?= csrf_field() ?>
        
        <!-- Status & Service -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-info-circle me-2 text-primary"></i>Status & Service</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Current Status</label>
                <select name="current_status_id" class="form-select">
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s->id ?>" <?= $shipment->current_status_id == $s->id ? 'selected' : '' ?>><?= htmlspecialchars($s->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Service Type</label>
                <select name="service_type" class="form-select">
                    <?php foreach (['domestic','international','express','freight','air_cargo','sea_freight','road_transport','same_day','last_mile'] as $type): ?>
                        <option value="<?= $type ?>" <?= $shipment->service_type === $type ? 'selected' : '' ?>><?= ucwords(str_replace('_', ' ', $type)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Package Type</label>
                <select name="package_type" class="form-select">
                    <?php foreach (['parcel','document','box','pallet','container','envelope'] as $pt): ?>
                        <option value="<?= $pt ?>" <?= $shipment->package_type === $pt ? 'selected' : '' ?>><?= ucfirst($pt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Sender Information -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-box-arrow-up-right me-2 text-primary"></i>Sender Information</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Sender Name</label>
                <input type="text" name="sender_name" class="form-control" value="<?= htmlspecialchars($shipment->sender_name ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Sender Phone</label>
                <input type="tel" name="sender_phone" class="form-control" value="<?= htmlspecialchars($shipment->sender_phone ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Sender Address</label>
                <input type="text" name="sender_address" class="form-control" value="<?= htmlspecialchars($shipment->sender_address ?? '') ?>">
            </div>
        </div>

        <!-- Recipient Information -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-box-arrow-in-down-right me-2 text-success"></i>Recipient Information</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Recipient Name</label>
                <input type="text" name="recipient_name" class="form-control" value="<?= htmlspecialchars($shipment->recipient_name) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Recipient Phone</label>
                <input type="tel" name="recipient_phone" class="form-control" value="<?= htmlspecialchars($shipment->recipient_phone) ?>">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Recipient Address</label>
                <input type="text" name="recipient_address" class="form-control" value="<?= htmlspecialchars($shipment->recipient_address) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">City</label>
                <input type="text" name="recipient_city" class="form-control" value="<?= htmlspecialchars($shipment->recipient_city) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">State</label>
                <input type="text" name="recipient_state" class="form-control" value="<?= htmlspecialchars($shipment->recipient_state) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Country</label>
                <input type="text" name="recipient_country" class="form-control" value="<?= htmlspecialchars($shipment->recipient_country) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Postal Code</label>
                <input type="text" name="recipient_postal_code" class="form-control" value="<?= htmlspecialchars($shipment->recipient_postal_code) ?>">
            </div>
        </div>

        <!-- Package Details -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-box me-2 text-warning"></i>Package Details</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Weight (kg)</label>
                <input type="number" name="weight" class="form-control" step="0.1" min="0.1" value="<?= $shipment->weight ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Length (cm)</label>
                <input type="number" name="length" class="form-control" step="0.1" min="0" value="<?= $shipment->length ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Width (cm)</label>
                <input type="number" name="width" class="form-control" step="0.1" min="0" value="<?= $shipment->width ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Height (cm)</label>
                <input type="number" name="height" class="form-control" step="0.1" min="0" value="<?= $shipment->height ?>">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($shipment->description) ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($shipment->notes) ?></textarea>
            </div>
            <div class="col-md-4">
                <div class="form-check"><input type="checkbox" name="is_fragile" class="form-check-input" id="isFragile" <?= $shipment->is_fragile ? 'checked' : '' ?>><label for="isFragile">Fragile</label></div>
            </div>
            <div class="col-md-4">
                <div class="form-check"><input type="checkbox" name="is_insured" class="form-check-input" id="isInsured" <?= $shipment->is_insured ? 'checked' : '' ?>><label for="isInsured">Insured</label></div>
            </div>
            <div class="col-md-4">
                <div class="form-check"><input type="checkbox" name="signature_required" class="form-check-input" id="sigRequired" <?= $shipment->signature_required ? 'checked' : '' ?>><label for="sigRequired">Signature Required</label></div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Declared Value ($)</label>
                <input type="number" name="declared_value" class="form-control" step="0.01" min="0" value="<?= $shipment->declared_value ?>">
            </div>
        </div>

        <!-- Assignment & Schedule -->
        <h6 class="fw-bold mb-3 py-2 px-3 bg-light rounded"><i class="bi bi-gear me-2"></i>Assignment & Schedule</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Origin Branch</label>
                <select name="origin_branch_id" class="form-select">
                    <option value="">-- Select --</option>
                    <?php foreach ($branches as $b): ?>
                        <option value="<?= $b->id ?>" <?= $shipment->origin_branch_id == $b->id ? 'selected' : '' ?>><?= htmlspecialchars($b->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Destination Branch</label>
                <select name="destination_branch_id" class="form-select">
                    <option value="">-- Select --</option>
                    <?php foreach ($branches as $b): ?>
                        <option value="<?= $b->id ?>" <?= $shipment->destination_branch_id == $b->id ? 'selected' : '' ?>><?= htmlspecialchars($b->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Assigned Driver</label>
                <select name="assigned_driver_id" class="form-select">
                    <option value="">-- None --</option>
                    <?php foreach ($drivers as $d): ?>
                        <option value="<?= $d->id ?>" <?= $shipment->assigned_driver_id == $d->id ? 'selected' : '' ?>><?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Assigned Vehicle</label>
                <select name="assigned_vehicle_id" class="form-select">
                    <option value="">-- None --</option>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?= $v->id ?>" <?= $shipment->assigned_vehicle_id == $v->id ? 'selected' : '' ?>><?= htmlspecialchars($v->registration_number) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Pickup Date</label>
                <input type="date" name="pickup_date" class="form-control" value="<?= $shipment->pickup_date ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Expected Delivery</label>
                <input type="date" name="expected_delivery_date" class="form-control" value="<?= $shipment->expected_delivery_date ?>">
            </div>
        </div>
        
        <div class="d-flex gap-2 justify-content-end border-top pt-4">
            <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>" class="btn btn-secondary admin-btn">Cancel</a>
            <button type="submit" class="btn btn-primary admin-btn"><i class="bi bi-save me-1"></i> Update Shipment</button>
        </div>
    </form>
</div>
