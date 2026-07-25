<?php
/**
 * Global Delivered Logistics - Frontend Web Routes
 */

use App\Core\Router;

// ------------------------------------------------
// Frontend Routes
// ------------------------------------------------
Router::get('/', 'Frontend\\HomeController@index');
Router::get('/home', 'Frontend\\HomeController@index');
Router::get('/tracking', 'Frontend\\TrackingController@index');
Router::post('/tracking/lookup', 'Frontend\\TrackingController@lookup');
Router::post('/tracking/subscribe', 'Frontend\\TrackingController@subscribe');
Router::post('/tracking/check-subscription', 'Frontend\\TrackingController@checkSubscription');
Router::get('/tracking/unsubscribe', 'Frontend\\TrackingController@unsubscribe');
Router::post('/tracking/unsubscribe', 'Frontend\\TrackingController@unsubscribe');
Router::get('/tracking/{number}', 'Frontend\\TrackingController@show');
Router::get('/services', 'Frontend\\ServicesController@index');
Router::get('/services/{type}', 'Frontend\\ServicesController@show');
Router::get('/quote', 'Frontend\\QuoteController@index');
Router::post('/quote/calculate', 'Frontend\\QuoteController@calculate');
Router::get('/contact', 'Frontend\\ContactController@index');
Router::post('/contact/send', 'Frontend\\ContactController@send');
Router::get('/about', 'Frontend\\HomeController@about');
Router::get('/faq', 'Frontend\\HomeController@faq');

// Policy Pages
Router::get('/privacy-policy', 'Frontend\\PoliciesController@privacy');
Router::get('/terms-of-service', 'Frontend\\PoliciesController@terms');
Router::get('/cookie-policy', 'Frontend\\PoliciesController@cookies');

// ------------------------------------------------
// Authentication Routes
// ------------------------------------------------
Router::get('/login', 'Frontend\\AuthController@loginForm');
Router::post('/login', 'Frontend\\AuthController@login');
Router::get('/register', 'Frontend\\AuthController@registerForm');
Router::post('/register', 'Frontend\\AuthController@register');
Router::get('/logout', 'Frontend\\AuthController@logout');
Router::get('/forgot-password', 'Frontend\\AuthController@forgotPassword');
Router::post('/forgot-password', 'Frontend\\AuthController@sendResetLink');

// ------------------------------------------------
// Customer Dashboard Routes
// ------------------------------------------------
Router::group(['prefix' => 'dashboard', 'middleware' => ['AuthMiddleware']], function () {
    Router::get('/', 'Frontend\\DashboardController@index');
    Router::get('/shipments', 'Frontend\\DashboardController@shipments');
    Router::get('/shipments/{id}', 'Frontend\\DashboardController@shipmentDetail');
    Router::get('/profile', 'Frontend\\DashboardController@profile');
    Router::post('/profile', 'Frontend\\DashboardController@updateProfile');
    Router::get('/addresses', 'Frontend\\DashboardController@addresses');
    Router::post('/addresses', 'Frontend\\DashboardController@storeAddress');
    Router::post('/addresses/{id}/delete', 'Frontend\\DashboardController@deleteAddress');
    Router::get('/invoices', 'Frontend\\DashboardController@invoices');
    Router::get('/invoices/{id}/download', 'Frontend\\DashboardController@downloadInvoice');
    Router::get('/shipments/{id}/label', 'Frontend\\DashboardController@printLabel');
    Router::get('/notifications', 'Frontend\\DashboardController@notifications');
});

