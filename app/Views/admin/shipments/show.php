<!-- Shipment Header -->
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($shipment->tracking_number) ?></h5>
            <span class="badge" style="background: <?= $shipment->status_color ?? '#6c757d' ?>; font-size: 0.9rem;">
                <?= htmlspecialchars($shipment->status_name ?? $shipment->status) ?>
            </span>
            <span class="text-muted ms-3 small">
                <i class="bi bi-calendar"></i> Created: <?= format_date($shipment->created_at) ?>
            </span>
            <?php if ($shipment->expected_delivery_date): ?>
            <span class="text-muted ms-3 small">
                <i class="bi bi-clock"></i> ETA: <?= format_date($shipment->expected_delivery_date) ?>
            </span>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>/edit" class="btn btn-outline-primary admin-btn admin-btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>/label" class="btn btn-outline-info admin-btn admin-btn-sm" target="_blank">
                <i class="bi bi-upc-scan me-1"></i> Label
            </a>
            <button class="btn btn-outline-secondary admin-btn admin-btn-sm" onclick="window.print()">
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
                <div class="admin-card h-100">
                    <h6 class="fw-bold mb-3"><i class="bi bi-box-arrow-up-right text-primary me-2"></i>Sender</h6>
                    <p class="fw-semibold mb-1"><?= htmlspecialchars($shipment->sender_name) ?></p>
                    <p class="mb-1 small">📧 <?= htmlspecialchars($shipment->sender_email) ?></p>
                    <p class="mb-1 small">📞 <?= htmlspecialchars($shipment->sender_phone) ?></p>
                    <p class="mb-0 small text-muted">📍 <?= htmlspecialchars($shipment->sender_address) ?>, <?= htmlspecialchars($shipment->sender_city) ?>, <?= htmlspecialchars($shipment->sender_country) ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="admin-card h-100">
                    <h6 class="fw-bold mb-3"><i class="bi bi-box-arrow-in-down-right text-success me-2"></i>Recipient</h6>
                    <p class="fw-semibold mb-1"><?= htmlspecialchars($shipment->recipient_name) ?></p>
                    <p class="mb-1 small">📧 <?= htmlspecialchars($shipment->recipient_email ?? '-') ?></p>
                    <p class="mb-1 small">📞 <?= htmlspecialchars($shipment->recipient_phone) ?></p>
                    <p class="mb-0 small text-muted">📍 <?= htmlspecialchars($shipment->recipient_address) ?>, <?= htmlspecialchars($shipment->recipient_city) ?>, <?= htmlspecialchars($shipment->recipient_country) ?></p>
                </div>
            </div>
        </div>
        
        <!-- Package Details -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-box me-2"></i>Package Details</h6>
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Service Type</small><span class="fw-semibold text-capitalize"><?= str_replace('_', ' ', htmlspecialchars($shipment->service_type)) ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Weight</small><span class="fw-semibold"><?= $shipment->weight ?> kg</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Dimensions</small><span class="fw-semibold"><?= $shipment->length ?>×<?= $shipment->width ?>×<?= $shipment->height ?> cm</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Package Type</small><span class="fw-semibold text-capitalize"><?= htmlspecialchars($shipment->package_type) ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Declared Value</small><span class="fw-semibold"><?= format_currency($shipment->declared_value) ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Insurance</small><span class="fw-semibold"><?= $shipment->is_insured ? 'Yes ($'.number_format($shipment->insurance_amount,2).')' : 'No' ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Signature Required</small><span class="fw-semibold"><?= $shipment->signature_required ? 'Yes' : 'No' ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">COD</small><span class="fw-semibold"><?= $shipment->is_cod ? format_currency($shipment->cod_amount) : 'No' ?></span></div>
                <?php if ($shipment->description): ?>
                <div class="col-12"><small class="text-muted d-block">Description</small><p class="mb-0"><?= htmlspecialchars($shipment->description) ?></p></div>
                <?php endif; ?>
                <?php if ($shipment->notes): ?>
                <div class="col-12"><small class="text-muted d-block">Notes</small><p class="mb-0"><?= htmlspecialchars($shipment->notes) ?></p></div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Financial -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-currency-dollar me-2"></i>Financial Details</h6>
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Total Charges</small><span class="fw-semibold"><?= format_currency($shipment->total_charges) ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Tax (<?= $shipment->grand_total && $shipment->total_charges ? round((($shipment->grand_total - $shipment->total_charges) / $shipment->total_charges) * 100, 1) : 0 ?>%)</small><span class="fw-semibold"><?= format_currency($shipment->tax_amount) ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Grand Total</small><span class="fw-bold fs-5 text-primary"><?= format_currency($shipment->grand_total) ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Payment Status</small><span class="fw-semibold text-capitalize"><?= htmlspecialchars($shipment->payment_status) ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Payment Method</small><span class="fw-semibold text-capitalize"><?= htmlspecialchars($shipment->payment_method ?? '-') ?></span></div>
                <div class="col-md-4"><small class="text-muted d-block">Currency</small><span class="fw-semibold"><?= htmlspecialchars($shipment->currency) ?></span></div>
            </div>
        </div>
        
        <!-- Documents -->
        <?php if (!empty($documents)): ?>
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-files me-2"></i>Documents</h6>
            <div class="list-group list-group-flush">
                <?php foreach ($documents as $doc): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <i class="bi bi-file-earmark me-2"></i>
                        <span class="fw-semibold"><?= htmlspecialchars($doc->name) ?></span>
                        <small class="text-muted ms-2">(<?= htmlspecialchars($doc->document_type) ?>)</small>
                    </div>
                    <a href="<?= BASE_URL ?>/admin/documents/<?= $doc->id ?>/download" class="btn btn-sm btn-outline-primary" target="_blank">Download</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Invoices -->
        <?php if (!empty($invoices)): ?>
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Invoices</h6>
            <div class="table-responsive">
                <table class="table table-sm table-admin">
                    <thead><tr><th>#</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php foreach ($invoices as $inv): ?>
                        <tr>
                            <td><?= htmlspecialchars($inv->invoice_number) ?></td>
                            <td><?= format_currency($inv->total) ?></td>
                            <td><span class="badge bg-<?= $inv->status === 'paid' ? 'success' : ($inv->status === 'overdue' ? 'danger' : 'warning') ?>"><?= $inv->status ?></span></td>
                            <td><small><?= format_date($inv->created_at) ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Shipment Live Location Map -->
        <?php if ($shipment->current_latitude && $shipment->current_longitude): ?>
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Live Shipment Location</h6>
            <div id="shipmentLiveMap" style="height: 250px; border-radius: 8px;" 
                 data-lat="<?= $shipment->current_latitude ?>" data-lng="<?= $shipment->current_longitude ?>"
                 data-tracking="<?= htmlspecialchars($shipment->tracking_number) ?>"></div>
            <?php if ($shipment->last_scan_at): ?>
            <small class="text-muted mt-2 d-block"><i class="bi bi-clock"></i> Last scan: <?= format_date($shipment->last_scan_at, 'M d, Y H:i') ?></small>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Right Column - Management -->
    <div class="col-lg-5">
        <!-- Status Update -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-arrow-up-circle me-2"></i>Update Status</h6>
            <form id="statusForm" onsubmit="event.preventDefault(); updateStatus(<?= $shipment->id ?>);">
                <div class="mb-3">
                    <select name="status_id" class="form-select" required>
                        <?php foreach ($allStatuses as $st): ?>
                        <option value="<?= $st->id ?>" <?= $st->id == $shipment->current_status_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($st->name) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="text" name="location" class="form-control" placeholder="Location (optional)">
                </div>
                <div class="mb-3">
                    <textarea name="description" class="form-control" rows="2" placeholder="Description / Remarks"></textarea>
                </div>
                <button type="submit" class="btn btn-primary admin-btn w-100">
                    <i class="bi bi-arrow-up-circle me-1"></i> Update Status
                </button>
            </form>
        </div>
        
        <!-- Assign Driver -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Assign Driver</h6>
            <form id="driverForm" onsubmit="event.preventDefault(); assignDriver(<?= $shipment->id ?>);">
                <div class="mb-3">
                    <select name="driver_id" class="form-select">
                        <option value="">Select Driver</option>
                        <?php foreach ($drivers as $d): ?>
                        <option value="<?= $d->id ?>" <?= $d->id == $shipment->assigned_driver_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d->first_name) ?> <?= htmlspecialchars($d->last_name) ?> (<?= htmlspecialchars($d->status) ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-info text-white admin-btn w-100">
                    <i class="bi bi-person-check me-1"></i> Assign Driver
                </button>
            </form>
            <?php if ($shipment->driver_name): ?>
            <div class="mt-3 p-3 bg-light rounded">
                <small class="text-muted">Current Driver:</small>
                <p class="fw-bold mb-0"><?= htmlspecialchars($shipment->driver_name) ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Assignment Info -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Assignment Info</h6>
            <div class="mb-2">
                <small class="text-muted">Origin Branch:</small>
                <p class="fw-semibold mb-0"><?= htmlspecialchars($shipment->origin_branch_name ?? 'Not assigned') ?></p>
            </div>
            <div class="mb-2">
                <small class="text-muted">Destination Branch:</small>
                <p class="fw-semibold mb-0"><?= htmlspecialchars($shipment->destination_branch_name ?? 'Not assigned') ?></p>
            </div>
            <div class="mb-2">
                <small class="text-muted">Current Warehouse:</small>
                <p class="fw-semibold mb-0"><?= htmlspecialchars($shipment->warehouse_name ?? 'N/A') ?></p>
            </div>
            <div>
                <small class="text-muted">Vehicle:</small>
                <p class="fw-semibold mb-0"><?= htmlspecialchars($shipment->vehicle_reg ?? 'Not assigned') ?></p>
            </div>
        </div>
        
        <!-- Tracking Timeline -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Tracking Timeline</h6>
            <?php if (!empty($history)): ?>
                <?php foreach ($history as $h): ?>
                <div class="d-flex gap-3 mb-3 pb-3 border-bottom timeline-entry" data-id="<?= $h->id ?>">
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: <?= $h->status_color ?? '#6c757d' ?>; flex-shrink: 0; margin-top: 4px;"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <p class="fw-semibold mb-0 small"><?= htmlspecialchars($h->status_name) ?></p>
                            <button class="btn btn-sm btn-outline-primary py-0 px-2" onclick="editHistory(<?= $h->id ?>, '<?= htmlspecialchars(addslashes($h->created_at), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($h->location ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($h->description ?? ''), ENT_QUOTES) ?>')" title="Edit entry">
                                <i class="bi bi-pencil-square" style="font-size:11px"></i>
                            </button>
                        </div>
                        <small class="text-muted"><?= $h->description ? htmlspecialchars($h->description) . ' • ' : '' ?><?= format_date($h->created_at, 'M d, Y H:i') ?></small>
                        <?php if ($h->location): ?>
                        <br><small class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($h->location) ?></small>
                        <?php endif; ?>
                        <?php if ($h->updated_by_name): ?>
                        <br><small class="text-muted">by <?= htmlspecialchars($h->updated_by_name) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted small mb-0">No tracking history available.</p>
            <?php endif; ?>
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
        <div class="modal-content" style="background:#fff;color:#1a1a2e">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Tracking Entry</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editHistoryForm">
                    <input type="hidden" id="editHistoryId">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Date & Time</label>
                        <input type="datetime-local" class="form-control" id="editHistoryDate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Location</label>
                        <input type="text" class="form-control" id="editHistoryLocation" placeholder="e.g. Nairobi, Kenya">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Description</label>
                        <textarea class="form-control" id="editHistoryDescription" rows="2" placeholder="Optional description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveHistory()"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
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
