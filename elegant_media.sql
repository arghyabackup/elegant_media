-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2022 at 04:51 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elegant_media`
--

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(100) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('0','1','2','3','4') NOT NULL DEFAULT '0' COMMENT '''0''=>''New'', ''1''=>''Open'', ''2''=>''Close'', ''3''=>''Resolve'', ''4''=>''Cancel''',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '	''0'' => ''Not Deleted'', ''1'' => ''Deleted''',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `reference_no`, `customer_name`, `email`, `phone`, `description`, `status`, `is_deleted`, `created_at`, `update_at`) VALUES
(1, '86868735', 'Uttam Das', 'uttam@gmail.com', '8714690221', 'Testing', '2', '0', '2022-02-02 15:47:26', '2022-02-02 15:48:08'),
(2, '65592622', 'Rajib Pal', 'pal@gmail.com', '8871446789', 'Testing good', '2', '0', '2022-02-02 15:48:51', '2022-02-02 15:49:45');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_notes`
--

CREATE TABLE `ticket_notes` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '	0=>Inactive, 1=>Active',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '	''0'' => ''Not Deleted'', ''1'' => ''Deleted''',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ticket_notes`
--

INSERT INTO `ticket_notes` (`id`, `ticket_id`, `agent_id`, `description`, `status`, `is_deleted`, `created_at`, `update_at`) VALUES
(1, 1, 1, 'Good Test', '1', '0', '2022-02-02 15:48:08', '2022-02-02 15:48:08'),
(2, 2, 2, 'Cche csssds sdsd', '1', '0', '2022-02-02 15:49:45', '2022-02-02 15:49:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=>Inactive, 1=>Active',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '	''0'' => ''Not Deleted'', ''1'' => ''Deleted''',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `phone`, `status`, `is_deleted`, `created_at`, `update_at`) VALUES
(1, 'Arghya Mitra', 'arghya', '$2y$10$mamNJii59RCAsk2o7pdhVOnYZvLs.yGViHng9QrAPFITmaxqvULFG', 'arghya@gmail.com', '1231221211', '1', '0', '2022-02-02 15:44:51', '2022-02-02 15:44:51'),
(2, 'Raja Dutta', 'raja', '$2y$10$cxiOqcCFpzGQUe4OF.V8kuQrUKzPVBufIFfPUk6wunebE19Mh3kZm', 'raja@gmail.com', '2312221223', '1', '0', '2022-02-02 15:46:19', '2022-02-02 15:46:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_notes`
--
ALTER TABLE `ticket_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ticket_notes`
--
ALTER TABLE `ticket_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
