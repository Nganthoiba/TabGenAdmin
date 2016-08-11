<?php
/*GCM Services in php*/
function sendGoogleCloudMessage($data, $ids) {
    $apiKey = 'AIzaSyBXuHwQiXttNBAlQO5sf8899OGK8ZPHbQ4';//'AIzaSyAczj4Or2XXcPX53zc9K2GREP-j9lV3zC8';
    $url = 'https://android.googleapis.com/gcm/send';
    $post = array(
        'registration_ids' => $ids,
        'data' => $data,
	//	'link' => $img,
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
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        header("location:notification.php");
    }
   
	
		$_SESSION['Error'] = "Notifiation Sent";
		header("location:notification.php");
		curl_close($ch);
}
?>
