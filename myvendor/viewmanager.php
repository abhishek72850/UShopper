<?php

	class ViewManager
	{

		private static $sort=array('1'=>"p.pactualprice ASC",
			'2'=>"p.pactualprice DESC",
			'3'=>"p.prating DESC",
			'4'=>"p.pdate DESC,p.ptime DESC");

		private static $ftype=array("price","discount","prate","srate","price_discount","price_prate","price_srate","discount_prate","discount_srate","prate_srate","price_discount_prate","discount_prate_srate","price_prate_srate","price_discount_srate","price_discount_prate_srate");
		
		public static function getViewItems($db,$sort=false,$filter=false,$search,$param,$range=array(),$offset=0,$uid=""){

			$orderby="";

			if($search==="latest"){
				
				$today=date("Y-m-d");
				$d= strtotime('5 days ago');
				$date=date("Y",$d)."-".date("m",$d)."-".date("d",$d);

				$sql="SELECT p.*,s.sname,i.photo_url FROM product_table p,image_gallery i,shop_table s WHERE p.pid=i.id AND p.sid=s.sid AND p.pdate>'".$date."'";
				$orderby="p.prating DESC";
			}
			else if($search==="prated"){

				$sql="SELECT r.*,p.*,s.sname,i.photo_url FROM rating_gallery r, product_table p,image_gallery i,shop_table s WHERE r.type='product' AND r.id=p.pid AND r.id=i.id AND p.sid=s.sid";
				$orderby="r.total_rate DESC";
			}
			else if($search==="srated"){
				
				$result=self::queryParse($db,"SELECT r.total_rate,s.*,i.photo_url FROM rating_gallery r, shop_table s,image_gallery i WHERE r.type='shop' AND r.id=s.sid AND r.id=i.id  ORDER BY r.total_rate DESC LIMIT ".$offset.",20");
				return self::resultBuilder($result);
				exit;
			}
			else if($search==="ftrend"){
				
				$sql="SELECT p.*,s.sname,i.photo_url FROM product_table p,image_gallery i,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('cloth')) AND p.pid=i.id AND p.sid=s.sid";
				$orderby="p.psold DESC";

				//echo $sql;	
			}
			else if($search==="daily"){
				
				$sql="SELECT p.*,s.sname,i.photo_url FROM product_table p,image_gallery i,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+dailyneeds')) AND p.pid=i.id AND p.sid=s.sid";
				$orderby="p.prating DESC";
			}
			else if($search==="discounts"){
				
				$sql="SELECT p.*,s.sname,i.photo_url FROM product_table p,image_gallery i,shop_table s WHERE p.pid=i.id AND p.sid=s.sid";
				$orderby="p.pdiscount DESC";
			}
			else if($search==="fwomen"){
				$sql="SELECT p.*,s.sname,i.photo_url FROM product_table p,image_gallery i,shop_table s WHERE p.pid=i.id AND p.sid=s.sid AND (MATCH(p.pname,p.ptag) AGAINST('+women+wear+cloth'))";
				$orderby="p.pdiscount DESC";
			}
			else if($search==="fmen"){
				
				$sql="SELECT p.*,s.sname,i.photo_url FROM product_table p,image_gallery i,shop_table s WHERE p.pid=i.id AND p.sid=s.sid AND (MATCH(p.pname,p.ptag) AGAINST('+men+wear+cloth'))";
				$orderby="p.pdiscount DESC";	
			}
			else if($search==="recent"){
				
				$sql="SELECT p.*,s.sname,i.photo_url FROM user_browser u,product_table p,image_gallery i,shop_table s WHERE u.uid='".$uid."' AND u.id=p.pid AND u.id=i.id AND p.sid=s.sid";
				$orderby="p.prating DESC,u.b_date DESC,u.b_time DESC";
			}


			if($sort&&$filter){
				$result=self::sortWithFilter($db,$param[1],$sql,$orderby,$range,$offset);
			}
			else if($sort){
				
				$result=self::queryParse($db,$sql." ORDER BY ".self::$sort[$param[0]].",".$orderby." LIMIT ".$offset.",20");

			}
			else if($filter){
				$result=self::sortWithFilter($db,$param[1],$sql,$orderby,$range,$offset);
			}
			else{
				// echo $sql." ORDER BY ".$orderby." LIMIT ".$offset.",20";
				$result=self::queryParse($db,$sql." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}

			return self::resultBuilder($result);
		}

		public function resultBuilder($result){

			if($result["progress"]){
				
				$list=array();

				while ( $row=mysqli_fetch_assoc($result["object"])) {

					$photo=array('photo'=>explode(',', $row['photo_url']));
					array_pop($row);

					$row=array_merge($row,$photo);

					array_push($list, $row);
				}

				return json_encode(array("success"=>true,"list"=>$list));
			}
			else{
				return json_encode(array("success"=>false,"error"=>"No Product Between this Price Range"));
			}
		}

		public function sortWithFilter($db,$type,$sql,$orderby="",$range=array(),$offset=0){

			if($type==="price"){
				$result=self::queryParse($db,$sql." AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="srate"){
				$result=self::queryParse($db,$sql." AND s.srating BETWEEN ".$range[3]." AND 5"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="discount"){
				$result=self::queryParse($db,$sql." AND p.pdiscount BETWEEN ".$range[4]." AND 100"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="prate"){
				$result=self::queryParse($db,$sql." AND p.prating BETWEEN ".$range[2]." AND 5"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="price_srate"){
				$result=self::queryParse($db,$sql." AND s.srating BETWEEN ".$range[3]." AND 5 AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="price_discount"){
				$result=self::queryParse($db,$sql." AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.pdiscount BETWEEN ".$range[4]." AND 100"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="price_prate"){
				$result=self::queryParse($db,$sql." AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[2]." AND 5"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="prate_srate"){
				$result=self::queryParse($db,$sql." AND s.srating BETWEEN ".$range[3]." AND 5 AND p.prating BETWEEN ".$range[2]." AND 5".$orderby." LIMIT "." ORDER BY ".$offset.",20");
			}
			else if($type==="discount_srate"){
				$result=self::queryParse($db,$sql." AND s.srating BETWEEN ".$range[3]." AND 5 AND p.pdiscount BETWEEN ".$range[4]." AND 100".$orderby." LIMIT "." ORDER BY ".$offset.",20");
			}
			else if($type==="discount_prate"){
				$result=self::queryParse($db,$sql." AND p.pdiscount BETWEEN ".$range[4]." AND 100 p.prating BETWEEN ".$range[2]." AND 5".$orderby." LIMIT "." ORDER BY ".$offset.",20");
			}
			else if($type==="price_discount_srate"){
				$result=self::queryParse($db,$sql." AND s.srating BETWEEN ".$range[3]." AND 5 AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.pdiscount BETWEEN ".$range[4]." AND 100"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="price_prate_srate"){
				$result=self::queryParse($db,$sql." AND s.srating BETWEEN ".$range[3]." AND 5 AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[4]." AND 5"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="price_discount_prate"){
				$result=self::queryParse($db,$sql." AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[2]." AND 5 AND p.pdiscount BETWEEN ".$range[4]." AND 100"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="discount_prate_srate"){
				$result=self::queryParse($db,$sql." AND s.srating BETWEEN ".$range[3]." AND 5 AND p.prating BETWEEN ".$range[2]." AND 5 AND p.pdiscount BETWEEN ".$range[4]." AND 100"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else if($type==="price_discount_prate_srate"){
				$result=self::queryParse($db,$sql." AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.pdiscount BETWEEN ".$range[4]." AND 100 AND s.srating BETWEEN ".$range[3]." AND 5 AND p.prating BETWEEN ".$range[2]." AND 5"." ORDER BY ".$orderby." LIMIT ".$offset.",20");
			}
			else{
				die("No Sort Or Filter found");
			}

			return $result;
		}

		public function queryParse($db,$sql,$opt=null){

			$result=mysqli_query($db,$sql);

			if(mysqli_num_rows($result)>0){
				return array("progress"=>true,"object"=>$result);
			}
			else{
				return array("progress"=>false);
			}
		}
	}

	if(isset($_POST["view"])&&isset($_POST["param"])) {
		
		require_once('../dbconfig.php');
		$database=new Database();
		$db=$database->getDbConnection();

		//var_dump($_POST);
 
		$discount=0;
		$pricemin=0;
		$pricemax=0;
		$prate=0;
		$srate=0;
		$offset=0;
		$uid="";
		
		if(isset($_POST["discount"])){
			$discount=$_POST["discount"];
		}
		if(isset($_POST["pricemin"])&&isset($_POST["pricemax"])){
			$pricemin=$_POST["pricemin"];	
			$pricemax=$_POST["pricemax"];
		}
		if(isset($_POST["prate"])){
			$prate=$_POST["prate"];	
		}
		if(isset($_POST["srate"])){
			$srate=$_POST["srate"];	
		}
		if(isset($_POST["offset"])){
			$offset=$_POST["offset"];
		}
		if(isset($_POST["uid"])){
			$uid=$_POST["uid"];
		}

		$param=explode(',', $_POST["param"]);

		//for no sort and filter
		if($param[0]==='0'&&$param[1]==='0'){
			echo ViewManager::getViewItems($db,false,false,$_POST['view'],array(),array(),$offset,$uid);
		}
		//for sorting with filters
		if($param[0]!=='0'&&$param[1]!=='0'){
			echo ViewManager::getViewItems($db,true,true,$_POST['view'],$param,array($pricemin,$pricemax,$prate,$srate,$discount),$offset,$uid);
		}
		//for only sorting
		else if($param[0]!=='0'){
			echo ViewManager::getViewItems($db,true,false,$_POST['view'],$param,array(),$offset,$uid);
		}
		//for only filter
		else if($param[1]!=='0'){
			echo ViewManager::getViewItems($db,false,true,$_POST['view'],$param,array($pricemin,$pricemax,$prate,$srate,$discount),$offset,$uid);
		}
	}
?>