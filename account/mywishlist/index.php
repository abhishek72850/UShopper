<?php
	session_start();

	require_once('../../fbvendor/autoload.php');
	require_once('../../gvendor/autoload.php');
	require_once('../../dbconfig.php');
	require_once('../../webuser.php');
	require_once('../../myvendor/usermanager.php');
	require_once('../../myvendor/wishlistmanager.php');
	require_once('../../myvendor/cartmanager.php');
	require_once('../../myvendor/variablecheck.php');

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

	if(VariableCheck::isUnknown($_GET,array('sort'))){
		
		$wish=new WishlistManager($_SESSION['id'],$_SESSION['email']);

		if(isset($_GET['sort'])){
			if(VariableCheck::checkSortValue($_GET["sort"])){

			}
			else{

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
	<link rel="stylesheet" type="text/css" href="../../css/pace.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="../../css/input_normalize.css" />
	<link rel="stylesheet" type="text/css" href="../../css/input_demo.css" />
	<link rel="stylesheet" type="text/css" href="../../css/input_set2.css" />
	<link rel="stylesheet" type="text/css" href="../../css/animate.css" />
	<link rel="stylesheet" type="text/css" href="../../css/header.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/footer.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/account.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/wishlist.css" />
	
	<style type="text/css">
		.account-navigation>div:nth-child(3)>ul>li:nth-child(2)>a{
		  background-color: #34c24d11;
		  color: #222;
		  border-left: 2px solid #34c24d;
		}
	</style>

	<script type="text/javascript" src="../../engine0/jquery.js"></script>
	<script type="text/javascript" src="../../js/featherlight.min.js"></script>
	<script src="../../js/pace.min.js"></script>
	<script type="text/javascript" src="../../js/notify.js"></script>
	<script type="text/javascript" src="../../js/noty/packaged/jquery.noty.packaged.min.js"></script>
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
					<form method="get" action="../../search.php">
						<input type="text" name="searchfor" placeholder="Search for your product "/>
						<button type="submit"><img src="../../images/search.png"></button>
						<select name="sby">
							<option value="product">By Product</option>
							<option value="shop">By Shop</option>
						</select>
						<input type="hidden" name="sort" value="1"/>
					</form>
				</li>
				<li>
					<input type="button" name="cartButton" class="check-it" data-href="../../cart.php" value="Cart"/>
					<span id="cartCounter"><?php if($user->getType()!==WebUser::TYPE_GUEST) echo CartManager::getCartCount($db,$_SESSION['id']); else echo "0"; ?></span>
				</li>
				<li>
					<div class="user-drop">
						<span>
							<?php

								$upic='';
								if(strpos($user->detail()['upic'],"images/")!==false){
									$upic="../../".$user->detail()['upic'];
								}
								else{
									$upic=$user->detail()['upic'];
								}

								if($user->getType()==WebUser::TYPE_FB){
									echo "<span>".$user->getFirstName()."</span>";
									echo "<img src='".$upic."' width='50px' height='50px' alt='user'></img>";
	 							}
	 							else if($user->getType()==WebUser::TYPE_GOOGLE){
	 								echo "<span>".$user->getFirstName()."</span>";
		 							echo "<img src='".$upic."' width='50px' height='50px' alt='user'></img>";
	 							}
	 							else if($user->getType()==WebUser::TYPE_SELF){
	 								echo "<span>".$user->getFirstName()."</span>";
		 							echo "<img src='".$upic."' width='50px' height='50px' alt='user'></img>";
	 							}
	 							else if($user->getType()==WebUser::TYPE_GUEST){
	 								echo "<span>".$_SESSION['name']."</span>";
	 								echo "<img src='../../images/guest-green.png' width='50px' height='50px' alt='user'></img>";		
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
										echo "<a href='../../logout.php' id='logout'>Logout</a>";
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
			<li><a href="../../search.php?searchfor=grocery&sby=product&sort=1">Grocery</a></li>
			<li><a href="../../search.php?searchfor=elcetronic&sby=product&sort=1">Electronics</a></li>
			<li><a href="../../search.php?searchfor=men+cloth+fashion&sby=product&sort=1">Mens Fashion</a></li>
			<li><a href="../../search.php?searchfor=women&sby=product&sort=1"">Womens Fashion</a></li>
			<li><a href="../../search.php?searchfor=shoe+footwear&sby=product&sort=1"">Footwear</a></li>
			<li><a href="../../search.php?searchfor=sweets+snacks+bakery&sby=product&sort=1"">Sweets and Snacks</a></li>
		</ul>
	</nav>
	<main>
		<div class="page-navigation"><?php echo $user->breadcrumb(); ?></div>
		<section>
			<div class="account-navigation">
				<div>My Account</div>
				<div>
					<img src="<?php echo $upic ?>" alt="user-pic" width="50px" height="50px"/>
					<h3><?php echo $user->getUName() ?></h3>
					<span><?php echo $user->getUEmail() ?></span>
				</div>
				<div>
					<h3>YOUR HISTORY</h3>
					<ul>
						<li><a href="../myorders">Orders</a></li>
						<li><a href="#">Wishlist</a></li>
						<li><a href="../myshops">Saved Stores</a></li>
					</ul>
				</div>
				<div>
					<h3>SETTINGS</h3>
					<ul>
						<li><a href="../">Personnel Setting's</a></li>
						<li><a href="../changepassword">Change Password</a></li>
						<li><a href="../myaddress">Addresses</a></li>
						<li><a href="../profilesetting">Profile</a></li>
						<li><a href="../acoountmail">Update Email/Mobile</a></li>
						<li><a href="../deactivate">Deactivate Account</a></li>
					</ul>
				</div>
				<div>
					<h3>PAYMENTS</h3>
					<ul>
						<li><a href="../mypoints">Loyality Points</a></li>
						<li><a href="../mycards">My Saved Cards</a></li>
					</ul>
				</div>
			</div>
			<div class="account-category">
				<div class="cat-head">
					<h3>WISHLIST</h3>
					<div>
						<label for="sort-list">Sort by:</label>
						<select id="sort-list">
							<option value="1" <?php if(isset($_GET['sort'])&&$_GET['sort']==="1")echo "selected" ?> >Low to High</option>
							<option value="2" <?php if(isset($_GET['sort'])&&$_GET['sort']==="2")echo "selected" ?> >High to Low</option>
							<option value="3" <?php if(isset($_GET['sort'])&&$_GET['sort']==="3 ")echo "selected" ?> >Date Added</option>
						</select>
					</div>
				</div>
				<div class="cat-body">

					<?php
						if($wish->verifyUser()){

							if(isset($_GET['sort'])){
								$data=json_decode($wish->sortWishlist("product",$_SESSION['id'],$_GET['sort']),true);
								//var_dump($data);
							}
							else{
								$data=json_decode($wish->getlist("product"),true);
							}

							if($data['success']){

								foreach ($data['list'] as $key => $value) {
									
					?>
					<div class="wishlist-item" data-item-id="<?php echo $value['pid'] ?>">
						<img src="<?php echo $value['photo'][0] ?>" onclick="window.location='../../product.php?id=<?php echo $value["pid"] ?>'" >
						<div>
							<h3 onclick="window.location='../../product.php?id=<?php echo $value["pid"] ?>'"><?php echo $value['pname']; ?></h3>
							<p><?php echo $value['pactualprice']; ?></p>
							<?php 
								if($value['instock']){
									echo "<p style='color:#34c24d'>Stock Available</p>";
								}
								else{
									echo "<p style='color:#f00'>Out of Stock</p>";	
								} 
							?>
							<div>
								<input class="manage" type="button" data-task="cart_add"  data-task-command="add" data-change-link="true" data-item-id="<?php echo $value['pid'] ?>" data-item-type="product" value="ADD TO CART">
								<input class="fav_ico" type="button" data-item-id="<?php echo $value['pid'] ?>" data-item-type="product" data-change-link="true" data-task="wishlist_remove" data-task-command="delete" value="REMOVE">
							</div>
						</div>
					</div>
					<?php
								} 
							}
							else{
								echo "<p class='wishlist-no-item'>Sorry no item in your Wishlist....:(</p>";
							}
						}
					?>
				</div>
			</div>
		</section>
  </main>
  <footer>
		<div>
			<div>
				<h3>Connect with us</h3>
				<ul>
					<li><a href="#"><img src="../images/facebook.png" alt="Facebook"/></a></li>
					<li><a href="#"><img src="../images/twitter.png" alt="Twitter"/></a></li>
					<li><a href="#"><img src="../images/instagram.png" alt="Instagram"/></a></li>
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
				<h3>Rely on eTailors</h3>
				<h4>Absolutely Guaranteed</h4>
				<h4>Every time. Any reason.<br> Or we'll make it right.</h4>
			</div>
		</div>
		<div>
			<span>PAYMENT METHOD</span>
			<ul>
				<li><img src="../images/visa.png" alt="visa"/></li>
				<li><img src="../images/master.png" alt="master"/></li>
				<li><img src="../images/maestro.png" alt="maestro"/></li>
				<li><img src="../images/rupay.jpg" alt="rupay"/></li>
				<li><img src="../images/netbank.png" alt="online"/></li>
			</ul>
		</div>
		<div>
			<ul>
				<li><img src="" alt="logo"/></li>
				<li>Fashion you want</li>
			</ul>
		</div>
		<div>
			<h4>Copyright 2017. All right reserved.</h4>
		</div>
  	</footer>
  	<script type="text/javascript" src="../../js/manager.jquery.js"></script>
	<script type="text/javascript" src="../../js/app.js"></script>
  	<script type="text/javascript">
  	
  	$(document).ready(function(){

		$("#sort-list").on('change',function(){

			var value=this.value;

			var json={
				"id":"<?php echo $_SESSION['id'] ?>",
				"email":"<?php echo $_SESSION['email'] ?>",
				"data":"sort",
				"value":this.value,
				"type":"product"
			};

	    	var request=$.ajax({
			 	url:"../../android/user_product/wishlistmanager.php",
			 	method:"POST",
			 	dataType:"text",
			 	data:json
			});

			request.done(function(data){
				//console.log(data);
				data=JSON.parse(data);
				if(data.success){
					var stateObj={source:"index.php"};
					history.pushState(stateObj,"wishlist","?sort="+value);
					loadItem(data.list);
				}
				else{

				}

			});
		});

		var loadItem=function(data){

			$('.cat-body>.wishlist-item').detach();

			for(key in data){

				var img=$("<img />",{
	 				"src":data[key].photo[0],
	 				click:function(){
	 					window.location='../../product.php?id='+data[key].id
	 				}
	 			});

	 			var name=$("<h3></h3>",{
	 				"text":data[key].pname,
	 				click:function(){
	 					window.location='../../product.php?id='+data[key].id
	 				}
	 			});

	 			var price=$("<p></p>",{
	 				"text":data[key].pactualprice
	 			});

	 			var stock;

	 			if(data[key].pquantity>0){
					stock=$("<p></p>",{
						"style":"color:#34c24d",
						"text":"Stock Available"
					});
				}
				else{
					
					stock=$("<p></p>",{
						"style":"color:#f00",
						"text":"Out of Stock"
					});
				} 

	 			var cart=$("<input/>",{
	 				"class":"manage",
	 				"name":"addtocart",
	 				"data-task-command":"add",
	 				"data-task":"cart_add",
	 				"data-item-id":data[key].id,
	 				"value":"Add to Cart"
	 			});

	 			var remove=$("<input/>",{
	 				"class":"manage",
	 				"name":"wishlist_remove",
	 				"data-task-command":"delete",
	 				"data-task":"wishlist_remove",
	 				"data-item-id":data[key].id,
	 				"value":"Remove"
	 			});

	 			var butC=$("<div></div>");
	 			var detail=$("<div></div>");
	 			var item=$("<div></div>",{
	 				"class":"wishlist-item",
	 				"data-item-id":data[key].id
	 			});

	 			butC.append(cart);
	 			butC.append(remove);

	 			detail.append(name);
	 			detail.append(price);
	 			detail.append(stock);
	 			detail.append(butC);

	 			item.append(img);
	 			item.append(detail);

	 			$(".cat-body").append(item);
			}
		}		
  	});
  </script>
</body>
</html>
