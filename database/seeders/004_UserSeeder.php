<?php
/**
 * Global Delivered Logistics - User & Customer Seeder
 */

namespace Database\Seeders;

class UserSeeder
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        // Get role IDs
        $roles = [];
        $rs = $this->pdo->query("SELECT id, slug FROM roles")->fetchAll(\PDO::FETCH_OBJ);
        foreach ($rs as $r) {
            $roles[$r->slug] = $r->id;
        }

        if (empty($roles)) {
            echo "  ⚠ No roles found. Run RoleSeeder first.\n";
            return;
        }

        // --- Admin User (password: admin123) ---
        $adminHash = password_hash('admin123', PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare(
            "INSERT IGNORE INTO users (role_id, first_name, last_name, email, phone, password, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, 1, NOW())"
        );

        $users = [
            ['role' => 'super_admin', 'first' => 'System',     'last' => 'Admin',     'email' => 'admin@globaldelivered.com',     'phone' => '+1-555-0001'],
            ['role' => 'admin',       'first' => 'John',       'last' => 'Doe',       'email' => 'john@globaldelivered.com',      'phone' => '+1-555-0002'],
            ['role' => 'admin',       'first' => 'Sarah',      'last' => 'Johnson',   'email' => 'sarah@globaldelivered.com',     'phone' => '+1-555-0003'],
            ['role' => 'customer',    'first' => 'Alice',      'last' => 'Williams',  'email' => 'alice@example.com',             'phone' => '+1-555-0101'],
            ['role' => 'customer',    'first' => 'Bob',        'last' => 'Brown',     'email' => 'bob@example.com',               'phone' => '+1-555-0102'],
            ['role' => 'customer',    'first' => 'Charlie',    'last' => 'Davis',     'email' => 'charlie@example.com',           'phone' => '+1-555-0103'],
        ];

        foreach ($users as $u) {
            $rid = $roles[$u['role']] ?? null;
            if ($rid) {
                $stmt->execute([$rid, $u['first'], $u['last'], $u['email'], $u['phone'], $adminHash]);
            }
        }

        echo "  Users: " . count($users) . " inserted (password: admin123).\n";

        // --- Customers (linked to users where applicable) ---
        $userIds = [];
        $us = $this->pdo->query("SELECT id, email FROM users")->fetchAll(\PDO::FETCH_OBJ);
        foreach ($us as $u) {
            $userIds[$u->email] = $u->id;
        }

        $customers = [
            ['user' => 'alice@example.com',     'type' => 'individual', 'first' => 'Alice',    'last' => 'Williams', 'email' => 'alice@example.com',      'phone' => '+1-555-0101', 'addr' => '123 Main St',   'city' => 'New York',     'state' => 'NY', 'country' => 'United States'],
            ['user' => 'bob@example.com',       'type' => 'individual', 'first' => 'Bob',      'last' => 'Brown',    'email' => 'bob@example.com',       'phone' => '+1-555-0102', 'addr' => '456 Oak Ave',   'city' => 'Los Angeles',  'state' => 'CA', 'country' => 'United States'],
            ['user' => 'charlie@example.com',   'type' => 'company',    'first' => 'Charlie',  'last' => 'Davis',    'email' => 'charlie@example.com',   'phone' => '+1-555-0103', 'addr' => '789 Pine Rd',   'city' => 'Chicago',      'state' => 'IL', 'country' => 'United States'],
            ['user' => '',                      'type' => 'company',    'first' => 'Global',   'last' => 'Traders',  'email' => 'info@globaltraders.com', 'phone' => '+1-555-0201', 'addr' => '100 Trade Ctr', 'city' => 'Miami',        'state' => 'FL', 'country' => 'United States'],
            ['user' => '',                      'type' => 'individual', 'first' => 'Diana',    'last' => 'Evans',    'email' => 'diana@example.com',      'phone' => '+44-20-1234',  'addr' => '1 London Rd',   'city' => 'London',      'state' => '',   'country' => 'United Kingdom'],
        ];

        $cstmt = $this->pdo->prepare(
            "INSERT IGNORE INTO customers (user_id, customer_type, company_name, first_name, last_name, email, phone, address_line1, city, state, country, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())"
        );

        foreach ($customers as $c) {
            $uid = !empty($c['user']) && isset($userIds[$c['user']]) ? $userIds[$c['user']] : null;
            $company = $c['type'] === 'company' ? $c['first'] . ' ' . $c['last'] : '';
            $cstmt->execute([
                $uid, $c['type'], $company, $c['first'], $c['last'],
                $c['email'], $c['phone'], $c['addr'], $c['city'], $c['state'], $c['country']
            ]);
        }

        echo "  Customers: " . count($customers) . " inserted.\n";
    }
}
