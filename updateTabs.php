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
		$template_name=$_POST['template_name'];
		$ou_name = $_POST['ou_name'];
		$token_id = get_token($ou_name,"thoiba","admin");
		if($template_name=="Chat Template"){
			if($token_id!=null){
				$team_id = getTeamId_by_OU_name($conn,$ou_name);
				$channel_array = array("display_name"=>$tab_name,"name"=>$tab_name,"team_id"=>$team_id,"type"=>"O");
				$data = json_encode($channel_array);
				$connection = new ConnectAPI();
				$url = "http://".IP.":8065/api/v1/channels/create";
				$response = json_decode($connection->sendPostDataWithToken($url,$data,$token_id));
				if($connection->httpResponseCode==200){
					updateTabTemplateAssociation($conn,$index,$tab_id,$template_name,$tab_name);
				}
				else{
					echo json_encode(array("index"=>"".$index,"response"=>"<font color='#198D24'>".$response->message."</font>","state"=>false));
				}
			}
			else 
				echo json_encode(array("index"=>"".$index,"response"=>"<font color='#198D24'>Token id is null</font>","state"=>false));
			
		}
		else updateTabTemplateAssociation($conn,$index,$tab_id,$template_name,$tab_name);		
				
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
					
					$updateTime = time();
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
					$response = array("index"=>"".$index,"response"=>"Template dosn't exist","state"=>false);
					echo json_encode($response);
				}							
			}
		}catch(PDOException $e){
			$response = array("index"=>"".$index,"response"=>"Failed to save data: ".$e->getMessage(),"state"=>false);
			echo json_encode($response);
		}
}

function get_token($team_name,$username,$password){
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
}

?>
