-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: php_project
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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `cid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'category id',
  `cname` varchar(30) NOT NULL COMMENT 'category name',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'iphone'),(2,'ipad'),(3,'mac'),(4,'watch'),(5,'others');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contactus`
--

DROP TABLE IF EXISTS `contactus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactus`
--

LOCK TABLES `contactus` WRITE;
/*!40000 ALTER TABLE `contactus` DISABLE KEYS */;
INSERT INTO `contactus` VALUES (1,'om','om@gmail.com','test','hi this is the first test i hope all goes well in the testing','2025-09-01 11:21:31',3);
/*!40000 ALTER TABLE `contactus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `pid` (`pid`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,7,6,2,120000.00),(2,7,5,1,80000.00),(3,8,3,1,55000.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(30) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`),
  KEY `uid` (`uid`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (7,3,'om','om@gmail.com','12,hiranagar,varchha,surat','9958812756',320000.00,'pending','2025-08-29 17:50:18'),(8,3,'jay','jay@gmail.com','jnjjbhdnijvdjv','9958812756',55000.00,'cancelled','2025-09-05 16:45:36');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `uid` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`uid`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (3,'734f4707ef2d8467df1d10a49392e7093167993ab57fc0b54f1d7b91acef6f07','2025-09-09 17:26:23');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `pname` varchar(250) NOT NULL,
  `rating` float DEFAULT 0,
  `how_many_bought` int(11) DEFAULT 0,
  `price` float NOT NULL,
  `EMI_avail` varchar(5) NOT NULL DEFAULT 'yes',
  `image` varchar(255) NOT NULL,
  `category` varchar(30) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (2,'Apple iPhone 13 (128GB) - Starlight',3.6,200,46500,'Yes','uploads/1756381389_iphone1.jpg','iphone','2025-08-28 11:43:09'),(3,'Apple iPhone 14 (128 GB) - Midnight',4.4,400,55000,'Yes','uploads/1756392710_iphone2.jpg','iphone','2025-08-28 14:51:50'),(4,'Apple iPhone 16 (128GB) - Teal',4.7,300,80000,'Yes','uploads/1756392746_iphone3.jpg','iphone','2025-08-28 14:52:26'),(5,'Apple iPhone 15 (256GB) - Pink',3.9,300,80000,'Yes','uploads/1756392770_iphone4.jpg','iphone','2025-08-28 14:52:50'),(6,'Apple iPhone 16 pro (128GB) - Natural Titanium',5,300,120000,'Yes','uploads/1756392792_iphone5.jpg','iphone','2025-08-28 14:53:12'),(7,'Apple 2024 MacBook Air (13-inch, Apple M3 chip with 8‑core CPU and 8‑core GPU, 16GB Unified Memory, 256GB) - Midnight',4.6,500,110000,'Yes','uploads/1756392851_mac1.jpg','mac','2025-08-28 14:54:11'),(8,'Apple MacBook Air Laptop: Apple M1 chip, 13.3-inch/33.74 cm Retina Display, 8GB RAM, 256GB SSD Storage, Backlit Keyboard, FaceTime HD Camera, Touch ID. Works with iPhone/iPad; Space Grey',4.4,300,68000,'Yes','uploads/1756392882_mac2.jpg','mac','2025-08-28 14:54:42'),(9,'2022 Apple MacBook Air Laptop with M2 chip: 13.6-inch Liquid Retina Display, 16GB RAM, 256GB SSD Storage, Backlit Keyboard, 1080p FaceTime HD Camera. Works with iPhone and iPad; Silver',4.7,600,94990,'Yes','uploads/1756392907_mac3.jpg','mac','2025-08-28 14:55:07'),(10,'Apple 2024 MacBook Pro Laptop with M4 Pro chip with 12‑core CPU and 16‑core GPU: Built for Apple Intelligence, (14.2″) Liquid Retina XDR Display, 24GB Unified Memory, 512GB SSD Storage; Space Black',3.9,300,185990,'Yes','uploads/1756392933_mac4.jpg','mac','2025-08-28 14:55:33'),(11,'Apple 2024 MacBook Air (13-inch, Apple M3 chip with 8-core CPU and 10‑core GPU, 24GB Unified Memory, 512GB) - Space Gray',3.1,200,145990,'Yes','uploads/1756392957_mac5.jpg','mac','2025-08-28 14:55:57'),(12,'Apple iPad (10th Generation): with A14 Bionic chip, 27.69 cm (10.9″) Liquid Retina Display, 64GB, Wi-Fi 6, 12MP front/12MP Back Camera, Touch ID, All-Day Battery Life – Blue',4.6,500,40000,'Yes','uploads/1756393016_ipad1.jpg','ipad','2025-08-28 14:56:56'),(13,'Apple iPad Air 11″ (M2): Liquid Retina Display, 128GB, Landscape 12MP Front Camera / 12MP Back Camera, Wi-Fi 6E, Touch ID, All-Day Battery Life — Space Grey',4.4,300,54999,'Yes','uploads/1756393038_ipad2.jpg','ipad','2025-08-28 14:57:18'),(14,'Apple iPad Pro 13″ (M4): Ultra Retina XDR Display, 256GB, Landscape 12MP Front Camera / 12MP Back Camera, LiDAR Scanner, Wi-Fi 6E, Face ID, All-Day Battery Life, Standard Glass — Space Black',4.7,600,125000,'Yes','uploads/1756393060_ipad3.jpg','ipad','2025-08-28 14:57:40'),(15,'Apple iPad Mini (A17 Pro): Apple Intelligence, 21.08 cm (8.3″) Liquid Retina Display, 128GB, Wi-Fi 6E, 12MP Front/12MP Back Camera, Touch ID, All-Day Battery Life — Blue',3.9,300,49999,'Yes','uploads/1756393083_ipad4.jpg','ipad','2025-08-28 14:58:03'),(16,'Apple Watch Series 10 [GPS 46 mm] Smartwatch with Jet Black Aluminium Case with Black Sport Band - S/M. Fitness Tracker, ECG App, Always-On Retina Display, Water Resistant',4.6,500,49990,'Yes','uploads/1756393122_watch1.jpg','watch','2025-08-28 14:58:42'),(17,'Apple Watch SE (2nd Gen, 2023) [GPS 40mm] Smartwatch with Silver Aluminum Case with Blue Cloud Sport Loop. Fitness & Sleep Tracker, Crash Detection, Heart Rate Monitor, Retina Display',4.4,300,25000,'Yes','uploads/1756393146_watch2.jpg','watch','2025-08-28 14:59:06'),(18,'Apple Watch Series 9 [GPS + Cellular 45mm]Smartwatch with (PRODUCT)RED Aluminum Case with (PRODUCT)RED Sport Band S/M. Fitness Tracker,Blood Oxygen & ECG Apps,Always-On Retina Display,Water Resistant',4.7,600,47999,'Yes','uploads/1756393167_watch3.jpg','watch','2025-08-28 14:59:27'),(19,'Apple Watch Series 10 [GPS + Cellular 46 mm] Smartwatch with Jet Black Aluminium Case with Ink Sport Loop. Fitness Tracker, ECG App, Always-On Retina Display, Carbon Neutral',3.9,300,59990,'Yes','uploads/1756393193_watch4.jpg','watch','2025-08-28 14:59:53'),(20,'Apple Watch Ultra 2 [GPS + Cellular 49 mm] Smartwatch, Sports Watch with Natural Titanium Case with Natural Titanium Milanese Loop - M. Fitness Tracker, Precision GPS, Action Button, Carbon Neutral',3.1,200,105999,'Yes','uploads/1756393229_watch5.jpg','watch','2025-08-28 15:00:29'),(21,'2022 Apple TV 4K Wi-Fi with 64GB Storage (3rd Generation)',4.6,500,14999,'Yes','uploads/1756393259_tv1.jpg','others','2025-08-28 15:00:59'),(22,'Foso Wall Mount for Apple Mini Pod, Alexa Echo Dot 3rd, 4th Gen with & Without LED Clock, Google Nest Mini Speaker Stand Holder for SmartSpeakers, Built-in Cable Management (Speaker not Included)',4.4,300,499,'Yes','uploads/1756393283_tv2.jpg','others','2025-08-28 15:01:23'),(23,'Apple AirPods Pro (2nd Generation) with MagSafe Case (USB-C) (White)',4.7,600,22999,'Yes','uploads/1756393307_tv3.jpg','others','2025-08-28 15:01:47'),(24,'Apple Lightning to USB Camera Adapter',3.9,300,2999,'Yes','uploads/1756393333_tv4.jpg','others','2025-08-28 15:02:13'),(25,'JioTag Air for iOS|Apple Find My Network Item Finder| Worldwide Tracking for Keys, Wallets, Luggage, Pets, Gadgets and More|1+1 Year Battery| No SIM Needed|120db Sound| BT 5.3',3.1,200,1499,'Yes','uploads/1756393355_tv5.jpg','others','2025-08-28 15:02:35');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(200) NOT NULL,
  `lname` varchar(200) NOT NULL,
  `uname` varchar(200) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'raj','patel','raj','raj@gamil.com','$2y$10$YasbEo8DdOmduG8P93CnzeQx/7q3FaU9EytYMexYAbDz9n9bQINzi','2025-08-28 10:12:12'),(4,'piyush','pandit','admin','admin@gmail.com','$2y$10$cFnJy9Jbc85qiG6/frDCyegyYtRt40DDBTD.ggIfqyHuamA0quC5i','2025-08-28 10:28:27'),(5,'jay','patel','jay','jay@gmail.com','$2y$10$1LQzs3jaAbNrB0uuk4GTLel1jUX2owkN5W9eNzXXX1cWCsX5tZkoO','2025-08-28 10:47:46');
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

-- Dump completed on 2025-09-26 16:28:36
