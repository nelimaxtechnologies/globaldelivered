<?php
/**
 * Global Delivered Logistics - Quote Calculator Controller
 */

namespace App\Controllers\Frontend;

use App\Core\Controller;

class QuoteController extends Controller
{
    /**
     * Display quote calculator
     */
    public function index(): void
    {
        $countries = $this->db->fetchAll("SELECT * FROM countries WHERE is_active = 1 ORDER BY name ASC");
        
        $this->view('frontend/quote', [
            'pageTitle' => 'Get a Quote - Global Delivered Logistics',
            'countries' => $countries,
            'metaDescription' => 'Calculate shipping costs instantly. Get competitive rates for domestic and international shipping.',
        ]);
    }

    /**
     * Calculate shipping quote (AJAX)
     */
    public function calculate(): void
    {
        $data = $this->getPostData();
        
        $rules = [
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|numeric|min:0.1',
            'package_type' => 'required',
        ];
        
        $validated = $this->validate($data, $rules);
        
        $weight = (float) $data['weight'];
        $length = (float) ($data['length'] ?? 0);
        $width = (float) ($data['width'] ?? 0);
        $height = (float) ($data['height'] ?? 0);
        $serviceType = sanitize($data['service_type'] ?? 'express');
        $isInsured = !empty($data['insurance']);
        $declaredValue = (float) ($data['declared_value'] ?? 0);
        $isPriority = !empty($data['priority']);
        
        // Calculate dimensional weight (DIM factor: 5000)
        $dimWeight = ($length * $width * $height) / 5000;
        $chargeableWeight = max($weight, $dimWeight);
        
        // Base rates by service type
        $rates = [
            'domestic' => ['base' => 5.00, 'per_kg' => 2.50, 'min_days' => 1, 'max_days' => 5],
            'international' => ['base' => 15.00, 'per_kg' => 8.00, 'min_days' => 3, 'max_days' => 10],
            'express' => ['base' => 12.00, 'per_kg' => 5.00, 'min_days' => 1, 'max_days' => 3],
            'same_day' => ['base' => 25.00, 'per_kg' => 3.00, 'min_days' => 0, 'max_days' => 0],
            'freight' => ['base' => 50.00, 'per_kg' => 1.50, 'min_days' => 3, 'max_days' => 14],
            'air_cargo' => ['base' => 35.00, 'per_kg' => 6.00, 'min_days' => 2, 'max_days' => 7],
            'sea_freight' => ['base' => 100.00, 'per_kg' => 0.50, 'min_days' => 10, 'max_days' => 40],
            'road_transport' => ['base' => 20.00, 'per_kg' => 1.00, 'min_days' => 3, 'max_days' => 14],
            'last_mile' => ['base' => 8.00, 'per_kg' => 2.00, 'min_days' => 0, 'max_days' => 1],
        ];
        
        $rate = $rates[$serviceType] ?? $rates['express'];
        
        // Calculate price
        $baseRate = $rate['base'];
        $perKgRate = $rate['per_kg'];
        
        $subtotal = $baseRate + ($perKgRate * $chargeableWeight);
        
        // Volume surcharge if applicable
        if ($dimWeight > $weight) {
            $subtotal *= 1.1; // 10% surcharge for oversized
        }
        
        // Priority surcharge
        if ($isPriority) {
            $subtotal *= 1.25; // 25% priority surcharge
        }
        
        // Insurance
        $insuranceAmount = 0;
        if ($isInsured && $declaredValue > 0) {
            $insuranceAmount = $declaredValue * 0.01; // 1% of declared value
            $subtotal += $insuranceAmount;
        }
        
        // Tax (e.g., 8%)
        $taxPercentage = 8;
        $taxAmount = $subtotal * ($taxPercentage / 100);
        $total = $subtotal + $taxAmount;
        
        // Transit time
        $transitDays = $rate['min_days'] . '-' . $rate['max_days'] . ' business days';
        if ($serviceType === 'same_day') {
            $transitDays = 'Same day';
        } elseif ($serviceType === 'last_mile') {
            $transitDays = '1 business day';
        }
        
        $this->success([
            'breakdown' => [
                'base_rate' => round($baseRate, 2),
                'weight_charge' => round($perKgRate * $chargeableWeight, 2),
                'chargeable_weight' => round($chargeableWeight, 2),
                'volume_surcharge' => $dimWeight > $weight,
                'priority_surcharge' => $isPriority,
                'insurance' => round($insuranceAmount, 2),
                'subtotal' => round($subtotal, 2),
                'tax_percentage' => $taxPercentage,
                'tax_amount' => round($taxAmount, 2),
                'total' => round($total, 2),
            ],
            'transit_time' => $transitDays,
            'service_type' => $serviceType,
            'weight' => $weight,
        ]);
    }
}
