<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>

	<?php include('ConnectAPI.php'); 
		include('server_IP.php');
  	?>
	<?php
	if(!empty($_POST)){
		$username = $_POST['username'];
		$password = $_POST['password'];
		if($username!='' && $password!=''){
			try{
				$data = array("name"=>"myteam","username"=>$username,"password"=>$password);
				$url_send ="http://".IP.":8065/api/v1/users/login";
				$str_data = json_encode($data);
				$conn = new ConnectAPI();
				$responseJsonData = $conn->sendPostData($url_send,$str_data);
				if($responseJsonData!=null){
					$data = json_decode($responseJsonData);	
					if($conn->httpResponseCode==200){
						session_start();
						$_SESSION['user_details'] = $responseJsonData;
						header('Location:home.php');
					}
					else echo "<br/>".$data->message." <a href='index.html'>Login Again</a>";
				}
				else echo "<p align='center'>Unable to connect API, or API is down... 
				Please contact the concerned developer</p>";
				//echo $str_data;
			}catch(Exception $e){
				echo "\n An exception occurs: ".$e->getMessage();
			}
		}
		else{
			echo "<p align='center'>Authentication Failed! "."<a href='index.html'>Login Again</a></p>";
		}
	}
	else {
		echo "Invalid Request "."<a href='index.html'>Login Again</a>";
	}?>
	</body>
</html>
