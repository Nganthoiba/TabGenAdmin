<?php
	include('connect_db.php');
	include('tabgen_php_functions.php');
	$org_id = $_POST['org_id'];
		
	if($conn){
		
		/*selecting all the OUs under the organisation to be deleted*/
		$query2="select Id from OrganisationUnit 
				where Organisation = 
					(select Name from Organisation where Id='$org_id')";
		$res = $conn->query($query2);
		if($res){
			$org_unit_id="";
			$flag=1;
			while($row = $res->fetch(PDO::FETCH_ASSOC)){
				$org_unit_id = $row['Id'];
				if(!deleteOU($conn,$org_unit_id)){
					$flag=0;
					break;
				}
				else{
					$flag=1;	
				}	
			}
			if($flag==0){
				echo json_encode(array("status"=>false,"message"=>"Unable to delete Organisation because 
					some Organisation Units under the organisation could not be deleted"));
			}
			else{
				$query1="delete from Organisation where Organisation.Id='$org_id'";
				if($conn->query($query1))
					echo json_encode(array("status"=>true,"message"=>"OU deleted"));
				else
					echo json_encode(array("status"=>true,"message"=>"OU could not be deleted"));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Unable to retrive Organisation Units 
				which exist under the organisation"));
		}
	}
	else {
		echo json_encode(array("status"=>false,"message"=>"Failed to connect database."));
	}
?>
