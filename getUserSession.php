<?php
	/*the purpose of this file is to get the user session*/
	include('tabgen_php_functions.php');
	include('ConnectAPI.php');
	include('connect_db.php');
	//session_start();
	if(isset($_COOKIE['user_details']) && $_COOKIE['user_details']!="" && isset($_COOKIE['login_header_response']) && $_COOKIE['login_header_response']!=""){
		$user_data = json_decode($_COOKIE['user_details']);
		if($user_data!=null){
			$user_id = getUserIdByToken($conn,get_token());
			if($user_id==null){
				echo "null";
			}
			else{ 
				$user_data->token=get_token();
				echo json_encode($user_data);
			}
		}
		else echo "null";	
	}
	else 
		echo "null";
	
?>
