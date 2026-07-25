<?php
/**
 * Global Delivered Logistics - Frontend Home Controller
 */

namespace App\Controllers\Frontend;

use App\Core\Controller;

class HomeController extends Controller
{
    /**
     * Display home page
     */
    public function index(): void
    {
        $db = \App\Core\Database::getInstance();
        
        // Get statistics
        $stats = (object) [
            'shipments' => 50000,
            'countries' => 200,
            'customers' => 10000,
            'drivers' => 500,
        ];
        
        // Get recent news/testimonials from settings or static data
        $testimonials = [
            (object) [
                'name' => 'Sarah Johnson',
                'company' => 'TechCorp International',
                'text' => 'Global Delivered Logistics transformed our supply chain. Their real-time tracking and reliable delivery have been game-changing for our business.',
                'rating' => 5,
            ],
            (object) [
                'name' => 'James Okafor',
                'company' => 'MedEquip Africa',
                'text' => 'We ship medical equipment across 15 countries. GDL\'s temperature-controlled logistics and customs handling are absolutely world-class.',
                'rating' => 5,
            ],
            (object) [
                'name' => 'Maria Schmidt',
                'company' => 'Bavarian Auto Parts',
                'text' => 'The express shipping from Germany to the US is incredible. Packages arrive in 2-3 days with full tracking. Highly recommended!',
                'rating' => 5,
            ],
            (object) [
                'name' => 'David Chen',
                'company' => 'Pacific Trade Co.',
                'text' => 'Their sea freight rates are competitive, and the dashboard makes it incredibly easy to manage all our shipments in one place.',
                'rating' => 4,
            ],
            (object) [
                'name' => 'Amina Hassan',
                'company' => 'Nairobi Exports Ltd',
                'text' => 'We export fresh produce to Europe and GDL handles our cold chain logistics perfectly. Every shipment arrives on time and in perfect condition.',
                'rating' => 5,
            ],
            (object) [
                'name' => 'Robert Williams',
                'company' => 'Skyline Retail Group',
                'text' => 'From warehouses in 3 countries, GDL manages all our inventory and distribution. Their platform gives us complete visibility across the board.',
                'rating' => 5,
            ],
            (object) [
                'name' => 'Priya Patel',
                'company' => 'Mumbai Textiles',
                'text' => 'Switching to GDL reduced our shipping costs by 30% while improving delivery times. Their customer support team is always responsive and helpful.',
                'rating' => 5,
            ],
            (object) [
                'name' => 'Carlos Rodriguez',
                'company' => 'LatAm Logistics Partners',
                'text' => 'The API integration was seamless. We connected our ERP system in under a day and now automate all our shipment bookings and tracking.',
                'rating' => 4,
            ],
            (object) [
                'name' => 'Emma Thompson',
                'company' => 'GreenEarth Supplies',
                'text' => 'Sustainability matters to us and GDL shares that commitment. Their carbon-neutral shipping option helped us meet our environmental goals.',
                'rating' => 5,
            ],
        ];
        
        $this->view('frontend/home', [
            'pageTitle' => 'Global Delivered Logistics - Worldwide Shipping & Courier Services',
            'metaDescription' => 'Global Delivered Logistics provides worldwide shipping, courier, freight, and logistics services. Real-time tracking, competitive rates, and enterprise-grade delivery solutions.',
            'stats' => $stats,
            'testimonials' => $testimonials,
        ]);
    }

    /**
     * About page
     */
    public function about(): void
    {
        $this->view('frontend/about', [
            'pageTitle' => 'About Us - Global Delivered Logistics',
        ]);
    }

    /**
     * FAQ page
     */
    public function faq(): void
    {
        $this->view('frontend/faq', [
            'pageTitle' => 'FAQ - Global Delivered Logistics',
        ]);
    }

    /**
     * Generate sitemap.xml
     */
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $pages = ['', 'tracking', 'services', 'quote', 'contact', 'about', 'faq', 'login', 'register'];
        foreach ($pages as $page) {
            echo '<url>';
            echo '<loc>' . BASE_URL . '/' . $page . '</loc>';
            echo '<changefreq>daily</changefreq>';
            echo '<priority>' . ($page === '' ? '1.0' : '0.8') . '</priority>';
            echo '</url>';
        }
        
        echo '</urlset>';
        exit;
    }

    /**
     * Generate robots.txt
     */
    public function robots(): void
    {
        header('Content-Type: text/plain');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /dashboard/\n";
        echo "Sitemap: " . BASE_URL . "/sitemap.xml\n";
        exit;
    }
}
