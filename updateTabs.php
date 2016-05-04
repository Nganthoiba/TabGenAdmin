<?php
session_start();
include('connect_db.php');
include('tabgen_php_functions.php');
include('ConnectAPI.php');
//include('server_IP.php');
	$index = $_POST['index'];
	if(!empty($_POST['tab_id']) && !empty($_POST['template_name']) && !empty($_POST['tab_name'])){
		$index = $_POST['index'];
		$tab_id = $_POST['tab_id'];
		$tab_name=$_POST['tab_name'];
		$previous_tab_name = $_POST['previous_tab'];
		$template_name=$_POST['template_name'];
		$ou_name = $_POST['ou_name'];
		
		if($template_name=="Chat Template"){
			//$token_id = get_token($ou_name,"thoiba","admin");
			
			$token_id = get_token();
			/*echo json_encode(array("index"=>"".$index,
				"response"=>"<font color='#198D24'>".$token_id."</font>","state"=>false));*/
			
			if($token_id!=null){
				$channel_details = getChannelByName($conn,$previous_tab_name);
				if($channel_details==null){
					//it means no channel same as the tab name has been created so far. so we are going to create one.
					//$team_id = getTeamId_by_OU_name($conn,$ou_name);
					session_start();
					$user_details = json_decode($_SESSION['user_details']);
					$team_id = $user_details->team_id;
					$channel_array = array("display_name"=>$tab_name,"name"=>$tab_name,"team_id"=>$team_id,"type"=>"O");
					$data = json_encode($channel_array);
					$connection = new ConnectAPI();
					
					$url = "http://".IP.":8065/api/v1/channels/create";
					$response = json_decode($connection->sendPostDataWithToken($url,$data,$token_id));
					if($connection->httpResponseCode==200){//it means channel has been created successfully
						updateTabTemplateAssociation($conn,$index,$tab_id,$template_name,$tab_name);
						/*echo json_encode(array("index"=>"".$index,
							"response"=>"<font color='#198D24'>Channel Created</font>","state"=>false));*/
					}
					else{
						echo json_encode(array("index"=>"".$index,"response"=>"<font color='#C52039'>".$response->message."</font>","state"=>false));
					}
					
				}
				else {
					/*echo json_encode(array("index"=>"".$index,
				"response"=>"<font color='#198D24'>Old Channel</font>","state"=>false));*/
					// it means a channel already exists with the same name as tab name, so we are going to update that channel name				
					/*
					 * value of $channel_details in the form of json :
					 [{
					 * "Id":"gqbq8545hbgptmsyf9yuapcx5w",
					 * "CreateAt":"1459834842155",
					 * "UpdateAt":"1459834842155",
					 * "DeleteAt":"0",
					 * "TeamId":"x1xt59ce9ir558gyhcefetpqjh",
					 * "Type":"O",
					 * "DisplayName":"chatting_for_immunologist",
					 * "Name":"chatting_for_immunologist",
					 * "Header":"","Purpose":"",
					 * "LastPostAt":"1459834870740",
					 * "TotalMsgCount":"1",
					 * "ExtraUpdateAt":"1459834857292",
					 * "CreatorId":"d4ms8cmjd3bhdyzge636mkauch"}]
					*/
					if($tab_name!=$previous_tab_name){
						$update_channel_data = json_encode(array("id"=>$channel_details[0]['Id'],
											"team_id"=>$channel_details[0]['TeamId'],
											"type"=>"O",
											"display_name"=>$tab_name,
											"name"=>$tab_name,
											"creator_id"=>$channel_details[0]['CreatorId']));
						$update_channel_url = "http://".IP.":8065/api/v1/channels/update";
						$updateChannel = new ConnectAPI();
						$update_channel_response = json_decode($updateChannel->sendPostDataWithToken($update_channel_url,$update_channel_data,$token_id));
						if($updateChannel->httpResponseCode==200){
							//it means channel has been updated successfully
							updateTabTemplateAssociation($conn,$index,$tab_id,$template_name,$tab_name);
						}
						else if($updateChannel->httpResponseCode==0){
							echo json_encode(array("index"=>"".$index,"response"=>"<font color='#C52039'>Server not found! Try again later</font>","state"=>false));
						}
						else{
							echo json_encode(array("index"=>"".$index,"response"=>"<font color='#C52039'>".$update_channel_response->message."</font>","state"=>false));
						}
					}
					else{
						updateTabTemplateAssociation($conn,$index,$tab_id,$template_name,$tab_name);
					}
				}
			}
			else 
				echo json_encode(array("index"=>"".$index,"response"=>"<font color='#198D24'>Token id is null, your session is expired. Login again.</font>","state"=>false));
			
		}
		else 
			updateTabTemplateAssociation($conn,$index,$tab_id,$template_name,$tab_name);		
				
	}
	else{
		$response = array("index"=>"".$index,"response"=>"No perameter passed..!","state"=>false);
		echo json_encode($response);
	}

function updateTabTemplateAssociation($conn,$index,$tab_id,$template_name,$tab_name){
	try{
			if($conn){
				$template_id=findTemplateId($conn,$template_name);
				if($template_id!=null){
					
					$updateTime = time()*1000;
					$query2 = "UPDATE Tab set TabTemplate = '$template_id',UpdateAt='$updateTime',Name='$tab_name' WHERE Tab.Id='$tab_id'";
							/*$query2 = "UPDATE Tab set TabTemplate = '$template_id',UpdateAt='$updateTime'  
							WHERE RoleId='$role_id' AND Name='$tab_name' AND RoleName='$role_name' AND OUId='$ou_id'";*/
					try{
						$result = $conn->query($query2);
						if($result){
							$response = array("index"=>"".$index,"response"=>"<font color='#198D24'>Updated</font>","state"=>true);
							echo json_encode($response);
						}
						else {
							$response = array("index"=>"".$index,"response"=>"<font color='#C52039'>Update Failed</font>","state"=>false);
							echo json_encode($response);
						}
					}
					catch(Exception $e){
						$response = array("index"=>"".$index,"response"=>"Failed to save data: ".$e->getMessage(),"state"=>false);
						echo json_encode($response);
					}
				}
				else{ 
					$response = array("index"=>"".$index,"response"=>"Template doesn't exist","state"=>false);
					echo json_encode($response);
				}							
			}
		}catch(PDOException $e){
			$response = array("index"=>"".$index,"response"=>"Failed to save data: ".$e->getMessage(),"state"=>false);
			echo json_encode($response);
		}
}

/*function get_token($team_name,$username,$password){
		$data = array("name"=>$team_name,"username"=>$username,"password"=>$password);
		$url_send ="http://".IP.":8065/api/v1/users/login";
		$token=null;
		$str_data = json_encode($data);
		$connect = new ConnectAPI();
		$responseJsonData = $connect->sendPostData($url_send,$str_data);
		if($responseJsonData!=null){
			$resp_data = json_decode($responseJsonData);	
			if($connect->httpResponseCode==200){
				$header = $connect->httpHeaderResponse;
				$header_array = $connect->http_parse_headers($header);
				
				foreach ($header_array as $name => $value) {
					//echo "The value of '$name' is '$value'<br>";
					if($name=="Token"){
						$token = $value;
						break;
					}
				}							
			}
			else $token=null; 
			
		}
		return $token;
}*/

?>
