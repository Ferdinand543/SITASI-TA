-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 09:44 AM
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
-- Database: `db_sistem`
--

-- --------------------------------------------------------

--
-- Table structure for table `bimbingan`
--

CREATE TABLE `bimbingan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nim_nid` varchar(20) NOT NULL,
  `dosen_nid` varchar(20) NOT NULL,
  `pertemuan_ke` int(11) NOT NULL,
  `tanggal_bimbingan` date NOT NULL,
  `topik_bimbingan` text NOT NULL,
  `dokumentasi` varchar(255) DEFAULT NULL,
  `status` enum('Baru Dikirim','Sudah Dilihat') NOT NULL DEFAULT 'Baru Dikirim',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bimbingan`
--

INSERT INTO `bimbingan` (`id`, `nim_nid`, `dosen_nid`, `pertemuan_ke`, `tanggal_bimbingan`, `topik_bimbingan`, `dokumentasi`, `status`, `created_at`, `updated_at`) VALUES
(1, '2350231010', '12345', 1, '2026-05-17', 'gh', 'bimbingan_2350231010_1779016750.jpeg', 'Baru Dikirim', '2026-05-17 04:19:10', '2026-05-17 04:19:10'),
(2, '2350231010', '1234567', 1, '2026-05-18', 'sg', 'bimbingan_2350231010_1779079818.jpeg', 'Baru Dikirim', '2026-05-17 21:50:18', '2026-05-17 21:50:18');

-- --------------------------------------------------------

--
-- Table structure for table `bimbingan_mahasiswa`
--

CREATE TABLE `bimbingan_mahasiswa` (
  `id` int(11) NOT NULL,
  `nim_nid` varchar(20) NOT NULL,
  `tanggal_bimbingan` date NOT NULL,
  `pertemuan_ke` int(2) NOT NULL,
  `topik_bimbingan` text NOT NULL,
  `dokumentasi` varchar(255) DEFAULT NULL,
  `status` enum('Baru Dikirim','Sudah Dilihat') DEFAULT 'Baru Dikirim'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bimbingan_mahasiswa`
--

