<?php

	if(file_exists("banner.json")){
		
		$json=file_get_contents("banner.json");
		$data=json_decode($json,true);
		$pics=array();
		array_push($pics,$data['images']['A'], 
											 $data['images']['B'],
											$data['images']['C'],
											 $data['images']['D'],
											 $data['images']['E']);

		echo json_encode(array('success' =>true ,
								'data'=>$pics 
								));
	}
	else{
		echo json_encode(array('success' => false,'error'=>'File Does not exist'));
	}

?>