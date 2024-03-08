-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 04, 2019 at 03:14 AM
-- Server version: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_for_new_project_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `creator_id` int(10) UNSIGNED DEFAULT NULL,
  `updater_id` int(10) UNSIGNED DEFAULT NULL,
  `deleter_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branches_creator_id_index` (`creator_id`),
  KEY `branches_updater_id_index` (`updater_id`),
  KEY `branches_deleter_id_index` (`deleter_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `address`, `description`, `created_at`, `updated_at`, `deleted_at`, `creator_id`, `updater_id`, `deleter_id`) VALUES
(1, 'បឹងសាយ៉ាប់', 'ផ្លូវ598 សង្កាត់ទួលសង្កែ ខណ្ឌ បស្សីកែវ', 'សាខាទី1', '2018-08-03 01:40:56', '2018-08-30 15:12:17', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_07_01_070728_create_user_groups_table', 2),
(4, '2018_07_01_070913_create_branches_table', 2),
(5, '2018_07_01_070945_create_supplier_groups_table', 2),
(6, '2018_07_01_071010_create_suppliers_table', 2),
(7, '2018_07_01_071045_create_products_categories_table', 2),
(8, '2018_07_01_071109_create_products_table', 2),
(9, '2018_07_01_071147_create_product_movements_table', 2),
(10, '2018_07_01_071237_create_open_transactions_table', 2),
(11, '2018_07_01_071324_create_balance_transactions_table', 2),
(12, '2018_07_01_071406_create_open_close_transactions_table', 2),
(22, '2018_07_01_071431_create_purchases_table', 5),
(23, '2018_07_01_071456_create_purchase_items_table', 6),
(15, '2018_07_01_071535_create_deposits_table', 2),
(16, '2018_07_01_071613_create_purchase_payments_table', 2),
(17, '2018_07_01_071646_create_purchase_payment_details_table', 2),
(18, '2018_07_16_093138_create_expens_table', 3),
(19, '2018_07_16_093326_create_group_prices_table', 3),
(21, '2018_07_16_155631_create_exchanges_table', 4),
(24, '2018_08_17_120303_create_card_points_table', 7),
(26, '2018_08_22_122041_create_permissions_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `user_group_id`, `name`, `created_at`, `updated_at`) VALUES
(2, 2, 'usergroups', '2019-01-03 09:33:22', '2019-01-03 09:33:22'),
(3, 2, 'user.edit', '2019-01-03 09:33:22', '2019-01-03 09:33:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `group_id`, `branch_id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'admin', 'admin@855solution.com', '$2y$10$uBVn5YE456Q2yH69MbIv5eYoqIxil4ClXftmVJ4FDrBQBZTbg706W', 'AiURO5UhLPm94pMcAZNXl7iHhGs3y6oMMMQLurlWqXinnnVZoE45uB7Kcn8Q', '2018-06-30 15:38:35', '2018-09-03 08:26:17'),
(2, 2, 1, 'Long Chenda', 'lengmouygak@yahoo.com', '$2y$10$uBVn5YE456Q2yH69MbIv5eYoqIxil4ClXftmVJ4FDrBQBZTbg706W', '5q2H9irCKzGTuhpugbrq5srfAAyMB2KfEvg9mfiATr41a6Qj33jyrcpQNbCD', '2018-09-03 06:59:15', '2018-09-03 09:32:00'),
(3, 3, 1, 'Touch Sela', 'touchsreyla888@gmail.com', '$2y$10$uBVn5YE456Q2yH69MbIv5eYoqIxil4ClXftmVJ4FDrBQBZTbg706W', 'sRq8QrSLX8IBM4FpqTwd0CmrZVdWIuhnFsaO0FcsLRLXlcoVzWVPRYki9gj9', '2018-09-03 07:01:58', '2018-09-03 08:31:20'),
(4, 2, 1, 'Ea Sreymean', 'kimhanna@yahoo.com', '$2y$10$uBVn5YE456Q2yH69MbIv5eYoqIxil4ClXftmVJ4FDrBQBZTbg706W', 'PVPC46SPqUuYcFestqY1oITc6Ag8H9Rft9rKWXjEHtMd1Ftpc4rJlrB5B3Wv', '2018-09-03 07:03:00', '2018-09-03 08:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `creator_id` int(10) UNSIGNED DEFAULT NULL,
  `updater_id` int(10) UNSIGNED DEFAULT NULL,
  `deleter_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_groups_creator_id_index` (`creator_id`),
  KEY `user_groups_updater_id_index` (`updater_id`),
  KEY `user_groups_deleter_id_index` (`deleter_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `description`, `created_at`, `updated_at`, `deleted_at`, `creator_id`, `updater_id`, `deleter_id`) VALUES
(1, 'admin', 'Admin', '2018-07-01 13:42:37', '2018-08-30 15:12:52', NULL, NULL, NULL, NULL),
(2, 'User', 'by User', '2018-07-01 13:43:37', '2019-01-04 02:23:21', NULL, NULL, NULL, NULL),
(3, 'Users', 'Users', '2018-09-03 07:40:46', '2019-01-04 02:22:46', NULL, NULL, NULL, NULL),
(4, 'Saller', 'gfn', '2019-01-04 02:30:17', '2019-01-04 02:30:33', '2019-01-04 02:30:33', NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
