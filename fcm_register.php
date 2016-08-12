<?php
/*this will register the user of the mobile app. Every time the user 
 * logs in the app on a particular mobile device, the mattermost api will give back the user details with his/her user id.
 * Then the mobile app need to request a token from the FCM server, with this token and user id the app will
 * request our local server to register(save) the details in our remote database in a separate table.
*/
 
header('Content-Type: application/json');
include('connect_db.php');
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
