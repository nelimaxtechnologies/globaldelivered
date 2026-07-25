<?php
/**
 * WhatsApp Controller - Admin handlers for WhatsApp module
 */

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Services\EvolutionAPI;
use App\Models\WhatsAppModel;

class WhatsAppController extends Controller
{
    private WhatsAppModel $model;
    private EvolutionAPI $api;

    public function __construct()
    {
        parent::__construct();
        $this->model = new WhatsAppModel();
        $this->api = new EvolutionAPI();
    }

    // ==================== Dashboard ====================

    public function dashboard(): void
    {
        $stats = $this->model->getMessageStats();
        $dailyStats = $this->model->getDailyStats(14);
        $conversations = $this->model->getConversations();
        $instances = $this->model->getInstances();

        $this->adminView('whatsapp/dashboard', [
            'pageTitle' => 'WhatsApp Dashboard',
            'stats' => $stats,
            'dailyStats' => $dailyStats,
            'conversations' => array_slice($conversations, 0, 10),
            'instances' => $instances,
            'connectedCount' => $this->model->getConnectedCount(),
            'totalContacts' => $this->model->getContactCount(),
        ]);
    }

    // ==================== Instances ====================

    public function instances(): void
    {
        $instances = $this->model->getInstances();
        $this->adminView('whatsapp/instances', [
            'pageTitle' => 'WhatsApp Instances',
            'instances' => $instances,
        ]);
    }

    public function createInstance(): void
    {
        $data = $this->getPostData();
        $name = sanitize($data['instance_name'] ?? '');

        if (empty($name)) {
            flash('error', 'Instance name is required.');
            $this->back();
        }

        try {
            $result = $this->api->createInstance($name, $data['phone'] ?? '');

            $this->model->saveInstance([
                'instance_name' => $name,
                'display_name' => $data['display_name'] ?? $name,
                'status' => 'disconnected',
                'phone' => $data['phone'] ?? '',
            ]);

            log_activity('whatsapp_instance_created', 'whatsapp', null, null, ['instance' => $name]);
            flash('success', "Instance '{$name}' created. Connect it now.");
            $this->redirect('/admin/whatsapp/instances');
        } catch (\Exception $e) {
            flash('error', 'Failed to create instance: ' . $e->getMessage());
            $this->back();
        }
    }

    public function connectInstance(string $name): void
    {
        try {
            $result = $this->api->connectInstance($name);

            if (isset($result['data']['base64'])) {
                $this->model->updateInstanceQR($name, $result['data']['base64']);
            }

            flash('success', 'Connecting instance...');
            $this->redirect("/admin/whatsapp/instances/{$name}/qr");
        } catch (\Exception $e) {
            flash('error', 'Failed to connect: ' . $e->getMessage());
            $this->back();
        }
    }

    public function instanceQR(string $name): void
    {
        $instance = $this->model->getInstance($name);
        if (!$instance) {
            flash('error', 'Instance not found.');
            $this->redirect('/admin/whatsapp/instances');
        }

        // Try to get fresh QR
        try {
            $result = $this->api->getQRCode($name);
            if (isset($result['data']['base64'])) {
                $this->model->updateInstanceQR($name, $result['data']['base64']);
                $instance->qrcode = $result['data']['base64'];
            }
            if (isset($result['data']['state'])) {
                $this->model->updateInstanceStatus($name, $result['data']['state']);
                $instance->status = $result['data']['state'];
            }
        } catch (\Exception $e) {
            // QR fetch failed, show existing or error
        }

        $this->adminView('whatsapp/instances', [
            'pageTitle' => "Connect: {$name}",
            'instances' => $this->model->getInstances(),
            'showQR' => $instance,
        ]);
    }

    public function restartInstance(string $name): void
    {
        try {
            $this->api->restart($name);
            $this->model->updateInstanceStatus($name, 'restarting');
            flash('success', 'Instance restarting...');
        } catch (\Exception $e) {
            flash('error', 'Failed: ' . $e->getMessage());
        }
        $this->redirect('/admin/whatsapp/instances');
    }

