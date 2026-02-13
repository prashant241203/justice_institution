-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2026 at 08:03 AM
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
  `lawyer_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `judgement_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_id`, `title`, `date_filed`, `status`, `judge_id`, `lawyer_id`, `created_by`, `created_at`, `updated_at`, `judgement_file`) VALUES
('C001', 'chori', '2026-01-08', 'Closed', 7, NULL, 3, '2026-01-18 09:38:25', NULL, NULL),
('C002', 'murder', '2026-01-02', 'Closed', 7, NULL, 2, '2026-01-18 09:49:26', NULL, NULL),
('C003', 'half-murder', '2026-01-22', 'Closed', 7, NULL, 3, '2026-02-03 12:28:18', NULL, NULL),
('C004', 'chori', '2026-01-05', 'Pending', 2, NULL, 2, '2026-02-04 05:20:00', NULL, NULL),
('C005', 'murder', '2026-01-06', 'Pending', 2, NULL, 2, '2026-02-04 05:20:00', NULL, NULL),
('C006', 'fraud', '2026-01-07', 'Closed', 7, NULL, 3, '2026-02-04 05:20:00', NULL, NULL),
('C007', 'chori', '2026-01-08', 'Pending', 2, NULL, 3, '2026-02-04 05:20:00', NULL, NULL),
('C008', 'assault', '2026-01-09', 'Pending', 2, NULL, 2, '2026-02-04 05:20:00', NULL, NULL),
('C009', 'cyber crime', '2026-01-10', 'Open', 7, NULL, 3, '2026-02-04 05:20:00', NULL, NULL),
('C010', 'theft', '2026-01-11', 'Closed', 7, NULL, 2, '2026-02-04 05:20:00', NULL, NULL),
('C011', 'domestic violence', '2026-01-12', 'Pending', 2, NULL, 3, '2026-02-04 05:20:00', NULL, NULL),
('C012', 'murder', '2026-01-13', 'Open', 7, NULL, 2, '2026-02-04 05:20:00', NULL, NULL),
('C013', 'fraud', '2026-01-14', 'Closed', 2, NULL, 3, '2026-02-04 05:20:00', NULL, NULL),
('C014', 'chori', '2026-01-01', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C015', 'murder', '2026-01-02', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C016', 'fraud', '2026-01-03', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C017', 'assault', '2026-01-04', 'Open', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C018', 'cyber crime', '2026-01-05', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C019', 'theft', '2026-01-06', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C020', 'domestic violence', '2026-01-07', 'Open', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C021', 'murder', '2026-01-08', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C022', 'fraud', '2026-01-09', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C023', 'chori', '2026-01-10', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C024', 'assault', '2026-01-11', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C025', 'cyber crime', '2026-01-12', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C026', 'theft', '2026-01-13', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C027', 'domestic violence', '2026-01-14', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C028', 'murder', '2026-01-15', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C029', 'fraud', '2026-01-16', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C030', 'chori', '2026-01-17', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C031', 'assault', '2026-01-18', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C032', 'cyber crime', '2026-01-19', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C033', 'theft', '2026-01-20', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C034', 'domestic violence', '2026-01-21', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C035', 'murder', '2026-01-22', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C036', 'fraud', '2026-01-23', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C037', 'chori', '2026-01-24', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C038', 'assault', '2026-01-25', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C039', 'cyber crime', '2026-01-26', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C040', 'theft', '2026-01-27', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C041', 'domestic violence', '2026-01-28', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C042', 'murder', '2026-01-29', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C043', 'fraud', '2026-01-30', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C044', 'chori', '2026-02-01', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C045', 'assault', '2026-02-02', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C046', 'cyber crime', '2026-02-03', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C047', 'theft', '2026-02-04', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C048', 'domestic violence', '2026-02-05', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C049', 'murder', '2026-02-06', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C050', 'fraud', '2026-02-07', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C051', 'chori', '2026-02-08', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C052', 'assault', '2026-02-09', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C053', 'cyber crime', '2026-02-10', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C054', 'theft', '2026-02-11', 'Closed', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C055', 'domestic violence', '2026-02-12', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C056', 'murder', '2026-02-13', 'Closed', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C057', 'fraud', '2026-02-14', 'Pending', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C058', 'chori', '2026-02-15', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C059', 'assault', '2026-02-16', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C060', 'cyber crime', '2026-02-17', 'Pending', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C061', 'theft', '2026-02-18', 'Closed', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C062', 'domestic violence', '2026-02-19', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C063', 'murder', '2026-02-20', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C064', 'arson', '2026-02-20', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C065', 'embezzlement', '2026-02-21', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C066', 'kidnapping', '2026-02-22', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C067', 'robbery', '2026-02-23', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C068', 'assault', '2026-02-24', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C069', 'fraud', '2026-02-25', 'Closed', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C070', 'cyber crime', '2026-02-26', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C071', 'domestic violence', '2026-02-27', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C072', 'theft', '2026-02-28', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C073', 'murder', '0000-00-00', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C074', 'chori', '2026-03-01', 'Open', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C075', 'assault', '2026-03-02', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C076', 'cyber crime', '2026-03-03', 'Pending', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C077', 'fraud', '2026-03-04', 'Open', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C078', 'murder', '2026-03-05', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C079', 'arson', '2026-03-06', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C080', 'embezzlement', '2026-03-07', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C081', 'kidnapping', '2026-03-08', 'Closed', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C082', 'robbery', '2026-03-09', 'Pending', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C083', 'assault', '2026-03-10', 'Open', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C084', 'fraud', '2026-03-11', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C085', 'cyber crime', '2026-03-12', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C086', 'domestic violence', '2026-03-13', 'Pending', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C087', 'theft', '2026-03-14', 'Closed', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C088', 'murder', '2026-03-15', 'Pending', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C089', 'chori', '2026-03-16', 'Open', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C090', 'assault', '2026-03-17', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C091', 'cyber crime', '2026-03-18', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C092', 'fraud', '2026-03-19', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C093', 'murder', '2026-03-20', 'Closed', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C094', 'arson', '2026-03-21', 'Open', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C095', 'embezzlement', '2026-03-22', 'Pending', 2, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C096', 'kidnapping', '2026-03-23', 'Pending', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C097', 'robbery', '2026-03-24', 'Open', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C098', 'assault', '2026-03-25', 'Pending', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C099', 'cyber crime', '2026-03-26', 'Closed', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C100', 'fraud', '2026-03-27', 'Pending', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C101', 'murder', '2026-03-28', 'Pending', 7, NULL, 2, '2026-02-04 05:20:44', NULL, NULL),
('C102', 'theft', '2026-03-29', 'Closed', 7, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C103', 'domestic violence', '2026-03-30', 'Pending', 2, 9, 2, '2026-02-04 05:20:44', NULL, NULL),
('C104', 'arson', '2026-03-31', 'Closed', 2, NULL, 3, '2026-02-04 05:20:44', NULL, NULL),
('C105', 'aassasasasasaasdsdsd', '2026-02-26', 'Pending', 2, NULL, NULL, '2026-02-11 05:41:54', NULL, NULL),
('C106', 'aagfghghghgh', '2026-02-16', 'Open', NULL, NULL, NULL, '2026-02-11 08:54:15', NULL, NULL),
('C107', 'asasasasasasasasasasasasasasasasasas', '2026-02-19', 'Pending', NULL, 9, NULL, '2026-02-12 05:59:02', NULL, NULL),
('C108', 'oooooooo', '2026-02-20', 'Open', NULL, 9, NULL, '2026-02-12 05:59:57', NULL, NULL),
('C109', 'ppppppppppppppppppppppppppppppppppp', '2026-02-15', 'Pending', 2, 9, 6, '2026-02-12 06:16:14', NULL, NULL),
('C110', 'iiiiiiiiiiiiiiiii', '2026-02-18', 'Open', NULL, 9, NULL, '2026-02-12 06:18:30', NULL, NULL),
('C111', 'uuuuuuuuuuuuuuuuuuuu', '2026-02-22', 'Open', 0, 9, 2, '2026-02-12 06:18:55', NULL, NULL),
('C112', 'wwwwwwwwwwwwww', '2026-02-15', 'Open', 0, 9, 6, '2026-02-12 06:30:33', NULL, NULL),
('C113', 'qqqqqqqqqqqqq', '2026-03-05', 'Pending', 0, 9, 3, '2026-02-12 06:30:51', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `doc_id` int(11) NOT NULL,
  `case_id` varchar(20) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`doc_id`, `case_id`, `uploaded_by`, `file_name`, `file_path`, `uploaded_at`) VALUES
(1, 'C103', 9, 'Screenshot (15).png', 'uploads/documents/1770808231_Screenshot__15_.png', '2026-02-11 11:10:31'),
(2, 'C103', 9, 'Screenshot (16).png', 'uploads/documents/1770808246_Screenshot__16_.png', '2026-02-11 11:10:46'),
(3, 'C103', 9, 'Screenshot (14).png', 'uploads/documents/1770809016_Screenshot__14_.png', '2026-02-11 11:23:36'),
(4, 'C-001', 9, 'Screenshot (14).png', 'uploads/documents/1770811019_Screenshot__14_.png', '2026-02-11 11:56:59'),
(5, 'C-001', 9, 'Screenshot (16).png', 'uploads/documents/1770812068_Screenshot__16_.png', '2026-02-11 12:14:28');

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
('H1000', 'C098', '2026-02-12', '8', '2026-02-09 11:12:20', NULL),
('H1001', 'C086', '2026-02-09', '5', '2026-02-09 11:23:11', NULL),
('H1002', 'C095', '2026-02-20', '5', '2026-02-10 10:23:36', NULL),
('H1003', 'C104', '2026-02-20', '7', '2026-02-10 10:39:03', NULL),
('H1004', 'C098', '2026-02-20', '5', '2026-02-10 10:45:43', NULL),
('H1005', 'C101', '2026-02-20', '1', '2026-02-10 10:55:11', NULL),
('H1006', 'C097', '2026-02-15', '8', '2026-02-10 11:52:11', NULL),
('H1007', 'C095', '2026-02-26', '6', '2026-02-10 12:26:27', NULL),
('H1008', 'C098', '2026-02-21', '5', '2026-02-11 04:37:31', NULL),
('H1009', 'C095', '2026-02-06', '8', '2026-02-11 04:37:54', NULL),
('H1010', 'C096', '2026-02-15', '5', '2026-02-11 04:38:11', NULL),
('H1011', 'C095', '2026-02-25', '5', '2026-02-11 04:39:43', NULL),
('H1012', 'C096', '2026-02-21', '6', '2026-02-11 04:40:37', NULL),
('H1013', 'C096', '2026-02-18', '6', '2026-02-11 04:54:06', NULL),
('H1014', 'C100', '2026-02-12', '8', '2026-02-11 05:13:38', NULL),
('H1015', 'C100', '2026-02-15', '1', '2026-02-11 05:14:08', NULL),
('H1016', 'C101', '2026-02-19', '2', '2026-02-11 05:23:51', NULL),
('H1017', 'C105', '2026-02-17', '5', '2026-02-11 06:03:27', NULL),
('H1018', 'C105', '2026-02-27', '8', '2026-02-11 06:04:19', NULL),
('H1019', 'C105', '2026-02-04', '6', '2026-02-11 06:06:02', NULL),
('H1020', 'C105', '2026-02-15', '6', '2026-02-11 06:06:34', NULL),
('H1021', 'C099', '2026-02-27', '1', '2026-02-11 06:45:07', NULL),
('H1022', 'C103', '2026-02-19', '8', '2026-02-11 11:01:53', NULL),
('H1023', 'C109', '2026-02-15', '6', '2026-02-12 06:16:46', NULL),
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
('J009', 'C104', '2026-02-28', 'Appeal Allowed', 'thats all..', '2026-02-05 12:09:19', NULL),
('J010', 'C098', '2026-02-10', 'Settlement', 'ookokmokmmkkk', '2026-02-10 10:48:25', NULL),
('J011', 'C104', '2026-02-10', 'Settlement', 'bjbjbjj', '2026-02-10 11:11:20', NULL),
('J012', 'C099', '2026-02-11', 'Guilty', 'kkmdkspoomfomf', '2026-02-11 06:46:28', NULL);

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
(885, 'C004', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(886, 'C005', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(887, 'C007', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(888, 'C008', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(889, 'C009', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(890, 'C011', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(891, 'C012', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(892, 'C014', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(893, 'C015', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(894, 'C017', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(895, 'C018', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(896, 'C020', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(897, 'C021', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(898, 'C023', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(899, 'C024', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(900, 'C026', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(901, 'C027', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(902, 'C029', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(903, 'C030', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(904, 'C032', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(905, 'C033', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(906, 'C035', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(907, 'C036', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(908, 'C038', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(909, 'C039', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(910, 'C041', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(911, 'C042', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(912, 'C044', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(913, 'C045', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(914, 'C047', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(915, 'C048', 'DELAY', 'Case pending more than 5 days', '2026-02-12 06:19:50', 'no'),
(916, 'C073', 'INVALID_DATE', 'Invalid case date', '2026-02-12 06:19:50', 'no'),
(917, 'C013', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(918, 'C051', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(919, 'C054', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(920, 'C056', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(921, 'C061', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(922, 'C063', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(923, 'C064', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(924, 'C067', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(925, 'C069', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(926, 'C070', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(932, 'C001', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(933, 'C002', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(934, 'C003', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(935, 'C006', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(936, 'C010', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(937, 'C016', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(938, 'C019', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(939, 'C022', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(940, 'C025', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(941, 'C028', 'JUDGE_OVERLOAD', 'Judge overloaded', '2026-02-12 06:19:50', 'no'),
(947, 'C031', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(948, 'C052', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(949, 'C059', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(950, 'C068', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(951, 'C075', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(954, 'C037', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(955, 'C058', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(956, 'C074', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(957, 'C089', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(961, 'C046', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(962, 'C053', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(963, 'C060', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(964, 'C076', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(965, 'C085', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(968, 'C034', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(969, 'C055', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(970, 'C062', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(971, 'C071', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(972, 'C086', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(975, 'C043', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(976, 'C050', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(977, 'C057', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(978, 'C077', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(979, 'C084', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(982, 'C049', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(983, 'C078', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(984, 'C088', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(985, 'C093', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(986, 'C101', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(989, 'C040', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(990, 'C072', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(991, 'C087', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no'),
(992, 'C102', 'REPEATED_CASE', 'Repeated case type', '2026-02-12 06:19:50', 'no');

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
  `approved_at` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`, `status`, `approved_by`, `approved_at`, `reset_token`, `reset_expires`) VALUES
