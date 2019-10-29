<?php

	if(file_exists("banner.json")){
		
		$json=file_get_contents("banner.json");
		$data=json_decode($json,true);

		echo json_encode(array('success' =>true ,
								'data'=>array('A' => $data['images']['A'], 
											'B' => $data['images']['B'],
											'C' => $data['images']['C'],
											'D' => $data['images']['D'],
											'E' => $data['images']['E']) 
								));
	}
	else{
		echo json_encode(array('success' => false));
	}

?>