<?php

	class UserParser
	{
		
		function __construct($argument)
		{
			
		}

		public static function getUser($db,$uid){
			$sql="SELECT * FROM user_table WHERE uid='".$uid."'";

			$result=mysqli_query($db,$sql);
			if(mysqli_num_rows($result)>0){
				$row=mysqli_fetch_assoc($result);

				echo json_encode(array("success"=>true,
								"error"=>mysqli_error($db),
								"data"=>array(
									"id"=>$row["uid"],
									"name"=>$row["uname"],
									"email"=>$row["uemail"],
									"type"=>$row["ulogintype"],
									"photo"=>$row["upic"])
									)
								);
			}
			else{
				echo json_encode(array("success"=>false,"error"=>"No Data Found"));
			}	
		}
	}

	if(isset($_POST['id'])){
		require_once ('../dbconfig.php');
		$database = new Database();
    	$db=$database->getDbConnection();

    	UserParser::getUser($db,$_POST['uid']);
	}
?>