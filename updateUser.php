<?php
include('connect_db.php');
include('tabgen_php_functions.php');

if(!empty($_POST['user_id'])){
	$user_display = $_POST['display_name'];
	$user_id = $_POST['user_id'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$updateTime = time();
	
	$query="Update Users set UpdateAt='time()',FirstName='$user_display',Username='$username',Email='$email' 
			where Id='$user_id'";
	if($conn->query($query)){
		echo json_encode(array("state"=>true,"message"=>"Successfully updated."));
	}
	else{
		echo json_encode(array("state"=>false,"message"=>"Sorry, we could not update."));
	}			
}
else{
	echo json_encode(array("status"=>false,"message"=>"Invalid request"));
}
?>
