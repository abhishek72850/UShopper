<?php
	session_start();

	require_once('fbvendor/autoload.php');
	require_once('gvendor/autoload.php');
	require_once('dbconfig.php');
	require_once('webuser.php');
	require_once('myvendor/cartmanager.php');
	require_once('myvendor/productmanager.php');
	require_once('myvendor/wishlistmanager.php');
	require_once('myvendor/shopmanager.php');
	require_once('myvendor/variablecheck.php');

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

	if(VariableCheck::isUnKnown($_GET,array("id","searchshopfor","sort","filter","pricemin","pricemax","discount","prate"))){
		if(isset($_GET["id"])){

			$shop=json_decode(ShopManager::getShopDetail($db,$_GET['id']),true);
				
			if(!$shop['success']){

			}
		}
		else{

		}
	}
	else{

	}

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo ucwords($shop['item']['sname']) ?></title>
	<link rel="stylesheet" href="css/pace.css"/>
	<link rel="stylesheet" type="text/css" href="engine0/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
  	<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
  	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="aos/dist/aos.css" />
	<link rel="stylesheet" type="text/css" href="css/animate.css">
	<link rel="stylesheet" type="text/css" href="css/css-stars.css">
	<link rel="stylesheet" type="text/css" href="css/header.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.css"/>
	<link rel="stylesheet" type="text/css" href="css/shopview.css"/>
	<link rel="stylesheet" type="text/css" href="css/footer.css"/>

	<script type="text/javascript" src="engine0/jquery.js"></script>
	<script type="text/javascript" src="engine0/jquery-ui.js"></script>

	<script type="text/javascript" src="js/pace.min.js"></script>
	<script type="text/javascript" src="aos/dist/aos.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>
	<script type="text/javascript" src="js/notify.js"></script>
	<script type="text/javascript" src="js/noty/packaged/jquery.noty.packaged.min.js"></script>
	<script type="text/javascript" src="js/jquery.barrating.min.js"></script>

	<style type="text/css">
		
		.vote-bar{
			display: inline-block;
			width: 0%;
			max-width: 60%;
			height: 10px;
			background-color: gold;
		}
		
	</style>
