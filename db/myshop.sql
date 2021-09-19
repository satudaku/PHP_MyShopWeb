-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2021 at 06:23 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(10) NOT NULL,
  `customer_name` varchar(20) DEFAULT NULL,
  `customer_email` varchar(40) NOT NULL,
  `customer_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `customer_email`, `customer_password`) VALUES
(5, '', 'a@a.a', '$2y$10$PSY/MCQdGpjMc.Ck8j0w2OswurPRW1MkCNfoc/JPxlZZQb1khf/tG'),
(6, 'qqqq', 'q@q.q', '$2y$10$jGSDwZAA.ttPF2BUKfvAVONc.BHIZ5rWl14Gz3qkjizodPNR5Nk2u');

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `order_product_id` int(10) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_price` int(11) NOT NULL,
  `customer_adress` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `order_id` int(10) NOT NULL,
  `order_placed_time` datetime NOT NULL,
  `payment_due_time` datetime NOT NULL,
  `order_paid_time` datetime DEFAULT NULL,
  `order_status` text NOT NULL,
  `shipping_code` text DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `order_product_id` int(11) DEFAULT NULL,
  `order_topup_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`order_id`, `order_placed_time`, `payment_due_time`, `order_paid_time`, `order_status`, `shipping_code`, `customer_id`, `order_product_id`, `order_topup_id`) VALUES
(1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 'unpaid', NULL, 5, NULL, 11),
(2, '2021-08-19 08:00:36', '2021-08-19 08:05:36', NULL, 'unpaid', NULL, 5, NULL, 14),
(3, '2021-08-19 08:10:00', '2021-08-19 08:15:00', NULL, 'unpaid', NULL, 6, NULL, 15),
(4, '2021-08-19 08:34:41', '2021-08-19 08:39:41', NULL, 'unpaid', NULL, 6, NULL, 16),
(1000000000, '2021-08-19 09:39:44', '2021-08-19 09:44:44', NULL, 'unpaid', NULL, 6, NULL, 17),
(1000000001, '2021-08-19 09:40:08', '2021-08-19 09:45:08', NULL, 'unpaid', NULL, 6, NULL, 18),
(1000000002, '2021-08-19 11:46:13', '2021-08-19 11:51:13', NULL, 'unpaid', NULL, 6, NULL, 1000000000);

-- --------------------------------------------------------

--
-- Table structure for table `order_topup`
--

CREATE TABLE `order_topup` (
  `order_topup_id` int(10) NOT NULL,
  `mobile_no` varchar(12) NOT NULL,
  `balance_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_topup`
--

INSERT INTO `order_topup` (`order_topup_id`, `mobile_no`, `balance_value`) VALUES
(1, '123456', 10000),
(2, '123456', 10000),
(3, '123456', 10000),
(4, '123456', 10000),
(5, '123456', 10000),
(6, '081123456', 10000),
(7, '081092002', 50000),
(8, '081092002', 50000),
(9, '081092002', 50000),
(10, '081092002', 50000),
(11, '081132120', 100000),
(12, '08134243243', 10000),
(13, '08134243243', 10000),
(14, '08134243243', 10000),
(15, '08145646565', 50000),
(16, '081231231', 10000),
(17, '081213313', 50000),
(18, '08112111133', 50000),
(1000000000, '081123431', 100000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`customer_email`);

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`order_product_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_product_id` (`order_product_id`),
  ADD KEY `order_topup_id` (`order_topup_id`);

--
-- Indexes for table `order_topup`
--
ALTER TABLE `order_topup`
  ADD PRIMARY KEY (`order_topup_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `order_product_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000003;

--
-- AUTO_INCREMENT for table `order_topup`
--
ALTER TABLE `order_topup`
  MODIFY `order_topup_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000001;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_status`
--
ALTER TABLE `order_status`
  ADD CONSTRAINT `order_status_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `order_status_ibfk_2` FOREIGN KEY (`order_product_id`) REFERENCES `order_product` (`order_product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_status_ibfk_3` FOREIGN KEY (`order_topup_id`) REFERENCES `order_topup` (`order_topup_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
