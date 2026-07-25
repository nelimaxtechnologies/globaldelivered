<?php
/**
 * Global Delivered Logistics - Contact Controller
 */

namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Services\EmailService;

class ContactController extends Controller
{
    /**
     * Display contact page
     */
    public function index(): void
    {
        $branches = $this->db->fetchAll(
            "SELECT * FROM branches WHERE is_active = 1 ORDER BY branch_type ASC, name ASC"
        );

        $settings = $this->getContactSettings();

        $this->view('frontend/contact', [
            'pageTitle' => 'Contact Us - Global Delivered Logistics',
            'branches' => $branches,
            'settings' => $settings,
            'metaDescription' => 'Get in touch with Global Delivered Logistics. Contact our 24/7 support team for shipping inquiries, quotes, and assistance.',
        ]);
    }

    /**
     * Send contact form (AJAX)
     */
    public function send(): void
    {
        $data = $this->getPostData();

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required|min_length:10',
        ];

        $validated = $this->validate($data, $rules);

        try {
            // Store in contact_submissions table
            $this->db->query(
                "INSERT INTO contact_submissions (name, email, phone, subject, message, ip_address, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, NOW())",
                [
                    sanitize($data['name']),
                    sanitize($data['email']),
                    sanitize($data['phone'] ?? ''),
                    sanitize($data['subject']),
                    sanitize($data['message']),
                    $_SERVER['REMOTE_ADDR'] ?? '',
                ]
            );

            // Send email via PHPMailer SMTP
            $emailService = EmailService::getInstance();
            $emailService->sendContactForm(
                sanitize($data['email']),
                sanitize($data['name']),
                sanitize($data['subject']),
                sanitize($data['message'])
            );

            log_activity('contact_form_submitted', null, null, null, [
                'name' => sanitize($data['name']),
                'email' => sanitize($data['email']),
            ]);

            $this->success(null, 'Thank you for your message! We will get back to you shortly.');

        } catch (\Exception $e) {
            error_log("Contact form error: " . $e->getMessage());
            $this->error('Failed to send message. Please try again later.');
        }
    }

    /**
     * Get contact settings from database
     */
    private function getContactSettings(): object
    {
        $settings = $this->db->fetchAll("SELECT `key`, `value` FROM settings WHERE `group` = 'general'");
        $result = [];
        foreach ($settings as $s) {
            $result[$s->key] = $s->value;
        }
        return (object) $result;
    }
}
