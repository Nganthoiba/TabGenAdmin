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
		if(isAdmin($conn,$user_id)){//check if the user is admin or not
			if(!empty($_POST['ou_name']) && !empty($_POST['role_name']) && !empty($_POST['tab_id'])){
				$ou_name = $_POST['ou_name'];
				$role_name = $_POST['role_name'];
				$tab_id = $_POST['tab_id'];
				$role_id = $_POST['role_id'];
				if($conn) {
					if(isTabOUSpecific($conn,$tab_id)){
						if($ou_name==getOUbyRole($conn,getRolebyTab($conn,$tab_id))){
							associateTabToRole($conn,$role_id,$tab_id);
						}
						else{
							echo json_encode(array("status"=>false,
							"message"=>"Tab is OU specific and cannot be associated to other OU!"));
						}	
					}
					else{	
						associateTabToRole($conn,$role_id,$tab_id);
					}
				}
				else 
					echo json_encode(array("status"=>false,"message"=>"Database Connection failed!"));
			}
			else{ 
				echo json_encode(array("status"=>false,"message"=>"Invalid parameter passed!"));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Sorry, you are not authorised for this service."));
		}
	}
}
			
?>