INSERT INTO `bimbingan_mahasiswa` (`id`, `nim_nid`, `tanggal_bimbingan`, `pertemuan_ke`, `topik_bimbingan`, `dokumentasi`, `status`) VALUES
(1, '2350231001', '2026-05-01', 1, 'Pembahasan Bab 1: Latar Belakang dan Rumusan Masalah', 'bimbingan1_2350231001.pdf', 'Sudah Dilihat'),
(2, '2350231001', '2026-05-08', 2, 'Revisi Bab 1 dan Pembahasan Bab 2: Tinjauan Pustaka', 'bimbingan2_2350231001.pdf', 'Sudah Dilihat'),
(3, '2350231001', '2026-05-15', 3, 'Pembahasan Bab 3: Metodologi Penelitian', NULL, 'Sudah Dilihat'),
(4, '2350231017', '2026-05-02', 1, 'Konsultasi Judul dan Topik TA', 'bimbingan1_2350231017.pdf', 'Sudah Dilihat'),
(5, '2350231017', '2026-05-09', 2, 'Pembahasan Bab 1 dan Bab 2', 'bimbingan2_2350231017.pdf', 'Sudah Dilihat'),
(6, '2350231017', '2026-05-16', 3, 'Revisi Metodologi Penelitian', NULL, 'Sudah Dilihat'),
(7, '2350231018', '2026-05-03', 1, 'Pengajuan Judul Proposal', 'bimbingan1_2350231018.pdf', 'Sudah Dilihat'),
(8, '2350231018', '2026-05-10', 2, 'Pembahasan Bab 1', NULL, 'Sudah Dilihat'),
(9, '2350231019', '2026-05-04', 1, 'Konsultasi BAB 1 dan BAB 2', 'bimbingan1_2350231019.pdf', 'Sudah Dilihat'),
(10, '2350231019', '2026-05-11', 2, 'Revisi BAB 2', NULL, 'Baru Dikirim'),
(11, '2350231020', '2026-05-05', 1, 'Pembahasan BAB 1', 'bimbingan1_2350231020.pdf', 'Sudah Dilihat'),
(12, '2350231020', '2026-05-12', 2, 'Pembahasan BAB 2 dan BAB 3', 'bimbingan2_2350231020.pdf', 'Sudah Dilihat'),
(13, '2350231020', '2026-05-19', 3, 'ACC Proposal', NULL, 'Baru Dikirim'),
(14, '2350231021', '2026-05-06', 1, 'Konsultasi Topik TA', NULL, 'Sudah Dilihat'),
(15, '2350231021', '2026-05-13', 2, 'Pembahasan BAB 1', 'bimbingan2_2350231021.pdf', 'Baru Dikirim'),
(16, '2350231022', '2026-05-07', 1, 'Pengajuan Judul', 'bimbingan1_2350231022.pdf', 'Sudah Dilihat'),
(17, '2350231023', '2026-05-01', 1, 'Pembahasan BAB 1 dan BAB 2', 'bimbingan1_2350231023.pdf', 'Sudah Dilihat'),
(18, '2350231023', '2026-05-08', 2, 'Revisi BAB 2', NULL, 'Sudah Dilihat'),
(19, '2350231023', '2026-05-15', 3, 'Pembahasan BAB 3', 'bimbingan3_2350231023.pdf', 'Sudah Dilihat'),
(20, '2350231024', '2026-05-02', 1, 'Konsultasi Judul', NULL, 'Sudah Dilihat'),
(21, '2350231024', '2026-05-09', 2, 'Pembahasan BAB 1', 'bimbingan2_2350231024.pdf', 'Sudah Dilihat'),
(22, '2350231024', '2026-05-16', 3, 'Revisi BAB 1 dan BAB 2', NULL, 'Sudah Dilihat'),
(23, '2350231025', '2026-05-03', 1, 'Pembahasan BAB 1', 'bimbingan1_2350231025.pdf', 'Sudah Dilihat'),
(24, '2350231025', '2026-05-10', 2, 'Pembahasan BAB 2', NULL, 'Sudah Dilihat');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen_pembimbing`
--

CREATE TABLE `dosen_pembimbing` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `nim_nid_dosen` varchar(20) NOT NULL,
  `urutan` tinyint(1) NOT NULL COMMENT '1 = Pembimbing 1, 2 = Pembimbing 2',
  `tanggal_penetapan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen_pembimbing`
--

INSERT INTO `dosen_pembimbing` (`id`, `proposal_id`, `nim_nid_dosen`, `urutan`, `tanggal_penetapan`) VALUES
(15, 16, '112345678', 1, '2026-05-16'),
(17, 16, '1234567', 2, '2026-05-16'),
(18, 17, '12345', 1, '2026-05-16'),
(19, 17, '1234567', 2, '2026-05-16'),
(20, 18, '12345', 1, '2026-05-17'),
(21, 18, '112345678', 2, '2026-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_roles`
--

