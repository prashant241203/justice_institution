-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2026 at 02:03 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `justice_institution`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `case_id` varchar(10) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `date_filed` date DEFAULT NULL,
  `status` enum('Open','Pending','Closed') DEFAULT 'Open',
  `judge_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_id`, `title`, `date_filed`, `status`, `judge_id`, `created_by`, `created_at`, `updated_at`) VALUES
('C-001', 'Land Dispute Case', '2026-02-06', 'Open', 2, 6, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-002', 'Property Fraud Case', '2026-02-06', 'Pending', 7, 6, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-003', 'Cheque Bounce Case', '2026-02-06', 'Open', 2, 3, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-004', 'Domestic Violence Case', '2026-02-06', 'Closed', 7, 3, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-005', 'Cyber Crime Case', '2026-02-06', 'Pending', 2, 6, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-006', 'Theft Case', '2026-02-06', 'Open', 7, 8, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-007', 'Bank Loan Dispute', '2026-02-06', 'Open', 2, 6, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-008', 'Road Accident Case', '2026-02-06', 'Closed', 7, 3, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-009', 'Family Court Case', '2026-02-06', 'Pending', 2, 6, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C-010', 'Employment Dispute Case', '2026-02-06', 'Open', 7, 8, '2026-02-06 13:01:26', '2026-02-06 18:31:26'),
('C001', 'chori', '2026-01-08', 'Closed', 7, 3, '2026-01-18 09:38:25', NULL),
('C002', 'murder', '2026-01-02', 'Closed', 7, 2, '2026-01-18 09:49:26', NULL),
('C003', 'half-murder', '2026-01-22', 'Closed', 7, 3, '2026-02-03 12:28:18', NULL),
('C004', 'chori', '2026-01-05', 'Pending', 2, 2, '2026-02-04 05:20:00', NULL),
('C005', 'murder', '2026-01-06', 'Pending', 2, 2, '2026-02-04 05:20:00', NULL),
('C006', 'fraud', '2026-01-07', 'Closed', 7, 3, '2026-02-04 05:20:00', NULL),
('C007', 'chori', '2026-01-08', 'Pending', 2, 3, '2026-02-04 05:20:00', NULL),
('C008', 'assault', '2026-01-09', 'Pending', 2, 2, '2026-02-04 05:20:00', NULL),
('C009', 'cyber crime', '2026-01-10', 'Open', 7, 3, '2026-02-04 05:20:00', NULL),
('C010', 'theft', '2026-01-11', 'Closed', 7, 2, '2026-02-04 05:20:00', NULL),
('C011', 'domestic violence', '2026-01-12', 'Pending', 2, 3, '2026-02-04 05:20:00', NULL),
('C012', 'murder', '2026-01-13', 'Open', 7, 2, '2026-02-04 05:20:00', NULL),
('C013', 'fraud', '2026-01-14', 'Closed', 2, 3, '2026-02-04 05:20:00', NULL),
('C014', 'chori', '2026-01-01', 'Open', 7, 2, '2026-02-04 05:20:44', NULL),
('C015', 'murder', '2026-01-02', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C016', 'fraud', '2026-01-03', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C017', 'assault', '2026-01-04', 'Open', 2, 3, '2026-02-04 05:20:44', NULL),
('C018', 'cyber crime', '2026-01-05', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C019', 'theft', '2026-01-06', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C020', 'domestic violence', '2026-01-07', 'Open', 2, 2, '2026-02-04 05:20:44', NULL),
('C021', 'murder', '2026-01-08', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C022', 'fraud', '2026-01-09', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C023', 'chori', '2026-01-10', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C024', 'assault', '2026-01-11', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C025', 'cyber crime', '2026-01-12', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C026', 'theft', '2026-01-13', 'Open', 7, 2, '2026-02-04 05:20:44', NULL),
('C027', 'domestic violence', '2026-01-14', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C028', 'murder', '2026-01-15', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C029', 'fraud', '2026-01-16', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C030', 'chori', '2026-01-17', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C031', 'assault', '2026-01-18', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C032', 'cyber crime', '2026-01-19', 'Open', 7, 2, '2026-02-04 05:20:44', NULL),
('C033', 'theft', '2026-01-20', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C034', 'domestic violence', '2026-01-21', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C035', 'murder', '2026-01-22', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C036', 'fraud', '2026-01-23', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C037', 'chori', '2026-01-24', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C038', 'assault', '2026-01-25', 'Open', 7, 2, '2026-02-04 05:20:44', NULL),
('C039', 'cyber crime', '2026-01-26', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C040', 'theft', '2026-01-27', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C041', 'domestic violence', '2026-01-28', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C042', 'murder', '2026-01-29', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C043', 'fraud', '2026-01-30', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C044', 'chori', '2026-02-01', 'Open', 7, 2, '2026-02-04 05:20:44', NULL),
('C045', 'assault', '2026-02-02', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C046', 'cyber crime', '2026-02-03', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C047', 'theft', '2026-02-04', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C048', 'domestic violence', '2026-02-05', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C049', 'murder', '2026-02-06', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C050', 'fraud', '2026-02-07', 'Open', 7, 2, '2026-02-04 05:20:44', NULL),
('C051', 'chori', '2026-02-08', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C052', 'assault', '2026-02-09', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C053', 'cyber crime', '2026-02-10', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C054', 'theft', '2026-02-11', 'Closed', 2, 2, '2026-02-04 05:20:44', NULL),
('C055', 'domestic violence', '2026-02-12', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C056', 'murder', '2026-02-13', 'Closed', 2, 2, '2026-02-04 05:20:44', NULL),
('C057', 'fraud', '2026-02-14', 'Pending', 3, 3, '2026-02-04 05:20:44', NULL),
('C058', 'chori', '2026-02-15', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C059', 'assault', '2026-02-16', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C060', 'cyber crime', '2026-02-17', 'Pending', 7, 2, '2026-02-04 05:20:44', NULL),
('C061', 'theft', '2026-02-18', 'Closed', 2, 3, '2026-02-04 05:20:44', NULL),
('C062', 'domestic violence', '2026-02-19', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C063', 'murder', '2026-02-20', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C064', 'arson', '2026-02-20', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C065', 'embezzlement', '2026-02-21', 'Open', 7, 2, '2026-02-04 05:20:44', NULL),
('C066', 'kidnapping', '2026-02-22', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C067', 'robbery', '2026-02-23', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C068', 'assault', '2026-02-24', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C069', 'fraud', '2026-02-25', 'Closed', 2, 2, '2026-02-04 05:20:44', NULL),
('C070', 'cyber crime', '2026-02-26', 'Pending', 2, 3, '2026-02-04 05:20:44', NULL),
('C071', 'domestic violence', '2026-02-27', 'Open', 7, 2, '2026-02-04 05:20:44', NULL),
('C072', 'theft', '2026-02-28', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C073', 'murder', '0000-00-00', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C074', 'chori', '2026-03-01', 'Open', 2, 3, '2026-02-04 05:20:44', NULL),
('C075', 'assault', '2026-03-02', 'Closed', 7, 2, '2026-02-04 05:20:44', NULL),
('C076', 'cyber crime', '2026-03-03', 'Pending', 7, 3, '2026-02-04 05:20:44', NULL),
('C077', 'fraud', '2026-03-04', 'Open', 2, 2, '2026-02-04 05:20:44', NULL),
('C078', 'murder', '2026-03-05', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C079', 'arson', '2026-03-06', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C080', 'embezzlement', '2026-03-07', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C081', 'kidnapping', '2026-03-08', 'Closed', 2, 2, '2026-02-04 05:20:44', NULL),
('C082', 'robbery', '2026-03-09', 'Pending', 7, 3, '2026-02-04 05:20:44', NULL),
('C083', 'assault', '2026-03-10', 'Open', 2, 2, '2026-02-04 05:20:44', NULL),
('C084', 'fraud', '2026-03-11', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C085', 'cyber crime', '2026-03-12', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C086', 'domestic violence', '2026-03-13', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C087', 'theft', '2026-03-14', 'Closed', 2, 2, '2026-02-04 05:20:44', NULL),
('C088', 'murder', '2026-03-15', 'Pending', 7, 3, '2026-02-04 05:20:44', NULL),
('C089', 'chori', '2026-03-16', 'Open', 2, 2, '2026-02-04 05:20:44', NULL),
('C090', 'assault', '2026-03-17', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C091', 'cyber crime', '2026-03-18', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C092', 'fraud', '2026-03-19', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C093', 'murder', '2026-03-20', 'Closed', 2, 2, '2026-02-04 05:20:44', NULL),
('C094', 'arson', '2026-03-21', 'Pending', 7, 3, '2026-02-04 05:20:44', NULL),
('C095', 'embezzlement', '2026-03-22', 'Open', 2, 2, '2026-02-04 05:20:44', NULL),
('C096', 'kidnapping', '2026-03-23', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C097', 'robbery', '2026-03-24', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C098', 'assault', '2026-03-25', 'Open', 7, 3, '2026-02-04 05:20:44', NULL),
('C099', 'cyber crime', '2026-03-26', 'Closed', 2, 2, '2026-02-04 05:20:44', NULL),
('C100', 'fraud', '2026-03-27', 'Pending', 7, 3, '2026-02-04 05:20:44', NULL),
('C101', 'murder', '2026-03-28', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C102', 'theft', '2026-03-29', 'Closed', 7, 3, '2026-02-04 05:20:44', NULL),
('C103', 'domestic violence', '2026-03-30', 'Pending', 2, 2, '2026-02-04 05:20:44', NULL),
('C104', 'arson', '2026-03-31', 'Closed', 2, 3, '2026-02-04 05:20:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hearings`
--

CREATE TABLE `hearings` (
  `hearing_id` varchar(10) NOT NULL,
  `case_id` varchar(10) DEFAULT NULL,
  `hearing_date` date DEFAULT NULL,
  `court_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hearings`
--

INSERT INTO `hearings` (`hearing_id`, `case_id`, `hearing_date`, `court_name`, `created_at`, `created_by`) VALUES
('H001', 'C001', '2026-01-15', '6', '2026-01-18 09:48:53', NULL),
('H002', 'C001', '2026-01-02', '12', '2026-01-18 10:13:56', NULL),
('H003', 'C002', '2026-01-07', '1', '2026-01-18 10:14:40', NULL),
('H004', 'C003', '2026-02-27', '5', '2026-02-04 04:35:06', NULL),
('H005', 'C002', '2026-02-13', '6', '2026-02-04 04:35:22', NULL),
('H006', 'C008', '2026-02-17', '7', '2026-02-04 06:37:15', NULL),
('H007', 'C010', '2026-02-19', '5', '2026-02-04 06:38:03', NULL),
('H008', 'C004', '2026-02-20', '7', '2026-02-04 06:40:04', NULL),
('H009', 'C059', '2026-02-19', '7', '2026-02-04 06:57:59', NULL),
('H010', 'C062', '2026-02-24', '8', '2026-02-04 07:10:55', NULL),
('H011', 'C056', '2026-02-24', '7', '2026-02-04 07:11:59', NULL),
('H012', 'C063', '2026-02-26', '8', '2026-02-04 07:12:08', NULL),
('H013', 'C054', '2026-02-15', '6', '2026-02-04 09:35:38', NULL),
('H014', 'C060', '2026-02-08', '2', '2026-02-04 09:42:21', NULL),
('H015', 'C041', '2026-02-08', '5', '2026-02-04 11:28:30', NULL),
('H016', 'C063', '2026-02-14', '7', '2026-02-04 11:39:33', NULL),
('H017', 'C104', '2026-02-21', '3', '2026-02-05 12:08:54', NULL),
('H018', 'C101', '2026-02-10', '1', '2026-02-05 12:10:06', NULL),
('H999', 'C001', '2026-02-05', '5', '2026-02-05 12:28:18', 1),
('HTODAY', 'C001', '2026-02-05', 'District Court', '2026-02-05 12:33:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `judgements`
--

CREATE TABLE `judgements` (
  `judgement_id` varchar(10) NOT NULL,
  `case_id` varchar(10) DEFAULT NULL,
  `judgement_date` date DEFAULT NULL,
  `outcome` varchar(255) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `judge_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `judgements`
--

INSERT INTO `judgements` (`judgement_id`, `case_id`, `judgement_date`, `outcome`, `summary`, `created_at`, `judge_id`) VALUES
('J001', 'C001', '2026-01-01', 'Acquitted', 'nothing', '2026-01-18 09:57:51', NULL),
('J002', 'C002', '2026-01-18', 'Not Guilty', 'everything is okay ', '2026-01-18 10:15:19', NULL),
('J003', 'C003', '2026-02-18', 'Appeal Allowed', 'may be opponent is wrong', '2026-02-04 04:37:06', NULL),
('J004', 'C059', '2026-02-27', 'Probation', 'aa', '2026-02-04 06:58:13', NULL),
('J005', 'C062', '2026-02-18', 'Dismissed', 'aaa', '2026-02-04 07:11:29', NULL),
('J006', 'C063', '2026-02-04', 'Guilty', 'aaa', '2026-02-04 07:12:38', NULL),
('J007', 'C056', '2026-02-06', 'Case Withdrawn', 'end', '2026-02-04 10:25:25', NULL),
('J008', 'C054', '2026-02-04', 'Case Withdrawn', 'end', '2026-02-04 10:38:29', NULL),
('J009', 'C104', '2026-02-28', 'Appeal Allowed', 'thats all..', '2026-02-05 12:09:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pattern_flags`
--

CREATE TABLE `pattern_flags` (
  `flag_id` int(11) NOT NULL,
  `case_id` varchar(10) DEFAULT NULL,
  `flag_type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved` enum('yes','no') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pattern_flags`
--

INSERT INTO `pattern_flags` (`flag_id`, `case_id`, `flag_type`, `description`, `created_at`, `resolved`) VALUES
(22, 'C004', 'DELAY', 'Case pending for more than 30 days', '2026-02-06 09:22:13', 'no'),
(23, 'C005', 'DELAY', 'Case pending for more than 30 days', '2026-02-06 09:22:13', 'no'),
(24, 'C014', 'DELAY', 'Case pending for more than 30 days', '2026-02-06 09:22:13', 'no'),
(25, 'C015', 'DELAY', 'Case pending for more than 30 days', '2026-02-06 09:22:13', 'no'),
(26, 'C017', 'DELAY', 'Case pending for more than 30 days', '2026-02-06 09:22:13', 'no'),
(27, 'C018', 'DELAY', 'Case pending for more than 30 days', '2026-02-06 09:22:13', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','judge','clerk','lawyer','analyst') NOT NULL DEFAULT 'clerk',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`, `status`, `approved_by`, `approved_at`) VALUES
(2, 'Justice Sharma', 'judge@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'judge', '2026-01-18 11:24:39', 'approved', NULL, NULL),
(3, 'Clerk Kumar', 'clerk@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'clerk', '2026-01-18 11:24:39', 'pending', NULL, NULL),
(4, 'Lawyer Verma', 'lawyer@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'lawyer', '2026-01-18 11:24:39', 'pending', NULL, NULL),
(5, 'Analyst Singh', 'analyst@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'analyst', '2026-01-18 11:24:39', 'pending', NULL, NULL),
(6, 'System Admin', 'admin@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'admin', '2026-02-04 05:00:24', 'approved', 6, '2026-02-05 17:36:42'),
(7, 'Justice Ramesh', 'judge3@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'judge', '2026-02-04 11:35:50', 'approved', NULL, NULL),
(8, 'Clerk Anjali', 'clerk3@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'clerk', '2026-02-04 11:35:50', 'pending', NULL, NULL),
(9, 'Lawyer Arjun', 'lawyer3@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'lawyer', '2026-02-04 11:35:50', 'pending', NULL, NULL),
(10, 'Analyst Priya', 'analyst3@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'analyst', '2026-02-04 11:35:50', 'pending', NULL, NULL),
(12, 'aaaa', 'aaaa@gmail.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'lawyer', '2026-02-06 09:16:15', 'rejected', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`case_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `hearings`
--
ALTER TABLE `hearings`
  ADD PRIMARY KEY (`hearing_id`),
  ADD KEY `case_id` (`case_id`);

--
-- Indexes for table `judgements`
--
ALTER TABLE `judgements`
  ADD PRIMARY KEY (`judgement_id`),
  ADD KEY `case_id` (`case_id`);

--
-- Indexes for table `pattern_flags`
--
ALTER TABLE `pattern_flags`
  ADD PRIMARY KEY (`flag_id`),
  ADD KEY `case_id` (`case_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`,`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pattern_flags`
--
ALTER TABLE `pattern_flags`
  MODIFY `flag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `hearings`
--
ALTER TABLE `hearings`
  ADD CONSTRAINT `hearings_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`);

--
-- Constraints for table `judgements`
--
ALTER TABLE `judgements`
  ADD CONSTRAINT `judgements_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`);

--
-- Constraints for table `pattern_flags`
--
ALTER TABLE `pattern_flags`
  ADD CONSTRAINT `pattern_flags_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
