<?php 

	class ShopManager
	{
		private static $sort=array('1'=>"p.pactualprice ASC",
			'2'=>"p.pactualprice DESC",
			'3'=>"p.prating DESC",
			'4'=>"p.pdate DESC,p.ptime DESC");

		private $ftype=array("price","discount","prate","price_discount","price_prate","discount_prate","price_discount_prate");
		
		function __construct()
		{
		}

		//Retrieve the Shop details
		public static function getShopDetail($db,$id){
			$result=ShopManager::queryParse($db,"SELECT s.*,r.*,i.photo_url FROM shop_table s,rating_gallery r,image_gallery i WHERE s.sid='".$id."' AND i.id='".$id."' AND r.id='".$id."'");

			if($result['progress']){

				$row=mysqli_fetch_assoc($result['object']);
				$photo=array('photo'=>explode(',', $row['photo_url']));
				array_pop($row);

				$row=array_merge($row,$photo);

				return json_encode(array('success'=>true,'item'=>$row));
			}
			else{
				return json_encode(array('success'=>true));
			}
		}

		//Create Shop Home
		public static function loadShopHome($db,$sid,$type,$uid=''){

			if($type==='trending_product'||$type==='ssftp'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE sid='".$sid."' AND p.pid=i.id ORDER BY p.psold DESC LIMIT 20");
			}
			else if($type==='latest_add'||$type==='ssflp'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE sid='".$sid."' AND p.pid=i.id ORDER BY p.pdate DESC,p.ptime DESC LIMIT 20");
			}
			else if($type==='discounts'||$type==='ssfdu'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE sid='".$sid."' AND p.pid=i.id ORDER BY p.pdiscount DESC LIMIT 20");	
			}
			else if($type==='user_recents'||$type==='ssfur'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,user_browser b,image_gallery i WHERE b.uid='".$uid."' AND b.id=p.pid AND b.id=i.id AND p.sid='".$sid."'");
			}
			else if($type==='shop_favourite'||$type==='ssfsf'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,wishlist_table w,image_gallery i WHERE w.uid='".$uid."' AND w.id=p.pid AND p.sid='".$sid."' AND p.pid=i.id");
			}

			return ShopManager::resultBuilder($result);
		}

		//Retrieve similar products A/c to user searches
		public static function getSimilarProducts($db,$uid,$sid){
			$result=ShopManager::queryParse($db,"SELECT p.ptag FROM product_table p,user_browser b WHERE b.uid='".$uid."' AND b.id=p.pid AND p.sid='".$sid."'");

			if($result['progress']){

				$allTag=array();
				while($row=mysqli_fetch_assoc($result['object'])){
					
					$tagArray=explode(',', $row['ptag']);

					foreach ($tagArray as $key => $value) {
						
						$found=false;
						foreach ($allTag as $key2 => $value2) {
							if($value===$value2){
								$found=true;
								break;
							}
						}

						if(!$found){
							array_push($allTag,$value);
						}
					}
				}

				return ShopManager::getSearchResult($db,implode('+', $allTag),$sid);
			}
			else{
				return json_encode(array("success"=>false));
			}

		}

		//Retrieve products A/c to searches
		public static function getSearchResult($db,$search,$sid,$offset=0){

			$search=strtolower(trim($search));
			$search2=strtr($search, " ,-_='","++++++");

			//Condition for All Products
			if($search==="_*_")
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE p.sid='".$sid."' AND p.pid=i.id ORDER BY p.prating LIMIT ".$offset.",20");
			else	
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE (match(p.pname,p.ptag) against('+".$search2."')) AND p.sid='".$sid."' AND p.pid=i.id ORDER BY p.prating LIMIT ".$offset.",20");

			return ShopManager::resultBuilder($result);
		
		}

		//Check for Reserved Category
		public static function isReserved($type){


			$category=array("ssftp","ssflp","ssfdu","ssfsf","ssfur","ssfii");

			foreach ($category as $key => $value) {
				if($value===$type){
					return true;
				}
			}

			return false;
		}

		//Retrieve Products A/c t o sorting and filtering
		public static function productSortFilter($db,$sort=false,$filter=false,$sid,$search,$param,$range=array(),$offset=0){

			$search=strtolower(trim($search));
			$search2=strtr($search, " ,-_=","+++++");

			if($sort&&$filter){
				$result=self::sortWithFilter($db,$param[1],self::$sort[$param[0]],$sid,$search,$range,$offset);
			}
			else if($sort){
				
				if($search==="_*_")
					$result=self::queryParse($db,"SELECT p.* FROM product_table p, shop_table s WHERE p.sid='".$sid."' AND s.sid='".$sid."' ORDER BY ".self::$sort[$param[0]]." LIMIT ".$offset.",20");
				else	
					$result=self::queryParse($db,"SELECT p.* FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND p.sid='".$sid."' AND s.sid='".$sid."' ORDER BY ".self::$sort[$param[0]]." LIMIT ".$offset.",20");
			}
			else if($filter){
				
				$result=self::sortWithFilter($db,$param[1],"p.prating DESC",$sid,$search,$range,$offset);	
			}
			
			if($result["progress"]){

				//$product=new ProductParser();
				
				$list=array();

				while ( $row=mysqli_fetch_assoc($result["object"])) {

					$data=self::queryParse($db,"SELECT photo_url FROM image_gallery WHERE id='".$row['pid']."'");

					if($data['progress']){
						$photo=mysqli_fetch_assoc($data["object"]);

						$row=array_merge($row, array("photo"=>explode(',',$photo["photo_url"])));
						array_push($list, $row);
					}
				}

				return json_encode(array("success"=>true,"list"=>$list));
			}
			else{
				return json_encode(array("success"=>false,"error"=>"No Product Between this Price Range"));
			}
		}

		public function sortWithFilter($db,$type,$query="",$sid,$search,$range=array(),$offset=0){

			$search2=strtr($search, " ,-_=","+++++");

			$extra="";

			if($search==="_*_"){
				$searchme="";
			}
			else{
				$searchme="(MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND";
			}

			if($type==="price"){
				$result=self::queryParse($db,"SELECT p.* FROM product_table p WHERE ".$searchme." p.sid='".$sid."' AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." ORDER BY ".$query." LIMIT ".$offset.",20");
			}
			else if($type==="discount"){
				$result=self::queryParse($db,"SELECT p.* FROM product_table p WHERE ".$searchme." p.sid='".$sid."' AND p.pdiscount BETWEEN ".$range[3]." AND 100 ORDER BY ".$query." LIMIT ".$offset.",20");
			}
			else if($type==="prate"){
				$result=self::queryParse($db,"SELECT p.* FROM product_table p WHERE ".$searchme." p.sid='".$sid."' AND p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT ".$offset.",20");
			}
			else if($type==="price_discount"){
				$result=self::queryParse($db,"SELECT p.* FROM product_table p WHERE ".$searchme." p.sid='".$sid."' AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.pdiscount BETWEEN ".$range[3]." AND 100 ORDER BY ".$query." LIMIT ".$offset.",20");
			}
			else if($type==="price_prate"){
				$result=self::queryParse($db,"SELECT p.* FROM product_table p WHERE ".$searchme." p.sid='".$sid."' AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT ".$offset.",20");
			}
			else if($type==="discount_prate"){
				$result=self::queryParse($db,"SELECT p.* FROM product_table p WHERE ".$searchme." p.sid='".$sid."' AND p.pdiscount BETWEEN ".$range[3]." AND 100 p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT ".$offset.",20");
			}
			else if($type==="price_discount_prate"){
				$result=self::queryParse($db,"SELECT p.* FROM product_table p WHERE ".$searchme." p.sid='".$sid."' AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[2]." AND 5 AND p.pdiscount BETWEEN ".$range[3]." AND 100 ORDER BY ".$query." LIMIT ".$offset.",20");
			}
			else{
				die("No Sort Or Filter found");
			}

			return $result;
		}

		public static function resultBuilder($result){

			if($result['progress']){

				$product=array();

				while($row=mysqli_fetch_assoc($result['object'])){
					
					$photo=array('photo'=>explode(',', $row['photo_url']));
					array_pop($row);

					$row=array_merge($row,$photo);
					array_push($product,$row);
				}

				return json_encode(array('success'=>true,'list'=>$product));
			}
			else{
				return json_encode(array('success'=>false));
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

	if(isset($_POST["param"])&&isset($_POST["searchshopfor"])&&isset($_POST["sid"])) {
		
		require_once('../dbconfig.php');
		$database=new Database();
		$db=$database->getDbConnection();

		$search=new ShopManager();

		//var_dump($_POST);
 
		$discount=0;
		$pricemin=0;
		$pricemax=0;
		$prate=0;
		$offset=0;
		
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
		if(isset($_POST["offset"])){
			$offset=$_POST["offset"];
		}

		$param=explode(',', $_POST["param"]);

		//for no sort and filter
		if($param[0]==='0'&&$param[1]==='0'){
			echo ShopManager::getSearchResult($db,$_POST["searchshopfor"],$_POST['sid'],$offset);
		}
		//for sorting with filters
		if($param[0]!=='0'&&$param[1]!=='0'){
			echo Shopmanager::productSortFilter($db,true,true,$_POST['sid'],$_POST["searchshopfor"],$param,array($pricemin,$pricemax,$prate,$discount),$offset);
		}
		//for only sorting
		else if($param[0]!=='0'){
			echo Shopmanager::productSortFilter($db,true,false,$_POST['sid'],$_POST["searchshopfor"],$param,array(),$offset);
		}
		//for only filter
		else if($param[1]!=='0'){
			echo Shopmanager::productSortFilter($db,false,true,$_POST['sid'],$_POST["searchshopfor"],$param,array($pricemin,$pricemax,$prate,$discount),$offset);
		}
	}
?>