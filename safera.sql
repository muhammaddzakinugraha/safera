-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2025 at 02:49 AM
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
-- Database: `safera`
--

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_tiket`
--

CREATE TABLE `pembelian_tiket` (
  `id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','success','failed','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ticket_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian_tiket`
--

INSERT INTO `pembelian_tiket` (`id`, `order_id`, `user_id`, `phone`, `total_amount`, `status`, `created_at`, `updated_at`, `ticket_type_id`) VALUES
(40, 'ORD-678778b0e54c8', 14, '838 9034 4214', 12233.00, 'pending', '2025-01-15 08:58:24', '2025-01-15 08:58:24', 35);

-- --------------------------------------------------------

--
-- Table structure for table `tipe_tiket`
--

CREATE TABLE `tipe_tiket` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stok` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipe_tiket`
--

INSERT INTO `tipe_tiket` (`id`, `name`, `price`, `description`, `created_at`, `updated_at`, `stok`) VALUES
(35, 'siswa - (3 hari)', 12233.00, 'tiket siswa', '2025-01-15 07:17:54', '2025-01-17 01:27:26', 200);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(14, 'ff', 'f@gnail.com', '$2y$10$k46BdnU/iZzZlVU0/9cKkepvFEdiNsvQDNSag2xXHhmRn2hoeqeaW', '2025-01-15 01:25:31', 'user'),
(15, 'irfan', '1@gmail.com', '$2y$10$IbFuhaS4X9sGHdZrHJ8GJewtt3TdRH4XdGFmVRPZX4ptF4sFUHDuO', '2025-01-16 19:16:11', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pembelian_tiket`
--
ALTER TABLE `pembelian_tiket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_ticket_type` (`ticket_type_id`);

--
-- Indexes for table `tipe_tiket`
--
ALTER TABLE `tipe_tiket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pembelian_tiket`
--
ALTER TABLE `pembelian_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tipe_tiket`
--
ALTER TABLE `tipe_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembelian_tiket`
--
ALTER TABLE `pembelian_tiket`
  ADD CONSTRAINT `fk_ticket_type` FOREIGN KEY (`ticket_type_id`) REFERENCES `tipe_tiket` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pembelian_tiket_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
