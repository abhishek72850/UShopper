<?php
	
	require_once('../dbconfig.php');
	

	class UserManager
	{

		private $uid;
		private $email;
		private $db;

		function __construct($uid,$email)
		{
			$this->uid=$uid;
			$this->email=$email;

			$this->dbConnect();	
		}

		public function dbConnect(){
			
			$database=new Database();
			$db=$database->getDbConnection();

			if(!$db){
				echo json_encode(array("success"=>false,"error"=>"Unable to connect to Database"));
				exit;
			}
			else{
				$this->db=$db;
			}

		}

		public function verifyUser(){
			$result=$this->queryParse("SELECT uid,uemail FROM user_table WHERE uid='".$this->uid."' AND uemail='".$this->email."'");

			return $result["progress"];
		}

		public function updatePersonnel($fname,$lname,$gender,$id,$email){

			$fname=strtolower(trim($fname));
			$lname=strtolower(trim($lname));
			$gender=strtoupper(trim($gender));

			if($gender!="M"&&$gender!="F"){
				return false;
			}

			$sql="UPDATE user_table SET uname='".$fname." ".$lname."' , ugender='".$gender."' WHERE uid='".$id."' AND uemail='".$email."'";

			$data=mysqli_query($this->db,$sql);

			if($data===TRUE)
				echo json_encode(array('success'=>true));
			else{
				echo json_encode(array('success'=>false,'error'=>mysqli_error($this->db)));
			}
		} 

		public function updatePassword($pass,$newp1,$newp2,$id,$email){
			
			$pass=trim($pass);
			$newp1=trim($newp1);
			$newp2=trim($newp2);

			if($newp1===$newp2){
				$sql="UPDATE user_table SET upassword='".$newp1."' WHERE uid='".$id."' AND uemail='".$email."'";
				$data=mysqli_query($this->db,$sql);

				if($data===TRUE)
					echo json_encode(array('success'=>true));
				else{
					echo json_encode(array('success'=>false,'error'=>mysqli_error($this->db)));
				}
			}
			else{
				echo json_encode(array('success'=>false,'error'=>'Password not Validated'));
			}
		}

		public function getUserAddress($uid){
			$result=$this->queryParse("SELECT * FROM address_table WHERE id='".$uid."'");

			if($result['progress']){
				$list=array();
				while($row=mysqli_fetch_assoc($result['object'])){
					$item=array('id'=>$row['aid'],'name'=>$row['name'],'stadr'=>$row['street_address'],'landmark'=>$row['landmark'],'mobile'=>$row['mobile'],'pin'=>$row['pincode']);

					array_push($list, $item);
				}

				echo json_encode(array('success'=>true,'error'=>mysqli_error($this->db),'list'=>$list));
			}
			else{
				echo json_encode(array('success'=>false,'error'=>mysqli_error($this->db)));
			}
		}

		public function createAddress($data,$type,$name,$stadr,$landmark,$mobile,$pincode,$aid=''){

			if($data==='add_adr'){
				require_once('validate.php');

				$aid=Validate::createUserFieldId('address',$this->uid);

				$sql="INSERT INTO address_table(id,aid,type,name,street_address,landmark,city,state,mobile,pincode) values('".$this->uid."','".$aid."','".$type."','".$name."','".$stadr."','".$landmark."','allahabad','up',".$mobile.",".$pincode.")";
			}
			else if($data==='edit_adr'){

				$sql="UPDATE address_table SET `type`='user', `name`='".$name."', `street_address`='".$stadr."', `landmark`='".$landmark."', `mobile`=".$mobile.", `pincode`=".$pincode." WHERE aid='".$aid."' AND id='".$this->uid."'";
			}

			$data=mysqli_query($this->db,$sql);

			if($data===TRUE){
				echo json_encode(array('success'=>true));
			}
			else{
				echo json_encode(array('success'=>false,'error'=>mysqli_error($this->db)));
			}
		}

		public function getAddress($aid){
			$result=$this->queryParse("SELECT * FROM address_table WHERE aid='".$aid."'");

			if($result['progress']){
				echo json_encode(array('success'=>true,'address'=>mysqli_fetch_assoc($result['object'])));
			}
			else{
				echo json_encode(array('success'=>false,'error'=>mysqli_error($this->db)));
			}
		}

		public function uploadProfile($file,$uid){

			$result=mysqli_query($this->db,"UPDATE user_table SET upic='".$file."' WHERE uid='".$uid."'");

			if($result===TRUE){
				echo json_encode(array('success'=>true));
			}
			else{
				echo json_encode(array('success'=>false,'error'=>mysqli_error($this->db)));
			}
		}

		public function queryParse($sql){

			$result=mysqli_query($this->db,$sql);

			if(mysqli_num_rows($result)>0){
				return array("progress"=>true,"object"=>$result);
			}
			else{
				return array("progress"=>false);
			}
		}
	}

	if(isset($_POST['id'])&&isset($_POST['email'])&&isset($_POST['type'])){

		$user=new UserManager($_POST['id'],$_POST['email']);

		if($user->verifyUser()){
			
			if($_POST['type']==='add_adr'){
				$user->createAddress($_POST['type'],'user',$_POST['name'],$_POST['stadr'],$_POST['landmark'],$_POST['mobile'],$_POST['pin']);
			}
			else if($_POST['type']==='edit_adr'){
				$user->createAddress($_POST['type'],'user',$_POST['name'],$_POST['stadr'],$_POST['landmark'],$_POST['mobile'],$_POST['pin'],$_POST['aid']);
			}
			else if($_POST['type']==='get_all_address'){
				$user->getUserAddress($_POST['id']);
			}
			else if($_POST['type']==='get_address'){
				$user->getAddress($_POST['aid']);
			}
			else if($_POST['type']==='update_personnel'){
				$user->updatePersonnel($_POST['fname'],$_POST['lname'],$_POST['gender'],$_POST['id'],$_POST['email']);
			}
			else if($_POST['type']==='update_password'){
				$user->updatePassword($_POST['mypass'],$_POST['npass1'],$_POST['npass2'],$_POST['id'],$_POST['email']);
			}
			else if($_POST['type']==='upload_profile'){
				$user->uploadProfile($_POST['file'],$_POST['id']);
			}
		}
		else{
			echo json_encode(array('success'=>false,'error'=>'User Verify Error'));
		}
	}
?>