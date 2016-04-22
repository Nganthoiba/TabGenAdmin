<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');
	$org_unit_id = $_POST['org_unit_id'];
	$time = time();
	
	if($conn){
		if(deleteOU($conn,$org_unit_id)){
			echo "true";
		}
		else {
			echo "false";
		}
	}
	
	
?>
