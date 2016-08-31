<?php
	/*code for getting roles*/
	include('connect_db.php');
	include('tabgen_php_functions.php');
	$org_unit = empty($_GET['org_unit']) || $_GET['org_unit']==' '?'null':$_GET['org_unit'];
	$only_ou_roles = $_GET['only_ou_roles'];//
	$organisation=$_GET['org'];
	if(empty($organisation)) 
		$organisation=getOrgbyOU($conn,$org_unit);
	if(empty($only_ou_roles) || $only_ou_roles=="no")//getting not ou specific roles
	{
		$query="select * from Role where OrganisationUnit='$org_unit' and DeleteAt=0 
				union 
				select * from Role where UniversalRole='true' and 
					OrganisationName='$organisation' and 
					DeleteAt=0 
				order by RoleName";
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
	else if($only_ou_roles=="yes"){//getting OU specific roles
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
