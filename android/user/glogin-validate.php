<?php

    require_once('../dbconfig.php');
    require_once('validate.php');
    require_once('userparser.php');

  if(isset($_POST['email'])&&isset($_POST['type'])&&isset($_POST['name'])&&isset($_POST['photo'])){

    $valid=new Validate($_POST['email'],'');

    $database=new Database();
    $db=$database->getDbConnection();

    if(!$db){
      $valid->echoResult(false,"DB Connection fail");
    }
    else{
      
      if($valid->checkExistance($db,false)){
       
        if($valid->getLoginType($db)==="SELF"){
          
          UserParser::getUser($db,$valid->getUid());
        }
        else if($valid->getLoginType($db)==="GOOGLE"){
          
          UserParser::getUser($db,$valid->getUid());
        }
        else if($valid->getLoginType($db)==="FB"){
          
          UserParser::getUser($db,$valid->getUid());
        }
      }
      else{
        $email=$_POST['email'];
        $name=$_POST['name'];
        $image=$_POST['photo'];
        $type=$_POST['type'];
        
        if($valid->createFbUser($db,$email,$name,$image,$type)){
          
          if(mysqli_affected_rows($db)!==0){
           
            UserParser::getUser($db,$valid->getUid());        
          }
          else
            $valid->echoResult(false,'No Data Found glongin-validate.php line 40');
        }
        else{
            $valid->echoResult(false,mysqli_error($db));
        }
      }
    }
    $database->closeDb();

  }
?>
