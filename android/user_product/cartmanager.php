<?php
	
	require_once('../dbconfig.php');

	class CartManager
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
			$sql="SELECT uid,uemail FROM user_table WHERE uid='".$this->uid."' AND uemail='".$this->email."'";

			$data=mysqli_query($this->db,$sql);
			if(mysqli_num_rows($data)>0){
				return true;
			}
			else{
				return false;
			}
		}

		public function checkItem($pid){
			$result=$this->queryParse("SELECT * FROM cart_table WHERE uid='".$this->uid."' AND pid='".$pid."'");

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

		public function checkProduct($pid){
			$result=$this->queryParse("SELECT * FROM product_table WHERE pid='".$pid."'");

			return $result["progress"];
		}

		public function updateCart($pid,$quantity,$add=true){
			if($add)
				$quantity=$quantity+$this->getQuantity($pid);

			$stock=json_decode($this->checkStock($pid,$quantity),true);

			if($stock['success']&&$stock['status']==='In Stock'){

				$sql="UPDATE cart_table SET quantity=".$quantity." WHERE pid='".$pid."'";

				$data=mysqli_query($this->db,$sql);

				if($data===TRUE){
					echo json_encode(array("success"=>true,"error"=>mysqli_error($this->db),"status"=>"Cart Updated"));
				}
				else{
					echo json_encode(array("success"=>false,"error"=>'Unable to add item to cart'));
				}
			}
			else if($stock['success']&&$stock['status']==='Quantity more than stock'){

				echo json_encode(array("success"=>false,"error"=>'Quantity is Less'));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>$stock['status']));
			}
		}

		public function addItem($pid,$quantity){

			$stock=json_decode($this->checkStock($pid,$quantity),true);

			if($stock['success']&&$stock['status']==='In Stock'){

				$sql="INSERT INTO cart_table (uid,pid,quantity,date,time) VALUES('".$this->uid."','".$pid."','".$quantity."',NOW(),NOW())";

				$data=mysqli_query($this->db,$sql);

				if($data===TRUE){
					echo json_encode(array("success"=>true,"error"=>mysqli_error($this->db),"status"=>"Product Added to cart"));
				}
				else{
					echo json_encode(array("success"=>false,"error"=>'Unable to add item to cart'));
				}
			}
			else if($stock['success']&&$stock['status']==='Quantity more than stock'){

				echo json_encode(array("success"=>false,"error"=>'Quantity is Less'));
			}
			else{
				echo json_encode(array("success"=>false,"error"=>$stock['status']));
			}
		}

		public function getQuantity($pid){
			$result=$this->queryParse("SELECT quantity FROM cart_table WHERE pid='".$pid."'");
			
			$data=mysqli_fetch_assoc($result["object"]);

			return $data["quantity"];
		}

		public function removeFromCart($pid){
			$sql="DELETE FROM cart_table WHERE uid='".$this->uid."' AND pid='".$pid."'";

			$data=mysqli_query($this->db,$sql);

			if($data===TRUE){
				return true;
			}	
			else{
				return false;
			}
		}

		public function checkStock($pid,$quantity){
			//if($this->checkItem($pid)){
				$result=$this->queryParse("SELECT pquantity FROM product_table WHERE pid='".$pid."' AND pquantity>0");

				if($result["progress"]){
					$result=$this->queryParse("SELECT pquantity FROM product_table WHERE pid='".$pid."' AND pquantity>=".$quantity);

					if($result["progress"]){
						return json_encode(array("success"=>true,"status"=>"In Stock"));
					}
					else{
						return json_encode(array("success"=>true,"status"=>"Quantity more than stock"));
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

		public function getCart(){
			$sql="SELECT pid,quantity FROM cart_table WHERE uid='".$this->uid."' ORDER BY date DESC,time DESC";

			$result=mysqli_query($this->db,$sql);

			if(mysqli_num_rows($result)>0){
				$parray=array();
				while($row=mysqli_fetch_assoc($result)){

					$query=$this->queryParse("SELECT * FROM product_table WHERE pid='".$row["pid"]."'");
					$query2=$this->queryParse("SELECT photo_url FROM image_gallery WHERE id='".$row["pid"]."' AND type='product'");

					if($query["progress"] && $query2["progress"]){
						$product=mysqli_fetch_assoc($query["object"]);
						$image=mysqli_fetch_assoc($query2["object"]);

						$item=array(
							'id'=>$product['pid'],
							'sid'=>$product['sid'],
							'name'=>$product['pname'],
							'mrp'=>$product['pmrp'],
							'actualprice'=>$product['pactualprice'],
							'stockquantity'=>$product['pquantity'],
							'cartquantity'=>$row['quantity'],
							'rating'=>$product['prating'],
							'offer'=>$product['poffer'],
							'photo'=>explode(",", $image['photo_url'])
							);
						array_push($parray,$item);
					}
					else{
						continue;
					}
				}
				echo json_encode(array("success"=>true,"error"=>mysqli_error($this->db),"product"=>$parray,"length"=>count($parray)));
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

		$cart=new CartManager($_POST['id'],$_POST['email']);

		if($cart->verifyUser()){
			
			if($_POST['data']==='cart'){
				$cart->getCart();
			}
			else if($_POST['data']==='checkitem' && isset($_POST['pid'])){
				if($cart->checkItem($_POST['pid'])){
					echo json_encode(array("success"=>true));
				}
				else{
					echo json_encode(array("success"=>false,"error"=>"Unable To Check Existance of Product In Cart"));
				}
			}
			else if($_POST['data']==='checkstock' && isset($_POST['pid']) && isset($_POST['quantity'])){
				echo $cart->checkStock($_POST['pid'],$_POST['quantity']);
			}
			else if($_POST['data']==='delete' && isset($_POST['pid'])){
				if($cart->checkItem($_POST['pid'])){
					if($cart->removeFromCart($_POST['pid'])){
						echo json_encode(array("success"=>true));
					}
					else{
						echo json_encode(array("success"=>false,"error"=>"Unable To Remove Product From Cart"));
					}
				}
				else{
					echo json_encode(array("success"=>false,"error"=>"No Product Found"));	
				}
			}
			else if($_POST['data']==='add' && isset($_POST['pid']) && isset($_POST['quantity'])){
				$cart->addToCart($_POST['pid'],$_POST['quantity']);
			}
			else if($_POST['data']==='update' && isset($_POST['pid']) && isset($_POST['quantity'])){
				$cart->updateCart($_POST['pid'],$_POST['quantity'],false);
			}
		}
		else{
			echo json_encode(array("success"=>false,"error"=>"User Not Found"));
		}
	}

?>