<?php
include('connect_db.php');
include('tabgen_php_functions.php'); 
if(!empty($_GET)){
	
	$orgUnit = $_GET['orgunit'];
	$role = $_GET['role'];
	if($conn){
		//echo "Database Connection Successful";
		if($role=="All"){
			$query="select OrganisationUnit,Role.RoleName, Tab.Name as Tab_Name, Tab.TabTemplate as Template_ID from Role,Tab where Role.RoleName = Tab.RoleName
					and Role.OrganisationUnit='$orgUnit' and Tab.RoleId=Role.Id order by Tab_Name";
		}
		else{
			/*if(isUniversalRole($conn,$role,$orgUnit)==true){
				$query="select OrganisationUnit,Role.RoleName, Tab.Name as Tab_Name, Tab.TabTemplate as Template_ID from Role,Tab where Role.RoleName = Tab.RoleName
				and Tab.RoleId=Role.Id order by Tab_Name";
			}
			else{*/
				$query="select OrganisationUnit,Role.RoleName, Tab.Name as Tab_Name, Tab.TabTemplate as Template_ID from Role,Tab where Role.RoleName = Tab.RoleName
				and Role.OrganisationUnit='$orgUnit' and Role.RoleName='$role' and Tab.RoleId=Role.Id order by Tab_Name";
			//}
		}
		$res=$conn->query($query);
		$count=0;
		while($row=$res->fetch(PDO::FETCH_ASSOC)){
			$my_array = array("OrganisationUnit"=>$row['OrganisationUnit'],"RoleName"=>$row['RoleName'],"Tab_Name"=>$row['Tab_Name'],"Template_Name"=>getTemplateName($conn,$row['Template_ID']));
			$output[]=$my_array;
			$count++;
		}
		if($count>0) 
			echo json_encode($output);
		else 
			echo "false";
	}
}
else echo "Invalid request";

?>