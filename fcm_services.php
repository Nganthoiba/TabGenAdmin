<?php
/*GCM Services in php*/
function sendFirebasedCloudMessage($fcm_token, $message) {
    $apiKey = 'key-AIzaSyAvs3D3fvlWo9tGl-cWt3a5BXRfCgd8nQo';
    //'AIzaSyBXuHwQiXttNBAlQO5sf8899OGK8ZPHbQ4';//'AIzaSyAczj4Or2XXcPX53zc9K2GREP-j9lV3zC8';
    $url = 'https://fcm.googleapis.com/fcm/send';
	 //'https://android.googleapis.com/gcm/send';
    $fields = array(
        'registration_ids' => $fcm_token,
        'data' => $message
    );
    $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, flase);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if($result==false){
		die('Curl failed: '.curl_error($ch));
	}
	return $result;
    /*
    if (curl_errno($ch)) {
        header("location:notification.php");
    }
   
	
	$_SESSION['Error'] = "Notifiation Sent";
	header("location:notification.php");
	curl_close($ch);
	*/
}
?>
