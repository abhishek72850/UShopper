-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2018 at 07:02 PM
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
-- Table structure for table `wishlist_table`
--

CREATE TABLE `wishlist_table` (
  `sno` int(10) NOT NULL,
  `uid` varchar(25) NOT NULL COMMENT 'user id',
  `id` varchar(25) NOT NULL COMMENT 'shop id or product id',
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wishlist_table`
--

INSERT INTO `wishlist_table` (`sno`, `uid`, `id`, `type`, `date`, `time`) VALUES
(3, '90004239633101403193', '11223344556677889900', 'product', '2017-04-08', '11:03:13'),
(4, '90004239633101403193', '67584957654857273649', 'product', '2017-04-08', '11:03:22'),
(5, '41191061010002919174', '45793209384932495654', 'product', '2017-04-21', '11:02:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wishlist_table`
--
ALTER TABLE `wishlist_table`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `sno` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wishlist_table`
--
ALTER TABLE `wishlist_table`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
