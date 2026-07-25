<?php
/**
 * Evolution API Webhook Handler
 * Receives events from Evolution API and processes them.
 */

require_once __DIR__ . '/../../bootstrap.php';

use App\Models\WhatsAppModel;
use App\Services\EvolutionAPI;

header('Content-Type: application/json');

try {
    $model = new WhatsAppModel();
    $settings = $model->getSettings();

    // Validate webhook secret if configured
    if (!empty($settings->webhook_secret)) {
        $secret = $_SERVER['HTTP_X_WEBHOOK_SECRET'] ?? $_GET['secret'] ?? '';
        if ($secret !== $settings->webhook_secret) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid webhook secret']);
            exit;
        }
    }

    $payload = json_decode(file_get_contents('php://input'), true);

    if (!$payload) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid payload']);
        exit;
    }

    $event = $payload['event'] ?? '';
    $instance = $payload['instance'] ?? '';
    $data = $payload['data'] ?? [];

    switch ($event) {
        case 'messages.upsert':
            handleIncomingMessage($model, $instance, $data);
            break;

        case 'messages.update':
            handleMessagesUpdate($model, $instance, $data);
            break;

        case 'connection.update':
            handleConnectionUpdate($model, $instance, $data);
            break;

        case 'qrcode.updated':
            handleQRUpdate($model, $instance, $data);
            break;

        default:
            // Log unknown events
            error_log("WhatsApp webhook: Unknown event {$event} from instance {$instance}");
    }

    echo json_encode(['success' => true, 'event' => $event]);

} catch (\Exception $e) {
    error_log("WhatsApp webhook error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal error']);
}

// ==================== Event Handlers ====================

function handleIncomingMessage(WhatsAppModel $model, string $instance, array $data): void
{
    $message = $data['data'] ?? $data;
    $key = $message['key'] ?? [];
    $phone = str_replace('@s.whatsapp.net', '', $key['remoteJid'] ?? '');
    $fromMe = $key['fromMe'] ?? false;
    $messageId = $key['id'] ?? '';
    $pushName = $message['pushName'] ?? '';

    if (!$phone || $phone === 'status@broadcast') return;

    // Get message content
    $messageType = 'text';
    $messageText = '';
    $mediaUrl = null;
    $mediaType = null;

    if (isset($message['message'])) {
        $msg = $message['message'];
        if (isset($msg['conversation'])) {
            $messageText = $msg['conversation'];
        } elseif (isset($msg['extendedTextMessage']['text'])) {
            $messageText = $msg['extendedTextMessage']['text'];
        } elseif (isset($msg['imageMessage'])) {
            $messageType = 'image';
            $messageText = $msg['imageMessage']['caption'] ?? '';
            $mediaUrl = $msg['imageMessage']['url'] ?? null;
            $mediaType = 'image';
        } elseif (isset($msg['videoMessage'])) {
            $messageType = 'video';
            $messageText = $msg['videoMessage']['caption'] ?? '';
            $mediaUrl = $msg['videoMessage']['url'] ?? null;
            $mediaType = 'video';
        } elseif (isset($msg['documentMessage'])) {
            $messageType = 'document';
            $messageText = $msg['documentMessage']['caption'] ?? '';
            $mediaUrl = $msg['documentMessage']['url'] ?? null;
            $mediaType = $msg['documentMessage']['mimetype'] ?? 'document';
        } elseif (isset($msg['audioMessage'])) {
            $messageType = 'audio';
            $mediaUrl = $msg['audioMessage']['url'] ?? null;
            $mediaType = 'audio';
        } elseif (isset($msg['locationMessage'])) {
            $messageType = 'location';
            $messageText = $msg['locationMessage']['name'] ?? '';
        } elseif (isset($msg['contactMessage'])) {
            $messageType = 'contact';
            $messageText = $msg['contactMessage']['displayName'] ?? '';
        }
    }

    // Save contact
    $contactName = $pushName ?: $phone;
    $model->saveContact([
        'phone' => $phone,
        'name' => $contactName,
        'instance' => $instance,
        'last_seen' => date('Y-m-d H:i:s'),
    ]);

    // Save message
    $model->saveMessage([
        'instance' => $instance,
        'phone' => $phone,
        'contact_name' => $contactName,
        'direction' => $fromMe ? 'outbound' : 'inbound',
        'message_type' => $messageType,
        'message' => $messageText,
        'media_url' => $mediaUrl,
        'media_type' => $mediaType,
        'status' => 'delivered',
        'message_id' => $messageId,
        'from_me' => $fromMe ? 1 : 0,
        'read_status' => 0,
    ]);
}

function handleMessagesUpdate(WhatsAppModel $model, string $instance, array $data): void
{
    $key = $data['key'] ?? [];
    $messageId = $key['id'] ?? '';
    $status = $data['update'] ?? [];

    if (empty($messageId)) return;

    if (isset($status['status'])) {
        $newStatus = $status['status'];
        $dbStatus = match($newStatus) {
            'DELIVERY_ACK' => 'delivered',
            'READ' => 'read',
            'PLAYED' => 'played',
            'SENT' => 'sent',
            'SERVER_ACK' => 'sent',
            default => 'sent',
        };

        $model->updateMessageStatus($messageId, $dbStatus);

        if ($dbStatus === 'read') {
            $model->markMessageRead($messageId);
        }
    }
}

function handleConnectionUpdate(WhatsAppModel $model, string $instance, array $data): void
{
    $state = $data['state'] ?? $data['connection'] ?? 'disconnected';

    $statusMap = [
        'open' => 'open',
        'close' => 'disconnected',
        'connecting' => 'connecting',
    ];

    $dbStatus = $statusMap[$state] ?? 'disconnected';
    $model->updateInstanceStatus($instance, $dbStatus);

    // If connected, clear QR
    if ($dbStatus === 'open') {
        $model->updateInstanceQR($instance, null);

        // Update phone and profile info
        if (isset($data['user']) || isset($data['info'])) {
            $user = $data['user'] ?? $data['info'] ?? [];
            $model->saveInstance([
                'instance_name' => $instance,
                'status' => 'open',
                'phone' => $user['id'] ?? $user['phone'] ?? '',
                'profile_name' => $user['name'] ?? $user['pushName'] ?? '',
            ]);
        }
    }
}

function handleQRUpdate(WhatsAppModel $model, string $instance, array $data): void
{
    $qrcode = $data['base64'] ?? $data['data'] ?? null;

    if ($qrcode) {
        $model->updateInstanceQR($instance, $qrcode);
    }
}
