<?php

  session_start();
  $_SESSION = array();
  require_once ('gvendor/autoload.php');
  require_once('dbconfig.php');
  require_once('validate.php');

  $token=$_POST['idtoken'];

  $client = new Google_Client();
  $client->setClientId("960111736235-n93s022448e5pn6vab44u6hdofh9jthl.apps.googleusercontent.com");

  setcookie('token',$token,time() + 60 * 60 * 24 * 30,'/','',false,true);

  $payload = $client->verifyIdToken($token);
  if ($payload) {
    $userid = $payload['sub'];

    $valid=new Validate($payload['email'],'');

    $database=new Database();
    $db=$database->getDbConnection();

    if(!$db){
      $valid->echoResult(false,"DB Connection fail");
    }
    else{
      
      if($valid->checkExistance($db,false)){
        if($valid->getLoginType($db)==="SELF"){

          $_SESSION['email_login']=$payload['email'];
          header('Location:userlogin.php');

        }
        else if($valid->getLoginType($db)==="GOOGLE"){
          setupGUser($valid->getUser($db));
        }
      }
      else{
        $email=$payload['email'];
        //$uid=$payload['sub'];
        $name=$payload['name'];
        $image=$payload['picture'];

        if($valid->createFbUser($db,$email,$name,$image,"GOOGLE")){
          
            setupGUser($valid->getUser($db));
        }
        else{
          $valid->echoResult(false,mysqli_error($db));
        }
      }
    }
    $database->closeDb();

    
  } else {
    // Invalid ID token
  }

  function setupGUser($payload){
    setcookie('name',$payload['name'],time() + 60 * 60 * 24 * 30,'/','',false,true);
    setcookie('id',$payload['id'],time() + 60 * 60 * 24 * 30,'/','',false,true);
    setcookie('email',$payload['email'],time() + 60 * 60 * 24 * 30,'/','',false,true);
    setcookie('type',$payload['type'],time() + 60 * 60 * 24 * 30,'/','',false,true);
    // If request specified a G Suite domain:
    //$domain = $payload['hd'];
  }
?>