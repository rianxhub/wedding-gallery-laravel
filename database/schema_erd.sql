-- ==============================================================
-- DATABASE: wedding_gallery
-- SKEMA LENGKAP: ERD + SISTEM LARAVEL + GALERI FOTO DRIVE
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `wedding_gallery` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `wedding_gallery`;

SET FOREIGN_KEY_CHECKS = 0;

-- Bersihkan tabel lama jika ada
DROP TABLE IF EXISTS `download_logs`;
DROP TABLE IF EXISTS `photos`;
DROP TABLE IF EXISTS `albums`;
DROP TABLE IF EXISTS `weddings`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;


-- ==============================================================
-- BAGIAN 1: TABEL UTAMA SESUAI ERD
-- ==============================================================

-- 1.1 TABEL USERS (ERD: USERS)
-- Relasi: USERS (1) mengelola (N) WEDDINGS
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 1.2 TABEL WEDDINGS (ERD: WEDDINGS)
-- Relasi: 
--   - Dikelola oleh USERS (user_id FK)
--   - Memiliki banyak ALBUMS (1 to N)
--   - Mencatat banyak DOWNLOAD_LOGS (1 to N)
CREATE TABLE `weddings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `couple_name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `wedding_date` DATE DEFAULT NULL,
  `drive_folder_id` VARCHAR(255) NOT NULL,
  `cover_drive_file_id` VARCHAR(255) DEFAULT NULL,
  `quote` TEXT DEFAULT NULL,
  `theme` VARCHAR(100) DEFAULT 'Rustic Floral',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `weddings_slug_unique` (`slug`),
  KEY `weddings_user_id_foreign` (`user_id`),
  CONSTRAINT `weddings_user_id_foreign` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 1.3 TABEL ALBUMS (ERD: ALBUMS)
-- Relasi: Merujuk ke WEDDINGS (wedding_id FK)
CREATE TABLE `albums` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `wedding_id` BIGINT UNSIGNED NOT NULL,
  `album_name` VARCHAR(255) NOT NULL,
  `drive_subfolder_id` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `albums_wedding_id_foreign` (`wedding_id`),
  CONSTRAINT `albums_wedding_id_foreign` 
    FOREIGN KEY (`wedding_id`) REFERENCES `weddings` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 1.4 TABEL DOWNLOAD_LOGS (ERD: DOWNLOAD_LOGS)
-- Relasi: Merujuk ke WEDDINGS (wedding_id FK)
CREATE TABLE `download_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `wedding_id` BIGINT UNSIGNED NOT NULL,
  `drive_file_id` VARCHAR(255) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `downloaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `download_logs_wedding_id_foreign` (`wedding_id`),
  CONSTRAINT `download_logs_wedding_id_foreign` 
    FOREIGN KEY (`wedding_id`) REFERENCES `weddings` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==============================================================
-- BAGIAN 2: TABEL PENDUKUNG GALERI FOTO DRIVE
-- ==============================================================
CREATE TABLE IF NOT EXISTS `photos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `wedding_id` BIGINT UNSIGNED NOT NULL,
  `drive_file_id` VARCHAR(255) NOT NULL,
  `nama_file` VARCHAR(255) NOT NULL,
  `mime_type` VARCHAR(255) DEFAULT NULL,
  `thumbnail_url` VARCHAR(255) DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `photos_drive_file_id_unique` (`drive_file_id`),
  KEY `photos_wedding_id_foreign` (`wedding_id`),
  CONSTRAINT `photos_wedding_id_foreign` 
    FOREIGN KEY (`wedding_id`) REFERENCES `weddings` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==============================================================
-- BAGIAN 3: TABEL SISTEM LARAVEL (SESSIONS, CACHE, JOBS)
-- Mencegah error 'Base table or view not found: sessions'
-- ==============================================================
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED DEFAULT NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==============================================================
-- DATA AWAL / SAMPLE SEED (OPSIONAL)
-- Password default user: password123
-- ==============================================================
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Administrator', 'admin@weddinggallery.com', '$2y$12$eG84G7rM2kG18F8N0eX.V.X92F0o8A6C5nOa8lQ3dJzQxHw4oI7qK', 'admin'),
(2, 'Rian & Aulia', 'rian.aulia@gmail.com', '$2y$12$eG84G7rM2kG18F8N0eX.V.X92F0o8A6C5nOa8lQ3dJzQxHw4oI7qK', 'user');

INSERT INTO `weddings` (`id`, `user_id`, `couple_name`, `slug`, `wedding_date`, `drive_folder_id`, `cover_drive_file_id`, `quote`, `theme`) VALUES
(1, 2, 'Rian & Aulia', 'rian-dan-aulia', '2026-12-20', '1nKq_OpBkPb8bp6bvcL7OyeZmHk6TZBrB', 'cover_rian_aulia_001', 'Two souls, one heart. Bersama melangkah dalam cinta selamanya.', 'Rustic Floral');

INSERT INTO `albums` (`id`, `wedding_id`, `album_name`, `drive_subfolder_id`, `sort_order`) VALUES
(1, 1, 'Prewedding Photo Shoot', 'subfolder_prewed_001', 1),
(2, 1, 'Akad Nikah & Ijab Qabul', 'subfolder_akad_002', 2),
(3, 1, 'Resepsi & Pesta Malam', 'subfolder_resepsi_003', 3);

INSERT INTO `download_logs` (`id`, `wedding_id`, `drive_file_id`, `file_name`, `ip_address`, `downloaded_at`) VALUES
(1, 1, 'file_img_001_highres', 'DSC_0012_Akad_HD.jpg', '192.168.1.15', NOW() - INTERVAL 2 HOUR),
(2, 1, 'file_img_002_highres', 'DSC_0045_Resepsi_HD.jpg', '114.124.200.5', NOW() - INTERVAL 1 HOUR);
