-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 05:21 PM
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
-- Database: `php_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cid` int(11) NOT NULL COMMENT 'category id',
  `cname` varchar(30) NOT NULL COMMENT 'category name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cid`, `cname`) VALUES
(1, 'iphone'),
(2, 'ipad'),
(3, 'mac'),
(4, 'watch'),
(5, 'others');

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `uid`) VALUES
(1, 'om', 'om@gmail.com', 'test', 'hi this is the first test i hope all goes well in the testing', '2025-09-01 11:21:31', 3),
(2, 'jay', 'jay@gmail.com', 'test', 'hi this is another test message.', '2025-11-12 14:25:27', 0),
(3, 'jay', 'jay@gmail.com', 'test', 'hi this is another test message.', '2025-11-12 14:26:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(30) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `uid` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pid` int(11) NOT NULL,
  `pname` varchar(250) NOT NULL,
  `rating` float DEFAULT 0,
  `how_many_bought` int(11) DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `EMI_avail` varchar(5) NOT NULL DEFAULT 'yes',
  `image` varchar(255) NOT NULL,
  `category` varchar(30) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pid`, `pname`, `rating`, `how_many_bought`, `price`, `description`, `EMI_avail`, `image`, `category`, `added_date`, `is_active`) VALUES
(2, 'Apple iPhone 13 (128GB) - Starlight', 0, 0, 46500.00, '-128 GB Internal Memory\r\n-15.40 cm (6.1 inch) Super Retina XDR Display\r\n-12 MP Main, 12 MP Ultra Wide camera\r\n-Portrait mode with Focus and Depth Control\r\n-12 MP Front Camera\r\n-A15 Bionic chip\r\n-MagSafe, Qi2 and Qi wireless charging\r\n-Face ID', 'Yes', 'uploads/1756381389_iphone1.jpg', 'iphone', '2025-08-28 11:43:09', 1),
(3, 'Apple iPhone 14 (128 GB) - Midnight', 0, 0, 48000.00, '128 GB Internal Memory\r\n15.40 cm (6.1 inch) Super Retina XDR Display\r\n12 MP Main, 12 MP Ultra Wide camera\r\nPortrait mode with Focus and Depth Control\r\n12 MP Front Camera\r\nA15 Bionic chip\r\nMagSafe, Qi2 and Qi wireless charging\r\nCrash Detection, Face ID', 'Yes', 'uploads/1756392710_iphone2.jpg', 'iphone', '2025-08-28 14:51:50', 1),
(4, 'Apple iPhone 16 (128GB) - Teal', 0, 0, 66000.00, 'Battery Life\r\nUp to 22 hours video playback\r\nBattery Charging Technology\r\nWireless charging\r\nBattery Capacity\r\n3561 mAh\r\nRemovable Battery\r\nNo\r\nDisplay Size\r\n6.1 in\r\nTouch Screen\r\nYes\r\nDisplay Resolution\r\n2556 x 1179\r\nDisplay Pixel Density\r\n460 ppi', 'Yes', 'uploads/1756392746_iphone3.jpg', 'iphone', '2025-08-28 14:52:26', 1),
(5, 'Apple iPhone 15 (256GB) - blue', 0, 0, 750000.00, '128 GB Internal Memory\r\n17.00 cm (6.7 inch) Super Retina XDR Display, Dynamic Island\r\n48 MP Main, 12 MP Ultra Wide camera\r\nNext-generation portraits with Focus and Depth Control\r\n12 MP Front Camera\r\nA16 Bionic chip\r\nMagSafe, Qi2 and Qi wireless charging\r\nUSB C, Crash Detection, Face ID', 'Yes', 'uploads/1764773663_iphone4.jpg', 'iphone', '2025-08-28 14:52:50', 1),
(6, 'Apple iPhone 16 pro (256GB) - Natural Titanium', 0, 0, 120000.00, 'Storage: 256 GB Internal Memory\r\nDisplay: 15.93 cm (6.3 inch), 2622 x 1206 resolution, Super Retina XDR all‑screen OLED display\r\nRear Camera: 48 MP Fusion, 48 MP Ultra Wide, 12 MP 5x Telephoto Camera\r\nRear Camera Feature: Smart HDR 5, Sapphire Crystal Lens Cover, Next-generation portraits with Focus and Depth Control, Portrait Lighting with six effects, Auto Image Stabilisation\r\nFront Camera: 12 MP Camera, Autofocus with Focus Pixels\r\nProcessor: A18 Pro Chip\r\nBattery: Built-in Rechargeable Lithium-ion Battery, Qi Wireless Charging', 'Yes', 'uploads/1756392792_iphone5.jpg', 'iphone', '2025-08-28 14:53:12', 1),
(7, 'Apple 2024 MacBook Air (13-inch, Apple M3 chip with 8‑core CPU and 8‑core GPU, 16GB Unified Memory, 256GB) - Midnight', 0, 0, 110000.00, 'Processor: Apple M3\r\nDisplay: 34.46 cms (13.6 inches) Liquid Retina\r\nMemory: 8GB Unified Memory RAM, 256GB SSD ROM\r\nOS: macOS Sequoia\r\nBacklit Keyboard, FaceTime HD Camera, Spatial Audio\r\nWarranty: 1 Year Onsite', 'Yes', 'uploads/1756392851_mac1.jpg', 'mac', '2025-08-28 14:54:11', 1),
(8, 'Apple MacBook Air Laptop: Apple M1 chip, 13.3-inch/33.74 cm Retina Display, 8GB RAM, 256GB SSD Storage, Backlit Keyboard, FaceTime HD Camera, Touch ID. Works with iPhone/iPad; Space Grey', 0, 0, 78000.00, 'Processor: Apple M1\r\nDisplay: 33.78 cms (13.3 inches) LED-Backlit\r\nMemory: 8GB DDR4 RAM, 256GB SSD ROM\r\nOS: macOS Big Sur\r\nWarranty: 1 Year Onsite', 'Yes', 'uploads/1756392882_mac2.jpg', 'mac', '2025-08-28 14:54:42', 1),
(9, '2022 Apple MacBook Air Laptop with M2 chip: 13.6-inch Liquid Retina Display, 16GB RAM, 256GB SSD Storage, Backlit Keyboard, 1080p FaceTime HD Camera. Works with iPhone and iPad; Silver', 0, 0, 94990.00, 'this is the test.\r\n photo will be changed to a iphone.\r\nand coursol will be added. ', 'Yes', 'uploads/1764772509_mac3.jpg', 'mac', '2025-08-28 14:55:07', 1),
(10, 'Apple 2024 MacBook Pro Laptop with M4 Pro chip with 12‑core CPU and 16‑core GPU: Built for Apple Intelligence, (14.2″) Liquid Retina XDR Display, 24GB Unified Memory, 512GB SSD Storage; Space Black', 0, 0, 185990.00, 'Processor: Apple M4 Pro\r\nDisplay: 35.97 cms (14.2 inches), Liquid Retina XDR\r\nMemory: 24GB Unified Memory RAM, 512GB SSD ROM\r\nOS: macOS Sequoia\r\nGraphics: Apple\r\nBuilt for Apple Intelligence, Touch ID, Ambient Light Sensor\r\nWarranty: 1 Year Limited', 'Yes', 'uploads/1756392933_mac4.jpg', 'mac', '2025-08-28 14:55:33', 1),
(11, 'Apple 2024 MacBook Air (13-inch, Apple M3 chip with 8-core CPU and 10‑core GPU, 24GB Unified Memory, 512GB) - Space Gray', 0, 0, 145990.00, 'Apple M4 chip with 10-core CPU, 8-core GPU, 16‑core Neural Engine\r\n16GB unified memory\r\n256GB SSD storage\r\n34.46 cm (13.6″) Liquid Retina display with True Tone²\r\n12MP Center Stage camera\r\nMagSafe 3 charging port\r\nTwo Thunderbolt 4 ports\r\n30W USB-C Power Adapter', 'Yes', 'uploads/1756392957_mac5.jpg', 'mac', '2025-08-28 14:55:57', 1),
(12, 'Apple iPad (10th Generation): with A14 Bionic chip, 27.69 cm (10.9″) Liquid Retina Display, 64GB, Wi-Fi 6, 12MP front/12MP Back Camera, Touch ID, All-Day Battery Life – Blue', 0, 0, 40000.00, 'Model Name-iPad\r\nMemory Storage Capacity-256 GB\r\nScreen Size-10.9 Inches\r\nOperating System-iPadOS', 'Yes', 'uploads/1756393016_ipad1.jpg', 'ipad', '2025-08-28 14:56:56', 1),
(13, 'Apple iPad Air 11″ (M2): Liquid Retina Display, 128GB, Landscape 12MP Front Camera / 12MP Back Camera, Wi-Fi 6E, Touch ID, All-Day Battery Life — Space Grey', 0, 0, 54999.00, '11 inches (27.59 cm) Liquid Retina Display, 60 Hz Refresh Rate\r\n8GB RAM, 128GB ROM\r\nApple M3 Chip, Octa Core\r\nUpto 10 Hours Battery Life\r\n12 MP Primary Camera, 12 MP Front Camera\r\nLandscape Stereo Speakers, Apple Intelligence, Video Mirroring', 'Yes', 'uploads/1756393038_ipad2.jpg', 'ipad', '2025-08-28 14:57:18', 1),
(14, 'Apple iPad Pro 13″ (M4): Ultra Retina XDR Display, 256GB, Landscape 12MP Front Camera / 12MP Back Camera, LiDAR Scanner, Wi-Fi 6E, Face ID, All-Day Battery Life, Standard Glass — Space Black', 0, 0, 125000.00, '11 inches (28.22 cm) Ultra Retina XDR Display, 120 Hz Refresh Rate\r\n8GB RAM, 256GB ROM\r\nApple M4 Chip, 9 Core\r\niPadOS 18\r\nUpto 10 Hours Battery Life\r\n12 MP Primary Camera, 12 MP Front Camera\r\nFour Speaker Audio, Ambient light sensors, Fingerprint Resistant', 'Yes', 'uploads/1756393060_ipad3.jpg', 'ipad', '2025-08-28 14:57:40', 1),
(15, 'Apple iPad Mini (A17 Pro): Apple Intelligence, 21.08 cm (8.3″) Liquid Retina Display, 128GB, Wi-Fi 6E, 12MP Front/12MP Back Camera, Touch ID, All-Day Battery Life — Blue', 0, 0, 39999.00, '11 inches (27.59 cm) Liquid Retina Display, 60 Hz Refresh Rate\r\n128GB ROM\r\nApple A16 Chip, Penta Core\r\niPadOS 18\r\nUpto 10 Hours Battery Life\r\n12 MP Primary Camera, 12 MP Front Camera\r\nLandscape Stereo Speakers, Touch ID, Video Mirroring', 'Yes', 'uploads/1756393083_ipad4.jpg', 'ipad', '2025-08-28 14:58:03', 1),
(16, 'Apple Watch Series 10 [GPS 46 mm] Smartwatch with Jet Black Aluminium Case with Black Sport Band - S/M. Fitness Tracker, ECG App, Always-On Retina Display, Water Resistant', 0, 0, 49990.00, 'Operating System-WatchOS\r\nMemory Storage Capacity-64 GB\r\nSpecial Feature-Activity Tracker, Alarm Clock, Always On Display, Fall Detection, GPS\r\nBattery Capacity-0.1 Milliamp Hours\r\nConnectivity Technology	Bluetooth- Wi-Fi', 'Yes', 'uploads/1756393122_watch1.jpg', 'watch', '2025-08-28 14:58:42', 1),
(17, 'Apple Watch SE (2nd Gen, 2023) [GPS 40mm] Smartwatch with Silver Aluminum Case with Blue Cloud Sport Loop. Fitness & Sleep Tracker, Crash Detection, Heart Rate Monitor, Retina Display', 0, 0, 25000.00, 'Operating System-Android, iOS\r\nMemory Storage Capacity-32 GB\r\nSpecial Feature-Sedentary Reminder, Sleep Monitor\r\nConnectivity Technology	- Wi-Fi 4 (802.11n);Bluetooth 5.3\r\nWireless Communication Standard', 'Yes', 'uploads/1756393146_watch2.jpg', 'watch', '2025-08-28 14:59:06', 1),
(18, 'Apple Watch Series 9 [GPS + Cellular 45mm]Smartwatch with (PRODUCT)RED Aluminum Case with (PRODUCT)RED Sport Band S/M. Fitness Tracker,Blood Oxygen & ECG Apps,Always-On Retina Display,Water Resistant', 0, 0, 47999.00, 'Always On Retina display\r\nWater resistant Upto 50 metres\r\n2x brighter display 2000 nits\r\nS9 SiP 60% faster than S8', 'Yes', 'uploads/1756393167_watch3.jpg', 'watch', '2025-08-28 14:59:27', 1),
(19, 'Apple Watch Series 10 [GPS + Cellular 46 mm] Smartwatch with Jet Black Aluminium Case with Ink Sport Loop. Fitness Tracker, ECG App, Always-On Retina Display, Carbon Neutral', 0, 0, 59990.00, 'Operating System-Ios\r\nMemory Storage Capacity-64 GB\r\nSpecial Feature-Fall Detection and Crash Detection\r\nConnectivity Technology - Wi-Fi 4 (802.11n);Bluetooth 5.3\r\nWireless Communication Standard', 'Yes', 'uploads/1756393193_watch4.jpg', 'watch', '2025-08-28 14:59:53', 1),
(20, 'Apple Watch Ultra 2 [GPS + Cellular 49 mm] Smartwatch, Sports Watch with Natural Titanium Case with Natural Titanium Milanese Loop - M. Fitness Tracker, Precision GPS, Action Button, Carbon Neutral', 0, 0, 105999.00, 'Memory Storage Capacity-64 GB\r\nConnectivity Technology	- Wi-Fi 4 (802.11n);Bluetooth 5.3\r\nBattery Cell Composition -Lithium Ion\r\nScreen Size-49 Millimetres\r\nBrand-Apple', 'Yes', 'uploads/1756393229_watch5.jpg', 'watch', '2025-08-28 15:00:29', 1),
(21, '2022 Apple TV 4K Wi-Fi with 64GB Storage (3rd Generation)', 0, 0, 14999.00, '\r\nBrand-Apple\r\nConnectivity Technology	- Bluetooth, Wi-Fi\r\nConnector Type-HDMI\r\nSpecial Feature-High Definition\r\nResolution-4k\r\nSupported Internet Services-Amazon Prime Video, Disney+, HBO Max, Netflix', 'Yes', 'uploads/1756393259_tv1.jpg', 'others', '2025-08-28 15:00:59', 1),
(22, 'Foso Wall Mount for Apple Mini Pod, Alexa Echo Dot 3rd, 4th Gen with & Without LED Clock, Google Nest Mini Speaker Stand Holder for SmartSpeakers, Built-in Cable Management (Speaker not Included)', 0, 0, 499.00, 'Brand-Foso\r\nColour-Alexa Echo Dot 4th Gen Stand\r\nCompatible Devices-Smart Speakers\r\nCompatible Phone Models-Echo Dot 4th Gen, Echo Dot 5th Gen Speaker\r\nMounting Type-Wall Mount', 'Yes', 'uploads/1756393283_tv2.jpg', 'others', '2025-08-28 15:01:23', 1),
(23, 'Apple AirPods Pro (2nd Generation) with MagSafe Case (USB-C) (White)', 0, 0, 22999.00, '\r\nBrand - Apple\r\nColour - White\r\nEar Placement - In Ear\r\nForm Factor - In Ear\r\nNoise Control	- Active Noise Cancellation', 'Yes', 'uploads/1756393307_tv3.jpg', 'others', '2025-08-28 15:01:47', 1),
(24, 'Apple Lightning to USB Camera Adapter', 0, 0, 1099.00, 'Full Name and Address of the Manufacturer:\r\nAPPLE INC, ONE APPLE PARK WAY, CUPERTINO, CA 95014, USA\r\n\r\nFull Name and Address of the Importer:\r\nAPPLE INDIA PRIVATE. LTD.,13TH FLOOR, PRESTIGE MINSK SQUARE, MUNICIPAL NO. 6, CUBBON ROAD, BENGALURU, KARNATAKA - 560 001 INDIA\r\n\r\nCountry of Origin:\r\nChina\r\n\r\nDetails of the Items in the Package (Number of Units/Quantity):\r\nCARD READER 1N', 'Yes', 'uploads/1756393333_tv4.jpg', 'others', '2025-08-28 15:02:13', 1),
(25, 'JioTag Air for iOS|Apple Find My Network Item Finder| Worldwide Tracking for Keys, Wallets, Luggage, Pets, Gadgets and More|1+1 Year Battery| No SIM Needed|120db Sound| BT 5.3', 3.1, 200, 1499.00, NULL, 'Yes', 'uploads/1756393355_tv5.jpg', 'others', '2025-08-28 15:02:35', 0),
(26, 'MagSafe Charger', 0, 1, 2500.00, 'Full Name and Address of the Manufacturer:\r\nAPPLE INC, ONE APPLE PARK WAY, CUPERTINO, CA 95014, USA\r\n\r\nFull Name and Address of the Importer:\r\nAPPLE INDIA PRIVATE. LTD.,13TH FLOOR, PRESTIGE MINSK SQUARE, MUNICIPAL NO. 6, CUBBON ROAD, BENGALURU, KARNATAKA - 560 001 INDIA\r\n\r\nCountry of Origin:\r\nVIETNAM\r\n\r\nDetails of the Items in the Package (Number of Units/Quantity):\r\nCHARGER 1N', 'yes', 'uploads/1764775621_ot4.jpeg', 'others', '2025-12-03 15:27:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `pid`, `image_path`) VALUES
(6, 2, 'uploads/1764769016_0_iphone13_1_.png'),
(7, 2, 'uploads/1764769016_1_iphone13.png'),
(8, 2, 'uploads/1764769016_2_iphone13.avif'),
(9, 3, 'uploads/1764769743_0_iphone14.avif'),
(10, 3, 'uploads/1764769743_1_iphone14_1_.png'),
(11, 3, 'uploads/1764769743_2_iphone14.png'),
(12, 4, 'uploads/1764769985_0_iphone16-3.png'),
(13, 4, 'uploads/1764769985_1_iphone16-2.png'),
(14, 4, 'uploads/1764769985_2_iphone16-1.png'),
(16, 5, 'uploads/1764770390_1_iphone15-3.png'),
(17, 5, 'uploads/1764770390_2_iphone15-2.png'),
(18, 5, 'uploads/1764770390_3_iphone15-1.png'),
(19, 6, 'uploads/1764771906_0_iph16pro-4.avif'),
(20, 6, 'uploads/1764771906_1_iph16pro-3.avif'),
(21, 6, 'uploads/1764771906_2_iph16pro-2.avif'),
(22, 6, 'uploads/1764771906_3_iph16pro-1.avif'),
(23, 7, 'uploads/1764772171_0_mac1-3.jpg'),
(24, 7, 'uploads/1764772171_1_mac1-2.jpg'),
(25, 7, 'uploads/1764772171_2_mac1-1.jpg'),
(26, 8, 'uploads/1764772335_0_mac2-3.jpg'),
(27, 8, 'uploads/1764772335_1_mac2-2.jpg'),
(28, 8, 'uploads/1764772335_2_mac2-1.jpg'),
(29, 9, 'uploads/1764772509_0_mac3-3.jpg'),
(30, 9, 'uploads/1764772509_1_mac3-2.jpg'),
(31, 9, 'uploads/1764772509_2_mac3-1.jpg'),
(32, 10, 'uploads/1764772643_0_mac4-3.jpg'),
(33, 10, 'uploads/1764772643_1_mac4-2.jpg'),
(34, 10, 'uploads/1764772643_2_mac4-1.jpg'),
(35, 11, 'uploads/1764772784_0_mac5-3.jpg'),
(36, 11, 'uploads/1764772784_1_mac5-1.jpg'),
(37, 11, 'uploads/1764772784_2_mac5-2.jpg'),
(38, 12, 'uploads/1764773059_0_ipad1-3.jpeg'),
(39, 12, 'uploads/1764773059_1_ipad1-2.jpeg'),
(40, 12, 'uploads/1764773059_2_ipad1.jpeg'),
(41, 13, 'uploads/1764773218_0_ipad2-3.jpg'),
(42, 13, 'uploads/1764773218_1_ipad2-2.jpg'),
(43, 13, 'uploads/1764773218_2_ipad2-1.jpg'),
(44, 14, 'uploads/1764773369_0_ipad4-3.jpg'),
(45, 14, 'uploads/1764773369_1_ipad4-2.jpg'),
(46, 14, 'uploads/1764773369_2_ipad4-1.jpg'),
(47, 15, 'uploads/1764773513_0_ipad5-3.jpg'),
(48, 15, 'uploads/1764773513_1_ipad5-2.jpg'),
(49, 15, 'uploads/1764773513_2_ipad5-1.jpg'),
(50, 5, 'uploads/1764773707_0_iphone4-1.jpg'),
(51, 6, 'uploads/1764773720_0_iphone4-1.jpg'),
(52, 2, 'uploads/1764773734_0_iphone4-1.jpg'),
(53, 4, 'uploads/1764773788_0_ip.jpg'),
(54, 16, 'uploads/1764773959_0_iwacth3.jpg'),
(55, 16, 'uploads/1764773959_1_iwatch2.jpg'),
(56, 16, 'uploads/1764773959_2_iwatch.jpg'),
(57, 17, 'uploads/1764774064_0_iw-2.jpg'),
(58, 17, 'uploads/1764774064_1_iwatch2.jpg'),
(59, 17, 'uploads/1764774064_2_iwatch.jpg'),
(60, 18, 'uploads/1764774160_0_iw3-1.jpg'),
(61, 18, 'uploads/1764774160_1_iwatch2.jpg'),
(62, 18, 'uploads/1764774160_2_iwatch.jpg'),
(64, 19, 'uploads/1764774347_1_iw5-2.jpg'),
(65, 19, 'uploads/1764774347_2_iw5-1.jpg'),
(67, 19, 'uploads/1764774434_0_iwatch2.jpg'),
(68, 20, 'uploads/1764774579_0_iw5-3.jpg'),
(69, 20, 'uploads/1764774579_1_iw5-2.jpg'),
(70, 20, 'uploads/1764774579_2_iw5-1.jpg'),
(71, 21, 'uploads/1764774706_0_ot1-3.jpg'),
(72, 21, 'uploads/1764774706_1_ot1-2.jpg'),
(73, 21, 'uploads/1764774706_2_ot1-1.jpg'),
(74, 22, 'uploads/1764774856_0_ot2-3.jpg'),
(75, 22, 'uploads/1764774856_1_ot2-2.jpg'),
(76, 22, 'uploads/1764774856_2_ot2-1.jpg'),
(77, 23, 'uploads/1764774979_0_ot3-3.jpg'),
(78, 23, 'uploads/1764774979_1_ot3-2.jpg'),
(79, 23, 'uploads/1764774979_2_ot3-1.jpg'),
(80, 24, 'uploads/1764775128_0_ot5-2.jpg'),
(81, 24, 'uploads/1764775128_1_ot5-1.jpg'),
(82, 24, 'uploads/1764775128_2_ot5.jpg'),
(83, 26, 'uploads/1764775621_0_ot44.jpeg'),
(84, 26, 'uploads/1764775621_1_ot43.jpeg'),
(85, 26, 'uploads/1764775621_2_ot41.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `fname` varchar(200) NOT NULL,
  `lname` varchar(200) NOT NULL,
  `uname` varchar(200) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `fname`, `lname`, `uname`, `email`, `password`, `created_at`) VALUES
(1, 'System', 'Admin', 'admin', 'admin@gmail.com', '$2y$10$Lw1/RWCJBtjsO1vxevX/Iu/.yP8Zc0288auryLquHmYCy2SLa5p2e', '2025-12-03 13:10:31'),
(2, 'meet', 'patel', 'meet', 'meet@gmail.com', '$2y$10$9uTi9gywWH6yyd7EXeye5unHkbY6jlsci1w7TeuXkP.PiQOmtLMfa', '2025-12-03 13:15:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'category id', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
