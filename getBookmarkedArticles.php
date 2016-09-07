<?php 
	/*this web service is only for  mobile app: getArticles_on_mobile_app.php: php file for listing out article for CME and Reference tabs in 3,2,1 format.*/
	header('Content-Type: application/json');
	include('tabgen_php_functions.php');
	include('connect_db.php');
	
	if($conn){
		$token = $_GET['token'];//getting token
		$user_id = getUserIdByToken($conn,$token);
		
		if($user_id!=null){
			
				$output=null;
				$query = "select Id,CreateAt,DeleteAt,UpdateAt,Name,Textual_content,Images,Links as external_link_url 
				from Article where Id in (select article_id from BookmarkArticle where user_id='$user_id') 
				and DeleteAt=0 and Active='true' 
				and TabId in (select Tab.Id from Tab,TabTemplate where Tab.TabTemplate=TabTemplate.Id 
								and TabTemplate.Name='CME Template')
				order by CreateAt desc";
				
				$item=null;
				$res=$conn->query($query);
				$count=0;
				
				while($row=$res->fetch(PDO::FETCH_ASSOC)){
					$row['CreateAt']=(double)$row['CreateAt'];
					$row['DeleteAt']=(double)$row['DeleteAt'];
					$row['UpdateAt']=(double)$row['UpdateAt'];
					$row['Name']=str_replace("''","'",$row['Name']);
					$row['Textual_content']=str_replace("''","'",$row['Textual_content']);
					$row['short_description']=substr($row['Textual_content'],0,80)."...";
					$row['Images']=($row['Images']==null)?"":$row['Images'];
					$row['images_url']=($row['Images']==null)?"http://".SERVER_IP."/TabGenAdmin/img/noimage.jpg":
					"http://".SERVER_IP."/TabGenAdmin/".$row['Images'];
					$row['detail_url']="http://".SERVER_IP."/TabGenAdmin/getAnArticle.php?article_id=".$row['Id'];
					$row['external_link_url'] =  "http://".SERVER_IP."/TabGenAdmin/getAnArticleWebView.php?article_id=".$row['Id'];
					$row['no_of_likes'] = getNoOfLikesOfArticle($conn,$row['Id']);
					$row['is_bookmarked_by_you']=isArticleAlreadyBookmarked($conn,$row['Id'],$user_id);
					$row['is_liked_by_you']=isArticleAlreadyLiked($conn,$row['Id'],$user_id);	
					$item[]=$row; 
					$count++;		
				}
				
				/*Response in json*/
				
				if($count==0){
					$response=array("status"=>false,"message"=>"No article has been added for this tab","response"=>$item);
					print json_encode($response);
				}
				else{	
					$response=array("status"=>true,"user_id"=>$user_id,"response"=>$item);
					print json_encode($response);
				}
		}
		else{
			echo json_encode(array("status"=>false,"response"=>null,
			"message"=>"Sorry, you have passed invalid or expired token.","token"=>$token));
		}
	}
	else{
		echo json_encode(array("status"=>false,"response"=>null,"message"=>"Sorry, unable to connect database."));
	}
?>
