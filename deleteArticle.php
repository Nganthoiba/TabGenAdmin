<?php 
	include('tabgen_php_functions.php');
	include('connect_db.php');
	
	if(!empty($_POST['article_id'])){
		$article_id = $_POST['article_id'];
		$time = time()*1000;
		$query = "update Article set DeleteAt=$time where Id='$article_id'";
		if($conn->query($query)){
			echo json_encode(array("status"=>true,"message"=>"The article has been deleted successfully"));
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"The article could not deleted."));
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"You have not passed article id."));
	}
?>
