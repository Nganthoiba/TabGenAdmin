<html>
	<head>
		<link rel="stylesheet" type="text/css" media="all" href="css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/bootstrap.min.css" />
	</head>
	<body>
		<?php 
		include('ConnectAPI.php');
		include('server_IP.php');
		//echo $_POST['token'];
		if(!empty($_POST['token'])){
			//echo IP;
			$url ="http://".IP.":8065/api/v1/users/logout";
			$token=$_POST['token'];
			$getOut = new ConnectAPI();
			$data = "";
			$result = $getOut->sendPostDataWithToken($url,$data,$token);
			
			
			if($result!=null){
				if($getOut->httpResponseCode==200){
					session_start();
					if(isset($_SESSION['user_details'])){
						$user_data = json_decode($_SESSION['user_details']);
						echo "<center><P class='alert alert-error'>".$user_data->username.", ";
						$_SESSION['user_details']="";
						unset($_SESSION['user_details']);
						session_destroy();
						
						setcookie("MMTOKEN", "", time() - (86400 * 30),'/');
						setcookie("user_details", "", time() - (86400 * 30),'/');
						setcookie("login_header_response", "", time() - (86400 * 30),'/');
						unset($_COOKIE['MMTOKEN']);
						unset($_COOKIE['user_details']);
						unset($_COOKIE['login_header_response']);
					}
					echo "You have successfully log out.</P>";
					echo "<a href='index.html' class='btn btn-link'>Click here to Log in</a></center>";
				}
				else{
					echo "<br/>HTTP Response Code: ".$getOut->httpResponseCode;
					$resp = json_decode($result);
					echo "<br/>Message: ".$resp->message;
				}
			}
			else{
				echo "<br/> result is null";
			}
		}
		else{
			echo "<P align='center' class='alert alert-error'> Please send a valid token.</P>";
		}
		
?>

	</body>
</html>
