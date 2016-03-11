<?php 
	$role = $_GET['Role'];
	$org_unit = $_GET['org_unit'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
	//$query="select TabTemplate.Name as Template_Name,TABS.Name as Tab_Name,RoleName from TabTemplate,TABS where RoleName='$role' AND Tab.TabTemplate=TabTemplate.Id";
			
	if($conn){
		if(isUniversalRole($conn,$role,$org_unit)){
			$temporaryQuery="select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,RoleName
					from TabTemplate,Tab
					where Tab.TabTemplate=TabTemplate.Id 
					order by Tab.Name";
			
		}else{
			$temporaryQuery="select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,RoleName,OrganisationUnit 
					from TabTemplate,Tab,OrganisationUnit 
					where Tab.TabTemplate=TabTemplate.Id 
						and OrganisationUnit.Id=Tab.OUId 
						and OrganisationUnit='$org_unit'
						and RoleName='$role'
					order by Tab.Name";
		}
		$res = $conn->query($temporaryQuery);	
			if($res){
				while($row=$res->fetch(PDO::FETCH_ASSOC)){
					$output[]=$row;
				}
				print(json_encode($output));
			}
	}
	

?>
