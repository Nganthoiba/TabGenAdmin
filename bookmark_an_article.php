<?php
/*Mobile app api: code for bookmarking and unbookmarking an article*/
include('connect_db.php');
include('tabgen_php_functions.php');
/*Client must send token id over http request header such that the key should be Authorization and the value must be token*/
$token = get_token_from_header();//getting token from header

if($token==null){
	echo json_encode(array("status"=>false,"message"=>"You have to pass your token id over http request header."));
}
else{
	$user_id = getUserIdByToken($conn,$token);
	if($user_id==null){
		echo json_encode(array("status"=>false,"message"=>"You have passed an invalid or expired token."));
	}
	else if(isValidUser($conn,$user_id)){//checks if the user is a valid
		$article_id = $_POST['article_id'];
		if(empty($article_id)){
			echo json_encode(array("status"=>false,"message"=>"You have to send the article id."));
		}
		else{
			
			
			/*checks if the article is already bookmarked by the user*/
			if(isArticleAlreadyBookmarked($conn,$article_id,$user_id)){
				/*Then unbookmark the article*/
				if(unbookmarkAnArticle($conn,$article_id,$user_id)){
					echo json_encode(array("status"=>true,"article_id"=>$article_id,"bookmark_status"=>"unbookmarked",
					"message"=>"You have removed the article from your bookmarked list."));
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"Sorry, your request culd not be fulfilled. Try later."));
				}
			}
			else{//otherwise bookmark the article
				if(bookmarkAnArticle($conn,$article_id,$user_id)){
					echo json_encode(array("status"=>true,"article_id"=>$article_id,"bookmark_status"=>"bookmarked",
					"message"=>"You have bookmarked the article."));
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"Sorry, your request culd not be fulfilled. Try later."));
				}
			}
			
			
				
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"You have passed an invalid user ID."));
	}
}
?>
