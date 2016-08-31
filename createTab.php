<?php
session_start();
/*php code to create a tab*/
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file
include('ConnectAPI.php');

$token = get_token_from_header();
if($token!=null){
	
	if(!empty($_POST['ou_specific'])){
		if($conn){
			$user_id = getUserIdByToken($conn,$token);
			if($user_id!=null && isValidUser($conn,$user_id)){
				if(isAdmin($conn,$user_id)){
					$ou_specific = $_POST['ou_specific'];
					$tab_name = $_POST['tab_name'];
					$createdBy = getUserNameById($conn,$user_id);
					$template_name = $_POST['template_name'];
					//if database is successfully connected
					if(!isTabExist($conn,$tab_name)){
						//if the tab does not exist already					
						$template_id=findTemplateId($conn,$template_name);
						if($template_id!=null){
							if($template_name=="Chat Template"){
								$user_details = json_decode($_SESSION['user_details']);
								$team_id = $user_details->team_id;
								$channel_array = array("display_name"=>$tab_name,
															"name"=>strtolower(str_replace(' ','_',$tab_name)),
															"team_id"=>$team_id,
															"type"=>"O");
								$data = json_encode($channel_array);
								$connection = new ConnectAPI();
								$url = "http://".IP.":8065/api/v1/channels/create";//url for creating a channel for chatting
								$response = json_decode($connection->sendPostDataWithToken($url,$data,$token));
								if($connection->httpResponseCode==200){//it means channel has been created successfully	
									create_tab($conn,$tab_name,$template_id,$createdBy,$ou_specific);	
								}
								else {
									echo json_encode(array("status"=>false,"message"=>$response->message));	
								}						
							}
							else{
								create_tab($conn,$tab_name,$template_id,$createdBy,$ou_specific);
							}
						}
						else{
							echo json_encode(array("status"=>false,"message"=>"Could not find template"));
						}
					}
					else {
						echo json_encode(array("status"=>false,"message"=>"A Tab with the same name already exists. Try another name."));
					}
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"Sorry! You are not authorised for this service."));
				}
			}
			else{
				echo json_encode(array("status"=>false,
				"message"=>"Oops! Either session expired or used invalid token, please login and try again."));
			}
		}
		else {
			echo json_encode(array("status"=>true,"message"=>"Unable to connect database"));
		}	
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Please select whether the tab is OU specific or not..!"));
	}	
}
else {
	echo json_encode(array("status"=>false,"message"=>"Unauthorized access.."));
}

?>

