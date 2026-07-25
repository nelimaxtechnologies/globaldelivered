<?php
/**
 * Global Delivered Logistics - Email Service
 *
 * Handles email sending via PHPMailer with SMTP,
 * template rendering, and queue processing.
 */

namespace App\Services;

use App\Core\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private static ?EmailService $instance = null;
    private Database $db;
    private array $config;

    private function __construct()
    {
        $this->db = Database::getInstance();
        $this->config = $this->loadConfig();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load mail config from database settings (with file fallback)
     */
    private function loadConfig(): array
    {
        $fileConfig = file_exists(__DIR__ . '/../../config/mail.php')
            ? require __DIR__ . '/../../config/mail.php'
            : [];

        $smtp = [
            'host'       => $this->db->fetchColumn("SELECT `value` FROM settings WHERE `key` = 'smtp_host' AND `group` = 'email'") ?? '',
            'port'       => (int) ($this->db->fetchColumn("SELECT `value` FROM settings WHERE `key` = 'smtp_port' AND `group` = 'email'") ?? 465),
            'username'   => $this->db->fetchColumn("SELECT `value` FROM settings WHERE `key` = 'smtp_username' AND `group` = 'email'") ?? '',
            'password'   => $this->db->fetchColumn("SELECT `value` FROM settings WHERE `key` = 'smtp_password' AND `group` = 'email'") ?? '',
            'encryption' => $this->db->fetchColumn("SELECT `value` FROM settings WHERE `key` = 'smtp_encryption' AND `group` = 'email'") ?? 'ssl',
            'from_email' => $this->db->fetchColumn("SELECT `value` FROM settings WHERE `key` = 'smtp_from_email' AND `group` = 'email'") ?? '',
            'from_name'  => $this->db->fetchColumn("SELECT `value` FROM settings WHERE `key` = 'smtp_from_name' AND `group` = 'email'") ?? 'Global Delivered Logistics',
        ];

        // Fallback to file config if DB is empty
        if (empty($smtp['host']) && !empty($fileConfig['smtp'])) {
            $smtp = $fileConfig['smtp'];
        }

        return $smtp;
    }

    /**
     * Create a configured PHPMailer instance
     */
    private function createMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = $this->config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $this->config['username'];
        $mail->Password   = $this->config['password'];

        $encryption = strtolower($this->config['encryption']);
        if ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $this->config['port'] ?: 465;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->config['port'] ?: 587;
        }

        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';

        return $mail;
    }

    /**
     * Send an email directly (not queued)
     */
    public function sendDirect(string $toEmail, string $toName, string $subject, string $htmlBody, string $altBody = ''): bool
    {
        try {
            $mail = $this->createMailer();

            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = $altBody ?: strip_tags($htmlBody);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email send error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Queue email for sending
     */
    public function queueEmail(string $toEmail, string $toName, string $subject, string $body, string $altBody = '', int $priority = 0): bool
    {
        try {
            $this->db->query(
                "INSERT INTO email_queue (to_email, to_name, subject, body, alt_body, status, priority, created_at)
                 VALUES (?, ?, ?, ?, ?, 'queued', ?, NOW())",
                [$toEmail, $toName, $subject, $body, $altBody, $priority]
            );
            return true;
        } catch (\Exception $e) {
            error_log("Email queue error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Process email queue — sends queued emails via PHPMailer SMTP
     */
    public function processQueue(int $limit = 10): array
    {
        $results = ['sent' => 0, 'failed' => 0, 'errors' => []];

        $emails = $this->db->fetchAll(
            "SELECT * FROM email_queue WHERE status = 'queued' AND retry_count < max_retries
             ORDER BY priority DESC, created_at ASC LIMIT ?",
            [$limit]
        );

        foreach ($emails as $email) {
            try {
                $this->db->query(
                    "UPDATE email_queue SET status = 'sending' WHERE id = ?",
                    [$email->id]
                );

                $mail = $this->createMailer();
                $mail->setFrom($this->config['from_email'], $this->config['from_name']);
                $mail->addAddress($email->to_email, $email->to_name ?? '');
                $mail->isHTML(true);
                $mail->Subject = $email->subject;
                $mail->Body    = $email->body;
                $mail->AltBody = $email->alt_body ?: strip_tags($email->body);

                $mail->send();

                $this->db->query(
                    "UPDATE email_queue SET status = 'sent', sent_at = NOW() WHERE id = ?",
                    [$email->id]
                );
                $results['sent']++;

            } catch (Exception $e) {
                $retryCount = $email->retry_count + 1;
                $status = $retryCount >= $email->max_retries ? 'failed' : 'queued';

                $this->db->query(
                    "UPDATE email_queue SET status = ?, retry_count = retry_count + 1, error_message = ? WHERE id = ?",
                    [$status, $e->getMessage(), $email->id]
                );

                $results['failed']++;
                $results['errors'][] = "Email #{$email->id}: {$e->getMessage()}";
            }
        }

        return $results;
    }

    /**
     * Send shipment created notification
     */
    public function sendShipmentCreated(string $email, string $name, string $trackingNumber, string $origin, string $destination): bool
    {
        $subject = "Shipment {$trackingNumber} Created Successfully";

        $body = $this->renderTemplate('shipment_created', [
            'name' => $name,
            'tracking_number' => $trackingNumber,
            'origin' => $origin,
            'destination' => $destination,
            'track_url' => BASE_URL . "/tracking/{$trackingNumber}",
        ]);

        return $this->queueEmail($email, $name, $subject, $body);
    }

    /**
     * Send status update notification
     */
    public function sendStatusUpdate(string $email, string $name, string $trackingNumber, string $status, string $location = ''): bool
    {
        $subject = "Shipment {$trackingNumber} - {$status}";

        $body = $this->renderTemplate('status_update', [
            'name' => $name,
            'tracking_number' => $trackingNumber,
            'status' => $status,
            'location' => $location,
            'track_url' => BASE_URL . "/tracking/{$trackingNumber}",
        ]);

        return $this->queueEmail($email, $name, $subject, $body);
    }

    /**
     * Send delivery confirmation
     */
    public function sendDelivered(string $email, string $name, string $trackingNumber, string $deliveredTo = ''): bool
    {
        $subject = "Shipment {$trackingNumber} Delivered!";

        $body = $this->renderTemplate('delivered', [
            'name' => $name,
            'tracking_number' => $trackingNumber,
            'delivered_to' => $deliveredTo,
        ]);

        return $this->queueEmail($email, $name, $subject, $body);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordReset(string $email, string $name, string $token): bool
    {
        $subject = "Password Reset Request";
        $resetUrl = BASE_URL . "/forgot-password?token={$token}";

        $body = $this->renderTemplate('password_reset', [
            'name' => $name,
            'reset_url' => $resetUrl,
            'token' => $token,
        ]);

        return $this->queueEmail($email, $name, $subject, $body, '', 10);
    }

    /**
     * Send contact form notification to admin
     */
    public function sendContactForm(string $fromEmail, string $fromName, string $subject, string $message): bool
    {
        $adminEmail = $this->config['from_email'];
        $htmlBody = $this->renderTemplate('contact_form', [
            'from_name' => $fromName,
            'from_email' => $fromEmail,
            'subject' => $subject,
            'message' => $message,
        ]);

        return $this->queueEmail($adminEmail, $this->config['from_name'], "Contact: {$subject}", $htmlBody);
    }

    /**
     * Send invoice email
     */
    public function sendInvoice(string $toEmail, string $toName, string $invoiceNumber, float $total, string $currency, string $dueDate): bool
    {
        $subject = "Invoice {$invoiceNumber} from Global Delivered Logistics";

        $body = $this->renderTemplate('invoice', [
            'name' => $toName,
            'invoice_number' => $invoiceNumber,
            'total' => number_format($total, 2),
            'currency' => $currency,
            'due_date' => $dueDate,
            'invoice_url' => BASE_URL . "/dashboard/invoices",
        ]);

        return $this->queueEmail($toEmail, $toName, $subject, $body);
    }

    /**
     * Get email queue stats
     */
    public function getQueueStats(): object
    {
        return $this->db->fetch(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'queued' THEN 1 ELSE 0 END) as queued,
                SUM(CASE WHEN status = 'sending' THEN 1 ELSE 0 END) as sending,
                SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
             FROM email_queue"
        );
    }

    /**
     * Render email HTML template
     */
    private function renderTemplate(string $template, array $data = []): string
    {
        $companyName = 'Global Delivered Logistics';
        $companyPhone = '+1-555-0123';
        $companyEmail = $this->config['from_email'] ?? 'info@globaldelivered.com';
        $year = date('Y');
        $baseUrl = BASE_URL;

        extract($data);

        $templateFile = __DIR__ . '/../Views/emails/' . $template . '.php';
        if (file_exists($templateFile)) {
            ob_start();
            require $templateFile;
            return ob_get_clean();
        }

        // Fallback inline template
        return $this->fallbackTemplate($template, $data);
    }

    /**
     * Fallback template if file not found
     */
    private function fallbackTemplate(string $template, array $data): string
    {
        $name = $data['name'] ?? 'Customer';
        $year = date('Y');
        $baseUrl = BASE_URL;

        $content = match ($template) {
            'shipment_created' => "
                <h2 style='color:#1a237e;'>Shipment Created!</h2>
                <p>Dear {$name},</p>
                <p>Your shipment has been created successfully.</p>
                <div style='background:#fff;padding:20px;border-radius:8px;margin:20px 0;'>
                    <p><strong>Tracking Number:</strong> {$data['tracking_number']}</p>
                    <p><strong>From:</strong> {$data['origin']}</p>
                    <p><strong>To:</strong> {$data['destination']}</p>
                </div>
                <a href='{$data['track_url']}' style='display:inline-block;background:#ff6f00;color:#fff;padding:12px 30px;text-decoration:none;border-radius:5px;font-weight:bold;'>Track Your Shipment</a>",
            'status_update' => "
                <h2 style='color:#1a237e;'>Status Update</h2>
                <p>Dear {$name},</p>
                <p>Your shipment <strong>{$data['tracking_number']}</strong> has been updated:</p>
                <div style='background:#fff;padding:20px;border-radius:8px;margin:20px 0;text-align:center;'>
                    <span style='background:#1a237e;color:#fff;padding:8px 20px;border-radius:20px;font-weight:bold;'>{$data['status']}</span>
                    " . (!empty($data['location']) ? "<p style='margin-top:10px;'><strong>Location:</strong> {$data['location']}</p>" : '') . "
                </div>
                <a href='{$data['track_url']}' style='display:inline-block;background:#ff6f00;color:#fff;padding:12px 30px;text-decoration:none;border-radius:5px;font-weight:bold;'>View Details</a>",
            'delivered' => "
                <h2 style='color:#2e7d32;'>Package Delivered!</h2>
                <p>Dear {$name},</p>
                <p>Your shipment <strong>{$data['tracking_number']}</strong> has been delivered.</p>
                " . (!empty($data['delivered_to']) ? "<p><strong>Delivered to:</strong> {$data['delivered_to']}</p>" : '') . "
                <div style='background:#e8f5e9;padding:20px;border-radius:8px;margin:20px 0;text-align:center;'>
                    <p style='font-weight:bold;'>Thank you for shipping with us!</p>
                </div>",
            'password_reset' => "
                <h2 style='color:#1a237e;'>Password Reset</h2>
                <p>Dear {$name},</p>
                <p>You requested a password reset. Click the button below to set a new password:</p>
                <a href='{$data['reset_url']}' style='display:inline-block;background:#dc3545;color:#fff;padding:12px 30px;text-decoration:none;border-radius:5px;font-weight:bold;'>Reset Password</a>
                <p style='margin-top:20px;color:#6c757d;font-size:12px;'>If you didn't request this, please ignore this email. This link expires in 1 hour.</p>",
            'contact_form' => "
                <h2 style='color:#1a237e;'>New Contact Form Submission</h2>
                <p><strong>From:</strong> {$data['from_name']} ({$data['from_email']})</p>
                <p><strong>Subject:</strong> {$data['subject']}</p>
                <div style='background:#fff;padding:20px;border-radius:8px;margin:20px 0;border-left:4px solid #1a237e;'>
                    " . nl2br(htmlspecialchars($data['message'])) . "
                </div>",
            'invoice' => "
                <h2 style='color:#1a237e;'>Invoice {$data['invoice_number']}</h2>
                <p>Dear {$name},</p>
                <p>Please find your invoice details below:</p>
                <div style='background:#fff;padding:20px;border-radius:8px;margin:20px 0;'>
                    <p><strong>Invoice:</strong> {$data['invoice_number']}</p>
                    <p><strong>Total:</strong> {$data['currency']} {$data['total']}</p>
                    <p><strong>Due Date:</strong> {$data['due_date']}</p>
                </div>
                <a href='{$data['invoice_url']}' style='display:inline-block;background:#1a237e;color:#fff;padding:12px 30px;text-decoration:none;border-radius:5px;font-weight:bold;'>View Invoice</a>",
            default => "<p>Hello {$name},</p><p>You have a new notification from Global Delivered Logistics.</p>",
        };

        return $this->wrapLayout($content);
    }

    /**
     * Wrap content in email layout
     */
    private function wrapLayout(string $content): string
    {
        $year = date('Y');
        return "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;'>
            <div style='max-width:600px;margin:0 auto;background:#fff;'>
                <div style='background:#1a237e;padding:30px;text-align:center;'>
                    <h1 style='color:#fff;margin:0;font-size:20px;'>Global Delivered Logistics</h1>
                </div>
                <div style='padding:30px;background:#f8f9fa;'>
                    {$content}
                </div>
                <div style='background:#0d1452;color:#fff;padding:20px;text-align:center;font-size:12px;'>
                    <p>Need help? Contact us at +1-555-0123 or info@globaldelivered.com</p>
                    <p>&copy; {$year} Global Delivered Logistics. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
