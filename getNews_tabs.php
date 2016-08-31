<?php
/*this will get list of news tabs under respective OUs in json*/
if(!empty($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	if($conn){
		$teams=getOUs($conn,$user_id);//getting a list of user accessible OUs
		$output=null;
		$query=null;
		
		
		$role_id = findRoleIdByUser_id($conn,$user_id);
		$role_name = getRoleByUserId($conn,$user_id);
		$accessible_teams=null;
		
		for($i=0;$i<sizeof($teams);$i++){//finding all the possible channels for a team
			$team_name = $teams[$i]['team_name'];
			$team_id = $teams[$i]['Id'];
			$query = "SELECT Tab.Id as tab_id,Tab.Name as tab_name
					FROM Tab,RoleTabAsson,Role,TabTemplate
						where Tab.Id=RoleTabAsson.TabId
							and Tab.TabTemplate=TabTemplate.Id
							and TabTemplate.Name='Latest News Template'
							and Tab.RoleId =Role.Id
                            and Role.OrganisationUnit='$team_name'
							and Tab.DeleteAt=0
							and RoleTabAsson.RoleId = '$role_id'
						order by Tab.CreateAt asc";
				
				$res = $conn->query($query);
				if($res){
					$count=0;
					$channels=null; 
					$tab_list=null;
					while($row=$res->fetch(PDO::FETCH_ASSOC)){
						$channels[]=$row;
						$count++;
					}	
					if($count>0){
						$output->response->org_units[]=array("ou_id"=>$team_id,"name"=>$team_name,"tabs"=>$channels);
					}
				}			
		}
		//$final_array = array("team_list"=>$accessible_teams,"channels"=>$output);
		print json_encode($output);
	}	
}

?>

