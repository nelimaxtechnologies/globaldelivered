<?php
/**
 * Global Delivered Logistics - Role & Permission Seeder
 */

namespace Database\Seeders;

class RoleSeeder
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        // Roles
        $roles = [
            ['name' => 'Super Admin',    'slug' => 'super_admin',     'description' => 'Full system access',              'is_system' => 1],
            ['name' => 'Admin',          'slug' => 'admin',           'description' => 'Administrative access',            'is_system' => 1],
            ['name' => 'Branch Manager', 'slug' => 'branch_manager',  'description' => 'Branch-level management',          'is_system' => 1],
            ['name' => 'Warehouse Manager', 'slug' => 'warehouse_manager', 'description' => 'Warehouse operations',         'is_system' => 1],
            ['name' => 'Driver',         'slug' => 'driver',          'description' => 'Delivery driver',                 'is_system' => 1],
            ['name' => 'Customer',       'slug' => 'customer',        'description' => 'Registered customer',             'is_system' => 1],
        ];

        $stmt = $this->pdo->prepare(
            "INSERT IGNORE INTO roles (name, slug, description, is_system, created_at) VALUES (?, ?, ?, ?, NOW())"
        );

        foreach ($roles as $r) {
            $stmt->execute([$r['name'], $r['slug'], $r['description'], $r['is_system']]);
        }

        echo "  Roles: " . count($roles) . " inserted.\n";
    }
}
