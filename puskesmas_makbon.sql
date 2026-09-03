-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 03, 2026
-- Server version: 8.4.3
-- PHP Version: 8.3.30
--
-- CATATAN: File ini sudah dimodifikasi - baris CREATE DATABASE dan USE dihapus
-- karena user database hosting tidak punya izin membuat database baru.
-- Import file ini setelah memilih database u636563619_pkmmakbon02 di phpMyAdmin.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP TABLE IF EXISTS `tracking_antrian`;
DROP TABLE IF EXISTS `antrian_online`;
DROP TABLE IF EXISTS `jadwal_operasional`;
DROP TABLE IF EXISTS `struktur_organisasi`;
DROP TABLE IF EXISTS `layanan`;
DROP TABLE IF EXISTS `saran_masukan`;
DROP TABLE IF EXISTS `profil_puskesmas`;
DROP TABLE IF EXISTS `petugas`;
DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `petugas` (
  `id_petugas` int NOT NULL AUTO_INCREMENT,
  `nama_petugas` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jabatan` varchar(50) DEFAULT 'Petugas Pelayanan',
  PRIMARY KEY (`id_petugas`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `profil_puskesmas` (
  `id_profil` int NOT NULL AUTO_INCREMENT,
  `nama_puskesmas` varchar(150) NOT NULL,
  `deskripsi_beranda` text,
  `visi` text,
  `misi` text,
  `sejarah` text,
  `alamat` text,
  `kontak` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `jam_operasional` varchar(150) DEFAULT NULL,
  `qr_bpjs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_profil`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `layanan` (
  `id_layanan` int NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(100) NOT NULL,
  `jenis` enum('BPJS','Non BPJS') NOT NULL DEFAULT 'Non BPJS',
  `deskripsi` text,
  `jadwal_layanan` varchar(150) DEFAULT NULL,
  `kuota_harian` int NOT NULL DEFAULT 30,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  PRIMARY KEY (`id_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `jadwal_operasional` (
  `id_jadwal` int NOT NULL AUTO_INCREMENT,
  `hari` varchar(20) NOT NULL,
  `jam_buka` time NOT NULL,
  `jam_tutup` time NOT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `id_petugas` int DEFAULT NULL,
  PRIMARY KEY (`id_jadwal`),
  KEY `id_petugas` (`id_petugas`),
  CONSTRAINT `jadwal_operasional_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `struktur_organisasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `jabatan` varchar(150) NOT NULL,
  `urutan` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `urutan` (`urutan`),
  CONSTRAINT `struktur_organisasi_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `struktur_organisasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `antrian_online` (
  `id_antrian` int NOT NULL AUTO_INCREMENT,
  `nama_pasien` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `id_layanan` int DEFAULT NULL,
  `layanan` varchar(100) DEFAULT NULL,
  `tanggal_antrian` date NOT NULL,
  `nomor_antrian` int NOT NULL,
  `status` enum('Menunggu','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu',
  `id_petugas` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_antrian`),
  KEY `id_layanan` (`id_layanan`),
  KEY `id_petugas` (`id_petugas`),
  CONSTRAINT `antrian_online_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE SET NULL,
  CONSTRAINT `antrian_online_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `tracking_antrian` (
  `id_tracking` int NOT NULL AUTO_INCREMENT,
  `id_antrian` int NOT NULL,
  `status` varchar(50) NOT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `waktu_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tracking`),
  KEY `id_antrian` (`id_antrian`),
  CONSTRAINT `tracking_antrian_ibfk_1` FOREIGN KEY (`id_antrian`) REFERENCES `antrian_online` (`id_antrian`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `saran_masukan` (
  `id_saran` int NOT NULL AUTO_INCREMENT,
  `nama_pengirim` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pesan` text NOT NULL,
  `balasan` text,
  `status` enum('Baru','Dibalas') NOT NULL DEFAULT 'Baru',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_saran`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_admin`) VALUES
(1, 'admin', '$2y$10$gcYyK6WwL5R/VedkCF4/wuLQ.1/atNeU862NrEqJTZ0UytyYqYFdC', 'Administrator');

INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `jabatan`) VALUES
(1, 'Siti Rahma', 'petugas1', '$2y$10$MrAyX6NWaww4odtxvoHa9.4l2gV2yVutRlDFfYASK2XPbBzyY9Nwi', 'Petugas Pendaftaran'),
(2, 'Petra', 'Petrabram', '$2y$10$9kHL0G.8cFP0wq78qZnT5OLb6tpEFS0XamgYyLiBNKRjaCdMzdQua', 'Perawat');

INSERT INTO `profil_puskesmas` (`id_profil`, `nama_puskesmas`, `deskripsi_beranda`, `visi`, `misi`, `sejarah`, `alamat`, `kontak`, `email`, `jam_operasional`, `qr_bpjs`) VALUES
(1, 'UPTD PUSKESMAS MAKBON', 'Melayani dengan Hati, Kesehatan Anda Kepuasan Kami', 'Mewujudkan pelayanan kesehatan yang bermutu dan berkualitas untuk mencapai Masyarakat Makbon sehat dan Mandiri', '1. Mengembangkan tenaga kesehatan yang profesional didukung oleh sarana dan prasarana yang memadai\r\n\r\n2. Memberikan pelayanan kesehatan secara optimal, adil dan berkesinambungan\r\n\r\n3. Mengembangkan sistem manajemen yang akuntabel dan modern\r\n\r\n4. Meningkatkan pemberdayaan masyarakat dalam bidang pembangunan kesehatan', 'Puskesmas Makbon berdiri untuk menjawab kebutuhan layanan kesehatan dasar masyarakat di wilayah Kecamatan Makbon, Kabupaten Sorong. Sampai sekarang ini Puskesmas berjalan dengan baik dilengkapi dengan fasilitas yang memadai', 'Jl. Korpri, Kel, Makbon, Distrik Makbon', '081234567890', 'pkmmakbon@gmail.com', 'Senin - Sabtu, 08.00 - 14.00 WIT', 'bpjs_1784772983_Screenshot_2026-07-22_231502.png');

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

INSERT INTO `jadwal_operasional` (`id_jadwal`, `hari`, `jam_buka`, `jam_tutup`, `keterangan`, `id_petugas`) VALUES
(1, 'Senin - Sabtu', '08:00:00', '14:00:00', 'Pelayanan Pemeriksaan & Poli', NULL),
(2, 'Senin - Sabtu', '08:00:00', '10:00:00', 'Pendaftaran & Rekam Medik', NULL),
(3, 'Setiap Hari', '00:00:00', '23:59:59', 'Tindakan Gawat Darurat & Persalinan (24 Jam)', NULL);

INSERT INTO `struktur_organisasi` (`id`, `parent_id`, `nama`, `jabatan`, `urutan`) VALUES
(1, NULL, 'Dr. Siti Rahma', 'Kepala Puskesmas', 1),
(2, 1, 'Budi Santoso', 'Koordinator Pelayanan', 1),
(3, 1, 'Maya Lestari', 'Kasubag Tata Usaha', 2),
(4, 2, 'Rina Wijaya', 'Petugas Pendaftaran', 1);

INSERT INTO `antrian_online` (`id_antrian`, `nama_pasien`, `no_hp`, `alamat`, `id_layanan`, `layanan`, `tanggal_antrian`, `nomor_antrian`, `status`, `id_petugas`, `created_at`) VALUES
(1, 'Bram', '082328631457', 'Sorong', 42, 'PELAYANAN ADMINISTRASI & SURAT KESEHATAN', '2026-09-11', 1, 'Menunggu', NULL, '2026-09-01 09:06:58'),
(2, 'Bram', '082328631458', 'Paso', 42, 'PELAYANAN ADMINISTRASI & SURAT KESEHATAN', '2026-09-11', 2, 'Menunggu', NULL, '2026-09-01 09:18:16');

INSERT INTO `tracking_antrian` (`id_tracking`, `id_antrian`, `status`, `keterangan`, `waktu_update`) VALUES
(1, 1, 'Menunggu', 'Pendaftaran antrian berhasil dibuat.', '2026-09-01 09:06:58'),
(2, 2, 'Menunggu', 'Pendaftaran antrian berhasil dibuat.', '2026-09-01 09:18:16');

INSERT INTO `saran_masukan` (`id_saran`, `nama_pengirim`, `email`, `pesan`, `balasan`, `status`, `created_at`) VALUES
(1, 'Bram', 'abrahamtitihalawa16@gmail.com', 'Saya mau ada penngadaan fasilitas nginap untuk pasien', NULL, 'Baru', '2026-07-10 10:22:46');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;