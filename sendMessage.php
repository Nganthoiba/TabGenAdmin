<?php 
	//php code for sending post messages
	
	header('Content-Type: application/json');
	include('connect_db.php');
	include('ConnectAPI.php');
	include('tabgen_php_functions.php');
					
	$data = file_get_contents("php://input");
	$channel_id = json_decode($data)->channel_id;
	$token = str_replace(' ','',str_replace('Bearer','',get_token_from_header()));
	
	$url = "http://".IP.":8065/api/v1/channels/".$channel_id."/create";
	$sendPosts = new ConnectAPI();
	$result = $sendPosts->sendPostDataWithToken($url,$data,$token);

	if($sendPosts->httpResponseCode==200){
		$decoded_res = json_decode($result);
		$decoded_res->sender_name=getUserNameById($conn,$decoded_res->user_id);
		echo json_encode($decoded_res);
		$fcm_tokens = get_notification_tokens_for_chat_tabs($conn,$decoded_res->id,$decoded_res->user_id);
		$decoded_res->notification_type=$root_id==""?"new_post":"comment";
		$decoded_res->ChannelName=getChannelNameById($conn,$channel_id);
		sendFirebasedCloudMessage($fcm_tokens, array("message"=>$decoded_res));//notifying message to other devises using the apps
	}
	else echo $result;
?>
