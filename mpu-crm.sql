-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2023 at 10:27 AM
-- Server version: 10.3.38-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 8.1.18

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
  `username` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `guid` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `current_team_id`, `profile_photo_path`, `guid`, `domain`, `created_at`, `updated_at`) VALUES
(1, 'master', 'Master', 'master@example.com', '2023-06-13 17:29:19', '$2y$10$zxNHC78K1fYWS7HnB.6K6eS3dynB41kXn/1oQlw2dVxNAEgnn1YlW', 'SaRr2o4QDX', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(2, 'admin', 'Admin', 'admin@example.com', '2023-06-13 17:29:20', '$2y$10$KHgLME07HK2Q9kD7Qpy5K.XqJk.diKxUS6Vx4z.4oc0WEWd4f3w2G', 'vXnfFLgke9VK3zS4DMYZiiEJ7bN0wFsDIWbesjeWC8OjXbHUBwsMn0FrHubE', NULL, NULL, NULL, NULL, '2023-06-13 17:29:20', '2023-06-13 17:29:20'),
(3, 'department', 'Department', 'department@example.com', '2023-06-13 17:29:20', '$2y$10$b73t7ZHWIcptlyV6n6ZILOkNVJTKTH9mb6EXMN3tP4EYR3PCSe62i', 'NONsUN5uAA', NULL, NULL, NULL, NULL, '2023-06-13 17:29:20', '2023-06-13 17:29:20'),
(4, 'wsvong', 'Seng', 'wsvong@mpu.edu.mo', '2023-06-13 17:29:20', '$2y$10$QyXPyHw.4WBKSgV4tNxN0eBrV03luBSD6wn.kiJR/5r3EKuw1dqQK', 'JaA1VWDs6y', NULL, NULL, NULL, NULL, '2023-06-13 17:29:20', '2023-06-13 17:29:20');

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
(1, 2, 1, NULL, NULL);

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
(1, 'enquiry_form', 'origin', '{\n                \"question\":\"擬入讀本校之學生現時所持有之證件 Type of personal Identification document held by the candidate\",\n                \"short\":\"持有證件\",\n                \"options\":[\n                    {\"value\":\"MO\",\"label\":\"澳門居民身份證 MACAU ID\"},\n                    {\"value\":\"CN\",\"label\":\"中華人民共和國居民身份證 CHINA ID\"},\n                    {\"value\":\"HK\",\"label\":\"香港居民身份證 HONG KONG ID\"},\n                    {\"value\":\"TW\",\"label\":\"台灣居民身份證 TAIWAN ID\"},\n                    {\"value\":\"FR\",\"label\":\"外國護照 (非持有上述證件的外國居民) PASSPORT\"}\n                ]\n            }', NULL, NULL),
(2, 'enquiry_form', 'degree', '{\n                \"question\":\"入讀課程類別 Apply programme\",\n                \"short\":\"入讀課程\",\n                \"options\":[\n                    {\"value\":\"B\",\"label\":\"學士學位 本科 Bachelor\"},\n                    {\"value\":\"M\",\"label\":\"碩士學位 Master\"},\n                    {\"value\":\"D\",\"label\":\"博士學位 Doctoral\"}\n                ]\n            }', NULL, NULL),
(3, 'enquiry_form', 'admission', '{\n                \"question\":\"入學途徑 Admission route\",\n                \"short\":\"入學途徑\",\n                \"options\":[\n                    {\"value\":\"EXAM\",\"label\":\"入學考試 Admission exams\"},\n                    {\"value\":\"RECOMMEND\",\"label\":\"澳門中學校長推薦新生入學計劃 Principals’ recommendation\"},\n                    {\"value\":\"TELENT\",\"label\":\"專才入學計劃 Local talents and professionals\"},\n                    {\"value\":\"DIRECT\",\"label\":\"直接入學 Direct admission\"},\n                    {\"value\":\"OTHER\",\"label\":\"其他 Others (現就讀於高等院校學士課程之學生適用 Applicable to students who are currently studying bachelor programs in higher education institutions)\"},\n                    {\"value\":\"GAOKAO\",\"label\":\"高考生 Mainland China GAOKAO students\"},\n                    {\"value\":\"DIRECT\",\"label\":\"直接入學 Direct admission (非高考生持國際課程或公開考試成績的內地籍高中應屆畢業生 Non-GAOKAO student and Fresh graduates from high schools who hold international curriculum or public exam result)\"},\n                    {\"value\":\"EXAM\",\"label\":\"入學考試 Admission exams\"},\n                    {\"value\":\"DIRECT\",\"label\":\"直接入學 Direct admission\"}\n                ]\n            }', NULL, NULL),
(4, 'enquiry_form', 'profile', '{\n                \"question\":\"簡介 Profiles\",\n                \"short\":\"簡介\",\n                \"options\":[\n                    {\"value\":\"STD\",\"label\":\"學生 Student\"},\n                    {\"value\":\"PAR\",\"label\":\"家長 Parent\"},\n                    {\"value\":\"TEA\",\"label\":\"老師 Teacher\"},\n                    {\"value\":\"EDU\",\"label\":\"教育機構/顧問 Education centre/counsellor\"},\n                    {\"value\":\"OTH\",\"label\":\"其他 Other\"}\n                ]\n            }', NULL, NULL),
(5, 'enquiry_form', 'apply', '{\n                \"question\":\"是否已報讀澳理大學位課程? Have you submitted an application for admission in current year?\",\n                \"short\":\"已報讀\",\n                \"options\":[\n                    {\"value\":false,\"label\":\"否 No\"},\n                    {\"value\":true,\"label\":\"是 Yes\"}\n                ]\n            }', NULL, NULL),
(6, 'enquiry_form', 'surname', '{\n                \"question\":\"姓 Surname\",\n                \"short\":\"姓\"\n            }', NULL, NULL),
(7, 'enquiry_form', 'givenname', '{\n                \"question\":\"名 Given Name\",\n                \"short\":\"名\"\n            }', NULL, NULL),
(8, 'enquiry_form', 'email', '{\n                \"question\":\"電郵 Email\",\n                \"short\":\"電郵\"\n            }', NULL, NULL),
(9, 'enquiry_form', 'contact_number', '{\n                \"question\": \"聯絡電話 phone number\",\n                \"short\":\"聯絡電話\"\n            }', NULL, NULL),
(10, 'enquiry_form', 'areacode', '{\n                \"question\":\"區號 Area code\",\n                \"short\":\"區號\"\n            }', NULL, NULL),
(11, 'enquiry_form', 'phone', '{\n                \"question\":\"電話 Phone number\",\n                \"short\":\"電話\"\n            }', NULL, NULL),
(12, 'enquiry_form', 'subjects', '{\n                \"question\":\"主旨 Subject\",\n                \"short\":\"主旨\",\n                \"options\":[\n                    {\"value\":\"ADM\",\"label\":\"入學要求 Admission Requirements\"},\n                    {\"value\":\"PRO\",\"label\":\"課程資訊 Programme Information\"},\n                    {\"value\":\"APP\",\"label\":\"報名資訊 Application Procedures\"},\n                    {\"value\":\"FEE\",\"label\":\"學費及奬學金 Fees and Scholarships\"},\n                    {\"value\":\"RES\",\"label\":\"錄取資訊 Admission Result\"},\n                    {\"value\":\"UPD\",\"label\":\"更新報名資訊 Updating Information on Application Form\"},\n                    {\"value\":\"PAY\",\"label\":\"繳費事宜 Payment Issue\"},\n                    {\"value\":\"REG\",\"label\":\"入學登記手續、體檢 Student registration and physical examination\"},\n                    {\"value\":\"OTH\",\"label\":\"其他 others\"}\n                ]\n            }', NULL, NULL),
(13, 'enquiry_form', 'agree', '{\n                \"question\":\"本人同意澳門理工大學就招生資訊或活動發送電郵或短訊予本人。<br>I give my consent to Macau Polytechnic University for send me emails or SMS regarding admissions information or activities.\",\n                \"short\":\"同意\"\n            }', NULL, NULL);

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
  `phone` varchar(255) DEFAULT NULL,
  `href` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `abbr_zh`, `abbr_en`, `abbr_pt`, `name_zh`, `name_en`, `name_pt`, `phone`, `href`, `created_at`, `updated_at`) VALUES
