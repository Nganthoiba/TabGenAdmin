<?php
	include('connect_db.php');
	$org_unit = $_GET['org_unit'];
	$query="select * from Role where OrganisationUnit='$org_unit'";
	if($conn){
		$res = $conn->query($query);					
		if($res){
			$count=0;
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
				$count++;
			}
			if($count>0)
				print(json_encode($output));
			else
				echo "false";
		}
	}
?>