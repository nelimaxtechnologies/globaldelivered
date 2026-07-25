<div class="container py-4">
    <a href="<?= BASE_URL ?>/dashboard/shipments" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Back to Shipments</a>
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($shipment->tracking_number) ?></h5>
                    <span class="badge" style="background:<?= $shipment->status_color ?? '#6c757d' ?>"><?= htmlspecialchars($shipment->status_name ?? $shipment->status) ?></span>
                    <span class="text-muted ms-3 small"><i class="bi bi-calendar"></i> ETA: <?= $shipment->expected_delivery_date ? format_date($shipment->expected_delivery_date) : 'Pending' ?></span>
                </div>
                <a href="<?= BASE_URL ?>/dashboard/shipments/<?= $shipment->id ?>/label" class="btn btn-outline-info btn-sm" target="_blank"><i class="bi bi-upc-scan"></i> Print Label</a>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-7">
            <!-- Package Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Package Details</h6>
                    <div class="row g-3">
                        <div class="col-6"><small class="text-muted">Service</small><p class="fw-semibold mb-0 text-capitalize"><?= str_replace('_', ' ', htmlspecialchars($shipment->service_type)) ?></p></div>
                        <div class="col-6"><small class="text-muted">Weight</small><p class="fw-semibold mb-0"><?= $shipment->weight ?> kg</p></div>
                        <div class="col-6"><small class="text-muted">From</small><p class="fw-semibold mb-0"><?= htmlspecialchars($shipment->sender_city) ?>, <?= htmlspecialchars($shipment->sender_country) ?></p></div>
                        <div class="col-6"><small class="text-muted">To</small><p class="fw-semibold mb-0"><?= htmlspecialchars($shipment->recipient_city) ?>, <?= htmlspecialchars($shipment->recipient_country) ?></p></div>
                    </div>
                </div>
            </div>
            
            <!-- Sender & Recipient -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2 text-primary">Sender</h6>
                            <p class="mb-1 fw-semibold"><?= htmlspecialchars($shipment->sender_name) ?></p>
                            <p class="mb-1 small"><?= htmlspecialchars($shipment->sender_phone) ?></p>
                            <p class="mb-0 small text-muted"><?= htmlspecialchars($shipment->sender_address) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2 text-success">Recipient</h6>
                            <p class="mb-1 fw-semibold"><?= htmlspecialchars($shipment->recipient_name) ?></p>
                            <p class="mb-1 small"><?= htmlspecialchars($shipment->recipient_phone) ?></p>
                            <p class="mb-0 small text-muted"><?= htmlspecialchars($shipment->recipient_address) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <!-- Tracking History -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Tracking History</h6>
                    <?php if (!empty($history)): ?>
                        <?php foreach ($history as $h): ?>
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: <?= $h->status_color ?? '#6c757d' ?>; flex-shrink: 0; margin-top: 5px;"></div>
                            <div>
                                <p class="fw-semibold mb-0 small"><?= htmlspecialchars($h->status_name) ?></p>
                                <small class="text-muted"><?= format_date($h->created_at, 'M d, Y H:i') ?></small>
                                <?php if ($h->description): ?><br><small><?= htmlspecialchars($h->description) ?></small><?php endif; ?>
                                <?php if ($h->location): ?><br><small class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($h->location) ?></small><?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted small">No tracking history yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
