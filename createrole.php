<?php
include('ConnectAPI.php');
include('tabgen_php_functions.php');
//include('server_IP.php');
include('connect_db.php');
$rolaname = $_POST['rolaname'];
$ousel = $_POST['ousel'];
$ou_specific = $_POST['ou_specific'];
$role_type = $_POST['role_type'];

if($rolaname!='' && $ousel!=''){
	$data = array(
	   "organisationUnit"  => $ousel,
	   "universalRole" => $ou_specific,	
		"role_name" => $rolaname 
	);
	//"universalRole" => $access,
	$url_send ="http://".IP.":8065/api/v1/organisationRole/create";
	$str_data = json_encode($data);
	
	$connect = new ConnectAPI();
	$result = $connect->sendPostData($url_send,$str_data);
	if($result!=null){
		try{
			$responseData = json_decode($result);
			if($connect->httpResponseCode==200){
				updateRoleType($conn,$responseData->id,$role_type);
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
		echo "false";
}
else{	
	echo 'false';
}

?>
