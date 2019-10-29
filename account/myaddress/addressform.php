<?php
	session_start();

	require_once('../../fbvendor/autoload.php');
	require_once('../../gvendor/autoload.php');
	require_once('../../dbconfig.php');
	require_once('../../webuser.php');
	require_once('../../validate.php');
	require_once('../../myvendor/usermanager.php');

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

		if(isset($_GET['type'])&&isset($_GET['aid'])&&$_GET['type']==='edit'){
			$data=json_decode(UserManager::getAddress($db,$_GET['aid']),true);
			if($data['success'])
				$address=$data['address'];
		}
		else if(isset($_GET['type'])&&$_GET['type']==='add'){

		}
	}
	else{
		header('location:../');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="../../css/input_normalize.css" />
	<link rel="stylesheet" type="text/css" href="../../css/input_demo.css" />
	<link rel="stylesheet" type="text/css" href="../../css/input_set2.css" />
	<link rel="stylesheet" type="text/css" href="../../css/addressform.css"/>

	<script type="text/javascript" src="../../engine0/jquery.js"></script>
	
</head>
<body>
	<form action="#">
		<span class="input input--nao">
			<input class="input__field input__field--nao" required type="text" id="addname" name="addname" value="<?php if(isset($address))echo $address['name'] ?>" />
			<label class="input__label input__label--nao" for="addname">
				<span class="input__label-content input__label-content--nao">Name*</span>
			</label>
			<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
				<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
			</svg>
		</span>
		<span class="input input--nao">
			<textarea class="input__field input__field--nao"  required id="streetadd" name="streetadd" cols="3" rows="3"><?php if(isset($address))echo $address['street_address'] ?></textarea>
			<label class="input__label input__label--nao" for="streetadd">
				<span class="input__label-content input__label-content--nao">Street Address*</span>
			</label>
			<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
				<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
			</svg>
		</span>
		<span class="input input--nao">
			<input class="input__field input__field--nao"  required type="text" id="addlandmark" name="addlandmark" value="<?php if(isset($address))echo $address['landmark'] ?>" />
			<label class="input__label input__label--nao" for="addlandmark">
				<span class="input__label-content input__label-content--nao">LandMark*</span>
			</label>
			<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
				<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
			</svg>
		</span>
		<div class="defined">
			<span class="input input--nao">
				<input class="input__field input__field--nao" required  type="text" id="addcity" name="addcity" value="Allahabad" disabled />
				<label class="input__label input__label--nao" for="addcity">
					<span class="input__label-content input__label-content--nao">City*</span>
				</label>
				<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
					<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
				</svg>
			</span>
			<span class="input input--nao">
				<input class="input__field input__field--nao"  required type="text" id="addstate" name="addstate" value="Uttar Pradesh" disabled />
				<label class="input__label input__label--nao" for="addstate">
					<span class="input__label-content input__label-content--nao">State*</span>
				</label>
				<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
					<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
				</svg>
			</span>
			<span class="input input--nao">
				<input class="input__field input__field--nao"  required type="text" id="addcountry" name="addcountry" value="India" disabled />
				<label class="input__label input__label--nao" for="addcountry">
					<span class="input__label-content input__label-content--nao">Country*</span>
				</label>
				<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
					<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
				</svg>
			</span>
		</div>
		<span class="input input--nao">
			<input class="input__field input__field--nao" type="text" required  id="addpincode" name="addpincode" value="<?php if(isset($address))echo $address['pincode'] ?>" />
			<label class="input__label input__label--nao" for="addpincode">
				<span class="input__label-content input__label-content--nao">Pincode*</span>
			</label>
			<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
				<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
			</svg>
		</span>
		<span class="input input--nao">
			<input class="input__field input__field--nao" type="text"  required id="addphone" name="addphone" value="<?php if(isset($address))echo $address['mobile'] ?>" />
			<label class="input__label input__label--nao" for="addphone">
				<span class="input__label-content input__label-content--nao">Phone Number*</span>
			</label>
			<svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
				<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
			</svg>
		</span>
		<div>
			<input type="submit" value="SAVE" name="saveadd"/>
		</div>
	</form>
	<div class="loadme"></div>
	<script type="text/javascript">
  	
  	$(document).ready(function(){

			/*$('form').on('submit',function(e){
				e.preventDefault();
				var reg=/[^a-z]/i;

			});*/

			$('form').submit(function(e){
				e.preventDefault();
				$("input").prop("disabled",false);
				$(".loadme").fadeToggle(500);

				var json={
					'id':'<?php echo $_SESSION['id'] ?>',
					'email':'<?php echo $_SESSION['email'] ?>',
					'type':window.parent.User.type+'_adr',
					'aid':'<?php if(isset($address))echo $address["aid"] ?>',
					'name':$('#addname').val().trim(),
					'stadr':$('#streetadd').val().trim(),
					'landmark':$('#addlandmark').val().trim(),
					'pin':$('#addpincode').val().trim(),
					'mobile':$('#addphone').val().trim()
				};

				var request=$.ajax({
					url:"../../android/user/usermanager.php",
					method:"POST",
					dataType:"text",
					data:json
				});

				request.done(function(data){
					//console.log(data);
					data=JSON.parse(data);
					if(!data.success){
						$("input").prop("disabled",false);
						$(".loadme").fadeToggle(500);
						alert(data.error);
					}
					else{
						$("input").prop("disabled",true);
						window.parent.User.finish();
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

			$('.input__field').trigger("change");

		});
	</script>

</body>
</html>