<?php 
	$user_id = $_GET['user_id'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	//$query="select TabTemplate.Name as Template_Name,TABS.Name as Tab_Name,RoleName from TabTemplate,TABS where RoleName='$role' AND Tab.TabTemplate=TabTemplate.Id";
			
	if($conn){
		if(isUserUniversalAccessRight($conn,$user_id)){// if the user has universal access right
			$temporaryQuery="select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,RoleName,OrganisationUnit 
					from TabTemplate,Tab,OrganisationUnit 
					where Tab.TabTemplate=TabTemplate.Id 
						and OrganisationUnit.Id=Tab.OUId
					order by Tab.Name";
			$res = $conn->query($temporaryQuery);	
			if($res){
				while($row=$res->fetch(PDO::FETCH_ASSOC)){
					$output[]=$row;
				}
				print(json_encode($output));
			}
			
		}else{
			/*$query="select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,RoleName,OrganisationUnit 
					from TabTemplate,Tab,OrganisationUnit 
					where Tab.TabTemplate=TabTemplate.Id 
						and OrganisationUnit.Id=Tab.OUId 
						and OrganisationUnit='$org_unit'
						and RoleName='$role'
					order by Tab.Name";*/
			$role = getRoleByUserId($conn,$user_id);
			$ou_id =getOuIdByUserId($conn,$user_id);
			$parent_ou_id = getParentOuId($conn,$ou_id);
			
			$own_tabs=findTabs($conn,$role,$ou_id);
			$parent_tabs=findTabs($conn,$role,$parent_ou_id);
			//echo "Parent OU id: ".$parent_ou_id;
			//print json_encode($own_tabs);
			print json_encode(concate_array($own_tabs,$parent_tabs));
			//print json_encode($parent_tabs);
		}
		
	}

function findTabs($conn,$role,$ou_id){
	$query = "select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,RoleName,OrganisationUnit
			  from TabTemplate,Tab,OrganisationUnit
			  where Tab.TabTemplate=TabTemplate.Id
			  and OrganisationUnit.Id=Tab.OUId
			  and RoleName='$role' 
			  and OUId='$ou_id'";
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
