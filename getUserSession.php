<?php
	/*the purpose of this file is to get the user session*/
	include('tabgen_php_functions.php');
	include('ConnectAPI.php');
	session_start();
	if(isset($_SESSION['user_details']) && isset($_SESSION['login_header_response'])){
		$user_data = json_decode($_SESSION['user_details']);
		if($user_data!=null){
				$user_data->token=get_token();
		}
		echo json_encode($user_data);
	}
	else 
		echo "null";
	
?>
