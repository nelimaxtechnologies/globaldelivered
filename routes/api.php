<?php
/**
 * Global Delivered Logistics - REST API Routes
 * 
 * Versioned API endpoints for mobile apps and third-party integrations.
 */

use App\Core\Router;

// ------------------------------------------------
// Public API Routes
// ------------------------------------------------
Router::group(['prefix' => 'api/v1'], function () {
    
    // Authentication
    Router::post('/auth/login', 'Api\\AuthController@login');
    Router::post('/auth/register', 'Api\\AuthController@register');
    Router::post('/auth/forgot-password', 'Api\\AuthController@forgotPassword');
    
    // Tracking (public)
    Router::get('/tracking/{number}', 'Api\\TrackingController@show');
    Router::get('/tracking/{number}/timeline', 'Api\\TrackingController@timeline');
    Router::get('/tracking/{number}/location', 'Api\\TrackingController@location');
    
    // Services & Quotes (public)
    Router::get('/services', 'Api\\TrackingController@services');
    Router::post('/quote/calculate', 'Api\\TrackingController@calculateQuote');
    
    // Branches (public)
    Router::get('/branches', 'Api\\TrackingController@branches');
    Router::get('/branches/{id}', 'Api\\TrackingController@branchDetail');
});

// ------------------------------------------------
// Authenticated API Routes
// ------------------------------------------------
Router::group(['prefix' => 'api/v1', 'middleware' => ['AuthMiddleware', 'RateLimitMiddleware']], function () {
    
    // User Profile
    Router::get('/user', 'Api\\AuthController@user');
    Router::put('/user', 'Api\\AuthController@updateProfile');
    Router::put('/user/password', 'Api\\AuthController@updatePassword');
    
    // Shipments
    Router::get('/shipments', 'Api\\TrackingController@userShipments');
    Router::post('/shipments', 'Api\\TrackingController@createShipment');
    Router::get('/shipments/{id}', 'Api\\TrackingController@shipmentDetail');
    
    // Driver endpoints
    Router::get('/driver/assignments', 'Api\\TrackingController@driverAssignments');
    Router::post('/driver/shipments/{id}/status', 'Api\\TrackingController@updateShipmentStatus');
    Router::post('/driver/location', 'Api\\TrackingController@updateLocation');
    
    // Documents
    Router::get('/documents', 'Api\\TrackingController@documents');
    Router::post('/documents/upload', 'Api\\TrackingController@uploadDocument');
});

// Webhook receiver (public, validated by signature)
Router::post('/api/webhook/payment', 'Api\\TrackingController@paymentWebhook');
Router::post('/api/webhook/tracking', 'Api\\TrackingController@trackingWebhook');
