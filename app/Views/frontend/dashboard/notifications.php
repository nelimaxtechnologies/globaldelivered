<div class="container py-4">
    <h4 class="fw-bold mb-4">Notifications</h4>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $n): ?>
                <div class="p-3 border-bottom <?= !$n->is_read ? 'bg-light' : '' ?>">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="fw-semibold mb-1 small"><?= htmlspecialchars($n->subject) ?></p>
                            <p class="mb-0 small text-muted"><?= htmlspecialchars($n->message) ?></p>
                        </div>
                        <small class="text-muted flex-shrink-0 ms-2"><?= time_ago($n->created_at) ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bell fs-1 d-block mb-2"></i>
                <p>No notifications yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
