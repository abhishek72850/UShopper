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
-- Table structure for table `cart_table`
--

CREATE TABLE `cart_table` (
  `sno` int(10) NOT NULL,
  `uid` varchar(25) NOT NULL COMMENT 'user id',
  `pid` varchar(25) NOT NULL COMMENT 'product id',
  `quantity` int(5) NOT NULL COMMENT 'product quantity',
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart_table`
--

INSERT INTO `cart_table` (`sno`, `uid`, `pid`, `quantity`, `date`, `time`) VALUES
(12, '90004239633101403193', '45793209384932495654', 2, '2017-04-09', '06:00:25'),
(13, '41191061010002919174', '67890543216661678977', 3, '2017-04-09', '16:46:22'),
(14, '106870505635566319848', '65748934857648374483', 4, '2017-04-13', '07:26:40'),
(15, '106870505635566319848', '78901234656789054321', 1, '2017-04-13', '07:26:45'),
(17, '90004239633101403193', '11223344556677889900', 1, '2017-04-15', '18:43:56'),
(18, '41191061010002919174', '45793209384932495654', 1, '2017-05-05', '14:48:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_table`
--
ALTER TABLE `cart_table`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_table`
--
ALTER TABLE `cart_table`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
