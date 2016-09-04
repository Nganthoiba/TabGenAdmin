
		<?php 
		include('ConnectAPI.php');
		include('connect_db.php');
		//echo $_POST['token'];
		if(!empty($_POST['token'])){
			//echo IP;
			$url ="http://".IP.":8065/api/v1/users/logout";
			$token=$_POST['token'];
			$getOut = new ConnectAPI();
			$data = "";
			$result = $getOut->sendPostDataWithToken($url,$data,$token);	
			if($result!=null){
				if($getOut->httpResponseCode==200){
					if(empty($_POST['fcm_token'])){
						if(isset($_COOKIE['user_details'])){
							$user_data = json_decode($_COOKIE['user_details']);
							$_COOKIE['user_details']="";
														
							/*destroying cookies*/
							setcookie("MMTOKEN", "", time() - (86400 * 30),'/');
							setcookie("user_details", "", time() - (86400 * 30),'/');
							setcookie("login_header_response", "", time() - (86400 * 30),'/');
							unset($_COOKIE['MMTOKEN']);
							unset($_COOKIE['user_details']);
							unset($_COOKIE['login_header_response']);
						}
						//header('Location:http://'.IP.'/TabGenAdmin/');
						echo "<center>You have successfully logout.</center>";
					}
					else{
						$fcm_token = $_POST['fcm_token'];
						if(delete_fcm_token($conn,$fcm_token)){
							echo json_encode(array("status"=>true,"message"=>"You have successfully logged out"));
						}
						else{
							echo json_encode(array("status"=>false,
							"message"=>"Sorry, we have an internal problem in logging out"));
						}
					}
				}
				else{
					/*echo "<br/>HTTP Response Code: ".$getOut->httpResponseCode;
					$resp = json_decode($result);
					echo "<br/>Message: ".$resp->message;*/
					echo $result;
				}
			}
			else{
				echo json_encode(array("status"=>false,
							"message"=>"Sorry, we have an internal problem in logging out, unable to connect api."));
			}
		}
		else{
			echo json_encode(array("status"=>false,
							"message"=>"Please send a valid token."));
		}
		
		function delete_fcm_token($conn,$fcm_token){
			$query = "delete from FCM_Users where token_id='$fcm_token'";
			return $conn->query($query);
		}
		
?>

