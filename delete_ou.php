<?php
	include('connect_db.php');
	$org_unit_id = $_POST['org_unit_id'];
	$query="delete from OrganisationUnit where OrganisationUnit.Id='$org_unit_id'";
	if($conn){
		$res = $conn->query($query);					
		if($res){
			echo "true";
		}
		else echo "false";
	}
?>