</head>
<body>
	<script type="text/javascript">
		
		var uShopper={
			search:<?php if(isset($_GET["searchshopfor"])) echo "'".$_GET["searchshopfor"]."'" ;else echo 'null';  ?>,
			isLogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>',
			notify:null,
			flip:<?php if(isset($_GET['searchshopfor']))echo 'true'; else echo 'false'; ?>,
			sort:<?php if(isset($_GET["sort"]))echo "'".$_GET["sort"]."'";else echo "'0'" ?>,
			filter:<?php if(isset($_GET["filter"]))echo "'".$_GET["filter"]."'";else echo "'0'"; ?>,
			sid:<?php echo "'".$shop['item']['sid']."'" ?>,
			whichfilter:"<?php if(isset($_GET["filter"]))echo $_GET["filter"] ;else echo "" ?>",
			priceFilter:<?php if(isset($_GET["pricemin"])&&$_GET["pricemin"]!=="0")echo "true";else echo "false"; ?>,
			pricemin:<?php if(isset($_GET["pricemin"]))echo $_GET["pricemin"];else echo "0"; ?>,
			pricemax:<?php if(isset($_GET["pricemax"]))echo $_GET["pricemax"];else echo "0"; ?>,
			disFilter:<?php if(isset($_GET["discount"])&&$_GET["discount"]!=="0")echo "true";else echo "false"; ?>,
			disValue:<?php if(isset($_GET["discount"]))echo $_GET["discount"];else echo "0"; ?>,
			prateFilter:<?php if(isset($_GET["prate"])&&$_GET["prate"]!=="0")echo "true";else echo "false"; ?>,
			prateValue:<?php if(isset($_GET["prate"]))echo $_GET["prate"];else echo "0"; ?>,
			pendingTag:"",
			ref:null,
			noProduct:false,
			disableAll:<?php if(isset($_GET['searchshopfor'])&&ShopManager::isReserved($_GET['searchshopfor']))echo "true";else echo "false"; ?>,
			offset:0

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
										echo "<a href='logout.php?refrer=".urlencode($_SERVER['REQUEST_URI'])."' id='logout'>Logout</a>";
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
			<li><a href="#">Grocery</a></li>
			<li><a href="#">Electronics</a></li>
			<li><a href="#">Mens Fashion</a></li>
			<li><a href="#">Womens Fashion</a></li>
			<li><a href="#">Footwear</a></li>
			<li><a href="#">Sweets and Snacks</a></li>
		</ul>
	</nav>
	<main>
		<section class="seller-view">
			<section class="shop-detail">
				<div>
					<div class="shop-blur" style="background-image: url('<?php echo $shop["item"]["photo"][0] ?>');" ></div>
					<span class="flip-me"><img src="images/flip.png"></span>
					<img src="<?php echo $shop['item']['photo'][0] ?>" alt="<?php echo $shop['item']['sname'] ?>">
					<h3><?php echo ucwords($shop['item']['sname']) ?></h3>
					<?php
						echo WishlistManager::checkInWishlist($db,$shop['item']['sid'],$user->getUID(),"shop");
					?>
				</div>	
				<div>
					<div>
						<select class="dynamic-rate">
								<?php 
									$rating=round( ((5*$shop['item']['five_rate'])+(4*$shop['item']['four_rate'])+(3*$shop['item']['three_rate'])+(2*$shop['item']['two_rate'])+(1*$shop['item']['one_rate']))/($shop['item']['five_rate']+$shop['item']['four_rate']+$shop['item']['three_rate']+$shop['item']['two_rate']+$shop['item']['one_rate']) );
								?>
							<option value="1" <?php if($rating==1)echo 'selected' ?> >1</option>
							<option value="2" <?php if($rating==2)echo 'selected' ?> >2</option>
							<option value="3" <?php if($rating==3)echo 'selected' ?> >3</option>
							<option value="4" <?php if($rating==4)echo 'selected' ?> >4</option>
							<option value="5" <?php if($rating>=5)echo 'selected' ?> >5</option>
						</select>
					</div>
					<div>
						<p>
							<?php $rate=((($shop['item']['five_rate']*100)/$shop['item']['total_rate'])*60)/100; ?>
							<span  class="vote-bar" data-expandto="<?php echo $rate ?>" ></span>
							<span ><?php echo $shop['item']['five_rate'] ?></span>
						</p>
						<p>
							<?php $rate=((($shop['item']['five_rate']*100)/$shop['item']['total_rate'])*60)/100; ?>
							<span  class="vote-bar" data-expandto="<?php echo $rate ?>" ></span>
							<span data-expandto="<?php echo $rate ?>" ><?php echo $shop['item']['four_rate'] ?></span>
						</p>
						<p>
							<?php $rate=((($shop['item']['five_rate']*100)/$shop['item']['total_rate'])*60)/100; ?>
							<span  class="vote-bar" data-expandto="<?php echo $rate ?>" ></span>
							<span data-expandto="<?php echo $rate ?>" ><?php echo $shop['item']['three_rate'] ?></span>
						</p>
						<p>
							<?php $rate=((($shop['item']['five_rate']*100)/$shop['item']['total_rate'])*60)/100; ?>
							<span  class="vote-bar" data-expandto="<?php echo $rate ?>" ></span>
							<span data-expandto="<?php echo $rate ?>" ><?php echo $shop['item']['two_rate'] ?></span>
						</p>
						<p>
							<?php $rate=((($shop['item']['five_rate']*100)/$shop['item']['total_rate'])*60)/100; ?>
							<span  class="vote-bar" data-expandto="<?php echo $rate ?>" ></span>
							<span data-expandto="<?php echo $rate ?>" ><?php echo $shop['item']['one_rate'] ?></span>
						</p>
					</div>
				</div>
				<div>
					<address>
						Address of the shop
					</address>
					<div>
						<p><?php echo $shop['item']['smobile'] ?></p>
						<p><?php echo $shop['item']['semail'] ?></p>
					</div>
				</div>
			</section>
			<section class="filter-bar">
				<div>
					<span class="flip-me"><img src="images/flip.png"></span>
					Filter by:
				</div>
				<div>
					<h4>Price Range</h4>
					<div class="pslider disable"></div>
					<div>
						<input class="disable" type="text" name="slidermin" value="500">
						<input class="disable" type="text" name="slidermax" value="1500">
						<button class="disable" type="button" id="go">GO</button>
					</div>
				</div>
				<div>
					<h4>Discount %</h4>
					<div class="radioset">
						<input class="disable" type="radio" id="mt90" name="discount" data-value="90"><label for="mt90">more than 90</label>
						<input class="disable" type="radio" id="mt80" name="discount" data-value="80"><label for="mt80">more than 80</label>
						<input class="disable" type="radio" id="mt50" name="discount" data-value="50"><label for="mt50">more than 50</label>
						<input class="disable" type="radio" id="mt30" name="discount" data-value="30"><label for="mt30">more than 30</label>
						<input class="disable" type="radio" id="mt10" name="discount" data-value="10"><label for="mt10">more than 10</label>
					</div>
				</div>
				<div>
					<h4>Product Rating</h4>
					<div class="radioset">
						<input class="disable" type="radio" id="4sup" name="prate" data-value="4"><label for="4sup">4 and Above</label>
						<input class="disable" type="radio" id="3sup" name="prate" data-value="3"><label for="3sup">3 and Above</label>
						<input class="disable" type="radio" id="2sup" name="prate" data-value="2"><label for="2sup">2 and Above</label>
						<input class="disable" type="radio" id="1sup" name="prate" data-value="1"><label for="1sup">1 and Above</label>
						<input class="disable" type="radio" id="0sup" name="prate" data-value="0"><label for="0sup">0 and Above</label>
					</div>
				</div>
			</section>
		</section>
		<section class="seller-shop">
			<section class="search-area">
					<div class="shop-blur" style="background-image: url('<?php echo $shop["item"]["photo"][0] ?>');" ></div>
					<div>
						<div>
							<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
								<input type="hidden" name="id" value="<?php echo $shop['item']['sid'] ?>">
								<input type="text" name="searchshopfor" placeholder="Search <?php echo ucwords($shop['item']['sname']) ?>" >
								<button type="submit"><img src="images/search.png"></button>
							</form>
							<button onclick="window.location='<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $shop['item']['sid'] ?>&searchshopfor=_*_'">View All</button>
						</div>
					</div>

					<?php
						if(isset($_GET['searchshopfor'])){
					?>
					<div>
						<span>Sort by:</span>
						<select class="sort disable">
							<option value="1" <?php if(isset($_GET['sort'])&&$_GET['sort']==="1")echo "selected" ?> >Low to High</option>
							<option value="2" <?php if(isset($_GET['sort'])&&$_GET['sort']==="2")echo "selected" ?> >High to Low</option>
							<option value="4" <?php if(isset($_GET['sort'])&&$_GET['sort']==="4")echo "selected" ?> >Latest</option>
							<option value="3" <?php if(isset($_GET['sort'])&&$_GET['sort']==="3")echo "selected" ?> >Popular</option>
						</select>
					</div>
					<?php
						}
					?>
			</section>
			<?php
				if(!isset($_GET['searchshopfor'])){
			?>
			<section class="shop-home">

				<?php

					$title=array('','Most Selling and Trending','Latest Products','Discounts for you','Your Favourite Shop Products','Your Recent Searches','You may also intrested in');
					$category=array('','ssftp','ssflp','ssfdu','ssfsf','ssfur','ssfii');

					$load=array(json_decode(ShopManager::loadShopHome($db,$shop['item']['sid'],'trending_product'),true),
						json_decode(ShopManager::loadShopHome($db,$shop['item']['sid'],'latest_add'),true),
						json_decode(ShopManager::loadShopHome($db,$shop['item']['sid'],'discounts'),true),
						json_decode(ShopManager::loadShopHome($db,$shop['item']['sid'],'shop_favourite',$user->getUID()),true),
						json_decode(ShopManager::loadShopHome($db,$shop['item']['sid'],'user_recents',$user->getUID()),true),json_decode(ShopManager::getSimilarProducts($db,$user->getUID(),$shop['item']['sid']),true));

					foreach ($load as $data => $result) {
					
						if(current($title)==='Your Recent Searches'||current($title)==='Your may also intrested in'||current($title)==='Your Favourite Shop Products'){
							
							if($user->getType()===WebUser::TYPE_GUEST){
								next($title);
								next($category);
								continue;
							}
						}

						if($result['success']){
				?>
				<div class="product_display">
					<div class="product_category" data-aos="fade-up" data-aos-once="true">
						<div>
							<a href="#" title="category"><?php next($category); echo next($title) ?></a>
							<button type="button" class="view-all" onclick="window.location='shopviewer.php?id=<?php echo $shop["item"]["sid"]."&searchshopfor=".current($category) ?>'"  data-task="recent" data-allowed="true"   >View All</button>
						</div>
						<div class="product_slider">
							<?php
								foreach ($result['list'] as $key => $value) {
									
							?>
							<div class="slider_item"  >
								<div class="item_view">
									<img src="<?php echo $value['photo'][0] ?>" alt="<?php echo $value['pname'] ?>">
									<?php
										echo WishlistManager::checkInWishlist($db,$value['pid'],$user->getUID(),"product");
									?>
								</div>
								<div class="item_detail">
									<a href="product.php?id=<?php echo $value['pid'] ?>" ><?php echo $value['pname'] ?></a>

									<?php
										if(current($title)!=='Discounts for you'){
									?>
										<span>Rs <?php echo $value['pactualprice'] ?><del><?php echo $value['pmrp'] ?></del></span>
									<?php
										}
										else{
									?>
										<span  style="color: #f00" ><?php echo round((($value['pmrp']-$value['pactualprice'])/$value['pmrp'])*100)
							 ?>%</span>
							 	<?php
							 			}
							 	?>
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
			<?php
				}
				else{

					if(!ShopManager::isReserved($_GET['searchshopfor'])){

						//for no sorting or filtering
						if(!isset($_GET["sort"])&&!isset($_GET["filter"])&&!isset($_GET["pricemin"])&&!isset($_GET["pricemax"])&&!isset($_GET["discount"])&&!isset($_GET["prate"])) {

							$data=json_decode(ShopManager::getSearchResult($db,$_GET['searchshopfor'],$shop['item']['sid']),true);
						}
						//for sorting and filtering
						else if((isset($_GET["sort"])&&isset($_GET["filter"]))&&(isset($_GET["pricemin"])&&isset($_GET["pricemax"])&&isset($_GET["discount"])&&isset($_GET["prate"]))) {
							
							if($_GET["sort"]!=='0'&&$_GET["filter"]!=='0'){
								
								if(VariableCheck::checkSortValue($_GET["sort"])&&VariableCheck::checkFilterValue($_GET["filter"])&&VariableCheck::checkPriceValue($_GET["pricemin"],$_GET["pricemax"])&&VariableCheck::checkDiscountValue($_GET["discount"])&&VariableCheck::checkRatingValue($_GET["prate"])){
									
									$data=json_decode(Shopmanager::productSortFilter($db,true,true,$shop['item']['sid'],$_GET["searchshopfor"],array($_GET["sort"],$_GET["filter"]),array($_GET["pricemin"],$_GET["pricemax"],$_GET["discount"],$_GET["prate"])),true);
								}
								else{
									// header("location:index.php");
									echo "filter sort data";
								}
							}
							else{
								echo "filter sort";
								// header("location:index.php");
							}
						}
						//for only sorting
						else if(isset($_GET["sort"])&&$_GET["sort"]!=='0'){

							if(VariableCheck::checkSortValue($_GET["sort"])){
								
								$data=json_decode(Shopmanager::productSortFilter($db,true,false,$shop['item']['sid'],$_GET["searchshopfor"],array($_GET['sort'],"0")),true);
							}
							else{
								echo "sort";
								// header("location:index.php");
							}
						}

						//for only filtering
						else if((isset($_GET["filter"])&&$_GET["filter"]!=='0')&&(isset($_GET["pricemin"])&&isset($_GET["pricemax"])&&isset($_GET["discount"])&&isset($_GET["prate"]))) {

							if(VariableCheck::checkFilterValue($_GET["filter"])&&VariableCheck::checkPriceValue($_GET["pricemin"],$_GET["pricemax"])&&VariableCheck::checkDiscountValue($_GET["discount"])&&VariableCheck::checkRatingValue($_GET["prate"])){

								$data=json_decode(Shopmanager::productSortFilter($db,false,true,$shop['item']['sid'],$_GET["searchshopfor"],array("0",$_GET["filter"]),array($_GET["pricemin"],$_GET["pricemax"],$_GET["discount"],$_GET["prate"])),true);
							}
							else{
								echo "filter";
								// header("location:index.php");
							}
						}
						else{
							echo "data";
							// header("location:index.php");
						}	
					}
					else{

						if($_GET['searchshopfor']==='ssfii'){
							$data=json_decode(ShopManager::getSimilarProducts($db,$user->getUID(),$shop['item']['sid']),true);
						}
						else if($_GET['searchshopfor']==='ssfur'||$_GET['searchshopfor']==='ssfsf'){
							$data=json_decode(ShopManager::loadShopHome($db,$shop['item']['sid'],$_GET['searchshopfor'],$user->getUID()),true);
						}
						else{
							$data=json_decode(ShopManager::loadShopHome($db,$shop['item']['sid'],$_GET['searchshopfor']),true);	
						}
					}
			?>
			<section class="shop-search">
				
				<div class="filter-tags">
					<?php
						if(isset($_GET["filter"])){
							if($_GET["pricemin"]!=="0"&&$_GET["pricemax"]!=="0"){
								echo "<div class='ftag price' data-filter-tag='price'>
									<div>Price Rs.".$_GET["pricemin"]." - ".$_GET["pricemax"]."</div>
									<span class='remove-tag'>x</span>
								</div>";
							}
							if($_GET["discount"]!=="0"){
								echo "<div class='ftag discount' data-filter-tag='discount'>
									<div>Discount More Than ".$_GET["discount"]."%</div>
									<span class='remove-tag'>x</span>
								</div>";
							}
							if ($_GET["prate"]!=="0") {
								echo "<div class='ftag prate' data-filter-tag='prate'>
									<div>Product Rate More Than ".$_GET["prate"]."</div>
									<span class='remove-tag'>x</span>
								</div>";
							}
						}
					?>
				</div>
				<div class="search-result-container">
					<?php 
						if($data['success']){
							foreach ($data['list'] as $key => $value) {
							
					?>
					<div class="item" >
						<img src= "<?php echo $value['photo'][0] ?>" alt="<?php echo $value['pname'] ?>" />
						<?php
							echo WishlistManager::checkInWishlist($db,$value['pid'],$user->getUID(),"product");
						?>
						<div class="item-detail">
							<a href="product.php?id=<?php echo $value['pid'] ?>"><?php echo $value['pname'] ?></a>
							<p>
								<select class="static-rate">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</p>
							<p><?php echo $value['pactualprice'] ?><del><?php echo $value['pmrp'] ?></del></p>
						</div>
						<div class="item-get">
							<button class="manage" type="button" data-task="cart_add"  data-task-command="add" data-item-id="<?php echo $value['pid'] ?>" data-item-type="product">ADD TO CART</button>
							<button onclick="window.location='placeorder.php?buy=product&quantity=1&id=<?php echo $value["pid"] ?>'" type="button">BUY</button>
						</div>
					</div>
					<?php
							}
							echo "<script>uShopper.offset=20;</script>";
						}
						else{
							echo "<script>uShopper.noProduct=true;</script>";
						}
						
					?>

					<?php 
						if($data['success']){
							if(count($data['list'])>=20){
					?>
					<div class="loadmore">
						<button class="load" ><span class="unloadsync">+ </span><span class="unloadsync">Load More</span><span id="loadsync"></span></button>
					</div>
					<?php
							}
						}
					?>

				</div>
				<div class="no-product">
					Sorry, we didn't find any product...:(
				</div>

			</section>
			<?php
				}
			?>
		</section>
	</main>
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
	<div class="loadme"></div>

	<script type="text/javascript" src="slick/slick.min.js"></script>
	<script type="text/javascript" src="js/manager.jquery.js"></script>
	<script type="text/javascript" src="js/app.js"></script>

	<script type="text/javascript" src="js/shopviewer.js "></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.fav').on("click",function(){

				if(uShopper.isLogin){
					
					<?php if($user->getType()!==WebUser::TYPE_GUEST){ ?>	
					
					$(this).manager({
						task:'wishlist_add',
			  			url:'android/user_product/wishlistmanager.php',
			  			data:{
			  				"id":"<?php if(isset($_SESSION['id']))echo $_SESSION['id'] ?>",
			  				"email":"<?php if(isset($_SESSION['email']))echo $_SESSION['email'] ?>",
			  				"data":'add',
			  				"pid":this.dataset.itemId,
			  				"type":this.dataset.itemType
			  			}	
					});

					<?php } ?>
					
				}
				else{
					$('.loginbut').trigger('click');
				}
			});

			if(uShopper.disableAll){
				$('.disable').prop("disabled",true);
			}

			$('.disable').on("mouseover",function(){
				if(uShopper.disableAll){
					alert("Sorting and Filtering System is not Available in this Category Search");
				}
			});
				
		});
	</script>
	
		

</body>
</html>