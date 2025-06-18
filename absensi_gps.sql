-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 16, 2025 at 12:58 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi_baru`
--

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--
CREATE DATABASE IF NOT EXISTS `absensi_gps` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `absensi_gps`;

CREATE TABLE `jabatan` (
  `id` int NOT NULL,
  `nama_jabatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama_jabatan`) VALUES
(1, 'Admin'),
(2, 'IT Support');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int NOT NULL,
  `nama_lokasi` varchar(225) NOT NULL,
  `alamat_lokasi` varchar(225) NOT NULL,
  `tipe_lokasi` varchar(225) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `radius` int NOT NULL,
  `zona_waktu` varchar(4) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `alamat_lokasi`, `tipe_lokasi`, `latitude`, `longitude`, `radius`, `zona_waktu`, `jam_masuk`, `jam_pulang`) VALUES
(3, 'Kantor Pusat', 'Jl. Penghulu Rasyid No.1, Pamarangan Kiwa, Kec. Tj., Kabupaten Tabalong, Kalimantan Selatan 71513', 'Pusat', '-2.1633515372117653', '  115.38075627405183', 1000000000, 'WITA', '08:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` text,
  `no_handphone` varchar(50) DEFAULT NULL,
  `id_jabatan` int DEFAULT NULL,
  `id_lok_presensi` int DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `nip`, `nama`, `jenis_kelamin`, `alamat`, `no_handphone`, `id_jabatan`, `id_lok_presensi`, `foto`) VALUES
(1, 'PEG-001', 'Elsa lidya', 'P', 'Jln. Test', '08123456789', 1, 3, NULL),
(2, 'PEG-0002', 'test', 'L', 'test', '08123453443', 2, 3, 'Screenshot (10).png'),
(3, 'PEG-0002', 'ucup', 'L', 'hksn', '08123453443', 2, 3, 'latihan looping 2.png');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int NOT NULL,
  `id_pegawai` int NOT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `foto_masuk` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `foto_keluar` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `id_pegawai`, `tanggal_masuk`, `jam_masuk`, `foto_masuk`, `jam_keluar`, `foto_keluar`) VALUES
(5, 2, '2025-02-16', '04:43:00', 'masuk_20250215_204843.jpeg', '11:37:28', 'keluar_20250216_033746.jpeg'),
(6, 3, '2025-02-16', '11:49:03', 'masuk_20250216_034910.jpeg', NULL, NULL),
(7, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134602.jpeg', NULL, NULL),
(8, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134603.jpeg', NULL, NULL),
(9, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134604.jpeg', NULL, NULL),
(10, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134605.jpeg', NULL, NULL),
(11, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134616.jpeg', NULL, NULL),
(12, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134618.jpeg', NULL, NULL),
(13, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134618.jpeg', NULL, NULL),
(14, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134625.jpeg', NULL, NULL),
(15, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134627.jpeg', NULL, NULL),
(16, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134628.jpeg', NULL, NULL),
(17, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134628.jpeg', NULL, NULL),
(18, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134628.jpeg', NULL, NULL),
(19, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134628.jpeg', NULL, NULL),
(20, 2, '2025-02-18', '21:45:39', 'masuk_20250218_134628.jpeg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `id_pegawai` int DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('aktif','non aktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'aktif',
  `role` enum('admin','pegawai') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'pegawai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_pegawai`, `username`, `password`, `status`, `role`) VALUES
(1, 1, 'elsa', '$2y$10$O9Qz9Z2z8F8y5vF5vF5vF.O9Qz9Z2z8F8y5vF5vF5vF5vF5vF5vF', 'aktif', 'admin'),
(2, 2, 'test', '$2y$10$O9Qz9Z2z8F8y5vF5vF5vF.O9Qz9Z2z8F8y5vF5vF5vF5vF5vF5vF', 'aktif', 'pegawai'),
(3, 3, 'ucup', '$2y$10$O9Qz9Z2z8F8y5vF5vF5vF.O9Qz9Z2z8F8y5vF5vF5vF5vF5vF5vF', 'aktif', 'pegawai');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_pegawai_jabatan` (`id_jabatan`),
  ADD KEY `FK_pegawai_lokasi_presensi` (`id_lok_presensi`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_users_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `FK_pegawai_jabatan` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id`),
  ADD CONSTRAINT `FK_pegawai_lokasi_presensi` FOREIGN KEY (`id_lok_presensi`) REFERENCES `lokasi_presensi` (`id`);

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
