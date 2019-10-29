<!DOCTYPE html>
<html>
<head>
	<title>eTailors</title>
	<link rel="stylesheet" type="text/css" href="../../css/header.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/footer.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/account.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/pace.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/featherlight.min.css" />

	<style type="text/css">
		.account-navigation>div:nth-child(5)>ul>li:nth-child(2)>a{
		  background-color: #34c24d11;
		  color: #222;
		  border-left: 2px solid #34c24d;
		}
	</style>

	<script type="text/javascript" src="../../engine0/jquery1.11.2.js"></script>
	<script type="text/javascript" src="../../js/featherlight.min.js"></script>
	<script src="../js/pace.min.js"></script>
</head>
<body>
	<header>
		<div class="subHead">
			<ul>
				<li>Download App</li>
				<li>Customer Care</li>
			</ul>
		</div>
		<div class="subHead">
			<ul>
				<li>
					<span>open</span>
					<nav>				
						<ul>
							<li><a href="#">Grocery</a></li>
							<li><a href="#">Electronics</a></li>
							<li><a href="#">Mens Fashion</a></li>
							<li><a href="#">Womens Fashion</a></li>
							<li><a href="#">Sports</a></li>
							<li><a href="#">Books</a></li>
						</ul>
					</nav>				
				</li>
				<li>uShopper</li>
				<li>logo</li>
				<li>
					<input type="text" name="searchBar" placeholder="Search for your product "/>
					<input type="button" name="searchBut" value="->"/>
					<select>
						<option value="sbyproduct">By Product</option>
						<option value="sbyshop">By Shop</option>
					</select>
				</li>
				<li>
					<input type="button" name="cartButton" value="Cart"/>
					<span id="cartCounter">0</span>
				</li>
				<li>
					<div class="user-drop">
						<span>SignIn<img src="images/img_a.jpg" width="50px" height="50px" alt="user"/></span>
						<div class="login-content">
							<a href="#">Your Account</a>
							<a href="#">Your Orders</a>
							<a href="#">Wishlist</a>
							<div>
								<button type="button">Register</button>
								<button type="button" data-featherlight=".loginlight">LogIn</button>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</header>
	<main>
		<div class="page-navigation">Navigation</div>
		<section>
			<div class="account-navigation">
				<div>My Account</div>
				<div>
					<img src="../images/img_a.jpg" alt="user-pic" width="50px" height="50px"/>
					<h3>User Name</h3>
					<span>Email Address</span>
				</div>
				<div>
					<h3>YOUR HISTORY</h3>
					<ul>
						<li><a href="myorders.html">Orders</a></li>
						<li><a href="mywishlist.html">Wishlist</a></li>
						<li><a href="savedstores.html">Saved Stores</a></li>
					</ul>
				</div>
				<div>
					<h3>SETTINGS</h3>
					<ul>
						<li><a href="../myaccount.html">Personnel Setting's</a></li>
						<li><a href="changepassword.html">Change Password</a></li>
						<li><a href="addresses.html">Addresses</a></li>
						<li><a href="profilesetting.html">Profile</a></li>
						<li><a href="accountmailupdate.html">Update Email/Mobile</a></li>
						<li><a href="deactivate.html">Deactivate Account</a></li>
					</ul>
				</div>
				<div>
					<h3>PAYMENTS</h3>
					<ul>
						<li><a href="loyalitypoints.html">Loyality Points</a></li>
						<li><a href="#">My Saved Cards</a></li>
					</ul>
				</div>
			</div>
			<div class="account-category">
				<div class="cat-head">
					<h3>SAVED CARDS</h3>
				</div>
				<div class="cat-body"></div>
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
</body>
</html>
