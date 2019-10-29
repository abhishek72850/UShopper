<?php
	session_start();	
	
	require_once('fbvendor/autoload.php');
	require_once('dbconfig.php');
	require_once('validate.php');

	$fb = new Facebook\Facebook([
  		'app_id'                => '412242725779828',
		'app_secret'            => 'f3a83929fd143747b65c0354927d0bc9',
		'default_graph_version' => 'v2.8',
		'persistent_data_handler'=>'session'
	]);

	$helper = $fb->getRedirectLoginHelper();
	try {
	  	$accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}

	if (isset($accessToken)) {
		// Logged in!
		  
		// Now you can redirect to another page and use the
		// access token from $_SESSION['facebook_access_token']

		// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();

		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);

		// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId('412242725779828'); // Replace {app-id} with your app id
		// If you know the user ID this access token belongs to, you can validate it here
		//$tokenMetadata->validateUserId('123');
		$tokenMetadata->validateExpiration();

		if (! $accessToken->isLongLived()) {
		  // Exchanges a short-lived access token for a long-lived one
		  try {
		    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		  } catch (Facebook\Exceptions\FacebookSDKException $e) {
		    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
		    exit;
		  }

		}

		//$_SESSION['fb_access_token'] = (string) $accessToken;
		
		setcookie('token',$accessToken,time() + 60 * 60 * 24 * 30,'/','',false,true);

		$fb->setDefaultAccessToken($accessToken);

	  	$response = $fb->get('/me?fields=id,name,email');
  		$userNode = $response->getGraphUser();

  		$valid=new Validate($userNode['email'],'');

  		$database=new Database();
		$db=$database->getDbConnection();
  		
  		if(!$db){
			$valid->echoResult(false,"DB Connection fail");
		}
		else{
			if($valid->checkExistance($db,false)){

				if($valid->getLoginType($db)==="SELF"){

					$_SESSION['email_login']=$userNode['email'];
					header('Location:userlogin.php');

				}
				else if($valid->getLoginType($db)==="FB"){
					setupFbUser($valid->getUser($db));
				}
			}
			else{
				
				$email=$userNode['email'];
				//$uid=$userNode['id'];
				$name=$userNode['name'];
				
				$res = $fb->get( '/me/picture?type=large&redirect=false' );
				$picture = $res->getGraphObject();
				$image=$picture['url'];

				if($valid->createFbUser($db,$email,$name,$image,'FB')){
					
			        setupFbUser($valid->getUser($db));
				}
				else{
		        	$valid->echoResult(false,mysqli_error($db));
		        }
			}
		}

		$database->closeDb();
  		
	}
	else{
		if ($helper->getError()) {
		    header('HTTP/1.0 401 Unauthorized');
		    echo "Error: " . $helper->getError() . "\n";
		    echo "Error Code: " . $helper->getErrorCode() . "\n";
		    echo "Error Reason: " . $helper->getErrorReason() . "\n";
		    echo "Error Description: " . $helper->getErrorDescription() . "\n";
		  } else {
		    header('HTTP/1.0 400 Bad Request');
		    echo 'Bad request';
		  }
		  exit;
	}

	function setupFbUser($userNode){
		setcookie('name',$userNode['name'],time() + 60 * 60 * 24 * 30,'/','',false,true);
  		setcookie('id',$userNode['id'],time() + 60 * 60 * 24 * 30,'/','',false,true);
  		setcookie('email',$userNode['email'],time() + 60 * 60 * 24 * 30,'/','',false,true);
  		setcookie('type',$userNode['type'],time() + 60 * 60 * 24 * 30,'/','',false,true);

		// User is logged in with a long-lived access token.
		// You can redirect them to a members-only page.
		header('Location:index.php');
	}
?>