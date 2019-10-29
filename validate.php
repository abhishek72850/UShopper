<?php

	require_once('class.verifyEmail.php'); 

	class Validate{

		private $email;
		private $pass1;
		private $pass2;
		private $uid;

		function __construct(){
			if(func_num_args()>2){
				$this->email=$this->sanitize(func_get_arg(0));
				$this->pass1=$this->sanitize(func_get_arg(1));
				$this->pass2=$this->sanitize(func_get_arg(2));
			}
			else if(func_num_args()==2){
				$this->email=$this->sanitize(func_get_arg(0));
				$this->pass1=$this->sanitize(func_get_arg(1));
			}
		}
		public function sanitize($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		public function verifyEmail(){
			$vmail = new verifyEmail();
		    $vmail->setStreamTimeoutWait(20);
		    $vmail->Debug= TRUE;
		    $vmail->Debugoutput= 'html';

		    $vmail->setEmailFrom('alexkay72850@gmail.com');

		    if ($vmail->check($this->email)) {
		        return true;
		    } elseif (verifyEmail::validate($this->email)) {
		        return false;
		    } else {
		    	return false;
		    } 
		}

		public function verifyPassword(){
			if(empty($this->pass1)||empty($this->pass2)){
				return false;
			}
			else if($this->pass1===$this->pass2){
				return true;
			}
			else{
				return false;
			}
		}

		public function getEmail(){
			return $this->email;
		}

		public function getPass(){
			return $this->pass1;
		}

		public function getUID(){
			return $this->uid;
		}

		public function createUser($db){
			if(!$this->checkExistance($db,false)){
				$date=date("dmY");
				$time=microtime();
				$this->uid=$this->generateUID($this->generateSalt($this->getEmail(),$date,$time));
				$type="SELF";
				$points=0;
				
				$em=mysqli_real_escape_string($db,$this->getEmail());
				$pas=mysqli_real_escape_string($db,$this->getPass());

				$sql="INSERT INTO user_table (uid,uemail,upassword,ulogintype,upoints,udate,utime,upic) VALUES (".$this->uid.",'".$em."','".$pas."','".$type."',".$points.",NOW(),NOW(),'images/guest-green.png');";

				$data=mysqli_query($db,$sql);
				
				if($data===TRUE){
					
					$this->createCookie("User",$this->uid,$em,'SELF');

					$this->echoResult(true);
				}
				else{
					$this->echoResult(false,"Database Error");
				}
			}
			else{
				$this->echoResult(false,"Email already exist");
			}
		}

		public function createFbUser($db,$email,$name,$image,$type){
			$date=date("dmY");
			$time=microtime();
			$points=0;
			$this->uid=$this->generateUID($this->generateSalt($this->getEmail(),$date,$time));
			
			$email=mysqli_real_escape_string($db,$email);
			
			$sql="INSERT INTO user_table (uid,uname,uemail,ulogintype,upic,upoints,udate,utime) VALUES ('".$this->uid."','".$name."','".$email."','".$type."','".$image."',".$points.",NOW(),NOW());";

			$data=mysqli_query($db,$sql);
			
			if($data===TRUE){
				return true;
			}
			else{
				return false;
			}
		}

		public function getUser($db){
			$sql="SELECT uname,uid,uemail,ulogintype FROM user_table WHERE uemail='".mysqli_real_escape_string($db,$this->getEmail())."'";
			$data=mysqli_query($db,$sql);
			if(mysqli_num_rows($data)>0){
				$row=mysqli_fetch_assoc($data);
				return array('name'=>$row['uname'],'id'=>$row['uid'],'email'=>$row['uemail'],'type'=>$row['ulogintype']);
			}
			else 
				return false;
		}

		public function checkExistance($db,$progress=false){
			$sql="SELECT * FROM user_table WHERE uemail='".mysqli_real_escape_string($db,$this->getEmail())."'";
			$data=mysqli_query($db,$sql);
			if(mysqli_num_rows($data)>0){
				if($progress){
					$row=mysqli_fetch_assoc($data);
					$this->createCookie($row['uname'],$row['uid'],$row['uemail'],$row['ulogintype']);
					mysqli_free_result($data);
					return true;
				}
				else{
					return true;
				}
			}
			else{
				return false;
			}
		}

		public function checkUserWithPass($db){
			$sql="SELECT * FROM user_table WHERE uemail='".mysqli_real_escape_string($db,$this->getEmail())."' AND upassword='".mysqli_real_escape_string($db,$this->getPass())."'";
			$data=mysqli_query($db,$sql);
			if(mysqli_num_rows($data)>0){
				
				$row=mysqli_fetch_assoc($data);
				$this->createCookie($row['uname'],$row['uid'],$row['uemail'],$row['ulogintype']);
				mysqli_free_result($data);
				return true;
			}
			else{
				return false;
			}	
		}

		public function getLoginType($db){
			$sql="SELECT ulogintype FROM user_table WHERE uemail='".mysqli_real_escape_string($db,$this->getEmail())."'";
			
			$data=mysqli_query($db,$sql);
			
			if(mysqli_num_rows($data)>0){
				$row=mysqli_fetch_assoc($data);
				return $row['ulogintype'];
			}
			else{
				return false;
			}

		}

		public function createCookie($user='User',$uid,$email,$type){
			setcookie('name',$user,time() + 60 * 60 * 24 * 30,'/','',false,true);
			setcookie('id',$uid,time() + 60 * 60 * 24 * 30,'/','',false,true);
			setcookie('email',$email,time() + 60 * 60 * 24 * 30,'/','',false,true);
			setcookie('type',$type,time() + 60 * 60 * 24 * 30,'/','',false,true);
		}

		public static function createUserFieldId($field,$uid){

			$salt_a="";

			for ($i=0; $i <strlen($field) ; $i++) { 
				$salt_a=$salt_a.ord($field[$i]);	
			}

			$salt=$uid.$salt_a;
			$field_id="";
			$first=true;
			while (strlen($field_id)<10) {
				$key=rand(0,strlen($salt)-1);
				if($first){
					if(0!==intval($salt[$key])){
						$field_id=$field_id.$salt[$key];
					}
					$first=false;
				}
				else{
					$field_id=$field_id.$salt[$key];
				}
			}

			return $field_id;
		}
		public function generateSalt($email,$date,$time){
	
			$salt_a="";
			for($i=0;$i<strlen($email);$i++){
				$salt_a=$salt_a.ord($email[$i]);
			}

			$t=explode(" ", $time);
			$f=explode(".", $t[0]);
			$salt_b=$t[1].$f[1];
			
			$salt=$salt_a.$salt_b.$date;
			
			return $salt;
		}

		public function generateUID($salt){
			$uid="";
			$first=true;
			while (strlen($uid)<20) {
				$key=rand(0,strlen($salt)-1);
				if($first){
					if(0!==intval($salt[$key])){
						$uid=$uid.$salt[$key];
					}
					$first=false;
				}
				else{
					$uid=$uid.$salt[$key];
				}
			}
			return $uid;
		}

		public function echoResult($success,$error='',$data=array()){
			$json = array('success' =>$success ,"error"=>$error ,"data"=>$data);
			echo json_encode($json);
		}
	}
?>