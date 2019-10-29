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
-- Table structure for table `product_table`
--

CREATE TABLE `product_table` (
  `sno` int(10) NOT NULL COMMENT 'serial number',
  `pid` varchar(25) NOT NULL COMMENT 'product id',
  `sid` varchar(25) NOT NULL COMMENT 'product shop id',
  `pname` varchar(100) NOT NULL COMMENT 'product name',
  `pmrp` int(10) NOT NULL COMMENT 'product mrp',
  `pactualprice` int(10) NOT NULL COMMENT 'product actual price',
  `pquantity` int(10) NOT NULL COMMENT 'product quantity in stock',
  `pfeature` varchar(500) NOT NULL COMMENT 'product feature',
  `pspecs` varchar(500) NOT NULL COMMENT 'product specification',
  `ptag` varchar(1000) NOT NULL COMMENT 'product category and search tags',
  `prating` int(5) NOT NULL DEFAULT 0 COMMENT 'product rating ',
  `poffer` varchar(500) DEFAULT NULL COMMENT 'any offer on product',
  `pdiscount` int(4) DEFAULT NULL,
  `pdate` date NOT NULL,
  `ptime` time NOT NULL,
  `psold` int(5) NOT NULL DEFAULT 0 COMMENT 'number of product sold',
  `barcode` bigint(20) DEFAULT NULL COMMENT 'barcode of the product'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_table`
--

INSERT INTO `product_table` (`sno`, `pid`, `sid`, `pname`, `pmrp`, `pactualprice`, `pquantity`, `pfeature`, `pspecs`, `ptag`, `prating`, `poffer`, `pdiscount`, `pdate`, `ptime`, `psold`, `barcode`) VALUES
(13, '78977123456789066616', '12345678900987654321', 'rin shoap', 25, 20, 2, 'clean your cloths perfectly', '100gm', 'shoap,rin,clothshoap,cleancloths', 8, '5%', 40, '2017-03-14', '05:00:35', 10, 42289197),
(14, '12345678977789066616', '12345678900987654321', 'hair gel', 500, 450, 5, 'for smooth and straight your hair', 'green color,90gm,menthol ', 'hairgel,gel,hair', 6, '5%', 40, '2017-03-01', '04:03:13', 100, 42289197),
(15, '67890543216661678977', '12345678900987654321', 'dove shampoo', 150, 140, 1, 'for sliky hair ,smooth hair', '150gm,with fragrance smell', 'shampoo,dove,hair,dailyneeds,grocery,general', 10, '4%', 40, '2017-03-09', '02:13:00', 150, 42289197),
(16, '78900987654321345678', '12345678900987654321', 'hair oil', 100, 90, 2, 'for black hair,long hair,thick hair', 'with almond,150ml', 'hairoil,oil,hair,dailyneeds,general,grocery', 9, '4%', 40, '2017-03-10', '02:22:00', 123, 42289197),
(17, '78954321606789054321', '12345678901234567890', 'garnier facewash', 150, 145, 1, 'for fair face,remove oil and dirt', 'with menthol,100ml', 'garnierfacewash,facewash,garnier,dailyneeds,grocery,general', 9, '2%', 40, '2017-03-03', '02:25:11', 34, 42289197),
(18, '45634658436598340634', '12345678901234567890', 'nivea cream', 190, 180, 2, 'dark spot removal,for bright skin', '85gm,white cream', 'niveacream,nivea,cream,dailyneeds,dailyproduct,grocery,general', 7, '6%', 40, '2017-03-02', '17:13:00', 98, 42289197),
(19, '90876541236789054321', '12345678901234567890', 'parle biscuits', 50, 45, 5, 'high protein,0%fat,sweeter in taste', '10 cookies,20% extra', 'parlebiscuits,parle,biscuits,cookies,dailyneeds,grocery,general,goodday,britenia', 8, '7%', 40, '2017-03-02', '16:00:12', 185, 42289197),
(20, '11223344556789067890', '12345678901234567890', 'haldiram namkeen', 130, 125, 1, 'good in taste,spicy ', '250gm,10% extra', 'haldiramnamkeen,hadiram,namkeen,snacks,dailyneeds,grocery,general', 7, '2%', 40, '2017-03-18', '03:35:00', 159, 42289197),
(21, '00998877665432112345', '12345609871234567890', 'shirts', 559, 499, 1, 'very comfortable,light weight  ', 'wool cotton,full sleeve,check', 'shirts,fullsleeveshirts,halfsleeveshirts,fashion,cloths,menswear,womenswear', 8, '10%', 40, '2017-03-08', '07:24:00', 50, 42289197),
(22, '12340998877665589076', '12345609871234567890', 'jeans', 1100, 999, 1, 'very comfortable', 'fabric,damage', 'jeans,pents,lowewear,menswear,womenswear,fashion,cloths', 7, '8%', 40, '2017-03-17', '03:09:24', 58, 42289197),
(23, '78901234656789054321', '12345609871234567890', 'jacket', 1799, 1499, 1, 'denim,very comfortable', 'full sleeve,fabric', 'jacket,upperwear,menswear,womenwear,fashion,cloths', 8, '11%', 40, '2017-03-07', '08:05:18', 55, 42289197),
(24, '11223344556677889900', '12345609871234567890', 'tshirts', 1000, 750, 3, 'very comfortable,light wieght', 'full sleeve,black,round neck', 'tshirts,upperwear,menswear,womenwear,fullsleevetshirts,fashion,cloths', 7, '9%', 40, '2017-03-02', '02:28:00', 90, 42289197),
(25, '00119922883377446655', '54321678901234567890', 'sweatshirts', 1500, 1199, 1, 'keeps you warmer,light weight,comfortable', 'fullsleeve,v-neck,check,denim', 'fullsleeve,sweatshirts,sweaters,winterwear,fashion,cloths', 8, '13%', 40, '2017-03-19', '05:13:27', 29, 42289197),
(26, '12332145665478987000', '54321678901234567890', 'trouser', 1200, 899, 2, 'loose,full,comfortable', 'printed,full', 'trouser,lowerwear,menswear,womenwear,fashion,cloths', 6, '10%', 40, '2017-03-01', '06:09:40', 96, 42289197),
(27, '10299201384756478393', '54321678901234567890', 'kurta pajama', 1000, 799, 2, 'comfortable,cotton', 'full sleeve,stand collar', 'kurtapajama,kurta,pajama,menswear,womenwear,fashion,cloths', 8, '7%', 40, '2017-03-11', '09:24:08', 67, 42289197),
(28, '12233344445555509867', '54321678901234567890', 'tracksuits', 1600, 1299, 1, 'fabric,comfortable,stand collar ', 'full sleeve', 'tracksuits,winterwear,fitness,fashion,menswear,womenwear,cloths', 9, '8%', 40, '2017-03-08', '03:24:14', 77, 42289197),
(29, '89076523418907652341', '09876543216789054321', 'rasgulla', 150, 140, 1, 'sweeter in taste,', 'black,spherical', 'rasgulla,lalmohan,gulabjamun,sweet,mithai', 8, '4%', 40, '2017-03-07', '04:11:48', 57, 42289197),
(30, '43254213409978665733', '09876543216789054321', 'laddu', 200, 180, 1, 'sweeter in taste', 'made up of boondi,250gm', 'sweets,mithai,laddu', 8, '7%', 40, '2017-03-16', '16:41:09', 78, 42289197),
(31, '89076543125432167822', '09876543216789054321', 'barfi', 250, 200, 1, 'sweeter in taste', 'made up of khoa,1kg', 'barfi,sweets,mithai', 9, '11%', 40, '2017-03-03', '15:17:11', 45, 42289197),
(32, '54321234567890009811', '09876543216789054321', 'gujia', 280, 240, 1, 'sweeter in taste', 'made up of khoa,1kg', 'gujia,sweets,mithai', 8, '9%', 40, '2017-03-08', '10:26:25', 55, 42289197),
(33, '99988737623489772346', '12345678900987654321', 'red bull', 220, 200, 2, 'gives you wings,energy drimk', '250ml', 'redbull,red,bull,energydrink,energy,drink,daily needs,dailyneeds,grocery,general', 8, '8%', 40, '2017-03-16', '15:43:00', 98, 42289197),
(34, '23456789876543456785', '12345678900987654321', 'glucose', 150, 140, 1, '150gm', 'energy drink', 'glucose,glucone d,gluconed,energydrink,energy drink,energy,drink,daily needs,dailyneeds,grocery,general', 7, '8%', 40, '2017-03-02', '03:00:00', 87, 42289197),
(35, '09834923498632468611', '12345678900987654321', 'nescafe coffee', 190, 175, 2, 'with rich coffee', '150gm', 'nescafecoffee,nescafe,coffee,dailyneeds,geneeal,grocery,cafecona', 7, '8%', 40, '2017-03-09', '08:20:23', 0, 42289197),
(36, '56475647564647564477', '12345678900987654321', 'tata tea', 55, 50, 1, 'with nice tea flavor', '250gm', 'tatatea,tata,tea,dailyneeds,daily needs,daily,needs,grocery,general', 8, '6%', 40, '2017-03-02', '20:00:00', 98, 42289197),
(37, '73687564387568743659', '12345678900987654321', 'Dettol handwash', 90, 80, 2, 'kill all the bacteria', '150ml,with fragrance', 'dettolhandwash,dettol,Dettol,handwash.dailyneeds,daily needs,grocery,general', 7, '7%', 40, '2017-03-09', '23:00:00', 93, 42289197),
(38, '45678765565567656767', '12345678900987654321', 'tata salt', 22, 20, 2, 'with iodine', '1kg,baki baad me', 'tatasalt,tata,salt,salts,dailyneeds,daily needs,daily,needs,grocery,general', 7, '7%', 40, '2017-03-15', '00:33:00', 77, 42289197),
(39, '65484873457345834561', '12345678900987654321', 'colgate pest', 90, 80, 2, 'for whiten your teeth', '5% extra,80gm', 'colgate,pest,colgatepest,dailyneeds,daily needs,daily,needs,grocery,general', 7, '8%', 40, '2017-03-01', '10:33:00', 66, 42289197),
(40, '45676567876098909892', '12345678900987654321', 'wildstone deo', 200, 190, 1, 'without gas,with awesome fragrant', '250ml', 'wildstonedeo,deo,deodrant,wildstone,perfume,dailyneeds,daily needs,grocery,general', 8, '4%', 40, '2017-03-01', '04:00:29', 100, 4),
(41, '67480293642973249398', '12345678900987654321', 'axe deo', 200, 190, 1, 'without gas', '250 ml', 'axedeo,deo,deodrant,axe,dailyneeds,daily needs,grocery,general', 8, '5%', 40, '2017-03-14', '09:24:00', 87, 42289197),
(42, '42734681924980122746', '12345678901234567890', 'navratan talc', 78, 70, 1, 'with dual coolness', '200gm', 'navratantalc,talc,powder,navratan,dailyneeds,daily needs,grocery,general', 9, '4%', 40, '2017-03-09', '11:00:00', 78, 42289197),
(43, '45793209384932495654', '12345609871234567890', 'half sleeve shirt', 500, 400, 1, 'half sleeve', 'chech', 'halfsleeveshirt,shirts,shirt,half sleeve,half sleeve shirt,fashion,cloths,cloth,menswear', 7, '5%', 40, '2017-03-01', '09:46:00', 200, 42289197),
(44, '02390239489385893034', '12345609871234567890', 'towel', 300, 200, 1, 'soft,light weight', 'check.plane', 'fashion,towel,cloths,cloth', 7, '6%', 40, '2017-03-08', '13:34:00', 68, 42289197),
(45, '98232484734910983285', '12345609871234567890', 'bed sheet', 500, 300, 1, 'full bed,long', 'soft,with multi color', 'bedsheet,bed sheet,bedsheets,bed,coths', 7, '6%', 40, '2017-03-07', '03:00:48', 67, 42289197),
(46, '43759237983455835233', '12345609871234567890', 'hoodie shirts', 1000, 700, 2, 'very comfortable,light weight', 'hoodie,fullsleeve', 'hoodie shirts,hoodieshirts,hoodie shirt,shirts,shirt,cloths,cloth,fashion', 8, '30%', 40, '2017-03-08', '05:00:46', 189, 42289197),
(47, '83479827485563767474', '54321678901234567890', 'sherwani ', 1800, 1400, 1, 'designer,light weight,very comfortable', 'fullsleeve,stand collar', 'sherwani,cloths,fashion,menswear,men\'s wear', 9, '19%', 40, '2017-03-09', '08:00:38', 75, 42289197),
(48, '67584957654857273649', '54321678901234567890', 'sport tshirts', 400, 250, 1, 'light weight,comfortable,absorbs sweat', 'round neck', 'sportwear,sport sear,sports wear,tshirts,tshirts,men\'s wear,men wear,mens wear,women wear,fashion,cloths,cloth', 9, '25%', 40, '2017-03-02', '00:38:00', 78, 42289197),
(49, '48593485938474378431', '54321678901234567890', 'formal shirt', 500, 400, 1, 'full sleeve,formal', 'black,full sleeve', 'formalshirts,formal shirts,formal shirt,formal,shirts,shirt,cloths,cloth,fashion', 8, '18%', 40, '2017-03-09', '00:17:28', 57, 42289197),
(50, '09809876789365238763', '54321678901234567890', 'formal pent ', 1000, 800, 1, 'formal wearing,comfortable', 'full pent,black', 'formalpent,formal pents,formal pent,pent,formal,men\'s wearing,men wearing,mens wearing,women wearing,womenswearing,cloths,cloth,fashion', 9, '15%', 40, '2017-03-22', '17:43:00', 86, 42289197),
(51, '65748934857648374483', '54321678901234567890', 'inner wear', 400, 250, 1, 'comfortable,light weight', 'blue,printed', 'innerwear,inner wear,inner,menswear,men\'s wear,men wear,womenswear,wpmen wear,women\'s wear,cloths,cloth,fashion', 8, '29%', 40, '2017-03-09', '15:00:40', 199, 42289197),
(52, '56748576859285788373', '12348355555666667890', 'imarti', 300, 250, 1, 'delicious,fresh', '1 kg,with delicious sweetness', 'imarti,sweeets,sweet,mithai,food', 8, '9%', 40, '2017-03-18', '00:16:16', 78, 42289197),
(53, '65748567587675892349', '12348355555666667890', 'petha ', 400, 300, 1, 'delicious,fresh,sweeter', '1 kg,delicious', 'petha,sweets,sweet,food,mithai,agra petha', 8, '9%', 40, '2017-03-08', '18:44:00', 68, 42289197),
(54, '56475647510984097477', '12348355555666667890', 'jalebi', 300, 280, 1, 'sweeter in taste,delicious,fresh', 'delicious,1 kg', 'jalebi,sweet,sweets,mithai,food', 8, '9%', 40, '2017-03-02', '13:21:00', 89, 42289197),
(55, '35874980234578293355', '12345098767890054321', 'chips', 60, 55, 3, 'tasty,crispy', 'lays chips,fresh chips', 'chips,bingo chips,lays chips,chip,food,daily needs,dailyneeds,daily need,grocery,general', 9, '5%', 40, '2017-03-11', '18:00:46', 65, 42289197),
(56, '28358923794756456456', '12345098767890054321', 'chocolate', 100, 90, 2, 'chocolaty,sweeter', 'dairy milk choco', 'chocolate,choco,chocolates,dairylmilk,dairy milk,5 star,kitkat,dessert,sweets,sweet,food,grocery,general items,daily needs, daily need', 8, '5%', 40, '2017-03-22', '08:13:00', 0, 42289197),
(57, '34895792833924034948', '12345098767890054321', 'flour', 150, 140, 1, 'fresh flour', 'fresh flour,whiter,4 kg', 'flour,patanjali flour,food,aata,daily need,daily needs,food items,kitchen item,grocery,general ', 9, '9%', 40, '2017-03-07', '13:22:00', 77, 42289197),
(58, '25728949784754857488', '12345098767890054321', 'bread', 100, 90, 4, 'fresh bread,brown bread', 'brown bread,4 packet', 'bread,food,food item,food items,bred,daily needs,daily need,dailyneeds,grocery,general', 8, '9%', 40, '2017-03-07', '12:18:00', 76, 42289197),
(59, '43857823475293847380', '12345789534444447890', 'surf', 100, 90, 1, 'clean your cloths', '1.5 kg,new look', 'surf,surf excel,washing powder,washing,washingpowder,cloths,daily needs,daily need,grocery,general', 8, '10%', 40, '2017-03-08', '07:35:23', 89, 42289197),
(60, '67654567656765788899', '12345789534444447890', 'turmeric', 50, 45, 1, 'turmeric powder', 'food items,10% extra', 'turmeric,food item,food items,daily needs,daily need,grocery,general', 8, '5%', 40, '2017-03-01', '08:40:18', 90, 42289197),
(61, '65347829353823843939', '12345789534444447890', 'sauce', 105, 90, 1, 'fresh tomato sauce', '1 kg ,red sauce', 'sauce,sauces,tomato sauce,tomato sauces,food,food items,food item,daily needs,daily need,grocery,general', 8, '9%', 40, '2017-03-14', '13:16:29', 78, 42289197),
(62, '48572984209309849357', '12345789534444447890', 'gem', 100, 90, 1, 'delicious,fresh', 'red gem,1 kg', 'gem,kissan gem,food,food items,food item,grocery,general', 8, '10%', 40, '2017-03-08', '15:39:40', 90, 42289197);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_table`
--
ALTER TABLE `product_table`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `sno` (`pid`),
  ADD KEY `sid` (`sid`);
ALTER TABLE `product_table` ADD FULLTEXT KEY `pname` (`pname`,`ptag`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_table`
--
ALTER TABLE `product_table`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT COMMENT 'serial number', AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
