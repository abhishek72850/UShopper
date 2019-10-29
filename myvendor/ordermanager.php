<?php

	class OrderManager
	{
		
		public static function generateOrderID($uid){

			$pre=time();
			$post=substr($uid, 9);

			$orderID=$pre.$post;

			return $orderID;

		}

		public static function placeOrder($db,$uid,$oid,$param=array()){

			if($param['itemType']==='cart'){
				$data=json_decode(CartManager::getCart($db,$uid),true);
			}
			else if($param['itemType']==='product'){
				$data=json_decode(CartManager::getBuyProduct($db,$param['itemId'],$param['iquantity']),true);
			}

			foreach ($data['list'] as $key => $value) {

				$result=mysqli_query($db,'INSERT INTO billing_table (order_id,uid,pid,quantity,aid,adrType,adrLat,adrLng,adrRaw,order_date,order_time,invoice_path,status,paymode) VALUES("'.$oid.'","'.$uid.'","'.$value["pid"].'","'.$value["cartquantity"].'","'.$param["adrId"].'","'.$param["adrType"].'",'.$param["adrlat"].','.$param["adrlng"].',"'.$param["adrString"].'",NOW(),NOW(),"no","pending","'.$param["paymode"].'")');

				if($result){

					if($param['itemType']==='cart'){
						$sql="DELETE FROM cart_table WHERE uid='".$uid."' AND pid='".$value['pid']."'";

						mysqli_query($db,$sql);
					}

					$quantity= intval($value['stockquantity'])-intval($value['cartquantity']);
					$product=self::queryParse($db,'UPDATE product_table SET `pquantity`='.$quantity.' WHERE pid="'.$value['pid'].'"');
				}
				else{
					return false;
				}
 
			}

			return true;
		}

		public static function getAllUserOrder($db,$uid){

			$result=self::queryParse($db,'SELECT b.order_id,b.order_date,p.*,i.photo_url FROM billing_table b,product_table p,image_gallery i WHERE b.uid="'.$uid.'" AND b.pid=p.pid AND b.pid=i.id AND p.pid=i.id');

			if($result['progress']){

				$item=array();
				while($row=mysqli_fetch_assoc($result['object'])){
					
					$row=array(
						"oid"=>$row['order_id'],
						"odate"=>$row['order_date'],
						"photo"=>explode(',',$row['photo_url']),
						"pname"=>$row['pname']
						);
					
					array_push($item, $row);
				}

				return json_encode(array('success'=>true,'list'=>$item));
			}
			else{
				return json_encode(array('success'=>false,'error'=>'No Product Found'));
			}
		}

		public static function getOrder($db,$oid,$uid){

			$result=self::queryParse($db,'SELECT b.quantity,p.*,i.photo_url FROM billing_table b,product_table p,image_gallery i WHERE b.order_id="'.$oid.'" AND b.uid="'.$uid.'" AND b.pid=p.pid AND b.pid=i.id AND p.pid=i.id');

			if($result['progress']){

				$item=array();
				while($row=mysqli_fetch_assoc($result['object'])){
					
					$row=array(
						"photo"=>explode(',',$row['photo_url']),
						"pname"=>$row['pname'],
						"quantity"=>$row['quantity'],
						"price"=>$row['pactualprice']
						);
					
					array_push($item, $row);
				}

				return json_encode(array('success'=>true,'list'=>$item));
			}
			else{
				return json_encode(array('success'=>false,'error'=>'No Product Found'));
			}

		}

		private function sendOrderEvent(){
			
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