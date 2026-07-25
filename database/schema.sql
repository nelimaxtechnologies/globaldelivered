-- ============================================================
-- Global Delivered Logistics (GDL) - Database Schema
-- Enterprise-grade courier & logistics management system
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------
-- 1. ROLES & PERMISSIONS
-- ---------------------------------------------------------
CREATE TABLE `roles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `slug` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `is_system` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `group` VARCHAR(50) DEFAULT NULL,
  `description` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_permissions_slug` (`slug`),
  INDEX `idx_permissions_group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role_permissions` (
  `role_id` INT UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 2. USERS
-- ---------------------------------------------------------
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `role_id` INT UNSIGNED NOT NULL,
  `branch_id` INT UNSIGNED NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `phone` VARCHAR(30) NULL,
  `password` VARCHAR(255) NOT NULL,
  `avatar` VARCHAR(255) NULL,
  `email_verified_at` TIMESTAMP NULL,
  `two_factor_secret` TEXT NULL,
  `two_factor_enabled` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `last_login_at` TIMESTAMP NULL,
  `last_login_ip` VARCHAR(45) NULL,
  `remember_token` VARCHAR(100) NULL,
  `password_reset_token` VARCHAR(100) NULL,
  `password_reset_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX `idx_users_email` (`email`),
  INDEX `idx_users_role` (`role_id`),
  INDEX `idx_users_branch` (`branch_id`),
  INDEX `idx_users_active` (`is_active`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_sessions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` TEXT NULL,
  `device_type` VARCHAR(50) NULL,
  `payload` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_sessions_user` (`user_id`),
  INDEX `idx_sessions_active` (`is_active`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 3. CUSTOMERS
-- ---------------------------------------------------------
CREATE TABLE `customers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NULL,
  `customer_type` ENUM('individual', 'company') DEFAULT 'individual',
  `company_name` VARCHAR(255) NULL,
  `company_registration` VARCHAR(100) NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `alternative_phone` VARCHAR(30) NULL,
  `whatsapp` VARCHAR(30) NULL,
  `address_line1` VARCHAR(255) NULL,
  `address_line2` VARCHAR(255) NULL,
  `city` VARCHAR(100) NULL,
  `state` VARCHAR(100) NULL,
  `country` VARCHAR(100) NULL,
  `postal_code` VARCHAR(20) NULL,
  `notes` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `email_verified_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX `idx_customers_email` (`email`),
  INDEX `idx_customers_phone` (`phone`),
  INDEX `idx_customers_user` (`user_id`),
  INDEX `idx_customers_country` (`country`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customer_addresses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT UNSIGNED NOT NULL,
  `label` VARCHAR(50) DEFAULT 'Home',
  `address_line1` VARCHAR(255) NOT NULL,
  `address_line2` VARCHAR(255) NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `country` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `is_default` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_custaddr_customer` (`customer_id`),
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `saved_recipients` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT UNSIGNED NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(30) NOT NULL,
  `address_line1` VARCHAR(255) NOT NULL,
  `address_line2` VARCHAR(255) NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `country` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_recipients_customer` (`customer_id`),
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 4. BRANCHES & WAREHOUSES
-- ---------------------------------------------------------
CREATE TABLE `countries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code` CHAR(2) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `phone_code` VARCHAR(10) NULL,
  `currency` VARCHAR(3) NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_countries_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `branches` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `code` VARCHAR(20) NOT NULL UNIQUE,
  `branch_type` ENUM('head_office', 'regional', 'local') DEFAULT 'local',
  `manager_id` INT UNSIGNED NULL,
  `address_line1` VARCHAR(255) NOT NULL,
  `address_line2` VARCHAR(255) NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `country` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(255) NULL,
  `whatsapp` VARCHAR(30) NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `opening_time` TIME NULL,
  `closing_time` TIME NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX `idx_branches_code` (`code`),
  INDEX `idx_branches_country` (`country`),
  INDEX `idx_branches_manager` (`manager_id`),
  INDEX `idx_branches_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `warehouses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `branch_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(20) NOT NULL UNIQUE,
  `manager_id` INT UNSIGNED NULL,
  `address_line1` VARCHAR(255) NOT NULL,
  `address_line2` VARCHAR(255) NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `country` VARCHAR(100) NOT NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `capacity` DECIMAL(12,2) NULL COMMENT 'cubic meters',
  `temperature_controlled` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX `idx_warehouses_branch` (`branch_id`),
  INDEX `idx_warehouses_code` (`code`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 5. VEHICLES
-- ---------------------------------------------------------
CREATE TABLE `vehicles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `branch_id` INT UNSIGNED NOT NULL,
  `vehicle_type` ENUM('car', 'motorbike', 'truck', 'van', 'container', 'air_cargo', 'ship') NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `registration_number` VARCHAR(50) NOT NULL UNIQUE,
  `make` VARCHAR(100) NULL,
  `model` VARCHAR(100) NULL,
  `year` YEAR NULL,
  `capacity_weight` DECIMAL(10,2) NULL COMMENT 'kg',
  `capacity_volume` DECIMAL(10,2) NULL COMMENT 'cubic meters',
  `fuel_type` VARCHAR(50) NULL,
  `insurance_policy` VARCHAR(255) NULL,
  `insurance_expiry` DATE NULL,
  `maintenance_last` DATE NULL,
  `maintenance_next` DATE NULL,
  `status` ENUM('active', 'maintenance', 'out_of_service', 'retired') DEFAULT 'active',
  `current_latitude` DECIMAL(10,7) NULL,
  `current_longitude` DECIMAL(10,7) NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX `idx_vehicles_branch` (`branch_id`),
  INDEX `idx_vehicles_reg` (`registration_number`),
  INDEX `idx_vehicles_status` (`status`),
  INDEX `idx_vehicles_type` (`vehicle_type`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 6. DRIVERS
-- ---------------------------------------------------------
CREATE TABLE `drivers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `branch_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NULL UNIQUE,
  `phone` VARCHAR(30) NOT NULL,
  `license_number` VARCHAR(50) NOT NULL UNIQUE,
  `license_expiry` DATE NULL,
  `license_class` VARCHAR(20) NULL,
  `assigned_vehicle_id` INT UNSIGNED NULL,
  `current_latitude` DECIMAL(10,7) NULL,
  `current_longitude` DECIMAL(10,7) NULL,
  `last_location_update` TIMESTAMP NULL,
  `status` ENUM('available', 'on_delivery', 'off_duty', 'on_leave') DEFAULT 'available',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX `idx_drivers_branch` (`branch_id`),
  INDEX `idx_drivers_user` (`user_id`),
  INDEX `idx_drivers_vehicle` (`assigned_vehicle_id`),
  INDEX `idx_drivers_status` (`status`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`assigned_vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 7. SHIPMENTS
-- ---------------------------------------------------------
CREATE TABLE `shipment_statuses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `color` VARCHAR(7) DEFAULT '#6c757d',
  `icon` VARCHAR(50) DEFAULT 'bi-circle',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_statuses_slug` (`slug`),
  INDEX `idx_statuses_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `shipments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `tracking_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_id` INT UNSIGNED NULL,
  `sender_name` VARCHAR(255) NOT NULL,
  `sender_email` VARCHAR(255) NOT NULL,
  `sender_phone` VARCHAR(30) NOT NULL,
  `sender_address` TEXT NOT NULL,
  `sender_city` VARCHAR(100) NOT NULL,
  `sender_state` VARCHAR(100) NOT NULL,
  `sender_country` VARCHAR(100) NOT NULL,
  `sender_postal_code` VARCHAR(20) NULL,
  `recipient_name` VARCHAR(255) NOT NULL,
  `recipient_email` VARCHAR(255) NULL,
  `recipient_phone` VARCHAR(30) NOT NULL,
  `recipient_address` TEXT NOT NULL,
  `recipient_city` VARCHAR(100) NOT NULL,
  `recipient_state` VARCHAR(100) NOT NULL,
  `recipient_country` VARCHAR(100) NOT NULL,
  `recipient_postal_code` VARCHAR(20) NULL,
  `origin_branch_id` INT UNSIGNED NULL,
  `destination_branch_id` INT UNSIGNED NULL,
  `current_warehouse_id` INT UNSIGNED NULL,
  `assigned_driver_id` INT UNSIGNED NULL,
  `assigned_vehicle_id` INT UNSIGNED NULL,
  `service_type` ENUM('domestic', 'international', 'express', 'same_day', 'freight', 'air_cargo', 'sea_freight', 'road_transport', 'warehousing', 'last_mile') NOT NULL,
  `package_type` VARCHAR(50) DEFAULT 'parcel',
  `weight` DECIMAL(10,2) NOT NULL COMMENT 'kg',
  `length` DECIMAL(10,2) NULL COMMENT 'cm',
  `width` DECIMAL(10,2) NULL COMMENT 'cm',
  `height` DECIMAL(10,2) NULL COMMENT 'cm',
  `description` TEXT NULL,
  `declared_value` DECIMAL(12,2) NULL,
  `is_fragile` TINYINT(1) DEFAULT 0,
  `is_insured` TINYINT(1) DEFAULT 0,
  `insurance_amount` DECIMAL(12,2) NULL,
  `is_cod` TINYINT(1) DEFAULT 0,
  `cod_amount` DECIMAL(12,2) NULL,
  `signature_required` TINYINT(1) DEFAULT 0,
  `reference_number` VARCHAR(100) NULL,
  `notes` TEXT NULL,
  `current_status_id` INT UNSIGNED NULL,
  `current_latitude` DECIMAL(10,7) NULL,
  `current_longitude` DECIMAL(10,7) NULL,
  `last_scan_at` TIMESTAMP NULL,
  `pickup_date` DATETIME NULL,
  `expected_delivery_date` DATETIME NULL,
  `actual_delivery_date` DATETIME NULL,
  `delivered_to` VARCHAR(255) NULL,
  `delivery_signature` TEXT NULL,
  `delivery_photo` VARCHAR(255) NULL,
  `total_charges` DECIMAL(12,2) NULL,
  `tax_amount` DECIMAL(12,2) NULL,
  `grand_total` DECIMAL(12,2) NULL,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `payment_status` ENUM('pending', 'paid', 'partially_paid', 'refunded', 'cancelled') DEFAULT 'pending',
  `payment_method` ENUM('cash', 'bank', 'stripe', 'paypal', 'flutterwave', 'paystack', 'mpesa') NULL,
  `status` ENUM('pending', 'active', 'in_transit', 'delivered', 'returned', 'cancelled', 'on_hold') DEFAULT 'pending',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_by` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX `idx_shipments_tracking` (`tracking_number`),
  INDEX `idx_shipments_customer` (`customer_id`),
  INDEX `idx_shipments_status` (`status`),
  INDEX `idx_shipments_payment` (`payment_status`),
  INDEX `idx_shipments_service` (`service_type`),
  INDEX `idx_shipments_driver` (`assigned_driver_id`),
  INDEX `idx_shipments_origin` (`origin_branch_id`),
  INDEX `idx_shipments_destination` (`destination_branch_id`),
  INDEX `idx_shipments_warehouse` (`current_warehouse_id`),
  INDEX `idx_shipments_current_status` (`current_status_id`),
  INDEX `idx_shipments_created` (`created_at`),
  INDEX `idx_shipments_sender_email` (`sender_email`),
  INDEX `idx_shipments_recipient_phone` (`recipient_phone`),
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`origin_branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`destination_branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`current_warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`assigned_driver_id`) REFERENCES `drivers`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`assigned_vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`current_status_id`) REFERENCES `shipment_statuses`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tracking_history` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT UNSIGNED NOT NULL,
  `status_id` INT UNSIGNED NOT NULL,
  `location` VARCHAR(255) NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `warehouse_id` INT UNSIGNED NULL,
  `description` TEXT NULL,
  `remarks` TEXT NULL,
  `updated_by` INT UNSIGNED NULL,
  `source` ENUM('admin', 'driver_app', 'api', 'system', 'warehouse') DEFAULT 'admin',
  `notified_customer` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_tracking_shipment` (`shipment_id`),
  INDEX `idx_tracking_status` (`status_id`),
  INDEX `idx_tracking_created` (`created_at`),
  INDEX `idx_tracking_location` (`latitude`, `longitude`),
  FOREIGN KEY (`shipment_id`) REFERENCES `shipments`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`status_id`) REFERENCES `shipment_statuses`(`id`),
  FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 8. INVOICES & PAYMENTS
-- ---------------------------------------------------------
CREATE TABLE `invoices` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
  `shipment_id` INT UNSIGNED NULL,
  `customer_id` INT UNSIGNED NULL,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `tax_percentage` DECIMAL(5,2) DEFAULT 0,
  `tax_amount` DECIMAL(12,2) DEFAULT 0,
  `discount_amount` DECIMAL(12,2) DEFAULT 0,
  `total` DECIMAL(12,2) NOT NULL,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `status` ENUM('draft', 'sent', 'paid', 'partially_paid', 'overdue', 'cancelled', 'refunded') DEFAULT 'draft',
  `due_date` DATE NULL,
  `paid_at` TIMESTAMP NULL,
  `notes` TEXT NULL,
  `created_by` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_invoices_number` (`invoice_number`),
  INDEX `idx_invoices_shipment` (`shipment_id`),
  INDEX `idx_invoices_customer` (`customer_id`),
  INDEX `idx_invoices_status` (`status`),
  FOREIGN KEY (`shipment_id`) REFERENCES `shipments`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT UNSIGNED NULL,
  `shipment_id` INT UNSIGNED NULL,
  `customer_id` INT UNSIGNED NULL,
  `transaction_id` VARCHAR(255) NULL UNIQUE,
  `payment_method` ENUM('cash', 'bank', 'stripe', 'paypal', 'flutterwave', 'paystack', 'mpesa') NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `payment_reference` VARCHAR(255) NULL,
  `payment_proof` VARCHAR(255) NULL,
  `status` ENUM('pending', 'completed', 'failed', 'refunded', 'cancelled') DEFAULT 'pending',
  `notes` TEXT NULL,
  `processed_by` INT UNSIGNED NULL,
  `processed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_payments_invoice` (`invoice_id`),
  INDEX `idx_payments_shipment` (`shipment_id`),
  INDEX `idx_payments_customer` (`customer_id`),
  INDEX `idx_payments_transaction` (`transaction_id`),
  INDEX `idx_payments_status` (`status`),
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipments`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 9. NOTIFICATIONS
-- ---------------------------------------------------------
CREATE TABLE `notifications` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NULL,
  `customer_id` INT UNSIGNED NULL,
  `type` ENUM('email', 'sms', 'push', 'system') NOT NULL,
  `channel` VARCHAR(50) DEFAULT 'system',
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `data` JSON NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `is_sent` TINYINT(1) DEFAULT 0,
  `sent_at` TIMESTAMP NULL,
  `read_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_notif_user` (`user_id`),
  INDEX `idx_notif_customer` (`customer_id`),
  INDEX `idx_notif_read` (`is_read`),
  INDEX `idx_notif_type` (`type`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `email_queue` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `to_email` VARCHAR(255) NOT NULL,
  `to_name` VARCHAR(255) NULL,
  `subject` VARCHAR(255) NOT NULL,
  `body` LONGTEXT NOT NULL,
  `alt_body` TEXT NULL,
  `attachments` JSON NULL,
  `template` VARCHAR(100) NULL,
  `template_data` JSON NULL,
  `priority` INT DEFAULT 0,
  `status` ENUM('queued', 'sending', 'sent', 'failed', 'cancelled') DEFAULT 'queued',
  `retry_count` INT DEFAULT 0,
  `max_retries` INT DEFAULT 3,
  `error_message` TEXT NULL,
  `sent_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_email_status` (`status`),
  INDEX `idx_email_priority` (`priority`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sms_queue` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `to_phone` VARCHAR(30) NOT NULL,
  `message` TEXT NOT NULL,
  `provider` ENUM('twilio', 'africas_talking', 'vonage') DEFAULT 'twilio',
  `priority` INT DEFAULT 0,
  `status` ENUM('queued', 'sending', 'sent', 'failed', 'cancelled') DEFAULT 'queued',
  `retry_count` INT DEFAULT 0,
  `max_retries` INT DEFAULT 3,
  `error_message` TEXT NULL,
  `sent_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_sms_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notification_templates` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `type` ENUM('email', 'sms', 'push') NOT NULL,
  `subject` VARCHAR(255) NULL,
  `body` TEXT NOT NULL,
  `variables` JSON NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_notif_templates_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 10. DOCUMENTS
-- ---------------------------------------------------------
CREATE TABLE `documents` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT UNSIGNED NULL,
  `customer_id` INT UNSIGNED NULL,
  `document_type` ENUM('invoice', 'label', 'proof_of_delivery', 'photo', 'customs', 'receipt', 'contract', 'attachment', 'other') NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `file_size` INT UNSIGNED NULL COMMENT 'bytes',
  `mime_type` VARCHAR(100) NULL,
  `notes` TEXT NULL,
  `uploaded_by` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_docs_shipment` (`shipment_id`),
  INDEX `idx_docs_customer` (`customer_id`),
  INDEX `idx_docs_type` (`document_type`),
  FOREIGN KEY (`shipment_id`) REFERENCES `shipments`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 11. AUDIT LOGS
-- ---------------------------------------------------------
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NULL,
  `action` VARCHAR(100) NOT NULL,
  `entity_type` VARCHAR(100) NULL,
  `entity_id` INT UNSIGNED NULL,
  `old_values` JSON NULL,
  `new_values` JSON NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `request_method` VARCHAR(10) NULL,
  `request_url` VARCHAR(500) NULL,
  `duration_ms` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_audit_user` (`user_id`),
  INDEX `idx_audit_action` (`action`),
  INDEX `idx_audit_entity` (`entity_type`, `entity_id`),
  INDEX `idx_audit_created` (`created_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 12. SETTINGS
-- ---------------------------------------------------------
CREATE TABLE `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `group` VARCHAR(50) NOT NULL DEFAULT 'general',
  `key` VARCHAR(100) NOT NULL,
  `value` TEXT NULL,
  `type` ENUM('text', 'number', 'boolean', 'json', 'email', 'file') DEFAULT 'text',
  `description` TEXT NULL,
  `is_system` TINYINT(1) DEFAULT 0,
  `is_encrypted` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_settings_group_key` (`group`, `key`),
  INDEX `idx_settings_group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 13. SHIPPING ZONES & RATES
-- ---------------------------------------------------------
CREATE TABLE `shipping_zones` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `countries` JSON NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `shipping_rates` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `zone_id` INT UNSIGNED NOT NULL,
  `service_type` ENUM('domestic', 'international', 'express', 'same_day', 'freight', 'air_cargo', 'sea_freight', 'road_transport', 'last_mile') NOT NULL,
  `weight_min` DECIMAL(10,2) DEFAULT 0,
  `weight_max` DECIMAL(10,2) DEFAULT 999999,
  `base_rate` DECIMAL(12,2) NOT NULL,
  `rate_per_kg` DECIMAL(12,2) DEFAULT 0,
  `volume_factor` DECIMAL(12,2) DEFAULT 5000 COMMENT 'DIM factor',
  `insurance_rate` DECIMAL(5,4) DEFAULT 0 COMMENT 'percentage',
  `estimated_days_min` INT DEFAULT 1,
  `estimated_days_max` INT DEFAULT 10,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_rates_zone` (`zone_id`),
  INDEX `idx_rates_service` (`service_type`),
  FOREIGN KEY (`zone_id`) REFERENCES `shipping_zones`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 14. WAREHOUSE INVENTORY
-- ---------------------------------------------------------
CREATE TABLE `warehouse_inventory` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `warehouse_id` INT UNSIGNED NOT NULL,
  `shipment_id` INT UNSIGNED NULL,
  `shelf_location` VARCHAR(50) NULL,
  `received_at` TIMESTAMP NULL,
  `expected_out_at` TIMESTAMP NULL,
  `shipped_at` TIMESTAMP NULL,
  `status` ENUM('received', 'stored', 'picking', 'packed', 'shipped', 'damaged') DEFAULT 'received',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_inv_warehouse` (`warehouse_id`),
  INDEX `idx_inv_shipment` (`shipment_id`),
  INDEX `idx_inv_status` (`status`),
  FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 15. BARCODE / QR SCANS LOG
-- ---------------------------------------------------------
CREATE TABLE `scan_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT UNSIGNED NOT NULL,
  `scan_type` ENUM('barcode', 'qr', 'rfid', 'manual') DEFAULT 'barcode',
  `scan_data` VARCHAR(255) NULL,
  `warehouse_id` INT UNSIGNED NULL,
  `location` VARCHAR(255) NULL,
  `device_id` VARCHAR(100) NULL,
  `scanned_by` INT UNSIGNED NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_scans_shipment` (`shipment_id`),
  INDEX `idx_scans_warehouse` (`warehouse_id`),
  FOREIGN KEY (`shipment_id`) REFERENCES `shipments`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`scanned_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- INSERT DEFAULT DATA
-- ============================================================

-- Default Roles
INSERT INTO `roles` (`name`, `slug`, `description`, `is_system`) VALUES
('Super Admin', 'super_admin', 'Full system access', 1),
('Admin', 'admin', 'Administrative access', 1),
('Branch Manager', 'branch_manager', 'Branch-level management', 1),
('Warehouse Manager', 'warehouse_manager', 'Warehouse operations', 1),
('Driver', 'driver', 'Delivery driver', 1),
('Customer', 'customer', 'Registered customer', 1);

-- Default Shipment Statuses
INSERT INTO `shipment_statuses` (`name`, `slug`, `color`, `icon`, `sort_order`) VALUES
('Order Received', 'order_received', '#3498db', 'bi-inbox', 1),
('Picked Up', 'picked_up', '#2ecc71', 'bi-box-seam', 2),
('At Warehouse', 'at_warehouse', '#f39c12', 'bi-building', 3),
('In Transit', 'in_transit', '#9b59b6', 'bi-truck', 4),
('Customs Clearance', 'customs_clearance', '#e74c3c', 'bi-shield-check', 5),
('Out for Delivery', 'out_for_delivery', '#1abc9c', 'bi-bicycle', 6),
('Delivered', 'delivered', '#27ae60', 'bi-check-circle', 7),
('Delayed', 'delayed', '#e74c3c', 'bi-exclamation-triangle', 8),
('Returned', 'returned', '#95a5a6', 'bi-arrow-return-left', 9),
('Cancelled', 'cancelled', '#e74c3c', 'bi-x-circle', 10),
('On Hold', 'on_hold', '#f39c12', 'bi-pause-circle', 11);

-- Default Settings
INSERT INTO `settings` (`group`, `key`, `value`, `type`, `description`, `is_system`) VALUES
('general', 'company_name', 'Global Delivered Logistics', 'text', 'Company Name', 1),
('general', 'company_email', 'info@globaldelivered.com', 'email', 'Company Email', 1),
('general', 'company_phone', '+1-555-0123', 'text', 'Company Phone', 1),
('general', 'company_address', '123 Logistics Ave, Suite 100', 'text', 'Company Address', 1),
('general', 'company_city', 'New York', 'text', 'City', 0),
('general', 'company_state', 'NY', 'text', 'State', 0),
('general', 'company_country', 'United States', 'text', 'Country', 0),
('general', 'company_currency', 'USD', 'text', 'Default Currency', 1),
('general', 'company_logo', '', 'file', 'Company Logo', 0),
('general', 'company_timezone', 'UTC', 'text', 'Timezone', 1),
('email', 'mail_driver', 'smtp', 'text', 'Mail Driver', 1),
('email', 'smtp_host', '', 'text', 'SMTP Host', 1),
('email', 'smtp_port', '587', 'number', 'SMTP Port', 1),
('email', 'smtp_username', '', 'text', 'SMTP Username', 1),
('email', 'smtp_password', '', 'text', 'SMTP Password', 1),
('email', 'smtp_encryption', 'tls', 'text', 'SMTP Encryption', 1),
('tracking', 'tracking_url', 'https://globaldelivered.com/tracking', 'text', 'Tracking URL', 0),
('tracking', 'auto_update_days', '7', 'number', 'Auto-cancel after days', 0),
('general', 'enable_dark_mode', 'false', 'boolean', 'Enable Dark Mode', 0);

-- Default Country (for development)
INSERT INTO `countries` (`code`, `name`, `phone_code`, `currency`) VALUES
('US', 'United States', '+1', 'USD'),
('GB', 'United Kingdom', '+44', 'GBP'),
('CA', 'Canada', '+1', 'CAD'),
('AU', 'Australia', '+61', 'AUD'),
('DE', 'Germany', '+49', 'EUR'),
('FR', 'France', '+33', 'EUR'),
('IT', 'Italy', '+39', 'EUR'),
('ES', 'Spain', '+34', 'EUR'),
('AE', 'United Arab Emirates', '+971', 'AED'),
('CN', 'China', '+86', 'CNY'),
('JP', 'Japan', '+81', 'JPY'),
('NG', 'Nigeria', '+234', 'NGN'),
('KE', 'Kenya', '+254', 'KES'),
('ZA', 'South Africa', '+27', 'ZAR'),
('BR', 'Brazil', '+55', 'BRL'),
('IN', 'India', '+91', 'INR');

SET FOREIGN_KEY_CHECKS = 1;
