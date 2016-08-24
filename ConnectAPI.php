<?php 
class ConnectAPI{
	var $httpResponseCode;
	var $httpHeaderResponse;
	var $body;
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
			curl_setopt($ch, CURLOPT_HEADER, true);
			$result = curl_exec($ch);
                        //echo $result; 
			$this->httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$header_size = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
            $this->httpHeaderResponse = substr($result,0,$header_size);//getting headers
			$this->body = substr($result,$header_size);//getting body
			curl_close($ch);  // Seems like good practice
			return $this->body;	
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		//return $result;
	}

	
	function sendPostDataWithToken($url,$data,$token){
		try{
			$ch = curl_init($url);
			$headers = array();
			$headers[] = 'Accept: application/json';
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Authorization: Bearer '.$token;
			
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");  
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			//curl_setopt($ch, CURLOPT_HEADER, true);
			$result = curl_exec($ch);
                        //echo $result; 
			$this->httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);  // Seems like good practice
			return $result;	
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
	}
	
	//Getting data from an api by passing token
	function getDataByToken($url,$token){
		try{
			$ch = curl_init($url);
			$headers = array();
			$headers[] = 'Accept: application/json';
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Authorization: Bearer '.$token;
			
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"GET");  
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			//curl_setopt($ch, CURLOPT_HEADER, true);
			$result = curl_exec($ch);
                        //echo $result; 
			$this->httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);  // Seems like good practice
			return $result;	
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
	}

	//php function to read/parse HTTP response header
	function parse_headers($header)
	{
		$retVal = array();
		$fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
		foreach( $fields as $field ) {
		    if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
		        $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
		        if( isset($retVal[$match[1]]) ) {
		            $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
		        } else {
		            $retVal[$match[1]] = trim($match[2]);
		        }
		    }
		}
		return $retVal;
	} 
}
?>

