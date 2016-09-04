<?php
include('ConnectAPI.php');
include('tabgen_php_functions.php');
include('connect_db.php');
$rolaname = $_POST['role_name'];
$org_name = $_POST['org_name'];

$ou_specific = $_POST['ou_specific'];
$role_type = $_POST['role_type'];
$universal_role=$ou_specific=="true"?"false":"true";
$ousel = $_POST['ousel'];//getting ou name

if (isRoleAlreadyExists($conn,$rolaname,$ousel,$ou_specific)==false){
	if(!empty($ousel)){	
		if($rolaname!='' && $ousel!=''){
			$data = array(
			   "organisation" => $org_name,
			   "organisationUnit"  => $ousel,
			   "universalRole" => $universal_role,	
				"role_name" => $rolaname 
			);
			
			$url_send ="http://".IP.":8065/api/v1/organisationRole/create";
			$str_data = json_encode($data);
			
			$connect = new ConnectAPI();
			$result = $connect->sendPostData($url_send,$str_data);
			if($result!=null){
				try{
					$responseData = json_decode($result);
					if($connect->httpResponseCode==200){
						updateRoleType($conn,$responseData->id,$role_type);
						echo "true";
					}else if($connect->httpResponseCode==0){
						echo "false";
					}
					else 
						echo "Error: ".$responseData->message;
				}catch(Exception $e){
					echo "Exception: ".$e->getMessage();
				}
			}
			else 
				echo "false";
		}
		else{	
			echo "false";
		}
	}
	else{
		echo "Please send OU.";
	}
}
else{
	echo "Sorry, a role with the same domain already exists!";
}

function isRoleAlreadyExists($conn,$rolaname,$ousel,$ou_specific){
	$universal_role=$ou_specific=="true"?"false":"true";
	if($ou_specific=="true"){
		$query="select count(*) as count from Role where UniversalRole='true'
					and RoleName='$rolaname'
					and DeleteAt=0";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if((int)$row['count']>0)
			return true;
		else
			return false;
	}
	else{
		$query="select count(*) as count from Role where RoleName='$rolaname' and DeleteAt=0";
		$res = $conn->query($query);
		$row = $res->fetch(PDO::FETCH_ASSOC);
		if((int)$row['count']>0)
			return true;
		else
			return false;
	}
}

?>
