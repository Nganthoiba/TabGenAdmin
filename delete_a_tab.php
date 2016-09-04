<?php
/*code for deleting a tab*/
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
$token = get_token_from_header();//getting token from header
if($token==null){
	echo json_encode(array("status"=>false,"message"=>"Your request is unauthorized."));
}
else{
	$user_id = getUserIdByToken($conn,$token);
	if($user_id==null){
		echo json_encode(array("status"=>false,"message"=>"Invalid token"));
	}
	else{
		if(isValidUser($conn,$user_id)){
			if(isAdmin($conn,$user_id)){
				if(!empty($_POST['tab_id'])){
					$tab_id = $_POST['tab_id'];
					
					if($conn){
						echo deleteATab($conn,$tab_id,$token);//deleting a tab
					}
					else{
						echo json_encode(array("status"=>false,"message"=>"Failed to Connect Database"));
					}		
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"Invalid Request"));
				}
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"You do not have sufficient permission"));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Sorry, the user id does not exist."));
		}
	}
}
	
	
?>
