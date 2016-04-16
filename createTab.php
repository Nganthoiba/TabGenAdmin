<?php
session_start();
//include('server_IP.php');
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file
include('ConnectAPI.php');

if(isset($_SESSION['user_details'])){
	$user_details = json_decode($_SESSION['user_details']);
	if(!empty($_POST)){
		$tab_name = $_POST['tab_name'];
		$createdBy = $user_details->username;
		$template_name = $_POST['template_name'];
		
		if($conn){
			if(!isTabExist($conn,$tab_name)){//if the tab does not exist already
				$template_id=findTemplateId($conn,$template_name);
				if($template_id!=null){
					if($template_name=="Chat Template"){
						$token_id = get_token();
						if($token_id!=null){
							$user_details = json_decode($_SESSION['user_details']);
							$team_id = $user_details->team_id;
							$channel_array = array("display_name"=>$tab_name,"name"=>$tab_name,"team_id"=>$team_id,"type"=>"O");
							$data = json_encode($channel_array);
							$connection = new ConnectAPI();
							$url = "http://".IP.":8065/api/v1/channels/create";
							$response = json_decode($connection->sendPostDataWithToken($url,$data,$token_id));
							if($connection->httpResponseCode==200){//it means channel has been created successfully	
								create_tab($conn,$tab_name,$template_id,$createdBy);	
							}
							else echo json_encode(array("status"=>false,"message"=>$response->message));
						}
						else {
							echo json_encode(array("status"=>false,"message"=>"Please Login Again, we are unable to get your token"));
						}							
					}
					else{
						create_tab($conn,$tab_name,$template_id,$createdBy);
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
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"No perameter passed..!"));
	}
}
else 
	echo json_encode(array("status"=>false,"message"=>"Session expired, login again."));
//creating tab	
function create_tab($conn,$tab_name,$template_id,$createdBy){
	$id = randId(26);//creating unique id
	$createAt = time();
	$query = "INSERT INTO Tab(Id,CreateAt,UpdateAt,DeleteAt,Name,CreatedBy,TabTemplate)
								values('$id','$createAt','$createAt',0,'$tab_name','$createdBy','$template_id')";
	if($conn->query($query)){
		echo json_encode(array("status"=>true,"message"=>"Tab created"));
	}
	else echo json_encode(array("status"=>false,"message"=>"Failed to create tab"));
}

?>

