<?php 
/*code for removing a tab from tabstrips*/
include('connect_db.php');
include('tabgen_php_functions.php');

$tab_id = $_POST['tab_id'];
$tabstrip_id = $_POST['tabstrip_id'];

$query = "delete from Tabstrip_Tab_Mapping where tabstripId='$tabstrip_id' and tabId='$tab_id'";
if($conn->query($query)){
	echo json_encode(array("status"=>true,"message"=>"Successfully removed."));
}
else{
	echo json_encode(array("status"=>false,"message"=>"Failed to remove tab."));
}

?>
