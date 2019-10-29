<?php

	require_once('../dbconfig.php');

	class ShopParser
	{
		
		function __construct($argument)
		{

		}

		function getShopDetail($db,$sid){
			$sql="SELECT * FROM shop_table WHERE sid='".$sid."'";
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				$row=mysqli_fetch_assoc($data);

				$sql="SELECT photo_url FROM image_gallery WHERE id='".$row['sid']."'";
				$data=mysqli_query($db,$sql);
				$photo=mysqli_fetch_assoc($data);

				echo json_encode(
					array(
						'success' =>true ,
						'error'=>mysqli_error($db),
						'data'=>array(
							'sid'=>$row['sid'],
							'name'=>$row['sname'],
							'pic'=>$row['spic'],
							'type'=>$row['stype'],
							'tag'=>$row['stag'],
							'email'=>$row['semail'],
							'mobile'=>$row['smobile'],
							'rating'=>$row['srating'],
							'date'=>$row['sdate'],
							'time'=>$row['stime'],
							'photo'=>explode(",", $photo['photo_url'])
						) 
					)
				);

			}else{
				echo json_encode(array('success'=>false,'error'=>mysqli_error($db)));

			}
		}
	}

	if(isset($_POST['sid'])){
		$database=new Database();
		$db=$database->getDbConnection();

		$parse=new ShopParser();

		$parse->getShopDetail($db,$_POST['sid']);
	}
?>