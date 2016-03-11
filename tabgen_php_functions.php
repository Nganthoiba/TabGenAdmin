<?php

function getTemplateName($conn,$template_id){
	//include('connect_db.php');
	$query_res = $conn->query("select TabTemplate.Name as Template_Name from TabTemplate where id='$template_id'");
	$result_row=$query_res->fetch(PDO::FETCH_ASSOC);
	return($result_row['Template_Name']);
}

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
?>