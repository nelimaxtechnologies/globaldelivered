<!-- ============================================================
     DOCUMENT DETAILS - PREMIUM MODERN DESIGN
     ============================================================ -->

<?php
$ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
$fileConfig = [
    'pdf' => ['icon' => 'bi-file-earmark-pdf', 'color' => '#dc3545', 'label' => 'PDF Document'],
    'jpg' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd', 'label' => 'JPEG Image'],
    'jpeg' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd', 'label' => 'JPEG Image'],
    'png' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd', 'label' => 'PNG Image'],
    'gif' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd', 'label' => 'GIF Image'],
    'webp' => ['icon' => 'bi-file-earmark-image', 'color' => '#0d6efd', 'label' => 'WebP Image'],
    'doc' => ['icon' => 'bi-file-earmark-word', 'color' => '#0d6efd', 'label' => 'Word Document'],
    'docx' => ['icon' => 'bi-file-earmark-word', 'color' => '#0d6efd', 'label' => 'Word Document'],
    'xls' => ['icon' => 'bi-file-earmark-excel', 'color' => '#198754', 'label' => 'Excel Spreadsheet'],
    'xlsx' => ['icon' => 'bi-file-earmark-excel', 'color' => '#198754', 'label' => 'Excel Spreadsheet'],
    'csv' => ['icon' => 'bi-filetype-csv', 'color' => '#198754', 'label' => 'CSV File'],
    'txt' => ['icon' => 'bi-file-earmark-text', 'color' => '#6c757d', 'label' => 'Text File'],
];
$fc = $fileConfig[$ext] ?? ['icon' => 'bi-file', 'color' => '#6c757d', 'label' => 'File'];
$isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
$isPdf = $ext === 'pdf';
$sizeStr = $doc->file_size ? ($doc->file_size > 1048576 ? round($doc->file_size / 1048576, 1) . ' MB' : round($doc->file_size / 1024, 1) . ' KB') : '-';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width:52px;height:52px;border-radius:14px;background:<?= $fc['color'] ?>15;display:flex;align-items:center;justify-content:center;">
            <i class="bi <?= $fc['icon'] ?>" style="color:<?= $fc['color'] ?>;font-size:1.6rem;"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0"><?= htmlspecialchars($doc->original_name) ?></h4>
            <small class="text-muted"><?= $fc['label'] ?> &middot; <?= $sizeStr ?></small>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/documents/<?= $doc->id ?>/download" class="btn btn-success admin-btn">
            <i class="bi bi-download me-1"></i> Download
        </a>
        <a href="<?= BASE_URL ?>/admin/documents" class="btn btn-outline-secondary admin-btn">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Preview -->
    <div class="col-lg-8">
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-eye me-2"></i>Preview</h6>
            <?php if ($isImage): ?>
            <div class="text-center" style="background:#f8f9fa;border-radius:12px;padding:20px;">
                <img src="<?= BASE_URL ?>/admin/documents/<?= $doc->id ?>/download"
                     class="img-fluid rounded" style="max-height:500px;cursor:zoom-in;"
                     alt="<?= htmlspecialchars($doc->original_name) ?>"
                     onclick="this.style.transform=this.style.transform==='scale(1.5)'?'scale(1)':'scale(1.5)';this.style.transition='transform 0.3s';">
                <p class="text-muted small mt-2 mb-0"><i class="bi bi-zoom-in me-1"></i> Click to zoom</p>
            </div>
            <?php elseif ($isPdf): ?>
            <div class="text-center py-5" style="background:#f8f9fa;border-radius:12px;">
                <div style="width:80px;height:80px;border-radius:20px;background:#dc354515;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-file-earmark-pdf" style="font-size:2.5rem;color:#dc3545;"></i>
                </div>
                <h6 class="fw-bold">PDF Document</h6>
                <p class="text-muted mb-3">Download to view the full document</p>
                <a href="<?= BASE_URL ?>/admin/documents/<?= $doc->id ?>/download" class="btn btn-primary btn-sm">
                    <i class="bi bi-download me-1"></i> Download PDF
                </a>
            </div>
            <?php else: ?>
            <div class="text-center py-5" style="background:#f8f9fa;border-radius:12px;">
                <div style="width:80px;height:80px;border-radius:20px;background:<?= $fc['color'] ?>15;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi <?= $fc['icon'] ?>" style="font-size:2.5rem;color:<?= $fc['color'] ?>;"></i>
                </div>
                <h6 class="fw-bold"><?= $fc['label'] ?></h6>
                <p class="text-muted mb-3">Download to view this file</p>
                <a href="<?= BASE_URL ?>/admin/documents/<?= $doc->id ?>/download" class="btn btn-primary btn-sm">
                    <i class="bi bi-download me-1"></i> Download File
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Details Sidebar -->
    <div class="col-lg-4">
        <!-- File Info -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>File Details</h6>
            <div class="summary-row"><small class="text-muted">Original Name</small><small class="fw-semibold"><?= htmlspecialchars($doc->original_name) ?></small></div>
            <div class="summary-row"><small class="text-muted">File Type</small><span class="badge rounded-pill" style="background:<?= $fc['color'] ?>15;color:<?= $fc['color'] ?>;border:1px solid <?= $fc['color'] ?>30;font-weight:600;"><?= strtoupper($ext) ?></span></div>
            <div class="summary-row"><small class="text-muted">File Size</small><small class="fw-semibold"><?= $sizeStr ?></small></div>
            <div class="summary-row"><small class="text-muted">MIME Type</small><small class="fw-semibold" style="font-size:0.8rem;"><?= htmlspecialchars($doc->mime_type ?? '-') ?></small></div>
            <div class="summary-row"><small class="text-muted">Uploaded By</small><small class="fw-semibold"><?= htmlspecialchars($doc->uploaded_by_name ?? '-') ?></small></div>
            <div class="summary-row"><small class="text-muted">Uploaded At</small><small class="fw-semibold"><?= format_date($doc->created_at) ?></small></div>
            <?php if ($doc->notes): ?>
            <hr>
            <small class="text-muted d-block mb-1">Notes</small>
            <p class="mb-0 small"><?= nl2br(htmlspecialchars($doc->notes)) ?></p>
            <?php endif; ?>
        </div>

        <!-- Linked Records -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-link-45deg me-2"></i>Linked Records</h6>
            <?php if ($doc->shipment_id): ?>
            <a href="<?= BASE_URL ?>/admin/shipments/<?= $doc->shipment_id ?>" class="text-decoration-none">
                <div class="d-flex align-items-center gap-2 p-2 rounded" style="background:#f8f9fa;">
                    <i class="bi bi-box-seam text-primary"></i>
                    <div>
                        <small class="text-muted">Shipment</small>
                        <div class="fw-semibold"><?= htmlspecialchars($doc->tracking_number ?? 'ID: ' . $doc->shipment_id) ?></div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </div>
            </a>
            <?php else: ?>
            <p class="text-muted small mb-0">No linked shipment</p>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Actions</h6>
            <div class="d-grid gap-2">
                <a href="<?= BASE_URL ?>/admin/documents/<?= $doc->id ?>/download" class="btn btn-success admin-btn">
                    <i class="bi bi-download me-1"></i> Download
                </a>
                <button class="btn btn-outline-danger admin-btn" onclick="deleteDocument(<?= $doc->id ?>)">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .summary-row { display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f3f5; }
    .summary-row:last-child { border-bottom:none; }
</style>

<script>
function deleteDocument(id) {
    Swal.fire({
        title: 'Delete Document?',
        text: 'This file will be permanently deleted. This action cannot be undone.',
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
}
</script>
