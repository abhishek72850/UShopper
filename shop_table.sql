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
-- Table structure for table `shop_table`
--

CREATE TABLE `shop_table` (
  `sno` int(11) NOT NULL,
  `sid` varchar(25) NOT NULL,
  `sname` varchar(100) NOT NULL,
  `spic` varchar(200) NOT NULL,
  `stype` varchar(20) NOT NULL,
  `stag` varchar(200) NOT NULL,
  `semail` varchar(200) NOT NULL,
  `smobile` int(11) NOT NULL,
  `srating` int(11) NOT NULL,
  `sdate` date NOT NULL,
  `stime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shop_table`
--

INSERT INTO `shop_table` (`sno`, `sid`, `sname`, `spic`, `stype`, `stag`, `semail`, `smobile`, `srating`, `sdate`, `stime`) VALUES
(13, '12345678900987654321', 'arpit store', 'PC/Pictures/myWall.jpg', 'grocery', 'grocery,dailyneeds,general', 'arpitstore@gmail.com', 987654321, 5, '2017-03-15', '06:35:10'),
(14, '12345678901234567890', 'raj general store', 'PC/Pictures/myWall/385168_robert_downey_jr.jpg', 'grocery', 'grocery,dailyneeds,general', 'rajstore@gamil.com', 912345678, 4, '2017-03-18', '14:09:24'),
(15, '12345609871234567890', 'arpit fashion store', 'PC/Pictures/myWall/385168_robert_downey_jr.jpg', 'cloths', 'fashion,dailyneeds,clothes,shirt,jeans,tshirts,jacket', 'arpitfashion@gmail.com', 981234567, 7, '2017-03-16', '05:00:00'),
(16, '54321678901234567890', 'raj fashion store', 'PC/Pictures/myWall/385168_robert_downey_jr.jpg', 'cloths', 'cloths,fashion,dailyneeds,shirts,tshirts,jacket,jeans', 'rajfashion@gmail.com', 912876543, 8, '2017-03-06', '03:15:00'),
(17, '09876543216789054321', 'sarswati sweets', 'PC/Pictures/myWall/385168_robert_downey_jr.jpg', 'sweets', 'sweets,laddu,pede,chamcham,rasgulla', 'sarswatisweet@gmail.com', 987651234, 6, '2017-03-14', '17:09:00'),
(18, '12345098767890054321', 'shivam general store', 'shivam.jpg', 'general store', 'generalstore,general store,grocery,store', 'shiamstore@gmail.com', 789876567, 8, '2017-03-09', '05:32:23'),
(19, '12345789534444447890', 'gulati general store', 'gulati.jpg', 'general store', 'generalstore,general store,grocery shop,shop', 'gulatistore@gmail.com', 789876567, 8, '2017-03-15', '06:09:24'),
(20, '12348355555666667890', 'pappu sweet store', 'pappusweet.jpg', 'sweet store', 'sweetstore,sweet store,grocery shop,grocery store,mithai shop', 'pappusweet@gmail.com', 789875455, 7, '2017-03-15', '05:28:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shop_table`
--
ALTER TABLE `shop_table`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shop_table`
--
ALTER TABLE `shop_table`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
