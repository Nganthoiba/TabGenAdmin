
<?php   
	include("server_IP.php");
	/*php code to get list of organisations created by a user*/
		if(empty($_GET['username'])){
                echo "<p align='center'>You have to pass a valid username.<br/>";
        }
        else {
                $username = $_GET['username'];
				include ('ConnectAPI.php');
				echo getOrganisationList($username);
		}
		function getOrganisationList($user_name){
			$array = array("createdBy"=>$user_name);
			$url_send ="http://".IP.":8065/api/v1/organisation/track";
			$str_data = json_encode($array);
			$connect = new ConnectAPI();
			$result = $connect->sendPostData($url_send,$str_data);
			if($connect->httpResponseCode==200)
				return $result;
			else
				return "error";
		}

?>
