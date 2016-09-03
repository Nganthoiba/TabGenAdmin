<?php 
	/*php code to update articles and news articles*/
	include('tabgen_php_functions.php');
	include('connect_db.php');
	$token = get_token_from_header();//getting token from header
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
			
				if(empty($_POST['article_id'])){
					echo json_encode(array("status"=>false,"message"=>"Article Id is not received..."));
				}
				else{
					$id = $_POST['article_id'];//it can be article id/new article Id
					$article_id=$_POST['article_id'];
					$time=time()*1000;
					/*For articles of cme and reference*/
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
						$links = $_POST['Links']=="null"?null:$_POST['Links'];
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
						$query = "update News set Details='$news_details', UpdateAt=$time where Id='$id'";
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
						$query = "update News set headline='$news_headline', UpdateAt=$time where Id='$id'";
						if($conn->query($query)){
							echo json_encode(array("status"=>true,"message"=>"Successfully updated..."));
						} 
						else{
							echo json_encode(array("status"=>false,"message"=>"Update failed..."));
						}
					}
					else if(!empty($_POST['news_link'])){
						$news_link = $_POST['news_link']=="null"?null:$_POST['news_link'];
						$query = "update News set Link='$news_link', UpdateAt=$time where Id='$id'";
						if($conn->query($query)){
							echo json_encode(array("status"=>true,"message"=>"Successfully updated..."));
						} 
						else{
							echo json_encode(array("status"=>false,"message"=>"Update failed..."));
						}
					}
				}
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"You are not authorised for this action."));
			}
		}
	}

?>
