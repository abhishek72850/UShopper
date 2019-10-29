<?php
	session_start();

	require_once('../../fbvendor/autoload.php');
	require_once('../../gvendor/autoload.php');
	require_once('../../dbconfig.php');
	require_once('../../webuser.php');
	require_once('../../myvendor/usermanager.php');
	require_once('../../myvendor/cartmanager.php');

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

	if (isset($_SESSION['id'])&&isset($_SESSION['email'])&&isset($_SESSION['name'])) {

	}
	else{
		header('location:../');
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
	<link rel="stylesheet" type="text/css" href="../../css/header.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/footer.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/account.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/address.css"/>

	<style type="text/css">
		.account-navigation>div:nth-child(4)>ul>li:nth-child(3)>a{
		  background-color: #34c24d11;
		  color: #222;
		  border-left: 2px solid #34c24d;
		}
	</style>

	<script type="text/javascript" src="../../engine0/jquery.js"></script>
	<script type="text/javascript" src="../../js/featherlight.min.js"></script>
	<script src="../../js/pace.min.js"></script>
</head>
<body>
	<script type="text/javascript">
		var User={
			type:null,
			adrId:'',
			feather:null,
			finish:function(){
				User.feather.close();
				window.location.reload();
			}
		}
	</script>
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
						<li><a href="../mywishlist">Wishlist</a></li>
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
						<li><a href="../accountmail">Update Email/Mobile</a></li>
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
					<h3>ADDRESSES</h3>
				</div>
				<div class="cat-body">
					<div class="saved-address-container">
						<?php 
							$data=json_decode(UserManager::getUserAddress($db,$_SESSION['id']),true);

							//print_r($data);
							if($data['success']){

								foreach ($data['list'] as $key => $value) {
								
						?>
						<div class="saved-address">
							<address>
								<h4><?php echo $value['name'] ?></h4>
								<p><?php echo $value['stadr'] ?></p>
								<p><?php echo $value['landmark'] ?></p>
								<p>
									<span>Allahabad</span>
									<span>UP</span>
									<span>India</span>
								</p>
								<p><?php echo $value['pin'] ?></p>
								<p><?php echo $value['mobile'] ?></p>
							</address>
							<div>
								<a href="javascript:void(0)" data-address='<?php echo $value["id"] ?>' class='edit-adr'>Edit</a>
								<a href="<?php echo $_SERVER['PHP_SELF'].'?remove='.$value["id"] ?>" data-address='<?php echo $value["id"] ?>' class='remove-adr'>Remove</a>
							</div>
						</div>
						<?php
								}
							}
						?>
						<div class="add-address-but">+</div>
					</div>
				</div>
			</div>
		</section>
  </main>
  <footer>
		<div>
			<div>
				<h3>Connect with us</h3>
				<ul>
					<li><a href="#"><img src="../../images/facebook.png" alt="Facebook"/></a></li>
					<li><a href="#"><img src="../../images/twitter.png" alt="Twitter"/></a></li>
					<li><a href="#"><img src="../../images/instagram.png" alt="Instagram"/></a></li>
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
				<li><img src="../../images/visa.png" alt="visa"/></li>
				<li><img src="../../images/master.png" alt="master"/></li>
				<li><img src="../../images/maestro.png" alt="maestro"/></li>
				<li><img src="../../images/rupay.jpg" alt="rupay"/></li>
				<li><img src="../../images/netbank.png" alt="online"/></li>
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

			/*$('form').on('submit',function(e){
				e.preventDefault();
				var reg=/[^a-z]/i;

			});*/

			var lightupFrame=function(type,aid){
				User.feather=$.featherlight({iframe: 'addressform.php?type='+type+'&aid='+aid,loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 400,iframeHeight: 450});
			}

			$('.add-address-but').on("click",function(){
				User.type='add';
				lightupFrame("add",'');
	 		});

	 		$('.edit-adr').on('click',function(){
	 			User.type='edit';
	 			User.adrId=this.dataset.address;
	 			lightupFrame('edit',this.dataset.address);
	 		});

		});
	</script>

</body>
</html>