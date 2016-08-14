	<?php 
		include('ConnectAPI.php'); 
		include('server_IP.php');
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
					//if(filter_var($email, FILTER_VALIDATE_EMAIL)){		
					$data = filter_var($username, FILTER_VALIDATE_EMAIL)==false?
						array("name"=>$team_name,"username"=>$username,"password"=>$password):
						array("name"=>$team_name,"email"=>$username,"password"=>$password);
					$url_send ="http://".IP.":8065/api/v1/users/login";
					$str_data = json_encode($data);
					$conn = new ConnectAPI();
					$responseJsonData = $conn->sendPostData($url_send,$str_data);
					if($responseJsonData!=null){
						$data = json_decode($responseJsonData);	
						if($conn->httpResponseCode==200){
							session_start();
							$_SESSION['user_details'] = $responseJsonData;
							setcookie("user_details", $responseJsonData, time() + (86400 * 30), "/");
							$_SESSION['login_header_response'] = $conn->httpHeaderResponse;
							setcookie("login_header_response",$conn->httpHeaderResponse, time() + (86400 * 30), "/");
							$user_data = json_decode($_SESSION['user_details']);
							if($user_data!=null){
								$user_data->token=getTokenFromHeader($conn->httpHeaderResponse);
								$cookie_name = "MMTOKEN";
								$cookie_value = getTokenFromHeader($conn->httpHeaderResponse);
								setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
							}
							if($data->roles =="system_admin" || $data->roles =="admin" && get_token()!=null){
								//header('Location:home.php');$conn->httpHeaderResponse
								echo json_encode(array("state"=>"true",
									"location"=>"home.php",
									"user_details"=>json_encode($user_data),"headers"=>$conn->httpHeaderResponse));
							}else{ 
								echo json_encode(array("state"=>"false","message"=>"You are not authorised!"));
							}
						}
						else 
							echo json_encode(array("state"=>"false","message"=>$data->message));
					}
							else echo json_encode(array("state"=>"false","message"=>"Unable to connect API, or API is down...."));		
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

