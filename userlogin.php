<?php
	session_start();
	require_once('fbvendor/autoload.php');

	$fb = new Facebook\Facebook([
  		'app_id'                => '412242725779828',
		'app_secret'            => 'f3a83929fd143747b65c0354927d0bc9',
		'default_graph_version' => 'v2.8',
	]);

	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email']; // optional
	$loginUrl = $helper->getLoginUrl('http://localhost/ushopper/login-callback.php', $permissions);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign in</title>
	<meta name="google-signin-client_id" content="960111736235-n93s022448e5pn6vab44u6hdofh9jthl.apps.googleusercontent.com">
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="css/input_normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/input_demo.css" />
	<link rel="stylesheet" type="text/css" href="css/input_set2.css" />
	<link rel="stylesheet" type="text/css" href="css/loginlight.css">
	<link rel="stylesheet" type="text/css" href="css/signinheader.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css"/>
	<link rel="stylesheet" href="css/pace.css"/>

	<script type="text/javascript" src="engine0/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>

	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<script src="js/pace.min.js"></script>
</head>
<body>
	<script>
      function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
	        var profile = googleUser.getBasicProfile();

	        // The ID token you need to pass to your backend:
	        var id_token = googleUser.getAuthResponse().id_token;

	        var xhr = new XMLHttpRequest();	
			xhr.open('POST', 'glogin-validate.php');
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onload = function() {
			  window.parent.location='index.php';
			};
			xhr.send('idtoken=' + id_token);
	    
      };
      function signOut() {
	    var auth2 = gapi.auth2.getAuthInstance();
	    auth2.signOut().then(function () {
	      console.log('User signed out.');
	    });
	  }

    </script>
	<header>
		<a href="index.php">Go to Home</a>
		<div>
			<h3>uShopper</h3>
		</div>	
	</header>
	<main>
		<section>
			<div>
				<form id="userloginform" action="#">
					<span class="input input--nao">
						<input class="input__field input__field--nao" data-validation="email" type="text" id="emailorphone" name="emailorphone" 
						<?php
							echo "value='".$_SESSION['email_login']."'";
						?>
						/>
						<label class="input__label input__label--nao" for="emailorphone">
							<span class="input__label-content input__label-content--nao">Email or Phone</span>
						</label>
						<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
							<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
						</svg>
					</span>
					<span class="input input--nao">
						<input class="input__field input__field--nao" maxlength="15" data-validation="length" data-validation-length="6-15" type="password" name="password" id="password" />
						<label class="input__label input__label--nao" for="password">
							<span class="input__label-content input__label-content--nao">Password</span>
						</label>
						<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
							<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
						</svg>
					</span>
					<div>
						<a href="#">forgot password?</a>
						<input type="submit" value="Log in"/>
					</div>
				</form>
			</div>
			<div>
				<h3>OR</h3>
				<div class="fb-login"> 
					<?php
						echo '<a href="' . $loginUrl . '" target="_self"><img src="images/facebook.png" alt=""/>Login</a>';
					?>
				</div>
				<div class="g-signin2" data-width="150" data-height="40" data-onsuccess="onSignIn">
				</div>
			</div>
			<div>
				<h3>Don't have a account <a href="#" id="signup">Click here</a></h3>
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
	<div class="loadme"></div>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			
				$.validate();

				$("#userloginform").submit(function(e){
					e.preventDefault();
					$("input").prop("disabled",true);
					$(".loadme").fadeToggle(500);

					var email=$("#emailorphone").val().trim();
					var pass=$("#password").val().trim();

					var json={
					"email":email,
					"password":pass,
					};

					console.log(json);

					var request=$.ajax({
						url:"loginself.php",
						method:"POST",
						dataType:"text",
						data:json,
					});

					request.done(function(data){
						console.log(data);
						data=JSON.parse(data);
						if(!data.success){
							
							$("input").prop("disabled",false);
							$(".loadme").fadeToggle(500);
						}
						else{
							$("input").prop("disabled",true);
							window.parent.location="index.php";
						}
					});
					request.fail(function(jqXhr,data,error){
						console.log(error);
					});
				});

				$('#signup').on("click",function(){
			 		$.featherlight({iframe: 'signupframe.php', loading:'Please wait...',iframeMaxWidth: '100%', iframeWidth: 500,iframeHeight: 400});
			 	});

				$(".input__field").on("change",function(){
					
					if($(this).val()!=='')
					{
						$(this).parent().addClass("input--filled");
					}

				});


				$(".input__field").on("blur",function(){
					if($(this).val()===''){
						$(this).parent().removeClass("input--filled");
					}
				});
				$(".input__field").on("focus",function(){
					console.log($(this).val());
					$(this).parent().addClass("input--filled");
				});

				$(".input__field").trigger('focus');
		});
	</script>
</body>
</html>