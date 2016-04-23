<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
	
	if($conn){
		$query = "select Users.*,OrganisationUnit,Organisation 
				from Users,User_OU_Mapping,OrganisationUnit
				where Users.Id=User_OU_Mapping.user_id
				and User_OU_Mapping.OU_id=OrganisationUnit.Id";
		$res = $conn->query($query);
		$output=null;
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
			}
			echo json_encode($output);
		}
		else{
			echo "error";
		}
	}
	else{
		echo "error";
	}
?>
