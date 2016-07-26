<?php
/*php file for activating or deactivating article*/
include('tabgen_php_functions.php');
include('connect_db.php');

	if(!empty($_POST['article_id'])){
		$article_id = $_POST['article_id'];
		if(!empty($_POST['type'])){
			$type=$_POST['type'];
			$activation_status = $_POST['status'];
			//echo "Active Status: ".$activation_status;
			$time = time()*1000;
			$query=null;
			if($type=="article")
				$query = "update Article set UpdateAt=$time,Active='$activation_status' where Id='$article_id'";
			else if($type=="news"){//for news articles
				$query = "update News set UpdateAt=$time,Active='$activation_status' where Id='$article_id'";
			}
			if($conn->query($query)){
				$message=$activation_status=="true"?"Article activated.":"Article deactivated.";
				echo json_encode(array("status"=>true,"message"=>$message));
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"The requested service could not be completed."));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"You have not passed activation type i.e. news/ article ."));
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"You have not passed article id."));
	}
?>
