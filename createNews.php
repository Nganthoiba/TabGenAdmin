<?php

if(!empty($_FILES)) {
	if(is_uploaded_file($_FILES['attachFile']['tmp_name'])) {
		$sourcePath = $_FILES['attachFile']['tmp_name'];
		$new_path = "uploaded_image/";		
		if(!is_dir($new_path) || !file_exists($new_path)){
            mkdir($new_path , 0777);
        }
		$targetPath = $new_path.$_FILES['attachFile']['name'];
		if(move_uploaded_file($sourcePath,$targetPath)) {
			 //echo "Target: ".$targetPath;
			echo json_encode(array("status"=>true,"message"=>$_FILES['attachFile']['name']));
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Failed to upload your image. Try again later."));
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Failed to upload your image. Try again later."));
	}
}
else{
	echo json_encode(array("status"=>false,"message"=>"No file sent. Try again later."));
}	
?>
