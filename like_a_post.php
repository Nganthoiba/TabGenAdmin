<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');
		
	$post_id = $_POST['post_id'];
	$user_id = $_POST['user_id'];
	
	if(isAlreadyLiked($conn,$post_id,$user_id)){
		if(unlikeAPost($conn,$post_id,$user_id)){
			echo json_encode(array("liked_status"=>"unliked","message"=>"You have unliked the post."));
		}
		else{
			echo json_encode(array("liked_status"=>"liked","message"=>"Sorry, unable unlike the post."));
		}	
	}
	else{
		if(likeAPost($conn,$post_id,$user_id)){
			echo json_encode(array("liked_status"=>"liked","message"=>"You have liked the post."));
		}
		else{
			echo json_encode(array("liked_status"=>"unliked","message"=>"Sorry, you could not like the post."));
		}
	}
	


?>
