<?php
/*Updating user data*/
include('connect_db.php');
include('tabgen_php_functions.php');
$token = get_token_from_header();
if($token==null){
	echo json_encode(array("status"=>false,"message"=>"Please login and try again"));
}
else{
	$admin_id = getUserIdByToken($conn,$token);
	if($admin_id==null){
		echo json_encode(array("status"=>false,"message"=>"Invalid token"));
	}
	else{
		if(isAdmin($conn,$admin_id)){//check if the user is admin or not
			if(!empty($_POST['user_id'])){
				$user_id = $_POST['user_id'];
				$updateTime = time()*1000;
				if(!empty($_POST['username']))
				{
					$username = $_POST['username'];
					$query="Update Users set UpdateAt='$updateTime',Username='$username' where Id='$user_id'";
					if($conn->query($query)){
						echo json_encode(array("status"=>true,"message"=>"Successfully updated."));
					}
					else{
						echo json_encode(array("status"=>false,"message"=>"Sorry, we could not update username."));
					}
				}
				else if(!empty($_POST['email']))
				{
					$email = $_POST['email'];
					if(filter_var($email, FILTER_VALIDATE_EMAIL)){
						$query="Update Users set UpdateAt='$updateTime',Email='$email' where Id='$user_id'";
						if($conn->query($query)){
							echo json_encode(array("status"=>true,"message"=>"Successfully updated."));
						}
						else{
							echo json_encode(array("status"=>false,"message"=>"Sorry, we could not update email."));
						}
					}
					else{
						echo json_encode(array("status"=>false,"message"=>"Sorry, you have entered invalid email format."));
					}
				}
				else if(!empty($_POST['display_name']))
				{
					$user_display = $_POST['display_name'];
					$query="Update Users set UpdateAt='$updateTime',FirstName='$user_display' where Id='$user_id'";
					if($conn->query($query)){
						echo json_encode(array("status"=>true,"message"=>"Successfully updated."));
					}
					else{
						echo json_encode(array("status"=>false,"message"=>"Sorry, we could not update display name."));
					}
				}				
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"Please send user id"));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"You are not authorised for this service"));
		}
	}
}
?>