    public function logoutInstance(string $name): void
    {
        try {
            $this->api->logout($name);
            $this->model->updateInstanceStatus($name, 'disconnected');
            $this->model->updateInstanceQR($name, null);
            flash('success', 'Instance logged out.');
        } catch (\Exception $e) {
            flash('error', 'Failed: ' . $e->getMessage());
        }
        $this->redirect('/admin/whatsapp/instances');
    }

    public function deleteInstance(string $name): void
    {
        try {
            $this->api->deleteInstance($name);
        } catch (\Exception $e) {
            // Continue even if API delete fails
        }
        $this->model->deleteInstance($name);
        log_activity('whatsapp_instance_deleted', 'whatsapp', null, null, ['instance' => $name]);
        flash('success', 'Instance deleted.');
        $this->redirect('/admin/whatsapp/instances');
    }

    public function instanceQRData(string $name): void
    {
        header('Content-Type: application/json');
        try {
            $result = $this->api->getQRCode($name);
            echo json_encode($result);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // ==================== Contacts ====================

    public function contacts(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');

        $paginated = $this->model->getContacts($search, '', $page, 25);
        $instances = $this->model->getInstances();

        $this->adminView('whatsapp/contacts', [
            'pageTitle' => 'WhatsApp Contacts',
            'contacts' => $paginated->data,
            'pagination' => $paginated,
            'instances' => $instances,
            'filters' => ['search' => $search],
        ]);
    }

    public function importContacts(): void
    {
        if (empty($_FILES['csv_file'])) {
            flash('error', 'No file uploaded.');
            $this->back();
        }

        $file = $_FILES['csv_file'];
        $handle = fopen($file['tmp_name'], 'r');
        if (!$handle) {
            flash('error', 'Cannot read file.');
            $this->back();
        }

        $contacts = [];
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $contact = array_combine($header, $row);
            $contacts[] = [
                'name' => $contact['name'] ?? $contact['Name'] ?? '',
                'phone' => \App\Services\EvolutionAPI::formatPhone($contact['phone'] ?? $contact['Phone'] ?? ''),
                'email' => $contact['email'] ?? $contact['Email'] ?? '',
                'country' => $contact['country'] ?? $contact['Country'] ?? '',
                'instance' => $_POST['instance'] ?? '',
            ];
        }
        fclose($handle);

        $result = $this->model->importContacts($contacts);
        flash('success', "Imported {$result['imported']} contacts. Skipped {$result['skipped']}.");
        $this->redirect('/admin/whatsapp/contacts');
    }

    public function exportContacts(): void
    {
        $contacts = $this->model->getContacts('', '', 1, 10000);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="whatsapp_contacts_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Name', 'Phone', 'Email', 'Country', 'Instance', 'Created']);

        foreach ($contacts->data as $c) {
            fputcsv($output, [$c->name, $c->phone, $c->email, $c->country, $c->instance, $c->created_at]);
        }
        fclose($output);
        exit;
    }

    public function deleteContacts(): void
    {
        $ids = $_POST['contact_ids'] ?? [];
        if (!empty($ids)) {
            $this->model->deleteContacts(array_map('intval', $ids));
            flash('success', count($ids) . ' contacts deleted.');
        }
        $this->redirect('/admin/whatsapp/contacts');
    }

    // ==================== Messages / Chats ====================

    public function messages(): void
    {
        $phone = sanitize($_GET['phone'] ?? '');
        $instance = sanitize($_GET['instance'] ?? $this->model->getSettings()->default_instance ?? '');

        $conversations = $this->model->getConversations($instance);
        $chatMessages = [];

        if ($phone) {
            $chatMessages = $this->model->getMessages($phone, $instance, 100);
            $chatMessages = array_reverse($chatMessages);
        }

        $instances = $this->model->getInstances();

        $this->adminView('whatsapp/messages', [
            'pageTitle' => 'WhatsApp Chats',
            'conversations' => $conversations,
            'chatMessages' => $chatMessages,
            'selectedPhone' => $phone,
            'selectedInstance' => $instance,
            'instances' => $instances,
        ]);
    }

    public function sendMessage(): void
    {
        $data = $this->getPostData();
        $phone = \App\Services\EvolutionAPI::formatPhone($data['phone'] ?? '');
        $message = $data['message'] ?? '';
        $instance = $data['instance'] ?? $this->model->getSettings()->default_instance ?? '';
        $messageType = $data['message_type'] ?? 'text';

        if (empty($phone) || empty($message)) {
            if ($this->isAjax()) { $this->error('Phone and message are required.'); }
            flash('error', 'Phone and message are required.');
            $this->back();
        }

        try {
            $jid = $phone . '@s.whatsapp.net';
            $result = $this->api->sendText($instance, $jid, $message);

            $this->model->saveMessage([
                'instance' => $instance,
                'phone' => $phone,
                'direction' => 'outbound',
                'message_type' => 'text',
                'message' => $message,
                'status' => 'sent',
                'message_id' => $result['data']['key']['id'] ?? null,
                'from_me' => 1,
            ]);

            // Update contact last seen
            $this->model->saveContact(['phone' => $phone, 'instance' => $instance, 'last_seen' => date('Y-m-d H:i:s')]);

            if ($this->isAjax()) {
                $this->success(['message_id' => $result['data']['key']['id'] ?? null], 'Message sent');
            }

            flash('success', 'Message sent!');
            $this->redirect("/admin/whatsapp/chats?phone={$phone}&instance={$instance}");
        } catch (\Exception $e) {
            if ($this->isAjax()) { $this->error($e->getMessage()); }
            flash('error', 'Failed to send: ' . $e->getMessage());
            $this->back();
        }
    }

    public function sendMedia(): void
    {
        $phone = \App\Services\EvolutionAPI::formatPhone($_POST['phone'] ?? '');
        $instance = $_POST['instance'] ?? $this->model->getSettings()->default_instance ?? '';
        $caption = $_POST['caption'] ?? '';
        $messageType = $_POST['message_type'] ?? 'image';

        if (empty($phone) || empty($_FILES['media'])) {
            flash('error', 'Phone and media file are required.');
            $this->back();
        }

        try {
            // Save uploaded file temporarily
            $tmpFile = $_FILES['media']['tmp_name'];
            $fileName = $_FILES['media']['name'];
            $mimeType = $_FILES['media']['type'];

            // Convert to base64
            $fileContent = file_get_contents($tmpFile);
            $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

            $jid = $phone . '@s.whatsapp.net';

            switch ($messageType) {
                case 'image':
                    $result = $this->api->sendImage($instance, $jid, $base64, $caption);
                    break;
                case 'video':
                    $result = $this->api->sendVideo($instance, $jid, $base64, $caption);
                    break;
                case 'document':
                    $result = $this->api->sendDocument($instance, $jid, $base64, $fileName, $caption);
                    break;
                case 'audio':
                    $result = $this->api->sendAudio($instance, $jid, $base64);
                    break;
                default:
                    $result = $this->api->sendImage($instance, $jid, $base64, $caption);
            }

            $this->model->saveMessage([
                'instance' => $instance,
                'phone' => $phone,
                'direction' => 'outbound',
                'message_type' => $messageType,
                'message' => $caption,
                'media_url' => $fileName,
                'media_type' => $mimeType,
                'filename' => $fileName,
                'status' => 'sent',
                'message_id' => $result['data']['key']['id'] ?? null,
                'from_me' => 1,
            ]);

            flash('success', 'Media sent!');
            $this->redirect("/admin/whatsapp/chats?phone={$phone}&instance={$instance}");
        } catch (\Exception $e) {
            flash('error', 'Failed to send media: ' . $e->getMessage());
            $this->back();
        }
    }

    // ==================== Templates ====================

    public function templates(): void
    {
        $templates = $this->model->getTemplates();
        $this->adminView('whatsapp/templates', [
            'pageTitle' => 'Message Templates',
            'templates' => $templates,
        ]);
    }

    public function storeTemplate(): void
    {
        $data = $this->getPostData();
        $this->model->saveTemplate($data);
        flash('success', 'Template saved!');
        $this->redirect('/admin/whatsapp/templates');
    }

    public function deleteTemplate(int $id): void
    {
        $this->model->deleteTemplate($id);
        flash('success', 'Template deleted.');
        $this->redirect('/admin/whatsapp/templates');
    }

    // ==================== Campaigns ====================

    public function campaigns(): void
    {
        $campaigns = $this->model->getCampaigns();
        $templates = $this->model->getTemplates();
        $instances = $this->model->getInstances();

        $this->adminView('whatsapp/campaigns', [
            'pageTitle' => 'Campaign Manager',
            'campaigns' => $campaigns,
            'templates' => $templates,
            'instances' => $instances,
        ]);
    }

    public function storeCampaign(): void
    {
        $data = $this->getPostData();
        $campaignId = $this->model->saveCampaign($data);

        if (!empty($data['contact_ids'])) {
            $this->model->addCampaignContacts($campaignId, $data['contact_ids']);
        }

        flash('success', 'Campaign created!');
        $this->redirect('/admin/whatsapp/campaigns');
    }

    public function startCampaign(int $id): void
    {
        $campaign = $this->model->getCampaign($id);
        if (!$campaign) {
            flash('error', 'Campaign not found.');
            $this->redirect('/admin/whatsapp/campaigns');
        }

        $this->model->updateCampaignStatus($id, 'running');
        $this->executeCampaign($id);
        $this->redirect('/admin/whatsapp/campaigns');
    }

    public function pauseCampaign(int $id): void
    {
        $this->model->updateCampaignStatus($id, 'paused');
        flash('success', 'Campaign paused.');
        $this->redirect('/admin/whatsapp/campaigns');
    }

    public function resumeCampaign(int $id): void
    {
        $this->model->updateCampaignStatus($id, 'running');
        $this->executeCampaign($id);
        $this->redirect('/admin/whatsapp/campaigns');
    }

    public function stopCampaign(int $id): void
    {
        $this->model->updateCampaignStatus($id, 'completed');
        flash('success', 'Campaign stopped.');
        $this->redirect('/admin/whatsapp/campaigns');
    }

    public function deleteCampaign(int $id): void
    {
        $this->model->deleteCampaign($id);
        flash('success', 'Campaign deleted.');
        $this->redirect('/admin/whatsapp/campaigns');
    }

    private function executeCampaign(int $campaignId): void
    {
        $campaign = $this->model->getCampaign($campaignId);
        if (!$campaign || $campaign->status !== 'running') return;

        $pending = $this->model->getCampaignPendingContacts($campaignId);

        foreach ($pending as $contact) {
            if ($this->model->getCampaign($campaignId)->status !== 'running') break;

            try {
                $phone = \App\Services\EvolutionAPI::formatPhone($contact->phone);
                $jid = $phone . '@s.whatsapp.net';
                $instance = $campaign->instance ?: $this->model->getSettings()->default_instance;

                $message = $campaign->template_message;
                $vars = ['customer' => $contact->name, 'phone' => $phone];
                $message = $this->model->renderTemplate($message, $vars);

                $result = $this->api->sendText($instance, $jid, $message);

                $this->model->updateCampaignContactStatus($campaignId, $contact->contact_id, 'sent', $result['data']['key']['id'] ?? '');
                $this->model->incrementTemplateUsage($campaign->template_id);

                usleep(2000000); // 2 second delay between messages
            } catch (\Exception $e) {
                $this->model->updateCampaignContactStatus($campaignId, $contact->contact_id, 'failed', '', $e->getMessage());
            }

            $this->model->updateCampaignStats($campaignId);
        }

        $this->model->updateCampaignStatus($campaignId, 'completed');
        $this->model->updateCampaignStats($campaignId);
    }

    // ==================== Automation ====================

    public function automation(): void
    {
        $automations = $this->model->getAutomations();
        $templates = $this->model->getTemplates();
        $instances = $this->model->getInstances();

        $this->adminView('whatsapp/automation', [
            'pageTitle' => 'Automation Rules',
            'automations' => $automations,
            'templates' => $templates,
            'instances' => $instances,
        ]);
    }

    public function storeAutomation(): void
    {
        $data = $this->getPostData();
        $this->model->saveAutomation($data);
        flash('success', 'Automation rule saved!');
        $this->redirect('/admin/whatsapp/automation');
    }

    public function deleteAutomation(int $id): void
    {
        $this->model->deleteAutomation($id);
        flash('success', 'Automation deleted.');
        $this->redirect('/admin/whatsapp/automation');
    }

    /**
     * Trigger automation from external events
     */
    public function triggerAutomation(string $event, array $data): void
    {
        $automations = $this->model->getActiveAutomations($event);

        foreach ($automations as $automation) {
            try {
                $instance = $automation->instance ?: $this->model->getSettings()->default_instance;
                $message = $this->model->renderTemplate($automation->template_message, $data);

                $phone = \App\Services\EvolutionAPI::formatPhone($data['phone'] ?? '');
                if (empty($phone)) continue;

                $jid = $phone . '@s.whatsapp.net';
                $result = $this->api->sendText($instance, $jid, $message);

                $this->model->saveMessage([
                    'instance' => $instance,
                    'phone' => $phone,
                    'direction' => 'outbound',
                    'message_type' => 'text',
                    'message' => $message,
                    'status' => 'sent',
                    'message_id' => $result['data']['key']['id'] ?? null,
                    'from_me' => 1,
                ]);
            } catch (\Exception $e) {
                error_log("Automation trigger failed: " . $e->getMessage());
            }
        }
    }

    // ==================== Logs ====================

    public function logs(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $instance = sanitize($_GET['instance'] ?? '');
        $paginated = $this->model->getLogs($instance, $page, 50);
        $instances = $this->model->getInstances();

        $this->adminView('whatsapp/logs', [
            'pageTitle' => 'API Logs',
            'logs' => $paginated->data,
            'pagination' => $paginated,
            'instances' => $instances,
            'filters' => ['instance' => $instance],
        ]);
    }

    public function clearLogs(): void
    {
        $this->model->clearLogs();
        flash('success', 'Logs cleared.');
        $this->redirect('/admin/whatsapp/logs');
    }

    // ==================== Settings ====================

    public function settings(): void
    {
        $settings = $this->model->getSettings();
        $this->adminView('whatsapp/settings', [
            'pageTitle' => 'WhatsApp Settings',
            'settings' => $settings,
        ]);
    }

    public function updateSettings(): void
    {
        $data = $this->getPostData();
        $this->model->updateSettings($data);
        log_activity('whatsapp_settings_updated', 'whatsapp');
        flash('success', 'Settings updated!');
        $this->redirect('/admin/whatsapp/settings');
    }

    public function testConnection(): void
    {
        $result = $this->api->testConnection();
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    // ==================== API Endpoints (AJAX) ====================

    public function apiInstanceStatus(string $name): void
    {
        header('Content-Type: application/json');
        try {
            $result = $this->api->getInstance($name);
            echo json_encode($result);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    public function apiSyncInstances(): void
    {
        header('Content-Type: application/json');
        try {
            $result = $this->api->getInstances();
            if (isset($result['data']) && is_array($result['data'])) {
                foreach ($result['data'] as $inst) {
                    $this->model->saveInstance([
                        'instance_name' => $inst['instanceName'] ?? $inst['name'] ?? '',
                        'status' => $inst['state'] ?? 'disconnected',
                        'phone' => $inst['number'] ?? '',
                        'profile_name' => $inst['profileName'] ?? '',
                        'profile_picture' => $inst['profilePictureUrl'] ?? '',
                    ]);
                }
            }
            echo json_encode(['success' => true, 'message' => 'Instances synced']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
