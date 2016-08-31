<?php
/*Mobile app api: code for like and dislike of a post*/
	include('connect_db.php');
	include('tabgen_php_functions.php');
		
	$post_id = $_POST['post_id'];
	$user_id = $_POST['user_id'];
	
	if(isAlreadyLiked($conn,$post_id,$user_id)){//checks if the user has already liked the post. If so then dislike the post
		if(dislikeAPost($conn,$post_id,$user_id)){
			echo json_encode(array("post_id"=>$post_id,"liked_status"=>"unliked","message"=>"You have disliked the post.",
			"no_of_likes"=>getNoOfLikes($conn,$post_id)));
		}
		else{
			echo json_encode(array("post_id"=>$post_id,"liked_status"=>"liked","message"=>"Sorry, unable to dislike the post.",
			"no_of_likes"=>getNoOfLikes($conn,$post_id)));
		}	
	}
	else{//otherwise like the post
		if(likeAPost($conn,$post_id,$user_id)){
			$fcm_tokens = get_notification_tokens_for_chat_tabs($conn,$post_id,$user_id);
			$channel_name = getChannelNameById($conn,getChannelIdByPost_id($conn,$post_id));
			$message = array("message"=>array("liker_id"=>$user_id,
											"liker_name"=>getUserDisplayNameById($conn,$user_id),
											"liked_post_id"=>$post_id,
											"ChannelId"=>getChannelIdByPost_id($conn,$post_id),
											"ChannelName"=>$channel_name,
											"TeamName"=>getOU_by_tab_Name($conn,$channel_name),
											"notification_type"=>"like"));
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
