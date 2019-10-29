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
-- Table structure for table `image_gallery`
--

CREATE TABLE `image_gallery` (
  `sno` int(11) NOT NULL,
  `id` varchar(25) NOT NULL COMMENT 'shop id or product id',
  `photo_url` varchar(500) NOT NULL COMMENT 'image paths',
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image_gallery`
--

INSERT INTO `image_gallery` (`sno`, `id`, `photo_url`, `type`) VALUES
(11, '12345678977789066616', 'https://thumb9.shutterstock.com/display_pic_with_logo/588721/115588432/stock-photo-composition-with-variety-of-grocery-products-including-vegetables-fruits-meat-dairy-and-wine-115588432.jpg', 'product'),
(12, '67890543216661678977', 'https://thumb9.shutterstock.com/display_pic_with_logo/1255993/395654092/stock-vector-plastic-shopping-basket-full-of-grocery-products-shopping-groceries-food-and-drink-vector-flat-395654092.jpg', 'product'),
(13, '78900987654321345678', 'http://1.bp.blogspot.com/-mlChAN8cr4Q/VGHA95mMiqI/AAAAAAAAABg/GOxaK2ONM84/s1600/Bag_of_Groceries.jpg', 'product'),
(14, '78954321606789054321', 'http://ghartakkirana.in/image/cache/data/Kissan%20Pineapple%20Jam-600x600.jpg', 'product'),
(15, '45634658436598340634', 'https://pimg.tradeindia.com/03204841/b/1/Cream-Corn.jpg', 'product'),
(16, '90876541236789054321', 'http://www.coghlans.com/images/products/products-camp-kitchen-thumb.jpg', 'product'),
(17, '11223344556789067890', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/a/m/amul_kool_cafe_200_ml_can_1.jpg', 'product'),
(18, '00998877665432112345', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/s/u/surabhi_paste_pani_puri_100gm.jpg', 'product'),
(19, '12340998877665589076', 'https://www.gatorade.com/_assets-sports-fuel/images/g_home_hero-sportsfuel-product.png', 'product'),
(20, '78901234656789054321', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/z/o/zoopy_noodles_mirch_msl_75gm.jpg', 'product'),
(21, '11223344556677889900', 'http://st3.depositphotos.com/5971520/13120/v/1600/depositphotos_131202370-stock-illustration-set-of-grocery-products-the.jpg', 'product'),
(22, '00119922883377446655', 'http://www.theloop.ca/wp-content/uploads/2015/09/1._Prema_Chai.jpg', 'product'),
(23, '12332145665478987000', 'http://www.itoyokado.co.jp/special/global/images/img_food_ranking03.jpg', 'product'),
(24, '10299201384756478393', 'http://www.itoyokado.co.jp/special/global/images/img_food_pre1.jpg', 'product'),
(25, '12233344445555509867', 'https://cdn-geo.dayre.me/08186244-b8ab-4e73-9188-bd1c43fb3c68-image.jpg', 'product'),
(26, '89076523418907652341', 'https://www.rentmantra.com/townsafari/wp-content/uploads/2016/10/870899ae-878c-11e5-9788-42b4b9d38c49.jpg', 'product'),
(27, '43254213409978665733', 'http://www.buyflower.in/blog/images/sweets-buyflower.jpg', 'product'),
(28, '89076543125432167822', 'http://ksmartstatic.sify.com/cmf-1.0.0/appflow/bawarchi.com/Image/okoncvaecjegd.jpg', 'product'),
(29, '54321234567890009811', 'http://i.ndtvimg.com/i/2015-07/sweet-625_625x350_61438262263.jpg', 'product'),
(30, '12345678900987654321', 'http://www.itoyokado.co.jp/special/global/images/img_food_ranking03.jpg', 'shop'),
(31, '12345678901234567890', 'https://endpoint914114.azureedge.net/globalassets/windsor-2016/shopping/big-shop/uvid-49b711/big-shop.jpg', 'shop'),
(32, '12345609871234567890', 'http://c8.alamy.com/comp/DGJ6HH/typical-english-village-grocery-shop-in-the-pretty-holiday-resort-DGJ6HH.jpg', 'shop'),
(33, '54321678901234567890', 'http://l7.alamy.com/zooms/22ec77cd17b9477da01f34e34c2bbcb3/off-license-and-grocery-shop-london-england-uk-cf42tb.jpg', 'shop'),
(34, '09876543216789054321', 'http://gb.fotolibra.com/images/larger-thumbnails/957208-alford-greengrocer-shop.jpeg', 'shop'),
(35, '78977123456789066616', 'http://china.cantonfairtrading.com/uploaddir/20110927/product_1_131361_20110927165146.jpg', 'product'),
(36, '12345098767890054321', 'http://l7.alamy.com/zooms/f80024d98c004a68bbdb42557e2691e2/iceland-grocery-store-front-in-the-uk-ekxbtx.jpg', 'shop'),
(37, '12345789534444447890', 'https://farm2.staticflickr.com/1012/1367530048_fa3fd3c4f4_o.jpg', 'shop'),
(38, '12348355555666667890', 'http://4.bp.blogspot.com/-U7CQ8bfUEI0/Uho2GaJxJeI/AAAAAAAACyQ/dMHPBQgDr38/s1600/998355_450082951755118_1725687790_n.jpg', 'shop'),
(39, '99988737623489772346', 'http://www.barkleyproductphotography.com/wp-content/uploads/galleries/post-49/full/Grocery-Food_008.jpg', 'product'),
(40, '23456789876543456785', 'https://www.skuvantage.com.au/wp-content/uploads/2015/01/McCormick_Aeroplane_Jelly_Grocery_Product_Photography-1.jpg', 'product'),
(41, '9834923498632468611', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/6/1/611.png', 'product'),
(42, '56475647564647564477', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09cc4712188b/6/1/611.png', 'product'),
(43, '73687564387568743659', 'http://nayazamana.in/image/cache/catalog/SaltSugarSpices/tataSalt-600x711.png', 'product'),
(44, '45678765565567656767', 'http://nayazamana.in/image/cache/catalog/SaltSugarSpices/tataSalt-600x711.png', 'product'),
(45, '65484873457345834561', 'http://jaipur.gozopping.com/u/products/Britannia-50-50-Maska-Chaska.png', 'product'),
(46, '45676567876098909892', 'http://jaipur.gozopping.com/u/products/Britannia-50-50-Maska-Chaska.png', 'product'),
(47, '45676567876098909892', 'http://jaipur.gozopping.com/u/products/Britannia-50-50-Maska-Chaska.png', 'product'),
(48, '67480293642973249398', 'https://www.gianteagle.com/ProductImages/OWN_BRANDS/GIANT_EAGLE/product_groceryAisles_crispBerryCrunch.jpg', 'product'),
(49, '67480293642973249398', 'https://www.gianteagle.com/ProductImages/OWN_BRANDS/GIANT_EAGLE/product_groceryAisles_crispBerryCrunch.jpg', 'product'),
(50, '67480293642973249398', 'https://www.gianteagle.com/ProductImages/OWN_BRANDS/GIANT_EAGLE/product_groceryAisles_crispBerryCrunch.jpg', 'product'),
(51, '42734681924980122746', 'http://lghttp.52041.nexcesscdn.net/802C5CB/magento/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/p/r/progress-grocery-olive-salad.jpg', 'product'),
(52, '45793209384932495654', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/t/a/tata_tea_premium_dust_3.jpg', 'product'),
(53, '02390239489385893034', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/t/a/tata_tea_premium_dust_3.jpg', 'product'),
(54, '2390239489385893034', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/t/a/tata_tea_premium_dust_3.jpg', 'product'),
(55, '98232484734910983285', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/m/c/mccain_garlic_potato_pops_1.5_kg.jpg', 'product'),
(56, '43759237983455835233', 'http://fizzkart.com/uploads/hul/orginal/Annapurnacrystalsalt1kg.jpg', 'product'),
(57, '83479827485563767474', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/t/a/tang_mango_125_gm_300x300.jpg', 'product'),
(58, '67584957654857273649', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/l/i/lifebuoy_handwash_care_185ml.jpg', 'product'),
(59, '48593485938474378431', 'http://www.chennaionlinegrocery.com/media/catalog/product/cache/1/small_image/295x/040ec09b1e35df139433887a97daa66f/D/O/DOMEX-ORIGINAL-TOILET-CLEANER-11711_8.jpg', 'product'),
(60, '09809876789365238763', 'http://aurkirana.com/media/catalog/product/cache/1/image/1200x1200/4873d441ccbc833e238ff09c4712188b/h/a/haldiram_badam_halwa_200_gm_1.jpg', 'product'),
(61, '65748934857648374483', 'http://cdn1.viewpoints.com/pro-product-photos/000/422/296/300/arm-and-hammer0baking-soda.jpg', 'product'),
(62, '56748576859285788373', 'http://cdn1.viewpoints.com/pro-product-photos/000/422/296/300/arm-and-hammer0baking-soda.jpg', 'product'),
(63, '65748567587675892349', 'https://www.gianteagle.com/ProductImages/OWN_BRANDS/GIANT_EAGLE/product_groceryAisles_crispBerryCrunch.jpg', 'product'),
(64, '56475647510984097477', 'http://www.st-hubert.com/userfiles/image/epicerie/460x460_sommaire-categorie/460x460_sauces_EN.png', 'product'),
(65, '35874980234578293355', 'http://www.zapmart.com/products/images/14679857410.jpg', 'product'),
(66, '28358923794756456456', 'http://lenpenzo.com/blog/wp-content/uploads/2012/01/misleading1.jpg', 'product'),
(67, '34895792833924034948', 'http://www.kapruka.com/shops/specialGifts/productImages/grocery0002.jpg', 'product'),
(68, '25728949784754857488', 'https://www.jomange.com/upload/product/middel/item_1671c43b73146386e6cf02bd42a3aa82VAGHBAKRI.jpg', 'product'),
(69, '43857823475293847380', 'https://www.timhortons.com/ca/images/product_bag_darkroast_medium.jpg', 'product'),
(70, '67654567656765788899', 'http://www.appukart.in/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/9/_/9_8.jpg', 'product'),
(71, '65347829353823843939', 'http://onlinegroceryproducts.in/admin/product/a8af3762d678f4fcb731e0bd0ba8d0dc.jpg', 'product'),
(72, '48572984209309849357', 'http://rationbag.in/media/catalog/product/cache/1/small_image/276x385/9df78eab33525d08d6e5fb8d27136e95/a/a/aashirwad-atta-multigrains2_2.png', 'product');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `image_gallery`
--
ALTER TABLE `image_gallery`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `image_gallery`
--
ALTER TABLE `image_gallery`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
