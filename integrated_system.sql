-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 10:40 AM
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
(1, '12345', '2026-06-18', 'Share Capital', 2, '2026-06-18 07:39:33', 'approved'),
(2, '12346', '2026-06-19', 'Share Capital Withdrawal', 1, '2026-06-18 23:54:05', 'approved'),
(3, 'JV-6A38E3461383A', '2026-06-22', 'Share Capital', 1, '2026-06-22 07:24:54', 'approved'),
(4, 'JV-6A38E36EA2829', '2026-06-22', 'Share Capital', 1, '2026-06-22 07:25:34', 'approved'),
(5, '12347', '2026-06-22', 'Share Capital', 1, '2026-06-22 08:01:13', 'approved'),
(6, '12348', '2026-06-23', 'Share Capital', 1, '2026-06-23 01:13:30', 'approved'),
(7, '12349', '2026-06-23', 'Share Capital', 1, '2026-06-23 02:35:48', 'approved');

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
(1, 1, 17, 'CAP', 'deposit', 0.0000, 25000.0000),
(2, 2, 17, 'CAP', 'withdrawal', 10000.0000, 0.0000),
(3, 3, 17, 'CAP', 'deposit', 0.0000, 100000.0000),
(4, 4, 29, 'CAP', 'deposit', 0.0000, 120000.0000),
(5, 5, 51, 'CAP', 'deposit', 0.0000, 600000.0000),
(6, 6, 26, 'CAP', 'deposit', 0.0000, 1000000.0000),
(7, 7, 25, 'CAP', 'deposit', 0.0000, 50000.0000);

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
(1, 17, 'Salary Loan', 'Real Property', 'Pending', 'Approved', 'Straight-line', 'Monthly', 25000.00, 2.00, 12, '2026-06-19', 0.00, '12345', '12345', 'Pending', 'uploads/1781837410_undertaking.pdf', 'uploads/1781837410_deed.pdf', '2026-06-19 02:50:10'),
(2, 25, 'Salary Loan', 'Post-Dated Check', 'Fully Paid', 'Approved', 'Straight-line', 'Monthly', 10000.00, 2.00, 3, '2026-06-19', 0.00, '', '', 'Updated', NULL, NULL, '2026-06-19 03:14:53'),
(3, 51, 'Salary Loan', 'Post-Dated Check', 'Pending', 'Approved', 'Straight-line', 'Monthly', 10000.00, 2.00, 3, '2026-06-23', 0.00, '', '', 'Updated', '', '', '2026-06-23 06:17:34'),
(4, 52, 'Salary Loan', 'Post-Dated Check', 'Pending', 'Approved', 'Straight-line', 'Monthly', 35000.00, 2.00, 6, '2025-06-25', 0.00, '', '', 'Updated', '', '', '2026-06-23 06:52:11');

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
(1, 1, 1, '2026-07-19', 2083.33, 500.00, 0.00, 0.00, 0.00, 0.00, 'paid', NULL),
(2, 1, 2, '2026-08-19', 2083.33, 500.00, 0.00, 0.00, 0.00, 0.00, 'paid', NULL),
(3, 1, 3, '2026-09-19', 2083.33, 500.00, 0.00, 0.00, 0.00, 0.00, 'paid', NULL),
(4, 1, 4, '2026-10-19', 2083.33, 500.00, 0.00, 0.00, 0.00, 0.00, 'paid', NULL),
(5, 1, 5, '2026-11-19', 2083.33, 500.00, 0.00, 0.00, 0.00, 0.00, 'paid', NULL),
(6, 1, 6, '2026-12-19', 2083.33, 500.00, 0.00, 0.00, 0.00, 0.00, 'paid', NULL),
(7, 1, 7, '2027-01-20', 2083.33, 500.00, 0.00, 583.31, 0.00, 123.45, 'pending', ''),
(8, 1, 8, '2027-02-19', 2083.33, 500.00, 0.00, 2083.33, 0.00, 0.00, 'pending', NULL),
(9, 1, 9, '2027-03-19', 2083.33, 500.00, 0.00, 2083.33, 0.00, 0.00, 'pending', NULL),
(10, 1, 10, '2027-04-19', 2083.33, 500.00, 0.00, 2083.33, 0.00, 0.00, 'pending', NULL),
(11, 1, 11, '2027-05-19', 2083.33, 500.00, 0.00, 2083.33, 0.00, 0.00, 'pending', NULL),
(12, 1, 12, '2027-06-19', 2083.33, 500.00, 0.00, 2083.33, 0.00, 0.00, 'pending', NULL),
(13, 2, 1, '2026-05-19', 3333.33, 200.00, 100.00, 0.00, 0.00, 0.00, 'paid', NULL),
(14, 2, 2, '2026-08-19', 3333.33, 200.00, 0.00, 0.00, 0.00, 0.00, 'paid', NULL),
(15, 2, 3, '2026-09-19', 3333.33, 200.00, 0.00, 0.00, 0.00, 0.00, 'paid', NULL),
(16, 3, 1, '2026-07-23', 3333.33, 200.00, 0.00, 3333.33, 200.00, 0.00, 'pending', ''),
(17, 3, 2, '2026-08-23', 3333.33, 200.00, 0.00, 3333.33, 200.00, 0.00, 'pending', ''),
(18, 3, 3, '2026-09-23', 3333.33, 200.00, 0.00, 3333.33, 200.00, 0.00, 'pending', ''),
(19, 4, 1, '2025-07-25', 5833.33, 700.00, 0.00, 5833.33, 700.00, 3208.33, 'overdue', ''),
(20, 4, 2, '2025-08-25', 5833.33, 700.00, 0.00, 5833.33, 700.00, 2916.67, 'overdue', ''),
(21, 4, 3, '2025-09-25', 5833.33, 700.00, 0.00, 5833.33, 700.00, 2625.00, 'overdue', ''),
(22, 4, 4, '2025-10-25', 5833.33, 700.00, 0.00, 5833.33, 700.00, 2333.33, 'overdue', ''),
(23, 4, 5, '2025-11-25', 5833.33, 700.00, 0.00, 5833.33, 700.00, 2041.67, 'overdue', ''),
(24, 4, 6, '2025-12-25', 5833.33, 700.00, 0.00, 5833.33, 700.00, 1750.00, 'overdue', '');

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
(1, 'COOP-2026-0001', 'Stephanie', 'Torres', 'Williams', 'Mrs.', 'IV', 'Stephanieie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1551-06-01', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(2, 'COOP-2026-0002', 'Stephanie', 'Ramos', 'Jones', 'Mr.', 'Jr.', 'Stephanieie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1660-05-27', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(3, 'COOP-2026-0003', 'Jessica', 'Reyes', 'Thomas', 'Mrs.', 'IV', 'Jessicaie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1307-12-16', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(4, 'COOP-2026-0004', 'Andrew', 'Bautista', 'Martinez', 'Dr.', 'III', 'Andrewie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1904-04-27', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(5, 'COOP-2026-0005', 'John', 'Mendoza', 'Davis', 'Dr.', 'Jr.', 'Johnie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1928-09-12', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(6, 'COOP-2026-0006', 'Jane', 'Dela Cruz', 'Rodriguez', 'Dr.', '', 'Janeie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1907-07-12', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(7, 'COOP-2026-0007', 'Jane', 'Torres', 'Jones', 'Dr.', 'IV', 'Janeie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1249-05-04', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(8, 'COOP-2026-0008', 'Danilo', 'Mendoza', 'Perez', 'Ms.', 'IV', 'Daniloie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1519-07-01', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(9, 'COOP-2026-0009', 'Maria Clara', 'Garcia', 'Rodriguez', 'Mrs.', 'III', 'Maria Claraie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1353-10-27', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(10, 'COOP-2026-0010', 'David', 'Torres', 'Castro', 'Mrs.', 'Jr.', 'Davidie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1202-12-21', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(11, 'COOP-2026-0011', 'Rey', 'Reyes', 'Brown', 'Engr.', 'III', 'Reyie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1313-07-03', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(12, 'COOP-2026-0012', 'Karen', 'Garcia', 'Brown', 'Ms.', 'Jr.', 'Karenie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1783-01-01', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(13, 'COOP-2026-0013', 'Mark', 'Torres', 'Jones', 'Mr.', 'III', 'Markie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1948-04-11', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(14, 'COOP-2026-0014', 'Amanda', 'Aquino', 'Gonzalez', 'Mr.', '', 'Amandaie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1336-08-16', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(15, 'COOP-2026-0015', 'Jane', 'Garcia', 'Gonzalez', 'Engr.', '', 'Janeie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1147-01-22', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(16, 'COOP-2026-0016', 'Stephanie', 'Bautista', 'Williams', 'Mrs.', 'IV', 'Stephanieie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1928-05-08', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(17, 'COOP-2026-0017', 'Amanda', 'Reyes', 'Anderson', 'Engr.', '', 'Amandaie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1468-10-20', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(18, 'COOP-2026-0018', 'Andrew', 'Aquino', 'Jones', 'Mr.', 'IV', 'Andrewie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1967-07-24', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(19, 'COOP-2026-0019', 'Sarah', 'Mendoza', 'Martinez', 'Dr.', '', 'Sarahie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1186-07-24', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(20, 'COOP-2026-0020', 'Ryan', 'Reyes', 'Wilson', 'Mrs.', '', 'Ryanie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1244-03-14', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(21, 'COOP-2026-0021', 'Princess', 'Bautista', 'Jones', 'Engr.', '', 'Princessie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1234-01-19', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(22, 'COOP-2026-0022', 'James', 'Cruz', 'Johnson', 'Engr.', 'Jr.', 'Jamesie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1467-04-05', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(23, 'COOP-2026-0023', 'William', 'Santos', 'Williams', 'Ms.', 'IV', 'Williamie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1122-12-09', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(24, 'COOP-2026-0024', 'Maria Clara', 'Mendoza', 'Martinez', 'Mr.', '', 'Maria Claraie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1498-07-16', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(25, 'COOP-2026-0025', 'Mark', 'Dela Cruz', 'Anderson', 'Ms.', 'IV', 'Markie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1884-04-03', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(26, 'COOP-2026-0026', 'Stephanie', 'Reyes', 'Hernandez', 'Mrs.', '', 'Stephanieie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1823-09-18', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(27, 'COOP-2026-0027', 'Emily', 'Ramos', 'Johnson', 'Dr.', 'III', 'Emilyie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1975-01-06', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(28, 'COOP-2026-0028', 'Jessica', 'Aquino', 'Jones', 'Dr.', 'IV', 'Jessicaie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1345-03-14', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(29, 'COOP-2026-0029', 'Maria', 'Cruz', 'Anderson', 'Dr.', '', 'Mariaie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1406-10-26', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(30, 'COOP-2026-0030', 'John', 'Bautista', 'Rodriguez', 'Ms.', 'Jr.', 'Johnie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1591-03-26', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(31, 'COOP-2026-0031', 'Jane', 'Garcia', 'Gonzalez', 'Dr.', '', 'Janeie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1322-12-22', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(32, 'COOP-2026-0032', 'Dorothy', 'Garcia', 'Anderson', 'Mr.', 'Jr.', 'Dorothyie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1390-06-13', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(33, 'COOP-2026-0033', 'James', 'Santos', 'Gonzalez', 'Engr.', 'Jr.', 'Jamesie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1253-12-15', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(34, 'COOP-2026-0034', 'David', 'Bautista', 'Smith', 'Mrs.', 'III', 'Davidie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1801-10-09', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(35, 'COOP-2026-0035', 'Princess', 'Ramos', 'Martinez', 'Engr.', 'IV', 'Princessie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1514-09-18', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(36, 'COOP-2026-0036', 'Amanda', 'Mendoza', 'Davis', 'Dr.', 'III', 'Amandaie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1922-03-10', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(37, 'COOP-2026-0037', 'Mark', 'Santos', 'Lopez', 'Ms.', 'IV', 'Markie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1906-08-06', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(38, 'COOP-2026-0038', 'Emily', 'Torres', 'Wilson', 'Ms.', '', 'Emilyie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1674-12-16', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(39, 'COOP-2026-0039', 'Rey', 'Aquino', 'Lopez', 'Engr.', 'III', 'Reyie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1675-04-28', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(40, 'COOP-2026-0040', 'David', 'Torres', 'Perez', 'Dr.', 'III', 'Davidie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1423-05-15', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(41, 'COOP-2026-0041', 'David', 'Ramos', 'Perez', 'Mr.', 'Jr.', 'Davidie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1213-02-24', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(42, 'COOP-2026-0042', 'Ryan', 'Torres', 'Smith', 'Mr.', 'Jr.', 'Ryanie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1892-10-20', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(43, 'COOP-2026-0043', 'Sarah', 'Bautista', 'Smith', 'Dr.', 'III', 'Sarahie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1484-04-01', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(44, 'COOP-2026-0044', 'Mark', 'Garcia', 'Johnson', 'Ms.', '', 'Markie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1413-12-14', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(45, 'COOP-2026-0045', 'Sarah', 'Bautista', 'Rodriguez', 'Mr.', 'IV', 'Sarahie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1324-11-06', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(46, 'COOP-2026-0046', 'Stephanie', 'Torres', 'Martinez', 'Mr.', 'IV', 'Stephanieie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1704-09-14', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(47, 'COOP-2026-0047', 'Emily', 'Cruz', 'Wilson', 'Mrs.', 'Jr.', 'Emilyie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1230-07-26', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(48, 'COOP-2026-0048', 'Princess', 'Cruz', 'Gonzalez', 'Ms.', 'IV', 'Princessie', 'Regular', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1792-05-17', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(49, 'COOP-2026-0049', 'Robert', 'Reyes', 'Williams', 'Ms.', 'III', 'Robertie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1269-12-05', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(50, 'COOP-2026-0050', 'Sarah', 'Cruz', 'Perez', 'Mr.', 'Jr.', 'Sarahie', 'Associate', 'Standard Capital Contribution Option', 'active', '2026-01-15', '1399-06-12', NULL, 'System auto-seeded cooperative member asset profile.', '2026-06-18 07:07:29', '2026-06-18 07:07:29'),
(51, 'COOP-2026-0051', 'Justin', NULL, 'Bieber', NULL, NULL, NULL, 'Regular', NULL, 'active', '0000-00-00', '0000-00-00', NULL, NULL, '2026-06-22 03:10:47', '2026-06-22 03:10:47'),
(52, 'COOP-2026-0052', 'Randall Jay', 'Veloria', 'Unarce', NULL, NULL, NULL, 'Regular', NULL, 'active', '2026-06-22', '2026-06-21', NULL, NULL, '2026-06-22 06:47:22', '2026-06-22 06:47:22');

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
(1, 1, 'Permanent', '#106', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 2', 'District 1', 'Manila', 'Metro Manila', 'NCR'),
(2, 2, 'Permanent', '#107', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 9', 'District 4', 'Manila', 'Metro Manila', 'NCR'),
(3, 3, 'Permanent', '#78', 'Rizal Avenue Ext.', 'San Jose', 'Zone 2', 'District 3', 'Antipolo', 'Rizal', 'Region IV-A'),
(4, 4, 'Permanent', '#221', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 9', 'District 4', 'Manila', 'Metro Manila', 'NCR'),
(5, 5, 'Permanent', '#123', 'Rizal Avenue Ext.', 'San Jose', 'Zone 1', 'District 2', 'Antipolo', 'Rizal', 'Region IV-A'),
(6, 6, 'Permanent', '#205', 'Rizal Avenue Ext.', 'Malagasang', 'Zone 2', 'District 4', 'Imus', 'Cavite', 'Region IV-A'),
(7, 7, 'Permanent', '#170', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 7', 'District 4', 'Manila', 'Metro Manila', 'NCR'),
(8, 8, 'Permanent', '#214', 'Rizal Avenue Ext.', 'Malagasang', 'Zone 1', 'District 2', 'Imus', 'Cavite', 'Region IV-A'),
(9, 9, 'Permanent', '#105', 'Rizal Avenue Ext.', 'San Jose', 'Zone 5', 'District 2', 'Antipolo', 'Rizal', 'Region IV-A'),
(10, 10, 'Permanent', '#93', 'Rizal Avenue Ext.', 'Malagasang', 'Zone 5', 'District 4', 'Imus', 'Cavite', 'Region IV-A'),
(11, 11, 'Permanent', '#198', 'Rizal Avenue Ext.', 'Balibago', 'Zone 6', 'District 2', 'Angeles City', 'Pampanga', 'Region III'),
(12, 12, 'Permanent', '#207', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 1', 'District 2', 'Manila', 'Metro Manila', 'NCR'),
(13, 13, 'Permanent', '#23', 'Rizal Avenue Ext.', 'Barangay 78', 'Zone 4', 'District 1', 'Caloocan', 'Metro Manila', 'NCR'),
(14, 14, 'Permanent', '#2', 'Rizal Avenue Ext.', 'Malagasang', 'Zone 4', 'District 3', 'Imus', 'Cavite', 'Region IV-A'),
(15, 15, 'Permanent', '#129', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 4', 'District 1', 'Manila', 'Metro Manila', 'NCR'),
(16, 16, 'Permanent', '#129', 'Rizal Avenue Ext.', 'Barangay 78', 'Zone 7', 'District 2', 'Caloocan', 'Metro Manila', 'NCR'),
(17, 17, 'Permanent', '#171', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 4', 'District 4', 'Manila', 'Metro Manila', 'NCR'),
(18, 18, 'Permanent', '#164', 'Rizal Avenue Ext.', 'San Jose', 'Zone 10', 'District 2', 'Antipolo', 'Rizal', 'Region IV-A'),
(19, 19, 'Permanent', '#81', 'Rizal Avenue Ext.', 'Malagasang', 'Zone 2', 'District 3', 'Imus', 'Cavite', 'Region IV-A'),
(20, 20, 'Permanent', '#60', 'Rizal Avenue Ext.', 'Balibago', 'Zone 5', 'District 2', 'Angeles City', 'Pampanga', 'Region III'),
(21, 21, 'Permanent', '#24', 'Rizal Avenue Ext.', 'Balibago', 'Zone 6', 'District 2', 'Angeles City', 'Pampanga', 'Region III'),
(22, 22, 'Permanent', '#169', 'Rizal Avenue Ext.', 'San Jose', 'Zone 2', 'District 3', 'Antipolo', 'Rizal', 'Region IV-A'),
(23, 23, 'Permanent', '#79', 'Rizal Avenue Ext.', 'Balibago', 'Zone 4', 'District 4', 'Angeles City', 'Pampanga', 'Region III'),
(24, 24, 'Permanent', '#204', 'Rizal Avenue Ext.', 'Balibago', 'Zone 8', 'District 2', 'Angeles City', 'Pampanga', 'Region III'),
(25, 25, 'Permanent', '#114', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 1', 'District 2', 'Manila', 'Metro Manila', 'NCR'),
(26, 26, 'Permanent', '#39', 'Rizal Avenue Ext.', 'Malagasang', 'Zone 10', 'District 3', 'Imus', 'Cavite', 'Region IV-A'),
(27, 27, 'Permanent', '#14', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 10', 'District 1', 'Manila', 'Metro Manila', 'NCR'),
(28, 28, 'Permanent', '#140', 'Rizal Avenue Ext.', 'San Jose', 'Zone 3', 'District 1', 'Antipolo', 'Rizal', 'Region IV-A'),
(29, 29, 'Permanent', '#163', 'Rizal Avenue Ext.', 'Balibago', 'Zone 10', 'District 4', 'Angeles City', 'Pampanga', 'Region III'),
(30, 30, 'Permanent', '#190', 'Rizal Avenue Ext.', 'San Jose', 'Zone 10', 'District 4', 'Antipolo', 'Rizal', 'Region IV-A'),
(31, 31, 'Permanent', '#41', 'Rizal Avenue Ext.', 'Barangay 78', 'Zone 6', 'District 2', 'Caloocan', 'Metro Manila', 'NCR'),
(32, 32, 'Permanent', '#207', 'Rizal Avenue Ext.', 'San Jose', 'Zone 2', 'District 4', 'Antipolo', 'Rizal', 'Region IV-A'),
(33, 33, 'Permanent', '#92', 'Rizal Avenue Ext.', 'Barangay 78', 'Zone 8', 'District 1', 'Caloocan', 'Metro Manila', 'NCR'),
(34, 34, 'Permanent', '#108', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 3', 'District 3', 'Manila', 'Metro Manila', 'NCR'),
(35, 35, 'Permanent', '#11', 'Rizal Avenue Ext.', 'Balibago', 'Zone 8', 'District 1', 'Angeles City', 'Pampanga', 'Region III'),
(36, 36, 'Permanent', '#175', 'Rizal Avenue Ext.', 'Balibago', 'Zone 5', 'District 4', 'Angeles City', 'Pampanga', 'Region III'),
(37, 37, 'Permanent', '#145', 'Rizal Avenue Ext.', 'Malagasang', 'Zone 10', 'District 1', 'Imus', 'Cavite', 'Region IV-A'),
(38, 38, 'Permanent', '#83', 'Rizal Avenue Ext.', 'San Jose', 'Zone 1', 'District 2', 'Antipolo', 'Rizal', 'Region IV-A'),
(39, 39, 'Permanent', '#189', 'Rizal Avenue Ext.', 'Barangay 78', 'Zone 7', 'District 3', 'Caloocan', 'Metro Manila', 'NCR'),
(40, 40, 'Permanent', '#11', 'Rizal Avenue Ext.', 'Balibago', 'Zone 10', 'District 1', 'Angeles City', 'Pampanga', 'Region III'),
(41, 41, 'Permanent', '#234', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 7', 'District 1', 'Manila', 'Metro Manila', 'NCR'),
(42, 42, 'Permanent', '#216', 'Rizal Avenue Ext.', 'San Jose', 'Zone 5', 'District 2', 'Antipolo', 'Rizal', 'Region IV-A'),
(43, 43, 'Permanent', '#159', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 6', 'District 2', 'Manila', 'Metro Manila', 'NCR'),
(44, 44, 'Permanent', '#147', 'Rizal Avenue Ext.', 'Balibago', 'Zone 7', 'District 4', 'Angeles City', 'Pampanga', 'Region III'),
(45, 45, 'Permanent', '#226', 'Rizal Avenue Ext.', 'Malagasang', 'Zone 1', 'District 4', 'Imus', 'Cavite', 'Region IV-A'),
(46, 46, 'Permanent', '#152', 'Rizal Avenue Ext.', 'San Jose', 'Zone 6', 'District 1', 'Antipolo', 'Rizal', 'Region IV-A'),
(47, 47, 'Permanent', '#77', 'Rizal Avenue Ext.', 'San Jose', 'Zone 6', 'District 2', 'Antipolo', 'Rizal', 'Region IV-A'),
(48, 48, 'Permanent', '#91', 'Rizal Avenue Ext.', 'Balibago', 'Zone 8', 'District 2', 'Angeles City', 'Pampanga', 'Region III'),
(49, 49, 'Permanent', '#112', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 8', 'District 1', 'Manila', 'Metro Manila', 'NCR'),
(50, 50, 'Permanent', '#180', 'Rizal Avenue Ext.', 'Barangay 12', 'Zone 9', 'District 1', 'Manila', 'Metro Manila', 'NCR'),
(54, 52, 'Home', '21', 'Street Test', 'Barangay Test', NULL, NULL, 'Town Test', 'Province Test', 'Region Test');

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
(1, 1, 'Child', 'Jane', NULL, 'Williams', NULL, NULL, '2015-08-20', NULL, 'Active'),
(2, 2, 'Sibling', 'Maria', NULL, 'Jones', NULL, NULL, '2015-08-20', NULL, 'Active'),
(3, 3, 'Sibling', 'Dorothy', NULL, 'Thomas', NULL, NULL, '2015-08-20', NULL, 'Active'),
(4, 4, 'Child', 'Mark', NULL, 'Martinez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(5, 5, 'Spouse', 'Maria Clara', NULL, 'Davis', NULL, NULL, '2015-08-20', NULL, 'Active'),
(6, 6, 'Spouse', 'Dorothy', NULL, 'Rodriguez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(7, 7, 'Sibling', 'Jun', NULL, 'Jones', NULL, NULL, '2015-08-20', NULL, 'Active'),
(8, 8, 'Spouse', 'Emily', NULL, 'Perez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(9, 9, 'Sibling', 'Emily', NULL, 'Rodriguez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(10, 10, 'Child', 'Sarah', NULL, 'Castro', NULL, NULL, '2015-08-20', NULL, 'Active'),
(11, 11, 'Spouse', 'Robert', NULL, 'Brown', NULL, NULL, '2015-08-20', NULL, 'Active'),
(12, 12, 'Sibling', 'James', NULL, 'Brown', NULL, NULL, '2015-08-20', NULL, 'Active'),
(13, 13, 'Sibling', 'Maria', NULL, 'Jones', NULL, NULL, '2015-08-20', NULL, 'Active'),
(14, 14, 'Spouse', 'Ryan', NULL, 'Gonzalez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(15, 15, 'Spouse', 'Princess', NULL, 'Gonzalez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(16, 16, 'Sibling', 'Maria', NULL, 'Williams', NULL, NULL, '2015-08-20', NULL, 'Active'),
(17, 17, 'Sibling', 'Emily', NULL, 'Anderson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(18, 18, 'Child', 'William', NULL, 'Jones', NULL, NULL, '2015-08-20', NULL, 'Active'),
(19, 19, 'Spouse', 'Maria Clara', NULL, 'Martinez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(20, 20, 'Sibling', 'Jessica', NULL, 'Wilson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(21, 21, 'Sibling', 'Mark', NULL, 'Jones', NULL, NULL, '2015-08-20', NULL, 'Active'),
(22, 22, 'Child', 'Sarah', NULL, 'Johnson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(23, 23, 'Sibling', 'Jun', NULL, 'Williams', NULL, NULL, '2015-08-20', NULL, 'Active'),
(24, 24, 'Child', 'Jessica', NULL, 'Martinez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(25, 25, 'Sibling', 'Andrew', NULL, 'Anderson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(26, 26, 'Sibling', 'Michael', NULL, 'Hernandez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(27, 27, 'Sibling', 'Mark', NULL, 'Johnson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(28, 28, 'Sibling', 'David', NULL, 'Jones', NULL, NULL, '2015-08-20', NULL, 'Active'),
(29, 29, 'Spouse', 'Andrew', NULL, 'Anderson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(30, 30, 'Child', 'Princess', NULL, 'Rodriguez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(31, 31, 'Sibling', 'William', NULL, 'Gonzalez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(32, 32, 'Sibling', 'Joseph', NULL, 'Anderson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(33, 33, 'Sibling', 'Princess', NULL, 'Gonzalez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(34, 34, 'Spouse', 'Michael', NULL, 'Smith', NULL, NULL, '2015-08-20', NULL, 'Active'),
(35, 35, 'Sibling', 'David', NULL, 'Martinez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(36, 36, 'Spouse', 'Ryan', NULL, 'Davis', NULL, NULL, '2015-08-20', NULL, 'Active'),
(37, 37, 'Spouse', 'Mark', NULL, 'Lopez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(38, 38, 'Spouse', 'Jun', NULL, 'Wilson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(39, 39, 'Child', 'William', NULL, 'Lopez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(40, 40, 'Spouse', 'James', NULL, 'Perez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(41, 41, 'Sibling', 'John', NULL, 'Perez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(42, 42, 'Child', 'Stephanie', NULL, 'Smith', NULL, NULL, '2015-08-20', NULL, 'Active'),
(43, 43, 'Child', 'William', NULL, 'Smith', NULL, NULL, '2015-08-20', NULL, 'Active'),
(44, 44, 'Spouse', 'Danilo', NULL, 'Johnson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(45, 45, 'Sibling', 'Emily', NULL, 'Rodriguez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(46, 46, 'Sibling', 'Robert', NULL, 'Martinez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(47, 47, 'Child', 'Ryan', NULL, 'Wilson', NULL, NULL, '2015-08-20', NULL, 'Active'),
(48, 48, 'Child', 'William', NULL, 'Gonzalez', NULL, NULL, '2015-08-20', NULL, 'Active'),
(49, 49, 'Child', 'Maria', NULL, 'Williams', NULL, NULL, '2015-08-20', NULL, 'Active'),
(50, 50, 'Sibling', 'Ryan', NULL, 'Perez', NULL, NULL, '2015-08-20', NULL, 'Active');

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
(1, 1, '09171196293', '09221347365', '(02) 88459639', NULL, 'stephanie.williams1@example.coop'),
(2, 2, '09173219827', '09221959779', '(02) 83154325', NULL, 'stephanie.jones2@example.coop'),
(3, 3, '09179651567', '09228823580', '(02) 89255242', NULL, 'jessica.thomas3@example.coop'),
(4, 4, '09174740322', '09225620868', '(02) 82245700', NULL, 'andrew.martinez4@example.coop'),
(5, 5, '09172451290', '09227775362', '(02) 89629730', NULL, 'john.davis5@example.coop'),
(6, 6, '09178017353', '09225878139', '(02) 86201225', NULL, 'jane.rodriguez6@example.coop'),
(7, 7, '09179939190', '09222869829', '(02) 85362781', NULL, 'jane.jones7@example.coop'),
(8, 8, '09179183851', '09225469808', '(02) 85494129', NULL, 'danilo.perez8@example.coop'),
(9, 9, '09172173574', '09222408959', '(02) 81773329', NULL, 'maria clara.rodriguez9@example.coop'),
(10, 10, '09172013426', '09229012728', '(02) 87048900', NULL, 'david.castro10@example.coop'),
(11, 11, '09171386696', '09229928262', '(02) 87048074', NULL, 'rey.brown11@example.coop'),
(12, 12, '09171416405', '09227003696', '(02) 88127280', NULL, 'karen.brown12@example.coop'),
(13, 13, '09171678684', '09229662009', '(02) 89293522', NULL, 'mark.jones13@example.coop'),
(14, 14, '09172073518', '09228407218', '(02) 86485098', NULL, 'amanda.gonzalez14@example.coop'),
(15, 15, '09179645476', '09229197234', '(02) 81914725', NULL, 'jane.gonzalez15@example.coop'),
(16, 16, '09177401546', '09224647512', '(02) 89106320', NULL, 'stephanie.williams16@example.coop'),
(17, 17, '09173699689', '09222902195', '(02) 82630802', NULL, 'amanda.anderson17@example.coop'),
(18, 18, '09174527349', '09229077688', '(02) 86072283', NULL, 'andrew.jones18@example.coop'),
(19, 19, '09173685884', '09222762184', '(02) 81350295', NULL, 'sarah.martinez19@example.coop'),
(20, 20, '09174073422', '09228133729', '(02) 84132351', NULL, 'ryan.wilson20@example.coop'),
(21, 21, '09177866364', '09228387645', '(02) 82828264', NULL, 'princess.jones21@example.coop'),
(22, 22, '09171990662', '09226662798', '(02) 81516226', NULL, 'james.johnson22@example.coop'),
(23, 23, '09171979807', '09226235115', '(02) 82768097', NULL, 'william.williams23@example.coop'),
(24, 24, '09177084150', '09227499341', '(02) 85651584', NULL, 'maria clara.martinez24@example.coop'),
(25, 25, '09172199935', '09221091630', '(02) 87029903', NULL, 'mark.anderson25@example.coop'),
(26, 26, '09179711637', '09227465617', '(02) 82335893', NULL, 'stephanie.hernandez26@example.coop'),
(27, 27, '09174460369', '09224067957', '(02) 82135438', NULL, 'emily.johnson27@example.coop'),
(28, 28, '09172059013', '09221932496', '(02) 82775081', NULL, 'jessica.jones28@example.coop'),
(29, 29, '09174825516', '09228373201', '(02) 83957371', NULL, 'maria.anderson29@example.coop'),
(30, 30, '09174327359', '09226371152', '(02) 87961911', NULL, 'john.rodriguez30@example.coop'),
(31, 31, '09178376560', '09225960100', '(02) 83709991', NULL, 'jane.gonzalez31@example.coop'),
(32, 32, '09173878649', '09226988479', '(02) 81689358', NULL, 'dorothy.anderson32@example.coop'),
(33, 33, '09172663202', '09225270471', '(02) 86865293', NULL, 'james.gonzalez33@example.coop'),
(34, 34, '09177060427', '09226247246', '(02) 83923579', NULL, 'david.smith34@example.coop'),
(35, 35, '09178675974', '09221611761', '(02) 83344595', NULL, 'princess.martinez35@example.coop'),
(36, 36, '09171476595', '09225735905', '(02) 82157569', NULL, 'amanda.davis36@example.coop'),
(37, 37, '09175172610', '09224598679', '(02) 89226006', NULL, 'mark.lopez37@example.coop'),
(38, 38, '09175983342', '09225083334', '(02) 81735794', NULL, 'emily.wilson38@example.coop'),
(39, 39, '09174862901', '09226190110', '(02) 83094507', NULL, 'rey.lopez39@example.coop'),
(40, 40, '09176930206', '09222246359', '(02) 82561832', NULL, 'david.perez40@example.coop'),
(41, 41, '09179520847', '09224964467', '(02) 88570327', NULL, 'david.perez41@example.coop'),
(42, 42, '09174803553', '09226702389', '(02) 81926946', NULL, 'ryan.smith42@example.coop'),
(43, 43, '09179003338', '09227998917', '(02) 87444865', NULL, 'sarah.smith43@example.coop'),
(44, 44, '09177122432', '09228316371', '(02) 82408956', NULL, 'mark.johnson44@example.coop'),
(45, 45, '09171249925', '09223098750', '(02) 86413188', NULL, 'sarah.rodriguez45@example.coop'),
(46, 46, '09177201069', '09228352267', '(02) 83472225', NULL, 'stephanie.martinez46@example.coop'),
(47, 47, '09172864883', '09223568678', '(02) 83752736', NULL, 'emily.wilson47@example.coop'),
(48, 48, '09174878572', '09221614676', '(02) 83999263', NULL, 'princess.gonzalez48@example.coop'),
(49, 49, '09172097265', '09227380693', '(02) 83786766', NULL, 'robert.williams49@example.coop'),
(50, 50, '09174314873', '09229429791', '(02) 86400037', NULL, 'sarah.perez50@example.coop'),
(51, 51, '123456789', NULL, NULL, NULL, 'justinbieber@gmail.com'),
(63, 52, '123456789', '123456789', NULL, NULL, 'u.randalljay00@gmail.com');

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
(1, 1, 'BS in Computer Science', 'Ateneo de Manila University', 'Manila', '2018-06-05', '2022-04-15'),
(2, 2, 'AB Communication', 'Polytechnic University of the Philippines', 'Manila', '2018-06-05', '2022-04-15'),
(3, 3, 'Associate in Hotel Management', 'De La Salle University', 'Antipolo', '2018-06-05', '2022-04-15'),
(4, 4, 'BS in Computer Science', 'Polytechnic University of the Philippines', 'Manila', '2018-06-05', '2022-04-15'),
(5, 5, 'BS in Computer Science', 'Polytechnic University of the Philippines', 'Antipolo', '2018-06-05', '2022-04-15'),
(6, 6, 'Associate in Hotel Management', 'University of Santo Tomas', 'Imus', '2018-06-05', '2022-04-15'),
(7, 7, 'AB Communication', 'Ateneo de Manila University', 'Manila', '2018-06-05', '2022-04-15'),
(8, 8, 'BS in Civil Engineering', 'Polytechnic University of the Philippines', 'Imus', '2018-06-05', '2022-04-15'),
(9, 9, 'Associate in Hotel Management', 'De La Salle University', 'Antipolo', '2018-06-05', '2022-04-15'),
(10, 10, 'BS in Computer Science', 'De La Salle University', 'Imus', '2018-06-05', '2022-04-15'),
(11, 11, 'AB Communication', 'University of the Philippines', 'Angeles City', '2018-06-05', '2022-04-15'),
(12, 12, 'Associate in Hotel Management', 'De La Salle University', 'Manila', '2018-06-05', '2022-04-15'),
(13, 13, 'BS in Computer Science', 'Polytechnic University of the Philippines', 'Caloocan', '2018-06-05', '2022-04-15'),
(14, 14, 'BS in Computer Science', 'Ateneo de Manila University', 'Imus', '2018-06-05', '2022-04-15'),
(15, 15, 'Associate in Hotel Management', 'Polytechnic University of the Philippines', 'Manila', '2018-06-05', '2022-04-15'),
(16, 16, 'Associate in Hotel Management', 'Polytechnic University of the Philippines', 'Caloocan', '2018-06-05', '2022-04-15'),
(17, 17, 'AB Communication', 'Ateneo de Manila University', 'Manila', '2018-06-05', '2022-04-15'),
(18, 18, 'Associate in Hotel Management', 'University of Santo Tomas', 'Antipolo', '2018-06-05', '2022-04-15'),
(19, 19, 'BS in Civil Engineering', 'University of Santo Tomas', 'Imus', '2018-06-05', '2022-04-15'),
(20, 20, 'BS in Civil Engineering', 'Ateneo de Manila University', 'Angeles City', '2018-06-05', '2022-04-15'),
(21, 21, 'AB Communication', 'Polytechnic University of the Philippines', 'Angeles City', '2018-06-05', '2022-04-15'),
(22, 22, 'Associate in Hotel Management', 'University of the Philippines', 'Antipolo', '2018-06-05', '2022-04-15'),
(23, 23, 'BS in Civil Engineering', 'University of Santo Tomas', 'Angeles City', '2018-06-05', '2022-04-15'),
(24, 24, 'BS in Business Administration', 'Ateneo de Manila University', 'Angeles City', '2018-06-05', '2022-04-15'),
(25, 25, 'BS in Business Administration', 'University of the Philippines', 'Manila', '2018-06-05', '2022-04-15'),
(26, 26, 'AB Communication', 'Ateneo de Manila University', 'Imus', '2018-06-05', '2022-04-15'),
(27, 27, 'AB Communication', 'Ateneo de Manila University', 'Manila', '2018-06-05', '2022-04-15'),
(28, 28, 'BS in Business Administration', 'De La Salle University', 'Antipolo', '2018-06-05', '2022-04-15'),
(29, 29, 'BS in Business Administration', 'De La Salle University', 'Angeles City', '2018-06-05', '2022-04-15'),
(30, 30, 'AB Communication', 'Ateneo de Manila University', 'Antipolo', '2018-06-05', '2022-04-15'),
(31, 31, 'BS in Civil Engineering', 'Polytechnic University of the Philippines', 'Caloocan', '2018-06-05', '2022-04-15'),
(32, 32, 'BS in Computer Science', 'University of Santo Tomas', 'Antipolo', '2018-06-05', '2022-04-15'),
(33, 33, 'BS in Business Administration', 'De La Salle University', 'Caloocan', '2018-06-05', '2022-04-15'),
(34, 34, 'AB Communication', 'University of the Philippines', 'Manila', '2018-06-05', '2022-04-15'),
(35, 35, 'AB Communication', 'University of Santo Tomas', 'Angeles City', '2018-06-05', '2022-04-15'),
(36, 36, 'AB Communication', 'Ateneo de Manila University', 'Angeles City', '2018-06-05', '2022-04-15'),
(37, 37, 'BS in Computer Science', 'De La Salle University', 'Imus', '2018-06-05', '2022-04-15'),
(38, 38, 'BS in Computer Science', 'University of Santo Tomas', 'Antipolo', '2018-06-05', '2022-04-15'),
(39, 39, 'BS in Civil Engineering', 'Polytechnic University of the Philippines', 'Caloocan', '2018-06-05', '2022-04-15'),
(40, 40, 'BS in Civil Engineering', 'University of the Philippines', 'Angeles City', '2018-06-05', '2022-04-15'),
(41, 41, 'BS in Civil Engineering', 'University of the Philippines', 'Manila', '2018-06-05', '2022-04-15'),
(42, 42, 'BS in Business Administration', 'University of Santo Tomas', 'Antipolo', '2018-06-05', '2022-04-15'),
(43, 43, 'BS in Business Administration', 'University of the Philippines', 'Manila', '2018-06-05', '2022-04-15'),
(44, 44, 'AB Communication', 'De La Salle University', 'Angeles City', '2018-06-05', '2022-04-15'),
(45, 45, 'Associate in Hotel Management', 'Ateneo de Manila University', 'Imus', '2018-06-05', '2022-04-15'),
(46, 46, 'BS in Computer Science', 'De La Salle University', 'Antipolo', '2018-06-05', '2022-04-15'),
(47, 47, 'Associate in Hotel Management', 'Ateneo de Manila University', 'Antipolo', '2018-06-05', '2022-04-15'),
(48, 48, 'BS in Computer Science', 'University of Santo Tomas', 'Angeles City', '2018-06-05', '2022-04-15'),
(49, 49, 'BS in Computer Science', 'University of Santo Tomas', 'Manila', '2018-06-05', '2022-04-15'),
(50, 50, 'AB Communication', 'University of the Philippines', 'Manila', '2018-06-05', '2022-04-15');

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
(1, 1, 'Project Manager', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(2, 2, 'Software Engineer', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(3, 3, 'Administrative Assistant', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(4, 4, 'Customer Support Specialist', 'Nexus Development', '2022-05-01', '2025-12-30'),
(5, 5, 'Project Manager', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(6, 6, 'Customer Support Specialist', 'Nexus Development', '2022-05-01', '2025-12-30'),
(7, 7, 'Customer Support Specialist', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(8, 8, 'Accountant', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(9, 9, 'Accountant', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(10, 10, 'Operations Supervisor', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(11, 11, 'Accountant', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(12, 12, 'Project Manager', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(13, 13, 'Software Engineer', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(14, 14, 'Accountant', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(15, 15, 'Customer Support Specialist', 'Nexus Development', '2022-05-01', '2025-12-30'),
(16, 16, 'Customer Support Specialist', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(17, 17, 'Accountant', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(18, 18, 'Software Engineer', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(19, 19, 'Software Engineer', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(20, 20, 'Administrative Assistant', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(21, 21, 'Project Manager', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(22, 22, 'Administrative Assistant', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(23, 23, 'Operations Supervisor', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(24, 24, 'Project Manager', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(25, 25, 'Operations Supervisor', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(26, 26, 'Operations Supervisor', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(27, 27, 'Accountant', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(28, 28, 'Administrative Assistant', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(29, 29, 'Accountant', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(30, 30, 'Operations Supervisor', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(31, 31, 'Customer Support Specialist', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(32, 32, 'Accountant', 'Nexus Development', '2022-05-01', '2025-12-30'),
(33, 33, 'Project Manager', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(34, 34, 'Software Engineer', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(35, 35, 'Project Manager', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(36, 36, 'Customer Support Specialist', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(37, 37, 'Operations Supervisor', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(38, 38, 'Administrative Assistant', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(39, 39, 'Administrative Assistant', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(40, 40, 'Administrative Assistant', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(41, 41, 'Accountant', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(42, 42, 'Accountant', 'Apex Outsourcing', '2022-05-01', '2025-12-30'),
(43, 43, 'Accountant', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(44, 44, 'Operations Supervisor', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(45, 45, 'Customer Support Specialist', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(46, 46, 'Customer Support Specialist', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(47, 47, 'Operations Supervisor', 'TechSolutions Inc.', '2022-05-01', '2025-12-30'),
(48, 48, 'Operations Supervisor', 'Global Finance Corp', '2022-05-01', '2025-12-30'),
(49, 49, 'Customer Support Specialist', 'Pioneer Enterprises', '2022-05-01', '2025-12-30'),
(50, 50, 'Project Manager', 'Pioneer Enterprises', '2022-05-01', '2025-12-30');

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
(1, 1, 'Associate', 'Member Developer', '951-826-205-000', 'Married', 'Female', '165 cm', '74 kg', 'Light', 'City Public Hospital'),
(2, 2, 'Associate', 'Member Developer', '198-262-829-000', 'Single', 'Male', '168 cm', '90 kg', 'Fair', 'City Public Hospital'),
(3, 3, 'Associate', 'Member Developer', '188-198-269-000', 'Single', 'Male', '151 cm', '74 kg', 'Fair', 'City Public Hospital'),
(4, 4, 'Associate', 'Member Developer', '567-190-864-000', 'Married', 'Female', '158 cm', '52 kg', 'Fair', 'City Public Hospital'),
(5, 5, 'Associate', 'Member Developer', '494-462-484-000', 'Married', 'Female', '164 cm', '59 kg', 'Tan', 'City Public Hospital'),
(6, 6, 'Associate', 'Member Developer', '599-635-564-000', 'Single', 'Female', '153 cm', '82 kg', 'Light', 'City Public Hospital'),
(7, 7, 'Associate', 'Member Developer', '481-397-753-000', 'Married', 'Male', '152 cm', '78 kg', 'Fair', 'City Public Hospital'),
(8, 8, 'Associate', 'Member Developer', '512-779-996-000', 'Married', 'Male', '160 cm', '57 kg', 'Tan', 'City Public Hospital'),
(9, 9, 'Associate', 'Member Developer', '897-947-935-000', 'Single', 'Male', '165 cm', '55 kg', 'Light', 'City Public Hospital'),
(10, 10, 'Associate', 'Member Developer', '454-103-784-000', 'Married', 'Female', '182 cm', '58 kg', 'Tan', 'City Public Hospital'),
(11, 11, 'Associate', 'Member Developer', '380-453-830-000', 'Single', 'Male', '167 cm', '73 kg', 'Tan', 'City Public Hospital'),
(12, 12, 'Associate', 'Member Developer', '647-139-580-000', 'Single', 'Male', '157 cm', '51 kg', 'Light', 'City Public Hospital'),
(13, 13, 'Associate', 'Member Developer', '224-142-772-000', 'Married', 'Female', '180 cm', '75 kg', 'Light', 'City Public Hospital'),
(14, 14, 'Associate', 'Member Developer', '943-613-457-000', 'Single', 'Female', '182 cm', '50 kg', 'Fair', 'City Public Hospital'),
(15, 15, 'Associate', 'Member Developer', '104-746-690-000', 'Single', 'Female', '175 cm', '66 kg', 'Tan', 'City Public Hospital'),
(16, 16, 'Associate', 'Member Developer', '972-633-329-000', 'Single', 'Male', '177 cm', '72 kg', 'Fair', 'City Public Hospital'),
(17, 17, 'Associate', 'Member Developer', '517-838-835-000', 'Single', 'Male', '181 cm', '50 kg', 'Fair', 'City Public Hospital'),
(18, 18, 'Associate', 'Member Developer', '106-782-425-000', 'Single', 'Male', '160 cm', '81 kg', 'Fair', 'City Public Hospital'),
(19, 19, 'Associate', 'Member Developer', '399-512-772-000', 'Married', 'Male', '168 cm', '82 kg', 'Fair', 'City Public Hospital'),
(20, 20, 'Associate', 'Member Developer', '161-628-492-000', 'Married', 'Male', '157 cm', '81 kg', 'Light', 'City Public Hospital'),
(21, 21, 'Associate', 'Member Developer', '474-370-818-000', 'Single', 'Female', '172 cm', '89 kg', 'Fair', 'City Public Hospital'),
(22, 22, 'Associate', 'Member Developer', '202-463-747-000', 'Married', 'Male', '174 cm', '79 kg', 'Tan', 'City Public Hospital'),
(23, 23, 'Associate', 'Member Developer', '699-907-314-000', 'Single', 'Female', '174 cm', '71 kg', 'Light', 'City Public Hospital'),
(24, 24, 'Associate', 'Member Developer', '432-203-950-000', 'Single', 'Female', '172 cm', '72 kg', 'Light', 'City Public Hospital'),
(25, 25, 'Associate', 'Member Developer', '681-281-311-000', 'Married', 'Female', '162 cm', '54 kg', 'Fair', 'City Public Hospital'),
(26, 26, 'Associate', 'Member Developer', '115-606-398-000', 'Married', 'Male', '172 cm', '79 kg', 'Fair', 'City Public Hospital'),
(27, 27, 'Associate', 'Member Developer', '675-446-389-000', 'Single', 'Female', '170 cm', '83 kg', 'Light', 'City Public Hospital'),
(28, 28, 'Associate', 'Member Developer', '671-429-757-000', 'Married', 'Male', '176 cm', '66 kg', 'Light', 'City Public Hospital'),
(29, 29, 'Associate', 'Member Developer', '583-121-318-000', 'Single', 'Female', '163 cm', '89 kg', 'Tan', 'City Public Hospital'),
(30, 30, 'Associate', 'Member Developer', '255-866-224-000', 'Married', 'Male', '182 cm', '83 kg', 'Light', 'City Public Hospital'),
(31, 31, 'Associate', 'Member Developer', '787-755-422-000', 'Married', 'Female', '171 cm', '64 kg', 'Tan', 'City Public Hospital'),
(32, 32, 'Associate', 'Member Developer', '428-588-882-000', 'Single', 'Male', '174 cm', '89 kg', 'Tan', 'City Public Hospital'),
(33, 33, 'Associate', 'Member Developer', '419-837-365-000', 'Married', 'Female', '162 cm', '73 kg', 'Light', 'City Public Hospital'),
(34, 34, 'Associate', 'Member Developer', '792-646-900-000', 'Single', 'Female', '163 cm', '81 kg', 'Tan', 'City Public Hospital'),
(35, 35, 'Associate', 'Member Developer', '971-431-484-000', 'Single', 'Male', '156 cm', '87 kg', 'Fair', 'City Public Hospital'),
(36, 36, 'Associate', 'Member Developer', '574-804-864-000', 'Single', 'Female', '164 cm', '64 kg', 'Fair', 'City Public Hospital'),
(37, 37, 'Associate', 'Member Developer', '179-856-164-000', 'Married', 'Male', '154 cm', '68 kg', 'Light', 'City Public Hospital'),
(38, 38, 'Associate', 'Member Developer', '837-574-954-000', 'Married', 'Male', '165 cm', '71 kg', 'Fair', 'City Public Hospital'),
(39, 39, 'Associate', 'Member Developer', '709-164-270-000', 'Single', 'Female', '184 cm', '88 kg', 'Light', 'City Public Hospital'),
(40, 40, 'Associate', 'Member Developer', '542-858-486-000', 'Married', 'Male', '153 cm', '90 kg', 'Light', 'City Public Hospital'),
(41, 41, 'Associate', 'Member Developer', '806-447-571-000', 'Single', 'Female', '185 cm', '69 kg', 'Light', 'City Public Hospital'),
(42, 42, 'Associate', 'Member Developer', '135-899-686-000', 'Married', 'Female', '174 cm', '74 kg', 'Light', 'City Public Hospital'),
(43, 43, 'Associate', 'Member Developer', '941-265-755-000', 'Single', 'Female', '171 cm', '89 kg', 'Fair', 'City Public Hospital'),
(44, 44, 'Associate', 'Member Developer', '304-264-588-000', 'Single', 'Female', '184 cm', '80 kg', 'Light', 'City Public Hospital'),
(45, 45, 'Associate', 'Member Developer', '320-363-107-000', 'Married', 'Male', '165 cm', '83 kg', 'Fair', 'City Public Hospital'),
(46, 46, 'Associate', 'Member Developer', '406-959-277-000', 'Married', 'Female', '167 cm', '88 kg', 'Light', 'City Public Hospital'),
(47, 47, 'Associate', 'Member Developer', '312-470-629-000', 'Married', 'Female', '169 cm', '73 kg', 'Fair', 'City Public Hospital'),
(48, 48, 'Associate', 'Member Developer', '357-592-331-000', 'Married', 'Male', '153 cm', '55 kg', 'Fair', 'City Public Hospital'),
(49, 49, 'Associate', 'Member Developer', '622-288-784-000', 'Married', 'Female', '164 cm', '81 kg', 'Light', 'City Public Hospital'),
(50, 50, 'Associate', 'Member Developer', '330-822-744-000', 'Married', 'Female', '172 cm', '68 kg', 'Fair', 'City Public Hospital'),
(62, 52, NULL, NULL, NULL, 'Single', 'Male', NULL, NULL, NULL, NULL);

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
(1, 1, 5000.00, 0.00, 5000.00, 0.00, 0.00, 'Global', 'OR-10023', '2026-06-19 02:51:13'),
(2, 1, 15000.00, 0.00, 1000.00, 14000.00, 0.00, 'Global', 'OR-10024', '2026-06-19 02:54:19'),
(3, 2, 10699.99, 100.00, 600.00, 9999.99, 0.00, 'Global', 'Final Settlement Test', '2026-06-19 03:23:14');

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
(2, 2, 'dummy_customer', 'dummy@test.com', '$2y$10$ua8918vz/NZ5o9aVm/GA6OXQ5Eyb0V/HFGWkz8uZL74TBCx25zXT6', 'active', '2026-06-18 02:48:40');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_vouchers`
--
ALTER TABLE `journal_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ledger_entries`
--
ALTER TABLE `ledger_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loan_schedules`
--
ALTER TABLE `loan_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `member_addresses`
--
ALTER TABLE `member_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `member_beneficiaries`
--
ALTER TABLE `member_beneficiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `member_contact`
--
ALTER TABLE `member_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `member_education`
--
ALTER TABLE `member_education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `member_experience`
--
ALTER TABLE `member_experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `member_profiles`
--
ALTER TABLE `member_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `payment_ledger`
--
ALTER TABLE `payment_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
