-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2026 at 07:05 AM
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
(1, 1, 'admin', 'VOUCHER_APPROVAL', 'Approved journal voucher ID #1', '::1', '2026-06-30 07:08:26'),
(2, 1, 'admin', 'LOAN_CREATED', 'Loan application created for Member ID 1, Amount: 35000', '::1', '2026-06-30 08:49:30'),
(3, 1, 'admin', 'VOUCHER_APPROVAL', 'Approved journal voucher ID #2', '::1', '2026-07-01 00:31:11'),
(4, 1, 'admin', 'LOAN_CREATED', 'Loan application created for Member ID 1, Amount: 35000', '::1', '2026-07-01 08:15:34'),
(5, 1, 'admin', 'LOAN_REJECTION', 'Rejected loan application entry #2', '::1', '2026-07-01 08:15:54'),
(6, 1, 'admin', 'LOAN_APPROVAL', 'Approved and activated loan allocation record #1', '::1', '2026-07-01 08:15:56'),
(7, 1, 'admin', 'VOUCHER_APPROVAL', 'Approved journal voucher ID #3', '::1', '2026-07-02 00:41:02'),
(8, 1, 'admin', 'USER_ROLE_TOGGLE', 'Altered role of operator #2 (user) to ADMINISTRATOR', '::1', '2026-07-02 03:06:35'),
(9, 1, 'admin', 'USER_ROLE_TOGGLE', 'Altered role of operator #2 (user) to STAFF', '::1', '2026-07-02 03:06:41'),
(10, 1, 'admin', 'USER_ROLE_TOGGLE', 'Altered role of operator #2 (user) to ADMINISTRATOR', '::1', '2026-07-02 04:00:22'),
(11, 1, 'admin', 'USER_ROLE_TOGGLE', 'Altered role of operator #2 (user) to STAFF', '::1', '2026-07-02 04:00:23'),
(12, 1, 'admin', 'USER_ROLE_TOGGLE', 'Altered role of operator #2 (user) to ADMINISTRATOR', '::1', '2026-07-02 07:09:49'),
(13, 1, 'admin', 'USER_ROLE_TOGGLE', 'Altered role of operator #2 (user) to STAFF', '::1', '2026-07-02 07:09:52');

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
(1, '00001', '2026-06-30', 'Share Capital', 2, '2026-06-30 07:08:07', 'approved'),
(2, '00002', '2026-07-01', 'Share Capital', 1, '2026-07-01 00:26:14', 'approved'),
(3, '00003', '2026-07-02', 'Share Capital Withdrawal', 1, '2026-07-02 00:40:58', 'approved');

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
(1, 1, 1, 'CAP', 'deposit', 0.0000, 10000.0000),
(2, 2, 2, 'CAP', 'deposit', 0.0000, 10000.0000),
(3, 3, 21, 'CAP', 'withdrawal', 5000.0000, 0.0000);

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
(1, 1, 'Salary Loan', 'Post-Dated Check', 'Active', 'Approved', 'Diminishing balance', 'Monthly', 35000.00, 2.00, 6, '2026-06-30', 0.00, NULL, NULL, NULL, NULL, NULL, '2026-06-30 08:49:30'),
(2, 1, 'Personal Loan', 'Post-Dated Check', 'Pending', 'Rejected', 'Diminishing balance', 'Monthly', 35000.00, 2.00, 6, '2026-07-01', 0.00, NULL, NULL, NULL, NULL, NULL, '2026-07-01 08:15:34');

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
(1, 1, 1, '2026-01-01', 5833.33, 700.00, 0.00, 5833.33, 700.00, 355.75, 'overdue', ''),
(2, 1, 2, '2026-08-30', 5833.33, 583.33, 0.00, 5833.33, 583.33, 0.00, 'pending', ''),
(3, 1, 3, '2026-09-30', 5833.33, 466.67, 0.00, 5833.33, 466.67, 0.00, 'pending', ''),
(4, 1, 4, '2026-10-30', 5833.33, 350.00, 0.00, 5833.33, 350.00, 0.00, 'pending', ''),
(5, 1, 5, '2026-11-30', 5833.33, 233.33, 0.00, 5833.33, 233.33, 0.00, 'pending', ''),
(6, 1, 6, '2026-12-30', 5833.33, 116.67, 0.00, 5833.33, 116.67, 0.00, 'pending', '');

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
(1, '0001', 'David', 'Santos', 'Reyes', '', '', 'Dav', 'Associate', 'Inactive', 'inactive', '2023-06-30', '1979-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(2, '0002', 'Nicole', 'Torres', 'Ramos', '', '', 'Nic', 'Associate', 'Inactive', 'active', '2016-06-30', '1975-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(3, '0003', 'Maria', 'Cruz', 'Ramos', '', '', 'Mar', 'Regular', 'Active', 'inactive', '2024-06-30', '1983-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(4, '0004', 'Rose', 'Garcia', 'Villanueva', '', '', 'Ros', 'Associate', 'Inactive', 'inactive', '2021-06-30', '1979-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(5, '0005', 'Grace', 'Mendoza', 'Diaz', '', '', 'Gra', 'Associate', 'Active', 'inactive', '2016-06-30', '1972-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(6, '0006', 'Karen', 'Reyes', 'Villanueva', '', '', 'Kar', 'Regular', 'Active', 'active', '2022-06-30', '1989-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(7, '0007', 'Nathan', 'Lopez', 'Garcia', '', '', 'Nat', 'Regular', 'Inactive', 'inactive', '2024-06-30', '1998-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(8, '0008', 'Anthony', 'Bautista', 'Diaz', '', '', 'Ant', 'Regular', 'Active', 'inactive', '2025-06-30', '1968-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(9, '0009', 'Paul', 'Dela Cruz', 'Garcia', '', '', 'Pau', 'Associate', 'Active', 'active', '2019-06-30', '2002-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(10, '0010', 'Rose', 'Flores', 'Flores', '', '', 'Ros', 'Associate', 'Inactive', 'active', '2016-06-30', '1980-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(11, '0011', 'Jessica', 'Mendoza', 'Garcia', '', '', 'Jes', 'Associate', 'Inactive', 'inactive', '2022-06-30', '1984-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(12, '0012', 'Maria', 'Dela Cruz', 'Navarro', '', '', 'Mar', 'Regular', 'Inactive', 'active', '2024-06-30', '1990-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(13, '0013', 'Michelle', 'Bautista', 'Lopez', '', '', 'Mic', 'Associate', 'Inactive', 'active', '2019-06-30', '1970-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(14, '0014', 'Michelle', 'Mendoza', 'Aquino', '', '', 'Mic', 'Regular', 'Inactive', 'active', '2025-06-30', '1976-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(15, '0015', 'Christine', 'Santos', 'Lopez', '', '', 'Chr', 'Associate', 'Inactive', 'inactive', '2016-06-30', '1974-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(16, '0016', 'Maria', 'Mendoza', 'Ramos', '', '', 'Mar', 'Associate', 'Active', 'inactive', '2021-06-30', '1990-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(17, '0017', 'Grace', 'Dela Cruz', 'Aquino', '', '', 'Gra', 'Associate', 'Inactive', 'active', '2023-06-30', '1967-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(18, '0018', 'Angela', 'Flores', 'Diaz', '', '', 'Ang', 'Associate', 'Inactive', 'inactive', '2024-06-30', '1984-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(19, '0019', 'Karen', 'Torres', 'Lopez', '', '', 'Kar', 'Regular', 'Active', 'active', '2019-06-30', '1968-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(20, '0020', 'Patricia', 'Flores', 'Mendoza', '', '', 'Pat', 'Regular', 'Active', 'active', '2025-06-30', '1972-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(21, '0021', 'Brian', 'Reyes', 'Cruz', '', '', 'Bri', 'Associate', 'Active', 'inactive', '2018-06-30', '1974-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(22, '0022', 'Nathan', 'Dela Cruz', 'Castro', '', '', 'Nat', 'Associate', 'Inactive', 'active', '2017-06-30', '1973-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(23, '0023', 'Kevin', 'Garcia', 'Fernandez', '', '', 'Kev', 'Regular', 'Inactive', 'active', '2022-06-30', '2001-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(24, '0024', 'Daniel', 'Garcia', 'Santos', '', '', 'Dan', 'Associate', 'Inactive', 'active', '2019-06-30', '1967-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00'),
(25, '0025', 'Rose', 'Bautista', 'Aquino', '', '', 'Ros', 'Associate', 'Active', 'active', '2020-06-30', '1997-06-30', NULL, 'Seed Data', '2026-06-30 07:05:00', '2026-06-30 07:05:00');

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
(1, 1, 'Home', '571', 'Sample Street', 'Maligaya', '6', 'District 6', 'Caloocan City', 'Metro Manila', 'NCR'),
(2, 2, 'Home', '611', 'Sample Street', 'Balibago', '8', 'District 4', 'Parañaque City', 'Metro Manila', 'NCR'),
(3, 3, 'Home', '69', 'Sample Street', 'Balibago', '4', 'District 4', 'Pasig City', 'Metro Manila', 'NCR'),
(4, 4, 'Home', '952', 'Sample Street', 'Mabini', '10', 'District 5', 'Quezon City', 'Metro Manila', 'NCR'),
(5, 5, 'Home', '587', 'Sample Street', 'Balibago', '1', 'District 1', 'Quezon City', 'Metro Manila', 'NCR'),
(6, 6, 'Home', '212', 'Sample Street', 'Balibago', '1', 'District 6', 'Pasig City', 'Metro Manila', 'NCR'),
(7, 7, 'Home', '70', 'Sample Street', 'Bagumbayan', '9', 'District 4', 'Parañaque City', 'Metro Manila', 'NCR'),
(8, 8, 'Home', '525', 'Sample Street', 'Mabini', '8', 'District 1', 'Las Piñas City', 'Metro Manila', 'NCR'),
(9, 9, 'Home', '380', 'Sample Street', 'Poblacion', '4', 'District 1', 'Makati City', 'Metro Manila', 'NCR'),
(10, 10, 'Home', '418', 'Sample Street', 'Balibago', '9', 'District 2', 'Parañaque City', 'Metro Manila', 'NCR'),
(11, 11, 'Home', '239', 'Sample Street', 'San Jose', '8', 'District 1', 'Manila', 'Metro Manila', 'NCR'),
(12, 12, 'Home', '490', 'Sample Street', 'Poblacion', '2', 'District 4', 'Pasig City', 'Metro Manila', 'NCR'),
(13, 13, 'Home', '170', 'Sample Street', 'San Roque', '6', 'District 3', 'Manila', 'Metro Manila', 'NCR'),
(14, 14, 'Home', '960', 'Sample Street', 'Maligaya', '8', 'District 2', 'Taguig City', 'Metro Manila', 'NCR'),
(15, 15, 'Home', '298', 'Sample Street', 'Bagumbayan', '9', 'District 2', 'Taguig City', 'Metro Manila', 'NCR'),
(16, 16, 'Home', '719', 'Sample Street', 'Maligaya', '7', 'District 2', 'Taguig City', 'Metro Manila', 'NCR'),
(17, 17, 'Home', '613', 'Sample Street', 'Balibago', '9', 'District 5', 'Makati City', 'Metro Manila', 'NCR'),
(18, 18, 'Home', '552', 'Sample Street', 'Bagumbayan', '10', 'District 5', 'Las Piñas City', 'Metro Manila', 'NCR'),
(19, 19, 'Home', '257', 'Sample Street', 'Maligaya', '1', 'District 4', 'Las Piñas City', 'Metro Manila', 'NCR'),
(20, 20, 'Home', '580', 'Sample Street', 'Maligaya', '2', 'District 3', 'Makati City', 'Metro Manila', 'NCR'),
(21, 21, 'Home', '781', 'Sample Street', 'Maligaya', '10', 'District 1', 'Quezon City', 'Metro Manila', 'NCR'),
(22, 22, 'Home', '46', 'Sample Street', 'Sta. Cruz', '9', 'District 5', 'Las Piñas City', 'Metro Manila', 'NCR'),
(23, 23, 'Home', '785', 'Sample Street', 'Sta. Cruz', '5', 'District 2', 'Pasig City', 'Metro Manila', 'NCR'),
(24, 24, 'Home', '52', 'Sample Street', 'Bagumbayan', '8', 'District 2', 'Pasig City', 'Metro Manila', 'NCR'),
(25, 25, 'Home', '558', 'Sample Street', 'Bagumbayan', '1', 'District 2', 'Parañaque City', 'Metro Manila', 'NCR');

-- --------------------------------------------------------

--
-- Table structure for table `member_beneficiaries`
--

CREATE TABLE `member_beneficiaries` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `relation` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `prefix` varchar(20) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_beneficiaries`
--

INSERT INTO `member_beneficiaries` (`id`, `member_id`, `relation`, `first_name`, `middle_name`, `last_name`, `prefix`, `suffix`, `date_of_birth`, `place_of_birth`, `status`) VALUES
(1, 1, 'Mother', 'Daniel', 'Cruz', 'Flores', '', '', '2007-06-30', 'Caloocan City', 'Active'),
(2, 2, 'Brother', 'John', 'Mendoza', 'Santos', '', '', '1978-06-30', 'Parañaque City', 'Active'),
(3, 3, 'Father', 'Robert', 'Garcia', 'Ramos', '', '', '2003-06-30', 'Pasig City', 'Active'),
(4, 4, 'Spouse', 'Anthony', 'Garcia', 'Diaz', '', '', '1997-06-30', 'Quezon City', 'Active'),
(5, 5, 'Sister', 'Grace', 'Santos', 'Ramos', '', '', '1979-06-30', 'Quezon City', 'Active'),
(6, 6, 'Spouse', 'David', 'Reyes', 'Navarro', '', '', '1962-06-30', 'Pasig City', 'Active'),
(7, 7, 'Sister', 'Nicole', 'Garcia', 'Diaz', '', '', '1973-06-30', 'Parañaque City', 'Active'),
(8, 8, 'Sister', 'Maria', 'Mendoza', 'Villanueva', '', '', '1973-06-30', 'Las Piñas City', 'Active'),
(9, 9, 'Mother', 'Paul', 'Garcia', 'Garcia', '', '', '1971-06-30', 'Makati City', 'Active'),
(10, 10, 'Mother', 'Nicole', 'Garcia', 'Reyes', '', '', '1961-06-30', 'Parañaque City', 'Active'),
(11, 11, 'Brother', 'Jessica', 'Flores', 'Diaz', '', '', '2006-06-30', 'Manila', 'Active'),
(12, 12, 'Mother', 'Karen', 'Dela Cruz', 'Villanueva', '', '', '1974-06-30', 'Pasig City', 'Active'),
(13, 13, 'Spouse', 'Mark', 'Torres', 'Santos', '', '', '2009-06-30', 'Manila', 'Active'),
(14, 14, 'Father', 'Joshua', 'Santos', 'Lopez', '', '', '1966-06-30', 'Taguig City', 'Active'),
(15, 15, 'Brother', 'Angela', 'Garcia', 'Diaz', '', '', '1985-06-30', 'Taguig City', 'Active'),
(16, 16, 'Sister', 'Mark', 'Dela Cruz', 'Cruz', '', '', '1983-06-30', 'Taguig City', 'Active'),
(17, 17, 'Spouse', 'Ryan', 'Mendoza', 'Villanueva', '', '', '1970-06-30', 'Makati City', 'Active'),
(18, 18, 'Sister', 'Michelle', 'Flores', 'Fernandez', '', '', '2003-06-30', 'Las Piñas City', 'Active'),
(19, 19, 'Mother', 'Robert', 'Mendoza', 'Mendoza', '', '', '2006-06-30', 'Las Piñas City', 'Active'),
(20, 20, 'Father', 'David', 'Flores', 'Castro', '', '', '1967-06-30', 'Makati City', 'Active'),
(21, 21, 'Father', 'Angela', 'Dela Cruz', 'Fernandez', '', '', '1983-06-30', 'Quezon City', 'Active'),
(22, 22, 'Mother', 'Paul', 'Mendoza', 'Cruz', '', '', '1984-06-30', 'Las Piñas City', 'Active'),
(23, 23, 'Spouse', 'James', 'Santos', 'Garcia', '', '', '1994-06-30', 'Pasig City', 'Active'),
(24, 24, 'Mother', 'Angela', 'Santos', 'Castro', '', '', '1965-06-30', 'Pasig City', 'Active'),
(25, 25, 'Mother', 'Joseph', 'Reyes', 'Diaz', '', '', '2005-06-30', 'Parañaque City', 'Active');

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
(1, 1, '09103937425', '', '', '', 'david.Reyes1@gmail.com'),
(2, 2, '09485354521', '', '', '', 'nicole.Ramos2@gmail.com'),
(3, 3, '09794892493', '', '', '', 'maria.Ramos3@gmail.com'),
(4, 4, '09632468991', '', '', '', 'rose.Villanueva4@gmail.com'),
(5, 5, '09112930448', '', '', '', 'grace.Diaz5@gmail.com'),
(6, 6, '09515731087', '', '', '', 'karen.Villanueva6@gmail.com'),
(7, 7, '09631629411', '', '', '', 'nathan.Garcia7@gmail.com'),
(8, 8, '09527427600', '', '', '', 'anthony.Diaz8@gmail.com'),
(9, 9, '09339499520', '', '', '', 'paul.Garcia9@gmail.com'),
(10, 10, '09819052629', '', '', '', 'rose.Flores10@gmail.com'),
(11, 11, '09617087734', '', '', '', 'jessica.Garcia11@gmail.com'),
(12, 12, '09513590049', '', '', '', 'maria.Navarro12@gmail.com'),
(13, 13, '09133969148', '', '', '', 'michelle.Lopez13@gmail.com'),
(14, 14, '09395381150', '', '', '', 'michelle.Aquino14@gmail.com'),
(15, 15, '09771568052', '', '', '', 'christine.Lopez15@gmail.com'),
(16, 16, '09547451689', '', '', '', 'maria.Ramos16@gmail.com'),
(17, 17, '09827049382', '', '', '', 'grace.Aquino17@gmail.com'),
(18, 18, '09624944830', '', '', '', 'angela.Diaz18@gmail.com'),
(19, 19, '09252684944', '', '', '', 'karen.Lopez19@gmail.com'),
(20, 20, '09487453393', '', '', '', 'patricia.Mendoza20@gmail.com'),
(21, 21, '09961999768', '', '', '', 'brian.Cruz21@gmail.com'),
(22, 22, '09305029202', '', '', '', 'nathan.Castro22@gmail.com'),
(23, 23, '09746603524', '', '', '', 'kevin.Fernandez23@gmail.com'),
(24, 24, '09371843276', '', '', '', 'daniel.Santos24@gmail.com'),
(25, 25, '09676324529', '', '', '', 'rose.Aquino25@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `member_education`
--

CREATE TABLE `member_education` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `program` varchar(255) NOT NULL,
  `school_university` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date_started` date DEFAULT NULL,
  `date_ended` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_education`
--

INSERT INTO `member_education` (`id`, `member_id`, `program`, `school_university`, `location`, `date_started`, `date_ended`) VALUES
(1, 1, 'BS Civil Engineering', 'University of Santo Tomas', 'Caloocan City', '2007-06-01', '2021-04-30'),
(2, 2, 'BS Information Technology', 'Ateneo de Manila University', 'Parañaque City', '2008-06-01', '2021-04-30'),
(3, 3, 'BS Computer Science', 'De La Salle University', 'Pasig City', '2011-06-01', '2020-04-30'),
(4, 4, 'BS Nursing', 'De La Salle University', 'Quezon City', '2007-06-01', '2018-04-30'),
(5, 5, 'BS Information Systems', 'University of the Philippines', 'Quezon City', '2009-06-01', '2016-04-30'),
(6, 6, 'BS Accountancy', 'Polytechnic University of the Philippines', 'Pasig City', '2008-06-01', '2022-04-30'),
(7, 7, 'BS Civil Engineering', 'University of the Philippines', 'Parañaque City', '2009-06-01', '2020-04-30'),
(8, 8, 'BS Nursing', 'University of Santo Tomas', 'Las Piñas City', '2014-06-01', '2021-04-30'),
(9, 9, 'BS Information Technology', 'University of Santo Tomas', 'Makati City', '2006-06-01', '2016-04-30'),
(10, 10, 'BS Civil Engineering', 'University of Santo Tomas', 'Parañaque City', '2003-06-01', '2021-04-30'),
(11, 11, 'BS Information Technology', 'Polytechnic University of the Philippines', 'Manila', '2004-06-01', '2020-04-30'),
(12, 12, 'BS Information Technology', 'Ateneo de Manila University', 'Pasig City', '2003-06-01', '2023-04-30'),
(13, 13, 'BS Computer Science', 'De La Salle University', 'Manila', '2002-06-01', '2018-04-30'),
(14, 14, 'BS Computer Science', 'University of the Philippines', 'Taguig City', '2004-06-01', '2018-04-30'),
(15, 15, 'BS Civil Engineering', 'Far Eastern University', 'Taguig City', '2009-06-01', '2023-04-30'),
(16, 16, 'BS Information Systems', 'Polytechnic University of the Philippines', 'Taguig City', '2007-06-01', '2017-04-30'),
(17, 17, 'BS Accountancy', 'De La Salle University', 'Makati City', '2008-06-01', '2016-04-30'),
(18, 18, 'BS Accountancy', 'Ateneo de Manila University', 'Las Piñas City', '2008-06-01', '2023-04-30'),
(19, 19, 'BS Accountancy', 'University of Santo Tomas', 'Las Piñas City', '2008-06-01', '2018-04-30'),
(20, 20, 'BS Computer Science', 'Ateneo de Manila University', 'Makati City', '2002-06-01', '2019-04-30'),
(21, 21, 'BS Information Systems', 'Far Eastern University', 'Quezon City', '2007-06-01', '2018-04-30'),
(22, 22, 'BS Accountancy', 'Polytechnic University of the Philippines', 'Las Piñas City', '2001-06-01', '2020-04-30'),
(23, 23, 'BS Information Technology', 'University of the Philippines', 'Pasig City', '2001-06-01', '2019-04-30'),
(24, 24, 'BS Information Technology', 'De La Salle University', 'Pasig City', '2013-06-01', '2020-04-30'),
(25, 25, 'BS Computer Science', 'University of Santo Tomas', 'Parañaque City', '2005-06-01', '2023-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `member_experience`
--

CREATE TABLE `member_experience` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `job_title` varchar(150) NOT NULL,
  `organization` varchar(255) NOT NULL,
  `date_started` date DEFAULT NULL,
  `date_ended` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_experience`
--

INSERT INTO `member_experience` (`id`, `member_id`, `job_title`, `organization`, `date_started`, `date_ended`) VALUES
(1, 1, 'Accountant', 'Future Systems', '2015-01-01', '2026-06-30'),
(2, 2, 'Accountant', 'Future Systems', '2019-01-01', '2026-06-30'),
(3, 3, 'Network Engineer', 'Metro Solutions', '2020-01-01', '2026-06-30'),
(4, 4, 'Project Manager', 'GlobalTech', '2019-01-01', '2026-06-30'),
(5, 5, 'HR Officer', 'XYZ Technologies', '2016-01-01', '2026-06-30'),
(6, 6, 'Teacher', 'GlobalTech', '2016-01-01', '2026-06-30'),
(7, 7, 'Project Manager', 'Innovate Inc.', '2018-01-01', '2026-06-30'),
(8, 8, 'Web Developer', 'Metro Solutions', '2016-01-01', '2026-06-30'),
(9, 9, 'Business Analyst', 'Innovate Inc.', '2019-01-01', '2026-06-30'),
(10, 10, 'Teacher', 'Metro Solutions', '2016-01-01', '2026-06-30'),
(11, 11, 'Software Developer', 'XYZ Technologies', '2019-01-01', '2026-06-30'),
(12, 12, 'HR Officer', 'XYZ Technologies', '2017-01-01', '2026-06-30'),
(13, 13, 'HR Officer', 'XYZ Technologies', '2016-01-01', '2026-06-30'),
(14, 14, 'Project Manager', 'XYZ Technologies', '2020-01-01', '2026-06-30'),
(15, 15, 'Project Manager', 'Innovate Inc.', '2020-01-01', '2026-06-30'),
(16, 16, 'Business Analyst', 'Innovate Inc.', '2016-01-01', '2026-06-30'),
(17, 17, 'Accountant', 'XYZ Technologies', '2016-01-01', '2026-06-30'),
(18, 18, 'Teacher', 'GlobalTech', '2019-01-01', '2026-06-30'),
(19, 19, 'Project Manager', 'Metro Solutions', '2019-01-01', '2026-06-30'),
(20, 20, 'Business Analyst', 'Metro Solutions', '2018-01-01', '2026-06-30'),
(21, 21, 'Project Manager', 'Metro Solutions', '2015-01-01', '2026-06-30'),
(22, 22, 'Accountant', 'Metro Solutions', '2017-01-01', '2026-06-30'),
(23, 23, 'Software Developer', 'ABC Corporation', '2017-01-01', '2026-06-30'),
(24, 24, 'Teacher', 'XYZ Technologies', '2017-01-01', '2026-06-30'),
(25, 25, 'Business Analyst', 'Innovate Inc.', '2015-01-01', '2026-06-30');

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
(1, 1, '', 'Accountant', '216-141-696', 'Single', 'Male', '182', '68', 'Light', 'Caloocan City'),
(2, 2, '', 'Accountant', '777-120-224', 'Single', 'Female', '189', '79', 'Fair', 'Parañaque City'),
(3, 3, '', 'Network Engineer', '339-353-522', 'Single', 'Male', '178', '61', 'Morena', 'Pasig City'),
(4, 4, '', 'Project Manager', '773-791-633', 'Single', 'Female', '184', '74', 'Medium', 'Quezon City'),
(5, 5, '', 'HR Officer', '236-741-813', 'Married', 'Male', '164', '63', 'Fair', 'Quezon City'),
(6, 6, '', 'Teacher', '583-771-555', 'Married', 'Male', '151', '53', 'Fair', 'Pasig City'),
(7, 7, '', 'Project Manager', '347-927-904', 'Married', 'Male', '157', '62', 'Medium', 'Parañaque City'),
(8, 8, '', 'Web Developer', '499-436-563', 'Single', 'Male', '169', '80', 'Light', 'Las Piñas City'),
(9, 9, '', 'Business Analyst', '931-516-220', 'Single', 'Male', '187', '82', 'Light', 'Makati City'),
(10, 10, '', 'Teacher', '447-155-235', 'Married', 'Female', '190', '87', 'Medium', 'Parañaque City'),
(11, 11, '', 'Software Developer', '793-163-523', 'Married', 'Female', '177', '61', 'Morena', 'Manila'),
(12, 12, '', 'HR Officer', '802-656-732', 'Single', 'Female', '181', '79', 'Fair', 'Pasig City'),
(13, 13, '', 'HR Officer', '737-639-552', 'Married', 'Male', '169', '83', 'Fair', 'Manila'),
(14, 14, '', 'Project Manager', '113-486-312', 'Married', 'Female', '178', '59', 'Light', 'Taguig City'),
(15, 15, '', 'Project Manager', '551-193-615', 'Married', 'Female', '178', '50', 'Light', 'Taguig City'),
(16, 16, '', 'Business Analyst', '433-639-303', 'Married', 'Female', '164', '89', 'Light', 'Taguig City'),
(17, 17, '', 'Accountant', '672-192-262', 'Single', 'Female', '170', '87', 'Morena', 'Makati City'),
(18, 18, '', 'Teacher', '926-859-600', 'Single', 'Male', '158', '56', 'Fair', 'Las Piñas City'),
(19, 19, '', 'Project Manager', '265-425-756', 'Single', 'Male', '154', '54', 'Fair', 'Las Piñas City'),
(20, 20, '', 'Business Analyst', '403-259-828', 'Married', 'Female', '175', '67', 'Morena', 'Makati City'),
(21, 21, '', 'Project Manager', '449-974-583', 'Single', 'Male', '158', '90', 'Fair', 'Quezon City'),
(22, 22, '', 'Accountant', '657-829-106', 'Single', 'Male', '158', '66', 'Morena', 'Las Piñas City'),
(23, 23, '', 'Software Developer', '194-547-566', 'Single', 'Male', '171', '85', 'Light', 'Pasig City'),
(24, 24, '', 'Teacher', '254-135-923', 'Married', 'Male', '175', '65', 'Medium', 'Pasig City'),
(25, 25, '', 'Business Analyst', '575-666-990', 'Married', 'Female', '154', '54', 'Morena', 'Parañaque City');

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
  ADD KEY `member_id` (`member_id`);

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
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `member_experience`
--
ALTER TABLE `member_experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `journal_vouchers`
--
ALTER TABLE `journal_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ledger_entries`
--
ALTER TABLE `ledger_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `loan_schedules`
--
ALTER TABLE `loan_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `member_addresses`
--
ALTER TABLE `member_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `member_beneficiaries`
--
ALTER TABLE `member_beneficiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `member_contact`
--
ALTER TABLE `member_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `member_education`
--
ALTER TABLE `member_education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `member_experience`
--
ALTER TABLE `member_experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `member_profiles`
--
ALTER TABLE `member_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payment_ledger`
--
ALTER TABLE `payment_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `member_beneficiaries_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_contact`
--
ALTER TABLE `member_contact`
  ADD CONSTRAINT `member_contact_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_education`
--
ALTER TABLE `member_education`
  ADD CONSTRAINT `member_education_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_experience`
--
ALTER TABLE `member_experience`
  ADD CONSTRAINT `member_experience_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

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
