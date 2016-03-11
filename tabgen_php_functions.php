<?php
/* function for creating tabs */
function createTabs($conn,$start,$no_of_tabs,$org_unit,$role_name,$createdBy){
	for($i=$start;$i<=$no_of_tabs;$i++){
		$tab_name = $org_unit." ".$role_name." Tab".$i;
		$id = randId(26);
		$role_id = findRoleId($conn,$org_unit,$role_name);
		$ou_id = findOUId($conn,$org_unit);
		$createAt = time();
		if($ou_id!=null){
			if($role_id!=null){
				$timestamp = time();
				$query="INSERT INTO Tab(Id,CreateAt,UpdateAt,Name,RoleName,CreatedBy,RoleId,OUId)
						values('$id','$createAt','$timestamp','$tab_name','$role_name','$createdBy','$role_id','$ou_id')";
				try{
					$result = $conn->query($query);
					if($result){
						echo $tab_name." Saved successfully.<br/>";
					}
					else echo $tab_name." Could not be saved.<br/>";
				}
				catch(Exception $e){
					echo $tab_name." Could not be saved: ".$e->getMessage()."<br/>";
				}
			}
			else echo $tab_name." failed to create, it seems role does not exist. Create it first.<br/>";
		}else echo $tab_name." Organisation Unit does not exist, create it first.<br/>";
	}
}
//to update user role
function updateUserRole($userId,$con){
	$role=$_POST['Role'];
	$query="UPDATE Users SET Roles='$role' WHERE Id='$userId'";
	if($con->query($query)){
		echo "true";
	}
	else echo "Role ".$role." could not be updated";;
}
	
function randId($length){
	$id = md5(uniqid());
		$char = str_shuffle($id);
		for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
			$rand .= $char{mt_rand(0, $l)};
		}
		return $rand;
	}
	/* function to find number of tabs of a particular role*/
function existingNoOfTabs($roleName,$org_unit,$conn){
	$res = $conn->query("SELECT COUNT(*) AS NO_OF_TABS 
							FROM Tab,Role 
							where Tab.RoleName='$roleName' and 
							Tab.RoleId=Role.Id and 
							Role.OrganisationUnit='$org_unit'");
	$row = $res->fetch(PDO::FETCH_ASSOC);
	$no_of_tabs = (int)$row['NO_OF_TABS'];
	return $no_of_tabs;
}

	
//to get OU id
function findOUId($conn,$org_unit){
	$query_result = $conn->query("select Id from OrganisationUnit where OrganisationUnit='$org_unit'");
	$row_data = $query_result->fetch(PDO::FETCH_ASSOC);
	$ou_id = $row_data['Id'];
	if(isset($ou_id))
		return $ou_id;
	else return null;
}
// to get RoleId
function findRoleId($conn,$org_unit,$role_name){
	$query_result = $conn->query("select Id from Role where OrganisationUnit='$org_unit' and RoleName='$role_name'");
	$row_data = $query_result->fetch(PDO::FETCH_ASSOC);
	$role_id = $row_data['Id'];
	if(isset($role_id))
		return $role_id;
	else return null;
}
//to get template Id
function findTemplateId($conn,$template_name){
	$query_result = $conn->query("SELECT Id,Name FROM TabTemplate where Name='$template_name'");
	$curr_row = $query_result->fetch(PDO::FETCH_ASSOC);
	$template_id = $curr_row['Id'];
	if(isset($template_id))
		return $template_id;
	else return null;
}
//for getting template name
function getTemplateName($conn,$template_id){
	//include('connect_db.php');
	$query_res = $conn->query("select TabTemplate.Name as Template_Name from TabTemplate where id='$template_id'");
	$result_row=$query_res->fetch(PDO::FETCH_ASSOC);
	return($result_row['Template_Name']);
}
//to check if the user role in an OU is a universal or not
function isUniversalRole($conn,$role_name,$orgunit){
	$resp = $conn->query("select * from Role where RoleName='$role_name' and OrganisationUnit='$orgunit'");
	if($resp){
		$row = $resp->fetch(PDO::FETCH_ASSOC);
		$universal_role = $row['UniversalRole'];
		if($universal_role=="true")
			return true;
		else return false;
	}
	return false;
}

//to get team_name
function getTeamName($conn,$team_id){
	$result = $conn->query("select * from Teams where Id='$team_id'");
	if($result){
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$team_name = $row['Name'];
		return $team_name;
	}
	else
		return null;
}

//getting team id by username from the users table
function getTeamIdByUsername($conn,$user_name){
	$result = $conn->query("select * from Users where Username='$user_name'");
	if($result){
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$team_id = $row['TeamId'];
		return $team_id;
	}
	else
		return null;
}

?>