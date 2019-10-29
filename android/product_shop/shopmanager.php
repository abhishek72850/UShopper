<?php 

	class ShopManager
	{
		private $sort=array('1'=>"p.pactualprice ASC",
			'2'=>"p.pactualprice DESC",
			'3'=>"p.prating DESC",
			'4'=>"p.pdate DESC,p.ptime DESC");

		private $ftype=array("price","discount","prate","price_discount","price_prate","discount_prate","price_discount_prate");
		
		function __construct()
		{
		}

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

		public static function loadShopHome($db,$sid,$type,$uid=''){

			if($type==='trending_product'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE sid='".$sid."' AND p.pid=i.id ORDER BY p.psold DESC LIMIT 20");
			}
			else if($type==='latest_add'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE sid='".$sid."' AND p.pid=i.id ORDER BY p.pdate DESC,p.ptime DESC LIMIT 20");
			}
			else if($type==='discounts'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE sid='".$sid."' AND p.pid=i.id ORDER BY p.pdiscount DESC LIMIT 20");	
			}
			else if($type==='user_recents'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,user_browser b,image_gallery i WHERE b.uid='".$uid."' AND b.id=p.pid AND b.id=i.id AND p.sid='".$sid."'");
			}
			else if($type==='shop_favourite'){
				$result=ShopManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,wishlist_table w,image_gallery i WHERE w.uid='".$uid."' AND w.id=p.pid AND p.sid='".$sid."' AND p.pid=i.id");
			}

			return ShopManager::resultBuilder($result);
		}

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
		public static function getSearchResult($db,$search,$sid){

			$search=strtolower(trim($search));
			$search2=strtr($search, " ,-_='","++++++");

			$result=ProductManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE (match(p.pname,p.ptag) against('+".$search2."')) AND p.sid='".$sid."' AND p.pid=i.id ORDER BY p.prating LIMIT 20");

			return ShopManager::resultBuilder($result);
		}

		public function productSortFilter($db,$sort=false,$filter=false,$sid,$search,$param,$range=array()){

			$search=strtolower(trim($search));
			$search2=strtr($search, " ,-_=","+++++");

			if($sort&&$filter){
				$result=$this->sortWithFilter($db,$param[1],$this->sort[$param[0]],$sid,$search,$range);
			}
			else if($sort){
				
				$result=$this->queryParse($db,"SELECT p.* FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid='".$sid."' ORDER BY ".$this->sort[$param[0]]." LIMIT 20");
			}
			else if($filter){
				
				$result=$this->sortWithFilter($db,$param[1],"p.prating DESC",$sid,$search,$range);	
			}
			
			if($result["progress"]){

				$product=new ProductParser();
				
				$list=array();

				while ( $row=mysqli_fetch_assoc($result["object"])) {

					$data=$this->queryParse("SELECT photo_url FROM image_gallery WHERE id='".$row['pid']."'");

					if($data['progress']){
						$photo=mysqli_fetch_assoc($data["object"]);

						array_push($list, $product->getRowData($row,$photo["photo_url"],true));
					}
				}

				return json_encode(array("success"=>true,"list"=>$list));
			}
			else{
				return json_encode(array("success"=>false,"error"=>"No Product Between this Price Range"));
			}
		}

		public function sortWithFilter($db,$type,$query="",$sid,$search,$range=array()){

			$search2=strtr($search, " ,-_=","+++++");

			if($type==="price"){
				$result=$this->queryParse($db,"SELECT p.* FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid='".$sid."' AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="discount"){
				$result=$this->queryParse($db,"SELECT p.* FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid='".$sid."' AND p.pdiscount BETWEEN ".$range[3]." AND 100 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="prate"){
				$result=$this->queryParse($db,"SELECT p.* FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid='".$sid."' AND p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_discount"){
				$result=$this->queryParse($db,"SELECT p.* FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid='".$sid."' AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.pdiscount BETWEEN ".$range[3]." AND 100 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_prate"){
				$result=$this->queryParse($db,"SELECT p.* FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid='".$sid."' AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="discount_prate"){
				$result=$this->queryParse($db,"SELECT p.* FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid='".$sid."' AND p.pdiscount BETWEEN ".$range[3]." AND 100 p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_discount_prate"){
				$result=$this->queryParse($db,"SELECT p.* FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid='".$sid."' AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[2]." AND 5 AND p.pdiscount BETWEEN ".$range[3]." AND 100 ORDER BY ".$query." LIMIT 20");
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
 
		$discount=0;
		$pricemin=0;
		$pricemax=0;
		$prate=0;
		
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

		$param=explode(',', $_POST["param"]);

		//for no sort and filter
		if($param[0]==='0'&&$param[1]==='0'){
			ShopManager::getSearchResult($db,$_POST["searchshopfor"],$_POST['sid']);
		}
		//for sorting with filters
		if($param[0]!=='0'&&$param[1]!=='0'){
			$search->productSortFilter($db,true,true,$_POST['sid'],$_POST["searchshopfor"],$param,array($pricemin,$pricemax,$prate,$discount));
		}
		//for only sorting
		else if($param[0]!=='0'){
			$search->productSortFilter($db,true,false,$_POST['sid'],$_POST["searchshopfor"],$param);
		}
		//for only filter
		else if($param[1]!=='0'){
			$search->productSortFilter($db,false,true,$_POST['sid'],$_POST["searchshopfor"],$param,array($pricemin,$pricemax,$prate,$discount));
		}

	}
?>