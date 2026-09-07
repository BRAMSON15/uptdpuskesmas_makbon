-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 06, 2026 at 11:11 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `puskesmas_makbon`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_admin`) VALUES
(1, 'admin', '$2y$10$28xw/BYL5nLE7Xo6BCTKIeNcvLE287H3t1vXdy8Mp0MzJX8Bxzxvq', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `antrian_online`
--

CREATE TABLE `antrian_online` (
  `id_antrian` int NOT NULL,
  `nama_pasien` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `id_layanan` int DEFAULT NULL,
  `layanan` varchar(100) DEFAULT NULL,
  `tanggal_antrian` date NOT NULL,
  `nomor_antrian` int NOT NULL,
  `status` enum('Menunggu','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu',
  `id_petugas` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `antrian_online`
--

INSERT INTO `antrian_online` (`id_antrian`, `nama_pasien`, `no_hp`, `alamat`, `id_layanan`, `layanan`, `tanggal_antrian`, `nomor_antrian`, `status`, `id_petugas`, `created_at`) VALUES
(1, 'Bram', '082328631457', 'Sorong', 42, 'PELAYANAN ADMINISTRASI & SURAT KESEHATAN', '2026-09-11', 1, 'Menunggu', NULL, '2026-09-01 09:06:58'),
(2, 'Bram', '082328631458', 'Paso', 42, 'PELAYANAN ADMINISTRASI & SURAT KESEHATAN', '2026-09-11', 2, 'Menunggu', NULL, '2026-09-01 09:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_operasional`
--

