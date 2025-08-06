-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2025 at 01:47 PM
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
-- Database: `obs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_reports`
--

CREATE TABLE IF NOT EXISTS `daily_reports` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `zone` varchar(255) DEFAULT NULL,
  `report` text NOT NULL,
  `report_date` date NOT NULL,
  `report_time` time NOT NULL,
  `shift` enum('day','night') NOT NULL,
  `manager_input` text DEFAULT NULL,
  `director_input` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_daily_reports_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_reports`
--

INSERT INTO `daily_reports` (`id`, `user_id`, `zone`, `report`, `report_date`, `report_time`, `shift`, `manager_input`, `director_input`, `created_at`, `updated_at`) VALUES
(9, 11, 'Nyayo', 'Enter your report here.', '2025-08-01', '11:58:00', 'day', NULL, NULL, '2025-08-01 05:58:31', '2025-08-01 05:58:31'),
(10, 11, 'Western', 'Okay', '2025-08-01', '12:04:00', 'day', NULL, NULL, '2025-08-01 06:04:54', '2025-08-01 06:04:54'),
(11, 11, 'Western', 'Report', '2025-08-01', '14:59:00', 'day', NULL, NULL, '2025-08-01 08:59:14', '2025-08-01 08:59:14'),
(12, 11, NULL, 'Report', '2025-08-04', '09:17:00', 'day', NULL, NULL, '2025-08-04 03:17:46', '2025-08-04 03:17:46'),
(13, 11, 'Western', 'Report', '2025-08-07', '09:19:00', 'day', NULL, NULL, '2025-08-04 03:20:07', '2025-08-04 03:20:07'),
(14, 11, 'Nyayo', 'Report', '2025-08-09', '09:20:00', 'day', NULL, NULL, '2025-08-04 03:20:19', '2025-08-04 03:20:19'),
(15, 11, NULL, 'Report', '2025-08-20', '09:38:00', 'day', NULL, NULL, '2025-08-05 03:39:16', '2025-08-05 03:39:16');

-- --------------------------------------------------------

--
-- Table structure for table `escalation_matrix`
--

CREATE TABLE IF NOT EXISTS `escalation_matrix` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `escalation_matrix_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `escalation_matrix`
--

