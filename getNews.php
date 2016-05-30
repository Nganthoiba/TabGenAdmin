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
				$query = "select * 
							from News
							where title in 
									(SELECT Tab.Name
										FROM Tab,RoleTabAsson,Role
										where Tab.Id=RoleTabAsson.TabId
										and Tab.RoleId =Role.Id
                                        and Role.OrganisationUnit='$team_name'
										and Tab.DeleteAt=0
										and RoleTabAsson.RoleId = '$role_id')";
				
				$res = $conn->query($query);
				if($res){
					$count=0;
					$channels=null;
					while($row=$res->fetch(PDO::FETCH_ASSOC)){
						$channels[]=$row;
						$count++;
					}	
					if($count>0){
						//$output[]=array($team_name=>$channels);
						//$accessible_teams[]=$team_name;
						$output->team_list[]->team_name=$team_name;
						$output->channels->$team_name=$channels;
					}
				}		
				
		}
		
		//$final_array = array("team_list"=>$accessible_teams,"channels"=>$output);
		print json_encode($output);
	}	
}


?>
