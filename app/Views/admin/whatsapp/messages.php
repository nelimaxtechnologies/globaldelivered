<!-- WhatsApp Chat Interface -->
<div class="admin-card p-0 overflow-hidden" style="height: calc(100vh - 140px);">
    <div class="row g-0 h-100">
        <!-- Left: Conversation List -->
        <div class="col-md-4 border-end" style="overflow-y:auto;max-height:calc(100vh - 140px);">
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-chat-dots me-2" style="color:#25D366;"></i>Chats</h6>
                    <span class="badge bg-success"><?= count($conversations) ?></span>
                </div>
                <form method="GET" class="mt-2">
                    <input type="hidden" name="instance" value="<?= htmlspecialchars($selectedInstance) ?>">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="phone" class="form-control" placeholder="Search phone..." value="<?= htmlspecialchars($selectedPhone) ?>">
                    </div>
                </form>
            </div>
            <div class="list-group list-group-flush">
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $conv): ?>
                    <a href="<?= BASE_URL ?>/admin/whatsapp/chats?phone=<?= urlencode($conv->phone) ?>&instance=<?= urlencode($conv->instance) ?>" class="list-group-item list-group-item-action <?= $selectedPhone === $conv->phone ? 'active' : '' ?>" style="border-left: 3px solid <?= $selectedPhone === $conv->phone ? '#25D366' : 'transparent' ?>;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;font-weight:600;font-size:0.75rem;">
                                <?= strtoupper(substr($conv->contact_name ?: $conv->phone, 0, 2)) ?>
                            </div>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold" style="font-size:0.85rem;"><?= htmlspecialchars($conv->contact_name ?: $conv->phone) ?></span>
                                    <small class="text-muted" style="font-size:0.7rem;"><?= time_ago($conv->last_message_at) ?></small>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted text-truncate" style="max-width:200px;"><?= htmlspecialchars(mb_strimwidth($conv->last_message, 0, 40, '...')) ?></small>
                                    <?php if ($conv->unread_count > 0): ?>
                                    <span class="badge bg-success rounded-pill" style="font-size:0.6rem;"><?= $conv->unread_count ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-dots fs-1 d-block mb-2" style="opacity:0.3;"></i>
                        <small>No conversations yet</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Chat -->
        <div class="col-md-8 d-flex flex-column" style="max-height:calc(100vh - 140px);">
            <?php if ($selectedPhone): ?>
            <!-- Chat Header -->
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background:#f8f9fa;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;font-weight:600;font-size:0.7rem;">
                        <?= strtoupper(substr($selectedPhone, 0, 2)) ?>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:0.9rem;"><?= htmlspecialchars($selectedPhone) ?></div>
                        <small class="text-muted">Instance: <?= htmlspecialchars($selectedInstance) ?></small>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-grow-1 overflow-auto p-3" id="messagesArea" style="background:#e5ddd5;">
                <?php if (!empty($chatMessages)): ?>
                    <?php foreach ($chatMessages as $msg): ?>
                    <div class="mb-2 <?= $msg->from_me ? 'text-end' : 'text-start' ?>">
                        <div class="d-inline-block px-3 py-2 rounded-3 <?= $msg->from_me ? 'bg-primary text-white' : 'bg-white' ?>" style="max-width:70%;box-shadow:0 1px 2px rgba(0,0,0,0.1);">
                            <?php if ($msg->message_type !== 'text'): ?>
                            <div class="mb-1"><small><i class="bi bi-paperclip me-1"></i><?= ucfirst($msg->message_type) ?></small></div>
                            <?php endif; ?>
                            <div style="font-size:0.88rem;"><?= nl2br(htmlspecialchars($msg->message)) ?></div>
                            <div class="text-end mt-1" style="font-size:0.65rem;opacity:0.7;">
                                <?= format_date($msg->created_at, 'H:i') ?>
                                <?php if ($msg->from_me): ?>
                                    <?php if ($msg->status === 'read'): ?><i class="bi bi-check-all"></i>
                                    <?php elseif ($msg->status === 'delivered'): ?><i class="bi bi-check-all"></i>
                                    <?php else: ?><i class="bi bi-check"></i><?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-dots fs-1 d-block mb-2" style="opacity:0.3;"></i>
                        <p>No messages with this contact yet</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Message Input -->
            <div class="p-3 border-top">
                <form method="POST" action="<?= BASE_URL ?>/admin/whatsapp/chats/send" id="chatForm">
                    <input type="hidden" name="phone" value="<?= htmlspecialchars($selectedPhone) ?>">
                    <input type="hidden" name="instance" value="<?= htmlspecialchars($selectedInstance) ?>">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#mediaModal" title="Attach file"><i class="bi bi-paperclip"></i></button>
                        <input type="text" name="message" class="form-control" placeholder="Type a message..." id="messageInput" required autocomplete="off">
                        <button type="submit" class="btn" style="background:#25D366;color:#fff;"><i class="bi bi-send-fill"></i></button>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <!-- No Chat Selected -->
            <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                <div class="text-center text-muted">
                    <i class="bi bi-whatsapp" style="font-size:5rem;opacity:0.15;color:#25D366;"></i>
                    <h5 class="mt-3">WhatsApp Web</h5>
                    <p>Select a conversation or search for a phone number to start chatting.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Media Upload Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>/admin/whatsapp/chats/send-media" enctype="multipart/form-data">
                <input type="hidden" name="phone" value="<?= htmlspecialchars($selectedPhone) ?>">
                <input type="hidden" name="instance" value="<?= htmlspecialchars($selectedInstance) ?>">
                <div class="modal-header">
                    <h6 class="fw-bold"><i class="bi bi-paperclip me-2"></i>Send Media</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Type</label>
                        <select name="message_type" class="form-select">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                            <option value="document">Document</option>
                            <option value="audio">Audio</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File</label>
                        <input type="file" name="media" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Caption</label>
                        <input type="text" name="caption" class="form-control" placeholder="Optional caption">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-send me-1"></i> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-scroll to bottom
var area = document.getElementById('messagesArea');
if (area) area.scrollTop = area.scrollHeight;

// Auto-scroll on Enter
document.getElementById('messageInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        // Form submits naturally
    }
});
</script>
