<?php 
/*code for adding tabs to tabstrips*/
include('connect_db.php');
include('tabgen_php_functions.php');

$tab_id = $_POST['tab_id'];
$tabstrip_id = $_POST['tabstrip_id'];
$query = "insert into Tabstrip_Tab_Mapping(tabstripId,tabId) values('$tabstrip_id','$tab_id')";
if($conn->query($query)){
	echo json_encode(array("status"=>true,"message"=>"Successfully added."));
}
else{
	echo json_encode(array("status"=>false,"message"=>"Failed to add tab."));
}

?>
