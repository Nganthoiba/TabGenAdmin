<?php 
	/*php file for creating article*/
	
	/*
	 * JSON TYPE::::>
	{
		"items": [{
			"id": 1,
			"title": "foo",
			"category": "landing_page",
			"thumbnail": "url",
			"thumbnail_type": "image / video",
			"thumbnail_url": " ",
			"attatchment_url": "www.google.com",
			"landing_page_image_url": "url",
			"detail_title": "clfkvlkf"
		}, {
			"id": 2,
			"title": "bar",
			"category": "normal_view",
			"thumbnail": "url",
			"thumbnail_type": "image / video",
			"thumbnail_url": "",
			"attatchment_url": "www.google.com",
			"landing_page_image_url": "",
			"detail_title": "cdkcmkd"
		}]
	}
	 * 
	 * 
	 * 
	 * 
	 * */
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
			$query = "select * from Article where TabId='$tab_id' and DeleteAt=0 order by CreateAt desc";
			$res = $conn->query($query);
			while($row=$res->fetch(PDO::FETCH_ASSOC)){
				$row['Name']=str_replace("''","'",$row['Name']);
				$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
				$output[]=$row;
			}
			$result->state=true;
			$result->output=$output;
			echo json_encode($result);
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
	}
?>
