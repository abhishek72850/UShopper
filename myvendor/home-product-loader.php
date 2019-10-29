<?php

	class HomeProductLoader
	{
		function __construct()
		{
			
		}

		public static function getRecentAdd($db,$limit=8,$offset=0){
			$today=date("Y-m-d");
			$d= strtotime('5 days ago');
			$date=date("Y",$d)."-".date("m",$d)."-".date("d",$d);

			$sql="SELECT p.*,i.photo_url,s.sname FROM product_table p,image_gallery i,shop_table s WHERE p.pid=i.id AND p.sid=s.sid AND p.pdate>'".$date."' LIMIT ".$offset.",".$limit;
		
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				$product=array();
			
				while(($row=mysqli_fetch_assoc($data))){
					
					$item= array('id' =>$row['pid'] ,'sname'=>$row['sname'],'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'discount'=>$row['pdiscount'],'photo'=>explode(",", $row['photo_url']) );

					array_push($product, $item);
				}
				
				return json_encode(array('success'=>true,'error'=>mysqli_error($db),'list'=>$product));

			}else{
				return json_encode(array('success'=>false,'error'=>"No Product Recently Added"));
			}
		}

		public static function getMostRated($db,$uid="",$limit=8,$offset=0){
			
			$sql="SELECT r.total_rate,r.five_rate,r.four_rate,r.three_rate,r.two_rate,r.one_rate,p.*,i.photo_url,s.sname FROM rating_gallery r, product_table p,image_gallery i,shop_table s WHERE r.type='product' AND r.id=p.pid AND r.id=i.id AND p.sid=s.sid ORDER BY r.total_rate DESC LIMIT ".$offset.",".$limit;
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				
				$product=array();

				while(($row=mysqli_fetch_assoc($data))){

					$status=false;

					if ($uid!=="") {
						$result=HomeProductLoader::queryParse($db,"SELECT * FROM wishlist_table WHERE uid='".$uid."' AND id='".$row['pid']."'");
						$status=$result['progress'];	
					}
					

				
					$item= array('id' =>$row['pid'] ,'sname'=>$row['sname'],'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'rate'=>$row['total_rate'],'r_five'=>$row['five_rate'],'r_four'=>$row['four_rate'],'r_three'=>$row['three_rate'],'r_two'=>$row['two_rate'],'r_one'=>$row['one_rate'],'discount'=>$row['pdiscount'],'inwishlist'=>$status,'photo'=>explode(",", $row['photo_url']) );

					array_push($product,$item);
				}
				return json_encode(array('success'=>true,'error'=>mysqli_error($db),'list'=>$product));

			}else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		public static function getDailyNeeds($db,$limit=8,$offset=0){
			$sql="SELECT p.pid,p.pname,p.pmrp,p.pactualprice,p.ptag,p.pdiscount,i.photo_url,s.sname FROM product_table p,image_gallery i,shop_table s WHERE (MATCH(pname,ptag) AGAINST('+dailyneeds')) AND p.pid=i.id AND p.sid=s.sid ORDER BY p.prating DESC LIMIT ".$offset.",".$limit;
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				$product=array();

				while($row=mysqli_fetch_assoc($data)){

					$item= array('id' =>$row['pid'] ,'sname'=>$row['sname'],'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'discount'=>$row['pdiscount'],'photo'=>explode(",", $row['photo_url']) );

					array_push($product,$item);
				}
				
				return json_encode(array('success'=>true,'error'=>mysqli_error($db),'list'=>$product));

			}else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		public static function getTrendingFashion($db,$limit=8,$offset=0){
			$sql="SELECT p.pid,p.pname,p.pmrp,p.pactualprice,p.ptag,p.pdiscount,i.photo_url,s.sname FROM product_table p,image_gallery i,shop_table s WHERE (MATCH(pname,ptag) AGAINST('cloth')) AND p.pid=i.id AND p.sid=s.sid ORDER BY p.psold DESC LIMIT ".$offset.",".$limit;
			
			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				$product=array();
	
				while($row=mysqli_fetch_assoc($data)){

					$item= array('id' =>$row['pid'] ,'sname'=>$row['sname'],'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'discount'=>$row['pdiscount'],'photo'=>explode(",", $row['photo_url'])
					);

					array_push($product,$item);
				}

				return json_encode(array('success'=>true,'error'=>mysqli_error($db),'list'=>$product));

			}else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}
		}

		public static function getMostRatedShop($db,$limit=8,$offset=0){
			$result=HomeProductLoader::queryParse($db,"SELECT r.total_rate,s.*,i.photo_url FROM rating_gallery r, shop_table s,image_gallery i WHERE r.type='shop' AND r.id=s.sid AND r.id=i.id ORDER BY r.total_rate DESC LIMIT ".$offset.",".$limit);

			if($result['progress']){
				$product=array();

				while($row=mysqli_fetch_assoc($result['object'])){

					if($row['sid']!==NULL){
						$item= array('id' =>$row['sid'] ,'name'=>$row['sname'],'tag'=>$row['stag'],'rate'=>$row['srating'],'photo'=>explode(",", $row['photo_url']) );

						array_push($product,$item);
					}

				}
			
				return json_encode(array('success'=>true,'error'=>mysqli_error($db),'list'=>$product));

			}else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}

		}

		public static function getDiscounts($db,$limit=8,$offset=0){

			$result=HomeProductLoader::queryParse($db,"SELECT p.*,i.photo_url,s.sname FROM product_table p,image_gallery i,shop_table s WHERE p.pid=i.id AND p.sid=s.sid ORDER BY p.pdiscount DESC LIMIT ".$offset.",".$limit);

			if ($result['progress']) {
				
				$product=array();

				while ($row=mysqli_fetch_assoc($result['object'])) {
					
					$item=array('id'=>$row['pid'] ,'sname'=>$row['sname'],'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'discount'=>$row['pdiscount'],'photo'=>explode(",", $row['photo_url'])
					);

					array_push($product,$item);
				}

				return json_encode(array('success'=>true,'error'=>mysqli_error($db),'list'=>$product));
			}
			else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
			}

		}

		public static function getFashionWear($db,$type,$limit=8,$offset=0){

			$result=HomeProductLoader::queryParse($db,"SELECT p.*,i.photo_url,s.sname FROM product_table p,image_gallery i,shop_table s WHERE p.pid=i.id AND p.sid=s.sid AND (MATCH(pname,ptag) AGAINST('+".$type."+wear+cloth')) ORDER BY p.pdiscount DESC LIMIT ".$offset.",".$limit);

			if ($result['progress']) {
				
				$product=array();

				while ($row=mysqli_fetch_assoc($result['object'])) {
					
					$item= array('id' =>$row['pid'] ,'sname'=>$row['sname'],'name'=>$row['pname'],'mrp'=>$row['pmrp'],'actualprice'=>$row['pactualprice'],'tag'=>$row['ptag'],'discount'=>$row['pdiscount'],'photo'=>explode(",", $row['photo_url'])
					);

					array_push($product,$item);
				}

				return json_encode(array('success'=>true,'error'=>mysqli_error($db),'list'=>$product));
			}
			else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));
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
?>