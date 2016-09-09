<?php 
	/*php code get list of templates*/
	$role = $_GET['Role'];
	$org_unit = $_GET['org_unit'];
	include('connect_db.php');
	include('tabgen_php_functions.php');
			
	if($conn){
		
		$temporaryQuery="select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,RoleName,OrganisationUnit 
					from TabTemplate,Tab,OrganisationUnit 
					where Tab.TabTemplate=TabTemplate.Id 
						and OrganisationUnit.Id=Tab.OUId 
						and OrganisationUnit='$org_unit'
						and RoleName='$role'
					order by Tab.Name
					union
					select TabTemplate.Name as Template_Name,Tab.Name as Tab_Name,RoleName,OrganisationUnit 
					from TabTemplate,Tab,OrganisationUnit 
					where Tab.TabTemplate=TabTemplate.Id 
						and OrganisationUnit.Id=Tab.OUId 
						and Tab.Id in (select TabId from RoleTabAsson where RoleId = 
											select Id from Role where OrganisationUnit='$org_unit' and RoleName='$role')
					order by Tab.Name";
					
		$res = $conn->query($temporaryQuery);	
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
			}
			print(json_encode($output));
		}
	}
	

?>
