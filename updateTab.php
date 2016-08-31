<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');
	include('ConnectAPI.php');
	if(!empty($_POST['tab_id']) && !empty($_POST['old_tab_name']) && !empty($_POST['template_name']) && !empty($_POST['new_tab_name'])){
		$tab_id = $_POST['tab_id'];
		$old_tab_name = $_POST['old_tab_name'];
		$new_tab_name = $_POST['new_tab_name'];
		$template_name = $_POST['template_name'];
		
		if($conn){
			if($template_name=="Latest News Template" || $template_name=="News Template"){
				$details=$_POST['news_details'];
				if(updateNews($conn,$old_tab_name,$new_tab_name,$details)){	
					updateTab($conn,$tab_id,$new_tab_name);
				}
				else{
					echo json_encode(array("status"=>false,
					"message"=>"Failed to update news"));
				}
			}
			else if($template_name=="Chat Template"){
				$token_id = $_POST['token'];//get_token();
				//echo json_encode(array("status"=>false,"message"=>$token_id));
				if($token_id!=null){
					/*getting channel details for the channel having same name as the earlier tab name*/
					$channel_details = getChannelByName($conn,$old_tab_name);//this returns null of the channel does not exists
					if($channel_details!=null){
						/* it means a channel already exists with the same name as tab name, so we are going to update that channel name
						with the new tab name.			
						
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
						$update_channel_data = json_encode(array("id"=>$channel_details[0]['Id'],
											"team_id"=>$channel_details[0]['TeamId'],
											"type"=>"O",
											"display_name"=>$new_tab_name,
											"name"=>strtolower(str_replace(' ','_',$new_tab_name)),
											"creator_id"=>$channel_details[0]['CreatorId']));
											
						$update_channel_url = "http://".IP.":8065/api/v1/channels/update";
						$updateChannel = new ConnectAPI();
						$update_channel_response = json_decode($updateChannel->sendPostDataWithToken($update_channel_url,$update_channel_data,$token_id));
						if($updateChannel->httpResponseCode==200){
							//it means channel has been updated successfully
							updateTab($conn,$tab_id,$new_tab_name);//and now updating the tab name
						}
						else if($updateChannel->httpResponseCode==0){
							echo json_encode(array("status"=>false,"message"=>"Unable to connect API for updating channel name"));
						}
						else{
							echo json_encode(array("status"=>false,"message"=>$update_channel_response->message));
						}
						
					}else{
						echo json_encode(array("status"=>false,"message"=>"No channel exists with the earlier tab name"));
					}
				}else{
					echo json_encode(array("status"=>false,"message"=>"Login again please, your token id is expired."));
				}
			}
			else{
				updateTab($conn,$tab_id,$new_tab_name);
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Database connection failed"));
		}
	}
	else{ 
		echo json_encode(array("status"=>false,"message"=>"Invalid Request!"));
	}
		
	function updateTab($conn,$tab_id,$new_tab_name){
		$updateTime=time()*1000;
		$query = "update Tab set Name = '$new_tab_name',UpdateAt='$updateTime' where Id='$tab_id'";
		if($conn->query($query)){
			echo json_encode(array("status"=>true,"message"=>"Successfully updated."));
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Oops! Unable to update tab name."));
		}
	}
	//function to update news
	function updateNews($conn,$old_news_title,$latest_news_title,$details){
		$query = "update News set
					title='$latest_news_title',
					Details='$details'
				  where title='$old_news_title'";
		return $conn->query($query);
	}
?>