// ------------------------------------------------
// Admin Routes
// ------------------------------------------------
Router::group(['prefix' => 'admin', 'middleware' => ['AuthMiddleware']], function () {
    // Dashboard
    Router::get('/', 'Admin\\DashboardController@index');
    Router::get('/dashboard', 'Admin\\DashboardController@index');
    Router::get('/dashboard/stats', 'Admin\\DashboardController@stats');
    Router::get('/dashboard/charts', 'Admin\\DashboardController@charts');
    
    // Shipments
    Router::resource('/shipments', 'Admin\\ShipmentController');
    Router::post('/shipments/{id}/status', 'Admin\\ShipmentController@updateStatus');
    Router::post('/shipments/{id}/assign-driver', 'Admin\\ShipmentController@assignDriver');
    Router::get('/shipments/{id}/label', 'Admin\\ShipmentController@printLabel');
    Router::get('/shipments/{id}/timeline', 'Admin\\ShipmentController@timeline');
    Router::post('/shipments/{id}/history/{historyId}/update', 'Admin\\ShipmentController@updateHistory');
    
    // Customers
    Router::resource('/customers', 'Admin\\CustomerController');
    
    // Drivers
    Router::resource('/drivers', 'Admin\\DriverController');
    
    // Vehicles
    Router::resource('/vehicles', 'Admin\\VehicleController');
    Router::post('/vehicles/{id}/toggle-status', 'Admin\\VehicleController@toggleStatus');
    
    // Branches
    Router::get('/branches/export', 'Admin\\BranchController@export');
    Router::post('/branches/{id}/toggle-active', 'Admin\\BranchController@toggleActive');
    Router::resource('/branches', 'Admin\\BranchController');
    
    // Warehouses
    Router::resource('/warehouses', 'Admin\\WarehouseController');
    Router::post('/warehouses/{id}/toggle-status', 'Admin\\WarehouseController@toggleStatus');
    
    // Invoices & Payments
    Router::get('/invoices/export', 'Admin\\InvoiceController@export');
    Router::get('/invoices/{id}/mark-sent', 'Admin\\InvoiceController@markSent');
    Router::get('/invoices/{id}/mark-paid', 'Admin\\InvoiceController@markPaid');
    Router::get('/invoices/{id}/mark-refunded', 'Admin\\InvoiceController@markRefunded');
    Router::resource('/invoices', 'Admin\\InvoiceController');
    Router::resource('/payments', 'Admin\\PaymentController');
    
    // Reports
    Router::get('/reports', 'Admin\\ReportController@index');
    Router::get('/reports/{type}', 'Admin\\ReportController@show');
    Router::post('/reports/export', 'Admin\\ReportController@export');
    
    // Notifications
    Router::get('/notifications', 'Admin\\NotificationController@index');
    Router::post('/notifications/send', 'Admin\\NotificationController@send');
    Router::post('/notifications/bulk-delete', 'Admin\\NotificationController@bulkDelete');
    Router::post('/notifications/bulk-mark-read', 'Admin\\NotificationController@bulkMarkRead');
    Router::post('/notifications/bulk-retry', 'Admin\\NotificationController@bulkRetry');
    Router::post('/notifications/mark-all-read', 'Admin\\NotificationController@markAllRead');
    Router::get('/notifications/queue', 'Admin\\NotificationController@queue');
    Router::get('/notifications/templates', 'Admin\\NotificationController@templates');
    Router::get('/notifications/recipients', 'Admin\\NotificationController@recipients');
    Router::get('/notifications/email/{id}/retry', 'Admin\\NotificationController@retryEmail');
    Router::get('/notifications/sms/{id}/retry', 'Admin\\NotificationController@retrySms');
    Router::post('/notifications/{id}/read', 'Admin\\NotificationController@markRead');
    Router::post('/notifications/{id}/toggle-read', 'Admin\\NotificationController@toggleRead');
    Router::get('/notifications/{id}/delete', 'Admin\\NotificationController@destroy');
    Router::post('/notifications/{id}/delete', 'Admin\\NotificationController@destroy');
    Router::get('/notifications/{id}', 'Admin\\NotificationController@show');
    
    // Documents
    Router::resource('/documents', 'Admin\\DocumentController');
    Router::get('/documents/{id}/download', 'Admin\\DocumentController@download');
    
    // Users & Roles
    Router::resource('/users', 'Admin\\UserController');
    Router::get('/users/{id}/impersonate', 'Admin\\UserController@impersonate');
    Router::post('/users/{id}/toggle-status', 'Admin\\UserController@toggleStatus');
    Router::post('/users/{id}/reset-password', 'Admin\\UserController@resetPassword');
    Router::get('/stop-impersonate', 'Admin\\UserController@stopImpersonate');
    // Roles
    Router::get('/roles', 'Admin\\UserController@roles');
    Router::post('/roles', 'Admin\\UserController@storeRole');
    Router::get('/roles/{id}/permissions', 'Admin\\UserController@getRolePermissions');
    Router::post('/roles/{id}/permissions', 'Admin\\UserController@updateRolePermissions');
    
    // Settings
    Router::get('/settings', 'Admin\\SettingsController@index');
    Router::post('/settings', 'Admin\\SettingsController@update');
    Router::post('/settings/profile', 'Admin\\SettingsController@profile');
    Router::get('/settings/{group}', 'Admin\\SettingsController@group');
    
    // Audit Logs
    Router::get('/audit-logs', 'Admin\\SettingsController@auditLogs');
    
    // API Settings
    Router::get('/api-settings', 'Admin\\SettingsController@apiSettings');
    Router::post('/api-settings/generate-key', 'Admin\\SettingsController@generateApiKey');
    Router::post('/api-settings/{id}/delete', 'Admin\\SettingsController@deleteApiKey');

    // Test Email
    Router::post('/settings/test-email', 'Admin\\SettingsController@testEmail');
    Router::post('/settings/process-email-queue', 'Admin\\SettingsController@processEmailQueue');

    // Contact Submissions
    Router::get('/contacts', 'Admin\\SettingsController@contacts');
    Router::get('/contacts/{id}', 'Admin\\SettingsController@showContact');
    Router::post('/contacts/{id}/status', 'Admin\\SettingsController@updateContactStatus');

    // WhatsApp Integration
    Router::get('/whatsapp', 'Admin\\WhatsAppController@dashboard');
    Router::get('/whatsapp/instances', 'Admin\\WhatsAppController@instances');
    Router::post('/whatsapp/instances/create', 'Admin\\WhatsAppController@createInstance');
    Router::get('/whatsapp/instances/{name}/connect', 'Admin\\WhatsAppController@connectInstance');
    Router::get('/whatsapp/instances/{name}/qr', 'Admin\\WhatsAppController@instanceQR');
    Router::get('/whatsapp/instances/{name}/qr-data', 'Admin\\WhatsAppController@instanceQRData');
    Router::get('/whatsapp/instances/{name}/restart', 'Admin\\WhatsAppController@restartInstance');
    Router::get('/whatsapp/instances/{name}/logout', 'Admin\\WhatsAppController@logoutInstance');
    Router::get('/whatsapp/instances/{name}/delete', 'Admin\\WhatsAppController@deleteInstance');
    Router::get('/whatsapp/chats', 'Admin\\WhatsAppController@messages');
    Router::post('/whatsapp/chats/send', 'Admin\\WhatsAppController@sendMessage');
    Router::post('/whatsapp/chats/send-media', 'Admin\\WhatsAppController@sendMedia');
    Router::get('/whatsapp/contacts', 'Admin\\WhatsAppController@contacts');
    Router::post('/whatsapp/contacts/import', 'Admin\\WhatsAppController@importContacts');
    Router::get('/whatsapp/contacts/export', 'Admin\\WhatsAppController@exportContacts');
    Router::post('/whatsapp/contacts/delete', 'Admin\\WhatsAppController@deleteContacts');
    Router::get('/whatsapp/templates', 'Admin\\WhatsAppController@templates');
    Router::post('/whatsapp/templates', 'Admin\\WhatsAppController@storeTemplate');
    Router::get('/whatsapp/templates/{id}/delete', 'Admin\\WhatsAppController@deleteTemplate');
    Router::get('/whatsapp/campaigns', 'Admin\\WhatsAppController@campaigns');
    Router::post('/whatsapp/campaigns', 'Admin\\WhatsAppController@storeCampaign');
    Router::get('/whatsapp/campaigns/{id}/start', 'Admin\\WhatsAppController@startCampaign');
    Router::get('/whatsapp/campaigns/{id}/pause', 'Admin\\WhatsAppController@pauseCampaign');
    Router::get('/whatsapp/campaigns/{id}/resume', 'Admin\\WhatsAppController@resumeCampaign');
    Router::get('/whatsapp/campaigns/{id}/stop', 'Admin\\WhatsAppController@stopCampaign');
    Router::get('/whatsapp/campaigns/{id}/delete', 'Admin\\WhatsAppController@deleteCampaign');
    Router::get('/whatsapp/automation', 'Admin\\WhatsAppController@automation');
    Router::post('/whatsapp/automation', 'Admin\\WhatsAppController@storeAutomation');
    Router::get('/whatsapp/automation/{id}/delete', 'Admin\\WhatsAppController@deleteAutomation');
    Router::get('/whatsapp/logs', 'Admin\\WhatsAppController@logs');
    Router::get('/whatsapp/logs/clear', 'Admin\\WhatsAppController@clearLogs');
    Router::get('/whatsapp/settings', 'Admin\\WhatsAppController@settings');
    Router::post('/whatsapp/settings', 'Admin\\WhatsAppController@updateSettings');
    Router::get('/whatsapp/test-connection', 'Admin\\WhatsAppController@testConnection');
    Router::get('/whatsapp/api/instance/{name}/status', 'Admin\\WhatsAppController@apiInstanceStatus');
    Router::get('/whatsapp/api/sync-instances', 'Admin\\WhatsAppController@apiSyncInstances');
});

// ------------------------------------------------
// Fallback Routes
// ------------------------------------------------
Router::get('/sitemap.xml', 'Frontend\\HomeController@sitemap');
Router::get('/robots.txt', 'Frontend\\HomeController@robots');
