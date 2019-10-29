<?php
	session_start();
	
	require_once('fbvendor/autoload.php');
	require_once ('gvendor/autoload.php');
	require_once('dbconfig.php');
	require_once('webuser.php');
	require_once('myvendor/cartmanager.php');
	require_once('myvendor/productmanager.php');

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
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="css/animate.css">
	<link rel="stylesheet" type="text/css" href="css/header.css"/>
	<link rel="stylesheet" type="text/css" href="css/footer.css"/>
	<link rel="stylesheet" type="text/css" href="css/cart.css"/>


	<script type="text/javascript" src="engine0/jquery.js"></script>
	<script type="text/javascript" src="aos/dist/aos.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>
	<script src="js/pace.min.js"></script>
	<script type="text/javascript" src="js/notify.js"></script>
	<script type="text/javascript" src="js/noty/packaged/jquery.noty.packaged.min.js"></script>
	
</head>
<body>
	<script type="text/javascript">
		
		var uShopper={
			isLogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			notify:null
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
						<input type="text" name="searchfor" placeholder="Search for your product "/>
						<button type="submit"><img src="images/search.png"></button>
						<select name="sby">
							<option value="product">By Product</option>
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
			<li><a href="#">Grocery</a></li>
			<li><a href="#">Electronics</a></li>
			<li><a href="#">Mens Fashion</a></li>
			<li><a href="#">Womens Fashion</a></li>
			<li><a href="#">Footwear</a></li>
			<li><a href="#">Sweets and Snacks</a></li>
		</ul>
	</nav>
	<main>
  	<section class="shopping-cart">
			<div>

				<span>Shopping cart
				<?php

					$data=json_decode(CartManager::getCart($db,$_SESSION['id']),true);

					if($data['success']){
						echo '('.$data['length'].' items)';
					}
					else{
						echo '(0 items)';
					}
				?>
				</span>

				<table class="cart-table">
					<thead>
						<tr>
							<th></th>
							<th>Item detail</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Delivery detail</th>
							<th>Subtotal</th>
						</tr>
					</thead>
					<tbody>
						<?php

							if($data['success']){

								foreach ($data['list'] as $key => $value) {
								
						?>
						<tr data-item-id="<?php echo $value['pid'] ?>">
							<td><img src="<?php echo $value['photo'][0] ?>" alt="item" width="80px" height="90px"></td>
							<td>
								<div>
									<a href="product.php?id=<?php echo $value['pid'] ?>"><h3><?php echo $value['pname'] ?></h3></a>
									<span>Size: M</span>
									<?php $stock=json_decode(CartManager::checkStock($db,$_SESSION['id'],$value['pid'],$value['cartquantity']),true); 
										if($stock['status']==='In Stock'){
											$style='color:#0f0';
										}
										else if($stock['status']==='Quantity more than stock'){
											$style='color:#fa0';
										}
										else{
											$style='color:#f00';
										}

									?>
									<span data-item-status="<?php if($stock['success'])echo '1';else echo '0' ?>" style="<?php echo $style ?>"><?php echo $stock['status']?></span>
								</div>
								<a href="javascript:void(0)" data-task="cart_remove" class="manage" data-item-id="<?php echo $value['pid'] ?>" data-command="delete">Remove</a>
							</td>
							<td>

								<select class="quantity" data-task="cart_update" data-command="update" data-item-id="<?php echo $value['pid'] ?>">
									<?php
										for ($i=1; $i <=$value['stockquantity'] ; $i++) { 
											if($i===intval($value['cartquantity']))
												echo '<option selected value="'.$i.'">'.$i.'</option>';
											else{
												echo '<option value="'.$i.'">'.$i.'</option>';
											}
										}
									?>
								</select>
							</td>
							<td>Rs <?php echo $value['pactualprice'] ?></td>
							<td>
								<h3>Free</h3>
								<span>Delivery time</span>
							</td>
							<td><?php echo $value['pactualprice']*$value['cartquantity'] ?></td>
							<td colspan="6" data-item-id="<?php echo $value['pid'] ?>" class="loadme"></td>
						</tr>
						<?php
								}
							}
							else{
						?>
						<tr>
							<td colspan="6" class="no-cart">No Item in your cart <button type="button">Start Shopping</button></td>
						</tr>
						<?php
							}
						?>
					</tbody>
					<?php
						if($data['success']){
					?>
					<tfoot>
						<tr>
							<td colspan="6">
								<?php
									$total=0;
									foreach ($data['list'] as $key => $value) {
											$total+=($value['pactualprice']*$value['cartquantity']);
									}
								?>
								<input onclick="window.location='placeorder.php?buy=cart'" type="button" data-total-price='<?php echo $total ?>' value="Proceed to pay Rs. <?php echo $total ?>" name="proceed-payment"/>
							</td>
						</tr>
					</tfoot>
					<?php
						}
					?>
				</table>
				<div style="height:100px;text-align:center;padding-top:40px;display:none;">No item in the cart</div>
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
				<h3>Rely on eTailors</h3>
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
				<li>Fashion you want</li>
			</ul>
		</div>
		<div>
			<h4>Copyright 2017. All right reserved.</h4>
		</div>
  	</footer>
  	<script type="text/javascript" src="js/manager.jquery.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
  	<script type="text/javascript">
  	
	  	$(document).ready(function() {

	  		
	  		$('.manage,.quantity').on('click change',function(e){

	  			if(uShopper.isLogin){

	  				<?php if($user->getType()!==WebUser::TYPE_GUEST){ ?>

	  				if($(this).attr('class')==='quantity'&&e.type==="click"){
	  					return;
	  				}
	  				if($(this).attr('class')==='manage'&&e.type==="change"){
	  					return;
	  				}

					$('td[data-item-id="'+this.dataset.itemId+'"]').fadeToggle(500);

		  			$(this).manager({
		  				task:this.dataset.task,
			  			url:'android/user_product/cartmanager.php',
			  			data:{
			  				"id":"<?php if(isset($_SESSION['id']))echo $_SESSION['id'] ?>",
			  				"email":"<?php if(isset($_SESSION['email']))echo $_SESSION['email'] ?>",
			  				"data":this.dataset.command,
			  				"pid":this.dataset.itemId,
			  				"quantity":$(this).context.value
			  			},
			  			callback:true,
			  			callbackfunc:function(ref){
			  				window.location.reload();
			  			}
		  			});

		  			<?php } ?>
		  		}
		  		else{
		  			$('.loginbut').trigger('click');
		  		}
	  		});
	  	});
  </script>
</body>
</html>