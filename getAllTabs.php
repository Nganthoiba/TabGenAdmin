<?php
include('connect_db.php');
include('tabgen_php_functions.php');

	if($conn){
			$query = "SELECT Tab.*,TabTemplate.Name as Template_Name 
						FROM Tab,TabTemplate
						where Tab.TabTemplate=TabTemplate.Id and
							Tab.DeleteAt=0
						order by Tab.CreateAt desc";
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
				}
			}
			if(sizeof($output)==0)
				echo "null";
			else
				echo json_encode($output);
	}
	else echo "false";


?>
