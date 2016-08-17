<?php
include('connect_db.php');
include('tabgen_php_functions.php');

$ou_specific_tab=$_GET['ou_specific_tab'];
$org=$_GET['org'];
$role_id = $_GET['role_id'];

if(!empty($_GET['role_id'])){
	$flag = $ou_specific_tab=="true"?1:0;
	$query=null;
	if($flag==1){
		$ou = $_GET['ou'];
		$query = "SELECT Tab.*,TabTemplate.Name as Template_Name 
						FROM Tab,TabTemplate
						where Tab.TabTemplate=TabTemplate.Id and
							Tab.OU_Specific='$flag' and
							Tab.Id not in(select TabId from RoleTabAsson where 
										RoleId='$role_id') and
							Tab.DeleteAt=0 and
							Tab.OrganisationUnit='$ou'
						order by Tab.CreateAt desc";
	}
	else{
		$query = "SELECT Tab.*,TabTemplate.Name as Template_Name 
						FROM Tab,TabTemplate
						where Tab.TabTemplate=TabTemplate.Id and
							Tab.OU_Specific='$flag' and
							Tab.Id not in(select TabId from RoleTabAsson where 
										RoleId='$role_id') and
							Tab.DeleteAt=0 and
							Tab.Organisation='$org'
						order by Tab.CreateAt desc";
	}
	//echo $role_id." ".$ou." ".$org." ".$flag;
	if($conn){
			//$role_id = findRoleId($conn,$ou,$role_name);
			$res = $conn->query($query);
			while($row = $res->fetch(PDO::FETCH_ASSOC)){
				if($row['Template_Name']=="Latest News Template" || $row['Template_Name']=="News Template"){
					$news_details=getNewsDetails($conn,$row['Name'])==null?"Enter news here":getNewsDetails($conn,$row['Name']);
					$output[]=array("Id"=>$row['Id'],"CreateAt"=>$row['CreateAt'],"UpdateAt"=>$row['UpdateAt'],
						"DeleteAt"=>$row['DeleteAt'],"Name"=>$row['Name'],"RoleName"=>$row['RoleName'],"CreatedBy"=>$row['CreatedBy'],
						"TabTemplate"=>$row['TabTemplate'],"RoleId"=>$row['RoleId'],"OU_Specific"=>$row['OU_Specific'],
						"RoleName"=>getRoleNamebyId($conn,$row['RoleId']),
						"Template_Name"=>$row['Template_Name'],
						"news_details"=>$news_details,
						"Org"=>$row['Organisation'],
						"OU"=>$row['OrganisationUnit']);
						//"OU"=>getOUbyRole($conn,$row['RoleId']));
				}
				else{
					$output[]=array("Id"=>$row['Id'],"CreateAt"=>$row['CreateAt'],"UpdateAt"=>$row['UpdateAt'],
					"DeleteAt"=>$row['DeleteAt'],"Name"=>$row['Name'],"RoleName"=>$row['RoleName'],"CreatedBy"=>$row['CreatedBy'],
					"TabTemplate"=>$row['TabTemplate'],"RoleId"=>$row['RoleId'],"OU_Specific"=>$row['OU_Specific'],
					"RoleName"=>getRoleNamebyId($conn,$row['RoleId']),
					"Template_Name"=>$row['Template_Name'],
					"news_details"=>" ",
					"Org"=>$row['Organisation'],
					"OU"=>$row['OrganisationUnit']);
					//"OU"=>getOUbyRole($conn,$row['RoleId']));
				}
				//$row;''
			}
			if(sizeof($output)==0)
				echo "null";
			else
				echo json_encode($output);
	}
	else echo "false";
}
else{
	echo "Role Id is not sent";
}



?>
