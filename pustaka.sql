-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2026 at 12:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pustaka`
--

-- --------------------------------------------------------

--
-- Table structure for table `koleksi`
--

CREATE TABLE `koleksi` (
  `id` int(11) NOT NULL,
  `kode_buku` varchar(20) NOT NULL,
  `judul_buku` varchar(100) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `status` enum('Habis','Menipis','Tersedia') DEFAULT 'Tersedia',
  `aksi` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koleksi`
--

INSERT INTO `koleksi` (`id`, `kode_buku`, `judul_buku`, `pengarang`, `kategori`, `stok`, `status`, `aksi`) VALUES
(1, 'BK001', 'Tentang Kamu', 'Tere Liye', 'Fiksi', 15, 'Tersedia', 'Edit/Hapus'),
(2, 'BK002', 'Laut Bercerita', 'Leila S. Chudori', 'Fiksi', 4, 'Menipis', 'Edit/Hapus'),
(3, 'BK003', 'Harry Potter and the Chamber of Secrets', 'J.K. Rowling', 'Fiksi', 11, 'Tersedia', 'Edit/Hapus'),
(4, 'BK004', 'Janji', 'Tere Liye', 'Fiksi', 10, 'Tersedia', 'Edit/Hapus'),
(5, 'BK005', 'Algoritma dan Struktur Data', 'E.K. Gombach', 'Teknologi', 20, 'Tersedia', 'Edit/Hapus'),
(8, 'BK007', 'Rasa', 'Tere Liye', 'Fiksi', 30, 'Tersedia', NULL),
(9, 'BK006', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Sejarah', 1, 'Menipis', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `kode_pinjam` varchar(20) DEFAULT NULL,
  `nama_peminjam` varchar(100) DEFAULT NULL,
  `judul_buku` varchar(200) DEFAULT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `kode_pinjam`, `nama_peminjam`, `judul_buku`, `tgl_pinjam`, `tgl_kembali`, `status`) VALUES
(1, 'PJ001', 'Pratita', 'Harry Potter and the Chamber of Secrets', '2026-05-14', '2026-05-30', 'Dipinjam'),
(2, 'PJ002', 'Jazilah', 'Laut Bercerita', '2026-05-07', '2026-05-14', 'Dikembalikan'),
(3, 'PJ003', 'Rahma', 'Bumi Manusia', '2026-05-01', '2026-05-10', 'Terlambat'),
(4, 'PJ004', 'Alya', 'Bumi Manusia', '2026-05-14', '2026-05-21', 'Dipinjam'),
(5, 'PJ005', 'Radhin', 'Bumi Manusia', '2026-05-07', '2026-05-21', 'Dikembalikan'),
(6, 'PJ006', 'Biandiera', 'Laut Bercerita', '2026-05-01', '2026-05-08', 'Terlambat'),
(7, 'PJ007', 'Maheswari', 'Bumi Manusia', '2026-05-01', '2026-05-08', 'Dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'pratita', '124250014');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `koleksi`
--
ALTER TABLE `koleksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pinjam` (`kode_pinjam`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `koleksi`
--
ALTER TABLE `koleksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
