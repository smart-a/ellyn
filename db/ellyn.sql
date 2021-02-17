-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2020 at 03:56 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ellyn`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `item_brand` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `item_brand`) VALUES
(2, 'Samsung'),
(3, 'HP'),
(4, 'Fendi'),
(5, 'Adiddas');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `item_id` varchar(15) NOT NULL,
  `size` varchar(5) NOT NULL,
  `color` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `username`, `item_id`, `size`, `color`) VALUES
(2, 'omogiwa', 'AKJHJZ0750', 'L', 'Black'),
(3, 'omogiwa', 'UCUKDJ2834', '', ''),
(4, 'omogiwa', 'KRVVDY3705', '40', ''),
(6, 'omogiwa', 'PRUNRO4263', '', ''),
(7, 'omogiwa', 'JNSTWC2742', 'M', 'Blue'),
(18, 'smarta', 'HCDHTQ8452', 'M', 'Black'),
(20, 'smarta', 'UCUKDJ2834', '', ''),
(21, 'smarta', 'PRUNRO4263', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `item_category` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `item_category`) VALUES
(14, 'Electronics'),
(17, 'Diapers'),
(18, 'Kitchen Utensils'),
(37, 'Toys'),
(38, 'Wears'),
(39, 'Shopping'),
(40, 'Cosmetics'),
(41, 'Women''s Fashion'),
(42, 'Men''s Fashion'),
(43, 'Baby Products'),
(44, 'Phones & Tablets'),
(45, 'Home & Office'),
(46, 'Health & Beauty');

-- --------------------------------------------------------

--
-- Table structure for table `e_token_auth`
--

CREATE TABLE `e_token_auth` (
  `user_hash` varchar(50) NOT NULL,
  `password_hash` varchar(20) NOT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `e_token_auth`
--

INSERT INTO `e_token_auth` (`user_hash`, `password_hash`, `expiry_date`) VALUES
('smarta', 'mBnrhprWuBfs5lFf', '2020-09-17 14:26:35');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` varchar(11) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `item_category` int(11) NOT NULL,
  `item_subcat` int(11) NOT NULL,
  `item_brand` int(11) NOT NULL,
  `item_amount` double NOT NULL,
  `item_qty` int(11) NOT NULL,
  `item_size` varchar(20) NOT NULL,
  `item_color` varchar(50) NOT NULL,
  `item_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_category`, `item_subcat`, `item_brand`, `item_amount`, `item_qty`, `item_size`, `item_color`, `item_date`) VALUES
