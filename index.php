<?php
	session_start();

	require_once('fbvendor/autoload.php');
	require_once ('gvendor/autoload.php');
	require_once('dbconfig.php');
	require_once('webuser.php');
	require_once('myvendor/cartmanager.php');
	require_once('myvendor/wishlistmanager.php');
	require_once('myvendor/productmanager.php');
	require_once('myvendor/home-product-loader.php');

	$user=null;

	$database=new Database();
	$db=$database->getDbConnection();
	//if user is logged in before
	if(isset($_COOKIE["type"])){

		$user=new WebUser($_COOKIE['id'],$_COOKIE['email'],$_COOKIE['type']);

		if($_COOKIE['type']==WebUser::TYPE_FB){
			$user->setupFbSession();
		}
		else if($_COOKIE['type']==WebUser::TYPE_GOOGLE){
			$user->setupGoogleSession();
		}
		else if($_COOKIE['type']==WebUser::TYPE_SELF){
			$user->setSelfSession();
		}
	}
	else{
		
		$user=new WebUser(substr(session_id(),0,20),WebUser::TYPE_GUEST);
		$_SESSION['id']=$user->getUID();
		$_SESSION['name']='Sign In';
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>uShopper</title>
	<link rel="stylesheet" href="css/pace.css"/>
	<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
  	<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
	<link rel="stylesheet" type="text/css" href="aos/dist/aos.css" />
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="css/input_normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/input_demo.css" />
	<link rel="stylesheet" type="text/css" href="css/input_set2.css" />
	<link rel="stylesheet" type="text/css" href="css/animate.css">
	<link rel="stylesheet" type="text/css" href="css/css-stars.css">
	<link rel="stylesheet" type="text/css" href="css/header.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.css"/>
	<link rel="stylesheet" type="text/css" href="css/footer.css"/>

	<!-- Start WOWSlider.com HEAD section --> <!-- add to the <head> of your page -->
	<link rel="stylesheet" type="text/css" href="engine0/style.css" />
	<script type="text/javascript" src="engine0/jquery.js"></script>
	<!-- End WOWSlider.com HEAD section -->
	
	<script type="text/javascript" src="aos/dist/aos.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>
	<script type="text/javascript" src="js/pace.min.js"></script>
	<script type="text/javascript" src="js/notify.js"></script>
	<script type="text/javascript" src="js/noty/packaged/jquery.noty.packaged.min.js"></script>
	<script type="text/javascript" src="js/jquery.barrating.min.js"></script>
</head>
<body>
	<script type="text/javascript">
		
		var uShopper={
			isLogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			notify:null,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>'
		};
	</script>
	<header>
		<div class="subHead">
			<ul>
				<li>Download App</li>
				<li>uSellers</li>
				<li>Customer Care</li>
			</ul>
		</div>
		<div class="subHead">
			<ul>
				<li>
					<span class="menu-stick">
						<div></div>
						<div></div>
						<div></div>
					</span>				
				</li>
				<li>uShopper</li>
				<li>logo</li>
				<li>
					<form method="get" action="search.php">
						<input type="text" name="searchfor" placeholder="Search for your product " required />
						<button type="submit"><img src="images/search.png"></button>
						<select name="sby" required>
							<option value="product" selected >By Product</option>
							<option value="shop">By Shop</option>
						</select>
						<input type="hidden" name="sort" value="1"/>
					</form>
				</li>
				<li>
					<input type="button" name="cartButton" class="check-it" data-href="cart.php" value="Cart"/>
					<span id="cartCounter"><?php if($user->getType()!==WebUser::TYPE_GUEST) echo CartManager::getCartCount($db,$_SESSION['id']); else echo "0"; ?></span>
				</li>
				<li>
					<div class="user-drop">
						<span>
							<?php

								if($user->getType()==WebUser::TYPE_FB){
									echo "<span>".$user->getFirstName()."</span>";
									echo "<img src='".$user->detail()['upic']."' width='50px' height='50px' alt='user'>";
	 							}
	 							else if($user->getType()==WebUser::TYPE_GOOGLE){
	 								echo "<span>".$user->getFirstName()."</span>";
		 							echo "<img src='".$user->detail()['upic']."' width='50px' height='50px' alt='user'>";
	 							}
	 							else if($user->getType()==WebUser::TYPE_SELF){
	 								echo "<span>".$user->getFirstName()."</span>";
		 							echo "<img src='".$user->detail()['upic']."' width='50px' height='50px' alt='user'>";
	 							}
	 							else if($user->getType()==WebUser::TYPE_GUEST){
	 								echo "<span>".$_SESSION['name']."</span>";
	 								echo "<img src='images/guest-green.png' width='50px' height='50px' alt='user'>";		
	 							}
							?>
							
						</span>
						<div class="login-content">
							<a href="account/" class="check-it">Your Account</a>
							<a href="account/myorders/" class="check-it">Your Orders</a>
							<a href="account/mywishlist/" class="check-it">Wishlist</a>
							<div>
								<?php
									if($user->getType()!=WebUser::TYPE_GUEST){
										echo "<a href='logout.php' id='logout'>Logout</a>";
									}
									else{
										echo "<button type='button' class='registerbut'>Register</button>
								<button type='button' class='loginbut'>LogIn</button>";
									}
								?>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</header>
	<nav class="menu-bar">				
		<ul>
			<li><a href="search.php?searchfor=grocery&sby=product&sort=1">Grocery</a></li>
			<li><a href="search.php?searchfor=elcetronic&sby=product&sort=1">Electronics</a></li>
			<li><a href="search.php?searchfor=men+cloth+fashion&sby=product&sort=1">Mens Fashion</a></li>
			<li><a href="search.php?searchfor=women&sby=product&sort=1"">Womens Fashion</a></li>
			<li><a href="search.php?searchfor=shoe+footwear&sby=product&sort=1"">Footwear</a></li>
			<li><a href="search.php?searchfor=sweets+snacks+bakery&sby=product&sort=1"">Sweets and Snacks</a></li>
		</ul>
	</nav>
	<section>

		<!-- Start WOWSlider.com BODY section --> <!-- add to the <body> of your page -->
		<div id="wowslider-container0">
			<div class="ws_images">
				<ul>
					<li><img src="data0/images/img_a.jpg" alt="img_a" title="img_a" id="wows0_0"/></li>
				</ul>
			</div>
			<div class="ws_bullets">
				<div>
					<a href="data0/images/img_a.jpg" title="img_a"><span><img src="data0/tooltips/img_a.jpg" alt="img_a"/>1</span></a>
					<a href="data0/images/img_b.jpg" title="img_b"><span>2</span></a>
					<a href="data0/images/img_c.jpg" title="img_c"><span>3</span></a>
					<a href="data0/images/img_d.png" title="img_d"><span>4</span></a>
					<a href="data0/images/img_e.jpg" title="img_e"><span>5</span></a>
				</div>
			</div>
			<div class="ws_script" style="position:absolute;left:-99%"></div>
			<div class="ws_shadow"></div>
		</div>	
		<script type="text/javascript" src="engine0/wowslider.js"></script>
		<script type="text/javascript" src="engine0/script.js"></script>
		<!-- End WOWSlider.com BODY section -->

		<?php

			$data=json_decode(HomeProductLoader::getRecentAdd($db),true);

			if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">Latest Adds</a>
					<button type="button" class="view-all" data-task="latest" >View All</button>
				</div>
				<div class="product_slider">

					<?php
						foreach ($data['list'] as $key => $value) {	
						
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['name'] ?>">
							<?php
								echo WishlistManager::checkInWishlist($db,$value['id'],$user->getUID(),"product");
							?>
						</div>
						<div class="item_detail">
							<a href=<?php echo '"product.php?id='.$value['id'].'"' ?> ><?php echo $value['name']; ?></a>
							<span>Rs <?php echo $value['actualprice']; ?><del><small>Rs <?php echo $value['mrp']; ?><small></del></span>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			/*if($user->getType()!==WebUser::TYPE_GUEST)
				$data=json_decode(HomeProductLoader::getMostRated($db,$_SESSION['id']),true);
			else*/
				$data=json_decode(HomeProductLoader::getMostRated($db),true);

			if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">Most Rated</a>
					<button type="button" class="view-all" data-task="prated">View All</button>
				</div>
				<div class="product_slider">

					<?php
						foreach ($data['list'] as $key => $value) {	
						
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src=<?php echo '"'.$value['photo'][0].'"' ?> alt=<?php echo '"'.$value['name'].'"' ?> >

							<?php
								echo WishlistManager::checkInWishlist($db,$value['id'],$user->getUID(),"product");
							?>
							
						</div>
						<div class="item_detail most-rated">
							<a href=<?php echo '"product.php?id='.$value['id'].'"' ?> ><?php echo $value['name']; ?></a>
							<span>
								<select class="static-rate">
									<?php 
										$rating=round( ((5*$value['r_five'])+(4*$value['r_four'])+(3*$value['r_three'])+(2*$value['r_two'])+(1*$value['r_one']))/($value['r_five']+$value['r_four']+$value['r_three']+$value['r_two']+$value['r_one']) );
									?>
									<option value="1" <?php if($rating==1)echo 'selected' ?> >1</option>
									<option value="2" <?php if($rating==2)echo 'selected' ?> >2</option>
									<option value="3" <?php if($rating==3)echo 'selected' ?> >3</option>
									<option value="4" <?php if($rating==4)echo 'selected' ?> >4</option>
									<option value="5" <?php if($rating>=5)echo 'selected' ?> >5</option>
								</select>
							</span>
							<span>Rs <?php echo $value['actualprice']; ?><del>Rs <?php echo $value['mrp']; ?></del></span>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			$data=json_decode(HomeProductLoader::getMostRatedShop($db),true);

			if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">Top Rated Shops</a>
					<button type="button" class="view-all" data-task="srated">View All</button>
				</div>
				<div class="product_slider">

					<?php
						foreach ($data['list'] as $key => $value) {	
						
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['name'] ?>">
							<?php
								echo WishlistManager::checkInWishlist($db,$value['id'],$user->getUID(),"shop");
							?>
						</div>
						<div class="item_detail">
							<a href='<?php echo "shopviewer.php?id=".$value["id"] ?>' ><?php echo $value['name']; ?></a>
							<span>
								<select class="static-rate">
									<option value="1" <?php if($value['rate']===1)echo 'selected' ?> >1</option>
									<option value="2" <?php if($value['rate']===2)echo 'selected' ?> >2</option>
									<option value="3" <?php if($value['rate']===3)echo 'selected' ?> >3</option>
									<option value="4" <?php if($value['rate']===4)echo 'selected' ?> >4</option>
									<option value="5" <?php if($value['rate']>=5)echo 'selected' ?> >5</option>
								</select>
							</span>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			$data=json_decode(HomeProductLoader::getTrendingFashion($db),true);

			if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">Fashion In Trend</a>
					<button type="button" class="view-all" data-task="ftrend">View All</button>
				</div>
				<div class="product_slider">

					<?php
						foreach ($data['list'] as $key => $value) {	
						
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['name'] ?>">
							<?php
								echo WishlistManager::checkInWishlist($db,$value['id'],$user->getUID(),"product");
							?>
						</div>
						<div class="item_detail">
							<a href=<?php echo '"product.php?id='.$value['id'].'"' ?> ><?php echo $value['name']; ?></a>
							<span>Rs <?php echo $value['actualprice']; ?><del>Rs <?php echo $value['mrp']; ?></del></span>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			$data=json_decode(HomeProductLoader::getDailyNeeds($db),true);

			if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">DailyNeeds</a>
					<button type="button" class="view-all" data-task="daily">View All</button>
				</div>
				<div class="product_slider">

					<?php
						foreach ($data['list'] as $key => $value) {	
						
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['name'] ?>">
							<?php
								echo WishlistManager::checkInWishlist($db,$value['id'],$user->getUID(),"product");
							?>
						</div>
						<div class="item_detail">
							<a href=<?php echo '"product.php?id='.$value['id'].'"' ?> ><?php echo $value['name']; ?></a>
							<span>Rs <?php echo $value['actualprice']; ?><del>Rs <?php echo $value['mrp']; ?></del></span>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			$data=json_decode(HomeProductLoader::getDiscounts($db),true);

			if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">Discounts you want</a>
					<button type="button" class="view-all" data-task="discounts">View All</button>
				</div>
				<div class="product_slider">

					<?php
						foreach ($data['list'] as $key => $value) {	
						
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['name'] ?>">
							<?php
								echo WishlistManager::checkInWishlist($db,$value['id'],$user->getUID(),"product");
							?>
						</div>
						<div class="item_detail">
							<a href=<?php echo '"product.php?id='.$value['id'].'"' ?> ><?php echo $value['name']; ?></a>
							<span style="color: #f00" >
							<?php 
								echo round((($value['mrp']-$value['actualprice'])/$value['mrp'])*100); 
							?>% off</span>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			$data=json_decode(HomeProductLoader::getFashionWear($db,'women'),true);

			if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">Women Fashion Wear</a>
					<button type="button" class="view-all" data-task="fwomen">View All</button>
				</div>
				<div class="product_slider">

					<?php
						foreach ($data['list'] as $key => $value) {	
						
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['name'] ?>">
							<?php
								echo WishlistManager::checkInWishlist($db,$value['id'],$user->getUID(),"product");
							?>
						</div>
						<div class="item_detail">
							<a href=<?php echo '"product.php?id='.$value['id'].'"' ?> ><?php echo $value['name']; ?></a>
							<span>Rs <?php echo $value['actualprice']; ?><del>Rs <?php echo $value['mrp']; ?></del></span>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			$data=json_decode(HomeProductLoader::getFashionWear($db,'men'),true);

			if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">Men Fashion Wear</a>
					<button type="button" class="view-all" data-task="fmen">View All</button>
				</div>
				<div class="product_slider">

					<?php
						foreach ($data['list'] as $key => $value) {	
						
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['name'] ?>">
							<?php
								echo WishlistManager::checkInWishlist($db,$value['id'],$user->getUID(),"product");
							?>
						</div>
						<div class="item_detail">
							<a href=<?php echo '"product.php?id='.$value['id'].'"' ?> ><?php echo $value['name']; ?></a>
							<span>Rs <?php echo $value['actualprice']; ?><del>Rs <?php echo $value['mrp']; ?></del></span>
						</div>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<?php
			if($user->getType()!=WebUser::TYPE_GUEST){
				$data=json_decode(ProductManager::getRecentProducts($db,$_SESSION['id']),true);
				
				if($data["success"]){
		?>
		
		<div class="product_display">
			<div class="product_category" data-aos="fade-up" data-aos-once="true">
				<div>
					<a href="#" title="category">Recently Viewed</a>
					<button type="button" class="view-all" data-task="recent" data-allowed="true">View All</button>
				</div>
				<div class="product_slider">

					<?php
							foreach ($data['list'] as $key => $value) {	
					?>
					<div class="slider_item"  >
						<div class="item_view">
							<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['pname'] ?>">
							<?php
								echo WishlistManager::checkInWishlist($db,$value['pid'],$user->getUID(),"product");
							?>
						</div>
						<div class="item_detail">
							<a href=<?php echo '"product.php?id='.$value['pid'].'"' ?> ><?php echo $value['pname']; ?></a>
							<span>Rs <?php echo $value['pactualprice']; ?><del>Rs <?php echo $value['pmrp']; ?></del></span>
						</div>
					</div>
					<?php
							}
					?>
				</div>
			</div>
		</div>
		<?php
				}
			}
		?>

	</section>
	
	<footer>
			<div>
				<div>
					<h3>Connect with us</h3>
					<ul>
						<li><a href="#"><img src="images/facebook.png" alt="Facebook"/></a></li>
						<li><a href="#"><img src="images/twitter.png" alt="Twitter"/></a></li>
						<li><a href="#"><img src="images/instagram.png" alt="Instagram"/></a></li>
					</ul>
				</div>
				<div>
					<h3>Get to know us</h3>
					<ul>
						<li><a href="#">About Us</a></li>
						<li><a href="#">Cookies and Privacy Policy</a></li>
						<li><a href="#">Terms of Use</a></li>
						<li><a href="#">FAQ</a></li>
						<li><a href="#">Help</a></li>
					</ul>
				</div>
				<div>
					<h3>Rely on uShopper</h3>
					<h4>Absolutely Guaranteed</h4>
					<h4>Every time. Any reason.<br> Or we'll make it right.</h4>
				</div>
			</div>
			<div>
				<span>PAYMENT METHOD</span>
				<ul>
					<li><img src="images/visa.png" alt="visa"/></li>
					<li><img src="images/master.png" alt="master"/></li>
					<li><img src="images/maestro.png" alt="maestro"/></li>
					<li><img src="images/rupay.jpg" alt="rupay"/></li>
					<li><img src="images/netbank.png" alt="online"/></li>
				</ul>
			</div>
			<div>
				<ul>
					<li><img src="" alt="logo"/></li>
					<li>The Market you know</li>
				</ul>
			</div>
			<div>
				<h4>Copyright 2017. All right reserved.</h4>
			</div>
	</footer>
	<script type="text/javascript" src="slick/slick.min.js"></script>
	<script type="text/javascript" src="js/manager.jquery.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			

			$('.view-all').on('click',function(){

				window.location.href="viewall.php?view="+this.dataset.task;
			});
		});
	</script>
</body>
</html>