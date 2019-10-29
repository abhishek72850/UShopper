<?php
	
	require_once('../dbconfig.php');

	class HomeProductLoader
	{
		private $json_data;
		
		function __construct()
		{
			
		}

		function getRecentAdd($db){
			$today=date("Y-m-d");
			$d= strtotime('5 days ago');
			$date=date("Y",$d)."-".date("m",$d)."-".date("d",$d);

			$sql="SELECT p.pid,p.pname,p.pmrp,p.pactualprice,p.ptag,i.photo_url FROM product_table p,image_gallery i WHERE pdate>'".$date."' AND p.pid=i.id LIMIT 8";
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				$product=array();

				while(($row=mysqli_fetch_assoc($data))){

					$item= array('id' =>$row['pid'] ,'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'photo'=>explode(",", $row['photo_url']) );

					array_push($product, $item);

				}
				$json_data=array('success'=>true,'error'=>mysqli_error($db),'product'=>$product);
				$this->json_data=json_encode($json_data);

			}else{
				$this->json_data=json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		function getMostRated($db){
			$sql="SELECT p.pid,p.pname,p.pmrp,p.pactualprice,p.ptag,i.photo_url,r.total_rate FROM product_table p,image_gallery i ,rating_gallery r WHERE p.pid=r.id AND p.pid=i.id ORDER BY r.total_rate DESC LIMIT 8";
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				$product=array();

				while(($row=mysqli_fetch_assoc($data))){					

					$item= array('id' =>$row['pid'] ,'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'photo'=>explode(",", $row['photo_url']) );

					array_push($product,$item);
				}

				$json_data=array('success'=>true,'error'=>mysqli_error($db),'product'=>$product);
				$this->json_data=json_encode($json_data);

			}else{
				$this->json_data=json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		function getDailyNeeds($db){
			$sql="SELECT p.pid,p.pname,p.pmrp,p.pactualprice,p.ptag,i.photo_url FROM product_table p,image_gallery i WHERE p.ptag LIKE '%daily%' AND p.pid=i.id ORDER BY p.prating DESC LIMIT 8";
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				$product=array();

				while(($row=mysqli_fetch_assoc($data))){
					
					$item= array('id' =>$row['pid'] ,'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'photo'=>explode(",", $row['photo_url']) );

					array_push($product,$item);
				}
				$json_data=array('success'=>true,'error'=>mysqli_error($db),'product'=>$product);
				$this->json_data=json_encode($json_data);

			}else{
				$this->json_data=json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		function getTrendingFashion($db){
			$sql="SELECT p.pid,p.pname,p.pmrp,p.pactualprice,p.ptag,i.photo_url FROM product_table p,image_gallery i WHERE p.ptag LIKE '%cloth%' AND p.pid=i.id ORDER BY psold DESC LIMIT 8";
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				$product=array();

				while(($row=mysqli_fetch_assoc($data))){

					$item= array('id' =>$row['pid'] ,'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'photo'=>explode(",", $row['photo_url']) );

					array_push($product,$item);
				}
				$json_data=array('success'=>true,'error'=>mysqli_error($db),'product'=>$product);
				$this->json_data=json_encode($json_data);

			}else{
				$this->json_data=json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		function echoResult(){
			echo $this->json_data;
		}

		function getResult(){
			return $this->json_data;
		}

	}

	
	if(isset($_POST['category'])){	
		$database=new Database();
		$db=$database->getDbConnection();
		$parser=new HomeProductLoader();
		
		if($_POST['category']==='recent'){
			$parser->getRecentAdd($db);
		}
		else if($_POST['category']==='mostrated'){
			$parser->getMostRated($db);
		}
		else if($_POST['category']==='dailyneeds'){
			$parser->getDailyNeeds($db);
		}
		else if($_POST['category']==='trending'){
			$parser->getTrendingFashion($db);
		}
		
		$parser->echoResult();
	}

?>