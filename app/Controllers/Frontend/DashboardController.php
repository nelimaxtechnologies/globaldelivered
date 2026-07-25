<?php
/**
 * Global Delivered Logistics - Customer Dashboard Controller
 */

namespace App\Controllers\Frontend;

use App\Core\Controller;

class DashboardController extends Controller
{
    /**
     * Render dashboard view with dashboard layout
     */
    protected function dashboardView(string $view, array $data = []): void
    {
        $this->view($view, $data, 'dashboard');
    }

    /**
     * Get customer record for current user, auto-create if missing.
     * Redirects admins to admin dashboard. Returns null if not found.
     */
    protected function getCustomer(): ?object
    {
        $userId = $_SESSION['user_id'];
        $customer = $this->db->fetch("SELECT * FROM customers WHERE user_id = ?", [$userId]);

        if ($customer) return $customer;

        // Admin/staff -> redirect to admin panel
        if (in_array($_SESSION['user_role'] ?? '', ['super_admin', 'admin', 'branch_manager'])) {
            $this->redirect('/admin/dashboard');
        }

        // Try to auto-create missing customer record from user data
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        if ($user) {
            $this->db->query(
                "INSERT INTO customers (user_id, first_name, last_name, email, phone, is_active, created_at)
                 VALUES (?, ?, ?, ?, ?, 1, NOW())",
                [$user->id, $user->first_name, $user->last_name, $user->email, $user->phone ?? '']
            );
            return $this->db->fetch("SELECT * FROM customers WHERE user_id = ?", [$userId]);
        }

        return null;
    }

    /**
     * Display customer dashboard
     */
    public function index(): void
    {
        $customer = $this->getCustomer();
        
        if (!$customer) {
            flash('error', 'Customer profile not found. Please contact support.');
            $this->redirect('/');
        }
        
        $stats = $this->db->fetch(
            "SELECT 
                COUNT(*) as total_shipments,
                SUM(CASE WHEN status = 'in_transit' THEN 1 ELSE 0 END) as in_transit,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
             FROM shipments WHERE customer_id = ? AND deleted_at IS NULL",
            [$customer->id]
        );
        
        $recentShipments = $this->db->fetchAll(
            "SELECT s.*, ss.name as status_name, ss.color as status_color
             FROM shipments s
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.customer_id = ? AND s.deleted_at IS NULL
             ORDER BY s.created_at DESC LIMIT 5",
            [$customer->id]
        );
        
        $this->dashboardView('frontend/dashboard/index', [
            'pageTitle' => 'Dashboard - Global Delivered Logistics',
            'customer' => $customer,
            'stats' => $stats,
            'shipments' => $recentShipments,
        ]);
    }

    /**
     * View all customer shipments
     */
    public function shipments(): void
    {
        $customer = $this->getCustomer();
        
        if (!$customer) {
            $this->redirect('/');
        }
        
        $page = (int) ($_GET['page'] ?? 1);
        $search = sanitize($_GET['search'] ?? '');
        
        $where = "WHERE customer_id = ? AND deleted_at IS NULL";
        $params = [$customer->id];
        
        if (!empty($search)) {
            $where .= " AND (tracking_number LIKE ? OR recipient_name LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $paginated = $this->db->paginate(
            "SELECT COUNT(*) FROM shipments {$where}",
            "SELECT s.*, ss.name as status_name, ss.color as status_color 
             FROM shipments s 
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id 
             {$where} ORDER BY s.created_at DESC",
            $params, $page, 15
        );
        
        $this->dashboardView('frontend/dashboard/shipments', [
            'pageTitle' => 'My Shipments - Global Delivered Logistics',
            'shipments' => $paginated->data,
            'pagination' => $paginated,
        ]);
    }

    /**
     * View single shipment
     */
    public function shipmentDetail(int $id): void
    {
        $customer = $this->getCustomer();
        
        if (!$customer) {
            $this->redirect('/');
        }

        $shipment = $this->db->fetch(
            "SELECT s.*, ss.name as status_name, ss.color as status_color
             FROM shipments s 
             LEFT JOIN shipment_statuses ss ON s.current_status_id = ss.id
             WHERE s.id = ? AND s.customer_id = ? AND s.deleted_at IS NULL",
            [$id, $customer->id]
        );
        
        if (!$shipment) {
            flash('error', 'Shipment not found.');
            $this->redirect('/dashboard/shipments');
        }
        
        $history = $this->db->fetchAll(
            "SELECT th.*, ss.name as status_name, ss.color as status_color, ss.icon as status_icon
             FROM tracking_history th
             JOIN shipment_statuses ss ON th.status_id = ss.id
             WHERE th.shipment_id = ?
             ORDER BY th.created_at ASC",
            [$id]
        );
        
        $this->dashboardView('frontend/dashboard/shipment_detail', [
            'pageTitle' => "Shipment: {$shipment->tracking_number}",
            'shipment' => $shipment,
            'history' => $history,
        ]);
    }

