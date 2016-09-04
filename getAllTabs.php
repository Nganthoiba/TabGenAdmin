<?php
include('connect_db.php');
include('tabgen_php_functions.php');
$token = get_token_from_header();//getting token from header
if($token==null){
	echo json_encode(array("status"=>false,"message"=>"Your request is unauthorized."));
}
else{
	$user_id = getUserIdByToken($conn,$token);
	if($user_id==null){
		echo json_encode(array("status"=>false,"message"=>"Invalid token"));
	}
	else{
		if(isValidUser($conn,$user_id)){
			if(isAdmin($conn,$user_id)){
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
						if(sizeof($output)==0){
							echo json_encode(array("status"=>false,"message"=>"No Tabs at present"));
						}
						else{
							echo json_encode(array("status"=>true,"tabs_resp"=>$output));
						}
				}
				else {
					echo json_encode(array("status"=>false,"message"=>"Sorry, Failed to connect database."));
				}
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"You do not have sufficient permission"));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Sorry, the user id does not exist."));
		}
	}
}

?>
