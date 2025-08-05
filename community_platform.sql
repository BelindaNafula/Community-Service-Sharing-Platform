-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2025 at 09:24 AM
-- Server version: 8.0.36
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `community_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$0baWBwStCe7Xffq8famZPOwncauplHq9AeCpry3rWD6yR99Qx1FEa');

-- --------------------------------------------------------

--
-- Table structure for table `provider_profiles`
--

CREATE TABLE `provider_profiles` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `contact_info` text,
  `bio` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_services`
--

CREATE TABLE `provider_services` (
  `id` int NOT NULL,
  `provider_id` int NOT NULL,
  `service_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `provider_services`
--

INSERT INTO `provider_services` (`id`, `provider_id`, `service_id`) VALUES
(1, 1, 3),
(2, 1, 2),
(3, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'PLUMBING', NULL, '2025-07-16 12:42:04'),
(2, 'TUITORING', NULL, '2025-07-16 12:42:21'),
(3, 'CLEANING', NULL, '2025-07-16 12:42:53'),
(7, 'ELECTRICAL', NULL, '2025-07-17 14:52:14');

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `bio` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `name`, `password`, `contact_info`, `location`, `bio`, `created_at`) VALUES
(1, 'dave', '$2y$10$Yqw1CSpqXiq1.JLCSGL2/OyU706ya.kikEv1Z9QSENYAqpSQl4Q0i', NULL, 'NAIROBI', NULL, '2025-07-15 21:21:56'),
(2, 'dave', '$2y$10$jXcQob1CQis7h651rjuituZcJ8q.NfXXoItaa1/24QTAhmVMnghs2', NULL, 'NAIROBI', NULL, '2025-07-15 21:34:27'),
(3, 'LAMECK', '$2y$10$87vSpj9BWTy.REAFv7wqXeh/TNHDGIdi41fTAmGJgrj8t6.l.Ye42', NULL, 'NAIROBI', NULL, '2025-07-18 14:35:45'),
(4, 'mercy', '$2y$10$gBcD1/Dl54FOfo/1gQUoYeigLK347g3hZQuaQ0XpU5woFOGuB8kGO', NULL, 'NAIROBI', NULL, '2025-07-19 03:59:27'),
(5, 'bells', '$2y$10$TNX3A.d4Ds53mfloppqwl.SzesdiYzz4.LAuE1F8Hw2mIJjpWIhkG', NULL, 'nakuru', NULL, '2025-07-19 18:43:18'),
(6, 'Cathy', '$2y$10$BVLQAQkdNEimb8KFSBmpJOfpfwsAwxe3mVFiXZGPyjb7b1jC/SVum', NULL, 'NAIROBI', NULL, '2025-07-25 06:05:36');

-- --------------------------------------------------------

--
-- Table structure for table `service_registrations`
--

CREATE TABLE `service_registrations` (
  `id` int NOT NULL,
  `provider_id` int NOT NULL,
  `service_id` int NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `second_name` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `experience` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `service_registrations`
--

INSERT INTO `service_registrations` (`id`, `provider_id`, `service_id`, `first_name`, `second_name`, `location`, `contact`, `email`, `experience`, `created_at`) VALUES
(1, 3, 1, 'LAMECK', 'NOAH', 'NAIROBI', '0799912959', 'angela@gmail.com', '3', '2025-07-18 15:44:06'),
(6, 1, 7, 'dave', 'david', 'NAIROBI', '0799912959', 'llyntri@gmail.com', '4', '2025-07-18 17:54:54'),
(10, 5, 7, 'bells', 'belinda', 'nakuru', '0799912959', 'belindanafula992@gmail.com', '5 years', '2025-07-19 18:44:41'),
(11, 5, 3, 'bells', 'belinda', 'kisumu', '0799912959', 'belindanafula992@gmail.com', '2 years', '2025-07-19 18:45:24'),
(12, 6, 2, 'Cathy', 'Catherine', 'NAIROBI', '0100233456', 'angela@gmail.com', '3 years', '2025-07-25 06:07:22'),
(13, 4, 3, 'mercy', 'susan', 'NAIROBI', '0703213018', 'aria00@gmail.com', '4 years', '2025-07-25 06:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `request_id` int NOT NULL,
  `user_id` int NOT NULL,
  `provider_id` int NOT NULL,
  `service_id` int NOT NULL,
  `message` text NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `location` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `location`, `created_at`) VALUES
(1, 'admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFx4j8rA7z7F35u3/YyC6H8Ueg3wNkmi', '', '2025-07-14 13:46:52'),
(4, 'rain', '$2y$10$Y.AISq1wjHguYCvxJMaNzOHdikMMQnsvX1GNfRfnLPNWEIpg2AamS', 'NAIROBI', '2025-07-15 22:07:08'),
(5, 'Faith', '$2y$10$fSoAkPtOVn1w8VpUCzINheHYNCYGqsTtmVAwKTcQFtOz9Cyf6LhJG', 'NAIROBI', '2025-07-16 13:40:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `provider_services`
--
ALTER TABLE `provider_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_registrations`
--
ALTER TABLE `service_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_services`
--
ALTER TABLE `provider_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_registrations`
--
ALTER TABLE `service_registrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `request_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `provider_profiles`
--
ALTER TABLE `provider_profiles`
  ADD CONSTRAINT `provider_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `provider_services`
--
ALTER TABLE `provider_services`
  ADD CONSTRAINT `provider_services_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `service_requests_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`),
  ADD CONSTRAINT `service_requests_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
