<?php

	class SearchManager
	{
		private $db;
		
		private $sort=array('1'=>"p.pactualprice ASC",
			'2'=>"p.pactualprice DESC",
			'3'=>"p.prating DESC",
			'4'=>"p.pdate DESC,p.ptime DESC");

		private $ftype=array("price","discount","prate","srate","price_discount","price_prate","price_srate","discount_prate","discount_srate","prate_srate","price_discount_prate","discount_prate_srate","price_prate_srate","price_discount_srate","price_discount_prate_srate");
		
		function __construct()
		{
			require_once('../dbconfig.php');
			require_once("productparser.php");

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

		public function getAutoCompleteList($search){
			
			$search=strtolower(trim($search));
			$search=explode(" ",strtr($search, " ,-_=","     "));

			$list="";
			foreach ($search as $key => $value) {
				if($key===0)
					$list=$list.$value;
				else
					$list=$list."|".$value;
			}

			$result=$this->queryParse("SELECT ptag FROM product_table WHERE  ptag RLIKE '".$list."' OR pname RLIKE '".$list."' LIMIT 50");

			if($result["progress"]){
				
				$list=array();
				
				while($row=mysqli_fetch_assoc($result["object"])){
					
					foreach ($search as $key => $value) {
						foreach ($tag=explode(",", $row["ptag"]) as $key2 => $value2) {
							
							$found=false;
							foreach ($list as $key3 => $value3) {
								if(ucfirst($value2)===$value3)
									$found=true;
							}
							if(strstr($value2, $value)!==false){
								
								if(!$found){
									array_push($list, ucfirst($value2));
								}
							}
						}
					}
				}

				echo json_encode(array("success"=>true,"list"=>$list));
			}
			else{
				echo json_encode(array("success"=>false));
			}

		}

		public function getProductList($search){

			$search=strtolower(trim($search));
			$search2=strtr($search, " ,-_=","+++++");

			$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid LIMIT 20");

			if($result["progress"]){			
				
				$product=new ProductParser();

				$list=array();
				while($item=mysqli_fetch_assoc($result["object"])){

					$data=$this->queryParse("SELECT photo_url FROM image_gallery WHERE id='".$item['pid']."'");
					$photo=mysqli_fetch_assoc($data["object"]);

					array_push($list, $product->getRowData($item,$photo["photo_url"],true));
				}
				
				echo json_encode(array("success"=>true,"list"=>$list));

			}
			else{
				echo json_encode(array("success"=>false,"error"=>"No Product found with this tag or name"));
			}
		}

		public function getByBarcode($code){
			$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE p.barcode='".$code."' AND s.sid=p.sid");

			if($result["progress"]){
				$product=new ProductParser();

				$list=array();
				while ($row=mysqli_fetch_assoc($result["object"])) {
					
					$data=$this->queryParse("SELECT photo_url FROM image_gallery WHERE id='".$row['pid']."'");
					$photo=mysqli_fetch_assoc($data["object"]);

					array_push($list, $product->getRowData($row,$photo["photo_url"],true));
				}

				echo json_encode(array("success"=>true,"list"=>$list));
			}else{
				echo json_encode(array("success"=>false,"error"=>"No Product found with this Barcode"));
			}
		}

		public function productSortFilter($sort=false,$filter=false,$search,$param,$range=array()){

			$search=strtolower(trim($search));
			$search2=strtr($search, " ,-_=","+++++");

			if($sort&&$filter){
				$result=$this->sortWithFilter($param[1],$this->sort[$param[0]],$search,$range);
			}
			else if($sort){
				
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid ORDER BY ".$this->sort[$param[0]]." LIMIT 20");
			}
			else if($filter){
				if($param[1]===$this->ftype[14]||$param[1]===$this->ftype[12]||$param[1]===$this->ftype[11]){
					$result=$this->sortWithFilter($param[1],"p.prating DESC,s.srating DESC",$search,$range);
				}
				else if($param[1]===$this->ftype[0]||$param[1]===$this->ftype[1]||$param[1]===$this->ftype[2]||$param[1]===$this->ftype[4]||$param[1]===$this->ftype[5]||$param[1]===$this->ftype[7]||$param[1]===$this->ftype[10]){
					$result=$this->sortWithFilter($param[1],"p.prating DESC",$search,$range);
				}
				else if($param[1]===$this->ftype[3]||$param[1]===$this->ftype[6]||$param[1]===$this->ftype[9]||$param[1]===$this->ftype[8]||$param[1]===$this->ftype[13]){
					$result=$this->sortWithFilter($param[1],"s.srating DESC",$search,$range);
				}
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

				echo json_encode(array("success"=>true,"list"=>$list));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>"No Product Between this Price Range"));
			}
		}

		public function sortWithFilter($type,$query="",$search,$range=array()){

			$search2=strtr($search, " ,-_=","+++++");

			if($type==="price"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="srate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND s.srating BETWEEN ".$range[3]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="discount"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND p.pdiscount BETWEEN ".$range[4]." AND 100 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="prate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_srate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND s.srating BETWEEN ".$range[3]." AND 5 AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_discount"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.pdiscount BETWEEN ".$range[4]." AND 100 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_prate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="prate_srate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND s.srating BETWEEN ".$range[3]." AND 5 AND p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="discount_srate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND s.srating BETWEEN ".$range[3]." AND 5 AND p.pdiscount BETWEEN ".$range[4]." AND 100 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="discount_prate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND p.pdiscount BETWEEN ".$range[4]." AND 100 p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_discount_srate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND s.srating BETWEEN ".$range[3]." AND 5 AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.pdiscount BETWEEN ".$range[4]." AND 100 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_prate_srate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND s.srating BETWEEN ".$range[3]." AND 5 AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[4]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_discount_prate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p, shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.prating BETWEEN ".$range[2]." AND 5 AND p.pdiscount BETWEEN ".$range[4]." AND 100 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="discount_prate_srate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND s.srating BETWEEN ".$range[3]." AND 5 AND p.prating BETWEEN ".$range[2]." AND 5 AND p.pdiscount BETWEEN ".$range[4]." AND 100 ORDER BY ".$query." LIMIT 20");
			}
			else if($type==="price_discount_prate_srate"){
				$result=$this->queryParse("SELECT p.*,s.srating,s.sname FROM product_table p,shop_table s WHERE (MATCH(p.pname,p.ptag) AGAINST('+".$search2."') OR p.pname LIKE '%".$search."%') AND s.sid=p.sid AND p.pactualprice BETWEEN ".$range[0]." AND ".$range[1]." AND p.pdiscount BETWEEN ".$range[4]." AND 100 AND s.srating BETWEEN ".$range[3]." AND 5 AND p.prating BETWEEN ".$range[2]." AND 5 ORDER BY ".$query." LIMIT 20");
			}
			else{
				die("No Sort Or Filter found");
			}

			return $result;
		}

		protected function queryParse($sql){

			$result=mysqli_query($this->db,$sql);
			
			if(@mysqli_num_rows($result)>0){
				return array("progress"=>true,"object"=>$result);
			}
			else{
				return array("progress"=>false);
			}
		}
	}

	if(isset($_POST["search"])&&isset($_POST["data"])) {

		$search=new SearchManager();

		$discount=0;
		$pricemin=0;
		$pricemax=0;
		$prate=0;
		$srate=0;
		
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
		
		if($_POST["search"]==="list"){
			$search->getAutoCompleteList($_POST["data"]);
		}
		else if($_POST["search"]==="search"){
			$search->getProductList($_POST["data"]);
		}
		else if($_POST["search"]==="barcode"){
			$search->getByBarcode($_POST["data"]);	
		}

		$param=explode(',', $_POST['search']);

		//for no sort and filter
		if($param[0]==='0'&&$param[1]==='0'){
			$search->getProductList($_POST["data"]);
		}
		//for sorting with filters
		if($param[0]!=='0'&&$param[1]!=='0'){
			$search->productSortFilter(true,true,$_POST["data"],$param,array($pricemin,$pricemax,$prate,$srate,$discount));
		}
		//for only sorting
		else if($param[0]!=='0'){
			$search->productSortFilter(true,false,$_POST["data"],$param);
		}
		//for only filter
		else if($param[1]!=='0'){
			$search->productSortFilter(false,true,$_POST["data"],$param,array($pricemin,$pricemax,$prate,$srate,$discount));
		}

	}
?>