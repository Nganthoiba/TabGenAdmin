<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
	
	if(!empty($_GET['user_name'])){
		$user_name = $_GET['user_name'];
		if($conn){
			$query = "select Users.*,OrganisationUnit,Organisation,UniversalAccess 
					from Users,User_OU_Mapping,OrganisationUnit,UserUniversalAccessibility
					where Users.Id=User_OU_Mapping.user_id
					and User_OU_Mapping.OU_id=OrganisationUnit.Id
					and Users.DeleteAt=0
					and Username like '%$user_name%'
					and Users.Id=UserUniversalAccessibility.UserId";
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
	}
	else{
			echo "Invalid Request, pass appropriate parameters";
	}
?>
