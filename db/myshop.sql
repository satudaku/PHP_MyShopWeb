-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2021 at 11:44 AM
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
(6, 'qqqq', 'q@q.q', '$2y$10$jGSDwZAA.ttPF2BUKfvAVONc.BHIZ5rWl14Gz3qkjizodPNR5Nk2u'),
(2083422000, 'dog', 'dogdog@dog.dog', '$2y$10$WfNAvRv7EC3HkhRFd3JM0eXGqhCo47wRYKulLOW.dOpPBgWrBkhIW');

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `order_product_id` int(10) NOT NULL,
  `product` varchar(150) NOT NULL,
  `product_price` int(11) NOT NULL,
  `shipping_address` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_product`
--

INSERT INTO `order_product` (`order_product_id`, `product`, `product_price`, `shipping_address`) VALUES
(1000000000, 'fdnsfjdsmlfkamds', 10001, 'adpfpafp1234'),
(1000000001, 'ewqeqwe', 10500, 'wqqqqq'),
(1000000002, '1111111sadasdadsada', 100010000, 'wweqqqqqqqqqqqqqqqq'),
(1000000003, '1wwwwwwwwwwwwwwwwwwwwwwww', 2147483647, 'wwwwwwwwwwwwwwwwwwwwwsaaaaaaaaaasssssssssssssssssssssssssssssss'),
(1000000004, '124342t53423', 244567, 'qrtehfdsffwd'),
(1000000005, 'erwerewr', 40000, 'errrr'),
(1000000006, '3525323', 2147483647, '322222222222'),
(1000000007, 'ewqeqe', 132333, 'qwwwwwww'),
(1000000008, '2x apple 3x orange 7x bananas', 35000, 'taman griya e25, jimabarn, kuta, badung, bali, indonesia'),
(1000000009, '21wdsadsa', 432221, 'sdaaaaaaaaaadww'),
(1000000010, '213321rfffffffffffffffff', 12343333, 'wqeqweqqqqqqqqq'),
(1000000011, '234', 2232222, 'trdffg'),
(1000000012, '2345ewrw', 87664887, '324vrervwerff');

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
(1000000022, '2021-08-24 11:31:45', '2021-08-24 11:36:45', NULL, 'cancelled', 'YAN407QM', 5, 1000000000, NULL),
(1000000023, '2021-08-24 17:30:22', '2021-08-24 17:35:22', NULL, 'paid', NULL, 5, NULL, 1000000017),
(1000000024, '2021-08-24 19:06:58', '2021-08-24 19:11:58', NULL, 'paid', NULL, 5, NULL, 1000000018),
(1000000025, '2021-08-25 05:14:20', '2021-08-25 05:19:20', NULL, 'cancelled', NULL, 5, NULL, 1000000019),
(1000000026, '2021-08-25 05:15:51', '2021-08-25 05:20:51', NULL, 'paid', '6JY80GSU', 5, 1000000001, NULL),
(1000000027, '2021-08-25 05:29:48', '2021-08-25 05:34:48', NULL, 'cancelled', NULL, 5, NULL, 1000000020),
(1000000028, '2021-08-25 06:59:23', '2021-08-25 07:04:23', '2021-08-25 07:02:34', 'paid', 'IT7ZC148', 5, 1000000002, NULL),
(1000000029, '2021-08-25 08:23:02', '2021-08-25 08:28:02', '2021-08-25 08:23:22', 'paid', 'DJB3SIK6', 5, 1000000003, NULL),
(1000000030, '2021-08-25 08:46:55', '2021-08-25 08:51:55', '2021-08-25 08:46:59', 'paid', NULL, 5, NULL, 1000000021),
(1000000031, '2021-08-25 08:47:21', '2021-08-25 08:52:21', NULL, 'cancelled', 'XSV0428I', 5, 1000000004, NULL),
(1000000032, '2021-08-25 08:52:55', '2021-08-25 08:57:55', NULL, 'cancelled', NULL, 5, NULL, 1000000022),
(1000000033, '2021-08-25 08:53:38', '2021-08-25 08:58:38', NULL, 'cancelled', NULL, 5, NULL, 1000000023),
(1000000034, '2021-08-25 11:47:34', '2021-08-25 11:52:34', '2021-08-25 11:48:18', 'paid', NULL, 5, NULL, 1000000024),
(1000000035, '2021-09-25 09:41:01', '2021-09-25 09:46:01', NULL, 'paid', NULL, 5, NULL, 1000000025),
(1000000036, '2021-09-25 09:49:25', '2021-09-25 09:54:25', NULL, 'cancelled', 'V38X7NZT', 5, 1000000005, NULL),
(1000000037, '2021-09-25 13:35:24', '2021-09-25 13:40:24', NULL, 'cancelled', NULL, 5, NULL, 1000000026),
(1000000038, '2021-09-25 13:43:37', '2021-09-25 13:48:37', NULL, 'cancelled', NULL, 5, NULL, 1000000027),
(1000000039, '2021-09-25 13:53:46', '2021-09-25 13:58:46', NULL, 'paid', NULL, 5, NULL, 1000000028),
(1000000040, '2021-09-25 14:32:12', '2021-09-25 14:37:12', NULL, 'paid', NULL, 5, NULL, 1000000029),
(1000000041, '2021-09-25 14:32:29', '2021-09-25 14:37:29', NULL, 'paid', NULL, 5, NULL, 1000000030),
(1000000042, '2021-09-25 14:34:01', '2021-09-25 14:39:01', NULL, 'cancelled', 'LRXM9D1W', 5, 1000000006, NULL),
(1000000043, '2021-09-25 14:43:43', '2021-09-25 14:48:43', NULL, 'cancelled', NULL, 5, NULL, 1000000031),
(1000000044, '2021-09-25 14:43:53', '2021-09-25 14:48:53', NULL, 'cancelled', NULL, 5, NULL, 1000000032),
(1000000045, '2021-09-26 09:15:10', '2021-09-26 09:20:10', NULL, 'cancelled', NULL, 5, NULL, 1000000033),
(1000000046, '2021-10-01 06:29:33', '2021-10-01 06:34:33', NULL, 'cancelled', NULL, 5, NULL, 1000000034),
(1000000047, '2021-10-01 06:30:24', '2021-10-01 06:35:24', NULL, 'cancelled', 'YRP1K8UG', 5, 1000000007, NULL),
(1000000048, '2021-10-01 06:53:06', '2021-10-01 06:58:06', NULL, 'cancelled', NULL, 5, NULL, 1000000035),
(1000000049, '2021-10-01 07:14:28', '2021-10-01 07:19:28', NULL, 'cancelled', NULL, 6, NULL, 1000000036),
(1000000050, '2021-10-01 07:54:26', '2021-10-01 07:59:26', NULL, 'cancelled', NULL, 5, NULL, 1000000037),
(1000000051, '2021-10-01 09:23:43', '2021-10-01 09:28:43', NULL, 'cancelled', NULL, 5, NULL, 1000000038),
(1000000052, '2021-10-01 09:46:41', '2021-10-01 09:51:41', NULL, 'cancelled', NULL, 5, NULL, 1000000039),
(1000000053, '2021-10-01 10:12:02', '2021-10-01 10:17:02', NULL, 'paid', 'Z6NA42HY', 5, 1000000008, NULL),
(1000000054, '2021-10-02 15:37:33', '2021-10-02 15:42:33', NULL, 'cancelled', '8RE5IJGW', 5, 1000000009, NULL),
(1000000055, '2021-10-02 15:43:12', '2021-10-02 15:48:12', NULL, 'cancelled', 'GC9YH8LS', 5, 1000000010, NULL),
(1000000056, '2021-10-03 05:21:09', '2021-10-03 05:26:09', NULL, 'cancelled', NULL, 2083422000, NULL, 1000000040),
(1000000057, '2021-10-03 05:27:56', '2021-10-03 05:32:56', NULL, 'paid', 'T24C31HJ', 2083422000, 1000000011, NULL),
(1000000058, '2021-10-03 05:45:18', '2021-10-03 05:50:18', NULL, 'unpaid', NULL, 2083422000, NULL, 1000000041),
(1000000059, '2021-10-03 07:41:01', '2021-10-03 07:46:01', NULL, 'failed', NULL, 5, NULL, 1000000042),
(1000000060, '2021-10-03 07:50:24', '2021-10-03 07:55:24', NULL, 'paid', NULL, 5, NULL, 1000000043),
(1000000061, '2021-10-03 07:52:51', '2021-10-03 07:57:51', NULL, 'failed', NULL, 5, NULL, 1000000044),
(1000000062, '2021-10-03 07:53:34', '2021-10-03 07:58:34', NULL, 'failed', NULL, 5, NULL, 1000000045),
(1000000063, '2021-10-03 08:06:38', '2021-10-03 08:11:38', NULL, 'cancelled', '4QN2LA16', 5, 1000000012, NULL);

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
(1000000017, '081213141414', 52500),
(1000000018, '081321321', 105000),
(1000000019, '081123213213', 105000),
(1000000020, '081111111', 105000),
(1000000021, '081234567', 105000),
(1000000022, '0811234522', 52500),
(1000000023, '081123456', 105000),
(1000000024, '08112345544', 52500),
(1000000025, '08112345676', 105000),
(1000000026, '0812134567', 52500),
(1000000027, '081467494444', 10500),
(1000000028, '081325235', 105000),
(1000000029, '08124444444', 105000),
(1000000030, '081432432423', 105000),
(1000000031, '081234324', 52500),
(1000000032, '0813123111', 105000),
(1000000033, '0811234564', 105000),
(1000000034, '081234567', 52500),
(1000000035, '081123123123', 52500),
(1000000036, '081123123123', 52500),
(1000000037, '081123123123', 10500),
(1000000038, '081123123123', 105000),
(1000000039, '081234234234', 52500),
(1000000040, '081123456', 105000),
(1000000041, '081765432', 105000),
(1000000042, '081123456', 105000),
(1000000043, '081123456', 105000),
(1000000044, '0814567654', 105000),
(1000000045, '081876543', 52500);

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
  MODIFY `customer_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2083422001;

--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `order_product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000013;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000064;

--
-- AUTO_INCREMENT for table `order_topup`
--
ALTER TABLE `order_topup`
  MODIFY `order_topup_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000046;

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
