<?php

	class VariableCheck
	{

		//for checking whether there is any unknown parameter passed by get or post
		public static function isUnKnown($param,$field){

			$result=true;

			foreach ($param as $key => $value) {
				foreach ($field as $key2 => $value2) {
					if($key===$value2){
						$result=true;
						break;
					}
					else{
						$result=false;
					}
				}

			}
			return $result;
		}

		//validating sort value given ins correct or not
		public static function checkSortValue($sort){
			
			$type=array("1","2","3","4");

			foreach ($type as $key => $value) {
				if($value===$sort){
					return true;
				}
			}
			return false;
		}

		//validating filter value give is correct or not
		public static function checkFilterValue($filter){
			$ftype=array("price","discount","prate","srate","price_discount","price_prate","price_srate","discount_prate","discount_srate","prate_srate","price_discount_prate","discount_prate_srate","price_prate_srate","price_discount_srate","price_discount_prate_srate");

			foreach ($ftype as $key => $value) {
				if($value===$filter){
					return true;
				}	
			}
			return false;
		}

		//validating price range
		public static function checkPriceValue($min,$max){
			settype($min, "integer");
			settype($max, "integer");

			if($min===0&&$max===0){
				return true;
			}
			else if(($min>0&&$max<=5000&&$max>0)&&($max>$min)){
				return true;
			}
			else{
				return false;
			}
		}

		//validating discount value
		public static function checkDiscountValue($value){
			settype($value, "integer");

			if($value>=0&&$value<=100){
				return true;
			}
			else{
				return false;
			}
		}

		//validating product rating and shop rating value
		public static function checkRatingValue($value){
			settype($value, "integer");

			if($value>=0&&$value<=5){
				return true;
			}
			else{
				return false;
			}
		}
	}
?>