-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2019 at 03:55 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flex`
--

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE `channels` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `channels`
--

INSERT INTO `channels` (`code`, `name`, `is_default`, `date_add`, `date_upd`) VALUES
('Online', 'Online', 0, '2019-07-06 15:35:21', '2019-07-06 15:35:21'),
('TT001', 'ขายตัวแทน', 1, '2019-07-06 15:33:40', '2019-07-07 10:55:53');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `name` varchar(20) NOT NULL,
  `value` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`name`, `value`, `description`, `date_upd`) VALUES
('DATE_FORMAT', 'BE', 'AD = ค.ศ., BE = พ.ศ.', '2019-06-27 07:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `group_code` varchar(15) DEFAULT NULL,
  `kind_code` varchar(15) DEFAULT NULL,
  `type_code` varchar(15) DEFAULT NULL,
  `class_code` varchar(15) DEFAULT NULL,
  `area_code` varchar(15) DEFAULT NULL,
  `sale_code` varchar(15) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_area`
--

CREATE TABLE `customer_area` (
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer_area`
--

INSERT INTO `customer_area` (`code`, `name`, `date_upd`) VALUES
('001', 'กรุงเทพฯ', '2019-07-07 13:02:29'),
('002', 'ภาคกลาง', '2019-07-07 13:00:11'),
('003', 'ภาคใต้', '2019-07-07 13:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `customer_class`
--

CREATE TABLE `customer_class` (
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer_class`
--

INSERT INTO `customer_class` (`code`, `name`, `date_upd`) VALUES
('001', 'เกรด A', '2019-07-07 13:07:56'),
('002', 'เกรด B', '2019-07-07 13:08:05'),
('003', 'เกรด C', '2019-07-07 13:08:15');

-- --------------------------------------------------------

--
-- Table structure for table `customer_group`
--

CREATE TABLE `customer_group` (
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer_group`
--

INSERT INTO `customer_group` (`code`, `name`, `date_upd`) VALUES
('020CA', 'ขายเงินสด', '2019-07-07 13:15:25'),
('020CR', 'ลูกค้าเครดิต', '2019-07-07 13:15:56'),
('020SP', 'ลูกค้าอภินันท์', '2019-07-07 13:15:40');

-- --------------------------------------------------------

--
-- Table structure for table `customer_kind`
--

CREATE TABLE `customer_kind` (
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer_kind`
--

INSERT INTO `customer_kind` (`code`, `name`, `date_upd`) VALUES
('CK001', 'Traditional trade', '2019-07-07 13:25:54'),
('CK002', 'Modern trade', '2019-07-07 13:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `customer_type`
--

CREATE TABLE `customer_type` (
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer_type`
--

INSERT INTO `customer_type` (`code`, `name`, `date_upd`) VALUES
('001', 'ขายส่ง', '2019-07-07 13:34:38'),
('002', 'ตัวแทน', '2019-07-07 13:33:29'),
('003', 'ขายปลีก', '2019-07-07 13:33:33');

-- --------------------------------------------------------

--
-- Table structure for table `discount_policy`
--

CREATE TABLE `discount_policy` (
  `id` int(11) NOT NULL,
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `group_code` varchar(10) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `position` int(3) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`code`, `name`, `group_code`, `active`, `position`) VALUES
('DBCARE', 'เพิ่ม/แก้ไข เขตลูกค้า', 'DB', 1, 5),
('DBCGRP', 'เพิ่ม/แก้ไข กลุ่มลูกค้า', 'DB', 1, 7),
('DBCHAN', 'เพิ่ม/แก้ไข ช่องทางขาย', 'DB', 1, 2),
('DBCKIN', 'เพิ่ม/แก้ไข ประเภทลูกค้า', 'DB', 1, 8),
('DBCLAS', 'เพิ่ม/แก้ไข เกรดลูกค้า', 'DB', 1, 6),
('DBCTYP', 'เพิ่ม/แก้ไข ชนิดลูกค้า', 'DB', 1, 9),
('DBCUST', 'เพิ่ม/แก้ไข ลูกค้า', 'DB', 1, 4),
('DBPAYM', 'เพิ่ม/แก้ไข ช่องทางชำระเงิน', 'DB', 1, 3),
('DBPROD', 'เพิ่ม/แก้ไข สินค้า', 'DB', 1, 1),
('ICCKBF', 'ตรวจสอบ BUFFER', 'IC', 1, 11),
('ICCKCN', 'ตรวจสอบ CANCLE', 'IC', 1, 12),
('ICCKMV', 'ตรวจสอบ MOVEMENT', 'IC', 1, 13),
('ICLEND', 'ยืมสินค้า', 'IC', 1, 3),
('ICODDO', 'ออเดอร์รอการจัดส่ง', 'IC', 1, 9),
('ICODIV', 'ออเดอร์จัดส่งแล้ว', 'IC', 1, 10),
('ICODPR', 'จัดสินค้า', 'IC', 1, 7),
('ICODQC', 'ตรวจสินค้า', 'IC', 1, 8),
('ICPURC', 'รับสินค้าจากการสั่งซื้อ', 'IC', 1, 4),
('ICSUPP', 'เบิกอภินันท์', 'IC', 1, 2),
('ICTRFM', 'เบิกสินค้าเพื่อแปรสภาพ', 'IC', 1, 1),
('ICTRRC', 'รับสินค้าจากการแปรสภาพ', 'IC', 1, 5),
('ICTRWH', 'โอน/ย้าย สินค้า', 'IC', 1, 6),
('SCBGSP', 'งบประมาณสปอนเซอร์', 'SC', 1, 7),
('SCBGSU', 'งบประมาณอภินันท์', 'SC', 1, 8),
('SCCONF', 'การกำหนดค่า', 'SC', 1, 1),
('SCPERM', 'กำหนดสิทธิ์', 'SC', 1, 3),
('SCPOLI', 'นโยบายส่วนลด', 'SC', 1, 5),
('SCPROF', 'เพิ่ม/แก้ไข โปรไฟล์', 'SC', 1, 2),
('SCRULE', 'เงื่อนไขส่วนลด', 'SC', 1, 6),
('SCUSER', 'เพิ่ม/แก้ไข ชื่อผู้ใช้งาน', 'SC', 1, 1),
('SOCCSO', 'ฝากขาย(ใบกำกับ)', 'SO', 1, 3),
('SOCCTR', 'ฝากขาย(โอนคลัง)', 'SO', 1, 4),
('SOODSO', 'ออเดอร์', 'SO', 1, 1),
('SOODSP', 'สปอนเซอร์', 'SO', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `menu_group`
--

CREATE TABLE `menu_group` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `position` int(5) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_group`
--

INSERT INTO `menu_group` (`code`, `name`, `position`, `active`) VALUES
('AC', 'ระบบบัญชี', 3, 1),
('DB', 'ระบบฐานข้อมูล', 6, 1),
('IC', 'ระบบคลังสินค้า', 1, 1),
('PO', 'ระบบซื้อ', 4, 1),
('SC', 'การกำหนดค่า', 5, 1),
('SO', 'ระบบขาย', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `role` varchar(1) NOT NULL DEFAULT 'S',
  `bookcode` varchar(5) NOT NULL,
  `reference` varchar(32) NOT NULL,
  `ref_code` varchar(20) DEFAULT NULL,
  `customer_code` varchar(16) NOT NULL,
  `channels_code` varchar(10) DEFAULT NULL,
  `payment_code` varchar(10) DEFAULT NULL,
  `sale_code` varchar(16) NOT NULL,
  `state` tinyint(2) NOT NULL DEFAULT '1',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `is_expired` tinyint(1) NOT NULL DEFAULT '0',
  `is_cancled` tinyint(1) NOT NULL DEFAULT '0',
  `is_online` tinyint(1) NOT NULL DEFAULT '0',
  `is_exported` tinyint(1) NOT NULL DEFAULT '0',
  `is_so` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'ส่งออก SO หรือ ไม่',
  `bDiscText` varchar(20) NOT NULL DEFAULT '0',
  `bDiscAmount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remark` text NOT NULL,
  `shipping_id` int(5) NOT NULL DEFAULT '0',
  `shipping_code` varchar(32) DEFAULT NULL,
  `shipping_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `service_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `user_id` int(5) NOT NULL,
  `update_id` int(11) NOT NULL DEFAULT '0' COMMENT 'last update by user',
  `branch_id` int(11) NOT NULL DEFAULT '0',
  `budget_id` int(11) NOT NULL DEFAULT '0',
  `rule_code` varchar(15) DEFAULT NULL COMMENT 'เลขที่ของนโยบายส่วนลดท้ายบิล',
  `gp` decimal(4,2) NOT NULL DEFAULT '0.00',
  `never_expire` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = ยกเว้นการหมดอายุ, 0 = ตามเงื่อนไข',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_role`
--

CREATE TABLE `order_role` (
  `code` varchar(1) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_role`
--

INSERT INTO `order_role` (`code`, `name`) VALUES
('C', 'ฝากขาย'),
('L', 'ยืม'),
('M', 'ตัดยอดฝากขาย'),
('P', 'สปอนเซอร์'),
('R', 'เบิก'),
('S', 'ขาย'),
('T', 'แปรสภาพ'),
('U', 'อภินันท์');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `has_term` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_upd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`code`, `name`, `is_default`, `has_term`, `date_add`, `date_upd`) VALUES
('001', 'เครดิตเทอม', 1, 1, '2019-07-07 16:21:15', '2019-07-07 19:16:19'),
('002', 'เงินสด', 0, 0, '2019-07-07 18:42:14', '2019-07-07 18:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `menu` varchar(10) NOT NULL,
  `uid` varchar(32) DEFAULT NULL,
  `id_profile` int(11) DEFAULT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '0',
  `can_add` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  `can_approve` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `menu`, `uid`, `id_profile`, `can_view`, `can_add`, `can_edit`, `can_delete`, `can_approve`) VALUES
(2, 'SCUSER', 'c4ca4238a0b923820dcc509a6f75849b', NULL, 1, 1, 1, 0, 1),
(3, 'SCPROF', 'c4ca4238a0b923820dcc509a6f75849b', NULL, 1, 1, 1, 1, 1),
(4, 'SCPERM', 'c4ca4238a0b923820dcc509a6f75849b', NULL, 1, 1, 1, 0, 1),
(64, 'ICTRFM', NULL, 1, 1, 1, 1, 1, 1),
(65, 'ICSUPP', NULL, 1, 1, 1, 1, 1, 1),
(66, 'ICLEND', NULL, 1, 1, 1, 1, 1, 1),
(67, 'ICPURC', NULL, 1, 1, 1, 1, 1, 1),
(68, 'ICTRRC', NULL, 1, 1, 1, 1, 1, 1),
(69, 'ICTRWH', NULL, 1, 1, 1, 1, 1, 1),
(70, 'ICODPR', NULL, 1, 1, 1, 1, 1, 1),
(71, 'ICODQC', NULL, 1, 1, 1, 1, 1, 1),
(72, 'ICODDO', NULL, 1, 1, 1, 1, 1, 1),
(73, 'ICODIV', NULL, 1, 1, 1, 1, 1, 1),
(74, 'ICCKBF', NULL, 1, 1, 1, 1, 1, 1),
(75, 'ICCKCN', NULL, 1, 1, 1, 1, 1, 1),
(76, 'ICCKMV', NULL, 1, 1, 1, 1, 1, 1),
(77, 'SOODSO', NULL, 1, 1, 1, 1, 1, 1),
(78, 'SOODSP', NULL, 1, 1, 1, 1, 1, 1),
(79, 'SOCCSO', NULL, 1, 1, 1, 1, 1, 1),
(80, 'SOCCTR', NULL, 1, 1, 1, 1, 1, 1),
(81, 'SCCONF', NULL, 1, 1, 1, 1, 1, 1),
(82, 'SCUSER', NULL, 1, 1, 1, 1, 1, 1),
(83, 'SCPROF', NULL, 1, 1, 1, 1, 1, 1),
(84, 'SCPERM', NULL, 1, 1, 1, 1, 1, 1),
(85, 'SCPOLI', NULL, 1, 1, 1, 1, 1, 1),
(86, 'SCRULE', NULL, 1, 1, 1, 1, 1, 1),
(87, 'SCBGSP', NULL, 1, 1, 1, 1, 1, 1),
(88, 'SCBGSU', NULL, 1, 1, 1, 1, 1, 1),
(89, 'DBPROD', NULL, 1, 1, 1, 1, 1, 1),
(90, 'DBCHAN', NULL, 1, 1, 1, 1, 1, 1),
(91, 'DBPAYM', NULL, 1, 1, 1, 1, 1, 1),
(92, 'DBCUST', NULL, 1, 1, 1, 1, 1, 1),
(93, 'DBCARE', NULL, 1, 1, 1, 1, 1, 1),
(94, 'DBCLAS', NULL, 1, 1, 1, 1, 1, 1),
(95, 'DBCGRP', NULL, 1, 1, 1, 1, 1, 1),
(96, 'DBCKIN', NULL, 1, 1, 1, 1, 1, 1),
(97, 'DBCTYP', NULL, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `name`) VALUES
(1, 'ADMIN'),
(4, 'Stock manager'),
(2, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `saleman`
--

CREATE TABLE `saleman` (
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `uname` varchar(50) NOT NULL COMMENT 'User Name',
  `pwd` varchar(100) NOT NULL COMMENT 'Password',
  `name` varchar(100) NOT NULL COMMENT 'Display name',
  `uid` varchar(32) NOT NULL COMMENT 'Unique id',
  `skey` varchar(100) NOT NULL COMMENT 'Secret key',
  `id_profile` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `uname`, `pwd`, `name`, `uid`, `skey`, `id_profile`, `active`, `date_add`) VALUES
(1, 'admin', '$2y$10$6KsTGHpGWBznzFNWCCxU7.1Eh7zwnLDCPJedh2X9p17V2eDBL48ha', 'ผู้ดูแลระบบ', 'c4ca4238a0b923820dcc509a6f75849b', '81dc9bdb52d04dc20036dbd8313ed055', 1, 1, '2019-06-25 20:13:50'),
(2, 'sutouch', '$2y$10$uV5yB6ERsWZalyEG5.33J.RH0QlYFhH2jypRuV9pWQ01phpggWyry', 'เจ้าหน้าที่ IT', 'c4ca4238a0b923820dcc509a6f75849a', '81dc9bdb52d04dc20036dbd8313ed056', 1, 1, '2019-06-25 20:13:50'),
(6, 'ALEX', '$2y$10$TcLo3ne8S91ktZygZTeWp.qKDwM8IHwOCuGBFNcNblCMXrF783TOm', 'ALEX', '0e79a32fcaa8c079113c0ffca0e404f2', '', 2, 0, '2019-07-03 21:29:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`code`),
  ADD KEY `group_code` (`group_code`),
  ADD KEY `kind_code` (`kind_code`),
  ADD KEY `type_code` (`type_code`),
  ADD KEY `class_code` (`class_code`),
  ADD KEY `area_code` (`area_code`),
  ADD KEY `sale_code` (`sale_code`);

--
-- Indexes for table `customer_area`
--
ALTER TABLE `customer_area`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `customer_class`
--
ALTER TABLE `customer_class`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `customer_group`
--
ALTER TABLE `customer_group`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `customer_kind`
--
ALTER TABLE `customer_kind`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `customer_type`
--
ALTER TABLE `customer_type`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `discount_policy`
--
ALTER TABLE `discount_policy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `start_date` (`start_date`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`code`),
  ADD KEY `groupCode` (`group_code`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `menu_group`
--
ALTER TABLE `menu_group`
  ADD PRIMARY KEY (`code`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `position` (`position`),
  ADD KEY `isActive` (`active`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `fk_channels_code` (`channels_code`),
  ADD KEY `fk_payment_code` (`payment_code`),
  ADD KEY `fk_customer` (`customer_code`),
  ADD KEY `fk_sale_code` (`sale_code`),
  ADD KEY `role` (`role`),
  ADD KEY `ref_code` (`ref_code`),
  ADD KEY `state` (`state`);

--
-- Indexes for table `order_role`
--
ALTER TABLE `order_role`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu` (`menu`,`uid`,`id_profile`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `saleman`
--
ALTER TABLE `saleman`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uname` (`uname`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD KEY `active` (`active`),
  ADD KEY `fk_profile_id` (`id_profile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discount_policy`
--
ALTER TABLE `discount_policy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customer_area` FOREIGN KEY (`area_code`) REFERENCES `customer_area` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer_class` FOREIGN KEY (`class_code`) REFERENCES `customer_class` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer_group` FOREIGN KEY (`group_code`) REFERENCES `customer_group` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer_kind` FOREIGN KEY (`kind_code`) REFERENCES `customer_kind` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer_type` FOREIGN KEY (`type_code`) REFERENCES `customer_type` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_saleman` FOREIGN KEY (`sale_code`) REFERENCES `saleman` (`code`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_group` FOREIGN KEY (`group_code`) REFERENCES `menu_group` (`code`) ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_channels_code` FOREIGN KEY (`channels_code`) REFERENCES `channels` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_code`) REFERENCES `customers` (`code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payment_code` FOREIGN KEY (`payment_code`) REFERENCES `payment_method` (`code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sale_code` FOREIGN KEY (`sale_code`) REFERENCES `saleman` (`code`) ON UPDATE CASCADE;

--
-- Constraints for table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `fk_menu_code` FOREIGN KEY (`menu`) REFERENCES `menu` (`code`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_profile_id` FOREIGN KEY (`id_profile`) REFERENCES `profile` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
