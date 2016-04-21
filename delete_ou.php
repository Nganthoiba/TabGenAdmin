<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');
	$org_unit_id = $_POST['org_unit_id'];
	$time = time();
	$ou_name=getOUNameByOuId($conn,$org_unit_id);
	if($conn){
		$res1=$conn->query("update Users set DeleteAt='$time' where Id in (select user_id 
							from User_OU_Mapping where OU_id='$org_unit_id')");
		if($res1){
			$res2=$conn->query("delete from User_OU_Mapping where OU_id = '$org_unit_id'");
			if($res2){
				$query="delete from OrganisationUnit where OrganisationUnit.Id='$org_unit_id'";
				$res3 = $conn->query($query);					
				if($res3){	
					$conn->query("update Tab set DeleteAt='$time' 
								where RoleId = (select Id from Role where OrganisationUnit='$ou_name')");
					$conn->query("Update Role set DeleteAt='$time' where OrganisationUnit='$ou_name'");
					
					$conn->query("delete from RoleTabAsson 
									where TabId in (select Id from Tab where DeleteAt !=0)
									OR RoleId in (select Id from Role where DeleteAt!=0)");
					echo "true";
				}
				else echo "false";
			}
			else{ 
				echo "false";
			}
		}	
	}
?>
