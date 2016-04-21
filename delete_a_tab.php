<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
	include('ConnectAPI.php');
	if(!empty($_POST['tab_id'])){
		$tab_id = $_POST['tab_id'];
		
		if($conn){
			$tab_details = getTabWithTemplate($conn,$tab_id);
			if($tab_details['Template_Name']=="Chat Template"){
				$token_id = get_token();
				//echo json_encode(array("status"=>false,"message"=>$token_id));
				if($token_id!=null){
					/*getting channel details for the channel having same name as the earlier tab name*/
					$tab_name=$tab_details['Name'];
					$channel_details = getChannelByName($conn,$tab_name);//this returns null of the channel does not exists
					if($channel_details!=null){
						/* it means a channel already exists with the same name as tab name, so we are going to delete that channel name
						with the new tab name.	*/		
						
						$delete_channel_data = null;
											
						$delete_channel_url = "http://".IP.":8065/api/v1/channels/".$channel_details[0]['Id']."/delete";
						$deleteChannel = new ConnectAPI();
						$delete_channel_response = json_decode($deleteChannel->sendPostDataWithToken($delete_channel_url,$delete_channel_data,$token_id));
						if($deleteChannel->httpResponseCode==200){
							//it means channel has been deleted successfully
							deleteTab($conn,$tab_id);	
						}
						else if($deleteChannel->httpResponseCode==0){
							echo json_encode(array("status"=>false,"message"=>"Unable to connect API for updating channel name"));
						}
						else{
							echo json_encode(array("status"=>false,"message"=>$delete_channel_response->message));
						}
						
						
					}else{
						echo json_encode(array("status"=>false,"message"=>"No channel exists with the earlier tab name"));
					}
					
				}
				else{
						echo json_encode(array("status"=>false,"message"=>"Token not found. Login again."));
				}
						
			}else{
				//deleting Tabs which is not chat template
				deleteTab($conn,$tab_id);
			}
		}
		else{
			echo json_encode(array("state"=>false,"message"=>"Failed to Connect Database"));
		}
		
	}
	else{
		echo json_encode(array("state"=>false,"message"=>"Invalid Request"));
	}
	
	function getTabWithTemplate($conn,$tab_id){
		$query = "SELECT Tab.*,TabTemplate.Name as Template_Name 
				FROM Tab,TabTemplate
				where Tab.TabTemplate=TabTemplate.Id and Tab.Id='$tab_id'";
		$res = $conn->query($query);
		if($res){
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		else 
			return null;
	}
	
?>
