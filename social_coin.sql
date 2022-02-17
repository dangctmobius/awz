/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50735
 Source Host           : localhost:3306
 Source Schema         : social_coin

 Target Server Type    : MySQL
 Target Server Version : 50735
 File Encoding         : 65001

 Date: 14/02/2022 13:31:12
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `deleted_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of comments
-- ----------------------------
BEGIN;
INSERT INTO `comments` VALUES (1, 1, 22, NULL, 'It might be a bit hard to see, but you only have to look at the red badge across the edge to realise what I mean.', 1643599950, NULL, NULL);
INSERT INTO `comments` VALUES (2, 2, 22, NULL, 'hello', 1644682693, NULL, NULL);
INSERT INTO `comments` VALUES (3, 2, 22, NULL, 'hello', 1644682705, NULL, NULL);
INSERT INTO `comments` VALUES (4, 2, 22, NULL, 'hellloweoiur', 1644683045, NULL, NULL);
INSERT INTO `comments` VALUES (5, 2, 22, NULL, 'bi viet hay qua ban oi', 1644683053, NULL, NULL);
INSERT INTO `comments` VALUES (6, 2, 22, NULL, 'toi muon spam', 1644683063, NULL, NULL);
INSERT INTO `comments` VALUES (7, 2, 22, NULL, 'xin chao cac ban toi ten la nguyen van a', 1644683202, NULL, NULL);
INSERT INTO `comments` VALUES (8, 2, 22, NULL, 'heloowieuroiwe', 1644683293, NULL, NULL);
INSERT INTO `comments` VALUES (9, 2, 22, NULL, '324324324234', 1644683297, NULL, NULL);
INSERT INTO `comments` VALUES (10, 2, 22, NULL, '2342343242334523', 1644683301, NULL, NULL);
INSERT INTO `comments` VALUES (11, 2, 22, NULL, '23u4u23o4uoi23u4oi', 1644683308, NULL, NULL);
INSERT INTO `comments` VALUES (12, 2, 21, NULL, 'heloi3uo432', 1644683334, NULL, NULL);
INSERT INTO `comments` VALUES (13, 2, 22, NULL, '373928749823748293 eiwriweiewuor \\aer\ner\ne\nqr\ne\nqr\neq\nreq', 1644684213, NULL, NULL);
INSERT INTO `comments` VALUES (14, 2, 23, NULL, 'ghê quá', 1644806370, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2022_01_12_073157_create_verification_codes_table', 1);
INSERT INTO `migrations` VALUES (6, '2022_01_20_214254_create_posts_table', 1);
INSERT INTO `migrations` VALUES (8, '2022_01_20_214302_create_comments_table', 2);
COMMIT;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `likes` bigint(20) NOT NULL,
  `status` int(20) DEFAULT NULL,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of posts
-- ----------------------------
BEGIN;
INSERT INTO `posts` VALUES (15, 2, 'caothanh dang neˋ bitcoin dang giam tut quan 33k', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/2022124_2_4365.jpg', 0, 1, 1643028698, NULL);
INSERT INTO `posts` VALUES (16, 2, 'helloo 232', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/2022124_2_4235.jpg', 0, 1, 1643030030, NULL);
INSERT INTO `posts` VALUES (17, 2, 'helloo', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/2022131_2_2503.jpg', 0, 1, 1643647003, NULL);
INSERT INTO `posts` VALUES (18, 2, 'heloiuoi32u4o23', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/2022131_2_5242.jpg', 0, 1, 1643647346, NULL);
INSERT INTO `posts` VALUES (19, 2, 'heloiuoi32u4o23', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/2022131_2_1747.jpg', 0, 1, 1643647350, NULL);
INSERT INTO `posts` VALUES (20, 2, 'hello', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/202221_2_8633.jpg', 0, 1, 1643713929, NULL);
INSERT INTO `posts` VALUES (21, 2, 'heloo co ba', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/202226_2_8729.jpg', 0, 1, 1644132069, NULL);
INSERT INTO `posts` VALUES (22, 3, 'hello 3', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/202226_3_4364.jpg', 0, 1, 1644137795, NULL);
INSERT INTO `posts` VALUES (23, 2, 'good morning', 'https://poposbucket.s3-ap-southeast-1.amazonaws.com/images/products/2022214_2_4350.jpg', 0, 1, 1644806355, NULL);
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'AZWORLD', 'typhoom1709@gmail.com', NULL, NULL, NULL, '$2y$10$PEkOFOmWlKPaiifT8i8IAeCPffgPhC97frZo9ilhSTLV6OVPoWk82', NULL, '2022-01-21 19:15:23', '2022-01-21 19:15:23');
INSERT INTO `users` VALUES (2, 'AZWORLD', 'thanhdang.ag@gmail.com', NULL, NULL, NULL, '$2y$10$jrwl2BJAk.TsyLsnW4lRDeiSZAxx0Z2MglLtttURuJXqrDN1Qt43m', NULL, '2022-01-21 20:27:53', '2022-01-21 20:27:53');
INSERT INTO `users` VALUES (3, 'Ngọc Đỉnh', 'caongocdinh17@gmail.com', NULL, 'https://kenh14cdn.com/2020/9/27/img3814-16008495660052057963035-16012244314321556076455.jpg', NULL, '$2y$10$242fEhG/lAWjw9zyxU4UReoKkG7/wmwAmx0Atp2CJIyZjlDKAT8he', NULL, '2022-02-06 08:51:55', '2022-02-06 08:51:55');
INSERT INTO `users` VALUES (4, 'AZWORLD_45048', 'thanhdang.ag@mail.com', NULL, 'https://kenh14cdn.com/2020/9/27/img3814-16008495660052057963035-16012244314321556076455.jpg', NULL, '$2y$10$RuK1lNTM1AVvA7ID1vKpEugfELgw7ZLQbnX4lStzt6eP2zoXWrYDW', NULL, '2022-02-06 08:57:19', '2022-02-06 08:57:19');
COMMIT;

-- ----------------------------
-- Table structure for verification_codes
-- ----------------------------
DROP TABLE IF EXISTS `verification_codes`;
CREATE TABLE `verification_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verifiable` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of verification_codes
-- ----------------------------
BEGIN;
INSERT INTO `verification_codes` VALUES (1, '$2y$10$c9gOKUvV9NB2xmzqSEQ0UugAjgo76w6qMyQkSjlewZ.AWcE5hPjAa', 'typhoom1709@gmail.com', '2022-01-21 20:15:23', '2022-01-21 19:15:23', '2022-01-21 19:15:23');
INSERT INTO `verification_codes` VALUES (4, '$2y$10$ry9n.hInFoTfapWiRj30n.z7iBEG5lMFMBCDTVOqG/b.xeXyu6iBW', 'thanhdang.ag@mail.com', '2022-02-06 09:57:19', '2022-02-06 08:57:19', '2022-02-06 08:57:19');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
