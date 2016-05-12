<?php 
	//php code for getting post messages
	$channel_id = $_GET['channel_id'];
	$token = $_GET['token'];
	$user_id = $_GET['user_id'];
	$post_id = $_GET['post_id'];
		
	include('connect_db.php');
	include('ConnectAPI.php');
	include('tabgen_php_functions.php');
	
	$url="http://".IP.":8065/api/v1/channels/".$channel_id."/post/".$post_id."/before/0/10";
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
	//http://128.199.111.18:8065/api/v1/channels/6qiw4zhjdjnabgwsfw1axkijrc/post/f6bda3nx1ifd5noixi1qyhffrc/before/4/10
?>
