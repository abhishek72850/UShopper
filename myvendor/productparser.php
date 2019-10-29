<?php

	class ProductParser
	{
		private $item;
		
		function __construct()
		{
			
		}

		function getItem(){
			return $this->item;
		}

		function getProductDetail($db,$pid){
			$sql="SELECT * FROM product_table WHERE pid='".$pid."'";

			$data=mysqli_query($db,$sql);
			if(mysqli_num_rows($data)>0){
				$row=mysqli_fetch_assoc($data);

				$sql="SELECT photo_url FROM image_gallery WHERE id='".$row['pid']."'";
				$data=mysqli_query($db,$sql);
				$photo=mysqli_fetch_assoc($data);
				$this->item=array(
							'pid'=>$row['pid'],
							'sid'=>$row['sid'],
							'name'=>$row['pname'],
							'mrp'=>$row['pmrp'],
							'actualprice'=>$row['pactualprice'],
							'quantity'=>$row['pquantity'],
							'feature'=>$row['pfeature'],
							'specs'=>$row['pspecs'],
							'tag'=>$row['ptag'],
							'rating'=>$row['prating'],
							'offer'=>$row['poffer'],
							'date'=>$row['pdate'],
							'time'=>$row['ptime'],
							'sold'=>$row['psold'],
							'photo'=>explode(",", $photo['photo_url'])
						);
				return json_encode(
					array(
						'success' =>true ,
						'error'=>mysqli_error($db),
						'data'=>$this->item
						) 
					);

			}else{
				return json_encode(array('success'=>false,'error'=>mysqli_error($db)));

			}
		}

		public function getRowData($row,$photo,$shop=false){
			$this->item=array(
							'pid'=>$row['pid'],
							'sid'=>$row['sid'],
							'name'=>$row['pname'],
							'mrp'=>$row['pmrp'],
							'actualprice'=>$row['pactualprice'],
							'quantity'=>$row['pquantity'],
							'feature'=>$row['pfeature'],
							'specs'=>$row['pspecs'],
							'tag'=>$row['ptag'],
							'rating'=>$row['prating'],
							'offer'=>$row['poffer'],
							'date'=>$row['pdate'],
							'time'=>$row['ptime'],
							'sold'=>$row['psold'],
							'photo'=>explode(",", $photo)
						);
			if($shop){
				return array_merge($this->item,array('srate'=>$row['srating'],'sname'=>$row['sname']));
			}else{
				return $this->item;
			}
		}
	}
?>