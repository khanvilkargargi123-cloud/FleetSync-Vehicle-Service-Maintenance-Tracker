-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2026 at 02:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fleetsync`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `vehicle_type` varchar(30) DEFAULT NULL,
  `km_reading` int(11) DEFAULT NULL,
  `service_type` varchar(100) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `last_service` date DEFAULT NULL,
  `next_service` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'Pending',
  `service_stage` varchar(50) NOT NULL DEFAULT 'Booking Confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reminder_sent` tinyint(1) NOT NULL DEFAULT 0,
  `payment_method` varchar(30) DEFAULT NULL,
  `payment_status` varchar(30) DEFAULT 'Pending',
  `payment_id` varchar(100) DEFAULT NULL,
  `service_completed_date` date DEFAULT NULL,
  `service_amount` decimal(10,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_email`, `customer_name`, `mobile`, `vehicle_number`, `brand`, `model`, `vehicle_type`, `km_reading`, `service_type`, `booking_date`, `booking_time`, `last_service`, `next_service`, `notes`, `image`, `status`, `service_stage`, `created_at`, `reminder_sent`, `payment_method`, `payment_status`, `payment_id`, `service_completed_date`, `service_amount`, `payment_date`) VALUES
(54, 'khanvilkarvaishnavi760@gmail.com', 'vaishnavi', '9359414464', 'Mh12AG64567', 'tata', '2018', 'Car', 358, 'General Service', '2026-07-16', '11:00:00', '2026-08-17', NULL, '\r\n', '', 'Confirmed', 'Booking Confirmed', '2026-08-11 15:20:03', 0, 'Cash', 'Paid', NULL, NULL, 6000.00, '2026-08-14'),
(69, 'khanvilkargargi123@gmail.com', 'Gargi', '9359760274', 'MHGA081805', 'SUV', '2025', 'Bike', 120, 'General Service', '2026-08-20', '11:00:00', '2026-08-20', '2026-08-15', '\r\n', '', 'Completed', 'Booking Confirmed', '2026-08-13 14:06:42', 1, 'Cash', 'Pending', NULL, '2026-08-13', 12000.00, NULL),
(70, 'khanvilkargargi123@gmail.com', 'Gargi', '9359760274', 'MHGA081805', 'SUV', '2025', 'Car', 120, 'General Service', '2026-08-14', '11:00:00', '2026-08-14', NULL, '\r\n', '', 'Confirmed', 'Booking Confirmed', '2026-08-14 14:52:57', 0, 'Card', 'Paid', NULL, NULL, 6000.00, '2026-08-14'),
(71, 'khanvilkargargi123@gmail.com', 'Gargi', '9359760274', 'MHGA081805', 'SUV', '2025', 'Car', 120, 'General Service', '2026-08-14', '11:00:00', '2026-08-14', NULL, '\r\n', '', 'Confirmed', 'Booking Confirmed', '2026-08-14 15:27:15', 0, 'UPI', 'Paid', NULL, NULL, 0.00, '2026-08-14'),
(72, 'khanvilkargargi123@gmail.com', 'Gargi', '9359760274', 'MHGA081805', 'SUV', '2025', 'Bike', 120, 'Oil Change', '2026-08-14', '11:00:00', '2026-08-14', NULL, '\r\n', '', 'Confirmed', 'Booking Confirmed', '2026-08-14 15:57:35', 0, 'Cash', 'Pending', NULL, NULL, 0.00, '2026-08-14'),
(73, 'khanvilkargargi123@gmail.com', 'Gargi', '9359760274', 'MHGA081805', 'SUV', '2025', 'SUV', 120, 'Battery Check', '2026-08-14', '17:00:00', '2026-08-14', NULL, '\r\n', '', 'Confirmed', 'Booking Confirmed', '2026-08-14 16:07:24', 0, 'Card', 'Pending', NULL, NULL, 0.00, NULL),
(74, 'khanvilkarvaishnavi760@gmail.com', 'vaishnavi', '9359414464', 'MH12AJ1408', 'TVS', '2018', 'Bike', 40, 'General Service', '2026-07-28', '11:00:00', '2026-01-07', NULL, '\r\n', '', 'Pending', 'Booking Confirmed', '2026-08-16 11:32:10', 0, 'Cash', 'Pending', NULL, NULL, 0.00, NULL),
(75, 'khanvilkarapurva85@gmail.com', 'shubhra', '9359414464', 'Mh12AG64567', 'tata', 'model-21', 'Car', 40, 'General Service', '2026-07-30', '14:00:00', '2026-01-29', NULL, '\r\n', '', 'Pending', 'Booking Confirmed', '2026-08-16 11:49:29', 0, 'UPI', 'Pending', NULL, NULL, 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `vehicle` varchar(30) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `fullname`, `email`, `phone`, `vehicle`, `subject`, `message`, `created_at`) VALUES
(1, 'Gargi khanvilkar', 'khanvilkargargi123@gmail.com', '9359760274', 'MH12AJ1408', 'Service Enquiry', 'I want to know about vehicle servicing.', '2026-08-04 14:01:29'),
(2, 'Gargi khanvilkar', 'khanvilkargargi123@gmail.com', '9359760274', 'MH12AJ1408', 'Service Enquiry', 'Nothing.', '2026-08-04 14:03:08'),
(3, 'Gargi khanvilkar', 'khanvilkargargi123@gmail.com', '9359760274', 'MH12AJ1408', 'Service Enquiry', 'I want Services Early.', '2026-08-07 15:44:09'),
(4, 'Gargi khanvilkar', 'khanvilkargargi123@gmail.com', '9359760274', 'MH12AJ1408', 'Service Enquiry', 'I want ez\r\narly.', '2026-08-07 15:53:58'),
(5, 'Gargi khanvilkar', 'khanvilkargargi123@gmail.com', '9359760274', 'MH12AJ1408', 'Service Enquiry', 'I want it early.', '2026-08-08 15:56:19'),
(6, 'Gargi khanvilkar', 'khanvilkargargi123@gmail.com', '9359760274', 'MH12AJ1408', 'Service Enquiry', 'Service is best.', '2026-08-15 18:08:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `created_at`, `address`, `profile_photo`) VALUES
(1, 'gargi khanvilkar', 'khanvilkargargi1123@gmail.com', '9359760274', '1234', '2026-07-20 14:40:58', 'khanvilkargargi1123@gmail.com', NULL),
(2, 'vedika', 'khanvilkarvedika1123@gmail.com', 'khanvilkarvedik', '123', '2026-07-29 13:49:32', 'Lanja', 'profile_uploads/profile_6a7b53191d483.jpg'),
(3, 'prajval', 'khanvilkarrprajval567@gmail.com', '9423297842', '45', '2026-07-29 14:16:23', 'Lanja', NULL),
(4, 'vaishnavi', 'khanvilkarvaishnavi760@gmail.com', '9359414464', '1234', '2026-07-29 14:41:16', 'At.post.kuve', 'profile_uploads/profile_6a7dbff08ae07.jpg'),
(5, 'Gargi khanvilkar', 'khanvilkargargi123@gmail.com', '9359760274', '1234', '2026-08-03 14:59:27', 'Ratnagiri', 'profile_uploads/profile_6a7ed71baf902.jpg'),
(6, 'Sanika khanvilkar', 'sanikakhanvilkar03@gmail.com', '8208501550', '1234', '2026-08-03 15:07:37', 'Ratnagiri', NULL),
(7, 'prajval khanvilkar ', 'prajvalkhanvilkar@gmail.com', '9860459687', '123', '2026-08-08 15:58:23', 'At post kuve', 'profile_uploads/profile_6a7b2dcb4943b.jpg'),
(8, 'Shubhra', 'khanvilkarapurva85@gmail.com', '9359760274', '123', '2026-08-16 11:43:48', 'At post kuve', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `vehicle_brand` varchar(50) NOT NULL,
  `vehicle_model` varchar(50) NOT NULL,
  `fuel_type` varchar(20) NOT NULL,
  `manufacturing_year` year(4) NOT NULL,
  `vehicle_color` varchar(30) NOT NULL,
  `last_service` date DEFAULT NULL,
  `next_service` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_email`, `vehicle_number`, `vehicle_brand`, `vehicle_model`, `fuel_type`, `manufacturing_year`, `vehicle_color`, `last_service`, `next_service`, `created_at`) VALUES
