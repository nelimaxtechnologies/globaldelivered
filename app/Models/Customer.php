<?php
/**
 * Global Delivered Logistics - Customer Model
 */

namespace App\Models;

use App\Core\Model;

class Customer extends Model
{
    protected static string $table = 'customers';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [
        'user_id', 'customer_type', 'company_name', 'company_registration',
        'first_name', 'last_name', 'email', 'phone', 'alternative_phone',
        'whatsapp', 'address_line1', 'address_line2', 'city', 'state',
        'country', 'postal_code', 'notes', 'is_active', 'email_verified_at'
    ];
    protected static array $searchable = [
        'first_name', 'last_name', 'email', 'phone', 'company_name'
    ];

    /**
     * Get full name
     */
    public function fullName(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get shipments for this customer
     */
    public function shipments(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM shipments WHERE customer_id = ? AND deleted_at IS NULL ORDER BY created_at DESC",
            [$this->id]
        );
    }

    /**
     * Get addresses
     */
    public function addresses(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM customer_addresses WHERE customer_id = ? ORDER BY is_default DESC, created_at DESC",
            [$this->id]
        );
    }

    /**
     * Get invoices
     */
    public function invoices(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM invoices WHERE customer_id = ? ORDER BY created_at DESC",
            [$this->id]
        );
    }

    /**
     * Get total spent
     */
    public function totalSpent(): float
    {
        $db = \App\Core\Database::getInstance();
        return (float) $db->fetchColumn(
            "SELECT COALESCE(SUM(grand_total), 0) FROM shipments WHERE customer_id = ? AND payment_status = 'paid' AND deleted_at IS NULL",
            [$this->id]
        );
    }
}
