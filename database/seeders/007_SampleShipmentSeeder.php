<?php
/**
 * Global Delivered Logistics - Sample Shipments Seeder
 *
 * Creates realistic test shipments with full tracking history.
 * Requires: RoleSeeder, StatusSeeder, UserSeeder, BranchSeeder, ShippingSeeder
 */

namespace Database\Seeders;

class SampleShipmentSeeder
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        // Gather IDs from existing data
        $customers = $this->pdo->query("SELECT id, email FROM customers WHERE is_active = 1 LIMIT 5")->fetchAll(\PDO::FETCH_OBJ);
        $branches = $this->pdo->query("SELECT id, code FROM branches")->fetchAll(\PDO::FETCH_OBJ);
        $drivers = $this->pdo->query("SELECT id, status FROM drivers LIMIT 5")->fetchAll(\PDO::FETCH_OBJ);
        $vehicles = $this->pdo->query("SELECT id FROM vehicles LIMIT 5")->fetchAll(\PDO::FETCH_OBJ);
        $statuses = $this->pdo->query("SELECT id, slug FROM shipment_statuses ORDER BY sort_order")->fetchAll(\PDO::FETCH_OBJ);
        $users = $this->pdo->query("SELECT id FROM users WHERE role_id = (SELECT id FROM roles WHERE slug = 'super_admin') LIMIT 1")->fetchAll(\PDO::FETCH_OBJ);

        if (empty($statuses) || empty($customers)) {
            echo "  ⚠ Missing required data (statuses or customers). Skipping sample shipments.\n";
            return;
        }

        $adminId = $users[0]->id ?? null;
        $statusMap = [];
        foreach ($statuses as $s) {
            $statusMap[$s->slug] = $s->id;
        }

        // --- Shipment definitions ---
        $shipments = [
            [
                'tracking' => 'GDL-' . $this->genCode(),
                'customer' => 0, 'service' => 'express',
                'sender'   => ['name' => 'Alice Williams', 'email' => 'alice@example.com', 'phone' => '+1-555-0101', 'addr' => '123 Main St', 'city' => 'New York', 'state' => 'NY', 'country' => 'United States'],
                'recipient'=> ['name' => 'Bob Brown', 'email' => 'bob@example.com', 'phone' => '+1-555-0102', 'addr' => '456 Oak Ave', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'United States'],
                'weight' => 2.5, 'length' => 30, 'width' => 20, 'height' => 15,
                'status' => 'out_for_delivery', 'desc' => 'Express documents',
                'value' => 150.00, 'fragile' => 0, 'insured' => 1,
                'origin' => 'HQ-001', 'dest' => 'LA-001',
                'payment' => 'paid',
                'history' => ['order_received', 'picked_up', 'at_warehouse', 'in_transit', 'out_for_delivery'],
            ],
            [
                'tracking' => 'GDL-' . $this->genCode(),
                'customer' => 1, 'service' => 'international',
                'sender'   => ['name' => 'Global Traders Inc', 'email' => 'info@globaltraders.com', 'phone' => '+1-555-0201', 'addr' => '100 Trade Ctr', 'city' => 'Miami', 'state' => 'FL', 'country' => 'United States'],
                'recipient'=> ['name' => 'James Wilson', 'email' => 'james@londonoffice.co.uk', 'phone' => '+44-20-9001', 'addr' => '55 Thames St', 'city' => 'London', 'state' => '', 'country' => 'United Kingdom'],
                'weight' => 15.0, 'length' => 60, 'width' => 40, 'height' => 30,
                'status' => 'in_transit', 'desc' => 'Commercial samples',
                'value' => 2500.00, 'fragile' => 1, 'insured' => 1,
                'origin' => 'MIA-01', 'dest' => 'LDN-01',
                'payment' => 'paid',
                'history' => ['order_received', 'picked_up', 'at_warehouse', 'in_transit'],
            ],
            [
                'tracking' => 'GDL-' . $this->genCode(),
                'customer' => 2, 'service' => 'freight',
                'sender'   => ['name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'phone' => '+1-555-0103', 'addr' => '789 Pine Rd', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'United States'],
                'recipient'=> ['name' => 'Ahmed Hassan', 'email' => 'ahmed@dubaibuyer.ae', 'phone' => '+971-50-1001', 'addr' => 'Jebel Ali Free Zone', 'city' => 'Dubai', 'state' => '', 'country' => 'United Arab Emirates'],
                'weight' => 450.0, 'length' => 120, 'width' => 100, 'height' => 80,
                'status' => 'customs_clearance', 'desc' => 'Industrial equipment',
                'value' => 15000.00, 'fragile' => 1, 'insured' => 1,
                'origin' => 'CHI-01', 'dest' => 'DXB-01',
                'payment' => 'paid',
                'history' => ['order_received', 'picked_up', 'at_warehouse', 'in_transit', 'customs_clearance'],
            ],
            [
                'tracking' => 'GDL-' . $this->genCode(),
                'customer' => 0, 'service' => 'same_day',
                'sender'   => ['name' => 'Diana Evans', 'email' => 'diana@example.com', 'phone' => '+44-20-1234', 'addr' => '1 London Rd', 'city' => 'London', 'state' => '', 'country' => 'United Kingdom'],
                'recipient'=> ['name' => 'Sarah Johnson', 'email' => 'sarah@globaldelivered.com', 'phone' => '+1-555-0003', 'addr' => '1 Logistics Ave', 'city' => 'New York', 'state' => 'NY', 'country' => 'United States'],
                'weight' => 0.5, 'length' => 25, 'width' => 18, 'height' => 2,
                'status' => 'delivered', 'desc' => 'Urgent contract documents',
                'value' => 500.00, 'fragile' => 0, 'insured' => 0,
                'origin' => 'LDN-01', 'dest' => 'HQ-001',
                'payment' => 'paid',
                'history' => ['order_received', 'picked_up', 'at_warehouse', 'in_transit', 'out_for_delivery', 'delivered'],
            ],
            [
                'tracking' => 'GDL-' . $this->genCode(),
                'customer' => 3, 'service' => 'air_cargo',
                'sender'   => ['name' => 'Bob Brown', 'email' => 'bob@example.com', 'phone' => '+1-555-0102', 'addr' => '456 Oak Ave', 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'United States'],
                'recipient'=> ['name' => 'Carlos Garcia', 'email' => 'carlos@example.es', 'phone' => '+34-91-555', 'addr' => 'Calle Mayor 10', 'city' => 'Madrid', 'state' => '', 'country' => 'Spain'],
                'weight' => 8.0, 'length' => 50, 'width' => 40, 'height' => 35,
                'status' => 'order_received', 'desc' => 'Electronics shipment',
                'value' => 3200.00, 'fragile' => 1, 'insured' => 1,
                'origin' => 'LA-001', 'dest' => 'HQ-001',
                'payment' => 'pending',
                'history' => ['order_received'],
            ],
            [
                'tracking' => 'GDL-' . $this->genCode(),
                'customer' => 0, 'service' => 'domestic',
                'sender'   => ['name' => 'Emma Jones', 'email' => 'emma.jones@gdl.com', 'phone' => '+1-555-2002', 'addr' => '200 Broadway', 'city' => 'New York', 'state' => 'NY', 'country' => 'United States'],
                'recipient'=> ['name' => 'Mike Smith', 'email' => 'mike.smith@gdl.com', 'phone' => '+1-555-2001', 'addr' => '800 Industrial Dr', 'city' => 'Chicago', 'state' => 'IL', 'country' => 'United States'],
                'weight' => 3.0, 'length' => 35, 'width' => 25, 'height' => 20,
                'status' => 'delivered', 'desc' => 'Inter-office documents & samples',
                'value' => 0, 'fragile' => 0, 'insured' => 0,
                'origin' => 'NY-001', 'dest' => 'CHI-01',
                'payment' => 'paid',
                'history' => ['order_received', 'picked_up', 'at_warehouse', 'in_transit', 'out_for_delivery', 'delivered'],
            ],
        ];

        $customerIds = array_map(fn($c) => $c->id, $customers);
        $branchMap = [];
        foreach ($branches as $b) {
            $branchMap[$b->code] = $b->id;
        }
        $driverIds = array_map(fn($d) => $d->id, $drivers);

        $insertedCount = 0;

        foreach ($shipments as $s) {
            $customerId = $customerIds[$s['customer']] ?? null;
            $originId = $branchMap[$s['origin']] ?? null;
            $destId = $branchMap[$s['dest']] ?? null;
            $driverId = $driverIds[array_rand($driverIds)] ?? null;
            $vehicleId = $vehicles[array_rand($vehicles)]->id ?? null;
            $statusId = $statusMap[$s['status']] ?? null;

            if (!$statusId) continue;

            $charges = $s['weight'] * 5 + ($s['value'] > 0 ? $s['value'] * 0.01 : 0);
            $tax = $charges * 0.08;
            $total = $charges + $tax;

            try {
                $this->pdo->beginTransaction();

                $stmt = $this->pdo->prepare(
                    "INSERT IGNORE INTO shipments (tracking_number, customer_id, sender_name, sender_email, sender_phone, sender_address, sender_city, sender_state, sender_country, recipient_name, recipient_email, recipient_phone, recipient_address, recipient_city, recipient_state, recipient_country, origin_branch_id, destination_branch_id, assigned_driver_id, assigned_vehicle_id, service_type, weight, length, width, height, description, declared_value, is_fragile, is_insured, current_status_id, total_charges, tax_amount, grand_total, currency, payment_status, status, is_active, created_by, created_at)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'USD', ?, ?, 1, ?, NOW())"
                );

                $stmt->execute([
                    $s['tracking'], $customerId,
                    $s['sender']['name'], $s['sender']['email'], $s['sender']['phone'],
                    $s['sender']['addr'], $s['sender']['city'], $s['sender']['state'], $s['sender']['country'],
                    $s['recipient']['name'], $s['recipient']['email'], $s['recipient']['phone'],
                    $s['recipient']['addr'], $s['recipient']['city'], $s['recipient']['state'], $s['recipient']['country'],
                    $originId, $destId, $driverId, $vehicleId,
                    $s['service'], $s['weight'], $s['length'], $s['width'], $s['height'],
                    $s['desc'], $s['value'], $s['fragile'], $s['insured'],
                    $statusId, $charges, $tax, $total, $s['payment'], $s['status'] === 'delivered' ? 'delivered' : ($s['status'] === 'order_received' ? 'pending' : 'active'),
                    $adminId,
                ]);

                $shipmentId = $this->pdo->lastInsertId();

                // Create tracking history entries
                foreach ($s['history'] as $idx => $statusSlug) {
                    $hStatusId = $statusMap[$statusSlug] ?? null;
                    if (!$hStatusId) continue;

                    $hoursAgo = (count($s['history']) - $idx) * random_int(4, 12);
                    $createdAt = date('Y-m-d H:i:s', time() - $hoursAgo * 3600);

                    $descriptions = [
                        'order_received' => 'Shipment created and order confirmed',
                        'picked_up' => 'Package collected from sender',
                        'at_warehouse' => 'Package arrived at sorting facility',
                        'in_transit' => 'Package is in transit to destination',
                        'customs_clearance' => 'Package undergoing customs clearance',
                        'out_for_delivery' => 'Package is out for delivery',
                        'delivered' => 'Package delivered successfully',
                    ];

                    $this->pdo->prepare(
                        "INSERT INTO tracking_history (shipment_id, status_id, location, description, updated_by, source, created_at)
                         VALUES (?, ?, ?, ?, ?, 'system', ?)"
                    )->execute([
                        $shipmentId, $hStatusId,
                        $idx < 2 ? $s['sender']['city'] : ($idx >= count($s['history']) - 2 ? $s['recipient']['city'] : 'In transit'),
                        $descriptions[$statusSlug] ?? "Status updated to {$statusSlug}",
                        $adminId,
                        $createdAt,
                    ]);
                }

                // If delivered, set actual delivery date
                if ($s['status'] === 'delivered') {
                    $lastHistoryTime = date('Y-m-d H:i:s', time() - random_int(1, 6) * 3600);
                    $this->pdo->prepare("UPDATE shipments SET actual_delivery_date = ?, last_scan_at = ? WHERE id = ?")
                        ->execute([$lastHistoryTime, $lastHistoryTime, $shipmentId]);
                }

                $this->pdo->commit();
                $insertedCount++;

            } catch (\PDOException $e) {
                $this->pdo->rollBack();
                echo "  ⚠ Shipment {$s['tracking']} skipped: " . $e->getMessage() . "\n";
            }
        }

        echo "  Sample shipments: {$insertedCount} inserted with tracking history.\n";
    }

    private function genCode(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < 12; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }
}
