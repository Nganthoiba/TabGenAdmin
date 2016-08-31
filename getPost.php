<?php 
	//php code for getting the first 60 post messages
	$channel_id = $_GET['channel_id'];
	$token = $_GET['token'];
	$user_id = $_GET['user_id'];
	
	include('connect_db.php');
	include('ConnectAPI.php');
	include('tabgen_php_functions.php');
	
	$url="http://".IP.":8065/api/v1/channels/".$channel_id."/posts/0/60";
	$getPosts = new ConnectAPI();
	$result = $getPosts->getDataByToken($url,$token);
	$decoded_res = json_decode($result);
	foreach($decoded_res->posts as $post_id => $post_details){
		$decoded_res->posts->$post_id->sender_name=getUserNameById($conn,$decoded_res->posts->$post_id->user_id);
		$decoded_res->posts->$post_id->no_of_reply=getNoOfReplies($conn,$post_id);
		$decoded_res->posts->$post_id->no_of_likes=getNoOfLikes($conn,$post_id);
		$decoded_res->posts->$post_id->isLikedByYou=isAlreadyLiked($conn,$post_id,$user_id);
		$decoded_res->posts->$post_id->isBookmarkedByYou=isAlreadyBookmarked($conn,$post_id,$user_id);
	}
	echo json_encode($decoded_res);
?>
