<?php
	if(!empty($_GET['username'])){
		include('connect_db.php');
		include('tabgen_php_functions.php');
		$username = $_GET['username'];
		if($conn){
			$team_id = getTeamIdByUsername($conn,$username);
			if($team_id!=null){
				$team_name = getTeamName($conn,$team_id);
				if($team_name!=null){
					echo json_encode(array("state"=>true,"team_name"=>$team_name));
				}
				else echo json_encode(array("state"=>false,"message"=>"Account does not exist"));
			}
			else echo json_encode(array("state"=>false,"message"=>"This account does not refer to any Team"));
		}
		else{
			 echo json_encode(array("state"=>false,"message"=>"Database connection failed!"));
		}	
	}
	else{
		echo json_encode(array("state"=>false,"message"=>"Pass your username!"));
	}
?>
