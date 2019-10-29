<?php
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	require_once('dbconfig.php');


	$database=new Database();
	$db=$database->getDbConnection();

	$sql="SELECT * FROM billing_table GROUP BY order_id";

	$result=mysqli_query($db,$sql);
	$item=array();

	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_assoc($result)){
			
			$row=array(
				"oid"=>$row['order_id'],
				"status"=>$row['status'],
				"address"=>"Allahabad"
				);
			
			array_push($item, $row);
		}
	}
	else{
		
	}
	
	//echo "event: abhishek\n";
	sleep(5);
	echo "data: ".json_encode($item)."\n\n";
	
	ob_end_flush();
	flush();
	
?>