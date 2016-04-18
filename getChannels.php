<?php
/* php code for getting list of channels along with IDs associated with the particular teams which the particular user belongs to */

if(!empty($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	if($conn){
		$teams=getOUs($conn,$user_id);//getting a	list of user accessible OUs
		$output=null;
		$query=null;
		
		//echo "hi Size: ".sizeof($teams);
		$role_id = findRoleIdByUser_id($conn,$user_id);
		$role_name = getRoleByUserId($conn,$user_id);
		$accessible_teams=null;
		for($i=0;$i<sizeof($teams);$i++){//finding all the possible channels for a team
			$team_name = $teams[$i]['team_name'];
			//echo $team_name."<br/>";
			if($team_name != "Associated Tabs"){
				//Getting OU specific channels
				$query = "select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,count(*) as members_count 
							from Channels,ChannelMembers
							where Channels.Name in (select Tab.Name from Tab 
															where RoleId = (select Id from Role 
															where OrganisationUnit='$team_name'
															and RoleName='$role_name'))
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
							$channels[]=$row;
						}
						else{
							//getting the other user in the private message channel
							$username=getUserInPrivateMessageChannel($conn,$row['Channel_ID'],$user_id);
							$channels[]=array("Channel_ID"=>$row['Channel_ID'],"Channel_name"=>$username,"members_count"=>$row["members_count"],"Team_Name"=>$row['Team_Name']);
						}
						$count++;
					}
					if($count>0){
						$accessible_teams[]=$team_name;
						$output[$i]=array($team_name=>$channels);
					}
				}
			}	
				
		}
		$final_array = array("team_list"=>concate_array($accessible_teams,array("Associated Tabs")),"channels"=>concate_array($output,getAssociatedChannels($conn,$user_id,$role_id)));
		print json_encode($final_array);
		//array($output[0],$output[1],$output[2])
		//print sizeof($output);
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
							where Channels.Name in (SELECT Tab.Name
								FROM Tab,TabTemplate,RoleTabAsson
								where Tab.TabTemplate=TabTemplate.Id
								and TabTemplate.Name='Chat Template'
								and Tab.Id=RoleTabAsson.TabId
								and RoleTabAsson.RoleId = '$role_id')
							and Channels.DeleteAt=0
							and Channels.Id=ChannelId
							group by Channels.Id";	
			//and Channels.Id in (select ChannelId from ChannelMembers where UserId='$user_id')
	$res = $conn->query($query);
	if($res){
		while($row=$res->fetch(PDO::FETCH_ASSOC)){
			if($row['Channel_name']!="")
				$channels[]=$row;
			else{
				//getting the other user in the private message channel
				$username=getUserInPrivateMessageChannel($conn,$row['Channel_ID'],$user_id);
				$channels[]=array("Channel_ID"=>$row['Channel_ID'],"Channel_name"=>$username,"members_count"=>$row["members_count"],"Team_Name"=>$row['Team_Name']);
			}
		}
		$output[]=array("Associated Tabs"=>$channels);
	}		
	return $output;
}
?>
