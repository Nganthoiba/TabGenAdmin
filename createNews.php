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
	$news_title=str_replace ("'","''", $news_title);
	$news_headline=str_replace ("'","''", $news_headline);
	$news_details=str_replace ("'","''", $news_details);
	$created_at = time()*1000;
	$status = "true";
	$query = "insert into News(Id,CreateAt,title,headline,Details,Active,tab_id) values('$id',$created_at,'$news_title',
	'$news_headline','$news_details','$status','$tab_id')";
	if($conn->query($query)){
		echo json_encode(array("status"=>true,"message"=>"News posted successfully."));
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"An internal problem occurs."));
	}
}


?>
