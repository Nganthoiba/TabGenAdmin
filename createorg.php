<?php include('ConnectAPI.php'); 
include ('server_IP.php');?>
<?php
//session_start();
if(isset($_COOKIE['user_details'])){
$user_details = json_decode($_COOKIE['user_details']);
if(!empty($_POST)){
	$org = $_POST['orgname'];
	//$dis = $_POST['display_name'];
	if($org!=''){
				
		$data = array(
			"name" => $org,
			"email" => $user_details->email,
			"createdBy"=>$user_details->username,
			"display_name"=>$dis);
		$url_send ="http://".IP.":8065/api/v1/organisation/create";
		$str_data = json_encode($data);
		$connect = new ConnectAPI();
		$result = $connect->sendPostData($url_send,$str_data);
		if($result!=null){
			try{
				$responseData = json_decode($result);
				if($connect->httpResponseCode==200){
					echo "true";
				}else if($connect->httpResponseCode==0){
					echo "false";
				}
				else 
					echo $responseData->message;
			}catch(Exception $e){
				echo "Exception: ".$e->getMessage();
			}
		}
		else 
			echo "Unable to connect API, API down.";	
	}
	else{
		echo "Please don't leave the input fields blank.";
	}
}
else 
	echo "No parameters, invalid request";
}
else echo "Oops! Your session is expired, please login back";
?>
