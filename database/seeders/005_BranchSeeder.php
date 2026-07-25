<?php
/**
 * Global Delivered Logistics - Branch & Warehouse Seeder
 */

namespace Database\Seeders;

class BranchSeeder
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        // Get the super_admin user to assign as manager
        $admin = $this->pdo->query(
            "SELECT id FROM users WHERE email = 'admin@globaldelivered.com' LIMIT 1"
        )->fetch(\PDO::FETCH_OBJ);
        $adminId = $admin ? $admin->id : null;

        // --- Branches ---
        $branches = [
            ['name' => 'Global Delivered HQ',          'slug' => 'global-delivered-hq',    'code' => 'HQ-001', 'type' => 'head_office', 'addr' => '1 Logistics Ave',     'city' => 'New York',     'state' => 'NY', 'country' => 'United States',   'phone' => '+1-555-1001', 'lat' => 40.7128,  'lng' => -74.0060],
            ['name' => 'New York Regional Hub',         'slug' => 'ny-regional-hub',        'code' => 'NY-001', 'type' => 'regional',    'addr' => '200 Broadway',         'city' => 'New York',     'state' => 'NY', 'country' => 'United States',   'phone' => '+1-555-1002', 'lat' => 40.7580,  'lng' => -73.9855],
            ['name' => 'Los Angeles Distribution Center','slug' => 'la-distribution-center','code' => 'LA-001', 'type' => 'regional',    'addr' => '500 Wilshire Blvd',     'city' => 'Los Angeles',  'state' => 'CA', 'country' => 'United States',   'phone' => '+1-555-1003', 'lat' => 34.0522,  'lng' => -118.2437],
            ['name' => 'London International Hub',      'slug' => 'london-intl-hub',        'code' => 'LDN-01', 'type' => 'regional',    'addr' => '10 Heathrow Business Park', 'city' => 'London',    'state' => '',   'country' => 'United Kingdom', 'phone' => '+44-20-8001', 'lat' => 51.5074, 'lng' => -0.1278],
            ['name' => 'Dubai Gateway',                 'slug' => 'dubai-gateway',           'code' => 'DXB-01', 'type' => 'regional',    'addr' => 'Jebel Ali Free Zone',    'city' => 'Dubai',       'state' => '',   'country' => 'United Arab Emirates', 'phone' => '+971-4-555', 'lat' => 25.2048, 'lng' => 55.2708],
            ['name' => 'Miami Freight Terminal',        'slug' => 'miami-freight',           'code' => 'MIA-01', 'type' => 'local',       'addr' => '300 Port Blvd',          'city' => 'Miami',       'state' => 'FL', 'country' => 'United States',   'phone' => '+1-555-1004', 'lat' => 25.7617, 'lng' => -80.1918],
            ['name' => 'Chicago Sorting Facility',      'slug' => 'chicago-sorting',         'code' => 'CHI-01', 'type' => 'local',       'addr' => '800 Industrial Dr',      'city' => 'Chicago',     'state' => 'IL', 'country' => 'United States',   'phone' => '+1-555-1005', 'lat' => 41.8781, 'lng' => -87.6298],
        ];

        $bstmt = $this->pdo->prepare(
            "INSERT IGNORE INTO branches (name, slug, code, branch_type, manager_id, address_line1, city, state, country, phone, latitude, longitude, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())"
        );

        foreach ($branches as $b) {
            $bstmt->execute([$b['name'], $b['slug'], $b['code'], $b['type'], $adminId, $b['addr'], $b['city'], $b['state'], $b['country'], $b['phone'], $b['lat'], $b['lng']]);
        }

        echo "  Branches: " . count($branches) . " inserted.\n";

        // --- Warehouses ---
        $branchIds = [];
        $bs = $this->pdo->query("SELECT id, slug FROM branches")->fetchAll(\PDO::FETCH_OBJ);
        foreach ($bs as $b) {
            $branchIds[$b->slug] = $b->id;
        }

        $warehouses = [
            ['branch' => 'global-delivered-hq',     'name' => 'HQ Main Warehouse',     'code' => 'WH-HQ-01', 'city' => 'New York',     'state' => 'NY', 'country' => 'United States',   'capacity' => 15000, 'temp' => 1],
            ['branch' => 'ny-regional-hub',          'name' => 'NY Sorting Center',     'code' => 'WH-NY-01', 'city' => 'New York',     'state' => 'NY', 'country' => 'United States',   'capacity' => 8000,  'temp' => 0],
            ['branch' => 'la-distribution-center',   'name' => 'LA Distribution Hub',   'code' => 'WH-LA-01', 'city' => 'Los Angeles',  'state' => 'CA', 'country' => 'United States',   'capacity' => 12000, 'temp' => 0],
            ['branch' => 'london-intl-hub',           'name' => 'London Cargo Center',   'code' => 'WH-LDN-01','city' => 'London',       'state' => '',   'country' => 'United Kingdom', 'capacity' => 10000, 'temp' => 1],
            ['branch' => 'dubai-gateway',             'name' => 'Dubai Freight Hub',     'code' => 'WH-DXB-01','city' => 'Dubai',        'state' => '',   'country' => 'United Arab Emirates', 'capacity' => 20000, 'temp' => 1],
        ];

        $wstmt = $this->pdo->prepare(
            "INSERT IGNORE INTO warehouses (branch_id, name, code, manager_id, address_line1, city, state, country, capacity, temperature_controlled, is_active, created_at)
             VALUES (?, ?, ?, ?, CONCAT('Warehouse facility, ', ?), ?, ?, ?, ?, ?, 1, NOW())"
        );

        foreach ($warehouses as $w) {
            $bid = $branchIds[$w['branch']] ?? null;
            if ($bid) {
                $wstmt->execute([$bid, $w['name'], $w['code'], $adminId, $w['city'], $w['city'], $w['state'], $w['country'], $w['capacity'], $w['temp']]);
            }
        }

        echo "  Warehouses: " . count($warehouses) . " inserted.\n";
    }
}
