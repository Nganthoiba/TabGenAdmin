<?php 
	/*php file for creating article*/
	include('tabgen_php_functions.php');
	include('connect_db.php');
	$tab_id = $_GET['tab_id'];
	if($conn){
		if(empty($_GET['tab_id'])){
			echo json_encode(array("status"=>false,"message"=>"Sorry, you have not passed the tab ID."));
		}
		else if(!isTabExistById($conn,$tab_id)){
			echo json_encode(array("status"=>false,"message"=>"Sorry, the tab does not exists, you have passed an invalid tab ID."));
		}
		else{
			$output=null;
			$query = "select Id,CreateAt,title,headline,Details,Image from News where tab_id='$tab_id' 
			order by CreateAt desc";
			$res = $conn->query($query);
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$row['CreateAt']=(double)$row['CreateAt'];
				$row['title']=str_replace("''","'",$row['title']);
				$row['headline']=str_replace("''","'",$row['headline']);
				$row['snippet']=substr($row['headline'],0,60)."...";
				$row['Details']=str_replace("''","'",$row['Details']);
				$row['Image']=$row['Image']==null?"":"http://128.199.111.18/TabGenAdmin/".$row['Image'];
				$row['Attachments']=getFiles($conn,$row['Id']);
				$output[]=$row;
			}
			/*
			 “Response”:[
				“items": {
					“Content_type”:”3”,
					”Sub_items”:[
					{“name":”news1”,”id”:”news_id1”,”image_url”:”_____”,”title”:”THIS is new article”,”Headline”:”amazing article”,”click_category_type”:”_____” },
					{“name":”news1”,”id”:”news_id1”,”image_url”:””},
					{“name":”news1”,”id”:”news_id1”,”image_url”:””}
					] 
				}
			] 
			*/
			$result->state=true;
			$result->Response->items->Content_type=0;
			$result->Response->items->Sub_items=$output;
			echo json_encode($result);
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
	}
?>
