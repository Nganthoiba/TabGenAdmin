<?php 
	include('tabgen_php_functions.php');
	include('connect_db.php');
	if(empty($_POST['article_id'])){
		echo json_encode(array("status"=>false,"message"=>"Article Id is not received..."));
	}
	else{
		$id = $_POST['article_id'];
		$article_id=$_POST['article_id'];
		$time=time()*1000;
		if(!empty($_POST['textual_content'])){
			$text = $_POST['textual_content'];
			$query = "update Article set Textual_content='$text', UpdateAt=$time where Id='$id'";
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
				echo json_encode(array("status"=>true,"message"=>"Successfully updated..."));
			} 
			else{
				echo json_encode(array("status"=>false,"message"=>"Update failed..."));
			}
		}
	}
	
?>
