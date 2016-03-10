<?php
session_start();
include('connect_db.php');
//include('server_IP.php');
	$index = $_POST['index'];
	if(!empty($_POST)){
		$index = $_POST['index'];
		$role_name=$_POST['role_name'];
		$tab_name=$_POST['tab_name'];
		$template_name=$_POST['template_name'];
		$org_unit = $_POST['org_unit'];
		try{
			if($conn){
				$template_id=findTemplateId($conn,$template_name);
				if($template_id!=null){
					$ou_id = findOUId($conn,$org_unit);
					$role_id = findRoleId($conn,$org_unit,$role_name);
					if($ou_id!=null){ 
						if($role_id!=null){
							$updateTime = time();
							$query2 = "UPDATE Tab set TabTemplate = '$template_id',UpdateAt='$updateTime'  
							WHERE RoleId='$role_id' AND Name='$tab_name' AND OUId='$ou_id'";
							/*$query2 = "UPDATE Tab set TabTemplate = '$template_id',UpdateAt='$updateTime'  
							WHERE RoleId='$role_id' AND Name='$tab_name' AND RoleName='$role_name' AND OUId='$ou_id'";*/
							try{
								$result = $conn->query($query2);
								if($result){
									$response = array("index"=>"".$index,"response"=>"<font color='#198D24'>Updated</font>");
									echo json_encode($response);
								}
								else {
									$response = array("index"=>"".$index,"response"=>"<font color='#C52039'>Update Failed</font>");
									echo json_encode($response);
								}
							}
							catch(Exception $e){
								$response = array("index"=>"".$index,"response"=>"Failed to save data: ".$e->getMessage());
								echo json_encode($response);
							}
						}
						else{
							$response = array("index"=>"".$index,"response"=>"Role Not Exist");
							echo json_encode($response);
						}
					}
					else{ 
						$response = array("index"=>"".$index,"response"=>"Organisation Unit does not exist");
						echo json_encode($response);
					}
				}
				else{ 
					$response = array("index"=>"".$index,"response"=>"Failed to get Template ID");
					echo json_encode($response);
				}							
			}
		}catch(PDOException $e){
			$response = array("index"=>"".$index,"response"=>"Failed to save data: ".$e->getMessage());
			echo json_encode($response);
		}		
	}
	else{
		$response = array("index"=>"".$index,"response"=>"No perameter passed..!");
		echo json_encode($response);
	}

function findOUId($conn,$org_unit){
	$query_result = $conn->query("select Id from OrganisationUnit where OrganisationUnit='$org_unit'");
	$row_data = $query_result->fetch(PDO::FETCH_ASSOC);
	$ou_id = $row_data['Id'];
	if(isset($ou_id))
		return $ou_id;
	else return null;
}

function findRoleId($conn,$org_unit,$role_name){
	$query_result = $conn->query("select Id from Role where OrganisationUnit='$org_unit' and RoleName='$role_name'");
	$row_data = $query_result->fetch(PDO::FETCH_ASSOC);
	$role_id = $row_data['Id'];
	if(isset($role_id))
		return $role_id;
	else return null;
}

function findTemplateId($conn,$template_name){
	$query_result = $conn->query("SELECT Id,Name FROM TabTemplate where Name='$template_name'");
	$curr_row = $query_result->fetch(PDO::FETCH_ASSOC);
	$template_id = $curr_row['Id'];
	if(isset($template_id))
		return $template_id;
	else return null;
}

?>
