<?php
$article_id = $_POST['article_id'];
include('connect_db.php');
include('tabgen_php_functions.php');

/*setting file upload size configuration programatically*/
ini_set('upload_max_filesize','256M');
ini_set('post_max_size','257M');
ini_set('max_input_time', 1300);
ini_set('max_execution_time', 1300);
/*********************************************************/
$token = get_token_from_header();//getting token from header
if($token==null){
	echo json_encode(array("status"=>false,"message"=>"Your request is unauthorized."));
}
else{
	$user_id = getUserIdByToken($conn,$token);
	if($user_id==null){
		echo json_encode(array("status"=>false,"message"=>"Invalid token"));
	}
	else{
		if(isAdmin($conn,$user_id)){//check if the user is admin or not
				if(!empty($_FILES)) {
					//userFile
					if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
						$sourcePath = $_FILES['userImage']['tmp_name'];
						$new_path = "uploaded_image/".$article_id."/";		
						if(!is_dir($new_path) || !file_exists($new_path)){
							mkdir($new_path , 0777);
						}
						$targetPath = $new_path.$_FILES['userImage']['name'];
						if(move_uploaded_file($sourcePath,$targetPath)) {//saving file
							if($conn){
								$time=time()*1000;
								$query = "Update Article set Images='$targetPath', UpdateAt=$time where Id='$article_id'";
								if($conn->query($query)){//updating db
									echo json_encode(array("status"=>true,"message"=>"Successfully uploaded..","image_path"=>$targetPath));
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
					else if(is_uploaded_file($_FILES['userFile']['tmp_name'])) {
						$sourcePath = $_FILES['userFile']['tmp_name'];
						$new_path = "uploaded_file/".$article_id."/";
						/*creates directory for the first time*/		
						if(!is_dir($new_path) || !file_exists($new_path)) {  
							mkdir($new_path , 0777);
						}
						$targetPath = $new_path.$_FILES['userFile']['name'];
						if(move_uploaded_file($sourcePath,$targetPath)) {
							if($conn){
								if(isFileAlreadyExist($conn,$article_id,$targetPath)){
									echo json_encode(array("status"=>false,"message"=>"File already exists."));
								}
								else{
									$time=time()*1000;
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
							}
							else{
								echo json_encode(array("status"=>false,"message"=>"Something went wrong.. Try again later."));
							}
						}
						else{
							echo json_encode(array("status"=>false,"message"=>"Failed to upload your file. Try again later."));
						}
					}
					//for news image
					else if(is_uploaded_file($_FILES['news_image']['tmp_name'])) {
						$sourcePath = $_FILES['news_image']['tmp_name'];
						$new_path = "uploaded_image/".$article_id."/";
								
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
				else{
					echo json_encode(array("status"=>false,"message"=>"No file is received...."));
				}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"You are not authorised for this action."));
		}
	}
}

function isFileAlreadyExist($conn,$article_id,$file_name){
	$query = "select count(*) as count from ArticleFiles where article_id='$article_id' and file_name='$file_name'";
	$res = $conn->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	if((int)$row['count']>0){
		return true;
	}
	else{
		return false;
	}
}
?>
