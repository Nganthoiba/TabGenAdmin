<?php
include('connect_db.php');
include('tabgen_php_functions.php');
session_start();
if(isset($_SESSION['user_details'])){
	$user_details = json_decode($_SESSION['user_details']);
	$created_by = $user_details->username;
	if($conn){
			$query = "SELECT Tab.*,TabTemplate.Name as Template_Name 
						FROM Tab,TabTemplate
						where Tab.TabTemplate=TabTemplate.Id and
							Tab.CreatedBy='$created_by' and
							Tab.DeleteAt=0
						order by Tab.CreateAt desc";
			$res = $conn->query($query);
			while($row = $res->fetch(PDO::FETCH_ASSOC)){
				$output[]=array("Id"=>$row['Id'],"CreateAt"=>$row['CreateAt'],"UpdateAt"=>$row['UpdateAt'],
				"DeleteAt"=>$row['DeleteAt'],"Name"=>$row['Name'],"RoleName"=>$row['RoleName'],"CreatedBy"=>$row['CreatedBy'],
				"TabTemplate"=>$row['TabTemplate'],"RoleId"=>$row['RoleId'],"OU_Specific"=>$row['OU_Specific'],
				"RoleName"=>getRoleNamebyId($conn,$row['RoleId']),
				"OU"=>getOUbyRole($conn,$row['RoleId']));
				//$row;''
			}
			echo json_encode($output);
	}
	else echo "false";
}
else 
	echo "Sesssion_expired";




?>
