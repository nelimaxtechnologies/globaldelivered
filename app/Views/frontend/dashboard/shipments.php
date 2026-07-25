<div class="container py-4">
    <h4 class="fw-bold mb-4">My Shipments</h4>
    
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search by tracking number or recipient..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button>
        </div>
        <div class="col-md-2">
            <a href="<?= BASE_URL ?>/dashboard/shipments" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (!empty($shipments)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr>
                        <th>Tracking #</th><th>Recipient</th><th>Service</th><th>Status</th><th>Date</th><th>Actions</th>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($shipments as $s): ?>
                        <tr>
                            <td><a href="<?= BASE_URL ?>/dashboard/shipments/<?= $s->id ?>" class="fw-semibold"><?= htmlspecialchars($s->tracking_number) ?></a></td>
                            <td><?= htmlspecialchars($s->recipient_name) ?><br><small class="text-muted"><?= htmlspecialchars($s->recipient_city) ?>, <?= htmlspecialchars($s->recipient_country) ?></small></td>
                            <td><small class="text-capitalize"><?= str_replace('_', ' ', htmlspecialchars($s->service_type)) ?></small></td>
                            <td><span class="badge" style="background:<?= $s->status_color ?? '#6c757d' ?>"><?= htmlspecialchars($s->status_name ?? $s->status) ?></span></td>
                            <td><small><?= format_date($s->created_at, 'M d, Y') ?></small></td>
                            <td>
                                <a href="<?= BASE_URL ?>/dashboard/shipments/<?= $s->id ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/dashboard/shipments/<?= $s->id ?>/label" class="btn btn-sm btn-outline-info" target="_blank"><i class="bi bi-upc-scan"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p>No shipments found.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($pagination->totalPages > 1): ?>
    <nav class="mt-3"><ul class="pagination justify-content-center">
        <li class="page-item <?= !$pagination->hasPrevious ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $pagination->page - 1 ?>&search=<?= urlencode($_GET['search'] ?? '') ?>">Previous</a>
        </li>
        <li class="page-item active"><span class="page-link">Page <?= $pagination->page ?> of <?= $pagination->totalPages ?></span></li>
        <li class="page-item <?= !$pagination->hasMore ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $pagination->page + 1 ?>&search=<?= urlencode($_GET['search'] ?? '') ?>">Next</a>
        </li>
    </ul></nav>
    <?php endif; ?>
</div>
