<?php
include('connect_db.php');
include('tabgen_php_functions.php');
$query1 = "select * from Tab"; 
$res  = $conn->query($query1);
while($row = $res->fetch(PDO::FETCH_ASSOC)){
	$ou = getOUbyRole($conn,$row['RoleId']);
	$tab_id = $row['Id'];
	/*if($ou==NULL)
		echo "Tab Id: ".$tab_id."OU: NULL<br/>";
	else
		echo "Tab Id: ".$tab_id."OU: ".$ou."<br/>";*/
	if($row['OrganisationUnit']==NULL){
		$query2 = "update Tab set OrganisationUnit='$ou' where Id='$tab_id'";
		if($conn->query($query2)){
			if($ou==NULL)
				echo "Tab Id: ".$tab_id." updated with OU: NULL<br/>";
			else
				echo "Tab Id: ".$tab_id." updated with OU: ".$ou."<br/>";
		}
		else{
			if($ou==NULL)
				echo "Tab Id: ".$tab_id." not updated with OU: NULL<br/>";
			else
				echo "Tab Id: ".$tab_id." not updated with OU: ".$ou."<br/>";
		}
	}
	else{
		echo "Tab Id: ".$tab_id." not updated, has already OU: ".$row['OrganisationUnit']."<br/>";
	}
}

$res  = $conn->query($query1);
while($row = $res->fetch(PDO::FETCH_ASSOC)){
	//if($row['OrganisationUnit']=='')
	$org = getOrgbyOU($conn,$row['OrganisationUnit']);
	$tab_id = $row['Id'];
	/*if($ou==NULL)
		echo "Tab Id: ".$tab_id."OU: NULL<br/>";
	else
		echo "Tab Id: ".$tab_id."OU: ".$ou."<br/>";*/
	if($row['Organisation']==NULL){
		$query2 = "update Tab set Organisation='$org' where Id='$tab_id' and OrganisationUnit!=''";
		if($conn->query($query2)){
			if($ou==NULL)
				echo "Tab Id: ".$tab_id." updated with org: NULL<br/>";
			else
				echo "Tab Id: ".$tab_id." updated with org: ".$org."<br/>";
		}
		else{
			if($ou==NULL)
				echo "Tab Id: ".$tab_id." not updated with org: NULL<br/>";
			else
				echo "Tab Id: ".$tab_id." not updated with org: ".$org."<br/>";
		}
	}
	else{
		echo "Tab Id: ".$tab_id." not updated, has already org: ".$row['Organisation']."<br/>";
	}
}


?>
