<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>

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
				if($conn){
					$team_id = getTeamIdByUsername($conn,$username);
					if($team_id!=null){
						$team_name = getTeamName($conn,$team_id);
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
									if($data->roles =="system_admin")
										header('Location:home.php');
									else 
										echo "You are not authorised";
								}
								else echo "<br/>".$data->message." <a href='index.html'>Login Again</a>";
							}
							else echo "<p align='center'>Unable to connect API, or API is down... 
							Please contact the concerned developer</p>";		
						}
						else echo "Team does not exist";
					}
					else echo "This account does not refer to any Team";
				}
				else echo "Unable to connect database.!";
			}catch(Exception $e){
				echo "\n An exception occurs: ".$e->getMessage();
			}
		}
		else{
			echo "<p align='center'>Authentication Failed! "."<a href='index.html'>Login Again</a> with proper username and password.</p>";
		}
	}
	else {
		echo "Invalid Request "."<a href='index.html'>Login Again</a>";
	}?>
	</body>
</html>