(1, 'khanvilkarivedika123@gmail.com', 'Mh12AJ8907', 'cv', 'fgj', 'Petrol', '2024', 'white', NULL, NULL, '2026-07-27 15:09:52'),
(2, 'khanvilkarivedika123@gmail.com', 'Mh12AJ8907', 'cv', 'fgj', 'Petrol', '2024', 'white', NULL, NULL, '2026-07-27 15:41:44'),
(3, 'khanvilkarivedika123@gmail.com', 'Mh12AJ8907', 'cv', 'fgj', 'Petrol', '2024', 'white', NULL, NULL, '2026-07-27 15:43:21'),
(4, 'khanvilkarivedika123@gmail.com', 'Mh12AJ8907', 'cv', 'fgj', 'Petrol', '2024', 'white', NULL, NULL, '2026-07-27 16:05:50'),
(5, 'khanvilkargargi1123@gmail.com', 'Mh12AG64567', 'TATA', 'model-21', 'Petrol', '2024', 'Red', NULL, NULL, '2026-07-27 16:19:43'),
(6, 'khanvilkarvaishnavi760@gmail.com', 'MH08VV1405', 'Rollce royale', 'Ratnagiri', 'Petrol', '2030', 'Black', NULL, NULL, '2026-07-29 14:44:27'),
(7, 'khanvilkarrprajval567@gmail.com', 'Mh12AG64567', 'Rollce royale', 'Ratnagiri', 'Petrol', '2023', 'Black', NULL, NULL, '2026-08-02 14:29:29'),
(8, 'khanvilkargargi123@gmail.com', 'Mh12AG64567', 'cv', 'Ratnagiri', 'Petrol', '2024', 'Red', NULL, NULL, '2026-08-14 16:51:09'),
(9, 'khanvilkarapurva85@gmail.com', 'Mh12AG64567', 'TATA', 'model-21', 'Petrol', '2022', 'Black', NULL, NULL, '2026-08-16 11:46:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