INSERT INTO `escalation_matrix` (`id`, `department_name`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Health Unit', 'health@gmail.com', '2025-08-05 03:08:05', '2025-08-05 03:08:05'),
(2, 'OSA', 'intern-ict@ku.ac.ke', '2025-08-05 04:13:16', '2025-08-05 04:13:16');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedback_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `created_at`, `updated_at`, `user_id`, `message`, `subject`) VALUES
(1, '2025-07-29 05:17:36', '2025-07-29 05:17:36', NULL, 'Good', 'Can we discuss this case?');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `occurrence_id` bigint(20) UNSIGNED NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `occurrence_id` (`occurrence_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `occurrence_id`, `original_name`, `created_at`, `updated_at`) VALUES
(19, 16, '1754375296_6891a480806d8_OBS All Occurrences (1).pdf', '2025-08-05 03:28:17', '2025-08-05 03:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `hostels`
--

CREATE TABLE IF NOT EXISTS `hostels` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `number_of_students` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_hostels_zone` (`zone_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostels`
--

INSERT INTO `hostels` (`id`, `zone_id`, `name`, `number_of_students`, `created_at`, `updated_at`) VALUES
(1, 1, 'Longonot', 1123, '2025-07-29 08:41:01', '2025-07-29 08:47:30'),
(2, 2, 'Nyayo 1', 300, '2025-08-01 04:39:50', '2025-08-01 04:39:50'),
(3, 2, 'Nyayo 2', 5000, '2025-08-04 06:11:49', '2025-08-04 06:11:49'),
(4, 2, 'Nyayo 2', 5000, '2025-08-04 06:12:13', '2025-08-04 06:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_29_075525_create_feedback_table', 2),
(5, '2025_08_05_054959_create_escalation_matrix_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `occurrences`
--

CREATE TABLE IF NOT EXISTS `occurrences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `shift` varchar(20) NOT NULL,
  `hostel` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `nature` text NOT NULL,
  `occurrence_type` varchar(50) NOT NULL,
  `action_taken` text NOT NULL,
  `resolution` text NOT NULL,
  `resolved` enum('yes','no') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `manager_input` text DEFAULT NULL,
  `director_input` text DEFAULT NULL,
  `zonal_officer_input` text DEFAULT NULL,
  `administrator_input` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `occurrences`
--

INSERT INTO `occurrences` (`id`, `user_id`, `shift`, `hostel`, `date`, `time`, `nature`, `occurrence_type`, `action_taken`, `resolution`, `resolved`, `created_at`, `updated_at`, `manager_input`, `director_input`, `zonal_officer_input`, `administrator_input`) VALUES
(13, 11, 'Day', 'Longonot', '2025-08-04', '09:15:00', 'Nature of Occurrence', 'Theft', 'Action Taken', 'Resolution / Outcome', 'yes', '2025-08-04 03:11:27', '2025-08-04 03:11:27', NULL, NULL, NULL, ''),
(14, 11, 'Day', 'Longonot', '2025-08-04', '12:11:00', 'Nature of Occurrence', 'Theft', 'Action Taken', 'Resolution / Outcome', 'yes', '2025-08-04 03:11:55', '2025-08-04 03:11:55', NULL, NULL, NULL, ''),
(15, 11, 'Day', 'Nyayo 1', '2025-08-04', '09:14:00', 'Resolution / Outcome', 'Fire', 'Resolution / Outcome', 'Resolution / Outcome', 'yes', '2025-08-04 03:12:17', '2025-08-04 03:12:17', NULL, NULL, NULL, ''),
(16, 11, 'Day', 'Nyayo 1', '2025-08-05', '09:29:00', 'Nature of Occurrence', 'Health', 'Action Taken', 'Resolution / Outcome', 'yes', '2025-08-05 03:28:16', '2025-08-05 08:07:18', NULL, NULL, 'Zonal Officer inputBy Isaiah Omulo', 'My input:By Isaiah Omulo'),
(17, 11, 'Day', 'Nyayo 1', '2025-08-05', '03:03:00', 'Nature of Occurrence', 'Fight', 'escalate', 'escalate to security office', 'no', '2025-08-05 03:31:27', '2025-08-05 08:08:11', 'aaction recommended', NULL, NULL, 'Hi:By Isaiah Omulo');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0WLjXXU5oHsxnevi70bSYB6BRlFHd4NfKP1Vgb8x', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMktmVk9va0lsZmVSeTV4MUlPekVwaU1HYUZDU0QwcHphV2wxeUoySiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ob3N0ZWxzL2NoYXJ0LWRhdGE/cGVyaW9kPW1vbnRobHkiO31zOjU6InN0YXRlIjtzOjQwOiJsZGhYemxUeFpyZXJsQVgxQkRldUNFejVFS1hXempXeUN6TEVwNmhEIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMjt9', 1754395675),
('nxdPFB1rVss8L8q7sBIPT7YvktSx47vpJXdEnXBS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDJsQkw0bmZYN2h6dkN3ZzN2YzhSQkhwc3ZxSmVuN1ZxRXF3dHdjSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbi9nb29nbGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU6InN0YXRlIjtzOjQwOiJVOTFGYWxubTQ5WmhDVllsRzZkYk5Hc3NISEoyeUhnT1M4N25vWmFlIjt9', 1754391628);

-- --------------------------------------------------------

--
-- Table structure for table `student_statistics`
--

CREATE TABLE IF NOT EXISTS `student_statistics` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `hostel_id` bigint(20) UNSIGNED NOT NULL,
  `record_date` date NOT NULL,
  `students_present` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `shift` enum('day','night') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_statistics_user_id_foreign` (`user_id`),
  KEY `student_statistics_hostel_id_foreign` (`hostel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_statistics`
--

INSERT INTO `student_statistics` (`id`, `user_id`, `hostel_id`, `record_date`, `students_present`, `comments`, `shift`, `created_at`, `updated_at`) VALUES
(4, 11, 1, '2025-08-01', 299, NULL, 'night', '2025-08-01 04:38:40', '2025-08-01 04:38:40'),
(5, 11, 2, '2025-08-01', 3000, NULL, 'day', '2025-08-01 04:45:14', '2025-08-01 04:45:14'),
(7, 11, 2, '2025-08-04', 800, 'Comments', 'night', '2025-08-04 03:27:12', '2025-08-04 03:27:12'),
(8, 11, 2, '2025-08-04', 5600, 'Comments', 'day', '2025-08-04 05:01:48', '2025-08-04 05:01:48'),
(9, 11, 2, '2025-07-15', 9000, NULL, 'day', '2025-08-04 05:56:54', '2025-08-04 05:56:54'),
(10, 11, 2, '2025-08-04', 600, NULL, 'night', '2025-08-04 05:58:37', '2025-08-04 05:58:37'),
(11, 11, 4, '2025-08-04', 5000, 'Initial hostel student count on creation.', 'day', '2025-08-04 06:12:13', '2025-08-04 06:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `logged_out_at` timestamp NULL DEFAULT NULL,
  `logged_in_at` timestamp NULL DEFAULT NULL,
  `role` enum('house_keeper','administrator','zonal_officer','director','manager','coordinator','hostel_attendant') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `google_id`, `logged_out_at`, `logged_in_at`, `role`, `phone`) VALUES
(6, 'Isaiah3', 'isai3ah132@gmail.com', NULL, NULL, NULL, '2025-07-29 03:40:36', '2025-07-29 03:40:36', NULL, NULL, NULL, NULL, NULL),
(7, 'Isaiah', 'isaiwah132@gmail.com', NULL, NULL, NULL, '2025-07-29 03:41:41', '2025-07-29 03:41:41', NULL, NULL, NULL, 'house_keeper', '7462827602'),
(8, 'Isaiah', 'omulo1developer@gmail.com', NULL, NULL, NULL, '2025-07-29 03:45:38', '2025-07-29 03:45:38', NULL, NULL, NULL, 'manager', '1746282760'),
(9, 'Mangoes', 'isaia2h132@gmail.com', NULL, NULL, NULL, '2025-07-29 03:46:31', '2025-07-29 03:46:31', NULL, NULL, NULL, 'house_keeper', '02746282760'),
(11, 'Isaiah Omulo', 'omulodeveloper@gmail.com', '2025-07-30 02:47:58', '$2y$12$rYyOr0eWLA18BLwL/c2ZGuJ7u3GAC6zJC7BHhCGkG.g7QCT45tzpa', NULL, '2025-07-30 02:48:00', '2025-08-05 09:05:42', '103535761912352333445', '2025-08-05 09:05:42', '2025-08-05 08:57:36', 'zonal_officer', '07462827633'),
(12, 'Intern ICT', 'intern-ict@ku.ac.ke', NULL, NULL, NULL, '2025-08-05 08:47:33', '2025-08-05 09:07:11', '116680174322884546765', '2025-08-05 08:57:27', '2025-08-05 09:05:56', 'house_keeper', '23443652');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE IF NOT EXISTS `zones` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Western', '2025-07-29 06:46:21', '2025-07-29 08:27:36'),
(2, 'Nyayo', '2025-08-01 04:39:25', '2025-08-01 04:39:25');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD CONSTRAINT `fk_daily_reports_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`occurrence_id`) REFERENCES `occurrences` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hostels`
--
ALTER TABLE `hostels`
  ADD CONSTRAINT `fk_hostels_zone` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `occurrences`
--
ALTER TABLE `occurrences`
  ADD CONSTRAINT `occurrences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_statistics`
--
ALTER TABLE `student_statistics`
  ADD CONSTRAINT `student_statistics_hostel_id_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_statistics_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
