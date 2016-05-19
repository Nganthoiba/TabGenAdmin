<?php
session_start();
//include('server_IP.php');
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file
include('ConnectAPI.php');

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
		echo json_encode(array("status"=>false,"message"=>"Invalid perameter passed..!"));
	}
}
else 
	echo json_encode(array("status"=>false,"message"=>"Session expired, please login again."));
//creating tab	
function create_tab($conn,$tab_name,$template_id,$createdBy,$ou_specific){
	$id = randId(26);//creating unique id
	$createAt = time()*1000;
	$query=null;
	
	$ou_name = $_POST['ou_name'];
	$role_name = $_POST['role_name'];
	$role_id = findRoleId($conn,$ou_name,$role_name);
	
	if($ou_specific == "true"){//check if the tab to be created is OU specific or not
		$query="INSERT INTO Tab(Id,CreateAt,UpdateAt,DeleteAt,Name,RoleName,CreatedBy,TabTemplate,RoleId,OU_Specific)
				values('$id','$createAt','$createAt',0,'$tab_name','$role_name','$createdBy','$template_id','$role_id',1)";
	}
	else{
		$query="INSERT INTO Tab(Id,CreateAt,UpdateAt,DeleteAt,Name,RoleName,CreatedBy,TabTemplate,RoleId,OU_Specific)
				values('$id','$createAt','$createAt',0,'$tab_name','$role_name','$createdBy','$template_id','$role_id',0)";	
	}
	
	if($role_id==null){
		echo json_encode(array("status"=>false,"message"=>"Oops! Role does not exist. Please refresh the page and try again."));
	}
	else{
		if($conn->query($query)){
			associateTabToRole($conn,$role_id,$id);
			echo json_encode(array("status"=>true,"message"=>"Tab created successfully"));
		}
		else{ 
			echo json_encode(array("status"=>false,"message"=>"Oops! Something is not right, try again later"));
		}
	}
	
}

?>

