<?php

	require_once('../dbconfig.php');

	class WishlistManager
	{
		private $uid;
		private $email;
		private $db;

		function __construct($uid,$email)
		{
			$this->uid=$uid;
			$this->email=$email;

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

		public function verifyUser(){
			$result=$this->queryParse("SELECT uid,uemail FROM user_table WHERE uid='".$this->uid."' AND uemail='".$this->email."'");

			return $result["progress"];
		}

		public function checkItem($pid){
			$result=$this->queryParse("SELECT * FROM wishlist_table WHERE uid='".$this->uid."' AND id='".$pid."'");

			return $result["progress"];
		}

		public function removeFromList($pid){
			$sql="DELETE FROM wishlist_table WHERE uid='".$this->uid."' AND id='".$pid."'";

			$data=mysqli_query($this->db,$sql);

			if($data===TRUE){
				return true;
			}	
			else{
				return false;
			}
		}

		public function checkStock($pid){
			$result=$this->queryParse("SELECT pquantity FROM product_table WHERE pid='".$pid."' AND pquantity>0");

			return $result["progress"];
		}

		public function addToWishlist($pid,$type){
			if($this->checkItem($pid)){
				if($this->removeFromList($pid)){
					$this->addItem($pid,$type);
				}
				else{
					echo json_encode(array("success"=>false,"error"=>"Unable To Remove Product From List"));
				}
			}
			else{
				$this->addItem($pid,$type);	
			}
		}

		public function addItem($pid,$type){
			$sql="INSERT INTO wishlist_table (uid,id,type,date,time) VALUES('".$this->uid."','".$pid."','".$type."',NOW(),NOW())";

			$data=mysqli_query($this->db,$sql);

			if($data===TRUE){
				echo json_encode(array("success"=>true,"error"=>mysqli_error($this->db)));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>mysqli_error($this->db)));
			}
		}

		public function getList($type){
			$sql="SELECT id FROM wishlist_table WHERE uid='".$this->uid."' AND type='".$type."' ORDER BY date DESC,time DESC";

			$result=mysqli_query($this->db,$sql);

			if(mysqli_num_rows($result)>0){
				$parray=array();
				while($row=mysqli_fetch_assoc($result)){

					if($type==="product"){
						$query=$this->queryParse("SELECT * FROM product_table WHERE pid='".$row["id"]."'");
					}
					else{
						$query=$this->queryParse("SELECT * FROM shop_table WHERE sid='".$row["id"]."'");
					}

					$query2=$this->queryParse("SELECT photo_url FROM image_gallery WHERE id='".$row["id"]."' AND type='".$type."'");

					if($query["progress"] && $query2["progress"]){
						$product=mysqli_fetch_assoc($query["object"]);
						$image=mysqli_fetch_assoc($query2["object"]);

						if($type==="product"){
							$item=array(
								'pid'=>$product['pid'],
								'sid'=>$product['sid'],
								'pname'=>$product['pname'],
								'pmrp'=>$product['pmrp'],
								'pactualprice'=>$product['pactualprice'],
								'prating'=>$product['prating'],
								'instock'=>$this->checkStock($product['pid']),
								'poffer'=>$product['poffer'],
								'photo'=>explode(",", $image['photo_url'])
								);
						}
						else{
							$item=array(
								'id'=>$product['sid'],
								'name'=>$product['sname'],
								'rating'=>$product['srating'],
								'category'=>$product['stype'],
								'email'=>$product['semail'],
								'mobile'=>$product['smobile'],
								'photo'=>explode(",", $image['photo_url'])
								);	
						}
						array_push($parray,$item);
					}
					else{
						continue;
					}
				}
				echo json_encode(array("success"=>true,"error"=>mysqli_error($this->db),"list"=>$parray,"length"=>count($parray)));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>"No Product Found"));
			}
		}

		public function sortWishList($type,$uid,$value){
			$sort=array("1"=>"p.pactualprice ASC","2"=>"p.pactualprice DESC","3"=>"w.date DESC,w.time DESC");

			$result=$this->queryParse("SELECT w.*,p.pname,p.pquantity,p.pactualprice,i.photo_url FROM wishlist_table w,image_gallery i,product_table p WHERE w.uid='".$uid."' AND w.id=i.id AND w.type='".$type."' AND w.id=p.pid ORDER BY ".$sort[$value]);

			if($result["progress"]){

				$list=array();
				while($item=mysqli_fetch_assoc($result["object"])){

					$photo=array('photo'=>explode(',', $item['photo_url']));
					array_pop($item);

					$item=array_merge($item,$photo);

					if($item['pquantity']>0){
						$item=array_merge($item, array("instock"=>true));	
					}
					else{
						$item=array_merge($item, array("instock"=>false));		
					}

					array_push($list, $item);
				}
				
				echo json_encode(array("success"=>true,"list"=>$list));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>"No Product Found"));
			}
		}

		public function queryParse($sql){

			$result=mysqli_query($this->db,$sql);

			if(mysqli_num_rows($result)>0){
				return array("progress"=>true,"object"=>$result);
			}
			else{
				return array("progress"=>false);
			}
		}

	}

	if(isset($_POST['id'])&&isset($_POST['email'])&&isset($_POST['data'])){

		$wish=new WishListManager($_POST['id'],$_POST['email']);

		if($wish->verifyUser()){
			if($_POST['data']==='list' && isset($_POST['type'])){
				
				$wish->getList($_POST['type']);
			}
			else if($_POST['data']==='checkitem' && isset($_POST['pid'])){
				
				if($wish->checkItem($_POST['pid'])){
					echo json_encode(array("success"=>true));
				}
				else{
					echo json_encode(array("success"=>false,"error"=>"Unable To Check Existance of Product In List"));
				}
			}
			else if($_POST['data']==='checkstock' && isset($_POST['pid'])){
				
				if($wish->checkStock($_POST['pid'])){
					echo json_encode(array("success"=>true,"status"=>"In Stock"));	
				}
				else{
					echo json_encode(array("success"=>false,"status"=>"Out Of Stock"));
				}
			}
			else if($_POST['data']==='delete' && isset($_POST['pid'])){
				
				if($wish->checkItem($_POST['pid'])){
					if($wish->removeFromList($_POST['pid'])){
						echo json_encode(array("success"=>true));
					}
					else{
						echo json_encode(array("success"=>false,"error"=>"Unable To Remove Product From List"));
					}
				}
				else{
					echo json_encode(array("success"=>false,"error"=>"No Product Found"));	
				}
			}
			else if($_POST['data']==='add' && isset($_POST['id']) && isset($_POST['type'])){
				$wish->addToWishlist($_POST['pid'],$_POST['type']);
			}
			else if($_POST['data']==='sort'&& isset($_POST['value'])){
				$wish->sortWishList($_POST['type'],$_POST['id'],$_POST['value']);
			}
		}
		else{
			echo json_encode(array("success"=>false,"error"=>"User Not Found"));
		}

	}
?>