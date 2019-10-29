<?php

	require_once('../../myvendor/productmanager.php')

	class ProductManager extends ProductManager
	{
		
		function __construct()
		{
		
		}

		public static function getProductDetail($db,$id){

			$result=mysqli_query($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE p.pid='".$id."' AND i.id='".$id."'");

			if(mysqli_num_rows($result)>0){

				$row=mysqli_fetch_assoc($result);
				$photo=array('photo'=>explode(',', $row['photo_url']));
				array_pop($row);

				$row=array_merge($row,$photo);

				$data=mysqli_query($db,"SELECT sname FROM shop_table WHERE sid='".$row['sid']."'");
				$row2=mysqli_fetch_assoc($data);

				$row=array_merge($row,array('sname'=>$row2['sname']));

				echo json_encode(array('success'=>true,'item'=>$row));
			}
			else{
				echo json_encode(array('success'=>false,'error'=>'No Such Product'));
			}
		}

		public static function getSimilarProduct($db,$search){

			$search=strtolower(trim($search));
			$search2=strtr($search, " ,-_='","++++++");

			$result=ProductManager::queryParse($db,"SELECT p.*,s.sname,s.srating FROM product_table p,shop_table s WHERE match(p.pname,p.ptag) against('+".$search2."') AND p.sid=s.sid ORDER BY p.prating LIMIT 20");

			if($result["progress"]){

				$product=new ProductParser();
				
				$list=array();

				while ( $row=mysqli_fetch_assoc($result["object"])) {

					$data=ProductManager::queryParse($db,"SELECT photo_url FROM image_gallery WHERE id='".$row['pid']."'");

					if($data['progress']){
						$photo=mysqli_fetch_assoc($data["object"]);

						array_push($list, $product->getRowData($row,$photo["photo_url"],true));
					}
				}

				echo json_encode(array("success"=>true,"list"=>$list));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>"No Product Between this Price Range"));
			}
		}

		public static function getAllComments($db,$pid){
			$result=ProductManager::queryParse($db,"SELECT * FROM comment_gallery WHERE c_on_id='".$pid."'");

			if($result['progress']){

				$list=array();

				while ($row=mysqli_fetch_assoc($result['object'])) {
					
					array_push($list, $row);
				}

				echo json_encode(array('success'=>true,'list'=>$list));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>"No Comment found"));
			}
		}

		public static function addComment($db,$name,$email,$comment,$rate,$uid,$pid){
			$sql="INSERT INTO comment_gallery (uid,uemail,uname,rate,c_on_id,comment,c_date,c_time) VALUES('".$uid."','".$email."','".$name."',".$rate.",'".$pid."','".$comment."',NOW(),NOW())";

			$result=mysqli_query($db,$sql);

			if($result===TRUE){
				echo json_encode(array('success'=>true));
			}
			else{
				echo json_encode(array('success'=>false,'error'=>'Unable to Post Comment'));
			}
		}

		public static function createBrowseTrack($db,$uid,$id,$type,$tag){

			$check=ProductManager::queryParse($db,"SELECT * FROM user_browser WHERE uid='".$uid."' AND id='".$id."'");

			if($check['progress']){
				$sql="UPDATE user_browser SET b_date=NOW(), b_time=NOW() WHERE uid='".$uid."' AND id='".$id."'";
			}
			else{
				$sql="INSERT INTO user_browser (uid,id,type,tags,b_date,b_time) VALUES('".$uid."','".$id."','".$type."','".mysqli_escape_string($db,$tag)."',NOW(),NOW())";	
			}
 
			$result=mysqli_query($db,$sql);

			if($result===TRUE){
				echo json_encode(array('success'=>true));
			}
			else{
				echo json_encode(array('success'=>false,'error'=>'Unable to create User browse track'));
			}
		}

		public static function getRecentProducts($db,$uid){

			$result=ProductManager::queryParse($db,"SELECT p.*,i.photo_url FROM user_browser u,product_table p,image_gallery i WHERE u.uid='".$uid."' AND u.id=p.pid AND u.id=i.id ORDER BY p.prating DESC,u.b_date DESC,u.b_time DESC LIMIT 20");

			if($result['progress']){

				$list=array();

				while ( $row=mysqli_fetch_assoc($result["object"])) {

					$photo=array('photo'=>explode(',', $row['photo_url']));
					array_pop($row);

					$row=array_merge($row,$photo);

					array_push($list, $row);
				}

				echo json_encode(array("success"=>true,"list"=>$list));
			}
			else{
				echo json_encode(array("success"=>false,'error'=>'No Recent Product'));
			}
		}

		public function queryParse($db,$sql){

			//echo $sql;
			$result=mysqli_query($db,$sql);

			if(mysqli_num_rows($result)>0){
				return array("progress"=>true,"object"=>$result);
			}
			else{
				return array("progress"=>false);
			}
		}
	}

	if(isset($_POST['get'])&&isset($_POST['id'])){

		require_once('../dbconfig.php');

		$database=new Database();
		$db=$database->getDbConnection();

		if($_POST['get']==='detail'){
			ProductManager::getProductDetail($db,$_POST['id']);
		}
		else if($_POST['get']==='similar'){

			if(isset($_POST['tag']))
				ProductManager::getSimilarProduct($db,$_POST['tag']);	
			else
				echo json_encode(array('success'=>false,'error'=>'Invalid Parameter'));

		}else if($_POST['get']==='allcomment'){
			ProductManager::getAllComments($db,$_POST['id']);
			
		}else if($_POST['get']==='addcomment'){

			if(isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['comment'])&&isset($_POST['rate'])&&isset($_POST['uid']))
				ProductManager::addComment($db,$_POST['name'],$_POST['email'],$_POST['comment'],$_POST['rate'],$_POST['uid'],$_POST['id']);
			else
				echo json_encode(array('success'=>false,'error'=>'Invalid Parameter'));
		}else if($_POST['get']==='track'){
			ProductManager::createBrowseTrack($db,$_POST['uid'],$_POST['id'],$_POST['type'],$_POST['tag'])
			
		}else if($_POST['get']==='recent'){
			ProductManager::getRecentProducts($db,$_POST['uid']);
		}

		$database->closeDb();

	}
?>