<?php
header('Content-Type: application/json');
include ('connect_db');
if(empty($_POST['user_id'])){
	echo json_encode(array("status"=>false,"message"=>"Sorry! Registration failed. You have not passed user id"));
}
else if(empty($_POST['token_id'])){
	echo json_encode(array("status"=>false,"message"=>"Sorry! Registration failed. You have not passed token id from FCM"));
}
else{
	if($conn){
		$user_id = $_POST['user_id'];
		$token_id = $_POST['token_id'];
		$query="insert into FCM_Users(user_id,token_id) values('$user_id','$token_id')";
		if($conn->query($query)){
			echo json_encode(array("status"=>true,"message"=>"Great! You have been successfully registered."));
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Sorry!
			 Registration failed. Unable to execute the query statement."));
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Sorry! Registration failed. Failed to connect database."));
	}
}
?>
