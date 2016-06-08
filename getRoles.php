<?php
	include('connect_db.php');
	$org_unit = $_GET['org_unit'];
	$only_ou_roles = $_GET['only_ou_roles'];//
	if(empty($only_ou_roles) || $only_ou_roles=="no")
	{
		$query="select * from Role where OrganisationUnit='$org_unit' and DeleteAt=0 order by RoleName";
				/*union 
				select * from Role where UniversalRole='true' and DeleteAt=0 order by RoleName";*/
		if($conn){
			$res = $conn->query($query);					
			if($res){
				$count=0;
				while($row=$res->fetch(PDO::FETCH_ASSOC)){
					$output[]=$row;
					$count++;
				}
				if($count>0)
					print(json_encode($output));
				else
					echo "false";
			}
		}
	}
	else if($only_ou_roles=="yes"){
		$query="select * from Role where OrganisationUnit='$org_unit' and DeleteAt=0 order by RoleName";
		if($conn){
			$res = $conn->query($query);					
			if($res){
				$count=0;
				while($row=$res->fetch(PDO::FETCH_ASSOC)){
					$output[]=$row;
					$count++;
				}
				if($count>0)
					print(json_encode($output));
				else
					echo "false";
			}
		}
	}
	else{
		echo "<b>Invalid perameters sent...!</b>";
	}
	
?>
