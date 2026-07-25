<?php
/**
 * Evolution API Service - Reusable cURL wrapper for Evolution API
 * All WhatsApp API communication goes through this service.
 */

namespace App\Services;

class EvolutionAPI
{
    private string $apiUrl;
    private string $apiKey;
    private int $timeout;
    private bool $enableLogs;

    public function __construct(?string $apiUrl = null, ?string $apiKey = null, int $timeout = 30, bool $enableLogs = true)
    {
        if ($apiUrl !== null) {
            $this->apiUrl = rtrim($apiUrl, '/');
        } else {
            $db = \App\Core\Database::getInstance();
            $settings = $db->fetch("SELECT * FROM whatsapp_settings LIMIT 1");
            $this->apiUrl = rtrim($settings->api_url ?? '', '/');
            $apiKey = $apiKey ?? $settings->api_key ?? '';
            $timeout = (int) ($settings->timeout ?? 30);
            $enableLogs = (bool) ($settings->enable_logs ?? 1);
        }

        $this->apiKey = $apiKey ?? '';
        $this->timeout = $timeout;
        $this->enableLogs = $enableLogs;
    }

    /**
     * Generic API request
     */
    private function request(string $method, string $endpoint, array $data = [], array $headers = []): array
    {
        $url = $this->apiUrl . $endpoint;

        $defaultHeaders = [
            'Content-Type: application/json',
            'apikey: ' . $this->apiKey,
        ];

        $allHeaders = array_merge($defaultHeaders, $headers);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $allHeaders,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $startTime = microtime(true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $duration = round((microtime(true) - $startTime) * 1000);
        curl_close($ch);

        $result = [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'http_code' => $httpCode,
            'data' => json_decode($response, true),
            'raw' => $response,
            'error' => $error ?: null,
            'duration_ms' => $duration,
        ];

        if ($this->enableLogs) {
            $this->log($method, $endpoint, $data, $result);
        }

        if ($error) {
            throw new \Exception("cURL Error: {$error} (Endpoint: {$method} {$endpoint})");
        }

        return $result;
    }

    /**
     * Log API request
     */
    private function log(string $method, string $endpoint, array $request, array $result): void
    {
        try {
            $db = \App\Core\Database::getInstance();
            $db->query(
                "INSERT INTO whatsapp_logs (instance, endpoint, method, request_headers, request_body, response_body, response_code, duration_ms, status, error_message, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    '',
                    $endpoint,
                    $method,
                    json_encode(['Content-Type' => 'application/json', 'apikey' => '***']),
                    json_encode($request),
                    $result['raw'],
                    $result['http_code'],
                    $result['duration_ms'],
                    $result['success'] ? 'success' : 'error',
                    $result['error'],
                ]
            );
        } catch (\Exception $e) {
            error_log("WhatsApp log failed: " . $e->getMessage());
        }
    }

    // ==================== Instance Management ====================

    /**
     * Create a new instance
     */
    public function createInstance(string $instanceName, string $number = '', string $qrcode = 'true', string $integration = 'WHATSAPP-BAILEYS'): array
    {
        return $this->request('POST', '/instance/create', [
            'instanceName' => $instanceName,
            'number' => $number,
            'qrcode' => $qrcode,
            'integration' => $integration,
            'reject_call' => false,
            'groups_ignore' => true,
            'always_online' => false,
            'webhook' => [
                'enabled' => true,
                'url' => $this->getWebhookUrl(),
                'by_events' => false,
                'base64' => false,
                'events' => [
                    'messages.upsert',
                    'messages.update',
                    'connection.update',
                    'qrcode.updated',
                ],
            ],
        ]);
    }

    /**
     * Get all instances
     */
    public function getInstances(): array
    {
        return $this->request('GET', '/instance/fetchInstances');
    }

    /**
     * Get instance info
     */
    public function getInstance(string $instanceName): array
    {
        return $this->request('GET', "/instance/connectionState/{$instanceName}");
    }

