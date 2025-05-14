-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 04:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `queueingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `id` int(11) NOT NULL,
  `queue_number` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('landtax','backroom','examiner','businesstax','payment','fireprotection','releasing') NOT NULL DEFAULT 'landtax',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `processing_by` varchar(11) DEFAULT '',
  `position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`id`, `queue_number`, `name`, `reason`, `status`, `created_at`, `updated_at`, `processing_by`, `position`) VALUES
(4402, 1, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:14', '2025-05-05 01:50:03', 'admin', 1),
(4403, 2, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:16', '2025-04-30 02:48:16', '', 2),
(4404, 3, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:18', '2025-04-30 02:48:18', '', 3),
(4405, 4, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:23', '2025-04-30 02:48:23', '', 4),
(4406, 5, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:26', '2025-04-30 02:48:26', '', 5),
(4407, 6, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:28', '2025-04-30 02:48:28', '', 6),
(4408, 7, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:30', '2025-04-30 02:48:30', '', 7),
(4409, 8, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:32', '2025-04-30 02:48:32', '', 8),
(4410, 9, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:38', '2025-04-30 02:48:38', '', 9),
(4411, 10, 'PEDE PA', 'Social Security System', 'landtax', '2025-04-30 02:48:41', '2025-04-30 02:48:41', '', 10),
(4414, 11, 'asdsa', 'Social Security System', 'landtax', '2025-05-01 12:28:23', '2025-05-01 12:28:23', '', 11),
(4416, 12, 'asdas', 'Social Security System', 'landtax', '2025-05-05 01:42:38', '2025-05-05 01:42:38', '', 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(10, 'admin', '$2y$10$sKStNWzj/ML5yPEhmDuFOu2U4sCqfNu/dmTp4YLFw2jN9ZFLXVH/2', 'admin'),
(11, 'superadmin1', '$2y$10$htCKK8aai2i1.l5DsQeCO.S50JAfm/Knu6wx5.l.pjN6zMXifZuay', 'superadmin'),
(12, 'ivan', '$2y$10$FUCq5Q6Nn1cfdbxu98rFUe.LUG7dwULoYsta6rI9/DTrAzsiFe4uK', 'landtax'),
(13, 'richmond', '$2y$10$igXs1Bt7UhbZLX0GMSMQg.haYlCnkSDOJ7pw1SuKOr/PPOtC3n0bi', 'backroom'),
(15, 'beia', '$2y$10$2lRI0Px6M9p6/kGXMc2.bOrutw6TfFgwtug7LVqIfMKS//TKd8ppa', 'landtax'),
(16, 'shan', '$2y$10$OdU8Ay4YZWW1fGnln0QxceUJ2CcxRojj3T2nqCpV90Af/5XGrHQuK', 'backroom'),
(17, 'trina', '$2y$10$.jQk0gok9811/tZ4jsm3J.oPnCpUt.hFRpJh7S3DwrdBvvcByq6T2', 'examiners'),
(18, 'johaina', '$2y$10$8dyOP36gnPCvVR.LyFjKAO.znf7O6lG3QWryaUztQf60A7UTYbVSK', 'examiners'),
(19, 'jc', '$2y$10$t13PFdkLtAMsTn8YIIUfhesoM3uqfhO/lCvyM.23VPREoiN04rJxq', 'businesstax'),
(20, 'gerome', '$2y$10$UdmaIsPXqtuwj9POl4ScyOT4vR3RizkpUCvId.DReXveYVPHUC63m', 'businesstax'),
(21, 'kleng', '$2y$10$7/dMMuz7OcaerLmXvEW/UeYEi3rQz1Fis0GZrqOr8nSpGA.zouoB6', 'payment'),
(22, 'anygma', '$2y$10$fugeRxeOChVIe7VHycvU2eKJH34cxsvrJ2.KnCNp1U2Bn.ClIs7t2', 'payment'),
(23, 'vince', '$2y$10$rbaWvejGl/SXwFh1ens16uGd.Zz4Dbw6/xMq0KfVgoPdv7u5lsTKm', 'fireprotection'),
(24, 'yman', '$2y$10$gw6A6aT2PwRuosCgshm17uOr0kvfxvm6zFTd9/i2DJ7ruK4N8q.S2', 'fireprotection'),
(25, 'joy', '$2y$10$vZa1HHnkekVzhvHRWlr/v.jY6gDX1TfQholiZhThy0es5jUPTn0Im', 'releasing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
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
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4417;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
