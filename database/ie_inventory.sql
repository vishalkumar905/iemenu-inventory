-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2022 at 04:05 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ie_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `ie_categories`
--

CREATE TABLE `ie_categories` (
  `id` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `parentId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `createdOn` int(11) NOT NULL,
  `categoryUrlTitle` varchar(255) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_categories`
--

INSERT INTO `ie_categories` (`id`, `categoryName`, `parentId`, `userId`, `createdOn`, `categoryUrlTitle`, `isDeleted`) VALUES
(1, 'Food', NULL, 0, 1601983906, NULL, NULL),
(2, 'Packaging', NULL, 0, 1601983906, NULL, NULL),
(3, 'House Keeping', NULL, 0, 1601983906, NULL, NULL),
(4, 'Consumables', NULL, 0, 1601983906, 'consumables', NULL),
(5, 'Maintenance', NULL, 0, 1601983945, 'maintenance', NULL),
(8, 'Machrniery Food', 2, 1, 1601989971, 'machrniery-food', NULL),
(9, 'New Macheniry', 2, 1, 1602124796, 'new-macheniry', NULL),
(10, 'Cold Press', 2, 1, 1602427796, 'cold-press', NULL),
(11, 'Vegetable', 2, 1, 1603643073, 'vegetable', NULL),
(12, 'Fruit', 2, 1, 1603643150, 'fruit', NULL),
(13, 'Fruit', 2, 1, 1603872028, 'fruit', NULL),
(14, 'Testing', 1, 1, 1603874597, 'testing', NULL),
(17, 'STATIONERY', 3, 1, 1605686220, 'stationery', NULL),
(18, 'BAKERY', 1, 1, 1605688886, 'bakery', NULL),
(19, 'PREMIX', 1, 1, 1605690800, 'premix', NULL),
(20, 'COUNTY', 1, 1, 1605691603, 'county', NULL),
(21, 'DRY FRUIT', 1, 1, 1605696085, 'dry-fruit', NULL),
(22, 'GROCERY', 1, 1, 1605696351, 'grocery', NULL),
(23, 'PAPER PRODUCTS', 2, 1, 1605696648, 'paper-product', NULL),
(24, 'EQUIPMENTS', 5, 1, 1605699857, 'equipments', NULL),
(25, 'HYGIENE', 3, 1, 1605700113, 'hygiene', NULL),
(26, 'CLEANING', 3, 1, 1605700257, 'cleaning', NULL),
(27, 'DRESS', 3, 1, 1605702861, 'dress', NULL),
(28, 'LIQUID', 1, 1, 1605702911, 'liquid', NULL),
(29, 'DAIRY', 1, 1, 1605702980, 'dairy', NULL),
(32, 'Machrniery Food', 2, 28, 1601989971, 'machrniery-food', NULL),
(33, 'New Macheniry', 2, 28, 1602124796, 'new-macheniry', NULL),
(34, 'Cold Press', 2, 28, 1602427796, 'cold-press', NULL),
(35, 'Vegetable', 2, 28, 1603643073, 'vegetable', NULL),
(36, 'Fruit', 2, 28, 1603643150, 'fruit', NULL),
(37, 'Fruit', 2, 28, 1603872028, 'fruit', NULL),
(38, 'Testing', 1, 28, 1603874597, 'testing', NULL),
(39, 'STATIONERY', 3, 28, 1605686220, 'stationery', NULL),
(40, 'BAKERY', 1, 28, 1605688886, 'bakery', NULL),
(41, 'PREMIX', 1, 28, 1605690800, 'premix', NULL),
(42, 'COUNTY', 1, 28, 1605691603, 'county', NULL),
(43, 'DRY FRUIT', 1, 28, 1605696085, 'dry-fruit', NULL),
(44, 'GROCERY', 1, 28, 1605696351, 'grocery', NULL),
(45, 'PAPER PRODUCTS', 2, 28, 1605696648, 'paper-product', NULL),
(46, 'EQUIPMENTS', 5, 28, 1605699857, 'equipments', NULL),
(47, 'HYGIENE', 3, 28, 1605700113, 'hygiene', NULL),
(48, 'CLEANING', 3, 28, 1605700257, 'cleaning', NULL),
(49, 'DRESS', 3, 28, 1605702861, 'dress', NULL),
(50, 'LIQUID', 1, 28, 1605702911, 'liquid', NULL),
(51, 'DAIRY', 1, 28, 1605702980, 'dairy', NULL),
(52, 'Food', NULL, 17, 1615467782, 'food', NULL),
(53, 'Packaging', NULL, 17, 1615467782, 'packaging', NULL),
(54, 'House Keeping', NULL, 17, 1615467782, 'house-keeping', NULL),
(55, 'Consumables', NULL, 17, 1615467782, 'consumables', NULL),
(56, 'Maintenance', NULL, 17, 1615467782, 'maintenance', NULL),
(57, 'Sauce', 1, 17, 1618737564, 'sauce', NULL),
(58, 'Salt', 1, 17, 1623148519, 'salt', NULL),
(59, 'Flour', 1, 17, 1623148612, 'flour', NULL),
(60, 'Masala', 1, 17, 1623903922, 'masala', NULL),
(61, 'Sugar', 1, 17, 1623903990, 'sugar', NULL),
(62, 'Dal', 1, 17, 1627465932, 'dal', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_closing_stocks`
--

CREATE TABLE `ie_closing_stocks` (
  `id` int(11) NOT NULL,
  `storeId` int(11) DEFAULT NULL,
  `openingStockNumber` int(11) DEFAULT NULL,
  `productId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `productSiUnitId` int(11) NOT NULL,
  `closingStockNumber` int(11) NOT NULL,
  `productQuantity` decimal(10,3) NOT NULL,
  `productQuantityConversion` decimal(40,4) DEFAULT 0.0000,
  `productBaseQuantityConversion` decimal(40,4) DEFAULT NULL,
  `productUnitPrice` decimal(20,2) NOT NULL,
  `productSubtotal` decimal(20,2) NOT NULL,
  `productTax` decimal(10,2) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `createdOn` int(11) DEFAULT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_closing_stocks`
--

INSERT INTO `ie_closing_stocks` (`id`, `storeId`, `openingStockNumber`, `productId`, `userId`, `productSiUnitId`, `closingStockNumber`, `productQuantity`, `productQuantityConversion`, `productBaseQuantityConversion`, `productUnitPrice`, `productSubtotal`, `productTax`, `comment`, `createdOn`, `updatedOn`, `isDeleted`) VALUES
(1, NULL, 1, 104, 1, 39, 1, '30.000', '30.0000', NULL, '0.00', '0.00', NULL, 'Testing for report section', 1610780892, NULL, NULL),
(2, NULL, 1, 105, 1, 39, 1, '50.000', '50.0000', NULL, '0.00', '0.00', NULL, 'Testing Again for report section', 1610780892, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_excel_imports`
--

CREATE TABLE `ie_excel_imports` (
  `excelImportId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `importType` tinyint(4) NOT NULL,
  `isSuccess` tinyint(4) DEFAULT NULL,
  `excelData` text DEFAULT NULL,
  `excelParsedData` text DEFAULT NULL,
  `createdOn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_excel_imports`
--

INSERT INTO `ie_excel_imports` (`excelImportId`, `userId`, `importType`, `isSuccess`, `excelData`, `excelParsedData`, `createdOn`) VALUES
(1, 17, 2, NULL, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini   Yellow\",2,\"KG\"],[null,null,null,null,null,\"Moong  Sprouts\",1,\"KG1\"],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[5,41,\"Grape Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', NULL, 1617863343),
(2, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini   Yellow\",2,\"KG\"],[null,null,null,null,null,\"Moong  Sprouts\",1,\"KG1\"],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[5,41,\"Grape Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"37\":{\"itemId\":37,\"itemName\":\"Fresh Lime Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini   Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productSiUnitId\":\"33\",\"errorMessage\":\"Product is not found\"},{\"productName\":\"Moong  Sprouts\",\"productQty\":1,\"productUnit\":\"KG1\",\"errorMessage\":\"Product is not found, Unit is not found\"}]}}', 1617863654),
(3, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[5,41,\"Grape Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '[]', 1617877634),
(4, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[5,41,\"Grape Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '[]', 1617878252),
(5, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '[]', 1617878314),
(6, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '[]', 1617878586),
(7, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"},{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]}}', 1617878877),
(8, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"},{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]}}', 1617879458),
(9, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":0,\"itemName\":\"\",\"itemQty\":null,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]}}', 1617880059),
(10, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]}}', 1617880623),
(11, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"47\":{\"itemId\":47,\"itemName\":\"Banana Shake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]}}', 1617882623),
(12, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\",\"Error\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null,\"Recipe already configured\"],[2,38,\"Mango Juice\",1,null,null,null,null,\"Recipe already configured\"],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\",\"Recipe already configured\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\",\"Recipe already configured\"],[4,40,\"Orange Juice\",1,null,\"Moong Sprouts\",2,\"KG\",\"Recipe already configured\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\",\"Product is not found\"],[6,42,\"Watermelon Juice\",1,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,\"Moong Sprouts\",2,\"KG\",\"Product is not found\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\",\"Product is not found\"],[8,48,\"Strawberry Milkshake\",1,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null,null]]', '{\"37\":{\"itemId\":37,\"itemName\":\"Fresh Lime Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"38\":{\"itemId\":38,\"itemName\":\"Mango Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"42\":{\"itemId\":42,\"itemName\":\"Watermelon Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[]},\"47\":{\"itemId\":47,\"itemName\":\"Banana Shake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"48\":{\"itemId\":48,\"itemName\":\"Strawberry Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"49\":{\"itemId\":49,\"itemName\":\"Vanilla Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"50\":{\"itemId\":50,\"itemName\":\"Apple Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"51\":{\"itemId\":51,\"itemName\":\"Chickoo Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"52\":{\"itemId\":52,\"itemName\":\"Aam Panna\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"53\":{\"itemId\":53,\"itemName\":\"Masala Butter Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"54\":{\"itemId\":54,\"itemName\":\"Badam Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"55\":{\"itemId\":55,\"itemName\":\"Jal Jeera\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"56\":{\"itemId\":56,\"itemName\":\"ICE Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"57\":{\"itemId\":57,\"itemName\":\"Indian Coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"58\":{\"itemId\":58,\"itemName\":\"Masala Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"59\":{\"itemId\":59,\"itemName\":\"Hot Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"60\":{\"itemId\":60,\"itemName\":\"Kashmiri Kawa\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"61\":{\"itemId\":61,\"itemName\":\"Hot Gulabjamun\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"62\":{\"itemId\":62,\"itemName\":\"Sponge Rasgulla\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"63\":{\"itemId\":63,\"itemName\":\"Besan Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"64\":{\"itemId\":64,\"itemName\":\"Motichoor Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"65\":{\"itemId\":65,\"itemName\":\"Rasmalai\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"66\":{\"itemId\":66,\"itemName\":\"Kaju Barfi\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"75\":{\"itemId\":75,\"itemName\":\"Steamed Rice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"681\":{\"itemId\":681,\"itemName\":\"TEA\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"682\":{\"itemId\":682,\"itemName\":\"coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"890\":{\"itemId\":890,\"itemName\":\"mutton\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]}}', 1618828554),
(13, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\",\"Error\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null,\"Recipe already configured\"],[2,38,\"Mango Juice\",1,null,null,null,null,\"Recipe already configured\"],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\",\"Recipe already configured\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\",\"Recipe already configured\"],[4,40,\"Orange Juice\",1,null,\"Moong Sprouts\",2,\"KG\",\"Recipe already configured\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\",\"Product is not found\"],[6,42,\"Watermelon Juice\",1,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,\"Moong Sprouts\",2,\"KG\",\"Product is not found\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\",\"Product is not found\"],[8,48,\"Strawberry Milkshake\",1,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null,null]]', '{\"37\":{\"itemId\":37,\"itemName\":\"Fresh Lime Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"38\":{\"itemId\":38,\"itemName\":\"Mango Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"42\":{\"itemId\":42,\"itemName\":\"Watermelon Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[]},\"47\":{\"itemId\":47,\"itemName\":\"Banana Shake\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"48\":{\"itemId\":48,\"itemName\":\"Strawberry Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"49\":{\"itemId\":49,\"itemName\":\"Vanilla Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"50\":{\"itemId\":50,\"itemName\":\"Apple Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"51\":{\"itemId\":51,\"itemName\":\"Chickoo Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"52\":{\"itemId\":52,\"itemName\":\"Aam Panna\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"53\":{\"itemId\":53,\"itemName\":\"Masala Butter Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"54\":{\"itemId\":54,\"itemName\":\"Badam Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"55\":{\"itemId\":55,\"itemName\":\"Jal Jeera\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"56\":{\"itemId\":56,\"itemName\":\"ICE Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"57\":{\"itemId\":57,\"itemName\":\"Indian Coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"58\":{\"itemId\":58,\"itemName\":\"Masala Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"59\":{\"itemId\":59,\"itemName\":\"Hot Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"60\":{\"itemId\":60,\"itemName\":\"Kashmiri Kawa\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"61\":{\"itemId\":61,\"itemName\":\"Hot Gulabjamun\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"62\":{\"itemId\":62,\"itemName\":\"Sponge Rasgulla\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"63\":{\"itemId\":63,\"itemName\":\"Besan Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"64\":{\"itemId\":64,\"itemName\":\"Motichoor Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"65\":{\"itemId\":65,\"itemName\":\"Rasmalai\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"66\":{\"itemId\":66,\"itemName\":\"Kaju Barfi\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"75\":{\"itemId\":75,\"itemName\":\"Steamed Rice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"681\":{\"itemId\":681,\"itemName\":\"TEA\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"682\":{\"itemId\":682,\"itemName\":\"coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"890\":{\"itemId\":890,\"itemName\":\"mutton\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]}}', 1618828567);
INSERT INTO `ie_excel_imports` (`excelImportId`, `userId`, `importType`, `isSuccess`, `excelData`, `excelParsedData`, `createdOn`) VALUES
(14, 17, 2, 1, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"37\":{\"itemId\":37,\"itemName\":\"Fresh Lime Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"38\":{\"itemId\":38,\"itemName\":\"Mango Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"42\":{\"itemId\":42,\"itemName\":\"Watermelon Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[]},\"47\":{\"itemId\":47,\"itemName\":\"Banana Shake\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"48\":{\"itemId\":48,\"itemName\":\"Strawberry Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"49\":{\"itemId\":49,\"itemName\":\"Vanilla Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"50\":{\"itemId\":50,\"itemName\":\"Apple Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"51\":{\"itemId\":51,\"itemName\":\"Chickoo Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"52\":{\"itemId\":52,\"itemName\":\"Aam Panna\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"53\":{\"itemId\":53,\"itemName\":\"Masala Butter Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"54\":{\"itemId\":54,\"itemName\":\"Badam Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"55\":{\"itemId\":55,\"itemName\":\"Jal Jeera\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"56\":{\"itemId\":56,\"itemName\":\"ICE Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"57\":{\"itemId\":57,\"itemName\":\"Indian Coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"58\":{\"itemId\":58,\"itemName\":\"Masala Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"59\":{\"itemId\":59,\"itemName\":\"Hot Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"60\":{\"itemId\":60,\"itemName\":\"Kashmiri Kawa\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"61\":{\"itemId\":61,\"itemName\":\"Hot Gulabjamun\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"62\":{\"itemId\":62,\"itemName\":\"Sponge Rasgulla\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"63\":{\"itemId\":63,\"itemName\":\"Besan Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"64\":{\"itemId\":64,\"itemName\":\"Motichoor Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"65\":{\"itemId\":65,\"itemName\":\"Rasmalai\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"66\":{\"itemId\":66,\"itemName\":\"Kaju Barfi\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"75\":{\"itemId\":75,\"itemName\":\"Steamed Rice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"681\":{\"itemId\":681,\"itemName\":\"TEA\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"682\":{\"itemId\":682,\"itemName\":\"coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"890\":{\"itemId\":890,\"itemName\":\"mutton\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]}}', 1618828577),
(15, 17, 2, 0, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,\"Moong Spddrouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"37\":{\"itemId\":37,\"itemName\":\"Fresh Lime Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"38\":{\"itemId\":38,\"itemName\":\"Mango Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"42\":{\"itemId\":42,\"itemName\":\"Watermelon Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[]},\"47\":{\"itemId\":47,\"itemName\":\"Banana Shake\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Spddrouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productSiUnitId\":\"33\",\"errorMessage\":\"Product is not found\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"48\":{\"itemId\":48,\"itemName\":\"Strawberry Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"49\":{\"itemId\":49,\"itemName\":\"Vanilla Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"50\":{\"itemId\":50,\"itemName\":\"Apple Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"51\":{\"itemId\":51,\"itemName\":\"Chickoo Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"52\":{\"itemId\":52,\"itemName\":\"Aam Panna\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"53\":{\"itemId\":53,\"itemName\":\"Masala Butter Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"54\":{\"itemId\":54,\"itemName\":\"Badam Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"55\":{\"itemId\":55,\"itemName\":\"Jal Jeera\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"56\":{\"itemId\":56,\"itemName\":\"ICE Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"57\":{\"itemId\":57,\"itemName\":\"Indian Coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"58\":{\"itemId\":58,\"itemName\":\"Masala Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"59\":{\"itemId\":59,\"itemName\":\"Hot Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"60\":{\"itemId\":60,\"itemName\":\"Kashmiri Kawa\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"61\":{\"itemId\":61,\"itemName\":\"Hot Gulabjamun\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"62\":{\"itemId\":62,\"itemName\":\"Sponge Rasgulla\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"63\":{\"itemId\":63,\"itemName\":\"Besan Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"64\":{\"itemId\":64,\"itemName\":\"Motichoor Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"65\":{\"itemId\":65,\"itemName\":\"Rasmalai\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"66\":{\"itemId\":66,\"itemName\":\"Kaju Barfi\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"75\":{\"itemId\":75,\"itemName\":\"Steamed Rice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"681\":{\"itemId\":681,\"itemName\":\"TEA\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"682\":{\"itemId\":682,\"itemName\":\"coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"890\":{\"itemId\":890,\"itemName\":\"mutton\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]}}', 1618830500),
(16, 17, 2, 0, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,\"Moong Spddrouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"37\":{\"itemId\":37,\"itemName\":\"Fresh Lime Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"38\":{\"itemId\":38,\"itemName\":\"Mango Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"42\":{\"itemId\":42,\"itemName\":\"Watermelon Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"47\":{\"itemId\":47,\"itemName\":\"Banana Shake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Spddrouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productSiUnitId\":\"33\",\"errorMessage\":\"Product is not found\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"48\":{\"itemId\":48,\"itemName\":\"Strawberry Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"49\":{\"itemId\":49,\"itemName\":\"Vanilla Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"50\":{\"itemId\":50,\"itemName\":\"Apple Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"51\":{\"itemId\":51,\"itemName\":\"Chickoo Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"52\":{\"itemId\":52,\"itemName\":\"Aam Panna\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"53\":{\"itemId\":53,\"itemName\":\"Masala Butter Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"54\":{\"itemId\":54,\"itemName\":\"Badam Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"55\":{\"itemId\":55,\"itemName\":\"Jal Jeera\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"56\":{\"itemId\":56,\"itemName\":\"ICE Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"57\":{\"itemId\":57,\"itemName\":\"Indian Coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"58\":{\"itemId\":58,\"itemName\":\"Masala Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"59\":{\"itemId\":59,\"itemName\":\"Hot Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"60\":{\"itemId\":60,\"itemName\":\"Kashmiri Kawa\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"61\":{\"itemId\":61,\"itemName\":\"Hot Gulabjamun\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"62\":{\"itemId\":62,\"itemName\":\"Sponge Rasgulla\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"63\":{\"itemId\":63,\"itemName\":\"Besan Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"64\":{\"itemId\":64,\"itemName\":\"Motichoor Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"65\":{\"itemId\":65,\"itemName\":\"Rasmalai\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"66\":{\"itemId\":66,\"itemName\":\"Kaju Barfi\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"75\":{\"itemId\":75,\"itemName\":\"Steamed Rice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"681\":{\"itemId\":681,\"itemName\":\"TEA\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"682\":{\"itemId\":682,\"itemName\":\"coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"890\":{\"itemId\":890,\"itemName\":\"mutton\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]}}', 1618893839),
(17, 17, 2, 0, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,\"Moong Spddrouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"37\":{\"itemId\":37,\"itemName\":\"Fresh Lime Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"38\":{\"itemId\":38,\"itemName\":\"Mango Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"errorMessage\":\"Recipe already configured\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"42\":{\"itemId\":42,\"itemName\":\"Watermelon Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"47\":{\"itemId\":47,\"itemName\":\"Banana Shake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Spddrouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productSiUnitId\":\"33\",\"errorMessage\":\"Product is not found\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"48\":{\"itemId\":48,\"itemName\":\"Strawberry Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"49\":{\"itemId\":49,\"itemName\":\"Vanilla Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"50\":{\"itemId\":50,\"itemName\":\"Apple Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"51\":{\"itemId\":51,\"itemName\":\"Chickoo Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"52\":{\"itemId\":52,\"itemName\":\"Aam Panna\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"53\":{\"itemId\":53,\"itemName\":\"Masala Butter Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"54\":{\"itemId\":54,\"itemName\":\"Badam Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"55\":{\"itemId\":55,\"itemName\":\"Jal Jeera\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"56\":{\"itemId\":56,\"itemName\":\"ICE Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"57\":{\"itemId\":57,\"itemName\":\"Indian Coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"58\":{\"itemId\":58,\"itemName\":\"Masala Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"59\":{\"itemId\":59,\"itemName\":\"Hot Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"60\":{\"itemId\":60,\"itemName\":\"Kashmiri Kawa\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"61\":{\"itemId\":61,\"itemName\":\"Hot Gulabjamun\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"62\":{\"itemId\":62,\"itemName\":\"Sponge Rasgulla\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"63\":{\"itemId\":63,\"itemName\":\"Besan Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"64\":{\"itemId\":64,\"itemName\":\"Motichoor Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"65\":{\"itemId\":65,\"itemName\":\"Rasmalai\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"66\":{\"itemId\":66,\"itemName\":\"Kaju Barfi\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"75\":{\"itemId\":75,\"itemName\":\"Steamed Rice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"681\":{\"itemId\":681,\"itemName\":\"TEA\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"682\":{\"itemId\":682,\"itemName\":\"coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"890\":{\"itemId\":890,\"itemName\":\"mutton\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]}}', 1618893870),
(18, 17, 2, 0, '[[\"SN\",\"Item Id\",\"Item Name\",\"Item Qty\",\"Item Unit\",\"Stock Item\",\"Stock Qty\",\"Stock Unit\"],[1,37,\"Fresh Lime Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[2,38,\"Mango Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],[3,39,\"Pineapple Juice\",1,null,\"Moong Sprouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[4,40,\"Orange Juice\",1,null,null,null,null],[null,null,null,null,null,\"Moong Sprouts\",2,\"KG\"],[5,41,\"Grape Juice\",1,null,\"Zucchini Yellow\",2,\"KG\"],[null,null,null,null,null,null,null,null],[6,42,\"Watermelon Juice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[7,47,\"Banana Shake\",1,null,\"Moong Spddrouts\",2,\"KG\"],[null,null,null,null,null,\"Zucchini Yellow\",2,\"KG\"],[8,48,\"Strawberry Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[9,49,\"Vanilla Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[10,50,\"Apple Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[11,51,\"Chickoo Milkshake\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[12,52,\"Aam Panna\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[13,53,\"Masala Butter Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[14,54,\"Badam Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[15,55,\"Jal Jeera\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[16,56,\"ICE Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[17,57,\"Indian Coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[18,58,\"Masala Tea\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[19,59,\"Hot Milk\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[20,60,\"Kashmiri Kawa\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[21,61,\"Hot Gulabjamun\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[22,62,\"Sponge Rasgulla\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[23,63,\"Besan Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[24,64,\"Motichoor Laddu\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[25,65,\"Rasmalai\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[26,66,\"Kaju Barfi\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[27,75,\"Steamed Rice\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[28,681,\"TEA\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[29,682,\"coffee\",1,null,null,null,null],[null,null,null,null,null,null,null,null],[30,890,\"mutton\",1,null,null,null,null]]', '{\"37\":{\"itemId\":37,\"itemName\":\"Fresh Lime Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"38\":{\"itemId\":38,\"itemName\":\"Mango Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"39\":{\"itemId\":39,\"itemName\":\"Pineapple Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"40\":{\"itemId\":40,\"itemName\":\"Orange Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Sprouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"329\",\"productSiUnitId\":\"33\"}]},\"41\":{\"itemId\":41,\"itemName\":\"Grape Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"42\":{\"itemId\":42,\"itemName\":\"Watermelon Juice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"47\":{\"itemId\":47,\"itemName\":\"Banana Shake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[{\"productName\":\"Moong Spddrouts\",\"productQty\":2,\"productUnit\":\"KG\",\"productSiUnitId\":\"33\",\"errorMessage\":\"Product is not found\"},{\"productName\":\"Zucchini Yellow\",\"productQty\":2,\"productUnit\":\"KG\",\"productId\":\"328\",\"productSiUnitId\":\"33\"}]},\"48\":{\"itemId\":48,\"itemName\":\"Strawberry Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"49\":{\"itemId\":49,\"itemName\":\"Vanilla Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"50\":{\"itemId\":50,\"itemName\":\"Apple Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"51\":{\"itemId\":51,\"itemName\":\"Chickoo Milkshake\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"52\":{\"itemId\":52,\"itemName\":\"Aam Panna\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"53\":{\"itemId\":53,\"itemName\":\"Masala Butter Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"54\":{\"itemId\":54,\"itemName\":\"Badam Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"55\":{\"itemId\":55,\"itemName\":\"Jal Jeera\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"56\":{\"itemId\":56,\"itemName\":\"ICE Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"57\":{\"itemId\":57,\"itemName\":\"Indian Coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"58\":{\"itemId\":58,\"itemName\":\"Masala Tea\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"59\":{\"itemId\":59,\"itemName\":\"Hot Milk\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"60\":{\"itemId\":60,\"itemName\":\"Kashmiri Kawa\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"61\":{\"itemId\":61,\"itemName\":\"Hot Gulabjamun\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"62\":{\"itemId\":62,\"itemName\":\"Sponge Rasgulla\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"63\":{\"itemId\":63,\"itemName\":\"Besan Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"64\":{\"itemId\":64,\"itemName\":\"Motichoor Laddu\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"65\":{\"itemId\":65,\"itemName\":\"Rasmalai\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"66\":{\"itemId\":66,\"itemName\":\"Kaju Barfi\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"75\":{\"itemId\":75,\"itemName\":\"Steamed Rice\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"681\":{\"itemId\":681,\"itemName\":\"TEA\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"682\":{\"itemId\":682,\"itemName\":\"coffee\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]},\"890\":{\"itemId\":890,\"itemName\":\"mutton\",\"itemQty\":1,\"itemUnit\":\"\",\"recipes\":[]}}', 1618894012);

-- --------------------------------------------------------

--
-- Table structure for table `ie_logged_in_history`
--

CREATE TABLE `ie_logged_in_history` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `createdOn` int(11) DEFAULT NULL,
  `updatedOn` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ie_opening_stocks`
--

CREATE TABLE `ie_opening_stocks` (
  `id` int(11) NOT NULL,
  `storeId` int(11) DEFAULT NULL,
  `productId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `productSiUnitId` int(11) NOT NULL,
  `openingStockNumber` int(11) NOT NULL,
  `productQuantity` decimal(10,3) NOT NULL,
  `productQuantityConversion` decimal(40,4) DEFAULT 0.0000,
  `productBaseQuantityConversion` decimal(40,4) DEFAULT NULL,
  `productUnitPrice` decimal(20,2) NOT NULL,
  `productSubtotal` decimal(20,2) NOT NULL,
  `productTax` decimal(10,2) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `createdOn` int(11) DEFAULT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_opening_stocks`
--

INSERT INTO `ie_opening_stocks` (`id`, `storeId`, `productId`, `userId`, `productSiUnitId`, `openingStockNumber`, `productQuantity`, `productQuantityConversion`, `productBaseQuantityConversion`, `productUnitPrice`, `productSubtotal`, `productTax`, `comment`, `createdOn`, `updatedOn`, `isDeleted`) VALUES
(1, NULL, 153, 28, 116, 1, '20.000', '20.0000', '20.0000', '20.00', '400.00', NULL, '', 1609143461, NULL, NULL),
(2, NULL, 154, 28, 33, 1, '2.000', '2000.0000', '2.0000', '50.00', '100.00', NULL, '', 1609143461, NULL, NULL),
(3, NULL, 155, 28, 33, 1, '20.000', '20000.0000', '20.0000', '20.00', '400.00', NULL, '', 1609143461, NULL, NULL),
(4, NULL, 156, 28, 33, 1, '20.000', '20000.0000', '20.0000', '123.00', '2460.00', NULL, '', 1609143461, NULL, NULL),
(5, NULL, 157, 28, 116, 1, '2.000', '2.0000', '2.0000', '10.00', '20.00', NULL, '', 1609143461, NULL, NULL),
(6, NULL, 158, 28, 116, 1, '10.000', '10.0000', '10.0000', '15.00', '150.00', NULL, '', 1609143461, NULL, NULL),
(7, NULL, 159, 28, 116, 1, '20.000', '20.0000', '20.0000', '21.00', '420.00', NULL, '', 1609143461, NULL, NULL),
(8, NULL, 160, 28, 33, 1, '20.000', '20000.0000', '20.0000', '10.00', '200.00', NULL, '', 1609143461, NULL, NULL),
(9, NULL, 161, 28, 33, 1, '10.000', '10000.0000', '10.0000', '20.00', '200.00', NULL, '', 1609143461, NULL, NULL),
(10, NULL, 162, 28, 33, 1, '10.000', '10000.0000', '10.0000', '22.00', '220.00', NULL, '', 1609143461, NULL, NULL),
(11, NULL, 1, 1, 116, 1, '20.000', '20.0000', '20.0000', '100.00', '2000.00', NULL, '', 1609143534, NULL, NULL),
(12, NULL, 2, 1, 33, 1, '25.000', '25000.0000', '25.0000', '245.00', '6125.00', NULL, '', 1609143534, NULL, NULL),
(13, NULL, 3, 1, 33, 1, '12.000', '12000.0000', '12.0000', '25.00', '300.00', NULL, '', 1609143534, NULL, NULL),
(14, NULL, 4, 1, 33, 1, '54.000', '54000.0000', '54.0000', '20.00', '1080.00', NULL, '', 1609143534, NULL, NULL),
(15, NULL, 5, 1, 116, 1, '88.000', '88.0000', '88.0000', '20.00', '1760.00', NULL, '', 1609143534, NULL, NULL),
(16, NULL, 6, 1, 116, 1, '21.000', '21.0000', '21.0000', '20.00', '420.00', NULL, '', 1609143534, NULL, NULL),
(17, NULL, 7, 1, 116, 1, '25.000', '25.0000', '25.0000', '20.00', '500.00', NULL, '', 1609143534, NULL, NULL),
(18, NULL, 8, 1, 33, 1, '12.000', '12000.0000', '12.0000', '50.00', '600.00', NULL, '', 1609143534, NULL, NULL),
(19, NULL, 9, 1, 33, 1, '78.000', '78000.0000', '78.0000', '12.00', '936.00', NULL, '', 1609143534, NULL, NULL),
(20, NULL, 10, 1, 33, 1, '12.000', '12000.0000', '12.0000', '56.00', '672.00', NULL, '', 1609143534, NULL, NULL),
(21, NULL, 334, 17, 32, 1, '20.000', '20.0000', '20.0000', '10.00', '200.00', NULL, '', 1618894284, NULL, NULL),
(22, NULL, 305, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(23, NULL, 306, 17, 33, 2, '1.000', '1000.0000', '1.0000', '20.00', '20.00', NULL, '', 1623149026, NULL, NULL),
(24, NULL, 307, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(25, NULL, 308, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(26, NULL, 309, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(27, NULL, 310, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(28, NULL, 311, 17, 33, 2, '1.000', '1000.0000', '1.0000', '2.00', '2.00', NULL, '', 1623149026, NULL, NULL),
(29, NULL, 312, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(30, NULL, 313, 17, 33, 2, '1.000', '1000.0000', '1.0000', '5.00', '5.00', NULL, '', 1623149026, NULL, NULL),
(31, NULL, 314, 17, 33, 2, '1.000', '1000.0000', '1.0000', '1.00', '1.00', NULL, '', 1623149026, NULL, NULL),
(32, NULL, 315, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(33, NULL, 319, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(34, NULL, 322, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(35, NULL, 324, 17, 33, 2, '10.000', '10000.0000', '10.0000', '10.00', '100.00', NULL, '', 1623149026, NULL, NULL),
(36, NULL, 325, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(37, NULL, 326, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(38, NULL, 327, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(39, NULL, 328, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(40, NULL, 329, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(41, NULL, 330, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(42, NULL, 331, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(43, NULL, 332, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(44, NULL, 333, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(45, NULL, 335, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(46, NULL, 336, 17, 33, 2, '1.000', '1000.0000', '1.0000', '10.00', '10.00', NULL, '', 1623149026, NULL, NULL),
(47, NULL, 341, 17, 33, 3, '100.000', '100000.0000', NULL, '10.00', '1000.00', NULL, '', 1627474799, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_order_recipes`
--

CREATE TABLE `ie_order_recipes` (
  `id` int(11) NOT NULL,
  `storeId` int(11) DEFAULT NULL,
  `productId` int(11) NOT NULL,
  `orderId` varchar(225) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `productSiUnitId` int(11) NOT NULL,
  `openingStockNumber` int(11) NOT NULL,
  `productQuantity` decimal(10,3) NOT NULL,
  `productQuantityConversion` decimal(40,4) DEFAULT 0.0000,
  `orderProductQuantity` decimal(10,3) DEFAULT NULL,
  `recipeProductQuantity` decimal(10,3) DEFAULT NULL,
  `productUnitPrice` decimal(20,2) NOT NULL,
  `productSubtotal` decimal(20,2) NOT NULL,
  `productTax` decimal(10,2) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `orderStatus` tinyint(4) DEFAULT NULL,
  `createdOn` int(11) DEFAULT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_order_recipes`
--

INSERT INTO `ie_order_recipes` (`id`, `storeId`, `productId`, `orderId`, `userId`, `productSiUnitId`, `openingStockNumber`, `productQuantity`, `productQuantityConversion`, `orderProductQuantity`, `recipeProductQuantity`, `productUnitPrice`, `productSubtotal`, `productTax`, `comment`, `orderStatus`, `createdOn`, `updatedOn`, `isDeleted`) VALUES
(1, NULL, 338, '10000147', 17, 32, 2, '30.000', '30.0000', '1.000', '30.000', '0.00', '0.00', NULL, NULL, 2, 1624458633, NULL, NULL),
(2, NULL, 331, '10000147', 17, 33, 2, '10.000', '10000.0000', '1.000', '10.000', '0.00', '0.00', NULL, NULL, 2, 1624458633, NULL, NULL),
(3, NULL, 337, '10000148', 17, 32, 2, '20.000', '20.0000', '1.000', '20.000', '0.00', '0.00', NULL, NULL, 2, 1624542889, 1624543465, NULL),
(4, NULL, 338, '10000148', 17, 32, 2, '100.000', '100.0000', '1.000', '100.000', '0.00', '0.00', NULL, NULL, 2, 1624542889, 1624543465, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_products`
--

CREATE TABLE `ie_products` (
  `id` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productCode` varchar(255) NOT NULL,
  `productType` int(11) DEFAULT NULL,
  `productImage` varchar(255) DEFAULT NULL,
  `productUnit` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `productSiUnits` text DEFAULT NULL,
  `hsnCode` varchar(255) NOT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `shelfLife` tinytext NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `uploadFromExcel` tinyint(4) NOT NULL DEFAULT 0,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_products`
--

INSERT INTO `ie_products` (`id`, `productName`, `productCode`, `productType`, `productImage`, `productUnit`, `categoryId`, `productSiUnits`, `hsnCode`, `createdOn`, `updatedOn`, `shelfLife`, `userId`, `uploadFromExcel`, `isDeleted`) VALUES
(1, 'Balsamic Vinegar', 'VK290', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(2, 'Black Pepper Whole', 'VK291', 2, NULL, 0, 22, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(3, 'Deggi Mirch', 'VK292', 2, NULL, 0, 22, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(4, 'Black salt', 'VK293', 2, NULL, 0, 22, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(5, 'Tobasco Sauce', 'VK294', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(6, 'White Vinegar', 'VK295', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(7, 'Gherkins', 'VK296', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(8, 'Fennel Seeds', 'VK297', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(9, 'Tomato sauce Readymade', 'VK298', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(10, 'White sauce Readymade', 'VK299', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(11, 'Candy', 'VK300', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(12, 'pesto', 'VK301', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(13, 'dry Basil', 'VK302', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(14, 'BBQ Sauce', 'VK303', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(15, 'English Mustard CCF', 'VK304', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(16, 'Chicken Breast', 'VK305', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(17, 'Eggs', 'VK306', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(18, 'Chicken Minced', 'VK307', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(19, 'Feta Cheese', 'VK308', 2, NULL, 0, 29, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(20, 'Milk Full Cream', 'VK309', 2, NULL, 0, 29, NULL, '', 1609135693, NULL, '0', 1, 1, NULL),
(21, 'Milk Toned', 'VK310', 2, NULL, 0, 29, NULL, '', 1609135693, NULL, '0', 1, 1, NULL),
(22, 'Vanilla Ice Cream', 'VK311', 2, NULL, 0, 29, 'a:1:{i:0;s:2:\"38\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(23, 'Sesame seeds ', 'VK312', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(24, 'Coconut powder ', 'VK313', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(25, 'Strawberry pulp ', 'VK314', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(26, 'Pineapple pulp ', 'VK315', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(27, 'Kiwi pulp ', 'VK316', 2, NULL, 0, 18, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(28, 'Blueberry pulp ', 'VK317', 2, NULL, 0, 18, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(29, 'Gelatin', 'VK318', 2, NULL, 0, 20, NULL, '', 1609135693, NULL, '0', 1, 1, NULL),
(30, 'Sugar Syrup 1 KG', 'VK319', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(31, 'Hazelnut', 'VK320', 2, NULL, 0, 21, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(32, 'Pizza Sauce', 'VK321', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(33, 'Pasta Sauce-Red', 'VK322', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(34, 'Pasta Sauce-White', 'VK323', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(35, 'Non Dairy Whipped Cream', 'VK324', 2, NULL, 0, 18, NULL, '', 1609135693, NULL, '0', 1, 1, NULL),
(36, 'Sugar Free Sachets Equal', 'VK325', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(37, 'Cheese Slice', 'VK326', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(38, 'Paneer', 'VK327', 2, NULL, 0, 29, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(39, 'Amul Cheese', 'VK328', 2, NULL, 0, 29, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(40, 'Kesar Badam Frappe', 'VK329', 2, NULL, 0, 19, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(41, 'Garlic Cheese Seasoning', 'VK330', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(42, 'Nimbu Mirchi Pudina', 'VK331', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(43, 'Piri Piri Seasoning', 'VK332', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(44, 'Habanero Tabasco', 'VK333', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(45, 'Cake Box-5Kg', 'VK363', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(46, 'Pastry Box', 'VK366', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(47, 'MRD Sticker', 'VK367', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(48, 'Cling Wrap', 'VK368', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(49, 'Burger Packing Paper', 'VK369', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(50, 'Multi purpose packing box', 'VK370', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(51, 'Paper Envelop', 'VK373', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(52, 'Broom Hard', 'VK376', 2, NULL, 0, 26, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(53, 'Mop Wringer Trolly - Small', 'VK377', 2, NULL, 0, 26, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(54, 'Suma Tab', 'VK378', 2, NULL, 0, 26, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(55, 'Suma Star EP D1', 'VK379', 2, NULL, 0, 26, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(56, 'Suma Grill HI Temp', 'VK380', 2, NULL, 0, 26, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(57, 'Suma Bac EP D10', 'VK381', 2, NULL, 0, 26, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(58, 'Face Shield', 'VK382', 2, NULL, 0, 25, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(59, 'Sanatizer Stand', 'VK383', 2, NULL, 0, 25, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(60, 'Day Dot Sticker - CCF', 'VK386', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(61, 'Printer Roll (Tharmal Paper)', 'VK389', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(62, 'Merry Chef Gloves', 'VK400', 2, NULL, 0, 23, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(63, 'Plastic Gloves', 'VK401', 2, NULL, 0, 23, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(64, 'Shirts', 'VK403', 2, NULL, 0, 27, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(65, 'Trouser', 'VK404', 2, NULL, 0, 27, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(66, 'Appron', 'VK405', 2, NULL, 0, 27, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(67, 'Caps', 'VK406', 2, NULL, 0, 27, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(68, 'Tandoori sauce', 'VK407', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(69, 'Mint sauce', 'VK408', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(70, 'Peri peri', 'VK409', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(71, 'paprika slice red', 'VK410', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(72, 'chana', 'VK411', 2, NULL, 0, 22, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(73, 'caramel', 'VK412', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(74, 'pineapple juice', 'VK413', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(75, 'orange juice', 'VK414', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(76, 'Soda', 'VK415', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(77, 'Choclate muffin', 'VK416', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(78, 'Almond muffin', 'VK417', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(79, 'Blue berry Muffin', 'VK418', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(80, 'Red velvet slice', 'VK419', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(81, 'Walnut brownie', 'VK420', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(82, 'Black forest', 'VK421', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(83, 'pineapple cake', 'VK422', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(84, 'Nutella', 'VK423', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(85, 'Jumbo bread(brown)', 'VK424', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(86, 'Jumbo bread', 'VK425', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(87, 'Burger bun', 'VK426', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(88, 'garlic bread', 'VK427', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(89, 'pizza base', 'VK428', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(90, 'Bugreata bread', 'VK429', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(91, 'Tortilla', 'VK430', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(92, 'CHAAT MASALA', 'VK150', 2, NULL, 0, 22, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(93, 'CEASER DRESSING', 'VK97', 2, NULL, 0, 20, 'a:1:{i:0;s:3:\"118\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(94, 'JALAPENO 3-KG', 'VK107', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(95, 'PAPRIKA POWDER', 'VK123', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(96, 'PENNE PASTA (RAW)', 'VK99', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(97, 'SUGAR', 'VK141', 2, NULL, 0, 22, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(98, 'SCHEZWAN SAUCE', 'VK95', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(99, 'SUNDRIED TOMATO', 'VK111', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(100, 'TUMERIC POWDER', 'VK136', 2, NULL, 0, 21, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(101, 'CASHEWNUT', 'VK130', 2, NULL, 0, 21, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(102, 'OREGANO HERB DRY', 'VK128', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(103, 'CARAMEL COLOUR', 'VK63', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"38\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(104, 'CHICKEN PATTY', 'VK121', 1, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(105, 'VEG PATTY', 'VK120', 1, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(106, 'MAIDA', 'VK142', 2, NULL, 0, 22, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(107, 'CORN FLOUR', 'VK145', 2, NULL, 0, 22, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(108, 'PILLSBERRY VANILLA MIX', 'VK69', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(109, 'PILLSBERRY CHOCOLATE MIX', 'VK70', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(110, 'BROWNIE MIX (EGG LESS)', 'VK71', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(111, 'TUTTY-FRUITY RED', 'VK62', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(112, 'TUTTY-FRUITY GREEN', 'VK61', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(113, 'GLUTEN POWDER', 'VK57', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(114, 'BREAD IMPROVER POWDER', 'VK54', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(115, 'FRESH YEAST', 'VK285', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(116, 'MILK POWDER', 'VK42', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(117, 'RICH CREAM', 'VK283', 2, NULL, 0, 29, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(118, 'STRAWBERRY CRUSH', 'VK28', 2, NULL, 0, 18, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(119, 'PINEAPPLE CRUSH', 'VK27', 2, NULL, 0, 18, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(120, 'ORANGE CRUSH', 'VK26', 2, NULL, 0, 18, 'a:1:{i:0;s:3:\"116\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(121, 'PINEAPPLE SLICE', 'VK84', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(122, 'BLUEBERRY FILLING', 'VK48', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(123, 'NUGEL (COLD GLAZE)', 'VK34', 2, NULL, 0, 18, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(124, 'GLUCOSE', 'VK43', 2, NULL, 0, 18, 'a:2:{i:0;s:2:\"32\";i:1;s:2:\"33\";}', '', 1609135693, 1609591475, '0', 1, 1, NULL),
(125, 'CHILLI FLAKES SACHET', 'VK159', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(126, 'TOMATO KETCHUP SACHET 8 GMS', 'VK106', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(127, 'MOZZERELLE CHEESE', 'VK122', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(128, 'PARMESAN CHEESE', 'VK117', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(129, 'ALPHANSO MANGO DRINK', 'VK79', 2, NULL, 0, 19, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(130, 'COLD COFFEE PREMIX', 'VK77', 2, NULL, 0, 19, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(131, 'NIMBU PANI MASALEDAR', 'VK75', 2, NULL, 0, 19, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(132, 'STRAWBERRY CHEESE CAKE FRAPPE', 'VK74', 2, NULL, 0, 19, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(133, 'JUICY PEACH PREMIX', 'VK76', 2, NULL, 0, 19, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(134, 'FRAPPE VANILLA THICKER', 'VK82', 2, NULL, 0, 19, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(135, 'CHIKU GULKAND PREMIX', 'VK80', 2, NULL, 0, 19, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(136, 'MAYONAISE (EGG LESS)', 'VK87', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"33\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(137, 'BOTTLES (WATER)', 'VK282', 2, NULL, 0, 28, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(138, 'SHAKE BOTTLE+LID PACK OF 30', 'VK168', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(139, 'SHAKE BOTTLE+LID PACK OF 42', 'VK176', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(140, 'CAKE BOX 1KG', 'VK184', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(141, 'CAKE BOX 1/2 KG', 'VK185', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(142, 'PLASTIC FORKS', 'VK161', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(143, 'PIZZA BOXES', 'VK152', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(144, 'BURGER BOXES', 'VK154', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(145, 'STRAWS', 'VK151', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(146, 'CARRY BAGS', 'VK174', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(147, 'WIPES N GLOW (C)', 'VK211', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(148, 'NAPKINS', 'VK155', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(149, 'Disposable Caps', 'VK406', 2, NULL, 0, 23, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(150, 'THOUSAND ISLAND', 'VK88', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(151, 'PAPRIKA POWDER', 'VK123', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, NULL, '0', 1, 1, NULL),
(152, 'DEJON MUSTARD', 'VK125', 2, NULL, 0, 20, 'a:1:{i:0;s:2:\"39\";}', '', 1609135693, 1610086423, '0', 1, 1, NULL),
(153, 'Balsamic Vinegar', 'VK290', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(154, 'Black Pepper Whole', 'VK291', 2, NULL, 0, 44, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(155, 'Deggi Mirch', 'VK292', 2, NULL, 0, 44, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(156, 'Black salt', 'VK293', 2, NULL, 0, 44, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(157, 'Tobasco Sauce', 'VK294', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(158, 'White Vinegar', 'VK295', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(159, 'Gherkins', 'VK296', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(160, 'Fennel Seeds', 'VK297', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(161, 'Tomato sauce Readymade', 'VK298', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(162, 'White sauce Readymade', 'VK299', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(163, 'Candy', 'VK300', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(164, 'pesto', 'VK301', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(165, 'dry Basil', 'VK302', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(166, 'BBQ Sauce', 'VK303', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(167, 'English Mustard CCF', 'VK304', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(168, 'Chicken Breast', 'VK305', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(169, 'Eggs', 'VK306', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(170, 'Chicken Minced', 'VK307', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(171, 'Feta Cheese', 'VK308', 2, NULL, 0, 51, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(172, 'Milk Full Cream', 'VK309', 2, NULL, 0, 51, NULL, '', 1609143162, NULL, '0', 28, 1, NULL),
(173, 'Milk Toned', 'VK310', 2, NULL, 0, 51, NULL, '', 1609143162, NULL, '0', 28, 1, NULL),
(174, 'Vanilla Ice Cream', 'VK311', 2, NULL, 0, 51, 'a:1:{i:0;s:2:\"38\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(175, 'Sesame seeds ', 'VK312', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(176, 'Coconut powder ', 'VK313', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(177, 'Strawberry pulp ', 'VK314', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(178, 'Pineapple pulp ', 'VK315', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(179, 'Kiwi pulp ', 'VK316', 2, NULL, 0, 40, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(180, 'Blueberry pulp ', 'VK317', 2, NULL, 0, 40, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(181, 'Gelatin', 'VK318', 2, NULL, 0, 42, NULL, '', 1609143162, NULL, '0', 28, 1, NULL),
(182, 'Sugar Syrup 1 KG', 'VK319', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(183, 'Hazelnut', 'VK320', 2, NULL, 0, 43, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(184, 'Pizza Sauce', 'VK321', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(185, 'Pasta Sauce-Red', 'VK322', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(186, 'Pasta Sauce-White', 'VK323', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(187, 'Non Dairy Whipped Cream', 'VK324', 2, NULL, 0, 40, NULL, '', 1609143162, NULL, '0', 28, 1, NULL),
(188, 'Sugar Free Sachets Equal', 'VK325', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(189, 'Cheese Slice', 'VK326', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(190, 'Paneer', 'VK327', 2, NULL, 0, 51, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(191, 'Amul Cheese', 'VK328', 2, NULL, 0, 51, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(192, 'Kesar Badam Frappe', 'VK329', 2, NULL, 0, 41, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(193, 'Garlic Cheese Seasoning', 'VK330', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(194, 'Nimbu Mirchi Pudina', 'VK331', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(195, 'Piri Piri Seasoning', 'VK332', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(196, 'Habanero Tabasco', 'VK333', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(197, 'Cake Box-5Kg', 'VK363', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(198, 'Pastry Box', 'VK366', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(199, 'MRD Sticker', 'VK367', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(200, 'Cling Wrap', 'VK368', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(201, 'Burger Packing Paper', 'VK369', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(202, 'Multi purpose packing box', 'VK370', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(203, 'Paper Envelop', 'VK373', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(204, 'Broom Hard', 'VK376', 2, NULL, 0, 48, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(205, 'Mop Wringer Trolly - Small', 'VK377', 2, NULL, 0, 48, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(206, 'Suma Tab', 'VK378', 2, NULL, 0, 48, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(207, 'Suma Star EP D1', 'VK379', 2, NULL, 0, 48, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(208, 'Suma Grill HI Temp', 'VK380', 2, NULL, 0, 48, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(209, 'Suma Bac EP D10', 'VK381', 2, NULL, 0, 48, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(210, 'Face Shield', 'VK382', 2, NULL, 0, 47, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(211, 'Sanatizer Stand', 'VK383', 2, NULL, 0, 47, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(212, 'Day Dot Sticker - CCF', 'VK386', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(213, 'Printer Roll (Tharmal Paper)', 'VK389', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(214, 'Merry Chef Gloves', 'VK400', 2, NULL, 0, 45, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(215, 'Plastic Gloves', 'VK401', 2, NULL, 0, 45, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(216, 'Shirts', 'VK403', 2, NULL, 0, 49, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(217, 'Trouser', 'VK404', 2, NULL, 0, 49, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(218, 'Appron', 'VK405', 2, NULL, 0, 49, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(219, 'Caps', 'VK406', 2, NULL, 0, 49, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(220, 'Tandoori sauce', 'VK407', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(221, 'Mint sauce', 'VK408', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(222, 'Peri peri', 'VK409', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(223, 'paprika slice red', 'VK410', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(224, 'chana', 'VK411', 2, NULL, 0, 44, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(225, 'caramel', 'VK412', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(226, 'pineapple juice', 'VK413', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(227, 'orange juice', 'VK414', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(228, 'Soda', 'VK415', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(229, 'Choclate muffin', 'VK416', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(230, 'Almond muffin', 'VK417', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(231, 'Blue berry Muffin', 'VK418', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(232, 'Red velvet slice', 'VK419', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(233, 'Walnut brownie', 'VK420', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(234, 'Black forest', 'VK421', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(235, 'pineapple cake', 'VK422', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(236, 'Nutella', 'VK423', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(237, 'Jumbo bread(brown)', 'VK424', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(238, 'Jumbo bread', 'VK425', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(239, 'Burger bun', 'VK426', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(240, 'garlic bread', 'VK427', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(241, 'pizza base', 'VK428', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(242, 'Bugreata bread', 'VK429', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(243, 'Tortilla', 'VK430', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(244, 'CHAAT MASALA', 'VK150', 2, NULL, 0, 44, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(245, 'CEASER DRESSING', 'VK97', 2, NULL, 0, 42, 'a:1:{i:0;s:3:\"118\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(246, 'JALAPENO 3-KG', 'VK107', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(247, 'PAPRIKA POWDER', 'VK123', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(248, 'PENNE PASTA (RAW)', 'VK99', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(249, 'SUGAR', 'VK141', 2, NULL, 0, 44, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(250, 'SCHEZWAN SAUCE', 'VK95', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(251, 'SUNDRIED TOMATO', 'VK111', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(252, 'TUMERIC POWDER', 'VK136', 2, NULL, 0, 43, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(253, 'CASHEWNUT', 'VK130', 2, NULL, 0, 43, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(254, 'OREGANO HERB DRY', 'VK128', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(255, 'CARAMEL COLOUR', 'VK63', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"38\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(256, 'CHICKEN PATTY', 'VK121', 1, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(257, 'VEG PATTY', 'VK120', 1, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(258, 'MAIDA', 'VK142', 2, NULL, 0, 44, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(259, 'CORN FLOUR', 'VK145', 2, NULL, 0, 44, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(260, 'PILLSBERRY VANILLA MIX', 'VK69', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(261, 'PILLSBERRY CHOCOLATE MIX', 'VK70', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(262, 'BROWNIE MIX (EGG LESS)', 'VK71', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(263, 'TUTTY-FRUITY RED', 'VK62', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(264, 'TUTTY-FRUITY GREEN', 'VK61', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(265, 'GLUTEN POWDER', 'VK57', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(266, 'BREAD IMPROVER POWDER', 'VK54', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(267, 'FRESH YEAST', 'VK285', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(268, 'MILK POWDER', 'VK42', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(269, 'RICH CREAM', 'VK283', 2, NULL, 0, 51, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(270, 'STRAWBERRY CRUSH', 'VK28', 2, NULL, 0, 40, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(271, 'PINEAPPLE CRUSH', 'VK27', 2, NULL, 0, 40, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(272, 'ORANGE CRUSH', 'VK26', 2, NULL, 0, 40, 'a:1:{i:0;s:3:\"116\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(273, 'PINEAPPLE SLICE', 'VK84', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(274, 'BLUEBERRY FILLING', 'VK48', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(275, 'NUGEL (COLD GLAZE)', 'VK34', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(276, 'GLUCOSE', 'VK43', 2, NULL, 0, 40, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(277, 'CHILLI FLAKES SACHET', 'VK159', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(278, 'TOMATO KETCHUP SACHET 8 GMS', 'VK106', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(279, 'MOZZERELLE CHEESE', 'VK122', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(280, 'PARMESAN CHEESE', 'VK117', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(281, 'ALPHANSO MANGO DRINK', 'VK79', 2, NULL, 0, 41, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(282, 'COLD COFFEE PREMIX', 'VK77', 2, NULL, 0, 41, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(283, 'NIMBU PANI MASALEDAR', 'VK75', 2, NULL, 0, 41, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(284, 'STRAWBERRY CHEESE CAKE FRAPPE', 'VK74', 2, NULL, 0, 41, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(285, 'JUICY PEACH PREMIX', 'VK76', 2, NULL, 0, 41, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(286, 'FRAPPE VANILLA THICKER', 'VK82', 2, NULL, 0, 41, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(287, 'CHIKU GULKAND PREMIX', 'VK80', 2, NULL, 0, 41, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(288, 'MAYONAISE (EGG LESS)', 'VK87', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"33\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(289, 'BOTTLES (WATER)', 'VK282', 2, NULL, 0, 50, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(290, 'SHAKE BOTTLE+LID PACK OF 30', 'VK168', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(291, 'SHAKE BOTTLE+LID PACK OF 42', 'VK176', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(292, 'CAKE BOX 1KG', 'VK184', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(293, 'CAKE BOX 1/2 KG', 'VK185', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(294, 'PLASTIC FORKS', 'VK161', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(295, 'PIZZA BOXES', 'VK152', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(296, 'BURGER BOXES', 'VK154', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(297, 'STRAWS', 'VK151', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(298, 'CARRY BAGS', 'VK174', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(299, 'WIPES N GLOW (C)', 'VK211', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(300, 'NAPKINS', 'VK155', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(301, 'Disposable Caps', 'VK406', 2, NULL, 0, 45, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(302, 'THOUSAND ISLAND', 'VK88', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(303, 'PAPRIKA POWDER', 'VK123', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(304, 'DEJON MUSTARD', 'VK125', 2, NULL, 0, 42, 'a:1:{i:0;s:2:\"39\";}', '', 1609143162, NULL, '0', 28, 1, NULL),
(305, 'Baby Corn', 'VK334', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(306, 'Basil leaves', 'VK335', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(307, 'Broccoli', 'VK336', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(308, 'Button Mushroom', 'VK337', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(309, 'Capsicum Green', 'VK338', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(310, 'Capsicum red', 'VK339', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(311, 'Capsicum yellow', 'VK340', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(312, 'Cherry tomato', 'VK341', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(313, 'Corriander Leaves Fresh', 'VK342', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(314, 'Cucumber', 'VK343', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(315, 'Garlic', 'VK344', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(316, 'Green Chilli', 'VK345', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(317, 'Green Lettuce', 'VK346', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(318, 'Iceberg lettuce', 'VK347', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(319, 'Lemon', 'VK348', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(320, 'Lollo roso lettuce', 'VK349', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(321, 'Mint Leave', 'VK350', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(322, 'Onion', 'VK351', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(323, 'Parshley Green', 'VK352', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(324, 'Red Chilli Fresh', 'VK353', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(325, 'Spring Onion', 'VK354', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(326, 'Tomato', 'VK355', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(327, 'Zucchini green', 'VK356', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(328, 'Zucchini Yellow', 'VK357', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(329, 'Moong Sprouts', 'VK358', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(330, 'Pineapple', 'VK359', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(331, 'Papaya', 'VK360', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(332, 'Peach', 'VK361', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(333, 'MANGO', 'VK362', 2, NULL, 0, 52, 'a:1:{i:0;s:2:\"33\";}', '', 1615467782, NULL, '0', 17, 1, NULL),
(334, 'Chili Sauce', 'P00938', 1, NULL, 0, 57, 'a:1:{i:0;s:2:\"32\";}', 'ADASDA987979', 1618737564, NULL, '1', 17, 0, NULL),
(335, 'TATA SALT', 'VK363', 2, NULL, 0, 58, 'a:2:{i:0;s:2:\"32\";i:1;s:2:\"33\";}', 'ADKAJSHDA', 1623148519, 1623148550, '0', 17, 0, NULL),
(336, 'Maida', 'VK364', 1, NULL, 0, 59, 'a:2:{i:0;s:2:\"32\";i:1;s:2:\"33\";}', '', 1623148612, NULL, '0', 17, 0, NULL),
(337, 'Elaichi', 'VK365', 2, NULL, 0, 60, 'a:1:{i:0;s:2:\"32\";}', 'ASDASDAS', 1623903922, NULL, '0', 17, 0, NULL),
(338, 'Sugar', 'VKC366', 2, NULL, 0, 61, 'a:1:{i:0;s:2:\"32\";}', 'ASDASDASD', 1623903990, NULL, '0', 17, 0, NULL),
(339, 'Atta', 'P0041', 2, NULL, 0, 59, 'a:1:{i:0;s:2:\"33\";}', 'ATTA001', 1624648475, NULL, '1', 17, 0, NULL),
(340, 'Pepsi', 'P0031', 2, NULL, 0, 2, 'a:7:{i:0;s:2:\"18\";i:1;s:2:\"19\";i:2;s:2:\"22\";i:3;s:2:\"31\";i:4;s:2:\"39\";i:5;s:2:\"42\";i:6;s:2:\"51\";}', 'ADASDASASD', 1625750030, NULL, '0', 17, 0, NULL),
(341, 'Chana dal', 'D001', 2, NULL, 0, 62, 'a:2:{i:0;s:2:\"32\";i:1;s:2:\"33\";}', 'ASDASD', 1627465932, 1627466406, '0', 17, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_products_taxes`
--

CREATE TABLE `ie_products_taxes` (
  `id` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `taxId` int(11) NOT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ie_purchase_stocks`
--

CREATE TABLE `ie_purchase_stocks` (
  `id` int(11) NOT NULL,
  `grnNumber` int(11) NOT NULL,
  `vendorId` int(11) NOT NULL,
  `storeId` int(11) DEFAULT NULL,
  `productId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `productSiUnitId` int(11) NOT NULL,
  `openingStockNumber` int(11) NOT NULL,
  `productQuantity` decimal(10,3) NOT NULL,
  `productQuantityConversion` decimal(40,4) DEFAULT 0.0000,
  `productUnitPrice` decimal(20,6) NOT NULL,
  `productSubtotal` decimal(20,6) NOT NULL,
  `productTax` decimal(20,6) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `billNumber` varchar(100) DEFAULT NULL,
  `billDate` int(11) DEFAULT NULL,
  `createdOn` int(11) DEFAULT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_purchase_stocks`
--

INSERT INTO `ie_purchase_stocks` (`id`, `grnNumber`, `vendorId`, `storeId`, `productId`, `userId`, `productSiUnitId`, `openingStockNumber`, `productQuantity`, `productQuantityConversion`, `productUnitPrice`, `productSubtotal`, `productTax`, `comment`, `billNumber`, `billDate`, `createdOn`, `updatedOn`, `isDeleted`) VALUES
(1, 1, 1, NULL, 116, 1, 33, 1, '50.000', '50000.0000', '60.000000', '3000.000000', NULL, '', 'BILL16012021', 1610821800, 1610781840, NULL, NULL),
(2, 1, 1, NULL, 118, 1, 116, 1, '50.000', '50.0000', '20.000000', '1000.000000', NULL, '', 'BILL16012021', 1610821800, 1610781840, NULL, NULL),
(3, 1, 1, NULL, 119, 1, 116, 1, '20.000', '20.0000', '210.000000', '4200.000000', NULL, '', 'BILL16012021', 1610821800, 1610781840, NULL, NULL),
(4, 1, 1, NULL, 120, 1, 116, 1, '50.000', '50.0000', '54.000000', '2700.000000', NULL, '', 'BILL16012021', 1610821800, 1610781840, NULL, NULL),
(5, 1, 1, NULL, 123, 1, 39, 1, '50.000', '50.0000', '89.000000', '4450.000000', NULL, '', 'BILL16012021', 1610821800, 1610781840, NULL, NULL),
(6, 1, 1, NULL, 124, 1, 33, 1, '50.000', '50000.0000', '20.000000', '1000.000000', NULL, 'Testing for GRN', 'BILL16012021', 1610821800, 1610781840, NULL, NULL),
(7, 1, 18, NULL, 336, 17, 33, 2, '5.000', '5000.0000', '200.000000', '1000.000000', NULL, '', 'BILL16062021', 1623868200, 1623904402, NULL, NULL),
(8, 1, 18, NULL, 337, 17, 32, 2, '500.000', '500.0000', '100.000000', '50000.000000', NULL, '', 'BILL16062021', 1623868200, 1623904402, NULL, NULL),
(9, 1, 18, NULL, 338, 17, 32, 2, '1200.000', '1200.0000', '100.000000', '120000.000000', NULL, '', 'BILL16062021', 1623868200, 1623904402, NULL, NULL),
(10, 2, 18, NULL, 339, 17, 33, 2, '2.000', '2000.0000', '200.000000', '400.000000', NULL, '2 KG ATTA', 'BILL26062021', 1624732200, 1624648688, NULL, NULL),
(11, 3, 18, NULL, 339, 17, 33, 2, '20.000', '20000.0000', '20.000000', '400.000000', NULL, '', '06072021JULY', 1625509800, 1625567547, NULL, NULL),
(12, 4, 18, NULL, 339, 17, 33, 2, '20.000', '20000.0000', '30.000000', '600.000000', NULL, '', '06072021', 1625596200, 1625567609, NULL, NULL),
(13, 5, 18, NULL, 340, 17, 19, 2, '10.000', '40.0000', '20.000000', '200.000000', NULL, 'Pepsi purchased 4 pc at the price of 20 of 20 quantity', 'BILL08072021', 1625682600, 1625750144, NULL, NULL),
(14, 6, 18, NULL, 341, 17, 33, 2, '100.000', '100000.0000', '10.000000', '1000.000000', NULL, '', 'INVOICE28072021', 1627410600, 1627465991, NULL, NULL),
(15, 7, 18, NULL, 341, 17, 32, 2, '500.000', '500.0000', '5.500000', '2750.000000', NULL, '', 'BILL28072021', 1627410600, 1627466491, NULL, NULL),
(16, 8, 18, NULL, 341, 17, 33, 2, '10.000', '10000.0000', '11.000000', '110.000000', NULL, '', 'BILL28072021', 1627410600, 1627466533, NULL, NULL),
(17, 9, 18, NULL, 341, 17, 33, 3, '10.000', '10000.0000', '11.000000', '110.000000', NULL, '', 'BILL28072021', 1627410600, 1627481659, NULL, NULL),
(18, 10, 18, NULL, 341, 17, 32, 3, '500.000', '500.0000', '0.010400', '5.200000', NULL, '', 'BILL28072021', 1627410600, 1627481962, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_recipes`
--

CREATE TABLE `ie_recipes` (
  `recipeId` int(11) NOT NULL,
  `menuItemId` int(11) NOT NULL,
  `menuItemQuantity` text DEFAULT NULL,
  `menuItemSiUnit` varchar(20) DEFAULT NULL,
  `menuItemRecipe` text NOT NULL,
  `userId` int(11) NOT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_recipes`
--

INSERT INTO `ie_recipes` (`recipeId`, `menuItemId`, `menuItemQuantity`, `menuItemSiUnit`, `menuItemRecipe`, `userId`, `createdOn`, `updatedOn`) VALUES
(2, 38, '{\"250ml\":\"50\"}', NULL, '{\"250ml\":[{\"productId\":\"338\",\"productSiUnitId\":\"32\",\"productQty\":\"30\"},{\"productId\":\"331\",\"productSiUnitId\":\"33\",\"productQty\":\"10\"}]}', 17, 1624077287, NULL),
(3, 37, '{\"Zomato\":\"10\",\"Outlet\":\"20\"}', NULL, '{\"Zomato\":[{\"productId\":\"336\",\"productSiUnitId\":\"33\",\"productQty\":\"1\"},{\"productId\":\"337\",\"productSiUnitId\":\"32\",\"productQty\":\"10\"}],\"Outlet\":[{\"productId\":\"337\",\"productSiUnitId\":\"32\",\"productQty\":\"20\"},{\"productId\":\"338\",\"productSiUnitId\":\"32\",\"productQty\":\"100\"}]}', 17, 1624090998, 1624091667),
(4, 681, '[\"1\",\"1\"]', NULL, '{\"\":[{\"productId\":\"337\",\"productSiUnitId\":\"32\",\"productQty\":\"1\"},{\"productId\":\"331\",\"productSiUnitId\":\"33\",\"productQty\":\"1\"}]}', 17, 1624092488, 1624093306);

-- --------------------------------------------------------

--
-- Table structure for table `ie_requests`
--

CREATE TABLE `ie_requests` (
  `id` int(11) NOT NULL,
  `userIdFrom` int(11) NOT NULL,
  `userIdTo` int(11) NOT NULL,
  `sender` tinyint(4) DEFAULT NULL COMMENT '1=Sender,2=Receiver',
  `requestType` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `indentRequestNumber` int(11) DEFAULT NULL,
  `userIdToOpeningStockNumber` int(11) NOT NULL,
  `userIdFromOpeningStockNumber` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT '0=Pending,\r\n1=Accepted,2=Rejected',
  `completedOn` int(11) DEFAULT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_requests`
--

INSERT INTO `ie_requests` (`id`, `userIdFrom`, `userIdTo`, `sender`, `requestType`, `comment`, `indentRequestNumber`, `userIdToOpeningStockNumber`, `userIdFromOpeningStockNumber`, `status`, `completedOn`, `createdOn`, `updatedOn`, `isDeleted`) VALUES
(1, 1, 28, NULL, 1, NULL, 1, 1, 1, 1, 1609851762, 1609851655, 1609851762, NULL),
(2, 28, 1, NULL, 2, NULL, 1, 1, 1, 1, 1609852394, 1609852295, 1609852394, NULL),
(3, 28, 1, NULL, 2, NULL, 2, 1, 1, 1, 1610018634, 1610017242, 1610018634, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_si_units`
--

CREATE TABLE `ie_si_units` (
  `id` int(11) NOT NULL,
  `unitName` varchar(255) NOT NULL,
  `parentId` int(11) DEFAULT NULL,
  `conversion` int(11) NOT NULL,
  `unitSymbol` varchar(50) DEFAULT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `createdOn` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_si_units`
--

INSERT INTO `ie_si_units` (`id`, `unitName`, `parentId`, `conversion`, `unitSymbol`, `updatedOn`, `createdOn`, `userId`, `isDeleted`) VALUES
(1, 'METRE', NULL, 1, NULL, NULL, 0, NULL, NULL),
(2, 'PIECE', NULL, 1, NULL, NULL, 0, NULL, NULL),
(3, 'GRAM', NULL, 1, NULL, NULL, 0, NULL, NULL),
(4, 'ML', NULL, 1, NULL, NULL, 0, NULL, NULL),
(5, 'BTL(750 ML)', 4, 750, NULL, NULL, 1602386576, NULL, NULL),
(6, 'BUNDLE(20KG)', 3, 20000, NULL, NULL, 1602386576, NULL, NULL),
(7, 'CTN ( 6*1560 GRM)', 3, 9360, NULL, NULL, 1602386576, NULL, NULL),
(8, 'CTN (1*12 LTR)', 4, 12000, NULL, NULL, 1602386576, NULL, NULL),
(9, 'CTN (12*690 ML)', 4, 8280, NULL, NULL, 1602386576, NULL, NULL),
(10, 'CTN (20*500 GRM)', 3, 10000, NULL, NULL, 1602386576, NULL, NULL),
(11, 'CTN (24*240 GRM)', 3, 5760, NULL, NULL, 1602386576, NULL, NULL),
(12, 'CTN (24*250 GRM)', 3, 6000, NULL, NULL, 1602386576, NULL, NULL),
(13, 'CTN (4*4 LTR)', 4, 16000, NULL, NULL, 1602386576, NULL, NULL),
(14, 'CTN (6*1200 GRM)', 3, 7200, NULL, NULL, 1602386576, NULL, NULL),
(15, 'CTN (6*500 GRM)', 3, 3000, NULL, NULL, 1602386576, NULL, NULL),
(16, 'CTN(12X1KG)', 3, 12000, NULL, NULL, 1602386576, NULL, NULL),
(17, 'CTN(12X1LTR)', 4, 12000, NULL, NULL, 1602386576, NULL, NULL),
(18, 'CTN(360PCS)', 2, 360, NULL, NULL, 1602386576, NULL, NULL),
(19, 'CTN(4PCS)', 2, 4, NULL, NULL, 1602386576, NULL, NULL),
(20, 'CTN(4X3.78 LTR)', 4, 15120, NULL, NULL, 1602386576, NULL, NULL),
(21, 'CTN(4X5LTR)', 4, 20000, NULL, NULL, 1602386576, NULL, NULL),
(22, 'CTN(6PCS)', 2, 6, NULL, NULL, 1602386576, NULL, NULL),
(23, 'CTN(6X1.1KG)', 3, 6600, NULL, NULL, 1602386576, NULL, NULL),
(24, 'CTN(6X2.2LTR)', 4, 13200, NULL, NULL, 1602386576, NULL, NULL),
(25, 'CTN(6X2.2kg)', 3, 13200, NULL, NULL, 1602386576, NULL, NULL),
(26, 'CTN(6X500GM)', 3, 3000, NULL, NULL, 1602386576, NULL, NULL),
(27, 'CTN(6X5LTR)', 4, 30000, NULL, NULL, 1602386576, NULL, NULL),
(28, 'CTN(6X750GM)', 3, 4500, NULL, NULL, 1602386576, NULL, NULL),
(29, 'CTN(6X935GM)', 3, 5610, NULL, NULL, 1602386576, NULL, NULL),
(30, 'CTN(6x2.5KG)', 3, 15000, NULL, NULL, 1602386576, NULL, NULL),
(31, 'CTN(8PCS)', 2, 8, NULL, NULL, 1602386576, NULL, NULL),
(32, 'GRAM', 3, 1, NULL, NULL, 1602386576, NULL, NULL),
(33, 'KG', 3, 1000, NULL, NULL, 1602386576, NULL, NULL),
(34, 'KG(1X50KG)', 3, 50000, NULL, NULL, 1602386576, NULL, NULL),
(35, 'KG(240GM)', 3, 240, NULL, NULL, 1602386576, NULL, NULL),
(36, 'LITRE', 4, 1000, NULL, NULL, 1602386576, NULL, NULL),
(37, 'LTR(1.5LTR)', 4, 1500, NULL, NULL, 1602386576, NULL, NULL),
(38, 'ML', 4, 1, NULL, NULL, 1602386576, NULL, NULL),
(39, 'PCS', 2, 1, NULL, NULL, 1602386576, NULL, NULL),
(40, 'PKT (10 KG)', 3, 10000, NULL, NULL, 1602386576, NULL, NULL),
(41, 'PKT (20 KG)', 3, 20000, NULL, NULL, 1602386576, NULL, NULL),
(42, 'PKT (25 PCS)', 2, 25, NULL, NULL, 1602386576, NULL, NULL),
(43, 'PKT(1 KG)', 3, 1000, NULL, NULL, 1602386576, NULL, NULL),
(44, 'PKT(1 LTR)', 4, 1000, NULL, NULL, 1602386576, NULL, NULL),
(45, 'PKT(1.1KG)', 3, 1100, NULL, NULL, 1602386576, NULL, NULL),
(46, 'PKT(1.2KG)', 3, 1200, NULL, NULL, 1602386576, NULL, NULL),
(47, 'PKT(10000PCS)', 2, 10000, NULL, NULL, 1602386576, NULL, NULL),
(48, 'PKT(1000PCS)', 2, 1000, NULL, NULL, 1602386576, NULL, NULL),
(49, 'PKT(100GM)', 3, 100, NULL, NULL, 1602386576, NULL, NULL),
(50, 'PKT(100PCS)', 2, 100, NULL, NULL, 1602386576, NULL, NULL),
(51, 'PKT(10PCS)', 2, 10, NULL, NULL, 1602386576, NULL, NULL),
(52, 'PKT(120 PCS)', 2, 120, NULL, NULL, 1602386576, NULL, NULL),
(53, 'PKT(125 GRMS)', 3, 125, NULL, NULL, 1602386576, NULL, NULL),
(54, 'PKT(12KG)', 3, 12000, NULL, NULL, 1602386576, NULL, NULL),
(55, 'PKT(12PCS)', 2, 12, NULL, NULL, 1602386576, NULL, NULL),
(56, 'PKT(1560GM)', 3, 1560, NULL, NULL, 1602386576, NULL, NULL),
(57, 'PKT(15KG)', 3, 15000, NULL, NULL, 1602386576, NULL, NULL),
(58, 'PKT(15PCS)', 2, 15, NULL, NULL, 1602386576, NULL, NULL),
(59, 'PKT(165GM)', 3, 165, NULL, NULL, 1602386576, NULL, NULL),
(60, 'PKT(1700GM)', 3, 1700, NULL, NULL, 1602386576, NULL, NULL),
(61, 'PKT(18PCS)', 2, 18, NULL, NULL, 1602386576, NULL, NULL),
(62, 'PKT(1KG)', 3, 1000, NULL, NULL, 1602386576, NULL, NULL),
(63, 'PKT(1LTR)', 4, 1000, NULL, NULL, 1602386576, NULL, NULL),
(64, 'PKT(2.2LTR)', 4, 2200, NULL, NULL, 1602386576, NULL, NULL),
(65, 'PKT(2.5 KG)', 3, 2500, NULL, NULL, 1602386576, NULL, NULL),
(66, 'PKT(2.5KG)', 3, 2500, NULL, NULL, 1602386576, NULL, NULL),
(67, 'PKT(2000PCS)', 2, 2000, NULL, NULL, 1602386576, NULL, NULL),
(68, 'PKT(200GM)', 3, 200, NULL, NULL, 1602386576, NULL, NULL),
(69, 'PKT(20PCS)', 2, 20, NULL, NULL, 1602386576, NULL, NULL),
(70, 'PKT(240 GM)', 3, 240, NULL, NULL, 1602386576, NULL, NULL),
(71, 'PKT(240GM)', 3, 240, NULL, NULL, 1602386576, NULL, NULL),
(72, 'PKT(240ML)', 4, 240, NULL, NULL, 1602386576, NULL, NULL),
(73, 'PKT(24PCS)', 2, 24, NULL, NULL, 1602386576, NULL, NULL),
(74, 'PKT(2500 PCS)', 2, 2500, NULL, NULL, 1602386576, NULL, NULL),
(75, 'PKT(250PCS)', 2, 250, NULL, NULL, 1602386576, NULL, NULL),
(76, 'PKT(265GM)', 3, 265, NULL, NULL, 1602386576, NULL, NULL),
(77, 'PKT(28GM)', 3, 28, NULL, NULL, 1602386576, NULL, NULL),
(78, 'PKT(3.1LTR)', 4, 3100, NULL, NULL, 1602386576, NULL, NULL),
(79, 'PKT(3.78LTR)', 4, 3780, NULL, NULL, 1602386576, NULL, NULL),
(80, 'PKT(3000PCS)', 2, 3000, NULL, NULL, 1602386576, NULL, NULL),
(81, 'PKT(300GM)', 3, 300, NULL, NULL, 1602386576, NULL, NULL),
(82, 'PKT(300PCS)', 2, 300, NULL, NULL, 1602386576, NULL, NULL),
(83, 'PKT(340GM)', 3, 340, NULL, NULL, 1602386576, NULL, NULL),
(84, 'PKT(350GM)', 3, 350, NULL, NULL, 1602386576, NULL, NULL),
(85, 'PKT(350PCS)', 2, 350, NULL, NULL, 1602386576, NULL, NULL),
(86, 'PKT(3600PCS)', 2, 3600, NULL, NULL, 1602386576, NULL, NULL),
(87, 'PKT(380ML)', 4, 380, NULL, NULL, 1602386576, NULL, NULL),
(88, 'PKT(453GM)', 3, 453, NULL, NULL, 1602386576, NULL, NULL),
(89, 'PKT(454GM)', 3, 454, NULL, NULL, 1602386576, NULL, NULL),
(90, 'PKT(480GM)', 3, 480, NULL, NULL, 1602386576, NULL, NULL),
(91, 'PKT(50 PCS)', 2, 50, NULL, NULL, 1602386576, NULL, NULL),
(92, 'PKT(500GM)', 3, 500, NULL, NULL, 1602386576, NULL, NULL),
(93, 'PKT(500ML)', 4, 500, NULL, NULL, 1602386576, NULL, NULL),
(94, 'PKT(500PCS)', 2, 500, NULL, NULL, 1602386576, NULL, NULL),
(95, 'PKT(50GM)', 3, 50, NULL, NULL, 1602386576, NULL, NULL),
(96, 'PKT(50KG)', 3, 50000, NULL, NULL, 1602386576, NULL, NULL),
(97, 'PKT(50PCS)', 2, 50, NULL, NULL, 1602386576, NULL, NULL),
(98, 'PKT(550GM)', 3, 550, NULL, NULL, 1602386576, NULL, NULL),
(99, 'PKT(600PCS)', 2, 600, NULL, NULL, 1602386576, NULL, NULL),
(100, 'PKT(650GM)', 3, 650, NULL, NULL, 1602386576, NULL, NULL),
(101, 'PKT(650PCS)', 2, 650, NULL, NULL, 1602386576, NULL, NULL),
(102, 'PKT(6PCS)', 2, 6, NULL, NULL, 1602386576, NULL, NULL),
(103, 'PKT(700PCS)', 2, 700, NULL, NULL, 1602386576, NULL, NULL),
(104, 'PORTION', 2, 1, NULL, NULL, 1602386576, NULL, NULL),
(105, 'Packet(250 GM)', 3, 250, NULL, NULL, 1602386576, NULL, NULL),
(106, 'PIECE', 2, 1, NULL, NULL, 1602386576, NULL, NULL),
(107, 'ROLL', 2, 1, NULL, NULL, 1602386576, NULL, NULL),
(108, 'ROLL(16PCS)', 2, 16, NULL, NULL, 1602386576, NULL, NULL),
(109, 'TIN(18LTR)', 4, 18000, NULL, NULL, 1602386576, NULL, NULL),
(110, 'TRAY(15PCS)', 2, 15, NULL, NULL, 1602386576, NULL, NULL),
(111, 'TRAY(6PCS)', 2, 6, NULL, NULL, 1602386576, NULL, NULL),
(112, 'TRAY(8PCS)', 2, 8, NULL, NULL, 1602386576, NULL, NULL),
(113, 'DOZEN', 2, 12, NULL, NULL, 1602386576, NULL, NULL),
(114, 'COUNT', 2, 1, NULL, NULL, 1602386576, NULL, NULL),
(115, 'SET', 2, 1, NULL, NULL, 1604946600, NULL, NULL),
(116, 'BTL', 2, 1, NULL, NULL, 1604946600, NULL, NULL),
(117, 'BOX', 2, 1, NULL, NULL, 1604946600, NULL, NULL),
(118, 'PKT', 2, 1, NULL, NULL, 1604946600, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_taxes`
--

CREATE TABLE `ie_taxes` (
  `id` int(11) NOT NULL,
  `taxName` varchar(255) NOT NULL,
  `taxPercentage` int(11) NOT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_taxes`
--

INSERT INTO `ie_taxes` (`id`, `taxName`, `taxPercentage`, `createdOn`, `updatedOn`, `userId`, `isDeleted`) VALUES
(1, 'GST', 18, 1602849623, NULL, NULL, NULL),
(2, 'CGST', 5, 1602849623, NULL, NULL, NULL),
(3, 'IGST', 10, 1602849623, 1602407958, NULL, NULL),
(7, 'GSTA', 18, 1602849623, NULL, NULL, NULL),
(8, 'VAT', 3, 1602849632, NULL, NULL, NULL),
(9, 'CES', 18, 1602849642, NULL, NULL, NULL),
(10, 'GSTC', 3, 1602849666, NULL, NULL, NULL),
(11, 'SGST', 5, 1602849674, NULL, NULL, NULL),
(12, 'NGST', 8, 1602849681, NULL, NULL, NULL),
(13, 'GST', 18, 1603875288, NULL, 1, NULL),
(14, 'CGST', 10, 1604926470, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_transfer_stocks`
--

CREATE TABLE `ie_transfer_stocks` (
  `id` int(11) NOT NULL,
  `requestId` int(11) DEFAULT NULL,
  `openingStockNumber` int(11) DEFAULT NULL,
  `productId` int(11) NOT NULL,
  `productSiUnitId` int(11) DEFAULT NULL,
  `productQuantity` decimal(10,2) NOT NULL,
  `requestedQty` decimal(10,2) DEFAULT NULL,
  `dispatchedQty` decimal(10,2) DEFAULT NULL,
  `receivedQty` decimal(10,2) DEFAULT NULL,
  `disputeQty` decimal(10,2) DEFAULT NULL,
  `productQuantityConversion` decimal(40,4) DEFAULT 0.0000,
  `productUnitPrice` decimal(10,3) DEFAULT NULL,
  `productTax` decimal(10,2) DEFAULT NULL,
  `productSubtotal` decimal(10,2) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `receiverMessage` varchar(255) DEFAULT NULL,
  `dispatcherMessage` varchar(255) DEFAULT NULL,
  `dispatcherStatus` tinyint(4) DEFAULT NULL COMMENT 'Accept=1,Reject=2',
  `receiverStatus` tinyint(4) DEFAULT NULL COMMENT 'Accept=1,Reject=2',
  `storeId` int(11) DEFAULT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_transfer_stocks`
--

INSERT INTO `ie_transfer_stocks` (`id`, `requestId`, `openingStockNumber`, `productId`, `productSiUnitId`, `productQuantity`, `requestedQty`, `dispatchedQty`, `receivedQty`, `disputeQty`, `productQuantityConversion`, `productUnitPrice`, `productTax`, `productSubtotal`, `comment`, `receiverMessage`, `dispatcherMessage`, `dispatcherStatus`, `receiverStatus`, `storeId`, `createdOn`, `updatedOn`, `isDeleted`) VALUES
(1, 1, 1, 1, 116, '60.00', NULL, '60.00', '60.00', NULL, '60.0000', '0.000', NULL, '0.00', '', NULL, NULL, NULL, NULL, NULL, 1609851655, 1609851762, NULL),
(2, 1, 1, 2, 33, '20.00', NULL, '20.00', '20.00', NULL, '20000.0000', '0.000', NULL, '0.00', '', NULL, NULL, NULL, NULL, NULL, 1609851655, 1609851762, NULL),
(3, 2, 1, 153, 116, '20.00', '30.00', '20.00', '20.00', NULL, '10.0000', '0.000', NULL, '0.00', '', 'Ok', 'I dont have enough stocks', 2, 1, NULL, 1609852295, 1609852542, NULL),
(4, 2, 1, 154, 33, '20.00', '30.00', '20.00', '20.00', NULL, '10000.0000', '0.000', NULL, '0.00', '', 'Ok', 'I dont have enough stocks', 2, 1, NULL, 1609852295, 1609852854, NULL),
(5, 3, 1, 153, 116, '25.00', '30.00', '28.00', '25.00', '3.00', '25.0000', '0.000', NULL, '0.00', '', 'Shortage', 'Shortage', NULL, NULL, NULL, 1610017242, 1610018634, NULL),
(6, 3, 1, 154, 33, '30.00', '30.00', '30.00', '30.00', NULL, '30000.0000', '0.000', NULL, '0.00', '', NULL, NULL, NULL, NULL, NULL, 1610017242, 1610018634, NULL),
(7, 3, 1, 155, 33, '30.00', '30.00', '30.00', '30.00', NULL, '30000.0000', '0.000', NULL, '0.00', '', NULL, NULL, NULL, NULL, NULL, 1610017242, 1610018634, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_users`
--

CREATE TABLE `ie_users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_users`
--

INSERT INTO `ie_users` (`id`, `firstName`, `lastName`, `email`, `password`, `type`, `status`, `createdOn`, `updatedOn`, `isDeleted`) VALUES
(1, 'TEST', 'TEST', 'test@gmail.com', '123456', 1, NULL, 0, NULL, NULL),
(2, 'TEST', 'TEST', 'test1@gmail.com', '123456', 1, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_vendors`
--

CREATE TABLE `ie_vendors` (
  `id` int(11) NOT NULL,
  `vendorName` varchar(255) NOT NULL,
  `vendorCode` varchar(255) NOT NULL,
  `vendorContact` varchar(255) NOT NULL,
  `vendorEmail` varchar(255) NOT NULL,
  `gstNumber` varchar(20) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `panNumber` varchar(20) NOT NULL,
  `vendorPerson` varchar(255) DEFAULT NULL,
  `serviceTaxNumber` varchar(50) DEFAULT NULL,
  `useTax` tinyint(4) DEFAULT NULL,
  `contractDateFrom` int(11) DEFAULT NULL,
  `contractDateTo` int(11) DEFAULT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_vendors`
--

INSERT INTO `ie_vendors` (`id`, `vendorName`, `vendorCode`, `vendorContact`, `vendorEmail`, `gstNumber`, `userId`, `panNumber`, `vendorPerson`, `serviceTaxNumber`, `useTax`, `contractDateFrom`, `contractDateTo`, `createdOn`, `updatedOn`, `status`, `isDeleted`) VALUES
(1, 'Vendor1', 'V001', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(2, 'Vendor2', 'V002', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(3, 'Vendor3', 'V003', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(4, 'Vendor4', 'V004', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(5, 'Vendor5', 'V005', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(6, 'Vendor6', 'V006', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(7, 'Vendor7', 'V007', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(8, 'Vendor8', 'V008', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(9, 'Vendor9', 'V009', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(10, 'Vendor10', 'V0010', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 1, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(11, 'Vendor11', 'V0011', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 28, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(12, 'Vendor12', 'V0012', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 28, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(13, 'Vendor13', 'V0013', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 28, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(14, 'Vendor14', 'V0014', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 28, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(15, 'Vendor15', 'V0015', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 28, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1602613800, 1602786600, 1602662095, 1602663160, NULL, NULL),
(16, 'Vendor16', 'V0016', '7878787874', 'ajay@gmail.com', '1245ASDFASDF', 28, 'SAFASD9879', NULL, 'ASDFASDFD', 1, 1603650600, 1603996200, 1602662095, 1603613502, NULL, NULL),
(17, 'Vendor17', 'V0017', '85858585', 'vendor@gmail.com', '8854554458', 28, '5554556', NULL, '24545454', 1, 1603823400, 1603909800, 1603875994, 1603885096, NULL, NULL),
(18, 'Vendor 1', 'VC0001', '7878787878', 'vendor1@gmail.com', 'ASDASDSAD', 17, 'BAHAS0989', NULL, 'ADASDASD', 1, 1623868200, 1624127400, 1623904068, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_vendor_products`
--

CREATE TABLE `ie_vendor_products` (
  `id` int(11) NOT NULL,
  `vendorId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_vendor_products`
--

INSERT INTO `ie_vendor_products` (`id`, `vendorId`, `productId`, `createdOn`, `updatedOn`, `userId`, `isDeleted`) VALUES
(1, 1, 27, 1609143365, NULL, 1, NULL),
(2, 1, 28, 1609143365, NULL, 1, NULL),
(3, 1, 35, 1609143365, NULL, 1, NULL),
(4, 1, 77, 1609143365, NULL, 1, NULL),
(5, 1, 78, 1609143365, NULL, 1, NULL),
(6, 1, 79, 1609143365, NULL, 1, NULL),
(7, 1, 80, 1609143365, NULL, 1, NULL),
(8, 1, 81, 1609143365, NULL, 1, NULL),
(9, 1, 82, 1609143365, NULL, 1, NULL),
(10, 1, 83, 1609143365, NULL, 1, NULL),
(11, 1, 85, 1609143365, NULL, 1, NULL),
(12, 1, 86, 1609143365, NULL, 1, NULL),
(13, 1, 87, 1609143365, NULL, 1, NULL),
(14, 1, 88, 1609143365, NULL, 1, NULL),
(15, 1, 89, 1609143365, NULL, 1, NULL),
(16, 1, 90, 1609143365, NULL, 1, NULL),
(17, 1, 91, 1609143365, NULL, 1, NULL),
(18, 1, 103, 1609143365, NULL, 1, NULL),
(19, 1, 108, 1609143365, NULL, 1, NULL),
(20, 1, 109, 1609143365, NULL, 1, NULL),
(21, 1, 111, 1609143365, NULL, 1, NULL),
(22, 1, 112, 1609143365, NULL, 1, NULL),
(23, 1, 113, 1609143365, NULL, 1, NULL),
(24, 1, 114, 1609143365, NULL, 1, NULL),
(25, 1, 115, 1609143365, NULL, 1, NULL),
(26, 1, 116, 1609143365, NULL, 1, NULL),
(27, 1, 118, 1609143365, NULL, 1, NULL),
(28, 1, 119, 1609143365, NULL, 1, NULL),
(29, 1, 120, 1609143365, NULL, 1, NULL),
(30, 1, 123, 1609143365, NULL, 1, NULL),
(31, 1, 124, 1609143365, NULL, 1, NULL),
(32, 11, 179, 1609143391, NULL, 28, NULL),
(33, 11, 180, 1609143391, NULL, 28, NULL),
(34, 11, 187, 1609143391, NULL, 28, NULL),
(35, 11, 229, 1609143391, NULL, 28, NULL),
(36, 11, 230, 1609143391, NULL, 28, NULL),
(37, 11, 231, 1609143391, NULL, 28, NULL),
(38, 11, 232, 1609143391, NULL, 28, NULL),
(39, 11, 233, 1609143391, NULL, 28, NULL),
(40, 11, 234, 1609143391, NULL, 28, NULL),
(41, 11, 235, 1609143391, NULL, 28, NULL),
(42, 11, 237, 1609143391, NULL, 28, NULL),
(43, 11, 238, 1609143391, NULL, 28, NULL),
(44, 11, 239, 1609143391, NULL, 28, NULL),
(45, 11, 240, 1609143391, NULL, 28, NULL),
(46, 11, 241, 1609143391, NULL, 28, NULL),
(47, 11, 242, 1609143391, NULL, 28, NULL),
(48, 11, 243, 1609143391, NULL, 28, NULL),
(49, 11, 255, 1609143391, NULL, 28, NULL),
(50, 11, 260, 1609143391, NULL, 28, NULL),
(51, 11, 261, 1609143391, NULL, 28, NULL),
(52, 11, 263, 1609143391, NULL, 28, NULL),
(53, 11, 264, 1609143391, NULL, 28, NULL),
(54, 11, 265, 1609143391, NULL, 28, NULL),
(55, 11, 266, 1609143391, NULL, 28, NULL),
(56, 11, 267, 1609143391, NULL, 28, NULL),
(57, 11, 268, 1609143391, NULL, 28, NULL),
(58, 11, 270, 1609143391, NULL, 28, NULL),
(59, 11, 271, 1609143391, NULL, 28, NULL),
(60, 11, 272, 1609143391, NULL, 28, NULL),
(61, 11, 275, 1609143391, NULL, 28, NULL),
(62, 11, 276, 1609143391, NULL, 28, NULL),
(63, 18, 334, 1623904091, NULL, 17, NULL),
(64, 18, 337, 1623904109, NULL, 17, NULL),
(65, 18, 338, 1623904124, NULL, 17, NULL),
(66, 18, 336, 1623904294, NULL, 17, NULL),
(67, 18, 339, 1624648627, NULL, 17, NULL),
(68, 18, 340, 1625750057, NULL, 17, NULL),
(69, 18, 341, 1627465949, NULL, 17, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ie_vendor_product_taxes`
--

CREATE TABLE `ie_vendor_product_taxes` (
  `id` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `vendorId` int(11) NOT NULL,
  `taxId` int(11) NOT NULL,
  `tax` longtext DEFAULT NULL,
  `createdOn` int(11) NOT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ie_wastage_stocks`
--

CREATE TABLE `ie_wastage_stocks` (
  `id` int(11) NOT NULL,
  `storeId` int(11) DEFAULT NULL,
  `openingStockNumber` int(11) DEFAULT NULL,
  `productId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `productSiUnitId` int(11) NOT NULL,
  `wastageStockNumber` int(11) NOT NULL,
  `productQuantity` decimal(10,3) NOT NULL,
  `productQuantityConversion` decimal(40,4) DEFAULT 0.0000,
  `productUnitPrice` decimal(20,2) NOT NULL,
  `productSubtotal` decimal(20,2) NOT NULL,
  `productTax` decimal(10,2) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `createdOn` int(11) DEFAULT NULL,
  `updatedOn` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ie_wastage_stocks`
--

INSERT INTO `ie_wastage_stocks` (`id`, `storeId`, `openingStockNumber`, `productId`, `userId`, `productSiUnitId`, `wastageStockNumber`, `productQuantity`, `productQuantityConversion`, `productUnitPrice`, `productSubtotal`, `productTax`, `comment`, `createdOn`, `updatedOn`, `isDeleted`) VALUES
(1, NULL, 1, 104, 1, 39, 1, '30.000', '30.0000', '0.00', '0.00', NULL, 'Tessting for wastage', 1610804725, NULL, NULL),
(2, NULL, 1, 105, 1, 39, 1, '50.000', '50.0000', '0.00', '0.00', NULL, 'Tessting for wastage', 1610804725, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ie_categories`
--
ALTER TABLE `ie_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_closing_stocks`
--
ALTER TABLE `ie_closing_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_excel_imports`
--
ALTER TABLE `ie_excel_imports`
  ADD PRIMARY KEY (`excelImportId`);

--
-- Indexes for table `ie_logged_in_history`
--
ALTER TABLE `ie_logged_in_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_opening_stocks`
--
ALTER TABLE `ie_opening_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_order_recipes`
--
ALTER TABLE `ie_order_recipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_products`
--
ALTER TABLE `ie_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_products_taxes`
--
ALTER TABLE `ie_products_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_purchase_stocks`
--
ALTER TABLE `ie_purchase_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_recipes`
--
ALTER TABLE `ie_recipes`
  ADD PRIMARY KEY (`recipeId`);

--
-- Indexes for table `ie_requests`
--
ALTER TABLE `ie_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_si_units`
--
ALTER TABLE `ie_si_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_taxes`
--
ALTER TABLE `ie_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_transfer_stocks`
--
ALTER TABLE `ie_transfer_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_users`
--
ALTER TABLE `ie_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_vendors`
--
ALTER TABLE `ie_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_vendor_products`
--
ALTER TABLE `ie_vendor_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_vendor_product_taxes`
--
ALTER TABLE `ie_vendor_product_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ie_wastage_stocks`
--
ALTER TABLE `ie_wastage_stocks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ie_categories`
--
ALTER TABLE `ie_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `ie_closing_stocks`
--
ALTER TABLE `ie_closing_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ie_excel_imports`
--
ALTER TABLE `ie_excel_imports`
  MODIFY `excelImportId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ie_logged_in_history`
--
ALTER TABLE `ie_logged_in_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ie_opening_stocks`
--
ALTER TABLE `ie_opening_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `ie_order_recipes`
--
ALTER TABLE `ie_order_recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ie_products`
--
ALTER TABLE `ie_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT for table `ie_products_taxes`
--
ALTER TABLE `ie_products_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ie_purchase_stocks`
--
ALTER TABLE `ie_purchase_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ie_recipes`
--
ALTER TABLE `ie_recipes`
  MODIFY `recipeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ie_requests`
--
ALTER TABLE `ie_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ie_si_units`
--
ALTER TABLE `ie_si_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `ie_taxes`
--
ALTER TABLE `ie_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ie_transfer_stocks`
--
ALTER TABLE `ie_transfer_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ie_users`
--
ALTER TABLE `ie_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ie_vendors`
--
ALTER TABLE `ie_vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ie_vendor_products`
--
ALTER TABLE `ie_vendor_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `ie_vendor_product_taxes`
--
ALTER TABLE `ie_vendor_product_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ie_wastage_stocks`
--
ALTER TABLE `ie_wastage_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
