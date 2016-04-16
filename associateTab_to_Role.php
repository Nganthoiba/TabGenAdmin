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
				if(!isTabAlreadyAssociated($conn,$role_id,$tab_id)){
					if($conn->query($query)){
						echo json_encode(array("status"=>true,"message"=>"Successfully associated."));
					}
					else {
						echo json_encode(array("status"=>false,"message"=>"Unable to associate, an internal problem occurs."));
					}
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"Tab is already associated!"));
				}
			}
			else echo json_encode(array("status"=>false,"message"=>"Role does not exist!"));
		}
		else echo json_encode(array("status"=>false,"message"=>"Database Connection failed!"));
	}
	else echo json_encode(array("status"=>false,"message"=>"Invalid parameter passed!"));
		
?>
