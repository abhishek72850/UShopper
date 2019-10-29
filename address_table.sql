-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2018 at 07:00 PM
-- Server version: 10.2.12-MariaDB
-- PHP Version: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id951708_ushopper`
--

-- --------------------------------------------------------

--
-- Table structure for table `address_table`
--

CREATE TABLE `address_table` (
  `sno` int(10) NOT NULL,
  `id` varchar(25) NOT NULL COMMENT 'shop id or user id',
  `aid` varchar(25) NOT NULL COMMENT 'shop or user address id',
  `type` varchar(10) NOT NULL COMMENT 'type of id whether its shop or user',
  `name` varchar(100) NOT NULL COMMENT 'name of the owner of that address',
  `street_address` varchar(200) NOT NULL COMMENT 'street address',
  `landmark` varchar(100) NOT NULL COMMENT 'landmark of address',
  `city` varchar(100) NOT NULL COMMENT 'city name',
  `state` varchar(100) NOT NULL COMMENT 'state name',
  `mobile` int(10) NOT NULL COMMENT 'mobile number',
  `pincode` int(10) NOT NULL COMMENT 'pincode of address'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address_table`
--
ALTER TABLE `address_table`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `sno` (`aid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address_table`
--
ALTER TABLE `address_table`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
