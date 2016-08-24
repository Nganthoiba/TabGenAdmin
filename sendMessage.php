<?php 
	//php code for sending post messages
	header('Content-Type: application/json');
	include('connect_db.php');
	include('ConnectAPI.php');
	include('tabgen_php_functions.php');
	
	
	$channel_id = $_POST['channel_id'];
	$token = $_POST['token'];
	$user_id = $_POST['user_id'];
	$Message = empty($_POST['Message'])?"":$_POST['Message'];
	$root_id = empty($_POST['root_id'])?"":$_POST['root_id'];
	$parent_id = empty($_POST['parent_id'])?"":$_POST['parent_id'];
	$filenames = empty($_POST['filenames'])?"":array($_POST['filenames']);
	
	$data = !empty($_POST['filenames'])?json_encode(
			array(	"channel_id"=>$channel_id,
					"message"=>$Message,
					"root_id"=>$root_id,
					"parent_id"=>$parent_id,
					"filenames"=>$filenames)):json_encode(
			array(	"channel_id"=>$channel_id,
					"message"=>$Message,
					"root_id"=>$root_id,
					"parent_id"=>$parent_id));
	
	$url = "http://".IP.":8065/api/v1/channels/".$channel_id."/create";
	$sendPosts = new ConnectAPI();
	$result = $sendPosts->sendPostDataWithToken($url,$data,$token);

	if($sendPosts->httpResponseCode==200){
		$decoded_res = json_decode($result);
		$decoded_res->sender_name=getUserNameById($conn,$decoded_res->user_id);
		echo json_encode($decoded_res);
		$fcm_tokens = get_notification_tokens_for_chat_tabs($conn,$decoded_res->id,$decoded_res->user_id);
		$decoded_res->notification_type=$root_id==""?"new_post":"comment";
		sendFirebasedCloudMessage($fcm_tokens, array("message"=>$decoded_res));//notifying message to other app
	}
	else echo $result;
?>
