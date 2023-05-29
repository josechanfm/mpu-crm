-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2023 at 09:32 AM
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
(1, 'Master', 'master@example.com', '2023-05-09 18:09:48', '$2y$10$Xq5WnzXEL/We1CmSGTUBbux3KMZKCT/N/exnJ5UTQuRmOTOyM6dly', 'XPplVFZ2TR', NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(2, 'Admin', 'admin@example.com', '2023-05-09 18:09:48', '$2y$10$/mCoF7AVJwQ/hoNy0Cwade0rPfLe87cf1OM2tWlOR4IoCCR7RaNvq', 'wyTPuVXWHm', NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(3, 'Organization', 'organization@example.com', '2023-05-09 18:09:48', '$2y$10$g9I0Vn9FwtN2yqlzMV9pa.iM1GggzSFvQ/sSLXyKOZ7UO2cJxKX5C', 'DHhqYfLBUw', NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48');

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
-- Table structure for table `admin_user_organization`
--

CREATE TABLE `admin_user_organization` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_user_id` bigint(20) NOT NULL,
  `organization_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_user_organization`
--

INSERT INTO `admin_user_organization` (`id`, `admin_user_id`, `organization_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 2, 3, NULL, NULL),
(3, 3, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cert_title` varchar(255) DEFAULT NULL,
  `cert_body` varchar(255) DEFAULT NULL,
  `cert_logo` varchar(255) DEFAULT NULL,
  `cert_template` varchar(255) DEFAULT NULL,
  `number_format` varchar(255) NOT NULL DEFAULT 'CERT-00000',
  `rank_caption` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_member`
--

CREATE TABLE `certificate_member` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_id` bigint(20) NOT NULL,
  `member_id` bigint(20) NOT NULL,
  `number` int(11) NOT NULL,
  `number_display` int(11) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `avata` date DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `authorize_by` varchar(255) DEFAULT NULL,
  `rank` varchar(255) DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Est.', NULL, NULL, 'Rudolph Greenfelder', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(2, 'Et.', NULL, NULL, 'Reagan Feil', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(3, 'Ipsa.', NULL, NULL, 'Prof. Marcellus Wisozk', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(4, 'Rem.', NULL, NULL, 'Evans Ebert', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(5, 'Hic.', NULL, NULL, 'Miss Euna Abshire II', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(6, 'Ut.', NULL, NULL, 'Madelyn Cummings PhD', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(7, 'Cum.', NULL, NULL, 'Emiliano Sipes', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(8, 'Sit.', NULL, NULL, 'Iliana Hand', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(9, 'Sunt.', NULL, NULL, 'Lina Larson', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(10, 'Non.', NULL, NULL, 'Prof. Darrion Fahey III', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48');

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

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `emailable_id`, `emailable_type`, `admin_user_id`, `sender`, `receiver`, `cc`, `subject`, `content`, `created_at`, `updated_at`) VALUES
(1, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'milford51@yahoo.com', '', 'Sunt.', 'Facilis rem velit voluptates quis autem nam facilis. Sint et voluptates non est recusandae amet et. Non provident non at eaque assumenda esse. Nulla tenetur reiciendis est et perspiciatis totam. Maxime molestias dignissimos unde cumque. Est et perferendis aut voluptas. In laboriosam numquam voluptates sit quia sed placeat. Distinctio quaerat optio a aut quae. Inventore aut consequatur similique vel omnis corrupti. Reprehenderit accusantium deserunt soluta sed.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(2, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'kenton37@runolfsson.com', '', 'Et.', 'Ea labore ad suscipit odit autem ea et. Blanditiis ipsa recusandae consequatur. Voluptas deleniti fuga alias dolor voluptatem et inventore. Neque et et eum doloremque architecto. Temporibus doloribus itaque et voluptatem. Nam minima magnam sequi placeat provident minima quo. Iusto illum qui voluptates aspernatur pariatur iste quod. Magnam ipsum ad non. Ducimus non dolore amet quibusdam aperiam reiciendis. Sed atque nihil nobis assumenda. Incidunt deleniti dolor fugiat.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(3, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'magdalena31@gmail.com', '', 'Qui.', 'Eaque non nostrum inventore consequatur soluta sint sit. Error reprehenderit doloremque error beatae. Et amet non delectus saepe facilis facere asperiores. Iusto dolores quis ab delectus. Qui rerum voluptatem molestias veniam est omnis. Quia nihil et beatae dolore ducimus earum. Vel eveniet culpa perspiciatis quis. Quibusdam ipsa autem voluptatum doloremque voluptatem provident et. Dolor id iure molestiae distinctio.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(4, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'wellington99@rodriguez.com', '', 'Cum.', 'Et quis aspernatur aut fugit odit sit eos adipisci. Consectetur ratione ipsam deserunt sunt. Omnis sit ipsum aut expedita aut. Asperiores quisquam omnis qui voluptate aut dicta fugiat qui. Suscipit in adipisci et. Natus animi dignissimos et rerum delectus sit. Id voluptatem fugiat laboriosam accusantium placeat. Tempore rerum occaecati iste molestiae officia sapiente rem ipsum. Aut blanditiis facilis earum provident aliquam harum sed porro. Et quia rerum repellendus rerum doloribus soluta.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(5, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'jovani43@dicki.com', '', 'Ut.', 'Nesciunt aspernatur enim placeat aut quis voluptates. Quia vitae dolor doloremque culpa cum ea. Repellendus quidem vel quam eaque maiores. Impedit unde dolores eum iste corporis corrupti. Asperiores illum et quam in mollitia iste dolor. Magni modi aliquam sint qui eum in. Consequuntur asperiores fugit nisi sit libero sunt tenetur rerum.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(6, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'samantha42@kihn.biz', '', 'Et.', 'Quis neque cupiditate non excepturi. Consequatur consequatur aut vel. Mollitia soluta consequatur et ea porro voluptates aut. Fugit quia accusamus eaque delectus distinctio quam. Quia quo et quibusdam tenetur. Quis magni ut voluptas architecto officia aspernatur molestiae. A illum unde culpa aliquid consectetur.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(7, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'dawn42@witting.org', '', 'Aut.', 'Rerum dolorem facilis iste qui ipsam sunt vel. Consequatur dolor molestiae aut expedita tempore vero ut. Velit non modi suscipit inventore. Ut tempora qui facilis consequuntur dolor. Porro nostrum aut quod occaecati delectus. Odit porro sequi eaque nam id. Optio hic velit eligendi ut sunt temporibus illum. Debitis modi libero aperiam voluptatem sapiente quidem ducimus. Praesentium quaerat molestiae repellat eos. Harum occaecati soluta repellat ipsum molestias. Ut qui omnis hic ducimus ex.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(8, 3, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'zackary41@hartmann.com', '', 'Eum.', 'Quisquam quisquam distinctio vel asperiores. Dolorem officia fugiat a sint laudantium repellat. Omnis in asperiores corrupti est temporibus nostrum quos dolorem. Iste eaque iste neque hic neque. Sit eaque impedit reprehenderit ex vitae. Et assumenda est fugit et. Similique assumenda amet omnis corporis sint animi. Libero possimus omnis et voluptatibus voluptatem doloribus voluptatum. Eius nihil quam voluptatem ullam distinctio voluptatibus.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(9, 2, 'App\\Models\\Inquiry', 2, 'no-replay@mpu.edu.mo', 'xkuhn@yahoo.com', '', 'Quia.', 'Exercitationem quos accusantium sunt facere aut dolore quidem. Exercitationem nihil iste ea at non tempore impedit. Commodi dolorum explicabo cum modi expedita sit. Provident occaecati non vel eveniet. Consequatur velit repellendus repudiandae. Enim quo error sunt. Asperiores excepturi illo laboriosam modi voluptas ut nobis. Molestias nihil ipsa saepe ea quasi ex. Sint laborum nostrum dolore enim quae excepturi consequuntur veniam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(10, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'lockman.luz@hintz.com', '', 'Quos.', 'Natus delectus enim id eum. Et maxime non adipisci animi dolorem eligendi. Dolore itaque est repellendus ut iure. Et aut consequuntur ut reiciendis minus magni. Aspernatur occaecati facilis cumque aut autem voluptatem accusamus. Ex consectetur voluptas aut est eius molestiae. Nihil iste non perferendis non. Perferendis consequatur deleniti exercitationem quisquam autem. Sed sit consequatur saepe eum incidunt explicabo ea. Non aut maiores voluptatem reiciendis hic quos maxime.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(11, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'harber.shawn@ortiz.com', '', 'Quis.', 'Aut necessitatibus pariatur voluptas qui minima voluptate id. Fuga eos sed quis autem et. Temporibus minus aut nihil tenetur repellendus sint voluptas. Iusto placeat eaque expedita eligendi expedita. Non non sunt voluptates optio. Qui culpa sit delectus quo quaerat dolor. Porro et nam saepe laborum qui commodi. Deserunt et voluptas odit. Eligendi in sit quisquam ea.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(12, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'cmitchell@gmail.com', '', 'Non.', 'Aliquid perspiciatis rerum consectetur repellendus et voluptas. Nesciunt quibusdam doloremque est omnis sit voluptatum molestiae. Eos voluptas similique repellat pariatur dolorem animi. Ipsam alias eveniet quasi quia quo. Enim exercitationem quidem est et ut. Error aut debitis ipsum voluptas rerum quis. Laborum voluptas doloremque eveniet architecto. Nihil eos laboriosam odio aspernatur recusandae voluptatem eius.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(13, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'cmarks@cassin.net', '', 'Ut.', 'Non voluptates fuga nostrum qui in sunt. Dolorem occaecati omnis est magni ut aut officiis. Tempora sit quo numquam totam sint aperiam tempore. Vel nulla dignissimos et ea rerum. Qui autem maxime autem in. Beatae assumenda hic ad. Dicta quibusdam vel quidem ut. Inventore qui laboriosam esse ipsam. Libero eos molestiae nobis non laudantium aut similique voluptates. Porro inventore unde vel nihil quod quia. Officia enim dolore ut mollitia dolore.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(14, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'nhand@little.com', '', 'Qui.', 'Aliquid dolores reiciendis impedit maiores quia. Quo sit et deserunt nesciunt. Porro sit aut a a. Atque est dignissimos in atque autem nihil cupiditate. Vitae et modi occaecati qui impedit. Ut quia alias similique ex consequatur magnam quia repudiandae. Velit sunt tempora vel et. Porro quia ratione fugiat ducimus placeat aut tenetur aspernatur. Et cum aliquam dolores voluptatem cupiditate magnam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(15, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'ajones@gmail.com', '', 'Est.', 'Quo ducimus totam voluptates a sint. Vel autem numquam tempore unde. Facilis similique vero rem consectetur ipsa neque enim. Quae qui earum dolor distinctio pariatur facere commodi sed. Autem consequatur repellendus odio. Voluptatem quia id sint voluptas excepturi. Aspernatur vitae nobis earum ipsum omnis. Aperiam magni ab consequatur corrupti impedit quos. Consequatur voluptas deserunt voluptatem sint consectetur voluptas. Nulla maxime ut aliquid sed.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(16, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'hokon@bergnaum.com', '', 'Est.', 'Cupiditate est voluptas ut tempore pariatur. Reprehenderit sit incidunt accusantium explicabo omnis distinctio magni molestiae. Omnis et veritatis sed dicta. Inventore laborum distinctio qui temporibus et ut quaerat. Nam et consequuntur recusandae ab numquam dolor. Omnis dolor at et. Et harum hic rerum. Praesentium eius officiis consequuntur distinctio quia molestiae aut suscipit. Incidunt minima enim labore eveniet error. Dolore mollitia quod dolores qui. Est est et ipsa sunt est ipsa.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(17, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'morris.pfannerstill@kling.org', '', 'Amet.', 'Ut iusto numquam illo et. Et excepturi provident atque quia at facilis. Pariatur et sit dolor suscipit amet maiores maiores. Omnis sunt possimus illum dicta ut iusto esse. Voluptatem culpa illum sint itaque. Natus non et corporis quos totam. Quae officia iste vitae expedita et. Ab odit vitae at laborum aut cumque qui qui. Nobis nemo aut laudantium hic dolorem tempore est.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(18, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'adaugherty@hotmail.com', '', 'Ab.', 'Pariatur asperiores ipsum doloribus. Autem quas ipsum voluptatem natus et doloribus non. Fuga consectetur consequatur natus omnis recusandae. Sit sed eveniet et. Numquam qui consectetur aut itaque aut. A possimus quia animi qui sed eum maxime ipsum. Eos doloribus animi aperiam aut sed optio sint enim. Ipsam recusandae unde quis consequatur. Impedit consequatur sed aliquam laborum ut qui rem odio. Blanditiis qui quae et nulla iste molestiae. Ducimus non placeat sit qui ipsum totam fuga.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(19, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'khodkiewicz@mohr.com', '', 'Nemo.', 'Quia facere commodi consequatur voluptates. Et dolores excepturi necessitatibus aliquam deleniti. Doloremque rem sed autem nesciunt aut ea consequuntur. Porro et quia ut et sit quia consequuntur. Sit error sapiente id dignissimos non libero eos vel. Exercitationem distinctio quaerat porro dolore. Dolor et error ut qui corporis. Nam sapiente sit rerum aut quos et provident.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(20, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'kovacek.camille@marquardt.com', '', 'Et.', 'Voluptas beatae voluptas consequatur consectetur quis rem. Itaque aut sint fuga est et velit nemo. Non nobis non tempore necessitatibus id minima reiciendis. Est tempore suscipit facere suscipit vel sunt recusandae. Aut possimus voluptatem omnis delectus voluptatem minima ad. Impedit ipsam doloribus rerum. Eum est sit qui in est eligendi. Illo aliquam laudantium aspernatur nam cumque voluptates. Saepe corporis dolor soluta sit et consequuntur.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(21, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'ahmad.blanda@hoeger.org', '', 'Sit.', 'Eveniet ratione repudiandae voluptas recusandae aspernatur vitae reprehenderit. Et ratione magnam ut quo ut qui. Veritatis numquam beatae iure rerum autem consequatur. Quia voluptatem quia alias corporis ex molestias. Dolore voluptate magnam delectus sapiente rerum enim velit. Doloribus delectus exercitationem nulla eum voluptas et. Debitis unde cupiditate dolorem mollitia asperiores. Voluptatum qui et qui est qui explicabo omnis.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(22, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'jzieme@gmail.com', '', 'At.', 'Distinctio nihil magni voluptatem quo ab nam. Adipisci odio et consectetur. Iste dolore aut odit quia alias perferendis laudantium. Sed architecto incidunt ut. Id impedit minima optio. Architecto ducimus repudiandae voluptatem est eum libero. Corrupti ut suscipit doloribus non totam. Aut quia et non. Architecto sit quam nesciunt tenetur eius rerum dignissimos. Aperiam ducimus sapiente quae eum minima sed dolor. Ut quasi in similique tempora.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(23, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'fadel.irma@hotmail.com', '', 'A.', 'Aut repudiandae quia ea rerum sapiente et. Rerum non minima quam veniam recusandae. Beatae laudantium et praesentium molestiae architecto blanditiis sed. Recusandae delectus consequatur voluptatibus soluta. Odio voluptatem deserunt mollitia cupiditate. Facere quia cumque qui fugiat molestiae corrupti quis. Sint officia aliquid aut ducimus molestias ducimus vero ut. At quidem tempora tenetur illo nesciunt. Dolores expedita omnis et tempora quisquam nihil.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(24, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'ncollins@deckow.com', '', 'Aut.', 'Nemo tenetur et aut impedit. Neque odio et sunt enim eos nemo blanditiis quia. Expedita eligendi ratione suscipit quis. Expedita non labore rerum. Reprehenderit laudantium praesentium magni amet consequatur. Debitis et ut deleniti facilis iure in fuga. Id adipisci fuga quia cumque expedita. Officia sapiente voluptas ut dolor expedita. Exercitationem dolor soluta eos aut vitae nemo. Sit et totam non nobis accusamus aut eos.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(25, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'joconner@dach.org', '', 'Nemo.', 'Expedita sed ea numquam tempora. Iusto qui omnis voluptas error ea sed omnis. Accusamus facilis neque dolores quo ex provident reprehenderit corporis. Sunt asperiores dolor magni magni et. Perferendis commodi at sint nobis. Facilis vel harum numquam sint totam omnis fugiat. Voluptatem incidunt corporis voluptatem impedit voluptate vitae corporis.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(26, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'carson.sipes@hotmail.com', '', 'Ut.', 'Et et nam consequuntur qui quia dolores reiciendis. Quia placeat et dicta aut est nesciunt laborum odit. Ut suscipit quia quia molestias aut fugit est. Libero consequatur similique voluptates labore. Iste et et aliquam aliquid. Dolorum est tempora facilis similique. Sed voluptas amet rerum sed alias sit ipsa. Praesentium ea nobis aut nisi. Ea doloremque aliquid quia dolores. Architecto nobis ex quas rem architecto et repellat. Consequatur nostrum ratione rem quaerat rerum atque ipsum.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(27, 3, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'emccullough@gmail.com', '', 'Qui.', 'Officia delectus neque facere ut repudiandae. Iste autem omnis sed odit et fuga voluptatem. Exercitationem sed praesentium non et ea dicta. Quidem libero autem sunt velit quam. Ut laboriosam consequatur ipsum. Inventore sequi consectetur et magnam et eum quia rerum. Facere officiis commodi unde quo aut dolores.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(28, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'rosenbaum.audra@gmail.com', '', 'In.', 'Ut quae possimus nobis. Porro velit accusantium quidem aut odio dicta sed. Accusamus deserunt et aut minima rem in. Molestiae voluptatem corporis ea expedita qui molestiae neque consequatur. Id earum neque sunt quas quam temporibus commodi. Enim ut voluptatem vel quibusdam cum et. Ea rem occaecati minus. Non nihil ut doloremque est omnis. Iusto tempora consequatur omnis sit in optio. Quia quo hic animi dolor maiores autem id.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(29, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'macejkovic.alicia@hotmail.com', '', 'Eum.', 'Deserunt reiciendis voluptates natus atque. Sed amet id libero. Quas eligendi ipsa enim possimus inventore. Quam culpa nesciunt eveniet accusamus. Autem consequatur atque optio ut et qui tenetur. Quis suscipit voluptatum autem blanditiis minus omnis rem id. Sunt ipsam suscipit dolorem porro. Ut et temporibus fuga autem repellendus.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(30, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'marlen07@gmail.com', '', 'At.', 'Ut minus autem iusto laboriosam voluptate deleniti cumque. Est voluptas nulla facilis nam ratione quaerat porro. Quia asperiores voluptatem quia sit amet aperiam totam. Laborum autem aut error minus minima. Quos ea qui blanditiis. Officia at dolor qui laboriosam qui. Ab autem nemo sit laboriosam. Est voluptatibus voluptate enim nostrum quisquam asperiores. Culpa eveniet qui ducimus laborum voluptatibus quasi.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(31, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'xharvey@gmail.com', '', 'Odit.', 'Explicabo officia assumenda quia autem doloremque voluptatum assumenda. Minus dolorum velit voluptatem ut. Deleniti molestiae vero quos velit debitis quibusdam dolor provident. Repellat dolor minima ut explicabo. Ut omnis assumenda quia. Sed inventore qui repellendus. Odio aut sunt similique dolor reiciendis officiis.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(32, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'ricky.bergstrom@breitenberg.com', '', 'Sit.', 'Ea est architecto aut illum amet. Beatae dolorem maxime ut error voluptatibus magnam veniam. Qui error dolor voluptas et velit et. Cumque voluptas earum quos quia occaecati rerum. Magnam alias ut necessitatibus et. Praesentium saepe harum ut quis. Quo maxime adipisci nostrum blanditiis sit et. Earum vel libero veniam ipsam omnis reiciendis. Vel est quod sunt dolorem quasi sequi. Sequi corrupti ratione aspernatur autem architecto sint. Repellat laboriosam veritatis illo quo laborum.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(33, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'florida.watsica@yahoo.com', '', 'Aut.', 'Est voluptatem eaque est aut saepe qui. Tempore adipisci consectetur explicabo quia aliquam tenetur. Exercitationem alias sint numquam. Doloribus dolores voluptas sit sunt numquam voluptatum consequatur. Est voluptatum itaque eaque deserunt repellendus impedit. Expedita cumque ut repellendus omnis incidunt recusandae ut. Ea debitis odit repellendus est aliquam excepturi.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(34, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'hkuvalis@gmail.com', '', 'Sit.', 'Sit natus corporis ut quo deserunt. Sapiente et dignissimos quidem. Minima officia ipsum numquam ut eaque soluta sunt. Sit rerum tenetur et provident voluptatem sit deserunt. Commodi qui quod eos mollitia ab. Voluptatem illo sunt enim. Veritatis et totam aut nobis. Nihil sunt sit nihil exercitationem impedit sint libero.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(35, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'kali.corwin@hotmail.com', '', 'Amet.', 'Aut quam id et quia. Non aliquid hic qui ipsum sed accusantium. Nihil qui aut ut nesciunt facere molestiae perspiciatis. Assumenda sit quidem fuga aut provident quidem eos. Reiciendis qui suscipit nesciunt id. Ratione harum aliquam in quibusdam quibusdam autem nisi et. Officiis autem sed quia libero nesciunt. Voluptatem culpa velit porro aut veniam nihil hic aut. Recusandae repellendus quia aut dolores asperiores. Omnis eos id blanditiis provident quod neque.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(36, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'amos.gaylord@hotmail.com', '', 'Et.', 'Error id eum facilis rem quo non repellendus voluptatem. Et iste eligendi qui ut. In maxime tenetur provident libero ut. Sit excepturi aut doloribus quibusdam laudantium porro. Quibusdam et ut quia dolor voluptate sit. Nam qui in qui labore minima explicabo repellendus. Aut quo autem ut. Perspiciatis aut officia sapiente similique aliquid quos qui. Molestiae ratione eum est et qui autem delectus. Nobis voluptatum quibusdam est dicta ut eum fugiat.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(37, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'brenda.paucek@gmail.com', '', 'Ut.', 'Eius optio dignissimos harum reiciendis nihil nulla. Autem reiciendis voluptas earum harum voluptas possimus dolore omnis. Dolor est molestiae consequatur. Blanditiis quis omnis animi ex. Corrupti qui aut ratione perferendis. Reprehenderit nihil dolor vero eveniet. Voluptates velit sint mollitia ipsa occaecati in mollitia quaerat. Et possimus at impedit quia. Et ut iusto nam recusandae.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(38, 3, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'maymie.towne@block.net', '', 'Eos.', 'Ut quia commodi aut est voluptas. Laudantium ipsa omnis dolor qui consequatur et. Soluta porro laborum et ut ut. Ipsa officia sit laborum. Aut sint repellendus et veniam qui vel minima. Quasi laboriosam officia esse eligendi maiores magnam nihil. Eveniet voluptate unde suscipit et et. Autem voluptate praesentium voluptatem a. Doloribus voluptatem pariatur quos omnis officia repudiandae minus. Mollitia qui quia quo quia.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(39, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'hudson.carole@yahoo.com', '', 'Ea.', 'Harum ea numquam libero facere. Non libero laborum maxime ratione officiis ut enim. At qui enim cumque voluptatibus ut alias. Sunt occaecati aut numquam corrupti accusamus soluta libero. Cum porro quia voluptatem aut est impedit. Et veniam quibusdam et eum quos et. Est quam unde aliquid et. Debitis quaerat vel non quo quam. Architecto enim dolorem et vero. Natus eligendi quidem hic.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(40, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'dorn@wolf.com', '', 'Ea.', 'Quae aut nulla asperiores est aut enim eum. Ea quis voluptates animi saepe quo possimus nam quia. Commodi quidem vel alias officiis at. Laborum quo laudantium et dolor illum sit et et. Cupiditate et veniam magnam quo ea. Iste aut ut nisi totam aut illo id. Sint ducimus temporibus quae mollitia natus minima nam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(41, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'murray.beier@schimmel.net', '', 'Eum.', 'Laboriosam dolores repellendus et tempore. Est commodi omnis dicta. Neque assumenda libero doloribus omnis. Quisquam sed possimus aliquid enim dolor libero. Quas libero illo eligendi aut. Dolor quia illo deleniti adipisci voluptas nostrum voluptatem. Perferendis veritatis blanditiis omnis dolorum sequi. Neque ad neque fugit. Sed qui eaque earum quaerat qui placeat quia. Sit sapiente sed autem perferendis dicta voluptatem cupiditate.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(42, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'nlind@stamm.net', '', 'Quis.', 'Est aut accusantium molestias placeat. Ut et occaecati beatae et. Eveniet et iste quod blanditiis quis quam quisquam maxime. Et velit laboriosam quia ducimus distinctio rerum. Et illo sunt modi est autem. Iusto repudiandae odit omnis eos laboriosam cupiditate ut nam. Et quae aut tempora iure. Voluptatem velit id aut qui voluptates eum quibusdam. Iure quo sunt vel dolor. Vel labore fugit architecto velit alias quo libero. Quia quo cupiditate eum qui sit dolore unde.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(43, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'tillman.mandy@hotmail.com', '', 'Eius.', 'Sit placeat totam odit et. Tempora et dolores reprehenderit fuga. Maiores enim consequatur nostrum nostrum sunt excepturi. Voluptatum quam rerum corporis libero in alias. Corrupti ut porro aliquid est neque est non ea. Saepe unde qui natus ratione aut repellat. Veniam voluptatem debitis fugiat illo enim rerum delectus quaerat. Maiores id sapiente harum distinctio pariatur.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(44, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'gwen64@herzog.biz', '', 'Eos.', 'Labore expedita eaque qui ea dignissimos. Mollitia sed quia quis nisi sed consectetur. Labore at velit omnis alias ea. Eveniet enim praesentium delectus voluptas voluptate blanditiis aut. Dolores et ea officia qui ut rem earum. Earum et quo cum nostrum. Tempora facere aliquid animi voluptatem explicabo hic. Sit odit ut et perspiciatis debitis et sapiente quisquam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(45, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'howe.sebastian@gmail.com', '', 'Est.', 'Animi quisquam ullam laboriosam dolorem libero quam. Repellat itaque explicabo voluptatum minima. Earum et iure suscipit aut soluta quisquam voluptatum. Ut cupiditate commodi sed dolor. Iste delectus inventore cupiditate inventore eligendi in officia. Deserunt sint ducimus et sunt magni. Laboriosam sit vero non odit nam. Debitis ea dicta praesentium cum. Magnam itaque accusamus incidunt. Minus eum beatae nobis ea magnam. Dolorum quas accusantium beatae debitis quia impedit quam magni.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(46, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'maryam39@gmail.com', '', 'Illo.', 'Magnam at dolorem quam reiciendis quos libero. Consequatur sit exercitationem officia dolores nesciunt. Non perspiciatis pariatur pariatur vero repellendus amet consequatur sit. Eveniet cupiditate omnis incidunt dolor. Temporibus esse adipisci alias vel molestiae nihil sapiente. Et labore officiis odio id culpa iste sint consequatur. Eius a dolorem maiores deleniti beatae. Velit non quia ex qui. Voluptates reiciendis nihil vitae. Itaque iusto sint saepe.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(47, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'emie.ondricka@rohan.info', '', 'Ipsa.', 'Aut deserunt sit maxime mollitia facilis. Magni nostrum vitae vero unde sequi itaque eos beatae. Molestiae dolorum quis accusantium alias quas voluptates. Quis fuga vel eius possimus ea velit. Accusantium accusantium accusamus dolore debitis nesciunt. Eius unde quos dolor dignissimos alias odio possimus.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(48, 5, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'hermiston.tressie@gmail.com', '', 'Sed.', 'Nam ipsa voluptatum repellat laboriosam nesciunt. Dolor quis facere quaerat. Cumque qui optio placeat unde. Exercitationem enim qui id. Hic cupiditate consectetur esse quod. Explicabo expedita et beatae accusantium facere totam eum. Libero saepe nesciunt tempora quas. Maiores aliquid at qui libero corporis et rerum. Aut unde est fugiat omnis fugiat error itaque. Sint qui voluptatem laudantium. Ipsam reiciendis rerum quibusdam quod.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(49, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'geovanni15@robel.com', '', 'Sint.', 'Ut nihil consequatur ab voluptatem nesciunt. Tenetur placeat nemo sint et sapiente et. Fugit sit velit eos nulla. Autem totam quidem aspernatur molestiae dolores unde. Ea beatae aut autem molestiae illo sunt. Et minus in eligendi rerum enim maiores. Quo id vel earum nihil. Et neque aliquam exercitationem aliquid.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(50, 3, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'ruecker.lempi@yahoo.com', '', 'Modi.', 'Atque ipsa officia sapiente odit hic officiis recusandae. Dicta qui dolor quam sit consequuntur suscipit. Quia aut vitae nam consequatur maxime consectetur. Expedita deleniti dolorem accusantium voluptatibus aut enim officiis. Facilis est ex aut perspiciatis maiores. Deleniti distinctio consequatur pariatur quos illum corrupti sint. Commodi aut aperiam voluptas similique.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(51, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'reinger.josefina@murazik.org', '', 'Quo.', 'Error itaque veritatis doloremque aut. Consequuntur ducimus voluptatem ullam culpa delectus. Sapiente laudantium at dolores ratione nostrum quasi corporis. Et illo corporis fugit ut omnis qui perspiciatis. Nostrum beatae maxime quibusdam et repudiandae. Adipisci maxime qui repellendus velit culpa quo. Id laboriosam impedit nihil explicabo id velit dolor.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(52, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'morissette.myah@bode.org', '', 'Iste.', 'Sed iusto sint dolor reiciendis quo quasi. Minus animi praesentium nulla est est nulla fuga. Ex odio quo eligendi non optio error vitae. Possimus quidem qui est sequi error quasi. Consectetur enim vel eos vel officiis aut sit. Quas repellendus veniam commodi ea. Ullam possimus fuga veniam earum quasi voluptate. Quibusdam accusantium molestiae illo occaecati consequatur sed earum. Et aut adipisci quo odio nulla odio. Qui placeat voluptatem magni in autem sequi.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(53, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'kris.pfannerstill@hotmail.com', '', 'Ea a.', 'Autem omnis aut tempore molestiae aut. Dolores necessitatibus reprehenderit autem molestias nihil ut. Earum laboriosam consectetur et autem. Eos magni rerum laborum officiis. Placeat possimus architecto quisquam tempore asperiores consectetur. Aspernatur et blanditiis mollitia excepturi. Cupiditate aut velit quia et dolor fuga sapiente. Aut dolor qui et voluptas.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(54, 1, 'App\\Models\\Inquiry', 2, 'no-replay@mpu.edu.mo', 'kade.luettgen@hotmail.com', '', 'Et.', 'Excepturi at quia corrupti facere nobis. Accusamus recusandae et molestiae fugit ipsa. Amet fugiat delectus dignissimos consequatur veniam accusamus. Sunt voluptatem facilis cum nisi. Sit velit consequatur impedit velit provident et voluptatem. Aliquid quae quasi expedita consequatur debitis perferendis non. Sunt laboriosam ut incidunt sed rerum dicta ullam nisi.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(55, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'skye00@beatty.com', '', 'Ex.', 'Nihil iure dignissimos repudiandae vel. Aperiam possimus deleniti iure aut porro eveniet odit. Ut dolor rerum repellendus quo eveniet. Ut laboriosam ea sed dicta maiores blanditiis. Et aut a aut quisquam sed minima cumque. Sed voluptatem sunt repellendus. Rerum dolor repellendus earum nostrum quia et libero quidem. Et id praesentium voluptate velit ut et nisi.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(56, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'pritchie@conn.com', '', 'Ut.', 'Iste aliquam in odio enim similique dolores. Fugit totam corrupti dolorum tempora deleniti. Adipisci aliquam saepe laudantium et sunt. Non alias enim omnis. Magnam consequatur a quia voluptatum veniam voluptatem. Laudantium minus possimus voluptatem odit consequuntur et. Ut dolor ab blanditiis numquam iure quis.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(57, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'clark40@yahoo.com', '', 'Eius.', 'Tempora voluptatibus aut repudiandae et sed optio ut. Magni sint qui alias repudiandae aut. Ab distinctio ut eos veritatis similique aut perferendis. Aliquam officia eligendi sed ad nam. Iusto recusandae esse sunt ratione modi maiores repellendus. Rerum est quos repudiandae quod odio eveniet. Et dicta rem est ut molestiae. Similique repudiandae autem itaque omnis. Non alias nihil veniam id. Cum sit vitae pariatur totam. Eligendi ad beatae cum ut aut deleniti.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(58, 5, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'roberto78@swaniawski.com', '', 'Cum.', 'Nam aut minus quasi earum non qui. Officiis rerum qui tenetur qui modi velit reprehenderit. Ea debitis autem iusto voluptate ut quia itaque. Sit odit ipsum omnis laudantium blanditiis. Ad delectus non voluptatem dolor corrupti quasi. Eligendi quidem mollitia dolor deleniti ullam iure. Qui voluptatem fugiat facere officiis. Molestias quia dolores ullam deleniti. Tenetur sequi et sed a rerum dolorem et. Eaque voluptatem autem minus alias delectus.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(59, 5, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'reina.fahey@hirthe.org', '', 'Eum.', 'Nisi quaerat reiciendis aut ipsum facere sunt. Consequatur consequatur saepe officia vitae eligendi in non vel. Eveniet explicabo fugit nemo ipsa. Dolores voluptas quisquam alias qui. Ut ut incidunt quam perspiciatis eos harum. Natus id sit sed corporis vero. Dignissimos rerum nemo tempore et dolorem. Doloremque eligendi totam quos facere cumque.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(60, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'gnolan@ankunding.com', '', 'Et.', 'Deserunt minima libero dolorum. Cupiditate est quia est ullam aliquam sunt sunt. Deserunt officia earum dolorum. Qui facilis rerum consequatur sit laborum. Consequuntur ea similique illum officiis culpa sed minus. Esse expedita deleniti accusantium esse voluptatem. Aut aut eaque sunt quidem eligendi. Earum nam totam enim eligendi sint dolorem.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(61, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'amiya.barton@hotmail.com', '', 'Modi.', 'Voluptatem consequatur est iusto dolore voluptas deserunt. Blanditiis aspernatur ut eius quis qui in. Omnis ullam ea explicabo qui. Qui aspernatur nobis molestiae harum quod vel nobis. Quia in non numquam. Sed ut qui assumenda beatae distinctio nam maiores. Perspiciatis veniam animi quam nobis repellat. Ipsa molestiae quas voluptatum maiores aut quae aut. Voluptatem deserunt ut ex sint.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(62, 5, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'madie.hoeger@sauer.info', '', 'Ut.', 'Totam modi accusamus praesentium minima modi quisquam ea. Asperiores distinctio dolorem nobis ut ea. Quidem sit rerum omnis ducimus tempore. Quas dolorem nostrum id blanditiis asperiores atque. Perspiciatis id tempora magnam sequi. Delectus porro tempora qui qui voluptatem omnis. Et voluptatibus atque non. Atque facilis ut optio et dignissimos. Quia sunt eos est quo corporis iure non modi. At rerum quo pariatur aut adipisci quia laudantium.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(63, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'ubernhard@gottlieb.com', '', 'Sed.', 'Blanditiis sunt beatae a quos iusto. Est ea distinctio quo laboriosam sunt. Pariatur molestiae laudantium assumenda facere iste et non. Et id consequatur nihil atque rerum. Dolores odit rerum aut perspiciatis expedita dicta saepe. Consequuntur quia magni magnam exercitationem voluptatem. Vero perferendis cumque quam reprehenderit culpa. Quidem sint ut pariatur eos vitae in in. Voluptatem non rerum officiis officiis dolor in et. Et sit qui sit.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(64, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'troy.rutherford@hotmail.com', '', 'Et.', 'Voluptate accusamus facilis eligendi mollitia. Aut facere libero facere et. Qui enim quia et eaque eaque et iusto. Voluptatibus et dolorum ducimus earum. Est qui quia dolores voluptas. Excepturi doloribus laudantium nostrum accusamus. Eum sed et et quaerat a facilis dolorum iste. A quibusdam molestiae nobis sunt consequatur iusto.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(65, 3, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'dabernathy@yahoo.com', '', 'Sunt.', 'Dolores quia illum explicabo illum accusantium ducimus. Neque eaque nihil provident veritatis. Error ducimus qui assumenda earum atque harum. Quasi aut tenetur nisi harum incidunt ipsam. Qui ea autem ex qui recusandae suscipit. Omnis accusantium voluptatem fugiat distinctio. Laborum aliquam aut occaecati nesciunt sed et. Sint aut illum non excepturi sunt provident. Recusandae atque tenetur laudantium consequuntur sit repellat. Voluptatem tempore et facilis libero dignissimos minus beatae.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(66, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'tromp.reinhold@skiles.biz', '', 'Qui.', 'Pariatur qui quod placeat itaque vel molestiae omnis. Non sunt dolor harum facere consequatur ullam numquam. Sunt ut libero facilis deleniti aut veritatis. Non perspiciatis numquam quis sapiente eum illo cum. Et voluptas ut velit quisquam nostrum. Perferendis illo voluptas optio et. Libero voluptatem quia non molestiae dolor amet. Illo voluptatibus quia sit suscipit rerum. Voluptatum facere praesentium nihil. Iusto provident sapiente ex.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(67, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'hettinger.koby@gmail.com', '', 'Odit.', 'Molestias assumenda dolorem maiores repellendus et debitis. Enim fuga aut perferendis assumenda dolorem placeat sint. Et dicta in sed laboriosam reiciendis animi. Molestiae pariatur facere quia modi ut nihil nihil. Temporibus voluptas esse et iste. Ea porro sint dolorem officiis eum amet. Soluta est rem deserunt at consequatur et unde. Eius et in vitae ipsam est quo. Reprehenderit et quam necessitatibus modi et pariatur.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(68, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'weissnat.quinton@emmerich.com', '', 'At.', 'Explicabo est consectetur nemo. Corporis iusto voluptas officia expedita ut nihil illo quo. Sed et est deserunt est aperiam eveniet laboriosam rem. Non dolorem et laudantium suscipit animi. Occaecati eaque at reiciendis quis. Sit dicta quia nostrum molestiae. Occaecati vero quis officia est natus voluptas omnis. Ut officiis et numquam provident qui ea sequi.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(69, 1, 'App\\Models\\Inquiry', 2, 'no-replay@mpu.edu.mo', 'deron.bahringer@glover.biz', '', 'Aut.', 'Consequatur sed et quae ut voluptatem est. Totam dignissimos illo quia quia. Saepe pariatur animi est nostrum sed. Soluta sequi animi maiores magnam. Aut sequi minima nesciunt ipsa asperiores. Laudantium iste est molestias architecto. Illo sequi veniam harum dolores. Occaecati praesentium repellat saepe doloribus sapiente. Assumenda laudantium architecto modi non. Voluptate aut similique optio eveniet commodi. Omnis esse quidem et eligendi enim saepe.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(70, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'chelsey.weimann@bogan.com', '', 'Et.', 'Qui tempora voluptatibus soluta qui corporis tempora delectus. Mollitia quis pariatur deserunt tempora voluptatem id. Animi neque iste voluptas inventore in iste aperiam. Accusantium rerum blanditiis vel. Cupiditate nihil ratione esse aut. Illum laudantium laudantium est qui est. Quae aut suscipit esse sunt. Officiis rerum eaque quos quis vero mollitia ipsam sit. Voluptate quidem dolorem libero aperiam. Ipsam amet ab ea magni sit. Iste eos quam blanditiis cupiditate quaerat.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(71, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'narciso17@gmail.com', '', 'Est.', 'Suscipit vero laboriosam natus et consectetur nesciunt. Exercitationem voluptatum nobis nesciunt earum quisquam ipsa. Est omnis quis molestias incidunt. Quia et sed consequatur officiis. Maiores nostrum vero voluptas mollitia nam. Omnis delectus voluptatem dolorem nesciunt. Suscipit occaecati accusantium possimus aperiam assumenda. Quod optio omnis aliquam molestiae in quisquam et. Nisi maxime ipsa optio aut illum.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(72, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'rziemann@yahoo.com', '', 'In.', 'Autem pariatur ad quidem illo. Praesentium nihil voluptatem ea quos quam corrupti maxime. Voluptatem vel dolor ut ducimus eos. Sit nemo nihil nihil eum impedit vitae accusantium. Facere architecto veniam libero dolore. Autem et ipsam odio fugit necessitatibus ut eligendi. Non nulla et et nemo aut hic. Corporis repudiandae quibusdam et rerum deserunt laboriosam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(73, 5, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'deondre.streich@yahoo.com', '', 'Rem.', 'Earum et enim debitis tempore. Consectetur alias dolore rem unde quae autem. Quo deserunt debitis sit labore incidunt distinctio. Voluptatem numquam vel saepe vero. Quae sit sed expedita aut ullam tenetur consectetur. Atque eos reiciendis a inventore. Nihil molestiae qui cum. Est aut et officiis soluta magni sit voluptas. Quia nemo cum sequi deleniti accusamus dolores et. Vitae minima harum laboriosam voluptas. Libero et et minus vero magni modi officiis aut. Magni suscipit in quis provident.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(74, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'ebert.anjali@hotmail.com', '', 'Quia.', 'Consequatur quae dignissimos ut molestiae laborum quis. Sit consequatur veritatis dolore quos. Quod iste quia exercitationem sit laboriosam officiis officia. Blanditiis repudiandae soluta earum quos unde. Quae impedit itaque expedita placeat et autem. Dolore ea vel dolores cupiditate dolore. Possimus dolorem et eum vero modi saepe rem et. Officia laborum nobis nesciunt deleniti. In dolorum dolor eum laboriosam et. Doloremque impedit harum unde est sint dignissimos sit.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(75, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'browe@hotmail.com', '', 'Nisi.', 'Ratione voluptas assumenda consequatur. Aliquid officia ratione ad maiores dolores velit laboriosam. Dolores ea possimus voluptate ab voluptatem. Reprehenderit ratione omnis veritatis perspiciatis molestias. In est esse a sit et. Molestias necessitatibus excepturi ex adipisci delectus. Ea ea magni ut est. Qui et maiores quia enim. Temporibus amet labore aut ipsum voluptas est sit nesciunt. Maiores sunt eligendi soluta modi qui.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(76, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'daniel.aniyah@bins.org', '', 'Quos.', 'Repudiandae voluptatem ab fugit ut dolorem. Consequuntur quia nam accusamus eius molestiae quia tempore. Consequatur soluta id molestiae. Ullam aut laudantium quaerat magnam cumque beatae incidunt. Excepturi nihil id et. Sint vitae qui voluptates repellendus. Quas ratione modi quis et autem optio. Rerum dolor ducimus quia nostrum sint natus id. Qui debitis quasi repellendus facilis possimus tempora omnis. Rerum est facere quia suscipit quisquam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(77, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'qprohaska@yahoo.com', '', 'Sit.', 'Id dolores inventore facilis odit qui quas. Reprehenderit natus ipsum aut commodi iure nam est. Laboriosam fugit qui tenetur. Omnis laboriosam porro reprehenderit voluptas ea. Sit id vitae iste quam soluta consequatur ullam. Sit quia quasi aut iusto.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(78, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'laisha04@hotmail.com', '', 'Sit.', 'Dolorem voluptates fuga qui architecto. Aut vitae repudiandae et. Nostrum minus nisi ducimus ea numquam ea. Et occaecati nam excepturi voluptate qui sit deleniti. Quia nisi eaque id non dolorem nobis architecto. Cumque quidem expedita numquam quis enim sed aut. Illum laudantium sapiente ut tempore iure quia nihil. Dolores dignissimos nobis distinctio fugit impedit. Saepe qui eaque eligendi vel perspiciatis rerum voluptatem. Ad fugiat numquam aliquam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(79, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'beryl.champlin@yahoo.com', '', 'Odit.', 'Aut eligendi et ea a natus cupiditate ut. Ut sint porro quo dolores aut voluptatem. Quisquam consequuntur corporis delectus quia animi eius ut. Mollitia sint et ut nemo. Sit dignissimos dignissimos a. Aperiam dolores nemo debitis nemo similique. Asperiores temporibus veritatis laborum odio deleniti necessitatibus temporibus mollitia.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(80, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'rspencer@kertzmann.info', '', 'Et.', 'Rem soluta ut aut. Illum deleniti ab nulla nobis tempora totam dolores. Deserunt qui aut voluptas dolor consectetur molestiae. Officia nobis voluptatem vel tenetur. Facere expedita nobis earum fugiat saepe. Ut maiores assumenda necessitatibus at natus esse. Et aliquam hic dolor fuga a et recusandae. Quo voluptatibus eos repellendus ab. Et cumque sunt explicabo et. Dolorum quis voluptatem dignissimos aspernatur voluptas.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(81, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'zschulist@gmail.com', '', 'Est.', 'Incidunt laborum ut enim odit quam. Cumque rerum quo explicabo nobis. Odio et illum harum laudantium sunt ipsum ut ex. Qui enim tenetur aut enim sed vel distinctio fugit. Reprehenderit doloribus soluta rerum. Aliquam blanditiis est autem reiciendis est recusandae ut. Saepe ab dolor libero optio nulla. Illo voluptas aut voluptatum eos. Illum iure itaque et ipsa. Et vitae ipsam est est. Fugit ad voluptate quidem et. Et nisi occaecati voluptatibus molestias omnis temporibus voluptatem.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(82, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'darren57@hotmail.com', '', 'Unde.', 'Et aut itaque consequatur ducimus delectus. In et corrupti placeat. Dolor consequatur consequatur repudiandae a laudantium consequatur totam ut. Commodi et fugit ut accusamus sint cum. Exercitationem voluptatem recusandae ipsam aut. Et corrupti nihil natus consequatur aliquid. Saepe in provident nihil quo ea nihil suscipit. Similique atque et pariatur eos praesentium aut.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(83, 10, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'april.halvorson@wunsch.org', '', 'Et.', 'Voluptates blanditiis ullam aperiam sit qui voluptatem. Et alias recusandae ullam maxime ut sed. Repellat doloribus qui omnis. Accusamus est ea voluptatem quia dignissimos minus vitae. Omnis qui enim laudantium qui numquam tempore hic sunt. Ut possimus consequatur excepturi reiciendis molestias suscipit aut. Commodi iste unde ut nesciunt alias ut qui.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(84, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'eliane.bailey@hermann.com', '', 'Ea.', 'Ipsum quos accusantium nihil totam eos laboriosam. Dolor inventore expedita vel voluptatem fugiat rerum. Aperiam labore est illo nihil minus magni dolores. Ipsam aut saepe hic quam et. Quibusdam consequatur esse quaerat ea labore sapiente sed. Eligendi sint autem magni qui sed distinctio. Ipsa dolor molestiae qui non cumque numquam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(85, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'juliet23@gmail.com', '', 'Qui.', 'Quos assumenda facilis molestiae velit placeat et itaque quidem. Ut eius voluptas cumque officiis et necessitatibus illo. Vel incidunt eos perspiciatis et. Suscipit non vero quam nobis provident voluptatibus. Consequatur non dignissimos expedita optio nemo omnis asperiores. Quos similique eligendi velit doloribus.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(86, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'arianna.bergstrom@hotmail.com', '', 'Id.', 'Aliquid dignissimos omnis fugiat voluptatibus. Iusto quia quae placeat ratione eos eum. Temporibus consequatur dolor labore cum perspiciatis sequi quidem. Vel perferendis officiis illo nostrum impedit natus praesentium eum. Architecto nesciunt ut est aliquid ut explicabo itaque laborum. Dolorum et in rerum similique maxime.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(87, 4, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'johnathan.dach@hotmail.com', '', 'Est.', 'Qui corrupti occaecati perspiciatis libero laboriosam reiciendis. Nihil consequatur in saepe minima est tenetur consequuntur voluptas. At minus delectus facere quod. Qui aut quae ipsa cumque est consequuntur molestias. Rerum fuga necessitatibus est vitae. Et ducimus magni et eveniet et autem dolor. Nostrum nostrum accusantium tenetur ut. Voluptatibus deserunt quo rem sint et aperiam est est. Quia aliquam exercitationem modi earum.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(88, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'graham.raoul@gmail.com', '', 'Quia.', 'Voluptates autem est at. Numquam nisi expedita commodi suscipit. Laudantium dolores earum totam vel voluptas et non. Ea velit quia voluptatem praesentium ut possimus. At animi est quas quisquam soluta repudiandae molestiae. Et ipsa nihil autem ut saepe exercitationem delectus. Harum est dolores qui fugit non error libero. Nemo quae quam sint eveniet iure accusantium. Est aut laborum culpa incidunt. Recusandae odit unde adipisci ut magnam qui et.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(89, 9, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'lueilwitz.kelvin@terry.com', '', 'Ut.', 'Aut velit voluptatum est et sed qui odit. Veritatis ea sequi et qui modi mollitia qui. Quia consectetur aut quas velit voluptatibus quo. Voluptas nobis aut odit repellendus doloremque itaque temporibus iusto. Dignissimos magni distinctio qui. Ea aut est explicabo. Quia rerum ad aperiam odit illum modi rem. Animi aliquid perferendis neque quas qui fugiat fuga. Iure repellat minima et in nulla sed consequatur non.', '2023-05-09 18:23:56', '2023-05-09 18:23:56');
INSERT INTO `emails` (`id`, `emailable_id`, `emailable_type`, `admin_user_id`, `sender`, `receiver`, `cc`, `subject`, `content`, `created_at`, `updated_at`) VALUES
(90, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'jamie53@gmail.com', '', 'Quis.', 'Animi beatae non voluptas dolores. Dolor exercitationem ducimus aut eos accusamus dicta. Repellat molestiae molestiae rerum voluptas maxime at. Commodi reprehenderit omnis minus fugiat explicabo molestias assumenda. Eum quaerat ipsa voluptas eaque quia cupiditate. Earum eligendi beatae aperiam perferendis consequatur numquam. Ut dolore est impedit vitae voluptatem ipsam. Labore inventore quidem ipsa officiis earum. Quas delectus alias dolorem est. Error alias illum at sit corrupti.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(91, 7, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'morar.orlando@cremin.com', '', 'Nemo.', 'Eveniet veniam aut quam. Ut commodi quisquam distinctio architecto officia eos. Temporibus officia veniam vero saepe quam reprehenderit. Nulla eius tempora doloremque accusantium sed omnis sit. Eos ullam perferendis voluptatem explicabo totam ut. Fugiat et molestias voluptas impedit fugiat ut ipsa. Alias natus corporis provident atque id qui commodi. Aspernatur aut dolorum dicta neque nihil beatae. Nam libero enim officia. Voluptas quidem nihil natus.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(92, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'schulist.alec@yahoo.com', '', 'Sint.', 'Expedita qui aliquam commodi ut qui est. Hic reiciendis vel et tempora perspiciatis explicabo. Ut sint quod rerum cumque. Assumenda eos est minima molestias dolores consectetur. In nihil blanditiis enim est possimus. Unde deserunt exercitationem atque odit odio. Asperiores porro adipisci cupiditate. Nostrum quia voluptas dolorem ab ea impedit debitis. Deleniti quidem consequatur labore sapiente fugiat et exercitationem.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(93, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'reichert.jovani@lueilwitz.com', '', 'Illo.', 'Enim labore eos adipisci in. Asperiores qui sapiente accusantium laudantium. Aliquid eaque accusamus qui. Delectus assumenda dolore velit eaque nostrum excepturi. Amet et error velit id cumque rerum. Odio dolor laborum eum odit quas quo modi. Recusandae accusamus culpa et corporis et. Ut est nihil consequatur distinctio aspernatur accusantium dolor. Rerum quod et exercitationem eos dolorem. Iure dolor quod sed.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(94, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'clementine.nicolas@gmail.com', '', 'Non.', 'Consequuntur earum officiis totam quisquam natus. Exercitationem et cum non esse in aliquid. Perspiciatis dignissimos aut cumque. Qui fugiat omnis non distinctio. Aliquam consequuntur ipsam voluptatem culpa autem consequuntur. Illo et quae aut. Neque saepe quos consectetur dolores. Doloremque et velit minus libero asperiores voluptatum natus.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(95, 2, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'martine.herman@hotmail.com', '', 'In.', 'Dolorem autem doloribus at blanditiis ab dolor. Esse quidem aspernatur est at et et. Dolorem accusamus et sit. Velit adipisci dolorum earum. Ut molestiae voluptas ut commodi officiis ipsa magni. Itaque aspernatur sit quo iusto. Provident voluptate quos placeat omnis eum nihil delectus ut. Et mollitia at inventore tempora voluptatibus rerum et adipisci. Autem vitae et labore voluptatem fugiat. Perferendis ducimus a id tempore. Qui commodi numquam laborum ipsam fugiat eos. Fugit et quae quo.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(96, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'dante.crist@gmail.com', '', 'Est.', 'Est deleniti voluptas praesentium aliquid labore. Sed quam enim consectetur quas libero. Sapiente autem sint quisquam hic est quia. In blanditiis dicta dolores distinctio sunt sit a voluptatibus. Placeat dolorem ut itaque molestiae. Nihil at nam in id. Voluptas perspiciatis quos qui adipisci qui voluptatem voluptas mollitia. Minima perspiciatis eius dolorem aut. Aut omnis repellendus esse dolor perferendis dolorum quia. Eum quae ut sed sed veritatis. Officiis ut id laborum sed sunt aperiam.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(97, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'alana.larson@gmail.com', '', 'Aut.', 'Commodi error modi velit quae aliquam earum. Perspiciatis autem ut commodi aliquid dolorum consequatur quaerat quas. Porro et qui quisquam rerum. Cupiditate quam et deleniti ipsa. Numquam dolores consequatur qui placeat numquam nam. Ratione numquam assumenda suscipit. Non necessitatibus voluptas ut omnis ut. Et vel inventore quaerat iste. Adipisci et quas id voluptatibus. Nihil quidem alias sit totam. Et aut non commodi error ab.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(98, 3, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'sabina.goldner@hotmail.com', '', 'Id.', 'Nihil distinctio provident odit vitae. Culpa minima officiis itaque dolorem. Labore earum corporis et. Quod suscipit explicabo voluptatibus rerum eos fuga quaerat. Maxime recusandae deserunt quis sint ad provident modi. Temporibus sed itaque fugit quaerat possimus. Nihil harum sed voluptas. Odit repellat libero nihil enim aperiam ad.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(99, 8, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'ted.leannon@larson.com', '', 'Quas.', 'Perferendis mollitia id consequatur molestiae aliquam porro magni animi. Officiis et debitis est ut. Necessitatibus at voluptate vero officiis. Iusto repellat exercitationem illum quis. Blanditiis pariatur perferendis enim quis. Sit aut voluptatem sapiente velit voluptatem molestiae. Qui sequi et in repellat. Earum harum molestias enim et corrupti aut earum. Possimus id molestiae ipsam dolorum. Quo culpa ratione atque sunt.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(100, 6, 'App\\Models\\Inquiry', 1, 'no-replay@mpu.edu.mo', 'caden.eichmann@feil.com', '', 'Aut.', 'Voluptatem quis a veritatis ut. Facere eum voluptas et. Similique voluptatem ut eos ipsum praesentium id quae. Tenetur at aut ipsa voluptatem mollitia minima facere eveniet. Mollitia vero quia facere sunt sed. Eligendi dolorem occaecati quo porro recusandae voluptas. Incidunt consequatur alias hic placeat in perferendis. Molestias vero illum adipisci officiis dignissimos et. Quos aut fugiat aliquid exercitationem quaerat ut sint.', '2023-05-09 18:23:56', '2023-05-09 18:23:56'),
(110, NULL, NULL, 2, 'a', 'a', '', 'a', '<p>aa</p>', '2023-05-10 01:32:45', '2023-05-10 01:32:45'),
(111, NULL, NULL, 2, 'a', 'a', '', 'a', '<p>aa</p>', '2023-05-10 01:42:17', '2023-05-10 01:42:17'),
(112, NULL, NULL, 2, 'bb', 'bb', '', 'bb', '<p>bb</p>', '2023-05-10 01:45:56', '2023-05-10 01:45:56'),
(113, NULL, NULL, 2, 'bb', 'bb', '', 'bb', '<p>bb</p>', '2023-05-10 01:51:16', '2023-05-10 01:51:16'),
(114, NULL, NULL, 2, 'bb', 'bb', '', 'bb', '<p>bb</p>', '2023-05-10 01:52:09', '2023-05-10 01:52:09'),
(115, NULL, NULL, 2, 'bb', 'bb', '', 'bb', '<p>bb</p>', '2023-05-10 01:52:25', '2023-05-10 01:52:25'),
(116, NULL, NULL, 2, 'aa', 'bb', '', 'dd', '<p>ee</p>', '2023-05-10 01:53:25', '2023-05-10 01:53:25'),
(117, NULL, NULL, 2, 'aa', 'bb', '', 'dd', '<p>ee</p>', '2023-05-10 01:54:11', '2023-05-10 01:54:11'),
(118, NULL, NULL, 2, 'aa', 'ss', '', 'ff', '<p>kljlk</p>', '2023-05-10 01:56:25', '2023-05-10 01:56:25'),
(119, NULL, NULL, 2, 'aa', 'ss', '', 'ff', '<p>kljlk</p>', '2023-05-10 01:57:35', '2023-05-10 01:57:35'),
(120, NULL, NULL, 2, 'aa', 'ss', '', 'ff', '<p>kljlk</p>', '2023-05-10 01:58:30', '2023-05-10 01:58:30'),
(121, NULL, NULL, 2, 'a', 'a', '', 'a', '<p>c</p>', '2023-05-10 19:55:18', '2023-05-10 19:55:18'),
(122, NULL, NULL, 2, 'a', 'a', '', 'a', '<p>c</p>', '2023-05-10 19:55:57', '2023-05-10 19:55:57'),
(123, 1, 'App\\Models\\Inquiry', 2, 'a', 'a', '', 'a', '<p>aaa</p>', '2023-05-10 20:00:15', '2023-05-10 20:00:15'),
(124, NULL, NULL, 2, 'sdf', 'ds', NULL, 'asdf', '<p>4545</p>', '2023-05-10 20:16:40', '2023-05-10 20:16:40'),
(125, NULL, NULL, 2, 'sdf', 'ds', NULL, 'asdf', '<p>4545</p>', '2023-05-10 20:17:29', '2023-05-10 20:17:29'),
(126, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'subject 1111', '<p>contentsssss</p>', '2023-05-10 20:45:11', '2023-05-10 20:45:11'),
(127, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'subject 1111', '<p>contentsssss</p>', '2023-05-10 20:45:43', '2023-05-10 20:45:43'),
(128, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'bb', '<p>cc</p>', '2023-05-10 20:46:37', '2023-05-10 20:46:37'),
(129, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'bb', '<p>cc</p>', '2023-05-10 20:46:56', '2023-05-10 20:46:56'),
(130, NULL, NULL, 2, 'sender@ipm.edu.mo', 'jose@ipm.edu.mo', NULL, 'asu', '<p>454</p>', '2023-05-10 23:26:13', '2023-05-10 23:26:13'),
(131, NULL, NULL, 2, 'sender@ipm.edu.mo', 'jose@ipm.edu.mo', NULL, 'asu', '<p>454</p>', '2023-05-10 23:27:12', '2023-05-10 23:27:12'),
(132, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'abc', '<p>efg</p>', '2023-05-10 23:29:47', '2023-05-10 23:29:47'),
(133, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'abc', '<p>efg</p>', '2023-05-10 23:32:18', '2023-05-10 23:32:18'),
(134, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'subjectd', '<p>content body</p>', '2023-05-10 23:34:13', '2023-05-10 23:34:13'),
(135, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'subject 2', '<p>conent html</p>', '2023-05-10 23:38:00', '2023-05-10 23:38:00'),
(136, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'subject11', '<p>Content ...</p>', '2023-05-11 00:15:54', '2023-05-11 00:15:54'),
(137, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'subject11', '<p>Content ...</p>', '2023-05-11 00:18:49', '2023-05-11 00:18:49'),
(138, NULL, NULL, 2, 'sender@ipm.edu.mo', 'josechan@ipm.edu.mo', NULL, 'subject', '<p>content</p>', '2023-05-11 00:19:59', '2023-05-11 00:19:59');

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
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) NOT NULL,
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

INSERT INTO `forms` (`id`, `organization_id`, `name`, `title`, `description`, `require_login`, `require_member`, `created_at`, `updated_at`) VALUES
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
  `department_id` bigint(20) DEFAULT NULL,
  `parent_id` bigint(20) NOT NULL DEFAULT 0,
  `root_id` bigint(20) NOT NULL DEFAULT 0,
  `honorific` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `admin_user_id` varchar(255) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `response_date` date DEFAULT NULL,
  `response` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `department_id`, `parent_id`, `root_id`, `honorific`, `name`, `title`, `content`, `email`, `phone`, `language`, `admin_user_id`, `request_date`, `response_date`, `response`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, NULL, NULL, '哪些課程', '澳門理工大學提供哪些課程?', 'mward@yahoo.com', '(952) 982-0092', NULL, '1', NULL, NULL, NULL, NULL, NULL),
(2, 1, 0, 2, NULL, NULL, '學制', '課程採用甚麼學制?', 'hardy.cormier@stanton.com', '1-337-831-3490', NULL, '1', NULL, NULL, NULL, NULL, NULL),
(3, 1, 2, 2, NULL, NULL, '學制 follow 1', '課程採用甚麼學制? follow up 1', 'white.joelle@gmail.com', '458-907-3572', NULL, '1', NULL, NULL, NULL, NULL, NULL),
(4, 1, 3, 2, NULL, NULL, '學制 follow 2', '課程採用甚麼學制? follow up 2', 'dschuster@yahoo.com', '650.751.1776', NULL, '1', NULL, NULL, '語言', NULL, NULL),
(5, 1, 0, 5, NULL, NULL, '授課語言', '課程採用甚麼語言授課?', 'thiel.clifford@yahoo.com', '1-859-414-0254', NULL, '1', NULL, '2023-05-12', '<p>response....</p>', NULL, '2023-05-11 20:02:06'),
(6, 1, 5, 5, NULL, NULL, '授課語言 follow up 1', '課程採用甚麼語言授課? follow up 1', 'rachel34@yahoo.com', '+1 (312) 763-7324', NULL, '1', NULL, NULL, '<p>111my response....11122</p>', NULL, '2023-05-11 19:32:12'),
(7, 1, 0, 7, NULL, NULL, '課程限制', 'Nihil placeat nihil nihil at provident. Perspiciatis error eos itaque porro. Totam libero necessitatibus voluptatem velit dolorum. Sed omnis voluptates ea et et iusto.語言', 'roy.mraz@hotmail.com', '+1.478.769.3753', NULL, '1', NULL, NULL, NULL, NULL, NULL),
(10, 1, 5, 5, NULL, NULL, '授課語言', '課程採用甚麼語言授課?', 'thiel.clifford@yahoo.com', '1-859-414-0254', NULL, '2', NULL, NULL, '<p>foloowup</p>', '2023-05-11 20:09:38', '2023-05-11 20:09:38'),
(11, 1, 6, 5, NULL, NULL, '授課語言 follow up 1', '課程採用甚麼語言授課? follow up 1', 'rachel34@yahoo.com', '+1 (312) 763-7324', NULL, '2', NULL, NULL, '<p>follow up 2222</p>', '2023-05-11 20:10:06', '2023-05-11 20:10:06');

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
(1, 'App\\Models\\Email', 122, '6e6249f9-deeb-466a-aae4-9a35978d04ff', 'emailAttachments', 'card_blue', '122_vc-upload-1683777180990-2.png', 'image/png', 'emailAttachment', 'emailAttachment', 168461, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-05-10 19:55:57', '2023-05-10 19:55:57'),
(2, 'App\\Models\\Email', 122, '850d9d52-d77b-46b5-98fe-6ce9929bcdf1', 'emailAttachments', 'Card_green', '122_vc-upload-1683777180990-4.png', 'image/png', 'emailAttachment', 'emailAttachment', 148351, '[]', '[]', '{\"preview\":true}', '[]', 2, '2023-05-10 19:55:57', '2023-05-10 19:55:57'),
(3, 'App\\Models\\Email', 123, 'fe470371-560c-495b-aef6-42b9d481df9c', 'emailAttachments', 'turnjs4-api-docs', '123_vc-upload-1683777571980-2.pdf', 'application/pdf', 'emailAttachment', 'emailAttachment', 178329, '[]', '[]', '[]', '[]', 1, '2023-05-10 20:00:15', '2023-05-10 20:00:15'),
(4, 'App\\Models\\Email', 124, '6adb52c9-d532-4104-9969-2be995c18281', 'emailAttachments', 'card_yellow', '124_vc-upload-1683777918318-2.png', 'image/png', 'emailAttachment', 'emailAttachment', 177872, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-05-10 20:16:40', '2023-05-10 20:16:41'),
(5, 'App\\Models\\Email', 125, '682db69c-2a59-44bf-b15d-ee83bf97d145', 'emailAttachments', 'card_yellow', '125_vc-upload-1683777918318-2.png', 'image/png', 'emailAttachment', 'emailAttachment', 177872, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-05-10 20:17:29', '2023-05-10 20:17:29'),
(6, 'App\\Models\\Email', 136, '7b3eb821-262e-44c4-90f8-a99a9e4e94ca', 'emailAttachments', 'card_red', '136_vc-upload-1683790778767-2.png', 'image/png', 'emailAttachment', 'emailAttachment', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-05-11 00:15:54', '2023-05-11 00:15:54'),
(7, 'App\\Models\\Email', 137, '32b97f72-27c2-4703-9d63-115e3c6936ad', 'emailAttachments', 'card_red', '137_vc-upload-1683790778767-2.png', 'image/png', 'emailAttachment', 'emailAttachment', 106143, '[]', '[]', '{\"preview\":true}', '[]', 1, '2023-05-11 00:18:49', '2023-05-11 00:18:49');

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
(1, NULL, 'Francisca', 'Muller', 'Ms. Agustina Stokes', NULL, NULL, 'wolff.joan@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(2, NULL, 'Shyanne', 'Runte', 'Tamia Tremblay DVM', NULL, NULL, 'dayna26@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(3, NULL, 'Luciano', 'Lockman', 'Orrin Berge', NULL, NULL, 'laney54@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(4, NULL, 'Lorenzo', 'Koss', 'Hollie Prosacco Sr.', NULL, NULL, 'bcasper@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(5, NULL, 'Kole', 'Kutch', 'Blanca Haley', NULL, NULL, 'qschinner@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(6, NULL, 'Matilde', 'Goyette', 'Mr. Rigoberto Frami', NULL, NULL, 'kristin.gislason@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(7, NULL, 'Alfonso', 'Effertz', 'Mr. Dino Schaefer PhD', NULL, NULL, 'smcglynn@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(8, NULL, 'Gideon', 'Gutkowski', 'Kelsie Dooley PhD', NULL, NULL, 'kirstin93@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(9, NULL, 'Ivah', 'Kuvalis', 'Prof. Ottis Stark', NULL, NULL, 'alden14@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(10, NULL, 'Felicity', 'Auer', 'Miss Cecile Hettinger', NULL, NULL, 'nicolas.hermann@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(11, NULL, 'Corrine', 'Jacobi', 'Prof. Cathryn Oberbrunner II', NULL, NULL, 'pietro27@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(12, NULL, 'Albert', 'Cremin', 'Emmie Nitzsche', NULL, NULL, 'genoveva.schuster@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(13, NULL, 'Jazmyn', 'Green', 'Prof. Glenda Rempel MD', NULL, NULL, 'angie72@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(14, NULL, 'Aimee', 'Jerde', 'Addie Runte', NULL, NULL, 'mharris@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(15, NULL, 'Walton', 'Schultz', 'Gabrielle Rippin', NULL, NULL, 'collier.eriberto@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(16, NULL, 'Hector', 'Nader', 'Milton Gutmann', NULL, NULL, 'sterling.wiza@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(17, NULL, 'Laurianne', 'Paucek', 'Rose Tremblay IV', NULL, NULL, 'raleigh47@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(18, NULL, 'Michelle', 'Treutel', 'Guadalupe Mills', NULL, NULL, 'crona.ova@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(19, NULL, 'Cleveland', 'Gutmann', 'Sydney Keebler', NULL, NULL, 'ulehner@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(20, NULL, 'Maryam', 'Bartell', 'Columbus Stokes', NULL, NULL, 'uriel.simonis@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(21, NULL, 'Arturo', 'Hyatt', 'Roosevelt Boehm', NULL, NULL, 'syble.graham@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(22, NULL, 'London', 'Pacocha', 'Hudson Fritsch', NULL, NULL, 'jennifer.treutel@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(23, NULL, 'Retha', 'Runte', 'Stefanie Heidenreich I', NULL, NULL, 'amos73@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(24, NULL, 'Beulah', 'Gutkowski', 'Alexys Beer', NULL, NULL, 'bianka.cremin@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(25, NULL, 'Alden', 'Will', 'Myrna Wolf', NULL, NULL, 'eugene50@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(26, NULL, 'Mackenzie', 'Kihn', 'Green Dicki', NULL, NULL, 'zgorczany@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(27, NULL, 'Mckenzie', 'Brekke', 'Verla Auer', NULL, NULL, 'aemard@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(28, NULL, 'Lourdes', 'Crooks', 'Drew Toy', NULL, NULL, 'baumbach.adonis@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(29, NULL, 'Trevor', 'Klocko', 'Katrine Jenkins', NULL, NULL, 'edgardo.donnelly@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(30, NULL, 'Martin', 'Weissnat', 'Loraine Powlowski', NULL, NULL, 'lauretta.bartoletti@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(31, NULL, 'Bernardo', 'Lockman', 'Prof. Winston Kshlerin', NULL, NULL, 'melvin74@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(32, NULL, 'Marcelo', 'Schaefer', 'Roosevelt Mann', NULL, NULL, 'mckenzie.yoshiko@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(33, NULL, 'Bradly', 'Carroll', 'Mrs. Ivy Wuckert III', NULL, NULL, 'shanna75@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(34, NULL, 'Zita', 'Grant', 'Kolby Schneider', NULL, NULL, 'destinee91@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(35, NULL, 'Arianna', 'Wiza', 'Dr. Marvin O\'Connell II', NULL, NULL, 'reagan.heathcote@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(36, NULL, 'Nicole', 'Goyette', 'Tillman Rau', NULL, NULL, 'stephen35@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(37, NULL, 'Marlon', 'Yundt', 'Romaine Brekke', NULL, NULL, 'francesca10@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(38, NULL, 'Weldon', 'Nader', 'Mr. Erick Altenwerth', NULL, NULL, 'krajcik.michaela@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(39, NULL, 'Alexie', 'McKenzie', 'Javier Krajcik', NULL, NULL, 'ioconnell@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(40, NULL, 'Napoleon', 'Mayert', 'Jarrett DuBuque', NULL, NULL, 'boehm.colby@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(41, NULL, 'Jillian', 'Gleason', 'Millie Fisher', NULL, NULL, 'elna82@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(42, NULL, 'Sidney', 'Brekke', 'Rashad Raynor', NULL, NULL, 'kiara.kub@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(43, NULL, 'Dax', 'Feest', 'Mrs. Peggie Hagenes', NULL, NULL, 'zmann@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(44, NULL, 'Garett', 'Shanahan', 'Prof. Madyson Hintz DDS', NULL, NULL, 'darwin.osinski@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(45, NULL, 'Retta', 'Gottlieb', 'Garry Pouros', NULL, NULL, 'rshields@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(46, NULL, 'Bryana', 'Ortiz', 'Miss Burnice Kuphal', NULL, NULL, 'fweimann@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(47, NULL, 'Murphy', 'Cartwright', 'Ima Wilkinson', NULL, NULL, 'hmacejkovic@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(48, NULL, 'Arnold', 'Towne', 'Meta Botsford', NULL, NULL, 'murazik.gabrielle@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(49, NULL, 'Dudley', 'Murphy', 'Celia Denesik', NULL, NULL, 'slittle@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(50, NULL, 'Dashawn', 'Emmerich', 'Clifford Cormier', NULL, NULL, 'jstamm@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(51, NULL, 'Arnoldo', 'Green', 'Angus Grady', NULL, NULL, 'fsatterfield@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(52, NULL, 'Callie', 'Howell', 'Stephan Bernhard', NULL, NULL, 'bspencer@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(53, NULL, 'Ewald', 'Eichmann', 'Paula Carroll', NULL, NULL, 'mcassin@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(54, NULL, 'Leone', 'White', 'Nathanial Spencer', NULL, NULL, 'zwillms@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(55, NULL, 'Lizeth', 'Schamberger', 'Crystal Keeling', NULL, NULL, 'hoppe.josefa@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(56, NULL, 'Amelia', 'Smith', 'Prof. Alejandrin Bahringer MD', NULL, NULL, 'stoltenberg.joshuah@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(57, NULL, 'Laury', 'Wilderman', 'Miss Lilliana Tremblay DVM', NULL, NULL, 'aryan@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(58, NULL, 'Newton', 'Hansen', 'Eleanora Tremblay', NULL, NULL, 'xtrantow@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(59, NULL, 'Shirley', 'Hermiston', 'Curtis Dicki PhD', NULL, NULL, 'zwalter@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(60, NULL, 'Brendan', 'Buckridge', 'Mara Medhurst', NULL, NULL, 'rohan.andreanne@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(61, NULL, 'Charley', 'Huel', 'Fletcher Ondricka', NULL, NULL, 'jast.emma@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(62, NULL, 'Eugene', 'Frami', 'Hollis Dietrich', NULL, NULL, 'betty18@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(63, NULL, 'Marcel', 'Osinski', 'Nelda Wilkinson', NULL, NULL, 'thompson.eulalia@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(64, NULL, 'Peggie', 'Lockman', 'Dr. Tod Hartmann', NULL, NULL, 'donnelly.rusty@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(65, NULL, 'Hubert', 'Schaefer', 'Eddie Crist', NULL, NULL, 'hunter.erdman@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(66, NULL, 'Antonietta', 'Thiel', 'Dr. Wallace Hilpert', NULL, NULL, 'sam.conroy@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(67, NULL, 'Ivy', 'Feil', 'Prudence Sanford', NULL, NULL, 'martine.feest@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(68, NULL, 'Lauriane', 'Konopelski', 'Grady Walsh', NULL, NULL, 'ryleigh.king@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(69, NULL, 'Kasey', 'Ortiz', 'Ms. Verdie Wolff Sr.', NULL, NULL, 'jason30@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(70, NULL, 'Ricardo', 'Huel', 'Brenden Hessel', NULL, NULL, 'qcummings@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(71, NULL, 'Devan', 'Bradtke', 'Alberta Jacobson', NULL, NULL, 'ikunze@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(72, NULL, 'Mya', 'Rippin', 'Prof. Rebeka Frami', NULL, NULL, 'carole.kovacek@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(73, NULL, 'Jaunita', 'Wisozk', 'Jamar Prohaska', NULL, NULL, 'howe.kaela@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(74, NULL, 'Easton', 'Farrell', 'Maria Harber', NULL, NULL, 'eleanore.corkery@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(75, NULL, 'Ian', 'Trantow', 'Wendy Kling', NULL, NULL, 'feest.electa@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(76, NULL, 'Javier', 'Willms', 'Kim VonRueden', NULL, NULL, 'oconn@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(77, NULL, 'Newton', 'Medhurst', 'Winston Nitzsche', NULL, NULL, 'evans87@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(78, NULL, 'Deborah', 'Abshire', 'Mr. Llewellyn Moen', NULL, NULL, 'canderson@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(79, NULL, 'Anastasia', 'Jacobi', 'Miss Zoey Kemmer DVM', NULL, NULL, 'raymond.ryan@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(80, NULL, 'Niko', 'Mosciski', 'Bernhard Mitchell PhD', NULL, NULL, 'klocko.ariane@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(81, NULL, 'Ramona', 'Erdman', 'Laron Paucek', NULL, NULL, 'botsford.ike@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(82, NULL, 'Richie', 'Langosh', 'Nigel Dibbert', NULL, NULL, 'marks.aurelio@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(83, NULL, 'Estelle', 'Beier', 'Prof. Dalton Jast', NULL, NULL, 'savanah29@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(84, NULL, 'Leann', 'Feil', 'Breana Schmitt', NULL, NULL, 'kendra81@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(85, NULL, 'Cesar', 'Kuhic', 'Dorothy Wolff', NULL, NULL, 'yost.bennett@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:47', '2023-05-09 18:09:47'),
(86, NULL, 'Ernestina', 'Legros', 'Ms. Clementine Torphy PhD', NULL, NULL, 'runolfsson.abraham@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(87, NULL, 'Julianne', 'Walsh', 'Mr. Reyes Tremblay', NULL, NULL, 'lowe.bethel@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(88, NULL, 'Abel', 'Jacobs', 'Miss Elza Emmerich', NULL, NULL, 'hoeger.amparo@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(89, NULL, 'Nannie', 'Wunsch', 'Humberto Rohan MD', NULL, NULL, 'dbruen@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(90, NULL, 'Judge', 'Pouros', 'Lloyd D\'Amore', NULL, NULL, 'gprice@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(91, NULL, 'Fannie', 'Strosin', 'Pedro Ziemann', NULL, NULL, 'elza.bernier@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(92, NULL, 'Otha', 'Huels', 'Dr. Ben Cruickshank', NULL, NULL, 'claude83@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(93, NULL, 'Sammie', 'Stanton', 'Gudrun Koelpin', NULL, NULL, 'xswift@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(94, NULL, 'Tyson', 'Bradtke', 'Prof. Gilda Brown', NULL, NULL, 'ncorkery@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(95, NULL, 'Fae', 'DuBuque', 'Luther Murazik MD', NULL, NULL, 'keeley49@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(96, NULL, 'Jamarcus', 'Wisoky', 'Bria Goyette', NULL, NULL, 'aubrey.collier@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(97, NULL, 'Shyanne', 'Schmidt', 'Prof. Patsy Hamill', NULL, NULL, 'ozella.rohan@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(98, NULL, 'Leif', 'Beier', 'Ole Schmidt IV', NULL, NULL, 'chowe@example.com', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(99, NULL, 'Solon', 'McDermott', 'Destini Buckridge', NULL, NULL, 'kyler.gleason@example.org', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(100, NULL, 'Bradley', 'Balistreri', 'Justice Schmidt', NULL, NULL, 'daisy53@example.net', NULL, NULL, NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `member_organization`
--

CREATE TABLE `member_organization` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) NOT NULL,
  `organization_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_organization`
--

INSERT INTO `member_organization` (`id`, `member_id`, `organization_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 1, NULL, NULL),
(3, 3, 1, NULL, NULL),
(4, 4, 2, NULL, NULL),
(5, 5, 2, NULL, NULL),
(6, 6, 2, NULL, NULL);

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
(14, '2023_03_07_013708_create_organizations_table', 1),
(15, '2023_03_07_013925_create_admin_user_organization_table', 1),
(16, '2023_03_07_090738_create_member_organization_table', 1),
(17, '2023_03_09_013112_create_member_user_table', 1),
(18, '2023_03_09_151059_create_certificates_table', 1),
(19, '2023_03_09_152008_create_certificate_member', 1),
(20, '2023_03_13_070345_create_forms_table', 1),
(21, '2023_03_13_094408_create_form_fields_table', 1),
(22, '2023_05_02_101333_create_departments_table', 1),
(23, '2023_05_02_101622_create_admin_user_department_table', 1),
(24, '2023_05_02_135438_create_inquiries_table', 1),
(25, '2023_05_10_015101_create_emails_table', 1),
(26, '2023_05_10_020322_create_media_table', 1),
(27, '2023_05_10_073558_create_notifications_table', 2);

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
(4, 'App\\Models\\AdminUser', 2),
(5, 'App\\Models\\User', 1);

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
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `abbr` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `href` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `abbr`, `full_name`, `email`, `phone`, `address`, `country`, `href`, `title`, `avatar`, `description`, `content`, `created_at`, `updated_at`) VALUES
(1, 'Quia.', 'Quinton Skiles', 'qkohler@yahoo.com', '678.555.7406', '5624 Oberbrunner Drive\nArmstrongberg, MA 76302', 'Liechtenstein', 'https://www.thewsu.org', 'Est officia aut dignissimos qui eveniet.', NULL, 'Eligendi quae in repellendus sed maxime nobis architecto eum. Suscipit sit facilis tenetur et modi quia. Molestiae eligendi autem quo magnam sint.', 'Eligendi autem non aut culpa reprehenderit. Commodi earum quisquam minus qui pariatur corrupti sit fugit. Ut nemo dolorem incidunt omnis est.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(2, 'Sint.', 'Jocelyn Kuphal', 'blick.claudie@corkery.info', '425.985.4253', '967 Wilderman Brooks Apt. 205\nNew Enola, GA 08030-4739', 'Mexico', 'https://www.thewsu.org', 'Qui voluptas non.', NULL, 'Tenetur eligendi voluptas cupiditate. Praesentium qui fugit voluptatem ipsam quaerat fugit. Autem et id ad eum et enim.', 'Ducimus id quo animi odit assumenda. Dolore cum ut possimus similique quia quia. Soluta velit voluptatibus quae consequatur. Ut provident ullam autem inventore aut ducimus.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(3, 'Aut.', 'Mrs. Natalie Wunsch IV', 'loma54@gmail.com', '+1-954-936-2675', '489 Keyshawn Alley\nPort Jena, AZ 08140', 'Seychelles', 'https://www.thewsu.org', 'Quia aut quaerat aliquid animi.', NULL, 'Quod nihil voluptates quisquam pariatur. Omnis autem molestiae amet debitis eaque ullam aperiam. Placeat et et nobis suscipit aspernatur ad non. Iure minima quis quasi soluta voluptatem.', 'Quo ea natus excepturi eaque repellendus facilis iure. Quod vitae iste magnam dicta labore quis atque. Minima qui assumenda ad voluptatum quo similique sapiente.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(4, 'A.', 'Clarissa Lesch', 'krystal.beier@gmail.com', '540-381-5244', '11704 Kimberly Mount Suite 180\nAlexandermouth, OR 87986', 'French Southern Territories', 'https://www.thewsu.org', 'Quia et amet distinctio accusantium.', NULL, 'Optio id ut aut eligendi officia assumenda. Officiis vero est commodi quisquam omnis in qui. Molestiae quaerat cum et consequatur aliquid ullam.', 'Dolorem totam et quos ut quo. Quo at recusandae ut laboriosam minus dolor eos. Maiores quam eveniet et. Qui iure earum quas in quas.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(5, 'Sunt.', 'Mrs. Cora Bogan PhD', 'denesik.berta@yahoo.com', '1-312-415-1127', '257 Monahan Expressway Apt. 221\nKendallborough, RI 04135', 'Uruguay', 'https://www.thewsu.org', 'Rem qui numquam est.', NULL, 'Velit molestias corporis sint deleniti ea rerum. Impedit maiores sit similique voluptatem tempore. Dolores in commodi sit consequatur. Quo et iusto expedita.', 'Ea et voluptates quis eum. Voluptatum accusamus impedit voluptas cupiditate rerum error. Eum aut facere ut asperiores deleniti aspernatur ut. Non quia illo omnis sit assumenda quod incidunt.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(6, 'Eos.', 'Diana Wilderman Sr.', 'nmcclure@hotmail.com', '339.230.3486', '223 Johnson Coves\nNorth Adrain, TX 12293', 'Turkey', 'https://www.thewsu.org', 'Eum repellat illo sint qui.', NULL, 'Et magni dolorem qui aut qui doloremque quo fugit. Sed voluptatum est ipsum voluptates et. Voluptas mollitia nobis delectus. Cum consequuntur asperiores dolores qui et assumenda blanditiis excepturi.', 'Occaecati omnis deleniti et molestiae sint. Aliquid sint nostrum rem qui. Deleniti autem animi doloribus sit impedit. Occaecati et ut earum quam dolore molestias temporibus ea.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(7, 'Amet.', 'Cleta Wisoky', 'osbaldo.hartmann@hotmail.com', '+1.928.804.0325', '49668 Gerda Mission\nStantonburgh, NY 29706', 'South Africa', 'https://www.thewsu.org', 'Doloribus dolore omnis dolorum.', NULL, 'Porro praesentium quos dolores in fuga voluptas aliquam. Vel soluta nulla nemo quibusdam molestias sunt. Deserunt doloremque placeat rerum dolor vel vero.', 'Nihil ut accusantium tenetur magni corrupti libero. Dolore dolor adipisci reprehenderit. Aliquam ipsam eum modi dignissimos tempore velit laborum.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(8, 'Ut.', 'Rhett Huels', 'hermina65@rutherford.com', '(660) 571-7220', '9697 Abbott Courts\nNorth Theo, AL 53431-9362', 'Indonesia', 'https://www.thewsu.org', 'Minus quae itaque autem minus.', NULL, 'Commodi sunt sapiente molestiae non. Voluptatum sunt voluptates amet quidem sit dolorum culpa qui. Fuga consequuntur reiciendis quia impedit aut. Sed dolore ut ab consequuntur.', 'Unde recusandae non nisi esse animi et. Quisquam asperiores non quia ea. Ut ea similique ratione necessitatibus aut accusantium mollitia. Esse dolorem eveniet sed pariatur aut.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(9, 'Vel.', 'Kathleen Howell', 'rodriguez.lora@nader.com', '1-414-808-0934', '3462 Daugherty Rapids\nKemmerville, CT 67086', 'Togo', 'https://www.thewsu.org', 'Laudantium ratione placeat harum consequatur.', NULL, 'Temporibus dolore at id voluptas. Temporibus ea veritatis qui neque iusto. Ea ea deserunt dolores non sed incidunt et. Est est quasi illo consequatur nemo.', 'Fuga qui et magni occaecati fuga rerum. Expedita aperiam occaecati excepturi quidem quas nam tempore. Et corrupti provident quasi quas. Omnis et distinctio architecto iste ullam.', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(10, 'Ipsa.', 'Mustafa Armstrong', 'berge.eliseo@hotmail.com', '831-927-3735', '105 Emie Mill Suite 485\nAnnettaport, VT 95393', 'Switzerland', 'https://www.thewsu.org', 'Ex voluptas ut vitae.', NULL, 'Voluptatem aut iusto eveniet praesentium repellat consequatur. Et dolorum debitis laborum in tempore. Aut et dolores cum accusantium ut qui. Molestiae voluptas nobis incidunt cumque sunt et.', 'Modi ut ullam neque dolor sed quo. Mollitia dignissimos totam molestiae rem et ab aspernatur. Animi molestias magni consequatur voluptate ut sed qui. Nemo eligendi nihil sunt doloribus.', '2023-05-09 18:09:48', '2023-05-09 18:09:48');

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
(1, 'master', 'admin_web', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(2, 'admin', 'admin_web', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(3, 'organization', 'admin_web', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(4, 'department', 'admin_web', '2023-05-09 18:09:48', '2023-05-09 18:09:48'),
(5, 'member', 'web', '2023-05-09 18:09:48', '2023-05-09 18:09:48');

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
('9qwEkY8GhlSkD9TVLSyr402MtuK2NwRZX5S76eJK', 2, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoicDdNNFBUVURmbVJMSzNGMWRReUphd1FiSm5LWUJBUTQ2Vk5qaWp4VSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvbWFuYWdlL2RlcGFydG1lbnQvMS9pbnF1aXJpZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU2OiJsb2dpbl9hZG1pbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MjM6InBhc3N3b3JkX2hhc2hfYWRtaW5fd2ViIjtzOjYwOiIkMnkkMTAkL21Db0Y3QVZKd1EvaG9OeTBDd2FkZTByUGZMZTg3Y2YxT00ydFdsT1I0SW9DQ1I3UmFOdnEiO3M6MTI6Im9yZ2FuaXphdGlvbiI7TzoyMzoiQXBwXE1vZGVsc1xPcmdhbml6YXRpb24iOjMwOntzOjEzOiIAKgBjb25uZWN0aW9uIjtzOjU6Im15c3FsIjtzOjg6IgAqAHRhYmxlIjtzOjEzOiJvcmdhbml6YXRpb25zIjtzOjEzOiIAKgBwcmltYXJ5S2V5IjtzOjI6ImlkIjtzOjEwOiIAKgBrZXlUeXBlIjtzOjM6ImludCI7czoxMjoiaW5jcmVtZW50aW5nIjtiOjE7czo3OiIAKgB3aXRoIjthOjA6e31zOjEyOiIAKgB3aXRoQ291bnQiO2E6MDp7fXM6MTk6InByZXZlbnRzTGF6eUxvYWRpbmciO2I6MDtzOjEwOiIAKgBwZXJQYWdlIjtpOjE1O3M6NjoiZXhpc3RzIjtiOjE7czoxODoid2FzUmVjZW50bHlDcmVhdGVkIjtiOjA7czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO3M6MTM6IgAqAGF0dHJpYnV0ZXMiO2E6MTQ6e3M6MjoiaWQiO2k6MTtzOjQ6ImFiYnIiO3M6NToiUXVpYS4iO3M6OToiZnVsbF9uYW1lIjtzOjE0OiJRdWludG9uIFNraWxlcyI7czo1OiJlbWFpbCI7czoxNzoicWtvaGxlckB5YWhvby5jb20iO3M6NToicGhvbmUiO3M6MTI6IjY3OC41NTUuNzQwNiI7czo3OiJhZGRyZXNzIjtzOjQ2OiI1NjI0IE9iZXJicnVubmVyIERyaXZlCkFybXN0cm9uZ2JlcmcsIE1BIDc2MzAyIjtzOjc6ImNvdW50cnkiO3M6MTM6IkxpZWNodGVuc3RlaW4iO3M6NDoiaHJlZiI7czoyMjoiaHR0cHM6Ly93d3cudGhld3N1Lm9yZyI7czo1OiJ0aXRsZSI7czo0MDoiRXN0IG9mZmljaWEgYXV0IGRpZ25pc3NpbW9zIHF1aSBldmVuaWV0LiI7czo2OiJhdmF0YXIiO047czoxMToiZGVzY3JpcHRpb24iO3M6MTQ2OiJFbGlnZW5kaSBxdWFlIGluIHJlcGVsbGVuZHVzIHNlZCBtYXhpbWUgbm9iaXMgYXJjaGl0ZWN0byBldW0uIFN1c2NpcGl0IHNpdCBmYWNpbGlzIHRlbmV0dXIgZXQgbW9kaSBxdWlhLiBNb2xlc3RpYWUgZWxpZ2VuZGkgYXV0ZW0gcXVvIG1hZ25hbSBzaW50LiI7czo3OiJjb250ZW50IjtzOjE0MToiRWxpZ2VuZGkgYXV0ZW0gbm9uIGF1dCBjdWxwYSByZXByZWhlbmRlcml0LiBDb21tb2RpIGVhcnVtIHF1aXNxdWFtIG1pbnVzIHF1aSBwYXJpYXR1ciBjb3JydXB0aSBzaXQgZnVnaXQuIFV0IG5lbW8gZG9sb3JlbSBpbmNpZHVudCBvbW5pcyBlc3QuIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDIzLTA1LTEwIDAyOjA5OjQ4IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDIzLTA1LTEwIDAyOjA5OjQ4Ijt9czoxMToiACoAb3JpZ2luYWwiO2E6MTQ6e3M6MjoiaWQiO2k6MTtzOjQ6ImFiYnIiO3M6NToiUXVpYS4iO3M6OToiZnVsbF9uYW1lIjtzOjE0OiJRdWludG9uIFNraWxlcyI7czo1OiJlbWFpbCI7czoxNzoicWtvaGxlckB5YWhvby5jb20iO3M6NToicGhvbmUiO3M6MTI6IjY3OC41NTUuNzQwNiI7czo3OiJhZGRyZXNzIjtzOjQ2OiI1NjI0IE9iZXJicnVubmVyIERyaXZlCkFybXN0cm9uZ2JlcmcsIE1BIDc2MzAyIjtzOjc6ImNvdW50cnkiO3M6MTM6IkxpZWNodGVuc3RlaW4iO3M6NDoiaHJlZiI7czoyMjoiaHR0cHM6Ly93d3cudGhld3N1Lm9yZyI7czo1OiJ0aXRsZSI7czo0MDoiRXN0IG9mZmljaWEgYXV0IGRpZ25pc3NpbW9zIHF1aSBldmVuaWV0LiI7czo2OiJhdmF0YXIiO047czoxMToiZGVzY3JpcHRpb24iO3M6MTQ2OiJFbGlnZW5kaSBxdWFlIGluIHJlcGVsbGVuZHVzIHNlZCBtYXhpbWUgbm9iaXMgYXJjaGl0ZWN0byBldW0uIFN1c2NpcGl0IHNpdCBmYWNpbGlzIHRlbmV0dXIgZXQgbW9kaSBxdWlhLiBNb2xlc3RpYWUgZWxpZ2VuZGkgYXV0ZW0gcXVvIG1hZ25hbSBzaW50LiI7czo3OiJjb250ZW50IjtzOjE0MToiRWxpZ2VuZGkgYXV0ZW0gbm9uIGF1dCBjdWxwYSByZXByZWhlbmRlcml0LiBDb21tb2RpIGVhcnVtIHF1aXNxdWFtIG1pbnVzIHF1aSBwYXJpYXR1ciBjb3JydXB0aSBzaXQgZnVnaXQuIFV0IG5lbW8gZG9sb3JlbSBpbmNpZHVudCBvbW5pcyBlc3QuIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDIzLTA1LTEwIDAyOjA5OjQ4IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDIzLTA1LTEwIDAyOjA5OjQ4Ijt9czoxMDoiACoAY2hhbmdlcyI7YTowOnt9czo4OiIAKgBjYXN0cyI7YTowOnt9czoxNzoiACoAY2xhc3NDYXN0Q2FjaGUiO2E6MDp7fXM6MjE6IgAqAGF0dHJpYnV0ZUNhc3RDYWNoZSI7YTowOnt9czo4OiIAKgBkYXRlcyI7YTowOnt9czoxMzoiACoAZGF0ZUZvcm1hdCI7TjtzOjEwOiIAKgBhcHBlbmRzIjthOjA6e31zOjE5OiIAKgBkaXNwYXRjaGVzRXZlbnRzIjthOjA6e31zOjE0OiIAKgBvYnNlcnZhYmxlcyI7YTowOnt9czoxMjoiACoAcmVsYXRpb25zIjthOjA6e31zOjEwOiIAKgB0b3VjaGVzIjthOjA6e31zOjEwOiJ0aW1lc3RhbXBzIjtiOjE7czo5OiIAKgBoaWRkZW4iO2E6MDp7fXM6MTA6IgAqAHZpc2libGUiO2E6MDp7fXM6MTE6IgAqAGZpbGxhYmxlIjthOjA6e31zOjEwOiIAKgBndWFyZGVkIjthOjE6e2k6MDtzOjE6IioiO319fQ==', 1683884228);

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
(1, 1, 'Member\'s Team', 1, '2023-05-09 18:09:48', '2023-05-09 18:09:48');

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
(1, 'Member', 'member@example.com', '2023-05-09 18:09:48', '$2y$10$yJKjhxcoUWWdhgxoFR1x/OK8TeOHsY2Tyoerbv3OH4J1Q49J1EgZ6', NULL, NULL, '2yxkDk4MDL', NULL, NULL, '2023-05-09 18:09:48', '2023-05-09 18:09:48');

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
-- Indexes for table `admin_user_organization`
--
ALTER TABLE `admin_user_organization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificate_member`
--
ALTER TABLE `certificate_member`
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
-- Indexes for table `member_organization`
--
ALTER TABLE `member_organization`
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
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_user_organization`
--
ALTER TABLE `admin_user_organization`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate_member`
--
ALTER TABLE `certificate_member`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `member_organization`
--
ALTER TABLE `member_organization`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `member_user`
--
ALTER TABLE `member_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
