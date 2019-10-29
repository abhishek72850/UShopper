<?php
	session_start();

	require_once('fbvendor/autoload.php');
	require_once ('gvendor/autoload.php');
	require_once('dbconfig.php');
	require_once('webuser.php');
	require_once('myvendor/cartmanager.php');
	require_once('myvendor/ordermanager.php');
	require_once('myvendor/productmanager.php');
	require_once('myvendor/usermanager.php');
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

	session_write_close();

	if(VariableCheck::isUnKnown($_GET,array("oid"))){

		$data=json_decode(OrderManager::getOrder($db,$_GET['oid'],$user->getUID()),true);

		if(!$data['success']){

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
	<link rel="stylesheet" type="text/css" href="css/input_normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/input_demo.css" />
	<link rel="stylesheet" type="text/css" href="css/input_set2.css" />
	<link rel="stylesheet" type="text/css" href="css/animate.css">
	<link rel="stylesheet" type="text/css" href="css/header.css"/>
	<link rel="stylesheet" type="text/css" href="css/orderplaced.css"/>
	<link rel="stylesheet" type="text/css" href="css/footer.css"/>

	<script type="text/javascript" src="engine0/jquery.js"></script>
	
	<script type="text/javascript" src="aos/dist/aos.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>
	<script type="text/javascript" src="js/pace.min.js"></script>
	<script type="text/javascript" src="js/notify.js"></script>
	<script type="text/javascript" src="js/noty/packaged/jquery.noty.packaged.min.js"></script>

</head>
<body>
	<script type="text/javascript">
		var uShopper={
			isLogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>
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
					<span id="cartCounter"><?php if($user->getType()!==WebUser::TYPE_GUEST) echo CartManager::getCartCount($db,$user->getUID()); else echo "0"; ?></span>
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
		<section>
			<div class="order-head">
				<h3>Congratulation's Your Order Has Been Placed Successfully!!!</h3>
				<h4>Your Order ID : <span><?php echo $_GET['oid'] ?></span></h4>
			</div>
			<div class="order-detail">
				<h4>Order Details</h4>
				<div  class="order-table">
					<table>
						<thead>
							<tr>
								<th></th>
								<th>Item Name</th>
								<th>Quantity</th>
								<th>Price</th>
								<th>SubTotal</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach ($data['list'] as $key => $value) {	
							?>
							<tr>
								<td><img src="<?php echo $value['photo'][0] ?>"></td>
								<td><?php echo ucwords($value['pname']) ?></td>
								<td><?php echo $value['quantity'] ?></td>
								<td><?php echo $value['price'] ?></td>
								<td><?php echo intval($value['quantity'])*intval($value['price']) ?></td>
							</tr>
							<?php
								}
							?>
						</tbody>
						<tfoot>
							<tr>
								<?php
									$total=0;
									foreach ($data['list'] as $key => $value) {
											$total+=($value['price']*$value['quantity']);
									}
								?>
								<td colspan="5">
									<span>Total : Rs <?php echo $total ?></span>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
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
	<script type="text/javascript" src="slick/slick.min.js"></script>
	<script type="text/javascript" src="js/manager.jquery.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
</body>
</html>