(2, 'Justice Sharma', 'judge@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'judge', '2026-01-18 11:24:39', 'approved', NULL, NULL, NULL, NULL),
(3, 'Clerk Kumar', 'clerk@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'clerk', '2026-01-18 11:24:39', 'approved', NULL, NULL, NULL, NULL),
(4, 'Lawyer Verma', 'lawyer@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'lawyer', '2026-01-18 11:24:39', 'pending', NULL, NULL, NULL, NULL),
(5, 'Analyst Singh', 'analyst@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'analyst', '2026-01-18 11:24:39', 'pending', NULL, NULL, 'a9f2a57f194f387431f4ed81acafab62', '2026-02-12 10:57:14'),
(6, 'System Admin', 'admin@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'admin', '2026-02-04 05:00:24', 'approved', 6, '2026-02-05 17:36:42', NULL, NULL),
(7, 'Justice Ramesh', 'judge3@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'judge', '2026-02-04 11:35:50', 'approved', NULL, NULL, NULL, NULL),
(8, 'Clerk Anjali', 'clerk3@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'clerk', '2026-02-04 11:35:50', 'pending', NULL, NULL, NULL, NULL),
(9, 'Lawyer Arjun', 'lawyer3@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'lawyer', '2026-02-04 11:35:50', 'approved', NULL, NULL, NULL, NULL),
(10, 'Analyst Priya', 'analyst3@court.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'analyst', '2026-02-04 11:35:50', 'approved', NULL, NULL, '6ae7ca18f9553abccfb302d61c158cdea05947f1176056d55713b4f93cecbaf28baeefbfa72ea5ba2aef27ebfde0d7dc98a6', '2026-02-12 09:21:01'),
(12, 'aaaa', 'aaaa@gmail.com', '$2y$10$BdchIpTEDyHB9AFfLuJu1uur0T1EOCGJNuARZoSGsTmeg.iFhsr0a', 'lawyer', '2026-02-06 09:16:15', 'rejected', NULL, NULL, NULL, NULL),
(13, 'dfdfdfd', 'vrajshah@gmail.com', '$2y$10$OpZUusAwBJxdL6CghnXOZeV.ZrJGLDJq4APZJbxo1n7aoOT5ei0Qm', 'analyst', '2026-02-12 04:40:27', 'approved', NULL, NULL, NULL, NULL),
(14, 'prashant', 'prashantharkhani2424@gmail.com', '$2y$10$barpT3RU8oaD1A3Ph7ZIuuvfVdFtTD/bC/zEDa/IoFsC6x/XhNKjm', 'analyst', '2026-02-12 08:58:31', 'approved', NULL, NULL, NULL, NULL);

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
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`doc_id`);

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
  ADD UNIQUE KEY `unique_case_flag` (`case_id`,`flag_type`),
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
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pattern_flags`
--
ALTER TABLE `pattern_flags`
  MODIFY `flag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=993;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
