<?php
	include('connect_db.php');
	$org_unit_id = $_POST['org_unit_id'];
	$query="delete from OrganisationUnit where OrganisationUnit.Id='$org_unit_id'";
	if($conn){
		$res = $conn->query($query);					
		if($res){
			$conn->query("delete from Teams where Teams.Name NOT IN (select OrganisationUnit from OrganisationUnit)");
			echo "true";
		}
		else echo "false";
	}
?>