-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2026 at 08:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `integrated_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `username`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'admin', 'MEMBER_CREATED', 'Created new member: Randall Jay Unarce (ID: COOP-2026-0001)', '::1', '2026-07-21 01:55:04'),
(2, 1, 'admin', 'MEMBER_UPDATED', 'Updated member ID: 1', '::1', '2026-07-21 01:56:13'),
(3, 1, 'admin', 'MEMBER_UPDATED', 'Updated member ID: 1', '::1', '2026-07-21 01:57:45'),
(4, 1, 'admin', 'MEMBER_UPDATED', 'Updated member ID: 1', '::1', '2026-07-21 02:03:03'),
(5, 1, 'admin', 'LOAN_CREATED', 'Created loan application for member #1', '::1', '2026-07-21 02:13:32'),
(6, 1, 'admin', 'LOAN_APPROVAL', 'Approved Loan #1', '::1', '2026-07-21 02:14:37'),
(7, 1, 'admin', 'LOAN_PAYMENT', 'Applied payment of 1000 to Loan #1', '::1', '2026-07-21 02:24:07'),
(8, 1, 'admin', 'LOAN_PAYMENT', 'Applied payment of 1000 to Loan #1', '::1', '2026-07-21 02:25:30'),
(9, 1, 'admin', 'LOAN_PAYMENT', 'Applied payment of 1000 to Loan #1', '::1', '2026-07-21 02:25:38'),
(10, 1, 'admin', 'LOAN_PAYMENT', 'Applied payment of 3533.33 to Loan #1', '::1', '2026-07-21 02:26:02'),
(11, 1, 'admin', 'VOUCHER_APPROVAL', 'Approved journal voucher #1', '::1', '2026-07-21 04:55:32'),
(12, 1, 'admin', 'LOAN_CREATED', 'Created loan application for member #1', '::1', '2026-07-21 05:20:10'),
(13, 1, 'admin', 'LOAN_APPROVAL', 'Approved Loan #2', '::1', '2026-07-21 05:20:14'),
(14, 1, 'admin', 'LOAN_CREATED', 'Created loan application for member #1', '::1', '2026-07-21 05:50:43'),
(15, 1, 'admin', 'LOAN_APPROVAL', 'Approved Loan #3', '::1', '2026-07-21 05:50:47'),
(16, 1, 'admin', 'LOAN_CREATED', 'Created loan application for member #1', '::1', '2026-07-21 06:18:48'),
(17, 1, 'admin', 'LOAN_APPROVAL', 'Approved Loan #4', '::1', '2026-07-21 06:19:41'),
(18, 1, 'admin', 'LOAN_PAYMENT', 'Applied payment of 4166.67 to Loan #4', '::1', '2026-07-21 06:20:02'),
(19, 1, 'admin', 'LOAN_PAYMENT', 'Applied payment of 1000 to Loan #4', '::1', '2026-07-21 06:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `journal_vouchers`
--

CREATE TABLE `journal_vouchers` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(100) NOT NULL,
  `transaction_date` date NOT NULL,
  `particulars` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_vouchers`
--

