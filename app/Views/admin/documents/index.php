<!-- ============================================================
     DOCUMENTS MANAGEMENT - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Documents</h4>
        <small class="text-muted">Manage uploaded files and documents</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/documents/create" class="btn btn-primary admin-btn">
        <i class="bi bi-cloud-arrow-up me-1"></i> Upload Document
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3" style="cursor:pointer;" onclick="window.location='<?= BASE_URL ?>/admin/documents'">
            <div class="fw-bold fs-5"><?= number_format($stats->total ?? 0) ?></div>
            <small class="text-muted">Total Files</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3">
            <div class="fw-bold fs-5 text-primary"><?= $stats->invoices ?? 0 ?></div>
            <small class="text-muted">Invoices</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3">
            <div class="fw-bold fs-5 text-info"><?= ($stats->labels ?? 0) + ($stats->pod ?? 0) ?></div>
            <small class="text-muted">Labels & POD</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3">
            <div class="fw-bold fs-5 text-success"><?= $stats->photos ?? 0 ?></div>
            <small class="text-muted">Photos</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3">
            <div class="fw-bold fs-5 text-warning"><?= $stats->customs ?? 0 ?></div>
            <small class="text-muted">Customs</small>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="admin-card mini-stat text-center py-3">
            <?php
            $totalBytes = $stats->total_size ?? 0;
            $sizeStr = $totalBytes > 1073741824 ? round($totalBytes / 1073741824, 1) . ' GB' : ($totalBytes > 1048576 ? round($totalBytes / 1048576, 1) . ' MB' : round($totalBytes / 1024, 1) . ' KB');
            ?>
            <div class="fw-bold fs-5"><?= $sizeStr ?></div>
            <small class="text-muted">Total Size</small>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="admin-card mb-4">
    <form method="GET" action="<?= BASE_URL ?>/admin/documents" id="filterForm">
        <div class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold small">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="File name, notes..." value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Type</label>
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <?php foreach ($docTypes as $t): ?>
                    <option value="<?= htmlspecialchars($t->document_type) ?>" <?= $filters['type'] === $t->document_type ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $t->document_type))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-semibold small">Shipment ID</label>
                <input type="number" name="shipment_id" class="form-control" placeholder="ID" value="<?= $filters['shipment_id'] ?: '' ?>">
            </div>
            <div class="col-lg-2 col-md-6">
                <button type="submit" class="btn btn-primary admin-btn w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
            <div class="col-lg-2 col-md-6">
                <a href="<?= BASE_URL ?>/admin/documents" class="btn btn-outline-secondary admin-btn w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
            </div>
        </div>
        <?php if (!empty($filters['search']) || !empty($filters['type']) || $filters['shipment_id']): ?>
        <div class="mt-3 d-flex gap-2 flex-wrap align-items-center">
            <small class="text-muted me-1">Active filters:</small>
            <?php if (!empty($filters['search'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">Search: <?= htmlspecialchars($filters['search']) ?> <a href="?type=<?= urlencode($filters['type']) ?>&shipment_id=<?= $filters['shipment_id'] ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
            <?php endif; ?>
            <?php if (!empty($filters['type'])): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">Type: <?= ucwords(str_replace('_', ' ', $filters['type'])) ?> <a href="?search=<?= urlencode($filters['search']) ?>&shipment_id=<?= $filters['shipment_id'] ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
            <?php endif; ?>
            <?php if ($filters['shipment_id']): ?>
                <span class="badge bg-primary d-flex align-items-center gap-1">Shipment: <?= $filters['shipment_id'] ?> <a href="?search=<?= urlencode($filters['search']) ?>&type=<?= urlencode($filters['type']) ?>" class="text-white" style="text-decoration:none;">&times;</a></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </form>
</div>

<!-- Type Filter Pills -->
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="<?= BASE_URL ?>/admin/documents" class="status-pill <?= empty($filters['type']) ? 'active' : '' ?>">
        <i class="bi bi-grid-3x3-gap me-1"></i> All <span class="badge bg-light text-dark ms-1"><?= number_format($stats->total ?? 0) ?></span>
    </a>
    <?php
    $typeIcons = ['invoice' => 'bi-receipt', 'label' => 'bi-upc-scan', 'proof_of_delivery' => 'bi-check-circle', 'photo' => 'bi-camera', 'customs' => 'bi-globe', 'receipt' => 'bi-credit-card', 'contract' => 'bi-file-text', 'other' => 'bi-files'];
    $typeAliases = ['proof_of_delivery' => 'pod'];
    foreach ($docTypes as $t):
        $typeKey = $typeAliases[$t->document_type] ?? $t->document_type;
        $typeCount = $stats->$typeKey ?? 0;
    ?>
    <a href="<?= BASE_URL ?>/admin/documents?type=<?= urlencode($t->document_type) ?>" class="status-pill <?= $filters['type'] === $t->document_type ? 'active' : '' ?>">
        <i class="bi <?= $typeIcons[$t->document_type] ?? 'bi-file' ?> me-1"></i> <?= ucwords(str_replace('_', ' ', $t->document_type)) ?>
        <?php if ($typeCount > 0): ?>
        <span class="badge bg-light text-dark ms-1"><?= $typeCount ?></span>
        <?php endif; ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- Documents Table -->
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <small class="text-muted">Showing <strong><?= $pagination->from ?></strong>–<strong><?= $pagination->to ?></strong> of <strong><?= number_format($pagination->total) ?></strong> documents</small>
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small me-1">Per page:</label>
            <select class="form-select form-select-sm" style="width:auto;" onchange="window.location=this.value">
                <?php foreach ([10, 25, 50, 100] as $pp): ?>
                <option value="?page=1&per_page=<?= $pp ?>&type=<?= urlencode($filters['type']) ?>&search=<?= urlencode($filters['search']) ?>&shipment_id=<?= $filters['shipment_id'] ?>" <?= ($pagination->perPage ?? 25) == $pp ? 'selected' : '' ?>><?= $pp ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>File Name</th>
                    <th>Type</th>
                    <th>Shipment</th>
                    <th>Uploaded By</th>
                    <th class="text-end">Size</th>
                    <th>Date</th>
                    <th class="text-center" style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $d):
                        $ext = strtolower(pathinfo($d->original_name, PATHINFO_EXTENSION));
                        $fileIcons = [
                            'pdf' => ['icon' => 'bi-file-earmark-pdf', 'color' => '#dc3545'],
                            'jpg' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd'],
                            'jpeg' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd'],
                            'png' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd'],
                            'gif' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd'],
                            'webp' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd'],
                            'doc' => ['icon' => 'bi-file-earmark-word', 'color' => '#0d6efd'],
                            'docx' => ['icon' => 'bi-file-earmark-word', 'color' => '#0d6efd'],
                            'xls' => ['icon' => 'bi-file-earmark-excel', 'color' => '#198754'],
                            'xlsx' => ['icon' => 'bi-file-earmark-excel', 'color' => '#198754'],
                            'csv' => ['icon' => 'bi-filetype-csv', 'color' => '#198754'],
                            'txt' => ['icon' => 'bi-file-earmark-text', 'color' => '#6c757d'],
                        ];
                        $fi = $fileIcons[$ext] ?? ['icon' => 'bi-file', 'color' => '#6c757d'];
                        $sizeKB = $d->file_size ? ($d->file_size > 1048576 ? round($d->file_size / 1048576, 1) . ' MB' : round($d->file_size / 1024, 1) . ' KB') : '-';
                    ?>
                    <tr>
                        <td>
                            <div style="width:36px;height:36px;border-radius:8px;background:<?= $fi['color'] ?>12;display:flex;align-items:center;justify-content:center;">
                                <i class="bi <?= $fi['icon'] ?>" style="color:<?= $fi['color'] ?>;font-size:1.1rem;"></i>
                            </div>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/documents/<?= $d->id ?>" class="text-decoration-none">
                                <div class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars($d->original_name) ?></div>
                            </a>
                            <?php if ($d->notes): ?>
                            <small class="text-muted"><?= htmlspecialchars(mb_strimwidth($d->notes, 0, 50, '...')) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background:<?= $fi['color'] ?>15;color:<?= $fi['color'] ?>;border:1px solid <?= $fi['color'] ?>30;font-weight:600;">
                                <?= ucwords(str_replace('_', ' ', $d->document_type)) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($d->tracking_number): ?>
                            <a href="<?= BASE_URL ?>/admin/shipments/<?= $d->shipment_id ?>" class="text-decoration-none">
                                <span class="badge bg-light text-dark border"><i class="bi bi-box-seam me-1"></i><?= htmlspecialchars($d->tracking_number) ?></span>
                            </a>
                            <?php else: ?>
                            <small class="text-muted">-</small>
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($d->uploaded_by_name ?? '-') ?></small></td>
                        <td class="text-end"><small class="text-muted"><?= $sizeKB ?></small></td>
                        <td><small class="text-muted"><?= format_date($d->created_at, 'M d, Y') ?></small></td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="<?= BASE_URL ?>/admin/documents/<?= $d->id ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/admin/documents/<?= $d->id ?>/download" class="btn btn-sm btn-outline-success" title="Download"><i class="bi bi-download"></i></a>
                                <button class="btn btn-sm btn-outline-danger delete-doc-btn" title="Delete" data-id="<?= $d->id ?>" data-name="<?= htmlspecialchars($d->original_name) ?>"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-files" style="font-size:2.5rem;opacity:0.3;"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No documents found</h6>
                                <p class="mb-3">Upload your first document to get started.</p>
                                <a href="<?= BASE_URL ?>/admin/documents/create" class="btn btn-primary btn-sm"><i class="bi bi-cloud-arrow-up me-1"></i> Upload Document</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination->totalPages > 1): ?>
    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
        <small class="text-muted">Page <?= $pagination->page ?> of <?= number_format($pagination->totalPages) ?></small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=1&type=<?= urlencode($filters['type']) ?>&search=<?= urlencode($filters['search']) ?>&shipment_id=<?= $filters['shipment_id'] ?>"><i class="bi bi-chevron-double-left"></i></a></li>
                <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page - 1 ?>&type=<?= urlencode($filters['type']) ?>&search=<?= urlencode($filters['search']) ?>&shipment_id=<?= $filters['shipment_id'] ?>"><i class="bi bi-chevron-left"></i></a></li>
                <?php
                $start = max(1, $pagination->page - 2);
                $end = min($pagination->totalPages, $pagination->page + 2);
                if ($start > 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <?php for ($p = $start; $p <= $end; $p++): ?>
                <li class="page-item <?= $p === $pagination->page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $p ?>&type=<?= urlencode($filters['type']) ?>&search=<?= urlencode($filters['search']) ?>&shipment_id=<?= $filters['shipment_id'] ?>"><?= $p ?></a></li>
                <?php endfor; ?>
                <?php if ($end < $pagination->totalPages): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->page + 1 ?>&type=<?= urlencode($filters['type']) ?>&search=<?= urlencode($filters['search']) ?>&shipment_id=<?= $filters['shipment_id'] ?>"><i class="bi bi-chevron-right"></i></a></li>
                <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $pagination->totalPages ?>&type=<?= urlencode($filters['type']) ?>&search=<?= urlencode($filters['search']) ?>&shipment_id=<?= $filters['shipment_id'] ?>"><i class="bi bi-chevron-double-right"></i></a></li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<style>
    .mini-stat { transition: all 0.2s; }
    .mini-stat:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .status-pill { display:inline-flex;align-items:center;padding:6px 14px;border-radius:20px;border:1px solid #e9ecef;background:#fff;color:#495057;text-decoration:none;font-size:0.82rem;font-weight:600;transition:all 0.2s;white-space:nowrap; }
    .status-pill:hover { background:#f8f9fa;border-color:#dee2e6;color:#212529;transform:translateY(-1px); }
    .status-pill.active { background:var(--admin-primary,#1a237e);color:#fff;border-color:var(--admin-primary,#1a237e); }
    .status-pill.active .badge { background:rgba(255,255,255,0.2) !important;color:#fff !important; }
    .status-pill .badge { font-size:0.7rem; }
    .table-admin thead th { font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6c757d;border-bottom:2px solid #e9ecef;padding:12px; }
    .table-admin tbody td { padding:12px;border-bottom:1px solid #f1f3f5; }
    .table-admin tbody tr:hover { background:#f8f9fa; }
    .pagination .page-link { border-radius:6px;margin:0 2px;border:none;color:#6c757d;font-size:0.82rem; }
    .pagination .page-item.active .page-link { background:var(--admin-primary,#1a237e);color:#fff; }
    .pagination .page-link:hover { background:#e9ecef; }
</style>

<script>
document.querySelectorAll('.delete-doc-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var name = this.getAttribute('data-name');
        Swal.fire({
            title: 'Delete Document?',
            text: 'Delete "' + name + '"? This cannot be undone.',
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
                form.action = '<?= BASE_URL ?>/admin/documents/' + id;
                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta) {
                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_csrf_token';
                    csrfInput.value = csrfMeta.getAttribute('content');
                    form.appendChild(csrfInput);
                }
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
</script>
