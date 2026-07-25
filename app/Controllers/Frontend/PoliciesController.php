<?php
/**
 * Global Delivered Logistics - Policies Controller
 */

namespace App\Controllers\Frontend;

use App\Core\Controller;

class PoliciesController extends Controller
{
    public function privacy(): void
    {
        $this->view('frontend/policies/privacy', [
            'pageTitle' => 'Privacy Policy - Global Delivered Logistics',
        ]);
    }

    public function terms(): void
    {
        $this->view('frontend/policies/terms', [
            'pageTitle' => 'Terms of Service - Global Delivered Logistics',
        ]);
    }

    public function cookies(): void
    {
        $this->view('frontend/policies/cookies', [
            'pageTitle' => 'Cookie Policy - Global Delivered Logistics',
        ]);
    }
}
