-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.31 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for gsb_alumni_new_db
CREATE DATABASE IF NOT EXISTS `gsb_alumni_new_db` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gsb_alumni_new_db`;

-- Dumping structure for table gsb_alumni_new_db.blog
CREATE TABLE IF NOT EXISTS `blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET utf8mb4 DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(150) CHARACTER SET utf8mb4 DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `blog_created_by` (`created_by`),
  KEY `blog_updated_by` (`updated_by`),
  KEY `blog_category_id` (`category_id`),
  CONSTRAINT `blog_category_id` FOREIGN KEY (`category_id`) REFERENCES `blog_category` (`category_id`),
  CONSTRAINT `blog_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `blog_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Dumping data for table gsb_alumni_new_db.blog: ~15 rows (approximately)
DELETE FROM `blog`;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
INSERT INTO `blog` (`blog_id`, `title`, `description`, `category_id`, `image`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	

-- Dumping structure for table gsb_alumni_new_db.blog_category
CREATE TABLE IF NOT EXISTS `blog_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `blog_category_created_by` (`created_by`),
  KEY `blog_category_updated_by` (`updated_by`),
  CONSTRAINT `blog_category_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `blog_category_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table gsb_alumni_new_db.blog_category: ~2 rows (approximately)
DELETE FROM `blog_category`;
/*!40000 ALTER TABLE `blog_category` DISABLE KEYS */;
INSERT INTO `blog_category` (`category_id`, `category_name`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(1, 'Test', 1, 1, NULL, NULL, NULL),
	(2, 'New Test', 1, 1, NULL, NULL, NULL);
/*!40000 ALTER TABLE `blog_category` ENABLE KEYS */;

-- Dumping structure for table gsb_alumni_new_db.blog_comment
CREATE TABLE IF NOT EXISTS `blog_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) DEFAULT NULL,
  `reply_id` int(11) DEFAULT '0',
  `message` longtext,
  `status` tinyint(1) DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `blog_comment_created_by` (`created_by`),
  KEY `blog_comment_updated_by` (`updated_by`),
  KEY `vlog_comments_blog_id` (`blog_id`),
  CONSTRAINT `blog_comment_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `blog_comment_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `vlog_comments_blog_id` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- Dumping data for table gsb_alumni_new_db.blog_comment: ~23 rows (approximately)
