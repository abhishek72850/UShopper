<?php
	session_start();

	require_once('fbvendor/autoload.php');
	require_once ('gvendor/autoload.php');
	require_once('dbconfig.php');
	require_once('webuser.php');
	require_once('myvendor/cartmanager.php');
	require_once('myvendor/wishlistmanager.php');
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
		
		// $user=new WebUser(substr(session_id(),0,20),WebUser::TYPE_GUEST);
		// $_SESSION['id']=$user->getUID();
		// $_SESSION['name']='Sign In';
		
	}

	session_write_close();

	if(VariableCheck::isUnKnown($_GET,array("buy","id","quantity"))){

		if($_GET['buy']==="product"&&isset($_GET['id'])&&isset($_GET['quantity'])){
			$data=json_decode(CartManager::getBuyProduct($db,$_GET['id'],$_GET['quantity']),true);
		}
		else if($_GET['buy']==="cart"){
			$data=json_decode(CartManager::getCart($db,$_SESSION['id']),true);
		}
	}
	else{

	}

	if(!$data['success']){
		echo "<script>alert('".$data['error']."');</script>";
		//header('location:index.php');
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
	<link rel="stylesheet" type="text/css" href="css/placeorder.css"/>
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
			isLogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			notify:null,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>',
			order:{
				address:{},
				item:{
					itemType:"<?php echo $_GET['buy'] ?>",

					itemId:'<?php if($_GET['buy']==='product')echo $data["list"][0]["pid"] ?>',
					iquantity:'<?php if($_GET['buy']==='product')echo $_GET["quantity"] ?>'
				},
				payment:{
					paymode:''
				},
				orderID:''
			},
			stage:{
				addrSelect:false
			}
		};

		var User={
			type:null,
			adrId:'',
			feather:null,
			finish:function(){
				User.feather.close();
				window.location.reload();
			}
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
		<section class="order-step">
			<div><span>1</span>SET ORDER DETAIL</div>
			<div><span>2</span>PAYMENT MODE</div>
			<div><span>3</span>ORDER CONFIRMED</div>
		</section>
		<section class="address-detail">
			<div>SELECT ADDRESS OF DELIVERY</div>
			<div>
				<div>
					<p>Your Addresses:</p>
					<ul>
						<?php
							$address=json_decode(UserManager::getUserAddress($db,$user->getUID()),true);

							if($address['success']){
								foreach ($address['list'] as $key => $value) {
								
						?>
						<li><span class="addr-select" data-addr-id="<?php echo $value['id'] ?>"><?php echo ucwords($value['name']) ?>,<?php echo ucfirst($value['stadr']) ?></span></li>
						<?php
								}
							}
						?>
						<li class="add-address-but">Add</li>
					</ul>
				</div>
				<div class="addr-edit-view">
					<div class="load-address"></div>
					<div>
						<label>Name</label>
						<input type="text" class="clear_me save_me" name="addr_name">
					</div>
					<div>
						<label>Street Address</label>
						<input type="text" class="clear_me save_me" name="addr_stradr">
					</div>
					<div>
						<label>Landmark</label>
						<input type="text" class="clear_me save_me" name="addr_landmark">
					</div>
					<div>
						<label></label>
						<input type="text" readonly disabled value="Allahabad" >
						<input type="text" readonly disabled value="UP">
					</div>
					<div>
						<label>Mobile</label>
						<input type="text" class="clear_me save_me" name="addr_mobile">
					</div>
					<div>
						<label>Pincode</label>
						<input type="text" class="clear_me save_me" name="addr_pincode">
					</div>
					<input type="hidden" id="addr_edit_id">
					<div>
						<button id="addr_edit">Edit</button>
						<button id="addr_clear">Clear</button>
						<button id="addr_save">Save Changes</button>
					</div>
				</div>
			</div>
			<div><span>OR</span></div>
			<div>
				<div class="map-note">
					<h4>Delivered At Map System</h4>
					<p><b>Note: </b>This System is still in beta version and there maybe chances that it cant fetch your desired location. Since the delivery service is only valid in <b>Rural Areas of Allahabad</b> any other <b>Remote Areas or Outside the Allahabad region</b> is selected will automatically causes the order cancellation without any notice to the user.</p>

					<b>Click on the MAP to select the address. Selected Address detail will be displayed below the MAP.</b>

					<div><input type="checkbox" id="map-terms" name="map-terms"><label for="map-terms">I Accept the Terms and Condition</label></div>
				</div>
				<div>
					<button onclick="initMap()">Load MAP</button>
					<div id="map"></div>
					<div class="map-address">
						<p>
							<span>Latitude: </span><span id="map-lat"></span>
						</p>
						<p>
							<span>Longitude: </span><span id="map-lng"></span>
						</p>
						<address>
							<b>Marked Address: </b>
							<p id="map-adr"></p>
						</address>
					</div>
				</div>
			</div>

		</section>
		<section class="order-detail">
			<div>ORDER DETAIL</div>
			<div>

				<span>
				<?php
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
									<?php 
										$stock=json_decode(CartManager::checkStock($db,$user->getUID(),$value['pid'],$value['cartquantity']),true); 
									
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
								<a href="javascript:void(0)" data-task="cart_remove" class="remove" data-item-id="<?php echo $value['pid'] ?>" data-task-command="delete">Remove</a>
							</td>
							<td>

								<select class="quantity cartq" data-task="cart_update" data-task-command="update" data-item-id="<?php echo $value['pid'] ?>">
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
							<td colspan="6" class="loadme<?php echo $value['pid'] ?>"></td>
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
								<input type="button" id="goforpay" data-total-price='<?php echo $total ?>' value="Proceed to pay Rs. <?php echo $total ?>"/>
							</td>
						</tr>
					</tfoot>
					<?php
						}
					?>
				</table>
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
	<script>
	    var map;
	    function initMap() {

	    	if(!document.getElementById('map-terms').checked){
	    		alert('Please Accept the terms and condition of MAP');
	    		return;
	    	}

	    	var cords={lat: 25.4358, lng: 81.8463};
	    	map = new google.maps.Map(document.getElementById('map'), {
	        	center: cords,
	        	zoom: 13,
	        	mapTypeId: google.maps.MapTypeId.ROADMAP
	        });
	        var marker = new google.maps.Marker({
	        	position: cords,
	        	map: map
	        });

	        var bounds = new google.maps.LatLngBounds(new google.maps.LatLng(24.814126608056398,80.07475458984368),new google.maps.LatLng(26.054281817619305,83.61784541015618));

	        var geocoder = new google.maps.Geocoder;


	        map.addListener('click',function(e){
	        	marker.setPosition(new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()));
	        	$('#map-lat').text(e.latLng.lat());
	        	$('#map-lng').text(e.latLng.lng());
	        	
	        	geocodeLatLng(geocoder,map,e.latLng.lat(), e.latLng.lng());
	        	
	        });

	        map.addListener('bounds_changed',function(e){
	        	
	        	if (bounds.contains(map.getCenter())) return;

			    var c = map.getCenter(),
		        x = c.lng(),
		        y = c.lat();

			    map.setCenter(new google.maps.LatLng(y, x));
	        });
	    }

	    function geocodeLatLng(geocoder, map,lat,lng) {
	        
	        var latlng = {lat, lng};
	        
	        geocoder.geocode({'location': latlng}, function(results, status) {
	          	
	          	if (status === 'OK') {
	            	if (results[1]) {
	              		//console.log(results[1]);
	              		//console.log(results[1].formatted_address);
	              		$('#map-adr').text(results[1].formatted_address);

			        	uShopper.order.address=$.extend({},uShopper.order.address,{
			        		adrType:'MAP',
			        		adrID:results[1].place_id,
			        		adrlat:lat,
			        		adrlng:lng,
			        		adrString:results[1].formatted_address
			        	});
	              		
	            	} else {
	              		alert('No results found');
	            	}
	          	} else {
	            	alert('Geocoder failed due to: ' + status);
	          	}
	        });

	        return 0;
	    }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCxdAJfjpBW6b9rYCaiTV6HrnsRCL2mMOk"
    async defer></script>

	<script type="text/javascript">
		$(document).ready(function(){

			$('#goforpay').on('click',function(){

				if(uShopper.order.address.adrType!='LIST'&&uShopper.order.address.adrType!='MAP'){
					triggerError('Please Select Address');
					return;
				}
				if(uShopper.order.item.itemType!='cart'&&uShopper.order.item.itemType!='product'){
					triggerError('Item not Specified');
					return;
				}

				User.feather=$.featherlight({iframe: 'paymentmode.php',loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 400,iframeHeight: 300});
			});

			var triggerError=function(error){
				alert(error);
			}

			$('.remove,.quantity').on('click change',function(e){

	  			if(uShopper.isLogin){

	  				<?php if($user->getType()!==WebUser::TYPE_GUEST){ ?>

	  				if($(this).hasClass('quantity')&&e.type==="click"){
	  					return;
	  				}
	  				if($(this).hasClass('remove')&&e.type==="change"){
	  					return;
	  				}

	  				if(uShopper.order.item.itemType==='product'&&$(this).hasClass('remove')){
	  					window.location='index.php';
	  				}

	  				if(uShopper.order.item.itemType==='product'&&$(this).hasClass('quantity')){
	  					uShopper.order.item.quantity=$(this).context.value;
	  					noty({
							text:'<div class="activity-item"><div class="activity">Quantity Updated Successfully</div> </div>',
							layout:"topCenter",
							type:'success',
							progressBar:true,
							animation: {
						    	open: 'animated bounceInLeft', 
						    	close: 'animated bounceOutLeft', 
					    		easing: 'swing',
						    	speed: 500 
							}
						}).setTimeout(2000);
	  					return;
	  				}

					$('td.loadme'+this.dataset.itemId).fadeToggle(500);

		  			$(this).manager({
		  				task:this.dataset.task,
			  			url:'android/user_product/cartmanager.php',
			  			data:{
			  				"id":"<?php if(isset($_SESSION['id']))echo $_SESSION['id'] ?>",
			  				"email":"<?php if(isset($_SESSION['email']))echo $_SESSION['email'] ?>",
			  				"data":this.dataset.taskCommand,
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


			var lightupFrame=function(type,aid){
				User.feather=$.featherlight({iframe: 'account/myaddress/addressform.php?type='+type+'&aid='+aid,loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 400,iframeHeight: 450});
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


			$('.addr-select').on('click',function(){

				$(this).parent().css({'background-color':'rgb(250,150,0)','color':'#fff'});
				
				uShopper.order.address=$.extend({},uShopper.order.address,{
	        		adrType:'LIST',
	        		adrID:this.dataset.addrId
	        	});

				$('.load-address').css('visibility','visible');

				var json={
					"id":this.dataset.addrId,
					"todo":"get_addr"
				};

				var request=$.ajax({
					url:"myvendor/usermanager.php",
					method:"POST",
					dataType:"text",
					data:json,
				});

				request.done(function(data){
					$('.load-address').css('visibility','hidden');
					//console.log(data);
					data=JSON.parse(data);
					if(data.success){

						$('.addr-edit-view>div:not(:first-child)').css('visibility','visible');

						$('input[name="addr_name"]').val(data.address.name);
						$('input[name="addr_stradr"]').val(data.address.street_address);
						$('input[name="addr_landmark"]').val(data.address.landmark);
						$('input[name="addr_mobile"]').val(data.address.mobile);
						$('input[name="addr_pincode"]').val(data.address.pincode);
						$('#addr_edit_id').val(data.address.aid);

						$('.save_me').prop('disabled',true);

						$('#addr_edit').prop('disabled',false);
						$('#addr_clear').prop('disabled',true);
						$('#addr_save').prop('disabled',true);
					}
					else{

					}
				});
			});

			$('#addr_edit').on('click',function(){

				$(this).prop('disabled',true);
				$('#addr_clear').prop('disabled',false);
				$('#addr_save').prop('disabled',false);
				$('.save_me').prop('disabled',false);
			});

			$('#addr_clear').on('click',function(){

				$('.clear_me').val('');
			});

			$('#addr_save').on('click',function(){

				var json={
					"todo":"update_addr",
					"id":$('#addr_edit_id').val(),
					"name":$('input[name="addr_name"]').val(),
					"str_adr":$('input[name="addr_stradr"]').val(),
					"landmark":$('input[name="addr_landmark"]').val(),
					"mobile":$('input[name="addr_mobile"]').val(),
					"pincode":$('input[name="addr_pincode"]').val()
				};

				var request=$.ajax({
					url:"myvendor/usermanager.php",
					method:"POST",
					dataType:"text",
					data:json,
				});

				request.done(function(data){
					//console.log(data);
				});
			});
		});
	</script>
</body>
</html>