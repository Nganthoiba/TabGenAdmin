<?php
	/*Mobile app api: to bookmark a post or to remove a post from bookmarked list*/
	include('connect_db.php');
	include('tabgen_php_functions.php');

	$action = $_POST['action'];/*action specifies what to do with the back end,
	either to add a bookmark, or to remove a bookmark, or to get a list of bookmarks for a particular user*/
	
	if($action=="addBookmark"){
		if(empty($_POST['user_id'])){
			echo json_encode(array("bookmark_type"=>"add","status"=>false,"message"=>"You have not passed user id."));
		}
		else if(empty($_POST['post_id'])){
			echo json_encode(array("bookmark_type"=>"add","status"=>false,"message"=>"You have not passed post id."));
		}
		else{
			$user_id = $_POST['user_id'];
			$post_id = $_POST['post_id'];
			if(addBookmark($conn,$post_id,$user_id)){
				echo json_encode(array("post_id"=>$post_id,"bookmark_type"=>"add",
				"status"=>true,"message"=>"You have successfully bookmarked."));
			}
			else{
				echo json_encode(array("post_id"=>$post_id,"bookmark_type"=>"add",
				"status"=>false,"message"=>"Oops! you could not bookmarked. Please try again."));
			}
		}
	}else if($action=="removeBookmark"){
		if(empty($_POST['user_id'])){
			echo json_encode(array("bookmark_type"=>"remove","status"=>false,"message"=>"You have not passed user id."));
		}
		else if(empty($_POST['post_id'])){
			echo json_encode(array("bookmark_type"=>"remove","status"=>false,"message"=>"You have not passed post id."));
		}
		else{
			$user_id = $_POST['user_id'];
			$post_id = $_POST['post_id'];
			if(removeBookmark($conn,$post_id,$user_id)){
				echo json_encode(array("post_id"=>$post_id,"bookmark_type"=>"remove",
				"status"=>true,"message"=>"You have successfully removed bookmark."));
			}
			else{
				echo json_encode(array("post_id"=>$post_id,"bookmark_type"=>"remove",
				"status"=>false,"message"=>"Oops! you could not remove bookmarked post. Please try again."));
			}
		}
	}
	else if($action=="getBookmarks"){
		if(empty($_POST['user_id'])){
			echo json_encode(array("status"=>false,"message"=>"You have not passed user id."));
		}else{
			$user_id = $_POST['user_id'];
			echo getBookmarks($conn,$user_id);
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Oops! Invalid Perameters passed..."));
	}
		
?>
