<?php //Team id: xx9cqu5dkffbj8ixhxw5j64dww 
/* 
{
 "team_id":"aypj6bea1jboxn3f9cd8q8juch",
 "email": "test@nowhere.com",
 "username":"prashant",
 "password":"123456",
 "name": "betty",
 "type": "O"
}
*/
?>
<?php
include('ConnectAPI.php');
include('connect_db.php');
include('tabgen_php_functions.php');
$user_displayname = $_POST['user_displayname'];
$role=$_POST['Role'];
$role_id=$_POST['role_id'];
$type = $_POST['type']=="true"?1:0;


if(validateUserDetails()==true){
	$id=$_POST['team_id'];
	$org_unit_name = $_POST['org_unit'];
	try{
		if($conn){
			
				$data = array(
				   "team_id" => $id,
					"email" => $_POST['email'],
					"username" => $_POST['username'], 
					"password" => $_POST['password'],
					"name" => $_POST['org_unit'],
					"roles"=>$_POST['Role'],
					"first_name"=>$user_displayname	
				);
				
				$url_send ="http://".IP.":8065/api/v1/users/create";
				$str_data = json_encode($data);
				
				$connect = new ConnectAPI();
				$result = $connect->sendPostData($url_send,$str_data);
				if($result!=null){
					try{
						$responseData = json_decode($result);
						if($connect->httpResponseCode==200){
							$role=$_POST['Role'];
							$role_id=$_POST['role_id'];	
							userUniversalAccess($conn,$responseData->id,$_POST['type']);
							$ou_id = findOUId($conn,$org_unit_name);
							
							if(updateUserRoleAndDisplayName($responseData->id,$conn,$role,$user_displayname)){
								if(mapUserwithOU($conn,$responseData->id,$ou_id,$role_id,$type)){
									echo "true";
								}
								else{
									echo "Internal Server error: Unable to map User with OU, 
											please contact the system administrator";
								}
							}
							else{
								echo "false";
							}
						}else if($connect->httpResponseCode==0){
							echo "Unable to communicate with the API";
						}
						else 
							echo $responseData->message;
					}catch(Exception $e){
						echo "Exception: ".$e->getMessage();
					}
				}
				else 
					echo "Oops! There may be a problem at the server. Try again later.";
		}
	}
	catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function validateUserDetails(){
	if(empty($_POST['username'])){
		echo "Username is blank";
		return false;
	}
	else if($_POST['password']!=$_POST['conf_pwd']){
		echo "Password does not match Confirm Password";
		return false;
	}
	else if(empty($_POST['email'])){
		echo "Email is blank";
		return false;
	}
	else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		echo "Please enter a valid email.";
		return false;
	}
	else if(empty($_POST['org_unit'])){
		echo "Select an Organisation Unit";
		return false;
	}
	else if(empty($_POST['Role'])){
		echo "Select a role";
		return false;
	}
	else if(empty($_POST['team_id'])){
		echo "Team id not available.";
		return false;
	}
	else 
		return true;	
}

?>
