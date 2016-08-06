<?php 
	/*php code to update articles and news articles*/
	include('tabgen_php_functions.php');
	include('connect_db.php');
	if(empty($_POST['article_id'])){
		echo json_encode(array("status"=>false,"message"=>"Article Id is not received..."));
	}
	else{
		$id = $_POST['article_id'];//it can be article id/new article Id
		$article_id=$_POST['article_id'];
		$time=time()*1000;
		if(!empty($_POST['textual_content'])){
			$text = $_POST['textual_content'];
			$text = str_replace ("'","''", $text);
			$query = "update Article set Textual_content='".$text."', UpdateAt=$time where Id='$id'";
			if($conn->query($query)){
				echo json_encode(array("status"=>true,"message"=>"Successfully updated..."));
			} 
			else{
				echo json_encode(array("status"=>false,"message"=>"Update failed..."));
			}
		}
		else if(!empty($_POST['Links'])){
			$links = $_POST['Links'];
			$query = "update Article set Links='$links', UpdateAt=$time where Id='$id'";
			if($conn->query($query)){
				echo json_encode(array("status"=>true,"message"=>"Successfully updated...","link"=>$links));
			} 
			else{
				echo json_encode(array("status"=>false,"message"=>"Update failed..."));
			}
		}/*
			For updating News article
		*/
		else if(!empty($_POST['news_details'])){
			$news_details = $_POST['news_details'];
			$news_details = str_replace ("'","''", $news_details);
			$query = "update News set Details='$news_details' where Id='$id'";
			if($conn->query($query)){
				echo json_encode(array("status"=>true,"message"=>"Successfully updated..."));
			} 
			else{
				echo json_encode(array("status"=>false,"message"=>"Update failed..."));
			}
		}
		else if(!empty($_POST['news_headline'])){
			$news_headline = $_POST['news_headline'];
			$news_headline = str_replace ("'","''", $news_headline);
			$query = "update News set headline='$news_headline' where Id='$id'";
			if($conn->query($query)){
				echo json_encode(array("status"=>true,"message"=>"Successfully updated..."));
			} 
			else{
				echo json_encode(array("status"=>false,"message"=>"Update failed..."));
			}
		}
	}
	
?>
