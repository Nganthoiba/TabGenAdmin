<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');// all the function/ methodes are in this php file
	if(!empty($_POST['ou_name']) && !empty($_POST['role_name']) && !empty($_POST['tab_id'])){
		$ou_name = $_POST['ou_name'];
		$role_name = $_POST['role_name'];
		$tab_id = $_POST['tab_id'];
		$role_id = $_POST['role_id'];
		if($conn) {
			//$role_id = findRoleId($conn,$ou_name,$role_name);
			if(isTabOUSpecific($conn,$tab_id)){
				if(getOUbyRole($conn,$role_id)==getOUbyRole($conn,getRolebyTab($conn,$tab_id))){
					//if the tab is to associated in the same OU under which tab was created
					associateTabToRole($conn,$role_id,$tab_id);
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"Tab is OU specific and cannot be associated to other OU!"));
				}	
			}
			else{	
				associateTabToRole($conn,$role_id,$tab_id);
			}
		}
		else echo json_encode(array("status"=>false,"message"=>"Database Connection failed!"));
	}
	else 
		echo json_encode(array("status"=>false,"message"=>"Invalid parameter passed!"));	
?>
