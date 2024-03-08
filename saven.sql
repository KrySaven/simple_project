-- Command

TRUNCATE TABLE products;
TRUNCATE TABLE product_pdf;
TRUNCATE TABLE product_colors;
TRUNCATE TABLE purchase_return;
TRUNCATE TABLE purchase_return_detail;
TRUNCATE TABLE purchase_return_pdf;
TRUNCATE TABLE categories;
TRUNCATE TABLE units;
TRUNCATE TABLE sizes;
TRUNCATE TABLE colors;

-- Truncate Purchase
TRUNCATE TABLE purchases;
TRUNCATE TABLE purchase_pdf;
TRUNCATE TABLE purchase_detail;
TRUNCATE TABLE purchase_history;

-- ========17-Jan-2024=========
CREATE TABLE `units` (
  `id` int NOT NULL AUTO_INCREMENT,
  `base_unit` int DEFAULT NULL,
  `is_size_unique` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name_kh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `angkortep-purchasing2`.`suppliers`
CHANGE COLUMN `active` `is_active` int NULL DEFAULT 1 AFTER `address`;


ALTER TABLE `angkortep-purchasing2`.`products`
ADD COLUMN `unit_id` int NULL AFTER `code_product`;
-- ======20/02/2024=======

CREATE TABLE `product_pdf` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `purchase_return_pdf` (
  `id` int NOT NULL AUTO_INCREMENT,
  `purchase_return_id` int DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `purchase_pdf` (
  `id` int NOT NULL AUTO_INCREMENT,
  `purchase_id` int DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- ===

ALTER TABLE `purchases`
DROP COLUMN `source_image`,
ADD COLUMN `source_image` longtext NULL AFTER `total_qty`;


-- =======
CREATE TABLE `status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name_kh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
====

ALTER TABLE `purchase_detail`
ADD COLUMN `status_id` int NULL AFTER `deleted_by`,
ADD COLUMN `status_date` datetime NULL AFTER `status_id`;
