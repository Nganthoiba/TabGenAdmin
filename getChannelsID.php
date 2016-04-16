<?php
/* php code for getting list of channels along with IDs associated with the particular teams which the particular user belongs to */

if(!empty($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	if($conn){
		$teams=getTeams($conn,$user_id);//getting a	list of user accessible teams
		$output=null;
		
		for($i=0;$i<sizeof($teams);$i++){//finding all the possible channels for a team
			$team_name = $teams[$i]['team_name'];
			$query = "select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,count(*) as members_count,Teams.Name as Team_Name
					  from Channels,Teams,ChannelMembers
					  where Channels.TeamId = Teams.Id
							and Channels.Id in (select ChannelId from ChannelMembers where UserId='$user_id')
							and Teams.Name='$team_name'
							and Channels.DeleteAt=0
							and Channels.Name!='off-topic'
							and Channels.Name!='town-square'
							and Channels.Id=ChannelId
							group by Channels.Id";//query to obtain channels which are not off-topic and townsquare
							
			/*$query = "select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,count(*) as members_count,Teams.Name as Team_Name
					  from Channels,Teams,ChannelMembers
					  where Channels.TeamId = Teams.Id
							and Channels.Id in (select ChannelId from ChannelMembers where UserId='$user_id')
							and Teams.Name='$team_name'
							and Channels.Id=ChannelId
							and Channels.Name!='off-topic'
							and Channels.Name!='town-square'
							group by Channels.Id";*/
			$channels=null;
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
				$output[$i]=array($team_name=>$channels);
			}
		}
		$final_array = array("team_list"=>$teams,"channels"=>$output);
		print json_encode($final_array);
	}
}
?>
