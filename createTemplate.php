<?php include('ConnectAPI.php'); 
include('server_IP.php');
?>
<?php
session_start();
if(isset($_SESSION['user_details'])){
$user_details = json_decode($_SESSION['user_details']);
if(!empty($_POST)){
	$template_name = $_POST['template_name'];
	$template = $_POST['template'];			
		$data = array(
			"name" => $template_name,
			"email" => $user_details->email,
			"createdBy"=>$user_details->username,
			"template"=>$template);
			//$user_details->email
		$url_send ="http://".IP.":8065/api/v1/tabtemplate/create";
		$str_data = json_encode($data);
		$connect = new ConnectAPI();
		$result = $connect->sendPostData($url_send,$str_data);
		if($result!=null){
			try{
				$responseData = json_decode($result);
				if($connect->httpResponseCode==200){
					echo "Template Created Successfully";
				}else if($connect->httpResponseCode==0){
					echo "Some unknown problem occur with server, Please try again later";
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
else 
	echo "No parameters, invalid request";
}
else echo "Oops! Your session is expired, please login back";
?>
