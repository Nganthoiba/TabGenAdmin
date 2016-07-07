<?php
include('connect_db.php');
include('tabgen_php_functions.php');
if(empty($_POST['org_name'])){
	echo json_encode(array("status"=>false,"message"=>"Please pass organisation name."));
}
else if(empty($_POST['ou_specific'])){
	echo json_encode(array("status"=>false,"message"=>"Please pass ou specific or not."));
}
else{
	$query=null;
	$ou_specific = $_POST['ou_specific']=="true"?1:0;
	if($ou_specific==1){
		$ou_name=$_POST['ou_name'];
		$query = "select * from Tabstrip where OrganisationUnit='$ou_name' and OU_Specific='$ou_specific' order by CreateAt desc"; 
	}
	else{
		$org_name=$_POST['org_name'];
		$query = "select * from Tabstrip where Organisation='$org_name' and OU_Specific='$ou_specific' order by CreateAt desc"; 
	}
	$res=$conn->query($query);
	if($res){
		$count=0;
		$output=null;
		while($row=$res->fetch(PDO::FETCH_ASSOC)){
			$output[]=$row;
			$count++;
		}
		if($count>0){
			echo json_encode(array("status"=>true,"output"=>$output));
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"No Tabstrip found.."));
		}
	}
	else 
		echo json_encode(array("status"=>false,"message"=>"An unknown error occurs."));
}
?>
