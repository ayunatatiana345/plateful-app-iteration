-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Apr 2026 pada 14.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `plateful-app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `analytics_logs`
--

CREATE TABLE `analytics_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `food_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_type` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `analytics_logs`
--

INSERT INTO `analytics_logs` (`id`, `user_id`, `food_item_id`, `action_type`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'inventory_added', '2026-03-26 02:00:00', '2026-03-26 02:00:00'),
(2, 1, 8, 'inventory_added', '2026-03-29 03:00:00', '2026-03-29 03:00:00'),
(3, 1, NULL, 'meal_planned', '2026-03-30 11:00:00', '2026-03-30 11:00:00'),
(4, 1, NULL, 'meal_completed', '2026-03-30 13:00:00', '2026-03-30 13:00:00'),
(5, 1, 3, 'donated', '2026-03-30 09:00:00', '2026-03-30 09:00:00'),
(6, 2, 8, 'donation_claimed', '2026-03-30 10:00:00', '2026-03-30 10:00:00'),
(7, 2, NULL, 'meal_planned', '2026-03-29 12:00:00', '2026-03-29 12:00:00'),
(8, 3, NULL, 'meal_planned', '2026-03-28 05:00:00', '2026-03-28 05:00:00'),
(9, 1, 10, 'donated', '2026-03-31 13:33:21', '2026-03-31 13:33:21'),
(10, 1, 26, 'inventory_added', '2026-03-27 02:00:00', '2026-03-27 02:00:00'),
(11, 1, 31, 'inventory_added', '2026-03-30 03:00:00', '2026-03-30 03:00:00'),
(12, 1, NULL, 'meal_planned', '2026-03-31 11:00:00', '2026-03-31 11:00:00'),
(13, 1, NULL, 'meal_completed', '2026-03-31 13:00:00', '2026-03-31 13:00:00'),
(14, 1, 26, 'donated', '2026-03-31 09:00:00', '2026-03-31 09:00:00'),
(15, 2, 31, 'donation_claimed', '2026-03-31 10:00:00', '2026-03-31 10:00:00'),
(16, 2, NULL, 'meal_planned', '2026-03-30 12:00:00', '2026-03-30 12:00:00'),
(17, 3, NULL, 'meal_planned', '2026-03-29 05:00:00', '2026-03-29 05:00:00'),
(18, 1, NULL, 'inventory_added', '2026-03-27 02:00:00', '2026-03-27 02:00:00'),
(19, 1, 54, 'inventory_added', '2026-03-30 03:00:00', '2026-03-30 03:00:00'),
(20, 1, NULL, 'meal_planned', '2026-03-31 11:00:00', '2026-03-31 11:00:00'),
(21, 1, NULL, 'meal_completed', '2026-03-31 13:00:00', '2026-03-31 13:00:00'),
(22, 1, NULL, 'donated', '2026-03-31 09:00:00', '2026-03-31 09:00:00'),
(23, 2, 54, 'donation_claimed', '2026-03-31 10:00:00', '2026-03-31 10:00:00'),
(24, 2, NULL, 'meal_planned', '2026-03-30 12:00:00', '2026-03-30 12:00:00'),
(25, 3, NULL, 'meal_planned', '2026-03-29 05:00:00', '2026-03-29 05:00:00'),
(27, 1, 33, 'donated', '2026-04-01 01:54:50', '2026-04-01 01:54:50'),
(28, 1, 69, 'donation_claimed', '2026-04-01 01:58:04', '2026-04-01 01:58:04'),
(29, 1, 59, 'donation_claimed', '2026-04-01 01:58:41', '2026-04-01 01:58:41'),
(30, 1, 64, 'donation_claimed', '2026-04-01 01:59:03', '2026-04-01 01:59:03'),
(31, 1, 33, 'donation_removed', '2026-04-01 02:09:43', '2026-04-01 02:09:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `donations`
--

CREATE TABLE `donations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `donor_id` bigint(20) UNSIGNED NOT NULL,
  `claimer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `food_item_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `donations`
--

INSERT INTO `donations` (`id`, `donor_id`, `claimer_id`, `food_item_id`, `description`, `pickup_location`, `availability`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 3, 'Extra spinach pack (still fresh). Pickup today or tomorrow.', NULL, NULL, 'available', '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(2, 1, 2, 8, 'Unopened bread pack. Claimed by Alya.', NULL, NULL, 'claimed', '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(3, 1, NULL, 10, NULL, NULL, NULL, 'available', '2026-03-31 13:33:21', '2026-03-31 13:33:21'),
(4, 1, NULL, 26, 'Extra spinach pack (still fresh). Pickup today or tomorrow.', 'Front gate', 'Today 18:00–20:00', 'available', '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(5, 1, 2, 31, 'Unopened bread pack. Claimed by Alya.', 'Lobby', 'Tomorrow 09:00–12:00', 'claimed', '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(7, 1, 2, 54, 'Unopened bread pack. Claimed by Alya.', 'Lobby', 'Tomorrow 09:00–12:00', 'claimed', '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(8, 2, 1, 59, 'Available for donation.', 'Apartment lobby', 'Today 18:00–20:00', 'claimed', '2026-03-31 23:05:37', '2026-04-01 01:58:41'),
(9, 3, NULL, 63, 'Available for donation.', 'Front gate', 'Tomorrow 09:00–12:00', 'available', '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(10, 4, 1, 64, 'Available for donation.', 'Security post', 'Anytime', 'claimed', '2026-03-31 23:05:37', '2026-04-01 01:59:03'),
(11, 5, NULL, 67, 'Available for donation.', 'Lobby', 'Weekend only', 'available', '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(12, 6, 1, 69, 'Available for donation.', 'Front gate', 'Today 17:00–19:00', 'claimed', '2026-03-31 23:05:37', '2026-04-01 01:58:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `food_items`
--

CREATE TABLE `food_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `storage_location` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(50) NOT NULL DEFAULT 'pcs',
  `purchase_date` date DEFAULT NULL,
  `expiration_date` date NOT NULL,
  `status` varchar(30) NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `food_items`
--

INSERT INTO `food_items` (`id`, `user_id`, `name`, `category`, `storage_location`, `notes`, `quantity`, `unit`, `purchase_date`, `expiration_date`, `status`, `used_at`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'Eggs', 'Dairy', NULL, NULL, 10.00, 'pcs', '2026-03-26', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(2, 1, 'Milk', 'Dairy', NULL, NULL, 1.00, 'L', '2026-03-28', '2026-04-04', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(3, 1, 'Spinach', 'Vegetables', NULL, NULL, 2.00, 'pack', '2026-03-29', '2026-04-01', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(4, 1, 'Chicken Breast', 'Meat', NULL, NULL, 1.00, 'kg', '2026-03-30', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(5, 1, 'Tomatoes', 'Vegetables', NULL, NULL, 6.00, 'pcs', '2026-03-27', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(6, 1, 'Bananas', 'Fruits', NULL, NULL, 6.00, 'pcs', '2026-03-25', '2026-03-30', 'Expired', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(7, 1, 'Rice', 'Grains', NULL, NULL, 2.00, 'kg', '2026-03-01', '2027-03-31', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(8, 1, 'Bread', 'Grains', NULL, NULL, 1.00, 'pack', '2026-03-29', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(9, 1, 'Cheese', 'Dairy', NULL, NULL, 250.00, 'g', '2026-03-21', '2026-04-07', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(10, 1, 'Yogurt', 'Dairy', NULL, NULL, 2.00, 'pcs', '2026-03-24', '2026-04-01', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(11, 2, 'Carrots', 'Vegetables', NULL, NULL, 500.00, 'g', '2026-03-28', '2026-04-06', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(12, 2, 'Apples', 'Fruits', NULL, NULL, 6.00, 'pcs', '2026-03-27', '2026-04-09', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(13, 2, 'Tofu', 'Protein', NULL, NULL, 2.00, 'pcs', '2026-03-30', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(14, 2, 'Chili Sauce', 'Sauce', NULL, NULL, 1.00, 'bottle', '2026-03-11', '2026-07-29', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(15, 3, 'Instant Noodles', 'Snacks', NULL, NULL, 5.00, 'pack', '2026-03-16', '2026-10-17', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(16, 3, 'Ground Beef', 'Meat', NULL, NULL, 500.00, 'g', '2026-03-29', '2026-04-01', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(17, 3, 'Lettuce', 'Vegetables', NULL, NULL, 1.00, 'pcs', '2026-03-29', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(18, 4, 'Oranges', 'Fruits', NULL, NULL, 8.00, 'pcs', '2026-03-28', '2026-04-10', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(19, 4, 'Butter', 'Dairy', NULL, NULL, 200.00, 'g', '2026-03-19', '2026-04-20', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(20, 5, 'Salmon Fillet', 'Meat', NULL, NULL, 2.00, 'pcs', '2026-03-30', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(21, 5, 'Broccoli', 'Vegetables', NULL, NULL, 1.00, 'pcs', '2026-03-29', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(22, 6, 'Oat Milk', 'Beverages', NULL, NULL, 1.00, 'L', '2026-03-25', '2026-04-05', 'Fresh', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(23, 6, 'Strawberries', 'Fruits', NULL, NULL, 2.00, 'pack', '2026-03-29', '2026-04-01', 'Expiring Soon', NULL, NULL, '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(24, 1, 'Eggs', 'Dairy', NULL, NULL, 10.00, 'pcs', '2026-03-27', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(25, 1, 'Milk', 'Dairy', NULL, NULL, 1.00, 'L', '2026-03-29', '2026-04-05', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(26, 1, 'Spinach', 'Vegetables', NULL, NULL, 2.00, 'pack', '2026-03-30', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(27, 1, 'Chicken Breast', 'Meat', NULL, NULL, 1.00, 'kg', '2026-03-31', '2026-04-04', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(28, 1, 'Tomatoes', 'Vegetables', NULL, NULL, 6.00, 'pcs', '2026-03-28', '2026-04-04', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(29, 1, 'Bananas', 'Fruits', NULL, NULL, 6.00, 'pcs', '2026-03-26', '2026-03-31', 'Expired', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(30, 1, 'Rice', 'Grains', NULL, NULL, 2.00, 'kg', '2026-03-02', '2027-04-01', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(31, 1, 'Bread', 'Grains', NULL, NULL, 1.00, 'pack', '2026-03-30', '2026-04-03', 'Expiring Soon', '2026-03-31 10:00:00', NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(32, 1, 'Cheese', 'Dairy', NULL, NULL, 250.00, 'g', '2026-03-22', '2026-04-08', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(33, 1, 'Yogurt', 'Dairy', NULL, NULL, 2.00, 'pcs', '2026-03-25', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(34, 2, 'Carrots', 'Vegetables', NULL, NULL, 500.00, 'g', '2026-03-29', '2026-04-07', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(35, 2, 'Apples', 'Fruits', NULL, NULL, 6.00, 'pcs', '2026-03-28', '2026-04-10', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(36, 2, 'Tofu', 'Protein', NULL, NULL, 2.00, 'pcs', '2026-03-31', '2026-04-04', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(37, 2, 'Chili Sauce', 'Sauce', NULL, NULL, 1.00, 'bottle', '2026-03-12', '2026-07-30', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(38, 3, 'Instant Noodles', 'Snacks', NULL, NULL, 5.00, 'pack', '2026-03-17', '2026-10-18', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(39, 3, 'Ground Beef', 'Meat', NULL, NULL, 500.00, 'g', '2026-03-30', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(40, 3, 'Lettuce', 'Vegetables', NULL, NULL, 1.00, 'pcs', '2026-03-30', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(41, 4, 'Oranges', 'Fruits', NULL, NULL, 8.00, 'pcs', '2026-03-29', '2026-04-11', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(42, 4, 'Butter', 'Dairy', NULL, NULL, 200.00, 'g', '2026-03-20', '2026-04-21', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(43, 5, 'Salmon Fillet', 'Meat', NULL, NULL, 2.00, 'pcs', '2026-03-31', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(44, 5, 'Broccoli', 'Vegetables', NULL, NULL, 1.00, 'pcs', '2026-03-30', '2026-04-04', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(45, 6, 'Oat Milk', 'Beverages', NULL, NULL, 1.00, 'L', '2026-03-26', '2026-04-06', 'Fresh', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(46, 6, 'Strawberries', 'Fruits', NULL, NULL, 2.00, 'pack', '2026-03-30', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(47, 1, 'Eggs', 'Dairy', NULL, NULL, 10.00, 'pcs', '2026-03-27', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(48, 1, 'Milk', 'Dairy', NULL, NULL, 1.00, 'L', '2026-03-29', '2026-04-05', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(50, 1, 'Chicken Breast', 'Meat', NULL, NULL, 1.00, 'kg', '2026-03-31', '2026-04-04', 'Expiring Soon', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(51, 1, 'Tomatoes', 'Vegetables', NULL, NULL, 6.00, 'pcs', '2026-03-28', '2026-04-04', 'Expiring Soon', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(52, 1, 'Bananas', 'Fruits', NULL, NULL, 6.00, 'pcs', '2026-03-26', '2026-03-31', 'Expired', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(53, 1, 'Rice', 'Grains', NULL, NULL, 2.00, 'kg', '2026-03-02', '2027-04-01', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(54, 1, 'Bread', 'Grains', NULL, NULL, 1.00, 'pack', '2026-03-30', '2026-04-03', 'Expiring Soon', '2026-03-31 10:00:00', NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(55, 1, 'Cheese', 'Dairy', NULL, NULL, 250.00, 'g', '2026-03-22', '2026-04-08', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(56, 1, 'Yogurt', 'Dairy', NULL, NULL, 2.00, 'pcs', '2026-03-25', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(57, 2, 'Carrots', 'Vegetables', NULL, NULL, 500.00, 'g', '2026-03-29', '2026-04-07', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(58, 2, 'Apples', 'Fruits', NULL, NULL, 6.00, 'pcs', '2026-03-28', '2026-04-10', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(59, 2, 'Tofu', 'Protein', NULL, NULL, 2.00, 'pcs', '2026-03-31', '2026-04-04', 'Expiring Soon', '2026-04-01 01:58:41', NULL, '2026-03-31 23:05:37', '2026-04-01 01:58:41'),
(60, 2, 'Chili Sauce', 'Sauce', NULL, NULL, 1.00, 'bottle', '2026-03-12', '2026-07-30', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(61, 3, 'Instant Noodles', 'Snacks', NULL, NULL, 5.00, 'pack', '2026-03-17', '2026-10-18', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(62, 3, 'Ground Beef', 'Meat', NULL, NULL, 500.00, 'g', '2026-03-30', '2026-04-02', 'Expiring Soon', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(63, 3, 'Lettuce', 'Vegetables', NULL, NULL, 1.00, 'pcs', '2026-03-30', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(64, 4, 'Oranges', 'Fruits', NULL, NULL, 8.00, 'pcs', '2026-03-29', '2026-04-11', 'Fresh', '2026-04-01 01:59:03', NULL, '2026-03-31 23:05:37', '2026-04-01 01:59:03'),
(65, 4, 'Butter', 'Dairy', NULL, NULL, 200.00, 'g', '2026-03-20', '2026-04-21', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(66, 5, 'Salmon Fillet', 'Meat', NULL, NULL, 2.00, 'pcs', '2026-03-31', '2026-04-03', 'Expiring Soon', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(67, 5, 'Broccoli', 'Vegetables', NULL, NULL, 1.00, 'pcs', '2026-03-30', '2026-04-04', 'Expiring Soon', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(68, 6, 'Oat Milk', 'Beverages', NULL, NULL, 1.00, 'L', '2026-03-26', '2026-04-06', 'Fresh', NULL, NULL, '2026-03-31 23:05:37', '2026-03-31 23:05:37'),
(69, 6, 'Strawberries', 'Fruits', NULL, NULL, 2.00, 'pack', '2026-03-30', '2026-04-02', 'Expiring Soon', '2026-04-01 01:58:04', NULL, '2026-03-31 23:05:37', '2026-04-01 01:58:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `meal_plans`
--

CREATE TABLE `meal_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `meal_name` varchar(255) NOT NULL,
  `planned_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'planned',
  `ingredients_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ingredients_used`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `meal_plans`
--

INSERT INTO `meal_plans` (`id`, `user_id`, `meal_name`, `planned_date`, `status`, `ingredients_used`, `created_at`, `updated_at`) VALUES
(1, 1, 'Chicken Stir Fry', '2026-04-01', 'planned', '[\"Chicken Breast\",\"Spinach\",\"Tomatoes\",\"Garlic\",\"Onion\"]', '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(2, 1, 'Egg Sandwich', '2026-03-31', 'planned', '[\"Eggs\",\"Bread\",\"Cheese\"]', '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(3, 1, 'Banana Yogurt Bowl', '2026-03-30', 'completed', '[\"Bananas\",\"Yogurt\"]', '2026-03-31 13:20:18', '2026-03-31 13:20:18'),
(4, 1, 'Chicken Stir Fry', '2026-04-02', 'planned', '[\"Chicken Breast\",\"Spinach\",\"Tomatoes\",\"Garlic\",\"Onion\"]', '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(5, 1, 'Egg Sandwich', '2026-04-01', 'planned', '[\"Eggs\",\"Bread\",\"Cheese\"]', '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(6, 1, 'Banana Yogurt Bowl', '2026-03-31', 'completed', '[\"Bananas\",\"Yogurt\"]', '2026-03-31 23:03:38', '2026-03-31 23:03:38'),
(7, 1, 'Chicken Stir Fry', '2026-04-02', 'planned', '[\"Chicken Breast\",\"Spinach\",\"Tomatoes\",\"Garlic\",\"Onion\"]', '2026-03-31 23:05:37', '2026-03-31 23:05:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_18_000001_create_food_items_table', 1),
(5, '2026_03_18_000002_create_meal_plans_table', 1),
(6, '2026_03_18_000003_create_donations_table', 1),
(7, '2026_03_18_000004_create_analytics_logs_table', 1),
(8, '2026_03_19_000005_add_image_path_to_food_items_table', 1),
(9, '2026_03_31_000006_add_household_size_to_users_table', 1),
(10, '2026_04_01_000006_add_used_at_to_food_items_table', 2),
(11, '2026_04_01_000007_add_fields_to_food_items_table', 3),
(12, '2026_04_01_000008_add_fields_to_donations_table', 3),
(13, '2026_04_01_000006_add_privacy_settings_to_users_table', 4),
(14, '2026_04_01_000007_create_user_notifications_table', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('8ENfQvpami2E3auy11qQsUxz1jZWX6ukG2ILuQNF', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUjE0b2tGY0FGWUxtSGNMWExWanFmQXNBTEZ2cGx0WVdtUVZOb0NJQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9maWxlIjtzOjU6InJvdXRlIjtzOjEyOiJwcm9maWxlLmVkaXQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1775044595);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `household_size` tinyint(3) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `privacy_two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `privacy_food_listing_visibility` varchar(20) NOT NULL DEFAULT 'public',
  `privacy_expiry_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `privacy_meal_plan_reminders` tinyint(1) NOT NULL DEFAULT 1,
  `privacy_donation_updates` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `household_size`, `remember_token`, `privacy_two_factor_enabled`, `privacy_food_listing_visibility`, `privacy_expiry_notifications`, `privacy_meal_plan_reminders`, `privacy_donation_updates`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', NULL, '$2y$12$gFCqheDqP5DCzvtwruIznevaG1DiXDP8TbD9qsJu/71a6yfW9.C3O', NULL, NULL, 0, 'public', 1, 1, 1, '2026-03-31 13:20:16', '2026-03-31 13:20:16'),
(2, 'Alya Putri', 'alya@example.com', NULL, '$2y$12$VXMVH829J.SJsaMoyExGUeyznM9MTVWYGiBrxGAXz5tu/n7qdGQvG', NULL, NULL, 0, 'public', 1, 1, 1, '2026-03-31 13:20:17', '2026-03-31 13:20:17'),
(3, 'Bima Pratama', 'bima@example.com', NULL, '$2y$12$eIK//a2vYL1CcgJSFXtcI.BpQQcPX4KQdRfmYRvEMhcvbN5CDxbDy', NULL, NULL, 0, 'public', 1, 1, 1, '2026-03-31 13:20:17', '2026-03-31 13:20:17'),
(4, 'Citra Santoso', 'citra@example.com', NULL, '$2y$12$NF9PbaUtwofOO9yk8XYgsOPL8IBgiXjgsL97rgZW6P8i9mXXMyj9W', NULL, NULL, 0, 'public', 1, 1, 1, '2026-03-31 13:20:17', '2026-03-31 13:20:17'),
(5, 'Dimas Saputra', 'dimas@example.com', NULL, '$2y$12$enDpQ5VkOBf7FN.qjsNW7eb.WG8H1.JX51vfuv2b31wehh8t2eB3.', NULL, NULL, 0, 'public', 1, 1, 1, '2026-03-31 13:20:17', '2026-03-31 13:20:17'),
(6, 'Nadia Rahma', 'nadia@example.com', NULL, '$2y$12$J6Ur6rJQr7Qk7MlP6lyAnOZdjLL2hwSX6V4LNED.eFgXwnIeJ0NOS', NULL, NULL, 0, 'public', 1, 1, 1, '2026-03-31 13:20:18', '2026-03-31 13:20:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `action_label` varchar(255) DEFAULT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `dedupe_key` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `dismissed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `analytics_logs`
--
ALTER TABLE `analytics_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `analytics_logs_food_item_id_foreign` (`food_item_id`),
  ADD KEY `analytics_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `analytics_logs_action_type_index` (`action_type`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donations_food_item_id_foreign` (`food_item_id`),
  ADD KEY `donations_donor_id_status_index` (`donor_id`,`status`),
  ADD KEY `donations_claimer_id_status_index` (`claimer_id`,`status`),
  ADD KEY `donations_status_index` (`status`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `food_items_user_id_name_index` (`user_id`,`name`),
  ADD KEY `food_items_category_index` (`category`),
  ADD KEY `food_items_expiration_date_index` (`expiration_date`),
  ADD KEY `food_items_status_index` (`status`),
  ADD KEY `food_items_used_at_index` (`used_at`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meal_plans_user_id_planned_date_index` (`user_id`,`planned_date`),
  ADD KEY `meal_plans_planned_date_index` (`planned_date`),
  ADD KEY `meal_plans_status_index` (`status`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_notifications_user_id_dedupe_key_unique` (`user_id`,`dedupe_key`),
  ADD KEY `user_notifications_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `user_notifications_type_index` (`type`),
  ADD KEY `user_notifications_dedupe_key_index` (`dedupe_key`),
  ADD KEY `user_notifications_read_at_index` (`read_at`),
  ADD KEY `user_notifications_dismissed_at_index` (`dismissed_at`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `analytics_logs`
--
ALTER TABLE `analytics_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `donations`
--
ALTER TABLE `donations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `analytics_logs`
--
ALTER TABLE `analytics_logs`
  ADD CONSTRAINT `analytics_logs_food_item_id_foreign` FOREIGN KEY (`food_item_id`) REFERENCES `food_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `analytics_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_claimer_id_foreign` FOREIGN KEY (`claimer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `donations_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donations_food_item_id_foreign` FOREIGN KEY (`food_item_id`) REFERENCES `food_items` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `food_items`
--
ALTER TABLE `food_items`
  ADD CONSTRAINT `food_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD CONSTRAINT `meal_plans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
