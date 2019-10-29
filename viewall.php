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
	require_once('myvendor/variablecheck.php');
	require_once('myvendor/viewmanager.php');

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

	if(VariableCheck::isUnKnown($_GET,array("view","sort","filter","pricemin","pricemax","discount","prate","srate"))){

		if($_GET['view']==="recent"){
			
			if($user->getType()===WebUser::TYPE_GUEST){

			} 
		}
	}
	else{

	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>uShopper</title>
	<link rel="stylesheet" href="css/pace.css"/>
	<link rel="stylesheet" type="text/css" href="aos/dist/aos.css" />
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="engine0/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/animate.css">
	<link rel="stylesheet" type="text/css" href="css/css-stars.css">
	<link rel="stylesheet" type="text/css" href="css/header.css"/>
	<link rel="stylesheet" type="text/css" href="css/footer.css"/>
	<link rel="stylesheet" type="text/css" href="css/viewall.css"/>
	
	<script type="text/javascript" src="engine0/jquery.js"></script>
	<script type="text/javascript" src="engine0/jquery-ui.js"></script>

	<script type="text/javascript" src="aos/dist/aos.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>
	<script type="text/javascript" src="js/pace.min.js"></script>
	<script type="text/javascript" src="js/notify.js"></script>
	<script type="text/javascript" src="js/noty/packaged/jquery.noty.packaged.min.js"></script>
	<script type="text/javascript" src="js/jquery.barrating.min.js"></script>
	<script type="text/javascript">

		var uShopper={
			search:"<?php echo $_GET["view"] ?>",
			uid:"<?php echo $user->getUID() ?>",
			sort:<?php if(isset($_GET["sort"]))echo "'".$_GET["sort"]."'";else echo "'0'" ?>,
			filter:<?php if(isset($_GET["filter"]))echo "'".$_GET["filter"]."'";else echo "'0'"; ?>,
			whichfilter:"<?php if(isset($_GET["filter"]))echo $_GET["filter"] ;else echo "" ?>",
			priceFilter:<?php if(isset($_GET["pricemin"])&&$_GET["pricemin"]!=="0")echo "true";else echo "false"; ?>,
			pricemin:<?php if(isset($_GET["pricemin"]))echo $_GET["pricemin"];else echo "0"; ?>,
			pricemax:<?php if(isset($_GET["pricemax"]))echo $_GET["pricemax"];else echo "0"; ?>,
			disFilter:<?php if(isset($_GET["discount"])&&$_GET["discount"]!=="0")echo "true";else echo "false"; ?>,
			disValue:<?php if(isset($_GET["discount"]))echo $_GET["discount"];else echo "0"; ?>,
			prateFilter:<?php if(isset($_GET["prate"])&&$_GET["prate"]!=="0")echo "true";else echo "false"; ?>,
			prateValue:<?php if(isset($_GET["prate"]))echo $_GET["prate"];else echo "0"; ?>,
			srateFilter:<?php if(isset($_GET["srate"])&&$_GET["srate"]!=="0")echo "true";else echo "false"; ?>,
			srateValue:<?php if(isset($_GET["srate"]))echo $_GET["srate"];else echo "0"; ?>,


			result:null,
			cursor:null,
			
			isloaded:false,
			isLogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>',
			ref:null,
			pendingTag:"",
			noProduct:false,
			offset:0
		};

	</script>
</head>
<body>
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
	 								echo "<img src='images/img_a.jpg' width='50px' height='50px' alt='user'>";		
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
			<li><a href="search.php?searchfor=women&sby=product&sort=1">Womens Fashion</a></li>
			<li><a href="search.php?searchfor=shoe+footwear&sby=product&sort=1">Footwear</a></li>
			<li><a href="search.php?searchfor=sweets+snacks+bakery&sby=product&sort=1">Sweets and Snacks</a></li>
		</ul>
	</nav>
	<main>

		<?php
			if($_GET['view']!=="srated"){
		?>
		<section class="filter-bar">
			<div>
				Filter by:
			</div>
			<div>
				<h4>Price Range</h4>
				<div class="pslider"></div>
				<div>
					<input type="text" name="slidermin" value="500">
					<input type="text" name="slidermax" value="1500">
					<button type="button" id="go">GO</button>
				</div>
			</div>
			<div>
		
				<h4>Discount %</h4>
				<div class="radioset">
					<input type="radio" id="mt90" name="discount" data-value="90"><label for="mt90">more than 90</label>
					<input type="radio" id="mt80" name="discount" data-value="80"><label for="mt80">more than 80</label>
					<input type="radio" id="mt50" name="discount" data-value="50"><label for="mt50">more than 50</label>
					<input type="radio" id="mt30" name="discount" data-value="30"><label for="mt30">more than 30</label>
					<input type="radio" id="mt10" name="discount" data-value="10"><label for="mt10">more than 10</label>
				</div>
			</div>
			<div>
				<h4>Product Rating</h4>
				<div class="radioset">
					<input type="radio" id="4sup" name="prate" data-value="4"><label for="4sup">4 and Above</label>
					<input type="radio" id="3sup" name="prate" data-value="3"><label for="3sup">3 and Above</label>
					<input type="radio" id="2sup" name="prate" data-value="2"><label for="2sup">2 and Above</label>
					<input type="radio" id="1sup" name="prate" data-value="1"><label for="1sup">1 and Above</label>
					<input type="radio" id="0sup" name="prate" data-value="0"><label for="0sup">0 and Above</label>
				</div>
			</div>
			<div>
				<h4>Shop Rating</h4>
				<div class="radioset">
					<input type="radio" id="s4sup" name="srate" data-value="4"><label for="s4sup">4 and Above</label>
					<input type="radio" id="s3sup" name="srate" data-value="3"><label for="s3sup">3 and Above</label>
					<input type="radio" id="s2sup" name="srate" data-value="2"><label for="s2sup">2 and Above</label>
					<input type="radio" id="s1sup" name="srate" data-value="1"><label for="s1sup">1 and Above</label>
					<input type="radio" id="s0sup" name="srate" data-value="0"><label for="s0sup">0 and Above</label>
				</div>
			</div>
		</section>
		<section class="search-result-bar">

			<div class="sort-bar">
				<div>Showing Result</div>
				<div>
					<span>Sort by: </span>
					<select class="sort disable">
						<option value="1" <?php if(isset($_GET['sort'])&&$_GET['sort']==="1")echo "selected" ?> >Low to High</option>
						<option value="2" <?php if(isset($_GET['sort'])&&$_GET['sort']==="2")echo "selected" ?> >High to Low</option>
						<option value="4" <?php if(isset($_GET['sort'])&&$_GET['sort']==="4")echo "selected" ?> >Latest</option>
						<option value="3" <?php if(isset($_GET['sort'])&&$_GET['sort']==="3")echo "selected" ?> >Popular</option>
					</select>
				</div>
			</div>
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
						if ($_GET["srate"]!=="0") {
							echo "<div class='ftag srate' data-filter-tag='srate'>
								<div>Shop Rate More Than ".$_GET["srate"]."</div>
								<span class='remove-tag'>x</span>
							</div>";
						}
					}
				?>
			</div>
			<div class="search-result-container">

				<?php


					if(!isset($_GET["sort"])&&!isset($_GET["filter"])&&!isset($_GET["pricemin"])&&!isset($_GET["pricemax"])&&!isset($_GET["discount"])&&!isset($_GET["prate"])&&!isset($_GET["srate"])) {
						
						$data=json_decode(ViewManager::getViewItems($db,false,false,$_GET['view'],array(),array(),0,$user->getUID()),true);
					}
					else if((isset($_GET["sort"])&&isset($_GET["filter"]))&&(isset($_GET["pricemin"])&&isset($_GET["pricemax"])&&isset($_GET["discount"])&&isset($_GET["prate"])&&isset($_GET["srate"]))) {
						
						if($_GET["sort"]!=='0'&&$_GET["filter"]!=='0'){
							
							if(VariableCheck::checkSortValue($_GET["sort"])&&VariableCheck::checkFilterValue($_GET["filter"])&&VariableCheck::checkPriceValue($_GET["pricemin"],$_GET["pricemax"])&&VariableCheck::checkDiscountValue($_GET["discount"])&&VariableCheck::checkRatingValue($_GET["prate"])&&VariableCheck::checkRatingValue($_GET["srate"])){

								$data=json_decode(ViewManager::getViewItems($db,true,true,$_GET['view'],array($_GET['sort'],$_GET['filter']),array($_GET["pricemin"],$_GET["pricemax"],$_GET["discount"],$_GET["prate"],$_GET["srate"]),0,$user->getUID()),true);
							}
							else{
								//header("location:index.php");
								echo "filter sort data";
							}
						}
						else{
							echo "filter sort";
							//header("location:index.php");
						}
					}
					else if(isset($_GET["sort"])&&$_GET["sort"]!=='0'){

						if(VariableCheck::checkSortValue($_GET["sort"])){
					
							$data=json_decode(ViewManager::getViewItems($db,true,false,$_GET['view'],array($_GET['sort'],"0"),array(),0,$user->getUID()),true);		

						}
						else{
							echo "sort";
							//header("location:index.php");
						}
					}
					else if((isset($_GET["filter"])&&$_GET["filter"]!=='0')&&(isset($_GET["pricemin"])&&isset($_GET["pricemax"])&&isset($_GET["discount"])&&isset($_GET["prate"])&&isset($_GET["srate"]))) {

						if(VariableCheck::checkFilterValue($_GET["filter"])&&VariableCheck::checkPriceValue($_GET["pricemin"],$_GET["pricemax"])&&VariableCheck::checkDiscountValue($_GET["discount"])&&VariableCheck::checkRatingValue($_GET["prate"])&&VariableCheck::checkRatingValue($_GET["srate"])){

							$data=json_decode(ViewManager::getViewItems($db,false,true,$_GET['view'],array("0",$_GET['filter']),array($_GET["pricemin"],$_GET["pricemax"],$_GET["discount"],$_GET["prate"],$_GET["srate"]),0,$user->getUID()),true);
						}
						else{
							echo "filter";
							//header("location:index.php");
						}
					}
 					else{
						echo "data";
						//header("location:index.php");
					}
					
					if($data['success']){
				?>

				<div class="product-container">

					<?php
						foreach ($data['list'] as $key => $value) {
					?>
				
					<div class="item" >
						<img src= "<?php echo $value["photo"][0]; ?>" />
						<?php
							echo WishlistManager::checkInWishlist($db,$value['pid'],$user->getUID(),"product");
						?>
						<div class="item-detail">
							<a href="product.php?id=<?php echo $value['pid'] ?>"><?php echo $value["pname"]; ?></a>
							<a href="#"><?php echo $value["sname"]; ?></a>
							<p>
								
								<select class="static-rate">
									<option value="1" <?php if($value['prating']===1)echo 'selected' ?> >1</option>
									<option value="2" <?php if($value['prating']===2)echo 'selected' ?> >2</option>
									<option value="3" <?php if($value['prating']===3)echo 'selected' ?> >3</option>
									<option value="4" <?php if($value['prating']===4)echo 'selected' ?> >4</option>
									<option value="5" <?php if($value['prating']>=5)echo 'selected' ?> >5</option>
								</select>

							</p>
							<p>Rs <?php echo $value["pactualprice"]; ?><del style="margin-left: 10px;"><?php echo $value["pmrp"]; ?></del></p>
						</div>
						<div class="item-get">
							<button class="manage" type="button"  data-task-command="add" data-item-id="<?php echo $value['pid'] ?>" data-task="cart_add" data-item-type="product">ADD TO CART</button>
							<button type="button" onclick="window.location='placeorder.php?buy=product&quantity=1&id=<?php echo $value["pid"] ?>'">BUY</button>
						</div>
					</div>
					<?php
						}
					?>
				</div>
				<?php
						echo "<script> uShopper.noProduct=false; </script>";
						echo "<script> uShopper.offset=20; </script>";
					}
					else{
						echo "<script> uShopper.noProduct=true; </script>";
					}
				?>
				<div class="no-product">
					Sorry, we didn't find any Shop...:(
				</div>
			</div>
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
			
		</section>
		<?php
			}
			else{

				$data=json_decode(ViewManager::getViewItems($db,false,false,$_GET['view'],array(),array(),0),true);
		?>
		<section class="shop-container">
			<div>
				<span>Showing Result</span>
			</div>
			
			<?php
				if($data['success']){

					foreach ($data['list'] as $key => $value) {
			?>
			<div class="shop">
				<div>
					<img src="<?php echo $value['photo'][0] ?>">
				</div>
				<div>
					<h3><?php echo ucwords($value['sname']) ?></h3>
					<p>
						<select class="static-rate">
							<option value="1" <?php if($value['srating']===1)echo 'selected' ?> >1</option>
							<option value="2" <?php if($value['srating']===2)echo 'selected' ?> >2</option>
							<option value="3" <?php if($value['srating']===3)echo 'selected' ?> >3</option>
							<option value="4" <?php if($value['srating']===4)echo 'selected' ?> >4</option>
							<option value="5" <?php if($value['srating']>=5)echo 'selected' ?> >5</option>
						</select>
						<span>Votes</span>
					</p>
					<p><?php echo ucwords($value['stype']) ?></p>
					<span><?php echo $value['smobile'] ?></span>
				</div>
				<div>
					<span class="fav" data-item-id="<?php echo $value['sid']; ?>" data-item-type="shop">F</span>
					<button onclick="window.location='shopviewer.php?id=<?php echo $value["sid"] ?>'">View Shop</button>
				</div>
			</div>
			<?php
					}
					echo "<script> uShopper.noProduct=false; </script>";
				}
				else{
					echo "<script> uShopper.noProduct=true; </script>";
				}
				
			?>
			<div class="no-product">
				Sorry, we didn't find any Shop...:(
			</div>
			<?php 
				if($data['success']){
					if(count($data['list'])>=20){
			?>
			<div class="loadmore-shop">
				<button class="load-shop" ><span class="unloadsync">+ </span><span class="unloadsync">Load More</span><span id="loadsync"></span></button>
			</div>
			<?php 
					}
				}
			?>
		</section>
		<?php
			}
		?>
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
	<script type="text/javascript" src="js/manager.jquery.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/viewall.js"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){


		});
		
	</script>
</body>
</html>