CREATE TABLE `dosen_roles` (
  `id` int(11) NOT NULL,
  `nim_nid` varchar(20) NOT NULL,
  `role_dosen` enum('reviewer','pembimbing','penguji','koordinator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen_roles`
--

INSERT INTO `dosen_roles` (`id`, `nim_nid`, `role_dosen`) VALUES
(1, '112345678', 'koordinator'),
(2, '112345678', 'reviewer'),
(3, '12345', 'koordinator'),
(4, '1234567', 'pembimbing');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_akademik`
--

CREATE TABLE `jadwal_akademik` (
  `id` int(11) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `sub_judul` varchar(255) DEFAULT NULL,
  `kategori` enum('Seminar','Administrasi','Bimbingan') NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('Akan Datang','Berlangsung','Selesai','Ditutup') DEFAULT 'Akan Datang'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_akademik`
--

INSERT INTO `jadwal_akademik` (`id`, `nama_kegiatan`, `sub_judul`, `kategori`, `tanggal`, `waktu`, `lokasi`, `deskripsi`, `status`) VALUES
(1, 'Seminar Proposal Gelombang 1', 'Batch 1A Genap 2024', 'Seminar', '2026-10-01', '08:00:00', 'R. Multimedia', 'Seminar proposal mahasiswa gelombang pertama semester genap', 'Akan Datang'),
(2, 'Upload Proposal Final', 'Sistem Unggah Dokumen', 'Administrasi', '2026-05-17', '23:59:00', 'Online', 'Batas akhir pengumpulan dokumen proposal final', 'Berlangsung'),
(3, 'Bimbingan Mahasiswa', 'Proses Bimbingan Mahasiswa', 'Bimbingan', '2026-05-12', '09:00:00', 'Gdg. Dekanat Lt.2', 'Sesi bimbingan rutin antara mahasiswa dan dosen pembimbing', 'Selesai'),
(4, 'Batas Pengumpulan Revisi', 'Tutup Akses 23:59', 'Administrasi', '2026-04-15', '16:00:00', 'Bagian Tata Usaha', 'Pengumpulan dokumen revisi hasil seminar proposal', 'Selesai'),
(5, 'Seminar Hasil Penelitian', 'Batch 2A Genap 2024', 'Seminar', '2026-05-18', '09:00:00', 'Aula Fakultas', 'Presentasi hasil penelitian tugas akhir mahasiswa', 'Akan Datang');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_04_18_174800_create_sessions_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `panduan_ta_dokumen`
--

CREATE TABLE `panduan_ta_dokumen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'document-text',
  `role` enum('all','mahasiswa','dosen') NOT NULL DEFAULT 'all',
  `urutan` int(11) NOT NULL DEFAULT 0,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_judul`
--

CREATE TABLE `pengajuan_judul` (
  `id` int(11) NOT NULL,
  `nim_nid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `topik_1` varchar(255) NOT NULL,
  `judul_1` varchar(255) NOT NULL,
  `mitra_1` varchar(255) DEFAULT NULL,
  `topik_2` varchar(255) NOT NULL,
  `judul_2` varchar(255) NOT NULL,
  `mitra_2` varchar(255) DEFAULT NULL,
  `topik_3` varchar(255) NOT NULL,
  `judul_3` varchar(255) NOT NULL,
  `mitra_3` varchar(255) DEFAULT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('menunggu verifikasi','disetujui','ditolak') NOT NULL DEFAULT 'menunggu verifikasi',
  `judul_disetujui` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_judul`
--

INSERT INTO `pengajuan_judul` (`id`, `nim_nid`, `topik_1`, `judul_1`, `mitra_1`, `topik_2`, `judul_2`, `mitra_2`, `topik_3`, `judul_3`, `mitra_3`, `tanggal_pengajuan`, `created_at`, `updated_at`, `status`, `judul_disetujui`) VALUES
(13, '2350231017', 'Im Good', 'Keren banget gue', NULL, 'Lo keren', 'Gue keren', NULL, 'Kita Bisa', 'Aku bisa', NULL, '2026-05-01', '2026-05-11 22:28:46', '2026-05-11 22:31:31', 'disetujui', 'Gue keren'),
(16, '2350231010', 'Indomik', 'Narkoba', NULL, 'Parfum', 'tilang', NULL, 'Sabu', 'hacigril', NULL, '2026-05-14', '2026-05-13 18:22:56', '2026-05-13 18:25:26', 'disetujui', 'tilang'),
(17, '2350231010', 'Indomik', 'roni', NULL, 'musik', 'yura', NULL, 'jajan', 'yura paul', NULL, '2026-05-15', '2026-05-13 18:37:30', '2026-05-13 18:38:10', 'disetujui', 'yura'),
(18, '2350231010', 'musik', 'Narkoba', NULL, 'Parfum', 'narkoba', NULL, 'Sabu', 'hacigril', NULL, '2026-05-16', '2026-05-16 00:19:54', '2026-05-16 00:58:03', 'ditolak', NULL),
(19, '2350231010', 'lampu', '1', NULL, '2', '2', NULL, '2', '2', NULL, '2026-05-16', '2026-05-16 00:56:46', '2026-05-16 00:57:35', 'ditolak', NULL),
(20, '2350231010', 'S', 'D', NULL, 'D', 'DD', NULL, 'D', 'D', NULL, '2026-05-16', '2026-05-16 01:04:21', '2026-05-16 01:05:38', 'ditolak', NULL),
(21, '2350231010', 'Y', '11', NULL, '1', '1', NULL, '1', '1', NULL, '2026-05-16', '2026-05-16 01:18:31', '2026-05-16 01:19:45', 'ditolak', NULL),
(22, '2350231010', 'S', 'Narkoba', NULL, 'v', 'v', NULL, 'h', 'v', NULL, '2026-05-17', '2026-05-17 04:22:30', '2026-05-17 04:22:30', 'menunggu verifikasi', NULL),
(23, '2350231010', 'sd', '11', NULL, 'D', 'DD', NULL, '2', 'yura paul', NULL, '2026-05-17', '2026-05-17 04:30:21', '2026-05-17 04:30:21', 'menunggu verifikasi', NULL),
(24, '2350231010', 'lampu', 'Narkoba', NULL, 'polisi', 'tilang', NULL, 'tni', '2', NULL, '2026-05-17', '2026-05-17 04:36:54', '2026-05-17 08:37:50', 'disetujui', '2'),
(25, '2350231010', 'Y', 'Narkoba', 'v', 'musik', 'DD', 'g', 'tni', 'hacigril', 'e', '2026-05-17', '2026-05-17 04:41:52', '2026-05-17 04:43:33', 'ditolak', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_proposal_bimbingan`
--

CREATE TABLE `pengajuan_proposal_bimbingan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nim_nid` varchar(20) NOT NULL,
  `dosen_nid` varchar(20) DEFAULT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `judul` varchar(500) NOT NULL,
  `file_proposal` varchar(255) NOT NULL,
  `status` enum('pending','diterima','ditolak','sudah_dilihat') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_proposal_bimbingan`
--

INSERT INTO `pengajuan_proposal_bimbingan` (`id`, `nim_nid`, `dosen_nid`, `tanggal_pengajuan`, `judul`, `file_proposal`, `status`, `created_at`, `updated_at`) VALUES
(1, '2350231010', NULL, '2026-05-17', 'The Garfield Movie 2024', 'proposal_2350231010_1779016573.pdf', 'sudah_dilihat', '2026-05-17 04:16:13', '2026-05-17 07:29:05'),
(2, '2350231010', NULL, '2026-05-17', 'The Garfield Movie 2024', 'proposal_2350231010_1779016729.pdf', 'sudah_dilihat', '2026-05-17 04:18:49', '2026-05-17 07:28:49'),
(3, '2350231010', NULL, '2026-05-20', 'HHDW', 'proposal_2350231010_1779024131.pdf', '', '2026-05-17 06:22:11', '2026-05-17 07:23:00'),
(4, '2350231010', NULL, '2026-05-27', 'Minios', 'proposal_2350231010_1779028460.pdf', 'sudah_dilihat', '2026-05-17 07:34:20', '2026-05-17 07:37:27'),
(5, '2350231010', NULL, '2026-05-29', 'azam', 'proposal_2350231010_1779028480.docx', 'sudah_dilihat', '2026-05-17 07:34:40', '2026-05-17 07:35:14'),
(6, '2350231010', NULL, '2026-05-17', 'The Garfield Movie 2024', 'proposal_2350231010_1779028763.pdf', 'sudah_dilihat', '2026-05-17 07:39:23', '2026-05-17 07:39:50'),
(7, '2350231010', NULL, '2026-05-19', 'Minios', 'proposal_2350231010_1779028940.pdf', 'sudah_dilihat', '2026-05-17 07:42:20', '2026-05-17 07:43:10'),
(8, '2350231010', NULL, '2026-05-28', 'HHDW', 'proposal_2350231010_1779028959.pdf', 'sudah_dilihat', '2026-05-17 07:42:39', '2026-05-17 07:48:32'),
(9, '2350231010', '12345', '2026-05-26', 'Minios', 'proposal_2350231010_1779030019.pdf', 'pending', '2026-05-17 08:00:19', '2026-05-17 08:00:19'),
(10, '2350231010', '12345', '2026-05-17', 'HHDW', 'proposal_2350231010_1779030041.pdf', 'sudah_dilihat', '2026-05-17 08:00:41', '2026-05-17 08:02:35'),
(11, '2350231010', '1234567', '2026-05-18', 'azam', 'proposal_2350231010_1779079788.pdf', 'pending', '2026-05-17 21:49:48', '2026-05-17 21:49:48');

-- --------------------------------------------------------

--
-- Table structure for table `progress_ta`
--

CREATE TABLE `progress_ta` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nim_nid` varchar(20) NOT NULL,
  `tahap` enum('Pengajuan Judul','Verifikasi Judul','Upload Proposal','Penetapan Dosen Pembimbing','Review Proposal','Bimbingan Tugas Akhir') NOT NULL,
  `status` enum('selesai','aktif','belum') NOT NULL DEFAULT 'belum',
  `tanggal` date DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `progress_ta`
--

INSERT INTO `progress_ta` (`id`, `nim_nid`, `tahap`, `status`, `tanggal`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, '2350231010', 'Pengajuan Judul', 'selesai', NULL, NULL, '2026-05-17 09:24:55', '2026-05-17 22:18:08'),
(2, '2350231010', 'Verifikasi Judul', 'selesai', NULL, NULL, '2026-05-17 09:24:55', '2026-05-17 22:18:08'),
(3, '2350231010', 'Upload Proposal', 'selesai', NULL, NULL, '2026-05-17 09:24:55', '2026-05-17 22:18:08'),
(4, '2350231010', 'Penetapan Dosen Pembimbing', 'selesai', NULL, NULL, '2026-05-17 09:24:55', '2026-05-17 22:18:08'),
(5, '2350231010', 'Review Proposal', 'aktif', NULL, NULL, '2026-05-17 09:24:55', '2026-05-17 22:18:08'),
(6, '2350231010', 'Bimbingan Tugas Akhir', 'aktif', NULL, NULL, '2026-05-17 09:24:55', '2026-05-17 22:18:08');

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE `proposal` (
  `id` int(11) NOT NULL,
  `nim_nid` varchar(20) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `file_proposal` varchar(255) DEFAULT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `status` enum('menunggu_verifikasi','menunggu_review','selesai','ditolak') NOT NULL DEFAULT 'menunggu_verifikasi',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposal`
--

INSERT INTO `proposal` (`id`, `nim_nid`, `judul`, `file_proposal`, `tanggal_pengajuan`, `status`, `created_at`, `updated_at`) VALUES
(16, '2350231010', 'Perancangan Sistem Informasi Manajemen Hotel Berbasis Web pada Hotel Berbunga', 'proposals/EDM01 (1).pdf', '2026-05-16', 'selesai', '2026-05-16 06:11:02', '2026-05-16 13:21:21'),
(17, '2350231010', 'Analisis Sistem Rekomendasi Produk pada Platform E-Commerce Menggunakan Data Riwayat Belanja', 'proposals/model 4.pdf', '2026-05-16', 'menunggu_review', '2026-05-16 06:24:33', '2026-05-16 06:25:35'),
(18, '2350231010', 'apa', 'proposals/693-Article Text-3299-1-10-20260402.pdf', '2026-05-17', 'menunggu_review', '2026-05-17 04:26:36', '2026-05-17 09:44:57'),
(19, '2350231010', 'N', 'proposals/EDM01 (1).pdf', '2026-05-17', 'menunggu_verifikasi', '2026-05-17 09:37:25', '2026-05-17 09:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ka5srDwNrO8HVwEgjtzfv7J3pSk8s6QLTnZA7NuC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOFp0bzM2Y0lwZ25SWXJvczdoaHZLaTJ6YlgyUXUzUGRNcHI4MlNyaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozMToibm90aWZfanVkdWxfdGVyYWtoaXJfMjM1MDIzMTAxMCI7aTo4O3M6MzQ6Im5vdGlmX3Byb3Bvc2FsX3RlcmFraGlyXzIzNTAyMzEwMTAiO2k6MTt9', 1779081499);

-- --------------------------------------------------------

--
-- Table structure for table `tinjauan_proposal`
--

CREATE TABLE `tinjauan_proposal` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `nim_nid_reviewer` varchar(20) NOT NULL,
  `catatan` text NOT NULL,
  `file_tinjauan` varchar(255) DEFAULT NULL,
  `tanggal_tinjauan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tinjauan_proposal`
--

INSERT INTO `tinjauan_proposal` (`id`, `proposal_id`, `nim_nid_reviewer`, `catatan`, `file_tinjauan`, `tanggal_tinjauan`) VALUES
(3, 16, '112345678', 'Proposal yang diajukan sudah cukup baik, namun masih terdapat beberapa hal yang perlu diperbaiki. Latar belakang masalah perlu diperkuat dengan data pendukung agar urgensi penelitian lebih jelas. Sela', 'tinjauan/EDM02 (2).pdf', '2026-05-16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `nim_nid` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','dosen','admin') NOT NULL,
  `angkatan` year(4) DEFAULT NULL,
  `foto` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`nim_nid`, `nama`, `email`, `password`, `role`, `angkatan`, `foto`) VALUES
('112345678', 'Jennie', 'jennie@gmail.com', '$2y$12$gI/tc1mZXqOWWk2I/27NPuM1Xl94DZODZWwjCka/65MzIhtLtlC82', 'dosen', NULL, ''),
('12345', 'Pak Budi', 'budi@email.com', '$2y$12$TCr.DdCsuUbMygFbdetbYenH3LiEU45DfrTgB5eNMkoOIvgAMbNFK', 'dosen', NULL, ''),
('1234567', 'elis', 'elis@gmail.com', '$2y$12$7IyVaS0OMik.tIXmK8Ey.uDN1CY2jwq8W25VPnRI6h5swKbi3jgq2', 'dosen', NULL, ''),
('2350231002', 'Jisoo', 'jisoo@gmail.com', '$2y$12$/BCmntPMwcEZdreKKPQ5oOGjZuAyDKZpJNtFbnoShL22rWgqo.0wW', 'mahasiswa', '2023', ''),
('2350231010', 'Rose', 'rose@gmail.com', '$2y$12$iPniyTxlfc6mEWa1SO7xS.AXqcxgNAV/dkQOVt2q2RMi0EkbnLxmu', 'mahasiswa', '2022', 'foto_profil/foto_2350231010.png'),
('2350231017', 'Raisa', 'raisa@gmail.com', '$2y$12$QxHYkedssf8/JjIVNy5gZuuPb1fWMq4.bEZymQdyFJ4MW4yr8JL2m', 'mahasiswa', '2023', ''),
('2350231018', 'Bruno Mars', 'bruno@gmail.com', '$2y$12$DeK0YdlQJCHAz8vTEAdGluJ7kNTxMxlQdRCDD1V7GH97PosJRfieO', 'mahasiswa', '2023', '');

-- --------------------------------------------------------

--
-- Table structure for table `usulan_pembimbing`
--

CREATE TABLE `usulan_pembimbing` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `nim_nid_dosen` varchar(20) NOT NULL,
  `urutan` tinyint(1) NOT NULL COMMENT '1 = Pembimbing 1, 2 = Pembimbing 2',
  `status` enum('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
  `tanggal_usulan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usulan_pembimbing`
--

INSERT INTO `usulan_pembimbing` (`id`, `proposal_id`, `nim_nid_dosen`, `urutan`, `status`, `tanggal_usulan`) VALUES
(27, 16, '112345678', 1, 'disetujui', '2026-05-16'),
(28, 16, '1234567', 2, 'ditolak', '2026-05-16'),
(29, 17, '12345', 1, 'disetujui', '2026-05-16'),
(30, 17, '1234567', 2, 'disetujui', '2026-05-16'),
(31, 18, '1234567', 1, 'ditolak', '2026-05-17'),
(32, 18, '1234567', 2, 'ditolak', '2026-05-17'),
(33, 19, '112345678', 1, 'menunggu', '2026-05-17'),
(34, 19, '1234567', 2, 'menunggu', '2026-05-17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bimbingan`
--
ALTER TABLE `bimbingan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bimbingan_mahasiswa`
--
ALTER TABLE `bimbingan_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nim_nid` (`nim_nid`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `dosen_pembimbing`
--
ALTER TABLE `dosen_pembimbing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`),
  ADD KEY `nim_nid_dosen` (`nim_nid_dosen`);

--
-- Indexes for table `dosen_roles`
--
ALTER TABLE `dosen_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dosen_role` (`nim_nid`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_akademik`
--
ALTER TABLE `jadwal_akademik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panduan_ta_dokumen`
--
ALTER TABLE `panduan_ta_dokumen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_judul`
--
ALTER TABLE `pengajuan_judul`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nim_nid` (`nim_nid`);

--
-- Indexes for table `pengajuan_proposal_bimbingan`
--
ALTER TABLE `pengajuan_proposal_bimbingan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress_ta`
--
ALTER TABLE `progress_ta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_progress_nim` (`nim_nid`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nim_nid` (`nim_nid`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tinjauan_proposal`
--
ALTER TABLE `tinjauan_proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`),
  ADD KEY `nim_nid_reviewer` (`nim_nid_reviewer`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`nim_nid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `usulan_pembimbing`
--
ALTER TABLE `usulan_pembimbing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`),
  ADD KEY `nim_nid_dosen` (`nim_nid_dosen`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bimbingan`
--
ALTER TABLE `bimbingan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bimbingan_mahasiswa`
--
ALTER TABLE `bimbingan_mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `dosen_pembimbing`
--
ALTER TABLE `dosen_pembimbing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `dosen_roles`
--
ALTER TABLE `dosen_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_akademik`
--
ALTER TABLE `jadwal_akademik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `panduan_ta_dokumen`
--
ALTER TABLE `panduan_ta_dokumen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pengajuan_judul`
--
ALTER TABLE `pengajuan_judul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `pengajuan_proposal_bimbingan`
--
ALTER TABLE `pengajuan_proposal_bimbingan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `progress_ta`
--
ALTER TABLE `progress_ta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tinjauan_proposal`
--
ALTER TABLE `tinjauan_proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usulan_pembimbing`
--
ALTER TABLE `usulan_pembimbing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dosen_pembimbing`
--
ALTER TABLE `dosen_pembimbing`
  ADD CONSTRAINT `fk_dospem_dosen` FOREIGN KEY (`nim_nid_dosen`) REFERENCES `users` (`nim_nid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dospem_proposal` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dosen_roles`
--
ALTER TABLE `dosen_roles`
  ADD CONSTRAINT `fk_dosen_role` FOREIGN KEY (`nim_nid`) REFERENCES `users` (`nim_nid`) ON DELETE CASCADE;

--
-- Constraints for table `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `fk_proposal_users` FOREIGN KEY (`nim_nid`) REFERENCES `users` (`nim_nid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tinjauan_proposal`
--
ALTER TABLE `tinjauan_proposal`
  ADD CONSTRAINT `fk_tinjauan_proposal` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tinjauan_reviewer` FOREIGN KEY (`nim_nid_reviewer`) REFERENCES `users` (`nim_nid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usulan_pembimbing`
--
ALTER TABLE `usulan_pembimbing`
  ADD CONSTRAINT `fk_usulan_dosen` FOREIGN KEY (`nim_nid_dosen`) REFERENCES `users` (`nim_nid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usulan_proposal` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
