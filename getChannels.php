<?php
/* php code for getting list of channels along with IDs associated with the particular teams which the particular user belongs to */

if(!empty($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	if($conn){
		$teams=getOUs($conn,$user_id);//getting a list of user accessible OUs
		$output=null;
		$query=null;
		
		//echo "hi Size: ".sizeof($teams);
		$role_id = findRoleIdByUser_id($conn,$user_id);
		$role_name = getRoleByUserId($conn,$user_id);
		$accessible_teams=null;
		for($i=0;$i<sizeof($teams);$i++){//finding all the possible channels for a team
			$team_name = $teams[$i]['team_name'];
			//echo $team_name."<br/>";
			
				//Getting OU specific channels
				$query = "select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,count(*) as members_count 
							from Channels,ChannelMembers
							where Channels.DisplayName in 
									(SELECT Tab.Name
										FROM Tab,RoleTabAsson,Role
										where Tab.Id=RoleTabAsson.TabId
										and Tab.RoleId =Role.Id
                                        and Role.OrganisationUnit='$team_name'
										and Tab.DeleteAt=0
										and RoleTabAsson.RoleId = '$role_id')
							and Channels.DeleteAt=0
							and Channels.Id=ChannelId
							group by Channels.Id
							
							union
							
						select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,count(*) as members_count 
							from Channels,ChannelMembers
							where Channels.DisplayName in (select Tab.Name from Tab 
															where RoleId = (select Id from Role 
																where OrganisationUnit='$team_name'
																and RoleName='$role_name')
															and Tab.DeleteAt=0)								
							and Channels.DeleteAt=0
							and Channels.Id=ChannelId
							group by Channels.Id";
				//and Channels.Id in (select ChannelId from ChannelMembers where UserId='$user_id')
				$res = $conn->query($query);
				if($res){
					$count=0;
					$channels=null;
					while($row=$res->fetch(PDO::FETCH_ASSOC)){
						if($row['Channel_name']!=""){
							//$channels[]=$row;
							$channels[]=array("Channel_ID"=>$row['Channel_ID'],"Channel_name"=>$row['Channel_name'],
							"members_count"=>getMembersCount($conn,$row['Channel_ID']));
						}
						else{
							//getting the other user in the private message channel
							$username=getUserInPrivateMessageChannel($conn,$row['Channel_ID'],$user_id);
							$channels[]=array("Channel_ID"=>$row['Channel_ID'],"Channel_name"=>$username,
							"members_count"=>getMembersCount($conn,$row['Channel_ID']));
						}
						$count++;
					}	
					if($count>0){
						$output[]=array($team_name=>$channels);
						$accessible_teams[]=$team_name;
					}
				}		
				
		}
		/*$final_array = array("team_list"=>concate_array(array("Others"),$accessible_teams),
		"channels"=>concate_array(getAssociatedChannels($conn,$user_id,$role_id),$output));*/
		$final_array = array("team_list"=>$accessible_teams,"channels"=>$output);
		print json_encode($final_array);
	}
	
	
}

function getAssociatedChannels($conn,$user_id,$role_id){
	$channels=null;
	$org_unit=getOU_Byuser_Id($conn,$user_id);
	//$role_name = getRoleByUserId($conn,$user_id);
	//echo $role_id;
	//echo $org_unit."\n";
	$query = "select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,count(*) as members_count 
							from Channels,ChannelMembers
							where Channels.DisplayName in (SELECT Tab.Name
								FROM Tab,TabTemplate,RoleTabAsson
								where Tab.TabTemplate=TabTemplate.Id
								and TabTemplate.Name='Chat Template'
								and Tab.Id=RoleTabAsson.TabId
								and Tab.DeleteAt=0
								and Tab.RoleId is null
								and RoleTabAsson.RoleId = '$role_id')
							and Channels.DeleteAt=0
							and Channels.Id=ChannelId
							group by Channels.Id";	
			//and Channels.Id in (select ChannelId from ChannelMembers where UserId='$user_id')
	$res = $conn->query($query);
	if($res){
		while($row=$res->fetch(PDO::FETCH_ASSOC)){
			if($row['Channel_name']!=""){
				//$channels[]=$row;
				$channels[]=array("Channel_ID"=>$row['Channel_ID'],"Channel_name"=>$row['Channel_name'],
				"members_count"=>getMembersCount($conn,$row['Channel_ID']));
			}else{
				//getting the other user in the private message channel
				$username=getUserInPrivateMessageChannel($conn,$row['Channel_ID'],$user_id);
				$channels[]=array("Channel_ID"=>$row['Channel_ID'],"Channel_name"=>$username,
				"members_count"=>getMembersCount($conn,$row['Channel_ID']));
			}
		}
		$output[]=array("Others"=>$channels);
	}		
	return $output;
}

?>
