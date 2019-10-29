<?php

	class CartManager
	{
		function __construct()
		{
		}

		public static function verifyUser($db,$uid,$email){
			$sql="SELECT uid,uemail FROM user_table WHERE uid='".$uid."' AND uemail='".$email."'";

			$data=mysqli_query($this->db,$sql);
			if(mysqli_num_rows($data)>0){
				return true;
			}
			else{
				return false;
			}
		}

		public static function checkItem($db,$uid,$pid){
			$result=CartManager::queryParse($db,"SELECT * FROM cart_table WHERE uid='".$uid."' AND pid='".$pid."'");

			return $result["progress"];
		}

		public function addToCart($pid,$quantity){
			if($this->checkProduct($pid)){
				if($this->checkItem($pid)){
					$this->updateCart($pid,$quantity);
				}
				else{
					$this->addItem($pid,$quantity);	
				}
			}
			else{
				echo json_encode(array("success"=>false,"error"=>"There is no such Product"));
			}
		}

		public static function checkProduct($pid){
			$result=$this->queryParse("SELECT * FROM product_table WHERE pid='".$pid."'");

			return $result["progress"];
		}

		public static function updateCart($pid,$quantity){
			$quantity=$quantity+$this->getQuantity($pid);

			$sql="UPDATE cart_table SET quantity=".$quantity." WHERE pid='".$pid."'";

			$data=mysqli_query($this->db,$sql);

			if($data===TRUE){
				echo json_encode(array("success"=>true,"error"=>mysqli_error($this->db),"status"=>"Cart Updated"));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>mysqli_error($this->db)));
			}
		}

		public static function addItem($pid,$quantity){
			$sql="INSERT INTO cart_table (uid,pid,quantity,date,time) VALUES('".$this->uid."','".$pid."','".$quantity."',NOW(),NOW())";

			$data=mysqli_query($this->db,$sql);

			if($data===TRUE){
				echo json_encode(array("success"=>true,"error"=>mysqli_error($this->db),"status"=>"Product Added to cart"));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>mysqli_error($this->db)));
			}
		}

		public static function getQuantity($db,$pid){
			$result=$this->queryParse("SELECT quantity FROM cart_table WHERE pid='".$pid."'");
			
			$data=mysqli_fetch_assoc($result["object"]);

			return $data["quantity"];
		}

		public static function removeFromCart($db,$pid){
			$sql="DELETE FROM cart_table WHERE uid='".$this->uid."' AND pid='".$pid."'";

			$data=mysqli_query($this->db,$sql);

			if($data===TRUE){
				return true;
			}	
			else{
				return false;
			}
		}

		public static function checkStock($db,$uid,$pid,$quantity){
			//if(CartManager::checkItem($db,$uid,$pid)){
				$result=CartManager::queryParse($db,"SELECT pquantity FROM product_table WHERE pid='".$pid."' AND pquantity>0");

				if($result["progress"]){
					$result=CartManager::queryParse($db,"SELECT pquantity FROM product_table WHERE pid='".$pid."' AND pquantity>=".$quantity);

					if($result["progress"]){
						return json_encode(array("success"=>true,"status"=>"In Stock"));
					}
					else{
						return json_encode(array("success"=>false,"status"=>"Less Than Stock"));
					}
				}
				else{
					return json_encode(array("success"=>false,"status"=>"Out Of Stock"));
				}
			/*}
			else{
				return json_encode(array("success"=>false,"status"=>"No Product Found"));
			}*/
		}

		public static function checkCartStock($db,$uid){

			$result=self::queryParse($db,'SELECT c.quantity,p.pquantity,p.pname FROM cart_table c,product_table p WHERE c.uid="'.$uid.'" AND c.pid=p.pid');

			if($result['progress']){

				$items=array();
				while ($row=mysqli_fetch_assoc($result['object'])) {
					
					$instock=true;
					$item=array();

					if($row['pquantity']<=0){
						$instock=false;

						$item['instock']=false;
						$item['pname']=$row['pname'];
						$item['status']='Out Of Stock';
					}
					else{
						$item['pname']=$row['pname'];
						if($row['quantity']<=$row['pquantity']){
							$item['instock']=true;
							$item['status']='More Than Order';
						}
						else{
							$item['instock']=false;
							$item['status']='Less Than Order';	
						}
					}

					array_push($items, $item);	
				}

				return $items;
			}
			else{

				return false;
			}
		}

		public static function checkProductStock($db,$pid,$quantity){
			$result=self::queryParse($db,'SELECT pquantity,pname FROM product_table WHERE pid="'.$pid.'"');

			if($result['progress']){

				$item=mysqli_fetch_assoc($result['object']);

				if($item['pquantity']<=0){
					return array(false,'Out Of Stock',$item['pname']);
				}
				else if($quantity>$item['pquantity']){
					return array(false,'Less Than Order',$item['pname']);
				}
				else{
					return array(true,'In Stock',$item['pname']);	
				}
			}
			else{
				return array(false,'Item Not Found','');
			}
		}

		public static function getCart($db,$uid){

			$result=CartManager::queryParse($db,"SELECT pid,quantity FROM cart_table WHERE uid='".$uid."' ORDER BY date DESC,time DESC");

			if($result['progress']){
				
				$parray=array();
				while($row=mysqli_fetch_assoc($result['object'])){

					$data=CartManager::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE p.pid='".$row["pid"]."' AND p.pid=i.id");

					if($data["progress"]){

						$product=mysqli_fetch_assoc($data["object"]);

						$item=array(
							'pid'=>$product['pid'],
							'sid'=>$product['sid'],
							'pname'=>$product['pname'],
							'pmrp'=>$product['pmrp'],
							'pactualprice'=>$product['pactualprice'],
							'stockquantity'=>$product['pquantity'],
							'cartquantity'=>$row['quantity'],
							'prating'=>$product['prating'],
							'poffer'=>$product['poffer'],
							'photo'=>explode(",", $product['photo_url'])
							);
						array_push($parray,$item);
					}
					else{
						continue;
					}
				}
				return json_encode(array("success"=>true,"error"=>mysqli_error($db),"list"=>$parray,"length"=>count($parray)));
			}
			else{
				return json_encode(array("success"=>false,"error"=>"No Product Found"));
			}
		}

		public static function getBuyProduct($db,$id,$quantity){
			$result=self::queryParse($db,"SELECT p.*,i.photo_url FROM product_table p,image_gallery i WHERE p.pid='".$id."' AND i.id='".$id."'");

			if($result['progress']){
				
				$row=mysqli_fetch_assoc($result['object']);

				$item=array(
					'pid'=>$row['pid'],
					'sid'=>$row['sid'],
					'pname'=>$row['pname'],
					'pmrp'=>$row['pmrp'],
					'pactualprice'=>$row['pactualprice'],
					'stockquantity'=>$row['pquantity'],
					'cartquantity'=>$quantity,
					'prating'=>$row['prating'],
					'poffer'=>$row['poffer'],
					'photo'=>explode(",", $row['photo_url'])
					);	
				
				if(intval($quantity)<=intval($row['pquantity']))
					return json_encode(array("success"=>true,"list"=>array($item),"length"=>count($item)));
				else
					return json_encode(array("success"=>false,"error"=>"Product is Out Of Stock"));
			}
			else{
				return json_encode(array("success"=>false,"error"=>"No Product Found"));
			}
		}
		
		public static function getCartCount($db,$id){

			$result=mysqli_query($db,"SELECT quantity FROM cart_table WHERE uid='".$id."'");

			if(mysqli_num_rows($result)>0){
				
				$count=0;
				while($row=mysqli_fetch_assoc($result)){
					$count+=$row['quantity'];
				}
				return $count;
			}
			else{
				return 0;
			}

		}

		public function queryParse($db,$sql){

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