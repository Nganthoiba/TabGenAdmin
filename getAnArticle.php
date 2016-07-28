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
			$row=$res->fetch(PDO::FETCH_ASSOC);
			$row['CreateAt']=(double)$row['CreateAt'];
			$row['DeleteAt']=(double)$row['DeleteAt'];
			$row['UpdateAt']=(double)$row['UpdateAt'];
			$row['Name']=str_replace("''","'",$row['Name']);
			$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
			$row['Images']=($row['Images']==null)?"":$row['Images'];
			$row['images_url']=($row['Images']==null)?"http://128.199.111.18/TabGenAdmin/img/noimage.jpg":
			"http://128.199.111.18/TabGenAdmin/".$row['Images'];
			$row['Filenames']=getFiles($conn,$row['Id']);
			$output = $row;
			echo json_decode(array("status"=>true,"response"=>$row));
		}
		else json_encode(array("status"=>false,"message"=>"Sorry, unable to get result."));
		
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
	}
}
?>