DELETE FROM `blog_comment`;
/*!40000 ALTER TABLE `blog_comment` DISABLE KEYS */;
INSERT INTO `blog_comment` (`comment_id`, `blog_id`, `reply_id`, `message`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
	(2, 1, 0, 'Test', 1, 1, '2021-12-26 19:43:45', NULL, NULL),
	
	(25, 11, 23, 'child 1.1', 1, 29, '2021-12-28 03:02:39', NULL, NULL);
/*!40000 ALTER TABLE `blog_comment` ENABLE KEYS */;

-- Dumping structure for table gsb_alumni_new_db.contact_us
CREATE TABLE IF NOT EXISTS `contact_us` (
  `name` varchar(55) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` longtext,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table gsb_alumni_new_db.contact_us: 2 rows
DELETE FROM `contact_us`;
/*!40000 ALTER TABLE `contact_us` DISABLE KEYS */;
INSERT INTO `contact_us` (`name`, `subject`, `message`, `updated_by`, `updated_at`) VALUES
	('Rasiq', 'Test Feedback', 'Hi, This is First Test Data', 'rasiq.hameed@krea.edu.in', '2021-09-30 15:09:00'),
	('Rasiq', 'test email 4', 'testing kjb,lhb5689', 'rasiq.hameed@krea.edu.in', '2021-12-28 13:03:47');
/*!40000 ALTER TABLE `contact_us` ENABLE KEYS */;

-- Dumping structure for table gsb_alumni_new_db.job_requests
CREATE TABLE IF NOT EXISTS `job_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `job_date` date DEFAULT NULL,
  `job_mode` varchar(50) DEFAULT NULL,
  `job_description` longtext,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_no` bigint(20) DEFAULT NULL,
  `apply_link` varchar(500) DEFAULT NULL,
  `job_status` varchar(50) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `job_primary_link` varchar(50) DEFAULT NULL,
  `additional_details` longtext,
  `sub_title` varchar(100) DEFAULT NULL,
  `posted_by` varchar(100) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `approved_by` varchar(500) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `webiste_link` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

-- Dumping data for table gsb_alumni_new_db.job_requests: 47 rows
DELETE FROM `job_requests`;
/*!40000 ALTER TABLE `job_requests` DISABLE KEYS */;
INSERT INTO `job_requests` (`id`, `company_name`, `title`, `location`, `job_date`, `job_mode`, `job_description`, `contact_email`, `contact_person`, `contact_no`, `apply_link`, `job_status`, `expiry_date`, `job_primary_link`, `additional_details`, `sub_title`, `posted_by`, `posted_at`, `approved_by`, `approved_at`, `webiste_link`) VALUES
	(1, 'Krea University', 'Senior Project Manager', 'Sricity', '2021-09-14', 'Full time', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui, delectus totam non est excepturi expedita, illum vitae vel dolore exercitationem nobis quasi dicta illo id quas. Error commodi, modi minus.\n\nPerferendis, quidem, facilis. Aspernatur alias numquam saepe deleniti dolorem quos repudiandae eaque ad eligendi quam, ratione, error minima culpa suscipit nostrum magni omnis est. Suscipit dolor sint aut maiores eius, id nemo, optio, quos tempora cum est quas. At recusandae obcaecati consequatur ipsa dignissimos, eius commodi qui quae exercitationem fugiat, voluptatem, nesciunt!\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem voluptatem vero culpa rerum similique labore, nisi minus voluptatum numquam fugiat.\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit. Placeat fugit sint reiciendis quas temporibus quam maxime nulla vitae consectetur perferendis, fugiat assumenda ex dicta molestias soluta est quo totam cum?', 'careers@krea.edu.in', 'Hr team', 4424998199, 'https://krea.edu.in/careers/', 'deleted', '2021-09-25', '', 'Looking to improve the security at your place of business? If so, we will provide you with the trained security officers and professionally licensed personnel needed for any business. From a security guard for construction site security to private event security, you can be sure to get the very best from our staff. Alternatively we provide tailor-made security guard training for your existing security staff.\n\n\nLooking to improve the security at your place of business? If so, we will provide you with the trained security officers and professionally licensed personnel needed for any business. From a security guard for construction site security to private event security, you can be sure to get the very best from our staff. Alternatively we provide tailor-made security guard training for your existing security staff.', 'Environment Hub', 'rasiq.hameed@krea.edu.in', NULL, 'rasiq.hameed@krea.edu.in', '2021-10-20 12:55:04', NULL),
	(52, 'Krea', 'rest', 'Chennai', '2022-01-27', 'Full time', '&lt;h3&gt;Rise in popularity&lt;/h3&gt;\r\n\r\n&lt;p&gt;After a slow start, blogging rapidly gained in popularity. Blog usage spread during 1999 and the years following, being further popularized by the near-simultaneous arrival of the first hosted blog tools:&lt;/p&gt;\r\n\r\n&lt;ul&gt;\r\n	&lt;li&gt;&lt;a href=&quot;https://en.wikipedia.org/wiki/Bruce_Ableson&quot;&gt;Bruce Ableson&lt;/a&gt;&amp;nbsp;launched&amp;nbsp;&lt;a href=&quot;https://en.wikipedia.org/wiki/Open_Diary&quot;&gt;Open Diary&lt;/a&gt;&amp;nbsp;in October 1998, which soon grew to thousands of online diaries. Open Diary innovated the reader comment, becoming the first blog community where readers could add comments to other writers&amp;#39; blog entries.&lt;/li&gt;\r\n	&lt;li&gt;&lt;a href=&quot;https://en.wikipedia.org/wiki/Brad_Fitzpatrick&quot;&gt;Brad Fitzpatrick&lt;/a&gt;&amp;nbsp;started&amp;nbsp;&lt;a href=&quot;https://en.wikipedia.org/wiki/LiveJournal&quot;&gt;LiveJournal&lt;/a&gt;&amp;nbsp;in March 1999.&lt;/li&gt;\r\n	&lt;li&gt;Andrew Smales created Pitas.com in July 1999 as an easier alternative to maintaining a &amp;quot;news page&amp;quot; on a Web site, followed by DiaryLand in September 1999, focusing more on a personal diary community.&lt;a href=&quot;https://en.wikipedia.org/wiki/Blog#cite_note-22&quot;&gt;[22]&lt;/a&gt;&lt;/li&gt;\r\n	&lt;li&gt;&lt;a href=&quot;https://en.wikipedia.org/wiki/Evan_Williams_(Internet_entrepreneur)&quot;&gt;Evan Williams&lt;/a&gt;&amp;nbsp;and&amp;nbsp;&lt;a href=&quot;https://en.wikipedia.org/wiki/Meg_Hourihan&quot;&gt;Meg Hourihan&lt;/a&gt;&amp;nbsp;(&lt;a href=&quot;https://en.wikipedia.org/wiki/Pyra_Labs&quot;&gt;Pyra Labs&lt;/a&gt;) launched&amp;nbsp;&lt;a href=&quot;https://en.wikipedia.org/wiki/Blogger.com&quot;&gt;Blogger.com&lt;/a&gt;&amp;nbsp;in August 1999 (purchased by Google in February 2003)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n\r\n&lt;h3&gt;Political impact&lt;/h3&gt;', 'temp@gmail.com', 'Rasiq', 7852369140, 'zxvvzxvc.com', 'pending', '2022-03-11', '', '&lt;h3&gt;Rise in popularity&lt;/h3&gt;\r\n\r\n&lt;p&gt;After a slow start, blogging rapidly gained in popularity. Blog usage spread during 1999 and the years following, being further popularized by the near-simultaneous arrival of the first hosted blog tools:&lt;/p&gt;\r\n\r\n&lt;ul&gt;\r\n	&lt;li&gt;&lt;a href=&quot;https://en.wikipedia.org/wiki/Bruce_Ableson&quot;&gt;Bruce Ableson&lt;/a&gt;&amp;nbsp;launched&amp;nbsp;&lt;a href=&quot;https://en.wikipedia.org/wiki/Open_Diary&quot;&gt;Open Diary&lt;/a&gt;&amp;nbsp;in October 1998, which soon grew to thousands of online diaries. Open Diary innovated the reader comment, becoming the first blog community where readers could add comments to other writers&amp;#39; blog entries.&lt;/li&gt;\r\n	&lt;li&gt;&lt;a href=&quot;https://en.wikipedia.org/wiki/Brad_Fitzpatrick&quot;&gt;Brad Fitzpatrick&lt;/a&gt;&amp;nbsp;started&amp;nbsp;&lt;a href=&quot;https://en.wikipedia.org/wiki/LiveJournal&quot;&gt;LiveJournal&lt;/a&gt;&amp;nbsp;in March 1999.&lt;/li&gt;\r\n	&lt;li&gt;Andrew Smales created Pitas.com in July 1999 as an easier alternative to maintaining a &amp;quot;news page&amp;quot; on a Web site, followed by DiaryLand in September 1999, focusing more on a personal diary community.&lt;a href=&quot;https://en.wikipedia.org/wiki/Blog#cite_note-22&quot;&gt;[22]&lt;/a&gt;&lt;/li&gt;\r\n	&lt;li&gt;&lt;a href=&quot;https://en.wikipedia.org/wiki/Evan_Williams_(Internet_entrepreneur)&quot;&gt;Evan Williams&lt;/a&gt;&amp;nbsp;and&amp;nbsp;&lt;a href=&quot;https://en.wikipedia.org/wiki/Meg_Hourihan&quot;&gt;Meg Hourihan&lt;/a&gt;&amp;nbsp;(&lt;a href=&quot;https://en.wikipedia.org/wiki/Pyra_Labs&quot;&gt;Pyra Labs&lt;/a&gt;) launched&amp;nbsp;&lt;a href=&quot;https://en.wikipedia.org/wiki/Blogger.com&quot;&gt;Blogger.com&lt;/a&gt;&amp;nbsp;in August 1999 (purchased by Google in February 2003)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n\r\n&lt;h3&gt;Political impact&lt;/h3&gt;', '2022', 'rasiq.hameed@krea.edu.in', '2022-01-27 01:50:35', NULL, NULL, NULL);
/*!40000 ALTER TABLE `job_requests` ENABLE KEYS */;

-- Dumping structure for table gsb_alumni_new_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(500) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `user_role` varchar(50) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `current_organization` varchar(500) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `current_location` varchar(100) DEFAULT NULL,
  `contact_no` bigint(20) DEFAULT NULL,
  `secondary_email` varchar(100) DEFAULT NULL,
  `course_name` varchar(50) DEFAULT NULL,
  `stream` varchar(50) DEFAULT NULL,
  `batch_no` varchar(50) DEFAULT NULL,
  `user_status` varchar(50) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` varchar(200) DEFAULT NULL,
  `graduated_yr` int(10) DEFAULT NULL,
  `roll_number` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;

-- Dumping data for table gsb_alumni_new_db.users: ~62 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `user_name`, `email`, `password`, `user_role`, `last_login`, `created`, `date_of_birth`, `current_organization`, `designation`, `current_location`, `contact_no`, `secondary_email`, `course_name`, `stream`, `batch_no`, `user_status`, `approved_at`, `approved_by`, `graduated_yr`, `roll_number`) VALUES
	((68, 'borih37454@sueshaw.com', 'borih37454@sueshaw.com', '3f2d2e5f1da78edf75293389ede3c373', 'alumni', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PGDM (MBA) Full Time', NULL, NULL, 'active', NULL, NULL, 1987, 'borih37454@sueshaw.com');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
