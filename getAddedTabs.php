<?php 
include('connect_db.php');
include('tabgen_php_functions.php');

$org_name = $_POST['org_name'];
$ou_name = $_POST['ou_name'];
$ou_specific = $_POST['ou_specific']=="true"?1:0;
$tabstrip_id = $_POST['tabstrip_id'];

$query=null;
$query = "select * from Tab 
	where Organisation='$org_name'
	and DeleteAt=0
	and Id in (select tabId from Tabstrip_Tab_Mapping where tabstripId='$tabstrip_id' order by slno asc)";
/*if($ou_specific==1){
	$query = "select * from Tab 
	where OrganisationUnit='$ou_name' 
	and OU_Specific=$ou_specific 
	and Id in (select tabId from Tabstrip_Tab_Mapping where tabstripId='$tabstrip_id')
	order by CreateAt desc"; 
}
else{
	$query = "select * from Tab 
	where Organisation='$org_name' 
	and OU_Specific=$ou_specific 
	and Id in (select tabId from Tabstrip_Tab_Mapping where tabstripId='$tabstrip_id')
	order by CreateAt desc"; 
}*/
$res = $conn->query($query);
if($res){
	$output=null;
	$count=0;
	while($row = $res->fetch(PDO::FETCH_ASSOC)){
		$output[]=$row;
		$count++;
	}
	if($count>0){
		echo json_encode(array("status"=>true,"output"=>$output));
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"No Tab found."));
	}
}
else{
	echo json_encode(array("status"=>false,"message"=>"Something is wrong with query."));
}


?>
