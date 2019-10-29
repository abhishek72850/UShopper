<?php

	class WebUser
	{
		private $uid;
		private $uname;
		private $uemail;
		private $utype;
		private $userArray;
		private $fname;
		private $lname;

		const TYPE_GUEST="GUEST";
		const TYPE_FB="FB";
		const TYPE_GOOGLE="GOOGLE";
		const TYPE_SELF="SELF";			//login types
		private $payload;

		private $fb;
		
		function __construct()
		{
			$num=func_num_args();
			if($num>2){
				$this->uid=func_get_arg(0);
				$this->uemail=func_get_arg(1);
				$this->utype=func_get_arg(2);
			}
			else{
				$this->uid=func_get_arg(0);
				$this->utype=func_get_arg(1);
			}
		}

		public function getUID(){
			return $this->uid;
		}
		public function getUName(){
			return $this->uname;
		}
		public function getUEmail(){
			return $this->uemail;
		}
		public function getType(){
			return $this->utype;
		}
		public function getFirstName(){
			return $this->fname;
		}
		public function getLastName(){
			return $this->lname;
		}


		public function setupFbSession(){
			$this->fb = new Facebook\Facebook([
		  		'app_id'                => '412242725779828',
				'app_secret'            => 'f3a83929fd143747b65c0354927d0bc9',
				'default_graph_version' => 'v2.8',
			]);

			if(isset($_COOKIE['token'])){
				try{
					$oAuth2Client = $this->fb->getOAuth2Client();

					$tokenMetadata = $oAuth2Client->debugToken($_COOKIE['token']);

					$tokenMetadata->validateAppId('412242725779828');
					
					//$tokenMetadata->validateUserId($this->getUID());
					//$tokenMetadata->validateExpiration();

					$this->fb->setDefaultAccessToken($_COOKIE['token']);

		  			//$response = $this->fb->get('/me?fields=id,name,email');
	  				//$userNode = $response->getGraphUser();

	  				$this->setSession();

	  			} catch(Facebook\Exceptions\FacebookResponseException $e) {
					// When Graph returns an error
					echo 'Graph returned an error: ' . $e->getMessage();
					exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
				  	// When validation fails or other local issues
				  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
				  	exit;
				}
			}
		}

		public function getFBInstance(){
			return $this->fb;
		}

		public function setupGoogleSession(){
			$client = new Google_Client();
			$client->setClientId("960111736235-n93s022448e5pn6vab44u6hdofh9jthl.apps.googleusercontent.com");

			$this->payload = $client->verifyIdToken($_COOKIE['token']);
			if ($this->payload) {

			  	$this->setSession();

			} else {
			  // Invalid ID token
			}
		}
		public function getGoogleInstance(){
			return $this->payload;
		}

		public function setSession(){

			$database=new Database();
			$db=$database->getDbConnection();

			$sql="SELECT * FROM user_table WHERE uid='".$this->getUID()."' AND uemail='".$this->getUEmail()."'";

			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				
				$row=mysqli_fetch_assoc($data);

				$_SESSION['id']=$row['uid'];
	  			
	  			if($row['uname']===NULL){
					$_SESSION['name']='User';
				}
				else{
					$_SESSION['name']=$row['uname'];
				}
	  			
	  			$_SESSION['email']=$row['uemail'];
		  		
	  			$this->uname=$_SESSION['name'];
	  			$this->fname=substr($_SESSION['name'],0,strpos($_SESSION['name']," "));
	  			$this->lname=substr($_SESSION['name'],strpos($_SESSION['name']," ")+1);

	  			$this->userArray=$row;
			}
			$database->closeDb();
		}

		public function detail(){
			return $this->userArray;
		}

		public function setSelfSession(){
			$this->setSession();
		}

		public function breadcrumb(){

			$uri=explode('/', $_SERVER['PHP_SELF']);
			array_shift($uri);
			array_pop($uri);

			$bread=array();
			$url=$_SERVER['PHP_SELF'];
			$pos=1;
			
			while(($pos=strpos($url, '/',$pos))!==false){
				
				$bread[current($uri)]=substr($url,0,$pos);
				next($uri);
				$pos++;
			}

			$crumb="";
			foreach ($bread as $key => $value) {
				$crumb.="<a href='".$value."'>".$key."</a><span>/</span>";
			}

			return $crumb;
		}
	}

?>