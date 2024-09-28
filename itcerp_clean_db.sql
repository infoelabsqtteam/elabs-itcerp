-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 22, 2017 at 06:22 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itcerp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `bank_id` int(10) UNSIGNED NOT NULL,
  `bank_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branch_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branch_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `IFSC` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `MICR` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city_db`
--

CREATE TABLE `city_db` (
  `city_id` int(10) UNSIGNED NOT NULL,
  `city_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `city_db`
--

INSERT INTO `city_db` (`city_id`, `city_code`, `city_name`, `state_id`, `created_at`, `updated_at`) VALUES
(1, 'PKL', 'Panchkulla', 1, '2017-02-03 02:57:32', '2017-02-03 01:54:37');

-- --------------------------------------------------------

--
-- Table structure for table `companyparameters`
--

CREATE TABLE `companyparameters` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_master`
--

CREATE TABLE `company_master` (
  `company_id` int(10) UNSIGNED NOT NULL,
  `company_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_city` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `company_master`
--

INSERT INTO `company_master` (`company_id`, `company_code`, `company_name`, `company_address`, `company_city`, `created_at`, `updated_at`) VALUES
(1, 'ITC01', 'Interstellar Testing Centre Pvt. LTD.', '86,Industrial Area Phase 1', 1, '2017-02-04 04:29:37', '2017-02-04 04:29:37');

-- --------------------------------------------------------

--
-- Table structure for table `countries_db`
--

CREATE TABLE `countries_db` (
  `country_id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_phone_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries_db`
--

INSERT INTO `countries_db` (`country_id`, `country_code`, `country_name`, `country_phone_code`, `created_at`, `updated_at`) VALUES
(1, 'IN', 'India', '+91', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_company_type`
--

CREATE TABLE `customer_company_type` (
  `company_type_id` int(10) UNSIGNED NOT NULL,
  `company_type_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer_company_type`
--

INSERT INTO `customer_company_type` (`company_type_id`, `company_type_name`, `created_at`, `updated_at`) VALUES
(1, 'Manufacturer', '2017-02-14 10:27:36', '2017-02-14 10:27:36'),
(2, 'Services', '2017-02-14 10:27:36', '2017-02-14 10:27:36'),
(3, 'Dealer', '2017-02-14 10:27:36', '2017-02-14 10:27:36'),
(4, 'Importer', '2017-02-14 10:27:36', '2017-02-14 10:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `customer_contact_persons`
--

CREATE TABLE `customer_contact_persons` (
  `contact_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `contact_name1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_name2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_designate1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_designate2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_mobile1` int(11) DEFAULT NULL,
  `contact_mobile2` int(11) DEFAULT NULL,
  `contact_email1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_email2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_main` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_locations`
--

CREATE TABLE `customer_locations` (
  `location_id` int(10) UNSIGNED NOT NULL,
  `location_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_master`
--

CREATE TABLE `customer_master` (
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_state` int(10) UNSIGNED NOT NULL,
  `customer_city` int(10) UNSIGNED NOT NULL,
  `customer_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sale_executive` int(10) UNSIGNED NOT NULL,
  `discount_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `discount_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_vat_cst` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mfg_lic_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ownership_type` int(10) UNSIGNED NOT NULL,
  `company_type` int(10) UNSIGNED NOT NULL,
  `owner_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_pan_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_tan_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_account_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_account_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_branch_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_rtgs_ifsc_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_ownership_type`
--

CREATE TABLE `customer_ownership_type` (
  `ownership_id` int(10) UNSIGNED NOT NULL,
  `ownership_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer_ownership_type`
--

INSERT INTO `customer_ownership_type` (`ownership_id`, `ownership_name`, `created_at`, `updated_at`) VALUES
(1, 'Company', '2017-02-14 10:28:43', '2017-02-14 10:28:43'),
(2, 'Individual', '2017-02-14 10:28:43', '2017-02-14 10:28:43'),
(3, 'Propriter', '2017-02-14 10:28:43', '2017-02-14 10:28:43'),
(4, 'HUF', '2017-02-14 10:28:43', '2017-02-14 10:28:43');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `department_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `division_id` int(10) UNSIGNED NOT NULL,
  `division_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`division_id`, `division_code`, `division_name`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'PKL01', 'Panchkulla', 1, '2017-02-04 04:30:30', '2017-02-04 04:30:30'),
(2, 'CHD01', 'Chandigarh', 1, '2017-02-04 04:30:46', '2017-02-04 04:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `division_parameters`
--

CREATE TABLE `division_parameters` (
  `division_parameter_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `division_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_city` int(10) UNSIGNED NOT NULL,
  `division_PAN` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_VAT_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_type`
--

CREATE TABLE `equipment_type` (
  `equipment_id` int(10) UNSIGNED NOT NULL,
  `equipment_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `equipment_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `equipment_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followup`
--

CREATE TABLE `followup` (
  `followup_id` int(10) UNSIGNED NOT NULL,
  `inquiry_id` int(10) UNSIGNED NOT NULL,
  `followup_by` int(10) UNSIGNED NOT NULL,
  `mode` enum('visit','phone','email','other') COLLATE utf8_unicode_ci NOT NULL,
  `followup_detail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `next_followup_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `inquiry_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `inquiry_detail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `inquiry_date` date NOT NULL,
  `next_followup_date` date NOT NULL,
  `inquiry_status` enum('open','closed','won') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_hdr`
--

CREATE TABLE `invoice_hdr` (
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_status` tinyint(4) NOT NULL DEFAULT '1',
  `customer_id` int(10) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `total_discount` decimal(10,2) NOT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `service_tax_rate` decimal(10,2) NOT NULL,
  `service_tax_amount` decimal(10,2) NOT NULL,
  `sbc_rate_amount` decimal(10,2) NOT NULL,
  `kkc_rate_amount` decimal(10,2) NOT NULL,
  `net_total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_hdr_detail`
--

CREATE TABLE `invoice_hdr_detail` (
  `invoice_dtl_id` int(10) UNSIGNED NOT NULL,
  `invoice_hdr_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `item_cat_id` int(10) UNSIGNED NOT NULL,
  `item_cat_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_cat_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_parent_cat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_master`
--

CREATE TABLE `item_master` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `item_cat_id` int(10) UNSIGNED NOT NULL,
  `item_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_barcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_long_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_technical_description` text COLLATE utf8_unicode_ci,
  `item_specification` text COLLATE utf8_unicode_ci,
  `item_unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_perishable` tinyint(2) DEFAULT NULL,
  `shelf_life_days` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `method_master`
--

CREATE TABLE `method_master` (
  `method_id` int(10) UNSIGNED NOT NULL,
  `method_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `method_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `method_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_12_13_052853_create_roles_table', 2),
(7, '2017_01_02_103411_create_companyParameters_table', 4),
(8, '2017_01_02_121638_create_unit_conversion_db_table', 5),
(9, '2017_01_02_114859_create_bank_accounts_table', 6),
(10, '2017_01_02_121508_create_units_db_table', 7),
(11, '2017_01_02_120057_create_countries_db_table', 8),
(12, '2017_01_02_120518_create_state_db_table', 9),
(13, '2017_01_02_121402_create_city_db_table', 9),
(14, '2017_01_02_115811_create_companies_db_table', 10),
(17, '2017_01_03_050741_create_rol_setting_table', 13),
(20, '2017_01_03_050545_create_branch_wise_items_table', 16),
(21, '2017_01_03_050628_create_branch_wise_item_stock_table', 17),
(22, '2017_01_03_050705_create_branch_wise_store_table', 17),
(24, '2017_01_03_050439_create_customer_contact_persons_table', 19),
(25, '2017_01_03_050813_create_req_slip_hdr_table', 20),
(27, '2017_01_03_050910_create_req_slip_short_close_table', 22),
(28, '2017_01_03_050938_create_test_standard_table', 23),
(30, '2017_01_03_051233_create_product_test_parameter_equipment_usage_table', 25),
(32, '2017_01_03_051005_create_test_parameter_categories_table', 27),
(37, '2017_01_03_050836_create_req_slip_dtl_table', 32),
(38, '2017_01_02_114823_create_branches_table', 0),
(40, '2017_01_06_052835_create_division_parameters_table', 33),
(46, '2017_01_03_051058_create_product_test_hdr_table', 38),
(48, '2017_01_03_051200_create_product_test_parameter_BOM_table', 39),
(49, '2017_01_02_122248_create_unit_product_categories_table', 40),
(57, '2017_01_02_122602_create_equipment_type_table', 45),
(58, '2017_01_02_122602_create_method_master_table', 45),
(59, '2017_01_03_051128_create_product_test_dtl_table', 45),
(60, '2017_01_03_051128_create_product_test_parameter_altern_method_table', 45),
(66, '2017_01_20_112760_create_order_sample_priority_table', 50),
(69, '2017_01_24_122849_create_invoice_hdr_table', 51),
(70, '2017_01_24_122850_create_invoice_hdr_detail_table', 51),
(73, '2017_01_09_102159_create_order_master_table', 53),
(78, '2017_01_09_112725_create_order_parameters_detail_table', 54),
(79, '2017_01_02_121720_create_departments_table', 55),
(80, '2017_01_03_051035_create_test_parameter_table', 56),
(82, '2017_01_09_102159_create_orders_table', 58),
(84, '2017_01_02_122248_create_product_categories_table', 59),
(85, '2017_01_02_122602_create_product_master_table', 59),
(86, '2017_01_03_050307_create_item_categories_table', 60),
(87, '2017_01_03_050336_create_item_master_table', 60),
(88, '2017_01_02_113939_create_company_master_table', 61),
(89, '2017_01_06_052835_create_divisions_table', 61),
(91, '2017_02_03_050407_create_city_db_table', 62),
(95, '2017_01_03_051035_create_customer_locations_table', 65),
(96, '2014_10_12_000000_create_users_table', 66),
(97, '2016_12_13_052854_create_role_user_table', 67),
(100, '2016_12_13_104729_create_users_detail_table', 68),
(103, '2017_02_09_094308_create_status_master_table', 70),
(104, '2017_02_08_073017_create_ownership_type_table', 71),
(105, '2017_02_08_073037_create_company_type_table', 71),
(108, '2017_02_08_073017_create_customer_ownership_type_table', 74),
(110, '2017_02_08_073037_create_customer_company_type_table', 75),
(112, '2017_02_03_050439_create_customer_contact_persons_table', 77),
(113, '2017_02_03_050408_create_customer_master_table', 78),
(114, '2017_01_03_050510_create_vendors_table', 79),
(115, '2017_01_11_084938_create_inquiry_table', 80),
(116, '2017_01_11_084938_create_followup_table', 81);

-- --------------------------------------------------------

--
-- Table structure for table `order_master`
--

CREATE TABLE `order_master` (
  `order_id` int(10) UNSIGNED NOT NULL,
  `order_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2','3') COLLATE utf8_unicode_ci NOT NULL COMMENT '0 for order placed,1 for report completed,2 for invoice completed,3 for payment completed',
  `division_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `sale_executive` int(10) UNSIGNED NOT NULL,
  `order_date` date NOT NULL,
  `report_date` date DEFAULT NULL,
  `test_product` int(10) UNSIGNED NOT NULL,
  `product_as_per_customer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `test_standard` int(10) UNSIGNED NOT NULL,
  `test_to_perform` int(10) UNSIGNED NOT NULL,
  `sample_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `manufactured_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supplied_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mfg_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiry_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `batch_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `batch_size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_qty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pi_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_priority_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `discount_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_parameters_detail`
--

CREATE TABLE `order_parameters_detail` (
  `analysis_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `test_param_alternative_id` int(10) UNSIGNED NOT NULL,
  `product_test_parameter` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `equipment_id` int(10) UNSIGNED NOT NULL,
  `method_id` int(10) UNSIGNED NOT NULL,
  `claim_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `standard_value_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `standard_value_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `standard_value_to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_taken_days` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_taken_mins` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `test_performed_by` int(10) UNSIGNED DEFAULT NULL,
  `test_result` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_sample_priority`
--

CREATE TABLE `order_sample_priority` (
  `sample_priority_id` int(10) UNSIGNED NOT NULL,
  `sample_priority_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sample_priority_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `p_category_id` int(10) UNSIGNED NOT NULL,
  `p_category_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `p_category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NULL',
  `p_parent_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_master`
--

CREATE TABLE `product_master` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `p_category_id` int(10) UNSIGNED NOT NULL,
  `product_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_barcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_test_dtl`
--

CREATE TABLE `product_test_dtl` (
  `product_test_dtl_id` int(10) UNSIGNED NOT NULL,
  `test_id` int(10) UNSIGNED NOT NULL,
  `test_parameter_id` int(10) UNSIGNED NOT NULL,
  `equipment_id` int(10) UNSIGNED NOT NULL,
  `method_id` int(10) UNSIGNED NOT NULL,
  `claim_dependent` tinyint(4) NOT NULL,
  `standard_value_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NULL',
  `standard_value_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `standard_value_to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_taken_days` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_taken_mins` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cost_price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_test_hdr`
--

CREATE TABLE `product_test_hdr` (
  `test_id` int(10) UNSIGNED NOT NULL,
  `test_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `test_standard_id` int(10) UNSIGNED NOT NULL,
  `wef` date DEFAULT NULL,
  `upto` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_test_parameter_altern_method`
--

CREATE TABLE `product_test_parameter_altern_method` (
  `product_test_param_altern_method_id` int(10) UNSIGNED NOT NULL,
  `product_test_dtl_id` int(10) UNSIGNED NOT NULL,
  `test_id` int(10) UNSIGNED NOT NULL,
  `test_parameter_id` int(10) UNSIGNED NOT NULL,
  `equipment_id` int(10) UNSIGNED NOT NULL,
  `method_id` int(10) UNSIGNED NOT NULL,
  `claim_dependent` tinyint(4) NOT NULL,
  `standard_value_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NULL',
  `standard_value_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NULL',
  `standard_value_to` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NULL',
  `time_taken_days` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_taken_mins` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cost_price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_test_parameter_bom`
--

CREATE TABLE `product_test_parameter_bom` (
  `test_BOM_id` int(10) UNSIGNED NOT NULL,
  `test_id` int(10) UNSIGNED NOT NULL,
  `product_test_dtl_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `consumed_qty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_test_parameter_equipment_usage`
--

CREATE TABLE `product_test_parameter_equipment_usage` (
  `equp_usage_id` int(10) UNSIGNED NOT NULL,
  `product_test_parameter_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `usage_time` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `req_slip_dtl`
--

CREATE TABLE `req_slip_dtl` (
  `req_slip_dlt_id` int(10) UNSIGNED NOT NULL,
  `req_slip_hdr_id` int(11) NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `required_qty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `req_slip_hdr`
--

CREATE TABLE `req_slip_hdr` (
  `req_slip_id` int(10) UNSIGNED NOT NULL,
  `req_slip_date` date NOT NULL,
  `req_slip_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `req_depart_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `req_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_close_dt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `req_slip_short_close`
--

CREATE TABLE `req_slip_short_close` (
  `req_slip_short_close_id` int(10) UNSIGNED NOT NULL,
  `req_slip_short_close_dt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `req_slip_short_close_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `group`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', '', 'default', '2016-12-13 00:12:04', '2016-12-13 00:12:04'),
(2, 'Employee', 'employee', NULL, 'default', '2016-12-13 00:12:04', '2016-12-13 00:12:04'),
(3, 'Customer', 'customer', 'All customer group', 'default', '2016-12-30 18:30:00', '2016-12-30 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '1-Admin,2-Emp,3-Customer',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2017-02-03 10:15:08', '2017-02-03 10:15:08'),
(2, 2, 2, '2017-02-04 04:34:00', '2017-02-04 04:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `rol_setting`
--

CREATE TABLE `rol_setting` (
  `rol_setting_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `MSL` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ROL` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `state_db`
--

CREATE TABLE `state_db` (
  `state_id` int(10) UNSIGNED NOT NULL,
  `state_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `state_db`
--

INSERT INTO `state_db` (`state_id`, `state_code`, `state_name`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 'PN', 'Punjab', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status_master`
--

CREATE TABLE `status_master` (
  `status_id` int(10) UNSIGNED NOT NULL,
  `status_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `status_master`
--

INSERT INTO `status_master` (`status_id`, `status_code`, `status_name`, `status_description`, `created_at`, `updated_at`) VALUES
(0, 'PEND', 'Pending Reports', 'Pending Report', '2017-02-09 01:53:34', '2017-02-09 10:39:19'),
(1, 'COMP', 'Completed Reports', 'Completed Report', '2017-02-09 01:53:34', '2017-02-09 10:39:16');

-- --------------------------------------------------------

--
-- Table structure for table `test_parameter`
--

CREATE TABLE `test_parameter` (
  `test_parameter_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `test_parameter_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_parameter_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_parameter_print_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_parameter_category` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_parameter_categories`
--

CREATE TABLE `test_parameter_categories` (
  `test_para_cat_id` int(10) UNSIGNED NOT NULL,
  `test_para_cat_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_para_cat_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_para_cat_print_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_category` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_standard`
--

CREATE TABLE `test_standard` (
  `test_std_id` int(10) UNSIGNED NOT NULL,
  `test_std_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_std_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units_db`
--

CREATE TABLE `units_db` (
  `unit_id` int(10) UNSIGNED NOT NULL,
  `unit_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unit_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_conversion_db`
--

CREATE TABLE `unit_conversion_db` (
  `unit_conversion_id` int(10) UNSIGNED NOT NULL,
  `from_unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to_unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `confirm_factor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_product_categories`
--

CREATE TABLE `unit_product_categories` (
  `p_category_id` int(10) UNSIGNED NOT NULL,
  `p_category_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `p_category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `p_parent_id` int(11) NOT NULL,
  `p_company_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `division_id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 0, 'ITC-ERP Admin', 'admin@itcerp.com', '$2y$10$zXtFWWzvcm9FXr.KyEKL6ucK0eCzsVyjWqb.1UX1W9YvY929iyTxq', 'XqwjoXmQbegIEZbwrhf8G0ENbHLbeUOSJPHZM79IQThPxkBzUiHUvs7wiWZF', '2016-12-12 19:01:39', '2017-02-18 00:38:36'),
(2, 1, 'Drish Emp', 'drish.emp@itclab.com', '$2y$10$pOFi9TOz3r2PRdUuDSUGg.gpiMpMm0TvsSM98JVbvZ/WldAE6X78G', '7OswYahyS7KOLfRD2KRCldRdB1vmNYwzEi5UkKfcSAg7AHkv0Kj52KVcE92Q', '2017-02-04 04:33:59', '2017-02-21 08:10:14');

-- --------------------------------------------------------

--
-- Table structure for table `users_detail`
--

CREATE TABLE `users_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_sales_person` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_detail`
--

INSERT INTO `users_detail` (`id`, `user_id`, `department_id`, `user_code`, `is_sales_person`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'ADMINERP', 0, '2016-12-12 19:01:39', '2016-12-12 19:01:39'),
(2, 2, 1, 'ds012', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `vendor_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `vendor_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vendor_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vendor_mobile` bigint(11) NOT NULL,
  `vendor_state` int(10) UNSIGNED NOT NULL,
  `vendor_city` int(10) UNSIGNED NOT NULL,
  `vendor_pincode` int(6) NOT NULL,
  `vendor_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vendor_cust_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendor_website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vat_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gst_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_person_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_person_mobile` bigint(11) DEFAULT NULL,
  `credit_days` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`bank_id`),
  ADD UNIQUE KEY `bank_accounts_bank_code_unique` (`bank_code`);

--
-- Indexes for table `city_db`
--
ALTER TABLE `city_db`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `city_db_city_code_unique` (`city_code`),
  ADD KEY `city_db_state_id_index` (`state_id`);

--
-- Indexes for table `companyparameters`
--
ALTER TABLE `companyparameters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_master`
--
ALTER TABLE `company_master`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `company_master_company_code_unique` (`company_code`),
  ADD KEY `company_master_company_city_index` (`company_city`);

--
-- Indexes for table `countries_db`
--
ALTER TABLE `countries_db`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `customer_company_type`
--
ALTER TABLE `customer_company_type`
  ADD PRIMARY KEY (`company_type_id`);

--
-- Indexes for table `customer_contact_persons`
--
ALTER TABLE `customer_contact_persons`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `customer_contact_persons_customer_id_index` (`customer_id`);

--
-- Indexes for table `customer_locations`
--
ALTER TABLE `customer_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD UNIQUE KEY `customer_locations_location_code_unique` (`location_code`),
  ADD KEY `customer_locations_customer_id_index` (`customer_id`);

--
-- Indexes for table `customer_master`
--
ALTER TABLE `customer_master`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_master_customer_code_unique` (`customer_code`),
  ADD KEY `customer_master_customer_state_index` (`customer_state`),
  ADD KEY `customer_master_customer_city_index` (`customer_city`),
  ADD KEY `customer_master_sale_executive_index` (`sale_executive`),
  ADD KEY `customer_master_ownership_type_index` (`ownership_type`),
  ADD KEY `customer_master_company_type_index` (`company_type`);

--
-- Indexes for table `customer_ownership_type`
--
ALTER TABLE `customer_ownership_type`
  ADD PRIMARY KEY (`ownership_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `departments_department_code_unique` (`department_code`),
  ADD KEY `departments_company_id_index` (`company_id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`division_id`),
  ADD UNIQUE KEY `divisions_division_code_unique` (`division_code`),
  ADD KEY `divisions_company_id_index` (`company_id`);

--
-- Indexes for table `division_parameters`
--
ALTER TABLE `division_parameters`
  ADD PRIMARY KEY (`division_parameter_id`),
  ADD KEY `division_parameters_division_id_index` (`division_id`),
  ADD KEY `division_parameters_division_city_index` (`division_city`);

--
-- Indexes for table `equipment_type`
--
ALTER TABLE `equipment_type`
  ADD PRIMARY KEY (`equipment_id`),
  ADD UNIQUE KEY `equipment_type_equipment_code_unique` (`equipment_code`);

--
-- Indexes for table `followup`
--
ALTER TABLE `followup`
  ADD PRIMARY KEY (`followup_id`),
  ADD KEY `followup_inquiry_id_foreign` (`inquiry_id`),
  ADD KEY `followup_followup_by_foreign` (`followup_by`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inquiry_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `invoice_hdr`
--
ALTER TABLE `invoice_hdr`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `invoice_hdr_division_id_index` (`division_id`),
  ADD KEY `invoice_hdr_customer_id_index` (`customer_id`);

--
-- Indexes for table `invoice_hdr_detail`
--
ALTER TABLE `invoice_hdr_detail`
  ADD PRIMARY KEY (`invoice_dtl_id`),
  ADD KEY `invoice_hdr_detail_invoice_hdr_id_index` (`invoice_hdr_id`),
  ADD KEY `invoice_hdr_detail_order_id_index` (`order_id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`item_cat_id`),
  ADD UNIQUE KEY `item_categories_item_cat_code_unique` (`item_cat_code`);

--
-- Indexes for table `item_master`
--
ALTER TABLE `item_master`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_master_item_code_unique` (`item_code`),
  ADD UNIQUE KEY `item_master_item_barcode_unique` (`item_barcode`),
  ADD KEY `item_master_item_cat_id_index` (`item_cat_id`);

--
-- Indexes for table `method_master`
--
ALTER TABLE `method_master`
  ADD PRIMARY KEY (`method_id`),
  ADD UNIQUE KEY `method_master_method_code_unique` (`method_code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_master`
--
ALTER TABLE `order_master`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_master_division_id_index` (`division_id`),
  ADD KEY `order_master_customer_id_index` (`customer_id`),
  ADD KEY `order_master_sale_executive_index` (`sale_executive`),
  ADD KEY `order_master_test_product_index` (`test_product`),
  ADD KEY `order_master_test_standard_index` (`test_standard`),
  ADD KEY `order_master_test_to_perform_index` (`test_to_perform`),
  ADD KEY `order_master_sample_priority_id_index` (`sample_priority_id`);

--
-- Indexes for table `order_parameters_detail`
--
ALTER TABLE `order_parameters_detail`
  ADD PRIMARY KEY (`analysis_id`),
  ADD KEY `order_parameters_detail_order_id_index` (`order_id`),
  ADD KEY `order_parameters_detail_product_test_parameter_index` (`product_test_parameter`),
  ADD KEY `order_parameters_detail_test_param_alternative_id_index` (`test_param_alternative_id`),
  ADD KEY `order_parameters_detail_department_id_index` (`department_id`),
  ADD KEY `order_parameters_detail_equipment_id_index` (`equipment_id`),
  ADD KEY `order_parameters_detail_method_id_index` (`method_id`),
  ADD KEY `order_parameters_detail_test_performed_by_index` (`test_performed_by`);

--
-- Indexes for table `order_sample_priority`
--
ALTER TABLE `order_sample_priority`
  ADD PRIMARY KEY (`sample_priority_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`p_category_id`),
  ADD UNIQUE KEY `product_categories_p_category_code_unique` (`p_category_code`);

--
-- Indexes for table `product_master`
--
ALTER TABLE `product_master`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_master_product_code_unique` (`product_code`),
  ADD KEY `product_master_p_category_id_index` (`p_category_id`);

--
-- Indexes for table `product_test_dtl`
--
ALTER TABLE `product_test_dtl`
  ADD PRIMARY KEY (`product_test_dtl_id`),
  ADD KEY `product_test_dtl_test_id_index` (`test_id`),
  ADD KEY `product_test_dtl_test_parameter_id_index` (`test_parameter_id`),
  ADD KEY `product_test_dtl_equipment_id_index` (`equipment_id`),
  ADD KEY `product_test_dtl_method_id_index` (`method_id`);

--
-- Indexes for table `product_test_hdr`
--
ALTER TABLE `product_test_hdr`
  ADD PRIMARY KEY (`test_id`),
  ADD UNIQUE KEY `product_test_hdr_test_code_unique` (`test_code`),
  ADD KEY `product_test_hdr_product_id_index` (`product_id`),
  ADD KEY `product_test_hdr_test_standard_id_index` (`test_standard_id`);

--
-- Indexes for table `product_test_parameter_altern_method`
--
ALTER TABLE `product_test_parameter_altern_method`
  ADD PRIMARY KEY (`product_test_param_altern_method_id`),
  ADD KEY `product_test_parameter_altern_method_product_test_dtl_id_index` (`product_test_dtl_id`),
  ADD KEY `product_test_parameter_altern_method_test_id_index` (`test_id`),
  ADD KEY `product_test_parameter_altern_method_test_parameter_id_index` (`test_parameter_id`),
  ADD KEY `product_test_parameter_altern_method_equipment_id_index` (`equipment_id`),
  ADD KEY `product_test_parameter_altern_method_method_id_index` (`method_id`);

--
-- Indexes for table `product_test_parameter_bom`
--
ALTER TABLE `product_test_parameter_bom`
  ADD PRIMARY KEY (`test_BOM_id`),
  ADD KEY `product_test_parameter_bom_test_id_index` (`test_id`),
  ADD KEY `product_test_parameter_bom_product_test_dtl_id_index` (`product_test_dtl_id`),
  ADD KEY `product_test_parameter_bom_item_id_index` (`item_id`);

--
-- Indexes for table `product_test_parameter_equipment_usage`
--
ALTER TABLE `product_test_parameter_equipment_usage`
  ADD PRIMARY KEY (`equp_usage_id`);

--
-- Indexes for table `req_slip_dtl`
--
ALTER TABLE `req_slip_dtl`
  ADD PRIMARY KEY (`req_slip_dlt_id`),
  ADD KEY `req_slip_dtl_item_id_index` (`item_id`);

--
-- Indexes for table `req_slip_hdr`
--
ALTER TABLE `req_slip_hdr`
  ADD PRIMARY KEY (`req_slip_id`);

--
-- Indexes for table `req_slip_short_close`
--
ALTER TABLE `req_slip_short_close`
  ADD PRIMARY KEY (`req_slip_short_close_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_role_id_index` (`role_id`),
  ADD KEY `role_user_user_id_index` (`user_id`);

--
-- Indexes for table `rol_setting`
--
ALTER TABLE `rol_setting`
  ADD PRIMARY KEY (`rol_setting_id`),
  ADD KEY `rol_setting_item_id_index` (`item_id`),
  ADD KEY `rol_setting_division_id_index` (`division_id`);

--
-- Indexes for table `state_db`
--
ALTER TABLE `state_db`
  ADD PRIMARY KEY (`state_id`),
  ADD UNIQUE KEY `state_db_state_code_unique` (`state_code`),
  ADD KEY `state_db_country_id_index` (`country_id`);

--
-- Indexes for table `status_master`
--
ALTER TABLE `status_master`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status_code` (`status_code`);

--
-- Indexes for table `test_parameter`
--
ALTER TABLE `test_parameter`
  ADD PRIMARY KEY (`test_parameter_id`),
  ADD UNIQUE KEY `test_parameter_test_parameter_code_unique` (`test_parameter_code`),
  ADD KEY `test_parameter_test_parameter_category_index` (`test_parameter_category`),
  ADD KEY `test_parameter_department_id_index` (`department_id`);

--
-- Indexes for table `test_parameter_categories`
--
ALTER TABLE `test_parameter_categories`
  ADD PRIMARY KEY (`test_para_cat_id`),
  ADD UNIQUE KEY `test_parameter_categories_test_para_cat_code_unique` (`test_para_cat_code`);

--
-- Indexes for table `test_standard`
--
ALTER TABLE `test_standard`
  ADD PRIMARY KEY (`test_std_id`);

--
-- Indexes for table `units_db`
--
ALTER TABLE `units_db`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `units_db_unit_code_unique` (`unit_code`);

--
-- Indexes for table `unit_conversion_db`
--
ALTER TABLE `unit_conversion_db`
  ADD PRIMARY KEY (`unit_conversion_id`);

--
-- Indexes for table `unit_product_categories`
--
ALTER TABLE `unit_product_categories`
  ADD PRIMARY KEY (`p_category_id`),
  ADD UNIQUE KEY `unit_product_categories_p_category_code_unique` (`p_category_code`),
  ADD KEY `unit_product_categories_p_company_id_index` (`p_company_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_division_id_index` (`division_id`);

--
-- Indexes for table `users_detail`
--
ALTER TABLE `users_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_detail_user_code_unique` (`user_code`),
  ADD KEY `users_detail_user_id_index` (`user_id`),
  ADD KEY `users_detail_department_id_index` (`department_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `vendors_vendor_code_unique` (`vendor_code`),
  ADD KEY `vendors_division_id_index` (`division_id`),
  ADD KEY `vendors_vendor_state_index` (`vendor_state`),
  ADD KEY `vendors_vendor_city_index` (`vendor_city`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `bank_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `city_db`
--
ALTER TABLE `city_db`
  MODIFY `city_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `companyparameters`
--
ALTER TABLE `companyparameters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `company_master`
--
ALTER TABLE `company_master`
  MODIFY `company_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `countries_db`
--
ALTER TABLE `countries_db`
  MODIFY `country_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customer_company_type`
--
ALTER TABLE `customer_company_type`
  MODIFY `company_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `customer_contact_persons`
--
ALTER TABLE `customer_contact_persons`
  MODIFY `contact_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `customer_locations`
--
ALTER TABLE `customer_locations`
  MODIFY `location_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_master`
--
ALTER TABLE `customer_master`
  MODIFY `customer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `customer_ownership_type`
--
ALTER TABLE `customer_ownership_type`
  MODIFY `ownership_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `division_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `division_parameters`
--
ALTER TABLE `division_parameters`
  MODIFY `division_parameter_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `equipment_type`
--
ALTER TABLE `equipment_type`
  MODIFY `equipment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `followup`
--
ALTER TABLE `followup`
  MODIFY `followup_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoice_hdr`
--
ALTER TABLE `invoice_hdr`
  MODIFY `invoice_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `invoice_hdr_detail`
--
ALTER TABLE `invoice_hdr_detail`
  MODIFY `invoice_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `item_cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `item_master`
--
ALTER TABLE `item_master`
  MODIFY `item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `method_master`
--
ALTER TABLE `method_master`
  MODIFY `method_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
--
-- AUTO_INCREMENT for table `order_master`
--
ALTER TABLE `order_master`
  MODIFY `order_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `order_parameters_detail`
--
ALTER TABLE `order_parameters_detail`
  MODIFY `analysis_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `order_sample_priority`
--
ALTER TABLE `order_sample_priority`
  MODIFY `sample_priority_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `p_category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `product_master`
--
ALTER TABLE `product_master`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `product_test_dtl`
--
ALTER TABLE `product_test_dtl`
  MODIFY `product_test_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `product_test_hdr`
--
ALTER TABLE `product_test_hdr`
  MODIFY `test_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `product_test_parameter_altern_method`
--
ALTER TABLE `product_test_parameter_altern_method`
  MODIFY `product_test_param_altern_method_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `product_test_parameter_bom`
--
ALTER TABLE `product_test_parameter_bom`
  MODIFY `test_BOM_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product_test_parameter_equipment_usage`
--
ALTER TABLE `product_test_parameter_equipment_usage`
  MODIFY `equp_usage_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `req_slip_dtl`
--
ALTER TABLE `req_slip_dtl`
  MODIFY `req_slip_dlt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `req_slip_hdr`
--
ALTER TABLE `req_slip_hdr`
  MODIFY `req_slip_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `req_slip_short_close`
--
ALTER TABLE `req_slip_short_close`
  MODIFY `req_slip_short_close_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rol_setting`
--
ALTER TABLE `rol_setting`
  MODIFY `rol_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `state_db`
--
ALTER TABLE `state_db`
  MODIFY `state_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `status_master`
--
ALTER TABLE `status_master`
  MODIFY `status_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `test_parameter`
--
ALTER TABLE `test_parameter`
  MODIFY `test_parameter_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `test_parameter_categories`
--
ALTER TABLE `test_parameter_categories`
  MODIFY `test_para_cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `test_standard`
--
ALTER TABLE `test_standard`
  MODIFY `test_std_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `units_db`
--
ALTER TABLE `units_db`
  MODIFY `unit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unit_conversion_db`
--
ALTER TABLE `unit_conversion_db`
  MODIFY `unit_conversion_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unit_product_categories`
--
ALTER TABLE `unit_product_categories`
  MODIFY `p_category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users_detail`
--
ALTER TABLE `users_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `city_db`
--
ALTER TABLE `city_db`
  ADD CONSTRAINT `city_db_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `state_db` (`state_id`) ON DELETE CASCADE;

--
-- Constraints for table `company_master`
--
ALTER TABLE `company_master`
  ADD CONSTRAINT `company_master_company_city_foreign` FOREIGN KEY (`company_city`) REFERENCES `city_db` (`city_id`);

--
-- Constraints for table `customer_contact_persons`
--
ALTER TABLE `customer_contact_persons`
  ADD CONSTRAINT `customer_contact_persons_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_locations`
--
ALTER TABLE `customer_locations`
  ADD CONSTRAINT `customer_locations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_master`
--
ALTER TABLE `customer_master`
  ADD CONSTRAINT `customer_master_customer_city_foreign` FOREIGN KEY (`customer_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `customer_master_customer_state_foreign` FOREIGN KEY (`customer_state`) REFERENCES `state_db` (`state_id`),
  ADD CONSTRAINT `customer_master_sale_executive_foreign` FOREIGN KEY (`sale_executive`) REFERENCES `users` (`id`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_master` (`company_id`);

--
-- Constraints for table `divisions`
--
ALTER TABLE `divisions`
  ADD CONSTRAINT `divisions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_master` (`company_id`) ON DELETE CASCADE;

--
-- Constraints for table `division_parameters`
--
ALTER TABLE `division_parameters`
  ADD CONSTRAINT `division_parameters_division_city_foreign` FOREIGN KEY (`division_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `division_parameters_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE CASCADE;

--
-- Constraints for table `followup`
--
ALTER TABLE `followup`
  ADD CONSTRAINT `followup_followup_by_foreign` FOREIGN KEY (`followup_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followup_inquiry_id_foreign` FOREIGN KEY (`inquiry_id`) REFERENCES `inquiry` (`id`);

--
-- Constraints for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD CONSTRAINT `inquiry_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`);

--
-- Constraints for table `invoice_hdr`
--
ALTER TABLE `invoice_hdr`
  ADD CONSTRAINT `invoice_hdr_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `invoice_hdr_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`);

--
-- Constraints for table `invoice_hdr_detail`
--
ALTER TABLE `invoice_hdr_detail`
  ADD CONSTRAINT `invoice_hdr_detail_invoice_hdr_id_foreign` FOREIGN KEY (`invoice_hdr_id`) REFERENCES `invoice_hdr` (`invoice_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_hdr_detail_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `item_master`
--
ALTER TABLE `item_master`
  ADD CONSTRAINT `item_master_item_cat_id_foreign` FOREIGN KEY (`item_cat_id`) REFERENCES `item_categories` (`item_cat_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_master`
--
ALTER TABLE `order_master`
  ADD CONSTRAINT `order_master_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `order_master_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `order_master_sale_executive_foreign` FOREIGN KEY (`sale_executive`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_master_test_product_foreign` FOREIGN KEY (`test_product`) REFERENCES `product_master` (`product_id`),
  ADD CONSTRAINT `order_master_test_standard_foreign` FOREIGN KEY (`test_standard`) REFERENCES `product_test_hdr` (`test_id`);

--
-- Constraints for table `order_parameters_detail`
--
ALTER TABLE `order_parameters_detail`
  ADD CONSTRAINT `order_parameters_detail_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `order_parameters_detail_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `order_parameters_detail_method_id_foreign` FOREIGN KEY (`method_id`) REFERENCES `method_master` (`method_id`),
  ADD CONSTRAINT `order_parameters_detail_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_parameters_detail_product_test_parameter_foreign` FOREIGN KEY (`product_test_parameter`) REFERENCES `product_test_dtl` (`product_test_dtl_id`),
  ADD CONSTRAINT `order_parameters_detail_test_performed_by_foreign` FOREIGN KEY (`test_performed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_master`
--
ALTER TABLE `product_master`
  ADD CONSTRAINT `product_master_p_category_id_foreign` FOREIGN KEY (`p_category_id`) REFERENCES `product_categories` (`p_category_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_test_dtl`
--
ALTER TABLE `product_test_dtl`
  ADD CONSTRAINT `product_test_dtl_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `product_test_dtl_method_id_foreign` FOREIGN KEY (`method_id`) REFERENCES `method_master` (`method_id`),
  ADD CONSTRAINT `product_test_dtl_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `product_test_hdr` (`test_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_dtl_test_parameter_id_foreign` FOREIGN KEY (`test_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`);

--
-- Constraints for table `product_test_hdr`
--
ALTER TABLE `product_test_hdr`
  ADD CONSTRAINT `product_test_hdr_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product_master` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_hdr_test_standard_id_foreign` FOREIGN KEY (`test_standard_id`) REFERENCES `test_standard` (`test_std_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_test_parameter_altern_method`
--
ALTER TABLE `product_test_parameter_altern_method`
  ADD CONSTRAINT `product_test_parameter_altern_method_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `product_test_parameter_altern_method_method_id_foreign` FOREIGN KEY (`method_id`) REFERENCES `method_master` (`method_id`),
  ADD CONSTRAINT `product_test_parameter_altern_method_product_test_dtl_id_foreign` FOREIGN KEY (`product_test_dtl_id`) REFERENCES `product_test_dtl` (`product_test_dtl_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_parameter_altern_method_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `product_test_hdr` (`test_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_parameter_altern_method_test_parameter_id_foreign` FOREIGN KEY (`test_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_test_parameter_bom`
--
ALTER TABLE `product_test_parameter_bom`
  ADD CONSTRAINT `product_test_parameter_bom_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_parameter_bom_product_test_dtl_id_foreign` FOREIGN KEY (`product_test_dtl_id`) REFERENCES `product_test_dtl` (`product_test_dtl_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_parameter_bom_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `product_test_hdr` (`test_id`) ON DELETE CASCADE;

--
-- Constraints for table `req_slip_dtl`
--
ALTER TABLE `req_slip_dtl`
  ADD CONSTRAINT `req_slip_dtl_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rol_setting`
--
ALTER TABLE `rol_setting`
  ADD CONSTRAINT `rol_setting_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `rol_setting_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`);

--
-- Constraints for table `state_db`
--
ALTER TABLE `state_db`
  ADD CONSTRAINT `state_db_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries_db` (`country_id`) ON DELETE CASCADE;

--
-- Constraints for table `test_parameter`
--
ALTER TABLE `test_parameter`
  ADD CONSTRAINT `test_parameter_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `test_parameter_test_parameter_category_foreign` FOREIGN KEY (`test_parameter_category`) REFERENCES `test_parameter_categories` (`test_para_cat_id`) ON DELETE CASCADE;

--
-- Constraints for table `unit_product_categories`
--
ALTER TABLE `unit_product_categories`
  ADD CONSTRAINT `unit_product_categories_p_company_id_foreign` FOREIGN KEY (`p_company_id`) REFERENCES `company_master` (`company_id`) ON DELETE CASCADE;

--
-- Constraints for table `users_detail`
--
ALTER TABLE `users_detail`
  ADD CONSTRAINT `users_detail_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `vendors_vendor_city_foreign` FOREIGN KEY (`vendor_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `vendors_vendor_state_foreign` FOREIGN KEY (`vendor_state`) REFERENCES `state_db` (`state_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
