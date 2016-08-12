<?php
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file
$token = get_token_from_header();
if($token==null){
	echo json_encode(array("status"=>false,"message"=>"Your request is unauthorized."));
}
else{
	$user_id = getUserIdByToken($conn,$token);
	if($user_id==null){
		echo json_encode(array("status"=>false,"message"=>"Invalid token"));
	}
	else
	{
		if(isAdmin($conn,$user_id)){//check if the user is admin or not
			$news_title = $_POST['news_title'];
			$news_headline = $_POST['news_headline'];
			$news_details = $_POST['news_details'];
			$tab_id = $_POST['tab_id'];
			$ext_link = $_POST['ext_link'];
			if(empty($news_title)){
				echo json_encode(array("status"=>false,"message"=>"Please send title of the news.."));
			}
			else if(empty($news_headline)){
				echo json_encode(array("status"=>false,"message"=>"Please send headline of news.."));
			}
			else if(empty($news_details)){
				echo json_encode(array("status"=>false,"message"=>"Please send details of news.."));
			}
			else if(empty($tab_id)){
				echo json_encode(array("status"=>false,"message"=>"Please send tab Id under which the news is to be posted."));
			}
			else if(isNewsTitleExists($conn,$news_title)){
				echo json_encode(array("status"=>false,"message"=>"A news with the same title already exists."));
			}
			else{
				$id = randId(26);//creating unique id
				$news_title=str_replace ("'","''", $news_title);
				$news_headline=str_replace ("'","''", $news_headline);
				$news_details=str_replace ("'","''", $news_details);
				$created_at = time()*1000;
				$status = "true";
				$query = "insert into News(Id,CreateAt,title,headline,Details,Link,Active,tab_id) values('$id',$created_at,'$news_title',
				'$news_headline','$news_details','$ext_link','$status','$tab_id')";
				if($conn->query($query)){
					echo json_encode(array("status"=>true,"message"=>"News posted successfully."));
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"An internal problem occurs."));
				}
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"You are not authorised for this action."));
		}
	}
}


?>
