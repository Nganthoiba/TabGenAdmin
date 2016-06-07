<?php
	include('connect_db.php');
	//include('tabgen_php_functions.php');
	
	$org_name=$_GET['org_name'];
	if($conn){
		$query = "select * from OrganisationUnit where Organisation='$org_name' order by CreateAt desc";
		$res = $conn->query($query);
		$output=null;
		$count=0;
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
				$count++;
			}
		}
		if($count>0)
			echo json_encode($output);
		else
			echo "null";
	}

?>
