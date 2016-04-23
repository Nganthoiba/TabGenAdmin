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

if(validateUserDetails()==true){
	$id=null;
	$org_unit_name = $_POST['org_unit'];
	try{
		if($conn){
			//$res = $conn->query("SELECT Id,Name from Teams where Name='$org_unit_name'");
			
			session_start();
			if(isset($_SESSION['user_details'])){
				
				$user_details = json_decode($_SESSION['user_details']);
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
								
							updateUserRole($responseData->id,$conn,$role);
							//updateUserFirstName($conn,$responseData->id,$user_displayname);
							$conn->query("UPDATE Users set FirstName='$user_displayname ' where Id='$responseData->id'");
							userUniversalAccess($conn,$responseData->id,$_POST['type']);
							$ou_id = findOUId($conn,$org_unit_name);
							mapUserwithOU($conn,$responseData->id,$ou_id);
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
			else echo "Session expired, please login again.";
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
