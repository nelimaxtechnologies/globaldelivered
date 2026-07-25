<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-primary border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#4a6cf7,#6a8cff);">
                    <i class="bi bi-file-text"></i>
                </div>
                <div>
                    <div class="stat-label">Total Templates</div>
                    <div class="stat-value text-primary"><?= number_format((int)($stats->total ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-success border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#28a745,#5cb85c);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Active</div>
                    <div class="stat-value text-success"><?= number_format((int)($stats->active ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-info border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#17a2b8,#45b8d4);">
                    <i class="bi bi-envelope"></i>
                </div>
                <div>
                    <div class="stat-label">Email Templates</div>
                    <div class="stat-value text-info"><?= number_format((int)($stats->emails ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card border-start border-warning border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:linear-gradient(135deg,#ffc107,#ffcd38);">
                    <i class="bi bi-phone"></i>
                </div>
                <div>
                    <div class="stat-label">SMS Templates</div>
                    <div class="stat-value text-warning"><?= number_format((int)($stats->sms ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="bi bi-file-text me-2"></i>Notification Templates</h5>
    <a href="<?= BASE_URL ?>/admin/notifications" class="btn btn-outline-secondary admin-btn admin-btn-sm"><i class="bi bi-bell me-1"></i> Notifications</a>
</div>

<?php if (!empty($templates)): ?>
<div class="row g-4">
    <?php foreach ($templates as $t): ?>
    <div class="col-lg-4 col-md-6">
        <div class="admin-card h-100" style="cursor:pointer;" onclick="previewTemplate(<?= htmlspecialchars(json_encode($t)) ?>)">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:44px;height:44px;border-radius:12px;background:<?= $t->type === 'email' ? 'linear-gradient(135deg,#4a6cf7,#6a8cff)' : ($t->type === 'sms' ? 'linear-gradient(135deg,#28a745,#5cb85c)' : 'linear-gradient(135deg,#17a2b8,#45b8d4)') ?>;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
                        <i class="bi bi-<?= $t->type === 'email' ? 'envelope' : ($t->type === 'sms' ? 'phone' : 'bell') ?>"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size:0.95rem;"><?= htmlspecialchars($t->name) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($t->slug) ?></small>
                    </div>
                </div>
                <span class="badge bg-<?= $t->is_active ? 'success' : 'secondary' ?> rounded-pill">
                    <i class="bi bi-<?= $t->is_active ? 'check-circle' : 'dash-circle' ?>"></i>
                    <?= $t->is_active ? 'Active' : 'Inactive' ?>
                </span>
            </div>
            
            <?php if ($t->subject): ?>
            <div class="mb-2">
                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Subject</small>
                <span class="fw-semibold" style="font-size:0.88rem;"><?= htmlspecialchars(mb_substr($t->subject, 0, 60)) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="mb-3">
                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Preview</small>
                <p class="text-muted mb-0" style="font-size:0.85rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= htmlspecialchars(mb_substr($t->body, 0, 120)) ?></p>
            </div>
            
            <?php
            $vars = json_decode($t->variables, true);
            if (!empty($vars)):
            ?>
            <div>
                <small class="text-muted d-block mb-1" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Variables</small>
                <div class="d-flex flex-wrap gap-1">
                    <?php foreach (array_slice($vars, 0, 5) as $var): ?>
                    <span class="badge bg-light text-dark border" style="font-size:0.7rem;font-family:monospace;">{{<?= htmlspecialchars($var) ?>}}</span>
                    <?php endforeach; ?>
                    <?php if (count($vars) > 5): ?>
                    <span class="badge bg-light text-muted" style="font-size:0.7rem;">+<?= count($vars) - 5 ?> more</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="badge bg-<?= $t->type === 'email' ? 'primary-subtle text-primary' : ($t->type === 'sms' ? 'success-subtle text-success' : 'info-subtle text-info') ?> rounded-pill px-2 py-1">
                    <?= ucfirst($t->type) ?>
                </span>
                <small class="text-muted"><?= $t->created_at ? time_ago($t->created_at) : '' ?></small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="text-center py-5">
    <div style="width:80px;height:80px;border-radius:50%;background:#f0f2f5;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="bi bi-file-earmark-text fs-2 text-muted"></i>
    </div>
    <h6 class="fw-semibold text-muted">No notification templates found</h6>
    <small class="text-muted">Templates can be defined in the database for automated email/SMS notifications.</small>
</div>
<?php endif; ?>

<!-- Template Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div id="previewIcon" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;"></div>
                    <div>
                        <h6 class="modal-title fw-bold" id="previewName"></h6>
                        <small class="text-muted" id="previewSlug"></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-auto">
                        <span class="badge rounded-pill px-3 py-2" id="previewTypeBadge"></span>
                    </div>
                    <div class="col-auto">
                        <span class="badge rounded-pill px-3 py-2" id="previewStatusBadge"></span>
                    </div>
                </div>
                
                <div id="previewSubjectSection" class="mb-3">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Subject</label>
                    <div class="p-3 rounded-3" style="background:#f8f9fa;font-size:0.95rem;" id="previewSubject"></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Body</label>
                    <div class="p-3 rounded-3" style="background:#f8f9fa;white-space:pre-wrap;line-height:1.7;font-size:0.9rem;max-height:300px;overflow-y:auto;" id="previewBody"></div>
                </div>
                
                <div id="previewVarsSection">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Available Variables</label>
                    <div class="d-flex flex-wrap gap-2" id="previewVars"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function previewTemplate(template) {
    const typeColors = { email: '#4a6cf7', sms: '#28a745', push: '#ffc107' };
    const color = typeColors[template.type] || '#6c757d';
    
    document.getElementById('previewIcon').style.background = `linear-gradient(135deg, ${color}, ${color}cc)`;
    document.getElementById('previewIcon').innerHTML = `<i class="bi bi-${template.type === 'email' ? 'envelope' : (template.type === 'sms' ? 'phone' : 'bell')}"></i>`;
    document.getElementById('previewName').textContent = template.name;
    document.getElementById('previewSlug').textContent = template.slug;
    
    document.getElementById('previewTypeBadge').className = `badge rounded-pill px-3 py-2 bg-${template.type === 'email' ? 'primary' : (template.type === 'sms' ? 'success' : 'info')}`;
    document.getElementById('previewTypeBadge').textContent = template.type.charAt(0).toUpperCase() + template.type.slice(1);
    
    document.getElementById('previewStatusBadge').className = `badge rounded-pill px-3 py-2 bg-${template.is_active ? 'success' : 'secondary'}`;
    document.getElementById('previewStatusBadge').textContent = template.is_active ? 'Active' : 'Inactive';
    
    const subjectSection = document.getElementById('previewSubjectSection');
    if (template.subject) {
        subjectSection.style.display = 'block';
        document.getElementById('previewSubject').textContent = template.subject;
    } else {
        subjectSection.style.display = 'none';
    }
    
    document.getElementById('previewBody').textContent = template.body;
    
    const vars = JSON.parse(template.variables || '[]');
    const varsSection = document.getElementById('previewVarsSection');
    const varsDiv = document.getElementById('previewVars');
    if (vars.length > 0) {
        varsSection.style.display = 'block';
        varsDiv.innerHTML = vars.map(v => `<span class="badge bg-light text-dark border px-3 py-2" style="font-family:monospace;">{{${v}}}</span>`).join('');
    } else {
        varsSection.style.display = 'none';
    }
    
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}
</script>
