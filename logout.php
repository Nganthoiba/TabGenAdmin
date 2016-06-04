<html>
	<head>
		<link rel="stylesheet" type="text/css" media="all" href="css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" media="all" href="css/bootstrap.min.css" />
	</head>
	<body>
		<?php session_start();
		if(isset($_SESSION['user_details'])){
			$user_data = json_decode($_SESSION['user_details']);
			echo "<center><P class='alert alert-error'>".$user_data->username.", ";
			$_SESSION['user_details']="";
			unset($_SESSION['user_details']);
			session_destroy();
			//setcookie("user_details", "", time() - 3600,'/');
			echo "You have successfully log out.</P>";
			echo "<a href='index.html' class='btn btn-link'>Click here to Log in</a></center>";
		
		 }
		 else echo "<P align='center' class='alert alert-error'> Session expired, 
			<a href='index.html' class='btn btn-link'>Login again</a></P>";
		//header('Location:index.html');
		/*
		$cookie_name = "user";
		$cookie_value = "John Doe";
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
		if(!isset($_COOKIE[$cookie_name])) {
			echo "Cookie named '" . $cookie_name . "' is not set!";
		} else {
			echo "Cookie '" . $cookie_name . "' is set!<br>";
			echo "Value is: " . $_COOKIE[$cookie_name];
		}
		setcookie($cookie_name, "", time() - 3600,'/');*/
?>

	</body>
</html>
