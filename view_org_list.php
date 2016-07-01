
<?php   
	include("server_IP.php");
	
		/*	
		session_start();
		if(!isset($_SESSION['user_details'])){
                echo "<p align='center'>You have to <a href='index.html'>login</a>, your session is expired.<br/>";
        }
        else {
                $user_data = json_decode($_SESSION['user_details']);
                $user_name = $user_data->username;
                echo $username;
				include ('ConnectAPI.php');
				echo getOrganisationList('thoiba');
		}
		*/
		if(empty($_GET['username'])){
                echo "<p align='center'>You have to pass a valid username.<br/>";
        }
        else {
                $username = $_GET['username'];
				include ('ConnectAPI.php');
				//echo $username;
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
