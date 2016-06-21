<?php 
	include('connect_db.php');
	include('tabgen_php_functions.php');
	
	$tabstrip_name = $_POST['tabstrip_name'];
	$org_name = $_POST['org_name'];
	$org_unit = $_POST['org_unit'];
	$role_id = $_POST['role_id'];
	$ou_specific = $_POST['ou_specific'];
	
	if(empty($tabstrip_name) || $tabstrip_name==null){
		echo json_encode(array("status"=>false,"message"=>"Tabstrip name is null."));
	}
	else if(empty($org_name) || $org_name==null){
		echo json_encode(array("status"=>false,"message"=>"Organisation name is null."));
	}
	else if(empty($org_unit) || $org_unit==null){
		echo json_encode(array("status"=>false,"message"=>"Organisation Unit name is null."));
	}
	else if(empty($role_id) || $role_id==null){
		echo json_encode(array("status"=>false,"message"=>"Role Id is null."));
	}
	else if(empty($ou_specific) || $ou_specific==null){
		echo json_encode(array("status"=>false,"message"=>"Please properly mention whether OU specific or not by passing true or false."));
	}
	else{
		
		if(isTabstripExist($conn,$tabstrip_name))
		{
			echo json_encode(array("status"=>false,"message"=>"A Tabstrip of the same name already exists."));
		}
		else{
			$id = randId(26);//creating unique id
			$time = time()*1000;
			$flag = $ou_specific == "true"?1:0;
			$query = "insert into Tabstrip(Id,CreateAt,UpdateAt,DeleteAt,Name,Organisation,OrganisationUnit,RoleId,OU_Specific)
						values('$id','$time','$time',0,'$tabstrip_name','$org_name','$org_unit','$role_id','$flag')";
						
			if($conn->query($query))
				echo json_encode(array("status"=>true,"message"=>"Tabstrip has been created."));
			else
				echo json_encode(array("status"=>true,"message"=>"Oops! Tabstrip could not be created due to some problem."));
		}
	}
	
?>
