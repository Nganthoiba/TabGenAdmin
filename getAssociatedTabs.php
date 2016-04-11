<?php 
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
	$ou_name = $_GET['ou_name'];
	$role_name = $_GET['role_name'];
	if($conn){
		$role_id = findRoleId($conn,$ou_name,$role_name);
		$query="select Tab.Id,Tab.Name 
				from RoleTabAsson,Tab 
				where Tab.Id=TabId and
					RoleTabAsson.RoleId='$role_id'";
		$res = $conn->query($query);
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$output[]=$row;
			}
			echo json_encode($output);
		}
		else
			echo "problem";
	}
	else
		echo "problem";
	
?>
