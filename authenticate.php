	<?php 
		include('ConnectAPI.php'); 
		include('connect_db.php');
		include('tabgen_php_functions.php');
  	?>
	<?php
	if(!empty($_POST)){
		$username = $_POST['username'];
		$password = $_POST['password'];
		if($username!='' && $password!=''){
			try{
				//if($conn){
					/*$team_id = getTeamIdByUsername($conn,$username);
					if($team_id!=null){*/
						$team_name = "organisation";//getTeamName($conn,$team_id);
						if($team_name!=null){
							
							$data = array("name"=>$team_name,"username"=>$username,"password"=>$password);
							$url_send ="http://".IP.":8065/api/v1/users/login";
							$str_data = json_encode($data);
							$conn = new ConnectAPI();
							$responseJsonData = $conn->sendPostData($url_send,$str_data);
							if($responseJsonData!=null){
								$data = json_decode($responseJsonData);	
								if($conn->httpResponseCode==200){
									session_start();
									$_SESSION['user_details'] = $responseJsonData;
									$_SESSION['login_header_response'] = $conn->httpHeaderResponse;
									
									if($data->roles =="system_admin" || $data->roles =="admin")
										//header('Location:home.php');$conn->httpHeaderResponse
										echo json_encode(array("state"=>"true","location"=>"home.php"));
									else 
										echo json_encode(array("state"=>"false","message"=>"You are not authorised!"));
								}
								else 
									echo json_encode(array("state"=>"false","message"=>$data->message));
							}
							else echo json_encode(array("state"=>"false","message"=>"Unable to connect API, or API is down... 
							Please contact the concerned developer."));		
						}
						else 
							echo json_encode(array("state"=>"false","message"=>"Team does not exist"));
					/*}
					else echo json_encode(array("state"=>"false","message"=>"Username does not exist."));*/ 
				/*}
				else json_encode(array("state"=>"false","message"=>"Unable to connect database!"));*/ 
			}catch(Exception $e){
				echo json_encode(array("state"=>"false","message"=>$e->getMessage()));
			}
		}
		else{
			echo json_encode(array("state"=>"false","message"=>"Authentication Failed! Login again with proper username and password."));
		}
	}
	else {
		echo "Invalid Request "."<a href='index.html'>Login Again</a>";
	}?>

