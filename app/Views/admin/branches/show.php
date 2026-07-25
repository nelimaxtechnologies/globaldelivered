<?php
$typeColors = [
    'head_office' => ['#6f42c1', '#6f42c120'],
    'regional' => ['#0dcaf0', '#0dcaf020'],
    'local' => ['#198754', '#19875420'],
];
[$typeColor, $typeBg] = $typeColors[$branch->branch_type] ?? ['#6c757d', '#6c757d20'];
?>

<!-- Branch Header -->
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width:56px;height:56px;border-radius:14px;background:<?= $typeBg ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-building" style="font-size:1.5rem;color:<?= $typeColor ?>;"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1">
                <?= htmlspecialchars($branch->name) ?>
                <span class="badge ms-2" style="font-size:0.72rem;background:<?= $typeBg ?>;color:<?= $typeColor ?>;border:1px solid <?= $typeColor ?>30;">
                    <?= htmlspecialchars($branch->code) ?>
                </span>
            </h4>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="badge rounded-pill" style="background:<?= $typeBg ?>;color:<?= $typeColor ?>;border:1px solid <?= $typeColor ?>30;font-weight:600;">
                    <?= str_replace('_', ' ', ucfirst($branch->branch_type)) ?>
                </span>
                <span class="small text-muted">
                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($branch->city) ?>, <?= htmlspecialchars($branch->country) ?>
                </span>
                <?php if ($branch->manager_name): ?>
                <span class="small text-muted"><i class="bi bi-person me-1"></i><?= htmlspecialchars($branch->manager_name) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <div class="form-check form-switch me-2 d-flex align-items-center gap-2">
            <input class="form-check-input branch-toggle" type="checkbox" data-id="<?= $branch->id ?>" <?= $branch->is_active ? 'checked' : '' ?>
                   style="cursor:pointer; <?= $branch->is_active ? 'background-color:#198754;border-color:#198754;' : '' ?>">
            <label class="form-check-label fw-semibold small" id="statusLabel"><?= $branch->is_active ? 'Active' : 'Inactive' ?></label>
        </div>
        <a href="<?= BASE_URL ?>/admin/branches/<?= $branch->id ?>/edit" class="btn btn-outline-primary admin-btn">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <button class="btn btn-outline-danger admin-btn delete-branch-btn" data-id="<?= $branch->id ?>" data-name="<?= htmlspecialchars($branch->name) ?>">
            <i class="bi bi-trash me-1"></i> Delete
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #0d6efd;">
            <div style="width:44px;height:44px;border-radius:12px;background:#0d6efd20;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                <i class="bi bi-box-seam" style="font-size:1.2rem;color:#0d6efd;"></i>
            </div>
            <div class="fw-bold fs-5"><?= number_format((int)($stats->total_shipments ?? 0)) ?></div>
            <small class="text-muted">Total Shipments</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #198754;">
            <div style="width:44px;height:44px;border-radius:12px;background:#19875420;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                <i class="bi bi-check-circle" style="font-size:1.2rem;color:#198754;"></i>
            </div>
            <div class="fw-bold fs-5 text-success"><?= number_format((int)($stats->delivered ?? 0)) ?></div>
            <small class="text-muted">Delivered</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #ffc107;">
            <div style="width:44px;height:44px;border-radius:12px;background:#ffc10720;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                <i class="bi bi-truck" style="font-size:1.2rem;color:#ffc107;"></i>
            </div>
            <div class="fw-bold fs-5" style="color:#ffc107;"><?= number_format((int)($stats->active ?? 0)) ?></div>
            <small class="text-muted">Active</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center py-3" style="border-top:3px solid #0dcaf0;">
            <div style="width:44px;height:44px;border-radius:12px;background:#0dcaf020;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                <i class="bi bi-currency-dollar" style="font-size:1.2rem;color:#0dcaf0;"></i>
            </div>
            <div class="fw-bold fs-5" style="color:#0dcaf0;"><?= format_currency($stats->total_revenue ?? 0) ?></div>
            <small class="text-muted">Total Revenue</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left: Info Cards -->
    <div class="col-lg-5">
        <!-- Contact Information -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-telephone me-2"></i>Contact Information</h6>
            <div class="d-flex align-items-center gap-3 mb-3 p-3" style="background:#f8f9fa;border-radius:10px;">
                <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#0d6efd,#0a58ca);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <div class="fw-semibold"><?= htmlspecialchars($branch->name) ?></div>
                    <small class="text-muted"><?= htmlspecialchars($branch->code) ?></small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-geo-alt text-muted" style="width:20px;"></i>
                <div>
                    <small><?= htmlspecialchars($branch->address_line1) ?><?= $branch->address_line2 ? ', ' . htmlspecialchars($branch->address_line2) : '' ?></small>
                    <div><small class="text-muted"><?= htmlspecialchars($branch->city) ?>, <?= htmlspecialchars($branch->state) ?> <?= htmlspecialchars($branch->postal_code) ?></small></div>
                    <div><small class="text-muted"><?= htmlspecialchars($branch->country) ?></small></div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-phone text-muted" style="width:20px;"></i>
                <small><?= htmlspecialchars($branch->phone) ?></small>
            </div>
            <?php if ($branch->email): ?>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-envelope text-muted" style="width:20px;"></i>
                <small><?= htmlspecialchars($branch->email) ?></small>
            </div>
            <?php endif; ?>
            <?php if ($branch->whatsapp): ?>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-whatsapp text-muted" style="width:20px;"></i>
                <small><?= htmlspecialchars($branch->whatsapp) ?></small>
            </div>
            <?php endif; ?>
            <?php if ($branch->manager_name): ?>
            <hr>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person text-muted" style="width:20px;"></i>
                <div>
                    <small class="fw-semibold"><?= htmlspecialchars($branch->manager_name) ?></small>
                    <?php if ($branch->manager_email): ?>
                    <div><small class="text-muted"><?= htmlspecialchars($branch->manager_email) ?></small></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Operating Hours -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock me-2"></i>Operating Hours</h6>
            <div class="d-flex align-items-center gap-3 p-3" style="background:#f8f9fa;border-radius:10px;">
                <div style="width:44px;height:44px;border-radius:12px;background:#ffc10720;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-clock-history" style="font-size:1.1rem;color:#ffc107;"></i>
                </div>
                <div>
                    <?php if ($branch->opening_time && $branch->closing_time): ?>
                    <div class="fw-semibold">
                        <?= date('g:i A', strtotime($branch->opening_time)) ?>
                        &mdash;
                        <?= date('g:i A', strtotime($branch->closing_time)) ?>
                    </div>
                    <?php
                    $open = new DateTime($branch->opening_time);
                    $close = new DateTime($branch->closing_time);
                    $hours = $open->diff($close)->h;
                    ?>
                    <small class="text-muted"><?= $hours ?> hours/day</small>
                    <?php else: ?>
                    <small class="text-muted">Hours not set</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Map -->
        <?php if ($branch->latitude && $branch->longitude): ?>
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-map me-2"></i>Location</h6>
            <div id="branchMap" style="height:250px;border-radius:10px;" data-lat="<?= $branch->latitude ?>" data-lng="<?= $branch->longitude ?>"></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Resources -->
    <div class="col-lg-8">
        <!-- Warehouses -->
        <div class="admin-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-boxes me-2"></i>Warehouses <span class="badge bg-primary ms-1" style="font-size:0.7rem;"><?= count($warehouses) ?></span></h6>
                <a href="<?= BASE_URL ?>/admin/warehouses?branch_id=<?= $branch->id ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus me-1"></i> View All
                </a>
            </div>
            <?php if (!empty($warehouses)): ?>
            <div class="row g-3">
                <?php foreach ($warehouses as $w): ?>
                <div class="col-md-6">
                    <div class="p-3" style="background:#f8f9fa;border-radius:10px;border-left:3px solid #0d6efd;">
                        <div class="fw-semibold mb-1"><?= htmlspecialchars($w->name) ?></div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted"><?= htmlspecialchars($w->code) ?></small>
                            <small class="text-muted"><?= htmlspecialchars($w->city) ?></small>
                        </div>
                        <?php if ($w->capacity): ?>
                        <small class="text-muted">Capacity: <?= number_format($w->capacity, 1) ?> m&sup3;</small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-4" style="background:#f8f9fa;border-radius:10px;">
                <div style="width:50px;height:50px;border-radius:12px;background:#e9ecef;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-boxes" style="font-size:1.3rem;opacity:0.3;"></i>
                </div>
                <p class="text-muted mb-2">No warehouses for this branch</p>
                <a href="<?= BASE_URL ?>/admin/warehouses/create?branch_id=<?= $branch->id ?>" class="btn btn-sm btn-outline-primary">Add Warehouse</a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Drivers & Vehicles -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="admin-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-person-badge me-2"></i>Drivers <span class="badge bg-success ms-1" style="font-size:0.7rem;"><?= count($drivers) ?></span></h6>
                    </div>
                    <?php if (!empty($drivers)): ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($drivers as $d): ?>
                        <?php
                        $driverStatusColors = [
                            'available' => '#198754',
                            'on_delivery' => '#ffc107',
                            'off_duty' => '#6c757d',
                            'on_leave' => '#0dcaf0',
                        ];
                        $dsc = $driverStatusColors[$d->status] ?? '#6c757d';
                        ?>
                        <li class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <a href="<?= BASE_URL ?>/admin/drivers/<?= $d->id ?>" class="text-decoration-none d-flex align-items-center gap-2">
                                <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#198754,#146c43);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;">
                                    <?= strtoupper(substr($d->first_name, 0, 1) . substr($d->last_name, 0, 1)) ?>
                                </div>
                                <small class="fw-semibold"><?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?></small>
                            </a>
                            <span class="badge rounded-pill" style="background:<?= $dsc ?>20;color:<?= $dsc ?>;font-size:0.65rem;font-weight:600;">
                                <?= str_replace('_', ' ', ucfirst($d->status)) ?>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <div class="text-center py-3" style="background:#f8f9fa;border-radius:10px;">
                        <i class="bi bi-person" style="font-size:1.5rem;opacity:0.3;"></i>
                        <p class="text-muted mb-0 mt-1">No drivers assigned</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="admin-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-truck me-2"></i>Vehicles <span class="badge bg-info ms-1" style="font-size:0.7rem;"><?= count($vehicles) ?></span></h6>
                    </div>
                    <?php if (!empty($vehicles)): ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($vehicles as $v): ?>
                        <?php
                        $vehicleStatusColors = [
                            'active' => '#198754',
                            'maintenance' => '#ffc107',
                            'out_of_service' => '#dc3545',
                        ];
                        $vsc = $vehicleStatusColors[$v->status] ?? '#6c757d';
                        ?>
                        <li class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <a href="<?= BASE_URL ?>/admin/vehicles/<?= $v->id ?>" class="text-decoration-none d-flex align-items-center gap-2">
                                <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#0dcaf0,#0aa2c0);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.7rem;flex-shrink:0;">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div>
                                    <small class="fw-semibold"><?= htmlspecialchars($v->name) ?></small>
                                    <div><small class="text-muted"><?= htmlspecialchars($v->registration_number) ?></small></div>
                                </div>
                            </a>
                            <span class="badge rounded-pill" style="background:<?= $vsc ?>20;color:<?= $vsc ?>;font-size:0.65rem;font-weight:600;">
                                <?= str_replace('_', ' ', ucfirst($v->status)) ?>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <div class="text-center py-3" style="background:#f8f9fa;border-radius:10px;">
                        <i class="bi bi-truck" style="font-size:1.5rem;opacity:0.3;"></i>
                        <p class="text-muted mb-0 mt-1">No vehicles assigned</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($branch->latitude && $branch->longitude): ?>
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('branchMap');
    var lat = parseFloat(el.dataset.lat);
    var lng = parseFloat(el.dataset.lng);
    var map = L.map(el).setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    L.marker([lat, lng]).addTo(map)
        .bindPopup('<strong><?= htmlspecialchars(addslashes($branch->name)) ?></strong><br><?= htmlspecialchars(addslashes($branch->address_line1)) ?>');
});
</script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Active toggle
    document.querySelectorAll('.branch-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var id = this.dataset.id;
            var btn = this;
            btn.disabled = true;
            $.ajax({
                url: '<?= BASE_URL ?>/admin/branches/' + id + '/toggle-active',
                method: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        btn.style.backgroundColor = res.is_active ? '#198754' : '';
                        btn.style.borderColor = res.is_active ? '#198754' : '';
                        var label = document.getElementById('statusLabel');
                        if (label) label.textContent = res.is_active ? 'Active' : 'Inactive';
                        showToast(res.message, 'success');
                    } else {
                        btn.checked = !btn.checked;
                        showToast(res.message || 'Failed', 'error');
                    }
                    btn.disabled = false;
                },
                error: function() {
                    btn.checked = !btn.checked;
                    btn.disabled = false;
                    showToast('Request failed', 'error');
                }
            });
        });
    });

    // Delete
    document.querySelectorAll('.delete-branch-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var name = this.dataset.name;
            Swal.fire({
                title: 'Delete Branch?',
                html: 'Are you sure you want to delete <strong>' + name + '</strong>?<br><small class="text-danger">This will soft-delete the branch. Warehouses, drivers, and vehicles will not be deleted.</small>',
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
                    form.action = '<?= BASE_URL ?>/admin/branches/' + id;
                    form.innerHTML = '<input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="<?= csrf_token() ?>">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