    /**
     * Connect /QR
     */
    public function connectInstance(string $instanceName): array
    {
        return $this->request('GET', "/instance/connect/{$instanceName}");
    }

    /**
     * Get QR Code
     */
    public function getQRCode(string $instanceName): array
    {
        return $this->request('GET', "/instance/connect/{$instanceName}");
    }

    /**
     * Logout instance
     */
    public function logout(string $instanceName): array
    {
        return $this->request('DELETE', "/instance/logout/{$instanceName}");
    }

    /**
     * Restart instance
     */
    public function restart(string $instanceName): array
    {
        return $this->request('PUT', "/instance/restart/{$instanceName}");
    }

    /**
     * Delete instance
     */
    public function deleteInstance(string $instanceName): array
    {
        return $this->request('DELETE', "/instance/delete/{$instanceName}");
    }

    /**
     * Set instance webhook
     */
    public function setWebhook(string $instanceName, string $url, array $events = []): array
    {
        return $this->request('POST', "/webhook/set/{$instanceName}", [
            'enabled' => true,
            'url' => $url,
            'by_events' => false,
            'base64' => false,
            'events' => $events ?: [
                'messages.upsert',
                'messages.update',
                'connection.update',
                'qrcode.updated',
            ],
        ]);
    }

    // ==================== Messaging ====================

    /**
     * Send text message
     */
    public function sendText(string $instance, string $number, string $message, array $options = []): array
    {
        return $this->request('POST', "/message/sendText/{$instance}", array_merge([
            'number' => $number,
            'text' => $message,
        ], $options));
    }

    /**
     * Send image
     */
    public function sendImage(string $instance, string $number, string $mediaUrl, string $caption = '', array $options = []): array
    {
        return $this->request('POST', "/message/sendImage/{$instance}", array_merge([
            'number' => $number,
            'mediatype' => 'image',
            'media' => $mediaUrl,
            'caption' => $caption,
        ], $options));
    }

    /**
     * Send video
     */
    public function sendVideo(string $instance, string $number, string $mediaUrl, string $caption = '', array $options = []): array
    {
        return $this->request('POST', "/message/sendVideo/{$instance}", array_merge([
            'number' => $number,
            'mediatype' => 'video',
            'media' => $mediaUrl,
            'caption' => $caption,
        ], $options));
    }

    /**
     * Send document
     */
    public function sendDocument(string $instance, string $number, string $mediaUrl, string $fileName = 'document', string $caption = '', array $options = []): array
    {
        return $this->request('POST', "/message/sendDocument/{$instance}", array_merge([
            'number' => $number,
            'mediatype' => 'document',
            'media' => $mediaUrl,
            'fileName' => $fileName,
            'caption' => $caption,
        ], $options));
    }

    /**
     * Send audio
     */
    public function sendAudio(string $instance, string $number, string $mediaUrl, array $options = []): array
    {
        return $this->request('POST', "/message/sendAudio/{$instance}", array_merge([
            'number' => $number,
            'mediatype' => 'audio',
            'audio' => $mediaUrl,
        ], $options));
    }

    /**
     * Send location
     */
    public function sendLocation(string $instance, string $number, float $lat, float $lng, string $name = '', string $address = '', array $options = []): array
    {
        return $this->request('POST', "/message/sendLocation/{$instance}", array_merge([
            'number' => $number,
            'name' => $name,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $lng,
        ], $options));
    }

    /**
     * Send contact card
     */
    public function sendContact(string $instance, string $number, array $contacts, array $options = []): array
    {
        return $this->request('POST', "/message/sendContact/{$instance}", array_merge([
            'number' => $number,
            'contacts' => $contacts,
        ], $options));
    }

    /**
     * Send buttons
     */
    public function sendButtons(string $instance, string $number, string $title, string $description, array $buttons, array $options = []): array
    {
        return $this->request('POST', "/message/sendButtons/{$instance}", array_merge([
            'number' => $number,
            'title' => $title,
            'description' => $description,
            'buttons' => $buttons,
        ], $options));
    }

