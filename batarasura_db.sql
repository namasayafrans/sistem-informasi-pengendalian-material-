-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 01:11 PM
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
-- Database: `batarasura_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_material`
--

CREATE TABLE `data_material` (
  `id` int(11) NOT NULL,
  `nama_material` varchar(100) NOT NULL,
  `no_part` varchar(50) NOT NULL,
  `material_spec` varchar(100) DEFAULT NULL,
  `material_size` varchar(50) DEFAULT NULL,
  `standard` varchar(50) DEFAULT NULL,
  `satuan` enum('gr','pcs','ml') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_material`
--

INSERT INTO `data_material` (`id`, `nama_material`, `no_part`, `material_spec`, `material_size`, `standard`, `satuan`, `created_at`) VALUES
(1, 'COVER PLATE', '5020-010-0302', 'SECEN-P', '0,6t x 1219 x Coil', '57,85', 'gr', '2026-02-03 14:58:14'),
(2, 'Housing', '5030-010-0001', 'SECEN-P', '0,6t x 1219 x Coil', '167,50', 'gr', '2026-02-05 15:22:28'),
(3, 'END PLATE A', '5030-010-0103', 'SECC-P', '0,4t x 1219 x Coil', '23,80', 'gr', '2026-02-05 15:34:59'),
(4, 'END PLATE B', '5030-010-0103', 'SECC-P', '0,6t x 1219 x Coil', '23,80', 'gr', '2026-02-05 15:40:37');

-- --------------------------------------------------------

--
-- Table structure for table `stok_material`
--

CREATE TABLE `stok_material` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `stok_sekarang` int(11) DEFAULT 0,
  `stok_minimum` int(11) DEFAULT 10,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_material`
--

INSERT INTO `stok_material` (`id`, `material_id`, `stok_sekarang`, `stok_minimum`, `last_update`) VALUES
(9, 1, 40, 10, '2026-02-12 15:13:38'),
(10, 3, 50, 10, '2026-02-12 15:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `stok_mutasi`
--

CREATE TABLE `stok_mutasi` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `tipe` enum('masuk','keluar') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_mutasi`
--

INSERT INTO `stok_mutasi` (`id`, `material_id`, `tipe`, `jumlah`, `tanggal`, `keterangan`, `created_at`) VALUES
(19, 1, 'masuk', 50, '2026-02-12', '', '2026-02-12 15:13:13'),
(20, 1, 'masuk', -10, '2026-02-12', 'Koreksi Stok (Manual Upgrade)', '2026-02-12 15:13:38'),
(21, 3, 'masuk', 60, '2026-02-12', '', '2026-02-12 15:14:06'),
(22, 3, 'masuk', -10, '2026-02-12', 'Koreksi Stok (Manual Upgrade)', '2026-02-12 15:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','supervisor') NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `last_login`) VALUES
(1, 'Indra Administrasi', 'admin@batarasura.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL),
(2, 'Budi Supervisor', 'spv@batarasura.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_material`
--
ALTER TABLE `data_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_material`
--
ALTER TABLE `stok_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `stok_mutasi`
--
ALTER TABLE `stok_mutasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`);

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
-- AUTO_INCREMENT for table `data_material`
--
ALTER TABLE `data_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stok_material`
--
ALTER TABLE `stok_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stok_mutasi`
--
ALTER TABLE `stok_mutasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stok_material`
--
ALTER TABLE `stok_material`
  ADD CONSTRAINT `stok_material_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `data_material` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stok_mutasi`
--
ALTER TABLE `stok_mutasi`
  ADD CONSTRAINT `stok_mutasi_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `data_material` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