('ACSKZA8445', 'Nothing to wear', 38, 5, 0, 2000, 1, 'L', '', '2020-07-01'),
('AKJHJZ0750', 'Men Native (White & Black)', 38, 4, 0, 8000, 3, 'L,XL', 'Black,White', '2020-06-11'),
('FBOYBR3386', 'Fendi Original', 38, 5, 0, 10000, 4, 'M', 'Black', '2020-06-09'),
('FTPBKI0235', 'Women Native', 38, 4, 0, 3000, 3, 'L', 'Green', '2020-06-11'),
('GEETDF3805', 'Luxury Chair', 45, 8, 0, 25000, 5, '', '', '2020-08-05'),
('GUHMPR8216', 'Luxury Bed', 45, 9, 0, 150000, 2, '', '', '2020-08-05'),
('HCDHTQ8452', 'Puma T-Shirt (Black)', 38, 5, 0, 3000, 4, 'M', 'Black', '2020-06-09'),
('HJWRDR8038', 'Nike T-Shirt', 38, 5, 0, 8000, 7, 'S,L', 'Blue,Black', '2020-06-09'),
('JNSTWC2742', 'Fendi Original', 38, 5, 0, 10000, 2, 'M,L', 'Blue,Black', '2020-06-09'),
('KRVVDY3705', 'Adiddas Shoe', 38, 3, 5, 3000, 4, '38,39,40,41', '', '2020-07-02'),
('MDGHJR6844', 'Nike T-Shirt', 38, 5, 0, 3000, 9, 'M', 'Blue', '2020-06-09'),
('MDQKWY5251', 'Puma T-Shirt (Black)', 38, 5, 0, 5000, 3, 'L,XL', 'Green,Yellow', '2020-06-09'),
('NFYHWF0515', 'Men Native', 38, 4, 0, 5000, 3, 'L,XL', 'Black,White', '2020-06-11'),
('PRUNRO4263', 'Set of Box', 39, 7, 0, 45000, 5000, '', '', '2020-07-10'),
('UCUKDJ2834', 'Fendi TV 32'''' LCD', 14, 1, 4, 50000, 1, '', '', '2020-06-11'),
('VYHUPT1041', 'Fendi Gown', 38, 6, 0, 3000, 3, 'L', 'Black', '2020-06-09');

-- --------------------------------------------------------

--
-- Table structure for table `item_like`
--

CREATE TABLE `item_like` (
  `like_id` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `item_id` varchar(15) NOT NULL,
  `liked` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_like`
--

INSERT INTO `item_like` (`like_id`, `username`, `item_id`, `liked`) VALUES
(1, 'smarta', 'NFYHWF0515', 1),
(16, 'smarta', 'JNSTWC2742', 1),
(17, 'smarta', 'AKJHJZ0750', 1),
(18, 'smarta', 'HJWRDR8038', 1),
(19, 'omogiwa', 'AKJHJZ0750', 1),
(20, 'omogiwa', 'UCUKDJ2834', 1),
(21, 'omogiwa', 'JNSTWC2742', 1),
(33, 'smarta', 'HCDHTQ8452', 1),
(35, 'smarta', 'ACSKZA8445', 1),
(36, 'smarta', 'PRUNRO4263', 1),
(37, 'smarta', 'UCUKDJ2834', 1),
(39, 'smarta', 'MDGHJR6844', 1),
(40, 'smarta', 'FBOYBR3386', 1),
(41, 'smarta', 'VYHUPT1041', 1),
(42, 'smarta', 'KRVVDY3705', 1),
(43, 'smarta', 'FTPBKI0235', 1),
(44, 'smarta', 'MDQKWY5251', 1),
(45, 'smarta', 'GEETDF3805', 1),
(46, 'smarta', 'GUHMPR8216', 1);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `fullname` varchar(60) NOT NULL,
  `country` varchar(10) NOT NULL,
  `state` varchar(15) NOT NULL,
  `city` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`fullname`, `country`, `state`, `city`, `address`, `phone`, `email`, `username`, `password`) VALUES
('Giwa Hafsor', 'Nigeria', 'Kaduna', 'Kaduna North', 'Gulubi road', '07036625659', 'giwaafusat9@gmail.com', 'omogiwa', 'giwarike9'),
('Adesoye Abeeb Olalekan', 'Nigeria', 'Kaduna', 'Kaduna North', 'Kaduna South', '08066342078', 'olalekan.adesoye@gmail.com', 'smarta', 'unique11');

-- --------------------------------------------------------

--
-- Table structure for table `pickup_station`
--

CREATE TABLE `pickup_station` (
  `pickup_id` int(11) NOT NULL,
  `state` varchar(15) NOT NULL,
  `city` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `fee` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pickup_station`
--

INSERT INTO `pickup_station` (`pickup_id`, `state`, `city`, `address`, `fee`) VALUES
(1, '', '', 'No 22 Runka street, off Isa Kaita road, Ungwan Sarki, Kaduna.', 2000);

-- --------------------------------------------------------

--
-- Table structure for table `subcat`
--

CREATE TABLE `subcat` (
  `subcat_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `item_subcat` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subcat`
--

INSERT INTO `subcat` (`subcat_id`, `cat_id`, `item_subcat`) VALUES
(1, 14, 'Television'),
(2, 14, 'Andriod Phone'),
(3, 38, 'Shoes'),
(4, 38, 'Natives'),
(5, 38, 'T-Shirt'),
(6, 38, 'Gown'),
(7, 39, 'Boxes'),
(8, 45, 'Chairs & Tables'),
(9, 45, 'Beds');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `e_token_auth`
--
ALTER TABLE `e_token_auth`
  ADD PRIMARY KEY (`user_hash`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `item_like`
--
ALTER TABLE `item_like`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`username`,`email`);

--
-- Indexes for table `pickup_station`
--
ALTER TABLE `pickup_station`
  ADD PRIMARY KEY (`pickup_id`);

--
-- Indexes for table `subcat`
--
ALTER TABLE `subcat`
  ADD PRIMARY KEY (`subcat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `item_like`
--
ALTER TABLE `item_like`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `pickup_station`
--
ALTER TABLE `pickup_station`
  MODIFY `pickup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subcat`
--
ALTER TABLE `subcat`
  MODIFY `subcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
