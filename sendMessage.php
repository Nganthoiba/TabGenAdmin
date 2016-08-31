<?php 
	//Mobile app api: php code for sending messages
	
	header('Content-Type: application/json');
	include('connect_db.php');
	include('ConnectAPI.php');
	include('tabgen_php_functions.php');
					
	$data = file_get_contents("php://input");
	$channel_id = json_decode($data)->channel_id;
	$root_id = json_decode($data)->root_id;
	$token = str_replace(' ','',str_replace('Bearer','',get_token_from_header()));
	
	$url = "http://".IP.":8065/api/v1/channels/".$channel_id."/create";
	$sendPosts = new ConnectAPI();
	$result = $sendPosts->sendPostDataWithToken($url,$data,$token);

	if($sendPosts->httpResponseCode==200){
		$decoded_res = json_decode($result);
		$decoded_res->sender_name = getUserNameById($conn,$decoded_res->user_id);
		$channel_name = getChannelNameById($conn,$channel_id);
		$fcm_tokens = get_notification_tokens_for_chat_tabs($conn,$decoded_res->id,$decoded_res->user_id);
		$decoded_res->notification_type=$root_id=="" || $root_id==null?"new_post":"comment";
		$decoded_res->ChannelName=$channel_name;
		$decoded_res->TeamName=getOU_by_tab_Name($conn,$channel_name);
		echo json_encode($decoded_res);
		sendFirebasedCloudMessage($fcm_tokens, array("message"=>$decoded_res));//notifying message to other devices using the apps
	}
	else echo $result;
?>
