<?php 
	//php code for getting post messages
	$token = $_GET['token'];
	$user_id = $_GET['user_id'];
	$post_id = $_GET['post_id'];
	include('connect_db.php');
	include('ConnectAPI.php');
	include('tabgen_php_functions.php');
	
	$channel_id = null;
	if(!empty($_GET['channel_id']))
		$channel_id = $_GET['channel_id'];
	else
		$channel_id = getChannelIdByPost_id($conn,$post_id);
	
	$url="http://".IP.":8065/api/v1/channels/".$channel_id."/post/".$post_id;
	$getPosts = new ConnectAPI();
	$result = $getPosts->getDataByToken($url,$token);
	$decoded_res = json_decode($result);
	foreach($decoded_res->posts as $post_id => $post_details){
		$decoded_res->posts->$post_id->no_of_reply=getNoOfReplies($conn,$post_id);
		$decoded_res->posts->$post_id->no_of_likes=getNoOfLikes($conn,$post_id);
		$decoded_res->posts->$post_id->isLikedByYou=isAlreadyLiked($conn,$post_id,$user_id);
		$decoded_res->posts->$post_id->isBookmarkedByYou=isAlreadyBookmarked($conn,$post_id,$user_id);
	}
	echo json_encode($decoded_res);
?>

