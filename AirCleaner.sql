/*
MySQL Data Transfer
Source Host: 104.199.244.108
Source Database: AirCleaner
Target Host: 104.199.244.108
Target Database: AirCleaner
Date: 2017/4/1 下午 01:44:05
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for City
-- ----------------------------
DROP TABLE IF EXISTS `City`;
CREATE TABLE `City` (
  `City_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `city_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`City_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for Group
-- ----------------------------
DROP TABLE IF EXISTS `Group`;
CREATE TABLE `Group` (
  `Group_ID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Group_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Group_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for Product
-- ----------------------------
DROP TABLE IF EXISTS `Product`;
CREATE TABLE `Product` (
  `UUID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Product_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Sale_city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`UUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for Product_sale
-- ----------------------------
DROP TABLE IF EXISTS `Product_sale`;
CREATE TABLE `Product_sale` (
  `UUID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `product_Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `frimeware_version` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `localtion_cityid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `customer_ID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sales_ID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `regedit_date` int(11) NOT NULL DEFAULT '0' COMMENT '用途不明',
  `group_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `room_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `room_size` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `IPaddress` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `BLEconnect` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`UUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for Product_status
-- ----------------------------
DROP TABLE IF EXISTS `Product_status`;
CREATE TABLE `Product_status` (
  `UUID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `datetime` int(11) NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fan_level` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `PM25` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `temperature` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `humidity` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `VOC` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `CO2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`UUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for Room
-- ----------------------------
DROP TABLE IF EXISTS `Room`;
CREATE TABLE `Room` (
  `Room_ID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Room_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `room_pic` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Room_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for Room_size
-- ----------------------------
DROP TABLE IF EXISTS `Room_size`;
CREATE TABLE `Room_size` (
  `Roomsize_ID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL DEFAULT '0',
  `city_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Roomsize_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sales
-- ----------------------------
DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `sales_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `superior` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `salecity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sales_id`),
  KEY `superior` (`superior`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for User
-- ----------------------------
DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `User_ID` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者編號',
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT '0',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `otp` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '識別字串',
  `login_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '入時間登',
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `otp` (`otp`),
  UNIQUE KEY `userName` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records 
-- ----------------------------
