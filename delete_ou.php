<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');
	$org_unit_id = $_POST['org_unit_id'];
	$query="delete from OrganisationUnit where OrganisationUnit.Id='$org_unit_id'";
	$ou_name=getOUNameByOuId($conn,$org_unit_id);
	if($conn){
		$res = $conn->query($query);					
		if($res){
			$time = time();
			$conn->query("update table Users set DeleteAt='$time' where TeamId=(select Id from Teams where Name='$ou_name')");
			$conn->query("delete from Teams where Teams.Name = '$ou_name'");
			echo "true";
		}
		else echo "false";
	}
?>
