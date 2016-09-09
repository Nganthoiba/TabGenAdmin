<?php 
	$user_id = $_GET['user_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
		
	if($conn){
		if(isAdmin($conn,$user_id)){
			$associated_tabs = findAllTabs($conn);
			print json_encode($associated_tabs);
		}
		else{
			$role_id = findRoleIdByUser_id($conn,$user_id);
			$associated_tabs = findAssociatedTabsByRoleId($conn,$role_id);
			print json_encode($associated_tabs);
		}
		
	}
	
//function to find tabs specific to roles
function findTabs($conn,$role,$ou_id){
	$query = "select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,Tab.RoleName,OrganisationUnit
			  from TabTemplate,Tab,Role
			  where Tab.TabTemplate=TabTemplate.Id
			  and Tab.RoleName='$role' 
			  and Tab.RoleId=Role.Id
			  order by Tab_Name";
	$output = null;
	$res = $conn->query($query);
	if($res){
		while($row=$res->fetch(PDO::FETCH_ASSOC)){
			$output[]=$row;
		}	
	}
	return ($output);
}

//function to find tabs specific to roles types
function findTabsByRoleType($conn,$role_type){
	$query = "select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,Tab.RoleName,RoleType,OrganisationUnit
			  from TabTemplate,Tab,Role
			  where Tab.TabTemplate=TabTemplate.Id
			  and Role.RoleType='$role_type'
			  and Tab.RoleId=Role.Id
			  order by Tab_Name";
	$output = null;
	$res = $conn->query($query);
	if($res){
		while($row=$res->fetch(PDO::FETCH_ASSOC)){
			$output[]=$row;
		}	
	}
	return ($output);
}
//function to find the associated tabs according to Role Id
function findAssociatedTabsByRoleId($conn,$role_id){
	$query = "select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,Tab.RoleName,OrganisationUnit
			  from TabTemplate,Tab,RoleTabAsson
			  where Tab.TabTemplate=TabTemplate.Id
              and Tab.Id=RoleTabAsson.TabId
              and RoleTabAsson.RoleId='$role_id'
              and Tab.DeleteAt=0";
	
    $output = null;
	$res = $conn->query($query);
	if($res){
		while($row=$res->fetch(PDO::FETCH_ASSOC)){
			$output[]=$row;
		}	
	}
	return ($output);
}

//function to get all active tabs
function findAllTabs($conn){
	$query = "select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,Tab.RoleName,OrganisationUnit
			  from TabTemplate,Tab
			  where Tab.TabTemplate=TabTemplate.Id and Tab.DeleteAt=0";
	
    $output = null;
	$res = $conn->query($query);
	if($res){
		while($row=$res->fetch(PDO::FETCH_ASSOC)){
			$output[]=$row;
		}	
	}
	return ($output);
}

?>