(1, 'Aut.', NULL, NULL, 'Kari Kautzer DVM', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(2, 'Sed.', NULL, NULL, 'Edyth Grant', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(3, 'Et.', NULL, NULL, 'Paolo Pfannerstill', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(4, 'Ut.', NULL, NULL, 'Bernardo Fadel', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(5, 'Qui.', NULL, NULL, 'Andrew Sipes MD', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(6, 'Iure.', NULL, NULL, 'Ezequiel Kulas III', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(7, 'Quia.', NULL, NULL, 'Leland Emard', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(8, 'Est.', NULL, NULL, 'Elbert Mitchell', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(9, 'Odit.', NULL, NULL, 'Ned Smitham', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(10, 'Eum.', NULL, NULL, 'Keshawn Langworth IV', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19');

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
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) NOT NULL,
  `lang` char(2) DEFAULT NULL,
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
  `has_question` tinyint(1) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry_questions`
--

CREATE TABLE `enquiry_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enquiry_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `enquiry_response_id` bigint(20) DEFAULT NULL,
  `content` text NOT NULL,
  `token` varchar(255) NOT NULL,
  `is_closed` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry_responses`
--

CREATE TABLE `enquiry_responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enquiry_question_id` bigint(20) NOT NULL,
  `title` text DEFAULT NULL,
  `remark` text NOT NULL,
  `by_email` tinyint(1) NOT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `email_subject` varchar(255) DEFAULT NULL,
  `email_content` text DEFAULT NULL,
  `admin_id` bigint(20) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `has_question` tinyint(1) DEFAULT NULL,
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
  `degree` varchar(255) NOT NULL,
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

INSERT INTO `faqs` (`id`, `degree`, `department_id`, `category_id`, `subjects`, `question_zh`, `answer_zh`, `question_en`, `answer_en`, `question_pt`, `answer_pt`, `created_at`, `updated_at`) VALUES
(1, '\"B\"', 1, 1, '[\"ADM\"]', '澳門理工大學提供哪些課程?', '本校提供<a href=\"https://www.mpu.edu.mo/admission_local/zh/postgraduate.php\" target=\"_blank\">「研究生課程」</a>及\n            <a href=\"https://www.mpu.edu.mo/admission_local/zh/undergraduate.php\" target=\"_blank\">「學士學位課程」</a>\n            （範疇包括：工商管理、資訊科技、語言與翻譯、公共管理與服務、藝術、健康科學及體育）。', NULL, NULL, NULL, NULL, NULL, '2023-06-15 00:24:08'),
(2, '', 1, 1, '[\"ADM\"]', '課程採用甚麼學制?', '博士學位課程修讀期為三年、碩士學位課程修讀期為兩年；<br>學士學位課程均為四年制。', NULL, NULL, NULL, NULL, NULL, NULL),
(3, '', 1, 1, '[\"ADM\"]', '課程採用甚麼語言授課?', '各課程採用不同的授課語言。有關課程之授課語言可於「研究生課程」及「學士學位課程」中瀏覽。', NULL, NULL, NULL, NULL, NULL, NULL),
(4, '', 1, 1, '[\"ADM\",\"PRO\"]', '課程是否限制文科或理科組別學生報考?', '本校文理兼收，任何組別學生皆可報考本校提供的課程。', NULL, NULL, NULL, NULL, NULL, NULL),
(5, '', 1, 2, '[\"PRO\"]', '	\n            報考研究生課程須具備哪些條件?', '博士學位課程：<br>\n            具碩士學位或同等學歷；或正修讀碩士學位學歷課程最後一年；及\n            尚須具備報讀課程要求的其他報考條件。<br>\n            碩士學位課程：\n            具學士學位或同等學歷；或正修讀學士學位學歷課程最後一年；及\n            尚須具備報讀課程要求的其他報考條件。', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `filleds`
--

CREATE TABLE `filleds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `form_id` bigint(20) NOT NULL,
  `staff` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `filleds`
--

INSERT INTO `filleds` (`id`, `form_id`, `staff`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 0, NULL, '2023-06-13 20:20:07', '2023-06-13 20:20:07'),
(2, 1, 0, NULL, '2023-06-13 20:20:35', '2023-06-13 20:20:35'),
(3, 1, 0, NULL, '2023-06-14 01:12:18', '2023-06-14 01:12:18'),
(4, 1, 0, NULL, '2023-06-14 01:13:34', '2023-06-14 01:13:34'),
(5, 1, 0, NULL, '2023-06-14 01:13:55', '2023-06-14 01:13:55'),
(6, 1, 0, NULL, '2023-06-14 01:14:53', '2023-06-14 01:14:53'),
(7, 1, 0, NULL, '2023-06-14 16:28:24', '2023-06-14 16:28:24');

-- --------------------------------------------------------

--
-- Table structure for table `filled_fields`
--

CREATE TABLE `filled_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `filled_id` bigint(20) NOT NULL,
  `form_field_id` bigint(20) NOT NULL,
  `field_value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `filled_fields`
--

INSERT INTO `filled_fields` (`id`, `filled_id`, `form_field_id`, `field_value`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '5454', '2023-06-13 20:20:07', '2023-06-13 20:20:07'),
(2, 1, 4, 'M', '2023-06-13 20:20:07', '2023-06-13 20:20:07'),
(3, 2, 1, '454', '2023-06-13 20:20:35', '2023-06-13 20:20:35'),
(4, 2, 4, 'B', NULL, NULL),
(5, 5, 1, '4564', '2023-06-14 01:13:55', '2023-06-14 01:13:55'),
(6, 5, 2, 'F', '2023-06-14 01:13:55', '2023-06-14 01:13:55'),
(7, 5, 3, '2023-06-01', '2023-06-14 01:13:55', '2023-06-14 01:13:55'),
(8, 5, 4, 'B', '2023-06-14 01:13:55', '2023-06-14 01:13:55'),
(9, 6, 1, '454', '2023-06-14 01:14:53', '2023-06-14 01:14:53'),
(10, 7, 1, '45456', '2023-06-14 16:28:24', '2023-06-14 16:28:24'),
(11, 7, 6, '<p>4545asfasfd</p>', '2023-06-14 16:28:24', '2023-06-14 16:28:24');

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
  `for_staff` tinyint(1) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `welcome` text DEFAULT NULL,
  `thanks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `department_id`, `name`, `title`, `description`, `require_login`, `for_staff`, `published`, `welcome`, `thanks`, `created_at`, `updated_at`) VALUES
(1, 1, 'first form', 'First form of title', '<p>45454</p>', 0, 0, 1, NULL, NULL, NULL, '2023-06-14 17:38:42'),
(2, 1, 'second form', 'Second form of title', NULL, 1, 0, 1, NULL, NULL, NULL, '2023-06-14 17:38:50'),
(3, 1, '454', '5454', '5454', 0, 1, 1, NULL, NULL, '2023-06-13 17:40:15', '2023-06-13 17:42:11');

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
  `text` varchar(255) DEFAULT NULL,
  `options` text DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `in_column` tinyint(1) NOT NULL DEFAULT 0,
  `rule` varchar(255) DEFAULT NULL,
  `validate` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_fields`
--

INSERT INTO `form_fields` (`id`, `form_id`, `field_name`, `field_label`, `type`, `text`, `options`, `required`, `in_column`, `rule`, `validate`, `remark`, `created_at`, `updated_at`) VALUES
(1, 1, 'username', 'Username', 'input', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, '2023-06-13 23:30:24'),
(2, 1, 'gender', 'Gender', 'radio', NULL, '[{\"value\":\"M\",\"label\":\"Male\"},{\"value\":\"F\",\"label\":\"Female\"}]', 1, 0, NULL, NULL, NULL, NULL, NULL),
(3, 1, 'dob', 'DOB', 'date', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL),
(4, 1, 'education', 'Education', 'select', NULL, '[{\"value\":\"B\",\"label\":\"Bachalor\"},{\"value\":\"M\",\"label\":\"Master\"},{\"value\":\"D\",\"label\":\"PhD.\"}]', 0, 1, NULL, NULL, NULL, NULL, NULL),
(5, 1, 'email', 'Email', 'email', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL),
(6, 1, 'remark', 'Remark', 'richtext', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '2023-06-13 18:02:07'),
(7, 2, 'username', 'Username', 'input', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL),
(8, 3, 'username', 'Username', 'input', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL);

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
(1, 'App\\Models\\Response', 4, 'b241f9b8-d744-408b-a72d-31e060dd3652', 'responseAttachments', 'card_blue', 'card_blue.png', 'image/png', 'media', 'media', 168461, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 01:04:41', '2023-06-15 01:04:41'),
(2, 'App\\Models\\Response', 5, '8a76258c-f61e-49f1-b5fc-20c9e5cc1f58', 'responseAttachments', 'card_blue', 'card_blue.png', 'image/png', 'media', 'media', 168461, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 01:07:12', '2023-06-15 01:07:12'),
(3, 'App\\Models\\Response', 10, '296fa7b9-aa21-4f70-958d-4df2d64cbe49', 'responseAttachments', 'card_red', 'card_red.png', 'image/png', 'media', 'media', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 01:15:06', '2023-06-15 01:15:06'),
(4, 'App\\Models\\EnquiryResponse', 6, 'a08af0e7-2271-48a8-89e4-d80be4e1495d', 'enquiryResponseAttachments', 'card_yellow', 'card_yellow.png', 'image/png', 'public', 'public', 177872, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 20:39:54', '2023-06-15 20:39:55'),
(5, 'App\\Models\\EnquiryResponse', 7, '1697b0cf-6d2a-4195-a624-fd9b5ac69557', 'enquiryResponseAttachments', 'card_red', 'card_red.png', 'image/png', 'public', 'public', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 20:45:27', '2023-06-15 20:45:27'),
(6, 'App\\Models\\EnquiryQuestion', 4, 'c0244575-a67d-44d1-b344-44d501e1623d', 'enquiryQuestionAttachments', 'Card_green', 'Card_green.png', 'image/png', 'inquiry', 'inquiry', 148351, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 20:48:39', '2023-06-15 20:48:39'),
(7, 'App\\Models\\EnquiryResponse', 8, 'a66875ce-2ecc-41e2-9092-12c73303be51', 'enquiryResponseAttachments', 'Card_green', 'Card_green.png', 'image/png', 'public', 'public', 148351, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 21:02:22', '2023-06-15 21:02:22'),
(8, 'App\\Models\\EnquiryResponse', 9, '9fff146a-f830-4cfb-b542-7cbfc7698bb6', 'enquiryResponseAttachments', 'card_red', 'card_red.png', 'image/png', 'inquiry', 'inquiry', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 21:09:17', '2023-06-15 21:09:17'),
(9, 'App\\Models\\EnquiryResponse', 10, '2c72ba98-fa59-4d18-ba6b-ee90598f9b3b', 'enquiryResponseAttachments', 'card_red', 'card_red.png', 'image/png', 'inquiry', 'inquiry', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 23:35:10', '2023-06-15 23:35:10'),
(10, 'App\\Models\\EnquiryResponse', 11, 'd99f624d-5277-4d50-a8ee-df40aed98a31', 'enquiryResponseAttachments', 'card_red', 'card_red.png', 'image/png', 'inquiry', 'inquiry', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 23:35:23', '2023-06-15 23:35:23'),
(11, 'App\\Models\\EnquiryResponse', 12, '9991cb02-f6e4-4b74-9488-ac893239a328', 'enquiryResponseAttachments', 'card_red', 'card_red.png', 'image/png', 'inquiry', 'inquiry', 106143, '[]', '[]', '[]', '[]', 1, '2023-06-15 23:36:37', '2023-06-15 23:36:37'),
(12, 'App\\Models\\EnquiryResponse', 13, '3efb76c8-47b8-470b-9539-1b45f1cad310', 'enquiryResponseAttachments', 'card_red', 'card_red.png', 'image/png', 'inquiry', 'inquiry', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 23:38:13', '2023-06-15 23:38:13'),
(13, 'App\\Models\\EnquiryResponse', 14, 'db6a9a48-5a8b-4163-943b-5d186bca3f25', 'enquiryResponseAttachments', 'card_red', 'card_red.png', 'image/png', 'inquiry', 'inquiry', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-15 23:38:13', '2023-06-15 23:38:13'),
(14, 'App\\Models\\EnquiryResponse', 29, '3168edf1-d697-4a02-9f37-9d4e30d8c165', 'enquiryResponseAttachments', 'card_yellow', 'card_yellow.png', 'image/png', 'inquiry', 'inquiry', 177872, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-19 01:36:48', '2023-06-19 01:36:49'),
(15, 'App\\Models\\EnquiryResponse', 34, '1497c00c-b312-4b6e-974e-83aac1184a79', 'enquiryResponseAttachments', 'card_yellow', 'card_yellow.png', 'image/png', 'inquiry', 'inquiry', 177872, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-06-19 17:44:14', '2023-06-19 17:44:14');

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
(1, 1, 'Connie', 'Rice', 'Reed Schuppe', NULL, NULL, 'schaefer.mose@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(2, NULL, 'Ian', 'Shields', 'Ms. Laisha Heller', NULL, NULL, 'audra.nikolaus@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(3, NULL, 'Leonardo', 'Kuphal', 'Ms. Kaia Orn', NULL, NULL, 'torphy.timothy@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(4, NULL, 'Evangeline', 'Aufderhar', 'Prof. Katarina Kertzmann II', NULL, NULL, 'senger.carlee@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(5, NULL, 'Nicolette', 'Mann', 'Dasia Christiansen', NULL, NULL, 'delpha.dare@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(6, NULL, 'Pasquale', 'Zulauf', 'Dr. Antonina Larson PhD', NULL, NULL, 'destinee.hermiston@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(7, NULL, 'Alisha', 'Rodriguez', 'Elian Durgan PhD', NULL, NULL, 'major08@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(8, NULL, 'Thurman', 'Deckow', 'Tate Cruickshank Jr.', NULL, NULL, 'waino46@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(9, NULL, 'Selmer', 'O\'Hara', 'Nellie Brekke', NULL, NULL, 'emurazik@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(10, NULL, 'Tierra', 'Turner', 'Clyde Gulgowski', NULL, NULL, 'jewel.stroman@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(11, NULL, 'Hattie', 'Runte', 'Prof. Efrain Ward', NULL, NULL, 'zkuhn@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(12, NULL, 'Polly', 'Zulauf', 'Prof. Colin Hegmann MD', NULL, NULL, 'rkuhn@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(13, NULL, 'Meaghan', 'Kreiger', 'Clara Cormier', NULL, NULL, 'judah.collins@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(14, NULL, 'Lurline', 'Swaniawski', 'Dorthy O\'Reilly', NULL, NULL, 'amills@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(15, NULL, 'Monty', 'Bins', 'Berenice Gorczany', NULL, NULL, 'ebert.jeremy@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(16, NULL, 'Elisha', 'Stroman', 'Regan Oberbrunner', NULL, NULL, 'schumm.brooklyn@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(17, NULL, 'Alayna', 'Mitchell', 'Mr. Brody Volkman', NULL, NULL, 'qritchie@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(18, NULL, 'Westley', 'Anderson', 'Mauricio O\'Reilly', NULL, NULL, 'mike59@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(19, NULL, 'Mathias', 'Cormier', 'Dr. Jennyfer Deckow PhD', NULL, NULL, 'joseph.waelchi@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(20, NULL, 'Kiara', 'Trantow', 'Ephraim Jerde', NULL, NULL, 'dzieme@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(21, NULL, 'River', 'Marks', 'Cleora Jaskolski', NULL, NULL, 'marquardt.ocie@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(22, NULL, 'Abbie', 'Botsford', 'Ms. Krystina Monahan', NULL, NULL, 'zboncak.garett@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(23, NULL, 'Dee', 'Carroll', 'Winifred Schumm', NULL, NULL, 'omosciski@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(24, NULL, 'Rodger', 'Doyle', 'Dr. Armando Schroeder IV', NULL, NULL, 'kkreiger@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(25, NULL, 'Electa', 'Turner', 'Marjorie Lakin', NULL, NULL, 'brandy99@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(26, NULL, 'Madisyn', 'Gulgowski', 'Noah Raynor', NULL, NULL, 'fredy18@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(27, NULL, 'Raoul', 'Langosh', 'Miss Maudie Hessel', NULL, NULL, 'grice@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(28, NULL, 'Alexandra', 'Hirthe', 'Tevin McCullough', NULL, NULL, 'matilde44@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(29, NULL, 'Graham', 'Schaden', 'Golden Shanahan', NULL, NULL, 'elouise11@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(30, NULL, 'Esmeralda', 'Denesik', 'Crystel Will', NULL, NULL, 'hickle.cathy@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(31, NULL, 'Carlo', 'Collier', 'Katherine Larkin', NULL, NULL, 'kulas.milan@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(32, NULL, 'Charlene', 'King', 'Molly Gusikowski', NULL, NULL, 'jamel.ratke@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(33, NULL, 'Krystal', 'Farrell', 'Dr. Joe Ledner Sr.', NULL, NULL, 'wpfannerstill@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(34, NULL, 'Addie', 'Ryan', 'Vernie Harber', NULL, NULL, 'jacey63@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(35, NULL, 'Carol', 'Lemke', 'Camylle Smith', NULL, NULL, 'shany71@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(36, NULL, 'Lucy', 'McGlynn', 'Frank Willms DDS', NULL, NULL, 'okon.ibrahim@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(37, NULL, 'Alvis', 'Gleason', 'Mr. Kamron Schultz', NULL, NULL, 'mohr.izabella@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(38, NULL, 'Marcel', 'Thompson', 'Leanna Schmeler', NULL, NULL, 'price.mose@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(39, NULL, 'Abraham', 'Dickens', 'Shemar Bins', NULL, NULL, 'michale.jacobson@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(40, NULL, 'Kaia', 'Mante', 'Rogers Kertzmann', NULL, NULL, 'ruecker.joey@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(41, NULL, 'Karianne', 'Legros', 'Franz Hagenes', NULL, NULL, 'rosemarie.mraz@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(42, NULL, 'Lavada', 'Welch', 'Ms. Chanel Botsford', NULL, NULL, 'uriah13@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(43, NULL, 'Casimer', 'Cormier', 'Lowell Ebert MD', NULL, NULL, 'aiden77@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(44, NULL, 'Abe', 'Crooks', 'Jazlyn Baumbach PhD', NULL, NULL, 'monahan.lynn@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(45, NULL, 'Mohammad', 'Farrell', 'Mrs. Courtney Bins', NULL, NULL, 'gemmerich@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(46, NULL, 'Janet', 'Haley', 'Lora Stehr', NULL, NULL, 'ratke.diana@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(47, NULL, 'Elta', 'Lowe', 'Margret Raynor', NULL, NULL, 'gunner.braun@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(48, NULL, 'Elza', 'O\'Reilly', 'Clemens Legros Jr.', NULL, NULL, 'emiliano.koss@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(49, NULL, 'George', 'Crona', 'Hellen Labadie', NULL, NULL, 'tkilback@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(50, NULL, 'Murl', 'Weber', 'Dr. Wilmer Stamm', NULL, NULL, 'quigley.isabel@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(51, NULL, 'Westley', 'Medhurst', 'Dr. Kian Konopelski MD', NULL, NULL, 'brian93@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(52, NULL, 'Kaylin', 'Rowe', 'Winfield Bogan', NULL, NULL, 'maurice.vandervort@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(53, NULL, 'Lenny', 'Jacobs', 'Cleo Prosacco', NULL, NULL, 'selena.ward@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(54, NULL, 'Joseph', 'Collins', 'Mya Boyer', NULL, NULL, 'cconroy@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(55, NULL, 'Alessia', 'Roberts', 'Otho Koch', NULL, NULL, 'grath@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(56, NULL, 'Seth', 'Larkin', 'Ms. Marjory Bode Jr.', NULL, NULL, 'kassulke.margarette@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(57, NULL, 'Freddy', 'Lueilwitz', 'Vivien Daugherty', NULL, NULL, 'imani.kutch@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(58, NULL, 'Nichole', 'Cummings', 'Dr. Armand Thompson IV', NULL, NULL, 'awaelchi@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(59, NULL, 'Rosemarie', 'Hackett', 'Kaleb Hammes', NULL, NULL, 'kamille81@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(60, NULL, 'Camron', 'Murphy', 'Colleen Wuckert', NULL, NULL, 'bogisich.tyreek@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(61, NULL, 'Damon', 'Zulauf', 'Sincere Larson', NULL, NULL, 'chasity40@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(62, NULL, 'Malcolm', 'Pfeffer', 'Priscilla Corkery', NULL, NULL, 'prohaska.deon@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(63, NULL, 'Cleve', 'Ernser', 'Dr. Steve Ryan I', NULL, NULL, 'kleannon@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(64, NULL, 'Julio', 'Gutmann', 'Austyn Kunze', NULL, NULL, 'ubeatty@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(65, NULL, 'Jessika', 'Botsford', 'Stanford Witting', NULL, NULL, 'lisette85@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(66, NULL, 'Sunny', 'Ullrich', 'Mr. Al Marquardt', NULL, NULL, 'connelly.lessie@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(67, NULL, 'Mandy', 'Hahn', 'Prof. Gunner Rippin Jr.', NULL, NULL, 'simone32@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(68, NULL, 'Virgil', 'Kutch', 'Zelma Jenkins', NULL, NULL, 'gorczany.shanny@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(69, NULL, 'Rene', 'Ullrich', 'Dina Schneider', NULL, NULL, 'magali30@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(70, NULL, 'Prudence', 'Wilkinson', 'Tracy Leffler', NULL, NULL, 'walker.whitney@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(71, NULL, 'Kenya', 'Schiller', 'Alta Rath', NULL, NULL, 'wilburn.fay@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(72, NULL, 'Andres', 'Herzog', 'Constance Boyer III', NULL, NULL, 'bruen.dayana@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(73, NULL, 'Brendon', 'Cartwright', 'Stewart Rodriguez', NULL, NULL, 'taryn78@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(74, NULL, 'Ashlynn', 'Runolfsson', 'Keith Jakubowski', NULL, NULL, 'maggio.jodie@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(75, NULL, 'Laverna', 'Cole', 'Isabelle Steuber', NULL, NULL, 'zhackett@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(76, NULL, 'David', 'Konopelski', 'Aurelia Stiedemann', NULL, NULL, 'schinner.kristin@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(77, NULL, 'Tod', 'Thiel', 'Mathias Sporer', NULL, NULL, 'kuhlman.hertha@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(78, NULL, 'Rigoberto', 'Rolfson', 'Presley Mann', NULL, NULL, 'pbeatty@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(79, NULL, 'Emanuel', 'Miller', 'Bethany Braun', NULL, NULL, 'kristopher.schuppe@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(80, NULL, 'Thurman', 'Morissette', 'Dillon Denesik Sr.', NULL, NULL, 'jmcclure@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(81, NULL, 'Velda', 'O\'Hara', 'Remington Kautzer', NULL, NULL, 'hskiles@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(82, NULL, 'Vaughn', 'Emard', 'Kraig Swaniawski Sr.', NULL, NULL, 'hal55@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(83, NULL, 'Kristopher', 'Hill', 'Sarah Willms', NULL, NULL, 'wanda.huel@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(84, NULL, 'Cory', 'Beier', 'Dan Schneider', NULL, NULL, 'crona.deon@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(85, NULL, 'Jerald', 'Wyman', 'Mr. Jo Hansen III', NULL, NULL, 'rachel65@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(86, NULL, 'Domenico', 'Haag', 'Kailyn Sawayn Jr.', NULL, NULL, 'rohara@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(87, NULL, 'Hassie', 'Hansen', 'Mrs. Maria Herzog', NULL, NULL, 'jennyfer.oberbrunner@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(88, NULL, 'Herta', 'Kuhn', 'Dr. Peter Ebert II', NULL, NULL, 'emelie.blick@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(89, NULL, 'Jazmin', 'Quitzon', 'Miss Lilla Gusikowski MD', NULL, NULL, 'stanton.brown@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(90, NULL, 'Flossie', 'Stoltenberg', 'Brandt Blick', NULL, NULL, 'wilfrid.wilkinson@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(91, NULL, 'Kenna', 'King', 'Prof. Jacklyn Wiegand IV', NULL, NULL, 'jamel23@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(92, NULL, 'Juliana', 'Hackett', 'Jessie Senger', NULL, NULL, 'csatterfield@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(93, NULL, 'Oran', 'Pollich', 'Candido Jones', NULL, NULL, 'koelpin.itzel@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(94, NULL, 'Macie', 'Reichert', 'Vesta Collins', NULL, NULL, 'valentina.haag@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(95, NULL, 'Harry', 'Reichert', 'Rudy Fadel', NULL, NULL, 'oliver80@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(96, NULL, 'Sincere', 'O\'Conner', 'Murl Reinger', NULL, NULL, 'nbahringer@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(97, NULL, 'Christa', 'White', 'Stephany Jenkins', NULL, NULL, 'holly14@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(98, NULL, 'Mckenna', 'Schimmel', 'Robyn Hegmann', NULL, NULL, 'berenice58@example.com', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(99, NULL, 'German', 'Klocko', 'Lola Kuhlman', NULL, NULL, 'yesenia09@example.org', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(100, NULL, 'Sam', 'Kris', 'Madaline Fahey', NULL, NULL, 'freida.maggio@example.net', NULL, NULL, NULL, NULL, '2023-06-13 17:29:19', '2023-06-13 17:29:19');

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
(20, '2023_05_02_135438_create_enquiries_table', 1),
(21, '2023_05_10_015101_create_emails_table', 1),
(22, '2023_05_10_020322_create_media_table', 1),
(23, '2023_05_10_073558_create_notifications_table', 1),
(24, '2023_05_25_065950_create_faqs_table', 1),
(25, '2023_06_01_005705_create_responses_table', 1),
(26, '2023_06_01_013714_create_configs_table', 1),
(27, '2023_06_08_044237_add_ldap_columns_to_users_table', 1),
(28, '2023_06_14_022803_create_filleds_table', 2),
(29, '2023_06_14_022812_create_filled_fields_table', 2),
(30, '2023_06_01_005705_create_enquiry_responses_table', 3),
(31, '2023_06_16_022512_create_enquiry_questions_table', 3);

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
(2, 'App\\Models\\AdminUser', 2);

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
(1, 'master', 'admin_web', '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(2, 'admin', 'admin_web', '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(3, 'department', 'admin_web', '2023-06-13 17:29:19', '2023-06-13 17:29:19'),
(4, 'member', 'web', '2023-06-13 17:29:19', '2023-06-13 17:29:19');

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
  `user_id` char(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9hbVtxFCmclF6ClcH61sN82OJBRbloTfutxBtY3F', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiVEtaNnlrS3h0azlQU2N0Y3M4MjZsVUhUQnl2VUV4YmZyOXh3WVVWUSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ4OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvZW5xdWlyeS90aWNrZXQvNDEvZmFlZGQ5YTQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU2OiJsb2dpbl9hZG1pbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MjM6InBhc3N3b3JkX2hhc2hfYWRtaW5fd2ViIjtzOjYwOiIkMnkkMTAkS0hnTE1FMDdISzJROWtEN1FweTVLLlhxSmsuZGlLeFVTNlZ4NHouNG9jMFdFV2Q0ZjN3MkciO3M6MTk6ImN1cnJlbnREZXBhcnRtZW50SWQiO2k6MTt9', 1687227957);

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
(1, 1, 'Member\'s Team', 1, '2023-06-13 17:29:20', '2023-06-13 17:29:20');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `guid` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`, `guid`, `domain`) VALUES
(1, 'Member', 'member@example.com', '2023-06-13 17:29:20', '$2y$10$F9OrH2dY9z6XjThoWn6wRe4Uy6lh5Wt3NpwnESZTVt1QYdfI.WCom', NULL, NULL, 'cSBuZNuqvzRjzKbWTgRwjEtUoTZRcUr8usasPwkCbzmnn8QRzYfG6iKNi091', 1, NULL, '2023-06-13 17:29:20', '2023-06-14 16:39:24', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_users_email_unique` (`email`),
  ADD UNIQUE KEY `admin_users_guid_unique` (`guid`);

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
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquiry_questions`
--
ALTER TABLE `enquiry_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquiry_responses`
--
ALTER TABLE `enquiry_responses`
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
-- Indexes for table `filleds`
--
ALTER TABLE `filleds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filled_fields`
--
ALTER TABLE `filled_fields`
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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_guid_unique` (`guid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_user_department`
--
ALTER TABLE `admin_user_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enquiry_questions`
--
ALTER TABLE `enquiry_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enquiry_responses`
--
ALTER TABLE `enquiry_responses`
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
-- AUTO_INCREMENT for table `filleds`
--
ALTER TABLE `filleds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `filled_fields`
--
ALTER TABLE `filled_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `form_fields`
--
ALTER TABLE `form_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
