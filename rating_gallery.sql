-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2018 at 07:01 PM
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
-- Table structure for table `rating_gallery`
--

CREATE TABLE `rating_gallery` (
  `sno` int(10) NOT NULL,
  `id` varchar(25) NOT NULL COMMENT 'shop id or product id',
  `five_rate` int(10) NOT NULL DEFAULT 0,
  `four_rate` int(10) NOT NULL DEFAULT 0,
  `three_rate` int(10) NOT NULL DEFAULT 0,
  `two_rate` int(10) NOT NULL DEFAULT 0,
  `one_rate` int(10) NOT NULL DEFAULT 0,
  `avg_rate` int(10) NOT NULL DEFAULT 0 COMMENT 'average rating',
  `total_rate` int(10) NOT NULL DEFAULT 0 COMMENT 'total rating',
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rating_gallery`
--

INSERT INTO `rating_gallery` (`sno`, `id`, `five_rate`, `four_rate`, `three_rate`, `two_rate`, `one_rate`, `avg_rate`, `total_rate`, `type`) VALUES
(1, '09876543216789054321', 4, 3, 2, 1, 1, 24, 242, 'shop'),
(2, '54321678901234567890', 4, 4, 3, 2, 1, 46, 123, 'shop'),
(3, '12345609871234567890', 5, 3, 3, 1, 1, 23, 221, 'shop'),
(4, '12345678901234567890', 4, 3, 2, 2, 1, 11, 111, 'shop'),
(5, '12345678900987654321', 3, 3, 3, 2, 1, 22, 123, 'shop'),
(6, '78977123456789066616', 4, 4, 2, 2, 1, 33, 432, 'product'),
(7, '12345678977789066616', 5, 4, 3, 2, 1, 33, 543, 'product'),
(8, '67890543216661678977', 5, 3, 3, 2, 1, 22, 33, 'product'),
(9, '78900987654321345678', 5, 4, 3, 2, 1, 12, 32, 'product'),
(10, '78954321606789054321', 4, 3, 2, 1, 1, 22, 333, 'product'),
(11, '45634658436598340634', 4, 3, 2, 2, 1, 32, 45, 'product'),
(12, '90876541236789054321', 3, 3, 3, 2, 1, 22, 31, 'product'),
(13, '11223344556789067890', 5, 3, 3, 2, 1, 22, 33, 'product'),
(14, '00998877665432112345', 4, 3, 2, 1, 1, 55, 66, 'product'),
(15, '12340998877665589076', 3, 2, 1, 2, 1, 46, 76, 'product'),
(16, '78901234656789054321', 4, 3, 2, 2, 1, 88, 789, 'product'),
(17, '11223344556677889900', 5, 4, 3, 2, 1, 11, 23, 'product'),
(18, '00119922883377446655', 2, 2, 2, 2, 1, 33, 44, 'product'),
(19, '12332145665478987000', 4, 4, 3, 2, 1, 43, 54, 'product'),
(20, '10299201384756478393', 3, 3, 3, 2, 1, 22, 34, 'product'),
(21, '12233344445555509867', 5, 4, 3, 2, 1, 12, 45, 'product'),
(22, '89076523418907652341', 3, 3, 3, 2, 1, 33, 23, 'product'),
(23, '43254213409978665733', 5, 4, 3, 2, 1, 56, 87, 'product'),
(26, '89076543125432167822', 4, 3, 2, 0, 1, 44, 337, 'product'),
(27, '54321234567890009811', 5, 4, 3, 2, 1, 33, 44, 'product'),
(28, '12345098767890054321', 5, 4, 3, 2, 1, 54, 65, 'shop'),
(29, '12345789534444447890', 4, 4, 2, 2, 1, 45, 54, 'shop'),
(30, '12348355555666667890', 5, 4, 2, 2, 1, 46, 56, 'shop'),
(31, '99988737623489772346', 4, 4, 2, 2, 1, 67, 86, 'product'),
(32, '23456789876543456785', 4, 4, 1, 2, 1, 57, 76, 'product'),
(33, '9834923498632468611', 3, 3, 3, 1, 1, 65, 75, 'product'),
(34, '56475647564647564477', 4, 3, 3, 2, 1, 55, 65, 'product'),
(35, '73687564387568743659', 4, 4, 3, 2, 1, 55, 77, 'product'),
(36, '45678765565567656767', 4, 3, 3, 1, 1, 45, 67, 'product'),
(37, '65484873457345834561', 3, 3, 3, 2, 1, 76, 87, 'product'),
(38, '45676567876098909892', 3, 2, 1, 2, 1, 70, 81, 'product'),
(39, '67480293642973249398', 4, 3, 1, 2, 1, 72, 82, 'product'),
(42, '42734681924980122746', 5, 3, 1, 1, 1, 74, 92, 'product'),
(43, '45793209384932495654', 5, 4, 1, 2, 1, 64, 79, 'product'),
(44, '2390239489385893034', 5, 3, 2, 2, 1, 54, 59, 'product'),
(46, '98232484734910983285', 5, 3, 3, 1, 1, 50, 69, 'product'),
(47, '43759237983455835233', 3, 3, 2, 1, 1, 40, 59, 'product'),
(48, '83479827485563767474', 5, 4, 2, 1, 1, 48, 89, 'product'),
(49, '67584957654857273649', 4, 4, 1, 1, 1, 38, 55, 'product'),
(50, '48593485938474378431', 4, 4, 2, 2, 1, 88, 99, 'product'),
(51, '9809876789365238763', 5, 4, 2, 1, 1, 44, 59, 'product'),
(52, '65748934857648374483', 4, 3, 2, 1, 1, 43, 44, 'product'),
(53, '56748576859285788373', 5, 3, 2, 2, 1, 77, 84, 'product'),
(54, '65748567587675892349', 5, 4, 2, 1, 1, 62, 74, 'product'),
(55, '56475647510984097477', 5, 4, 2, 2, 1, 12, 24, 'product'),
(56, '35874980234578293355', 5, 3, 3, 2, 1, 11, 14, 'product'),
(57, '28358923794756456456', 3, 3, 3, 2, 1, 15, 19, 'product'),
(58, '34895792833924034948', 5, 3, 3, 1, 1, 20, 29, 'product'),
(59, '25728949784754857488', 5, 4, 3, 2, 1, 22, 27, 'product'),
(60, '43857823475293847380', 3, 4, 3, 1, 1, 32, 37, 'product'),
(61, '67654567656765788899', 5, 2, 3, 2, 1, 42, 48, 'product'),
(62, '65347829353823843939', 5, 3, 2, 2, 1, 52, 58, 'product'),
(63, '48572984209309849357', 5, 4, 3, 2, 1, 100, 115, 'product');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rating_gallery`
--
ALTER TABLE `rating_gallery`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `sno` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rating_gallery`
--
ALTER TABLE `rating_gallery`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
