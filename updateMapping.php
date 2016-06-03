<?php
include('connect_db.php');
include('tabgen_php_functions.php');
$query = "select user_id from User_OU_Mapping";
$res = $conn->query($query);
while($row = $res->fetch(PDO::FETCH_ASSOC)){
	$user_id = $row['user_id'];
	
	$role_id = findRoleIdByUser_id($conn,$user_id);
	$universal_access = isUserUniversalAccessRight($conn,$user_id)?1:0;
	$inner_query="update User_OU_Mapping set RoleId='$role_id',UniversalAccess='$universal_access' where user_id='$user_id'";
	if($conn->query($inner_query)){
		echo "Updated Successfully for user id = ".$user_id." <br/>";
	}
	else{
		echo "Update failed for user id = ".$user_id." <br/>";
	}

}
?>
