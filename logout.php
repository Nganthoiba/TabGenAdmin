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
			echo "You have successfully log out.</P>";
			echo "<a href='index.html' class='btn btn-success'>Click here to Log in</a></center>";
		
		 }
		 else echo "<P align='center' class='alert alert-error'> Session expired, 
			<a href='index.html' class='btn btn-success'>Login again</a></P>";
		?>
	</body>
</html>
