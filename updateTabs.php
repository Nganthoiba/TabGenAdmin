<?php
session_start();
include('connect_db.php');
include('tabgen_php_functions.php');
//include('server_IP.php');
	$index = $_POST['index'];
	if(!empty($_POST['tab_id']) && !empty($_POST['template_name']) && !empty($_POST['tab_name'])){
		$index = $_POST['index'];
		$tab_id = $_POST['tab_id'];
		$tab_name=$_POST['tab_name'];
		$template_name=$_POST['template_name'];
		
		try{
			if($conn){
				$template_id=findTemplateId($conn,$template_name);
				if($template_id!=null){
					
					$updateTime = time();
					$query2 = "UPDATE Tab set TabTemplate = '$template_id',UpdateAt='$updateTime',Name='$tab_name' WHERE Tab.Id='$tab_id'";
							/*$query2 = "UPDATE Tab set TabTemplate = '$template_id',UpdateAt='$updateTime'  
							WHERE RoleId='$role_id' AND Name='$tab_name' AND RoleName='$role_name' AND OUId='$ou_id'";*/
					try{
						$result = $conn->query($query2);
						if($result){
							$response = array("index"=>"".$index,"response"=>"<font color='#198D24'>Updated</font>","state"=>true);
							echo json_encode($response);
						}
						else {
							$response = array("index"=>"".$index,"response"=>"<font color='#C52039'>Update Failed</font>","state"=>false);
							echo json_encode($response);
						}
					}
					catch(Exception $e){
						$response = array("index"=>"".$index,"response"=>"Failed to save data: ".$e->getMessage(),"state"=>false);
						echo json_encode($response);
					}
				}
				else{ 
					$response = array("index"=>"".$index,"response"=>"Template dosn't exist","state"=>false);
					echo json_encode($response);
				}							
			}
		}catch(PDOException $e){
			$response = array("index"=>"".$index,"response"=>"Failed to save data: ".$e->getMessage(),"state"=>false);
			echo json_encode($response);
		}		
	}
	else{
		$response = array("index"=>"".$index,"response"=>"No perameter passed..!","state"=>false);
		echo json_encode($response);
	}



?>
