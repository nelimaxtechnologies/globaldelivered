<div class="container py-4">
    <h4 class="fw-bold mb-4">My Invoices</h4>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (!empty($invoices)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr>
                        <th>Invoice #</th><th>Tracking #</th><th>Amount</th><th>Status</th><th>Date</th><th></th>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($invoices as $inv): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($inv->invoice_number) ?></td>
                            <td><small><?= htmlspecialchars($inv->tracking_number ?? '-') ?></small></td>
                            <td><?= format_currency($inv->total) ?></td>
                            <td><span class="badge bg-<?= $inv->status === 'paid' ? 'success' : ($inv->status === 'overdue' ? 'danger' : 'warning') ?>"><?= htmlspecialchars($inv->status) ?></span></td>
                            <td><small><?= format_date($inv->created_at) ?></small></td>
                            <td><a href="<?= BASE_URL ?>/dashboard/invoices/<?= $inv->id ?>/download" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                <p>No invoices yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
