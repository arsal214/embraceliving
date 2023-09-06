-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2021 at 09:34 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magicmoments`
--

-- --------------------------------------------------------

--
-- Table structure for table `mmt_newsletters`
--

CREATE TABLE `mmt_newsletters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `week` enum('CURRENT','NEXT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mmt_newsletters`
--

INSERT INTO `mmt_newsletters` (`id`, `group_id`, `file_name`, `file_category`, `week`, `created_at`, `updated_at`) VALUES
(1, 1, 'online_content.pdf', 'Our Yesterday', 'CURRENT', '2021-12-01 17:52:52', '2021-12-01 15:10:15'),
(2, 1, 'our_yesterday.pdf', 'Online Content Notes', 'CURRENT', '2021-12-01 17:53:23', '2021-12-01 15:10:49'),
(3, 1, '', 'Current Calendar', 'CURRENT', '2021-12-01 17:53:23', '2021-12-01 20:08:34'),
(4, 1, 'weekly_printables.pdf', 'Weekly Printables', 'CURRENT', '2021-12-01 17:53:39', '2021-12-01 15:10:26'),
(5, 1, '', 'Our Yesterday', 'NEXT', '2021-12-01 17:53:59', '2021-12-01 20:08:41'),
(6, 1, '', 'Online Content Notes', 'NEXT', '2021-12-01 17:54:21', '2021-12-01 17:54:21'),
(7, 1, '', 'Current Calendar', 'NEXT', '2021-12-01 17:54:21', '2021-12-01 17:54:21'),
(8, 1, '', 'Weekly Printables', 'NEXT', '2021-12-01 17:54:44', '2021-12-01 17:54:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mmt_newsletters`
--
ALTER TABLE `mmt_newsletters`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mmt_newsletters`
--
ALTER TABLE `mmt_newsletters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
