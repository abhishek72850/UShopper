<?php

	

if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // include db connect class
    require_once ('../dbconfig.php');
    require_once('validate.php');
    require_once("userparser.php");

    // connecting to db
    $database = new Database();
    $db=$database->getDbConnection();

    $validate=new Validate($email,$password);


    if($validate->checkExistance($db,false)){
      	$response=$validate->verifyUser($db);

		if($response["success"]){
			UserParser::getUser($db,$response["uid"]);
		}
		else{
			$validate->echoResult(false,'User Verify User');
		}
      
    }
    else{
      $validate->echoResult(false,'User Does not Exist');
    }
  }

?>
