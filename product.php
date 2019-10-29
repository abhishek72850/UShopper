<?php
	session_start();

	require_once('fbvendor/autoload.php');
	require_once ('gvendor/autoload.php');
	require_once('dbconfig.php');
	require_once('webuser.php');
	require_once('myvendor/usermanager.php');
	require_once('myvendor/cartmanager.php');
	require_once('myvendor/wishlistmanager.php');
	require_once('myvendor/productparser.php');
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

	if(isset($_GET['id'])){

		$product=json_decode(ProductManager::getProductDetail($db,$_GET['id']),true);

		if(!$product['success']){

		}

		if($user->getType()!=WebUser::TYPE_GUEST){

			if(ProductManager::createBrowseTrack($db,$_SESSION['id'],$product['item']['pid'],'product',$product['item']['ptag'])){
				
			}
			else{
				echo "unable to create track";
			}

			if(isset($_POST['cname'])&&isset($_POST['cemail'])&&isset($_POST['ctext'])&&isset($_POST['crate'])){
				if(ProductManager::addComment($db,$_POST['cname'],$_POST['cemail'],$_POST['ctext'],$_POST['crate'],$_SESSION['id'],$_GET['id'])){
					
				}
				else{
					echo "Error";
				}
			}
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo ucfirst($product['item']['pname']) ?></title>
	<link rel="stylesheet" href="css/pace.css"/>
	<link rel="stylesheet" type="text/css" href="aos/dist/aos.css" />
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
  	<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
	<link rel="stylesheet" type="text/css" href="css/input_normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/input_demo.css" />
	<link rel="stylesheet" type="text/css" href="css/input_set2.css" />
	<link rel="stylesheet" type="text/css" href="css/animate.css">
	<link rel="stylesheet" type="text/css" href="css/css-stars.css">
	<link rel="stylesheet" type="text/css" href="css/header.css"/>
	<link rel="stylesheet" type="text/css" href="css/footer.css"/>
	<link rel="stylesheet" type="text/css" href="css/product.css"/>


	<script type="text/javascript" src="engine0/jquery.js"></script>
	<script type="text/javascript" src="aos/dist/aos.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>
	<script type="text/javascript" src="js/elevatezoom.js"></script>
	<script src="js/pace.min.js"></script>
	<script type="text/javascript" src="js/notify.js"></script>
	<script type="text/javascript" src="js/noty/packaged/jquery.noty.packaged.min.js"></script>
	<script type="text/javascript" src="js/jquery.barrating.min.js"></script>

</head>
<body>
	<script type="text/javascript">
		
		var uShopper={
			isLogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
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
	    <div class="page-navigation"><?php echo $user->breadcrumb(); ?></div>
	    
	    <section class="product-detail">
	      	
	      	<div class="product-image-gallery">
		        <div>

		        <?php 
		        	foreach ($product['item']['photo'] as $key => $value) {
		        		echo "<img src='".$value."' data-aos='zoom-in' data-aos-once='true' />";		
		        	}
		        ?>
			    		    	
		        </div>
		        <div>
		          	<img src="<?php echo $product['item']['photo'][0] ?>" alt="image-zoom" id="zoom_02" data-zoom-image="<?php echo $product['item']['photo'][0] ?>"/>
		          	<div class="order-button">
						<button class="manage" type="button" data-task="cart_add"  data-task-command="add" data-item-id="<?php echo $product['item']['pid'] ?>" data-item-type="product">ADD TO CART</button>
						<button type="button" class="buynow" data-href="placeorder.php?buy=product&quantity=1&id=<?php echo $product['item']['pid'] ?>">Buy Now</button>
					</div>
		        </div>
	      	</div>
	      	
	      	<div class="product-order">
		        <div>
		        	<div>
			          	<h3><?php echo $product['item']['pname'] ?></h3>
			          	<a href="shopviewer.php?id=<?php echo $product['item']['sid'] ?>">Seller: <span><?php echo $product['item']['sname'] ?></span></a>
			        </div>
			        <?php
						echo WishlistManager::checkInWishlist($db,$product['item']['pid'],$user->getUID(),"product");
					?>
		        </div>
		        <div>
					<div>
						<a href="#">
							<select class="static-rate">

								<option value="1" <?php if($product['item']['prating']===1)echo 'selected' ?> >1</option>
								<option value="1" <?php if($product['item']['prating']===2)echo 'selected' ?> >2</option>
								<option value="1" <?php if($product['item']['prating']===3)echo 'selected' ?> >3</option>
								<option value="1" <?php if($product['item']['prating']===4)echo 'selected' ?> >4</option>
								<option value="1" <?php if($product['item']['prating']>=5)echo 'selected' ?> >5</option>
							</select>
						</a>
						<a href="#go-to-review">Review</a>
						<a href="#">Go to Store</a>
					</div>
					<div>
						<p><span>uShopper Guarantee</span> Safe and Secure Payments. 100% Authentic and High Quality products</p>
					</div>
		        </div>
				<div>
					<span>Rs.<?php echo $product['item']['pactualprice'] ?> <del><?php echo $product['item']['pmrp'] ?></del><span style="color: #f00;margin-left: 20px;font-size: .8em;"><?php 
								echo round((($product['item']['pmrp']-$product['item']['pactualprice'])/$product['item']['pmrp'])*100); 
							?>% off</span></span>
				</div>
				<div class="quantity-select">
					<label for="quantity">Quantity</label>
					<select id="quantity">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
						<option>6</option>
					</select>
				</div>
				<div class="size-select">
					<span>Size:</span>
					<ul>
						<li><button type="button">M</button></li>
						<li><button type="button">L</button></li>
						<li><button type="button">XL</button></li>
						<li><button type="button">XXL</button></li>
						<li><button type="button">Add Size</button></li>
					</ul>
					<a href="#">Size Chart</a>
				</div>
				<div class="pin-check">
					<ul>
						<li>Delivery</li>
						<li>
							<input type="text" placeholder="Enter PIN"/>
							<input type="button" value="Check"/>
						</li>
						<li>Estimate Delivery</li>
					</ul>
				</div>
				
	      	</div>
	    </section>
	    
	    <?php 
	    	$item_list=json_decode(ProductManager::getSimilarProduct($db,$product['item']['ptag']),true);

	    	if($item_list['success']){
	    ?>
	    <section class="similar-products-display slick-container" data-aos="fade-up" data-aos-once="true">
	    	<div>
	    		<p>More Similar Products you may like</p>
	    	</div>
	    	<div class="product-slider">

    		<?php 
    			foreach ($item_list['list'] as $key => $value) {
    				
    		?>
	    		<div class="slider_item" data-aos="flip-left" data-aos-once="true" >
					
					<div class="item_view">
						<img src="<?php echo $value['photo'][0] ?>" alt="">
						<?php
							echo WishlistManager::checkInWishlist($db,$value['pid'],$user->getUID(),"product");
						?>
					</div>
					<div class="item_detail">
						<a href="<?php echo 'product.php?id='.$value['pid'] ?>" ><?php echo $value['name'] ?></a>
						<span>Rs <?php echo $value['actualprice'] ?><del style="margin-left: 10px;"><?php echo $value['mrp'] ?></del></span>
					</div>
				</div>
			<?php
				}
			?>		
	    	</div>
	    </section>
	    <?php
	    	}
	    ?>

	    <section class="review-qa-product">
	    	
	    	<div class="review-container" id="go-to-review">
	    		
	    		<nav class="qa-tab">
	    			<span>Comments and Review's</span>
	    		</nav>
	    		<section class="comment-body">
	    			
	    			<div class="all-comments">
	    			<?php
	    				$comment=json_decode(ProductManager::getAllComments($db,$product['item']['pid']),true);

	    				if($comment['success']){

	    					foreach ($comment['list'] as $key => $value) {
	    						
	    			?>
	    				<div class="comment-box"  data-aos="fade-up" data-aos-once="true">
	    					<img src="images/img_a.jpg" alt="user">
	    					<div>
	    						<span>
	    							<h5><?php echo $value['uname'] ?></h5>
	    						
	    							<select class="static-rate">
	    								<option value="1" <?php if($value['rate']===1)echo "selected" ?> >1</option>
	    								<option value="2" <?php if($value['rate']===2)echo "selected" ?> >2</option>
	    								<option value="3" <?php if($value['rate']===3)echo "selected" ?> >3</option>
	    								<option value="4" <?php if($value['rate']===4)echo "selected" ?> >4</option>
	    								<option value="5" <?php if($value['rate']===5)echo "selected" ?> >5</option>
	    							</select>
	    						</span>
	    						<p><?php echo $value['comment'] ?></p>
	    					</div>
	    				</div>
	    			<?php
	    					}
	    				}
	    				else{
	    			?>
	    				<div class="no-comment">No reviews or comment on this item</div>
	    			<?php 
	    				}
	    			?>
	    			</div>
	    			<div class="comment-form">
	    				<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
	    					<div>
	    						<input type="text" name="cname" placeholder="Enter your name" required>
	    					</div>
	    					<div>
	    						<input type="email" name="cemail" placeholder="Enter your email"  required>
	    						<small>*Your email will not be published anywhere</small>
	    					</div>
	    					<div>
	    						<textarea name="ctext" placeholder="Comment here...."  required></textarea>
	    					</div>
	    					<div>
	    						<span>Product Rating: </span>
	    						<select class="crate" name="crate" required>
	    							<option selected value="1">1</option>
	    							<option value="2">2</option>
	    							<option value="3">3</option>
	    							<option value="4">4</option>
	    							<option value="5">5</option>
	    						</select>
	    					</div>
	    					<div>
	    						<input type="reset" onclick="$('.crate').barrating('clear')" value="Clear Field's">
	    						<input type="submit" value="Add Comment">
	    					</div>
	    				</form>
	    			</div>	
	    		</section>
	    	</div>
	    </section>


	    <?php 
	    	$item_list=json_decode(ProductManager::getRecentProducts($db,$_SESSION['id']),true);

	    	if($item_list['success']){
	    ?>
	    <section class="similar-products-display slick-container" data-aos="fade-up" data-aos-once="true">
	    	<div>
	    		<p>Your Recent Products</p>
	    	</div>
	    	<div class="product-slider">

    		<?php 
    			foreach ($item_list['list'] as $key => $value) {
    				
    		?>
	    		<div class="slider_item" data-aos="flip-left" data-aos-once="true">
					
					<div class="item_view">
						<img src="<?php echo $value['photo'][0] ?>" alt="">
						<?php
							echo WishlistManager::checkInWishlist($db,$value['pid'],$user->getUID(),"product");
						?>
					</div>
					<div class="item_detail">
						<a href="<?php echo 'product.php?id='.$value['pid'] ?>" ><?php echo $value['pname'] ?></a>
						<span><?php echo $value['pactualprice'] ?></span>
					</div>
				</div>
			<?php
				}
			?>		
	    	</div>
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
  	<script type="text/javascript" src="slick/slick.min.js"></script>
  	<script type="text/javascript" src="js/manager.jquery.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			$(window).scroll(function(){
				
				$('.product-order').scrollTop($(this).scrollTop());

			});

			$('.crate').barrating({
		        theme: 'css-stars',
		        initialRating:0,
		        allowEmpty:false,
		    });

			$("#zoom_02").elevateZoom({
				tint:true,
				responsive:true,
				easing:true,
				zoomWindowWidth:700,
				zoomWindowHeight:400,
				zoomWindowPosition:1,
				zoomWindowOffetx:15,
				zoomWindowOffety:-2,
				cursor:'crosshair',
				tintColour:'#F90',
				tintOpacity:0.5
			});

			$('.product-slider').slick({
				dots: false,
	  	 		infinite: false,
	  			speed: 300,
	  			slidesToShow: 6,
	  			slidesToScroll: 4
			});

		 	$('.comment-form>form').on('submit',function(e){

		 		if(uShopper.isLogin){
		 			
		 		}
		 		else{
		 			e.preventDefault();
		 			$('.loginbut').trigger('click');
		 		}
		 	});


		});
	</script>
</body>
</html>
