<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
	if(!empty($_POST['ou_name']) && !empty($_POST['role_name']) && !empty($_POST['tab_id'])){
		$ou_name = $_POST['ou_name'];
		$role_name = $_POST['role_name'];
		$tab_id = $_POST['tab_id'];
		if($conn) {
			$role_id = findRoleId($conn,$ou_name,$role_name);
			$query = "insert into RoleTabAsson values('$role_id','$tab_id')";
			if($role_id!=null){
				if($conn->query($query)){
					echo "true";
				}
				else {
					echo "false";
				}
			}
			else echo "Role does not exist";
		}
		else echo "Database Connection failed";
	}
	else 
		echo "Invalid parameter passed!";
?>