CREATE TABLE `jadwal_operasional` (
  `id_jadwal` int NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_buka` time NOT NULL,
  `jam_tutup` time NOT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `id_petugas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal_operasional`
--

INSERT INTO `jadwal_operasional` (`id_jadwal`, `hari`, `jam_buka`, `jam_tutup`, `keterangan`, `id_petugas`) VALUES
(1, 'Senin - Sabtu', '08:00:00', '14:00:00', 'Pelayanan Pemeriksaan & Poli', NULL),
(2, 'Senin - Sabtu', '08:00:00', '10:00:00', 'Pendaftaran & Rekam Medik', NULL),
(3, 'Setiap Hari', '00:00:00', '23:59:59', 'Tindakan Gawat Darurat & Persalinan (24 Jam)', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `jenis` enum('BPJS','Non BPJS') NOT NULL DEFAULT 'Non BPJS',
  `deskripsi` text,
  `jadwal_layanan` varchar(150) DEFAULT NULL,
  `kuota_harian` int NOT NULL DEFAULT '30',
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `jenis`, `deskripsi`, `jadwal_layanan`, `kuota_harian`, `status`) VALUES
(1, 'PELAYANAN PENDAFTARAN & REKAM MEDIK', 'BPJS', 'Memberikan pelayanan pendaftaran & rekam medik secara profesional.', 'SENIN - SABTU, 08.00 - 10.00', 30, 'Aktif'),
(2, 'PELAYANAN PEMERIKSAAN UMUM', 'BPJS', 'Memberikan pelayanan pemeriksaan umum secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(3, 'PELAYANAN PEMERIKSAAN MTBS', 'BPJS', 'Memberikan pelayanan pemeriksaan mtbs secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(4, 'PELAYANAN KONSELING GIZI & SANITASI', 'BPJS', 'Memberikan pelayanan konseling gizi & sanitasi secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(5, 'PELAYANAN PROMOSI KESEHATAN', 'BPJS', 'Memberikan pelayanan promosi kesehatan secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(6, 'PELAYANAN GIZI MASYARAKAT', 'BPJS', 'Memberikan pelayanan gizi masyarakat secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(7, 'PELAYANAN KESEHATAN KELUARGA (KIA & KB)', 'BPJS', 'Memberikan pelayanan kesehatan keluarga (kia & kb) secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(8, 'PELAYANAN P2 - TBC PARU', 'BPJS', 'Memberikan pelayanan p2 - tbc paru secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(9, 'PELAYANAN P2 - KUSTA', 'BPJS', 'Memberikan pelayanan p2 - kusta secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(10, 'PELAYANAN P2 - HIV', 'BPJS', 'Memberikan pelayanan p2 - hiv secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(11, 'PELAYANAN P2 - IMS', 'BPJS', 'Memberikan pelayanan p2 - ims secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(12, 'PELAYANAN P2 - DIARE', 'BPJS', 'Memberikan pelayanan p2 - diare secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(13, 'PELAYANAN P2 - MALARIA', 'BPJS', 'Memberikan pelayanan p2 - malaria secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(14, 'PELAYANAN P2 - DBD', 'BPJS', 'Memberikan pelayanan p2 - dbd secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(15, 'PELAYANAN P2 - IMUNISASI', 'BPJS', 'Memberikan pelayanan p2 - imunisasi secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(16, 'PELAYANAN P T M', 'BPJS', 'Memberikan pelayanan p t m secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(17, 'PELAYANAN SURVEILANS PENYAKIT', 'BPJS', 'Memberikan pelayanan surveilans penyakit secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(18, 'PELAYANAN LABORATORIUM', 'BPJS', 'Memberikan pelayanan laboratorium secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(19, 'PELAYANAN FARMASI', 'BPJS', 'Memberikan pelayanan farmasi secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(20, 'PELAYANAN ADMINISTRASI & SURAT KESEHATAN', 'BPJS', 'Memberikan pelayanan administrasi & surat kesehatan secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(21, 'PELAYANAN TINDAKAN GAWAT DARURAT', 'BPJS', 'Memberikan pelayanan tindakan gawat darurat secara profesional.', 'SETIAP HARI, 1 X 24 JAM', 30, 'Aktif'),
(22, 'PELAYANAN PERSALINAN NORMAL', 'BPJS', 'Memberikan pelayanan persalinan normal secara profesional.', 'SETIAP HARI, 1 X 24 JAM', 30, 'Aktif'),
(23, 'PELAYANAN PENDAFTARAN & REKAM MEDIK', 'Non BPJS', 'Memberikan pelayanan pendaftaran & rekam medik secara profesional.', 'SENIN - SABTU, 08.00 - 10.00', 30, 'Aktif'),
(24, 'PELAYANAN PEMERIKSAAN UMUM', 'Non BPJS', 'Memberikan pelayanan pemeriksaan umum secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(25, 'PELAYANAN PEMERIKSAAN MTBS', 'Non BPJS', 'Memberikan pelayanan pemeriksaan mtbs secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(26, 'PELAYANAN KONSELING GIZI & SANITASI', 'Non BPJS', 'Memberikan pelayanan konseling gizi & sanitasi secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(27, 'PELAYANAN PROMOSI KESEHATAN', 'Non BPJS', 'Memberikan pelayanan promosi kesehatan secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(28, 'PELAYANAN GIZI MASYARAKAT', 'Non BPJS', 'Memberikan pelayanan gizi masyarakat secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(29, 'PELAYANAN KESEHATAN KELUARGA (KIA & KB)', 'Non BPJS', 'Memberikan pelayanan kesehatan keluarga (kia & kb) secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(30, 'PELAYANAN P2 - TBC PARU', 'Non BPJS', 'Memberikan pelayanan p2 - tbc paru secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(31, 'PELAYANAN P2 - KUSTA', 'Non BPJS', 'Memberikan pelayanan p2 - kusta secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(32, 'PELAYANAN P2 - HIV', 'Non BPJS', 'Memberikan pelayanan p2 - hiv secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(33, 'PELAYANAN P2 - IMS', 'Non BPJS', 'Memberikan pelayanan p2 - ims secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(34, 'PELAYANAN P2 - DIARE', 'Non BPJS', 'Memberikan pelayanan p2 - diare secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(35, 'PELAYANAN P2 - MALARIA', 'Non BPJS', 'Memberikan pelayanan p2 - malaria secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(36, 'PELAYANAN P2 - DBD', 'Non BPJS', 'Memberikan pelayanan p2 - dbd secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(37, 'PELAYANAN P2 - IMUNISASI', 'Non BPJS', 'Memberikan pelayanan p2 - imunisasi secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(38, 'PELAYANAN P T M', 'Non BPJS', 'Memberikan pelayanan p t m secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(39, 'PELAYANAN SURVEILANS PENYAKIT', 'Non BPJS', 'Memberikan pelayanan surveilans penyakit secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(40, 'PELAYANAN LABORATORIUM', 'Non BPJS', 'Memberikan pelayanan laboratorium secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(41, 'PELAYANAN FARMASI', 'Non BPJS', 'Memberikan pelayanan farmasi secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(42, 'PELAYANAN ADMINISTRASI & SURAT KESEHATAN', 'Non BPJS', 'Memberikan pelayanan administrasi & surat kesehatan secara profesional.', 'SENIN - SABTU, 08.00 - 14.00', 30, 'Aktif'),
(43, 'PELAYANAN TINDAKAN GAWAT DARURAT', 'Non BPJS', 'Memberikan pelayanan tindakan gawat darurat secara profesional.', 'SETIAP HARI, 1 X 24 JAM', 30, 'Aktif'),
(44, 'PELAYANAN PERSALINAN NORMAL', 'Non BPJS', 'Memberikan pelayanan persalinan normal secara profesional.', 'SETIAP HARI, 1 X 24 JAM', 30, 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jabatan` varchar(50) DEFAULT 'Petugas Pelayanan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `jabatan`) VALUES
(3, 'Ari', 'Ari', '$2y$10$UH9Jefh6Gnx7Yb4AFZBJKOvgnQ5brMmylc1qrT6H1Ecr1.KHewalu', 'petugas');

-- --------------------------------------------------------

--
-- Table structure for table `profil_puskesmas`
--

CREATE TABLE `profil_puskesmas` (
  `id_profil` int NOT NULL,
  `nama_puskesmas` varchar(150) NOT NULL,
  `deskripsi_beranda` text,
  `visi` text,
  `misi` text,
  `sejarah` text,
  `alamat` text,
  `kontak` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `jam_operasional` varchar(150) DEFAULT NULL,
  `qr_bpjs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profil_puskesmas`
--

INSERT INTO `profil_puskesmas` (`id_profil`, `nama_puskesmas`, `deskripsi_beranda`, `visi`, `misi`, `sejarah`, `alamat`, `kontak`, `email`, `jam_operasional`, `qr_bpjs`) VALUES
(1, 'UPTD PUSKESMAS MAKBON', 'Melayani dengan Hati, Kesehatan Anda Kepuasan Kami', 'Mewujudkan pelayanan kesehatan yang bermutu dan berkualitas untuk mencapai Masyarakat Makbon sehat dan Mandiri', '1. Mengembangkan tenaga kesehatan yang profesional didukung oleh sarana dan prasarana yang memadai\r\n\r\n2. Memberikan pelayanan kesehatan secara optimal, adil dan berkesinambungan\r\n\r\n3. Mengembangkan sistem manajemen yang akuntabel dan modern\r\n\r\n4. Meningkatkan pemberdayaan masyarakat dalam bidang pembangunan kesehatan', 'Puskesmas Makbon berdiri untuk menjawab kebutuhan layanan kesehatan dasar masyarakat di wilayah Kecamatan Makbon, Kabupaten Sorong. Sampai sekarang ini Puskesmas berjalan dengan baik dilengkapi dengan fasilitas yang memadai', 'Jl. Korpri, Kel, Makbon, Distrik Makbon', '081234567890', 'pkmmakbon@gmail.com', 'Senin - Sabtu, 08.00 - 14.00 WIT', 'bpjs_1784772983_Screenshot_2026-07-22_231502.png');

-- --------------------------------------------------------

--
-- Table structure for table `saran_masukan`
--

CREATE TABLE `saran_masukan` (
  `id_saran` int NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pesan` text NOT NULL,
  `balasan` text,
  `status` enum('Baru','Dibalas') NOT NULL DEFAULT 'Baru',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `saran_masukan`
--

INSERT INTO `saran_masukan` (`id_saran`, `nama_pengirim`, `email`, `pesan`, `balasan`, `status`, `created_at`) VALUES
(1, 'Bram', 'abrahamtitihalawa16@gmail.com', 'Saya mau ada penngadaan fasilitas nginap untuk pasien', NULL, 'Baru', '2026-07-10 10:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi`
--

CREATE TABLE `struktur_organisasi` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `jabatan` varchar(150) NOT NULL,
  `urutan` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `struktur_organisasi`
--

INSERT INTO `struktur_organisasi` (`id`, `parent_id`, `nama`, `jabatan`, `urutan`) VALUES
(1, NULL, 'Dr. Siti Rahma', 'Kepala Puskesmas', 1),
(2, 1, 'Budi Santoso', 'Koordinator Pelayanan', 1),
(3, 1, 'Maya Lestari', 'Kasubag Tata Usaha', 2),
(4, 2, 'Rina Wijaya', 'Petugas Pendaftaran', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tracking_antrian`
--

CREATE TABLE `tracking_antrian` (
  `id_tracking` int NOT NULL,
  `id_antrian` int NOT NULL,
  `status` varchar(50) NOT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `waktu_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tracking_antrian`
--

INSERT INTO `tracking_antrian` (`id_tracking`, `id_antrian`, `status`, `keterangan`, `waktu_update`) VALUES
(1, 1, 'Menunggu', 'Pendaftaran antrian berhasil dibuat.', '2026-09-01 09:06:58'),
(2, 2, 'Menunggu', 'Pendaftaran antrian berhasil dibuat.', '2026-09-01 09:18:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `antrian_online`
--
ALTER TABLE `antrian_online`
  ADD PRIMARY KEY (`id_antrian`),
  ADD KEY `id_layanan` (`id_layanan`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `jadwal_operasional`
--
ALTER TABLE `jadwal_operasional`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `profil_puskesmas`
--
ALTER TABLE `profil_puskesmas`
  ADD PRIMARY KEY (`id_profil`);

--
-- Indexes for table `saran_masukan`
--
ALTER TABLE `saran_masukan`
  ADD PRIMARY KEY (`id_saran`);

--
-- Indexes for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `urutan` (`urutan`);

--
-- Indexes for table `tracking_antrian`
--
ALTER TABLE `tracking_antrian`
  ADD PRIMARY KEY (`id_tracking`),
  ADD KEY `id_antrian` (`id_antrian`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `antrian_online`
--
ALTER TABLE `antrian_online`
  MODIFY `id_antrian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal_operasional`
--
ALTER TABLE `jadwal_operasional`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profil_puskesmas`
--
ALTER TABLE `profil_puskesmas`
  MODIFY `id_profil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saran_masukan`
--
ALTER TABLE `saran_masukan`
  MODIFY `id_saran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tracking_antrian`
--
ALTER TABLE `tracking_antrian`
  MODIFY `id_tracking` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antrian_online`
--
ALTER TABLE `antrian_online`
  ADD CONSTRAINT `antrian_online_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE SET NULL,
  ADD CONSTRAINT `antrian_online_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE SET NULL;

--
-- Constraints for table `jadwal_operasional`
--
ALTER TABLE `jadwal_operasional`
  ADD CONSTRAINT `jadwal_operasional_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE SET NULL;

--
-- Constraints for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  ADD CONSTRAINT `struktur_organisasi_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `struktur_organisasi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracking_antrian`
--
ALTER TABLE `tracking_antrian`
  ADD CONSTRAINT `tracking_antrian_ibfk_1` FOREIGN KEY (`id_antrian`) REFERENCES `antrian_online` (`id_antrian`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
