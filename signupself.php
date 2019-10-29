<?php
	
	require_once('dbconfig.php');
	require_once('validate.php');
	

	if(($_SERVER["REQUEST_METHOD"]=="POST")&&isset($_POST["email"])&&isset($_POST["password"])&&isset($_POST["cnfpassword"])){
		

		if(!empty($_POST["email"])&& !empty($_POST["password"])&& !empty($_POST["cnfpassword"])){
			

			$valid=new Validate($_POST["email"],$_POST["password"],$_POST["cnfpassword"]);

			//echo $valid->verifyEmail();

			if($valid->verifyPassword()){
				$database=new Database();
				$db=$database->getDbConnection();
				if(!$db){
					echo "Error: DB Connection fail";
				}
				else{
					$valid->createUser($db);
				}
				$database->closeDb();
			}
			else{
				$valid->echoResult(false,"Password Validation Failed");
			}
		}
	}
?>