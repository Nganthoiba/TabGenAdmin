
<?php 

if (isset($_SERVER['HTTP_X_AUTHENTICATION_TOKEN'])) {
    $request_header = $_SERVER['HTTP_X_AUTHENTICATION_TOKEN'];
    echo $request_header;
} else {
    if (function_exists('getallheaders')) {
        foreach (getallheaders() as $header_name => $header_value) {
            if ($header_name == 'X_AUTHENTICATION_TOKEN') {
                $request_header = $header_value;
            }
        }
        echo $request_header;
    }
}
	/*phpInfo(); 
	
	$new_path="userdata/profile_pics";
	if(!is_dir($new_path) || !file_exists($new_path)) {
            if(mkdir($new_path , 0777)){
				echo json_encode(array("status"=>false,"message"=>"Directory created.."));
			} 
			else{
				echo json_encode(array("status"=>false,"message"=>"Directory not created.."));
			}  
        }else{
			echo json_encode(array("status"=>false,"message"=>"Directory already exists.."));
		}*/

?>
