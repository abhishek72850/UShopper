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
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `sno` bigint(10) NOT NULL COMMENT 'serial number',
  `uid` varchar(25) NOT NULL COMMENT 'user unique id',
  `uemail` varchar(100) NOT NULL COMMENT 'user email',
  `uname` varchar(50) DEFAULT NULL COMMENT 'user name',
  `ulogintype` varchar(10) NOT NULL COMMENT 'user login type google, fb or self',
  `can_fb_login` tinyint(1) DEFAULT NULL COMMENT 'can user login with facebook',
  `can_g_login` tinyint(1) DEFAULT NULL COMMENT 'can user login with google',
  `upassword` varchar(15) DEFAULT NULL COMMENT 'user password',
  `upic` varchar(200) DEFAULT NULL COMMENT 'user profile pic url',
  `ugender` varchar(6) DEFAULT NULL COMMENT 'user gender',
  `umobile` bigint(10) DEFAULT NULL COMMENT 'user mobile number',
  `upoints` int(10) NOT NULL DEFAULT 0 COMMENT 'user reward point',
  `ufavtags` varchar(500) DEFAULT NULL COMMENT 'user search tags',
  `udate` date NOT NULL COMMENT 'user signup date',
  `utime` time NOT NULL COMMENT 'user signup time'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`sno`, `uid`, `uemail`, `uname`, `ulogintype`, `can_fb_login`, `can_g_login`, `upassword`, `upic`, `ugender`, `umobile`, `upoints`, `ufavtags`, `udate`, `utime`) VALUES
(2, '123456789', 'abhi@gmail.com', NULL, 'SELF', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2017-03-18', '01:58:23'),
(3, '106870505635566319848', 'alexkay72850@gmail.com', 'Abhishek Kumar', 'GOOGLE', NULL, NULL, NULL, 'https://lh3.googleusercontent.com/-krDuqHkLMqw/AAAAAAAAAAI/AAAAAAAAACs/qvZm22z88qo/s96-c/photo.jpg', NULL, NULL, 0, NULL, '2017-03-18', '02:16:42'),
(4, '12170011110150292206', 'gmail.com', NULL, 'SELF', NULL, NULL, '12345', NULL, NULL, NULL, 0, NULL, '2017-03-22', '11:31:38'),
(5, '41191061010002919174', 'shubhamgulati81@gmail.com', 'shubham gulati', 'GOOGLE', NULL, NULL, NULL, 'https://lh4.googleusercontent.com/-eE0p0-sSz1Q/AAAAAAAAAAI/AAAAAAAAD7w/oXCxd_hP2zw/s96-c/photo.jpg', NULL, NULL, 0, NULL, '2017-03-22', '12:00:06'),
(6, '90004239633101403193', 'getconnect2shubham@yahoo.com', 'Shubham Gulati', 'FB', NULL, NULL, NULL, 'https://scontent.xx.fbcdn.net/v/t1.0-1/s100x100/17190704_1272472322846696_5117384613764878913_n.jpg?oh=8f115a8ae318c6ade2809bccc0d9059f&oe=5927E8E9', NULL, NULL, 0, NULL, '2017-03-22', '12:01:00'),
(7, '42290041712018121909', 'shubham@gmail.com', NULL, 'SELF', NULL, NULL, '12345', NULL, NULL, NULL, 0, NULL, '2017-03-22', '12:02:06'),
(8, '10076981903098061111', 'shubham2@gmail.com', NULL, 'SELF', NULL, NULL, '12345', NULL, NULL, NULL, 0, NULL, '2018-04-14', '07:57:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `sno` (`uid`,`uemail`,`umobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `sno` bigint(10) NOT NULL AUTO_INCREMENT COMMENT 'serial number', AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
