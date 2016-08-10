<?php 
	/*php file for creating article*/
	include('tabgen_php_functions.php');
	include('connect_db.php');
	$token = get_token_from_header();
	if($token!=null){
		
		$tab_id = $_POST['tab_id'];
		$name = $_POST['Name'];
		$textual_content = $_POST['textual_content'];
		$link = $_POST['link'];
		if($conn){
			$user_id = getUserIdByToken($conn,$token);//this will check the validity of the token and return the user id
			if($user_id==null){
				echo json_encode(array("status"=>false,"message"=>"Invalid token"));
			}
			else
			{
				if(isAdmin($conn,$user_id))
				{
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
						$textual_content = str_replace ("'","''", $textual_content);
						$name = str_replace ("'","''", $name);
						$id = randId(26);
						$time = time()*1000;
						$query = "insert into Article(Id,CreateAt,UpdateAt,DeleteAt,Name,TabId,Textual_content,Links,Active)
									values('$id',$time,$time,0,'$name','$tab_id','$textual_content','$link','true')";
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
					echo json_encode(array("status"=>false,"message"=>"You are not authorised for this action."));
				}
			}
		}
		else{
			echo json_encode(array("status"=>false,"message"=>"Sorry, unable to connect database."));
		}
	}
	else{
		echo json_encode(array("status"=>false,"message"=>"Empty token id."));
	}
	
?>
