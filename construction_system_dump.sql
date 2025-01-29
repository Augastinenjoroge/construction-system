/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.6.2-MariaDB, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: construction_system
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `Admin`
--

DROP TABLE IF EXISTS `Admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `admin_username` varchar(50) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_phone_number` varchar(15) DEFAULT NULL,
  `admin_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_username` (`admin_username`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Admin`
--

LOCK TABLES `Admin` WRITE;
/*!40000 ALTER TABLE `Admin` DISABLE KEYS */;
INSERT INTO `Admin` VALUES
(1,13,'ubnt','ubnt@gmail.com','0712345678','2024-12-03 21:40:22'),
(2,14,'admin','admin@gmail.com','0712345678','2024-12-03 21:54:32');
/*!40000 ALTER TABLE `Admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Applications`
--

DROP TABLE IF EXISTS `Applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Applications` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `worker_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `application_status` varchar(50) DEFAULT 'pending',
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`application_id`),
  KEY `worker_id` (`worker_id`),
  KEY `job_id` (`job_id`),
  CONSTRAINT `Applications_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `Worker` (`worker_id`) ON DELETE CASCADE,
  CONSTRAINT `Applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `Jobs` (`job_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Applications`
--

LOCK TABLES `Applications` WRITE;
/*!40000 ALTER TABLE `Applications` DISABLE KEYS */;
INSERT INTO `Applications` VALUES
(2,1,2,'approved','2024-11-22 09:43:07'),
(3,2,3,'pending','2024-12-01 07:23:05'),
(4,1,3,'approved','2024-12-01 08:41:05'),
(5,1,1,'pending','2024-12-01 08:56:21');
/*!40000 ALTER TABLE `Applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Company`
--

DROP TABLE IF EXISTS `Company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_email` varchar(100) NOT NULL,
  `company_phone_number` varchar(15) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `company_profile` varchar(255) DEFAULT 'images/construction.jpeg',
  `company_description` text DEFAULT NULL,
  PRIMARY KEY (`company_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Company_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Company`
--

LOCK TABLES `Company` WRITE;
/*!40000 ALTER TABLE `Company` DISABLE KEYS */;
INSERT INTO `Company` VALUES
(1,1,'Aga Khan University Hospital','nakleagusto@gmail.com','0745688031','Nairobi','2024-10-21 09:33:56','images/construction.jpeg',NULL),
(2,9,'Aga Khan University','nakle@gmail.com','0745688031','Kisumu','2024-10-28 11:41:35','images/construction.jpeg',NULL),
(3,10,'Del Monte Kenya','delmontekenya@gmail.com','0712213443','Thika','2024-11-10 07:16:41','images/profiles/1731232709_delmotekenya.jpg','<p><strong>Del Monte Kenya Limited</strong>&nbsp;is a Kenyan&nbsp;<a href=\"https://en.wikipedia.org/wiki/Food_processing\">food processing</a>&nbsp;company that operates in the&nbsp;<a href=\"https://en.wikipedia.org/wiki/Horticulture\">cultivation</a>,&nbsp;<a href=\"https://en.wikipedia.org/wiki/Food_industry\">production</a>, and&nbsp;<a href=\"https://en.wikipedia.org/wiki/Canning\">canning</a>&nbsp;of&nbsp;<a href=\"https://en.wikipedia.org/wiki/Pineapple\">pineapple</a>&nbsp;products.<a href=\"https://en.wikipedia.org/wiki/Del_Monte_Kenya#cite_note-Fox-Liebenthal-3\">[3]</a><a href=\"https://en.wikipedia.org/wiki/Del_Monte_Kenya#cite_note-ExportingAfrica-4\">[4]</a>&nbsp;Del Monte Kenya Limited, a wholly owned subsidiary of&nbsp;<a href=\"https://freshdelmonte.com/\">Fresh Del Monte Produce Inc.</a>, is a leading producer, marketer, and distributor of high-quality fresh fruit and prepared food in Europe, Africa, and the Middle East.</p>\r\n\r\n<p>The company produces canned solid pineapple,&nbsp;<a href=\"https://en.wikipedia.org/wiki/Juice_concentrate\">juice concentrates</a>,&nbsp;<a href=\"https://en.wikipedia.org/wiki/Fructose\">mill juice sugar</a>,&nbsp;and cattle&nbsp;<a href=\"https://en.wikipedia.org/wiki/Fodder\">feed</a>. Kenya&#39;s largest single-manufactured export is canned pineapple, and the country ranks among the top five pineapple exporters in the world, both of which feats are direct results of the company&#39;s existence and operations. Del Monte Kenya is the single largest exporter of Kenyan products, moving 5,000 containers per annum through the Mombasa port.<a href=\"https://en.wikipedia.org/wiki/Del_Monte_Kenya#cite_note-5\">[5]</a>&nbsp;&nbsp;</p>\r\n');
/*!40000 ALTER TABLE `Company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Jobs`
--

DROP TABLE IF EXISTS `Jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Jobs` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `job_description` text NOT NULL,
  `job_location` varchar(255) DEFAULT NULL,
  `job_salary` decimal(10,2) DEFAULT NULL,
  `job_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `job_status` varchar(20) DEFAULT 'available',
  PRIMARY KEY (`job_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `Jobs_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `Company` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Jobs`
--

LOCK TABLES `Jobs` WRITE;
/*!40000 ALTER TABLE `Jobs` DISABLE KEYS */;
INSERT INTO `Jobs` VALUES
(1,1,'Building and dismantling:','<p>Erecting and taking down barricades, scaffolding, and other structures.</p>\r\n','limuru center',100.00,'2024-11-06 08:34:49','available'),
(2,3,'Geotechnical Engineers:','<p><strong>Del Monte Kenya</strong></p>\r\n\r\n<p><strong>Job Description:</strong></p>\r\n\r\n<p>Del Monte Kenya is seeking a highly skilled Geotechnical Engineer to join our dynamic team. The ideal candidate will possess a strong understanding of soil mechanics and rock mechanics, along with extensive experience in analyzing subsurface conditions.</p>\r\n\r\n<p><strong>Key Responsibilities:</strong></p>\r\n\r\n<ul>\r\n	<li>Conduct comprehensive site investigations, including soil sampling, laboratory testing, and field inspections.</li>\r\n	<li>Analyze soil and rock properties to determine their suitability for various construction activities. &nbsp;</li>\r\n	<li>Evaluate potential geotechnical risks and hazards, such as landslides, subsidence, and erosion. &nbsp;</li>\r\n	<li>Design and implement appropriate foundation systems and ground improvement techniques.</li>\r\n	<li>Prepare detailed geotechnical reports and engineering drawings.</li>\r\n	<li>Collaborate with other engineering disciplines to ensure seamless project execution.</li>\r\n	<li>Monitor construction activities to verify compliance with geotechnical design specifications.</li>\r\n	<li>Stay updated on the latest advancements in geotechnical engineering practices.</li>\r\n</ul>\r\n\r\n<p><strong>Qualifications and Experience:</strong></p>\r\n\r\n<ul>\r\n	<li>Bachelor&#39;s degree in Civil Engineering with a specialization in Geotechnical Engineering.</li>\r\n	<li>Master&#39;s degree in Geotechnical Engineering preferred.</li>\r\n	<li>Proven experience in geotechnical engineering, preferably in the agricultural or construction industry.</li>\r\n	<li>Strong knowledge of soil mechanics, rock mechanics, and foundation engineering principles.</li>\r\n	<li>Proficiency in geotechnical software (e.g., Plaxis, Slope/W) and design tools. &nbsp;</li>\r\n	<li>Excellent analytical and problem-solving skills.</li>\r\n	<li>Strong communication and interpersonal skills.</li>\r\n	<li>Ability to work independently and as part of a team. &nbsp;</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n','Thika',250.00,'2024-11-10 07:25:06','completed'),
(3,3,'Identity and Access Management - IAM Analyst','<p>&nbsp;</p>\r\n\r\n<p><strong>ROLE SUMMARY</strong></p>\r\n\r\n<p>Our client is a technology-focused financial services organization dedicated to harnessing technological innovations that socially and economically empower consumers, businesses, enterprises, and communities.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>As an <strong>Identity and Access Management (IAM) Analyst</strong>, you will support implementing and operationalizing an IAM framework using SailPoint. The role involves project coordination, management reporting, and assisting in the technical implementation of requirements across scoped systems. The ideal candidate will possess strong organizational and analytical skills and be capable of bridging technical and business requirements for seamless IAM adoption.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>KEY RESPONSIBILITIES</strong></p>\r\n\r\n<p><strong>Framework Implementation Support</strong></p>\r\n\r\n<ul>\r\n	<li>Assist in deploying and operationalizing the SailPoint IAM framework for various applications.</li>\r\n	<li>Provide support to the technical teams in implementing IAM requirements on scoped systems.</li>\r\n</ul>\r\n\r\n<p><strong>Project Coordination</strong></p>\r\n\r\n<ul>\r\n	<li>Work closely with the Global Information Security IAM team and the Project Management Office to track and coordinate project progress.</li>\r\n	<li>Facilitate lower-level stakeholder engagements and ensure tasks align with the broader project goals.</li>\r\n</ul>\r\n\r\n<p><strong>Management Reporting</strong></p>\r\n\r\n<ul>\r\n	<li>Support the development of management reporting requirements.</li>\r\n	<li>Generate regular reports on IAM framework progress, compliance, and adoption metrics.</li>\r\n</ul>\r\n\r\n<p><strong>Requirement Management</strong></p>\r\n\r\n<ul>\r\n	<li>Collaborate with internal teams to gather and document additional requirements during the project lifecycle.</li>\r\n	<li>Ensure that additional requirements are integrated effectively into the IAM framework.</li>\r\n</ul>\r\n\r\n<p><strong>Documentation and Knowledge Sharing</strong></p>\r\n\r\n<ul>\r\n	<li>Maintain and update project documentation to reflect progress and decisions.</li>\r\n	<li>Assist in knowledge transfer to technical and non-technical teams to ensure smooth post-implementation operations.</li>\r\n</ul>\r\n\r\n<p><strong>KEY QUALIFICATIONS</strong></p>\r\n\r\n<p><strong>Experience: </strong>Minimum of 3-5 years in identity and access management with hands-on experience with SailPoint or similar IAM platforms.</p>\r\n\r\n<p><strong>Technical Skills:</strong></p>\r\n\r\n<ul>\r\n	<li>Strong understanding of IAM frameworks, user provisioning, and access governance.</li>\r\n	<li>Familiarity with enterprise IAM implementation and management.</li>\r\n	<li>Basic knowledge of integrating IAM with directory services (e.g., Active Directory) and cloud environments (Microsoft Azure, AWS, etc.).</li>\r\n	<li>Ability to interpret IAM performance metrics and translate findings into actionable insights.</li>\r\n	<li>Knowledge of compliance standards such as GDPR, HIPAA, or PCI DSS.</li>\r\n</ul>\r\n\r\n<p><strong>Certifications: </strong>IAM or cybersecurity (e.g., SailPoint, CISSP, etc.) are a plus.</p>\r\n\r\n<p><strong>Communication Skills: </strong>Ability to communicate effectively with technical and non-technical stakeholders.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>ADDITIONAL INFORMATION</strong></p>\r\n\r\n<p><strong>Employment Type: </strong>Contract</p>\r\n\r\n<p><strong>Job Function: </strong>IAM Analyst</p>\r\n\r\n<p><strong>Duration: </strong>12 Months</p>\r\n\r\n<p><strong>Posting Date: </strong>29th November 2024</p>\r\n\r\n<p><strong>Seniority Level: </strong>Senior-level; 3+ years</p>\r\n\r\n<p><strong>Validity: </strong>1 month</p>\r\n\r\n<p><strong>Industry: </strong>Financial Services</p>\r\n\r\n<p><strong>Expected Start Date: </strong>Immediate - 30 days</p>\r\n\r\n<p><strong>Location: </strong>On-site and\\or Hybrid</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>GOODINFO</strong> is a Technology Services firm. Our mission is to &lsquo;help our clients build digital products <em>their </em>customers love&rsquo;. We offer <em>ideation, CX, UX/UI, architecture, engineering, staffing, outsourcing support </em>&amp; other <em>technology related services </em>to high-performance organizations globally.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Our goal is to &lsquo;<em>build and power digital products for more than 50% of global pedigree-tech-programs, within the next 10 years </em>&ndash; while helping 1 million+ Africans secure technology &amp; related jobs&rsquo;.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Join us today &ndash; and <em>help our customers deliver world class digital products to their users</em>.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>DISCLAIMER</strong></p>\r\n\r\n<ul>\r\n	<li>Communication will be with short-listed candidates only. If you do not receive feedback on your application within (3) three weeks, please consider it unsuccessful.</li>\r\n	<li>GOODINFO\\SHIPHT reserves the right not to proceed with an appointment for any advertised role.</li>\r\n	<li>All appointments will be made in line with GOODINFO&rsquo;S Employment Equity Plan and Policies.</li>\r\n	<li>All applications will be treated confidentially.</li>\r\n</ul>\r\n','Thika',1000.00,'2024-12-01 07:20:32','completed');
/*!40000 ALTER TABLE `Jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Payments`
--

DROP TABLE IF EXISTS `Payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `worker_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `company_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `payment_status` varchar(50) NOT NULL DEFAULT 'Payment Processed',
  PRIMARY KEY (`payment_id`),
  KEY `worker_id` (`worker_id`),
  KEY `job_id` (`job_id`),
  KEY `fk_payments_company` (`company_id`),
  KEY `fk_payments_application` (`application_id`),
  CONSTRAINT `Payments_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `Worker` (`worker_id`) ON DELETE CASCADE,
  CONSTRAINT `Payments_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `Jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_payments_application` FOREIGN KEY (`application_id`) REFERENCES `Applications` (`application_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_payments_company` FOREIGN KEY (`company_id`) REFERENCES `Company` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Payments`
--

LOCK TABLES `Payments` WRITE;
/*!40000 ALTER TABLE `Payments` DISABLE KEYS */;
INSERT INTO `Payments` VALUES
(3,1,2,367.00,'2024-11-27 00:43:31',3,2,'Payment Processed');
/*!40000 ALTER TABLE `Payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Reviews`
--

DROP TABLE IF EXISTS `Reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `worker_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`review_id`),
  KEY `worker_id` (`worker_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `Reviews_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `Worker` (`worker_id`) ON DELETE CASCADE,
  CONSTRAINT `Reviews_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `Company` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Reviews`
--

LOCK TABLES `Reviews` WRITE;
/*!40000 ALTER TABLE `Reviews` DISABLE KEYS */;
INSERT INTO `Reviews` VALUES
(7,1,1,'nice to work with them they pay on time',5,'2024-12-02 09:21:52');
/*!40000 ALTER TABLE `Reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Roles`
--

DROP TABLE IF EXISTS `User_Roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User_Roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Roles`
--

LOCK TABLES `User_Roles` WRITE;
/*!40000 ALTER TABLE `User_Roles` DISABLE KEYS */;
INSERT INTO `User_Roles` VALUES
(1,'Admin'),
(3,'Company'),
(2,'Worker');
/*!40000 ALTER TABLE `User_Roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `User_Roles` (`role_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES

/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Worker`
--

DROP TABLE IF EXISTS `Worker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Worker` (
  `worker_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `worker_username` varchar(50) NOT NULL,
  `worker_email` varchar(100) NOT NULL,
  `worker_phone_number` varchar(15) DEFAULT NULL,
  `worker_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `worker_address` tinytext DEFAULT NULL,
  `worker_description` text DEFAULT NULL,
  PRIMARY KEY (`worker_id`),
  UNIQUE KEY `worker_username` (`worker_username`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Worker_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Worker`
--

LOCK TABLES `Worker` WRITE;
/*!40000 ALTER TABLE `Worker` DISABLE KEYS */;
INSERT INTO `Worker` VALUES

/*!40000 ALTER TABLE `Worker` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2024-12-05  0:27:30
