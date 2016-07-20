<?php
$article_id = $_POST['article_id'];
include('connect_db.php');
include('tabgen_php_functions.php');
if(!empty($_FILES)) {
	//userFile
	if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
		$sourcePath = $_FILES['userImage']['tmp_name'];
		$new_path = "uploaded_image/";		
		if(!is_dir($new_path) || !file_exists($new_path)){
            mkdir($new_path , 0777);
        }
		$targetPath = $new_path.$_FILES['userImage']['name'];
		if(move_uploaded_file($sourcePath,$targetPath)) {
			 //echo "Target: ".$targetPath;
			if($conn){
				$time=time()*1000;
				$query = "Update Article set Images='$targetPath', UpdateAt=$time where Id='$article_id'";
				if($conn->query($query)){
					//echo "<img src='".$targetPath."' width='100%' height='80%'/>";
					echo json_encode(array("status"=>true,"message"=>"Successfully uploaded..","image_path"=>$targetPath));
				}
				else{
					//echo "<center>Something went wrong.. Try again later.</center>";
					echo json_encode(array("status"=>false,"message"=>"Something went wrong.. Try again later."));
				}
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"Something went wrong.. Try again later."));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Failed to upload your image. Try again later."));
		}
	}
	else if(is_uploaded_file($_FILES['userFile']['tmp_name'])) {
		$sourcePath = $_FILES['userFile']['tmp_name'];
		$new_path = 'uploaded_file/';
				
		if(!is_dir($new_path) || !file_exists($new_path)) {
			/*
            if(mkdir($new_path , 0777)){
				echo json_encode(array("status"=>false,"message"=>"Directory created.."));
			} 
			else{
				echo json_encode(array("status"=>false,"message"=>"Directory not created.."));
			}
			*/  
			mkdir($new_path , 0777);
        }
        $targetPath = $new_path.$_FILES['userFile']['name'];
		if(move_uploaded_file($sourcePath,$targetPath)) {
			 //echo "Target: ".$targetPath;
			if($conn){
				$time=time()*1000;
				//$query = "Update Article set Filenames='$targetPath', UpdateAt=$time where Id='$article_id'";
				$file_id = randId(26);//creating unique id
				$query = "insert into ArticleFiles(Id,article_id,file_name) values('$file_id','$article_id','$targetPath')";
				if($conn->query($query)){
					$file_list = getFiles($conn,$article_id);
					echo json_encode(array("status"=>true,"message"=>"Successfully uploaded..","files_storage_path"=>$file_list));
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"Something went wrong.. Try again later."));
				}
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"Something went wrong.. Try again later."));
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Failed to upload your image. Try again later."));
		}
	}
	else if(is_uploaded_file($_FILES['news_image']['tmp_name'])) {
		$sourcePath = $_FILES['news_image']['tmp_name'];
		$new_path = 'uploaded_file/';
				
		if(!is_dir($new_path) || !file_exists($new_path)) {  
			mkdir($new_path , 0777);
        }
        $targetPath = $new_path.$_FILES['news_image']['name'];
		if(move_uploaded_file($sourcePath,$targetPath)) {
			$query = "Update News set Image='$targetPath' where Id='$article_id'";
			if($conn->query($query)){
				echo json_encode(array("status"=>true,"message"=>"Successfully uploaded..","image_path"=>$targetPath));
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"Something went wrong.. Try again later."));
			}
				
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Failed to upload your image. Try again later."));
		}
	}
}
else echo "No file is received....";

?>
