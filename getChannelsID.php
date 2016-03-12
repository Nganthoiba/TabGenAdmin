<?php
/* php code for getting list of channels along with IDs associated with the particular teams which the particular user belongs to */

if(!empty($_POST['user_id'])){
	$user_id = $_POST['user_id'];
	include('connect_db.php');
	if($conn){
		$query = "select Channels.Id as Channel_ID, Channels.DisplayName as Channel_name,Users.Username,Teams.Name as Team_Name
				from Channels,Users,Teams
				where Channels.TeamId = Users.TeamId and Teams.Id = Users.TeamId and Users.Id='$user_id'";
		$res=$conn->query($query);
		if($res){
             while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
             }
             echo json_encode($output);
        }
	}
//}
?>
