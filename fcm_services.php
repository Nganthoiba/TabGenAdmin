<?php
include('connect_db.php');
include('tabgen_php_functions.php');
$res = $conn->query("select token_id from FCM_Users");
$tokens = array();
while($row = $res->fetch(PDO::FETCH_ASSOC)){
	$tokens[]=$row['token_id'];
}

$message = array("message"=>"FCM Push notification test");

$msg_result = sendFirebasedCloudMessage($tokens,$message);

echo $msg_result;

//echo json_encode($tokens);


?>
