<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
	include('ConnectAPI.php');
	if(!empty($_POST['tab_id'])){
		$tab_id = $_POST['tab_id'];
		
		if($conn){
			echo deleteATab($conn,$tab_id);
		}
		else{
			echo json_encode(array("state"=>false,"message"=>"Failed to Connect Database"));
		}
		
	}
	else{
		echo json_encode(array("state"=>false,"message"=>"Invalid Request"));
	}
	
	
?>
