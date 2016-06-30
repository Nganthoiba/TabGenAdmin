<?php 
	/*php file for creating article*/
	include('tabgen_php_functions.php');
	include('connect_db.php');
	$tab_id = $_POST['tab_id'];
	$name = $_POST['Name'];
	$textual_content = $_POST['textual_content'];
	$link = $_POST['link'];
	if($conn){
		if(empty($tab_id)){
			echo json_encode(array("status"=>false,"message"=>"tab id is empty"));
		}
		else if(!isTabExistById($conn,$tab_id)){
			echo json_encode(array("status"=>false,"message"=>"Sorry, the tab does not exists, you have passed an invalid tab ID."));
		}
		else if(empty($name)){
			echo json_encode(array("status"=>false,"message"=>"Sorry, you have not passed the article name which is mandatory."));
		}
		else if(empty($textual_content)){
			echo json_encode(array("status"=>false,"message"=>"Sorry, you have not passed the article content which is mandatory."));
		}
		else{
			if(empty($link)){
				$link=null;
			}
			/*
				Id varchar(26),
				CreateAt bigint(20),
				UpdateAt bigint(20),
				DeleteAt bigint(20),
				Name varchar(64),
				TabId varchar(26),
				Textual_content varchar(4000),
				Images varchar(4000),
				Filenames varchar(4000),
				Links varchar(4000)
			*/
			$textual_content = str_replace ("'","''", $textual_content);
			$name = str_replace ("'","''", $name);
			$id = randId(26);
			$time = time()*1000;
			$query = "insert into Article(Id,CreateAt,UpdateAt,DeleteAt,Name,TabId,Textual_content,Links)
						values('$id',$time,$time,0,'$name','$tab_id','$textual_content','$link')";
			//echo $query;
			if(isArticleExist($conn,$name)==false){
				//echo json_encode(array("status"=>true,"message"=>"Successfully Created."));
				$res = $conn->query($query);
				if($res==true){
					echo json_encode(array("status"=>true,"message"=>"Successfully Created."));
				}
				else{
					echo json_encode(array("status"=>false,"message"=>"Sorry, unable to create article."));
				}
			}
			else{
				echo json_encode(array("status"=>false,"message"=>"Sorry, an article of the same name already exists, 
				please try with other name."));
			}
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
	}
	
	
?>