    /**
     * Update profile
     */
    public function profile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile();
            return;
        }
        
        $customer = $this->getCustomer();
        
        if (!$customer) {
            $this->redirect('/');
        }
        
        $this->dashboardView('frontend/dashboard/profile', [
            'pageTitle' => 'My Profile - Global Delivered Logistics',
            'customer' => $customer,
        ]);
    }

    /**
     * Process profile update
     */
    private function updateProfile(): void
    {
        $data = [
            'phone' => sanitize($_POST['phone'] ?? ''),
            'address_line1' => sanitize($_POST['address_line1'] ?? ''),
            'city' => sanitize($_POST['city'] ?? ''),
            'state' => sanitize($_POST['state'] ?? ''),
            'country' => sanitize($_POST['country'] ?? ''),
            'postal_code' => sanitize($_POST['postal_code'] ?? ''),
        ];
        
        $this->db->query(
            "UPDATE customers SET phone = ?, address_line1 = ?, city = ?, state = ?, country = ?, postal_code = ?, updated_at = NOW()
             WHERE user_id = ?",
            [$data['phone'], $data['address_line1'], $data['city'], $data['state'], $data['country'], $data['postal_code'], $_SESSION['user_id']]
        );
        
        flash('success', 'Profile updated successfully!');
        $this->back();
    }

    /**
     * Address management
     */
    public function addresses(): void
    {
        $customer = $this->getCustomer();
        
        if (!$customer) {
            $this->redirect('/');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->db->query(
                "INSERT INTO customer_addresses (customer_id, label, address_line1, address_line2, city, state, country, postal_code, is_default, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [$customer->id, sanitize($_POST['label'] ?? 'Home'), sanitize($_POST['address_line1']), sanitize($_POST['address_line2'] ?? ''),
                 sanitize($_POST['city']), sanitize($_POST['state']), sanitize($_POST['country']), sanitize($_POST['postal_code'] ?? ''), 
                 !empty($_POST['is_default'])]
            );
            flash('success', 'Address added!');
            $this->redirect('/dashboard/addresses');
        }
        
        $addresses = $this->db->fetchAll("SELECT * FROM customer_addresses WHERE customer_id = ? ORDER BY is_default DESC", [$customer->id]);
        
        $this->dashboardView('frontend/dashboard/addresses', [
            'pageTitle' => 'My Addresses - Global Delivered Logistics',
            'addresses' => $addresses,
        ]);
    }

    public function deleteAddress(int $id): void
    {
        $this->db->query("DELETE FROM customer_addresses WHERE id = ?", [$id]);
        flash('success', 'Address deleted.');
        $this->redirect('/dashboard/addresses');
    }

    /**
     * Invoices list
     */
    public function invoices(): void
    {
        $customer = $this->getCustomer();
        
        if (!$customer) {
            $this->redirect('/');
        }
        
        $invoices = $this->db->fetchAll(
            "SELECT i.*, s.tracking_number FROM invoices i 
             LEFT JOIN shipments s ON i.shipment_id = s.id
             WHERE i.customer_id = ? ORDER BY i.created_at DESC",
            [$customer->id]
        );
        
        $this->dashboardView('frontend/dashboard/invoices', [
            'pageTitle' => 'My Invoices - Global Delivered Logistics',
            'invoices' => $invoices,
        ]);
    }

    /**
     * Download invoice (placeholder)
     */
    public function downloadInvoice(int $id): void
    {
        flash('success', 'Invoice download feature coming soon.');
        $this->redirect('/dashboard/invoices');
    }

    /**
     * Print shipping label
     */
    public function printLabel(int $id): void
    {
        $customer = $this->getCustomer();
        
        if (!$customer) {
            $this->redirect('/');
        }
        
        $shipment = $this->db->fetch(
            "SELECT * FROM shipments WHERE id = ? AND customer_id = ?",
            [$id, $customer->id]
        );
        
        if (!$shipment) {
            flash('error', 'Shipment not found.');
            $this->redirect('/dashboard/shipments');
        }
        
        $serviceLabel = ucwords(str_replace('_', ' ', $shipment->service_type));
        $date = date('M d, Y');
        $weight = $shipment->weight . ' kg';
        $dims = $shipment->length && $shipment->width && $shipment->height
            ? "{$shipment->length}×{$shipment->width}×{$shipment->height} cm" : '—';
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <title>Shipping Label — <?= htmlspecialchars($shipment->tracking_number) ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            *{margin:0;padding:0;box-sizing:border-box}
            body{font-family:'Inter',sans-serif;background:#fff;color:#1a1a2e;-webkit-print-color-adjust:exact;print-color-adjust:exact}
            .label{width:4in;padding:0;margin:0 auto;border:1.5px solid #e0e0e0;overflow:hidden}
            .label-header{background:linear-gradient(135deg,#0d1452 0%,#1a237e 60%,#283593 100%);color:#fff;padding:14px 16px;display:flex;align-items:center;justify-content:space-between}
            .brand{display:flex;flex-direction:row;align-items:center;gap:8px}
            .brand-name{font-size:13px;font-weight:800;letter-spacing:0.04em;text-transform:uppercase}
            .brand-tag{font-size:7.5px;font-weight:500;opacity:0.7;letter-spacing:0.1em;text-transform:uppercase}
            .service-pill{background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.25);color:#fff;font-size:7.5px;font-weight:700;padding:4px 10px;border-radius:20px;letter-spacing:0.06em;text-transform:uppercase}
            .tracking-section{padding:14px 16px 10px;text-align:center;border-bottom:1px solid #eee}
            .tracking-label{font-size:7px;font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:#888;margin-bottom:4px}
            .tracking-number{font-size:20px;font-weight:800;letter-spacing:0.08em;color:#0d1452;font-family:'Courier New',monospace}
            .barcode-area{padding:8px 16px 12px;text-align:center}
            .barcode-lines{display:flex;justify-content:center;gap:1.2px;height:36px;margin-bottom:4px}
            .barcode-lines span{display:block;background:#1a1a2e;border-radius:0.5px}
            .barcode-text{font-size:7.5px;font-weight:600;letter-spacing:0.18em;color:#555;font-family:'Courier New',monospace}
            .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:0}
            .info-box{padding:12px 16px;border-top:1px solid #eee}
            .info-box.from{border-right:1px solid #eee}
            .info-tag{display:inline-flex;align-items:center;gap:4px;font-size:7px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;margin-bottom:6px;padding:2px 8px;border-radius:3px}
            .info-tag.from-tag{background:#e8eaf6;color:#1a237e}
            .info-tag.to-tag{background:#e8f5e9;color:#2e7d32}
            .info-name{font-size:11px;font-weight:700;margin-bottom:2px;line-height:1.3}
            .info-line{font-size:8.5px;color:#555;line-height:1.5}
            .info-phone{font-size:8.5px;color:#1a237e;font-weight:600;margin-top:2px}
            .details-bar{display:flex;border-top:1px solid #eee}
            .detail-item{flex:1;padding:10px 16px;text-align:center;border-right:1px solid #eee}
            .detail-item:last-child{border-right:none}
            .detail-label{font-size:6.5px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:#888;margin-bottom:2px}
            .detail-value{font-size:10px;font-weight:700;color:#1a1a2e}
            .label-footer{background:#f8f9fc;border-top:1px solid #eee;padding:10px 16px;display:flex;justify-content:space-between;align-items:center}
            .footer-left{font-size:7px;color:#888;line-height:1.6}
            .footer-right{text-align:right}
            .footer-qr{width:32px;height:32px;background:#e8eaf6;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:5px;font-weight:700;color:#1a237e;line-height:1.1;text-align:center}
            @media print{body{margin:0;padding:0;background:#fff}.label{border:none;box-shadow:none}@page{size:4in auto;margin:0}}
        </style>
        </head>
        <body>
        <div class="label">
            <div class="label-header">
                <div class="brand d-flex align-items-center gap-2">
                    <svg width="28" height="28" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0">
                        <defs><linearGradient id="gdlLblC" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#FFD54F"/><stop offset="100%" style="stop-color:#FFA726"/></linearGradient></defs>
                        <circle cx="60" cy="60" r="56" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="3"/>
                        <rect x="36" y="40" width="48" height="36" rx="4" fill="url(#gdlLblC)" opacity="0.95"/>
                        <line x1="36" y1="52" x2="84" y2="52" stroke="rgba(26,35,126,0.3)" stroke-width="2"/>
                        <line x1="60" y1="40" x2="60" y2="76" stroke="rgba(26,35,126,0.3)" stroke-width="2"/>
                        <rect x="52" y="36" width="16" height="8" rx="2" fill="#fff" opacity="0.3"/>
                        <path d="M78 32 L92 48 L82 48 L82 64 L74 64 L74 48 L64 48 Z" fill="#fff" opacity="0.9"/>
                        <text x="60" y="100" text-anchor="middle" font-family="Inter,sans-serif" font-weight="800" font-size="16" fill="url(#gdlLblC)" letter-spacing="3">GDL</text>
                    </svg>
                    <div>
                        <div class="brand-name">Global Delivered</div>
                        <div class="brand-tag">Worldwide Logistics</div>
                    </div>
                </div>
                <div class="service-pill"><?= $serviceLabel ?></div>
            </div>
            <div class="tracking-section">
                <div class="tracking-label">Tracking Number</div>
                <div class="tracking-number"><?= htmlspecialchars($shipment->tracking_number) ?></div>
            </div>
            <div class="barcode-area">
                <a href="<?= BASE_URL ?>/tracking/<?= htmlspecialchars($shipment->tracking_number) ?>" target="_blank" style="text-decoration:none;display:block">
                    <svg id="barcode"></svg>
                </a>
            </div>
            <div class="info-grid">
                <div class="info-box from">
                    <div class="info-tag from-tag">Ship From</div>
                    <div class="info-name"><?= htmlspecialchars($shipment->sender_name) ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->sender_address) ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->sender_city) ?>, <?= htmlspecialchars($shipment->sender_state) ?> <?= htmlspecialchars($shipment->sender_postal_code ?? '') ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->sender_country) ?></div>
                    <div class="info-phone"><?= htmlspecialchars($shipment->sender_phone) ?></div>
                </div>
                <div class="info-box to">
                    <div class="info-tag to-tag">Ship To</div>
                    <div class="info-name"><?= htmlspecialchars($shipment->recipient_name) ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->recipient_address) ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->recipient_city) ?>, <?= htmlspecialchars($shipment->recipient_state) ?> <?= htmlspecialchars($shipment->recipient_postal_code ?? '') ?></div>
                    <div class="info-line"><?= htmlspecialchars($shipment->recipient_country) ?></div>
                    <div class="info-phone"><?= htmlspecialchars($shipment->recipient_phone) ?></div>
                </div>
            </div>
            <div class="details-bar">
                <div class="detail-item">
                    <div class="detail-label">Weight</div>
                    <div class="detail-value"><?= $weight ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Dimensions</div>
                    <div class="detail-value"><?= $dims ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Pickup Date</div>
                    <div class="detail-value"><?= $shipment->pickup_date ? format_date($shipment->pickup_date, 'M d, Y') : '—' ?></div>
                </div>
                <?php if ($shipment->is_fragile): ?>
                <div class="detail-item">
                    <div class="detail-label">Handle</div>
                    <div class="detail-value" style="color:#d32f2f">⚠ Fragile</div>
                </div>
                <?php endif; ?>
                <?php if ($shipment->is_insured): ?>
                <div class="detail-item">
                    <div class="detail-label">Insured</div>
                    <div class="detail-value" style="color:#2e7d32">✓ Yes</div>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($shipment->description): ?>
            <div style="padding:10px 16px;border-top:1px solid #eee;">
                <div style="font-size:6.5px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:#888;margin-bottom:2px;">Contents</div>
                <div style="font-size:8.5px;color:#1a1a2e;line-height:1.5;"><?= htmlspecialchars($shipment->description) ?></div>
            </div>
            <?php endif; ?>
            <div class="label-footer">
                <div class="footer-left">
                    track@globaldelivered.biz<br>
                    +254 729 373 801 · 24/7 Support
                </div>
                <div class="footer-right">
                    <div class="footer-qr">GDL<br>COURIER</div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
        <script>
            JsBarcode("#barcode", "<?= htmlspecialchars($shipment->tracking_number) ?>", {
                format: "CODE128",
                width: 1.5,
                height: 40,
                displayValue: true,
                font: "Courier",
                fontSize: 12,
                textMargin: 4,
                margin: 0
            });
            window.onload = function(){ setTimeout(function(){ window.print(); }, 500); };
        </script>
        </body>
        </html>
        <?php
        exit;
    }

    /**
     * View notifications
     */
    public function notifications(): void
    {
        $notifications = $this->db->fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50",
            [$_SESSION['user_id']]
        );
        
        // Mark all as read
        $this->db->query(
            "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE user_id = ? AND is_read = 0",
            [$_SESSION['user_id']]
        );
        
        if (is_ajax()) {
            $this->success(['notifications' => $notifications, 'unread' => 0]);
        }
        
        $this->dashboardView('frontend/dashboard/notifications', [
            'pageTitle' => 'Notifications - Global Delivered Logistics',
            'notifications' => $notifications,
        ]);
    }
}
