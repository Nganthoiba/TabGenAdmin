<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
	if(empty($_POST['tab_id'])){
		echo json_encode(array("status"=>true,"message"=>"Tab Id is missing."));
	}
	else if(empty($_POST['role_id'])){
		echo json_encode(array("status"=>true,"message"=>"Role Id is missing."));
	}
	else{
			$tab_id = $_POST['tab_id'];
			/*$ou_name = $_POST['ou_name'];
			$role_name = $_POST['role_name'];*/
			$role_id = $_POST['role_id'];
			if($conn){
				//$role_id = findRoleId($conn,$ou_name,$role_name);
				$query = "delete from RoleTabAsson where TabId='$tab_id' and RoleId='$role_id'";
				if($conn->query($query)){
					echo json_encode(array("status"=>true,"message"=>"deleted"));
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"unable to drop"));
				}
				
			}
			else 
				echo json_encode(array("status"=>true,"message"=>"Failed to connect database"));
	}
?>
