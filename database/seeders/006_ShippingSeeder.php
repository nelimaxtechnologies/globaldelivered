<?php
/**
 * Global Delivered Logistics - Drivers, Vehicles & Sample Shipments Seeder
 */

namespace Database\Seeders;

class ShippingSeeder
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $branchIds = [];
        $bs = $this->pdo->query("SELECT id, code FROM branches")->fetchAll(\PDO::FETCH_OBJ);
        foreach ($bs as $b) {
            $branchIds[$b->code] = $b->id;
        }

        if (empty($branchIds)) {
            echo "  ⚠ No branches found. Run BranchSeeder first.\n";
            return;
        }

        // --- Vehicles ---
        $vehicles = [
            ['branch' => 'NY-001', 'type' => 'van',     'name' => 'Ford Transit 101',    'reg' => 'NY-VAN-101',  'make' => 'Ford',     'capacity' => 1200],
            ['branch' => 'NY-001', 'type' => 'van',     'name' => 'Ford Transit 102',    'reg' => 'NY-VAN-102',  'make' => 'Ford',     'capacity' => 1200],
            ['branch' => 'LA-001', 'type' => 'truck',   'name' => 'Volvo FH16 201',      'reg' => 'LA-TRK-201',  'make' => 'Volvo',    'capacity' => 18000],
            ['branch' => 'LA-001', 'type' => 'truck',   'name' => 'Volvo FH16 202',      'reg' => 'LA-TRK-202',  'make' => 'Volvo',    'capacity' => 18000],
            ['branch' => 'LDN-01', 'type' => 'van',     'name' => 'Mercedes Sprinter L1', 'reg' => 'LDN-VAN-01', 'make' => 'Mercedes', 'capacity' => 1000],
            ['branch' => 'DXB-01', 'type' => 'truck',   'name' => 'Scania R-Series D1',  'reg' => 'DXB-TRK-01',  'make' => 'Scania',   'capacity' => 22000],
            ['branch' => 'MIA-01', 'type' => 'container','name' => 'Container Carrier C1','reg' => 'MIA-CON-01',  'make' => 'Maersk',   'capacity' => 30000],
            ['branch' => 'CHI-01', 'type' => 'van',     'name' => 'Ram ProMaster C1',    'reg' => 'CHI-VAN-01',  'make' => 'Ram',      'capacity' => 1100],
            ['branch' => 'HQ-001', 'type' => 'car',     'name' => 'Toyota Camry Fleet 1', 'reg' => 'HQ-CAR-01',   'make' => 'Toyota',   'capacity' => 400],
            ['branch' => 'HQ-001', 'type' => 'car',     'name' => 'Toyota Camry Fleet 2', 'reg' => 'HQ-CAR-02',   'make' => 'Toyota',   'capacity' => 400],
        ];

        $vstmt = $this->pdo->prepare(
            "INSERT IGNORE INTO vehicles (branch_id, vehicle_type, name, registration_number, make, model, capacity_weight, status, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'active', 1, NOW())"
        );

        foreach ($vehicles as $v) {
            $bid = $branchIds[$v['branch']] ?? null;
            if ($bid) {
                $vstmt->execute([$bid, $v['type'], $v['name'], $v['reg'], $v['make'], $v['type'], $v['capacity']]);
            }
        }

        echo "  Vehicles: " . count($vehicles) . " inserted.\n";

        // --- Drivers ---
        $vehicleIds = [];
        $vs = $this->pdo->query("SELECT id, registration_number FROM vehicles")->fetchAll(\PDO::FETCH_OBJ);
        foreach ($vs as $v) {
            $vehicleIds[$v->registration_number] = $v->id;
        }

        $drivers = [
            ['branch' => 'NY-001', 'first' => 'Mike',    'last' => 'Smith',  'email' => 'mike.smith@gdl.com',      'phone' => '+1-555-2001', 'license' => 'NY-DL-1001', 'vehicle' => 'NY-VAN-101'],
            ['branch' => 'NY-001', 'first' => 'Emma',    'last' => 'Jones',  'email' => 'emma.jones@gdl.com',      'phone' => '+1-555-2002', 'license' => 'NY-DL-1002', 'vehicle' => 'NY-VAN-102'],
            ['branch' => 'LA-001', 'first' => 'Carlos',  'last' => 'Garcia', 'email' => 'carlos.garcia@gdl.com',    'phone' => '+1-555-2003', 'license' => 'CA-DL-2001', 'vehicle' => 'LA-TRK-201'],
            ['branch' => 'LA-001', 'first' => 'David',   'last' => 'Kim',    'email' => 'david.kim@gdl.com',       'phone' => '+1-555-2004', 'license' => 'CA-DL-2002', 'vehicle' => 'LA-TRK-202'],
            ['branch' => 'LDN-01', 'first' => 'James',   'last' => 'Wilson', 'email' => 'james.wilson@gdl.com',    'phone' => '+44-20-9001', 'license' => 'UK-DL-3001', 'vehicle' => 'LDN-VAN-01'],
            ['branch' => 'DXB-01', 'first' => 'Ahmed',   'last' => 'Hassan', 'email' => 'ahmed.hassan@gdl.com',    'phone' => '+971-50-1001','license' => 'UAE-DL-4001','vehicle' => 'DXB-TRK-01'],
            ['branch' => 'MIA-01', 'first' => 'Jose',    'last' => 'Martinez','email' => 'jose.martinez@gdl.com',   'phone' => '+1-555-2005', 'license' => 'FL-DL-5001', 'vehicle' => 'MIA-CON-01'],
            ['branch' => 'CHI-01', 'first' => 'Amanda',  'last' => 'Taylor', 'email' => 'amanda.taylor@gdl.com',    'phone' => '+1-555-2006', 'license' => 'IL-DL-6001', 'vehicle' => 'CHI-VAN-01'],
        ];

        $dstmt = $this->pdo->prepare(
            "INSERT IGNORE INTO drivers (branch_id, first_name, last_name, email, phone, license_number, assigned_vehicle_id, status, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'available', 1, NOW())"
        );

        foreach ($drivers as $d) {
            $bid = $branchIds[$d['branch']] ?? null;
            $vid = isset($d['vehicle']) && isset($vehicleIds[$d['vehicle']]) ? $vehicleIds[$d['vehicle']] : null;
            if ($bid) {
                $dstmt->execute([$bid, $d['first'], $d['last'], $d['email'], $d['phone'], $d['license'], $vid]);
            }
        }

        echo "  Drivers: " . count($drivers) . " inserted.\n";
    }
}
