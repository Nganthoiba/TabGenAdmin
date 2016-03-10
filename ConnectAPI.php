<?php 
class ConnectAPI{
	var $httpResponseCode;
	function sendPostData($url, $data){
	        /*echo $url, $data;*/
	
		try{
		$ch = curl_init($url);
			$headers = array();
			$headers[] = 'Accept: application/json';
			$headers[] = 'Content-Type: application/json';
			
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");  
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			$result = curl_exec($ch);
                        //echo $result; 
			$this->httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        // echo $this->httpResponseCode;
			curl_close($ch);  // Seems like good practice
			return $result;	
		}catch(Exception $e){
			echo $e->getMessage();
			$result = null;
		}
		return $result;
	}
}
?>

