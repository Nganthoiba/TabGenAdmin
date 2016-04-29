<?php 
	//php code for getting post messages
	$channel_id = $_GET['channel_id'];
	$token = $_GET['token'];
	
	include('connect_db.php');
	include('ConnectAPI.php');
	include('tabgen_php_functions.php');
	
	$url="http://".IP.":8065/api/v1/channels/".$channel_id."/posts/0/20";
	$getPosts = new ConnectAPI();
	$result = $getPosts->getDataByToken($url,$token);
	$decoded_res = json_decode($result);
	foreach($decoded_res->order as $post_id){
		$decoded_res->posts->$post_id->no_of_reply=getNoOfReplies($conn,$post_id);
	}
	echo json_encode($decoded_res);
?>
