-- ===================================================
-- Business Connect - Complete InfinityFree MySQL Schema & Seed
-- Compatible with phpMyAdmin and MySQL 5.7+ / 8.0+ / MariaDB
-- Default user password for seeded accounts: password123
-- ===================================================

SET FOREIGN_KEY_CHECKS=0;

-- ===================================================
-- Business Connect - Complete MySQL Database Schema
-- Compatible with MySQL 8.0+ and MariaDB 10.4+
-- ===================================================


-- --------------------------------------------------------
-- Table: roles
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `label` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `phone` VARCHAR(50) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active', 'inactive', 'banned') DEFAULT 'active',
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_users_roles` (`role_id`),
  CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: business_categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `business_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `icon` VARCHAR(100) DEFAULT 'bi-shop',
  `description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: businesses
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `businesses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `owner_name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `gst_number` VARCHAR(50) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `whatsapp` VARCHAR(50) DEFAULT NULL,
  `facebook` VARCHAR(255) DEFAULT NULL,
  `instagram` VARCHAR(255) DEFAULT NULL,
  `linkedin` VARCHAR(255) DEFAULT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `google_maps_embed` TEXT DEFAULT NULL,
  `opening_time` TIME DEFAULT NULL,
  `closing_time` TIME DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `pin_code` VARCHAR(20) DEFAULT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `views_count` BIGINT UNSIGNED DEFAULT 0,
  `logo` VARCHAR(255) DEFAULT NULL,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_businesses_users` (`user_id`),
  KEY `fk_businesses_categories` (`category_id`),
  CONSTRAINT `fk_businesses_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_businesses_categories` FOREIGN KEY (`category_id`) REFERENCES `business_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: product_categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: products
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `discount` DECIMAL(5,2) DEFAULT 0.00,
  `stock` INT DEFAULT 0,
  `sku` VARCHAR(100) UNIQUE DEFAULT NULL,
  `images` JSON DEFAULT NULL,
  `availability` ENUM('in_stock', 'out_of_stock', 'pre_order') DEFAULT 'in_stock',
  `tags` JSON DEFAULT NULL,
  `specifications` JSON DEFAULT NULL,
  `status` ENUM('active', 'draft', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_products_businesses` (`business_id`),
  KEY `fk_products_categories` (`category_id`),
  CONSTRAINT `fk_products_businesses` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_products_categories` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: services
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `services` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `duration` VARCHAR(100) DEFAULT NULL,
  `availability` ENUM('available', 'unavailable') DEFAULT 'available',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_services_businesses` (`business_id`),
  CONSTRAINT `fk_services_businesses` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: inquiries
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT UNSIGNED DEFAULT NULL,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `preferred_contact_time` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('pending', 'replied', 'closed') DEFAULT 'pending',
  `reply_message` TEXT DEFAULT NULL,
  `replied_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_inquiries_customers` (`customer_id`),
  KEY `fk_inquiries_businesses` (`business_id`),
  CONSTRAINT `fk_inquiries_customers` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_inquiries_businesses` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: reviews
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `rating` TINYINT NOT NULL,
  `review_text` TEXT NOT NULL,
  `images` JSON DEFAULT NULL,
  `likes_count` INT UNSIGNED DEFAULT 0,
  `status` ENUM('approved', 'pending', 'rejected') DEFAULT 'approved',
  `reported` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_reviews_customers` (`customer_id`),
  KEY `fk_reviews_businesses` (`business_id`),
  CONSTRAINT `fk_reviews_customers` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_businesses` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: favorites
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_customer_business` (`customer_id`, `business_id`),
  CONSTRAINT `fk_favorites_customers` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favorites_businesses` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: notifications
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `type` VARCHAR(50) DEFAULT 'info',
  `is_read` TINYINT(1) DEFAULT 0,
  `link` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_notifications_users` (`user_id`),
  CONSTRAINT `fk_notifications_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: galleries
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` BIGINT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `type` ENUM('photo', 'document') DEFAULT 'photo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_galleries_businesses` (`business_id`),
  CONSTRAINT `fk_galleries_businesses` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: contact_messages
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('new', 'read', 'replied') DEFAULT 'new',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: settings
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(255) NOT NULL UNIQUE,
  `value` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: activity_logs
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_activity_users` (`user_id`),
  CONSTRAINT `fk_activity_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: password_resets
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  KEY `email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================

-- ===================================================
-- INITIAL SEED DATA
-- ===================================================

INSERT INTO `roles` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'System Administrator', NOW(), NOW()),
(2, 'business_owner', 'Business Owner', NOW(), NOW()),
(3, 'customer', 'Customer', NOW(), NOW())
ON DUPLICATE KEY UPDATE `label` = VALUES(`label`);

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'admin@businessconnect.com', '+1-800-555-0199', '$2y$10$QNImF6wE3vruBeQVwsfEVeSod6RjklBV0tOQAI/kz98ClhIKw06aK', 1, 'active', NOW(), NOW()),
(2, 'Alex Carter (Nexus Tech)', 'alex@nexustech.com', '+1-555-0147', '$2y$10$QNImF6wE3vruBeQVwsfEVeSod6RjklBV0tOQAI/kz98ClhIKw06aK', 2, 'active', NOW(), NOW()),
(3, 'Sophia Bennett (Green Leaf)', 'sophia@greenleaf.com', '+1-555-0188', '$2y$10$QNImF6wE3vruBeQVwsfEVeSod6RjklBV0tOQAI/kz98ClhIKw06aK', 2, 'active', NOW(), NOW()),
(4, 'David Miller', 'david.customer@gmail.com', '+1-555-0192', '$2y$10$QNImF6wE3vruBeQVwsfEVeSod6RjklBV0tOQAI/kz98ClhIKw06aK', 3, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `business_categories` (`id`, `name`, `slug`, `icon`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Information Technology', 'technology', 'bi-laptop', 'Software development, IT consulting, cloud infrastructure and cybersecurity.', 'active', NOW(), NOW()),
(2, 'Healthcare & Wellness', 'healthcare', 'bi-heart-pulse', 'Hospitals, clinics, physical therapy, medical diagnostics and wellness centers.', 'active', NOW(), NOW()),
(3, 'Real Estate & Living', 'real-estate', 'bi-building-gear', 'Commercial properties, luxury apartments, architecture and land development.', 'active', NOW(), NOW()),
(4, 'Retail & Consumer Goods', 'retail', 'bi-cart-check', 'Electronics, apparel, home decor, lifestyle products and luxury goods.', 'active', NOW(), NOW()),
(5, 'Financial Services', 'financial', 'bi-cash-coin', 'Banking, investment advisory, tax compliance and accounting solutions.', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `product_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Electronics & Gadgets', 'electronics', NOW(), NOW()),
(2, 'Software Licenses', 'software', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `businesses` (`id`, `user_id`, `category_id`, `name`, `owner_name`, `description`, `gst_number`, `website`, `email`, `phone`, `whatsapp`, `location`, `opening_time`, `closing_time`, `state`, `city`, `address`, `pin_code`, `status`, `views_count`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'NexusTech Solutions Ltd', 'Alex Carter', 'NexusTech is an industry-leading software and cloud transformation consulting firm empowering global enterprises.', '27ABCDE1234F1Z5', 'https://nexustech.example.com', 'contact@nexustech.com', '+1-555-0147', '+1-555-0147', 'Tech Park Tower A, San Francisco, CA', '09:00:00', '18:00:00', 'California', 'San Francisco', '100 Silicon Way, Suite 400', '94105', 'approved', 1420, NOW(), NOW()),
(2, 3, 2, 'Green Leaf Organic Wellness', 'Sophia Bennett', 'Premium organic health supplements, holistic therapy, and eco-friendly wellness products.', '27XYZAB5678G2Z9', 'https://greenleafwellness.example.com', 'hello@greenleaf.com', '+1-555-0188', '+1-555-0188', 'Main Street Plaza, Austin, TX', '08:30:00', '20:00:00', 'Texas', 'Austin', '450 Organic Boulevard', '78701', 'approved', 890, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `products` (`id`, `business_id`, `category_id`, `name`, `description`, `price`, `discount`, `stock`, `sku`, `availability`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Enterprise CRM Cloud License', 'Automate sales pipelines, customer relations, and multi-channel marketing campaigns.', 499.00, 10.00, 100, 'NEX-CRM-01', 'in_stock', 'active', NOW(), NOW()),
(2, 2, 1, 'Organic Hemp Herbal Oil Extract', 'Cold-pressed 100% pure organic hemp oil formula for maximum stress relief and vitality.', 65.00, 5.00, 250, 'GL-HEMP-500', 'in_stock', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `services` (`id`, `business_id`, `name`, `description`, `price`, `duration`, `availability`, `created_at`, `updated_at`) VALUES
(1, 1, 'Custom Cloud Infrastructure Audit', 'Complete security audit, cost optimization, and DevOps pipeline setup for AWS / Azure.', 1200.00, '3 Days', 'available', NOW(), NOW()),
(2, 2, 'Holistic Wellness Consultation', 'Personalized 1-on-1 diet, nutritional evaluation, and lifestyle optimization plan.', 150.00, '60 Minutes', 'available', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `inquiries` (`id`, `customer_id`, `business_id`, `name`, `email`, `phone`, `subject`, `message`, `preferred_contact_time`, `status`, `reply_message`, `replied_at`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'David Miller', 'david.customer@gmail.com', '+1-555-0192', 'Quote request for custom CRM implementation', 'Hello, our company has 50 sales reps. We would like a full migration quote to NexusTech CRM.', 'Morning (9 AM - 12 PM)', 'replied', 'Thank you David! Our senior solutions engineer will contact you tomorrow morning.', NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE `subject` = VALUES(`subject`);

INSERT INTO `reviews` (`id`, `customer_id`, `business_id`, `rating`, `review_text`, `status`, `likes_count`, `reported`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 5, 'Outstanding IT support and cloud migration service. NexusTech saved us over 30% in infrastructure costs!', 'approved', 14, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE `review_text` = VALUES(`review_text`);

INSERT INTO `favorites` (`id`, `customer_id`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 4, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`);

SET FOREIGN_KEY_CHECKS=1;
