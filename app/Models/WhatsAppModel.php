<?php
/**
 * WhatsApp Model - Database operations for WhatsApp module
 */

namespace App\Models;

use App\Core\Database;

class WhatsAppModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ==================== Settings ====================

    public function getSettings(): ?object
    {
        return $this->db->fetch("SELECT * FROM whatsapp_settings LIMIT 1");
    }

    public function updateSettings(array $data): bool
    {
        $this->db->query(
            "UPDATE whatsapp_settings SET api_url=?, api_key=?, default_instance=?, webhook_url=?, webhook_secret=?, auto_retry=?, timeout=?, enable_logs=?, enable_notifications=?, updated_at=NOW() WHERE id=1",
            [
                $data['api_url'] ?? '',
                $data['api_key'] ?? '',
                $data['default_instance'] ?? '',
                $data['webhook_url'] ?? '',
                $data['webhook_secret'] ?? '',
                !empty($data['auto_retry']) ? 1 : 0,
                (int) ($data['timeout'] ?? 30),
                !empty($data['enable_logs']) ? 1 : 0,
                !empty($data['enable_notifications']) ? 1 : 0,
            ]
        );
        return true;
    }

    // ==================== Instances ====================

    public function getInstances(): array
    {
        return $this->db->fetchAll("SELECT * FROM whatsapp_instances ORDER BY created_at DESC");
    }

    public function getInstance(string $name): ?object
    {
        return $this->db->fetch("SELECT * FROM whatsapp_instances WHERE instance_name = ?", [$name]);
    }

    public function saveInstance(array $data): void
    {
        $exists = $this->db->fetchColumn("SELECT COUNT(*) FROM whatsapp_instances WHERE instance_name = ?", [$data['instance_name']]);
        if ($exists) {
            $this->db->query(
                "UPDATE whatsapp_instances SET status=?, phone=?, profile_name=?, profile_picture=?, qrcode=?, battery=?, updated_at=NOW() WHERE instance_name=?",
                [$data['status'] ?? 'disconnected', $data['phone'] ?? '', $data['profile_name'] ?? '', $data['profile_picture'] ?? '', $data['qrcode'] ?? null, $data['battery'] ?? 0, $data['instance_name']]
            );
        } else {
            $this->db->query(
                "INSERT INTO whatsapp_instances (instance_name, display_name, status, phone, profile_name, profile_picture, qrcode, battery, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [$data['instance_name'], $data['display_name'] ?? $data['instance_name'], $data['status'] ?? 'disconnected', $data['phone'] ?? '', $data['profile_name'] ?? '', $data['profile_picture'] ?? '', $data['qrcode'] ?? null, $data['battery'] ?? 0]
            );
        }
    }

    public function updateInstanceStatus(string $name, string $status): void
    {
        $this->db->query("UPDATE whatsapp_instances SET status=?, last_seen=NOW(), updated_at=NOW() WHERE instance_name=?", [$status, $name]);
    }

    public function updateInstanceQR(string $name, ?string $qrcode): void
    {
        $this->db->query("UPDATE whatsapp_instances SET qrcode=?, updated_at=NOW() WHERE instance_name=?", [$qrcode, $name]);
    }

    public function deleteInstance(string $name): void
    {
        $this->db->query("DELETE FROM whatsapp_instances WHERE instance_name=?", [$name]);
    }

    public function getInstanceCount(): int
    {
        return (int) $this->db->fetchColumn("SELECT COUNT(*) FROM whatsapp_instances");
    }

    public function getConnectedCount(): int
    {
        return (int) $this->db->fetchColumn("SELECT COUNT(*) FROM whatsapp_instances WHERE status = 'open'");
    }

    // ==================== Contacts ====================

    public function getContacts(string $search = '', string $instance = '', int $page = 1, int $perPage = 25)
    {
        $where = "WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (name LIKE ? OR phone LIKE ? OR email LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($instance)) {
            $where .= " AND instance = ?";
            $params[] = $instance;
        }

        return $this->db->paginate(
            "SELECT COUNT(*) FROM whatsapp_contacts {$where}",
            "SELECT * FROM whatsapp_contacts {$where} ORDER BY created_at DESC",
            $params, $page, $perPage
        );
    }

    public function getContactByPhone(string $phone, string $instance = ''): ?object
    {
        if ($instance) {
            return $this->db->fetch("SELECT * FROM whatsapp_contacts WHERE phone = ? AND instance = ?", [$phone, $instance]);
        }
        return $this->db->fetch("SELECT * FROM whatsapp_contacts WHERE phone = ?", [$phone]);
    }

    public function saveContact(array $data): int
    {
        $exists = $this->db->fetchColumn("SELECT id FROM whatsapp_contacts WHERE phone = ? AND instance = ?", [$data['phone'], $data['instance'] ?? '']);
        if ($exists) {
            $this->db->query(
                "UPDATE whatsapp_contacts SET name=?, email=?, country=?, notes=?, tags=?, last_seen=IFNULL(last_seen, ?), updated_at=NOW() WHERE id=?",
                [$data['name'] ?? '', $data['email'] ?? '', $data['country'] ?? '', $data['notes'] ?? '', $data['tags'] ?? '', $data['last_seen'] ?? null, $exists]
            );
            return (int) $exists;
        } else {
            $this->db->query(
                "INSERT INTO whatsapp_contacts (instance, name, phone, country, email, notes, tags, last_seen, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [$data['instance'] ?? '', $data['name'] ?? '', $data['phone'], $data['country'] ?? '', $data['email'] ?? '', $data['notes'] ?? '', $data['tags'] ?? '', $data['last_seen'] ?? null]
            );
            return (int) $this->db->lastInsertId();
        }
    }

    public function importContacts(array $contacts): array
    {
        $imported = 0;
        $skipped = 0;
        foreach ($contacts as $c) {
            if (empty($c['phone'])) { $skipped++; continue; }
            $this->saveContact($c);
            $imported++;
        }
        return ['imported' => $imported, 'skipped' => $skipped];
    }

    public function deleteContacts(array $ids): void
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $this->db->query("DELETE FROM whatsapp_contacts WHERE id IN ({$placeholders})", $ids);
    }

    public function getContactCount(): int
    {
        return (int) $this->db->fetchColumn("SELECT COUNT(*) FROM whatsapp_contacts");
    }

    // ==================== Messages ====================

    public function saveMessage(array $data): int
    {
        $this->db->query(
            "INSERT INTO whatsapp_messages (instance, phone, contact_name, direction, message_type, message, media_url, media_type, filename, status, message_id, from_me, read_status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $data['instance'] ?? '',
                $data['phone'] ?? '',
                $data['contact_name'] ?? '',
                $data['direction'] ?? 'outbound',
                $data['message_type'] ?? 'text',
                $data['message'] ?? '',
                $data['media_url'] ?? null,
                $data['media_type'] ?? null,
                $data['filename'] ?? null,
                $data['status'] ?? 'sent',
                $data['message_id'] ?? null,
                $data['from_me'] ?? 0,
                $data['read_status'] ?? 0,
            ]
        );
        return (int) $this->db->lastInsertId();
    }

    public function getMessages(string $phone, string $instance = '', int $limit = 50): array
    {
        $where = "WHERE phone = ?";
        $params = [$phone];
        if ($instance) {
            $where .= " AND instance = ?";
            $params[] = $instance;
        }
        return $this->db->fetchAll("SELECT * FROM whatsapp_messages {$where} ORDER BY created_at DESC LIMIT ?", array_merge($params, [$limit]));
    }

    public function getConversations(string $instance = ''): array
    {
        $where = "WHERE 1=1";
        $params = [];
        if ($instance) {
            $where .= " AND m.instance = ?";
            $params[] = $instance;
        }

        return $this->db->fetchAll(
            "SELECT m.phone, m.contact_name, m.message as last_message, m.created_at as last_message_at, m.direction, m.status, m.instance,
                    (SELECT COUNT(*) FROM whatsapp_messages WHERE phone = m.phone AND instance = m.instance AND from_me = 0 AND read_status = 0) as unread_count
             FROM whatsapp_messages m
             INNER JOIN (
                 SELECT phone, instance, MAX(created_at) as max_date
                 FROM whatsapp_messages {$where}
                 GROUP BY phone, instance
             ) latest ON m.phone = latest.phone AND m.instance = latest.instance AND m.created_at = latest.max_date
             ORDER BY m.created_at DESC",
            $params
        );
    }

    public function getMessageCountToday(): int
    {
        return (int) $this->db->fetchColumn("SELECT COUNT(*) FROM whatsapp_messages WHERE DATE(created_at) = CURDATE()");
    }

    public function getMessageStats(): array
    {
        $row = $this->db->fetch(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN direction = 'outbound' THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN direction = 'inbound' THEN 1 ELSE 0 END) as received,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today
             FROM whatsapp_messages"
        );
        return $row ? (array) $row : ['total'=>0,'sent'=>0,'received'=>0,'pending'=>0,'failed'=>0,'today'=>0];
    }

    public function getDailyStats(int $days = 7): array
    {
        return $this->db->fetchAll(
            "SELECT DATE(created_at) as date,
                    SUM(CASE WHEN direction = 'outbound' THEN 1 ELSE 0 END) as sent,
                    SUM(CASE WHEN direction = 'inbound' THEN 1 ELSE 0 END) as received
             FROM whatsapp_messages
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             GROUP BY DATE(created_at) ORDER BY date ASC",
            [$days]
        );
    }

    public function updateMessageStatus(string $messageId, string $status): void
    {
        $this->db->query("UPDATE whatsapp_messages SET status = ? WHERE message_id = ?", [$status, $messageId]);
    }

    public function markMessageRead(string $messageId): void
    {
        $this->db->query("UPDATE whatsapp_messages SET read_status = 1 WHERE message_id = ?", [$messageId]);
    }

    // ==================== Templates ====================

    public function getTemplates(): array
    {
        return $this->db->fetchAll("SELECT * FROM whatsapp_templates ORDER BY created_at DESC");
    }

    public function getTemplate(int $id): ?object
    {
        return $this->db->fetch("SELECT * FROM whatsapp_templates WHERE id = ?", [$id]);
    }

    public function saveTemplate(array $data): int
    {
        if (!empty($data['id'])) {
            $this->db->query(
                "UPDATE whatsapp_templates SET title=?, category=?, message=?, variables=?, media_url=?, is_active=?, updated_at=NOW() WHERE id=?",
                [$data['title'], $data['category'] ?? 'general', $data['message'], $data['variables'] ?? '', $data['media_url'] ?? '', !empty($data['is_active']) ? 1 : 0, $data['id']]
            );
            return (int) $data['id'];
        } else {
            $this->db->query(
                "INSERT INTO whatsapp_templates (title, category, message, variables, media_url, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
                [$data['title'], $data['category'] ?? 'general', $data['message'], $data['variables'] ?? '', $data['media_url'] ?? '', !empty($data['is_active']) ? 1 : 0]
            );
            return (int) $this->db->lastInsertId();
        }
    }

    public function deleteTemplate(int $id): void
    {
        $this->db->query("DELETE FROM whatsapp_templates WHERE id = ?", [$id]);
    }

    public function incrementTemplateUsage(int $id): void
    {
        $this->db->query("UPDATE whatsapp_templates SET use_count = use_count + 1 WHERE id = ?", [$id]);
    }

    public function renderTemplate(string $message, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }
        return $message;
    }

    // ==================== Campaigns ====================

    public function getCampaigns(): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, t.title as template_title
             FROM whatsapp_campaigns c
             LEFT JOIN whatsapp_templates t ON c.template_id = t.id
             ORDER BY c.created_at DESC"
        );
    }

    public function getCampaign(int $id): ?object
    {
        return $this->db->fetch(
            "SELECT c.*, t.title as template_title, t.message as template_message
             FROM whatsapp_campaigns c
             LEFT JOIN whatsapp_templates t ON c.template_id = t.id
             WHERE c.id = ?", [$id]
        );
    }

    public function saveCampaign(array $data): int
    {
        $this->db->query(
            "INSERT INTO whatsapp_campaigns (name, template_id, instance, status, schedule_at, total_contacts, sent, delivered, failed, pending, created_at)
             VALUES (?, ?, ?, ?, ?, ?, 0, 0, 0, ?, NOW())",
            [
                $data['name'],
                $data['template_id'] ?? null,
                $data['instance'] ?? '',
                $data['status'] ?? 'draft',
                $data['schedule_at'] ?? null,
                $data['total_contacts'] ?? 0,
                $data['total_contacts'] ?? 0,
            ]
        );
        return (int) $this->db->lastInsertId();
    }

    public function updateCampaignStatus(int $id, string $status): void
    {
        $this->db->query("UPDATE whatsapp_campaigns SET status = ?, updated_at = NOW() WHERE id = ?", [$status, $id]);
    }

    public function updateCampaignStats(int $id): void
    {
        $this->db->query(
            "UPDATE whatsapp_campaigns SET
                sent = (SELECT COUNT(*) FROM whatsapp_campaign_contacts WHERE campaign_id = ? AND status IN ('sent', 'delivered')),
                delivered = (SELECT COUNT(*) FROM whatsapp_campaign_contacts WHERE campaign_id = ? AND status = 'delivered'),
                failed = (SELECT COUNT(*) FROM whatsapp_campaign_contacts WHERE campaign_id = ? AND status = 'failed'),
                pending = (SELECT COUNT(*) FROM whatsapp_campaign_contacts WHERE campaign_id = ? AND status = 'pending'),
                updated_at = NOW()
             WHERE id = ?",
            [$id, $id, $id, $id, $id]
        );
    }

    public function addCampaignContacts(int $campaignId, array $contactIds): void
    {
        foreach ($contactIds as $contactId) {
            $this->db->query(
                "INSERT IGNORE INTO whatsapp_campaign_contacts (campaign_id, contact_id, status, created_at) VALUES (?, ?, 'pending', NOW())",
                [$campaignId, $contactId]
            );
        }
    }

    public function getCampaignPendingContacts(int $campaignId): array
    {
        return $this->db->fetchAll(
            "SELECT cc.*, c.phone, c.name
             FROM whatsapp_campaign_contacts cc
             JOIN whatsapp_contacts c ON cc.contact_id = c.id
             WHERE cc.campaign_id = ? AND cc.status = 'pending'
             ORDER BY cc.id ASC LIMIT 50",
            [$campaignId]
        );
    }

    public function updateCampaignContactStatus(int $campaignId, int $contactId, string $status, string $messageId = '', string $error = ''): void
    {
        $this->db->query(
            "UPDATE whatsapp_campaign_contacts SET status = ?, message_id = ?, error_message = ?, sent_at = IF(? = 'sent', NOW(), sent_at) WHERE campaign_id = ? AND contact_id = ?",
            [$status, $messageId, $error, $status, $campaignId, $contactId]
        );
    }

    public function deleteCampaign(int $id): void
    {
        $this->db->query("DELETE FROM whatsapp_campaign_contacts WHERE campaign_id = ?", [$id]);
        $this->db->query("DELETE FROM whatsapp_campaigns WHERE id = ?", [$id]);
    }

    // ==================== Automations ====================

    public function getAutomations(): array
    {
        return $this->db->fetchAll(
            "SELECT a.*, t.title as template_title
             FROM whatsapp_automations a
             LEFT JOIN whatsapp_templates t ON a.template_id = t.id
             ORDER BY a.created_at DESC"
        );
    }

    public function getAutomation(int $id): ?object
    {
        return $this->db->fetch("SELECT * FROM whatsapp_automations WHERE id = ?", [$id]);
    }

    public function saveAutomation(array $data): int
    {
        if (!empty($data['id'])) {
            $this->db->query(
                "UPDATE whatsapp_automations SET name=?, trigger_event=?, template_id=?, instance=?, recipient_type=?, is_active=?, conditions=?, updated_at=NOW() WHERE id=?",
                [$data['name'], $data['trigger_event'], $data['template_id'] ?? null, $data['instance'] ?? '', $data['recipient_type'] ?? 'customer', !empty($data['is_active']) ? 1 : 0, $data['conditions'] ?? '', $data['id']]
            );
            return (int) $data['id'];
        } else {
            $this->db->query(
                "INSERT INTO whatsapp_automations (name, trigger_event, template_id, instance, recipient_type, is_active, conditions, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [$data['name'], $data['trigger_event'], $data['template_id'] ?? null, $data['instance'] ?? '', $data['recipient_type'] ?? 'customer', !empty($data['is_active']) ? 1 : 0, $data['conditions'] ?? '']
            );
            return (int) $this->db->lastInsertId();
        }
    }

    public function deleteAutomation(int $id): void
    {
        $this->db->query("DELETE FROM whatsapp_automations WHERE id = ?", [$id]);
    }

    public function getActiveAutomations(string $triggerEvent): array
    {
        return $this->db->fetchAll(
            "SELECT a.*, t.title as template_title, t.message as template_message
             FROM whatsapp_automations a
             LEFT JOIN whatsapp_templates t ON a.template_id = t.id
             WHERE a.trigger_event = ? AND a.is_active = 1",
            [$triggerEvent]
        );
    }

    // ==================== Logs ====================

    public function getLogs(string $instance = '', int $page = 1, int $perPage = 50)
    {
        $where = "WHERE 1=1";
        $params = [];
        if (!empty($instance)) {
            $where .= " AND instance = ?";
            $params[] = $instance;
        }
        return $this->db->paginate(
            "SELECT COUNT(*) FROM whatsapp_logs {$where}",
            "SELECT * FROM whatsapp_logs {$where} ORDER BY created_at DESC",
            $params, $page, $perPage
        );
    }

    public function clearLogs(): void
    {
        $this->db->query("TRUNCATE TABLE whatsapp_logs");
    }

    // ==================== Search ====================

    public function globalSearch(string $query): array
    {
        $q = "%{$query}%";
        $results = [];

        $results['contacts'] = $this->db->fetchAll(
            "SELECT id, name, phone, 'contact' as type FROM whatsapp_contacts WHERE name LIKE ? OR phone LIKE ? LIMIT 10",
            [$q, $q]
        );

        $results['messages'] = $this->db->fetchAll(
            "SELECT id, phone, contact_name, message, 'message' as type FROM whatsapp_messages WHERE message LIKE ? OR phone LIKE ? LIMIT 10",
            [$q, $q]
        );

        $results['templates'] = $this->db->fetchAll(
            "SELECT id, title, 'template' as type FROM whatsapp_templates WHERE title LIKE ? OR message LIKE ? LIMIT 10",
            [$q, $q]
        );

        return $results;
    }
}
