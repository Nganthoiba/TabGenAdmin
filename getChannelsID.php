<?php
/* php code for getting list of channels along with IDs associated with the particular teams which the particular user belongs to */

if(!empty($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	if($conn){
		$teams=getTeams($conn,$user_id);
		//$json_data = json_encode($teams);
		//print $json_data;
		//echo "Size of array: ".sizeof($teams);
		$output=null;
		for($i=0;$i<sizeof($teams);$i++){
			$team_name = $teams[$i]['team_name'];
			$query = "select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,Teams.Name as Team_Name
						from Channels,Teams
						where Channels.TeamId = Teams.Id and
							Teams.Name='$team_name'";
			$channels=null;
			$res = $conn->query($query);
			if($res){
				while($row=$res->fetch(PDO::FETCH_ASSOC)){
					$channels[]=$row;
				}
				$output[$i]=array($team_name=>$channels);
			}
		}
		$final_array = array("team_list"=>$teams,"channels"=>$output);
		print json_encode($final_array);
		//print_r ($teams);
		/*$query = "select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,Users.Username,Teams.Name as Team_Name
				from Channels,Users,Teams
				where Channels.TeamId = Users.TeamId and Teams.Id = Users.TeamId and Users.Id='$user_id'";
		$res=$conn->query($query);
		if($res){
             while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
             }
             echo json_encode($output);
        }*/
	}
}
?>
