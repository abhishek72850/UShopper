<?php
	session_start();

	try{
		require_once('fbvendor/autoload.php');
		require_once('gvendor/autoload.php');
		require_once('dbconfig.php');
		require_once('webuser.php');
		require_once('myvendor/cartmanager.php');
		require_once('myvendor/productmanager.php');
		require_once('myvendor/ordermanager.php');
		require_once('myvendor/usermanager.php');
		require_once('myvendor/variablecheck.php');
	}
	catch(Exception $e){
		echo $e->getCode();
	}


	$user=null;
	$db=null;
	$errorLog=array();

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

	//var_dump($_GET);

	$errorLog=array();


	function validateOrder($db,$user,$param=array())
	{
		$valid=true;

		if($param['adrType']==='LIST'){
			if(!UserManager::checkAddress($db,$user->getUID(),$param['adrId'])){
				$errorLog[0]='Given Address of delivery is not valid';
				return false;
			}
		}
		else if($param['adrType']==='MAP'){
			if(!(strstr(strtolower($param['adrString']),'allahabad')?true:false)){
				$errorLog[1]='MAP Address is Not Valid';
				return false;
			}
		}
		else{
			return false;
		}


		if($param['itemType']==='cart'){

			$result=CartManager::checkCartStock($db,$user->getUID());

			if($result===false){
				$errorLog[2]='There is no item in your cart';
				return false;
			}  
			else{

				foreach ($result as $key => $value) {
					if($value['instock']===false){
						$errorLog[3]=$value['pname'].' is '.$value['status'];
						$valid=false;
					}
				}
				if(!$valid){
					return false;
				}
			}
		}
		else if($param['itemType']==='product'){

			$result=CartManager::checkProductStock($db,$param['itemId'],$param['iquantity']);

			if(!$result[0]){
				$errorLog[4]=$result[2].' '.$result[1];
				return false;
			}
		}
		else{
			return false;
		}

		return true;
	}

	if(VariableCheck::isUnKnown($_GET,array('adrType','adrID','adrlat','adrlng','adrString','itemType','itemId','iquantity','paymode'))){

		$param=array('adrId'=>'','adrType'=>'','adrlat'=>0,'adrlng'=>0,'adrString'=>'','itemType'=>'','itemId'=>'','iquantity'=>0,'paymode'=>'');

		if(isset($_GET['adrType'])){
			$param['adrType']=$_GET['adrType'];
		}
		if(isset($_GET['adrID'])){
			$param['adrId']=$_GET['adrID'];
		}
		if(isset($_GET['adrlat'])){
			$param['adrlat']=$_GET['adrlat'];
		}
		if(isset($_GET['adrlng'])){
			$param['adrlng']=$_GET['adrlng'];
		}
		if(isset($_GET['adrString'])){
			$param['adrString']=$_GET['adrString'];
		}
		if(isset($_GET['itemType'])){
			$param['itemType']=$_GET['itemType'];	
		}
		if(isset($_GET['itemId'])){
			$param['itemId']=$_GET['itemId'];
		}
		if(isset($_GET['iquantity'])){
			$param['iquantity']=$_GET['iquantity'];
		}
		if(isset($_GET['paymode'])){
			$param['paymode']=$_GET['paymode'];
		}

		if(isset($_GET['paymode'])&&$_GET['paymode']==='cod'){

			$result=validateOrder($db,$user,$param);

			//var_dump($result);

			if($result){
				$orderID=OrderManager::generateOrderID($user->getUID());
				
				if(OrderManager::placeOrder($db,$user->getUID(),$orderID,$param)){

					header('location:orderplaced.php?oid='.$orderID);
				}
				else{
					echo "Unable to Generate ORDER ID";
				}
			}
			else{
				echo "No Product";
				var_dump($errorLog);
			}

		}
		else if(isset($_GET['paymode'])&&$_GET['paymode']==='paypal'){

		}
		else{

		}
	}
	else{

	}
?>