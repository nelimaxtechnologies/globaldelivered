<!-- ============================================================
     UPLOAD DOCUMENT - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-cloud-arrow-up me-2"></i>Upload Document</h4>
        <small class="text-muted">Upload files and attach to shipments</small>
    </div>
    <a href="<?= BASE_URL ?>/admin/documents" class="btn btn-outline-secondary admin-btn">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<!-- Shipment Context Banner -->
<?php if ($shipment): ?>
<div class="alert alert-info d-flex align-items-center mb-4" style="border-radius:12px;border-left:4px solid #0dcaf0;">
    <div class="me-3">
        <div style="width:40px;height:40px;border-radius:10px;background:#0dcaf020;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-box-seam text-info fs-5"></i>
        </div>
    </div>
    <div>
        Attaching to shipment:
        <a href="<?= BASE_URL ?>/admin/shipments/<?= $shipment->id ?>" class="fw-bold"><?= htmlspecialchars($shipment->tracking_number) ?></a>
    </div>
</div>
<?php endif; ?>

<?php if ($customer): ?>
<div class="alert alert-info d-flex align-items-center mb-4" style="border-radius:12px;border-left:4px solid #0dcaf0;">
    <div class="me-3">
        <div style="width:40px;height:40px;border-radius:10px;background:#0dcaf020;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-person text-info fs-5"></i>
        </div>
    </div>
    <div>
        Customer: <strong><?= htmlspecialchars($customer->first_name . ' ' . $customer->last_name) ?></strong>
    </div>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="<?= BASE_URL ?>/admin/documents" enctype="multipart/form-data" id="uploadForm">
            <?= csrf_field() ?>

            <?php if ($shipment): ?>
            <input type="hidden" name="shipment_id" value="<?= $shipment->id ?>">
            <?php endif; ?>
            <?php if ($customer): ?>
            <input type="hidden" name="customer_id" value="<?= $customer->id ?>">
            <?php endif; ?>

            <!-- Document Details -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">1</div>
                    <h6 class="fw-bold mb-0">Document Details</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" id="docType">
                            <option value="invoice">Invoice</option>
                            <option value="label">Shipping Label</option>
                            <option value="proof_of_delivery">Proof of Delivery</option>
                            <option value="photo">Photo</option>
                            <option value="customs">Customs Document</option>
                            <option value="receipt">Receipt</option>
                            <option value="contract">Contract</option>
                            <option value="other" selected>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Shipment</label>
                        <select name="shipment_id" class="form-select select2" <?= $shipment ? 'disabled' : '' ?>>
                            <option value="">No shipment</option>
                            <?php foreach ($shipments as $s): ?>
                            <option value="<?= $s->id ?>" <?= $shipment && $s->id == $shipment->id ? 'selected' : '' ?>><?= htmlspecialchars($s->label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional description or notes about this document..."></textarea>
                    </div>
                </div>
            </div>

            <!-- File Upload Zone -->
            <div class="admin-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#198754,#146c43);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">2</div>
                    <h6 class="fw-bold mb-0">Select File</h6>
                </div>

                <div id="dropZone" class="drop-zone" onclick="document.getElementById('fileInput').click()">
                    <div id="uploadPrompt">
                        <div style="width:80px;height:80px;border-radius:20px;background:#f4f6f9;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-cloud-arrow-up" style="font-size:2.5rem;color:#adb5bd;"></i>
                        </div>
                        <h6 class="fw-bold mb-1">Drag & drop your file here</h6>
                        <p class="text-muted small mb-3">or click to browse from your computer</p>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation();document.getElementById('fileInput').click();">
                            <i class="bi bi-folder2-open me-1"></i> Browse Files
                        </button>
                        <div class="mt-3">
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <span class="badge bg-light text-dark"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-file-earmark-image me-1"></i>Images</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-file-earmark-word me-1"></i>Word</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-file-earmark-excel me-1"></i>Excel</span>
                                <span class="badge bg-light text-dark"><i class="bi bi-filetype-csv me-1"></i>CSV</span>
                            </div>
                            <small class="text-muted mt-2 d-block">Max file size: 10MB</small>
                        </div>
                    </div>

                    <div id="uploadPreview" style="display:none;">
                        <div class="d-flex align-items-center gap-3 p-4 bg-light rounded-3">
                            <div id="fileIcon" style="width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;"></div>
                            <div class="flex-grow-1 text-start">
                                <p class="fw-bold mb-0" id="fileName">-</p>
                                <small class="text-muted" id="fileSize">-</small>
                                <span class="badge bg-secondary ms-2" id="fileExt">-</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="resetUpload()">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="progress mt-3" style="height:6px;display:none;" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%;background:linear-gradient(90deg,#198754,#20c997);" id="progressBar"></div>
                        </div>
                    </div>

                    <input type="file" name="file" id="fileInput" class="d-none"
                           accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,.doc,.docx,.xls,.xlsx,.csv,.txt"
                           onchange="handleFiles(this.files)">
                </div>
            </div>

            <!-- Submit -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg admin-btn" id="submitBtn" disabled>
                    <i class="bi bi-cloud-arrow-up me-1"></i> Upload Document
                </button>
                <a href="<?= BASE_URL ?>/admin/documents" class="btn btn-outline-secondary btn-lg admin-btn">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- File Type Reference -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Supported Files</h6>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:#dc354512;display:flex;align-items:center;justify-content:center;"><i class="bi bi-file-earmark-pdf" style="color:#dc3545;"></i></div>
                    <small class="fw-semibold">PDF Documents</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:#0d6efd12;display:flex;align-items:center;justify-content:center;"><i class="bi bi-file-earmark-image" style="color:#0d6efd;"></i></div>
                    <small class="fw-semibold">Images (JPG, PNG, GIF, WebP)</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:#0d6efd12;display:flex;align-items:center;justify-content:center;"><i class="bi bi-file-earmark-word" style="color:#0d6efd;"></i></div>
                    <small class="fw-semibold">Word (DOC, DOCX)</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:#19875412;display:flex;align-items:center;justify-content:center;"><i class="bi bi-file-earmark-excel" style="color:#198754;"></i></div>
                    <small class="fw-semibold">Excel (XLS, XLSX, CSV)</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:#6c757d12;display:flex;align-items:center;justify-content:center;"><i class="bi bi-file-earmark-text" style="color:#6c757d;"></i></div>
                    <small class="fw-semibold">Text Files (TXT)</small>
                </div>
            </div>
        </div>

        <!-- Upload Tips -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Tips</h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Drag & drop or click to browse</small></li>
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Files are organized by type and date</small></li>
                <li class="mb-2"><small><i class="bi bi-check2 text-success me-2"></i>Attach to shipments for easy access</small></li>
                <li class="mb-0"><small><i class="bi bi-check2 text-success me-2"></i>Max file size: 10MB</small></li>
            </ul>
        </div>

        <!-- Recent Uploads -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Recent Uploads</h6>
            <?php if (!empty($recentDocs)): ?>
                <?php foreach ($recentDocs as $rd):
                    $rdExt = strtolower(pathinfo($rd->original_name, PATHINFO_EXTENSION));
                    $rdColors = ['pdf'=>'#dc3545','jpg'=>'#0d6efd','jpeg'=>'#0d6efd','png'=>'#0d6efd','gif'=>'#0d6efd','webp'=>'#0d6efd','doc'=>'#0d6efd','docx'=>'#0d6efd','xls'=>'#198754','xlsx'=>'#198754','csv'=>'#198754','txt'=>'#6c757d'];
                    $rdColor = $rdColors[$rdExt] ?? '#6c757d';
                    $rdSize = $rd->file_size ? ($rd->file_size > 1048576 ? round($rd->file_size/1048576,1).'MB' : round($rd->file_size/1024,1).'KB') : '-';
                ?>
                <a href="<?= BASE_URL ?>/admin/documents/<?= $rd->id ?>" class="text-decoration-none d-block p-2 rounded mb-1" style="transition:background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:28px;height:28px;border-radius:6px;background:<?= $rdColor ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-file-earmark" style="color:<?= $rdColor ?>;font-size:0.75rem;"></i>
                        </div>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-semibold text-truncate" style="font-size:0.82rem;color:#212529;"><?= htmlspecialchars($rd->original_name) ?></div>
                            <small class="text-muted"><?= $rdSize ?> &middot; <?= format_date($rd->created_at, 'M d, H:i') ?></small>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted small mb-0">No recent uploads</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .drop-zone { border:2px dashed #dee2e6;border-radius:12px;padding:40px 20px;text-align:center;cursor:pointer;transition:all 0.3s;background:#fff; }
    .drop-zone:hover { border-color:var(--admin-primary,#1a237e);background:rgba(26,35,126,0.02); }
    .drop-zone.drag-over { border-color:var(--admin-primary,#1a237e);background:rgba(26,35,126,0.05);transform:scale(1.01); }
</style>

<script>
let selectedFile = null;

const dropZone = document.getElementById('dropZone');
dropZone.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('drag-over'); });
dropZone.addEventListener('dragleave', function(e) { e.preventDefault(); this.classList.remove('drag-over'); });
dropZone.addEventListener('drop', function(e) { e.preventDefault(); this.classList.remove('drag-over'); handleFiles(e.dataTransfer.files); });

function handleFiles(files) {
    if (files.length === 0) return;
    const file = files[0];
    selectedFile = file;

    if (file.size > 10485760) {
        Swal.fire({ icon: 'error', title: 'File too large', text: 'Maximum file size is 10MB.' });
        resetUpload();
        return;
    }

    const allowed = ['pdf','jpg','jpeg','png','gif','webp','doc','docx','xls','xlsx','csv','txt'];
    const ext = file.name.split('.').pop().toLowerCase();
    if (!allowed.includes(ext)) {
        Swal.fire({ icon: 'error', title: 'Invalid file type', text: 'This file type is not allowed.' });
        resetUpload();
        return;
    }

    var extColors = { pdf:'#dc3545', jpg:'#0d6efd', jpeg:'#0d6efd', png:'#0d6efd', gif:'#0d6efd', webp:'#0d6efd', doc:'#0d6efd', docx:'#0d6efd', xls:'#198754', xlsx:'#198754', csv:'#198754', txt:'#6c757d' };
    var extIcons = { pdf:'bi-file-earmark-pdf', jpg:'bi-file-earmark-image', jpeg:'bi-file-earmark-image', png:'bi-file-earmark-image', gif:'bi-file-earmark-image', webp:'bi-file-earmark-image', doc:'bi-file-earmark-word', docx:'bi-file-earmark-word', xls:'bi-file-earmark-excel', xlsx:'bi-file-earmark-excel', csv:'bi-filetype-csv', txt:'bi-file-earmark-text' };
    var color = extColors[ext] || '#6c757d';
    var icon = extIcons[ext] || 'bi-file';

    document.getElementById('uploadPrompt').style.display = 'none';
    document.getElementById('uploadPreview').style.display = 'block';
    document.getElementById('fileName').textContent = file.name;

    var sizeStr = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + ' MB' : (file.size / 1024).toFixed(1) + ' KB';
    document.getElementById('fileSize').textContent = sizeStr;
    document.getElementById('fileExt').textContent = '.' + ext;

    var iconEl = document.getElementById('fileIcon');
    iconEl.style.background = color + '15';
    iconEl.innerHTML = '<i class="bi ' + icon + '" style="color:' + color + ';"></i>';

    document.getElementById('submitBtn').disabled = false;
}

function resetUpload() {
    selectedFile = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('uploadPrompt').style.display = 'block';
    document.getElementById('uploadPreview').style.display = 'none';
    document.getElementById('submitBtn').disabled = true;
}

$('#uploadForm').on('submit', function(e) {
    if (!selectedFile) {
        Swal.fire({ icon: 'warning', title: 'No file selected', text: 'Please select a file to upload.' });
        e.preventDefault();
        return;
    }
    $('#uploadProgress').show();
    $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Uploading...');
    var progress = 0;
    var interval = setInterval(function() {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        $('#progressBar').css('width', progress + '%');
    }, 300);
    setTimeout(function() { clearInterval(interval); $('#progressBar').css('width', '100%'); }, 2000);
});
</script>
