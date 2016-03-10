<?php
session_start();
//include('server_IP.php');
include('connect_db.php');
//$conn = new PDO("mysql:host=188.166.210.24;dbname=mattermost_test",'mmuser','mostest');
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
			else echo "Role ".$role_name." does not exist for ".$org_unit.". Create it first.<br/>";
		}else echo "Organisation Unit named ".$org_unit." does not exist, create it first.<br/>";
	}
	else{
		echo "No perameter passed..!";
	}
}
else echo "Session expired, login again.";
?>
<?php
	/* function for creating tabs */
	function createTabs($conn,$start,$no_of_tabs,$org_unit,$role_name,$createdBy){
		for($i=$start;$i<=$no_of_tabs;$i++){
			$tab_name = $org_unit." ".$role_name." Tab".$i;
			$id = randId(26);
			$role_id = findRoleId($conn,$org_unit,$role_name);
			$ou_id = findOUId($conn,$org_unit);
			$createAt = time();
			if($ou_id!=null){
				if($role_id!=null){
					$timestamp = time();
					$query="INSERT INTO Tab(Id,CreateAt,UpdateAt,Name,RoleName,CreatedBy,RoleId,OUId)
						values('$id','$createAt','$timestamp','$tab_name','$role_name','$createdBy','$role_id','$ou_id')";
					try{
						$result = $conn->query($query);
						if($result){
							echo $tab_name." Saved successfully.<br/>";
						}
						else echo $tab_name." Could not be saved.<br/>";
					}
					catch(Exception $e){
						echo $tab_name." Could not be saved: ".$e->getMessage()."<br/>";
					}
				}
				else echo $tab_name." failed to create, it seems role does not exist. Create it first.<br/>";
			}else echo $tab_name." Organisation Unit does not exist, create it first.<br/>";
		}
	}
	
	function randId($length){
		$id = md5(uniqid());
		$char = str_shuffle($id);
		for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
			$rand .= $char{mt_rand(0, $l)};
		}
		return $rand;
	}
	/* function to find number of tabs of a particular role*/
	function existingNoOfTabs($roleName,$org_unit,$conn){
		$res = $conn->query("SELECT COUNT(*) AS NO_OF_TABS 
							FROM Tab,Role 
							where Tab.RoleName='$roleName' and 
							Tab.RoleId=Role.Id and 
							Role.OrganisationUnit='$org_unit'");
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$no_of_tabs = (int)$row['NO_OF_TABS'];
		return $no_of_tabs;
	}
	function findRoleId($conn,$org_unit,$role_name){
		$query_result = $conn->query("select Id as Role_ID from Role where OrganisationUnit='$org_unit' and RoleName='$role_name'");
		$row_data = $query_result->fetch(PDO::FETCH_ASSOC);
		$role_id = $row_data['Role_ID'];
		if(isset($role_id))
			return $role_id;
		else return null;
	}
	
	function findOUId($conn,$org_unit){
		$query_result = $conn->query("select Id from OrganisationUnit where OrganisationUnit='$org_unit'");
		$row_data = $query_result->fetch(PDO::FETCH_ASSOC);
		$ou_id = $row_data['Id'];
		if(isset($ou_id))
			return $ou_id;
		else return null;
	}
?>
