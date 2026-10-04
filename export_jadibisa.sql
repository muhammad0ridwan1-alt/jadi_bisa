-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: jadibisa
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `absensi_details`
--

DROP TABLE IF EXISTS `absensi_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `absensi_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpha') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hadir',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensi_details_absensi_id_foreign` (`absensi_id`),
  KEY `absensi_details_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `absensi_details_absensi_id_foreign` FOREIGN KEY (`absensi_id`) REFERENCES `absensis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_details_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absensi_details`
--

LOCK TABLES `absensi_details` WRITE;
/*!40000 ALTER TABLE `absensi_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `absensi_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `absensis`
--

DROP TABLE IF EXISTS `absensis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `pertemuan_ke` int NOT NULL,
  `topik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absensis_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `absensis_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absensis`
--

LOCK TABLES `absensis` WRITE;
/*!40000 ALTER TABLE `absensis` DISABLE KEYS */;
/*!40000 ALTER TABLE `absensis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `angkatans`
--

DROP TABLE IF EXISTS `angkatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `angkatans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_angkatan` int NOT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `angkatans_nomor_angkatan_unique` (`nomor_angkatan`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `angkatans`
--

LOCK TABLES `angkatans` WRITE;
/*!40000 ALTER TABLE `angkatans` DISABLE KEYS */;
INSERT INTO `angkatans` VALUES (2,30,'2026/2027',1,'2026-08-17 08:38:43','2026-10-04 00:22:10');
/*!40000 ALTER TABLE `angkatans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bst_scores`
--

DROP TABLE IF EXISTS `bst_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bst_scores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `cpm` int NOT NULL,
  `wpm` int NOT NULL,
  `accuracy` double NOT NULL,
  `cawu` int NOT NULL DEFAULT '3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bst_scores_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `bst_scores_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bst_scores`
--

LOCK TABLES `bst_scores` WRITE;
/*!40000 ALTER TABLE `bst_scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `bst_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('jadibisa-cache-ek@jadibisa.com|::1','i:1;',1788261827),('jadibisa-cache-ek@jadibisa.com|::1:timer','i:1788261827;',1788261827),('jadibisa-cache-mahasiswa_bm@jadibisa.com|::1','i:1;',1788261699),('jadibisa-cache-mahasiswa_bm@jadibisa.com|::1:timer','i:1788261699;',1788261699),('jadibisa-cache-yu@jadibisa.com|::1','i:1;',1788261821),('jadibisa-cache-yu@jadibisa.com|::1:timer','i:1788261821;',1788261821);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwals`
--

DROP TABLE IF EXISTS `jadwals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `angkatan` int NOT NULL DEFAULT '29',
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2025/2026',
  `dosen_id` bigint unsigned DEFAULT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'R. Kelas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwals_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `jadwals_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `jadwals_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `jadwals_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwals`
--

LOCK TABLES `jadwals` WRITE;
/*!40000 ALTER TABLE `jadwals` DISABLE KEYS */;
/*!40000 ALTER TABLE `jadwals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas_list`
--

DROP TABLE IF EXISTS `kelas_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_list` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Administrasi Perkantoran',
  `angkatan` int NOT NULL DEFAULT '29',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas_list`
--

LOCK TABLES `kelas_list` WRITE;
/*!40000 ALTER TABLE `kelas_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `kelas_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_kuliahs`
--

DROP TABLE IF EXISTS `mata_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliahs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_mk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` enum('Administrasi Perkantoran','Bisnis Manajemen') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cawu` int NOT NULL DEFAULT '3',
  `angkatan` int NOT NULL DEFAULT '29',
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2025/2026',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dosen_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_kuliahs_kode_mk_unique` (`kode_mk`),
  KEY `mata_kuliahs_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `mata_kuliahs_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_kuliahs`
--

LOCK TABLES `mata_kuliahs` WRITE;
/*!40000 ALTER TABLE `mata_kuliahs` DISABLE KEYS */;
/*!40000 ALTER TABLE `mata_kuliahs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_19_061303_create_mata_kuliahs_table',1),(5,'2026_07_19_061304_create_moduls_table',1),(6,'2026_07_19_062515_add_file_and_youtube_to_moduls_table',1),(7,'2026_07_19_063520_add_dosen_id_to_mata_kuliahs_table',1),(8,'2026_07_19_134546_create_nilais_table',1),(9,'2026_08_02_000001_add_campus_fields_to_users_table',1),(10,'2026_08_02_000002_create_pengumumans_table',1),(11,'2026_08_02_000003_create_tugases_and_submissions_table',1),(12,'2026_08_02_000004_create_absensis_table',1),(13,'2026_08_02_000005_create_jadwals_table',1),(14,'2026_08_02_000006_create_modul_progress_table',1),(15,'2026_08_03_000007_update_jadibisa_schema_for_bec',1),(16,'2026_08_05_000001_update_schema_for_angkatan_and_features',1),(17,'2026_08_05_000002_create_kelas_and_angkatans_table',1),(18,'2026_08_05_000003_add_completed_fields_to_modul_progresses_table',1),(19,'2026_08_05_000004_add_dosen_teaching_fields_to_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modul_progresses`
--

DROP TABLE IF EXISTS `modul_progresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modul_progresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `modul_id` bigint unsigned NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modul_progresses_mahasiswa_id_modul_id_unique` (`mahasiswa_id`,`modul_id`),
  KEY `modul_progresses_modul_id_foreign` (`modul_id`),
  CONSTRAINT `modul_progresses_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `modul_progresses_modul_id_foreign` FOREIGN KEY (`modul_id`) REFERENCES `moduls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modul_progresses`
--

LOCK TABLES `modul_progresses` WRITE;
/*!40000 ALTER TABLE `modul_progresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `modul_progresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moduls`
--

DROP TABLE IF EXISTS `moduls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `moduls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('materi','kuis') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `moduls_dosen_id_foreign` (`dosen_id`),
  KEY `moduls_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `moduls_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `moduls_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moduls`
--

LOCK TABLES `moduls` WRITE;
/*!40000 ALTER TABLE `moduls` DISABLE KEYS */;
/*!40000 ALTER TABLE `moduls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilais`
--

DROP TABLE IF EXISTS `nilais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilais` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `modul_id` bigint unsigned NOT NULL,
  `score` int DEFAULT NULL,
  `status` enum('submitted','graded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nilais_mahasiswa_id_foreign` (`mahasiswa_id`),
  KEY `nilais_modul_id_foreign` (`modul_id`),
  CONSTRAINT `nilais_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilais_modul_id_foreign` FOREIGN KEY (`modul_id`) REFERENCES `moduls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilais`
--

LOCK TABLES `nilais` WRITE;
/*!40000 ALTER TABLE `nilais` DISABLE KEYS */;
/*!40000 ALTER TABLE `nilais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengumumans`
--

DROP TABLE IF EXISTS `pengumumans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengumumans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` enum('semua','Administrasi Perkantoran','Bisnis Manajemen') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'semua',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengumumans_user_id_foreign` (`user_id`),
  CONSTRAINT `pengumumans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengumumans`
--

LOCK TABLES `pengumumans` WRITE;
/*!40000 ALTER TABLE `pengumumans` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengumumans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('1mtIuicr27PIoX3ozAIAKdFxrEZ2H72zSdPskMeE',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.7725','eyJfdG9rZW4iOiJPMVNRV2ZidVBSOGVUMGJMRVk5cXZheFQ5TUcyTkhjWnpyYXpvR29ZIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvamFkaWJpc2FcL3B1YmxpY1wvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1791098976),('33Q7inUnUQv8EVA2GQ7jpmUt05VjEOsXGhvu3QPS',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJGNFlQMENCZnRVWGs1U0VuUGtqbWxJSjkzOW1NZVI4eXpQaUw1UHVYIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9qYWRpYmlzYVwvcHVibGljXC9hcGlcL25vdGlmaWNhdGlvbnNcL3BvbGw/c2luY2U9MjAyNi0xMC0wNFQxMCUzQTA0JTNBMDclMkIwMCUzQTAwIiwicm91dGUiOiJhcGkubm90aWZpY2F0aW9ucy5wb2xsIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==',1791108253),('9Oim9B1re7YbptxSaquTxEagUrI8LDiSUyR0uvGc',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.7725','eyJfdG9rZW4iOiJVUVpLbU13R2trN0duWGJqSVliMm9RWDAzTnJlYnc1NDdVRUJFd3JPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvamFkaWJpc2FcL3B1YmxpY1wvcmVnaXN0ZXIiLCJyb3V0ZSI6InJlZ2lzdGVyIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1791099581),('rxFO7C77y3uo34LJaDUEUNejTPNiFW4zaNZaOjH8',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ6TFE5Wk1PVXRaQml3ejFOTlBKcHY3anZiYlo4b2NTT2hmSU1Mc2JLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvamFkaWJpc2FcL3B1YmxpY1wvYXBpXC9ub3RpZmljYXRpb25zXC9wb2xsP3NpbmNlPTIwMjYtMTAtMDRUMDclM0E0MiUzQTE0JTJCMDAlM0EwMCIsInJvdXRlIjoiYXBpLm5vdGlmaWNhdGlvbnMucG9sbCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1791099736),('sUVnrvvPXnSoBzlpAwlvkxq4mOwJzKQVROQCjAPA',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.7725','eyJfdG9rZW4iOiJ0QzR5TXFHYXZ6MWlUYXRqZlhwTm9ZZkcyQW1WTHBYWHB3aEtpWkVCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvamFkaWJpc2FcL3B1YmxpY1wvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1791099575),('UNkZKQBAA0DhSrc47iP7FURBscQ3M4hroJpjEmFm',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJMOHNPb1dtWGRiN0dvMk1ISUZNVkJoa3FoU1h0eDAyTWRuR3gxM3NMIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvamFkaWJpc2FcL3B1YmxpY1wvYWRtaW5cL21hdGt1bCIsInJvdXRlIjoiYWRtaW4ubWF0a3VsIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9',1791097146);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tugas_submissions`
--

DROP TABLE IF EXISTS `tugas_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tugas_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tugas_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `score` int DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `status` enum('submitted','graded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugas_submissions_tugas_id_foreign` (`tugas_id`),
  KEY `tugas_submissions_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `tugas_submissions_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tugas_submissions_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tugas_submissions`
--

LOCK TABLES `tugas_submissions` WRITE;
/*!40000 ALTER TABLE `tugas_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tugas_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tugases`
--

DROP TABLE IF EXISTS `tugases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tugases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `angkatan` int NOT NULL DEFAULT '29',
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2025/2026',
  `max_score` int NOT NULL DEFAULT '100',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugases_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `tugases_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `tugases_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tugases_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tugases`
--

LOCK TABLES `tugases` WRITE;
/*!40000 ALTER TABLE `tugases` DISABLE KEYS */;
/*!40000 ALTER TABLE `tugases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','dosen','mahasiswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `kode_dosen` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mata_kuliah_diampu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sesi_per_kelas` int DEFAULT '1',
  `jurusan` enum('Administrasi Perkantoran','Bisnis Manajemen') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `angkatan` int NOT NULL DEFAULT '29',
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2025/2026',
  `cawu` int NOT NULL DEFAULT '3',
  `semester` int NOT NULL DEFAULT '1',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator BEC','admin@jadibisa.com',NULL,NULL,'$2y$12$4CZ8iWSRr92rlYuqE1JpQeRoeA871Rg0QLqpmiFi2/i2v.AQ3zODW','admin',NULL,NULL,1,'Administrasi Perkantoran',NULL,30,'2026/2027',3,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:43','2026-10-04 00:52:42'),(2,'Arwadi','ar@jadibisa.com',NULL,NULL,'$2y$12$x8O8GpR4rxj2.huAwFCY3.mS2VPu1RuRcrynZdjdY2VSUwXXLkgUC','dosen','AR',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:43','2026-10-04 00:54:37'),(3,'Candra Pradipta','ca@jadibisa.com',NULL,NULL,'$2y$12$UI/BugtV6N11vL8DherYUeym8HPZvozBrjL9eZfEGn.ByITpWsaje','dosen','CA',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:44','2026-10-04 00:52:42'),(4,'Chamirsyah Puspitasari','cp@jadibisa.com',NULL,NULL,'$2y$12$tyDRcNMFyx.JPisM93shzubSaRqQ0Ou6LXLWdr33kLUOgpGMqtD2G','dosen','CP',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:44','2026-10-04 00:52:43'),(5,'Dian Ardiyansah','da@jadibisa.com',NULL,NULL,'$2y$12$gn.5pzHhI9So69fzbibmceJxFxyN4HuWG6WE/R2w3OP272jQw1Gnu','dosen','DA',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:44','2026-10-04 00:52:43'),(6,'Shoni Hamdani','sh@jadibisa.com',NULL,NULL,'$2y$12$ks4QRg4AYJpM8h5FwZ.8.u6UXfZV.bkvMT99mNhMPXxuJUPbwo/CS','dosen','SH',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:44','2026-10-04 00:52:43'),(7,'Deva Ardiansyah','dv@jadibisa.com',NULL,NULL,'$2y$12$F1j2fp3mQE.CIounnJ3kQO79o8/PDXiPoNfO/cL/zxf/ClofF1Kua','dosen','DV',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:45','2026-10-04 00:52:43'),(8,'Ema Hermawati','eh@jadibisa.com',NULL,NULL,'$2y$12$Gh2u1Zo98MjKsIN6qxoTbu7hOoMf3lxAoBQYRZHca6zxqBmOOhKR6','dosen','EH',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:45','2026-10-04 00:52:44'),(9,'Eka Marindra Susilowati','em@jadibisa.com',NULL,NULL,'$2y$12$otuA86xq6ON6GqdSHadx4Og.FIFa6VtFm9NRh2GNMLe/CInmhVvya','dosen','EM',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:45','2026-10-04 00:52:44'),(10,'Chandra Sentosa','cs@jadibisa.com',NULL,NULL,'$2y$12$/WPstFKB5EeqLWVDVkOLTudyOWMAIPzvhzjngEUjUzbfm0/I5G.fK','dosen','CS',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:46','2026-10-04 00:52:44'),(11,'Maulana Rahman','mr@jadibisa.com',NULL,NULL,'$2y$12$aJr5TRp8hANGxr//BDumSeVpC/UMFfFl2mvQprh.nkNpaMAnJ2rLC','dosen','MR',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:46','2026-10-04 00:52:45'),(12,'Lindawati','ld@jadibisa.com',NULL,NULL,'$2y$12$2jllPJ59bSdqLGPz95Nh1ez7eLX89SCdypzrLExInsBQEL9cq1SlW','dosen','LD',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:46','2026-10-04 00:52:45'),(13,'Nurul Af Idah Widyaningsih','na@jadibisa.com',NULL,NULL,'$2y$12$vdmwCb2QRpeaIPQq/0G0rOCqObDU5Kgkz0eAVO5JqHraxnlAflkGi','dosen','NA',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:46','2026-10-04 00:52:45'),(14,'Nur Hanifah','nh@jadibisa.com',NULL,NULL,'$2y$12$GD2A3PJEms2j2FaUy4uFIu6YIYmWm/whqXJwB6bdmKTxIub2C2LbS','dosen','NH',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:47','2026-10-04 00:52:46'),(15,'Rani Fidiyanti','rf@jadibisa.com',NULL,NULL,'$2y$12$gRcYgM0PeLLrGBydMOmkH.NW53o4e3yUnMw.kYZSXeLVzDTI3DIvG','dosen','RF',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:47','2026-10-04 00:52:46'),(16,'Sofyan Nata Komaradjaja','sk@jadibisa.com',NULL,NULL,'$2y$12$4FzJnh67Q5vJztgWnyA2yezRRtuBBM83tOkY21ZsYrKMf56tsLGA2','dosen','SK',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:47','2026-10-04 00:52:46'),(17,'Sidik Nurrohman','sn@jadibisa.com',NULL,NULL,'$2y$12$ctcoReO4OTk6d4SVQDNiPeQ5k5EqCkkviH1IVC1Sc29gJAeGlfhLK','dosen','SN',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:47','2026-10-04 00:52:46'),(18,'Wiwi Alwiyah','wa@jadibisa.com',NULL,NULL,'$2y$12$F5.zFzoLjPumQajTCxMg9.N886Ron6hNVzoiWPEOJxNc/Jb1DNoKa','dosen','WA',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:48','2026-10-04 00:52:47'),(19,'Yuli Ekawati','ye@jadibisa.com',NULL,NULL,'$2y$12$PDsEsCU2jNvJ0Btl3fj/vuxlqQxJuMLdeRDQtprf03qI8F4GcR4Ya','dosen','YE',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:48','2026-10-04 00:52:47'),(20,'Yuni Sutari','ys@jadibisa.com',NULL,NULL,'$2y$12$PgOBmclspGlSe9Ow3X1zfeTkl58XZRnVAWpdbSFML.tLA0jLC4XeO','dosen','YS',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:48','2026-10-04 00:52:47'),(21,'Moedjiwati Sajid (Titiek)','ti@jadibisa.com',NULL,NULL,'$2y$12$QQ616Iq0EKcCI5w53IPNyueoZKmgzAJz1Ggd19U4qVL6B5OTOUrjy','dosen','TI',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:49','2026-10-04 00:52:48'),(22,'Yusuf Saefudin','yf@jadibisa.com',NULL,NULL,'$2y$12$V3G4ffIrojQluhkRaHwNR.sOJ4dC8vgB57zsZwQycOf5G4O.d5He.','dosen','YF',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:49','2026-10-04 00:52:48'),(23,'Deden Supriatna','ds@jadibisa.com',NULL,NULL,'$2y$12$.C0lZJBYu.WOWSChs2Llcu2FxQmWUtqhyhaK6Sgp9Yn8Ey2l40o4q','dosen','DS',NULL,2,'Administrasi Perkantoran',NULL,30,'2026/2027',1,1,NULL,NULL,NULL,NULL,'2026-08-17 08:38:49','2026-10-04 00:52:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-04 17:33:00
