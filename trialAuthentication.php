<?php include('ConnectAPI.php');   ?>
<?php

	$email = "leecba@gmail.com";
	$password = "nganthoi";
	echo "Email: ".$email." and Password: ".$password."\n";
	if($email!='' && $password!=''){
		$data = array("name"=>"org1","email" => $email,"password" => $password);
		$url_send ="http://188.166.210.24:8065/api/v1/users/login";
		$str_data = json_encode($data);
		
		$conn = new ConnectAPI();
		
		$responseJsonData = $conn->sendPostData($url_send,$str_data);
		$data = json_decode($responseJsonData);	
		echo $responseJsonData;
		/*
			if($conn->httpResponseCode==200){
				//echo "Hello! ".$data->username;
				header('Location:home.php');
			}
			else if($conn->httpResponseCode==0){
				echo "\n Unable to connect api: \n";
			}
			else echo $data->message;
		*/
	}
	else{
		echo 'Authentication Failed!';
	}


?>