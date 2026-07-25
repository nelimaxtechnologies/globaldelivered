<?php
/**
 * Global Delivered Logistics - Shipment Status Seeder
 */

namespace Database\Seeders;

class StatusSeeder
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $statuses = [
            ['name' => 'Order Received',     'slug' => 'order_received',     'color' => '#3498db', 'icon' => 'bi-inbox',             'sort_order' => 1],
            ['name' => 'Picked Up',          'slug' => 'picked_up',          'color' => '#2ecc71', 'icon' => 'bi-box-seam',          'sort_order' => 2],
            ['name' => 'At Warehouse',       'slug' => 'at_warehouse',       'color' => '#f39c12', 'icon' => 'bi-building',          'sort_order' => 3],
            ['name' => 'In Transit',         'slug' => 'in_transit',         'color' => '#9b59b6', 'icon' => 'bi-truck',             'sort_order' => 4],
            ['name' => 'Customs Clearance',  'slug' => 'customs_clearance',  'color' => '#e74c3c', 'icon' => 'bi-shield-check',      'sort_order' => 5],
            ['name' => 'Fees Payment Required', 'slug' => 'fees_payment_required', 'color' => '#e67e22', 'icon' => 'bi-credit-card', 'sort_order' => 6],
            ['name' => 'Awaiting Forwarding to Final Destination', 'slug' => 'awaiting_forwarding', 'color' => '#2980b9', 'icon' => 'bi-arrow-left-right', 'sort_order' => 7],
            ['name' => 'Out for Delivery',   'slug' => 'out_for_delivery',   'color' => '#1abc9c', 'icon' => 'bi-bicycle',           'sort_order' => 8],
            ['name' => 'Delivered',          'slug' => 'delivered',          'color' => '#27ae60', 'icon' => 'bi-check-circle',      'sort_order' => 9],
            ['name' => 'Delayed',            'slug' => 'delayed',            'color' => '#e74c3c', 'icon' => 'bi-exclamation-triangle', 'sort_order' => 10],
            ['name' => 'Returned',           'slug' => 'returned',           'color' => '#95a5a6', 'icon' => 'bi-arrow-return-left',  'sort_order' => 11],
            ['name' => 'Cancelled',          'slug' => 'cancelled',          'color' => '#e74c3c', 'icon' => 'bi-x-circle',          'sort_order' => 12],
            ['name' => 'On Hold',            'slug' => 'on_hold',            'color' => '#f39c12', 'icon' => 'bi-pause-circle',      'sort_order' => 13],
        ];

        $stmt = $this->pdo->prepare(
            "INSERT IGNORE INTO shipment_statuses (name, slug, color, icon, sort_order, created_at) VALUES (?, ?, ?, ?, ?, NOW())"
        );

        foreach ($statuses as $s) {
            $stmt->execute([$s['name'], $s['slug'], $s['color'], $s['icon'], $s['sort_order']]);
        }

        echo "  Statuses: " . count($statuses) . " inserted.\n";
    }
}
