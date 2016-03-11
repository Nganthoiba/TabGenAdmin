<?php
session_start();
//include('server_IP.php');
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file

if(isset($_SESSION['user_details'])){
	$user_details = json_decode($_SESSION['user_details']);
	if(!empty($_POST)){
		$role_name=$_POST['role_name'];
		$no_of_tabs=(int)$_POST['no_of_tabs'];
		$org_unit = $_POST['orgunit'];
		$createdBy = $user_details->username;
		$role_id = findRoleId($conn,$org_unit,$role_name);
		$ou_id = findOUId($conn,$org_unit);
		if($ou_id!=null){
			if($role_id!=null){
				try{	
					if($conn){
						$existing_no_of_tabs = existingNoOfTabs($role_name,$org_unit,$conn);
						if($existing_no_of_tabs==0){
							$start = 1;
							createTabs($conn,$start,$no_of_tabs,$org_unit,$role_name,$createdBy);//creating tabs
						}
						else if($existing_no_of_tabs < $no_of_tabs){
							$start = $existing_no_of_tabs+1;
							createTabs($conn,$start,$no_of_tabs,$org_unit,$role_name,$createdBy);//creating tabs
						}
						else{
							echo $no_of_tabs." Tab(s) already created..";
						}	
					}			
				}
				catch(PDOException $e){
					echo "Failed to save: ".$e->getMessage();
				}
			}
			else echo "Role <b>".$role_name."</b> does not exist for <b>".$org_unit."</b>. Create it first.<br/>";
		}else echo "Organisation Unit named ".$org_unit." does not exist, create it first.<br/>";
	}
	else{
		echo "No perameter passed..!";
	}
}
else echo "Session expired, login again.";
?>

