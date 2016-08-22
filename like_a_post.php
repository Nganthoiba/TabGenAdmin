<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');
		
	$post_id = $_POST['post_id'];
	$user_id = $_POST['user_id'];
	
	if(isAlreadyLiked($conn,$post_id,$user_id)){
		if(dislikeAPost($conn,$post_id,$user_id)){
			echo json_encode(array("post_id"=>$post_id,"liked_status"=>"unliked","message"=>"You have unliked the post.",
			"no_of_likes"=>getNoOfLikes($conn,$post_id)));
		}
		else{
			echo json_encode(array("post_id"=>$post_id,"liked_status"=>"liked","message"=>"Sorry, unable unlike the post.",
			"no_of_likes"=>getNoOfLikes($conn,$post_id)));
		}	
	}
	else{
		if(likeAPost($conn,$post_id,$user_id)){
			$fcm_tokens = get_notification_tokens_for_chat_tabs($conn,$post_id,$user_id);
			$message = array("message"=>array("liker_id"=>$user_id,
														"liker_name"=>getUserDisplayNameById($conn,$user_id),
														"liked_post_id"=>$post_id));
			sendFirebasedCloudMessage($fcm_tokens, $message);//notifying message to other app
			echo json_encode(array("post_id"=>$post_id,"liked_status"=>"liked","message"=>"You have liked the post.",
			"no_of_likes"=>getNoOfLikes($conn,$post_id)));
			
		}
		else{
			echo json_encode(array("post_id"=>$post_id,"liked_status"=>"unliked","message"=>"Sorry, you could not like the post.",
			"no_of_likes"=>getNoOfLikes($conn,$post_id)));
		}
	}
?>
