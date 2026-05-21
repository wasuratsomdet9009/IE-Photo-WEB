-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2026 at 11:12 PM
-- Server version: 10.6.13-MariaDB
-- PHP Version: 8.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iephotoo_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_type` enum('equipment','studio') NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_email` varchar(100) DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `form_image_path` varchar(255) DEFAULT NULL,
  `usage_reason` text DEFAULT NULL,
  `usage_type` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','rejected','returned','cancelled','pending_return') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `consent_token` varchar(64) DEFAULT NULL,
  `consent_responded_at` timestamp NULL DEFAULT NULL,
  `responsible_user_id` int(11) DEFAULT NULL,
  `return_image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_consents`
--

CREATE TABLE `email_consents` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `action` enum('approve','reject') NOT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('camera','lens','accessory') NOT NULL,
  `status` enum('available','borrowed','maintenance') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `name`, `type`, `status`) VALUES
(1, 'a7', 'camera', 'available'),
(2, 'หี', 'accessory', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE `feeds` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_likes`
--

CREATE TABLE `feed_likes` (
  `id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studios`
--

CREATE TABLE `studios` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studios`
--

INSERT INTO `studios` (`id`, `name`, `status`) VALUES
(1, 'Studio 1', 'open'),
(2, 'Studio 2', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `due_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urgent_contacts`
--

CREATE TABLE `urgent_contacts` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('calling','acknowledged','resolved') NOT NULL DEFAULT 'calling',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `urgent_contacts`
--

INSERT INTO `urgent_contacts` (`id`, `sender_id`, `receiver_id`, `status`, `created_at`) VALUES
(1, 2, 1, 'resolved', '2026-03-22 15:21:11'),
(2, 3, 2, 'resolved', '2026-03-23 03:01:37'),
(3, 2, 3, 'resolved', '2026-03-23 04:10:43'),
(4, 4, 2, 'resolved', '2026-05-13 06:22:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_id` varchar(8) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('admin','member','guest') NOT NULL DEFAULT 'member',
  `profile_image` varchar(255) DEFAULT 'default.png',
  `contact_status` enum('available','busy','offline') NOT NULL DEFAULT 'offline',
  `profile_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_id`, `email`, `password`, `phone`, `role`, `profile_image`, `contact_status`, `profile_completed`, `created_at`) VALUES
(1, '68030263', '68030263@kmitl.ac.th', '$2y$10$Wbkphhq8WMthauwRdsXorO9MZwemApY6MLQ8b2z2xSA8TtqePuCbq', '0830334694', 'admin', '1_1774178934.png', 'offline', 1, '2026-03-22 11:28:34'),
(2, '68030266', '68030266@kmitl.ac.th', '$2y$10$Sg5EJz/dmhRqSnEVI6NEleuEN8l3pZWDJ6eDsRwS3EKe.HifnBFai', '0830334694', 'member', 'default.png', 'offline', 1, '2026-03-22 11:58:17'),
(3, '68030262', '68030262@kmitl.ac.th', '$2y$10$m9v8m.iSyi1Jyx/W25Y4dex22JY2fliJbB7nR8nGyOh7A7vcCGpg6', '0632212946', 'member', '3_1774234794.jpeg', 'offline', 1, '2026-03-23 02:59:25'),
(4, '68030260', '68030260@kmitl.ac.th', '$2y$10$yOncl29O6IdIsWrHYadIo.9KbJ3b8SwpdfzGWPgRrar3bxHj5sxjy', '0830334694', 'member', 'default.png', 'offline', 1, '2026-05-13 06:11:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `email_consents`
--
ALTER TABLE `email_consents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `feed_likes`
--
ALTER TABLE `feed_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feed_id` (`feed_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_by` (`assigned_by`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `urgent_contacts`
--
ALTER TABLE `urgent_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `email_consents`
--
ALTER TABLE `email_consents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feed_likes`
--
ALTER TABLE `feed_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `studios`
--
ALTER TABLE `studios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `urgent_contacts`
--
ALTER TABLE `urgent_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `email_consents`
--
ALTER TABLE `email_consents`
  ADD CONSTRAINT `email_consents_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feeds`
--
ALTER TABLE `feeds`
  ADD CONSTRAINT `feeds_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feed_likes`
--
ALTER TABLE `feed_likes`
  ADD CONSTRAINT `feed_likes_ibfk_1` FOREIGN KEY (`feed_id`) REFERENCES `feeds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feed_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `urgent_contacts`
--
ALTER TABLE `urgent_contacts`
  ADD CONSTRAINT `urgent_contacts_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `urgent_contacts_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
