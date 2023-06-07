-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2023 at 04:48 PM
-- Server version: 10.3.38-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mpu-crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Master', 'master@example.com', '2023-05-31 18:28:32', '$2y$10$DMFzKr93/EeJpUefl.DRCOJODYYUYsML1slGw8y4RSKxSLZxJA8HG', 'PpvqINnMRA', NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(2, 'Admin', 'admin@example.com', '2023-05-31 18:28:32', '$2y$10$fvEbgX3FZo5FjCIBE0b54.tJLN9mDKErtkMQANmJkjj4Yu40Mn/vi', 'XvA0ipDTmodfY0FRh4BXhsjN5rBMgjbu6ZarI6PZpeG5W28xfOrvuBfSyNrm', NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(3, 'Department', 'department@example.com', '2023-05-31 18:28:32', '$2y$10$EiSwM8O/cm8wufEGWjOqW.xFGgeR13oFiqQtb34DkKUHY3CcEjqSi', 'cjCtTUVSd4', NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_department`
--

CREATE TABLE `admin_user_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_user_id` bigint(20) NOT NULL,
  `department_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_user_department`
--

INSERT INTO `admin_user_department` (`id`, `admin_user_id`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 2, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE `configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `division` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configs`
--

INSERT INTO `configs` (`id`, `division`, `label`, `value`, `created_at`, `updated_at`) VALUES
(1, 'inquiry_form', 'origin', '{\n                \"question\":\"擬入讀本校之學生現時所持有之證件 Type of personal Identification document held by the candidate\",\n                \"short\":\"持有證件\",\n                \"options\":[\n                    {\"value\":\"MO\",\"label\":\"澳門居民身份證 MACAU ID\"},\n                    {\"value\":\"CN\",\"label\":\"中華人民共和國居民身份證 CHINA ID\"},\n                    {\"value\":\"HK\",\"label\":\"香港居民身份證 HONG KONG ID\"},\n                    {\"value\":\"TW\",\"label\":\"台灣居民身份證 TAIWAN ID\"},\n                    {\"value\":\"FR\",\"label\":\"外國護照 (非持有上述證件的外國居民) PASSPORT\"}\n                ]\n            }', NULL, NULL),
(2, 'inquiry_form', 'degree', '{\n                \"question\":\"入讀課程類別 Apply programme\",\n                \"short\":\"入讀課程\",\n                \"options\":[\n                    {\"value\":\"B\",\"label\":\"學士學位 本科 Bachelor\"},\n                    {\"value\":\"M\",\"label\":\"碩士學位 Master\"},\n                    {\"value\":\"D\",\"label\":\"博士學位 Doctoral\"}\n                ]\n            }', NULL, NULL),
(3, 'inquiry_form', 'admission', '{\n                \"question\":\"入學途徑 Admission route\",\n                \"short\":\"入學途徑\",\n                \"options\":[\n                    {\"value\":\"EXAM\",\"label\":\"入學考試 Admission exams\"},\n                    {\"value\":\"RECOMMEND\",\"label\":\"澳門中學校長推薦新生入學計劃 Principals’ recommendation\"},\n                    {\"value\":\"TELENT\",\"label\":\"專才入學計劃 Local talents and professionals\"},\n                    {\"value\":\"DIRECT\",\"label\":\"直接入學 Direct admission\"},\n                    {\"value\":\"OTHER\",\"label\":\"其他 Others (現就讀於高等院校學士課程之學生適用 Applicable to students who are currently studying bachelor programs in higher education institutions)\"},\n                    {\"value\":\"GAOKAO\",\"label\":\"高考生 Mainland China GAOKAO students\"},\n                    {\"value\":\"DIRECT\",\"label\":\"直接入學 Direct admission (非高考生持國際課程或公開考試成績的內地籍高中應屆畢業生 Non-GAOKAO student and Fresh graduates from high schools who hold international curriculum or public exam result)\"},\n                    {\"value\":\"EXAM\",\"label\":\"入學考試 Admission exams\"},\n                    {\"value\":\"DIRECT\",\"label\":\"直接入學 Direct admission\"}\n                ]\n            }', NULL, NULL),
(4, 'inquiry_form', 'profile', '{\n                \"question\":\"簡介 Profiles\",\n                \"short\":\"簡介\",\n                \"options\":[\n                    {\"value\":\"STD\",\"label\":\"學生 Student\"},\n                    {\"value\":\"PAR\",\"label\":\"家長 Parent\"},\n                    {\"value\":\"TEA\",\"label\":\"老師 Teacher\"},\n                    {\"value\":\"EDU\",\"label\":\"教育機構/顧問 Education centre/counsellor\"},\n                    {\"value\":\"OTH\",\"label\":\"其他 Other\"}\n                ]\n            }', NULL, NULL),
(5, 'inquiry_form', 'apply', '{\n                \"question\":\"是否已報讀澳理大學位課程? Have you submitted an application for admission in current year?\",\n                \"short\":\"已報讀\",\n                \"options\":[\n                    {\"value\":false,\"label\":\"否 No\"},\n                    {\"value\":true,\"label\":\"是 Yes\"}\n                ]\n            }', NULL, NULL),
(6, 'inquiry_form', 'surname', '{\n                \"question\":\"姓 Surname\",\n                \"short\":\"姓\"\n            }', NULL, NULL),
(7, 'inquiry_form', 'givenname', '{\n                \"question\":\"名 Given Name\",\n                \"short\":\"名\"\n            }', NULL, NULL),
(8, 'inquiry_form', 'email', '{\n                \"question\":\"電郵 Email\",\n                \"short\":\"電郵\"\n            }', NULL, NULL),
(9, 'inquiry_form', 'contact_number', '{\n                \"question\": \"聯絡電話 phone number\",\n                \"short\":\"聯絡電話\"\n            }', NULL, NULL),
(10, 'inquiry_form', 'areacode', '{\n                \"question\":\"區號 Area code\",\n                \"short\":\"區號\"\n            }', NULL, NULL),
(11, 'inquiry_form', 'phone', '{\n                \"question\":\"電話 Phone number\",\n                \"short\":\"電話\"\n            }', NULL, NULL),
(12, 'inquiry_form', 'subjects', '{\n                \"question\":\"主旨 Subject\",\n                \"short\":\"主旨\",\n                \"options\":[\n                    {\"value\":\"ADM\",\"label\":\"入學要求 Admission Requirements\"},\n                    {\"value\":\"PRO\",\"label\":\"課程資訊 Programme Information\"},\n                    {\"value\":\"APP\",\"label\":\"報名資訊 Application Procedures\"},\n                    {\"value\":\"FEE\",\"label\":\"學費及奬學金 Fees and Scholarships\"},\n                    {\"value\":\"RES\",\"label\":\"錄取資訊 Admission Result\"},\n                    {\"value\":\"UPD\",\"label\":\"更新報名資訊 Updating Information on Application Form\"},\n                    {\"value\":\"PAY\",\"label\":\"繳費事宜 Payment Issue\"},\n                    {\"value\":\"REG\",\"label\":\"入學登記手續、體檢 Student registration and physical examination\"},\n                    {\"value\":\"OTH\",\"label\":\"其他 others\"}\n                ]\n            }', NULL, NULL),
(13, 'inquiry_form', 'agree', '{\n                \"question\":\"本人同意澳門理工大學就招生資訊或活動發送電郵或短訊予本人。<br>I give my consent to Macau Polytechnic University for send me emails or SMS regarding admissions information or activities.\",\n                \"short\":\"同意\"\n            }', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `abbr_zh` varchar(255) DEFAULT NULL,
  `abbr_en` varchar(255) DEFAULT NULL,
  `abbr_pt` varchar(255) DEFAULT NULL,
  `name_zh` varchar(255) DEFAULT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `name_pt` varchar(255) DEFAULT NULL,
  `landing_zh` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `href` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `abbr_zh`, `abbr_en`, `abbr_pt`, `name_zh`, `name_en`, `name_pt`, `landing_zh`, `phone`, `href`, `created_at`, `updated_at`) VALUES
(1, 'Ut.', NULL, NULL, '招生註冊處', NULL, NULL, '				<p>\r\n	招生註冊處是<a href=\"saa.php\">學術事務部</a>下設部門之一，負責協調學校學位課程之推廣、入學、學科單元註冊、學籍管理、學生檔案管理、學歷證明文件簽發等相關事宜。</p>\r\n<h4>\r\n	辦公時間：</h4>\r\n<div class=\"tbl-wrap no-border\">\r\n	<div>\r\n		<table>\r\n			<tbody>\r\n				<tr>\r\n					<th>\r\n						星期一至五</th>\r\n					<td>\r\n						早上9時至下午7時*</td>\r\n				</tr>\r\n				<tr>\r\n					<td colspan=\"2\">\r\n						<p>\r\n							星期六、日及公眾假期休息</p>\r\n						<div class=\"remarks\">\r\n							<strong>*&nbsp;</strong><span><a href=\"http://www.ipm.edu.mo/student_corner/zh/calendar.php\">休課期</a>之辦公時間為：<br>\r\n							星期一至四早上9時至下午5時45分<br>\r\n							星期五早上9時至下午5時30分</span></div>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>\r\n\r\n\r\n\r\n\r\n						\r\n				', NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(2, 'In.', NULL, NULL, 'Shawna Lynch', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(3, 'Quod.', NULL, NULL, 'Elian Aufderhar', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(4, 'Ex.', NULL, NULL, 'Ryan Kozey', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(5, 'Quas.', NULL, NULL, 'Mr. Haley Witting Sr.', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(6, 'Esse.', NULL, NULL, 'Kyla Thompson PhD', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(7, 'Et.', NULL, NULL, 'Ford Berge', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(8, 'Quae.', NULL, NULL, 'Dr. Judge Wintheiser III', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(9, 'In.', NULL, NULL, 'Freda Predovic PhD', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(10, 'Nisi.', NULL, NULL, 'Myra Weissnat', NULL, NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `emailable_id` bigint(20) DEFAULT NULL,
  `emailable_type` varchar(255) DEFAULT NULL,
  `admin_user_id` bigint(20) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `receiver` varchar(255) NOT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `question_zh` varchar(255) NOT NULL,
  `answer_zh` text NOT NULL,
  `question_en` varchar(255) DEFAULT NULL,
  `answer_en` text DEFAULT NULL,
  `question_pt` varchar(255) DEFAULT NULL,
  `answer_pt` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `department_id`, `category_id`, `subjects`, `question_zh`, `answer_zh`, `question_en`, `answer_en`, `question_pt`, `answer_pt`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '[\"ADM\"]', '澳門理工大學提供哪些課程?', '本校提供<a href=\"https://www.mpu.edu.mo/admission_local/zh/postgraduate.php\" target=\"_blank\">「研究生課程」</a>及\n            <a href=\"https://www.mpu.edu.mo/admission_local/zh/undergraduate.php\" target=\"_blank\">「學士學位課程」</a>\n            （範疇包括：工商管理、資訊科技、語言與翻譯、公共管理與服務、藝術、健康科學及體育）。', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 1, '[\"ADM\"]', '課程採用甚麼學制?', '博士學位課程修讀期為三年、碩士學位課程修讀期為兩年；<br>學士學位課程均為四年制。', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 1, '[\"ADM\"]', '課程採用甚麼語言授課?', '各課程採用不同的授課語言。有關課程之授課語言可於「研究生課程」及「學士學位課程」中瀏覽。', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 1, '[\"ADM\",\"PRO\"]', '課程是否限制文科或理科組別學生報考?', '本校文理兼收，任何組別學生皆可報考本校提供的課程。', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 2, '[\"PRO\"]', '	\n            報考研究生課程須具備哪些條件?', '博士學位課程：<br>\n            具碩士學位或同等學歷；或正修讀碩士學位學歷課程最後一年；及\n            尚須具備報讀課程要求的其他報考條件。<br>\n            碩士學位課程：\n            具學士學位或同等學歷；或正修讀學士學位學歷課程最後一年；及\n            尚須具備報讀課程要求的其他報考條件。', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `require_login` tinyint(1) NOT NULL DEFAULT 0,
  `require_member` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `department_id`, `name`, `title`, `description`, `require_login`, `require_member`, `created_at`, `updated_at`) VALUES
(1, 2, 'first form', 'First form of title', NULL, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `form_fields`
--

CREATE TABLE `form_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `form_id` bigint(20) UNSIGNED NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_label` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'input',
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `rule` varchar(255) DEFAULT NULL,
  `validate` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_fields`
--

INSERT INTO `form_fields` (`id`, `form_id`, `field_name`, `field_label`, `type`, `required`, `rule`, `validate`, `remark`, `created_at`, `updated_at`) VALUES
(1, 1, 'field 1', 'label 1', 'input', 0, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) NOT NULL,
  `lang` char(2) NOT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `admission` varchar(255) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `apply` tinyint(1) NOT NULL DEFAULT 0,
  `surname` varchar(255) DEFAULT NULL,
  `givenname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `areacode` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `has_question` tinyint(1) NOT NULL DEFAULT 0,
  `question` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `department_id`, `lang`, `origin`, `degree`, `admission`, `profile`, `apply`, `surname`, `givenname`, `email`, `areacode`, `phone`, `subjects`, `token`, `has_question`, `question`, `created_at`, `updated_at`) VALUES
(1, 1, 'zh', 'MO', 'B', 'EXAM', 'STD', 0, 'surname', 'givenname', 'example@example.com', '853', '123456', '[\"PRO\"]', 'a93cdb80', 0, 'content of my question', NULL, NULL),
(2, 1, 'zh', 'MO', 'B', 'EXAM', 'STD', 0, 'surname2', 'givenname2', 'example@example.com2', '85322', '12345622', '[\"ADM\"]', 'a93cdb80', 0, 'content of my question 2', NULL, NULL),
(3, 1, 'zh', 'MO', 'B', 'EXAM', 'STD', 1, 'Jose', 'Chan', 'josechan@ipm.edu.mo', '853', '63860836', '[\"PRO\"]', 'f5dfe5d8', 1, '454', '2023-05-31 18:44:45', '2023-05-31 18:44:51'),
(4, 1, 'zh', 'MO', 'B', 'EXAM', 'STD', 1, 'Jose', 'Chan', 'josechan@ipm.edu.mo', '853', '63860836', '[\"ADM\"]', '56005e7b', 1, '1111111111111111111111111', '2023-06-01 16:55:52', '2023-06-01 16:56:16'),
(5, 1, 'zh', 'MO', 'B', 'EXAM', 'STD', 1, 'Jose', 'Chan', 'josechan@ipm.edu.mo', '853', '63860836', '[\"ADM\"]', '04866f20', 0, NULL, '2023-06-02 01:22:46', '2023-06-02 01:22:46'),
(6, 1, 'zh', 'MO', 'B', 'EXAM', 'STD', 1, 'Jose', 'Chan', 'josechan@ipm.edu.mo', '853', '63860836', '[\"ADM\"]', 'f5701821', 0, NULL, '2023-06-02 01:24:32', '2023-06-02 01:24:32'),
(7, 1, 'zh', 'MO', 'B', 'EXAM', 'STD', 1, 'Jose', 'Chan', 'josechan@ipm.edu.mo', '853', '63860836', '[\"ADM\"]', 'e24e4d9c', 0, NULL, '2023-06-02 01:25:54', '2023-06-02 01:25:54');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Response', 7, 'cd1a6b86-e22c-43e6-8073-390461005d19', 'responseAttachments', 'Screen Shot 2022-04-14 at 19.19.47', 'Screen-Shot-2022-04-14-at-19.19.47.png', 'image/png', 'media', 'media', 11175, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-01 21:05:11', '2023-06-01 21:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `user_id`, `first_name`, `last_name`, `display_name`, `gender`, `dob`, `email`, `phone`, `country`, `nationality`, `address`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Mozell', 'Dickinson', 'Sonia Spinka', NULL, NULL, 'freeda.gottlieb@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(2, NULL, 'Trenton', 'Buckridge', 'Marcelle Walsh', NULL, NULL, 'lynn61@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(3, NULL, 'Baron', 'Franecki', 'Carissa Windler', NULL, NULL, 'javier.sawayn@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(4, NULL, 'Alaina', 'Dooley', 'Saige Yundt', NULL, NULL, 'ara.runte@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(5, NULL, 'Cristal', 'Hegmann', 'Meagan Ebert', NULL, NULL, 'newell.abernathy@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(6, NULL, 'Terry', 'Marks', 'Rubye Runolfsdottir I', NULL, NULL, 'freida.lockman@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(7, NULL, 'Estefania', 'Gusikowski', 'Leopold Ortiz', NULL, NULL, 'fiona96@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(8, NULL, 'Donavon', 'Fay', 'Shakira Nikolaus', NULL, NULL, 'schamberger.karley@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(9, NULL, 'Anais', 'Dickens', 'Mariah Kovacek DVM', NULL, NULL, 'qhartmann@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(10, NULL, 'Craig', 'Satterfield', 'Dr. Miracle Pfeffer V', NULL, NULL, 'reid.christiansen@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(11, NULL, 'Mariah', 'Yundt', 'Prof. Miller Schimmel', NULL, NULL, 'hgoldner@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(12, NULL, 'Randi', 'Russel', 'Opal Witting', NULL, NULL, 'anya.harvey@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(13, NULL, 'Morgan', 'Maggio', 'Prof. Caroline O\'Kon III', NULL, NULL, 'jondricka@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(14, NULL, 'Meaghan', 'Rogahn', 'Rosemarie Hoppe', NULL, NULL, 'lora.effertz@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(15, NULL, 'Greta', 'Schuster', 'Miss Kitty Paucek MD', NULL, NULL, 'forrest.cormier@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(16, NULL, 'Letha', 'Williamson', 'Juston Price', NULL, NULL, 'jay49@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(17, NULL, 'Beth', 'Abbott', 'Jaycee Greenholt', NULL, NULL, 'angel61@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(18, NULL, 'Melvin', 'McClure', 'Abbie Russel', NULL, NULL, 'milton.towne@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(19, NULL, 'Lucie', 'Hansen', 'Dr. Braden DuBuque I', NULL, NULL, 'chyna16@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(20, NULL, 'Curtis', 'Reichert', 'Aiyana Wiegand', NULL, NULL, 'gunnar.goodwin@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(21, NULL, 'Adolfo', 'Wilkinson', 'Prof. Kali Lang PhD', NULL, NULL, 'donny.rippin@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(22, NULL, 'Lauriane', 'Mueller', 'Mrs. Anais Denesik', NULL, NULL, 'helena.bayer@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(23, NULL, 'Nikita', 'Kris', 'Sabrina Kilback', NULL, NULL, 'mcglynn.delfina@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(24, NULL, 'Edwin', 'Weimann', 'Adrien Harvey III', NULL, NULL, 'vbartell@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(25, NULL, 'Kay', 'Goyette', 'Mr. Cooper Stark', NULL, NULL, 'smonahan@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(26, NULL, 'Jacky', 'Jakubowski', 'Libbie Littel', NULL, NULL, 'schoen.yoshiko@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(27, NULL, 'Sibyl', 'Strosin', 'Foster Paucek', NULL, NULL, 'onie79@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(28, NULL, 'Claire', 'Nolan', 'Della Emmerich', NULL, NULL, 'nfranecki@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(29, NULL, 'Lexie', 'Swift', 'Marlene Nienow', NULL, NULL, 'hoppe.cordia@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(30, NULL, 'Furman', 'Dickens', 'Lazaro Roob', NULL, NULL, 'gdonnelly@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(31, NULL, 'Veda', 'Adams', 'Crystal Altenwerth', NULL, NULL, 'lehner.cary@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(32, NULL, 'Agnes', 'Keeling', 'Lenna Turner', NULL, NULL, 'bbotsford@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(33, NULL, 'Alden', 'Emard', 'Vernice Schamberger', NULL, NULL, 'doyle.eldon@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(34, NULL, 'Jude', 'Stokes', 'Lupe Gusikowski', NULL, NULL, 'hills.idell@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(35, NULL, 'Bailee', 'Littel', 'Genoveva Goodwin', NULL, NULL, 'nia21@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(36, NULL, 'Rahul', 'Brakus', 'Rebekah Schulist', NULL, NULL, 'jakubowski.sophie@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(37, NULL, 'Mayra', 'Flatley', 'Mrs. Anika Corwin', NULL, NULL, 'armand.feeney@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(38, NULL, 'Loy', 'Wisozk', 'Vance Schamberger II', NULL, NULL, 'schinner.jasmin@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(39, NULL, 'Harry', 'Dare', 'Nicolette Reynolds', NULL, NULL, 'sienna41@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(40, NULL, 'Tierra', 'Pollich', 'Louie Hauck', NULL, NULL, 'torphy.ansel@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(41, NULL, 'Skye', 'Turcotte', 'Kieran Watsica', NULL, NULL, 'zolson@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(42, NULL, 'Christine', 'Simonis', 'Dr. Reymundo Larkin II', NULL, NULL, 'pspinka@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(43, NULL, 'Hollie', 'Bayer', 'Issac Ratke', NULL, NULL, 'swiegand@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(44, NULL, 'Celia', 'Torphy', 'Prof. Savanna Stark', NULL, NULL, 'williamson.cathryn@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(45, NULL, 'Henry', 'Nader', 'Loren Schaefer', NULL, NULL, 'waters.verona@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(46, NULL, 'Stevie', 'Cassin', 'Oren Kunze', NULL, NULL, 'ptowne@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(47, NULL, 'Beth', 'Bailey', 'Shanon Predovic', NULL, NULL, 'trantow.elliott@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(48, NULL, 'Jean', 'Wilkinson', 'Abigale Metz', NULL, NULL, 'roberts.chesley@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(49, NULL, 'Cleta', 'Cummings', 'Tia Gulgowski', NULL, NULL, 'arnold30@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(50, NULL, 'Steve', 'Lindgren', 'Ms. Amina Dickens I', NULL, NULL, 'schuster.roberto@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(51, NULL, 'Frieda', 'Wuckert', 'Prof. Ruby Vandervort IV', NULL, NULL, 'kuvalis.andrew@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(52, NULL, 'Kaitlin', 'Leffler', 'Bernita Johnston DVM', NULL, NULL, 'destini14@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(53, NULL, 'Luz', 'Schneider', 'Prof. Elisha Hintz III', NULL, NULL, 'russel.stephanie@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(54, NULL, 'Sonya', 'Cormier', 'Eugene Waters MD', NULL, NULL, 'imonahan@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(55, NULL, 'Orlo', 'Spencer', 'Giovani Lind', NULL, NULL, 'little.brayan@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(56, NULL, 'Rasheed', 'Wehner', 'Shirley Stanton', NULL, NULL, 'kohler.foster@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(57, NULL, 'Sarah', 'Rutherford', 'Jessika Schamberger', NULL, NULL, 'carrie53@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(58, NULL, 'Bernita', 'Bauch', 'Adonis Hyatt', NULL, NULL, 'rspencer@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(59, NULL, 'Tom', 'Kris', 'Reyna Wintheiser', NULL, NULL, 'eldred.quigley@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(60, NULL, 'Lilliana', 'Mueller', 'Griffin Bahringer V', NULL, NULL, 'metz.antonia@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(61, NULL, 'Watson', 'Brekke', 'Ms. Adah Upton II', NULL, NULL, 'haag.teresa@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(62, NULL, 'Eddie', 'Grant', 'Ms. Delfina Robel PhD', NULL, NULL, 'cyrus59@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(63, NULL, 'Wilmer', 'Howe', 'Estelle Armstrong', NULL, NULL, 'fanny.jakubowski@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(64, NULL, 'Mable', 'Kreiger', 'Flossie Jenkins Sr.', NULL, NULL, 'epagac@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(65, NULL, 'Shirley', 'Blanda', 'Ms. Breana Boyer', NULL, NULL, 'ernser.franco@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(66, NULL, 'Libbie', 'Lind', 'Ora Farrell', NULL, NULL, 'fanny42@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(67, NULL, 'Pinkie', 'Waters', 'Albert Zemlak', NULL, NULL, 'dessie.sawayn@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(68, NULL, 'Dillan', 'Zboncak', 'Mr. Raleigh Lubowitz III', NULL, NULL, 'spinka.jermey@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(69, NULL, 'Demetrius', 'Pfeffer', 'Myrna Pagac', NULL, NULL, 'ebert.daren@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(70, NULL, 'Janice', 'Jerde', 'Ken Shanahan', NULL, NULL, 'devan97@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(71, NULL, 'Amya', 'Gutmann', 'Clifton Predovic', NULL, NULL, 'katrina44@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(72, NULL, 'Sylvia', 'King', 'Darlene Kessler', NULL, NULL, 'gerry.orn@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(73, NULL, 'Casimer', 'Hagenes', 'Bianka Dicki', NULL, NULL, 'ara.ruecker@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(74, NULL, 'Felipa', 'Gerlach', 'Alex Kassulke', NULL, NULL, 'isabelle58@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(75, NULL, 'Misty', 'Hauck', 'Baron Murphy', NULL, NULL, 'keenan.bednar@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(76, NULL, 'Violette', 'Cole', 'Gayle Franecki', NULL, NULL, 'thora96@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(77, NULL, 'Kelvin', 'Volkman', 'Prof. Hank Willms', NULL, NULL, 'kody40@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(78, NULL, 'Leonel', 'Mertz', 'Wilmer Parisian', NULL, NULL, 'lschroeder@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(79, NULL, 'Bernadette', 'Rippin', 'Carissa Mosciski', NULL, NULL, 'anastacio.powlowski@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(80, NULL, 'Miracle', 'Kertzmann', 'Freeda Hodkiewicz', NULL, NULL, 'lreilly@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(81, NULL, 'Jamar', 'Cole', 'Dr. Christy Rohan', NULL, NULL, 'eldridge90@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(82, NULL, 'Lennie', 'Schuppe', 'Kaylee Wuckert', NULL, NULL, 'annabelle.turner@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(83, NULL, 'Ephraim', 'Connelly', 'Duane Yost', NULL, NULL, 'philip.medhurst@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(84, NULL, 'Herminio', 'Hyatt', 'Miss Dixie Abshire', NULL, NULL, 'cody.schuppe@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(85, NULL, 'Ana', 'Corkery', 'Mr. Enrique Kihn', NULL, NULL, 'bryan@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(86, NULL, 'Manley', 'Vandervort', 'Jewel Kulas', NULL, NULL, 'abshire.keara@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(87, NULL, 'Jules', 'Waters', 'Mrs. Margaretta Ratke PhD', NULL, NULL, 'liliana07@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(88, NULL, 'Jessika', 'Sporer', 'Amalia Gibson', NULL, NULL, 'hbahringer@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(89, NULL, 'Maxine', 'Muller', 'Albertha Goldner DDS', NULL, NULL, 'lea50@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(90, NULL, 'Giovanny', 'Durgan', 'Emely Wilkinson DDS', NULL, NULL, 'ccremin@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(91, NULL, 'Cristian', 'Maggio', 'Jessyca O\'Keefe', NULL, NULL, 'xsanford@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(92, NULL, 'Ella', 'Kerluke', 'Ms. Bridie Kuvalis', NULL, NULL, 'eerdman@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(93, NULL, 'Don', 'Harris', 'Katharina Barton', NULL, NULL, 'prunolfsson@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(94, NULL, 'Jaunita', 'Schimmel', 'Dallas Schmitt', NULL, NULL, 'herman.bertrand@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(95, NULL, 'Julien', 'Wilkinson', 'Dr. Ignatius Wuckert I', NULL, NULL, 'renner.josefa@example.com', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(96, NULL, 'Guy', 'Altenwerth', 'Mr. Barton Spinka', NULL, NULL, 'lemuel93@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(97, NULL, 'Candice', 'Mann', 'Prof. Anibal Beatty', NULL, NULL, 'annabelle12@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(98, NULL, 'Aniyah', 'Bogisich', 'Alessandro Trantow', NULL, NULL, 'sandy08@example.org', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(99, NULL, 'Gilberto', 'Reichert', 'Manuela Vandervort', NULL, NULL, 'lauriane56@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(100, NULL, 'Virginie', 'Bernhard', 'Roderick Kiehn', NULL, NULL, 'dillan.sanford@example.net', NULL, NULL, NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `member_department`
--

CREATE TABLE `member_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) NOT NULL,
  `department_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_user`
--

CREATE TABLE `member_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_user`
--

INSERT INTO `member_user` (`id`, `member_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2020_05_21_100000_create_teams_table', 1),
(7, '2020_05_21_200000_create_team_user_table', 1),
(8, '2020_05_21_300000_create_team_invitations_table', 1),
(9, '2023_02_13_031558_create_sessions_table', 1),
(10, '2023_02_21_011308_create_permission_tables', 1),
(11, '2023_02_27_000000_create_admin_users_table', 1),
(12, '2023_03_06_114213_create_members_table', 1),
(13, '2023_03_06_114739_create_referres_table', 1),
(14, '2023_03_07_090738_create_member_organization_table', 1),
(15, '2023_03_09_013112_create_member_user_table', 1),
(16, '2023_03_13_070345_create_forms_table', 1),
(17, '2023_03_13_094408_create_form_fields_table', 1),
(18, '2023_05_02_101333_create_departments_table', 1),
(19, '2023_05_02_101622_create_admin_user_department_table', 1),
(20, '2023_05_02_135438_create_inquiries_table', 1),
(21, '2023_05_10_015101_create_emails_table', 1),
(22, '2023_05_10_020322_create_media_table', 1),
(23, '2023_05_10_073558_create_notifications_table', 1),
(24, '2023_05_25_065950_create_faqs_table', 1),
(25, '2023_06_01_005705_create_responses_table', 1),
(26, '2023_06_01_013714_create_configs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\AdminUser', 1),
(2, 'App\\Models\\AdminUser', 2),
(3, 'App\\Models\\AdminUser', 2),
(3, 'App\\Models\\AdminUser', 3),
(4, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referres`
--

CREATE TABLE `referres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inquiry_id` bigint(20) NOT NULL,
  `title` text DEFAULT NULL,
  `remark` text NOT NULL,
  `by_email` tinyint(1) NOT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `email_subject` varchar(255) DEFAULT NULL,
  `email_content` text DEFAULT NULL,
  `admin_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`id`, `inquiry_id`, `title`, `remark`, `by_email`, `email_address`, `email_subject`, `email_content`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 4, '44', '444', 0, NULL, NULL, NULL, 2, '2023-06-01 21:02:23', '2023-06-01 21:02:23'),
(2, 4, '11', '11', 0, NULL, NULL, NULL, 2, '2023-06-01 21:02:42', '2023-06-01 21:02:42'),
(3, 4, '454', '454', 0, NULL, NULL, NULL, 2, '2023-06-01 21:03:11', '2023-06-01 21:03:11'),
(4, 4, '454', '454', 0, NULL, NULL, NULL, 2, '2023-06-01 21:03:36', '2023-06-01 21:03:36'),
(5, 4, '4545', '45454', 0, NULL, NULL, NULL, 2, '2023-06-01 21:03:57', '2023-06-01 21:03:57'),
(6, 4, '5454', '454', 0, NULL, NULL, NULL, 2, '2023-06-01 21:05:01', '2023-06-01 21:05:01'),
(7, 4, '5454', '454', 0, NULL, NULL, NULL, 2, '2023-06-01 21:05:11', '2023-06-01 21:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'master', 'admin_web', '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(2, 'admin', 'admin_web', '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(3, 'department', 'admin_web', '2023-05-31 18:28:32', '2023-05-31 18:28:32'),
(4, 'member', 'web', '2023-05-31 18:28:32', '2023-05-31 18:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('fsAWoOeP6sWoA3poyah9c5gPlP6AImzNd0OP1Fvc', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidnpQQmVoNVJwV0x6WXpmZG93NHhlQzc5MGUySEtEMGZzV1VxSDk3USI7czo1NjoibG9naW5fYWRtaW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjIzOiJwYXNzd29yZF9oYXNoX2FkbWluX3dlYiI7czo2MDoiJDJ5JDEwJGZ2RWJnWDNGWm81RmpDSUJFMGI1NC50SkxOOW1ES0VydGtNUUFObUpramo0WXU0ME1uL3ZpIjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1685954802);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `personal_team` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `user_id`, `name`, `personal_team`, `created_at`, `updated_at`) VALUES
(1, 1, 'Member\'s Team', 1, '2023-05-31 18:28:32', '2023-05-31 18:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `team_invitations`
--

CREATE TABLE `team_invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_user`
--

CREATE TABLE `team_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Member', 'member@example.com', '2023-05-31 18:28:32', '$2y$10$iE5nGshefB8brVw.nR2qsOCAh2iPRxQ7jh5.MdJUl5YRlWKbL0cxG', NULL, NULL, 'CATRNao176', NULL, NULL, '2023-05-31 18:28:32', '2023-05-31 18:28:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_users_email_unique` (`email`);

--
-- Indexes for table `admin_user_department`
--
ALTER TABLE `admin_user_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_fields_form_id_foreign` (`form_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_department`
--
ALTER TABLE `member_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_user`
--
ALTER TABLE `member_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `referres`
--
ALTER TABLE `referres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teams_user_id_index` (`user_id`);

--
-- Indexes for table `team_invitations`
--
ALTER TABLE `team_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_invitations_team_id_email_unique` (`team_id`,`email`);

--
-- Indexes for table `team_user`
--
ALTER TABLE `team_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_user_team_id_user_id_unique` (`team_id`,`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_user_department`
--
ALTER TABLE `admin_user_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `configs`
--
ALTER TABLE `configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `form_fields`
--
ALTER TABLE `form_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `member_department`
--
ALTER TABLE `member_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_user`
--
ALTER TABLE `member_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referres`
--
ALTER TABLE `referres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_invitations`
--
ALTER TABLE `team_invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_user`
--
ALTER TABLE `team_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD CONSTRAINT `form_fields_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_invitations`
--
ALTER TABLE `team_invitations`
  ADD CONSTRAINT `team_invitations_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
