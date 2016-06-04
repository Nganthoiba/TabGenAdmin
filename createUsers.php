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
//include('server_IP.php');
include('connect_db.php');
include('tabgen_php_functions.php');
$user_displayname = $_POST['user_displayname'];
$role=$_POST['Role'];
$role_id=$_POST['role_id'];
$type = $_POST['type']=="true"?1:0;
//echo "Has access other OU: ".$type;
//echo "Role: ".$role." Id: ".$role_id;

if(validateUserDetails()==true){
	$id=null;
	$org_unit_name = $_POST['org_unit'];
	try{
		if($conn){
			//$res = $conn->query("SELECT Id,Name from Teams where Name='$org_unit_name'");
			session_start();
			$user_details=null;
			
			if(isset($_SESSION['user_details'])){
				$user_details = json_decode($_SESSION['user_details']);
			}
			else if(isset($_COOKIE['user_details'])){
				$user_details = json_decode($_COOKIE['user_details']);
			}
			if($user_details!=null){	
				$id = $user_details->team_id;
				$data = array(
				   "team_id" => $id,
					"email" => $_POST['email'],
					"username" => $_POST['username'], 
					"password" => $_POST['password'],
					"name" => $_POST['org_unit']	
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
							if(updateUserRoleAndDisplayName($responseData->id,$conn,$role,$user_displayname)){
								userUniversalAccess($conn,$responseData->id,$_POST['type']);
								$ou_id = findOUId($conn,$org_unit_name);
								if(mapUserwithOU($conn,$responseData->id,$ou_id,$role_id,$type))
									echo "true";
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
			else{
				 echo "Session expired, please login again.";
				  //header('Location: index.html');
			 }
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
	else if(empty($_POST['org_unit'])){
		echo "Select an Organisation Unit";
		return false;
	}
	else if(empty($_POST['Role'])){
		echo "Select a role";
		return false;
	}
	else 
		return true;	
}

?>