INSERT INTO `journal_vouchers` (`id`, `reference_number`, `transaction_date`, `particulars`, `created_by`, `created_at`, `status`) VALUES
(1, '00001', '2026-07-21', 'Share Capital Deposit', 1, '2026-07-21 04:55:26', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `ledger_entries`
--

CREATE TABLE `ledger_entries` (
  `id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `account_code` varchar(20) NOT NULL DEFAULT 'CAP',
  `entry_type` enum('deposit','dividend','withdrawal','mrs_deduction') NOT NULL,
  `debit` decimal(15,4) DEFAULT 0.0000,
  `credit` decimal(15,4) DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ledger_entries`
--

INSERT INTO `ledger_entries` (`id`, `voucher_id`, `member_id`, `account_code`, `entry_type`, `debit`, `credit`) VALUES
(1, 1, 1, 'CAP', 'deposit', 0.0000, 21000.0000);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `loan_type` varchar(50) NOT NULL,
  `collateral` varchar(50) NOT NULL,
  `soa_status` varchar(30) DEFAULT 'Pending',
  `loan_status` varchar(30) DEFAULT 'Pending',
  `amortization_type` varchar(30) NOT NULL,
  `payment_frequency` varchar(30) DEFAULT 'Monthly',
  `principal` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `terms` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `manual_payment` decimal(15,2) DEFAULT 0.00,
  `tct_no` varchar(100) DEFAULT NULL,
  `tax_declaration_no` varchar(100) DEFAULT NULL,
  `real_property_status` varchar(50) DEFAULT NULL,
  `undertaking_doc` varchar(255) DEFAULT NULL,
  `deed_of_rights_doc` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `member_id`, `loan_type`, `collateral`, `soa_status`, `loan_status`, `amortization_type`, `payment_frequency`, `principal`, `interest_rate`, `terms`, `start_date`, `manual_payment`, `tct_no`, `tax_declaration_no`, `real_property_status`, `undertaking_doc`, `deed_of_rights_doc`, `created_at`) VALUES
(1, 1, 'Salary Loan', 'Post-Dated Check', 'Active', 'Approved', 'Diminishing balance', 'Monthly', 35000.00, 2.00, 6, '2026-07-21', 0.00, NULL, NULL, NULL, NULL, NULL, '2026-07-21 02:13:32'),
(2, 1, 'Salary Loan', 'Post-Dated Check', 'Active', 'Approved', 'Diminishing balance', 'Monthly', 35000.00, 2.00, 6, '2026-07-21', 0.00, NULL, NULL, NULL, NULL, NULL, '2026-07-21 05:20:10'),
(3, 1, 'Pension Loan', 'Post-Dated Check', 'Active', 'Approved', 'Straight-line', 'Monthly', 60000.00, 2.00, 12, '2026-07-21', 0.00, NULL, NULL, NULL, NULL, NULL, '2026-07-21 05:50:43'),
(4, 1, 'Salary Loan', 'Post-Dated Check', 'Active', 'Approved', 'Straight-line', 'Monthly', 50000.00, 2.00, 12, '2026-07-21', 0.00, NULL, NULL, NULL, NULL, NULL, '2026-07-21 06:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `loan_schedules`
--

CREATE TABLE `loan_schedules` (
  `id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `period` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `principal` decimal(15,2) NOT NULL,
  `interest` decimal(15,2) NOT NULL,
  `orig_penalty` decimal(15,2) DEFAULT 0.00,
  `rem_principal` decimal(15,2) NOT NULL,
  `rem_interest` decimal(15,2) NOT NULL,
  `rem_penalty` decimal(15,2) DEFAULT 0.00,
  `status` varchar(20) DEFAULT 'Pending',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_schedules`
--

INSERT INTO `loan_schedules` (`id`, `loan_id`, `period`, `due_date`, `principal`, `interest`, `orig_penalty`, `rem_principal`, `rem_interest`, `rem_penalty`, `status`, `remarks`) VALUES
(1, 1, 1, '2026-08-21', 5833.33, 700.00, 0.00, 0.00, 0.00, 0.00, 'paid', ''),
(2, 1, 2, '2026-09-21', 5833.33, 583.33, 0.00, 5833.33, 583.33, 0.00, 'pending', ''),
(3, 1, 3, '2026-10-21', 5833.33, 466.67, 0.00, 5833.33, 466.67, 0.00, 'pending', ''),
(4, 1, 4, '2026-11-21', 5833.33, 350.00, 0.00, 5833.33, 350.00, 0.00, 'pending', ''),
(5, 1, 5, '2026-12-21', 5833.33, 233.33, 0.00, 5833.33, 233.33, 0.00, 'pending', ''),
(6, 1, 6, '2027-01-21', 5833.33, 116.67, 0.00, 5833.33, 116.67, 0.00, 'pending', ''),
(7, 2, 1, '2026-08-21', 5833.33, 700.00, 0.00, 5833.33, 700.00, 0.00, 'pending', ''),
(8, 2, 2, '2026-09-21', 5833.33, 583.33, 0.00, 5833.33, 583.33, 0.00, 'pending', ''),
(9, 2, 3, '2026-10-21', 5833.33, 466.67, 0.00, 5833.33, 466.67, 0.00, 'pending', ''),
(10, 2, 4, '2026-11-21', 5833.33, 350.00, 0.00, 5833.33, 350.00, 0.00, 'pending', ''),
(11, 2, 5, '2026-12-21', 5833.33, 233.33, 0.00, 5833.33, 233.33, 0.00, 'pending', ''),
(12, 2, 6, '2027-01-21', 5833.33, 116.67, 0.00, 5833.33, 116.67, 0.00, 'pending', ''),
(13, 3, 1, '2026-08-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(14, 3, 2, '2026-09-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(15, 3, 3, '2026-10-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(16, 3, 4, '2026-11-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(17, 3, 5, '2026-12-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(18, 3, 6, '2027-01-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(19, 3, 7, '2027-02-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(20, 3, 8, '2027-03-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(21, 3, 9, '2027-04-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(22, 3, 10, '2027-05-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(23, 3, 11, '2027-06-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(24, 3, 12, '2027-07-21', 5000.00, 1200.00, 0.00, 5000.00, 1200.00, 0.00, 'pending', ''),
(25, 4, 1, '2026-08-21', 4166.67, 1000.00, 0.00, 0.00, 0.00, 0.00, 'paid', ''),
(26, 4, 2, '2026-09-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(27, 4, 3, '2026-10-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(28, 4, 4, '2026-11-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(29, 4, 5, '2026-12-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(30, 4, 6, '2027-01-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(31, 4, 7, '2027-02-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(32, 4, 8, '2027-03-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(33, 4, 9, '2027-04-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(34, 4, 10, '2027-05-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(35, 4, 11, '2027-06-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', ''),
(36, 4, 12, '2027-07-21', 4166.67, 1000.00, 0.00, 4166.67, 1000.00, 0.00, 'pending', '');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `member_number` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `prefix` varchar(20) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `membership_type` varchar(50) DEFAULT 'Regular',
  `subscription` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','deceased') DEFAULT 'active',
  `date_of_membership` date NOT NULL,
  `date_of_birth` date NOT NULL,
  `date_of_death` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `member_number`, `first_name`, `middle_name`, `last_name`, `prefix`, `suffix`, `nickname`, `membership_type`, `subscription`, `status`, `date_of_membership`, `date_of_birth`, `date_of_death`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'COOP-2026-0001', 'Randall Jay', 'Veloria', 'Unarce', NULL, 'JR.', NULL, 'Associate', NULL, 'active', '2026-07-21', '2003-07-21', NULL, NULL, '2026-07-21 01:55:04', '2026-07-21 02:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `member_addresses`
--

CREATE TABLE `member_addresses` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `address_type` varchar(50) DEFAULT 'Permanent',
  `house_number` varchar(50) DEFAULT NULL,
  `street` varchar(150) DEFAULT NULL,
  `barangay` varchar(150) NOT NULL,
  `zone` varchar(50) DEFAULT NULL,
  `district` varchar(150) DEFAULT NULL,
  `town_city` varchar(150) NOT NULL,
  `province` varchar(150) NOT NULL,
  `region` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_addresses`
--

INSERT INTO `member_addresses` (`id`, `member_id`, `address_type`, `house_number`, `street`, `barangay`, `zone`, `district`, `town_city`, `province`, `region`) VALUES
(1, 1, 'Home', '', '', '', NULL, NULL, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `member_beneficiaries`
--

CREATE TABLE `member_beneficiaries` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `relationship` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `allocation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_beneficiaries`
--

INSERT INTO `member_beneficiaries` (`id`, `member_id`, `full_name`, `relationship`, `birth_date`, `contact_number`, `allocation`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 'Myrna Veloria Unarce', 'Mother', '2026-07-21', '12345678999', 50.00, 'Active', '2026-07-21 02:03:03', '2026-07-21 02:03:03'),
(7, 1, 'Rahannah Jane Veloria Unarce', 'Sister', '2005-07-24', '12345678999', 50.00, 'Active', '2026-07-21 02:03:03', '2026-07-21 02:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `member_contact`
--

CREATE TABLE `member_contact` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `phone_no_1` varchar(50) NOT NULL,
  `phone_no_2` varchar(50) DEFAULT NULL,
  `telephone_no_1` varchar(50) DEFAULT NULL,
  `telephone_no_2` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_contact`
--

INSERT INTO `member_contact` (`id`, `member_id`, `phone_no_1`, `phone_no_2`, `telephone_no_1`, `telephone_no_2`, `email`) VALUES
(1, 1, '0995 508 1740', '', NULL, NULL, 'u.randalljay00@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `member_education`
--

CREATE TABLE `member_education` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `education_level` enum('Elementary','High School','Vocational','College','Master''s Degree','Doctorate') NOT NULL,
  `course` varchar(255) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `year_graduated` year(4) DEFAULT NULL,
  `honors` varchar(255) DEFAULT NULL,
  `education_remarks` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_education`
--

INSERT INTO `member_education` (`id`, `member_id`, `education_level`, `course`, `school`, `year_graduated`, `honors`, `education_remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'College', '', '', '2025', '', '', 'Active', '2026-07-21 01:55:04', '2026-07-21 01:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `member_employment`
--

CREATE TABLE `member_employment` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `employment_status` enum('Employed','Self-employed','Unemployed','Student','Retired') NOT NULL,
  `occupation` varchar(150) DEFAULT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `employer_address` text DEFAULT NULL,
  `monthly_income` decimal(12,2) DEFAULT NULL,
  `employment_remarks` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_employment`
--

INSERT INTO `member_employment` (`id`, `member_id`, `employment_status`, `occupation`, `employer_name`, `employer_address`, `monthly_income`, `employment_remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Employed', 'Acting HR', '', '', 15000.00, '', 'Active', '2026-07-21 01:55:04', '2026-07-21 01:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `member_profiles`
--

CREATE TABLE `member_profiles` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `title_rank` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `tin_no` varchar(50) DEFAULT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced','Separated') DEFAULT 'Single',
  `sex` enum('Male','Female') DEFAULT NULL,
  `height` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `complexion` varchar(50) DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_profiles`
--

INSERT INTO `member_profiles` (`id`, `member_id`, `title_rank`, `position`, `tin_no`, `marital_status`, `sex`, `height`, `weight`, `complexion`, `birthplace`) VALUES
(1, 1, '', '', '', 'Single', 'Male', '', '', '', 'Quezon City');

-- --------------------------------------------------------

--
-- Table structure for table `payment_ledger`
--

CREATE TABLE `payment_ledger` (
  `id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL,
  `penalty_applied` decimal(15,2) NOT NULL DEFAULT 0.00,
  `interest_applied` decimal(15,2) NOT NULL DEFAULT 0.00,
  `principal_applied` decimal(15,2) NOT NULL DEFAULT 0.00,
  `excess` decimal(15,2) DEFAULT 0.00,
  `type` varchar(20) DEFAULT 'Global',
  `remarks` text DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_ledger`
--

INSERT INTO `payment_ledger` (`id`, `loan_id`, `amount_paid`, `penalty_applied`, `interest_applied`, `principal_applied`, `excess`, `type`, `remarks`, `datetime`) VALUES
(1, 1, 1000.00, 0.00, 700.00, 300.00, 0.00, 'Global', 'Global Waterfall Repayment', '2026-07-21 02:24:07'),
(2, 1, 1000.00, 0.00, 0.00, 1000.00, 0.00, 'Global', 'Global Waterfall Repayment', '2026-07-21 02:25:30'),
(3, 1, 1000.00, 0.00, 0.00, 1000.00, 0.00, 'Global', 'Global Waterfall Repayment', '2026-07-21 02:25:38'),
(4, 1, 3533.33, 0.00, 0.00, 3533.33, 0.00, 'Global', 'Global Waterfall Repayment', '2026-07-21 02:26:02'),
(5, 4, 4166.67, 0.00, 1000.00, 3166.67, 0.00, 'Global', 'Global Waterfall Repayment', '2026-07-21 06:20:02'),
(6, 4, 1000.00, 0.00, 0.00, 1000.00, 0.00, 'Global', 'Global Waterfall Repayment', '2026-07-21 06:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `description`, `created_at`) VALUES
(1, 'Admin', 'Full system access, managing configurations and users.', '2026-06-15 03:49:30'),
(2, 'Staff', 'Internal employees managing loans, ledger entries, and approvals.', '2026-06-15 03:49:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `username`, `email`, `password_hash`, `status`, `created_at`) VALUES
(1, 1, 'admin', 'justinbieber@gmail.com', '$2y$10$pNIz.r1TYdKWhDfshjqaveo4gZi96ZFdB7.Vp7SzYGFzRuDn0faiG', 'active', '2026-06-15 03:50:29'),
(2, 2, 'user', 'dummy@test.com', '$2y$10$ua8918vz/NZ5o9aVm/GA6OXQ5Eyb0V/HFGWkz8uZL74TBCx25zXT6', 'active', '2026-06-18 02:48:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_vouchers`
--
ALTER TABLE `journal_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_number` (`reference_number`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ledger_entries`
--
ALTER TABLE `ledger_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_id` (`voucher_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `loan_schedules`
--
ALTER TABLE `loan_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_number` (`member_number`);

--
-- Indexes for table `member_addresses`
--
ALTER TABLE `member_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `member_beneficiaries`
--
ALTER TABLE `member_beneficiaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_member_beneficiaries_member` (`member_id`);

--
-- Indexes for table `member_contact`
--
ALTER TABLE `member_contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_id` (`member_id`);

--
-- Indexes for table `member_education`
--
ALTER TABLE `member_education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_member_education_member` (`member_id`);

--
-- Indexes for table `member_employment`
--
ALTER TABLE `member_employment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_member_experience_member` (`member_id`);

--
-- Indexes for table `member_profiles`
--
ALTER TABLE `member_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_id` (`member_id`);

--
-- Indexes for table `payment_ledger`
--
ALTER TABLE `payment_ledger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `journal_vouchers`
--
ALTER TABLE `journal_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ledger_entries`
--
ALTER TABLE `ledger_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loan_schedules`
--
ALTER TABLE `loan_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member_addresses`
--
ALTER TABLE `member_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member_beneficiaries`
--
ALTER TABLE `member_beneficiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `member_contact`
--
ALTER TABLE `member_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member_education`
--
ALTER TABLE `member_education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member_employment`
--
ALTER TABLE `member_employment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member_profiles`
--
ALTER TABLE `member_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_ledger`
--
ALTER TABLE `payment_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `journal_vouchers`
--
ALTER TABLE `journal_vouchers`
  ADD CONSTRAINT `journal_vouchers_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `ledger_entries`
--
ALTER TABLE `ledger_entries`
  ADD CONSTRAINT `ledger_entries_ibfk_1` FOREIGN KEY (`voucher_id`) REFERENCES `journal_vouchers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ledger_entries_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_addresses`
--
ALTER TABLE `member_addresses`
  ADD CONSTRAINT `member_addresses_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_beneficiaries`
--
ALTER TABLE `member_beneficiaries`
  ADD CONSTRAINT `fk_member_beneficiaries_member` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_contact`
--
ALTER TABLE `member_contact`
  ADD CONSTRAINT `member_contact_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_education`
--
ALTER TABLE `member_education`
  ADD CONSTRAINT `fk_member_education_member` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_employment`
--
ALTER TABLE `member_employment`
  ADD CONSTRAINT `fk_member_experience_member` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_profiles`
--
ALTER TABLE `member_profiles`
  ADD CONSTRAINT `member_profiles_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
