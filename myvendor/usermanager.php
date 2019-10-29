<?php
	
	class UserManager
	{

		function __construct()
		{	
		}


		public static function verifyUser($db,$id,$email,$pass=""){

			if($pass==="")
				$result=UserManager::queryParse($db,"SELECT uid,uemail FROM user_table WHERE uid='".$id."' AND uemail='".$email."'");
			else
				$result=UserManager::queryParse($db,"SELECT uid,uemail FROM user_table WHERE uid='".$id."' AND uemail='".$email."' AND upassword='".$pass."'");

			return $result["progress"];
		}

		public static function updatePersonnel($db,$fname,$lname,$gender,$id,$email){

			$fname=strtolower(trim($fname));
			$lname=strtolower(trim($lname));
			$gender=strtoupper(trim($gender));

			if($gender!="M"&&$gender!="F"){
				return false;
			}

			$sql="UPDATE user_table SET uname='".$fname." ".$lname."' , ugender='".$gender."' WHERE uid='".$id."' AND uemail='".$email."'";

			$data=mysqli_query($db,$sql);
		} 

		public static function updatePassword($db,$pass,$newp1,$newp2,$id,$email){
			
			$pass=trim($pass);
			$newp1=trim($newp1);
			$newp2=trim($newp2);

			if($newp1===$newp2){
				$sql="UPDATE user_table SET upassword='".$newp1."' WHERE uid='".$id."' AND uemail='".$email."'";
				$data=mysqli_query($db,$sql);
			}
		}

		public static function getUserAddress($db,$uid){
			$result=UserManager::queryParse($db,"SELECT * FROM address_table WHERE id='".$uid."'");

			if($result['progress']){
				$list=array();
				while($row=mysqli_fetch_assoc($result['object'])){
					$item=array('id'=>$row['aid'],'name'=>$row['name'],'stadr'=>$row['street_address'],'landmark'=>$row['landmark'],'mobile'=>$row['mobile'],'pin'=>$row['pincode']);

					array_push($list, $item);
				}

				return json_encode(array('success'=>true,'error'=>mysqli_error($db),'list'=>$list));
			}
			else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		public static function createAddress($db,$id,$type,$name,$stadr,$landmark,$mobile,$pincode){

			$aid=Validate::createUserField('address',$id);

			$sql="INSERT INTO address_table(id,aid,type,name,street_address,landmark,city,state,mobile,pincode) values('".$id."','".$aid."','".$type."','".$name."','".$stadr."','".$landmark."','allahabad','up',".$mobile.",".$pincode.")";

			$data=mysqli_query($db,$sql);

			if($data===TRUE){
				return json_encode(array('success'=>true));
			}
			else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		public static function updateAddress($db,$id,$type,$name,$stradr,$landmark,$mobile,$pincode){

			$sql="UPDATE address_table SET `type`='".$type."' , `name`='".$name."' , `street_address`='".$stradr."' , `landmark`='".$landmark."' , `mobile`='".$mobile."' , `pincode`='".$pincode."' WHERE aid='".$id."'";
			
			$data=mysqli_query($db,$sql);
			
			if($data===TRUE){
				return json_encode(array('success'=>true,'message'=>"Address Updated Successfully"));
			}
			else{
				return json_encode(array('success'=>false,'error'=>"Unable to Update Address"));
			}
		}

		public static function checkAddress($db,$uid,$aid){
			$result=UserManager::queryParse($db,"SELECT * FROM address_table WHERE aid='".$aid."' AND id='".$uid."'");

			return $result['progress'];
		}

		public static function getAddress($db,$aid){
			$result=UserManager::queryParse($db,"SELECT * FROM address_table WHERE aid='".$aid."'");

			if($result['progress']){
				return json_encode(array('success'=>true,'address'=>mysqli_fetch_assoc($result['object'])));
			}
			else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		public static function updateProfilePic($db,$tmp_name,$name,$id){

			$sql="UPDATE user_table SET `upic`='".$name."' WHERE uid='".$id."'";
			
			$data=mysqli_query($db,$sql);
			
			if($data===TRUE){
				$result=move_uploaded_file($tmp_name, "../../".$name);

				if(!$result){
					mysqli_query($db,"UPDATE user_table SET `upic`='images/user_male.png' WHERE uid='".$id."'");		
				}
			}
		}

		public function queryParse($db,$sql){

			$result=mysqli_query($db,$sql);

			if(mysqli_num_rows($result)>0){
				return array("progress"=>true,"object"=>$result);
			}
			else{
				return array("progress"=>false);
			}
		}
	}

	if(isset($_POST['todo'])){

		require_once('../dbconfig.php');
		$database=new Database();
		$db=$database->getDbConnection();

		//var_dump($_POST);

		if($_POST['todo']==="get_addr"&&isset($_POST['id'])){
			echo UserManager::getAddress($db,$_POST['id']);
		}
		else if($_POST['todo']==="update_addr"&&isset($_POST['id'])&&isset($_POST['name'])&&isset($_POST['str_adr'])&&isset($_POST['landmark'])&&isset($_POST['mobile'])&&isset($_POST['pincode'])){

			echo UserManager::updateAddress($db,$_POST['id'],'user',$_POST['name'],$_POST['str_adr'],$_POST['landmark'],$_POST['mobile'],$_POST['pincode']);
		}
	}
?>