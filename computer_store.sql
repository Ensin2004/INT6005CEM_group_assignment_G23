-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2025 at 07:13 PM
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
-- Database: `computer_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(30) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_pwd` varchar(255) NOT NULL,
  `admin_image` varchar(255) DEFAULT 'no_profile_pic.png',
  `role` enum('super_admin','manager') NOT NULL DEFAULT 'manager',
  `account_status` enum('active','banned') NOT NULL DEFAULT 'active',
  `wrong_pwd_count` int(11) DEFAULT 0,
  `lock_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_name`, `admin_email`, `admin_pwd`, `admin_image`, `role`, `account_status`, `wrong_pwd_count`, `lock_until`) VALUES
(1, 'admin', 'bryanyeoh681@gmail.com', '$argon2id$v=19$m=131072,t=3,p=1$N0M4SklPLzlZNE1BMGIySQ$3ZjBhBoTqZOxQtMb/GtwFvrSml9LbB6XoUdtZx3HlNI', 'no_profile_pic.png', 'super_admin', 'active', 0, NULL),
(3, 'test', 'test@gmail.com', '$argon2id$v=19$m=131072,t=3,p=1$ZEV1T1ZCUktVQ1Q4aDhsMw$CvGEK5bUa7OksETTYJprCBEDFhYBGDwc14zoQpO7SK4', 'no_profile_pic.png', 'manager', 'active', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Mouse'),
(2, 'Keyboard'),
(3, 'Monitor'),
(4, 'Graphic Card'),
(5, 'CPU'),
(6, 'Memory'),
(7, 'Storage'),
(8, 'Motherboard'),
(9, 'Power Supply Unit');

-- --------------------------------------------------------

--
-- Table structure for table `homepage`
--

CREATE TABLE `homepage` (
  `id` int(11) NOT NULL,
  `aboutus_description` text NOT NULL,
  `mission_description` text NOT NULL,
  `whatwedo1` varchar(255) NOT NULL,
  `whatwedo2` varchar(255) NOT NULL,
  `whatwedo3` varchar(255) NOT NULL,
  `whatwedo4` varchar(255) NOT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `image5` varchar(255) DEFAULT NULL,
  `image6` varchar(255) DEFAULT NULL,
  `image7` varchar(255) DEFAULT NULL,
  `image8` varchar(255) DEFAULT NULL,
  `image9` varchar(255) DEFAULT NULL,
  `image10` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage`
--

INSERT INTO `homepage` (`id`, `aboutus_description`, `mission_description`, `whatwedo1`, `whatwedo2`, `whatwedo3`, `whatwedo4`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `image7`, `image8`, `image9`, `image10`) VALUES
(1, 'At KAHTECH, we specialize in providing top-quality PCs tailored to your needs. Whether you&#039;re a gamer, a professional, or simply in need of a reliable machine, we&#039;ve got you covered. Our wide range of computers ensures you&#039;ll find the perfect match for your requirements. Experience the best in performance, quality, and customer service with KAHTECH. Not just that, We also offer delivery around Penang at the cheapest prices available, ensuring you get your dream PC without breaking the bank. Plus, we allow you to customize your own PC and make price comparisons to find the best deals. Shop with us today and elevate your computing experience.\n\nRemember Our Motto: &quot;We sell PCs but not Cars&quot;', 'At KAHTECH, our mission is to revolutionize the way you experience computing by providing top-quality PCs tailored to your unique needs. We are committed to delivering exceptional value, performance, and service to our customers. We strive to offer the best PCs at the most competitive prices, ensuring that cutting-edge technology is accessible to everyone. We empower you to customize your own PC, giving you the flexibility to build a machine that perfectly suits your requirements. We provide transparent price comparisons, helping you make informed decisions and get the best deals. We ensure prompt and affordable delivery across Penang, making it easier for you to receive your perfect PC right at your doorstep.\r\n', 'We deliver our top-quality PCs promptly and efficiently throughout Penang, ensuring you receive your orders with ease and convenience.', 'Customize your PC to meet your specific needs. Choose your preferred components and configurations to create the perfect machine.', 'We offer flexible payment options including Touch &#039;n Go and Cash on Delivery, providing you with convenience and security.', 'Benefit from transparent price comparisons to find the best deals. We help you make informed decisions and maximize value.', '6676825acfb986.90923731.jpg', '6676825ad00075.79441267.jpg', '6676825ad03847.81452378.jpg', '6676825ad09968.35978741.jpg', '6676825ad0d3a8.52288149.jpg', '6676825ad10421.50951903.jpg', '6676825ad12f42.55160122.jpg', '6676825ad15693.53523597.jpg', '6676825ad17cc2.58044209.jpg', '6676825ad1a0a4.39169068.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_qty` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL DEFAULT '',
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `image5` varchar(255) DEFAULT NULL,
  `item_status` enum('Active','Deleted') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `category_id`, `item_name`, `price`, `stock_qty`, `description`, `image1`, `image2`, `image3`, `image4`, `image5`, `item_status`) VALUES
(1, 1, 'Logitech G502 High Performance Gaming Mouse', 179.00, 0, 'PHYSICAL SPECIFICATIONS\r\nHeight: 132 mm\r\nWidth: 75 mm\r\nDepth: 40 mm\r\nWeight: 121 g, mouse only\r\nOptional extra weights: up to 18g (5x3.6g)\r\nCable length: 2.10 m\r\n\r\nTRACKING\r\nSensor: HERO 25K™\r\nResolution: 100 – 25,600 dpi\r\nZero smoothing/acceleration/filtering\r\nMax. acceleration: &gt; 40 G2Tested on Logitech G240 Gaming Mouse Pad\r\nMax. speed: &gt; 400 IPS3Tested on Logitech G240 Gaming Mouse Pad', '667685d118bca3.75405915.jpeg', '667685d118ef17.65019889.jpeg', '667685d1193414.12625631.jpeg', '667685d11974e3.37533744.jpeg', '667685d119b2c5.09377473.jpeg', 'Active'),
(2, 1, 'Logitech Wireless Mouse 2.4GHz ', 55.00, 1, 'Compact Comfortable Mouse: With a comfortable and contoured shape, this ambidextrous wireless mouse feels great in either hand, and is far superior to a touchpad\r\n-Durable and Reliable: Enjoy a durable scroll wheel and up to 1 year of battery life with this USB wireless mouse, which comes with an AA battery and a smart sleep mode function\r\n-Universal Compatibility: Your Logitech mouse works with your Windows PC, Mac or laptop. That means no matter what type of computer you own today – or buy tomorrow – your mouse will be compatible\r\n-Plug and Play: This wireless computer mouse is super speedy to set up, so you won&#039;t have to fiddle around with technology. Just plug in the tiny nano receiver and you&#039;re in business\r\n-Advanced Wireless Connectivity: You get the same reliability of a corded mouse with wireless convenience and freedom-fast data transmission and virtually no delays or dropouts', '66768a918570e1.63090529.jpeg', '66768a918571c7.82675918.jpeg', '66768a918571d8.72096126.jpeg', '66768a918571e7.11286541.jpeg', '66768a918571f4.53283544.jpeg', 'Active'),
(3, 1, 'Razer Basilisk V3 Customizable Wired Chroma RGB Gaming Mouse', 229.00, 4, 'ICONIC ERGONOMIC DESIGN WITH THUMB REST — PC gaming mouse favored by millions worldwide with a form factor that perfectly supports the hand while its buttons are optimally positioned for quick and easy access', '66768ba8988c02.84677764.jpeg', '66768ba8988cb6.51842439.jpeg', '66768ba8988cc0.70453718.jpeg', '66768ba8988cd7.41210901.jpeg', '66768ba8988ce2.04731329.jpeg', 'Active'),
(4, 2, 'New RK ROYAL KLUDGE RKR75  Mechanical Keyboard', 180.39, 8, 'Specifications:\r\nBrand Name: Royal Kludge\r\nProduct Model: RKR75\r\nKeys Number: 80\r\nSwitch: K Silver Switch\r\nConnection Mode:Wired\r\nBacklit: RGB\r\nSystem: Windows/Android/Mac/IOS\r\nBattery Capacity: 3750mAh\r\nSize：323*142.3*41.63mm\r\nWeight:0.8kg', '66768c98512925.60012415.jpeg', '66768c98512ac3.08328175.jpeg', '66768c98512ad0.87681181.jpeg', '66768c98512ae0.76802359.jpeg', '66768c98512af7.23324482.jpeg', 'Active'),
(5, 2, 'Logitech G713 TKL Mechanical Gaming Keyboard ', 612.00, 6, 'Part of the Aurora Collection, G713 Gaming Keyboard is a compact, comfy keyboard that delivers low-key vibes with high-key performance so you can express yourself and play your way. Float away with its dreamy white design and cloud-soft palm rest. A compact, tenkeyless layout, and adjustable height give that good game feeling, all-day long. Show off your playstyle with ethereal, zonal LIGHTSYNC RGB and four responsive Play Moods that reflect your state of play. Create and play your best with versatile and responsive USB-C cable connectivity. Customize your look with key caps and top plates in a variety of fun colours.', '66768d7599f360.73441858.jpeg', '66768d7599f411.60143506.jpeg', '66768d7599f425.08410537.jpeg', '66768d7599f446.34926952.jpeg', '66768d7599f456.61209373.jpeg', 'Active'),
(6, 2, 'AKKO MOD007 PC Mechanical Keyboard Swappable Custom Keyboard ', 446.05, 5, 'Brand: AKKO\r\nModel: MOD 007 PC\r\nConnection mode: USB PS/2 Type-C USB\r\nNumber of connected devices: 1\r\nWired or not: No\r\nKey number: 82\r\nMechanical keyboard or not: Mechanical keyboard\r\nBacklight effect: RGB\r\nKeyboard polling rate: 1000HZ', '66768eb670b8b8.16104454.jpeg', '66768eb670b9f8.98613018.jpeg', '66768eb670ba12.45937219.jpeg', '66768eb670ba35.55814974.jpeg', '66768eb670ba53.19898649.jpeg', 'Active'),
(7, 3, 'CER NITRO 180Hz 1Ms HDR10 FREE-SYNC PREMIUM GAMING MONITOR', 409.00, 7, 'SPECIFICATIONS :\r\n1. Model : ACER NITRO QG241Y S3\r\nTechnology : Free-Sync\r\nBrightness : 250 cd/m²\r\nContrast Ratio : 100mil:1 (ACM)\r\nResponse Time : 1ms(VRB)\r\nRefresh Rate : 180Hz\r\nDisplay Screen / Design / Resolution : 23.8&quot; FHD (1920 x 1080) VA, 72% NTSC\r\nInterface :2x HDMI(2.0)\r\n1x DisplayPort(1.2)\r\nSpeaker : N/A\r\nVesa Wall Mount : 100*100\r\nDimensions : (W x H x D): 540.2 mm x 411.7 mm x 220.7 mm (with stand)\r\nWeight : 3.85 kg\r\nWarranty : 3-Years Warranty By Acer Malaysia', '66768fe9695313.55455526.jpeg', '66768fe9695406.20401994.jpeg', '66768fe9695421.55122809.jpeg', '66768fe9695434.51966866.jpeg', '66768fe9695445.80081855.jpeg', 'Active'),
(8, 3, 'EXPOSE Monitor PC 19-24 Inch Gaming Monitor 75HZ HDMI Lcd Monitor', 138.90, 5, 'Product details of  Monitor PC 19-24 Inch Gaming  Monitor 75HZ  HDMI Lcd Monitor\r\nWelcome to Expose TECH\r\n📢Follow the store to get more real-time data of store products  \r\n📢Please collect the coupon before placing an order\r\n🚚The store is near Selangor , we will ship within 24 hours. You will receive the express package within 3-4 days\r\n🔥Payment methods support COD and online payment\r\nOur store has wholesale business. Customers who need to order in large quantities can consult customer service to get more discounts. Please consult customer service for specific procedures that support customers’ self-pickup of products.', '667690de12df45.79112627.jpeg', '667690de12e4f7.33456417.jpeg', '667690de12e513.21655852.jpeg', '667690de12e524.16280648.jpeg', '667690de12e548.07537278.jpeg', 'Active'),
(9, 3, 'Monitor PC 24 Inch Curved Monitor Gaming ', 548.00, 8, 'Our products are in stock, buy on the same day, send the ⭐Order now and receive a valuable coupon ⭐⭐ Additional discounts for multiple orders⭐\r\n(If you need wholesale, please enter the store and contact customer service for details)\r\n📢New products, first-hand, of course 100% authentic, guaranteed by the Malaysian service center.\r\n🚚Delivery within 2-4 days for provinces around the place of origin or nearby\r\n📢 5 years warranty. If there is a problem with the product within 5 years, please contact the store. We will replace with new products free of charge. We are a responsible shop, willing to provide you with the best service.\r\n📢Our store can issue receipts. If a receipt is required please confirm the order and send your information and email to the merchant.', '667691a62183b7.62440509.jpeg', '667691a6218473.96795625.jpeg', '667691a62184a5.28001158.jpeg', '667691a62184c0.53669022.jpeg', '667691a62184d5.24450885.jpeg', 'Active'),
(10, 4, 'ASUS NVIDIA GTX1650 RTX4060 8GB DUAL GRAPHIC CARD ', 766.00, 7, 'SPECIFICATIONS\r\nModel : DUAL-GTX1650-O4GD6-P-EVO\r\nGraphic Engine : NVIDIA® GeForce GTX 1650\r\nBus Standard : PCI Express 3.0\r\nOpenGL : OpenGL®4.6\r\nVideo Memory : 4GB GDDR6\r\nEngine Clock : OC mode: 1785 MHz (Boost Clock) / Default mode: 1755 MHz (Boost Clock)\r\nCUDA Core : 896\r\nMemory Speed : 12 Gbps\r\nMemory Interface : 128-bit\r\nResolution : Digital Max Resolution 7680 x 4320\r\nInterface : Yes x 1 (Native DVI-D) / Yes x 1 (Native HDMI 2.0b) / Yes x 1 (Native DisplayPort 1.4a) / HDCP Support Yes (2.2)\r\nMaximum Display Support : 3\r\nNVlink/ Crossfire Support : No\r\nAccessories : 1 x Speedsetup Manual\r\nSoftware : ASUS GPU Tweak III &amp; GeForce Game Ready Driver &amp; Studio Driver: please download all software from the support site.\r\nDimensions : 21.2 x 12.1 x 4 cm / 8.3 x 4.8 x 1.6 inches\r\nRecommended PSU : 300W\r\nPower Connectors : 1 x 6-pin\r\nSlot : 2 Slot', '667692c5502256.09033281.jpeg', '667692c5502333.08197550.jpeg', '667692c5502349.60375168.jpeg', '667692c5502351.69085539.jpeg', '667692c5502360.01844887.jpeg', 'Active'),
(11, 4, 'Asus ROG Geforce Edition GDDR6X Graphic Card (24GB) RTX4090 ', 11789.00, 6, 'ROG Strix GeForce RTX™ 4090 OC Edition 24GB GDDR6X\r\nGraphic Engine : NVIDIA® GeForce RTX™ 4090\r\nBus Standard : PCI Express 4.0\r\nOpenGL : OpenGL®4.6\r\nVideo Memory : 24GB GDDR6X\r\nCUDA Core : 16384\r\nMemory Speed : 21 Gbps\r\nMemory Interface : 384-bit\r\nResolution : Digital Max Resolution 7680 x 4320\r\nMaximum Display Support : 4\r\nNVlink/ Crossfire Support : No \r\nDimensions : 357.6 x 149.3 x 70.1mm\r\nRecommended PSU : 1000W\r\nPower Connectors : 1 x 16-pin\r\nSlot : 3.5 Slot\r\nAURA SYNC : ARGB ', '6676937cb8a172.82113218.jpeg', '6676937cb8a249.89468444.jpeg', '6676937cb8a265.60744864.jpeg', '6676937cb8a279.14037981.jpeg', '6676937cb8a296.68450830.jpeg', 'Active'),
(12, 4, 'Sapphire PULSE AMD Radeon™  16GB GDDR6 Graphics Card ', 1839.00, 8, '• Model : 11339-04-20G\r\n• Graphic Engine : AMD Radeon™ RX 7600 XT Graphics Card (AMD RDNA™ 3 Architecture)\r\n• Bus Standard : PCI Express 4.0 x8\r\n• Engine Clock\r\nBoost Clock: Up to 2810 MHz\r\nGame Clock: Up to 2539 MHz\r\n• Video Memory : 16GB GDDR6 128-bit\r\n• Stream Processors : 2048\r\n• Infinity Cache : 32MB\r\n• Ray Tracing Accelerators : 32\r\n• AI Accelerator : 64\r\n• Memory Speed : 18 Gbps\r\n• Resolution : Digital Max Resolution 7680 x 4320', '667694c5e282b0.15601116.jpeg', '667694c5e28392.92110047.jpeg', '667694c5e283b7.14406728.jpeg', '667694c5e283c8.90432698.jpeg', '667694c5e283d7.33387669.jpeg', 'Active'),
(13, 5, 'INTEL Core I5 14400F 2.5Ghz 14th Gen LGA1700 Processor', 989.00, 8, 'CPU Specifications\r\nTotal Cores 10\r\n# of Performance-cores 6\r\n# of Efficient-cores 4\r\nTotal Threads 16\r\nMax Turbo Frequency 4.7 GHz\r\nPerformance-core Max Turbo Frequency 4.7 GHz\r\nEfficient-core Max Turbo Frequency 3.5 GHz\r\nPerformance-core Base Frequency2.5 GHz\r\nEfficient-core Base Frequency1.8 GHz\r\nCache 20 MB Intel® Smart Cache\r\nTotal L2 Cache9.5 MB\r\nProcessor Base Power 65 W\r\nMaximum Turbo Power 148 W', '6676962789ea92.69851252.jpeg', '6676962789eb75.11365695.jpeg', '', '', '', 'Active'),
(14, 5, 'INTEL CORE I7 12700', 1293.00, 7, 'SPEIFICATIONS\r\nI7 12700\r\nTotal Cores\r\n12', '667696de10ea45.32672732.jpeg', '667696de10eaf3.88122221.jpeg', '667696de10eb05.28098785.jpeg', '667696de10eb20.14286063.jpeg', '', 'Active'),
(15, 5, 'AMD Ryzen 7 5700X3D 3.0 Thread AM4 Processor', 1488.00, 8, 'Power up your gaming experiences with exceptional performance by including the AMD Ryzen 7 5700X3D 3 GHz Eight-Core AM4 Processor in your computer build. Featuring a unique AMD 3D V-Cache vertical design, this eight-core, 16 thread processor includes 100MB of L3 cache while still allowing the CPU to fit socket AM4 motherboards.', '667697ae570843.51098324.jpeg', '667697ae570909.26779883.jpeg', '667697ae570914.77648893.jpeg', '', '', 'Active'),
(16, 6, 'ADATA DDR5 U-Dimm Desktop PC RAM Memory 16GB 8GB ', 145.00, 6, 'The future of DRAM is here in the form of the ADATA DDR5-4800 U-DIMM module.\r\nCompared to its predecessors, this module provides a significant speed boost, higher capacities, reduced power consumption, and increased bandwidth per CPU core.\r\nThey deliver faster data transfers than DDR4, coupled with lower energy consumption that reduces heat and extends battery life.', '66769844e880c6.74252668.jpeg', '66769844e881c4.40880762.jpeg', '66769844e881d2.74898026.jpeg', '66769844e881f0.66489750.jpeg', '66769844e88203.38556987.jpeg', 'Active'),
(17, 6, 'Kingston HyperX FURY RAM DDR4 ', 159.00, 5, ' Low power consumption — DDR4’s lower power requirements mean less heat and higher reliability. Low 1.2 volts draw less power from your system, which results in a cooler and quieter PC.', '667698f3dc5348.65500605.jpeg', '667698f3dc5472.35661815.jpeg', '667698f3dc5487.70312936.jpeg', '667698f3dc5492.67448803.jpeg', '667698f3dc54a3.51540906.jpeg', 'Active'),
(18, 6, 'Kingston Fury Beast RGB DDR5 4800Mhz ', 1183.00, 8, 'Kingston FURY Beast Black DDR5 RGB lets you overclock in style on next-gen gaming platforms with cutting-edge technology. Experience the superior speed advancements of DDR5 with double the banks and double the burst length.\r\nVibrant RGB lighting customisable with Kingston FURY CTRL software and patented Infrared Sync Technology along with the new heat spreader design sets you apart in and out of the game', '667699a5a00173.16490298.jpeg', '667699a5a00209.14735295.jpeg', '667699a5a00221.56295770.jpeg', '667699a5a00231.03698358.jpeg', '667699a5a00241.52728974.jpeg', 'Active'),
(19, 7, 'HDD Hard Disk SATA 250GB Desktop Storage', 25.00, 1, 'Internal Hard Drives\r\nMix brand Desktop PC sata 3.5&quot; HDD\r\nStorage Capacity: 250GB', '66769a46a104e0.17308223.jpeg', '66769a46a10586.41047973.jpeg', '66769a46a10591.86118913.jpeg', '', '', 'Active'),
(20, 7, 'Desktop PC HDD Hard Disk SATA 1TB Storage', 79.00, 6, '- Brand: MIX BRAND (Western Digital, seagate. TOSHIBA, Hitachi)\r\n- Platform: Laptop / Desktop PC\r\n- Device Type : Internal Hard Drive\r\n- Condition: Used, 100% Health status\r\n- Interfaces: SATA\r\n- Form Factor: 3.5&quot;\r\n- Warranty : 1 month', '66769ad636b045.87990760.jpeg', '66769ad636b212.80191391.jpeg', '', '', '', 'Active'),
(21, 7, 'Super Blue Hard Drive 4TB hdd Pesonal PC Desktop Storage', 345.80, 9, 'Interface Type : SATA 6.0\r\nItem Condition : New\r\nHDD Capacity : 500GB, 750GB, 1TB, 1.5TB, 2TB, 3TB, 4TB\r\nSize : 3.5&quot;\r\nCache : 64MB\r\nStyle : HDD\r\nType : Internal\r\nSpeed : 5400rpm / 7200rpm', '66769b7b554565.08658790.jpeg', '66769b7b554624.60606770.jpeg', '66769b7b554631.63713468.jpeg', '66769b7b554649.65260532.jpeg', '66769b7b554658.84429492.jpeg', 'Active'),
(22, 8, 'Motherboard AMD B650 for PC Gamer', 550.00, 7, 'RAID Supported: YES\r\nProcessor compatibility: AMD Ryzen 7000 Series-R3,AMD Ryzen 7000 Series-R5,AMD Ryzen 7000 Series-R7,AMD Ryzen 7000 Series-R9\r\nUsage Scenario: Enthusiast and Overclocking,GAMING, Home Sound and Image, Home, Office, DESIGN, Video Production, Industrial Computer, Workstation, Server,Mini-PC,MINING, Scientific Computing, 3-D Modeling, Video Rendering, MaxCompute, Other, HPC\r\nSpecial function: RGB,Intel Optane ready, Armor, Support for Overclocking,HiFi,NONE', '66769c7e575396.21635768.jpeg', '66769c7e5754c9.59760341.jpeg', '66769c7e5754e1.96791249.jpeg', '66769c7e5754f8.59710226.jpeg', '66769c7e575509.44846131.jpeg', 'Active'),
(23, 8, 'ASUS P5G41C-M LX P5G41 Original Motherboard', 74.80, 8, 'Motherboard chip\r\nIntegrated chip graphics card/sound card/network card\r\nMain chipset Intel G41\r\nChipset description Intel G41 North Bridge + ICH7 South Bridge chipset\r\nDisplay chip Integrated Intel GMA X4500 display core, maximum shared memory: 1GB\r\nAudio chip integrated Realtek ALC887 8-channel audio chip\r\nNetwork card chip Onboard Realtek RTL8111E Gigabit network card', '66769d1b3d2439.78646205.jpeg', '66769d1b3d24c5.84937788.jpeg', '66769d1b3d24d9.64831651.jpeg', '66769d1b3d24e2.25551780.jpeg', '66769d1b3d24f2.97776626.jpeg', 'Active'),
(24, 8, 'X99 Motherboard E5 2666 V3 Intel LAG2011-3 DDR3 128GB', 189.61, 9, 'Extensive Processor Compatibility: This motherboard set supports Intel LGA2011-3 series processors, including E5-V3 and V4 CPUs that support DDR3 memory. Enjoy a wide range of processor options for your computing needs.', '66769e2dd94878.17151611.jpeg', '66769e2dd94945.44964850.jpeg', '66769e2dd94959.02773701.jpeg', '66769e2dd94962.08292177.jpeg', '66769e2dd94972.72061781.jpeg', 'Active'),
(25, 9, 'Imperion 600W Extreme Series Black Edition Gaming PSU', 59.00, 8, 'Support two way sata equipment\r\nHigh performance cooling solution\r\nPSU as well as the whole system\r\nSuper low noise: noise is less than 28dB at light load\r\nLess than 35dB at full load\r\nHigh efficiency', '66769f2c6eb2e0.82709761.jpeg', '66769f2c6f3e37.15650598.jpeg', '66769f2c6f86d0.97217484.jpeg', '66769f2c6fc539.73283544.jpeg', '66769f2c700700.01256047.jpeg', 'Active'),
(26, 9, 'Original Salpido Professional PSU500W', 45.90, 8, '1 Year Local Manufacturer Warranty\r\n-Power: ATX 500W\r\n-20+4pin, 4pin-12V, Molex 4pin x 1, SATAx3\r\n-Input : 5A\r\n-Input Frequency :50Hz\r\n-Fan : 8cm super silent fan\r\n-Noise : 21dBA', '66769fc5a986e3.77906575.jpeg', '66769fc5a98851.04240113.jpeg', '66769fc5a98878.16177386.jpeg', '66769fc5a98897.37254496.jpeg', '66769fc5a988a5.06351771.jpeg', 'Active'),
(27, 9, 'Asus ROG Strix 1000W Gold Aura Edition Gaming Power Supply PSU', 1205.00, 8, 'Features an Aluminum Case that is coupled to the internal power transformer, helping to reduce temperatures and noise levels. heatsink to reduce temperatures and noise levels. \r\n• Large ROG heatsinks cover critical components, delivering lower temperatures and noise than reference designs. \r\n• Axial-tech fan design features a smaller fan hub that facilitates longer blades and a barrier ring that increases downward air pressure. ', '6676a063be5971.73548780.jpeg', '6676a063be5a79.33411413.jpeg', '6676a063be5a83.12533804.jpeg', '6676a063be5a98.11695956.jpeg', '6676a063be5aa0.58815203.jpeg', 'Active'),
(28, 1, 'Logitech ERGO M575 Wireless Trackball Mouse', 208.00, 8, 'Ergonomic comfort design, relaxed hand and arm: The comfortable, sculpted ergonomic shape of this mouse naturally fits your hand', '667f7456ce9227.37350569.jpeg', '667f7456ce9342.56788569.jpeg', '667f7456ce9362.88259024.jpeg', '667f7456ce9377.61898553.jpeg', '667f7456ce9382.36754844.jpeg', 'Active'),
(29, 1, 'SteelSeries Rival 650 Wireless RGB Gaming Mouse ', 419.00, 10, 'RIVAL 650 WIRELESS\r\nThe First True Performance Wireless Mouse\r\nExclusive Quantum WirelessTM offers lag-free 1000Hz (1ms) gaming', '667f74f6590221.01192163.jpeg', '667f74f65902d5.91660344.jpeg', '667f74f65902e2.67024604.jpeg', '667f74f65902f9.86711376.jpeg', '667f74f6590308.17552619.jpeg', 'Active'),
(30, 2, 'Armaggeddon MKA 1C Neo LED Backlight Mechanical Gaming Keyboard', 69.85, 10, 'Armaggeddon MKA 1C Neo Psychswift\r\nFeatures :\r\n- Plug &amp; Play (No Driver Required)\r\n- 5 Zone Multicolour LED Back Light\r\n- KevlarTech keypads with lifetime fade proof warranty', '667f7581e97414.05445354.jpeg', '667f7581e97480.44083748.jpeg', '667f7581e97492.97527580.jpeg', '667f7581e974a6.35117605.jpeg', '667f7581e974b6.00557344.jpeg', 'Active'),
(31, 2, 'SteelSeries Apex Pro RGB Omnipoint Mechanical Gaming Keyboard ', 1049.00, 10, 'APEX PRO\r\nAdjustable Mechanical Switch Gaming Keyboard', '667f7617aad503.20498829.jpeg', '667f7617aad5d3.70783573.jpeg', '667f7617aad5e9.22568674.jpeg', '667f7617aad5f9.62677378.jpeg', '667f7617aad604.70577496.jpeg', 'Active'),
(32, 3, 'Alienware AW3423DW 34&quot; CURVED QD-OLED GAMING MONITOR', 4950.00, 9, 'The world&#039;s first QD-OLED gaming monitor. Featuring infinite contrast ratio and VESA Display HDR for remarkably vivid colors and visuals bursting to life.', '667f76b5235f25.18184874.jpeg', '667f76b5235fa9.31281509.jpeg', '667f76b5235fc9.24966623.jpeg', '667f76b5235fd9.10532881.jpeg', '667f76b5236004.66146991.jpeg', 'Active'),
(33, 3, 'MSI Optix MPG321UR-QD Quantum Dot UHD', 5335.00, 10, 'SPECIFICATIONS\r\nPanel Size 32&quot; (81.28 cm)\r\nPanel Resolution 3840 x 2160 (UHD)\r\nRefresh Rate 144HZ\r\nResponse time 1ms(MPRT)\r\nPanel Type IPS', '667f775d07e8e0.50170928.jpeg', '667f775d07e970.31896100.jpeg', '667f775d07e987.85164337.jpeg', '667f775d07e998.37289306.jpeg', '667f775d07e9a1.23449220.jpeg', 'Active'),
(34, 4, 'ASUS GIGABYTE RTX 3070 Graphic Card', 1178.00, 8, '_NVIDIA SERIES GPU_', '667f78456d4898.34728394.jpeg', '667f78456d4993.21255734.jpeg', '667f78456d49a2.55031437.jpeg', '667f78456d49c5.46254220.jpeg', '667f78456d49d0.91316970.jpeg', 'Active'),
(35, 4, 'Nvidia GeForce RTX2060 6GB Graphic Card', 2149.00, 10, 'Features: \r\n- Powered by GeForce RTX™ 2060\r\n- Integrated with 6GB GDDR6 192-bit memory interface\r\n- WINDFORCE 2X Cooling System with alternate spinning fans\r\n- 90mm unique blade fans\r\n- Protection backplate', '667f78ca42f9d5.31987628.jpeg', '667f78ca42fa89.59590581.jpeg', '', '', '', 'Active'),
(36, 5, 'INTEL CORE I5 10400 2.9GHZ SOCKET 1200 PROCESSOR', 598.00, 9, 'Product Collection\r\n10th Generation Intel® Core™ i5 Processors', '667f797a67f220.94456959.jpeg', '667f797a67f2d9.84008044.jpeg', '667f797a67f2f1.05501560.jpeg', '667f797a67f305.22375377.jpeg', '', 'Active'),
(37, 5, 'INTEL CORE i9-12900K 30M Cache', 3189.00, 10, 'Product Collection : 12th Generation Intel® Core™ i9 Processors\r\nCode Name : Products formerly Alder Lake\r\nVertical Segment : Desktop\r\nProcessor Number : i9-12900K', '667f7a3582a117.88872813.jpeg', '667f7a3582a1b3.46887759.jpeg', '667f7a3582a1d8.98985393.jpeg', '667f7a3582a1e1.34358396.jpeg', '', 'Active'),
(38, 6, 'Kingston HyperX Impact DDR4 RAM SODIMM 8GB', 176.00, 9, 'Powerful SODIMM performance-maximize your memory and enhance your game multitasking and rendering\r\nPlug and play automatic overclocking function-Impact DDR4 automatically overclocks itself to the highest release frequency\r\nIntel XMP-Ready Profiles-Our engineers pre-defined Intel Extreme Memory Profiles to maximize the performance of our memory modules to a speed of 3200MHz', '667f7ace4554a4.88027344.jpeg', '667f7ace455546.40896176.jpeg', '667f7ace455560.68278099.jpeg', '667f7ace455589.44423916.jpeg', '667f7ace455598.86848948.jpeg', 'Active'),
(39, 6, 'Crucial DDR5 32GB RAM PC Desktop Memory ', 439.00, 8, 'Harness blazing speeds at 4800MHz — 1.5x faster than DDR4\r\nGame at higher frame rates and run demanding software with nearly 2x the bandwidth of DDR4\r\nEmpower your system for the next-generation multi-core CPUs', '667f7b7cdecd65.73097982.jpeg', '667f7b7cdecee7.45065041.jpeg', '667f7b7cdecef6.77238960.jpeg', '667f7b7cdecf11.49568385.jpeg', '667f7b7cdecf30.56088631.jpeg', 'Active'),
(40, 7, 'Kingston SSD A400 Internal Solid State Drive', 318.00, 10, 'Amazing speed and sturdy reliability, A400 SSD solid state drive: fast boot, load files and transfer a variety of storage capacity, can meet the desktop and notebook computers.\r\nSupported by the latest Gen controller, it is 10 times faster than traditional hard drives, providing higher performance, ultra-sensitive multi-tasking and an overall faster system.', '667f7c22a2cbd0.10108963.jpeg', '667f7c22a2cd40.31475357.jpeg', '667f7c22a2cd65.39099950.jpeg', '667f7c22a2cd78.91972518.jpeg', '667f7c22a2cd88.88475656.jpeg', 'Active'),
(41, 7, 'Samsung SSD 860 EVO 250GB', 735.00, 9, 'Samsung 860 EVO is the latest version of the world&#039;s best-selling *SATA SSD series, designed to improve the performance of mainstream PCs and notebooks. This solid state drive uses the latest V-NAND technology, is fast and reliable, and has a variety of compatible shapes and functions.', '667f7cbaa64761.88379087.jpeg', '667f7cbaa64815.04679257.jpeg', '667f7cbaa64824.54754496.jpeg', '667f7cbaa64831.91615542.jpeg', '667f7cbaa64842.57886424.jpeg', 'Active'),
(42, 8, 'Asus B550-F ROG STRIX GAMING WI-FI AMD AM4 Motherboard', 2935.00, 9, 'SPECIFICATION:\r\nCPU:AMD Ryzen™ 5000 Series/ 5000 G-Series/ 4000 G-Series/ 3000 Series Desktop Processors\r\n\r\n', '667f7d4c2b0311.78647972.jpeg', '667f7d4c2b03b3.41288990.jpeg', '667f7d4c2b03d8.87405807.jpeg', '667f7d4c2b03e0.26266597.jpeg', '667f7d4c2b03f3.21950594.jpeg', 'Active'),
(43, 8, 'ASROCK B550M-HDV DDR4 / D4 AM4 MOTHERBOARD', 329.00, 9, 'B550M-HDV\r\nSupports AMD AM4 Socket Ryzen™ 3000, 3000 G-Series, 4000 G-Series, 5000 and 5000 G-Series Desktop Processors*\r\n6 Power Phase Design\r\nSupports DDR4 4733+ (OC)', '667f7dd8975656.17259427.jpeg', '667f7dd8975708.95556039.jpeg', '667f7dd8975724.57865914.jpeg', '667f7dd8975738.20586200.jpeg', '667f7dd8975744.93661002.jpeg', 'Active'),
(44, 9, 'Original Kingses ATX-500W Power Supply Unit PSU', 45.98, 10, 'Specification :-\r\n- Total Max Power 500W\r\n- ATX 20+4pin x 1\r\n- ATX 12V CPU 4pin x 1\r\n- SATA Connector x 2\r\n- Molex (IDE) 4pin x 2\r\n- 12cm Fan', '667f82199a0ee1.25615801.jpeg', '667f82199a1062.98778378.jpeg', '667f82199a1091.22597733.jpeg', '667f82199a10b9.39311056.jpeg', '667f82199a10d7.33538159.jpeg', 'Active'),
(45, 9, 'FSP Hydro PTM PRO ATX3.0 (PCIe5.0) 1200W PSU', 1128.00, 9, 'Hydro PTM PRO ATX3.0(PCIe5.0) 1200W\r\nComplies with ATX12V V3.0 &amp; EPS12V V2.92\r\nIntel Latest CPU ready\r\nGlobal Safety Approved', '667f82c30f30f2.51809196.jpeg', '667f82c30f31a1.06713651.jpeg', '667f82c30f31c1.50507798.jpeg', '667f82c30f31d1.72077185.jpeg', '667f82c30f31e2.50044782.jpeg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `mylist`
--

CREATE TABLE `mylist` (
  `id` int(11) NOT NULL,
  `list_name` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cpu_id` int(11) DEFAULT NULL,
  `motherboard_id` int(11) DEFAULT NULL,
  `memory_id` int(11) DEFAULT NULL,
  `gpu_id` int(11) DEFAULT NULL,
  `psu_id` int(11) DEFAULT NULL,
  `storage_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status_id` int(11) DEFAULT 1,
  `delivery_address` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_type` enum('TNG','COD') NOT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderstatus`
--

CREATE TABLE `orderstatus` (
  `id` int(11) NOT NULL,
  `status_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderstatus`
--

INSERT INTO `orderstatus` (`id`, `status_name`) VALUES
(1, 'Processing'),
(2, 'Packaging'),
(3, 'Delivering'),
(4, 'To Receive'),
(5, 'Completed'),
(6, 'Rejected'),
(7, 'Incomplete');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `secondary_email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `user_image` varchar(255) DEFAULT 'no_profile_pic.png',
  `wrong_pwd_count` int(11) DEFAULT 0,
  `lock_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `email`, `secondary_email`, `phone`, `user_address`, `pwd`, `user_image`, `wrong_pwd_count`, `lock_until`) VALUES
(1, 'First', 'aimarief0919@gmail.com', 'peggywork812@gmail.com', '0123456789', 'Taman Seri Sari ', '$argon2id$v=19$m=131072,t=3,p=1$THJQNFBYSm4yanB6LmhZdQ$lJZmRTTKkiVJyrGzj8kh+kqSwel5cmKYcOfafah2tyE', 'no_profile_pic.png', 6, '2025-11-11 16:43:01'),
(4, 'Cojean', 'P22014471@student.newinti.edu.my', NULL, '017-5807201', 'PISA Home Centre, Level 1, Car Park Complex PISA, Pulau Pinang', 'Cojean123@', 'no_profile_pic.png', 0, NULL),
(5, 'Cincai', 'hzhsia603@gmail.com', NULL, '012-8888888', 'A123, Regency Height, Sungai Ara, Penang', 'Cincai123@', 'no_profile_pic.png', 0, NULL),
(19, 'arief', 'qiqihutao919@gmail.com', '', '0123456789', '12345aaa', 'arief12345@', 'no_profile_pic.png', 0, NULL),
(26, 'Junzo', 'junzobryan28@gmail.com', NULL, '012-3456789', 'bryan 123, at jalan bryan', '$argon2id$v=19$m=131072,t=3,p=1$U0RrZG8zT3ZnV0svLkRWUw$CzXPF5fKRoesN6YJHvBYWHHUzu2JMPjJpSjQiGaL7aQ', 'no_profile_pic.png', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homepage`
--
ALTER TABLE `homepage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `mylist`
--
ALTER TABLE `mylist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cpu_id` (`cpu_id`),
  ADD KEY `motherboard_id` (`motherboard_id`),
  ADD KEY `memory_id` (`memory_id`),
  ADD KEY `gpu_id` (`gpu_id`),
  ADD KEY `psu_id` (`psu_id`),
  ADD KEY `storage_id` (`storage_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orderstatus`
--
ALTER TABLE `orderstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `homepage`
--
ALTER TABLE `homepage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `mylist`
--
ALTER TABLE `mylist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1059;

--
-- AUTO_INCREMENT for table `orderstatus`
--
ALTER TABLE `orderstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `mylist`
--
ALTER TABLE `mylist`
  ADD CONSTRAINT `mylist_ibfk_1` FOREIGN KEY (`cpu_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mylist_ibfk_2` FOREIGN KEY (`motherboard_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mylist_ibfk_3` FOREIGN KEY (`memory_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mylist_ibfk_4` FOREIGN KEY (`gpu_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mylist_ibfk_5` FOREIGN KEY (`psu_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mylist_ibfk_6` FOREIGN KEY (`storage_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mylist_ibfk_7` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `orderstatus` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
