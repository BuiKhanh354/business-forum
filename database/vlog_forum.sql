-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: vlog_forum
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#2563eb',
  `description` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Chiến lược kinh doanh','chien-luoc-kinh-doanh','#1d4ed8','Định hướng chiến lược kinh doanh thực chiến',0,'2026-11-21 03:30:16','2026-11-21 04:14:52'),(2,'Tài chính & Dòng tiền','tai-chinh-dong-tien','#16a34a','Kiểm soát dòng tiền, tối ưu hóa chi phí vận hành và quản trị rủi ro tài chính.',0,'2026-11-21 03:30:16','2026-11-21 03:30:16'),(3,'Thị trường & Đầu tư','thi-truong-dau-tu','#d97706','Cập nhật diễn biến thị trường, cơ hội hợp tác và xúc tiến đầu tư kinh doanh.',0,'2026-11-21 03:30:16','2026-11-21 03:30:16'),(4,'Công nghệ & AI','cong-nghe-ai','#7c3aed','Chuyển đổi số, tự động hóa quy trình và ứng dụng trí tuệ nhân tạo.',0,'2026-11-21 03:30:16','2026-11-21 03:30:16'),(5,'Quản trị & ESG','quan-tri-esg','#0d9488','Mô hình quản trị bền vững, tiêu chuẩn xanh và trách nhiệm xã hội.',0,'2026-11-21 03:30:16','2026-11-21 03:30:16'),(6,'Thông báo','thong-bao','#64748b','Thông báo Ban Quản Trị',0,'2026-11-21 11:05:21','2026-11-21 04:16:17'),(10,'test','test','#16a34a',NULL,0,'2026-11-21 04:48:11','2026-11-21 04:48:11'),(15,'test_2','test-2','#2563eb',NULL,0,'2026-11-22 10:35:25','2026-11-22 10:35:25'),(16,'test_4','test-4','#2563eb',NULL,0,'2026-11-22 10:35:29','2026-11-22 10:35:29'),(17,'test_8','test-8','#2563eb',NULL,0,'2026-11-22 10:35:33','2026-11-22 10:35:33'),(18,'test_9','test-9','#2563eb',NULL,0,'2026-11-22 10:35:43','2026-11-22 10:35:43');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'approved',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `likes_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_post_id_foreign` (`post_id`),
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,'Nguyễn Văn Minh (CEO TechCorp)','minh.nguyen@techcorp.vn','Góc nhìn rất thực tế và sắc bén của anh Khánh! Doanh nghiệp vừa và nhỏ hiện nay rất cần tối ưu chi phí trước khi mở rộng quy mô.','approved',1,14,'2026-11-21 03:21:30','2026-11-22 09:18:40'),(2,2,'Trần Thị Thu (Giám đốc Đầu tư Alpha Capital)','thu.tran@alphacapital.vn','Tôi đã đăng ký vé tham dự tọa đàm! Rất mong được gặp gỡ và giao lưu cùng các anh em doanh nhân.','approved',0,8,'2026-11-21 03:21:30','2026-11-21 03:21:30'),(3,1,'Lê Khắc Toàn (Giám đốc Vận hành)',NULL,'Bài viết phân tích rất chuẩn xác xu hướng công nghệ năm 2026!','approved',0,0,'2026-11-21 04:59:35','2026-11-21 09:56:56'),(4,14,'Khánh',NULL,'Test','approved',1,1,'2026-11-21 06:32:52','2026-11-22 09:15:42');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2026_11_21_095803_create_posts_table',1),(6,'2026_11_21_095804_create_comments_table',1),(7,'2026_11_21_102806_create_categories_table',2),(8,'2026_11_21_114116_add_external_link_to_posts_table',3),(9,'2026_11_21_120516_add_gallery_images_to_posts_table',4),(10,'2026_11_21_122719_create_settings_table',5),(11,'2026_11_21_141500_add_profile_fields_to_users_table',6),(12,'2026_11_21_142500_add_avatar_history_to_users_table',7),(13,'2026_11_21_000001_create_proposals_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Chiến lược',
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `external_link` varchar(255) DEFAULT NULL,
  `external_link_title` varchar(255) DEFAULT NULL,
  `external_link_image` varchar(1000) DEFAULT NULL,
  `reading_time` int(11) NOT NULL DEFAULT 5,
  `views_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `likes_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_category_id_foreign` (`category_id`),
  CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'Chiến lược thích ứng của doanh nghiệp trước chu kỳ công nghệ và kinh tế số 2026','chien-luoc-thich-ung-doanh-nghiep-2026','Chiến lược kinh doanh','Chia sẻ một bài báo về xu hướng phát triển trí tuệ nhân tạo và kinh tế số hiện nay. Đây là nguồn tham khảo rất hữu ích cho cộng đồng doanh nghiệp trong việc tối ưu hóa chi phí vận hành và xây dựng lợi thế cạnh tranh dài hạn.','Nội dung chi tiết phân tích chuyên sâu về sự chuyển dịch của các mô hình kinh doanh trong kỷ nguyên số...','assets/images/post-tech-ai.jpg',NULL,'https://abc-link.com','Bài báo về tôi',NULL,5,1437,156,1,1,'2026-11-21 03:21:30','2026-11-22 09:05:06'),(2,6,'Thông báo: Tọa đàm kết nối doanh nhân và cơ hội xúc tiến đầu tư tháng 11','thong-bao-toa-dam-ket-noi-doanh-nhan-thang-11','Thông báo','Trân trọng kính mời các thành viên ban điều hành, quý nhà đầu tư cùng tham dự buổi tọa đàm kết nối kinh doanh nhằm mở rộng mạng lưới giao thương và tìm kiếm cơ hội hợp tác chiến lược trong quý 4.','Thông tin chi tiết về chương trình tọa đàm, diễn giả khách mời và quy trình đăng ký tham dự...','assets/images/post-summit.jpg',NULL,NULL,NULL,NULL,3,985,89,0,1,'2026-11-21 03:21:30','2026-11-22 08:38:50'),(3,2,'Kinh nghiệm tối ưu dòng tiền và quản trị rủi ro thanh khoản 2026','kinh-nghiem-toi-uu-dong-tien-2026','T?i ch?nh & D?ng ti?n','Phân tích cấu trúc dòng tiền hoạt động và giải pháp phòng ngừa rủi ro tỷ giá cho doanh nghiệp.','Chi ti?t ph?n t?ch chi?n lu?c d?ng ti?n...','assets/images/post-tech-ai.jpg',NULL,NULL,NULL,NULL,6,640,48,1,1,'2026-11-21 11:05:21','2026-11-21 04:09:46'),(4,4,'Chiến lược ứng dụng Trí tuệ nhân tạo (AI) trong tự động hóa vận hành SME','chien-luoc-ung-dung-ai-sme-2026','C?ng ngh? & AI','Bản thảo kế hoạch chuyển đổi số và ứng dụng các mô hình AI tối ưu chi phí cho doanh nghiệp vừa và nhỏ.','B?n nh?p dang trong qu? tr?nh ho?n thi?n...','assets/images/post-summit.jpg',NULL,NULL,NULL,NULL,4,3,0,0,1,'2026-11-21 11:05:21','2026-11-21 08:18:53'),(6,10,'bài viết mới','bai-viet-moi','test','Trang quản lýdddddddddddđ','Trang VAA','uploads/posts/1795263211_HvXKoGxDTj.png','[\"uploads\\/posts\\/1795263211_HvXKoGxDTj.png\",\"uploads\\/posts\\/1795263211_TC5psPuEk9.png\"]','https://www.cloudflare.com/','cloudflare','https://www.cloudflare.com/preview.png',6,21,0,1,1,'2026-11-21 04:49:09','2026-11-22 10:37:19'),(8,NULL,'Bài viết chiến lược kinh doanh 5 ảnh tiêu biểu','bai-viet-chien-luoc-kinh-doanh-5-anh-tieu-bieu','Chiến lược kinh doanh','Bộ sưu tập hình ảnh sự kiện và không gian làm việc doanh nghiệp với đầy đủ 5 góc nhìn.','Nội dung chi tiết giới thiệu 5 góc nhìn chiến lược.','uploads/posts/1795263083_mQ1FescDVS.jpg','[\"uploads\\/posts\\/1795263083_mQ1FescDVS.jpg\",\"uploads\\/posts\\/1795263083_VSoqh9ZFlT.jpg\",\"uploads\\/posts\\/1795263083_I9SVNLCFf8.jpg\",\"uploads\\/posts\\/1795263083_dduclS71oW.jpg\",\"uploads\\/posts\\/1795263083_9no98zkfyy.jpg\"]',NULL,NULL,NULL,5,6,0,1,1,'2026-11-21 05:11:23','2026-11-22 10:23:48'),(9,4,'Tối ưu hóa thuế thu nhập doanh nghiệp và kế hoạch tài khóa 2026','toi-uu-hoa-thue-thu-nhap-doanh-nghiep-va-ke-hoach-tai-khoa-2026','Công nghệ & AI','Các phương pháp hợp pháp và minh bạch giúp doanh nghiệp giảm thiểu gánh nặng thuế và tuân thủ chuẩn mực kiểm toán.','Nội dung chi tiết về hoạch định thuế và kiểm toán doanh nghiệp...','assets/images/post-tech-ai.jpg','[\"assets\\/images\\/post-tech-ai.jpg\"]',NULL,NULL,NULL,4,731,45,0,1,'2026-11-21 05:17:28','2026-11-21 06:43:19'),(10,3,'Xây dựng văn hóa tổ chức gắn liền với hiệu suất công việc (KPIs & OKRs)','xay-dung-van-hoa-to-chuc-gan-lien-voi-hieu-suat-cong-viec-kpis-okrs','Thị trường & Đầu tư','Làm thế nào để gắn kết mục tiêu của cá nhân với sứ mệnh chiến lược của toàn bộ ban điều hành.','Nội dung phân tích mô hình quản trị mục tiêu OKRs kết hợp KPIs...','assets/images/post-tech-ai.jpg','[\"assets\\/images\\/post-tech-ai.jpg\"]',NULL,NULL,NULL,6,520,38,0,1,'2026-11-21 05:17:28','2026-11-21 05:17:28'),(11,3,'Báo cáo xúc tiến thương mại quốc tế và các hiệp định FTA thế hệ mới','bao-cao-xuc-tien-thuong-mai-quoc-te-va-cac-hiep-dinh-fta-the-he-moi','Thị trường & Đầu tư','Khai thác tối đa lợi thế từ CPTPP và EVFTA cho các ngành hàng xuất khẩu chủ lực của Việt Nam.','Chi tiết về lộ trình cắt giảm thuế quan và quy tắc xuất xứ hàng hóa...','assets/images/post-tech-ai.jpg','[\"assets\\/images\\/post-tech-ai.jpg\"]',NULL,NULL,NULL,5,890,67,0,1,'2026-11-21 05:17:28','2026-11-21 05:17:28'),(12,2,'Quản trị chuỗi cung ứng bền vững trước biến động logistics toàn cầu','quan-tri-chuoi-cung-ung-ben-vung-truoc-bien-dong-logistics-toan-cau','Tài chính & Dòng tiền','Giải pháp đa dạng hóa nhà cung ứng và ứng dụng phần mềm dự báo tồn kho theo thời gian thực.','Phân tích thực trạng cước vận tải biển và các kịch bản dự phòng chuỗi cung ứng...','assets/images/post-tech-ai.jpg','[\"assets\\/images\\/post-tech-ai.jpg\"]',NULL,NULL,NULL,5,410,29,0,1,'2026-11-21 05:17:28','2026-11-21 05:17:28'),(13,5,'Mô hình kinh doanh tuần hoàn và lợi thế cạnh tranh của doanh nghiệp tiên phong','mo-hinh-kinh-doanh-tuan-hoan-va-loi-the-canh-tranh-cua-doanh-nghiep-tien-phong','Quản trị & ESG','Biến rác thải thành tài nguyên và cách các tập đoàn hàng đầu thế giới kiến tạo dòng doanh thu mới.','Các bước triển khai kinh tế tuần hoàn trong sản xuất và dịch vụ...','assets/images/post-tech-ai.jpg','[\"assets\\/images\\/post-tech-ai.jpg\"]',NULL,NULL,NULL,4,611,51,0,1,'2026-11-21 05:17:28','2026-11-22 08:25:04'),(14,5,'Nghệ thuật đàm phán hợp đồng thương mại lớn dành cho CEO & Founder','nghe-thuat-dam-phan-hop-dong-thuong-mai-lon-danh-cho-ceo-founder','Quản trị & ESG','Những nguyên tắc cốt lõi giúp nhà lãnh đạo làm chủ bàn đàm phán và bảo vệ quyền lợi tối đa cho doanh nghiệp.','Chiến lược đàm phán theo phương pháp Harvard và kinh nghiệm thực chiến...','assets/images/post-tech-ai.jpg','[\"assets\\/images\\/post-tech-ai.jpg\"]',NULL,NULL,NULL,7,1160,112,0,1,'2026-11-21 05:17:28','2026-11-22 10:25:44'),(15,10,'Kế hoạch chuyển giao quyền lực và quản trị công ty gia đình thế hệ thứ hai','ke-hoach-chuyen-giao-quyen-luc-va-quan-tri-cong-ty-gia-dinh-the-he-thu-hai','test','Làm sao để thế hệ kế nghiệp tiếp nối thành công mà không gây xung đột nội bộ ban lãnh đạo.','Nghiên cứu trường hợp chuyển giao thế hệ tại các tập đoàn gia đình lớn...','assets/images/post-tech-ai.jpg','[\"assets\\/images\\/post-tech-ai.jpg\"]',NULL,NULL,NULL,6,974,84,0,1,'2026-11-21 05:17:28','2026-11-22 09:08:53'),(16,10,'Ứng dụng Big Data và phân tích hành vi khách hàng trong ngành bán lẻ','ung-dung-big-data-va-phan-tich-hanh-vi-khach-hang-trong-nganh-ban-le','test','Thấu hiểu hành trình khách hàng từ dữ liệu điểm bán để gia tăng tỷ lệ chuyển đổi và giá trị trọn đời (CLV).','Các công cụ phân tích dữ liệu bán lẻ và phương pháp A/B testing...','assets/images/post-tech-ai.jpg','[\"assets\\/images\\/post-tech-ai.jpg\"]',NULL,NULL,NULL,5,652,48,0,1,'2026-11-21 05:17:28','2026-11-22 10:38:48');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposals`
