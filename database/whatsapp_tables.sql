-- WhatsApp Integration Tables for Evolution API
-- Run this migration to create required tables

-- WhatsApp Settings
CREATE TABLE IF NOT EXISTS whatsapp_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_url VARCHAR(500) NOT NULL DEFAULT '',
    api_key VARCHAR(500) NOT NULL DEFAULT '',
    default_instance VARCHAR(255) DEFAULT '',
    webhook_url VARCHAR(500) DEFAULT '',
    webhook_secret VARCHAR(255) DEFAULT '',
    auto_retry TINYINT(1) DEFAULT 1,
    timeout INT DEFAULT 30,
    enable_logs TINYINT(1) DEFAULT 1,
    enable_notifications TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WhatsApp Instances
CREATE TABLE IF NOT EXISTS whatsapp_instances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instance_name VARCHAR(255) NOT NULL,
    display_name VARCHAR(255) DEFAULT '',
    status VARCHAR(50) DEFAULT 'disconnected',
    phone VARCHAR(50) DEFAULT '',
    profile_name VARCHAR(255) DEFAULT '',
    profile_picture TEXT DEFAULT NULL,
    qrcode TEXT DEFAULT NULL,
    battery INT DEFAULT 0,
    last_seen DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_instance_name (instance_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WhatsApp Contacts
CREATE TABLE IF NOT EXISTS whatsapp_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instance VARCHAR(255) DEFAULT '',
    name VARCHAR(255) DEFAULT '',
    phone VARCHAR(50) NOT NULL,
    country VARCHAR(100) DEFAULT '',
    email VARCHAR(255) DEFAULT '',
    notes TEXT DEFAULT NULL,
    tags TEXT DEFAULT NULL,
    last_seen DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_phone_instance (phone, instance)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WhatsApp Messages
CREATE TABLE IF NOT EXISTS whatsapp_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instance VARCHAR(255) DEFAULT '',
    phone VARCHAR(50) DEFAULT '',
    contact_name VARCHAR(255) DEFAULT '',
    direction ENUM('inbound', 'outbound') DEFAULT 'outbound',
    message_type VARCHAR(50) DEFAULT 'text',
    message LONGTEXT DEFAULT NULL,
    media_url TEXT DEFAULT NULL,
    media_type VARCHAR(100) DEFAULT NULL,
    filename VARCHAR(255) DEFAULT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    message_id VARCHAR(255) DEFAULT NULL,
    from_me TINYINT(1) DEFAULT 0,
    read_status TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_instance_phone (instance, phone),
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WhatsApp Templates
CREATE TABLE IF NOT EXISTS whatsapp_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) DEFAULT 'general',
    message LONGTEXT NOT NULL,
    variables TEXT DEFAULT NULL,
    media_url TEXT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    use_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WhatsApp Campaigns
CREATE TABLE IF NOT EXISTS whatsapp_campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    template_id INT DEFAULT NULL,
    instance VARCHAR(255) DEFAULT '',
    status ENUM('draft', 'scheduled', 'running', 'paused', 'completed', 'failed') DEFAULT 'draft',
    schedule_at DATETIME DEFAULT NULL,
    total_contacts INT DEFAULT 0,
    sent INT DEFAULT 0,
    delivered INT DEFAULT 0,
    failed INT DEFAULT 0,
    pending INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WhatsApp Campaign Contacts (pivot)
CREATE TABLE IF NOT EXISTS whatsapp_campaign_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT NOT NULL,
    contact_id INT NOT NULL,
    status ENUM('pending', 'sent', 'delivered', 'failed') DEFAULT 'pending',
    message_id VARCHAR(255) DEFAULT NULL,
    error_message TEXT DEFAULT NULL,
    sent_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_campaign (campaign_id),
    INDEX idx_contact (contact_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WhatsApp Automations
CREATE TABLE IF NOT EXISTS whatsapp_automations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    trigger_event VARCHAR(100) NOT NULL,
    template_id INT DEFAULT NULL,
    instance VARCHAR(255) DEFAULT '',
    recipient_type VARCHAR(50) DEFAULT 'customer',
    is_active TINYINT(1) DEFAULT 1,
    conditions TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WhatsApp Logs
CREATE TABLE IF NOT EXISTS whatsapp_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instance VARCHAR(255) DEFAULT '',
    endpoint VARCHAR(500) DEFAULT '',
    method VARCHAR(10) DEFAULT 'GET',
    request_headers TEXT DEFAULT NULL,
    request_body LONGTEXT DEFAULT NULL,
    response_body LONGTEXT DEFAULT NULL,
    response_code INT DEFAULT 0,
    duration_ms INT DEFAULT 0,
    status ENUM('success', 'error') DEFAULT 'success',
    error_message TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_instance (instance),
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO whatsapp_settings (api_url, api_key) VALUES ('', '') ON DUPLICATE KEY UPDATE id=id;
