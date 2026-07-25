<?php
/**
 * Global Delivered Logistics - System Constants
 * All constants use defined() guards to prevent errors when loaded multiple times.
 */

// System paths
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));
defined('APP_PATH') or define('APP_PATH', ROOT_PATH . '/app');
defined('CONFIG_PATH') or define('CONFIG_PATH', ROOT_PATH . '/config');
defined('PUBLIC_PATH') or define('PUBLIC_PATH', ROOT_PATH . '/public');
defined('STORAGE_PATH') or define('STORAGE_PATH', ROOT_PATH . '/storage');
defined('VIEWS_PATH') or define('VIEWS_PATH', APP_PATH . '/Views');
defined('UPLOADS_PATH') or define('UPLOADS_PATH', STORAGE_PATH . '/uploads');
defined('LOGS_PATH') or define('LOGS_PATH', STORAGE_PATH . '/logs');
defined('CACHE_PATH') or define('CACHE_PATH', STORAGE_PATH . '/cache');

// URL constants
defined('BASE_URL') or define('BASE_URL', rtrim(env('APP_URL', 'http://localhost/globaldelivered'), '/'));
defined('ASSETS_URL') or define('ASSETS_URL', BASE_URL . '/public/assets');
defined('ADMIN_URL') or define('ADMIN_URL', BASE_URL . '/public/admin');

// Date/Time format
defined('DATE_FORMAT') or define('DATE_FORMAT', 'Y-m-d');
defined('DATETIME_FORMAT') or define('DATETIME_FORMAT', 'Y-m-d H:i:s');
defined('TIME_FORMAT') or define('TIME_FORMAT', 'H:i:s');

// Default pagination
defined('PAGINATION_PER_PAGE') or define('PAGINATION_PER_PAGE', 25);

// Tracking
defined('TRACKING_PREFIX') or define('TRACKING_PREFIX', 'GDL');
defined('TRACKING_LENGTH') or define('TRACKING_LENGTH', 12);

// API
defined('API_RATE_LIMIT') or define('API_RATE_LIMIT', 60); // requests per minute
defined('API_VERSION') or define('API_VERSION', 'v1');

// File upload
defined('MAX_UPLOAD_SIZE') or define('MAX_UPLOAD_SIZE', 10485760); // 10MB
defined('ALLOWED_FILE_TYPES') or define('ALLOWED_FILE_TYPES', 'jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,csv');
