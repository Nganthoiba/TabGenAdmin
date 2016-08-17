<?php
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file

$token = get_token_from_header();
if($token==null){
	echo json_encode(array("status"=>false,"message"=>"Your request is unauthorized, please login and try again."));
}
else{
	$user_id = getUserIdByToken($conn,$token);
	if($user_id==null){
		echo json_encode(array("status"=>false,"message"=>"Invalid token!, please login and try again."));
	}
	else{
		if(isAdmin($conn,$user_id)){	
			if(empty($_POST['tab_id'])){
				echo json_encode(array("status"=>true,"message"=>"Tab Id is missing."));
			}
			else if(empty($_POST['role_id'])){
				echo json_encode(array("status"=>true,"message"=>"Role Id is missing."));
			}
			else{
				$tab_id = $_POST['tab_id'];
				$role_id = $_POST['role_id'];
				if($conn){
					$query = "delete from RoleTabAsson where TabId='$tab_id' and RoleId='$role_id'";
					if($conn->query($query)){
						echo json_encode(array("status"=>true,"message"=>"deleted"));
					}
					else{
						echo json_encode(array("status"=>false,"message"=>"unable to drop"));
					}		
				}
				else{ 
					echo json_encode(array("status"=>true,"message"=>"Failed to connect database"));
				}
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Sorry, you are not authorised for this service."));
		}
	}
}
?>
