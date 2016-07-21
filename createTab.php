<?php
session_start();
//include('server_IP.php');
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file
include('ConnectAPI.php');
//$ou_specific = $_POST['ou_specific'];
if(isset($_SESSION['user_details'])){
	$user_details = json_decode($_SESSION['user_details']);
	
	if(!empty($_POST['ou_specific'])){
		
		$ou_specific = $_POST['ou_specific'];
		
		$tab_name = $_POST['tab_name'];
		$createdBy = $user_details->username;
		$template_name = $_POST['template_name'];
		
		if($conn){
			//if database is successfully connected
			if(!isTabExist($conn,$tab_name)){
				//if the tab does not exist already
								
				$template_id=findTemplateId($conn,$template_name);
				if($template_id!=null){
					if($template_name=="Chat Template"){
						$token_id = get_token();
						if($token_id!=null){
							$user_details = json_decode($_SESSION['user_details']);
							$team_id = $user_details->team_id;
							$channel_array = array("display_name"=>$tab_name,
													"name"=>strtolower(str_replace(' ','_',$tab_name)),
													"team_id"=>$team_id,
													"type"=>"O");
							$data = json_encode($channel_array);
							$connection = new ConnectAPI();
							$url = "http://".IP.":8065/api/v1/channels/create";//url for creating a channel for chatting
							$response = json_decode($connection->sendPostDataWithToken($url,$data,$token_id));
							if($connection->httpResponseCode==200){//it means channel has been created successfully	
								create_tab($conn,$tab_name,$template_id,$createdBy,$ou_specific);	
							}
							else echo json_encode(array("status"=>false,"message"=>$response->message));
						}
						else {
							echo json_encode(array("status"=>false,"message"=>"Please Login Again, we are unable to get your token"));
						}							
					}
					else if($template_name=="Latest News Template"){
						if(isNewsTitleExists($conn,$title)){
							echo json_encode(array("status"=>false,"message"=>"News with the same title already existed."));
						}
						else{
							if(createNews($conn,$tab_name)){
								create_tab($conn,$tab_name,$template_id,$createdBy,$ou_specific);
							}
							else{
								echo json_encode(array("status"=>false,"message"=>"Unable to create news"));
							}
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
				echo json_encode(array("status"=>false,"message"=>"A Tab with that name already exists. Try another name."));
			}
		}
		else echo json_encode(array("status"=>true,"message"=>"Unable to connect database"));
		
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Please select whether the tab is OU specific or not..!"));
	}
	
}
else 
	echo json_encode(array("status"=>false,"message"=>"Session expired, please login again."));
//echo json_encode(array("status"=>true,"message"=>$ou_specific));

//php function to create News
function createNews($conn,$title){
	$id = randId(26);//creating unique id
	$createAt = time()*1000;
	$query = "insert into News (Id,CreateAt,title) values('$id','$createAt','$title')";
	if($conn->query($query)){
		return true;
	}else{
		return false;
	}
}
?>

