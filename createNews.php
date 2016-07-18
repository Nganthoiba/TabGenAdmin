<?php
include('connect_db.php');
include('tabgen_php_functions.php');// all the function/ methodes are in this php file
$news_title = $_POST['news_title'];
$news_headline = $_POST['news_headline'];
$news_details = $_POST['news_details'];
$tab_id = $_POST['tab_id'];
if(empty($news_title)){
	echo json_encode(array("status"=>false,"message"=>"Please send title of the news.."));
}
else if(empty($news_headline)){
	echo json_encode(array("status"=>false,"message"=>"Please send headline of news.."));
}
else if(empty($news_details)){
	echo json_encode(array("status"=>false,"message"=>"Please send details of news.."));
}
else if(empty($tab_id)){
	echo json_encode(array("status"=>false,"message"=>"Please send tab Id under which the news is to be posted."));
}
else if(isNewsTitleExists($conn,$news_title)){
	echo json_encode(array("status"=>false,"message"=>"A news with the same title already exists."));
}
else{
	$id = randId(26);//creating unique id
	$created_at = time()*1000;
	$query = "insert into News(Id,CreateAt,title,headline,Details,tab_id) values('$id',$created_at,'$news_title',
	'$news_headline','$news_details','$tab_id')";
	if($conn->query($query)){
		echo json_encode(array("status"=>true,"message"=>"News posted successfully."));
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"An internal problem occurs."));
	}
}
/*
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
*/
function isNewsTitleExists($conn,$title){
	$query = "select count(*) as count from News where title='$title'";
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
