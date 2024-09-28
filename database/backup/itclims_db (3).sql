-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 28, 2024 at 07:21 PM
-- Server version: 5.7.42-0ubuntu0.18.04.1
-- PHP Version: 7.1.33-51+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itclims_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `central_po_dtls`
--

CREATE TABLE `central_po_dtls` (
  `cpo_id` int(10) UNSIGNED NOT NULL,
  `cpo_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cpo_customer_id` int(10) UNSIGNED NOT NULL,
  `cpo_customer_city` int(10) UNSIGNED NOT NULL,
  `cpo_sample_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cpo_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cpo_amount` decimal(10,2) NOT NULL,
  `cpo_date` datetime NOT NULL,
  `cpo_status` tinyint(4) DEFAULT NULL COMMENT '1 for Active,2 for Deactive',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `central_stp_dtls`
--

CREATE TABLE `central_stp_dtls` (
  `cstp_id` int(10) UNSIGNED NOT NULL,
  `cstp_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cstp_customer_id` int(10) UNSIGNED NOT NULL,
  `cstp_customer_city` int(10) UNSIGNED NOT NULL,
  `cstp_sample_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cstp_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cstp_date` datetime NOT NULL,
  `cstp_status` tinyint(4) DEFAULT NULL COMMENT '1 for Active,2 for Deactive',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city_db`
--

CREATE TABLE `city_db` (
  `city_id` int(10) UNSIGNED NOT NULL,
  `city_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `column_master`
--

CREATE TABLE `column_master` (
  `column_id` int(10) UNSIGNED NOT NULL,
  `column_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `column_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `column_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `equipment_type_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries_db`
--

CREATE TABLE `countries_db` (
  `country_id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_phone_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0 for Inactive and 1 for Active',
  `country_level` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes`
--

CREATE TABLE `credit_notes` (
  `credit_note_id` int(10) UNSIGNED NOT NULL,
  `credit_note_type_id` int(10) UNSIGNED DEFAULT NULL COMMENT '1 for Auto,2 for Manual',
  `division_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `credit_reference_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit_note_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `credit_note_date` date NOT NULL,
  `credit_note_amount` decimal(10,2) NOT NULL,
  `credit_note_sgst_rate` decimal(10,2) DEFAULT NULL,
  `credit_note_sgst_amount` decimal(10,2) DEFAULT NULL,
  `credit_note_cgst_rate` decimal(10,2) DEFAULT NULL,
  `credit_note_cgst_amount` decimal(10,2) DEFAULT NULL,
  `credit_note_igst_rate` decimal(10,2) DEFAULT NULL,
  `credit_note_igst_amount` decimal(10,2) DEFAULT NULL,
  `credit_note_net_amount` decimal(10,2) NOT NULL,
  `credit_note_remark` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_billing_types`
--

CREATE TABLE `customer_billing_types` (
  `billing_type_id` int(10) UNSIGNED NOT NULL,
  `billing_type_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_status` tinyint(4) NOT NULL COMMENT '1 for active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_company_type`
--

CREATE TABLE `customer_company_type` (
  `company_type_id` int(10) UNSIGNED NOT NULL,
  `company_type_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_type_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_com_crm_email_addresses`
--

CREATE TABLE `customer_com_crm_email_addresses` (
  `cccea_id` int(10) UNSIGNED NOT NULL,
  `cccea_division_id` int(10) UNSIGNED NOT NULL,
  `cccea_product_category_id` int(10) UNSIGNED NOT NULL,
  `cccea_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cccea_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cccea_status` tinyint(4) DEFAULT NULL COMMENT '1 for active,2 for Inactive',
  `cccea_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_contact_persons`
--

CREATE TABLE `customer_contact_persons` (
  `contact_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `contact_name1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_name2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_designate1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_designate2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_mobile1` bigint(20) DEFAULT NULL,
  `contact_mobile2` bigint(20) DEFAULT NULL,
  `contact_email1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_email2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_main` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_defined_structures`
--

CREATE TABLE `customer_defined_structures` (
  `cdit_id` int(10) NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `product_category_id` int(10) UNSIGNED DEFAULT NULL,
  `invoicing_type_id` int(10) UNSIGNED DEFAULT NULL,
  `billing_type_id` int(10) UNSIGNED DEFAULT NULL,
  `discount_type_id` int(10) UNSIGNED DEFAULT NULL,
  `discount_value` varchar(255) DEFAULT NULL,
  `tat_editable` tinyint(4) DEFAULT NULL COMMENT '0 or Null for uneditable TAT,1 for editable TAT',
  `customer_invoicing_type_status` tinyint(4) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_discount_types`
--

CREATE TABLE `customer_discount_types` (
  `discount_type_id` int(10) UNSIGNED NOT NULL,
  `discount_type_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `discount_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_email_addresses`
--

CREATE TABLE `customer_email_addresses` (
  `customer_email_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_email_addresses_bk_28M22`
--

CREATE TABLE `customer_email_addresses_bk_28M22` (
  `customer_email_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_excecutives`
--

CREATE TABLE `customer_excecutives` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `employee_code` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_excecutives1`
--

CREATE TABLE `customer_excecutives1` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `employee_code` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_exist_account_hold_upload_dtl`
--

CREATE TABLE `customer_exist_account_hold_upload_dtl` (
  `ceahud_id` int(10) UNSIGNED NOT NULL,
  `ceahud_customer_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ceahud_customer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ceahud_customer_city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ceahud_outstanding_amt` decimal(10,2) DEFAULT NULL,
  `ceahud_ab_outstanding_amt` decimal(10,2) DEFAULT NULL,
  `ceahud_customer_id` int(10) UNSIGNED DEFAULT NULL,
  `ceahud_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_gst_categories`
--

CREATE TABLE `customer_gst_categories` (
  `cgc_id` int(10) UNSIGNED NOT NULL,
  `cgc_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cgc_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cgc_status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_gst_tax_slab_types`
--

CREATE TABLE `customer_gst_tax_slab_types` (
  `cgtst_id` int(10) UNSIGNED NOT NULL,
  `cgtst_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cgtst_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cgtst_status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_gst_types`
--

CREATE TABLE `customer_gst_types` (
  `cgt_id` int(10) UNSIGNED NOT NULL,
  `cgt_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cgt_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cgt_status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_hold_dtl`
--

CREATE TABLE `customer_hold_dtl` (
  `chd_id` int(10) UNSIGNED NOT NULL,
  `chd_customer_id` int(10) UNSIGNED NOT NULL,
  `chd_hold_description` text COLLATE utf8_unicode_ci NOT NULL,
  `chd_hold_date` datetime NOT NULL,
  `chd_hold_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chd_hold_status` tinyint(4) DEFAULT NULL COMMENT '1 for manual hold ,2 for Credit Collection,3 for Account Hold, 4 for Account Hold due to Uploaded Record',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoicing_rates`
--

CREATE TABLE `customer_invoicing_rates` (
  `cir_id` int(10) UNSIGNED NOT NULL,
  `invoicing_type_id` int(10) UNSIGNED NOT NULL,
  `cir_division_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_customer_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_country_id` int(10) UNSIGNED DEFAULT '101',
  `cir_state_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_city_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_c_product_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_product_category_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_p_category_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_sub_p_category_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_test_parameter_category_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_parameter_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_equipment_type_id` int(10) DEFAULT NULL,
  `cir_test_standard_id` int(10) DEFAULT NULL,
  `cir_equipment_count` int(10) DEFAULT NULL,
  `cir_is_detector` tinyint(4) DEFAULT NULL,
  `cir_detector_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_running_time_id` int(10) UNSIGNED DEFAULT NULL,
  `cir_no_of_injection` int(10) DEFAULT NULL,
  `invoicing_rate` decimal(10,2) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoicing_running_time`
--

CREATE TABLE `customer_invoicing_running_time` (
  `invoicing_running_time_id` int(10) UNSIGNED NOT NULL,
  `invoicing_running_time_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoicing_running_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoicing_running_time_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoicing_running_time_status` tinyint(4) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoicing_types`
--

CREATE TABLE `customer_invoicing_types` (
  `invoicing_type_id` int(10) UNSIGNED NOT NULL,
  `invoicing_type_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoicing_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
-- Table structure for table `customer_logic_detail`
--

CREATE TABLE `customer_logic_detail` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_name` varchar(102) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `logic_customer_code_org` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_master`
--

CREATE TABLE `customer_master` (
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logic_customer_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_country` int(10) UNSIGNED NOT NULL,
  `customer_state` int(10) UNSIGNED NOT NULL,
  `customer_city` int(10) UNSIGNED NOT NULL,
  `customer_pincode` int(10) UNSIGNED NOT NULL,
  `customer_type` int(10) NOT NULL,
  `billing_type` int(10) UNSIGNED NOT NULL,
  `invoicing_type_id` int(10) UNSIGNED DEFAULT NULL,
  `sale_executive` int(10) UNSIGNED NOT NULL,
  `discount_type` int(10) NOT NULL,
  `discount_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_vat_cst` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mfg_lic_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ownership_type` int(10) UNSIGNED DEFAULT NULL,
  `company_type` int(10) UNSIGNED DEFAULT NULL,
  `owner_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_pan_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_tan_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_gst_category_id` int(10) DEFAULT NULL,
  `customer_gst_type_id` int(10) DEFAULT NULL,
  `customer_gst_tax_slab_type_id` int(10) DEFAULT NULL,
  `customer_gst_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_priority_id` int(10) UNSIGNED DEFAULT NULL,
  `bank_account_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_account_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_branch_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_rtgs_ifsc_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_status` tinyint(4) DEFAULT '1' COMMENT '0 for Pending,1 for active,2 for inactive,3 for hold',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_ownership_type`
--

CREATE TABLE `customer_ownership_type` (
  `ownership_id` int(10) UNSIGNED NOT NULL,
  `ownership_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ownership_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_priority_types`
--

CREATE TABLE `customer_priority_types` (
  `customer_priority_id` int(10) NOT NULL,
  `customer_priority_name` varchar(255) NOT NULL,
  `customer_priority_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for active,0 for inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_sales_executive_logs`
--

CREATE TABLE `customer_sales_executive_logs` (
  `csel_id` int(10) UNSIGNED NOT NULL,
  `csel_customer_id` int(10) UNSIGNED NOT NULL,
  `csel_customer_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `csel_sale_executive_id` int(10) UNSIGNED NOT NULL,
  `csel_sale_executive_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `csel_created_by` int(10) UNSIGNED DEFAULT NULL,
  `csel_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE `customer_types` (
  `type_id` int(10) UNSIGNED NOT NULL,
  `customer_type_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `customer_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_defined_fields`
--

CREATE TABLE `custom_defined_fields` (
  `label_id` int(10) NOT NULL,
  `label_name` varchar(255) NOT NULL,
  `label_value` varchar(255) NOT NULL,
  `label_status` tinyint(4) UNSIGNED NOT NULL DEFAULT '1',
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `debit_notes`
--

CREATE TABLE `debit_notes` (
  `debit_note_id` int(10) UNSIGNED NOT NULL,
  `debit_note_type_id` int(10) UNSIGNED DEFAULT NULL COMMENT '1 for Auto,2 for Manual',
  `division_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `debit_reference_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `debit_note_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `debit_note_date` date NOT NULL,
  `debit_note_amount` decimal(10,2) NOT NULL,
  `debit_note_sgst_rate` decimal(10,2) DEFAULT NULL,
  `debit_note_sgst_amount` decimal(10,2) DEFAULT NULL,
  `debit_note_cgst_rate` decimal(10,2) DEFAULT NULL,
  `debit_note_cgst_amount` decimal(10,2) DEFAULT NULL,
  `debit_note_igst_rate` decimal(10,2) DEFAULT NULL,
  `debit_note_igst_amount` decimal(10,2) DEFAULT NULL,
  `debit_note_net_amount` decimal(10,2) NOT NULL,
  `debit_note_remark` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `department_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department_type` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `department_product_categories_link`
--

CREATE TABLE `department_product_categories_link` (
  `department_product_categories_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_type`
--

CREATE TABLE `department_type` (
  `department_type_id` int(10) UNSIGNED NOT NULL,
  `department_type_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `detector_master`
--

CREATE TABLE `detector_master` (
  `detector_id` int(10) UNSIGNED NOT NULL,
  `detector_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detector_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detector_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `equipment_type_id` int(10) UNSIGNED DEFAULT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1 for active , 2 for inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `division_parameters`
--

CREATE TABLE `division_parameters` (
  `division_parameter_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `division_address` text COLLATE utf8_unicode_ci NOT NULL,
  `division_country` int(10) UNSIGNED NOT NULL,
  `division_state` int(10) UNSIGNED NOT NULL,
  `division_city` int(10) UNSIGNED NOT NULL,
  `division_PAN` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_VAT_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `division_wise_items`
--

CREATE TABLE `division_wise_items` (
  `division_wise_item_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `msl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `division_wise_item_stock`
--

CREATE TABLE `division_wise_item_stock` (
  `division_wise_item_stock_id` int(10) UNSIGNED NOT NULL,
  `store_id` int(10) UNSIGNED DEFAULT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `openning_balance` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `openning_balance_date` date NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `division_wise_stores`
--

CREATE TABLE `division_wise_stores` (
  `store_id` int(10) UNSIGNED NOT NULL,
  `store_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `store_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_sales_dtl`
--

CREATE TABLE `employee_sales_dtl` (
  `ust_id` int(11) NOT NULL,
  `ust_division_id` int(3) DEFAULT NULL,
  `ust_product_category_id` int(11) DEFAULT NULL,
  `ust_customer_id` int(11) DEFAULT NULL,
  `ust_user_id` int(20) DEFAULT NULL,
  `ust_amount` decimal(10,2) DEFAULT NULL,
  `ust_date` date DEFAULT NULL,
  `ust_status` tinyint(4) DEFAULT NULL,
  `created_by` int(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_type`
--

CREATE TABLE `equipment_type` (
  `equipment_id` int(10) UNSIGNED NOT NULL,
  `equipment_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `equipment_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `equipment_capacity` int(10) NOT NULL,
  `equipment_description` text COLLATE utf8_unicode_ci NOT NULL,
  `product_category_id` int(10) UNSIGNED DEFAULT NULL,
  `is_equipment_selected` tinyint(4) DEFAULT NULL COMMENT '1 for selected,0 for not selected',
  `equipment_sort_by` tinyint(4) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1 for active , 2 for inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holiday_master`
--

CREATE TABLE `holiday_master` (
  `holiday_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `holiday_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `holiday_date` date NOT NULL,
  `holiday_status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ign_hdr`
--

CREATE TABLE `ign_hdr` (
  `ign_hdr_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `ign_date` date DEFAULT NULL,
  `ign_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED DEFAULT NULL,
  `vendor_bill_date` date DEFAULT NULL,
  `vendor_bill_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gate_pass_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(10) UNSIGNED DEFAULT NULL,
  `employee_detail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_bill_amount` decimal(10,2) NOT NULL,
  `total_pass_amount` decimal(10,2) NOT NULL,
  `total_landing_amount` decimal(10,2) NOT NULL,
  `total_sales_tax_amount` decimal(10,2) NOT NULL,
  `total_vat_amount` decimal(10,2) NOT NULL,
  `total_excise_duty_amount` decimal(10,2) NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ign_hdr_dtl`
--

CREATE TABLE `ign_hdr_dtl` (
  `ign_hdr_dtl_id` int(10) UNSIGNED NOT NULL,
  `ign_hdr_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `po_hdr_id` int(10) UNSIGNED DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `bill_qty` int(10) UNSIGNED NOT NULL,
  `received_qty` int(10) UNSIGNED NOT NULL,
  `ok_qty` int(10) UNSIGNED NOT NULL,
  `bill_rate` decimal(10,2) NOT NULL,
  `pass_rate` decimal(10,2) NOT NULL,
  `landing_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indent_hdr`
--

CREATE TABLE `indent_hdr` (
  `indent_hdr_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `indent_date` date NOT NULL,
  `indent_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_closed` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `short_close_date` date DEFAULT NULL,
  `indented_by` int(10) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indent_hdr_detail`
--

CREATE TABLE `indent_hdr_detail` (
  `indent_dtl_id` int(10) UNSIGNED NOT NULL,
  `indent_hdr_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `indent_qty` int(10) NOT NULL,
  `qty_on_po` int(10) NOT NULL,
  `qty_purchased_on_po` int(10) NOT NULL,
  `qty_purchased_direct` int(10) NOT NULL,
  `qty_short_closed` int(10) NOT NULL,
  `required_by_date` date NOT NULL,
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
  `inquiry_detail` text COLLATE utf8_unicode_ci NOT NULL,
  `inquiry_date` date NOT NULL,
  `next_followup_date` date NOT NULL,
  `inquiry_status` enum('open','closed','won') COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiry_followups`
--

CREATE TABLE `inquiry_followups` (
  `followup_id` int(10) UNSIGNED NOT NULL,
  `inquiry_id` int(10) UNSIGNED NOT NULL,
  `followup_by` int(10) UNSIGNED NOT NULL,
  `mode` enum('visit','phone','email','other') COLLATE utf8_unicode_ci NOT NULL,
  `followup_detail` text COLLATE utf8_unicode_ci NOT NULL,
  `next_followup_date` date NOT NULL,
  `status` enum('open','closed','won') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instance_master`
--

CREATE TABLE `instance_master` (
  `instance_id` int(10) UNSIGNED NOT NULL,
  `instance_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `instance_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `instance_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `equipment_type_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_cancellation_dtls`
--

CREATE TABLE `invoice_cancellation_dtls` (
  `invoice_cancellation_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `invoice_cancelled_date` datetime NOT NULL,
  `invoice_cancellation_description` text COLLATE utf8_unicode_ci,
  `invoice_cancelled_by` int(10) UNSIGNED NOT NULL,
  `invoice_canc_approved_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cancellation Approved By',
  `invoice_canc_approved_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_dispatch_dtls`
--

CREATE TABLE `invoice_dispatch_dtls` (
  `invoice_dispatch_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `invoice_dispatch_by` int(10) UNSIGNED NOT NULL,
  `ar_bill_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_dispatch_date` datetime NOT NULL,
  `invoice_dispatch_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_financial_years`
--

CREATE TABLE `invoice_financial_years` (
  `ify_id` int(10) UNSIGNED NOT NULL,
  `ify_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ify_from_date` date NOT NULL,
  `ify_to_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_hdr`
--

CREATE TABLE `invoice_hdr` (
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `invoice_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for active,2 for cancelled',
  `division_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_date` datetime NOT NULL,
  `invoice_type` int(10) UNSIGNED DEFAULT NULL,
  `customer_gst_tax_slab_type_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_invoicing_id` int(10) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `total_discount` decimal(10,2) NOT NULL,
  `surcharge_amount` decimal(10,2) DEFAULT NULL,
  `extra_amount` decimal(10,2) DEFAULT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `sgst_rate` decimal(10,2) DEFAULT NULL,
  `sgst_amount` decimal(10,2) DEFAULT NULL,
  `cgst_rate` decimal(10,2) DEFAULT NULL,
  `cgst_amount` decimal(10,2) DEFAULT NULL,
  `igst_rate` decimal(10,2) DEFAULT NULL,
  `igst_amount` decimal(10,2) DEFAULT NULL,
  `net_total_amount` decimal(10,2) NOT NULL,
  `invoice_file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_file_name_without_hf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inv_fin_yr_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_hdr_detail`
--

CREATE TABLE `invoice_hdr_detail` (
  `invoice_dtl_id` int(10) UNSIGNED NOT NULL,
  `invoice_hdr_status` tinyint(4) DEFAULT NULL COMMENT '1 for active,2 for cancelled',
  `invoice_hdr_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `order_invoicing_to` int(10) UNSIGNED DEFAULT NULL COMMENT 'Alais Customer Id',
  `order_amount` decimal(10,2) NOT NULL,
  `order_discount` decimal(10,2) DEFAULT NULL,
  `extra_amount` decimal(10,2) DEFAULT NULL,
  `surcharge_amount` decimal(10,2) DEFAULT NULL,
  `order_total_amount` decimal(10,2) DEFAULT NULL,
  `order_sgst_rate` decimal(10,2) DEFAULT NULL,
  `order_sgst_amount` decimal(10,2) DEFAULT NULL,
  `order_cgst_rate` decimal(10,2) DEFAULT NULL,
  `order_cgst_amount` decimal(10,2) DEFAULT NULL,
  `order_igst_rate` decimal(10,2) DEFAULT NULL,
  `order_igst_amount` decimal(10,2) DEFAULT NULL,
  `order_net_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_session_dtl`
--

CREATE TABLE `invoice_session_dtl` (
  `invoice_session_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `invoice_session_year` int(10) DEFAULT NULL,
  `invoice_session_status` tinyint(4) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itc_std_rate_dtl`
--

CREATE TABLE `itc_std_rate_dtl` (
  `id` int(11) NOT NULL,
  `department` varchar(9) DEFAULT NULL,
  `test_parameter_code` varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `test_parameter_category_name` varchar(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `equipment_type_name` varchar(38) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `test_parameter_category_id` bigint(20) NOT NULL,
  `test_parameter_id` bigint(10) NOT NULL,
  `equipment_type_id` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `item_cat_id` int(10) UNSIGNED NOT NULL,
  `item_cat_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_cat_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_parent_cat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
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
  `item_unit` int(10) UNSIGNED NOT NULL,
  `item_barcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_description` text COLLATE utf8_unicode_ci NOT NULL,
  `item_long_description` text COLLATE utf8_unicode_ci,
  `item_technical_description` text COLLATE utf8_unicode_ci,
  `item_specification` text COLLATE utf8_unicode_ci,
  `is_perishable` tinyint(4) DEFAULT NULL,
  `shelf_life_days` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
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
  `equipment_type_id` int(10) UNSIGNED DEFAULT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1 for active , 2 for inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `missing_credit_notes`
--

CREATE TABLE `missing_credit_notes` (
  `id` int(11) NOT NULL,
  `credit_note_id` int(4) DEFAULT NULL,
  `product_category_id` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mis_report_default_types`
--

CREATE TABLE `mis_report_default_types` (
  `mis_report_id` int(10) UNSIGNED NOT NULL,
  `mis_report_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mis_report_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mis_report_status` tinyint(4) NOT NULL,
  `mis_report_order_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `module_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_level` int(10) UNSIGNED NOT NULL,
  `module_sort_by` int(10) NOT NULL DEFAULT '1',
  `module_status` int(10) UNSIGNED NOT NULL COMMENT '0 for inactive,1 foractive',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `module_navigations`
--

CREATE TABLE `module_navigations` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED DEFAULT NULL,
  `module_menu_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `module_permissions`
--

CREATE TABLE `module_permissions` (
  `module_permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED DEFAULT NULL,
  `sub_module_id` int(10) UNSIGNED DEFAULT NULL,
  `module_menu_id` int(10) UNSIGNED DEFAULT NULL,
  `add` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 for disable 1 for enable',
  `edit` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 for disable 1 for enable',
  `view` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 for disable 1 for enable',
  `delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 for disable 1 for enable',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_accreditation_certificate_master`
--

CREATE TABLE `order_accreditation_certificate_master` (
  `oac_id` int(10) UNSIGNED NOT NULL,
  `oac_division_id` int(10) UNSIGNED NOT NULL,
  `oac_product_category_id` int(10) UNSIGNED NOT NULL,
  `oac_name` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `oac_multi_location_lab_value` int(10) NOT NULL,
  `oac_status` tinyint(4) DEFAULT NULL COMMENT '0 for inactive,1 for active',
  `oac_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_amended_dtl`
--

CREATE TABLE `order_amended_dtl` (
  `oad_id` int(10) UNSIGNED NOT NULL,
  `oad_order_id` int(10) UNSIGNED NOT NULL,
  `oad_amended_stage` int(10) UNSIGNED NOT NULL,
  `oad_amended_date` datetime NOT NULL,
  `oad_amended_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_amendment_master`
--

CREATE TABLE `order_amendment_master` (
  `oam_id` int(10) UNSIGNED NOT NULL,
  `oam_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `oam_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `oam_status` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for Inactive,1 for Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_analyst_window_settings`
--

CREATE TABLE `order_analyst_window_settings` (
  `oaws_id` int(10) UNSIGNED NOT NULL,
  `oaws_unique_id` int(10) UNSIGNED NOT NULL,
  `oaws_division_id` int(10) UNSIGNED NOT NULL,
  `oaws_product_category_id` int(10) UNSIGNED NOT NULL,
  `oaws_equipment_type_id` int(10) UNSIGNED NOT NULL,
  `oaws_created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_brand_type_dtl`
--

CREATE TABLE `order_brand_type_dtl` (
  `obtd_id` int(10) UNSIGNED NOT NULL,
  `obtd_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `obtd_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_cancellation_dtls`
--

CREATE TABLE `order_cancellation_dtls` (
  `order_cancellation_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `cancellation_type_id` int(10) UNSIGNED NOT NULL,
  `cancellation_description` text COLLATE utf8_unicode_ci NOT NULL,
  `cancelled_date` datetime NOT NULL,
  `recovered_date` datetime DEFAULT NULL,
  `cancelled_by` int(10) UNSIGNED NOT NULL,
  `cancellation_stage` int(10) UNSIGNED NOT NULL COMMENT 'Order Cancellation Stage',
  `cancellation_status` tinyint(3) UNSIGNED NOT NULL COMMENT '1 for Cancelled,2 for recovered',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_cancellation_types`
--

CREATE TABLE `order_cancellation_types` (
  `order_cancellation_type_id` int(10) UNSIGNED NOT NULL,
  `order_cancellation_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_cancellation_type_status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_client_approval_dtl`
--

CREATE TABLE `order_client_approval_dtl` (
  `ocad_id` int(10) UNSIGNED NOT NULL,
  `ocad_order_id` int(10) UNSIGNED NOT NULL,
  `ocad_approved_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ocad_date` date NOT NULL,
  `ocad_credit_period` int(11) NOT NULL,
  `ocad_date_upto_amt` date NOT NULL,
  `ocad_status` tinyint(4) DEFAULT NULL COMMENT '1 for active,2 for Inactive',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_defined_test_std_dtl`
--

CREATE TABLE `order_defined_test_std_dtl` (
  `odtsd_id` int(10) UNSIGNED NOT NULL,
  `odtsd_branch_id` int(10) UNSIGNED NOT NULL,
  `odtsd_product_category_id` int(10) UNSIGNED NOT NULL,
  `odtsd_test_standard_id` int(10) UNSIGNED NOT NULL,
  `odtsd_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_discipline_group_dtl`
--

CREATE TABLE `order_discipline_group_dtl` (
  `odg_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `test_parameter_category_id` int(10) UNSIGNED NOT NULL,
  `discipline_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_dispatch_dtl`
--

CREATE TABLE `order_dispatch_dtl` (
  `dispatch_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `dispatch_by` int(10) UNSIGNED NOT NULL,
  `ar_bill_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dispatch_date` datetime NOT NULL,
  `amend_status` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_dynamic_fields`
--

CREATE TABLE `order_dynamic_fields` (
  `odfs_id` int(10) UNSIGNED NOT NULL,
  `dynamic_field_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dynamic_field_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dynamic_field_status` tinyint(4) DEFAULT NULL,
  `odfs_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_dynamic_field_dtl`
--

CREATE TABLE `order_dynamic_field_dtl` (
  `odf_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `order_field_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_field_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_field_date` datetime NOT NULL,
  `odf_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_expected_due_date_logs`
--

CREATE TABLE `order_expected_due_date_logs` (
  `oeddl_id` int(10) UNSIGNED NOT NULL,
  `oeddl_order_id` int(10) UNSIGNED NOT NULL,
  `oeddl_current_expected_due_date` datetime NOT NULL,
  `oeddl_modified_expected_due_date` datetime NOT NULL,
  `oeddl_no_of_days` int(11) NOT NULL,
  `oeddl_reason_of_change` text COLLATE utf8_unicode_ci NOT NULL,
  `oeddl_exclude_logic_calculation` tinyint(4) DEFAULT NULL COMMENT '1 for exclude, 2 for Include',
  `oeddl_send_mail_status` tinyint(4) DEFAULT NULL COMMENT '1 for Yes, 2 for No',
  `oeddl_modified_date` datetime NOT NULL,
  `oeddl_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_header_notes`
--

CREATE TABLE `order_header_notes` (
  `header_id` int(10) UNSIGNED NOT NULL,
  `header_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `header_limit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'For DT Parameter Limit',
  `header_status` tinyint(4) DEFAULT '0',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_hold_master`
--

CREATE TABLE `order_hold_master` (
  `oh_id` int(10) UNSIGNED NOT NULL,
  `oh_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `oh_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `oh_status` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for Inactive,1 for Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_incharge_dtl`
--

CREATE TABLE `order_incharge_dtl` (
  `oid_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `oid_employee_id` int(10) UNSIGNED NOT NULL,
  `oid_equipment_type_id` int(10) UNSIGNED NOT NULL,
  `oid_assign_date` datetime NOT NULL,
  `oid_confirm_date` datetime DEFAULT NULL,
  `oid_confirm_by` int(10) UNSIGNED DEFAULT NULL,
  `oid_status` tinyint(4) DEFAULT NULL COMMENT '0 for assigned,1 for confirmed,2 for need modification,3 for removed',
  `oid_equipment_ids` longtext,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_incharge_process_dtl`
--

CREATE TABLE `order_incharge_process_dtl` (
  `oipd_id` int(10) UNSIGNED NOT NULL,
  `oipd_order_id` int(10) UNSIGNED NOT NULL,
  `oipd_incharge_id` int(10) UNSIGNED NOT NULL,
  `oipd_analysis_id` int(10) UNSIGNED NOT NULL,
  `oipd_user_id` int(10) UNSIGNED NOT NULL,
  `oipd_date` datetime NOT NULL,
  `oipd_status` tinyint(4) DEFAULT NULL COMMENT '1 for current,2 for previous',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_linked_po_dtl`
--

CREATE TABLE `order_linked_po_dtl` (
  `olpd_id` int(10) UNSIGNED NOT NULL,
  `olpd_order_id` int(10) UNSIGNED NOT NULL,
  `olpd_cpo_id` int(10) UNSIGNED NOT NULL,
  `olpd_cpo_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `olpd_cpo_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `olpd_cpo_sample_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `olpd_cpo_date` datetime NOT NULL,
  `olpd_date` datetime NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_linked_stp_dtl`
--

CREATE TABLE `order_linked_stp_dtl` (
  `olsd_id` int(10) UNSIGNED NOT NULL,
  `olsd_order_id` int(10) UNSIGNED NOT NULL,
  `olsd_cstp_id` int(10) UNSIGNED NOT NULL,
  `olsd_cstp_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `olsd_cstp_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `olsd_cstp_sample_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `olsd_date` datetime NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_linked_trf_dtl`
--

CREATE TABLE `order_linked_trf_dtl` (
  `oltd_id` int(10) UNSIGNED NOT NULL,
  `oltd_order_id` int(10) UNSIGNED NOT NULL,
  `oltd_trf_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `oltd_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `oltd_date` datetime NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_mail_dtl`
--

CREATE TABLE `order_mail_dtl` (
  `mail_id` int(10) UNSIGNED NOT NULL,
  `mail_content_type` tinyint(4) NOT NULL COMMENT '1-New Party Sample booked,2-order placed,3-Report Generation,4-invoice Generation,5-stability prototype order confirmation,6-stability order Notification',
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `stb_order_hdr_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `mail_header` text COLLATE utf8_unicode_ci,
  `mail_body` text COLLATE utf8_unicode_ci,
  `mail_date` datetime NOT NULL,
  `mail_type` tinyint(4) DEFAULT NULL COMMENT '1 for final,2 for Draft',
  `mail_by` int(10) UNSIGNED NOT NULL,
  `mail_status` tinyint(4) NOT NULL,
  `mail_active_type` tinyint(4) DEFAULT NULL COMMENT 'Latest Mail Send',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_mail_status`
--

CREATE TABLE `order_mail_status` (
  `oms_id` int(10) UNSIGNED NOT NULL,
  `order_mail_status_id` int(11) NOT NULL,
  `order_mail_status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_master`
--

CREATE TABLE `order_master` (
  `order_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED DEFAULT NULL,
  `order_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(10) UNSIGNED DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `booking_date` datetime NOT NULL COMMENT 'Current Date of Order Booking',
  `expected_due_date` datetime DEFAULT NULL,
  `order_dept_due_date` datetime DEFAULT NULL,
  `order_report_due_date` datetime DEFAULT NULL,
  `order_scheduled_date` datetime DEFAULT NULL COMMENT 'Order Scheduled Date',
  `test_completion_date` datetime DEFAULT NULL COMMENT 'Test Completion Date',
  `incharge_reviewing_date` datetime DEFAULT NULL COMMENT 'Section Incharge Reviewing Date',
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_city` int(10) UNSIGNED NOT NULL,
  `sale_executive` int(10) UNSIGNED NOT NULL,
  `mfg_lic_no` text COLLATE utf8_unicode_ci NOT NULL,
  `discount_type_id` int(10) UNSIGNED NOT NULL,
  `discount_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sampling_date` datetime DEFAULT NULL,
  `po_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `po_date` datetime DEFAULT NULL,
  `sample_id` int(10) UNSIGNED DEFAULT NULL,
  `sample_priority_id` int(10) UNSIGNED DEFAULT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `sample_description_id` int(10) UNSIGNED NOT NULL,
  `product_test_id` int(10) UNSIGNED NOT NULL,
  `test_standard` int(10) UNSIGNED NOT NULL,
  `defined_test_standard` int(10) UNSIGNED DEFAULT NULL,
  `invoicing_type_id` int(10) UNSIGNED DEFAULT NULL,
  `billing_type_id` int(10) UNSIGNED DEFAULT NULL,
  `manufactured_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supplied_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_as_per_customer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mfg_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiry_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `batch_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `batch_size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_qty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pi_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `letter_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `barcode` longtext COLLATE utf8_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci,
  `is_sealed` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for unsealed,1 for sealed,2 for Intact,3 for N/A',
  `is_signed` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for unsigned,1 for signed,2 for N/A',
  `brand_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `packing_mode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quotation_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `submission_type` tinyint(3) UNSIGNED DEFAULT NULL,
  `advance_details` text COLLATE utf8_unicode_ci,
  `actual_submission_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reporting_to` int(10) UNSIGNED DEFAULT NULL,
  `invoicing_to` int(10) UNSIGNED DEFAULT NULL,
  `surcharge_value` decimal(10,2) DEFAULT NULL,
  `extra_amount` decimal(10,2) DEFAULT NULL,
  `booked_order_amount` decimal(10,2) DEFAULT NULL COMMENT 'Booked Amount',
  `dispatched_date_time` datetime DEFAULT NULL,
  `header_note` text COLLATE utf8_unicode_ci,
  `stability_note` text COLLATE utf8_unicode_ci,
  `order_sample_type` tinyint(4) DEFAULT NULL COMMENT '''1'' for inter-laboratory,''2'' for Compensatory',
  `hold_reason` text COLLATE utf8_unicode_ci,
  `job_order_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_analytical_sheet_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_analytical_sheet_cal_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_reinvoiced_count` int(10) DEFAULT NULL,
  `tat_in_days` int(10) DEFAULT NULL COMMENT 'Entered tat in days',
  `stb_order_hdr_detail_id` int(10) UNSIGNED DEFAULT NULL,
  `sample_condition` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Sample Condition Detail',
  `sampler_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
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
  `product_test_parameter` int(10) UNSIGNED NOT NULL,
  `test_param_alternative_id` int(10) UNSIGNED DEFAULT NULL,
  `test_parameter_id` int(10) UNSIGNED DEFAULT NULL,
  `equipment_type_id` int(10) UNSIGNED DEFAULT NULL,
  `method_id` int(10) UNSIGNED DEFAULT NULL,
  `detector_id` int(10) UNSIGNED DEFAULT NULL,
  `column_id` int(10) UNSIGNED DEFAULT NULL,
  `instance_id` int(10) UNSIGNED DEFAULT NULL,
  `display_decimal_place` tinyint(4) DEFAULT NULL,
  `claim_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `claim_value_unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `standard_value_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `standard_value_from` text COLLATE utf8_unicode_ci,
  `standard_value_to` text COLLATE utf8_unicode_ci,
  `order_parameter_nabl_scope` tinyint(4) DEFAULT NULL COMMENT '0 for without Scope,1 for within Scope',
  `measurement_uncertainty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `limit_determination` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lod` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mrpl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `validation_protocol` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_taken_days` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_taken_mins` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dept_due_date` datetime DEFAULT NULL,
  `report_due_date` datetime DEFAULT NULL,
  `oaws_ui_setting_id` int(10) UNSIGNED DEFAULT NULL,
  `running_time_id` int(10) UNSIGNED DEFAULT NULL,
  `no_of_injection` int(10) UNSIGNED DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `variation_price` decimal(10,2) NOT NULL,
  `analysis_start_date` timestamp NULL DEFAULT NULL COMMENT 'Date of start of Analysis',
  `analysis_completion_date` datetime DEFAULT NULL COMMENT 'Date of completion of Analysis',
  `test_performed_by` int(10) UNSIGNED DEFAULT NULL,
  `test_result` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_process_log`
--

CREATE TABLE `order_process_log` (
  `opl_id` int(10) UNSIGNED NOT NULL,
  `opl_user_id` int(10) UNSIGNED DEFAULT NULL,
  `opl_order_id` int(10) UNSIGNED NOT NULL,
  `opl_order_status_id` int(10) UNSIGNED NOT NULL,
  `opl_date` datetime NOT NULL,
  `opl_current_stage` tinyint(4) NOT NULL COMMENT '1 for current statge',
  `opl_amend_status` tinyint(4) DEFAULT '0',
  `opl_amended_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'Report Amended By',
  `note` text COLLATE utf8_unicode_ci,
  `error_parameter_ids` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_purchase_order_logs`
--

CREATE TABLE `order_purchase_order_logs` (
  `opol_id` int(10) UNSIGNED NOT NULL,
  `opol_order_id` int(10) UNSIGNED NOT NULL,
  `opol_order_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `opol_po_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `opol_po_date` date NOT NULL,
  `opol_created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_details`
--

CREATE TABLE `order_report_details` (
  `order_report_id` int(10) UNSIGNED NOT NULL,
  `report_id` int(10) UNSIGNED NOT NULL,
  `report_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nabl_multi_location_lab_value` tinyint(4) DEFAULT NULL COMMENT 'Value of oac_multi_location_lab_value',
  `nabl_no` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nabl Code',
  `report_date` datetime DEFAULT NULL,
  `reviewing_date` datetime DEFAULT NULL COMMENT 'Report Reviewing Date',
  `reviewed_by` int(10) UNSIGNED DEFAULT NULL,
  `finalizing_date` datetime DEFAULT NULL COMMENT 'Report Finalizing Date',
  `finalized_by` int(10) UNSIGNED DEFAULT NULL,
  `approving_date` datetime DEFAULT NULL COMMENT 'Report Approving Date',
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `report_type` tinyint(4) DEFAULT NULL COMMENT '1 for final,2 for Draft',
  `with_amendment_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_amended_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `grade_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `declared_values` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_sample_value` tinyint(4) DEFAULT NULL COMMENT '1 for yes,2 for no,3 for n/a',
  `result_drived_value` int(10) UNSIGNED DEFAULT NULL COMMENT '1 for yes,2 for no,3 for n/a,4 for group',
  `deviation_value` tinyint(4) DEFAULT NULL COMMENT '1 for yes,2 for no,3 for n/a',
  `remark_value` text COLLATE utf8_unicode_ci,
  `stability_remark_value` text COLLATE utf8_unicode_ci COMMENT 'Stability Remark value added by reviewer',
  `test_standard_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_value` text COLLATE utf8_unicode_ci,
  `report_file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `report_file_name_without_hf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `report_microbiological_name_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Name of the Microbiological Involves in the particular Order',
  `report_microbiological_sign_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Microbiological Sign',
  `header_content` text COLLATE utf8_unicode_ci COMMENT 'Report Header',
  `footer_content` text COLLATE utf8_unicode_ci COMMENT 'Report Footer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_disciplines`
--

CREATE TABLE `order_report_disciplines` (
  `or_discipline_id` int(10) UNSIGNED NOT NULL,
  `or_discipline_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `or_discipline_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `or_discipline_status` tinyint(4) DEFAULT NULL COMMENT '1 for Active,2 for Deactive',
  `or_discipline_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_discipline_parameter_dtls`
--

CREATE TABLE `order_report_discipline_parameter_dtls` (
  `ordp_id` int(10) UNSIGNED NOT NULL,
  `ordp_division_id` int(10) UNSIGNED NOT NULL,
  `ordp_product_category_id` int(10) UNSIGNED NOT NULL,
  `ordp_discipline_id` int(10) UNSIGNED NOT NULL,
  `ordp_test_parameter_category_id` int(10) UNSIGNED NOT NULL,
  `ordp_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_groups`
--

CREATE TABLE `order_report_groups` (
  `org_group_id` int(10) UNSIGNED NOT NULL,
  `org_group_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_group_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_division_id` int(10) UNSIGNED NOT NULL,
  `org_product_category_id` int(10) UNSIGNED NOT NULL,
  `org_group_status` tinyint(4) DEFAULT NULL COMMENT '1 for Active,2 for Deactive',
  `org_group_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_header_types`
--

CREATE TABLE `order_report_header_types` (
  `orht_id` int(10) UNSIGNED NOT NULL,
  `orht_division_id` int(10) UNSIGNED NOT NULL,
  `orht_product_category_id` int(10) UNSIGNED NOT NULL,
  `orht_customer_type` int(10) UNSIGNED NOT NULL,
  `orht_report_hdr_type` int(10) UNSIGNED NOT NULL,
  `orht_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_microbiological_dtl`
--

CREATE TABLE `order_report_microbiological_dtl` (
  `ormbd_id` int(10) UNSIGNED NOT NULL,
  `report_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `report_microbiological_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Name of the Microbiological Involves in the particular Order',
  `report_microbiological_sign` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Microbiological Sign',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_middle_authorized_signs`
--

CREATE TABLE `order_report_middle_authorized_signs` (
  `ormad_id` int(10) UNSIGNED NOT NULL,
  `ormad_order_id` int(10) UNSIGNED NOT NULL,
  `ormad_employee_id` int(10) UNSIGNED NOT NULL,
  `ormad_employee_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ormad_employee_sign` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_note_remark_default`
--

CREATE TABLE `order_report_note_remark_default` (
  `remark_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `remark_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` int(10) NOT NULL COMMENT '1 for notes and 2 for remarks',
  `is_display_stamp` tinyint(4) DEFAULT NULL COMMENT '1 for standard quality stamp,2 for not standard quality stamp',
  `remark_status` tinyint(4) DEFAULT '1',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_options`
--

CREATE TABLE `order_report_options` (
  `report_option_id` int(10) UNSIGNED NOT NULL,
  `report_option_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_settings`
--

CREATE TABLE `order_report_settings` (
  `ors_id` int(10) UNSIGNED NOT NULL,
  `ors_type_id` int(10) UNSIGNED NOT NULL COMMENT '1 for Customer Defined Fields',
  `ors_division_id` int(10) UNSIGNED NOT NULL,
  `ors_product_category_id` int(10) UNSIGNED NOT NULL,
  `ors_column_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ors_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_report_sign_dtls`
--

CREATE TABLE `order_report_sign_dtls` (
  `orsd_id` int(10) UNSIGNED NOT NULL,
  `orsd_employee_id` int(10) UNSIGNED NOT NULL,
  `orsd_division_id` int(10) UNSIGNED NOT NULL,
  `orsd_product_category_id` int(10) UNSIGNED NOT NULL,
  `orsd_equipment_type_id` int(10) UNSIGNED NOT NULL,
  `orsd_created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_sample_priority`
--

CREATE TABLE `order_sample_priority` (
  `sample_priority_id` int(10) UNSIGNED NOT NULL,
  `sample_priority_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sample_priority_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sample_priority_status` tinyint(4) NOT NULL DEFAULT '1',
  `sample_priority_color_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Color Code of Sample Priority',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_sealed_unsealed`
--

CREATE TABLE `order_sealed_unsealed` (
  `osus_id` int(10) UNSIGNED NOT NULL,
  `osus_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `osus_status` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for Inactive,1 for Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_signed_unsigned`
--

CREATE TABLE `order_signed_unsigned` (
  `osu_id` int(10) UNSIGNED NOT NULL,
  `osu_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `osu_status` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for Inactive,1 for Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_stability_notes`
--

CREATE TABLE `order_stability_notes` (
  `stability_id` int(10) UNSIGNED NOT NULL,
  `stability_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stability_status` tinyint(4) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `order_status_id` int(10) UNSIGNED NOT NULL,
  `order_status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_status_alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_status_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `color_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_made_hdr`
--

CREATE TABLE `payment_made_hdr` (
  `payment_made_hdr_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_made_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_made_date` date NOT NULL,
  `payment_made_amount` decimal(10,2) NOT NULL,
  `payment_source_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_received_hdr`
--

CREATE TABLE `payment_received_hdr` (
  `payment_received_hdr_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_source_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_received_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_received_date` date NOT NULL,
  `payment_amount_received` decimal(10,2) NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `payment_sources`
--

CREATE TABLE `payment_sources` (
  `payment_source_id` int(10) UNSIGNED NOT NULL,
  `payment_source_name` varchar(255) NOT NULL,
  `payment_source_description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_hdr`
--

CREATE TABLE `po_hdr` (
  `po_hdr_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `dpo_po_type` tinyint(4) NOT NULL COMMENT '1 for PO,2 For DPO',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for Open,2 for Close',
  `dpo_date` date DEFAULT NULL,
  `dpo_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `po_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_term` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amendment_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amendment_date` date DEFAULT NULL,
  `amendment_detail` text COLLATE utf8_unicode_ci,
  `total_qty` int(10) NOT NULL,
  `gross_total` decimal(10,2) NOT NULL,
  `item_discount` decimal(10,2) DEFAULT NULL,
  `amount_after_discount` decimal(10,2) DEFAULT NULL,
  `excise_duty_rate` decimal(10,2) NOT NULL,
  `amount_after_excise_duty_rate` decimal(10,2) NOT NULL,
  `sales_tax_rate` decimal(10,2) NOT NULL,
  `amount_after_sales_tax_rate` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `short_close_date` date DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_hdr_detail`
--

CREATE TABLE `po_hdr_detail` (
  `po_dtl_id` int(10) UNSIGNED NOT NULL,
  `po_hdr_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `item_rate` decimal(10,2) NOT NULL,
  `purchased_qty` int(10) NOT NULL,
  `item_amount` decimal(10,2) NOT NULL,
  `short_close_qty` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_indent_detail`
--

CREATE TABLE `po_indent_detail` (
  `po_indent_dtl_id` int(10) UNSIGNED NOT NULL,
  `po_hdr_id` int(10) UNSIGNED NOT NULL,
  `indent_dtl_id` int(10) UNSIGNED NOT NULL,
  `qty` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_status`
--

CREATE TABLE `po_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `po_status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `po_status_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `p_category_id` int(10) UNSIGNED NOT NULL,
  `p_category_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `p_category_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `level` tinyint(3) NOT NULL,
  `p_status` tinyint(4) DEFAULT NULL COMMENT '1 for active,0 for inactive',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
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
  `product_barcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_master_alias`
--

CREATE TABLE `product_master_alias` (
  `c_product_id` int(10) UNSIGNED NOT NULL,
  `c_product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `c_product_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `view_type` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_test_dtl`
--

CREATE TABLE `product_test_dtl` (
  `product_test_dtl_id` int(10) UNSIGNED NOT NULL,
  `test_id` int(10) UNSIGNED NOT NULL,
  `test_parameter_id` int(10) UNSIGNED NOT NULL,
  `parameter_sort_by` int(10) DEFAULT NULL,
  `method_id` int(10) UNSIGNED DEFAULT NULL,
  `equipment_type_id` int(10) UNSIGNED DEFAULT NULL,
  `detector_id` int(10) UNSIGNED DEFAULT NULL,
  `claim_dependent` int(10) UNSIGNED NOT NULL,
  `parameter_decimal_place` tinyint(4) DEFAULT NULL COMMENT 'Decimal Format Limit',
  `standard_value_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `standard_value_from` text COLLATE utf8_unicode_ci,
  `standard_value_to` text COLLATE utf8_unicode_ci,
  `time_taken_days_v1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_taken_days` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_taken_mins` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `parameter_nabl_scope` tinyint(4) DEFAULT NULL COMMENT '1 for Yes,0 for No',
  `measurement_uncertainty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `limit_determination` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lod` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mrpl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `validation_protocol` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1 for active , 2 for inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_test_hdr`
--

CREATE TABLE `product_test_hdr` (
  `test_id` int(10) UNSIGNED NOT NULL,
  `test_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `test_standard_id` int(10) UNSIGNED NOT NULL,
  `wef` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `upto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `equipment_type_id` int(10) UNSIGNED DEFAULT NULL,
  `method_id` int(10) UNSIGNED DEFAULT NULL,
  `detector_id` int(10) UNSIGNED DEFAULT NULL,
  `standard_value_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NULL',
  `standard_value_from` text COLLATE utf8_unicode_ci,
  `standard_value_to` text COLLATE utf8_unicode_ci,
  `claim_dependent` int(10) UNSIGNED DEFAULT NULL,
  `time_taken_days` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_taken_mins` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `running_time_id` int(10) UNSIGNED DEFAULT NULL,
  `no_of_injection` int(10) UNSIGNED DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_header_type_default`
--

CREATE TABLE `report_header_type_default` (
  `rhtd_id` int(10) UNSIGNED NOT NULL,
  `rhtd_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rhtd_status` tinyint(4) DEFAULT NULL COMMENT '1 for Active, 2 for Inactive',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `req_slip_dtl`
--

CREATE TABLE `req_slip_dtl` (
  `req_slip_dlt_id` int(10) UNSIGNED NOT NULL,
  `req_slip_hdr_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `required_qty` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `req_slip_hdr`
--

CREATE TABLE `req_slip_hdr` (
  `req_slip_id` int(10) UNSIGNED NOT NULL,
  `req_slip_date` date NOT NULL,
  `req_slip_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `req_department_id` int(10) UNSIGNED NOT NULL,
  `req_by` int(10) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `short_close_date` date NOT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `req_slip_short_close`
--

CREATE TABLE `req_slip_short_close` (
  `req_slip_short_close_id` int(10) UNSIGNED NOT NULL,
  `req_slip_short_close_dt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `req_slip_short_close_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL COMMENT '0 for inactive,1 for active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '1-Administrator,2-Employer,3-Sample Receiver,4-Order Booker,5 -Job Scheduler.6-Tester, 7-Reporter,8-Reviewer,9-Finalizer,10-Approval,11-Invoice generator',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `samples`
--

CREATE TABLE `samples` (
  `sample_id` int(10) UNSIGNED NOT NULL,
  `sample_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `sample_date` datetime NOT NULL,
  `sample_current_date` datetime NOT NULL,
  `sample_booked_date` datetime DEFAULT NULL COMMENT 'Booking Date of Sample',
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sample_mode_id` int(10) UNSIGNED NOT NULL,
  `sample_mode_description` longtext COLLATE utf8_unicode_ci,
  `sample_status` tinyint(4) DEFAULT NULL COMMENT 'o for received,1 for booked,2 for waiting and 3 for hold',
  `internal_transfer` tinyint(4) DEFAULT NULL COMMENT '1 for internal transfer',
  `trf_id` int(10) UNSIGNED DEFAULT NULL,
  `remarks` longtext COLLATE utf8_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sample_modes`
--

CREATE TABLE `sample_modes` (
  `sample_mode_id` int(10) UNSIGNED NOT NULL,
  `sample_mode_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sample_mode_status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sample_status_default`
--

CREATE TABLE `sample_status_default` (
  `sample_status_id` int(10) UNSIGNED NOT NULL,
  `sample_status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sample_status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_mail_dtl`
--

CREATE TABLE `scheduled_mail_dtl` (
  `smd_id` int(10) UNSIGNED NOT NULL,
  `smd_content_type` tinyint(3) UNSIGNED NOT NULL COMMENT '1 for VOC,2 for Order Confirmation',
  `smd_customer_id` int(10) UNSIGNED NOT NULL,
  `smd_order_ids` longtext COLLATE utf8_unicode_ci NOT NULL,
  `smd_template_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smd_status` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for unsend,1 for send',
  `smd_date` datetime NOT NULL,
  `smd_mail_counter` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_mis_report_dtls`
--

CREATE TABLE `scheduled_mis_report_dtls` (
  `smrd_id` int(10) UNSIGNED NOT NULL,
  `smrd_division_id` int(10) UNSIGNED NOT NULL,
  `smrd_product_category_id` int(10) UNSIGNED NOT NULL,
  `smrd_mis_report_id` int(10) UNSIGNED NOT NULL,
  `smrd_to_email_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smrd_from_email_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smrd_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedulings`
--

CREATE TABLE `schedulings` (
  `scheduling_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '0 for Unassigned,1 for Assigned,2 for Pending,3 for Completed',
  `order_id` int(10) UNSIGNED NOT NULL,
  `order_parameter_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED DEFAULT NULL,
  `equipment_type_id` int(10) UNSIGNED DEFAULT NULL,
  `employee_id` int(10) UNSIGNED DEFAULT NULL,
  `tentative_date` date DEFAULT NULL,
  `tentative_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `scheduled_at` datetime DEFAULT NULL COMMENT 'Scheduled Date',
  `scheduled_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'Schuduled By',
  `completed_at` datetime DEFAULT NULL COMMENT 'Test Completion Date',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(10) NOT NULL,
  `setting_group_id` int(10) UNSIGNED NOT NULL,
  `product_category_id` int(10) UNSIGNED DEFAULT NULL,
  `setting_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `setting_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `setting_status` tinyint(4) NOT NULL,
  `is_display` tinyint(4) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting_groups`
--

CREATE TABLE `setting_groups` (
  `setting_group_id` int(10) UNSIGNED NOT NULL,
  `setting_group_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `setting_group_status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `state_db`
--

CREATE TABLE `state_db` (
  `state_id` int(10) UNSIGNED NOT NULL,
  `state_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `state_status` tinyint(4) NOT NULL DEFAULT '1',
  `state_level` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `stb_order_hdr`
--

CREATE TABLE `stb_order_hdr` (
  `stb_order_hdr_id` int(10) UNSIGNED NOT NULL,
  `stb_prototype_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stb_status` int(10) NOT NULL,
  `stb_prototype_date` datetime NOT NULL,
  `stb_division_id` int(10) UNSIGNED NOT NULL,
  `stb_product_category_id` int(10) UNSIGNED NOT NULL,
  `stb_sample_id` int(10) UNSIGNED DEFAULT NULL,
  `stb_customer_id` int(10) UNSIGNED NOT NULL,
  `stb_sale_executive` int(10) UNSIGNED NOT NULL,
  `stb_mfg_lic_no` text COLLATE utf8_unicode_ci NOT NULL,
  `stb_customer_city` int(10) UNSIGNED NOT NULL,
  `stb_discount_type_id` int(10) UNSIGNED NOT NULL,
  `stb_discount_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_invoicing_type_id` int(10) UNSIGNED NOT NULL,
  `stb_billing_type_id` int(10) UNSIGNED NOT NULL,
  `stb_product_id` int(10) NOT NULL,
  `stb_sample_description_id` int(10) UNSIGNED NOT NULL,
  `stb_batch_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stb_reference_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_letter_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_brand_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stb_batch_size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_sample_qty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_sample_qty_unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_sample_priority_id` int(10) UNSIGNED NOT NULL,
  `stb_is_sealed` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for unsealed,1 for sealed,2 for Intact,3 for N/A',
  `stb_is_signed` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for unsigned,1 for signed,2 for N/A',
  `stb_packing_mode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_quotation_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_submission_type` tinyint(3) UNSIGNED DEFAULT NULL,
  `stb_actual_submission_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_advance_details` text COLLATE utf8_unicode_ci,
  `stb_pi_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_manufactured_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_supplied_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_mfg_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_expiry_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_remarks` text COLLATE utf8_unicode_ci,
  `stb_sampling_date` datetime DEFAULT NULL,
  `stb_extra_amount` decimal(10,2) DEFAULT NULL,
  `stb_po_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_po_date` datetime DEFAULT NULL,
  `stb_reporting_to` int(10) UNSIGNED DEFAULT NULL,
  `stb_invoicing_to` int(10) UNSIGNED DEFAULT NULL,
  `stb_surcharge_value` decimal(10,2) DEFAULT NULL,
  `stb_product_description` text COLLATE utf8_unicode_ci,
  `stb_sample_pack` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_storage_cond_sample_pack` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_sample_pack_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_orientation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stb_created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stb_order_hdr_dtl`
--

CREATE TABLE `stb_order_hdr_dtl` (
  `stb_order_hdr_dtl_id` int(10) UNSIGNED NOT NULL,
  `stb_order_book_status` tinyint(4) DEFAULT NULL,
  `stb_order_hdr_id` int(10) UNSIGNED NOT NULL,
  `stb_product_id` int(10) UNSIGNED NOT NULL,
  `stb_test_standard_id` int(10) UNSIGNED NOT NULL,
  `stb_product_test_id` int(10) UNSIGNED NOT NULL,
  `stb_start_date` datetime NOT NULL,
  `stb_end_date` datetime NOT NULL,
  `stb_label_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stb_order_hdr_dtl_detail`
--

CREATE TABLE `stb_order_hdr_dtl_detail` (
  `stb_order_hdr_detail_id` int(10) UNSIGNED NOT NULL,
  `stb_order_hdr_detail_status` tinyint(4) DEFAULT '0' COMMENT '0 for pending,1 for booked',
  `stb_order_hdr_id` int(10) UNSIGNED NOT NULL,
  `stb_order_hdr_dtl_id` int(10) UNSIGNED NOT NULL,
  `stb_stability_type_id` int(10) UNSIGNED NOT NULL,
  `stb_product_test_dtl_id` int(10) UNSIGNED NOT NULL,
  `stb_dtl_sample_qty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stb_condition_temperature` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Stability Temperature',
  `stb_product_test_stf_id` tinyint(4) DEFAULT NULL COMMENT '1 for STF,0 for Not',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stb_order_noti_dtl`
--

CREATE TABLE `stb_order_noti_dtl` (
  `stb_order_noti_dtl_id` int(10) UNSIGNED NOT NULL,
  `stb_order_hdr_id` int(10) UNSIGNED NOT NULL,
  `stb_order_hdr_dtl_id` int(10) UNSIGNED NOT NULL,
  `stb_order_noti_dtl_date` datetime NOT NULL,
  `stb_order_noti_confirm_date` datetime DEFAULT NULL,
  `stb_order_noti_confirm_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stb_order_sample_qty_logs`
--

CREATE TABLE `stb_order_sample_qty_logs` (
  `stb_sq_logs_id` int(10) UNSIGNED NOT NULL,
  `stb_order_hdr_id` int(10) UNSIGNED NOT NULL,
  `stb_log_sample_qty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stb_order_stability_types`
--

CREATE TABLE `stb_order_stability_types` (
  `stb_stability_type_id` int(10) UNSIGNED NOT NULL,
  `stb_stability_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stb_stability_type_status` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for Inactive,1 for Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_dtl`
--

CREATE TABLE `template_dtl` (
  `template_id` int(10) UNSIGNED NOT NULL,
  `template_type_id` int(10) UNSIGNED NOT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `product_category_id` int(10) UNSIGNED DEFAULT NULL,
  `header_content` text COLLATE utf8_unicode_ci NOT NULL,
  `footer_content` text COLLATE utf8_unicode_ci,
  `template_status_id` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_types`
--

CREATE TABLE `template_types` (
  `template_type_id` int(10) UNSIGNED NOT NULL,
  `template_type_name` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_parameter`
--

CREATE TABLE `test_parameter` (
  `test_parameter_id` int(10) UNSIGNED NOT NULL,
  `test_parameter_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_parameter_name` text COLLATE utf8_unicode_ci NOT NULL,
  `test_parameter_print_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `test_parameter_category_id` int(10) UNSIGNED NOT NULL,
  `test_parameter_invoicing` int(10) UNSIGNED DEFAULT NULL,
  `test_parameter_invoicing_parent_id` int(10) UNSIGNED DEFAULT NULL,
  `test_parameter_nabl_scope` tinyint(4) DEFAULT NULL COMMENT '1 for Yes,0 for No',
  `test_parameter_decimal_place` tinyint(4) DEFAULT NULL COMMENT 'Decimal Format Limit',
  `cost_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `tpn_editor_status` tinyint(2) DEFAULT NULL COMMENT '1 for enable and 0 for disable',
  `tpd_editor_status` tinyint(2) DEFAULT NULL COMMENT '1 for enable and 0 for disable',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1 for active , 2 for inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `level` int(10) DEFAULT NULL,
  `category_sort_by` int(10) DEFAULT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1 for active , 2 for inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_parameter_equipment_types`
--

CREATE TABLE `test_parameter_equipment_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `test_parameter_id` int(10) UNSIGNED NOT NULL,
  `equipment_type_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_parameter_invoicing_parents`
--

CREATE TABLE `test_parameter_invoicing_parents` (
  `tpip_id` int(10) UNSIGNED NOT NULL,
  `test_parameter_id` int(10) UNSIGNED DEFAULT NULL,
  `test_parameter_status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `test_parameter_invoicing_parents_new`
--

CREATE TABLE `test_parameter_invoicing_parents_new` (
  `id` int(11) NOT NULL,
  `parameterid` varchar(10) DEFAULT NULL,
  `Parameter name` varchar(28) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `test_standard`
--

CREATE TABLE `test_standard` (
  `test_std_id` int(10) UNSIGNED NOT NULL,
  `test_std_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_std_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `test_std_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1 for active , 2 for inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trf_hdrs`
--

CREATE TABLE `trf_hdrs` (
  `trf_id` int(10) UNSIGNED NOT NULL,
  `trf_status` tinyint(4) DEFAULT NULL COMMENT '0 for pending,2 for Booked',
  `trf_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trf_date` datetime NOT NULL,
  `trf_division_id` int(10) UNSIGNED NOT NULL,
  `trf_product_category_id` int(10) UNSIGNED NOT NULL,
  `trf_type` tinyint(4) DEFAULT NULL COMMENT '1 for Master Data Selection,2 for Manual Data Addition',
  `trf_customer_id` int(10) UNSIGNED NOT NULL,
  `trf_product_test_id` int(10) UNSIGNED DEFAULT NULL,
  `trf_test_standard_id` int(10) UNSIGNED DEFAULT NULL,
  `trf_p_category_id` int(10) UNSIGNED DEFAULT NULL,
  `trf_sub_p_category_id` int(10) UNSIGNED DEFAULT NULL,
  `trf_product_id` int(10) UNSIGNED DEFAULT NULL,
  `trf_test_standard_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_product_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_sample_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trf_mfg_lic_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_manufactured_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_supplied_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_mfg_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_expiry_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_batch_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_batch_size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_sample_qty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_reporting_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_invoicing_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_reporting_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_invoicing_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trf_storage_condition_id` int(10) UNSIGNED DEFAULT NULL,
  `trf_active_deactive_status` tinyint(4) DEFAULT NULL COMMENT '1 for Active,2 for Deactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trf_hdr_dtls`
--

CREATE TABLE `trf_hdr_dtls` (
  `trf_hdr_dtl_id` int(10) UNSIGNED NOT NULL,
  `trf_hdr_id` int(10) UNSIGNED NOT NULL,
  `trf_product_test_dtl_id` int(10) UNSIGNED DEFAULT NULL,
  `trf_test_parameter_id` int(10) UNSIGNED DEFAULT NULL,
  `trf_test_parameter_name` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trf_storge_condition_dtls`
--

CREATE TABLE `trf_storge_condition_dtls` (
  `trf_sc_id` int(10) UNSIGNED NOT NULL,
  `trf_sc_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trf_sc_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_product_categories`
--

CREATE TABLE `unit_product_categories` (
  `p_category_id` int(10) UNSIGNED NOT NULL,
  `p_category_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `p_category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `p_parent_id` int(10) NOT NULL,
  `p_company_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `role_id` tinyint(4) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_sales_person` int(10) UNSIGNED DEFAULT NULL,
  `is_sampler_person` int(10) UNSIGNED DEFAULT NULL,
  `user_signature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for active 2 for deactive',
  `activated_at` timestamp NULL DEFAULT NULL,
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `password_changed_at` datetime DEFAULT NULL COMMENT 'Password Changed Date',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `users_department_detail`
--

CREATE TABLE `users_department_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_equipment_detail`
--

CREATE TABLE `users_equipment_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `equipment_type_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_custom_permissions`
--

CREATE TABLE `user_custom_permissions` (
  `ucp_id` int(10) UNSIGNED NOT NULL,
  `ucp_user_id` int(10) UNSIGNED NOT NULL,
  `ucp_permission_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ucp_permission_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_log_activities`
--

CREATE TABLE `user_log_activities` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` text COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sales_target_details`
--

CREATE TABLE `user_sales_target_details` (
  `ust_id` int(10) UNSIGNED NOT NULL,
  `ust_user_id` int(10) UNSIGNED NOT NULL,
  `ust_division_id` int(10) UNSIGNED NOT NULL,
  `ust_product_category_id` int(10) UNSIGNED NOT NULL,
  `ust_customer_id` int(10) UNSIGNED NOT NULL,
  `ust_type_id` int(10) UNSIGNED NOT NULL,
  `ust_month` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ust_year` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ust_date` date NOT NULL,
  `ust_amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ust_status` tinyint(4) DEFAULT NULL COMMENT '1 for Active,2 for Deactive',
  `ust_fin_year_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sales_target_types`
--

CREATE TABLE `user_sales_target_types` (
  `usty_id` int(10) UNSIGNED NOT NULL,
  `usty_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `usty_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `usty_status` tinyint(4) DEFAULT NULL COMMENT '1 for Active,2 for Deactive',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voc_mail_dtl`
--

CREATE TABLE `voc_mail_dtl` (
  `voc_id` int(10) UNSIGNED NOT NULL,
  `voc_customer_id` int(10) UNSIGNED NOT NULL,
  `voc_order_ids` longtext COLLATE utf8_unicode_ci NOT NULL,
  `voc_template_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `voc_status` tinyint(3) UNSIGNED NOT NULL COMMENT '0 for unsend,1 for send',
  `voc_mail_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `central_po_dtls`
--
ALTER TABLE `central_po_dtls`
  ADD PRIMARY KEY (`cpo_id`),
  ADD KEY `central_po_dtls_cpo_customer_id_index` (`cpo_customer_id`),
  ADD KEY `central_po_dtls_cpo_customer_city_index` (`cpo_customer_city`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `cpo_no` (`cpo_no`),
  ADD KEY `cpo_sample_name` (`cpo_sample_name`),
  ADD KEY `cpo_date` (`cpo_date`);

--
-- Indexes for table `central_stp_dtls`
--
ALTER TABLE `central_stp_dtls`
  ADD PRIMARY KEY (`cstp_id`),
  ADD KEY `central_stp_dtls_cstp_customer_id_index` (`cstp_customer_id`),
  ADD KEY `central_stp_dtls_cstp_customer_city_index` (`cstp_customer_city`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `cstp_no` (`cstp_no`),
  ADD KEY `cstp_date` (`cstp_date`),
  ADD KEY `cstp_sample_name` (`cstp_sample_name`);

--
-- Indexes for table `city_db`
--
ALTER TABLE `city_db`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `city_name` (`city_name`,`state_id`),
  ADD KEY `city_db_state_id_index` (`state_id`),
  ADD KEY `city_db_created_by_foreign` (`created_by`),
  ADD KEY `city_code` (`city_code`);

--
-- Indexes for table `column_master`
--
ALTER TABLE `column_master`
  ADD PRIMARY KEY (`column_id`),
  ADD UNIQUE KEY `column_master_column_code_unique` (`column_code`),
  ADD UNIQUE KEY `column_master_column_name_unique` (`column_name`),
  ADD KEY `column_master_equipment_type_id_index` (`equipment_type_id`),
  ADD KEY `column_master_product_category_id_index` (`product_category_id`),
  ADD KEY `column_master_created_by_index` (`created_by`);

--
-- Indexes for table `company_master`
--
ALTER TABLE `company_master`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `company_master_company_code_unique` (`company_code`),
  ADD KEY `company_master_company_city_index` (`company_city`),
  ADD KEY `company_master_created_by_foreign` (`created_by`),
  ADD KEY `company_name` (`company_name`);

--
-- Indexes for table `countries_db`
--
ALTER TABLE `countries_db`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `country_name` (`country_name`),
  ADD UNIQUE KEY `country_code` (`country_code`),
  ADD KEY `country_phone_code` (`country_phone_code`);

--
-- Indexes for table `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD PRIMARY KEY (`credit_note_id`),
  ADD UNIQUE KEY `credit_note_no` (`credit_note_no`),
  ADD KEY `credit_notes_division_id_index` (`division_id`),
  ADD KEY `credit_notes_customer_id_index` (`customer_id`),
  ADD KEY `credit_notes_created_by_index` (`created_by`),
  ADD KEY `credit_notes_invoice_id_foreign` (`invoice_id`),
  ADD KEY `credit_note_type_id` (`credit_note_type_id`),
  ADD KEY `credit_note_date` (`credit_note_date`),
  ADD KEY `product_category_id` (`product_category_id`);

--
-- Indexes for table `customer_billing_types`
--
ALTER TABLE `customer_billing_types`
  ADD PRIMARY KEY (`billing_type_id`),
  ADD UNIQUE KEY `billing_type_code` (`billing_type_code`),
  ADD KEY `billing_type` (`billing_type`);

--
-- Indexes for table `customer_company_type`
--
ALTER TABLE `customer_company_type`
  ADD PRIMARY KEY (`company_type_id`),
  ADD UNIQUE KEY `company_type_code_2` (`company_type_code`),
  ADD KEY `customer_company_type_created_by_foreign` (`created_by`),
  ADD KEY `company_type_code` (`company_type_code`),
  ADD KEY `company_type_name` (`company_type_name`);

--
-- Indexes for table `customer_com_crm_email_addresses`
--
ALTER TABLE `customer_com_crm_email_addresses`
  ADD PRIMARY KEY (`cccea_id`),
  ADD KEY `customer_com_crm_email_addresses_cccea_division_id_index` (`cccea_division_id`),
  ADD KEY `customer_com_crm_email_addresses_cccea_product_category_id_index` (`cccea_product_category_id`),
  ADD KEY `customer_com_crm_email_addresses_cccea_created_by_index` (`cccea_created_by`);

--
-- Indexes for table `customer_contact_persons`
--
ALTER TABLE `customer_contact_persons`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `customer_contact_persons_customer_id_index` (`customer_id`),
  ADD KEY `contact_name1` (`contact_name1`),
  ADD KEY `contact_name2` (`contact_name2`),
  ADD KEY `contact_mobile1` (`contact_mobile1`),
  ADD KEY `contact_mobile2` (`contact_mobile2`);

--
-- Indexes for table `customer_defined_structures`
--
ALTER TABLE `customer_defined_structures`
  ADD PRIMARY KEY (`cdit_id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`,`division_id`,`product_category_id`) USING BTREE,
  ADD KEY `customer_defined_structures_ product_cayegory_id _foreign` (`product_category_id`),
  ADD KEY `customer_defined_structures_invoicing_type_id_foreign` (`invoicing_type_id`),
  ADD KEY `customer_defined_structures_ billing_type_id _foreign` (`billing_type_id`),
  ADD KEY `customer_defined_structures_ discount_type_id _foreign` (`discount_type_id`),
  ADD KEY `customer_defined_structures_ division_id _foreign` (`division_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `customer_discount_types`
--
ALTER TABLE `customer_discount_types`
  ADD PRIMARY KEY (`discount_type_id`),
  ADD UNIQUE KEY `discount_type_code_2` (`discount_type_code`),
  ADD KEY `discount_type_code` (`discount_type_code`),
  ADD KEY `discount_type` (`discount_type`);

--
-- Indexes for table `customer_email_addresses`
--
ALTER TABLE `customer_email_addresses`
  ADD PRIMARY KEY (`customer_email_id`),
  ADD KEY `customer_email_addresses_customer_id_index` (`customer_id`),
  ADD KEY `customer_email` (`customer_email`),
  ADD KEY `customer_email_type` (`customer_email_type`);

--
-- Indexes for table `customer_email_addresses_bk_28M22`
--
ALTER TABLE `customer_email_addresses_bk_28M22`
  ADD PRIMARY KEY (`customer_email_id`),
  ADD KEY `customer_email_addresses_customer_id_index` (`customer_id`),
  ADD KEY `customer_email` (`customer_email`),
  ADD KEY `customer_email_type` (`customer_email_type`);

--
-- Indexes for table `customer_excecutives`
--
ALTER TABLE `customer_excecutives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_excecutives1`
--
ALTER TABLE `customer_excecutives1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_exist_account_hold_upload_dtl`
--
ALTER TABLE `customer_exist_account_hold_upload_dtl`
  ADD PRIMARY KEY (`ceahud_id`),
  ADD KEY `customer_exist_account_hold_upload_dtl_ceahud_customer_id_index` (`ceahud_customer_id`),
  ADD KEY `customer_exist_account_hold_upload_dtl_ceahud_created_by_index` (`ceahud_created_by`);

--
-- Indexes for table `customer_gst_categories`
--
ALTER TABLE `customer_gst_categories`
  ADD PRIMARY KEY (`cgc_id`),
  ADD UNIQUE KEY `cgc_code` (`cgc_code`),
  ADD UNIQUE KEY `cgc_code_2` (`cgc_code`),
  ADD KEY `cgc_name` (`cgc_name`);

--
-- Indexes for table `customer_gst_tax_slab_types`
--
ALTER TABLE `customer_gst_tax_slab_types`
  ADD PRIMARY KEY (`cgtst_id`),
  ADD UNIQUE KEY `cgtst_code_2` (`cgtst_code`),
  ADD KEY `cgtst_code` (`cgtst_code`),
  ADD KEY `cgtst_name` (`cgtst_name`);

--
-- Indexes for table `customer_gst_types`
--
ALTER TABLE `customer_gst_types`
  ADD PRIMARY KEY (`cgt_id`),
  ADD UNIQUE KEY `cgt_code` (`cgt_code`),
  ADD KEY `cgt_name` (`cgt_name`);

--
-- Indexes for table `customer_hold_dtl`
--
ALTER TABLE `customer_hold_dtl`
  ADD PRIMARY KEY (`chd_id`),
  ADD KEY `customer_hold_dtl_chd_customer_id_index` (`chd_customer_id`),
  ADD KEY `chd_hold_date` (`chd_hold_date`),
  ADD KEY `chd_hold_by` (`chd_hold_by`);

--
-- Indexes for table `customer_invoicing_rates`
--
ALTER TABLE `customer_invoicing_rates`
  ADD PRIMARY KEY (`cir_id`),
  ADD KEY `customer_invoicing_rates_invoicing_type_id_index` (`invoicing_type_id`),
  ADD KEY `customer_invoicing_rates_cir_state_id_index` (`cir_state_id`),
  ADD KEY `customer_invoicing_rates_cir_city_id_index` (`cir_city_id`),
  ADD KEY `customer_invoicing_rates_cir_customer_id_index` (`cir_customer_id`),
  ADD KEY `customer_invoicing_rates_cir_c_product_id_index` (`cir_c_product_id`),
  ADD KEY `customer_invoicing_rates_cir_parameter_id_index` (`cir_parameter_id`),
  ADD KEY `customer_invoicing_rates_created_by_index` (`created_by`),
  ADD KEY `customer_invoicing_rates_cir_product_category_id_foreign` (`cir_product_category_id`),
  ADD KEY `cir_p_category_id` (`cir_p_category_id`),
  ADD KEY `cir_sub_p_category_id` (`cir_sub_p_category_id`),
  ADD KEY `cir_equipment_type_id` (`cir_equipment_type_id`),
  ADD KEY `cir_test_standard_id` (`cir_test_standard_id`),
  ADD KEY `cir_equipment_count` (`cir_equipment_count`),
  ADD KEY `cir_is_detector` (`cir_is_detector`),
  ADD KEY `cir_detector_id` (`cir_detector_id`),
  ADD KEY `invoicing_rate` (`invoicing_rate`),
  ADD KEY `cir_running_time_id` (`cir_running_time_id`),
  ADD KEY `cir_no_of_injection` (`cir_no_of_injection`),
  ADD KEY `cir_test_parameter_category_id` (`cir_test_parameter_category_id`),
  ADD KEY `customer_invoicing_rates_ cir_division_id _foreign` (`cir_division_id`),
  ADD KEY `cir_country_id` (`cir_country_id`);

--
-- Indexes for table `customer_invoicing_running_time`
--
ALTER TABLE `customer_invoicing_running_time`
  ADD PRIMARY KEY (`invoicing_running_time_id`),
  ADD KEY `invoicing_running_time_id` (`invoicing_running_time_id`),
  ADD KEY `invoicing_running_time_code` (`invoicing_running_time_code`),
  ADD KEY `invoicing_running_time` (`invoicing_running_time_key`),
  ADD KEY `invoicing_running_time_value` (`invoicing_running_time`);

--
-- Indexes for table `customer_invoicing_types`
--
ALTER TABLE `customer_invoicing_types`
  ADD PRIMARY KEY (`invoicing_type_id`),
  ADD KEY `invoicing_type_code` (`invoicing_type_code`),
  ADD KEY `invoicing_type` (`invoicing_type`);

--
-- Indexes for table `customer_locations`
--
ALTER TABLE `customer_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD UNIQUE KEY `customer_locations_location_code_unique` (`location_code`),
  ADD KEY `customer_locations_customer_id_index` (`customer_id`);

--
-- Indexes for table `customer_logic_detail`
--
ALTER TABLE `customer_logic_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_master`
--
ALTER TABLE `customer_master`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`),
  ADD UNIQUE KEY `logic_customer_code` (`logic_customer_code`),
  ADD KEY `customer_master_customer_state_index` (`customer_state`),
  ADD KEY `customer_master_customer_city_index` (`customer_city`),
  ADD KEY `customer_master_sale_executive_index` (`sale_executive`),
  ADD KEY `customer_master_ownership_type_index` (`ownership_type`),
  ADD KEY `customer_master_company_type_index` (`company_type`),
  ADD KEY `customer_master_created_by_foreign` (`created_by`),
  ADD KEY `customer_master_invoicing_type_id_foreign` (`invoicing_type_id`),
  ADD KEY `customer_master_billing_type_foreign` (`billing_type`),
  ADD KEY `customer_priority_id` (`customer_priority_id`),
  ADD KEY `customer_master_customer_country_foreign` (`customer_country`),
  ADD KEY `customer_code` (`customer_code`),
  ADD KEY `customer_gst_category_id` (`customer_gst_category_id`),
  ADD KEY `customer_gst_type_id` (`customer_gst_type_id`),
  ADD KEY `customer_gst_tax_slab_type_id` (`customer_gst_tax_slab_type_id`),
  ADD KEY `customer_name` (`customer_name`),
  ADD KEY `customer_phone` (`customer_phone`),
  ADD KEY `customer_mobile` (`customer_mobile`);

--
-- Indexes for table `customer_ownership_type`
--
ALTER TABLE `customer_ownership_type`
  ADD PRIMARY KEY (`ownership_id`),
  ADD KEY `customer_ownership_type_created_by_foreign` (`created_by`);

--
-- Indexes for table `customer_priority_types`
--
ALTER TABLE `customer_priority_types`
  ADD PRIMARY KEY (`customer_priority_id`);

--
-- Indexes for table `customer_sales_executive_logs`
--
ALTER TABLE `customer_sales_executive_logs`
  ADD PRIMARY KEY (`csel_id`),
  ADD KEY `customer_sales_executive_logs_csel_customer_id_index` (`csel_customer_id`),
  ADD KEY `customer_sales_executive_logs_csel_sale_executive_id_index` (`csel_sale_executive_id`),
  ADD KEY `customer_sales_executive_logs_csel_created_by_index` (`csel_created_by`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `custom_defined_fields`
--
ALTER TABLE `custom_defined_fields`
  ADD PRIMARY KEY (`label_id`),
  ADD KEY `custom_defined_created_by_foreign` (`created_by`) USING BTREE;

--
-- Indexes for table `debit_notes`
--
ALTER TABLE `debit_notes`
  ADD PRIMARY KEY (`debit_note_id`),
  ADD UNIQUE KEY `debit_note_no` (`debit_note_no`),
  ADD KEY `debit_notes_division_id_index` (`division_id`),
  ADD KEY `debit_notes_customer_id_index` (`customer_id`),
  ADD KEY `debit_notes_created_by_index` (`created_by`),
  ADD KEY `debit_notes_invoice_id_foreign` (`invoice_id`),
  ADD KEY `debit_note_type_id` (`debit_note_type_id`),
  ADD KEY `product_category_id` (`product_category_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `departments_department_code_unique` (`department_code`),
  ADD KEY `departments_company_id_index` (`company_id`),
  ADD KEY `departments_created_by_foreign` (`created_by`),
  ADD KEY `departments_department_type_foreign` (`department_type`),
  ADD KEY `department_name` (`department_name`);

--
-- Indexes for table `department_product_categories_link`
--
ALTER TABLE `department_product_categories_link`
  ADD PRIMARY KEY (`department_product_categories_id`),
  ADD UNIQUE KEY `department_id` (`department_id`),
  ADD UNIQUE KEY `product_category_id` (`product_category_id`),
  ADD KEY `department_product_categories_link_department_id_index` (`department_id`),
  ADD KEY `department_product_categories_link_product_category_id_index` (`product_category_id`);

--
-- Indexes for table `department_type`
--
ALTER TABLE `department_type`
  ADD PRIMARY KEY (`department_type_id`),
  ADD UNIQUE KEY `department_type_code` (`department_type_code`),
  ADD KEY `department_type_created_by_index` (`created_by`),
  ADD KEY `department_type_name` (`department_type_name`);

--
-- Indexes for table `detector_master`
--
ALTER TABLE `detector_master`
  ADD PRIMARY KEY (`detector_id`),
  ADD UNIQUE KEY `detector_master_detector_code_unique` (`detector_code`),
  ADD UNIQUE KEY `unique_index` (`detector_name`,`equipment_type_id`,`product_category_id`),
  ADD KEY `detector_master_equipment_type_id_index` (`equipment_type_id`),
  ADD KEY `detector_master_product_category_id_index` (`product_category_id`),
  ADD KEY `detector_master_created_by_index` (`created_by`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`division_id`),
  ADD UNIQUE KEY `divisions_division_code_unique` (`division_code`),
  ADD KEY `divisions_company_id_index` (`company_id`),
  ADD KEY `divisions_created_by_foreign` (`created_by`),
  ADD KEY `division_name` (`division_name`);

--
-- Indexes for table `division_parameters`
--
ALTER TABLE `division_parameters`
  ADD PRIMARY KEY (`division_parameter_id`),
  ADD KEY `division_parameters_division_id_index` (`division_id`),
  ADD KEY `division_parameters_division_city_index` (`division_city`),
  ADD KEY `division_country` (`division_country`),
  ADD KEY `division_state` (`division_state`);

--
-- Indexes for table `division_wise_items`
--
ALTER TABLE `division_wise_items`
  ADD PRIMARY KEY (`division_wise_item_id`),
  ADD KEY `division_wise_items_item_id_index` (`item_id`),
  ADD KEY `division_wise_items_division_id_index` (`division_id`),
  ADD KEY `division_wise_items_created_by_foreign` (`created_by`);

--
-- Indexes for table `division_wise_item_stock`
--
ALTER TABLE `division_wise_item_stock`
  ADD PRIMARY KEY (`division_wise_item_stock_id`),
  ADD KEY `division_wise_item_stock_store_id_index` (`store_id`),
  ADD KEY `division_wise_item_stock_item_id_index` (`item_id`),
  ADD KEY `division_wise_item_stock_division_id_index` (`division_id`),
  ADD KEY `division_wise_item_stock_created_by_index` (`created_by`);

--
-- Indexes for table `division_wise_stores`
--
ALTER TABLE `division_wise_stores`
  ADD PRIMARY KEY (`store_id`),
  ADD KEY `division_wise_stores_division_id_index` (`division_id`),
  ADD KEY `division_wise_stores_created_by_foreign` (`created_by`);

--
-- Indexes for table `employee_sales_dtl`
--
ALTER TABLE `employee_sales_dtl`
  ADD PRIMARY KEY (`ust_id`);

--
-- Indexes for table `equipment_type`
--
ALTER TABLE `equipment_type`
  ADD PRIMARY KEY (`equipment_id`),
  ADD UNIQUE KEY `equipment_type_equipment_code_unique` (`equipment_code`),
  ADD UNIQUE KEY `equipment_name` (`equipment_name`),
  ADD KEY `equipment_type_product_category_id_index` (`product_category_id`),
  ADD KEY `equipment_type_created_by_index` (`created_by`),
  ADD KEY `equipment_capacity` (`equipment_capacity`),
  ADD KEY `equipment_sort_by` (`equipment_sort_by`);

--
-- Indexes for table `holiday_master`
--
ALTER TABLE `holiday_master`
  ADD PRIMARY KEY (`holiday_id`),
  ADD UNIQUE KEY `division_id_2` (`division_id`,`holiday_date`),
  ADD KEY `division_id` (`division_id`),
  ADD KEY `holiday_name` (`holiday_name`);

--
-- Indexes for table `ign_hdr`
--
ALTER TABLE `ign_hdr`
  ADD PRIMARY KEY (`ign_hdr_id`),
  ADD KEY `ign_hdr_division_id_index` (`division_id`),
  ADD KEY `ign_hdr_vendor_id_index` (`vendor_id`),
  ADD KEY `ign_hdr_employee_id_index` (`employee_id`),
  ADD KEY `ign_hdr_created_by_index` (`created_by`);

--
-- Indexes for table `ign_hdr_dtl`
--
ALTER TABLE `ign_hdr_dtl`
  ADD PRIMARY KEY (`ign_hdr_dtl_id`),
  ADD KEY `ign_hdr_dtl_ign_hdr_id_index` (`ign_hdr_id`),
  ADD KEY `ign_hdr_dtl_po_hdr_id_index` (`po_hdr_id`),
  ADD KEY `ign_hdr_dtl_item_id_index` (`item_id`);

--
-- Indexes for table `indent_hdr`
--
ALTER TABLE `indent_hdr`
  ADD PRIMARY KEY (`indent_hdr_id`),
  ADD KEY `indent_hdr_division_id_index` (`division_id`),
  ADD KEY `indent_hdr_indented_by_index` (`indented_by`),
  ADD KEY `indent_hdr_created_by_index` (`created_by`);

--
-- Indexes for table `indent_hdr_detail`
--
ALTER TABLE `indent_hdr_detail`
  ADD PRIMARY KEY (`indent_dtl_id`),
  ADD KEY `indent_hdr_detail_indent_hdr_id_index` (`indent_hdr_id`),
  ADD KEY `indent_hdr_detail_item_id_index` (`item_id`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inquiry_customer_id_foreign` (`customer_id`),
  ADD KEY `inquiry_created_by_foreign` (`created_by`);

--
-- Indexes for table `inquiry_followups`
--
ALTER TABLE `inquiry_followups`
  ADD PRIMARY KEY (`followup_id`),
  ADD KEY `followup_inquiry_id_foreign` (`inquiry_id`),
  ADD KEY `followup_followup_by_foreign` (`followup_by`),
  ADD KEY `followup_created_by_foreign` (`created_by`);

--
-- Indexes for table `instance_master`
--
ALTER TABLE `instance_master`
  ADD PRIMARY KEY (`instance_id`),
  ADD UNIQUE KEY `instance_master_instance_code_unique` (`instance_code`),
  ADD UNIQUE KEY `instance_master_instance_name_unique` (`instance_name`),
  ADD KEY `instance_master_equipment_type_id_index` (`equipment_type_id`),
  ADD KEY `instance_master_product_category_id_index` (`product_category_id`),
  ADD KEY `instance_master_created_by_index` (`created_by`);

--
-- Indexes for table `invoice_cancellation_dtls`
--
ALTER TABLE `invoice_cancellation_dtls`
  ADD PRIMARY KEY (`invoice_cancellation_id`),
  ADD KEY `invoice_cancellation_dtls_invoice_id_index` (`invoice_id`),
  ADD KEY `invoice_cancellation_dtls_invoice_cancelled_by_index` (`invoice_cancelled_by`);

--
-- Indexes for table `invoice_dispatch_dtls`
--
ALTER TABLE `invoice_dispatch_dtls`
  ADD PRIMARY KEY (`invoice_dispatch_id`),
  ADD KEY `invoice_dispatch_dtls_invoice_id_index` (`invoice_id`),
  ADD KEY `invoice_dispatch_dtls_invoice_dispatch_by_index` (`invoice_dispatch_by`),
  ADD KEY `invoice_dispatch_date` (`invoice_dispatch_date`),
  ADD KEY `ar_bill_no` (`ar_bill_no`);

--
-- Indexes for table `invoice_financial_years`
--
ALTER TABLE `invoice_financial_years`
  ADD PRIMARY KEY (`ify_id`);

--
-- Indexes for table `invoice_hdr`
--
ALTER TABLE `invoice_hdr`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `invoice_hdr_division_id_index` (`division_id`),
  ADD KEY `invoice_hdr_customer_id_index` (`customer_id`),
  ADD KEY `invoice_hdr_created_by_foreign` (`created_by`),
  ADD KEY `product_category_id` (`product_category_id`),
  ADD KEY `invoice_status` (`invoice_status`),
  ADD KEY `invoice_type` (`invoice_type`),
  ADD KEY `customer_gst_tax_slab_type_id` (`customer_gst_tax_slab_type_id`),
  ADD KEY `invoice_date` (`invoice_date`),
  ADD KEY `customer_invoicing_id` (`customer_invoicing_id`),
  ADD KEY `invoice_hdr_inv_fin_yr_id_foreign` (`inv_fin_yr_id`);

--
-- Indexes for table `invoice_hdr_detail`
--
ALTER TABLE `invoice_hdr_detail`
  ADD PRIMARY KEY (`invoice_dtl_id`),
  ADD KEY `invoice_hdr_detail_invoice_hdr_id_index` (`invoice_hdr_id`),
  ADD KEY `invoice_hdr_detail_order_id_index` (`order_id`),
  ADD KEY `order_invoicing_to` (`order_invoicing_to`),
  ADD KEY `invoice_hdr_status` (`invoice_hdr_status`),
  ADD KEY `invoice_hdr_detail_idx_invoice_status_order_id` (`invoice_hdr_status`,`order_id`);

--
-- Indexes for table `invoice_session_dtl`
--
ALTER TABLE `invoice_session_dtl`
  ADD PRIMARY KEY (`invoice_session_id`),
  ADD KEY `invoice_session_dtl_division_id_index` (`division_id`),
  ADD KEY `invoice_session_dtl_product_category_id_index` (`product_category_id`),
  ADD KEY `invoice_session_year` (`invoice_session_year`);

--
-- Indexes for table `itc_std_rate_dtl`
--
ALTER TABLE `itc_std_rate_dtl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`item_cat_id`),
  ADD UNIQUE KEY `item_categories_item_cat_code_unique` (`item_cat_code`),
  ADD KEY `item_categories_created_by_foreign` (`created_by`);

--
-- Indexes for table `item_master`
--
ALTER TABLE `item_master`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_code` (`item_code`),
  ADD UNIQUE KEY `item_barcode` (`item_barcode`),
  ADD KEY `item_master_item_cat_id_index` (`item_cat_id`),
  ADD KEY `item_master_created_by_foreign` (`created_by`),
  ADD KEY `item_master_item_unit_foreign` (`item_unit`) USING BTREE;

--
-- Indexes for table `method_master`
--
ALTER TABLE `method_master`
  ADD PRIMARY KEY (`method_id`),
  ADD UNIQUE KEY `method_master_method_code_unique` (`method_code`),
  ADD UNIQUE KEY `unique_index` (`method_name`,`equipment_type_id`,`product_category_id`),
  ADD KEY `method_master_equipment_type_id_index` (`equipment_type_id`),
  ADD KEY `method_master_product_category_id_index` (`product_category_id`),
  ADD KEY `method_master_created_by_index` (`created_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `missing_credit_notes`
--
ALTER TABLE `missing_credit_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mis_report_default_types`
--
ALTER TABLE `mis_report_default_types`
  ADD PRIMARY KEY (`mis_report_id`),
  ADD UNIQUE KEY `mis_report_code` (`mis_report_code`),
  ADD KEY `mis_report_order_by` (`mis_report_order_by`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modules_module_category_index` (`parent_id`),
  ADD KEY `modules_level_index` (`module_level`),
  ADD KEY `modules_status_index` (`module_status`),
  ADD KEY `modules_created_by_foreign` (`created_by`),
  ADD KEY `module_name` (`module_name`);

--
-- Indexes for table `module_navigations`
--
ALTER TABLE `module_navigations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_navigations_role_id_index` (`role_id`),
  ADD KEY `module_navigations_module_id_index` (`module_id`),
  ADD KEY `module_navigations_module_menu_id_index` (`module_menu_id`),
  ADD KEY `module_navigations_created_by_index` (`created_by`);

--
-- Indexes for table `module_permissions`
--
ALTER TABLE `module_permissions`
  ADD PRIMARY KEY (`module_permission_id`),
  ADD KEY `module_permissions_role_id_index` (`role_id`),
  ADD KEY `module_permissions_module_id_index` (`module_id`),
  ADD KEY `module_permissions_sub_module_id_index` (`sub_module_id`),
  ADD KEY `module_permissions_module_menu_id_index` (`module_menu_id`),
  ADD KEY `module_permissions_created_by_index` (`created_by`);

--
-- Indexes for table `order_accreditation_certificate_master`
--
ALTER TABLE `order_accreditation_certificate_master`
  ADD PRIMARY KEY (`oac_id`),
  ADD UNIQUE KEY `oac_division_id` (`oac_division_id`,`oac_product_category_id`,`oac_name`),
  ADD KEY `order_accreditation_certificate_master_oac_division_id_index` (`oac_division_id`),
  ADD KEY `order_accreditation_certificate_master_oac_created_by_foreign` (`oac_created_by`),
  ADD KEY `oac_product_category_id` (`oac_product_category_id`),
  ADD KEY `oac_multi_location_lab_value` (`oac_multi_location_lab_value`);

--
-- Indexes for table `order_amended_dtl`
--
ALTER TABLE `order_amended_dtl`
  ADD PRIMARY KEY (`oad_id`),
  ADD KEY `order_amended_dtl_oad_order_id_index` (`oad_order_id`),
  ADD KEY `order_amended_dtl_oad_amended_stage_index` (`oad_amended_stage`),
  ADD KEY `order_amended_dtl_oad_amended_by_index` (`oad_amended_by`),
  ADD KEY `oad_amended_date` (`oad_amended_date`);

--
-- Indexes for table `order_amendment_master`
--
ALTER TABLE `order_amendment_master`
  ADD PRIMARY KEY (`oam_id`),
  ADD UNIQUE KEY `oam_code` (`oam_code`),
  ADD KEY `order_amendment_master_oam_status_index` (`oam_status`),
  ADD KEY `oam_name` (`oam_name`);

--
-- Indexes for table `order_analyst_window_settings`
--
ALTER TABLE `order_analyst_window_settings`
  ADD PRIMARY KEY (`oaws_id`),
  ADD KEY `order_analyst_window_settings_oaws_unique_id_index` (`oaws_unique_id`),
  ADD KEY `order_analyst_window_settings_oaws_division_id_index` (`oaws_division_id`),
  ADD KEY `order_analyst_window_settings_oaws_product_category_id_index` (`oaws_product_category_id`),
  ADD KEY `order_analyst_window_settings_oaws_equipment_type_id_index` (`oaws_equipment_type_id`),
  ADD KEY `order_analyst_window_settings_oaws_created_by_index` (`oaws_created_by`);

--
-- Indexes for table `order_brand_type_dtl`
--
ALTER TABLE `order_brand_type_dtl`
  ADD PRIMARY KEY (`obtd_id`),
  ADD UNIQUE KEY `obtd_code_2` (`obtd_code`),
  ADD KEY `obtd_code` (`obtd_code`),
  ADD KEY `obtd_name` (`obtd_name`);

--
-- Indexes for table `order_cancellation_dtls`
--
ALTER TABLE `order_cancellation_dtls`
  ADD PRIMARY KEY (`order_cancellation_id`),
  ADD KEY `order_cancellation_dtls_order_id_index` (`order_id`),
  ADD KEY `order_cancellation_dtls_cancellation_stage_index` (`cancellation_stage`),
  ADD KEY `order_cancellation_dtls_cancellation_type_id_index` (`cancellation_type_id`),
  ADD KEY `order_cancellation_dtls_cancellation_status_index` (`cancellation_status`),
  ADD KEY `order_cancellation_dtls_cancelled_by_index` (`cancelled_by`),
  ADD KEY `cancelled_date` (`cancelled_date`);

--
-- Indexes for table `order_cancellation_types`
--
ALTER TABLE `order_cancellation_types`
  ADD PRIMARY KEY (`order_cancellation_type_id`),
  ADD KEY `order_cancellation_types_order_cancellation_type_id_index` (`order_cancellation_type_id`),
  ADD KEY `order_cancellation_types_order_cancellation_type_name_index` (`order_cancellation_type_name`),
  ADD KEY `order_cancellation_types_header_status_index` (`order_cancellation_type_status`);

--
-- Indexes for table `order_client_approval_dtl`
--
ALTER TABLE `order_client_approval_dtl`
  ADD PRIMARY KEY (`ocad_id`),
  ADD KEY `order_client_approval_dtl_ocad_order_id_index` (`ocad_order_id`),
  ADD KEY `order_client_approval_dtl_ocad_credit_period_index` (`ocad_credit_period`);

--
-- Indexes for table `order_defined_test_std_dtl`
--
ALTER TABLE `order_defined_test_std_dtl`
  ADD PRIMARY KEY (`odtsd_id`),
  ADD UNIQUE KEY `odtsd_branch_id` (`odtsd_branch_id`,`odtsd_product_category_id`,`odtsd_test_standard_id`),
  ADD KEY `order_defined_test_std_dtl_odtsd_branch_id_index` (`odtsd_branch_id`),
  ADD KEY `order_defined_test_std_dtl_odtsd_product_category_id_index` (`odtsd_product_category_id`),
  ADD KEY `order_defined_test_std_dtl_odtsd_test_standard_id_index` (`odtsd_test_standard_id`),
  ADD KEY `order_defined_test_std_dtl_odtsd_created_by_index` (`odtsd_created_by`);

--
-- Indexes for table `order_discipline_group_dtl`
--
ALTER TABLE `order_discipline_group_dtl`
  ADD PRIMARY KEY (`odg_id`),
  ADD KEY `odg_test_parameter_category_id` (`test_parameter_category_id`),
  ADD KEY `order_discipline_group_dtl_discipline_id_foreign` (`discipline_id`),
  ADD KEY `order_discipline_group_dtl_group_id_foreign` (`group_id`),
  ADD KEY `order_discipline_group_dtl_order_id_index` (`order_id`),
  ADD KEY `order_discipline_group_dtl_created_by_index` (`created_by`);

--
-- Indexes for table `order_dispatch_dtl`
--
ALTER TABLE `order_dispatch_dtl`
  ADD PRIMARY KEY (`dispatch_id`),
  ADD KEY `order_dispatch_dtl_order_id_index` (`order_id`),
  ADD KEY `order_dispatch_dtl_dispatch_by_index` (`dispatch_by`),
  ADD KEY `ar_bill_no` (`ar_bill_no`),
  ADD KEY `dispatch_date` (`dispatch_date`),
  ADD KEY `order_dispatch_dtl_idx_amend_status_order_id` (`amend_status`,`order_id`);

--
-- Indexes for table `order_dynamic_fields`
--
ALTER TABLE `order_dynamic_fields`
  ADD PRIMARY KEY (`odfs_id`),
  ADD UNIQUE KEY `order_dynamic_fields_dynamic_field_code_unique` (`dynamic_field_code`),
  ADD KEY `order_dynamic_fields_odfs_created_by_index` (`odfs_created_by`);

--
-- Indexes for table `order_dynamic_field_dtl`
--
ALTER TABLE `order_dynamic_field_dtl`
  ADD PRIMARY KEY (`odf_id`),
  ADD KEY `order_dynamic_field_dtl_order_id_index` (`order_id`),
  ADD KEY `order_dynamic_field_dtl_odf_created_by_index` (`odf_created_by`);

--
-- Indexes for table `order_expected_due_date_logs`
--
ALTER TABLE `order_expected_due_date_logs`
  ADD PRIMARY KEY (`oeddl_id`),
  ADD KEY `order_expected_due_date_logs_oeddl_order_id_index` (`oeddl_order_id`),
  ADD KEY `order_expected_due_date_logs_oeddl_no_of_days_index` (`oeddl_no_of_days`),
  ADD KEY `order_expected_due_date_logs_oeddl_created_by_index` (`oeddl_created_by`);

--
-- Indexes for table `order_header_notes`
--
ALTER TABLE `order_header_notes`
  ADD PRIMARY KEY (`header_id`),
  ADD UNIQUE KEY `header_name_2` (`header_name`),
  ADD KEY `header_limit` (`header_limit`),
  ADD KEY `header_name` (`header_name`),
  ADD KEY `header_status` (`header_status`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `header_id` (`header_id`);

--
-- Indexes for table `order_hold_master`
--
ALTER TABLE `order_hold_master`
  ADD PRIMARY KEY (`oh_id`),
  ADD UNIQUE KEY `oh_code` (`oh_code`),
  ADD KEY `order_hold_master_oh_status_index` (`oh_status`),
  ADD KEY `oh_name` (`oh_name`);

--
-- Indexes for table `order_incharge_dtl`
--
ALTER TABLE `order_incharge_dtl`
  ADD PRIMARY KEY (`oid_id`),
  ADD KEY `order_incharge_dtl_order_id_foreign` (`order_id`),
  ADD KEY `order_incharge_dtl_oid_employee_id_foreign` (`oid_employee_id`),
  ADD KEY `order_incharge_dtl_oid_confirm_by_foreign` (`oid_confirm_by`),
  ADD KEY `order_incharge_dtl_oid_equipment_type_id_foreign` (`oid_equipment_type_id`),
  ADD KEY `oid_assign_date` (`oid_assign_date`),
  ADD KEY `oid_confirm_date` (`oid_confirm_date`),
  ADD KEY `oid_status` (`oid_status`);

--
-- Indexes for table `order_incharge_process_dtl`
--
ALTER TABLE `order_incharge_process_dtl`
  ADD PRIMARY KEY (`oipd_id`),
  ADD KEY `order_incharge_process_dtl_oipd_incharge_id_index` (`oipd_incharge_id`),
  ADD KEY `order_incharge_process_dtl_oipd_user_id_index` (`oipd_user_id`),
  ADD KEY `order_incharge_process_dtl_oipd_analysis_id_index` (`oipd_analysis_id`),
  ADD KEY `order_incharge_process_dtl_oipd_order_id_index` (`oipd_order_id`),
  ADD KEY `oipd_date` (`oipd_date`),
  ADD KEY `oipd_status` (`oipd_status`);

--
-- Indexes for table `order_linked_po_dtl`
--
ALTER TABLE `order_linked_po_dtl`
  ADD PRIMARY KEY (`olpd_id`),
  ADD UNIQUE KEY `olpd_order_id` (`olpd_order_id`),
  ADD KEY `order_linked_po_dtl_olpd_order_id_index` (`olpd_order_id`),
  ADD KEY `order_linked_po_dtl_olpd_cpo_id_index` (`olpd_cpo_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `olpd_cpo_no` (`olpd_cpo_no`),
  ADD KEY `olpd_cpo_date` (`olpd_cpo_date`),
  ADD KEY `olpd_date` (`olpd_date`);

--
-- Indexes for table `order_linked_stp_dtl`
--
ALTER TABLE `order_linked_stp_dtl`
  ADD PRIMARY KEY (`olsd_id`),
  ADD UNIQUE KEY `olsd_order_id` (`olsd_order_id`),
  ADD KEY `order_linked_stp_dtl_olsd_order_id_index` (`olsd_order_id`),
  ADD KEY `order_linked_stp_dtl_olsd_cstp_id_index` (`olsd_cstp_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `olsd_cstp_no` (`olsd_cstp_no`),
  ADD KEY `olsd_date` (`olsd_date`);

--
-- Indexes for table `order_linked_trf_dtl`
--
ALTER TABLE `order_linked_trf_dtl`
  ADD PRIMARY KEY (`oltd_id`),
  ADD UNIQUE KEY `oltd_order_id` (`oltd_order_id`),
  ADD KEY `order_linked_trf_dtl_oltd_order_id_index` (`oltd_order_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `oltd_trf_no` (`oltd_trf_no`),
  ADD KEY `oltd_date` (`oltd_date`);

--
-- Indexes for table `order_mail_dtl`
--
ALTER TABLE `order_mail_dtl`
  ADD PRIMARY KEY (`mail_id`),
  ADD KEY `order_mail_dtl_order_id_index` (`order_id`),
  ADD KEY `order_mail_dtl_customer_id_index` (`customer_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `mail_by` (`mail_by`),
  ADD KEY `order_mail_dtl_stb_order_hdr_id_foreign` (`stb_order_hdr_id`),
  ADD KEY `mail_content_type` (`mail_content_type`),
  ADD KEY `mail_date` (`mail_date`);

--
-- Indexes for table `order_mail_status`
--
ALTER TABLE `order_mail_status`
  ADD PRIMARY KEY (`oms_id`);

--
-- Indexes for table `order_master`
--
ALTER TABLE `order_master`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_no_2` (`order_no`),
  ADD KEY `order_master_division_id_index` (`division_id`),
  ADD KEY `order_master_customer_id_index` (`customer_id`),
  ADD KEY `order_master_sale_executive_index` (`sale_executive`),
  ADD KEY `order_master_product_id_index` (`product_id`),
  ADD KEY `order_master_test_standard_index` (`test_standard`),
  ADD KEY `order_master_product_test_id_index` (`product_test_id`),
  ADD KEY `order_master_sample_priority_id_index` (`sample_priority_id`),
  ADD KEY `order_master_discount_type_id_index` (`discount_type_id`),
  ADD KEY `order_master_is_signed_index` (`is_signed`),
  ADD KEY `order_master_submission_type_index` (`submission_type`),
  ADD KEY `order_master_created_by_index` (`created_by`),
  ADD KEY `order_master_sample_id_foreign` (`sample_id`),
  ADD KEY `order_master_product_category_id_foreign` (`product_category_id`),
  ADD KEY `status` (`status`),
  ADD KEY `sample_description` (`sample_description_id`),
  ADD KEY `order_master_invoicing_type_id_foreign` (`invoicing_type_id`),
  ADD KEY `customer_city` (`customer_city`),
  ADD KEY `reporting_to` (`reporting_to`),
  ADD KEY `invoicing_to` (`invoicing_to`),
  ADD KEY `billing_type_id` (`billing_type_id`),
  ADD KEY `stb_order_hdr_detail_id` (`stb_order_hdr_detail_id`),
  ADD KEY `booking_date` (`booking_date`),
  ADD KEY `expected_due_date` (`expected_due_date`),
  ADD KEY `order_scheduled_date` (`order_scheduled_date`),
  ADD KEY `test_completion_date` (`test_completion_date`),
  ADD KEY `incharge_reviewing_date` (`incharge_reviewing_date`),
  ADD KEY `batch_no` (`batch_no`),
  ADD KEY `brand_type` (`brand_type`),
  ADD KEY `tat_in_days` (`tat_in_days`),
  ADD KEY `booked_order_amount` (`booked_order_amount`),
  ADD KEY `order_sample_type` (`order_sample_type`),
  ADD KEY `order_date` (`order_date`),
  ADD KEY `po_date` (`po_date`),
  ADD KEY `po_no` (`po_no`),
  ADD KEY `dispatched_date_time` (`dispatched_date_time`),
  ADD KEY `order_master_idx_division_id_status` (`division_id`,`status`),
  ADD KEY `order_master_idx_division_id_order_id` (`division_id`,`order_id`),
  ADD KEY `division_id` (`division_id`,`product_category_id`,`status`),
  ADD KEY `defined_test_standard` (`defined_test_standard`),
  ADD KEY `order_master_sampler_id_foreign` (`sampler_id`);

--
-- Indexes for table `order_parameters_detail`
--
ALTER TABLE `order_parameters_detail`
  ADD PRIMARY KEY (`analysis_id`),
  ADD KEY `order_parameters_detail_order_id_index` (`order_id`),
  ADD KEY `order_parameters_detail_product_test_parameter_index` (`product_test_parameter`),
  ADD KEY `order_parameters_detail_test_param_alternative_id_index` (`test_param_alternative_id`),
  ADD KEY `order_parameters_detail_equipment_type_id_index` (`equipment_type_id`),
  ADD KEY `order_parameters_detail_method_id_index` (`method_id`),
  ADD KEY `order_parameters_detail_test_performed_by_index` (`test_performed_by`),
  ADD KEY `order_parameters_detail_test_parameter_id_foreign` (`test_parameter_id`),
  ADD KEY `analysis_id` (`analysis_id`),
  ADD KEY `detector_id` (`detector_id`),
  ADD KEY `running_time_id` (`running_time_id`),
  ADD KEY `no_of_injection` (`no_of_injection`),
  ADD KEY `dept_due_date` (`dept_due_date`),
  ADD KEY `time_taken_days` (`time_taken_days`),
  ADD KEY `report_due_date` (`report_due_date`),
  ADD KEY `order_parameter_nabl_scope` (`order_parameter_nabl_scope`),
  ADD KEY `display_decimal_place` (`display_decimal_place`),
  ADD KEY `claim_value` (`claim_value`),
  ADD KEY `oaws_ui_setting_id` (`oaws_ui_setting_id`),
  ADD KEY `fk_column_id` (`column_id`),
  ADD KEY `fk_instance_id` (`instance_id`);

--
-- Indexes for table `order_process_log`
--
ALTER TABLE `order_process_log`
  ADD PRIMARY KEY (`opl_id`),
  ADD KEY `order_process_log_opl_order_id_index` (`opl_order_id`),
  ADD KEY `order_process_log_opl_order_status_id_index` (`opl_order_status_id`),
  ADD KEY `order_process_log_opl_user_id_index` (`opl_user_id`),
  ADD KEY `opl_id` (`opl_id`),
  ADD KEY `opl_current_stage` (`opl_current_stage`),
  ADD KEY `opl_amend_status` (`opl_amend_status`),
  ADD KEY `error_parameter_ids` (`error_parameter_ids`),
  ADD KEY `opl_date` (`opl_date`),
  ADD KEY `opl_amended_by` (`opl_amended_by`),
  ADD KEY `order_process_log_idx_opl_stage_opl_status_opl_id` (`opl_current_stage`,`opl_amend_status`,`opl_order_id`);

--
-- Indexes for table `order_purchase_order_logs`
--
ALTER TABLE `order_purchase_order_logs`
  ADD PRIMARY KEY (`opol_id`),
  ADD KEY `order_purchase_order_logs_opol_order_id_index` (`opol_order_id`),
  ADD KEY `order_purchase_order_logs_opol_created_by_index` (`opol_created_by`);

--
-- Indexes for table `order_report_details`
--
ALTER TABLE `order_report_details`
  ADD PRIMARY KEY (`order_report_id`),
  ADD UNIQUE KEY `report_id` (`report_id`),
  ADD KEY `order_report_details_report_id_index` (`report_id`),
  ADD KEY `order_report_details_result_drived_value_foreign` (`result_drived_value`),
  ADD KEY `report_no` (`report_no`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `finalized_by` (`finalized_by`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `nabl_no` (`nabl_no`),
  ADD KEY `report_date` (`report_date`),
  ADD KEY `reviewing_date` (`reviewing_date`),
  ADD KEY `finalizing_date` (`finalizing_date`),
  ADD KEY `approving_date` (`approving_date`),
  ADD KEY `nabl_multi_location_lab_value` (`nabl_multi_location_lab_value`);

--
-- Indexes for table `order_report_disciplines`
--
ALTER TABLE `order_report_disciplines`
  ADD PRIMARY KEY (`or_discipline_id`),
  ADD UNIQUE KEY `order_report_disciplines_or_discipline_code_unique` (`or_discipline_code`),
  ADD UNIQUE KEY `order_report_disciplines_or_discipline_name_unique` (`or_discipline_name`),
  ADD KEY `order_report_disciplines_or_discipline_created_by_index` (`or_discipline_created_by`);

--
-- Indexes for table `order_report_discipline_parameter_dtls`
--
ALTER TABLE `order_report_discipline_parameter_dtls`
  ADD PRIMARY KEY (`ordp_id`),
  ADD UNIQUE KEY `division_product_cat_discipline_param_unique` (`ordp_division_id`,`ordp_product_category_id`,`ordp_discipline_id`,`ordp_test_parameter_category_id`),
  ADD UNIQUE KEY `ordp_division_id` (`ordp_division_id`,`ordp_product_category_id`,`ordp_test_parameter_category_id`),
  ADD KEY `ordpd_product_category_id` (`ordp_product_category_id`),
  ADD KEY `ordpd_discipline_id` (`ordp_discipline_id`),
  ADD KEY `ordpd_test_parameter_category_id` (`ordp_test_parameter_category_id`),
  ADD KEY `order_report_discipline_parameter_dtls_ordp_created_by_index` (`ordp_created_by`);

--
-- Indexes for table `order_report_groups`
--
ALTER TABLE `order_report_groups`
  ADD PRIMARY KEY (`org_group_id`),
  ADD UNIQUE KEY `group_division_product_cat_unique` (`org_group_name`,`org_division_id`,`org_product_category_id`),
  ADD UNIQUE KEY `order_report_groups_org_group_code_unique` (`org_group_code`),
  ADD UNIQUE KEY `order_report_groups_org_group_name_unique` (`org_group_name`),
  ADD KEY `order_report_groups_org_division_id_index` (`org_division_id`),
  ADD KEY `order_report_groups_org_product_category_id_index` (`org_product_category_id`),
  ADD KEY `order_report_groups_org_group_created_by_index` (`org_group_created_by`);

--
-- Indexes for table `order_report_header_types`
--
ALTER TABLE `order_report_header_types`
  ADD PRIMARY KEY (`orht_id`),
  ADD KEY `order_report_header_types_orht_product_category_id_foreign` (`orht_product_category_id`),
  ADD KEY `order_report_header_types_orht_customer_type_foreign` (`orht_customer_type`),
  ADD KEY `order_report_header_types_orht_created_by_foreign` (`orht_created_by`),
  ADD KEY `order_report_header_types_orht_division_id_index` (`orht_division_id`),
  ADD KEY `order_report_header_types_orht_report_hdr_type_index` (`orht_report_hdr_type`);

--
-- Indexes for table `order_report_microbiological_dtl`
--
ALTER TABLE `order_report_microbiological_dtl`
  ADD PRIMARY KEY (`ormbd_id`),
  ADD KEY `order_report_details_report_id_index` (`report_id`),
  ADD KEY `report_microbiological_name` (`report_microbiological_name`),
  ADD KEY `report_microbiological_sign` (`report_microbiological_sign`),
  ADD KEY `order_report_microbiological_dtl_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_report_middle_authorized_signs`
--
ALTER TABLE `order_report_middle_authorized_signs`
  ADD PRIMARY KEY (`ormad_id`),
  ADD KEY `order_report_middle_authorized_signs_ormad_order_id_index` (`ormad_order_id`),
  ADD KEY `order_report_middle_authorized_signs_ormad_employee_id_index` (`ormad_employee_id`);

--
-- Indexes for table `order_report_note_remark_default`
--
ALTER TABLE `order_report_note_remark_default`
  ADD PRIMARY KEY (`remark_id`),
  ADD KEY `product_category_id` (`product_category_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `division_id` (`division_id`),
  ADD KEY `type` (`type`),
  ADD KEY `is_display_stamp` (`is_display_stamp`),
  ADD KEY `remark_status` (`remark_status`);

--
-- Indexes for table `order_report_options`
--
ALTER TABLE `order_report_options`
  ADD PRIMARY KEY (`report_option_id`),
  ADD KEY `report_option_name` (`report_option_name`);

--
-- Indexes for table `order_report_settings`
--
ALTER TABLE `order_report_settings`
  ADD PRIMARY KEY (`ors_id`),
  ADD UNIQUE KEY `ors_division_product_category_column_name_unique` (`ors_division_id`,`ors_product_category_id`,`ors_column_name`),
  ADD KEY `order_report_settings_ors_type_id_index` (`ors_type_id`),
  ADD KEY `order_report_settings_ors_division_id_index` (`ors_division_id`),
  ADD KEY `order_report_settings_ors_product_category_id_index` (`ors_product_category_id`),
  ADD KEY `order_report_settings_ors_created_by_index` (`ors_created_by`);

--
-- Indexes for table `order_report_sign_dtls`
--
ALTER TABLE `order_report_sign_dtls`
  ADD PRIMARY KEY (`orsd_id`),
  ADD KEY `order_report_sign_dtls_orsd_employee_id_index` (`orsd_employee_id`),
  ADD KEY `order_report_sign_dtls_orsd_division_id_index` (`orsd_division_id`),
  ADD KEY `order_report_sign_dtls_orsd_product_category_id_index` (`orsd_product_category_id`),
  ADD KEY `order_report_sign_dtls_orsd_equipment_type_id_index` (`orsd_equipment_type_id`),
  ADD KEY `order_report_sign_dtls_orsd_created_by_index` (`orsd_created_by`);

--
-- Indexes for table `order_sample_priority`
--
ALTER TABLE `order_sample_priority`
  ADD PRIMARY KEY (`sample_priority_id`),
  ADD KEY `sample_priority_id` (`sample_priority_id`),
  ADD KEY `sample_priority_code` (`sample_priority_code`),
  ADD KEY `sample_priority_name` (`sample_priority_name`),
  ADD KEY `sample_priority_status` (`sample_priority_status`),
  ADD KEY `sample_priority_color_code` (`sample_priority_color_code`);

--
-- Indexes for table `order_sealed_unsealed`
--
ALTER TABLE `order_sealed_unsealed`
  ADD PRIMARY KEY (`osus_id`),
  ADD UNIQUE KEY `osus_name` (`osus_name`),
  ADD KEY `order_sealed_unsealed_osus_status_index` (`osus_status`);

--
-- Indexes for table `order_signed_unsigned`
--
ALTER TABLE `order_signed_unsigned`
  ADD PRIMARY KEY (`osu_id`),
  ADD KEY `order_signed_unsigned_osu_status_index` (`osu_status`),
  ADD KEY `osu_name` (`osu_name`);

--
-- Indexes for table `order_stability_notes`
--
ALTER TABLE `order_stability_notes`
  ADD PRIMARY KEY (`stability_id`),
  ADD KEY `stability_name` (`stability_name`),
  ADD KEY `stability_status` (`stability_status`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`order_status_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `order_status_name` (`order_status_name`),
  ADD KEY `status` (`status`),
  ADD KEY `order_status_title` (`order_status_title`),
  ADD KEY `order_status_alias` (`order_status_alias`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `payment_made_hdr`
--
ALTER TABLE `payment_made_hdr`
  ADD PRIMARY KEY (`payment_made_hdr_id`),
  ADD UNIQUE KEY `payment_made_no` (`payment_made_no`),
  ADD KEY `payment_made_hdr_division_id_index` (`division_id`),
  ADD KEY `payment_made_hdr_vendor_id_index` (`vendor_id`),
  ADD KEY `payment_made_hdr_created_by_foreign` (`created_by`),
  ADD KEY `payment_source_id` (`payment_source_id`);

--
-- Indexes for table `payment_received_hdr`
--
ALTER TABLE `payment_received_hdr`
  ADD PRIMARY KEY (`payment_received_hdr_id`),
  ADD UNIQUE KEY `payment_received_no` (`payment_received_no`),
  ADD KEY `payment_received_hdr_division_id_index` (`division_id`),
  ADD KEY `payment_received_hdr_customer_id_index` (`customer_id`),
  ADD KEY `payment_received_hdr_created_by_foreign` (`created_by`),
  ADD KEY `payment_source_id` (`payment_source_id`);

--
-- Indexes for table `payment_sources`
--
ALTER TABLE `payment_sources`
  ADD PRIMARY KEY (`payment_source_id`),
  ADD KEY `payment_sources_created_by_foreign` (`created_by`);

--
-- Indexes for table `po_hdr`
--
ALTER TABLE `po_hdr`
  ADD PRIMARY KEY (`po_hdr_id`),
  ADD KEY `po_hdr_division_id_index` (`division_id`),
  ADD KEY `po_hdr_vendor_id_index` (`vendor_id`),
  ADD KEY `po_hdr_created_by_index` (`created_by`);

--
-- Indexes for table `po_hdr_detail`
--
ALTER TABLE `po_hdr_detail`
  ADD PRIMARY KEY (`po_dtl_id`),
  ADD KEY `po_hdr_detail_po_hdr_id_index` (`po_hdr_id`),
  ADD KEY `po_hdr_detail_item_id_index` (`item_id`);

--
-- Indexes for table `po_indent_detail`
--
ALTER TABLE `po_indent_detail`
  ADD PRIMARY KEY (`po_indent_dtl_id`),
  ADD KEY `po_indent_detail_po_hdr_id_index` (`po_hdr_id`),
  ADD KEY `po_indent_detail_indent_dtl_id_index` (`indent_dtl_id`);

--
-- Indexes for table `po_status`
--
ALTER TABLE `po_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`p_category_id`),
  ADD UNIQUE KEY `product_categories_p_category_code_unique` (`p_category_code`),
  ADD KEY `product_categories_created_by_foreign` (`created_by`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `p_category_name` (`p_category_name`),
  ADD KEY `level` (`level`),
  ADD KEY `p_status` (`p_status`);

--
-- Indexes for table `product_master`
--
ALTER TABLE `product_master`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_master_product_code_unique` (`product_code`),
  ADD KEY `product_master_p_category_id_index` (`p_category_id`),
  ADD KEY `product_master_created_by_foreign` (`created_by`),
  ADD KEY `product_name` (`product_name`);

--
-- Indexes for table `product_master_alias`
--
ALTER TABLE `product_master_alias`
  ADD PRIMARY KEY (`c_product_id`),
  ADD UNIQUE KEY `unique_index` (`c_product_name`,`product_id`),
  ADD KEY `product_master_alias_product_id_index` (`product_id`),
  ADD KEY `product_master_alias_created_by_index` (`created_by`),
  ADD KEY `c_product_status` (`c_product_status`);

--
-- Indexes for table `product_test_dtl`
--
ALTER TABLE `product_test_dtl`
  ADD PRIMARY KEY (`product_test_dtl_id`),
  ADD UNIQUE KEY `unique_index` (`test_id`,`test_parameter_id`),
  ADD KEY `product_test_dtl_test_id_index` (`test_id`),
  ADD KEY `product_test_dtl_test_parameter_id_index` (`test_parameter_id`),
  ADD KEY `product_test_dtl_equipment_type_id_index` (`equipment_type_id`),
  ADD KEY `product_test_dtl_method_id_index` (`method_id`),
  ADD KEY `product_test_dtl_claim_dependent_index` (`claim_dependent`),
  ADD KEY `product_test_dtl_created_by_index` (`created_by`),
  ADD KEY `product_test_dtl_detector_id_foreign` (`detector_id`),
  ADD KEY `time_taken_days` (`time_taken_days`),
  ADD KEY `parameter_sort_by` (`parameter_sort_by`),
  ADD KEY `parameter_decimal_place` (`parameter_decimal_place`),
  ADD KEY `parameter_nabl_scope` (`parameter_nabl_scope`);

--
-- Indexes for table `product_test_hdr`
--
ALTER TABLE `product_test_hdr`
  ADD PRIMARY KEY (`test_id`),
  ADD UNIQUE KEY `product_test_hdr_test_code_unique` (`test_code`),
  ADD KEY `product_test_hdr_product_id_index` (`product_id`),
  ADD KEY `product_test_hdr_test_standard_id_index` (`test_standard_id`),
  ADD KEY `product_test_hdr_created_by_index` (`created_by`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `product_test_parameter_altern_method`
--
ALTER TABLE `product_test_parameter_altern_method`
  ADD PRIMARY KEY (`product_test_param_altern_method_id`),
  ADD KEY `product_test_parameter_altern_method_product_test_dtl_id_index` (`product_test_dtl_id`),
  ADD KEY `product_test_parameter_altern_method_test_id_index` (`test_id`),
  ADD KEY `product_test_parameter_altern_method_test_parameter_id_index` (`test_parameter_id`),
  ADD KEY `product_test_parameter_altern_method_equipment_type_id_index` (`equipment_type_id`),
  ADD KEY `product_test_parameter_altern_method_method_id_index` (`method_id`),
  ADD KEY `product_test_parameter_altern_method_claim_dependent_index` (`claim_dependent`),
  ADD KEY `product_test_parameter_altern_method_created_by_index` (`created_by`),
  ADD KEY `detector_id` (`detector_id`),
  ADD KEY `running_time_id` (`running_time_id`),
  ADD KEY `no_of_injection` (`no_of_injection`),
  ADD KEY `time_taken_days` (`time_taken_days`);

--
-- Indexes for table `report_header_type_default`
--
ALTER TABLE `report_header_type_default`
  ADD PRIMARY KEY (`rhtd_id`),
  ADD KEY `report_header_type_default_rhtd_status_index` (`rhtd_status`);

--
-- Indexes for table `req_slip_dtl`
--
ALTER TABLE `req_slip_dtl`
  ADD PRIMARY KEY (`req_slip_dlt_id`),
  ADD KEY `req_slip_dtl_req_slip_hdr_id_index` (`req_slip_hdr_id`),
  ADD KEY `req_slip_dtl_item_id_index` (`item_id`);

--
-- Indexes for table `req_slip_hdr`
--
ALTER TABLE `req_slip_hdr`
  ADD PRIMARY KEY (`req_slip_id`),
  ADD KEY `req_slip_hdr_req_department_id_index` (`req_department_id`),
  ADD KEY `req_slip_hdr_req_by_index` (`req_by`),
  ADD KEY `req_slip_hdr_division_id_index` (`division_id`),
  ADD KEY `req_slip_hdr_created_by_index` (`created_by`);

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
  ADD UNIQUE KEY `roles_slug_unique` (`slug`),
  ADD KEY `id` (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `group` (`group`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_role_id_index` (`role_id`),
  ADD KEY `role_user_user_id_index` (`user_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `rol_setting`
--
ALTER TABLE `rol_setting`
  ADD PRIMARY KEY (`rol_setting_id`),
  ADD KEY `rol_setting_item_id_index` (`item_id`),
  ADD KEY `rol_setting_division_id_index` (`division_id`);

--
-- Indexes for table `samples`
--
ALTER TABLE `samples`
  ADD PRIMARY KEY (`sample_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`),
  ADD KEY `samples_division_id_index` (`division_id`),
  ADD KEY `samples_product_category_id_index` (`product_category_id`),
  ADD KEY `samples_customer_id_index` (`customer_id`),
  ADD KEY `samples_sample_mode_id_index` (`sample_mode_id`),
  ADD KEY `samples_created_by_index` (`created_by`),
  ADD KEY `trf_id` (`trf_id`),
  ADD KEY `sample_no` (`sample_no`),
  ADD KEY `sample_date` (`sample_date`),
  ADD KEY `sample_current_date` (`sample_current_date`),
  ADD KEY `sample_booked_date` (`sample_booked_date`),
  ADD KEY `customer_name` (`customer_name`),
  ADD KEY `sample_status` (`sample_status`),
  ADD KEY `internal_transfer` (`internal_transfer`);

--
-- Indexes for table `sample_modes`
--
ALTER TABLE `sample_modes`
  ADD PRIMARY KEY (`sample_mode_id`),
  ADD UNIQUE KEY `sample_modes_sample_mode_name_unique` (`sample_mode_name`),
  ADD KEY `sample_mode_status` (`sample_mode_status`);

--
-- Indexes for table `sample_status_default`
--
ALTER TABLE `sample_status_default`
  ADD PRIMARY KEY (`sample_status_id`),
  ADD UNIQUE KEY `sample_status_name_2` (`sample_status_name`),
  ADD KEY `sample_status_id` (`sample_status_id`),
  ADD KEY `sample_status_name` (`sample_status_name`),
  ADD KEY `sample_status` (`sample_status`);

--
-- Indexes for table `scheduled_mail_dtl`
--
ALTER TABLE `scheduled_mail_dtl`
  ADD PRIMARY KEY (`smd_id`),
  ADD KEY `scheduled_mail_dtl_smd_customer_id_index` (`smd_customer_id`),
  ADD KEY `scheduled_mail_dtl_smd_content_type_index` (`smd_content_type`),
  ADD KEY `scheduled_mail_dtl_smd_status_index` (`smd_status`),
  ADD KEY `smd_template_name` (`smd_template_name`),
  ADD KEY `smd_date` (`smd_date`),
  ADD KEY `smd_mail_counter` (`smd_mail_counter`);

--
-- Indexes for table `scheduled_mis_report_dtls`
--
ALTER TABLE `scheduled_mis_report_dtls`
  ADD PRIMARY KEY (`smrd_id`),
  ADD KEY `scheduled_mis_report_dtls_smrd_division_id_index` (`smrd_division_id`),
  ADD KEY `scheduled_mis_report_dtls_smrd_product_category_id_index` (`smrd_product_category_id`),
  ADD KEY `scheduled_mis_report_dtls_smrd_mis_report_id_index` (`smrd_mis_report_id`),
  ADD KEY `scheduled_mis_report_dtls_smrd_created_by_index` (`smrd_created_by`),
  ADD KEY `smrd_to_email_address` (`smrd_to_email_address`),
  ADD KEY `smrd_from_email_address` (`smrd_from_email_address`);

--
-- Indexes for table `schedulings`
--
ALTER TABLE `schedulings`
  ADD PRIMARY KEY (`scheduling_id`),
  ADD KEY `schedulings_order_id_index` (`order_id`),
  ADD KEY `schedulings_order_parameter_id_index` (`order_parameter_id`),
  ADD KEY `schedulings_equipment_type_id_index` (`equipment_type_id`),
  ADD KEY `schedulings_employee_id_index` (`employee_id`),
  ADD KEY `schedulings_created_by_index` (`created_by`),
  ADD KEY `schedulings_product_category_id_foreign` (`product_category_id`),
  ADD KEY `scheduled_by` (`scheduled_by`),
  ADD KEY `status` (`status`),
  ADD KEY `scheduled_at` (`scheduled_at`),
  ADD KEY `completed_at` (`completed_at`),
  ADD KEY `schedulings_idx_employee_id_order_id_status` (`employee_id`,`order_id`,`status`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `setting_group_id` (`setting_group_id`),
  ADD KEY `product_category_id` (`product_category_id`),
  ADD KEY `setting_value` (`setting_value`),
  ADD KEY `setting_status` (`setting_status`),
  ADD KEY `is_display` (`is_display`);

--
-- Indexes for table `setting_groups`
--
ALTER TABLE `setting_groups`
  ADD PRIMARY KEY (`setting_group_id`),
  ADD KEY `setting_group_name` (`setting_group_name`),
  ADD KEY `setting_group_status` (`setting_group_status`);

--
-- Indexes for table `state_db`
--
ALTER TABLE `state_db`
  ADD PRIMARY KEY (`state_id`),
  ADD UNIQUE KEY `state_name` (`state_name`,`country_id`),
  ADD UNIQUE KEY `state_db_state_code_unique` (`state_code`),
  ADD KEY `state_db_country_id_index` (`country_id`),
  ADD KEY `state_db_created_by_foreign` (`created_by`),
  ADD KEY `state_status` (`state_status`),
  ADD KEY `state_level` (`state_level`);

--
-- Indexes for table `status_master`
--
ALTER TABLE `status_master`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status_code` (`status_code`),
  ADD KEY `status_name` (`status_name`);

--
-- Indexes for table `stb_order_hdr`
--
ALTER TABLE `stb_order_hdr`
  ADD PRIMARY KEY (`stb_order_hdr_id`),
  ADD UNIQUE KEY `stb_prototype_no` (`stb_prototype_no`),
  ADD KEY `stb_order_hdr_stb_division_id_index` (`stb_division_id`),
  ADD KEY `stb_order_hdr_stb_product_category_id_index` (`stb_product_category_id`),
  ADD KEY `stb_order_hdr_stb_sample_id_index` (`stb_sample_id`),
  ADD KEY `stb_order_hdr_stb_customer_id_index` (`stb_customer_id`),
  ADD KEY `stb_order_hdr_stb_sale_executive_index` (`stb_sale_executive`),
  ADD KEY `stb_order_hdr_stb_customer_city_index` (`stb_customer_city`),
  ADD KEY `stb_order_hdr_stb_discount_type_id_index` (`stb_discount_type_id`),
  ADD KEY `stb_order_hdr_stb_invoicing_type_id_index` (`stb_invoicing_type_id`),
  ADD KEY `stb_order_hdr_stb_billing_type_id_index` (`stb_billing_type_id`),
  ADD KEY `stb_order_hdr_stb_sample_description_id_index` (`stb_sample_description_id`),
  ADD KEY `stb_order_hdr_stb_sample_priority_id_index` (`stb_sample_priority_id`),
  ADD KEY `stb_order_hdr_stb_is_sealed_index` (`stb_is_sealed`),
  ADD KEY `stb_order_hdr_stb_is_signed_index` (`stb_is_signed`),
  ADD KEY `stb_order_hdr_stb_submission_type_index` (`stb_submission_type`),
  ADD KEY `stb_order_hdr_stb_created_by_index` (`stb_created_by`),
  ADD KEY `stb_reporting_to` (`stb_reporting_to`),
  ADD KEY `stb_invoicing_to` (`stb_invoicing_to`),
  ADD KEY `stb_product_id` (`stb_product_id`),
  ADD KEY `stb_status` (`stb_status`),
  ADD KEY `stb_batch_no` (`stb_batch_no`),
  ADD KEY `stb_po_date` (`stb_po_date`),
  ADD KEY `stb_po_no` (`stb_po_no`),
  ADD KEY `stb_prototype_date` (`stb_prototype_date`),
  ADD KEY `stb_sample_qty` (`stb_sample_qty`);

--
-- Indexes for table `stb_order_hdr_dtl`
--
ALTER TABLE `stb_order_hdr_dtl`
  ADD PRIMARY KEY (`stb_order_hdr_dtl_id`),
  ADD KEY `stb_order_hdr_dtl_stb_order_hdr_id_index` (`stb_order_hdr_id`),
  ADD KEY `stb_order_hdr_dtl_stb_product_id_index` (`stb_product_id`),
  ADD KEY `stb_order_hdr_dtl_stb_test_standard_id_index` (`stb_test_standard_id`),
  ADD KEY `stb_order_hdr_dtl_stb_product_test_id_index` (`stb_product_test_id`),
  ADD KEY `stb_order_book_status` (`stb_order_book_status`),
  ADD KEY `stb_start_date` (`stb_start_date`),
  ADD KEY `stb_end_date` (`stb_end_date`),
  ADD KEY `stb_label_name` (`stb_label_name`);

--
-- Indexes for table `stb_order_hdr_dtl_detail`
--
ALTER TABLE `stb_order_hdr_dtl_detail`
  ADD PRIMARY KEY (`stb_order_hdr_detail_id`),
  ADD KEY `stb_order_hdr_dtl_detail_stb_order_hdr_id_index` (`stb_order_hdr_id`),
  ADD KEY `stb_order_hdr_dtl_detail_stb_order_hdr_dtl_id_index` (`stb_order_hdr_dtl_id`),
  ADD KEY `stb_order_hdr_dtl_detail_stb_stability_type_id_index` (`stb_stability_type_id`),
  ADD KEY `stb_order_hdr_dtl_detail_stb_product_test_dtl_id_index` (`stb_product_test_dtl_id`),
  ADD KEY `stb_product_test_stf_id` (`stb_product_test_stf_id`),
  ADD KEY `stb_order_hdr_detail_status` (`stb_order_hdr_detail_status`),
  ADD KEY `stb_dtl_sample_qty` (`stb_dtl_sample_qty`);

--
-- Indexes for table `stb_order_noti_dtl`
--
ALTER TABLE `stb_order_noti_dtl`
  ADD PRIMARY KEY (`stb_order_noti_dtl_id`),
  ADD KEY `stb_order_noti_dtl_stb_order_hdr_id_index` (`stb_order_hdr_id`),
  ADD KEY `stb_order_noti_dtl_stb_order_hdr_dtl_id_index` (`stb_order_hdr_dtl_id`),
  ADD KEY `stb_order_noti_dtl_stb_order_noti_confirm_by_index` (`stb_order_noti_confirm_by`),
  ADD KEY `stb_order_noti_dtl_date` (`stb_order_noti_dtl_date`),
  ADD KEY `stb_order_noti_confirm_date` (`stb_order_noti_confirm_date`);

--
-- Indexes for table `stb_order_sample_qty_logs`
--
ALTER TABLE `stb_order_sample_qty_logs`
  ADD PRIMARY KEY (`stb_sq_logs_id`),
  ADD KEY `stb_order_sample_qty_logs_stb_order_hdr_id_index` (`stb_order_hdr_id`),
  ADD KEY `stb_log_sample_qty` (`stb_log_sample_qty`);

--
-- Indexes for table `stb_order_stability_types`
--
ALTER TABLE `stb_order_stability_types`
  ADD PRIMARY KEY (`stb_stability_type_id`),
  ADD UNIQUE KEY `stb_stability_type_name` (`stb_stability_type_name`),
  ADD KEY `stb_order_stability_types_stb_stability_type_status_index` (`stb_stability_type_status`);

--
-- Indexes for table `template_dtl`
--
ALTER TABLE `template_dtl`
  ADD PRIMARY KEY (`template_id`),
  ADD UNIQUE KEY `template_type_id_2` (`template_type_id`,`division_id`,`product_category_id`),
  ADD KEY `template_status_id` (`template_status_id`),
  ADD KEY `product_category_id` (`product_category_id`),
  ADD KEY `division_id` (`division_id`),
  ADD KEY `template_type_id` (`template_type_id`);

--
-- Indexes for table `template_types`
--
ALTER TABLE `template_types`
  ADD PRIMARY KEY (`template_type_id`);

--
-- Indexes for table `test_parameter`
--
ALTER TABLE `test_parameter`
  ADD PRIMARY KEY (`test_parameter_id`),
  ADD UNIQUE KEY `test_parameter_test_parameter_code_unique` (`test_parameter_code`),
  ADD KEY `test_parameter_test_parameter_category_id_index` (`test_parameter_category_id`),
  ADD KEY `test_parameter_created_by_index` (`created_by`),
  ADD KEY `test_parameter_id` (`test_parameter_id`),
  ADD KEY `test_parameter_category_id` (`test_parameter_category_id`),
  ADD KEY `test_parameter_invoicing_parent_id` (`test_parameter_invoicing_parent_id`),
  ADD KEY `test_parameter_invoicing` (`test_parameter_invoicing`),
  ADD KEY `test_parameter_nabl_scope` (`test_parameter_nabl_scope`),
  ADD KEY `test_parameter_decimal_place` (`test_parameter_decimal_place`);

--
-- Indexes for table `test_parameter_categories`
--
ALTER TABLE `test_parameter_categories`
  ADD PRIMARY KEY (`test_para_cat_id`),
  ADD UNIQUE KEY `test_parameter_categories_test_para_cat_code_unique` (`test_para_cat_code`),
  ADD UNIQUE KEY `test_para_cat_name` (`test_para_cat_name`,`product_category_id`),
  ADD KEY `test_parameter_categories_product_category_id_index` (`product_category_id`),
  ADD KEY `test_parameter_categories_created_by_index` (`created_by`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `category_sort_by` (`category_sort_by`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `test_parameter_equipment_types`
--
ALTER TABLE `test_parameter_equipment_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_parameter_equipment_types_test_parameter_id_index` (`test_parameter_id`),
  ADD KEY `test_parameter_equipment_types_equipment_type_id_index` (`equipment_type_id`);

--
-- Indexes for table `test_parameter_invoicing_parents`
--
ALTER TABLE `test_parameter_invoicing_parents`
  ADD PRIMARY KEY (`tpip_id`),
  ADD KEY `test_parameter_invoicing_parents_ test_parameter_id _foreign` (`test_parameter_id`),
  ADD KEY `test_parameter_status` (`test_parameter_status`);

--
-- Indexes for table `test_parameter_invoicing_parents_new`
--
ALTER TABLE `test_parameter_invoicing_parents_new`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_standard`
--
ALTER TABLE `test_standard`
  ADD PRIMARY KEY (`test_std_id`),
  ADD UNIQUE KEY `unique_index` (`test_std_name`,`product_category_id`),
  ADD KEY `test_standard_product_category_id_index` (`product_category_id`),
  ADD KEY `test_standard_created_by_index` (`created_by`),
  ADD KEY `test_std_code` (`test_std_code`);

--
-- Indexes for table `trf_hdrs`
--
ALTER TABLE `trf_hdrs`
  ADD PRIMARY KEY (`trf_id`),
  ADD UNIQUE KEY `trf_no` (`trf_no`),
  ADD KEY `trf_hdrs_trf_division_id_index` (`trf_division_id`),
  ADD KEY `trf_hdrs_trf_product_category_id_index` (`trf_product_category_id`),
  ADD KEY `trf_hdrs_trf_customer_id_index` (`trf_customer_id`),
  ADD KEY `trf_hdrs_trf_product_test_id_index` (`trf_product_test_id`),
  ADD KEY `trf_hdrs_trf_test_standard_id_index` (`trf_test_standard_id`),
  ADD KEY `trf_hdrs_trf_p_category_id_index` (`trf_p_category_id`),
  ADD KEY `trf_hdrs_trf_sub_p_category_id_index` (`trf_sub_p_category_id`),
  ADD KEY `trf_hdrs_trf_product_id_index` (`trf_product_id`),
  ADD KEY `trf_hdrs_trf_storage_condition_id_index` (`trf_storage_condition_id`),
  ADD KEY `trf_date` (`trf_date`),
  ADD KEY `trf_status` (`trf_status`),
  ADD KEY `trf_batch_no` (`trf_batch_no`),
  ADD KEY `trf_reporting_to` (`trf_reporting_to`),
  ADD KEY `trf_invoicing_to` (`trf_invoicing_to`),
  ADD KEY `trf_type` (`trf_type`);

--
-- Indexes for table `trf_hdr_dtls`
--
ALTER TABLE `trf_hdr_dtls`
  ADD PRIMARY KEY (`trf_hdr_dtl_id`),
  ADD KEY `trf_hdr_dtls_trf_hdr_id_index` (`trf_hdr_id`),
  ADD KEY `trf_hdr_dtls_trf_product_test_dtl_id_index` (`trf_product_test_dtl_id`),
  ADD KEY `trf_hdr_dtls_trf_test_parameter_id_index` (`trf_test_parameter_id`);

--
-- Indexes for table `trf_storge_condition_dtls`
--
ALTER TABLE `trf_storge_condition_dtls`
  ADD PRIMARY KEY (`trf_sc_id`),
  ADD KEY `trf_sc_name` (`trf_sc_name`),
  ADD KEY `trf_sc_status` (`trf_sc_status`);

--
-- Indexes for table `units_db`
--
ALTER TABLE `units_db`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `units_db_unit_code_unique` (`unit_code`),
  ADD KEY `units_db_created_by_foreign` (`created_by`);

--
-- Indexes for table `unit_conversion_db`
--
ALTER TABLE `unit_conversion_db`
  ADD PRIMARY KEY (`unit_conversion_id`),
  ADD KEY `unit_conversion_db_created_by_foreign` (`created_by`);

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
  ADD UNIQUE KEY `users_user_code_unique` (`user_code`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_division_id_index` (`division_id`),
  ADD KEY `users_created_by_index` (`created_by`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `name` (`name`),
  ADD KEY `password` (`password`),
  ADD KEY `is_sales_person` (`is_sales_person`),
  ADD KEY `user_signature` (`user_signature`),
  ADD KEY `activated_at` (`activated_at`),
  ADD KEY `deactivated_at` (`deactivated_at`),
  ADD KEY `status` (`status`),
  ADD KEY `password_changed_at` (`password_changed_at`);

--
-- Indexes for table `users_department_detail`
--
ALTER TABLE `users_department_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_department_detail_user_id_index` (`user_id`),
  ADD KEY `users_department_detail_department_id_index` (`department_id`);

--
-- Indexes for table `users_equipment_detail`
--
ALTER TABLE `users_equipment_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_equipment_detail_user_id_index` (`user_id`),
  ADD KEY `users_equipment_detail_equipment_type_id_index` (`equipment_type_id`);

--
-- Indexes for table `user_custom_permissions`
--
ALTER TABLE `user_custom_permissions`
  ADD PRIMARY KEY (`ucp_id`),
  ADD KEY `user_custom_permissions_ucp_user_id_index` (`ucp_user_id`);

--
-- Indexes for table `user_log_activities`
--
ALTER TABLE `user_log_activities`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `action` (`action`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_sales_target_details`
--
ALTER TABLE `user_sales_target_details`
  ADD PRIMARY KEY (`ust_id`),
  ADD UNIQUE KEY `ust_user_id` (`ust_user_id`,`ust_division_id`,`ust_product_category_id`,`ust_customer_id`,`ust_type_id`,`ust_month`,`ust_year`),
  ADD KEY `user_sales_target_details_ust_user_id_index` (`ust_user_id`),
  ADD KEY `user_sales_target_details_ust_division_id_index` (`ust_division_id`),
  ADD KEY `user_sales_target_details_ust_product_category_id_index` (`ust_product_category_id`),
  ADD KEY `user_sales_target_details_ust_customer_id_index` (`ust_customer_id`),
  ADD KEY `user_sales_target_details_ust_type_id_index` (`ust_type_id`),
  ADD KEY `user_sales_target_details_ust_fin_year_id_index` (`ust_fin_year_id`),
  ADD KEY `user_sales_target_details_created_by_index` (`created_by`);

--
-- Indexes for table `user_sales_target_types`
--
ALTER TABLE `user_sales_target_types`
  ADD PRIMARY KEY (`usty_id`),
  ADD UNIQUE KEY `user_sales_target_types_usty_code_unique` (`usty_code`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `vendors_vendor_code_unique` (`vendor_code`),
  ADD KEY `vendors_division_id_index` (`division_id`),
  ADD KEY `vendors_vendor_state_index` (`vendor_state`),
  ADD KEY `vendors_vendor_city_index` (`vendor_city`),
  ADD KEY `vendors_created_by_foreign` (`created_by`);

--
-- Indexes for table `voc_mail_dtl`
--
ALTER TABLE `voc_mail_dtl`
  ADD PRIMARY KEY (`voc_id`),
  ADD KEY `voc_mail_dtl_voc_customer_id_index` (`voc_customer_id`),
  ADD KEY `voc_mail_dtl_voc_status_index` (`voc_status`),
  ADD KEY `voc_template_name` (`voc_template_name`),
  ADD KEY `voc_mail_date` (`voc_mail_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `central_po_dtls`
--
ALTER TABLE `central_po_dtls`
  MODIFY `cpo_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `central_stp_dtls`
--
ALTER TABLE `central_stp_dtls`
  MODIFY `cstp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `city_db`
--
ALTER TABLE `city_db`
  MODIFY `city_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48394;
--
-- AUTO_INCREMENT for table `column_master`
--
ALTER TABLE `column_master`
  MODIFY `column_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `company_master`
--
ALTER TABLE `company_master`
  MODIFY `company_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `countries_db`
--
ALTER TABLE `countries_db`
  MODIFY `country_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;
--
-- AUTO_INCREMENT for table `credit_notes`
--
ALTER TABLE `credit_notes`
  MODIFY `credit_note_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8541;
--
-- AUTO_INCREMENT for table `customer_billing_types`
--
ALTER TABLE `customer_billing_types`
  MODIFY `billing_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `customer_company_type`
--
ALTER TABLE `customer_company_type`
  MODIFY `company_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `customer_com_crm_email_addresses`
--
ALTER TABLE `customer_com_crm_email_addresses`
  MODIFY `cccea_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_contact_persons`
--
ALTER TABLE `customer_contact_persons`
  MODIFY `contact_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16280;
--
-- AUTO_INCREMENT for table `customer_defined_structures`
--
ALTER TABLE `customer_defined_structures`
  MODIFY `cdit_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15372;
--
-- AUTO_INCREMENT for table `customer_discount_types`
--
ALTER TABLE `customer_discount_types`
  MODIFY `discount_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `customer_email_addresses`
--
ALTER TABLE `customer_email_addresses`
  MODIFY `customer_email_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21926;
--
-- AUTO_INCREMENT for table `customer_email_addresses_bk_28M22`
--
ALTER TABLE `customer_email_addresses_bk_28M22`
  MODIFY `customer_email_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16734;
--
-- AUTO_INCREMENT for table `customer_excecutives`
--
ALTER TABLE `customer_excecutives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;
--
-- AUTO_INCREMENT for table `customer_excecutives1`
--
ALTER TABLE `customer_excecutives1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1540;
--
-- AUTO_INCREMENT for table `customer_exist_account_hold_upload_dtl`
--
ALTER TABLE `customer_exist_account_hold_upload_dtl`
  MODIFY `ceahud_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_gst_categories`
--
ALTER TABLE `customer_gst_categories`
  MODIFY `cgc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `customer_gst_tax_slab_types`
--
ALTER TABLE `customer_gst_tax_slab_types`
  MODIFY `cgtst_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `customer_gst_types`
--
ALTER TABLE `customer_gst_types`
  MODIFY `cgt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `customer_hold_dtl`
--
ALTER TABLE `customer_hold_dtl`
  MODIFY `chd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37074;
--
-- AUTO_INCREMENT for table `customer_invoicing_rates`
--
ALTER TABLE `customer_invoicing_rates`
  MODIFY `cir_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148025;
--
-- AUTO_INCREMENT for table `customer_invoicing_running_time`
--
ALTER TABLE `customer_invoicing_running_time`
  MODIFY `invoicing_running_time_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `customer_invoicing_types`
--
ALTER TABLE `customer_invoicing_types`
  MODIFY `invoicing_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `customer_locations`
--
ALTER TABLE `customer_locations`
  MODIFY `location_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_logic_detail`
--
ALTER TABLE `customer_logic_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3485;
--
-- AUTO_INCREMENT for table `customer_master`
--
ALTER TABLE `customer_master`
  MODIFY `customer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16297;
--
-- AUTO_INCREMENT for table `customer_ownership_type`
--
ALTER TABLE `customer_ownership_type`
  MODIFY `ownership_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `customer_priority_types`
--
ALTER TABLE `customer_priority_types`
  MODIFY `customer_priority_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `customer_sales_executive_logs`
--
ALTER TABLE `customer_sales_executive_logs`
  MODIFY `csel_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20378;
--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `custom_defined_fields`
--
ALTER TABLE `custom_defined_fields`
  MODIFY `label_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `debit_notes`
--
ALTER TABLE `debit_notes`
  MODIFY `debit_note_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `department_product_categories_link`
--
ALTER TABLE `department_product_categories_link`
  MODIFY `department_product_categories_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `department_type`
--
ALTER TABLE `department_type`
  MODIFY `department_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `detector_master`
--
ALTER TABLE `detector_master`
  MODIFY `detector_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `division_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `division_parameters`
--
ALTER TABLE `division_parameters`
  MODIFY `division_parameter_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `division_wise_items`
--
ALTER TABLE `division_wise_items`
  MODIFY `division_wise_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `division_wise_item_stock`
--
ALTER TABLE `division_wise_item_stock`
  MODIFY `division_wise_item_stock_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `division_wise_stores`
--
ALTER TABLE `division_wise_stores`
  MODIFY `store_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employee_sales_dtl`
--
ALTER TABLE `employee_sales_dtl`
  MODIFY `ust_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9796;
--
-- AUTO_INCREMENT for table `equipment_type`
--
ALTER TABLE `equipment_type`
  MODIFY `equipment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=495;
--
-- AUTO_INCREMENT for table `holiday_master`
--
ALTER TABLE `holiday_master`
  MODIFY `holiday_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;
--
-- AUTO_INCREMENT for table `ign_hdr`
--
ALTER TABLE `ign_hdr`
  MODIFY `ign_hdr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ign_hdr_dtl`
--
ALTER TABLE `ign_hdr_dtl`
  MODIFY `ign_hdr_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `indent_hdr`
--
ALTER TABLE `indent_hdr`
  MODIFY `indent_hdr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `indent_hdr_detail`
--
ALTER TABLE `indent_hdr_detail`
  MODIFY `indent_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inquiry_followups`
--
ALTER TABLE `inquiry_followups`
  MODIFY `followup_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instance_master`
--
ALTER TABLE `instance_master`
  MODIFY `instance_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `invoice_cancellation_dtls`
--
ALTER TABLE `invoice_cancellation_dtls`
  MODIFY `invoice_cancellation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7541;
--
-- AUTO_INCREMENT for table `invoice_dispatch_dtls`
--
ALTER TABLE `invoice_dispatch_dtls`
  MODIFY `invoice_dispatch_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89356;
--
-- AUTO_INCREMENT for table `invoice_financial_years`
--
ALTER TABLE `invoice_financial_years`
  MODIFY `ify_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `invoice_hdr`
--
ALTER TABLE `invoice_hdr`
  MODIFY `invoice_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105878;
--
-- AUTO_INCREMENT for table `invoice_hdr_detail`
--
ALTER TABLE `invoice_hdr_detail`
  MODIFY `invoice_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=551328;
--
-- AUTO_INCREMENT for table `invoice_session_dtl`
--
ALTER TABLE `invoice_session_dtl`
  MODIFY `invoice_session_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `itc_std_rate_dtl`
--
ALTER TABLE `itc_std_rate_dtl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15378;
--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `item_cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_master`
--
ALTER TABLE `item_master`
  MODIFY `item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `method_master`
--
ALTER TABLE `method_master`
  MODIFY `method_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22780;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `missing_credit_notes`
--
ALTER TABLE `missing_credit_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT for table `mis_report_default_types`
--
ALTER TABLE `mis_report_default_types`
  MODIFY `mis_report_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;
--
-- AUTO_INCREMENT for table `module_navigations`
--
ALTER TABLE `module_navigations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2592;
--
-- AUTO_INCREMENT for table `module_permissions`
--
ALTER TABLE `module_permissions`
  MODIFY `module_permission_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=860;
--
-- AUTO_INCREMENT for table `order_accreditation_certificate_master`
--
ALTER TABLE `order_accreditation_certificate_master`
  MODIFY `oac_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `order_amended_dtl`
--
ALTER TABLE `order_amended_dtl`
  MODIFY `oad_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23103;
--
-- AUTO_INCREMENT for table `order_amendment_master`
--
ALTER TABLE `order_amendment_master`
  MODIFY `oam_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_analyst_window_settings`
--
ALTER TABLE `order_analyst_window_settings`
  MODIFY `oaws_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `order_brand_type_dtl`
--
ALTER TABLE `order_brand_type_dtl`
  MODIFY `obtd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2496;
--
-- AUTO_INCREMENT for table `order_cancellation_dtls`
--
ALTER TABLE `order_cancellation_dtls`
  MODIFY `order_cancellation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10936;
--
-- AUTO_INCREMENT for table `order_cancellation_types`
--
ALTER TABLE `order_cancellation_types`
  MODIFY `order_cancellation_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `order_client_approval_dtl`
--
ALTER TABLE `order_client_approval_dtl`
  MODIFY `ocad_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1162;
--
-- AUTO_INCREMENT for table `order_defined_test_std_dtl`
--
ALTER TABLE `order_defined_test_std_dtl`
  MODIFY `odtsd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `order_discipline_group_dtl`
--
ALTER TABLE `order_discipline_group_dtl`
  MODIFY `odg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=714122;
--
-- AUTO_INCREMENT for table `order_dispatch_dtl`
--
ALTER TABLE `order_dispatch_dtl`
  MODIFY `dispatch_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=470008;
--
-- AUTO_INCREMENT for table `order_dynamic_fields`
--
ALTER TABLE `order_dynamic_fields`
  MODIFY `odfs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `order_dynamic_field_dtl`
--
ALTER TABLE `order_dynamic_field_dtl`
  MODIFY `odf_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37127;
--
-- AUTO_INCREMENT for table `order_expected_due_date_logs`
--
ALTER TABLE `order_expected_due_date_logs`
  MODIFY `oeddl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10256;
--
-- AUTO_INCREMENT for table `order_header_notes`
--
ALTER TABLE `order_header_notes`
  MODIFY `header_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `order_hold_master`
--
ALTER TABLE `order_hold_master`
  MODIFY `oh_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_incharge_dtl`
--
ALTER TABLE `order_incharge_dtl`
  MODIFY `oid_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2649922;
--
-- AUTO_INCREMENT for table `order_incharge_process_dtl`
--
ALTER TABLE `order_incharge_process_dtl`
  MODIFY `oipd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26605;
--
-- AUTO_INCREMENT for table `order_linked_po_dtl`
--
ALTER TABLE `order_linked_po_dtl`
  MODIFY `olpd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_linked_stp_dtl`
--
ALTER TABLE `order_linked_stp_dtl`
  MODIFY `olsd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_linked_trf_dtl`
--
ALTER TABLE `order_linked_trf_dtl`
  MODIFY `oltd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44189;
--
-- AUTO_INCREMENT for table `order_mail_dtl`
--
ALTER TABLE `order_mail_dtl`
  MODIFY `mail_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=937571;
--
-- AUTO_INCREMENT for table `order_mail_status`
--
ALTER TABLE `order_mail_status`
  MODIFY `oms_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `order_master`
--
ALTER TABLE `order_master`
  MODIFY `order_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=515616;
--
-- AUTO_INCREMENT for table `order_parameters_detail`
--
ALTER TABLE `order_parameters_detail`
  MODIFY `analysis_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8094842;
--
-- AUTO_INCREMENT for table `order_process_log`
--
ALTER TABLE `order_process_log`
  MODIFY `opl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18574466;
--
-- AUTO_INCREMENT for table `order_purchase_order_logs`
--
ALTER TABLE `order_purchase_order_logs`
  MODIFY `opol_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18235;
--
-- AUTO_INCREMENT for table `order_report_details`
--
ALTER TABLE `order_report_details`
  MODIFY `order_report_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=505424;
--
-- AUTO_INCREMENT for table `order_report_disciplines`
--
ALTER TABLE `order_report_disciplines`
  MODIFY `or_discipline_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `order_report_discipline_parameter_dtls`
--
ALTER TABLE `order_report_discipline_parameter_dtls`
  MODIFY `ordp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1684;
--
-- AUTO_INCREMENT for table `order_report_groups`
--
ALTER TABLE `order_report_groups`
  MODIFY `org_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `order_report_header_types`
--
ALTER TABLE `order_report_header_types`
  MODIFY `orht_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `order_report_microbiological_dtl`
--
ALTER TABLE `order_report_microbiological_dtl`
  MODIFY `ormbd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84348;
--
-- AUTO_INCREMENT for table `order_report_middle_authorized_signs`
--
ALTER TABLE `order_report_middle_authorized_signs`
  MODIFY `ormad_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8941;
--
-- AUTO_INCREMENT for table `order_report_note_remark_default`
--
ALTER TABLE `order_report_note_remark_default`
  MODIFY `remark_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;
--
-- AUTO_INCREMENT for table `order_report_options`
--
ALTER TABLE `order_report_options`
  MODIFY `report_option_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `order_report_settings`
--
ALTER TABLE `order_report_settings`
  MODIFY `ors_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;
--
-- AUTO_INCREMENT for table `order_report_sign_dtls`
--
ALTER TABLE `order_report_sign_dtls`
  MODIFY `orsd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
--
-- AUTO_INCREMENT for table `order_sample_priority`
--
ALTER TABLE `order_sample_priority`
  MODIFY `sample_priority_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `order_sealed_unsealed`
--
ALTER TABLE `order_sealed_unsealed`
  MODIFY `osus_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `order_signed_unsigned`
--
ALTER TABLE `order_signed_unsigned`
  MODIFY `osu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `order_stability_notes`
--
ALTER TABLE `order_stability_notes`
  MODIFY `stability_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3318;
--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_status_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `payment_made_hdr`
--
ALTER TABLE `payment_made_hdr`
  MODIFY `payment_made_hdr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_received_hdr`
--
ALTER TABLE `payment_received_hdr`
  MODIFY `payment_received_hdr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_sources`
--
ALTER TABLE `payment_sources`
  MODIFY `payment_source_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `po_hdr`
--
ALTER TABLE `po_hdr`
  MODIFY `po_hdr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_hdr_detail`
--
ALTER TABLE `po_hdr_detail`
  MODIFY `po_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_indent_detail`
--
ALTER TABLE `po_indent_detail`
  MODIFY `po_indent_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_status`
--
ALTER TABLE `po_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `p_category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=542;
--
-- AUTO_INCREMENT for table `product_master`
--
ALTER TABLE `product_master`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32770;
--
-- AUTO_INCREMENT for table `product_master_alias`
--
ALTER TABLE `product_master_alias`
  MODIFY `c_product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124782;
--
-- AUTO_INCREMENT for table `product_test_dtl`
--
ALTER TABLE `product_test_dtl`
  MODIFY `product_test_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=608325;
--
-- AUTO_INCREMENT for table `product_test_hdr`
--
ALTER TABLE `product_test_hdr`
  MODIFY `test_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34525;
--
-- AUTO_INCREMENT for table `product_test_parameter_altern_method`
--
ALTER TABLE `product_test_parameter_altern_method`
  MODIFY `product_test_param_altern_method_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `report_header_type_default`
--
ALTER TABLE `report_header_type_default`
  MODIFY `rhtd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12756;
--
-- AUTO_INCREMENT for table `rol_setting`
--
ALTER TABLE `rol_setting`
  MODIFY `rol_setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `samples`
--
ALTER TABLE `samples`
  MODIFY `sample_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138454;
--
-- AUTO_INCREMENT for table `sample_modes`
--
ALTER TABLE `sample_modes`
  MODIFY `sample_mode_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `scheduled_mail_dtl`
--
ALTER TABLE `scheduled_mail_dtl`
  MODIFY `smd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58709;
--
-- AUTO_INCREMENT for table `scheduled_mis_report_dtls`
--
ALTER TABLE `scheduled_mis_report_dtls`
  MODIFY `smrd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2101;
--
-- AUTO_INCREMENT for table `schedulings`
--
ALTER TABLE `schedulings`
  MODIFY `scheduling_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8146500;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT for table `setting_groups`
--
ALTER TABLE `setting_groups`
  MODIFY `setting_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `state_db`
--
ALTER TABLE `state_db`
  MODIFY `state_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4126;
--
-- AUTO_INCREMENT for table `status_master`
--
ALTER TABLE `status_master`
  MODIFY `status_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `stb_order_hdr`
--
ALTER TABLE `stb_order_hdr`
  MODIFY `stb_order_hdr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=493;
--
-- AUTO_INCREMENT for table `stb_order_hdr_dtl`
--
ALTER TABLE `stb_order_hdr_dtl`
  MODIFY `stb_order_hdr_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2075;
--
-- AUTO_INCREMENT for table `stb_order_hdr_dtl_detail`
--
ALTER TABLE `stb_order_hdr_dtl_detail`
  MODIFY `stb_order_hdr_detail_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41385;
--
-- AUTO_INCREMENT for table `stb_order_noti_dtl`
--
ALTER TABLE `stb_order_noti_dtl`
  MODIFY `stb_order_noti_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2075;
--
-- AUTO_INCREMENT for table `stb_order_sample_qty_logs`
--
ALTER TABLE `stb_order_sample_qty_logs`
  MODIFY `stb_sq_logs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=537;
--
-- AUTO_INCREMENT for table `stb_order_stability_types`
--
ALTER TABLE `stb_order_stability_types`
  MODIFY `stb_stability_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `template_dtl`
--
ALTER TABLE `template_dtl`
  MODIFY `template_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `template_types`
--
ALTER TABLE `template_types`
  MODIFY `template_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `test_parameter`
--
ALTER TABLE `test_parameter`
  MODIFY `test_parameter_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82071;
--
-- AUTO_INCREMENT for table `test_parameter_categories`
--
ALTER TABLE `test_parameter_categories`
  MODIFY `test_para_cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=541;
--
-- AUTO_INCREMENT for table `test_parameter_equipment_types`
--
ALTER TABLE `test_parameter_equipment_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180171;
--
-- AUTO_INCREMENT for table `test_parameter_invoicing_parents`
--
ALTER TABLE `test_parameter_invoicing_parents`
  MODIFY `tpip_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT for table `test_parameter_invoicing_parents_new`
--
ALTER TABLE `test_parameter_invoicing_parents_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `test_standard`
--
ALTER TABLE `test_standard`
  MODIFY `test_std_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=918;
--
-- AUTO_INCREMENT for table `trf_hdrs`
--
ALTER TABLE `trf_hdrs`
  MODIFY `trf_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `trf_hdr_dtls`
--
ALTER TABLE `trf_hdr_dtls`
  MODIFY `trf_hdr_dtl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `trf_storge_condition_dtls`
--
ALTER TABLE `trf_storge_condition_dtls`
  MODIFY `trf_sc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=837;
--
-- AUTO_INCREMENT for table `users_department_detail`
--
ALTER TABLE `users_department_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18752;
--
-- AUTO_INCREMENT for table `users_equipment_detail`
--
ALTER TABLE `users_equipment_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123694;
--
-- AUTO_INCREMENT for table `user_custom_permissions`
--
ALTER TABLE `user_custom_permissions`
  MODIFY `ucp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `user_log_activities`
--
ALTER TABLE `user_log_activities`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1155057;
--
-- AUTO_INCREMENT for table `user_sales_target_details`
--
ALTER TABLE `user_sales_target_details`
  MODIFY `ust_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_sales_target_types`
--
ALTER TABLE `user_sales_target_types`
  MODIFY `usty_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `voc_mail_dtl`
--
ALTER TABLE `voc_mail_dtl`
  MODIFY `voc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31883;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `central_po_dtls`
--
ALTER TABLE `central_po_dtls`
  ADD CONSTRAINT `central_po_dtls_cpo_customer_city_foreign` FOREIGN KEY (`cpo_customer_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `central_po_dtls_cpo_customer_id_foreign` FOREIGN KEY (`cpo_customer_id`) REFERENCES `customer_master` (`customer_id`);

--
-- Constraints for table `central_stp_dtls`
--
ALTER TABLE `central_stp_dtls`
  ADD CONSTRAINT `central_stp_dtls_cstp_customer_city_foreign` FOREIGN KEY (`cstp_customer_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `central_stp_dtls_cstp_customer_id_foreign` FOREIGN KEY (`cstp_customer_id`) REFERENCES `customer_master` (`customer_id`);

--
-- Constraints for table `city_db`
--
ALTER TABLE `city_db`
  ADD CONSTRAINT `city_db_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `city_db_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `state_db` (`state_id`) ON DELETE CASCADE;

--
-- Constraints for table `column_master`
--
ALTER TABLE `column_master`
  ADD CONSTRAINT `column_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `column_master_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `column_master_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `company_master`
--
ALTER TABLE `company_master`
  ADD CONSTRAINT `company_master_company_city_foreign` FOREIGN KEY (`company_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `company_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD CONSTRAINT `credit_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `credit_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `credit_notes_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `credit_notes_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_hdr` (`invoice_id`),
  ADD CONSTRAINT `credit_notes_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `customer_company_type`
--
ALTER TABLE `customer_company_type`
  ADD CONSTRAINT `customer_company_type_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_com_crm_email_addresses`
--
ALTER TABLE `customer_com_crm_email_addresses`
  ADD CONSTRAINT `cccea_division_id_foreign` FOREIGN KEY (`cccea_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `cccea_product_category_id_foreign` FOREIGN KEY (`cccea_product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `customer_com_crm_email_addresses_cccea_created_by_foreign` FOREIGN KEY (`cccea_created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_contact_persons`
--
ALTER TABLE `customer_contact_persons`
  ADD CONSTRAINT `customer_contact_persons_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_defined_structures`
--
ALTER TABLE `customer_defined_structures`
  ADD CONSTRAINT `customer_defined_structures_ billing_type_id _foreign` FOREIGN KEY (`billing_type_id`) REFERENCES `customer_billing_types` (`billing_type_id`),
  ADD CONSTRAINT `customer_defined_structures_ discount_type_id _foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `customer_discount_types` (`discount_type_id`),
  ADD CONSTRAINT `customer_defined_structures_ division_id _foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `customer_defined_structures_ product_cayegory_id _foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `customer_defined_structures_customer_id _foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `customer_defined_structures_invoicing_type_id_foreign` FOREIGN KEY (`invoicing_type_id`) REFERENCES `customer_invoicing_types` (`invoicing_type_id`);

--
-- Constraints for table `customer_email_addresses`
--
ALTER TABLE `customer_email_addresses`
  ADD CONSTRAINT `customer_email_addresses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_exist_account_hold_upload_dtl`
--
ALTER TABLE `customer_exist_account_hold_upload_dtl`
  ADD CONSTRAINT `ceahud_created_by` FOREIGN KEY (`ceahud_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ceahud_customer_id` FOREIGN KEY (`ceahud_customer_id`) REFERENCES `customer_master` (`customer_id`);

--
-- Constraints for table `customer_hold_dtl`
--
ALTER TABLE `customer_hold_dtl`
  ADD CONSTRAINT `customer_hold_dtl_chd_customer_id_foreign` FOREIGN KEY (`chd_customer_id`) REFERENCES `customer_master` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_invoicing_rates`
--
ALTER TABLE `customer_invoicing_rates`
  ADD CONSTRAINT `customer_invoicing_rates_ cir_division_id _foreign` FOREIGN KEY (`cir_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `customer_invoicing_rates_cir_c_product_id_foreign` FOREIGN KEY (`cir_c_product_id`) REFERENCES `product_master_alias` (`c_product_id`),
  ADD CONSTRAINT `customer_invoicing_rates_cir_city_id_foreign` FOREIGN KEY (`cir_city_id`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `customer_invoicing_rates_cir_country_id_foreign` FOREIGN KEY (`cir_country_id`) REFERENCES `countries_db` (`country_id`),
  ADD CONSTRAINT `customer_invoicing_rates_cir_customer_id_foreign` FOREIGN KEY (`cir_customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `customer_invoicing_rates_cir_parameter_id_foreign` FOREIGN KEY (`cir_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`),
  ADD CONSTRAINT `customer_invoicing_rates_cir_product_category_id_foreign` FOREIGN KEY (`cir_product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `customer_invoicing_rates_cir_state_id_foreign` FOREIGN KEY (`cir_state_id`) REFERENCES `state_db` (`state_id`),
  ADD CONSTRAINT `customer_invoicing_rates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `customer_invoicing_rates_invoicing_type_id_foreign` FOREIGN KEY (`invoicing_type_id`) REFERENCES `customer_invoicing_types` (`invoicing_type_id`);

--
-- Constraints for table `customer_locations`
--
ALTER TABLE `customer_locations`
  ADD CONSTRAINT `customer_locations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_master`
--
ALTER TABLE `customer_master`
  ADD CONSTRAINT `customer_master_billing_type_foreign` FOREIGN KEY (`billing_type`) REFERENCES `customer_billing_types` (`billing_type_id`),
  ADD CONSTRAINT `customer_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `customer_master_customer_city_foreign` FOREIGN KEY (`customer_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `customer_master_customer_country_foreign` FOREIGN KEY (`customer_country`) REFERENCES `countries_db` (`country_id`),
  ADD CONSTRAINT `customer_master_customer_state_foreign` FOREIGN KEY (`customer_state`) REFERENCES `state_db` (`state_id`),
  ADD CONSTRAINT `customer_master_invoicing_type_id_foreign` FOREIGN KEY (`invoicing_type_id`) REFERENCES `customer_invoicing_types` (`invoicing_type_id`),
  ADD CONSTRAINT `customer_master_sale_executive_foreign` FOREIGN KEY (`sale_executive`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_ownership_type`
--
ALTER TABLE `customer_ownership_type`
  ADD CONSTRAINT `customer_ownership_type_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_sales_executive_logs`
--
ALTER TABLE `customer_sales_executive_logs`
  ADD CONSTRAINT `customer_sales_executive_logs_csel_created_by_foreign` FOREIGN KEY (`csel_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `customer_sales_executive_logs_csel_customer_id_foreign` FOREIGN KEY (`csel_customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `customer_sales_executive_logs_csel_sale_executive_id_foreign` FOREIGN KEY (`csel_sale_executive_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `debit_notes`
--
ALTER TABLE `debit_notes`
  ADD CONSTRAINT `debit_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `debit_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `debit_notes_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `debit_notes_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_hdr` (`invoice_id`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_master` (`company_id`),
  ADD CONSTRAINT `departments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `departments_department_type_foreign` FOREIGN KEY (`department_type`) REFERENCES `department_type` (`department_type_id`);

--
-- Constraints for table `department_product_categories_link`
--
ALTER TABLE `department_product_categories_link`
  ADD CONSTRAINT `department_product_categories_link_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `department_product_categories_link_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `department_type`
--
ALTER TABLE `department_type`
  ADD CONSTRAINT `department_type_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detector_master`
--
ALTER TABLE `detector_master`
  ADD CONSTRAINT `detector_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `detector_master_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `detector_master_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `divisions`
--
ALTER TABLE `divisions`
  ADD CONSTRAINT `divisions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_master` (`company_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `divisions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `division_parameters`
--
ALTER TABLE `division_parameters`
  ADD CONSTRAINT `division_parameters_division_city_foreign` FOREIGN KEY (`division_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `division_parameters_division_country_foreign` FOREIGN KEY (`division_country`) REFERENCES `countries_db` (`country_id`),
  ADD CONSTRAINT `division_parameters_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `division_parameters_division_state_foreign` FOREIGN KEY (`division_state`) REFERENCES `state_db` (`state_id`);

--
-- Constraints for table `division_wise_items`
--
ALTER TABLE `division_wise_items`
  ADD CONSTRAINT `division_wise_items_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `division_wise_items_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `division_wise_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`);

--
-- Constraints for table `division_wise_item_stock`
--
ALTER TABLE `division_wise_item_stock`
  ADD CONSTRAINT `division_wise_item_stock_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `division_wise_item_stock_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `division_wise_item_stock_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`),
  ADD CONSTRAINT `division_wise_item_stock_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `division_wise_stores` (`store_id`);

--
-- Constraints for table `division_wise_stores`
--
ALTER TABLE `division_wise_stores`
  ADD CONSTRAINT `division_wise_stores_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `division_wise_stores_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`);

--
-- Constraints for table `equipment_type`
--
ALTER TABLE `equipment_type`
  ADD CONSTRAINT `equipment_type_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `equipment_type_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `holiday_master`
--
ALTER TABLE `holiday_master`
  ADD CONSTRAINT `holiday_master_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`);

--
-- Constraints for table `ign_hdr`
--
ALTER TABLE `ign_hdr`
  ADD CONSTRAINT `ign_hdr_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ign_hdr_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `ign_hdr_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ign_hdr_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`);

--
-- Constraints for table `ign_hdr_dtl`
--
ALTER TABLE `ign_hdr_dtl`
  ADD CONSTRAINT `ign_hdr_dtl_ign_hdr_id_foreign` FOREIGN KEY (`ign_hdr_id`) REFERENCES `ign_hdr` (`ign_hdr_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ign_hdr_dtl_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`),
  ADD CONSTRAINT `ign_hdr_dtl_po_hdr_id_foreign` FOREIGN KEY (`po_hdr_id`) REFERENCES `po_hdr` (`po_hdr_id`);

--
-- Constraints for table `indent_hdr`
--
ALTER TABLE `indent_hdr`
  ADD CONSTRAINT `indent_hdr_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `indent_hdr_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `indent_hdr_indented_by_foreign` FOREIGN KEY (`indented_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `indent_hdr_detail`
--
ALTER TABLE `indent_hdr_detail`
  ADD CONSTRAINT `indent_hdr_detail_indent_hdr_id_foreign` FOREIGN KEY (`indent_hdr_id`) REFERENCES `indent_hdr` (`indent_hdr_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `indent_hdr_detail_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`);

--
-- Constraints for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD CONSTRAINT `inquiry_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inquiry_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`);

--
-- Constraints for table `inquiry_followups`
--
ALTER TABLE `inquiry_followups`
  ADD CONSTRAINT `followup_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followup_followup_by_foreign` FOREIGN KEY (`followup_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followup_inquiry_id_foreign` FOREIGN KEY (`inquiry_id`) REFERENCES `inquiry` (`id`);

--
-- Constraints for table `instance_master`
--
ALTER TABLE `instance_master`
  ADD CONSTRAINT `instance_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `instance_master_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `instance_master_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `invoice_cancellation_dtls`
--
ALTER TABLE `invoice_cancellation_dtls`
  ADD CONSTRAINT `invoice_cancellation_dtls_invoice_cancelled_by_foreign` FOREIGN KEY (`invoice_cancelled_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoice_cancellation_dtls_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_hdr` (`invoice_id`);

--
-- Constraints for table `invoice_dispatch_dtls`
--
ALTER TABLE `invoice_dispatch_dtls`
  ADD CONSTRAINT `invoice_dispatch_dtls_invoice_dispatch_by_foreign` FOREIGN KEY (`invoice_dispatch_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoice_dispatch_dtls_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_hdr` (`invoice_id`);

--
-- Constraints for table `invoice_hdr`
--
ALTER TABLE `invoice_hdr`
  ADD CONSTRAINT `invoice_hdr_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoice_hdr_customer_gst_tax_slab_type_id_foreign` FOREIGN KEY (`customer_gst_tax_slab_type_id`) REFERENCES `customer_gst_tax_slab_types` (`cgtst_id`),
  ADD CONSTRAINT `invoice_hdr_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `invoice_hdr_customer_invoicing_id_foreign` FOREIGN KEY (`customer_invoicing_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `invoice_hdr_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `invoice_hdr_inv_fin_yr_id_foreign` FOREIGN KEY (`inv_fin_yr_id`) REFERENCES `invoice_financial_years` (`ify_id`),
  ADD CONSTRAINT `invoice_hdr_invoice_type_foreign` FOREIGN KEY (`invoice_type`) REFERENCES `customer_billing_types` (`billing_type_id`),
  ADD CONSTRAINT `invoice_hdr_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `invoice_hdr_detail`
--
ALTER TABLE `invoice_hdr_detail`
  ADD CONSTRAINT `invoice_hdr_detail_invoice_hdr_id_foreign` FOREIGN KEY (`invoice_hdr_id`) REFERENCES `invoice_hdr` (`invoice_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_hdr_detail_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`),
  ADD CONSTRAINT `invoice_hdr_detail_order_invoicing_to_foreign` FOREIGN KEY (`order_invoicing_to`) REFERENCES `customer_master` (`customer_id`);

--
-- Constraints for table `invoice_session_dtl`
--
ALTER TABLE `invoice_session_dtl`
  ADD CONSTRAINT `invoice_session_dtl_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `invoice_session_dtl_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD CONSTRAINT `item_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `item_master`
--
ALTER TABLE `item_master`
  ADD CONSTRAINT `item_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `item_master_item_cat_id_foreign` FOREIGN KEY (`item_cat_id`) REFERENCES `item_categories` (`item_cat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_master_item_unit_foreign` FOREIGN KEY (`item_unit`) REFERENCES `units_db` (`unit_id`);

--
-- Constraints for table `method_master`
--
ALTER TABLE `method_master`
  ADD CONSTRAINT `method_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `method_master_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `method_master_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `module_navigations`
--
ALTER TABLE `module_navigations`
  ADD CONSTRAINT `module_navigations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `module_navigations_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `module_navigations_module_menu_id_foreign` FOREIGN KEY (`module_menu_id`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `module_navigations_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `module_permissions`
--
ALTER TABLE `module_permissions`
  ADD CONSTRAINT `module_permissions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `module_permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `module_permissions_module_menu_id_foreign` FOREIGN KEY (`module_menu_id`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `module_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `module_permissions_sub_module_id_foreign` FOREIGN KEY (`sub_module_id`) REFERENCES `modules` (`id`);

--
-- Constraints for table `order_accreditation_certificate_master`
--
ALTER TABLE `order_accreditation_certificate_master`
  ADD CONSTRAINT `order_accr_certificate_master_oac_product_category_id_foreign` FOREIGN KEY (`oac_product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `order_accreditation_certificate_master_oac_created_by_foreign` FOREIGN KEY (`oac_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_accreditation_certificate_master_oac_division_id_foreign` FOREIGN KEY (`oac_division_id`) REFERENCES `divisions` (`division_id`);

--
-- Constraints for table `order_amended_dtl`
--
ALTER TABLE `order_amended_dtl`
  ADD CONSTRAINT `order_amended_dtl_oad_amended_by_foreign` FOREIGN KEY (`oad_amended_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_amended_dtl_oad_amended_stage_foreign` FOREIGN KEY (`oad_amended_stage`) REFERENCES `order_status` (`order_status_id`),
  ADD CONSTRAINT `order_amended_dtl_oad_order_id_foreign` FOREIGN KEY (`oad_order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_analyst_window_settings`
--
ALTER TABLE `order_analyst_window_settings`
  ADD CONSTRAINT `order_analyst_window_settings_oaws_created_by_foreign` FOREIGN KEY (`oaws_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_analyst_window_settings_oaws_division_id_foreign` FOREIGN KEY (`oaws_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `order_analyst_window_settings_oaws_equipment_type_id_foreign` FOREIGN KEY (`oaws_equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `order_analyst_window_settings_oaws_product_category_id_foreign` FOREIGN KEY (`oaws_product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `order_cancellation_dtls`
--
ALTER TABLE `order_cancellation_dtls`
  ADD CONSTRAINT `order_cancellation_dtls_cancellation_stage_foreign` FOREIGN KEY (`cancellation_stage`) REFERENCES `order_status` (`order_status_id`),
  ADD CONSTRAINT `order_cancellation_dtls_cancellation_type_id_foreign` FOREIGN KEY (`cancellation_type_id`) REFERENCES `order_cancellation_types` (`order_cancellation_type_id`),
  ADD CONSTRAINT `order_cancellation_dtls_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_cancellation_dtls_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_client_approval_dtl`
--
ALTER TABLE `order_client_approval_dtl`
  ADD CONSTRAINT `order_client_approval_dtl_ocad_order_id_foreign` FOREIGN KEY (`ocad_order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_defined_test_std_dtl`
--
ALTER TABLE `order_defined_test_std_dtl`
  ADD CONSTRAINT `order_defined_test_std_dtl_odtsd_branch_id_foreign` FOREIGN KEY (`odtsd_branch_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `order_defined_test_std_dtl_odtsd_created_by_foreign` FOREIGN KEY (`odtsd_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_defined_test_std_dtl_odtsd_product_category_id_foreign` FOREIGN KEY (`odtsd_product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `order_defined_test_std_dtl_odtsd_test_standard_id_foreign` FOREIGN KEY (`odtsd_test_standard_id`) REFERENCES `test_standard` (`test_std_id`);

--
-- Constraints for table `order_discipline_group_dtl`
--
ALTER TABLE `order_discipline_group_dtl`
  ADD CONSTRAINT `odg_test_parameter_category_id` FOREIGN KEY (`test_parameter_category_id`) REFERENCES `test_parameter_categories` (`test_para_cat_id`),
  ADD CONSTRAINT `order_discipline_group_dtl_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_discipline_group_dtl_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `order_report_disciplines` (`or_discipline_id`),
  ADD CONSTRAINT `order_discipline_group_dtl_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `order_report_groups` (`org_group_id`),
  ADD CONSTRAINT `order_discipline_group_dtl_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_dispatch_dtl`
--
ALTER TABLE `order_dispatch_dtl`
  ADD CONSTRAINT `order_dispatch_dtl_dispatch_by_foreign` FOREIGN KEY (`dispatch_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_dispatch_dtl_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_dynamic_field_dtl`
--
ALTER TABLE `order_dynamic_field_dtl`
  ADD CONSTRAINT `order_dynamic_field_dtl_odf_created_by_foreign` FOREIGN KEY (`odf_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_dynamic_field_dtl_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_expected_due_date_logs`
--
ALTER TABLE `order_expected_due_date_logs`
  ADD CONSTRAINT `order_expected_due_date_logs_oeddl_created_by_foreign` FOREIGN KEY (`oeddl_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_expected_due_date_logs_oeddl_order_id_foreign` FOREIGN KEY (`oeddl_order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_incharge_dtl`
--
ALTER TABLE `order_incharge_dtl`
  ADD CONSTRAINT `order_incharge_dtl_oid_confirm_by_foreign` FOREIGN KEY (`oid_confirm_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_incharge_dtl_oid_employee_id_foreign` FOREIGN KEY (`oid_employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_incharge_dtl_oid_equipment_type_id_foreign` FOREIGN KEY (`oid_equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `order_incharge_dtl_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_incharge_process_dtl`
--
ALTER TABLE `order_incharge_process_dtl`
  ADD CONSTRAINT `order_incharge_process_dtl_oipd_analysis_id_foreign` FOREIGN KEY (`oipd_analysis_id`) REFERENCES `order_parameters_detail` (`analysis_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_incharge_process_dtl_oipd_incharge_id_foreign` FOREIGN KEY (`oipd_incharge_id`) REFERENCES `order_incharge_dtl` (`oid_id`),
  ADD CONSTRAINT `order_incharge_process_dtl_oipd_order_id_foreign` FOREIGN KEY (`oipd_order_id`) REFERENCES `order_master` (`order_id`),
  ADD CONSTRAINT `order_incharge_process_dtl_oipd_user_id_foreign` FOREIGN KEY (`oipd_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_linked_po_dtl`
--
ALTER TABLE `order_linked_po_dtl`
  ADD CONSTRAINT `order_linked_po_dtl_olpd_cpo_id_foreign` FOREIGN KEY (`olpd_cpo_id`) REFERENCES `central_po_dtls` (`cpo_id`),
  ADD CONSTRAINT `order_linked_po_dtl_olpd_order_id_foreign` FOREIGN KEY (`olpd_order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_linked_stp_dtl`
--
ALTER TABLE `order_linked_stp_dtl`
  ADD CONSTRAINT `order_linked_stp_dtl_olsd_cstp_id_foreign` FOREIGN KEY (`olsd_cstp_id`) REFERENCES `central_stp_dtls` (`cstp_id`),
  ADD CONSTRAINT `order_linked_stp_dtl_olsd_order_id_foreign` FOREIGN KEY (`olsd_order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_linked_trf_dtl`
--
ALTER TABLE `order_linked_trf_dtl`
  ADD CONSTRAINT `order_linked_trf_dtl_oltd_order_id_foreign` FOREIGN KEY (`oltd_order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_mail_dtl`
--
ALTER TABLE `order_mail_dtl`
  ADD CONSTRAINT `order_mail_dtl_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `order_mail_dtl_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_hdr` (`invoice_id`),
  ADD CONSTRAINT `order_mail_dtl_mail_by_foreign` FOREIGN KEY (`mail_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_mail_dtl_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`),
  ADD CONSTRAINT `order_mail_dtl_stb_order_hdr_id_foreign` FOREIGN KEY (`stb_order_hdr_id`) REFERENCES `stb_order_hdr` (`stb_order_hdr_id`);

--
-- Constraints for table `order_master`
--
ALTER TABLE `order_master`
  ADD CONSTRAINT `order_master_billing_type_id_foreign` FOREIGN KEY (`billing_type_id`) REFERENCES `customer_billing_types` (`billing_type_id`),
  ADD CONSTRAINT `order_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_master_customer_city_foreign` FOREIGN KEY (`customer_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `order_master_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `order_master_defined_test_standard_foreign` FOREIGN KEY (`defined_test_standard`) REFERENCES `test_standard` (`test_std_id`),
  ADD CONSTRAINT `order_master_discount_type_id_foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `customer_discount_types` (`discount_type_id`),
  ADD CONSTRAINT `order_master_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `order_master_invoicing_to_foreign` FOREIGN KEY (`invoicing_to`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `order_master_invoicing_type_id_foreign` FOREIGN KEY (`invoicing_type_id`) REFERENCES `customer_invoicing_types` (`invoicing_type_id`),
  ADD CONSTRAINT `order_master_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `order_master_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product_master` (`product_id`),
  ADD CONSTRAINT `order_master_product_test_id_foreign` FOREIGN KEY (`product_test_id`) REFERENCES `product_test_hdr` (`test_id`),
  ADD CONSTRAINT `order_master_reporting_to_foreign` FOREIGN KEY (`reporting_to`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `order_master_sale_executive_foreign` FOREIGN KEY (`sale_executive`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_master_sample_description_id_foreign` FOREIGN KEY (`sample_description_id`) REFERENCES `product_master_alias` (`c_product_id`),
  ADD CONSTRAINT `order_master_sample_id_foreign` FOREIGN KEY (`sample_id`) REFERENCES `samples` (`sample_id`),
  ADD CONSTRAINT `order_master_sample_priority_id_foreign` FOREIGN KEY (`sample_priority_id`) REFERENCES `order_sample_priority` (`sample_priority_id`),
  ADD CONSTRAINT `order_master_sampler_id_foreign` FOREIGN KEY (`sampler_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_master_status_foreign` FOREIGN KEY (`status`) REFERENCES `order_status` (`order_status_id`),
  ADD CONSTRAINT `order_master_stb_order_hdr_detail_id_foreign` FOREIGN KEY (`stb_order_hdr_detail_id`) REFERENCES `stb_order_hdr_dtl_detail` (`stb_order_hdr_detail_id`),
  ADD CONSTRAINT `order_master_test_standard_foreign` FOREIGN KEY (`test_standard`) REFERENCES `test_standard` (`test_std_id`);

--
-- Constraints for table `order_parameters_detail`
--
ALTER TABLE `order_parameters_detail`
  ADD CONSTRAINT `fk_column_id` FOREIGN KEY (`column_id`) REFERENCES `column_master` (`column_id`),
  ADD CONSTRAINT `fk_instance_id` FOREIGN KEY (`instance_id`) REFERENCES `instance_master` (`instance_id`),
  ADD CONSTRAINT `fk_oaws_ui_setting_id` FOREIGN KEY (`oaws_ui_setting_id`) REFERENCES `order_analyst_window_settings` (`oaws_id`),
  ADD CONSTRAINT `fk_running_time_id` FOREIGN KEY (`running_time_id`) REFERENCES `customer_invoicing_running_time` (`invoicing_running_time_id`),
  ADD CONSTRAINT `order_parameters_detail_detector_id_foreign` FOREIGN KEY (`detector_id`) REFERENCES `detector_master` (`detector_id`),
  ADD CONSTRAINT `order_parameters_detail_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `order_parameters_detail_method_id_foreign` FOREIGN KEY (`method_id`) REFERENCES `method_master` (`method_id`),
  ADD CONSTRAINT `order_parameters_detail_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_parameters_detail_product_test_parameter_foreign` FOREIGN KEY (`product_test_parameter`) REFERENCES `product_test_dtl` (`product_test_dtl_id`),
  ADD CONSTRAINT `order_parameters_detail_test_parameter_id_foreign` FOREIGN KEY (`test_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`),
  ADD CONSTRAINT `order_parameters_detail_test_performed_by_foreign` FOREIGN KEY (`test_performed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_process_log`
--
ALTER TABLE `order_process_log`
  ADD CONSTRAINT `order_process_log_opl_opl_amended_by_foreign` FOREIGN KEY (`opl_amended_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_process_log_opl_order_id_foreign` FOREIGN KEY (`opl_order_id`) REFERENCES `order_master` (`order_id`),
  ADD CONSTRAINT `order_process_log_opl_order_status_id_foreign` FOREIGN KEY (`opl_order_status_id`) REFERENCES `order_status` (`order_status_id`),
  ADD CONSTRAINT `order_process_log_opl_user_id_foreign` FOREIGN KEY (`opl_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_purchase_order_logs`
--
ALTER TABLE `order_purchase_order_logs`
  ADD CONSTRAINT `order_purchase_order_logs_opol_created_by_foreign` FOREIGN KEY (`opol_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_purchase_order_logs_opol_order_id_foreign` FOREIGN KEY (`opol_order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_report_details`
--
ALTER TABLE `order_report_details`
  ADD CONSTRAINT `order_report_details_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_report_details_finalized_by_foreign` FOREIGN KEY (`finalized_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_report_details_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `order_master` (`order_id`),
  ADD CONSTRAINT `order_report_details_result_drived_value_foreign` FOREIGN KEY (`result_drived_value`) REFERENCES `order_report_options` (`report_option_id`),
  ADD CONSTRAINT `order_report_details_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_report_disciplines`
--
ALTER TABLE `order_report_disciplines`
  ADD CONSTRAINT `order_report_disciplines_or_discipline_created_by_foreign` FOREIGN KEY (`or_discipline_created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_report_discipline_parameter_dtls`
--
ALTER TABLE `order_report_discipline_parameter_dtls`
  ADD CONSTRAINT `order_report_discipline_parameter_dtls_ordp_division_id_foreign` FOREIGN KEY (`ordp_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `ordpd_discipline_id` FOREIGN KEY (`ordp_discipline_id`) REFERENCES `order_report_disciplines` (`or_discipline_id`),
  ADD CONSTRAINT `ordpd_ordp_created_by` FOREIGN KEY (`ordp_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ordpd_product_category_id` FOREIGN KEY (`ordp_product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `ordpd_test_parameter_category_id` FOREIGN KEY (`ordp_test_parameter_category_id`) REFERENCES `test_parameter_categories` (`test_para_cat_id`);

--
-- Constraints for table `order_report_groups`
--
ALTER TABLE `order_report_groups`
  ADD CONSTRAINT `order_report_groups_org_division_id_foreign` FOREIGN KEY (`org_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `order_report_groups_org_group_created_by_foreign` FOREIGN KEY (`org_group_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_report_groups_org_product_category_id_foreign` FOREIGN KEY (`org_product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `order_report_microbiological_dtl`
--
ALTER TABLE `order_report_microbiological_dtl`
  ADD CONSTRAINT `order_report_microbiological_dtl_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `order_master` (`order_id`),
  ADD CONSTRAINT `order_report_microbiological_dtl_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_report_middle_authorized_signs`
--
ALTER TABLE `order_report_middle_authorized_signs`
  ADD CONSTRAINT `order_report_middle_authorized_signs_ormad_employee_id_foreign` FOREIGN KEY (`ormad_employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_report_middle_authorized_signs_ormad_order_id_foreign` FOREIGN KEY (`ormad_order_id`) REFERENCES `order_master` (`order_id`);

--
-- Constraints for table `order_report_note_remark_default`
--
ALTER TABLE `order_report_note_remark_default`
  ADD CONSTRAINT `order_report_note_remark_default_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_report_note_remark_default_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `order_report_note_remark_default_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `order_report_settings`
--
ALTER TABLE `order_report_settings`
  ADD CONSTRAINT `order_report_settings_ors_created_by_foreign` FOREIGN KEY (`ors_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_report_settings_ors_division_id_foreign` FOREIGN KEY (`ors_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `order_report_settings_ors_product_category_id_foreign` FOREIGN KEY (`ors_product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `order_report_sign_dtls`
--
ALTER TABLE `order_report_sign_dtls`
  ADD CONSTRAINT `order_report_sign_dtls_orsd_created_by_foreign` FOREIGN KEY (`orsd_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_report_sign_dtls_orsd_division_id_foreign` FOREIGN KEY (`orsd_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `order_report_sign_dtls_orsd_employee_id_foreign` FOREIGN KEY (`orsd_employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_report_sign_dtls_orsd_equipment_type_id_foreign` FOREIGN KEY (`orsd_equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `order_report_sign_dtls_orsd_product_category_id_foreign` FOREIGN KEY (`orsd_product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `order_status`
--
ALTER TABLE `order_status`
  ADD CONSTRAINT `order_status_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `payment_made_hdr`
--
ALTER TABLE `payment_made_hdr`
  ADD CONSTRAINT `payment_made_hdr_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payment_made_hdr_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `payment_made_hdr_payment_source_id_foreign` FOREIGN KEY (`payment_source_id`) REFERENCES `payment_sources` (`payment_source_id`),
  ADD CONSTRAINT `payment_made_hdr_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`);

--
-- Constraints for table `payment_received_hdr`
--
ALTER TABLE `payment_received_hdr`
  ADD CONSTRAINT `payment_received_hdr_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payment_received_hdr_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `payment_received_hdr_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `payment_received_hdr_payment_source_id_foreign` FOREIGN KEY (`payment_source_id`) REFERENCES `payment_sources` (`payment_source_id`);

--
-- Constraints for table `payment_sources`
--
ALTER TABLE `payment_sources`
  ADD CONSTRAINT `payment_sources_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `po_hdr`
--
ALTER TABLE `po_hdr`
  ADD CONSTRAINT `po_hdr_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `po_hdr_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `po_hdr_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`);

--
-- Constraints for table `po_hdr_detail`
--
ALTER TABLE `po_hdr_detail`
  ADD CONSTRAINT `po_hdr_detail_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`),
  ADD CONSTRAINT `po_hdr_detail_po_hdr_id_foreign` FOREIGN KEY (`po_hdr_id`) REFERENCES `po_hdr` (`po_hdr_id`) ON DELETE CASCADE;

--
-- Constraints for table `po_indent_detail`
--
ALTER TABLE `po_indent_detail`
  ADD CONSTRAINT `po_indent_detail_indent_dtl_id_foreign` FOREIGN KEY (`indent_dtl_id`) REFERENCES `indent_hdr_detail` (`indent_dtl_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `po_indent_detail_po_hdr_id_foreign` FOREIGN KEY (`po_hdr_id`) REFERENCES `po_hdr` (`po_hdr_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_master`
--
ALTER TABLE `product_master`
  ADD CONSTRAINT `product_master_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_master_p_category_id_foreign` FOREIGN KEY (`p_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `product_master_alias`
--
ALTER TABLE `product_master_alias`
  ADD CONSTRAINT `product_master_alias_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_master_alias_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product_master` (`product_id`);

--
-- Constraints for table `product_test_dtl`
--
ALTER TABLE `product_test_dtl`
  ADD CONSTRAINT `product_test_dtl_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_test_dtl_detector_id_foreign` FOREIGN KEY (`detector_id`) REFERENCES `detector_master` (`detector_id`),
  ADD CONSTRAINT `product_test_dtl_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `product_test_dtl_method_id_foreign` FOREIGN KEY (`method_id`) REFERENCES `method_master` (`method_id`),
  ADD CONSTRAINT `product_test_dtl_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `product_test_hdr` (`test_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_dtl_test_parameter_id_foreign` FOREIGN KEY (`test_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`);

--
-- Constraints for table `product_test_hdr`
--
ALTER TABLE `product_test_hdr`
  ADD CONSTRAINT `product_test_hdr_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_test_hdr_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product_master` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_hdr_test_standard_id_foreign` FOREIGN KEY (`test_standard_id`) REFERENCES `test_standard` (`test_std_id`);

--
-- Constraints for table `product_test_parameter_altern_method`
--
ALTER TABLE `product_test_parameter_altern_method`
  ADD CONSTRAINT `product_test_parameter_altern_method_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_test_parameter_altern_method_detector_id_foreign` FOREIGN KEY (`detector_id`) REFERENCES `detector_master` (`detector_id`),
  ADD CONSTRAINT `product_test_parameter_altern_method_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `product_test_parameter_altern_method_method_id_foreign` FOREIGN KEY (`method_id`) REFERENCES `method_master` (`method_id`),
  ADD CONSTRAINT `product_test_parameter_altern_method_product_test_dtl_id_foreign` FOREIGN KEY (`product_test_dtl_id`) REFERENCES `product_test_dtl` (`product_test_dtl_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_parameter_altern_method_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `product_test_hdr` (`test_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_test_parameter_altern_method_test_parameter_id_foreign` FOREIGN KEY (`test_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`) ON DELETE CASCADE;

--
-- Constraints for table `req_slip_dtl`
--
ALTER TABLE `req_slip_dtl`
  ADD CONSTRAINT `req_slip_dtl_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`),
  ADD CONSTRAINT `req_slip_dtl_req_slip_hdr_id_foreign` FOREIGN KEY (`req_slip_hdr_id`) REFERENCES `req_slip_hdr` (`req_slip_id`) ON DELETE CASCADE;

--
-- Constraints for table `req_slip_hdr`
--
ALTER TABLE `req_slip_hdr`
  ADD CONSTRAINT `req_slip_hdr_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `req_slip_hdr_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `req_slip_hdr_req_by_foreign` FOREIGN KEY (`req_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `req_slip_hdr_req_department_id_foreign` FOREIGN KEY (`req_department_id`) REFERENCES `departments` (`department_id`);

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
-- Constraints for table `samples`
--
ALTER TABLE `samples`
  ADD CONSTRAINT `samples_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `samples_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `samples_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `samples_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `samples_sample_mode_id_foreign` FOREIGN KEY (`sample_mode_id`) REFERENCES `sample_modes` (`sample_mode_id`),
  ADD CONSTRAINT `samples_trf_id_foreign` FOREIGN KEY (`trf_id`) REFERENCES `trf_hdrs` (`trf_id`);

--
-- Constraints for table `scheduled_mail_dtl`
--
ALTER TABLE `scheduled_mail_dtl`
  ADD CONSTRAINT `scheduled_mail_dtl_smd_customer_id_foreign` FOREIGN KEY (`smd_customer_id`) REFERENCES `customer_master` (`customer_id`);

--
-- Constraints for table `scheduled_mis_report_dtls`
--
ALTER TABLE `scheduled_mis_report_dtls`
  ADD CONSTRAINT `scheduled_mis_report_dtls_smrd_created_by_foreign` FOREIGN KEY (`smrd_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `scheduled_mis_report_dtls_smrd_division_id_foreign` FOREIGN KEY (`smrd_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `scheduled_mis_report_dtls_smrd_mis_report_id_foreign` FOREIGN KEY (`smrd_mis_report_id`) REFERENCES `mis_report_default_types` (`mis_report_id`),
  ADD CONSTRAINT `scheduled_mis_report_dtls_smrd_product_category_id_foreign` FOREIGN KEY (`smrd_product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `schedulings`
--
ALTER TABLE `schedulings`
  ADD CONSTRAINT `schedulings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `schedulings_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `schedulings_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `schedulings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_master` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedulings_order_parameter_id_foreign` FOREIGN KEY (`order_parameter_id`) REFERENCES `order_parameters_detail` (`analysis_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedulings_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `schedulings_scheduled_by_foreign` FOREIGN KEY (`scheduled_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_setting_group_id_foreign` FOREIGN KEY (`setting_group_id`) REFERENCES `setting_groups` (`setting_group_id`);

--
-- Constraints for table `state_db`
--
ALTER TABLE `state_db`
  ADD CONSTRAINT `state_db_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries_db` (`country_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `state_db_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stb_order_hdr`
--
ALTER TABLE `stb_order_hdr`
  ADD CONSTRAINT `stb_order_hdr_stb_billing_type_id_foreign` FOREIGN KEY (`stb_billing_type_id`) REFERENCES `customer_billing_types` (`billing_type_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_created_by_foreign` FOREIGN KEY (`stb_created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stb_order_hdr_stb_customer_city_foreign` FOREIGN KEY (`stb_customer_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_customer_id_foreign` FOREIGN KEY (`stb_customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_discount_type_id_foreign` FOREIGN KEY (`stb_discount_type_id`) REFERENCES `customer_discount_types` (`discount_type_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_division_id_foreign` FOREIGN KEY (`stb_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_invoicing_type_id_foreign` FOREIGN KEY (`stb_invoicing_type_id`) REFERENCES `customer_invoicing_types` (`invoicing_type_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_product_category_id_foreign` FOREIGN KEY (`stb_product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_sale_executive_foreign` FOREIGN KEY (`stb_sale_executive`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stb_order_hdr_stb_sample_description_id_foreign` FOREIGN KEY (`stb_sample_description_id`) REFERENCES `product_master_alias` (`c_product_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_sample_id_foreign` FOREIGN KEY (`stb_sample_id`) REFERENCES `samples` (`sample_id`),
  ADD CONSTRAINT `stb_order_hdr_stb_sample_priority_id_foreign` FOREIGN KEY (`stb_sample_priority_id`) REFERENCES `order_sample_priority` (`sample_priority_id`);

--
-- Constraints for table `stb_order_hdr_dtl`
--
ALTER TABLE `stb_order_hdr_dtl`
  ADD CONSTRAINT `stb_order_hdr_dtl_stb_order_hdr_id_foreign` FOREIGN KEY (`stb_order_hdr_id`) REFERENCES `stb_order_hdr` (`stb_order_hdr_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stb_order_hdr_dtl_stb_product_id_foreign` FOREIGN KEY (`stb_product_id`) REFERENCES `product_master` (`product_id`),
  ADD CONSTRAINT `stb_order_hdr_dtl_stb_product_test_id_foreign` FOREIGN KEY (`stb_product_test_id`) REFERENCES `product_test_hdr` (`test_id`),
  ADD CONSTRAINT `stb_order_hdr_dtl_stb_test_standard_id_foreign` FOREIGN KEY (`stb_test_standard_id`) REFERENCES `test_standard` (`test_std_id`);

--
-- Constraints for table `stb_order_hdr_dtl_detail`
--
ALTER TABLE `stb_order_hdr_dtl_detail`
  ADD CONSTRAINT `stb_order_hdr_dtl_detail_stb_order_hdr_dtl_id_foreign` FOREIGN KEY (`stb_order_hdr_dtl_id`) REFERENCES `stb_order_hdr_dtl` (`stb_order_hdr_dtl_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stb_order_hdr_dtl_detail_stb_order_hdr_id_foreign` FOREIGN KEY (`stb_order_hdr_id`) REFERENCES `stb_order_hdr` (`stb_order_hdr_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stb_order_hdr_dtl_detail_stb_product_test_dtl_id_foreign` FOREIGN KEY (`stb_product_test_dtl_id`) REFERENCES `product_test_dtl` (`product_test_dtl_id`),
  ADD CONSTRAINT `stb_order_hdr_dtl_detail_stb_stability_type_id_foreign` FOREIGN KEY (`stb_stability_type_id`) REFERENCES `stb_order_stability_types` (`stb_stability_type_id`);

--
-- Constraints for table `stb_order_noti_dtl`
--
ALTER TABLE `stb_order_noti_dtl`
  ADD CONSTRAINT `stb_order_noti_dtl_stb_order_hdr_dtl_id_foreign` FOREIGN KEY (`stb_order_hdr_dtl_id`) REFERENCES `stb_order_hdr_dtl` (`stb_order_hdr_dtl_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stb_order_noti_dtl_stb_order_hdr_id_foreign` FOREIGN KEY (`stb_order_hdr_id`) REFERENCES `stb_order_hdr` (`stb_order_hdr_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stb_order_noti_dtl_stb_order_noti_confirm_by_foreign` FOREIGN KEY (`stb_order_noti_confirm_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stb_order_sample_qty_logs`
--
ALTER TABLE `stb_order_sample_qty_logs`
  ADD CONSTRAINT `stb_order_sample_qty_logs_stb_order_hdr_id_foreign` FOREIGN KEY (`stb_order_hdr_id`) REFERENCES `stb_order_hdr` (`stb_order_hdr_id`) ON DELETE CASCADE;

--
-- Constraints for table `template_dtl`
--
ALTER TABLE `template_dtl`
  ADD CONSTRAINT `template_dtl_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `template_dtl_product_cayegory_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `template_dtl_template_type_id_foreign` FOREIGN KEY (`template_type_id`) REFERENCES `template_types` (`template_type_id`);

--
-- Constraints for table `test_parameter`
--
ALTER TABLE `test_parameter`
  ADD CONSTRAINT `test_parameter_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `test_parameter_test_parameter_category_id_foreign` FOREIGN KEY (`test_parameter_category_id`) REFERENCES `test_parameter_categories` (`test_para_cat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_parameter_tpip_foreign` FOREIGN KEY (`test_parameter_invoicing_parent_id`) REFERENCES `test_parameter_invoicing_parents` (`tpip_id`);

--
-- Constraints for table `test_parameter_categories`
--
ALTER TABLE `test_parameter_categories`
  ADD CONSTRAINT `test_parameter_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `test_parameter_categories_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `test_parameter_equipment_types`
--
ALTER TABLE `test_parameter_equipment_types`
  ADD CONSTRAINT `test_parameter_equipment_types_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `test_parameter_equipment_types_test_parameter_id_foreign` FOREIGN KEY (`test_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`) ON DELETE CASCADE;

--
-- Constraints for table `test_parameter_invoicing_parents`
--
ALTER TABLE `test_parameter_invoicing_parents`
  ADD CONSTRAINT `test_parameter_invoicing_parents_ test_parameter_id _foreign` FOREIGN KEY (`test_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`);

--
-- Constraints for table `test_standard`
--
ALTER TABLE `test_standard`
  ADD CONSTRAINT `test_standard_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `test_standard_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`p_category_id`);

--
-- Constraints for table `trf_hdrs`
--
ALTER TABLE `trf_hdrs`
  ADD CONSTRAINT `trf_hdrs_trf_customer_id_foreign` FOREIGN KEY (`trf_customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `trf_hdrs_trf_division_id_foreign` FOREIGN KEY (`trf_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `trf_hdrs_trf_p_category_id_foreign` FOREIGN KEY (`trf_p_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `trf_hdrs_trf_product_category_id_foreign` FOREIGN KEY (`trf_product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `trf_hdrs_trf_product_id_foreign` FOREIGN KEY (`trf_product_id`) REFERENCES `product_master` (`product_id`),
  ADD CONSTRAINT `trf_hdrs_trf_product_test_id_foreign` FOREIGN KEY (`trf_product_test_id`) REFERENCES `product_test_hdr` (`test_id`),
  ADD CONSTRAINT `trf_hdrs_trf_storage_condition_id_foreign` FOREIGN KEY (`trf_storage_condition_id`) REFERENCES `trf_storge_condition_dtls` (`trf_sc_id`),
  ADD CONSTRAINT `trf_hdrs_trf_sub_p_category_id_foreign` FOREIGN KEY (`trf_sub_p_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `trf_hdrs_trf_test_standard_id_foreign` FOREIGN KEY (`trf_test_standard_id`) REFERENCES `test_standard` (`test_std_id`);

--
-- Constraints for table `trf_hdr_dtls`
--
ALTER TABLE `trf_hdr_dtls`
  ADD CONSTRAINT `trf_hdr_dtls_trf_hdr_id_foreign` FOREIGN KEY (`trf_hdr_id`) REFERENCES `trf_hdrs` (`trf_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trf_hdr_dtls_trf_product_test_dtl_id_foreign` FOREIGN KEY (`trf_product_test_dtl_id`) REFERENCES `product_test_dtl` (`product_test_dtl_id`),
  ADD CONSTRAINT `trf_hdr_dtls_trf_test_parameter_id_foreign` FOREIGN KEY (`trf_test_parameter_id`) REFERENCES `test_parameter` (`test_parameter_id`);

--
-- Constraints for table `units_db`
--
ALTER TABLE `units_db`
  ADD CONSTRAINT `units_db_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `unit_conversion_db`
--
ALTER TABLE `unit_conversion_db`
  ADD CONSTRAINT `unit_conversion_db_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `unit_product_categories`
--
ALTER TABLE `unit_product_categories`
  ADD CONSTRAINT `unit_product_categories_p_company_id_foreign` FOREIGN KEY (`p_company_id`) REFERENCES `company_master` (`company_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`);

--
-- Constraints for table `users_department_detail`
--
ALTER TABLE `users_department_detail`
  ADD CONSTRAINT `users_department_detail_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `users_department_detail_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_equipment_detail`
--
ALTER TABLE `users_equipment_detail`
  ADD CONSTRAINT `users_equipment_detail_equipment_type_id_foreign` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`equipment_id`),
  ADD CONSTRAINT `users_equipment_detail_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_custom_permissions`
--
ALTER TABLE `user_custom_permissions`
  ADD CONSTRAINT `user_custom_permissions_ucp_user_id_foreign` FOREIGN KEY (`ucp_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_log_activities`
--
ALTER TABLE `user_log_activities`
  ADD CONSTRAINT `user_log_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_sales_target_details`
--
ALTER TABLE `user_sales_target_details`
  ADD CONSTRAINT `user_sales_target_details_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_sales_target_details_ust_customer_id_foreign` FOREIGN KEY (`ust_customer_id`) REFERENCES `customer_master` (`customer_id`),
  ADD CONSTRAINT `user_sales_target_details_ust_division_id_foreign` FOREIGN KEY (`ust_division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `user_sales_target_details_ust_fin_year_id_foreign` FOREIGN KEY (`ust_fin_year_id`) REFERENCES `invoice_financial_years` (`ify_id`),
  ADD CONSTRAINT `user_sales_target_details_ust_product_category_id_foreign` FOREIGN KEY (`ust_product_category_id`) REFERENCES `product_categories` (`p_category_id`),
  ADD CONSTRAINT `user_sales_target_details_ust_type_id_foreign` FOREIGN KEY (`ust_type_id`) REFERENCES `user_sales_target_types` (`usty_id`),
  ADD CONSTRAINT `user_sales_target_details_ust_user_id_foreign` FOREIGN KEY (`ust_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `vendors_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`),
  ADD CONSTRAINT `vendors_vendor_city_foreign` FOREIGN KEY (`vendor_city`) REFERENCES `city_db` (`city_id`),
  ADD CONSTRAINT `vendors_vendor_state_foreign` FOREIGN KEY (`vendor_state`) REFERENCES `state_db` (`state_id`);

--
-- Constraints for table `voc_mail_dtl`
--
ALTER TABLE `voc_mail_dtl`
  ADD CONSTRAINT `voc_mail_dtl_voc_customer_id_foreign` FOREIGN KEY (`voc_customer_id`) REFERENCES `customer_master` (`customer_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
