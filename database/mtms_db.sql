-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2024 at 12:55 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mtms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch_list`
--

CREATE TABLE `branch_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branch_list`
--

INSERT INTO `branch_list` (`id`, `name`, `address`, `status`, `date_created`) VALUES
(1, 'Branch 101', 'Sample Address', 1, '2021-10-28 10:05:08'),
(2, 'Branch 102', 'Sample Address 2', 1, '2021-10-28 10:05:19'),
(3, 'Branch 103', 'Sample Address 3', 1, '2021-10-28 10:05:33'),
(5, 'Branch 104', '', 1, '2024-01-05 00:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `fee_list`
--

CREATE TABLE `fee_list` (
  `id` int(30) NOT NULL,
  `amount_from` float NOT NULL DEFAULT 0,
  `amount_to` float NOT NULL DEFAULT 0,
  `fee` float NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fee_list`
--

INSERT INTO `fee_list` (`id`, `amount_from`, `amount_to`, `fee`, `date_created`) VALUES
(1, 0.01, 500, 10, '2021-10-28 10:51:15'),
(2, 501, 1500, 15, '2021-10-28 10:51:54'),
(3, 1501, 3000, 25, '2021-10-28 10:52:17'),
(4, 3001, 1000000000, 100, '2021-10-28 10:52:54');

-- --------------------------------------------------------

--
-- Table structure for table `otp_record`
--

CREATE TABLE `otp_record` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `otp` mediumint(9) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otp_record`
--

INSERT INTO `otp_record` (`id`, `user_id`, `otp`, `status`) VALUES
(9, 24, 246620, 0);

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Mudan International Money Transfer'),
(6, 'short_name', 'M.I.M.T'),
(11, 'logo', 'uploads/logo-1704399613.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1635386199.png');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_list`
--

CREATE TABLE `transaction_list` (
  `id` int(30) NOT NULL,
  `tracking_code` varchar(50) NOT NULL,
  `branch_id` int(30) DEFAULT NULL,
  `sending_amount` float NOT NULL DEFAULT 0,
  `fee` float NOT NULL DEFAULT 0,
  `purpose` text DEFAULT NULL,
  `user_id` int(30) DEFAULT NULL,
  `send_to` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `transection_type` enum('sent','received') NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_meta`
--

CREATE TABLE `transaction_meta` (
  `transaction_id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(13) DEFAULT NULL,
  `email` text NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 0,
  `branch_id` int(30) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `phone`, `email`, `balance`, `branch_id`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatar-1.png?v=1704399643', NULL, 1, '123456789', '', 0, NULL, '2021-01-20 14:02:37', '2024-01-05 01:20:43'),
(24, 'shoaib', 'iqbal', 'shaibi', '218131b2d5c4e609e1b38c2047ac406b', 'uploads/avatar-24.png?v=1704488092', NULL, 2, '123456789', 'shaibi3036@gmail.com', 0, 1, '2024-01-06 01:47:38', '2024-01-06 01:54:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch_list`
--
ALTER TABLE `branch_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_list`
--
ALTER TABLE `fee_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_record`
--
ALTER TABLE `otp_record`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_list`
--
ALTER TABLE `transaction_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sent_to` (`send_to`),
  ADD KEY `send_to` (`send_to`),
  ADD KEY `send_to_2` (`send_to`);

--
-- Indexes for table `transaction_meta`
--
ALTER TABLE `transaction_meta`
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch_list`
--
ALTER TABLE `branch_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fee_list`
--
ALTER TABLE `fee_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `otp_record`
--
ALTER TABLE `otp_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transaction_list`
--
ALTER TABLE `transaction_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `otp_record`
--
ALTER TABLE `otp_record`
  ADD CONSTRAINT `otp_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction_list`
--
ALTER TABLE `transaction_list`
  ADD CONSTRAINT `transaction_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaction_list_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branch_list` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaction_list_ibfk_3` FOREIGN KEY (`send_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction_meta`
--
ALTER TABLE `transaction_meta`
  ADD CONSTRAINT `transaction_meta_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch_list` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