--

DROP TABLE IF EXISTS `proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `purpose` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `attachment_name` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `is_starred` tinyint(1) NOT NULL DEFAULT 0,
  `admin_notes` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals`
--

LOCK TABLES `proposals` WRITE;
/*!40000 ALTER TABLE `proposals` DISABLE KEYS */;
INSERT INTO `proposals` VALUES (1,'Nguyễn Hoàng Nam','nam.nguyen@forbes.vn','0908 123 456','Forbes Vietnam Media','Phó Tổng Biên tập','Hợp tác nội dung & Bài viết chuyên sâu','Thư ngỏ hợp tác chuỗi bài phân tích Kinh tế vĩ mô & Tái cấu trúc Doanh nghiệp 2026','Kính gửi Ban Quản trị KẾT NỐI,\\n\\nForbes Vietnam đánh giá rất cao chất lượng bài viết và tư duy quản trị chuyên sâu trên diễn đàn. Chúng tôi muốn đề xuất ký kết hợp tác nội dung chiến lược, trích dẫn các bài xã luận độc quyền của Founder và đồng tổ chức Diễn đàn Lãnh đạo Doanh nghiệp Quý 2/2026.\\n\\nRất mong sớm nhận được phản hồi để hai bên sắp xếp buổi gặp mặt tại Bitexco Q.1.','uploads/proposals/proposal_sample_forbes.pdf','Ho-so-hop-tac-Forbes-2026.pdf','pending',1,1,'Đối tác uy tín hàng đầu. Cần ưu tiên sắp xếp lịch hẹn trực tiếp trong tuần này.',NULL,'2026-11-21 09:43:48','2026-11-22 09:15:23'),(2,'Trần Minh Quân','quan.tm@vingroup.net','0918 888 999','Vingroup Capital & Innovation','Giám đốc Đầu tư mạo hiểm','Giới thiệu cơ hội đầu tư & Dòng vốn','Đề xuất kết nối nguồn vốn đầu tư Series A cho các startup công nghệ quản trị','Chào Ban Quản trị KẾT NỐI,\\n\\nHiện tại quỹ chúng tôi đang tìm kiếm các giải pháp ERP và số hóa doanh nghiệp vừa và nhỏ tại Việt Nam. Qua diễn đàn, chúng tôi thấy có nhiều góc nhìn rất thực tế về thị trường. Chúng tôi mong muốn được kết nối và tài trợ cho chuỗi bài thảo luận về dòng vốn mạo hiểm.','uploads/proposals/proposal_sample_pitch.docx','De-xuat-dau-tu-Series-A.docx','contacted',0,1,'Đã gửi email phản hồi lần 1. Đang chờ đối tác gửi bản tóm tắt danh mục đầu tư.',NULL,'2026-11-21 09:43:48','2026-11-21 09:43:48'),(3,'Lê Thanh Thảo','thao.le@techcombank.com.vn','0989 333 444','Techcombank Priority Banking','Trưởng phòng Phát triển Đối tác Cao cấp','Tài trợ Hội thảo / Sự kiện doanh nghiệp','Tài trợ kim cương cho Hội thảo Quản trị Tài chính Doanh nghiệp tư nhân','Kính gửi Founder,\\n\\nKhối Khách hàng Doanh nghiệp của Techcombank rất muốn đồng hành cùng diễn đàn KẾT NỐI trong sự kiện thường niên sắp tới. Gói tài trợ dự kiến bao gồm địa điểm và chi phí tổ chức hội thảo chuyên đề.',NULL,NULL,'approved',1,0,'Đã chốt hợp đồng nguyên tắc. Đang soạn thảo văn bản ký kết chính thức.',NULL,'2026-11-21 09:43:48','2026-11-22 09:15:32'),(4,'Đỗ Quang Vinh','vinh.do@shb.com.vn','0903 999 888','SHB Finance','Phó Chủ tịch HĐQT','Mời diễn giả / Cố vấn kinh tế','Trân trọng kính mời Founder tham gia Diễn đàn Kinh tế Tài chính 2026','Kính gửi Anh Khánh - Founder KẾT NỐI, chúng tôi trân trọng kính mời anh tham dự phiên tọa đàm về chuyển đổi số ngành ngân hàng diễn ra vào tháng sau.',NULL,NULL,'pending',1,1,NULL,'127.0.0.1','2026-11-21 09:44:30','2026-11-22 09:15:14'),(5,'TS. Hoàng Minh Trí','tri.hoang@vietsme.org.vn','Chưa có SĐT',NULL,'Độc giả / Đối tác gửi riêng','Tin nhắn / Thư ngỏ riêng cho Founder','Thư ngỏ riêng: Chào anh Khánh, tôi theo dõi chuỗi bài viết chiến lược của anh từ...','Chào anh Khánh, tôi theo dõi chuỗi bài viết chiến lược của anh từ lâu. Tôi muốn hẹn anh một buổi cafe để trao đổi về cơ hội đồng tác giả một cuốn sách về Quản trị tinh gọn cho doanh nghiệp Việt.',NULL,NULL,'pending',1,1,NULL,'127.0.0.1','2026-11-21 09:46:35','2026-11-22 09:11:42');
/*!40000 ALTER TABLE `proposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','KẾT NỐI','branding','2026-11-21 05:28:13','2026-11-22 08:33:20'),(2,'brand_name','KẾT NỐI','branding','2026-11-21 05:28:13','2026-11-22 08:33:20'),(5,'site_favicon','uploads/theme/favicon_1795359396_mtbqgp.svg','branding','2026-11-21 05:28:13','2026-11-22 07:56:36'),(6,'theme_mode','dark','appearance','2026-11-21 05:28:13','2026-11-22 19:30:05'),(7,'theme_preset','default','appearance','2026-11-21 05:28:13','2026-11-22 08:18:15'),(8,'primary_color','#2563eb','appearance','2026-11-21 05:28:13','2026-11-22 08:20:06'),(9,'accent_color','#d97706','appearance','2026-11-21 05:28:13','2026-11-21 05:28:13'),(10,'bg_color','#f8fafc','appearance','2026-11-21 05:28:13','2026-11-21 05:28:13'),(11,'card_bg_color','#ffffff','appearance','2026-11-21 05:28:13','2026-11-21 05:28:13'),(12,'border_radius','default','appearance','2026-11-21 05:28:13','2026-11-21 06:34:32'),(13,'cover_desktop','uploads/theme/cover_desk_1795357981_hW7gjz.png','cover','2026-11-21 05:28:13','2026-11-22 08:20:20'),(14,'cover_mobile','assets/images/cover-night-skyline.jpg','cover','2026-11-21 05:28:13','2026-11-22 10:22:49'),(15,'cover_height_desktop','280px','cover','2026-11-21 05:28:13','2026-11-22 07:56:44'),(16,'cover_height_mobile','220px','cover','2026-11-21 05:28:13','2026-11-21 06:34:32'),(19,'blog_title','Blogggggg','content','2026-11-21 05:28:13','2026-11-22 07:36:39'),(20,'blog_subtitle','Cùng trao đổi góc nhìn chiến lược, cập nhật thị trường và chia sẻ kiến thức quản trị.','content','2026-11-21 05:28:13','2026-11-21 05:28:13'),(21,'footer_about','KẾT NỐI - Nền tảng chia sẻ thông tin, thảo luận chuyên sâu về quản trị doanh nghiệp, đầu tư tài chính và phát triển bền vững.','content','2026-11-21 05:28:13','2026-11-21 09:23:02'),(22,'contact_hotline','1900 6868','contact','2026-11-21 05:28:13','2026-11-21 09:23:02'),(23,'contact_email','banbientap@ketnoi.vnnnn','contact','2026-11-21 05:28:13','2026-11-22 08:52:47'),(24,'contact_address','Tầng 118, Toà tháp Doanh nhân, Quận 1, TP. Hồ Chí Minh','contact','2026-11-21 05:28:13','2026-11-22 07:43:56'),(25,'copyright_text','© 2026 KẾT NỐI Business Community. Tất cả quyền được bảo lưu.','contact','2026-11-21 05:28:13','2026-11-21 09:23:02'),(26,'social_facebook','','social','2026-11-21 05:28:13','2026-11-22 07:55:08'),(27,'social_linkedin','','social','2026-11-21 05:28:13','2026-11-22 07:55:08'),(28,'social_youtube','https://youtube.com','social','2026-11-21 05:28:13','2026-11-21 05:28:13'),(29,'social_telegram','','social','2026-11-21 05:28:13','2026-11-22 07:55:08'),(30,'cover_desktop_history','[\"assets\\/images\\/cover-banner.jpg\",\"uploads\\/theme\\/cover_desk_1795358083_LmTQRV.png\",\"uploads\\/theme\\/cover_desk_1795357981_hW7gjz.png\",\"assets\\/images\\/cover-boardroom.jpg\",\"assets\\/images\\/cover-night-skyline.jpg\",\"assets\\/images\\/post-tech-ai.jpg\"]','cover','2026-11-22 07:33:01','2026-11-22 08:20:20'),(31,'social_links','[\"https:\\/\\/youtube.com\",\"https:\\/\\/vnexpress.net\\/\",\"https:\\/\\/gmail.com\\/\",\"https:\\/\\/chatgpt.com\\/\",\"https:\\/\\/www.cloudflare.com\\/\"]','social','2026-11-22 07:55:08','2026-11-22 08:51:36'),(32,'cover_mobile_history','[\"assets\\/images\\/cover-banner.jpg\",\"uploads\\/theme\\/cover_mob_1795278888_cSqAvG.png\",\"assets\\/images\\/cover-night-skyline.jpg\",\"assets\\/images\\/cover-boardroom.jpg\",\"assets\\/images\\/post-tech-ai.jpg\"]','cover','2026-11-22 07:57:18','2026-11-22 10:22:49');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT 'assets/images/admin-khanh.jpg',
  `avatar_history` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT '0900 123 456',
  `role_title` varchar(255) DEFAULT 'Founder & Ban Quản trị',
  `bio` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Bùi Viết Khánh','adminnnnn@gmail.com','uploads/avatars/avatar_1795272448_Y1XGAm.jpg','[\"uploads\\/avatars\\/avatar_1795272433_4nTcAT.png\",\"uploads\\/avatars\\/avatar_1795270943_47LVo9.png\",\"uploads\\/avatars\\/avatar_1795272448_Y1XGAm.jpg\",\"assets\\/images\\/avatar-admin-2.jpg\",\"assets\\/images\\/avatar-admin-3.jpg\"]','123456789','Founder','Test',NULL,'$2y$12$cwT2fdkzpr0..Mi8wtPO6OKZqk1kz9l.yihPQ8IgrrdtEkEIq6S36','8e08TZcVw00rZB5VGfH2YmhNWBDCdAIjzWshjeObwKWa4yvFeDmr6eJjEG0a','2026-11-21 03:21:30','2026-11-22 09:36:12');
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

-- Dump completed on 2026-11-23  9:31:20
