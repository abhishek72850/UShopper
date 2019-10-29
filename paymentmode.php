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
		
		$user=new WebUser(substr(session_id(),0,20),WebUser::TYPE_GUEST);
		$_SESSION['id']=$user->getUID();
		$_SESSION['name']='Sign In';
	}

	session_write_close();
?>
<!DOCTYPE html>
<html>
<head>
	<title>uShopper</title>
	<link rel="stylesheet" type="text/css" href="css/paymentmode.css"/>
	
	<script type="text/javascript" src="engine0/jquery.js"></script>
	
</head>
<body>
	<header>
		<h4>SELECT YOUR PAYMENT MODE</h4>
		<p>Please select one of the options given below , either choose paypal online payement gateway or COD( Cash on Delivery)</p>
	</header>
	<main>
		<div>
			<div>
				<input type="radio" id="paypalpay" value="paypal" name="paymode">
				<label for="paypalpay">PayPal</label>	
			</div>
			<div>
				<input type="radio" id="codpay" value="cod" name="paymode">
				<label for="codpay">Cash On Delivery</label>	
			</div>
		</div>
	</main>
	<footer>
		<button id="cancel">Cancel</button>
		<button id="proceed">Proceed</button>
	</footer>

	<script type="text/javascript">
		$(document).ready(function(){

			$('input[type="radio"]').on('click',function(){

				window.parent.uShopper.order.payment.paymode=this.value;
			});

			$('#proceed').on('click',function(){

				if(window.parent.uShopper.order.payment.paymode!='cod'&&window.parent.uShopper.order.payment.paymode!='paypal'){
					alert('Please Select Payment Mode');
					return;
				}

				var address=jQuery.param(window.parent.uShopper.order.address);
				var item=jQuery.param(window.parent.uShopper.order.item);
				var payment=jQuery.param(window.parent.uShopper.order.payment);

				window.parent.location='validateorder.php?'+address+'&'+item+'&'+payment;
			});

			$('#cancel').on('click',function(){
				window.parent.User.feather.close();
			});


		});
	</script>

</body>
</html>