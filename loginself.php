<?php
	
	require_once('dbconfig.php');
	require_once('validate.php');


	if(($_SERVER["REQUEST_METHOD"]=="POST")&&isset($_POST["email"])&&isset($_POST["password"])){
		

		if(!empty($_POST["email"])&& !empty($_POST["password"])){

			
			$valid=new Validate($_POST["email"],$_POST["password"]);

			//echo $valid->verifyEmail();

		
			$database=new Database();
			$db=$database->getDbConnection();

			if(!$db){
				$valid->echoResult(false,"DB Connection fail");
			}
			else{
				if($valid->checkUserWithPass($db)){
					 $valid->echoResult(true,'Connected');
				}
				else{
					 $valid->echoResult(false,'User Does not Exist');					
				}
			}
			$database->closeDb();
		
		}
	}
?>