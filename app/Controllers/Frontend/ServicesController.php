<?php
/**
 * Global Delivered Logistics - Services Controller
 */

namespace App\Controllers\Frontend;

use App\Core\Controller;

class ServicesController extends Controller
{
    private array $services = [
        'domestic' => [
            'title' => 'Domestic Shipping',
            'icon' => 'bi-building',
            'description' => 'Fast and reliable domestic shipping services across the country. Next-day and 2-day delivery options available.',
            'features' => ['Next-day delivery', 'Real-time tracking', 'Insurance options', 'Scheduled pickup', 'Online management'],
        ],
        'international' => [
            'title' => 'International Shipping',
            'icon' => 'bi-globe2',
            'description' => 'Global shipping solutions to over 200 countries and territories worldwide. Comprehensive customs clearance included.',
            'features' => ['200+ countries', 'Customs clearance', 'Door-to-door', 'Track & trace', 'Documentation support'],
        ],
        'express' => [
            'title' => 'Express Delivery',
            'icon' => 'bi-lightning',
            'description' => 'Time-critical express delivery services for urgent shipments. Guaranteed delivery windows available.',
            'features' => ['Guaranteed delivery', 'Priority handling', 'Same-day options', 'Real-time alerts', 'Dedicated support'],
        ],
        'same_day' => [
            'title' => 'Same Day Delivery',
            'icon' => 'bi-clock',
            'description' => 'Lightning-fast same-day delivery within major metropolitan areas. Perfect for urgent documents and parcels.',
            'features' => ['Same-day guarantee', 'Local coverage', 'Instant tracking', 'Proof of delivery', 'Mobile updates'],
        ],
        'freight' => [
            'title' => 'Freight Services',
            'icon' => 'bi-truck',
            'description' => 'Comprehensive freight solutions including LTL and FTL shipping. Ideal for palletized and bulk cargo.',
            'features' => ['LTL & FTL options', 'Palletized shipping', 'Bulk cargo', 'Warehousing', 'Distribution'],
        ],
        'air_cargo' => [
            'title' => 'Air Cargo',
            'icon' => 'bi-airplane',
            'description' => 'Premium air freight services with priority boarding and expedited customs processing for time-sensitive shipments.',
            'features' => ['Priority boarding', 'Fast customs', 'Global network', 'Temperature control', 'Real-time tracking'],
        ],
        'sea_freight' => [
            'title' => 'Sea Freight',
            'icon' => 'bi-ship',
            'description' => 'Cost-effective sea freight solutions for large shipments. FCL and LCL container shipping worldwide.',
            'features' => ['FCL & LCL containers', 'Port-to-port', 'Consolidation', 'Customs brokerage', 'Cargo insurance'],
        ],
        'road_transport' => [
            'title' => 'Road Transport',
            'icon' => 'bi-truck-front',
            'description' => 'Reliable road transport network connecting major cities and cross-border routes with full tracking.',
            'features' => ['Cross-border routes', 'Fleet tracking', 'Temperature control', 'Security escorts', 'Flexible scheduling'],
        ],
        'warehousing' => [
            'title' => 'Warehousing',
            'icon' => 'bi-boxes',
            'description' => 'Strategic warehousing solutions with inventory management, order fulfillment, and distribution services.',
            'features' => ['Secure storage', 'Inventory management', 'Order fulfillment', 'Pick & pack', 'Distribution'],
        ],
    ];

    /**
     * Display all services
     */
    public function index(): void
    {
        $this->view('frontend/services', [
            'pageTitle' => 'Our Services - Global Delivered Logistics',
            'services' => $this->services,
            'metaDescription' => 'Comprehensive logistics services including domestic, international, express, freight, air cargo, sea freight, and warehousing solutions.',
        ]);
    }

    /**
     * Display single service detail
     */
    public function show(string $type): void
    {
        if (!isset($this->services[$type])) {
            $this->view('frontend/services', [
                'pageTitle' => 'Services',
                'error' => 'Service not found.',
                'services' => $this->services,
            ]);
            return;
        }

        $service = $this->services[$type];
        $this->view('frontend/service_detail', [
            'pageTitle' => $service['title'] . ' - Global Delivered Logistics',
            'service' => $service,
            'serviceType' => $type,
        ]);
    }
}
