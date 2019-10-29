<?php

	session_start();
	
	require_once('fbvendor/autoload.php');

	$fb = new Facebook\Facebook([
  		'app_id'                => '412242725779828',
		'app_secret'            => 'f3a83929fd143747b65c0354927d0bc9',
		'default_graph_version' => 'v2.8',
		'persistent_data_handler'=>'session'
	]);

	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email']; // optional
	$loginUrl = $helper->getLoginUrl('http://localhost/ushopper/login-callback.php', $permissions);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Log In</title>
	<meta name="google-signin-client_id" content="960111736235-n93s022448e5pn6vab44u6hdofh9jthl.apps.googleusercontent.com">
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />
	<link rel="stylesheet" type="text/css" href="css/input_normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/input_demo.css" />
	<link rel="stylesheet" type="text/css" href="css/input_set2.css" />
	<link rel="stylesheet" type="text/css" href="css/loginlight.css">

	<script type="text/javascript" src="engine0/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.min.js"></script>
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	
</head>
<body>
	<script>

	      	function onSignIn(googleUser) {
	        	// Useful data for your client-side scripts:
		        var profile = googleUser.getBasicProfile();

		        // The ID token you need to pass to your backend:
		        var id_token = googleUser.getAuthResponse().id_token;

				var xhr=$.ajax({
					url:"glogin-validate.php",
					method:"POST",
					dataType:"text",
					data:'idtoken=' + id_token
				});

				xhr.done(function(data){
					$(".loadme").fadeToggle(500);
					console.log(data);
					window.parent.location='index.php';
				});

				xhr.fail(function(jqXhr,data,error){
					console.log(error);
					$(".loadme").fadeToggle(500);
				});
		    
	      	};

		    function signOut() {
			    var auth2 = gapi.auth2.getAuthInstance();
			    auth2.signOut().then(function () {
			      console.log('User signed out.');
			    });
			}

		   	window.onbeforeunload = function(e){
      			gapi.auth2.getAuthInstance().disconnect();
    		};

    </script>

	<div class="loginlight">
		<div>
			<div>
				<h3>Login</h3>
				<p>Get access to your Orders, Wishlist and Recommendations</p>
			</div>
		</div>
		<div class="formcontainer">
			<div>
				<form id="lightloginform" action="#" autocomplete="off">
					<span class="input input--nao">
						<input class="input__field input__field--nao" data-validation="email" type="text" id="emailorphone" name="emailorphone" />
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
					echo '<a href="'.$loginUrl.'" target="_top"><img src="images/facebook.png" alt=""/>Login</a>';
					?>
				</div>
				<div class="g-signin2" onclick="$('.loadme').fadeToggle(500);" data-onsuccess="onSignIn"></div>
			</div>
			<div>
				<h3>Don't have a account <a href="#" onclick="window.parent.swapToSignup()">Click here</a></h3>
			</div>
		</div>
	</div>
	<div class="loadme"></div>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

				$.validate();

				$("#lightloginform").submit(function(e){
					e.preventDefault();
					$("input").prop("disabled",true);
					$(".loadme").fadeToggle(500);

					var email=$("#emailorphone").val().trim();
					var pass=$("#password").val().trim();

					var json={
					"email":email,
					"password":pass,
					};

					var request=$.ajax({
						url:"loginself.php",
						method:"POST",
						dataType:"text",
						data:json,
					});

					request.done(function(data){
						
						data=JSON.parse(data);
						if(!data.success){
							
							$("input").prop("disabled",false);
							$(".loadme").fadeToggle(500);
							alert(data.error);
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
					$(this).parent().addClass("input--filled");
				});

		});
	</script>
</body>
</html>