    /**
     * Send list menu
     */
    public function sendList(string $instance, string $number, string $title, string $description, string $buttonText, array $sections, array $options = []): array
    {
        return $this->request('POST', "/message/sendList/{$instance}", array_merge([
            'number' => $number,
            'title' => $title,
            'description' => $description,
            'buttonText' => $buttonText,
            'sections' => $sections,
        ], $options));
    }

    /**
     * Send reaction
     */
    public function sendReaction(string $instance, string $number, string $messageId, string $emoji): array
    {
        return $this->request('POST', "/message/sendReaction/{$instance}", [
            'number' => $number,
            'reaction' => $emoji,
            'messageId' => $messageId,
        ]);
    }

    // ==================== Chat & Contacts ====================

    /**
     * Get chats
     */
    public function getChats(string $instance): array
    {
        return $this->request('GET', "/chat/findChats/{$instance}");
    }

    /**
     * Get contacts
     */
    public function getContacts(string $instance): array
    {
        return $this->request('GET', "/chat/findContacts/{$instance}");
    }

    /**
     * Get messages for a phone
     */
    public function getMessages(string $instance, string $phone, int $offset = 0, int $limit = 50): array
    {
        return $this->request('POST', "/chat/findMessages/{$instance}", [
            'where' => ['key' => ['remoteJid' => $phone . '@s.whatsapp.net']],
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Mark as read
     */
    public function markRead(string $instance, string $messageKey): array
    {
        return $this->request('POST', "/chat/markIsRead/{$instance}", [
            'read' => true,
            'key' => $messageKey,
        ]);
    }

    /**
     * Send typing indicator
     */
    public function typing(string $instance, string $number): array
    {
        return $this->request('POST', "/chat/sendPresence/{$instance}", [
            'number' => $number,
            'presence' => 'composing',
        ]);
    }

    // ==================== Media ====================

    /**
     * Download media
     */
    public function downloadMedia(string $instance, array $messageKey): array
    {
        return $this->request('POST', "/message/downloadMedia/{$instance}", [
            'message' => $messageKey,
        ]);
    }

    /**
     * Upload media
     */
    public function uploadMedia(string $instance, string $mediaUrl, string $mimeType = 'image/jpeg'): array
    {
        return $this->request('POST', "/message/uploadMedia/{$instance}", [
            'mimetype' => $mimeType,
            'media' => $mediaUrl,
        ]);
    }

    // ==================== Groups ====================

    /**
     * Create group
     */
    public function createGroup(string $instance, string $subject, array $participants): array
    {
        return $this->request('POST', "/group/create/{$instance}", [
            'subject' => $subject,
            'participants' => $participants,
        ]);
    }

    /**
     * Add participants
     */
    public function addParticipants(string $instance, string $groupId, array $participants): array
    {
        return $this->request('POST', "/group/participants/add/{$instance}", [
            'groupJid' => $groupId,
            'participants' => $participants,
        ]);
    }

    /**
     * Remove participants
     */
    public function removeParticipants(string $instance, string $groupId, array $participants): array
    {
        return $this->request('DELETE', "/group/participants/remove/{$instance}", [
            'groupJid' => $groupId,
            'participants' => $participants,
        ]);
    }

    // ==================== Helpers ====================

    /**
     * Get webhook URL from settings
     */
    private function getWebhookUrl(): string
    {
        try {
            $db = \App\Core\Database::getInstance();
            $settings = $db->fetch("SELECT webhook_url FROM whatsapp_settings LIMIT 1");
            return $settings->webhook_url ?? '';
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Format phone number (remove + and spaces)
     */
    public static function formatPhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Test connection
     */
    public function testConnection(): array
    {
        try {
            $result = $this->getInstances();
            return [
                'success' => true,
                'message' => 'Connection successful',
                'instances' => $result['data'] ?? [],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
