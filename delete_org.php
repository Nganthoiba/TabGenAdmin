<?php
	include('connect_db.php');
	$org_id = $_POST['org_id'];
	$query="delete from Organisation where Organisation.Id='$org_id'";
	if($conn){
		$res = $conn->query($query);					
		if($res){
			echo "true";
		}
		else echo "false";
	}
?>