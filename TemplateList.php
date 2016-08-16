
<?php	
	include('server_IP.php');		
	session_start();
	if(!isset($_COOKIE['user_details'])){
         echo "error";
    }
    else {
        $user_data = json_decode($_COOKIE['user_details']);
		$user_name = $user_data->username;
		include ('ConnectAPI.php');
		echo getTemplateList($user_name);
	}
	function getTemplateList($username){
		$data="";
		$array = array("name"=>$username);
		$url_send ="http://".IP.":8065/api/v1/tabtemplate/track";
		$str_data = json_encode($array);
		$connect = new ConnectAPI();
		$result = $connect->sendPostData($url_send,$str_data);
				
		if($result!=null){
			if($connect->httpResponseCode==200){					
					return $result;
			}else{
					return "false";
			}
		}
	}
?>
