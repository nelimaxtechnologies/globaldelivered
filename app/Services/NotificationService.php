<?php
/**
 * Global Delivered Logistics - Notification Service
 * 
 * Manages all notification channels (email, SMS, push, system)
 * with template support and retry logic.
 */

namespace App\Services;

class NotificationService
{
    private static array $channels = ['email', 'sms', 'push', 'system'];
    private static array $notifications = [];

    /**
     * Send shipment notification
     */
    public static function shipmentCreated(object $shipment, array $channels = ['email', 'system']): void
    {
        $data = [
            'type' => 'shipment_created',
            'subject' => "Shipment {$shipment->tracking_number} Created",
            'message' => "Your shipment {$shipment->tracking_number} has been created successfully.",
            'shipment_id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
        ];
        
        self::dispatch($shipment->sender_email, $shipment->sender_name, $data, $channels);
    }

    /**
     * Send status update notification
     */
    public static function statusUpdated(object $shipment, string $statusName, string $description = ''): void
    {
        $data = [
            'type' => 'status_updated',
            'subject' => "Shipment {$shipment->tracking_number} - {$statusName}",
            'message' => "Your shipment {$shipment->tracking_number} status has been updated to: {$statusName}. {$description}",
            'shipment_id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'status' => $statusName,
        ];
        
        self::dispatch($shipment->sender_email, $shipment->sender_name, $data, ['email', 'system']);
    }

    /**
     * Send delivery notification
     */
    public static function delivered(object $shipment, string $deliveredTo = ''): void
    {
        $data = [
            'type' => 'delivered',
            'subject' => "Shipment {$shipment->tracking_number} Delivered!",
            'message' => "Your shipment {$shipment->tracking_number} has been delivered successfully." . 
                         ($deliveredTo ? " Delivered to: {$deliveredTo}" : ''),
            'shipment_id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'delivered_to' => $deliveredTo,
        ];
        
        self::dispatch($shipment->sender_email, $shipment->sender_name, $data, ['email', 'system']);
    }

    /**
     * Send delay notification
     */
    public static function delayed(object $shipment, string $reason = ''): void
    {
        $data = [
            'type' => 'delayed',
            'subject' => "Shipment {$shipment->tracking_number} - Delay",
            'message' => "Your shipment {$shipment->tracking_number} has been delayed. " . ($reason ? "Reason: {$reason}" : "We apologize for the inconvenience."),
            'shipment_id' => $shipment->id,
            'tracking_number' => $shipment->tracking_number,
            'reason' => $reason,
        ];
        
        self::dispatch($shipment->sender_email, $shipment->sender_name, $data, ['email', 'system']);
    }

    /**
     * Dispatch notification to specified channels
     */
    private static function dispatch(string $recipient, string $name, array $data, array $channels): void
    {
        $db = \App\Core\Database::getInstance();
        
        foreach ($channels as $channel) {
            if (!in_array($channel, self::$channels)) continue;
            
            switch ($channel) {
                case 'email':
                    self::sendEmail($db, $recipient, $name, $data);
                    break;
                    
                case 'system':
                    self::sendSystem($db, $recipient, $data);
                    break;
                    
                case 'sms':
                    // Future: Integrate Twilio/Africa's Talking
                    self::queueSms($db, $recipient, $data);
                    break;
            }
        }
    }

    /**
     * Send email notification
     */
    private static function sendEmail($db, string $email, string $name, array $data): void
    {
        $db->query(
            "INSERT INTO email_queue (to_email, to_name, subject, body, template, template_data, status, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, 'queued', NOW())",
            [
                $email,
                $name,
                $data['subject'],
                $data['message'],
                $data['type'] ?? 'general',
                json_encode($data),
            ]
        );
    }

    /**
     * Send system notification
     */
    private static function sendSystem($db, string $email, array $data): void
    {
        // Find user by email
        $user = $db->fetch("SELECT id FROM users WHERE email = ?", [$email]);
        
        if ($user) {
            $db->query(
                "INSERT INTO notifications (user_id, type, channel, subject, message, data, is_read, is_sent, created_at) 
                 VALUES (?, 'system', 'system', ?, ?, ?, 0, 1, NOW())",
                [$user->id, $data['subject'], $data['message'], json_encode($data)]
            );
        }
    }

    /**
     * Queue SMS notification
     */
    private static function queueSms($db, string $phone, array $data): void
    {
        $db->query(
            "INSERT INTO sms_queue (to_phone, message, provider, status, created_at) 
             VALUES (?, ?, 'twilio', 'queued', NOW())",
            [$phone, $data['message']]
        );
    }

    /**
     * Get unread notifications for user
     */
    public static function getUnread(int $userId, int $limit = 10): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 
             ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(int $notificationId): void
    {
        $db = \App\Core\Database::getInstance();
        $db->query(
            "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE id = ?",
            [$notificationId]
        );
    }

    /**
     * Mark all notifications as read for user
     */
    public static function markAllAsRead(int $userId): void
    {
        $db = \App\Core\Database::getInstance();
        $db->query(
            "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE user_id = ? AND is_read = 0",
            [$userId]
        );
    }
}
