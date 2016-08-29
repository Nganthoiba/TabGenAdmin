<?php
include('tabgen_php_functions.php');
include('connect_db.php');

if(empty($_GET['article_id'])){
	echo json_encode(array("status"=>false,"message"=>"Please send article ID"));
}
else{
	$id = $_GET['article_id'];
	if($conn){
		$output=null;
		$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,Links as external_link_url,Active 
			from Article where Id='$id'";
		$res=$conn->query($query);
		if($res){
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$row['CreateAt']=(double)$row['CreateAt'];
				$row['DeleteAt']=(double)$row['DeleteAt'];
				$row['UpdateAt']=(double)$row['UpdateAt'];
				$row['Name']=str_replace("''","'",$row['Name']);
				$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
				$row['Images']=($row['Images']==null)?"":$row['Images'];
				$row['images_url']=($row['Images']==null)?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":
				"http://".SERVER_IP."/TabGenAdmin/".$row['Images'];
				$row['Filenames']=getAttatchment($conn,$row['Id']);
				/*external link*/
				$row['external_link_url'] = "http://".SERVER_IP."/TabGenAdmin/getAnArticleWebView.php?article_id=".$id;
				$output[] = $row;
			}
			echo json_encode(array("status"=>true,
			"response"=>$output,
			"web_view"=>"http://".SERVER_IP."/TabGenAdmin/getAnArticleWebView.php?article_id=".$id));
		}
		else {
			echo json_encode(array("status"=>false,"message"=>"Sorry, unable to get result."));
		}
		
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
	}
}
function getAttatchment($conn,$article_id){
	$query = "select Id,caption,file_name from ArticleFiles where article_id='$article_id'";
	$res = $conn->query($query);
	$files_output=array();
	while($row = $res->fetch(PDO::FETCH_ASSOC)){
		$row['file_type']=getFileType($row['file_name']);
		$row['attachment_url']="http://".SERVER_IP."/TabGenAdmin/".$row['file_name'];
		$row['file_name']=substr($row['file_name'],strripos($row['file_name'],"/")+1);
		$row['caption']=($row['caption']==null)?"":$row['caption'];
		$files_output[]=$row;
	}
	return $files_output;
}

function getFileType($filename){
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$file_type="";
	switch($ext){
		case "gif": 
		case "jpeg":
		case "png":	
		case "bmp":
		case "jpg":	$file_type="image";
					break;
		case "pdf": $file_type="pdf";
					break;
		case "docx":
		case "doc":	$file_type="word";
					break;
		case "pptx":
		case "ppt":	$file_type="power_point";
					break;	
		case "mkv":
		case "mpeg":
		case "mp4":	$file_type="video";
					break;
		default: 	$file_type="others";	
	}
	return $file_type;
}
?>
