<?php include ('ConnectAPI.php');
	include('server_IP.php');
?>
<?php 
if(!empty($_POST['name'])){
	$data = array("name"=>$_POST['name']);
	$url_send ="http://".IP.":8065/api/v1/organisation/findByName";
	$str_data = json_encode($data);
	$connect = new ConnectAPI();
	$result = $connect->sendPostData($url_send,$str_data);
	if($result!=null){
		try{
			$responseData = json_decode($result);
			/*
			if($connect->httpResponseCode==200){
				echo "Organisation exists with id = ".$responseData->id." created by: ".$responseData->email;
			}
			else 
				echo $responseData->message;*/
			echo $result;
		}catch(Exception $e){
				echo "Exception: ".$e->getMessage();
		}
	}
	else 
		echo "Unable to connect API, API down.";
}
else {
	echo "Please enter the organisation name";
}
?>
