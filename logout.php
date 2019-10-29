<?php
	session_start();

	$_SESSION = array();
	$_COOKIE = array();

	  setcookie('name','',time() -3000,'/','',false,true);
  	setcookie('id','',time() -3000,'/','',false,true);
  	setcookie('email','',time() -3000,'/','',false,true);
  	setcookie('type','',time() -3000,'/','',false,true);
  	setcookie('token','',time() -3000,'/','',false,true);

  	session_destroy();
    session_write_close();

    if(isset($_GET['refrer']))
  	  header("Refresh:1;URL=".$_GET['refrer']);
    else
      header("Refresh:1;URL=./");
?>